<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class ExamrecoveryTest extends CI_Controller{
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
    }

    /* Form View */
    public function index()
    {
        $selectedMemberId = '';
        if (isset($_POST['btnGetDetails'])) 
        {
            $selectedMemberId = $_POST['regnumber'];
            if ($selectedMemberId != '') 
            {
                $MemberArray = $this->validateMember($selectedMemberId);
            } 
            else 
            {
                $this->session->set_flashdata('flsh_msg', 'The Membership No. field is required.');
                redirect(base_url() . 'ExamRecovery');
            }
            if (!empty($MemberArray)) 
            {
                $data = array('middle_content' => 'ExamRecovery/examrecovery','MemberArray' => $MemberArray);
                $this->load->view('ExamRecovery/examrecovery_common_view', $data);
            }
        } 
        else 
        {
            $data = array('middle_content' => 'ExamRecovery/examrecovery');
            $this->load->view('ExamRecovery/examrecovery_common_view', $data);
        }
    }

    /* Validate Member Function */
    function validateMember($selectedMemberId)
    {
        $validateQry = $this->db->query("SELECT member_no FROM exam_recovery_master WHERE member_no = '" . $selectedMemberId . "'  LIMIT 1 ");
        
        if($validateQry->num_rows() > 0)
        {
            $validateMemberNo = $validateQry->row_array();
            
            $this->db->join('admit_exam_master', 'exam_recovery_master.exam_code = admit_exam_master.exam_code', 'left');
             $this->db->join('member_registration', 'exam_recovery_master.member_no = member_registration.regnumber', 'left');
            $this->db->where('member_no', $selectedMemberId);
            $MemberArray = $this->master_model->getRecords('exam_recovery_master', '', 'exam_recovery_master.*,admit_exam_master.description,regnumber,firstname,middlename,lastname,email,mobile');
            
            if (empty($MemberArray)) 
            {
                $this->session->set_flashdata('flsh_msg', 'Please Enter Valid Membership No.');
                redirect(base_url() . 'ExamRecovery');
            }
            return $MemberArray;
        }
        else
        {
            $this->session->set_flashdata('flsh_msg', 'You are not eligible member for Exam Recovery..!');
            redirect(base_url() . 'ExamRecovery');
        }
    }

    /* Stored detials after click on Pay Now Button */
    public function stored_details()
    {
        $regnumber = $invoice_id = $tax_type  = '';
        $cgst_rate = $sgst_rate = $cgst_amt = $sgst_amt = $cs_total  = $igst_rate = $igst_amt = $igst_total = 0;        
        $selected_invoice_id = base64_decode($this->uri->segment(3));
        
        if ($selected_invoice_id == "") 
        { 
            redirect(base_url() . 'ExamRecovery'); 
        } 
        else 
        {
            $this->db->where('invoice_id', $selected_invoice_id);
            $examArr = $this->master_model->getRecords('exam_recovery_master', '', '','','','1');
            
            if (!empty($examArr)) 
            {
                $regnumber   = $examArr[0]['member_no'];
                if($examArr[0]['state_of_center']=='MAH')
                {
                    $cgst_rate = $examArr[0]['cgst_rate'];
                    $sgst_rate = $examArr[0]['sgst_rate'];
                    $cgst_amt  = $examArr[0]['cgst_amt'];
                    $sgst_amt  = $examArr[0]['sgst_amt'];
                    $cs_total  = $examArr[0]['cs_total'];
                    $tax_type  = 'Intra';
                } 
                else 
                {
                    $igst_rate  = $examArr[0]['igst_rate'];
                    $igst_amt   = $examArr[0]['igst_amt'];
                    $igst_total = $examArr[0]['igst_total']; 
                    $tax_type   = 'Inter';
                }
                    
                /* Stored Details in the Exam Invoice table */
                $invoice_insert_array=array(
                    'exam_code' => $examArr[0]['exam_code'],
                    'exam_period' => $examArr[0]['exam_period'],
                    'center_code' => $examArr[0]['center_code'],
                    'center_name' => $examArr[0]['center_name'],
                    'state_of_center' => $examArr[0]['state_of_center'],
                    'member_no' => $regnumber,
                    'app_type' =>'K',
                    'service_code' =>$this->config->item('exam_service_code'),
                    'qty' => '1',
                    'state_code' => $examArr[0]['state_code'],
                    'state_name' => $examArr[0]['state_name'],
                    'tax_type' => $tax_type,
                    'fee_amt' => $examArr[0]['fee_amt'],
                    'cgst_rate'=>$cgst_rate,
                    'cgst_amt'=>$cgst_amt,
                    'sgst_rate'=>$sgst_rate,
                    'sgst_amt'=>$sgst_amt,
                    'igst_rate'=>$igst_rate,
                    'igst_amt'=>$igst_amt,
                    'cs_total'=>$cs_total,
                    'igst_total'=>$igst_total,
                    'exempt'=>$examArr[0]['exempt'],
                    'created_on'=>date('Y-m-d H:i:s'));
          
                if ($new_invoice_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array, true))
                {
                    /* User Log Activities  */
                    $log_title   = "Exam Recovery-Stored Details";
                    $log_message = serialize($invoice_insert_array);
                    $rId         = $selected_invoice_id;
                    $regNo       = $regnumber;
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                    
                    /* Create User Session */
                    $userarr = array('selected_invoice_id' => $selected_invoice_id,'regnumber' => $regnumber,'new_invoice_id' => $new_invoice_id);
                    $this->session->set_userdata('memberdata', $userarr);
                    
                    /* Go to Make Payment */
                    redirect(base_url() . "ExamRecovery/make_payment");
                } 
                else 
                {
                    redirect(base_url() . 'ExamRecovery');
                }
            } 
            else
            {
                redirect(base_url() . 'ExamRecovery');
            }
        }
    }

    /* Make Payment */
    public function make_payment()
    {
        /* Variables */
        $igst_total = $igst_amt = $pay_type = $exam_code = $selected_invoice_id = $regnumber = $new_invoice_id = $custom_field = '';
        $flag = 1;
        $selected_invoice_id = $this->session->userdata['memberdata']['selected_invoice_id']; // Seleted Invoice Id
        $regnumber  = $this->session->userdata['memberdata']['regnumber'];  // Member No
        $new_invoice_id   = $this->session->userdata['memberdata']['new_invoice_id'];   // New Invoice Id
        
        if ($selected_invoice_id == "" || $regnumber == "" || $new_invoice_id == "") 
        {
            /* User Log Activities  */
            $log_title   = "Exam Recovery-Session Expired";
            $log_message = 'Program Code : ' . $this->session->userdata['memberdata']['selected_invoice_id'];
            $rId         = $this->session->userdata['memberdata']['new_invoice_id'];
            $regNo       = $this->session->userdata['memberdata']['regnumber'];
            storedUserActivity($log_title, $log_message, $rId, $regNo); 
            $this->session->set_flashdata('flsh_msg', 'Your session has been expired. Please try again...!');
            redirect(base_url() . 'ExamRecovery');
        }
        
        if (isset($_POST['processPayment']) && $_POST['processPayment']) 
        {
            $pg_name = 'billdesk';
            $this->db->where('member_no', $regnumber);
            $this->db->where('invoice_id', $new_invoice_id);
            $MemberArray    = $this->master_model->getRecords('exam_invoice', '', '','','','1');
            if (!empty($MemberArray)) {
                $cs_total       = $MemberArray[0]['cs_total'];
                $igst_total     = $MemberArray[0]['igst_total'];
                if ($cs_total != "0.00") {
                    $amount = $cs_total;
                } else {
                    $amount = $igst_total;
                }
                $exam_code       = $MemberArray[0]['exam_code'];
            } else {
                redirect(base_url() . 'ExamRecovery'); 
             }
            $pg_flag         = 'iibfrec'; 
            $insert_data     = array(
                'member_regnumber' => $regnumber,
                'exam_code' => $exam_code,
                'gateway' => "sbiepay",
                'amount' => $amount,
                'date' => date('Y-m-d H:i:s'),
                'ref_id' => $new_invoice_id,
                'description' => "Exam Recovery",
                'pay_type' => 14,
                'status' => 2,
                'pg_flag' => $pg_flag
            );
        /* Stored details in the Payment Transaction table */
            $pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
            
            $MerchantOrderNo = sbi_exam_order_id($pt_id);
            $custom_field    = $MerchantOrderNo . "^iibfexam^" . "^iibfrec^" . "^" . $regnumber; 
            
            $billdesk_additional_info    = $MerchantOrderNo . "-iibfexam" . "-iibfrec" . "-" . $regnumber;

            $update_data     = array('receipt_no' => $MerchantOrderNo,'pg_other_details' => $custom_field);
            $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
            if ($pg_name == 'sbi') 
                        {
                            exit();
                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                $key            = $this->config->item('sbi_m_key');
                $merchIdVal     = $this->config->item('sbi_merchIdVal');
                $AggregatorId   = $this->config->item('sbi_AggregatorId');
                $pg_success_url = base_url() . "ExamRecovery/sbitranssuccess";
                $pg_fail_url    = base_url() . "ExamRecovery/sbitransfail";
        //exit;
            $MerchantCustomerID  = $new_invoice_id;
            $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
            $data["merchIdVal"]  = $merchIdVal;
            $EncryptTrans        = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
            $aes                 = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $EncryptTrans         = $aes->encrypt($EncryptTrans);
            $data["EncryptTrans"] = $EncryptTrans;
                $this->load->view('pg_sbi_form', $data);
            } 
                        elseif ($pg_name == 'billdesk') 
                        {
                            $update_payment_data = array('gateway' =>'billdesk');
                            $this->master_model->updateRecord('payment_transaction',$update_payment_data,array('id'=>$pt_id));
                            
                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'ExamRecovery/handle_billdesk_response','','','',$billdesk_additional_info);
                //print_r($billdesk_additional_info);
                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid'] = $billdesk_res['bdorderid'];
                    $data['token']     = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
                    $data['returnUrl'] = $billdesk_res['returnUrl']; 
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                }else{
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'ExamRecovery');
                }
            }
        } 
        else
        {
            $data['show_billdesk_option_flag'] = 1;
            $this->load->view('pg_sbi/make_payment_page', $data);
        }
    }
}
?>