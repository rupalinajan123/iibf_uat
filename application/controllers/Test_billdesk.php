<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Test_billdesk extends CI_Controller
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
        $this->load->helper('cookie');
        $this->load->model('log_model');
        $this->load->model('KYC_Log_model');
        $this->load->model('billdesk_pg_model');
        $this->chk_session->Check_mult_session();
        //exit;
        $this->load->model('refund_after_capacity_full');
        header("Access-Control-Allow-Origin: *");
    }
    // function index(){
    //     $pt_id = (rand(10,100));

    //     $MerchantOrderNo = sbi_exam_order_id($pt_id);
    //     $regid = 85858789;
    //     $billdesk_res = $this->billdesk_pg_model->init_payment_request_demo($MerchantOrderNo,'1' , $regid, $regid, '', 'Test_billdesk/handle_billdesk_response', '', '', '');
    //     echo '<pre>'; print_r($billdesk_res); echo '</pre>';
    // }

    public function decode_bd()
    {

        $res = $this->billdesk_pg_model->verify_res("eyJhbGciOiJIUzI1NiIsImNsaWVudGlkIjoiaW5kaW5zYmFmIiwia2lkIjoiSE1BQyJ9.eyJtZXJjaWQiOiJJTkRJTlNCQUYiLCJ0cmFuc2FjdGlvbl9kYXRlIjoiMjAyMi0wOS0yOVQwNDoxMToyNyswNTozMCIsInN1cmNoYXJnZSI6IjAuMDAiLCJwYXltZW50X21ldGhvZF90eXBlIjoidXBpIiwiYW1vdW50IjoiNDcyLjAwIiwicnUiOiJodHRwczovL2lpYmYuZXNkc2Nvbm5lY3QuY29tL05vbk1lbWJlci9oYW5kbGVfYmlsbGRlc2tfcmVzcG9uc2UiLCJvcmRlcmlkIjoiOTAzNjkxNTQyIiwidHJhbnNhY3Rpb25fZXJyb3JfdHlwZSI6InN1Y2Nlc3MiLCJkaXNjb3VudCI6IjAuMDAiLCJiYW5rX3JlZl9ubyI6IjIyNzIzMjUyMTU5NiIsInRyYW5zYWN0aW9uaWQiOiJYSEQ1MDc4MzIwMzQ2MyIsInR4bl9wcm9jZXNzX3R5cGUiOiJpbnRlbnQiLCJiYW5raWQiOiJIRDUiLCJhZGRpdGlvbmFsX2luZm8iOnsiYWRkaXRpb25hbF9pbmZvNyI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvNiI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvOSI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvOCI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvMTAiOiJOQSIsImFkZGl0aW9uYWxfaW5mbzEiOiI5MDM2OTE1NDIiLCJhZGRpdGlvbmFsX2luZm8zIjoiODAxMjk1NTQxIiwiYWRkaXRpb25hbF9pbmZvMiI6ImlpYmZleGFtIiwiYWRkaXRpb25hbF9pbmZvNSI6Ik5BIiwiYWRkaXRpb25hbF9pbmZvNCI6IjEwMTIwMjIxMSJ9LCJpdGVtY29kZSI6IkRJUkVDVCIsInRyYW5zYWN0aW9uX2Vycm9yX2NvZGUiOiJUUlMwMDAwIiwiY3VycmVuY3kiOiIzNTYiLCJhdXRoX3N0YXR1cyI6IjAzMDAiLCJ0cmFuc2FjdGlvbl9lcnJvcl9kZXNjIjoiVHJhbnNhY3Rpb24gU3VjY2Vzc2Z1bCIsIm9iamVjdGlkIjoidHJhbnNhY3Rpb24iLCJjaGFyZ2VfYW1vdW50IjoiNDcyLjAwIn0.7COprBz78yBaM5ANdRjy-H5U59blmnCw7H2LKq9yGe8");
        echo "<pre>";
        print_r($res);

    }

    public function test_pass()
    {
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('sbi_m_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $decpass = $aes->decrypt("QVRs2Hh6kDNKKind33XkLQ==") ;
        echo $decpass;
    }

    public function make_refund(){

        $this->refund_after_capacity_full->make_refund(903727929);

    }

}
