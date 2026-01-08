<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class ExamRecoveryJaiib extends CI_Controller
{
    public function __construct()
    {
        
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->helper('exam_recovery_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->load->model('billdesk_pg_model');
        $this->load->model('jaib_recovery_model');

        $cookie_name  = "instruction";
        $cookie_value = "1";
        setcookie($cookie_name, $cookie_value, time() + (60 * 10), "/");
        // if( $this->get_client_ip() != '115.124.115.69'){exit();}
        
    }
/* Form View */

    public function index()
    {
       
        $selectedMemberId = '';
        if (isset($_POST['btnGetDetails'])) {
            $selectedMemberId = $_POST['regnumber'];
            if ($selectedMemberId != '') {
                $MemberArray = $this->validateMember($selectedMemberId);
            } else {
                $this->session->set_flashdata('flsh_msg', 'The Membership No. field is required.');
                redirect(base_url() . 'ExamRecoveryJaiib');
            }
            if (!empty($MemberArray)) {
                $data = array('middle_content' => 'ExamRecoveryJaiib/examrecovery', 'MemberArray' => $MemberArray);
                $this->load->view('ExamRecoveryJaiib/examrecovery_common_view', $data);
            }
        } else {
            $data = array('middle_content' => 'ExamRecoveryJaiib/examrecovery');
            $this->load->view('ExamRecoveryJaiib/examrecovery_common_view', $data);
        }
    }
/* Validate Member Function */
    public function validateMember($selectedMemberId)
    {
        $validateQry = $this->db->query("SELECT member_no FROM jaiib_exam_recovery_master WHERE member_no = '" . $selectedMemberId . "'  LIMIT 1 ");
        if ($validateQry->num_rows() > 0) {
            $validateMemberNo = $validateQry->row_array();
            $this->db->join('admit_exam_master', 'jaiib_exam_recovery_master.exam_code = admit_exam_master.exam_code', 'left');
            $this->db->join('member_registration', 'jaiib_exam_recovery_master.member_no = member_registration.regnumber', 'left');
            $this->db->where('member_no', $selectedMemberId);
            $MemberArray = $this->master_model->getRecords('jaiib_exam_recovery_master', '', 'jaiib_exam_recovery_master.*,admit_exam_master.description,regnumber,firstname,middlename,lastname,email,mobile');

            if (empty($MemberArray)) {
                $this->session->set_flashdata('flsh_msg', 'Please Enter Valid Membership No.');
                redirect(base_url() . 'ExamRecoveryJaiib');
            }
            return $MemberArray;
        } else {
            $this->session->set_flashdata('flsh_msg', 'You are not eligible member for Exam Recovery..!');
            redirect(base_url() . 'ExamRecoveryJaiib');
        }
    }
/* Stored detials after click on Pay Now Button */
    public function stored_details()
    {
        $regnumber           = $invoice_id           = $tax_type           = '';
        $cgst_rate           = $sgst_rate           = $cgst_amt           = $sgst_amt           = $cs_total           = $igst_rate           = $igst_amt           = $igst_total           = 0;
        $selected_invoice_id = base64_decode($this->uri->segment(3));
        if ($selected_invoice_id == "") {
            redirect(base_url() . 'ExamRecoveryJaiib');
        } else {
            $this->db->where('invoice_id', $selected_invoice_id);
            $examArr = $this->master_model->getRecords('jaiib_exam_recovery_master', '', '', '', '', '1');
            if (!empty($examArr)) {
                $regnumber = $examArr[0]['member_no'];
                if ($examArr[0]['state_of_center'] == 'MAH') {
                    $cgst_rate = $examArr[0]['cgst_rate'];
                    $sgst_rate = $examArr[0]['sgst_rate'];
                    $cgst_amt  = $examArr[0]['cgst_amt'];
                    $sgst_amt  = $examArr[0]['sgst_amt'];
                    $cs_total  = $examArr[0]['cs_total'];
                    $tax_type  = 'Intra';
                } else {
                    $igst_rate  = $examArr[0]['igst_rate'];
                    $igst_amt   = $examArr[0]['igst_amt'];
                    $igst_total = $examArr[0]['igst_total'];
                    $tax_type   = 'Inter';
                }

/* Create User Session */
                $new_invoice_id = $examArr[0]['invoice_id'];
                $pt_id          = $examArr[0]['id'];
                $userarr        = array('selected_invoice_id' => $selected_invoice_id, 'regnumber' => $regnumber, 'new_invoice_id' => $new_invoice_id, 'pt_id' => $pt_id);
                $this->session->set_userdata('memberdata', $userarr);
/* Go to Make Payment */
                redirect(base_url() . "ExamRecoveryJaiib/make_payment");

            } else {
                redirect(base_url() . 'ExamRecoveryJaiib');
            }
        }
    }
/* Make Payment */
    public function make_payment()
    {

/* Variables */
        $igst_total          = $igst_amt          = $pay_type          = $exam_code          = $selected_invoice_id          = $regnumber          = $new_invoice_id          = $custom_field          = '';
        $flag                = 1;
        $selected_invoice_id = $this->session->userdata['memberdata']['selected_invoice_id']; // Seleted Invoice Id
        $regnumber           = $this->session->userdata['memberdata']['regnumber']; // Member No
        $new_invoice_id      = $this->session->userdata['memberdata']['new_invoice_id']; // New Invoice Id
        // $pt_id      = $this->session->userdata['memberdata']['pt_id']; // id of jaiib exam master
        if ($selected_invoice_id == "" || $regnumber == "" || $new_invoice_id == "") {
/* User Log Activities  */
            $log_title   = "Exam Recovery-Session Expired";
            $log_message = 'Program Code : ' . $this->session->userdata['memberdata']['selected_invoice_id'];
            $rId         = $this->session->userdata['memberdata']['new_invoice_id'];
            $regNo       = $this->session->userdata['memberdata']['regnumber'];
            storedUserActivity($log_title, $log_message, $rId, $regNo);
            $this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
            redirect(base_url() . 'ExamRecoveryJaiib');
        }
        if (isset($_POST['processPayment']) && $_POST['processPayment']) {
            $pg_name = 'billdesk';
            $this->db->where('member_no', $regnumber);
            $this->db->where('invoice_id', $new_invoice_id);
            $MemberArray = $this->master_model->getRecords('exam_invoice', '', '', '', '', '1');

            if (!empty($MemberArray)) {
                $cs_total   = $MemberArray[0]['cs_total'];
                $igst_total = $MemberArray[0]['igst_total'];
                if ($cs_total != "0.00") {
                    $amount = $cs_total;
                } else {
                    $amount = $igst_total;
                }
                $exam_code = $MemberArray[0]['exam_code'];
            } else {
                redirect(base_url() . 'ExamRecoveryJaiib');
            }
            $pg_flag = 'iibfrec';

            /* $this->db->where('member_regnumber', $regnumber);
            $this->db->where('status','3');
            $pt_id = $this->master_model->getRecords('payment_transaction',array('exam_code'=>21,'pay_type'=>2),'id');
            $pt_id =  $pt_id[0]['id'];*/
            $pt_id = $MemberArray[0]['pay_txn_id'];

            $MerchantOrderNo = sbi_exam_order_id($pt_id);

            /* Update new receipt_no */
            $new_receipt_no_update = array('new_receipt_no' => $MerchantOrderNo);

            $this->master_model->updateRecord('jaiib_exam_recovery_master', $new_receipt_no_update, array('invoice_id' => $selected_invoice_id, 'member_no' => $regnumber));

            $custom_field = $MerchantOrderNo . "^iibfexam^" . "^iibfrec^" . "^" . $regnumber;

            $billdesk_additional_info = $MerchantOrderNo . "-iibfexam" . "-iibfrec" . "-" . $regnumber;

            $custom_field = $MerchantOrderNo . "^iibfexam^" . "^iibfrec^" . "^" . $regnumber;

            $billdesk_additional_info = $MerchantOrderNo . "-iibfexam" . "-iibfrec" . "-" . $regnumber;

            if ($pg_name == 'sbi') {
                exit;
             
            } elseif ($pg_name == 'billdesk') {

              
                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'ExamRecoveryJaiib/handle_billdesk_response', '', '', '', $billdesk_additional_info);

                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid']      = $billdesk_res['bdorderid'];
                    $data['token']          = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                    $data['returnUrl']      = $billdesk_res['returnUrl'];
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                } else {
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'ExamRecoveryJaiib');
                }
            }
        } else {
            $data['show_billdesk_option_flag'] = 1;
            $this->load->view('pg_sbi/make_payment_page', $data);
        }
    }

    public function handle_billdesk_response()
    {


        $selected_invoice_id = $attachpath = $invoiceNumber = '';
        $selected_invoice_id = $this->session->userdata['memberdata']['selected_invoice_id']; // Seleted Invoice Id
        $member_regnumber    = $this->session->userdata['memberdata']['regnumber'];
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
            $auth_status            = $responsedata['auth_status'];
            $encData                = $_REQUEST['transaction_response'];
            $get_user_regnum_info   = $this->master_model->getRecords('jaiib_exam_recovery_master', array('member_no' => $member_regnumber));

            if (empty($get_user_regnum_info)) {
                redirect(base_url() . 'ExamRecoveryJaiib');
            }

            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
            // print_r($qry_api_response);
            if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['pay_status'] == 2) {

                /* Update Pay Status */
                $jaiib_exam_recovery_master_data = array('new_receipt_no' => $MerchantOrderNo, 'new_transaction_no' => $transaction_no, 'pay_status' => 1, 'modified_on' => date('Y-m-d H:i:s'));

                $this->master_model->updateRecord('jaiib_exam_recovery_master', $jaiib_exam_recovery_master_data, array('invoice_id' => $selected_invoice_id, 'member_no' => $member_regnumber));

                /* Transaction Log */
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                /* Email */
                $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'jaiib_user_exam_recovery_email'), '', '', '1');
                if (!empty($member_regnumber)) {
                    $user_info = $this->Master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile', '', '', '1');
                }



                $this->jaib_recovery_model->settle($member_regnumber);

                if (count($emailerstr) > 0) {
                    /* Set Email sending options */
                    $info_arr = array(
                        // 'to'      => $user_info[0]['email'],
                        //'to' => 'esdstesting12@gmail.com',
                        'to'      => 'chaitali.jadhav@esds.co.in',
                        //'to' => 'anishrivastava@iibf.org.in',
                        'from'    => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'],
                        'message' => $emailerstr[0]['emailer_text'],
                    );
                    // $this->Emailsending->mailsend_attch($info_arr, $attachpath)

                }
                redirect(base_url() . 'ExamRecoveryJaiib/acknowledge/');
            } elseif ($auth_status == '0002') {
                $get_user_regnum_info = $this->master_model->getRecords('jaiib_exam_recovery_master', array('member_no' => $member_regnumber));
                if ($get_user_regnum_info[0]['pay_status'] == '2') {

                    /* Update Pay Status */
                    $jaiib_exam_recovery_master_data = array('new_receipt_no' => $MerchantOrderNo, 'new_transaction_no' => $transaction_no, 'pay_status' => 2, 'modified_on' => date('Y-m-d H:i:s'));

                    $this->master_model->updateRecord('jaiib_exam_recovery_master', $jaiib_exam_recovery_master_data, array('invoice_id' => $selected_invoice_id, 'member_no' => $member_regnumber));

                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
                }
                $this->session->set_flashdata('flsh_msg', 'Transaction pending...!');
                redirect(base_url() . 'ExamRecoveryJaiib');
            } else {

                $get_user_regnum_info = $this->master_model->getRecords('jaiib_exam_recovery_master', array('member_no' => $member_regnumber));
                if ($get_user_regnum_info[0]['pay_status'] == '2') {

                    /* Update Pay Status */
                    $jaiib_exam_recovery_master_data = array('new_receipt_no' => $MerchantOrderNo, 'new_transaction_no' => $transaction_no, 'pay_status' => 2, 'modified_on' => date('Y-m-d H:i:s'));

                    $this->master_model->updateRecord('jaiib_exam_recovery_master', $jaiib_exam_recovery_master_data, array('invoice_id' => $selected_invoice_id, 'member_no' => $member_regnumber));

                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
                }
                $this->session->set_flashdata('flsh_msg', 'Transaction fail...!');
                redirect(base_url() . 'ExamRecoveryJaiib');
            }
        } else {
            die("Please try again...");
        }
    }
    /* Payment Success And Invoice genrate */
    public function sbitranssuccess()
    {
        $selected_invoice_id = $attachpath = $invoiceNumber = '';
        $selected_invoice_id = $this->session->userdata['memberdata']['selected_invoice_id']; // Seleted Invoice Id
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
            if (isset($_REQUEST['merchIdVal'])) {$merchIdVal = $_REQUEST['merchIdVal'];}
            if (isset($_REQUEST['Bank_Code'])) {$Bank_Code = $_REQUEST['Bank_Code'];}
            if (isset($_REQUEST['pushRespData'])) {$encData = $_REQUEST['pushRespData'];}
            $q_details = sbiqueryapi($MerchantOrderNo);
            if ($q_details) {
                if ($q_details[2] == "SUCCESS") {
                    $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id', '', '', '1');
                    if (empty($get_user_regnum_info)) {
                        redirect(base_url() . 'ExamRecovery');
                    }
                    if ($get_user_regnum_info[0]['status'] == 2) {
                        $new_invoice_id   = $get_user_regnum_info[0]['ref_id'];
                        $member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
                        //Query to get Payment details
                        $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $member_regnumber), 'transaction_no,date,amount,id');
                        $update_data  = array(
                            'transaction_no'      => $transaction_no,
                            'status'              => 1,
                            'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                            'auth_code'           => '0300',
                            'bankcode'            => $responsedata[8],
                            'paymode'             => $responsedata[5],
                            'callback'            => 'B2B',
                        );
                        $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                        /* Transaction Log */
                        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                        /* Update Exam Invoice */
                        $exam_invoice_data = array('pay_txn_id' => $payment_info[0]['id'], 'receipt_no' => $MerchantOrderNo, 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $transaction_no);
                        $this->master_model->updateRecord('exam_invoice', $exam_invoice_data, array('invoice_id' => $new_invoice_id, 'member_no' => $member_regnumber));
                        /* Update Pay Status */
                        $jaiib_exam_recovery_master_data = array('pay_status' => 1, 'modified_on' => date('Y-m-d H:i:s'));
                        $this->master_model->updateRecord('jaiib_exam_recovery_master', $jaiib_exam_recovery_master_data, array('invoice_id' => $selected_invoice_id, 'member_no' => $member_regnumber));
                        /* Email */
                        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_exam_recovery_email'), '', '', '1');
                        if (!empty($member_regnumber)) {
                            $user_info = $this->Master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile', '', '', '1');
                        }
                        if (count($emailerstr) > 0) {
                            /* Set Email sending options */
                            $info_arr = array(
                                'to'      => $user_info[0]['email'],
                                //'to' => 'esdstesting12@gmail.com',
                                //'to' => 'bhushan.amrutkar09@gmail.com',
                                //'to' => 'anishrivastava@iibf.org.in',
                                'from'    => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $emailerstr[0]['emailer_text'],
                            );
                            /* Invoice Number Genarate Functinality */
                            if ($new_invoice_id != '') {
                                $invoiceNumber = generate_exam_recovery_invoice_number($new_invoice_id);
                                if ($invoiceNumber != '') {
                                    $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                                }
                                $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                                $this->db->where('pay_txn_id', $payment_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                                /* Invoice Create Function */
                                $attachpath = genarate_exam_recovery_invoice($new_invoice_id);
                                /* User Log Activities  */
                                $log_title   = "Exam Recovery-Invoice Genarate";
                                $log_message = serialize($update_data);
                                $rId         = $new_invoice_id;
                                $regNo       = $member_regnumber;
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }
                            if ($attachpath != '') {
                                if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                    redirect(base_url() . 'ExamRecovery/acknowledge/');
                                } else {
                                    redirect(base_url() . 'ExamRecovery/acknowledge/');
                                }
                            } else {
                                redirect(base_url() . 'ExamRecovery/acknowledge/');
                            }
                        }
                    }
                }
            }
            redirect(base_url() . 'ExamRecovery/acknowledge/');
        } else {
            die("Please try again...");
        }
    }
    /* Payment Fail */
    public function sbitransfail()
    {
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
            $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status', '', '', '1');
            if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {
                if (isset($_REQUEST['merchIdVal'])) {$merchIdVal = $_REQUEST['merchIdVal'];}
                if (isset($_REQUEST['Bank_Code'])) {$Bank_Code = $_REQUEST['Bank_Code'];}
                if (isset($_REQUEST['pushRespData'])) {$encData = $_REQUEST['pushRespData'];}
                $update_data = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 0,
                    'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                    'auth_code'           => '0399',
                    'bankcode'            => $responsedata[8],
                    'paymode'             => $responsedata[5],
                    'callback'            => 'B2B',
                );
                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
            }
            $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
            redirect(base_url() . 'ExamRecovery');
            //echo "Transaction failed";
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
    /* Showing acknowledge after registration */
    public function acknowledge()
    {
        $data = array('middle_content' => 'ExamRecoveryJaiib/examrecovery_acknowledge.php');
        $this->load->view('ExamRecoveryJaiib/examrecovery_common_view', $data);
    }
    /* Payphi code */
    public function payphisuccess()
    {
        redirect(base_url() . 'ExamRecoveryJaiib/acknowledge/');
        /*echo "<pre>"; print_r($_POST);
        echo $_POST['merchantTxnNo'];
        echo "<br/><Br/>";
        echo $_SESSION['merchantTxnNo'];
        exit;*/
        ### Sbisucess code
        $selected_invoice_id = $attachpath = $invoiceNumber = '';
        $selected_invoice_id = $this->session->userdata['memberdata']['selected_invoice_id']; // Seleted Invoice Id
        $response            = $_POST;
        if (isset($_POST['respDescription'])) {
            if ($_POST['respDescription'] == "Transaction successful") {
                $transaction_no       = $_POST['merchantTxnNo'];
                $MerchantOrderNo      = $_POST['paymentID'];
                $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('transaction_no' => $transaction_no), 'ref_id,member_regnumber,status,id', '', '', '1');
                if (empty($get_user_regnum_info)) {
                    redirect(base_url() . 'ExamRecovery');
                }
                if ($get_user_regnum_info[0]['status'] == 2) {
                    $new_invoice_id   = $get_user_regnum_info[0]['ref_id'];
                    $member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
                    //Query to get Payment details
                    $payment_info = $this->master_model->getRecords('payment_transaction', array('transaction_no' => $transaction_no, 'member_regnumber' => $member_regnumber), 'transaction_no,date,amount,id');
                    $update_data  = array(
                        'receipt_no'          => $MerchantOrderNo,
                        'status'              => 1,
                        'transaction_details' => $_POST['respDescription'],
                        'auth_code'           => $_POST['responseCode'],
                        'bankcode'            => '', //$_POST['paymentSubInstType']
                        'paymode'             => $_POST['paymentMode'],
                        'callback'            => 'B2B',
                    );
                    $this->master_model->updateRecord('payment_transaction', $update_data, array('transaction_no' => $transaction_no));
                    /* Transaction Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]); */
                    /* Update Exam Invoice */
                    $exam_invoice_data = array('pay_txn_id' => $payment_info[0]['id'], 'receipt_no' => $MerchantOrderNo, 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $transaction_no);
                    $this->master_model->updateRecord('exam_invoice', $exam_invoice_data, array('invoice_id' => $new_invoice_id, 'member_no' => $member_regnumber));
                    /* Update Pay Status */
                    $jaiib_exam_recovery_master_data = array('pay_status' => 1, 'modified_on' => date('Y-m-d H:i:s'));
                    $this->master_model->updateRecord('jaiib_exam_recovery_master', $jaiib_exam_recovery_master_data, array('invoice_id' => $selected_invoice_id, 'member_no' => $member_regnumber));
                    /* Email */
                    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_exam_recovery_email'), '', '', '1');
                    if (!empty($member_regnumber)) {
                        $user_info = $this->Master_model->getRecords('member_registration', array('regnumber' => $member_regnumber, 'isactive' => '1'), 'email,mobile', '', '', '1');
                    }
                    if (count($emailerstr) > 0) {
                        /* Set Email sending options */
                        $info_arr = array(
                            'to'      => $user_info[0]['email'],
                            'from'    => $emailerstr[0]['from'],
                            'subject' => $emailerstr[0]['subject'],
                            'message' => $emailerstr[0]['emailer_text'],
                        );
                        /* Invoice Number Genarate Functinality */
                        if ($new_invoice_id != '') {
                            $invoiceNumber = generate_exam_recovery_invoice_number($new_invoice_id);
                            if ($invoiceNumber != '') {
                                $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                            }
                            $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                            $this->db->where('pay_txn_id', $payment_info[0]['id']);
                            $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                            /* Invoice Create Function */
                            $attachpath = genarate_exam_recovery_invoice($new_invoice_id);
                            /* User Log Activities  */
                            $log_title   = "Exam Recovery-Invoice Genarate";
                            $log_message = serialize($update_data);
                            $rId         = $new_invoice_id;
                            $regNo       = $member_regnumber;
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                        }
                        if ($attachpath != '') {
                            if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                redirect(base_url() . 'ExamRecovery/acknowledge/');
                            } else {
                                redirect(base_url() . 'ExamRecovery/acknowledge/');
                            }
                        } else {
                            redirect(base_url() . 'ExamRecovery/acknowledge/');
                        }
                    }
                }
            }
        }
        redirect(base_url() . 'ExamRecovery/acknowledge/');
    }
    public function payphi_stored_details()
    {
        ### Stored details code
        $regnumber           = $invoice_id           = $tax_type           = '';
        $cgst_rate           = $sgst_rate           = $cgst_amt           = $sgst_amt           = $cs_total           = $igst_rate           = $igst_amt           = $igst_total           = 0;
        $selected_invoice_id = base64_decode($this->uri->segment(3));
        $merchantTxnNo       = $this->uri->segment(4);
        $this->db->where('invoice_id', $selected_invoice_id);
        $examArr = $this->master_model->getRecords('jaiib_exam_recovery_master', '', '', '', '', '1');
        if (!empty($examArr)) {
            $regnumber = $examArr[0]['member_no'];
            if ($examArr[0]['state_of_center'] == 'MAH') {
                $cgst_rate = $examArr[0]['cgst_rate'];
                $sgst_rate = $examArr[0]['sgst_rate'];
                $cgst_amt  = $examArr[0]['cgst_amt'];
                $sgst_amt  = $examArr[0]['sgst_amt'];
                $cs_total  = $examArr[0]['cs_total'];
                $tax_type  = 'Intra';
            } else {
                $igst_rate  = $examArr[0]['igst_rate'];
                $igst_amt   = $examArr[0]['igst_amt'];
                $igst_total = $examArr[0]['igst_total'];
                $tax_type   = 'Inter';
            }
            /* Stored Details in the Exam Invoice table */
            $invoice_insert_array = array(
                'exam_code'       => $examArr[0]['exam_code'],
                'exam_period'     => $examArr[0]['exam_period'],
                'center_code'     => $examArr[0]['center_code'],
                'center_name'     => $examArr[0]['center_name'],
                'state_of_center' => $examArr[0]['state_of_center'],
                'member_no'       => $regnumber,
                'app_type'        => 'K',
                'service_code'    => $this->config->item('exam_service_code'),
                'qty'             => '1',
                'state_code'      => $examArr[0]['state_code'],
                'state_name'      => $examArr[0]['state_name'],
                'tax_type'        => $tax_type,
                'fee_amt'         => $examArr[0]['fee_amt'],
                'cgst_rate'       => $cgst_rate,
                'cgst_amt'        => $cgst_amt,
                'sgst_rate'       => $sgst_rate,
                'sgst_amt'        => $sgst_amt,
                'igst_rate'       => $igst_rate,
                'igst_amt'        => $igst_amt,
                'cs_total'        => $cs_total,
                'igst_total'      => $igst_total,
                'exempt'          => $examArr[0]['exempt'],
                'created_on'      => date('Y-m-d H:i:s'));
            if ($new_invoice_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array, true)) {
                /* User Log Activities  */
                $log_title   = "Exam Recovery-Stored Details";
                $log_message = serialize($invoice_insert_array);
                $rId         = $selected_invoice_id;
                $regNo       = $regnumber;
                storedUserActivity($log_title, $log_message, $rId, $regNo);
                /* Create User Session */
                $userarr = array('selected_invoice_id' => $selected_invoice_id, 'regnumber' => $regnumber, 'new_invoice_id' => $new_invoice_id);
                $this->session->set_userdata('memberdata', $userarr);
            }
        }
        ### Sbi makepayment code
        $flag = 1;
        /* Variables */
        /* $igst_total = $igst_amt = $pay_type = $exam_code = $selected_invoice_id = $regnumber = $new_invoice_id = $custom_field = '';
        $flag = 1;
        $selected_invoice_id = $selected_invoice_id; // Seleted Invoice Id
        $regnumber  = $regnumber;  // Member No
        $new_invoice_id   = $new_invoice_id;   // New Invoice Id*/
        if ($selected_invoice_id == "" || $regnumber == "" || $new_invoice_id == "") {
            /* User Log Activities  */
            $log_title   = "Exam Recovery-Session Expired";
            $log_message = 'Program Code : ' . $this->session->userdata['memberdata']['selected_invoice_id'];
            $rId         = $this->session->userdata['memberdata']['new_invoice_id'];
            $regNo       = $this->session->userdata['memberdata']['regnumber'];
            storedUserActivity($log_title, $log_message, $rId, $regNo);
            $this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
            redirect(base_url() . 'ExamRecovery');
        }
        $this->db->where('member_no', $regnumber);
        $this->db->where('invoice_id', $new_invoice_id);
        $MemberArray = $this->master_model->getRecords('exam_invoice', '', '', '', '', '1');
        if (!empty($MemberArray)) {
            $cs_total   = $MemberArray[0]['cs_total'];
            $igst_total = $MemberArray[0]['igst_total'];
            if ($cs_total != "0.00") {
                $amount = $cs_total;
            } else {
                $amount = $igst_total;
            }
            $exam_code = $MemberArray[0]['exam_code'];
        } else {
            redirect(base_url() . 'ExamRecovery');
        }
        //exit;
        /* Stored details in the Payment Transaction table */
        $pg_flag     = 'iibfrec';
        $insert_data = array(
            'member_regnumber' => $regnumber,
            'exam_code'        => $exam_code,
            'gateway'          => "payphi",
            'amount'           => $amount,
            'date'             => date('Y-m-d H:i:s'),
            'ref_id'           => $new_invoice_id,
            'description'      => "Exam Recovery",
            'pay_type'         => 14,
            'status'           => 2,
            'pg_flag'          => $pg_flag,
            'transaction_no'   => $merchantTxnNo,
        );
        $pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
        //$MerchantOrderNo = sbi_exam_order_id($pt_id);
        //$custom_field    = $MerchantOrderNo . "^iibfexam^" . "^iibfrec^" . "^" . $regnumber;
        $custom_field = "^iibfexam^" . "^iibfrec^" . "^" . $regnumber;
        $update_data  = array('pg_other_details' => $custom_field);
        $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
    }

    public function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }
}
