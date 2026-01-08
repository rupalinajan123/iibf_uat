<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Duplicate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('master_helper');

        $this->load->model('master_model');
        $this->load->library('email');
        $this->load->model('chk_session');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->chk_session->chk_member_session();
        $this->load->model('billdesk_pg_model');
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

    ##------------------ Dupicate Icard(PRAFULL)---------------##
    public function card()
    {

        $msg = '';
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute', 'LEFT');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber')));

        //get photo path
        $oldfilepath_photo = get_img_name($this->session->userdata('regnumber'), 'p');
        //get sign path
        $oldfilepath_s = get_img_name($this->session->userdata('regnumber'), 's');

        //get idproof path
        $oldfilepath_pr = get_img_name($this->session->userdata('regnumber'), 'pr');

        //get full name
        $namesub = $firstname = $middlename = $lastname = $displayname = '';
        if (isset($user_info[0]['namesub'])) {
            $namesub = $user_info[0]['namesub'];
        }

        if (isset($user_info[0]['firstname'])) {
            $firstname = $user_info[0]['firstname'];
        }

        if (isset($user_info[0]['middlename'])) {
            $middlename = $user_info[0]['middlename'];
        }

        if (isset($user_info[0]['lastname'])) {
            $middlename = $user_info[0]['lastname'];
        }

        if (isset($user_info[0]['displayname'])) {
            $displayname = $user_info[0]['displayname'];
        }

        $username         = $namesub . ' ' . $firstname . ' ' . $middlename . ' ' . $lastname;
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        if ($username == '') {
            $username = $user_info[0]['displayname'];
        }

        if (file_exists($oldfilepath_photo) && file_exists($oldfilepath_s) && isset($userfinalstrname) && $user_info[0]['dateofbirth'] != '' && $user_info[0]['dateofbirth'] != '0000-00-00' && $user_info[0]['associatedinstitute'] != '') {
            //echo '///';exit;
            //$data=array('middle_content'=>'duplicate_Icard','user_info'=>$user_info);
            //$this->load->view('common_view',$data);

            //redirect(base_url().'home/dashboard/');
            $cnt = 0;
            delete_cookie('did');
            $flag      = 1;
            $valcookie = duplicateid_get_cookie();

            if ($valcookie) {
                $did = $valcookie;
                $this->db->where('pay_status !=', '0');
                $this->db->where('pay_status !=', '');
                $checkuser = $this->master_model->getRecords('duplicate_icard', array('did' => $did, 'regnumber' => $this->session->userdata('regnumber')));
                if (count($checkuser) > 0) {
                    delete_cookie('did');
                } else {
                    $checkpayment = $this->master_model->getRecords('payment_transaction', array('ref_id' => $did, 'status' => '2'));
                    if (count($checkpayment) > 0) {
                        ///$datearr=explode(' ',$checkpayment[0]['date']);
                        $endTime      = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($checkpayment[0]['date'])));
                        $current_time = date("Y-m-d H:i:s");
                        if (strtotime($current_time) <= strtotime($endTime)) {
                            $flag = 0;
                        } else {
                            delete_cookie('did');
                        }
                    } else {
                        $flag = 1;
                        delete_cookie('did');
                    }
                }
            }

            $user_images = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), 'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');
            /*if(!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto'])
            ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['scannedphoto']==''
            ||$user_images[0]['mobile']=='' ||$user_images[0]['email']=='')
            {*/
            //if(!is_file(get_img_name($this->session->userdata('regnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p'))  || validate_userdata($this->session->userdata('regnumber')))
            if (!is_file(get_img_name($this->session->userdata('regnumber'), 's')) || !is_file(get_img_name($this->session->userdata('regnumber'), 'p')) || validate_userdata($this->session->userdata('regnumber'))) {
                redirect(base_url() . 'Duplicate/notification');
            }

            $desc = '';
            if (isset($_POST['btnDupicate'])) {
                $this->form_validation->set_rules('optreason', 'Reason', 'trim|required');
                $this->form_validation->set_rules('optcheck', 'Reason', 'trim|required');
                if ($this->form_validation->run() == true) {
                    if ($this->input->post('optreason') == 'mis') {
                        $desc = 'My Original I-card is lost/misplaced';
                    } elseif ($this->input->post('optreason') == 'dam') {
                        $desc = 'My Original I-card is torn/damaged';
                    } elseif ($this->input->post('optreason') == 'cha') {
                        $desc = 'After Marriage my name has been changed';
                    }

                    $check_attempt = $this->master_model->getRecords('duplicate_icard', array('regnumber' => $this->session->userdata('regnumber'), 'pay_status ' => '1'), 'icard_cnt'
                        , array('did' => 'DESC'), '', '1');

                    if (count($check_attempt) > 0) {
                        /*if($check_attempt[0]['icard_cnt']==2)
                        {
                        $flag=0;
                        }
                        else
                        {
                        $cnt=$check_attempt[0]['icard_cnt']+1;
                        $flag=1;
                        }*/
                        $cnt  = $check_attempt[0]['icard_cnt'] + 1;
                        $flag = 1;
                    } else {
                        $flag = 1;
                        $cnt  = 1;}

                    if ($flag == 1) {
                        $insert_array = array('regnumber' => $this->session->userdata('regnumber'),
                            'reason_type'                     => $this->input->post('optreason'),
                            'description'                     => $desc,
                            'added_date'                      => date('Y-m-d H:i:s'),
                            'icard_cnt'                       => $cnt,
                            'pay_status'                      => 0);

                        if ($last_inset_id = $this->master_model->insertRecord('duplicate_icard', $insert_array, true)) {
                            $userdata = array('icardid' => $last_inset_id, 'desc' => $desc);
                            $this->session->set_userdata($userdata);
                            redirect(base_url() . 'Duplicate/make_payment');
                            /*$insert_array_payment=array('member_regnumber'=>$this->session->userdata('regnumber'),
                        'amount'=>'345',
                        'date'=>date('Y-m-d h:i:s'),
                        'transaction_no'=>'HHMP4854000644',
                        'receipt_no'=>'2138112',
                        'pay_type'=>'3',
                        'ref_id'=>$last_inset_id,
                        'description'=>$desc,
                        'transaction_details'=>'Trans Details: PME10013-3D Secure Authentication failure Trans Details: PME10013-3D Secure Authentication failure Trans Details: PME10013-3D Secu',
                        'status'=>'1');

                        if(    $this->master_model->insertRecord('payment_transaction',$insert_array_payment))
                        {
                        $this->session->set_flashdata('success','Request for duplicate I-card has been placed successfully !!');
                        redirect(base_url('Duplicate/card/'));
                        }*/
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Unable to proceed for duplicate I-card request');
                        redirect(base_url('Duplicate/card/'));
                    }
                } else {
                    $data['validation_errors'] = validation_errors();
                }

            }

            $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute', 'LEFT');
            $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber')));

            if ($flag == 0) {
                $data = array('middle_content' => 'duplicate_cookie_msg');
                $this->load->view('common_view', $data);
            } else {

                $data = array('middle_content' => 'duplicate_Icard', 'user_info' => $user_info);
                $this->load->view('common_view', $data);
            }

        } else {
            $msg .= '<li>
Please check all mandatory fields in profile <a href="' . base_url() . 'Home/profile/">click here</a> to update the, profile. For any queries contact zonal office.</li>';

            $data = array('middle_content' => 'member_notification', 'msg' => $msg);
            $this->load->view('common_view', $data);

        }
    }

    public function IDsuccess()
    {
        $this->db->select('member_registration.*,institution_master.name,duplicate_icard.description');
        $this->db->join('duplicate_icard', 'duplicate_icard.regnumber=member_registration.regnumber');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('duplicate_icard.pay_status', '1');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'member_registration.regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), '', array('duplicate_icard.did' => 'DESC'));

        //get photo path
        $oldfilepath_photo = get_img_name($this->session->userdata('regnumber'), 'p');
        //get sign path
        $oldfilepath_s = get_img_name($this->session->userdata('regnumber'), 's');

        //get idproof path
        $oldfilepath_pr = get_img_name($this->session->userdata('regnumber'), 'pr');

        //get full name
        $namesub = $firstname = $middlename = $lastname = $displayname = '';
        if (isset($user_info[0]['namesub'])) {
            $namesub = $user_info[0]['namesub'];
        }

        if (isset($user_info[0]['firstname'])) {
            $firstname = $user_info[0]['firstname'];
        }

        if (isset($user_info[0]['middlename'])) {
            $middlename = $user_info[0]['middlename'];
        }

        if (isset($user_info[0]['lastname'])) {
            $middlename = $user_info[0]['lastname'];
        }

        if (isset($user_info[0]['displayname'])) {
            $displayname = $user_info[0]['displayname'];
        }

        $username         = $namesub . ' ' . $firstname . ' ' . $middlename . ' ' . $lastname;
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        if ($username == '') {
            $username = $user_info[0]['displayname'];
        }

        // print_r($user_info[0]['associatedinstitute']);
        // echo '***';
        // exit;

        if (file_exists($oldfilepath_photo) && file_exists($oldfilepath_s) && isset($userfinalstrname) && $user_info[0]['dateofbirth'] != '' && $user_info[0]['dateofbirth'] != '0000-00-00' && $user_info[0]['associatedinstitute'] != '') {
            $data = array('middle_content' => 'print_duplicate_thankyou', 'user_info' => $user_info);
        } else {
            $msg .= '<li>
Please check all mandatory fields in profile <a href="' . base_url() . 'Home/profile/">click here</a> to update the, profile. For any queries contact zonal office.</li>';

            $data = array('middle_content' => 'member_notification', 'msg' => $msg);
        }
        //exit;
        if (count($user_info) <= 0) {
            redirect(base_url() . 'Home/dashboard/');
        }

        $this->load->view('common_view', $data);
    }

    public function make_payment()
    {
        $cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
        $cgst_amt  = $sgst_amt  = $igst_amt  = '';
        $cs_total  = $igst_total  = '';
        $getstate  = $getcenter  = $getfees  = array();

        $valcookie = applyexam_get_cookie();
        /*if($valcookie)
        {
        redirect(base_url().'home/dashboard/');
        }*/
        //echo $_SERVER['HTTP_REFERER'];exit;
        if (isset($_POST['processPayment']) && $_POST['processPayment']) {
            //billdesk - 2022-03-23
            $pg_name = $this->input->post('pg_name');

            $regno = $this->session->userdata('regnumber');
            /*include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $merchIdVal = $this->config->item('sbi_merchIdVal');
            $AggregatorId = $this->config->item('sbi_AggregatorId');

            $pg_success_url = base_url()."Duplicate/sbitranssuccess";
            $pg_fail_url    = base_url()."Duplicate/sbitransfail"; */

            //    $amount = $this->config->item('dup_id_card_fee');
            //$MerchantOrderNo    = generate_order_id("idcard_sbi_order_id");

            //Duplicate ID card
            //Ref1 = orderid
            //Ref2 = iibfexam
            //Ref3 = IIBFDUP
            //Ref4 = member reg no
            /*changes by pooj@*/
            //intra , intre and J&k set Dup Id card  Invoice tax
            $this->db->join('state_master', 'member_registration.state = state_master.state_code', 'LEFT');
            $member_deatails = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), 'state_master.state_name,member_registration.state,state_master.state_code,state_master.exempt');
            $state_details   = $this->master_model->getRecords('state_master', array('state_code' => $member_deatails[0]['state']), 'state_no,exempt');

            if ($member_deatails[0]['state'] == 'MAH') {
                //set a rate (e.g 9%,9% or 18%)
                $cgst_rate = $this->config->item('Dup_Id_cgst_rate');
                $sgst_rate = $this->config->item('Dup_Id_sgst_rate');
                //set an amount as per rate
                $cgst_amt = $this->config->item('Dup_Id_cgst_amt');
                $sgst_amt = $this->config->item('Dup_Id_sgst_amt');
                $amount   = $this->config->item('Dup_Id_cs_total');
                //set an total amount
                $cs_total = $this->config->item('Dup_Id_cs_total');

                $tax_type = 'Intra';

            } else {
                $igst_rate  = $this->config->item('Dup_Id_igst_rate');
                $igst_amt   = $this->config->item('Dup_Id_igst_amt');
                $igst_total = $this->config->item('Dup_Id_igst_tot');
                $amount     = $this->config->item('Dup_Id_igst_tot');
                $tax_type   = 'Inter';
            }

            /*if($member_deatails[0]['state']=='JAM' )
            {
            $cgst_rate=$sgst_rate=$igst_rate='';
            $cgst_amt=$sgst_amt=$igst_amt='';
            $igst_total=$this->config->item('Dup_Id_apply_fee');
            $amount =$this->config->item('Dup_Id_apply_fee');
            }*/

            // Create transaction
            $insert_data = array(
                'member_regnumber' => $regno,
                'amount'           => $amount,
                'gateway'          => "sbiepay",
                'date'             => date('Y-m-d H:i:s'),
                'pay_type'         => '3',
                'ref_id'           => $this->session->userdata('icardid'),
                'description'      => "Duplicate ID card request. Reason:" . $this->session->userdata('desc'),
                'status'           => '2',
                //'receipt_no'       => $MerchantOrderNo,
                'pg_flag'          => 'iibfdup',
                //'pg_other_details'=>$custom_field
            );

            $pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
            $regnumber       = $this->session->userdata('regnumber');
            $MerchantOrderNo = idcard_sbi_order_id($pt_id);

            $custom_field          = $MerchantOrderNo . "^iibfexam^" . "^iibfdup^" . "^" . $regnumber;
            $custom_field_billdesk = $MerchantOrderNo . "-iibfexam" . "-iibfdup" . "-" . $regnumber;

            //$this->db->join('tbl_expense_type', TABLE_NAME.'.expense_type_fk = tbl_expense_type.expense_type_pk', 'LEFT');

            // update receipt no. in payment transaction -
            $update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
            $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));

            /*Dup_Id Invoice */
            $invoice_insert_array = array('pay_txn_id' => $pt_id,
                'receipt_no'                               => $MerchantOrderNo,
                'exam_code'                                => '',
                'center_code'                              => '',
                'center_name'                              => '',
                'state_of_center'                          => $member_deatails[0]['state'],
                'member_no'                                => $this->session->userdata('regnumber'),
                'app_type'                                 => 'D',
                'exam_period'                              => '',
                'service_code'                             => $this->config->item('Dup_Id_service_code'),
                'qty'                                      => '1',
                'state_code'                               => $state_details[0]['state_no'],
                'state_name'                               => $member_deatails[0]['state_name'],
                'tax_type'                                 => $tax_type,
                'fee_amt'                                  => $this->config->item('Dup_Id_apply_fee'),
                'cgst_rate'                                => $cgst_rate,
                'cgst_amt'                                 => $cgst_amt,
                'sgst_rate'                                => $sgst_rate,
                'sgst_amt'                                 => $sgst_amt,
                'igst_rate'                                => $igst_rate,
                'igst_amt'                                 => $igst_amt,
                'cs_total'                                 => $cs_total,
                'igst_total'                               => $igst_total,
                'exempt'                                   => $state_details[0]['exempt'],
                'created_on'                               => date('Y-m-d H:i:s'));

            $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);
            /* Close  Dup_id  Invoice */

            //set cookie for duplicate  I-card
            duplicateid_set_cookie($this->session->userdata('icardid'));

            $MerchantCustomerID = $regno;

            //$custom_field = "^iibfdup^^";

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
/*
$EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";

$aes = new CryptAES();
$aes->set_key(base64_decode($key));
$aes->require_pkcs5();

$EncryptTrans = $aes->encrypt($EncryptTrans);

$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value

$this->load->view('pg_sbi_form',$data); */

            // billdesk flag - 2022-03-23
            if ($pg_name == 'sbi') {
                $regno = $this->session->userdata('regnumber');
                include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                $key          = $this->config->item('sbi_m_key');
                $merchIdVal   = $this->config->item('sbi_merchIdVal');
                $AggregatorId = $this->config->item('sbi_AggregatorId');

                $pg_success_url = base_url() . "Duplicate/sbitranssuccess";
                $pg_fail_url    = base_url() . "Duplicate/sbitransfail";
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
            } elseif ($pg_name == 'billdesk') {
                $update_payment_data = array('gateway' => 'billdesk');
                $this->master_model->updateRecord('payment_transaction', $update_payment_data, array('id' => $pt_id));

                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'Duplicate/handle_billdesk_response', '', '', '', $custom_field_billdesk);

                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid']      = $billdesk_res['bdorderid'];
                    $data['token']          = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                    $data['returnUrl']      = $billdesk_res['returnUrl'];
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                } else {
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'Duplicate/card');
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

            $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id', '', '', '1');
            if (empty($get_user_regnum_info)) {
                redirect(base_url() . 'DupCert');
            }
            $new_invoice_id   = $get_user_regnum_info[0]['ref_id'];
            $member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
            //Query to get Payment details
            $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $member_regnumber), 'transaction_no,date,amount,id');

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

            /* Update Exam Invoice */
            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
            if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2) {

                $exam_invoice_data = array('pay_txn_id' => $payment_info[0]['id'], 'receipt_no' => $MerchantOrderNo, 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $transaction_no);
                $this->master_model->updateRecord('exam_invoice', $exam_invoice_data, array('invoice_id' => $new_invoice_id, 'member_no' => $member_regnumber));

                /* Update Pay Status */
                $update_data22 = array('pay_status' => '1');
                $this->master_model->updateRecord('duplicate_icard', $update_data22, array('did' => $new_invoice_id));
                //echo $this->db->last_query(); die;

                //Manage Log
                /* $pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);     */

                $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'duplicate_id'));

                if (count($emailerstr) > 0 && (count($get_user_regnum_info) > 0)) {
                    //Query to get user details
                    $user_info        = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum_info[0]['member_regnumber']), 'namesub,firstname,middlename,lastname,email,usrpassword,mobile');
                    $username         = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                    $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                    $newstring1       = str_replace("#DATE#", "" . date('Y-m-d h:s:i') . "", $emailerstr[0]['emailer_text']);
                    $newstring2       = str_replace("#MEM_NO#", "" . $get_user_regnum_info[0]['member_regnumber'] . "", $newstring1);
                    $final_str        = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring2);
                    $info_arr         = array('to' => 'pratibha.purkar@esds.co.in', 'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'], 'message' => $final_str);
                    //$user_info[0]['email']

                    /*changes by pooja */
                    //genertate invoice and email send with invoice attach 8-7-2017
                    //get invoice
                    $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id']));

                    //echo $this->db->last_query();exit;
                    if (count($getinvoice_number) > 0) {
                        /*    if($getinvoice_number[0]['state_of_center']=='JAM')
                        {
                        $invoiceNumber = generate_duplicate_id_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                        if($invoiceNumber)
                        {
                        $invoiceNumber=$this->config->item('Dup_Id_invoice_no_prefix_jammu').$invoiceNumber;
                        }
                        }
                        else
                        {*/
                        $invoiceNumber = generate_duplicate_id_invoice_number($getinvoice_number[0]['invoice_id']);
                        if ($invoiceNumber) {
                            $invoiceNumber = $this->config->item('Dup_Id_invoice_no_prefix') . $invoiceNumber;
                        }
                        //}
                        $update_data33 = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                        $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                        $this->master_model->updateRecord('exam_invoice', $update_data33, array('receipt_no' => $MerchantOrderNo));
                        $attachpath = genarate_duplicateicard_invoice($getinvoice_number[0]['invoice_id']);
                    }

                    if ($attachpath != '') {
                        //if($this->Emailsending->mailsend($info_arr))
                        if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                            $this->session->set_flashdata('success', 'Request for duplicate I-card has been placed successfully !!');
                            $pay_status = array();
                            $regnumber  = $this->session->userdata('regnumber');
                            $where1     = array('member_number' => $regnumber);
                            //$orderby1 = array("did"=>"Desc");
                            $pay_status = $this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), $where1);

                            /* User Log Activities : Pooja */
                            $uerlog      = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), 'regid');
                            $user_info   = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));
                            $log_title   = "Apply for Duplicate Id card : " . $uerlog[0]['regid'];
                            $log_message = serialize($user_info);
                            $rId         = $uerlog[0]['regid'];
                            $regNo       = $this->session->userdata('regnumber');
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            /* Close User Log Actitives */

                            redirect(base_url('Duplicate/IDsuccess/'));
                        } else {
                            redirect(base_url('Duplicate/IDsuccess/'));
                        }
                    } else {
                        redirect(base_url('Duplicate/IDsuccess/'));
                    }

                }
            } 
            elseif ($auth_status=='0002') {
            	
            	$update_data44 = array('transaction_no' => $transaction_no, 'status' => 2, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0002', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                $this->master_model->updateRecord('payment_transaction', $update_data44, array('receipt_no' => $MerchantOrderNo));

                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                $this->session->set_flashdata('flsh_msg', 'Transaction in process...!');
                redirect(base_url() . 'Duplicate/card');
            }
            else //if ($transaction_error_type == 'payment_authorization_error')
            {
                $update_data44 = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                $this->master_model->updateRecord('payment_transaction', $update_data44, array('receipt_no' => $MerchantOrderNo));

                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                redirect(base_url() . 'Duplicate/card');
            }
        } else {
            die("Please try again...");
        }
    }

    public function sbitranssuccess()
    {
        delete_cookie('did');
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('sbi_m_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $encData         = $aes->decrypt($_REQUEST['encData']);
        $responsedata    = explode("|", $encData);
        $MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
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

        // Handle transaction sucess case
        $q_details = sbiqueryapi($MerchantOrderNo);
        if ($q_details) {
            if ($q_details[2] == "SUCCESS") {

                $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,id');
                if ($get_user_regnum[0]['status'] == 2) {

                    if (count($get_user_regnum) > 0) {
                        $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'regnumber,usrpassword,email');
                    }

                    $update_data = array('pay_status' => '1');
                    $this->master_model->updateRecord('duplicate_icard', $update_data, array('did' => $get_user_regnum[0]['ref_id']));

                    $update_data = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                    $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                    //Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);

                    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'duplicate_id'));

                    if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
                        //Query to get user details
                        $user_info        = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'namesub,firstname,middlename,lastname,email,usrpassword,mobile');
                        $username         = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                        $newstring1       = str_replace("#DATE#", "" . date('Y-m-d h:s:i') . "", $emailerstr[0]['emailer_text']);
                        $newstring2       = str_replace("#MEM_NO#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
                        $final_str        = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring2);
                        $info_arr         = array('to' => $user_info[0]['email'], 'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'], 'message' => $final_str);

                        /*changes by pooja */
                        //genertate invoice and email send with invoice attach 8-7-2017
                        //get invoice
                        $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum[0]['id']));

                        //echo $this->db->last_query();exit;
                        if (count($getinvoice_number) > 0) {
                            /*    if($getinvoice_number[0]['state_of_center']=='JAM')
                            {
                            $invoiceNumber = generate_duplicate_id_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                            if($invoiceNumber)
                            {
                            $invoiceNumber=$this->config->item('Dup_Id_invoice_no_prefix_jammu').$invoiceNumber;
                            }
                            }
                            else
                            {*/
                            $invoiceNumber = generate_duplicate_id_invoice_number($getinvoice_number[0]['invoice_id']);
                            if ($invoiceNumber) {
                                $invoiceNumber = $this->config->item('Dup_Id_invoice_no_prefix') . $invoiceNumber;
                            }
                            //}
                            $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                            $this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
                            $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                            $attachpath = genarate_duplicateicard_invoice($getinvoice_number[0]['invoice_id']);
                        }

                        if ($attachpath != '') {
                            //if($this->Emailsending->mailsend($info_arr))
                            if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                $this->session->set_flashdata('success', 'Request for duplicate I-card has been placed successfully !!');
                                $pay_status = array();
                                $regnumber  = $this->session->userdata('regnumber');
                                $where1     = array('member_number' => $regnumber);
                                //$orderby1 = array("did"=>"Desc");
                                $pay_status = $this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), $where1);

                                /* User Log Activities : Pooja */
                                $uerlog      = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), 'regid');
                                $user_info   = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));
                                $log_title   = "Apply for Duplicate Id card : " . $uerlog[0]['regid'];
                                $log_message = serialize($user_info);
                                $rId         = $uerlog[0]['regid'];
                                $regNo       = $this->session->userdata('regnumber');
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                                /* Close User Log Actitives */

                                redirect(base_url('Duplicate/IDsuccess/'));
                            } else {
                                redirect(base_url('Duplicate/IDsuccess/'));
                            }
                        } else {
                            redirect(base_url('Duplicate/IDsuccess/'));
                        }

                    }
                }
            }
        }

        //    }
        /*else
    {
    die("Please try again...");
    }*/
    }

    public function sbitransfail()
    {
        delete_cookie('did');
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
            $this->session->set_flashdata('error', 'Transaction has been fail, please try again!!');
            redirect(base_url('Duplicate/card/'));
        } else {
            die("Please try again...");
        }
    }

    ##---------Forcefully Update profile mesage to user(prafull)-----------##
    public function notification()
    {
        $msg  = '';
        $flag = 1;
        // $user_images=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'),'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');

        $user_images = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), 'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');

        if ((!file_exists('./uploads/photograph/' . $user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/' . $user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/' . $user_images[0]['idproofphoto']) || $user_images[0]['scannedphoto'] == '' || $user_images[0]['scannedsignaturephoto'] == '' || $user_images[0]['idproofphoto'] == '') && (!is_file(get_img_name($this->session->userdata('regnumber'), 'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'), 's')) || !is_file(get_img_name($this->session->userdata('regnumber'), 'p')))) {
            $flag = 0;
            $msg .= '<li>Your Photo/signature or ID proof are not available kindly go to Edit Profile and <a href="' . base_url() . 'Home/profile/">click here</a> to upload the Photo/Signature, then submit the application for Duplicate I-card. For any queries contact zonal office.</li>';
        }
        if ($user_images[0]['mobile'] == '' || $user_images[0]['email'] == '') {
            $flag = 0;
            $msg .= '<li>Your email id or mobile number are not available kindly go to Edit Profile and <a href="' . base_url() . 'Home/profile/">click here</a> to update the, email id or mobile number and submit the application for Duplicate I-card. For any queries contact zonal office.</li>';
        }

        if (validate_userdata($this->session->userdata('regnumber'))) {
            $flag = 0;
            $msg .= '<li>
Please check all mandatory fields in profile <a href="' . base_url() . 'Home/profile/">click here</a> to update the, profile. For any queries contact zonal office.</li>';
        }

        /*if((!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']) ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']) && ($user_images[0]['mobile']=='' ||$user_images[0]['email']==''))
        {
        $flag=0;
        $msg='<li>Your Photo/signature are not available kindly go to Edit Profile and <a href="'.base_url().'Home/profile/">click here</a> to upload the Photo/Signature, then submit the application for Duplicate I-card. For any queries contact zonal office.</li>

        <li>Your email id or mobile number are not available kindly go to Edit Profile and <a href="'.base_url().'Home/profile/">click here</a> to update the, then email id or mobile number and submit the application for Duplicate I-card. For any queries contact zonal office.</li>';
        }*/

        if ($flag) {
            redirect(base_url() . 'Home/dashboard');
        }

        $data = array('middle_content' => 'member_notification', 'msg' => $msg);
        $this->load->view('common_view', $data);
    }

    ##---------------------- Public function duplicate icard pdf ##########
    public function cardpdf()
    {
        $this->db->select('member_registration.*,institution_master.name,duplicate_icard.description');
        $this->db->join('duplicate_icard', 'duplicate_icard.regnumber=member_registration.regnumber');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('duplicate_icard.pay_status', '1');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'member_registration.regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), '', array('duplicate_icard.did' => 'DESC'));

        /*change fee as per state */
        $this->db->join('state_master', 'member_registration.state = state_master.state_code', 'LEFT');
        $member_deatails = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), 'state_master.state_name,member_registration.state');
        /*if($member_deatails[0]['state']=='JAM')
        {
        $fee=$this->config->item('Dup_Id_apply_fee');
        }else
        {*/
        $fee = $this->config->item('Dup_Id_cs_total');
        /*}*/

        $username         = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

        $html = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">
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
								<tr><td style="text-align:center"><strong><h3>Duplicate ID Card Request</h3></strong></td></tr>
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
						<td class="tablecontent2">Name :</td>
						<td colspan="2" class="tablecontent2" nowrap="nowrap">' . $userfinalstrname . '
						</td>
					</tr>

					<tr>
						<td class="tablecontent2">Bank/Institution Name :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap">' . wordwrap($user_info[0]['name'], 20, "<br />\n") . ' </td>
					</tr>

					<tr>
						<td class="tablecontent2">Date of Birth :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap">' . date('d-m-Y', strtotime($user_info[0]['dateofbirth'])) . '</td>
					</tr>

					<tr>
						<td class="tablecontent2">Mobile :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['mobile'] . ' </td>
					</tr>

				<tr>
						<td class="tablecontent2" width="51%">Email:</td>
						<td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap">' . $user_info[0]['email'] . '</td>
					</tr>

					 <tr>
						<td class="tablecontent2">Reason For Duplicate I-card :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['description'] . '</td>
					</tr>

				  <tr>
						<td class="tablecontent2">Fee :</td>
						<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $fee . '</td>
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

    ##---------------------- Public function print icard##########
    public function print_duplicate_icard()
    {
        $this->db->select('member_registration.*,institution_master.name,duplicate_icard.description');
        $this->db->join('duplicate_icard', 'duplicate_icard.regnumber=member_registration.regnumber');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('duplicate_icard.pay_status', '1');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'member_registration.regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), '', array('duplicate_icard.did' => 'DESC'));
        $data      = array('middle_content' => 'print_duplicate_icard', 'user_info' => $user_info);
        $this->load->view('common_view', $data);
    }

    ############get download count #############
    public function getCount()
    {
        $hisaarr         = array('member_number' => $this->session->userdata('regnumber'));
        $cnthistory      = $this->master_model->getRecords('member_idcard_cnt', $hisaarr);
        $dowanload_count = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')), 'card_cnt');
        echo $dowanload_count[0]['card_cnt'];
    }

}
