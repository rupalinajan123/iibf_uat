<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Transaction extends CI_Controller
{
    public $UserID;

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('bulk_admin')) {
            redirect('bulk/admin/Login');
        }
        $this->UserData = $this->session->userdata('bulk_admin');
        $this->UserID   = $this->UserData['id'];
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        $this->load->helper('master_helper');
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('general_helper');
        $this->load->helper('bulk_admitcard_helper');
        $this->load->helper('bulk_invoice_helper');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->elearning_course_code = [528, 529, 530, 531, 534];
    }

    public function transactions()
    {
        $this->session->set_userdata('field', '');
        $this->session->set_userdata('value', '');
        $this->session->set_userdata('per_page', '');
        $this->session->set_userdata('sortkey', '');
        $this->session->set_userdata('sortval', '');

        //$data["exam_period_list"] = array_unique($this->Master_model->getRecords("misc_master","","exam_period")); // remove duplicates from this array
        //$data["exam_period_list"] = $this->db->query("SELECT DISTINCT(exam_period) FROM misc_master WHERE misc_delete = '0'")->result_array();
        $data["institute_list"] = $this->Master_model->getRecords("bulk_accerdited_master", "accerdited_delete = '0'", "institute_code,institute_name");
        //$data["exam_list"] = $this->Master_model->getRecords("exam_master","exam_delete = '0'","exam_code,description");

        $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'bulk/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">Transactions</li>
		 </ol>';

        $this->load->view('bulk/admin/transaction/transactions', $data);
    }

    // function to get list of All transactions-
    public function getTransactions()
    {
        $data['result']  = array();
        $data['action']  = array();
        $data['links']   = '';
        $data['success'] = '';
        $field           = '';
        $value           = '';
        $sortkey         = '';
        $sortval         = '';
        $per_page        = '';
        $limit           = 10;
        $start           = 0;

        $session_arr = check_session();
        if ($session_arr) {
            $field    = $session_arr['field'];
            $value    = $session_arr['value'];
            $sortkey  = $session_arr['sortkey'];
            $sortval  = $session_arr['sortval'];
            $per_page = $session_arr['per_page'];
            $start    = $session_arr['start'];
        }

        $reg_no = '';

        $where = '';
        if ($value != "") {
            $temp_where = array();

            $post_data = explode('~', $value);

            //print_r($post_data); die();

            if (count($post_data) > 0) {
                $reg_no         = isset($post_data[0]) ? $post_data[0] : '';
                $txn_no         = isset($post_data[1]) ? $post_data[1] : '';
                $from_date      = isset($post_data[2]) ? $post_data[2] : '';
                $to_date        = isset($post_data[3]) ? $post_data[3] : '';
                $payment_mode   = isset($post_data[4]) ? $post_data[4] : '';
                $payment_status = isset($post_data[5]) ? $post_data[5] : '';
                $inst_code      = isset($post_data[6]) ? $post_data[6] : '';
                $exam_period    = isset($post_data[7]) ? $post_data[7] : '';
                $exam_code      = isset($post_data[8]) ? $post_data[8] : '';
                //reg_no+'~'+txn_no+'~'+from_date+'~'+to_date+'~'+payment_mode+'~'+payment_status+'~'+inst_code+'~'+exam_period+'~'+exam_code

                if ($reg_no != "") {
                    $temp_where[] = 'member_registration.regnumber = "' . $reg_no . '"';
                }

                if ($txn_no != "") {
                    $temp_where[] = 'bulk_payment_transaction.transaction_no = "' . $txn_no . '" OR bulk_payment_transaction.UTR_no = "' . $txn_no . '"';
                }

                if ($from_date != "" && $to_date == "") {
                    $temp_where[] = 'DATE(bulk_payment_transaction.date) = "' . $from_date . '"';
                } else if ($from_date != "" && $to_date != "") {
                    $temp_where[] = '(DATE(bulk_payment_transaction.date) BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
                }

                /*if($payment_mode != "")
                {
                $temp_where[] = 'gateway = "'.$payment_mode.'"';
                }*/

                if ($payment_status != "" && $payment_status != "undefined") {
                    $temp_where[] = 'bulk_payment_transaction.status = "' . $payment_status . '"';
                } else {
                    $temp_where[] = '(bulk_payment_transaction.status = "0" OR bulk_payment_transaction.status = "1")'; // status = success or fail
                }

                if ($inst_code != "") {
                    $temp_where[] = 'bulk_payment_transaction.inst_code = "' . $inst_code . '"';
                }

                /*if($exam_period != "")
                {
                $temp_where[] = 'exam_period = "'.$exam_period.'"';
                }

                if($exam_code != "")
                {
                $temp_where[] = 'exam_code = "'.$exam_code.'"';
                }*/

                if (!empty($temp_where)) {
                    $where .= implode(" AND ", $temp_where);
                }
            }
        } else {
            $where .= '(status = "0" OR status = "1")'; // status = success or fail
        }

        $select = 'bulk_payment_transaction.id,bulk_payment_transaction.exam_code,bulk_payment_transaction.exam_period,gateway,bulk_payment_transaction.inst_code,bulk_payment_transaction.receipt_no,status,bulk_payment_transaction.transaction_no,UTR_no,DATE_FORMAT(date,"%d-%m-%Y") As pay_date,pay_count AS member_count,amount,exam_invoice.disc_amt,exam_invoice.tds_amt,bulk_accerdited_master.institute_name AS inst_name';

        $this->db->join('bulk_accerdited_master', 'bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code', 'LEFT');

        if ($reg_no != "") {
            $this->db->join('bulk_member_payment_transaction', 'bulk_member_payment_transaction.ptid = bulk_payment_transaction.id', 'LEFT');
            $this->db->join('member_exam', 'member_exam.id = bulk_member_payment_transaction.memexamid', 'LEFT');
            $this->db->join('member_registration', 'member_registration.regnumber = member_exam.regnumber', 'LEFT');
            $this->db->where('member_registration.isactive = "1"');
        }

        //$where .= ' ORDER BY date DESC';

        //do not count records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
        //$where .= ' AND date(bulk_payment_transaction.date) > "2017-01-08"';

        $this->db->where($where);
        $this->db->where("bulk_payment_transaction.exam_code !=", '997');
        // get total record count for pagination
        $total_row = $this->UserModel->getRecordCount("bulk_payment_transaction", "", "");

        //$data['query1'] = $this->db->last_query(); die();

        // transactions order by date in descending order -
        $sortkey = 'updated_date';
        $sortval = 'DESC';

        $this->db->join('bulk_accerdited_master', 'bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code', 'LEFT');

        $this->db->join('exam_invoice', 'exam_invoice.pay_txn_id = bulk_payment_transaction.id', 'LEFT');
        $this->db->where('exam_invoice.app_type = "Z"'); // app_type 'Z' in exam_invoice table is for Bulk exam module
        if ($reg_no != "") {
            $this->db->join('bulk_member_payment_transaction', 'bulk_member_payment_transaction.ptid = bulk_payment_transaction.id', 'LEFT');
            $this->db->join('member_exam', 'member_exam.id = bulk_member_payment_transaction.memexamid', 'LEFT');
            $this->db->join('member_registration', 'member_registration.regnumber = member_exam.regnumber', 'LEFT');
            $this->db->where('member_registration.isactive = "1"');
        }
        //do not show records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
        //$where .= ' AND date(bulk_payment_transaction.date) > "2017-01-08"';

        $this->db->where($where);
        $this->db->where("bulk_payment_transaction.exam_code !=", '997');
        $res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', $sortkey, $sortval, $per_page, $start);

        //$data['query'] = $this->db->last_query();

        if ($res) {
            $result = $res->result_array();

            $result_new = array();

            foreach ($result as $row) {
                $pay_status = $row['status'];

                foreach ($row as $key => $value) {
                    if ($key == "status" && $value == 1) // status = 1 for Success
                    {
                        $row['status'] = '<span class="label label-success">Success</span>';
                    } else if ($key == "status" && $value == 0) // status = 0 for Error
                    {
                        $row['status'] = '<span class="label label-danger">Fail</span>';
                    }

                    if ($key == "gateway" && $value == "1") // gateway = 1 for NEFT/RTGS
                    {
                        $row['transaction_no'] = $row['UTR_no'];
                    }

                    // if transaction no is empty -
                    if ($key == "transaction_no" || $key == "UTR_no") {
                        if ($row['transaction_no'] == "" && $row['UTR_no'] == "") {
                            $row['transaction_no'] = 'NA';
                        }
                    }

                    // if bankcode is empty -
                    /*if($key == "bankcode") // gateway = 1 for NEFT/RTGS
                    {
                    if($row['bankcode'] == "")
                    {
                    $row['bankcode'] = 'NA';
                    }
                    }*/

                    // get reg nos. for each payment transaction -
                    $reg_no_list = array();

                    $select2 = 'member_registration.regnumber';
                    $this->db->join('member_exam', 'member_exam.id = bulk_member_payment_transaction.memexamid', 'LEFT');
                    $this->db->join('member_registration', 'member_registration.regnumber = member_exam.regnumber', 'LEFT');
                    $this->db->where('member_registration.isactive = "1"');
                    $this->db->where('bulk_member_payment_transaction.ptid = ' . $row['id']);
                    $res2    = $this->UserModel->getRecords("bulk_member_payment_transaction", $select2, '', '', '', '', '', '');
                    $result2 = $res2->result_array();

                    /*$select2 = 'member_exam.regid';
                    $this->db->join('member_exam','member_exam.id = bulk_member_payment_transaction.memexamid','LEFT');
                    $this->db->where('bulk_member_payment_transaction.ptid = '.$row['id']);
                    $res2 = $this->UserModel->getRecords("bulk_member_payment_transaction", $select2, '', '', '', '', '', '');
                    $result2 = $res2->result_array();*/

                    //$data['query3'] = $this->db->last_query();

                    foreach ($result2 as $row2) {
                        $reg_no_list[] = $row2['regnumber'];
                    }

                    //$reg_nos = implode(",", $reg_no_list);

                    //$row['paid_reg_nos'] = wordwrap($reg_nos,31,"<br>\n", TRUE);

                    $reg_nos = "";
                    $cnt     = 0;
                    if (count($reg_no_list) > 0 && $reg_no_list[0] != "") // check if more than 1 reg. no.
                    {
                        $temp_arr = array();
                        foreach ($reg_no_list as $r) {
                            $temp_list = '';

                            $cnt++;
                            $temp_list .= $r;
                            if ($cnt % 2 == 0) {
                                $temp_list .= "<br>";
                            }
                            // display 2 reg. nos. each line

                            $temp_arr[] = $temp_list;
                        }
                        $reg_nos .= implode(',', $temp_arr);
                    } else {
                        $reg_nos .= "-";
                    }
                    $row['paid_reg_nos'] = $reg_nos;
                }

                $result_new[] = $row;

                // action -
                $action = '<a href="' . base_url() . 'bulk/admin/transaction/view_inst_receipt/' . base64_encode($row['id']) . '" target="_blank">Receipt</a>';

                /******************* code added for GST Changes, by Bhagwan Sahane, on 07-07-2017 ***************/

                // get invoice image for this transaction
                $invoice_img_path = '';

                $this->db->where('exam_invoice.app_type = "Z"');
                $exam_invoice = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $row['id']), 'invoice_image,date_of_invoice,gstin_no,invoice_no');
                if (count($exam_invoice) > 0) {
                    // get invoice image path
                    $invoice_image = $exam_invoice[0]['invoice_image'];
                    if ($invoice_image) {
                        $invoice_img_path = $invoice_image;
                    }
                }
                $str_invoice_no = str_replace("/", "_", $exam_invoice[0]['invoice_no']);
                //$str_invoice_no_str = str_replace("-","_",$str_invoice_no);
                if ($invoice_img_path != '') {
                    if ($exam_invoice[0]['date_of_invoice'] < '2020-12-31 23:59:59') {
                        $action .= ' <br> | <a href="' . base_url() . 'uploads/bulkexaminvoice/supplier/' . $invoice_img_path . '" target="_blank">Invoice</a>';
                    } else {
                        if ($exam_invoice[0]['gstin_no'] == '') {
                            $action .= ' <br> | <a href="' . base_url() . 'uploads/bulkexaminvoice/supplier/' . $invoice_img_path . '" target="_blank">Invoice</a>';
                        } else {
                            /*$action .= ' <br> | <a href="http://10.10.233.76:8083/irnapi/getDataByDocNo/'.$str_invoice_no_str.'" target="_blank">Invoice</a>';*/
                            ## Code added on 2-Mar-2021
                            $action .= ' <br> | <a href="' . base_url() . 'bulk/admin/transaction/getInvoice/' . base64_encode($str_invoice_no) . '">E-Invoice</a>';
                        }
                    }
                }

                /******************* eof code added for GST Changes, by Bhagwan Sahane, on 07-07-2017 ***************/
                $show_Admit_Card = true;
                if ( in_array( $row['exam_code'], $this->elearning_course_code) ) {
                    $show_Admit_Card = false;
                }
                // admit card - 
                if ($pay_status == 1 &&  $show_Admit_Card) // if payment status is Success
                {
                    $action .= ' <br> | <a href="' . base_url() . 'bulk/admin/transaction/mem_admit_card_list/' . base64_encode($row['id']) . '" target="_blank">Admit Card 1</a>';
                }

                $data['action'][] = $action;
            }

            $data['result'] = $result_new;

            if (count($result_new)) {
                $data['success'] = 'Success';
            } else {
                $data['success'] = '';
            }

            $url = base_url() . "bulk/admin/transaction/getTransactions/";
            //$total_row = count($result_new);
            $config = pagination_init($url, $total_row, $per_page, 2);
            $this->pagination->initialize($config);

            $str_links     = $this->pagination->create_links();
            $data["links"] = $str_links;
            if (($start + $per_page) > $total_row) {
                $end_of_total = $total_row;
            } else {
                $end_of_total = $start + $per_page;
            }

            $data['info']  = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
            $data['index'] = $start + 1;
        }

        $json_res = json_encode($data);
        echo $json_res;
    }
    public function getInvoice()
    {

        $inv_no = base64_decode($this->uri->segment(5));
        ## Test invoice no
        //$inv_no = 'EDN_20-21_000310';
        ## Live Url
        $service_url = 'http://10.10.233.76:8083/irnapi/getDataByDocNo/' . $inv_no;
        $curl        = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($curl);
        curl_close($curl);
        $json_objekat = json_decode($curl_response);
        $file_cont    = base64_decode($json_objekat->signedPdf);
        header('Content-Type: application/pdf');
        header('Content-Length:' . strlen($file_cont));
        header('Content-disposition: attachment; filename=invoice.pdf');
        header('Content-Transfer-Encoding: Binary');
        echo $file_cont;

        //$this->session->set_flashdata('success','E-invoice downloaded successfully.');
        redirect(base_url() . 'bulk/admin/transaction/transactions');

    }
    // function to view Bulk institute payment receipt -
    public function view_inst_receipt($txn_id)
    {
        $txn_id = base64_decode($txn_id);

        $select = 'bulk_payment_transaction.id,bulk_payment_transaction.inst_code,receipt_no,gateway,transaction_no,UTR_no,amount,DATE_FORMAT(date,"%d-%m-%Y") As date,exam_period,status,bulk_accerdited_master.institute_name AS inst_name,bulk_accerdited_master.email AS inst_email';
        $this->db->join('bulk_accerdited_master', 'bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code', 'LEFT');
        $this->db->where('bulk_payment_transaction.id = "' . $txn_id . '"');
        $res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');

        $result = $res->result_array();

        $data['result'] = $result[0];

        $this->load->view('bulk/admin/transaction/view_inst_receipt', $data);
    }

    // function to view member receipt list for Bulk payment -
    public function mem_receipt_list($txn_id)
    {
        $txn_id = base64_decode($txn_id);

        $data['txn_id'] = $txn_id;

        // get payment transaction details -
        $select = 'bulk_payment_transaction.id,bulk_payment_transaction.inst_code,receipt_no,transaction_no,pay_count,amount,DATE_FORMAT(date,"%d-%m-%Y") As date,exam_period,status,bulk_accerdited_master.institute_name AS inst_name,bulk_accerdited_master.email AS inst_email';
        $this->db->join('bulk_accerdited_master', 'bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code', 'LEFT');
        $this->db->where('bulk_payment_transaction.id = "' . $txn_id . '"');
        $res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');

        $txn_result = $res->result_array();

        $total_amount = $txn_result[0]['amount'];

        $data['total_amount'] = $total_amount;

        // get list of all members for this payment transaction -
        $select = 'member_registration.regid,member_registration.regnumber,member_registration.firstname,member_registration.lastname,member_registration.email,member_exam.exam_fee';
        $this->db->join('member_exam', 'member_exam.id = bulk_member_payment_transaction.memexamid', 'LEFT');
        $this->db->join('member_registration', 'member_registration.regnumber = member_exam.regnumber', 'LEFT');
        $this->db->where('member_registration.isactive = "1"');
        $this->db->where('bulk_member_payment_transaction.ptid = ' . $txn_id);
        $res = $this->UserModel->getRecords("bulk_member_payment_transaction", $select, '', '', '', '', '', '');

        $result = $res->result_array();

        $data['result'] = $result;

        $this->load->view('bulk/admin/transaction/mem_receipt_list', $data);
    }

    // function to get member receipt details -
    public function mem_receipt($txn_id, $mem_id)
    {
        $txn_id = base64_decode($txn_id);
        $mem_id = base64_decode($mem_id);

        // get payment transaction details -
        $select = 'bulk_payment_transaction.id,bulk_payment_transaction.inst_code,receipt_no,gateway,transaction_no,UTR_no,pay_count,amount,DATE_FORMAT(date,"%d-%m-%Y") As date,exam_period,status,bulk_accerdited_master.institute_name AS inst_name,bulk_accerdited_master.email AS inst_email';
        $this->db->join('bulk_accerdited_master', 'bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code', 'LEFT');
        $this->db->where('bulk_payment_transaction.id = "' . $txn_id . '"');
        $res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');

        $txn_result = $res->result_array();

        $data['txn_details'] = $txn_result[0];

        // get list of all members for this payment transaction -
        $select = 'member_registration.regid,member_registration.regnumber,member_registration.firstname,member_registration.lastname,email,member_exam.exam_fee';
        $this->db->join('member_exam', 'member_exam.id = bulk_member_payment_transaction.memexamid', 'LEFT');
        $this->db->join('member_registration', 'member_registration.regnumber = member_exam.regnumber', 'LEFT');
        $this->db->where('member_registration.isactive = "1"');
        $this->db->where('bulk_member_payment_transaction.ptid = ' . $txn_id . ' AND member_registration.regid = ' . $mem_id);
        $res = $this->UserModel->getRecords("bulk_member_payment_transaction", $select, '', '', '', '', '', '');

        $mem_result = $res->result_array();

        $data['mem_details'] = $mem_result[0];

        $this->load->view('bulk/admin/transaction/mem_receipt', $data);
    }

    // function to view Bank institute Admit Card List -
    public function mem_admit_card_list($txn_id)
    {
        $txn_id = base64_decode($txn_id);

        $data['txn_id'] = $txn_id;

        // get list of all members for this payment transaction -
        $select = 'member_registration.regid,member_registration.regnumber,member_registration.firstname,member_registration.lastname,member_registration.email,member_exam.exam_fee,member_exam.exam_code,member_exam.exam_period';
        $this->db->join('member_exam', 'member_exam.id = bulk_member_payment_transaction.memexamid', 'LEFT');
        $this->db->join('member_registration', 'member_registration.regnumber = member_exam.regnumber', 'LEFT');
        $this->db->where('member_registration.isactive = "1"');
        $this->db->where('bulk_member_payment_transaction.ptid = ' . $txn_id);
        $res = $this->UserModel->getRecords("bulk_member_payment_transaction", $select, '', '', '', '', '', '');

        //echo $this->db->last_query(); die();

        $result = $res->result_array();

        $data['result'] = $result;

        $this->load->view('bulk/admin/transaction/mem_admit_card_list', $data);
    }

    public function neft_transactions()
    {
        $this->session->set_userdata('field', '');
        $this->session->set_userdata('value', '');
        $this->session->set_userdata('per_page', '');
        $this->session->set_userdata('sortkey', '');
        $this->session->set_userdata('sortval', '');

        $data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'bulk/admin/MainController"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">Approve NEFT Transactions</li>
		 </ol>';

        $this->load->view('bulk/admin/transaction/neft_transactions', $data);
    }

    // function to get list of NEFT transactions-
    public function getNeftTransactions()
    {
        $data['result']  = array();
        $data['action']  = array();
        $data['links']   = '';
        $data['success'] = '';
        $field           = '';
        $value           = '';
        $sortkey         = '';
        $sortval         = '';
        $per_page        = '';
        $limit           = 10;
        $start           = 0;

        $session_arr = check_session();
        if ($session_arr) {
            $field    = $session_arr['field'];
            $value    = $session_arr['value'];
            $sortkey  = $session_arr['sortkey'];
            $sortval  = $session_arr['sortval'];
            $per_page = $session_arr['per_page'];
            $start    = $session_arr['start'];
        }

        // transactions order by date in descending order -
        $sortkey = 'created_date';
        $sortval = 'DESC';

        // get total record count for pagination
        $this->db->select('count(*) as tot');
        $this->db->join('bulk_accerdited_master', 'bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code', 'LEFT');
        $this->db->join('exam_master', 'exam_master.exam_code = bulk_payment_transaction.exam_code', 'LEFT');
        $this->db->where('gateway = "1"');
        $this->db->where("bulk_payment_transaction.exam_code !=", '997');
        //do not count records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
        //$this->db->where('date(bulk_payment_transaction.date) > ','2017-01-08');

        $resArr = $this->db->get("bulk_payment_transaction");

        if ($resArr) {
            $result = $resArr->result_array();
        }
        $total_row = $result[0]["tot"];

        //$data['query1'] = $this->db->last_query();

        $select = 'bulk_payment_transaction.id,bulk_payment_transaction.exam_code,bulk_payment_transaction.exam_period,bulk_payment_transaction.inst_code,UTR_no AS transaction_no,exam_master.description AS Bulk,pay_count AS member_count,amount,exam_invoice.disc_amt,exam_invoice.tds_amt,DATE_FORMAT(date,"%d-%m-%Y") As pay_date,bulk_payment_transaction.created_date AS added_date,bulk_payment_transaction.updated_date,bulk_payment_transaction.exam_period,status,bulk_accerdited_master.institute_name AS inst_name'; // "Bulk" is Application in NEFT Transactions table in Bulk Admin (Hard coded)
        $this->db->join('exam_invoice', 'exam_invoice.pay_txn_id = bulk_payment_transaction.id', 'LEFT');
        $this->db->join('bulk_accerdited_master', 'bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code', 'LEFT');
        $this->db->join('exam_master', 'exam_master.exam_code = bulk_payment_transaction.exam_code', 'LEFT');
        $this->db->where('gateway = "1"');
        $this->db->where('exam_invoice.app_type = "Z"');
        //do not include records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
        //$this->db->where('date(bulk_payment_transaction.date) > ','2017-01-08');
        // if (count($_POST) > 0) {
        //     $per_page = $this->input->post('per_page');
        // }
        $this->db->where("bulk_payment_transaction.exam_code !=", '997');
        $res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', $sortkey, $sortval, $per_page, $start);

        $data['query'] = $this->db->last_query();

        if ($res) {
            $result = $res->result_array();

            $data['result'] = $result;

            foreach ($result as $row) {
                // check if approved by Bulk Admin -
                if ($row['status'] == 3 && $row['transaction_no'] != 'TEMP-UTR-IIBF') // status = 3 for Applied and Not Temp NEFT
                {
                    $action = '<span data-id="' . $row['id'] . '"><a href="javascript:void(0)" onclick="confirmVerify(' . $row['id'] . ');">Verify </a></span>';
                } else if ($row['status'] == 3 && $row['transaction_no'] == 'TEMP-UTR-IIBF') // status = 3 for Applied and Temp NEFT
                {
                    //$action = '<span class="label label-primary">Temp NEFT</span>';
                    $action = '';
                } else if ($row['status'] == 1) // status = 1 for Success
                {
                    $action = '<span class="label label-success">Approved</span>';
                } else if ($row['status'] == 0) // status = 0 for Rejected
                {
                    $action = '<span class="label label-danger">Rejected</span>';
                }
                $data['action'][] = $action;
            }

            if (count($result)) {
                $data['success'] = 'Success';
            } else {
                $data['success'] = '';
            }

            $url = base_url() . "bulk/admin/transaction/getNeftTransactions/";
            //$total_row = count($result);
            $config = pagination_init($url, $total_row, $per_page, 2);
            $this->pagination->initialize($config);

            $str_links     = $this->pagination->create_links();
            $data["links"] = $str_links;
            if (($start + $per_page) > $total_row) {
                $end_of_total = $total_row;
            } else {
                $end_of_total = $start + $per_page;
            }

            $data['info']  = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
            $data['index'] = $start + 1;
        }

        $json_res = json_encode($data);
        echo $json_res;
    }

    // function to get NEFT transaction details by id -
    public function getNeftTransactionDetails()
    {
        $data['result']  = array();
        $data['success'] = '';

        $id = $this->input->post('id');

        // "Bulk" is Application in NEFT Transactions table in Bulk Admin (Hard coded)
        $select = 'bulk_payment_transaction.id,UTR_no AS transaction_no,exam_master.description AS Bulk,pay_count AS member_count,amount,DATE_FORMAT(date,"%d-%m-%Y") As date,exam_period,status,bulk_accerdited_master.institute_name AS inst_name';
        $this->db->join('bulk_accerdited_master', 'bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code', 'LEFT');
        $this->db->join('exam_master', 'exam_master.exam_code = bulk_payment_transaction.exam_code', 'LEFT');
        $this->db->where('gateway = "1" AND bulk_payment_transaction.id = "' . $id . '"');
        $res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');

        if ($res) {
            $result = $res->result_array();

            if (count($result)) {
                $data['success'] = 'success';
            } else {
                $data['success'] = '';
            }

            $data['result'] = $result;
        }

        $json_res = json_encode($data);
        echo $json_res;
    }

    // function to approve/reject NEFT transaction -
    public function approveNeftTransactions_old20nov2018()
    {
        $data            = array();
        $mem_exam_id_arr = array();
        $sub_arr         = array();
        $arr_cnt         = array();
        $mem_cnt         = array();
        $flag            = 0;

        $utr_no       = $this->input->post('utr_no'); // post parameter
        $id           = $this->input->post('id'); // post parameter
        $mem_count    = $this->input->post('mem_count'); // post parameter
        $payment_amt  = $this->input->post('payment_amt'); // post parameter
        $payment_date = $this->input->post('payment_date'); // post parameter

        /*echo "pawan>>".$payment_date;
        echo "<br/>";
        echo date("Y-m-d H:i:s", strtotime($payment_date)),
        exit;*/

        /*$utr_no = 123458745; // post parameter
        $id = 1; // post parameter
        $mem_count = 5; // post parameter
        $payment_amt = 35005; // post parameter
        $payment_date = date('Y-m-d'); // post parameter
        $action = "Approved";*/

        $updated_date = date('Y-m-d H:i:s');
        $status       = '';

        // Fetch all member_exam_id
        $memexamidlst = $this->master_model->getRecords('bulk_member_payment_transaction', array('ptid' => $id));
        foreach ($memexamidlst as $memexamids) {
            $mem_exam_id_arr[] = $memexamids['memexamid'];
        }
        //fetch all record for which we want to check capacity
        $this->db->where_in('mem_exam_id', $mem_exam_id_arr);
        $member_array = $this->master_model->getRecords('admit_card_details', array('remark' => 2, 'record_source' => 'bulk'));

        //if($action == "Approved"){
        if ($this->input->post('action') == "Approved") {

            $i = 0;
            foreach ($member_array as $member_record) {
                $venue_code  = $member_record['venueid'];
                $exam_date   = $member_record['exam_date'];
                $center_code = $member_record['center_code'];
                $exam_time   = $member_record['time'];
                $sub_code    = $member_record['sub_cd'];
                $type        = 'bulk';
                $capacity    = check_capacity_bulk_approve($venue_code, $exam_date, $exam_time, $center_code);
                //echo $capacity;
                //echo "<br/>";
                if ($capacity != 0) {
                    // capacity available
                    $sub_details = array("exam_code" => $member_record['exm_cd'], "center_code" => $center_code, "venue_code" => $venue_code, "exam_date" => $exam_date, "exam_time" => $exam_time, "mem_mem_no" => $member_record['mem_mem_no'], "admitcard_id" => $member_record['admitcard_id'], "sub_code" => $member_record['sub_cd'], 'exam_period' => $member_record['exm_prd'], 'member_exam_id' => $member_record['mem_exam_id']);
                    $sub_arr[]   = $sub_details;
                    $i++;

                    $update_data = array(
                        'regnumber'   => $member_record['mem_mem_no'],
                        'venue_code'  => $member_record['venueid'],
                        'exam_date'   => $member_record['exam_date'],
                        'center_code' => $member_record['center_code'],
                        'exam_time'   => $member_record['time'],
                        'sub_code'    => $member_record['sub_cd'],
                    );
                    log_bulk_admin($log_title = "Capacity available", $log_message = serialize($update_data));

                } else {
                    // capacity full

                    $sub_arr = array();
                    $flag    = 1;
                    /*echo "Capacity full";
                    echo "<br/>";*/

                    $update_data_one = array(
                        'regnumber'   => $member_record['mem_mem_no'],
                        'venue_code'  => $member_record['venueid'],
                        'exam_date'   => $member_record['exam_date'],
                        'center_code' => $member_record['center_code'],
                        'exam_time'   => $member_record['time'],
                        'sub_code'    => $member_record['sub_cd'],
                        'approve_id'  => $id,
                    );
                    log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                    $data['success'] = 'Capacity not available!!';
                    break;

                }
                if ($flag == 1) {
                    break;
                }

            } // end of member array forloop

            if ($flag == 1) {
                /*echo "<br/>";
                echo "Flag one";
                echo "<br/>";*/
                $reject_status = 0;
                $mem_exam_str  = implode(",", $mem_exam_id_arr);
                $desc          = 'Payment Failed - Rejected by Admin';

                $this->db->query("update member_exam set pay_status = 0 where id IN (" . $mem_exam_str . ")");

                // update bulk payment transaction table
                $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
                $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

                // update exam invoice table
                $update_exam_invoice_reject = array('transaction_no' => '');
                $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

                // update admit card details table
                $this->db->query("update admit_card_details set remark = 4 where mem_exam_id IN (" . $mem_exam_str . ")");

                //insert in log table
                $update_data_one = array(
                    'utr_no'     => $utr_no,
                    'approve_id' => $id,
                );
                log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                $data['success'] = 'Capacity not available!!';
                //break;

            }

            // below code execute if capacity is available for all member in runnig batch and allocate seatnumber
            if (count($member_array) > 0 && $flag == 0 && count($member_array) == count($sub_arr)) {
                $j = 0;
                foreach ($sub_arr as $sub_details) {
                    //$password = random_password();
                    $v_code       = $sub_details['venue_code'];
                    $e_date       = $sub_details['exam_date'];
                    $e_time       = $sub_details['exam_time'];
                    $sub_code     = $sub_details['sub_code'];
                    $exam_code    = $sub_details['exam_code'];
                    $mem_mem_no   = $sub_details['mem_mem_no'];
                    $admitcard_id = $sub_details['admitcard_id'];
                    $exam_period  = $sub_details['exam_period'];
                    $center_code  = $sub_details['center_code'];

                    // get venue details
                    $get_venue_details = $this->master_model->getRecords('venue_master', array('venue_code' => $v_code, 'exam_date' => $e_date, 'session_time' => $e_time, 'center_code' => $center_code));

                    $seat_allocation = getseat_bulk($exam_code, $center_code, $v_code, $e_date, $e_time, $exam_period, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);

                    //$seat_allocation = 2;
                    if ($seat_allocation != '') {
                        // update admit_card_detail table
                        //$update_seatno = array('seat_identification'=>$seat_allocation,'pwd' => $password);
                        $update_seatno = array('seat_identification' => $seat_allocation);
                        $this->master_model->updateRecord('admit_card_details', $update_seatno, array('admitcard_id' => $admitcard_id));
                        $j++;
                        $arr_cnt[] = $sub_details['admitcard_id'];
                        $mem_cnt[] = $sub_details['mem_mem_no'];

                        $update_data_two = array(
                            'regnumber'   => $mem_mem_no,
                            'venue_code'  => $v_code,
                            'exam_date'   => $e_date,
                            'center_code' => $center_code,
                            'exam_time'   => $e_time,
                            'sub_code'    => $sub_code,
                        );
                        log_bulk_admin($log_title = "Seat allocate successfully", $log_message = serialize($update_data_two));

                        //echo "seat allocation done=>".$password." # ".$mem_mem_no;
                        //echo "<br/>";
                    } else {
                        // allocation fail
                        $arr_cnt = array();
                        $mem_cnt = array();
                        //echo "seat no not generated";
                        //echo "<br/>";
                        $data['success']   = 'error while allocating seat!!';
                        $update_data_three = array(
                            'regnumber'   => $mem_mem_no,
                            'venue_code'  => $v_code,
                            'exam_date'   => $e_date,
                            'center_code' => $center_code,
                            'exam_time'   => $e_time,
                            'sub_code'    => $sub_code,
                        );
                        log_bulk_admin($log_title = "Seat not allocate ", $log_message = serialize($update_data_three));
                    }
                } // end of forloop of sub_arr
            } else {

                $update_data_one = array(
                    'approve_id' => $id,
                );
                log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                $data['success'] = 'Capacity not available123!!';

                //echo "capacity not available";
                //echo "<br/>";
            }

            /*exit;*/
            /*echo "<pre>";
            print_r($member_array);
            echo "<br/>";
            print_r($sub_arr);
            echo "<br/>";
            print_r($arr_cnt);
            echo "<br/>";
            print_r($mem_cnt);
            echo "<br/>";
            echo "count of member_array. ".count($member_array);
            echo "<br/>";
            echo "Count of sub_arr     . ".count($sub_arr);
            echo "<br/>";
            echo "Count of arr_arr     . ".count($arr_cnt);
            echo "<br/>";
            echo "Count of mem_cnt     . ".count($mem_cnt);
            echo "<br/>";
            echo "Value of flag        . ".$flag;
            echo "<br/>";
            echo "*****************************";
            echo "<br/>";
            print_r(array_unique($mem_cnt));
            echo "<br/>";
            exit;*/

            // Update required table below
            if (count($sub_arr) == count($member_array) && $flag == 0 && count($member_array) > 0) {
                // update bulk_payment_transaction table
                $data['success'] = 'success';
                $status          = 1;
                $desc            = 'Payment Success - Approved by Admin';
                $update_data     = array(
                    'status'       => $status,
                    'UTR_no'       => $utr_no,
                    'pay_count'    => $mem_count,
                    'amount'       => $payment_amt,
                    'date'         => date("Y-m-d H:i:s", strtotime($payment_date)),
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                );
                $this->master_model->updateRecord('bulk_payment_transaction', $update_data, array('id' => $id));

                $new_mem_regid = array();

                foreach ($sub_arr as $record) {
                    // update member_exam table
                    $exam_period             = $record['exam_period'];
                    $update_member_exam_date = array('pay_status' => $status, 'modified_on' => $updated_date);
                    $this->master_model->updateRecord('member_exam', $update_member_exam_date, array('id' => $record['member_exam_id']));

                    // update admit_card_detail table
                    $update_seatno_remark = array('remark' => 1, 'modified_on' => $updated_date);
                    $this->master_model->updateRecord('admit_card_details', $update_seatno_remark, array('admitcard_id' => $record['admitcard_id']));

                    //Check user is old OR fresh
                    $user_stat = check_user_stat($record['mem_mem_no']);
                    /*$user_stat = 1; // old user
                    $user_stat = 0; // fresh user*/
                    if ($user_stat == 0) {

                        $new_mem_regid[] = $record['mem_mem_no'];
                        $new_password    = $this->generate_random_password();
                        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                        $key = $this->config->item('pass_key');
                        $aes = new CryptAES();
                        $aes->set_key(base64_decode($key));
                        $aes->require_pkcs5();
                        $encPass = $aes->encrypt($new_password);

                        $memregid     = $record['mem_mem_no'];
                        $memregnumber = generate_NM_memreg($memregid);

                        // update member registration table
                        $update_data_member_tbl = array(
                            'regnumber'   => $memregnumber,
                            'usrpassword' => $encPass,
                            'isactive'    => '1',
                            'createdon'   => $updated_date,

                        );
                        $this->master_model->updateRecord('member_registration', $update_data_member_tbl, array('regid' => $memregid));

                        // update member_exam table
                        $update_data_member_exam_tbl = array(
                            'regnumber'   => $memregnumber,
                            'modified_on' => $updated_date,

                        );
                        $this->master_model->updateRecord('member_exam', $update_data_member_exam_tbl, array('regnumber' => $memregid));

                        // update admit card detail table
                        $update_data_admit_card_tbl = array(
                            'mem_mem_no'  => $memregnumber,
                            // 'mem_type'    => 'NM',
                            'modified_on' => $updated_date,

                        );
                        $this->master_model->updateRecord('admit_card_details', $update_data_admit_card_tbl, array('mem_mem_no' => $memregid));

                        $log_update_new_mem = array(
                            'regnumber' => $memregnumber,
                            'regid'     => $memregid,
                        );
                        log_bulk_admin($log_title = "Bulk Reg No. generated successfully after NEFT Approval.", $log_message = serialize($log_update_new_mem));

                        //update uploaded file names which will include generated registration number
                        //get cuurent saved file names from DB
                        $currentpics                = $this->master_model->getRecords('member_registration', array('regid' => $memregid), 'scannedphoto, scannedsignaturephoto, idproofphoto');
                        $scannedphoto_file          = '';
                        $scannedsignaturephoto_file = '';
                        $idproofphoto_file          = '';

                        if (count($currentpics) > 0) {
                            $currentphotos              = $currentpics[0];
                            $scannedphoto_file          = $currentphotos['scannedphoto'];
                            $scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
                            $idproofphoto_file          = $currentphotos['idproofphoto'];

                        }
                        $upd_files  = array();
                        $photo_file = 'p_' . $memregnumber . '.jpg';
                        $sign_file  = 's_' . $memregnumber . '.jpg';
                        $proof_file = 'pr_' . $memregnumber . '.jpg';

                        if (!empty($scannedphoto_file)) {
                            if (@rename("./uploads/photograph/" . $scannedphoto_file, "./uploads/photograph/" . $photo_file)) {
                                $upd_files['scannedphoto'] = $photo_file;
                            }
                        }
                        if (!empty($scannedsignaturephoto_file)) {
                            if (@rename("./uploads/scansignature/" . $scannedsignaturephoto_file, "./uploads/scansignature/" . $sign_file)) {
                                $upd_files['scannedsignaturephoto'] = $sign_file;
                            }
                        }
                        if (!empty($idproofphoto_file)) {
                            if (@rename("./uploads/idproof/" . $idproofphoto_file, "./uploads/idproof/" . $proof_file)) {
                                $upd_files['idproofphoto'] = $proof_file;
                            }
                        }

                        if (count($upd_files) > 0) {
                            log_bulk_admin($log_title = "Bulk Member Images Updated successfully after NEFT Approval.", $log_message = serialize($upd_files));

                            $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $memregid));
                        }
                    }

                } // end of forloop

                // Generate admitcard pdf call
                $this->db->group_by('mem_mem_no,exm_cd');
                $this->db->where_in('mem_exam_id', $mem_exam_id_arr);
                $member_array_admitcard = $this->master_model->getRecords('admit_card_details', array('remark' => '1', 'record_source' => 'bulk'), 'mem_mem_no,exm_cd,exm_prd');
                foreach ($member_array_admitcard as $member_array_record) {
                    $attchpath_admitcard = genarate_admitcard_bulk($member_array_record['mem_mem_no'], $member_array_record['exm_cd'], $member_array_record['exm_prd']);
                    //echo ">>". $attchpath_admitcard;
                    //echo "<br/>";

                    // Send email template of admitcard pdf to user
                    if ($attchpath_admitcard != '') {

                        $member_info_new = $this->master_model->getRecords('member_registration', array('regnumber' => $member_array_record['mem_mem_no']));

                        //Query to get exam details
                        $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period', 'left');
                        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                        $exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $member_array_record['mem_mem_no'], 'member_exam.exam_period' => $member_array_record['exm_prd']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

                        $username         = $member_info_new[0]['firstname'] . ' ' . $member_info_new[0]['middlename'] . ' ' . $member_info_new[0]['lastname'];
                        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

                        $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                        $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);

                        //Query to get Medium
                        $this->db->where('exam_code', $member_array_record['exm_cd']);
                        $this->db->where('exam_period', $exam_info[0]['exam_period']);
                        $this->db->where('medium_code', $exam_info[0]['exam_medium']);
                        $this->db->where('medium_delete', '0');
                        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

                        if ($exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} elseif ($exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';}

                        if (in_array($member_info_new[0]['regid'], $new_mem_regid)) {
                            // email for new member

                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            $decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));

                            $emailerstr  = $this->master_model->getRecords('emailer', array('emailer_name' => 'new_non_member_bulk'));
                            $newstring1  = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                            $newstring2  = str_replace("#REG_NUM#", "" . $member_array_record['mem_mem_no'] . "", $newstring1);
                            $newstring3  = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                            $newstring4  = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                            $newstring6  = str_replace("#ADD1#", "" . $member_info_new[0]['address1'] . "", $newstring4);
                            $newstring7  = str_replace("#ADD2#", "" . $member_info_new[0]['address2'] . "", $newstring6);
                            $newstring8  = str_replace("#ADD3#", "" . $member_info_new[0]['address3'] . "", $newstring7);
                            $newstring9  = str_replace("#ADD4#", "" . $member_info_new[0]['address4'] . "", $newstring8);
                            $newstring10 = str_replace("#DISTRICT#", "" . $member_info_new[0]['district'] . "", $newstring9);
                            $newstring11 = str_replace("#CITY#", "" . $member_info_new[0]['city'] . "", $newstring10);
                            $newstring12 = str_replace("#STATE#", "" . $member_info_new[0]['state_name'] . "", $newstring11);
                            $newstring13 = str_replace("#PINCODE#", "" . $member_info_new[0]['pincode'] . "", $newstring12);
                            $newstring14 = str_replace("#EMAIL#", "" . $member_info_new[0]['email'] . "", $newstring13);
                            $newstring15 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
                            $newstring16 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
                            $newstring17 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
                            $newstring18 = str_replace("#MODE#", "" . $mode . "", $newstring17);
                            //$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
                            $newstring20 = str_replace("#PASS#", "" . $decpass . "", $newstring18);
                            //$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
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
                            $final_str_pdf = $newstring21;

                            //$final_str_pdf = "This is mail for new member";

                            $files_pdf    = array($attchpath_admitcard);
                            if(in_array($exam_info[0]['exam_code'],$this->config->item('skippedAdmitCardForExams'))) {
                                $files_pdf = array();
                                
                            }
                            $info_arr_pdf = array('to' => $member_info_new[0]['email'],
                                //'to'=>'esdstesting12@gmail.com',
                                //'to'=>'ztest2500@gmail.com',
                                'from'                     => 'noreply@iibf.org.in',
                                'subject'                  => 'Bulk Exam application',
                                'message'                  => $final_str_pdf,
                            );
                            $this->Emailsending->mailsend_attch($info_arr_pdf, $files_pdf);
                        } elseif (!in_array($member_info_new[0]['regid'], $new_mem_regid)) {
                            // email for old member

                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            $decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));

                            $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'old_non_member_bulk'));
                            $newstring1 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                            $newstring2 = str_replace("#REG_NUM#", "" . $member_array_record['mem_mem_no'] . "", $newstring1);
                            $newstring3 = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                            $newstring4 = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                            //$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
                            $newstring6  = str_replace("#ADD1#", "" . $member_info_new[0]['address1'] . "", $newstring4);
                            $newstring7  = str_replace("#ADD2#", "" . $member_info_new[0]['address2'] . "", $newstring6);
                            $newstring8  = str_replace("#ADD3#", "" . $member_info_new[0]['address3'] . "", $newstring7);
                            $newstring9  = str_replace("#ADD4#", "" . $member_info_new[0]['address4'] . "", $newstring8);
                            $newstring10 = str_replace("#DISTRICT#", "" . $member_info_new[0]['district'] . "", $newstring9);
                            $newstring11 = str_replace("#CITY#", "" . $member_info_new[0]['city'] . "", $newstring10);
                            $newstring12 = str_replace("#STATE#", "" . $member_info_new[0]['state_name'] . "", $newstring11);
                            $newstring13 = str_replace("#PINCODE#", "" . $member_info_new[0]['pincode'] . "", $newstring12);
                            $newstring14 = str_replace("#EMAIL#", "" . $member_info_new[0]['email'] . "", $newstring13);
                            $newstring15 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
                            $newstring16 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
                            $newstring17 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
                            $newstring18 = str_replace("#MODE#", "" . $mode . "", $newstring17);
                            //$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
                            $newstring20 = str_replace("#PASS#", "" . $decpass . "", $newstring18);
                            //$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
                            //$final_str_pdf = $newstring20;

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
                            $final_str_pdf = $newstring21;
                            //$final_str_pdf = "This is mail for old member";

                            $files_pdf    = array($attchpath_admitcard);
                            $info_arr_pdf = array('to' => $member_info_new[0]['email'],
                                //'to'=>'esdstesting12@gmail.com',
                                //'to'=>'ztest2500@gmail.com',
                                'from'                     => 'noreply@iibf.org.in',
                                'subject'                  => 'Bulk Exam application1',
                                'message'                  => $final_str_pdf,
                            );
                            $this->Emailsending->mailsend_attch($info_arr_pdf, $files_pdf);
                        }

                    }
                }

                // Generate exam invoice call
                $getinvoice_id = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $id), 'invoice_id');
                $invoiceNumber = bulk_generate_exam_invoice_number($getinvoice_id[0]['invoice_id']);
                if ($invoiceNumber) {
                    $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                } else {
                    $invoiceNumber = '';
                }

                $update_data_invoice = array('invoice_no' => $invoiceNumber, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $utr_no);
                $this->db->where('pay_txn_id', $id);
                $this->master_model->updateRecord('exam_invoice', $update_data_invoice, array('receipt_no' => $id));

                $attchpath_examinvoice = generate_bulk_examinvoice($id);
                //echo "##". $attchpath_examinvoice;
                //echo "<br/>";

                $this->db->join('bulk_payment_transaction', 'bulk_payment_transaction.inst_code = bulk_accerdited_master.institute_code');
                $this->db->where('bulk_payment_transaction.id', $id);
                $bank_info = $this->master_model->getRecords('bulk_accerdited_master', '', 'email');

                // Send email template of exam invoice to bank
                if ($attchpath_examinvoice != '') {
                    $final_str_invoice = "Please check attach invoice";
                    $files_invoice     = array($attchpath_examinvoice);
                    $info_arr_invoice  = array('to' => $bank_info[0]['email'],
                        //'to'=>'esdstesting12@gmail.com',
                        'from'                          => 'noreply@iibf.org.in',
                        'subject'                       => 'Bulk Exam application2',
                        'message'                       => $final_str_invoice,
                    );
                    $this->Emailsending->mailsend_attch($info_arr_invoice, $files_invoice);
                }

            } // end of if

        } elseif ($this->input->post('action') == "Rejected") {
            // update member exam table.
            $reject_status = 0;
            $mem_exam_str  = implode(",", $mem_exam_id_arr);
            $desc          = 'Payment Failed - Rejected by Admin';
            $this->db->query("update member_exam set pay_status = 0, modified_on = '" . $updated_date . "' where id IN (" . $mem_exam_str . ")");

            // update bulk payment transaction table
            $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
            $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

            // update exam invoice table
            $update_exam_invoice_reject = array('transaction_no' => '', 'modified_on' => $updated_date);
            $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

            // update admit card details table
            $this->db->query("update admit_card_details set remark = 4, modified_on = '" . $updated_date . "' where mem_exam_id IN (" . $mem_exam_str . ")");

            //insert in log table

            $update_data_log = array(
                'status'       => $reject_status,
                'description'  => $desc,
                'updated_date' => $updated_date,
                'approve_id'   => $id,
            );

            log_bulk_admin($log_title = "Bulk Admin NEFT Rejected Successfully", $log_message = serialize($update_data_log));

            $data['success'] = 'Bulk Admin NEFT Rejected Successfully';

        }

        $json_res = json_encode($data);
        echo $json_res;

    } // end of function

    // function to approve/reject NEFT transaction -
    public function approveNeftTransactions_18jun2019()
    {
        $data            = array();
        $mem_exam_id_arr = array();
        $sub_arr         = array();
        $arr_cnt         = array();
        $mem_cnt         = array();
        $flag            = 0;
        $chk_exm_cd_arr  = array();

        $utr_no       = $this->input->post('utr_no'); // post parameter
        $id           = $this->input->post('id'); // post parameter
        $mem_count    = $this->input->post('mem_count'); // post parameter
        $payment_amt  = $this->input->post('payment_amt'); // post parameter
        $payment_date = $this->input->post('payment_date'); // post parameter

        /*$utr_no = 123458745; // post parameter
        $id = 1; // post parameter
        $mem_count = 5; // post parameter
        $payment_amt = 35005; // post parameter
        $payment_date = date('Y-m-d'); // post parameter
        $action = "Approved";*/

        $updated_date = date('Y-m-d H:i:s');
        $status       = '';

        // Fetch all member_exam_id
        $memexamidlst = $this->master_model->getRecords('bulk_member_payment_transaction', array('ptid' => $id));
        foreach ($memexamidlst as $memexamids) {
            $mem_exam_id_arr[] = $memexamids['memexamid'];
        }
        //fetch all record for which we want to check capacity
        $this->db->where_in('mem_exam_id', $mem_exam_id_arr);
        $member_array = $this->master_model->getRecords('admit_card_details', array('remark' => 2, 'record_source' => 'bulk'));

        // Query to get exam code
        $this->db->where_in('id', $mem_exam_id_arr);
        $exam_code = $this->master_model->getRecords('member_exam', '', 'exam_code');
        foreach ($exam_code as $exam_code) {
            $chk_exm_cd_arr[] = $exam_code['exam_code'];
        }
        $unique_exm_cd = array_unique($chk_exm_cd_arr);
        $chk_exm_cd    = $unique_exm_cd[0];
        //echo ">>".$chk_exm_cd;
        //exit;
        if (($chk_exm_cd == 101
            || $chk_exm_cd == 1010
            || $chk_exm_cd == 10100
            || $chk_exm_cd == 101000
            || $chk_exm_cd == 1010000
            || $chk_exm_cd == 10100000
            || $chk_exm_cd == 996) && sizeof($unique_exm_cd) == 1) {

            if ($this->input->post('action') == "Approved") {

                // update bulk_payment_transaction table
                $data['success'] = 'success';
                $status          = 1;
                $desc            = 'Payment Success - Approved by Admin';
                $update_data     = array(
                    'status'       => $status,
                    'UTR_no'       => $utr_no,
                    'pay_count'    => $mem_count,
                    'amount'       => $payment_amt,
                    'date'         => date("Y-m-d H:i:s", strtotime($payment_date)),
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                );
                $this->master_model->updateRecord('bulk_payment_transaction', $update_data, array('id' => $id));

                // update member_exam table
                //$exam_period = $record['exam_period'];
                $update_member_exam_date = array('pay_status' => $status, 'modified_on' => $updated_date);

                for ($i = 0; $i < sizeof($mem_exam_id_arr); $i++) {
                    $this->master_model->updateRecord('member_exam', $update_member_exam_date, array('id' => $mem_exam_id_arr[$i]));
                    $member_exam = $this->master_model->getRecords('member_exam', array('id' => $mem_exam_id_arr[$i]), 'regnumber');
                    foreach ($member_exam as $member_exam_rec) {
                        $user_stat = check_user_stat($member_exam_rec['regnumber']);
                        /*$user_stat = 1; // old user
                        $user_stat = 0; // fresh user*/
                        if ($user_stat == 0) {
                            $new_mem_regid[] = $member_exam_rec['regnumber'];
                            $new_password    = $this->generate_random_password();
                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            $encPass = $aes->encrypt($new_password);

                            $memregid     = $member_exam_rec['regnumber'];
                            $memregnumber = generate_NM_memreg($memregid);

                            // update member registration table
                            $update_data_member_tbl = array(
                                'regnumber'   => $memregnumber,
                                'usrpassword' => $encPass,
                                'isactive'    => '1',
                                'createdon'   => $updated_date,
                            );
                            $this->master_model->updateRecord('member_registration', $update_data_member_tbl, array('regid' => $memregid));

                            // update member_exam table
                            $update_data_member_exam_tbl = array(
                                'regnumber'   => $memregnumber,
                                'modified_on' => $updated_date,
                            );
                            $this->master_model->updateRecord('member_exam', $update_data_member_exam_tbl, array('regnumber' => $memregid));

                            $log_update_new_mem = array(
                                'regnumber' => $memregnumber,
                                'regid'     => $memregid,
                            );
                            log_bulk_admin($log_title = "Bulk Reg No. generated successfully after NEFT Approval.", $log_message = serialize($log_update_new_mem));

                            //update uploaded file names which will include generated registration number
                            //get cuurent saved file names from DB
                            $currentpics                = $this->master_model->getRecords('member_registration', array('regid' => $memregid), 'scannedphoto, scannedsignaturephoto, idproofphoto');
                            $scannedphoto_file          = '';
                            $scannedsignaturephoto_file = '';
                            $idproofphoto_file          = '';

                            if (count($currentpics) > 0) {
                                $currentphotos              = $currentpics[0];
                                $scannedphoto_file          = $currentphotos['scannedphoto'];
                                $scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
                                $idproofphoto_file          = $currentphotos['idproofphoto'];

                            }
                            $upd_files  = array();
                            $photo_file = 'p_' . $memregnumber . '.jpg';
                            $sign_file  = 's_' . $memregnumber . '.jpg';
                            $proof_file = 'pr_' . $memregnumber . '.jpg';

                            if (!empty($scannedphoto_file)) {
                                if (@rename("./uploads/photograph/" . $scannedphoto_file, "./uploads/photograph/" . $photo_file)) {
                                    $upd_files['scannedphoto'] = $photo_file;
                                }
                            }
                            if (!empty($scannedsignaturephoto_file)) {
                                if (@rename("./uploads/scansignature/" . $scannedsignaturephoto_file, "./uploads/scansignature/" . $sign_file)) {
                                    $upd_files['scannedsignaturephoto'] = $sign_file;
                                }
                            }
                            if (!empty($idproofphoto_file)) {
                                if (@rename("./uploads/idproof/" . $idproofphoto_file, "./uploads/idproof/" . $proof_file)) {
                                    $upd_files['idproofphoto'] = $proof_file;
                                }
                            }

                            if (count($upd_files) > 0) {
                                log_bulk_admin($log_title = "Bulk Member Images Updated successfully after NEFT Approval.", $log_message = serialize($upd_files));

                                $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $memregid));
                            }
                        }
                    }

                }

                // Generate exam invoice call
                $getinvoice_id = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $id), 'invoice_id');
                $invoiceNumber = bulk_generate_exam_invoice_number($getinvoice_id[0]['invoice_id']);
                if ($invoiceNumber) {
                    $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                } else {
                    $invoiceNumber = '';
                }

                $update_data_invoice = array('invoice_no' => $invoiceNumber, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $utr_no);
                $this->db->where('pay_txn_id', $id);
                $this->master_model->updateRecord('exam_invoice', $update_data_invoice, array('receipt_no' => $id));

                $attchpath_examinvoice = generate_bulk_examinvoice($id);

                $this->db->join('bulk_payment_transaction', 'bulk_payment_transaction.inst_code = bulk_accerdited_master.institute_code');
                $this->db->where('bulk_payment_transaction.id', $id);
                $bank_info = $this->master_model->getRecords('bulk_accerdited_master', '', 'email');

                // Send email template of exam invoice to bank
                if ($attchpath_examinvoice != '') {
                    $final_str_invoice = "Please check attach invoice";
                    $files_invoice     = array($attchpath_examinvoice);
                    $info_arr_invoice  = array( //'to'=>$bank_info[0]['email'],
                        'to'      => 'pawansing.pardeshi@esds.co.in',
                        'from'    => 'noreply@iibf.org.in',
                        'subject' => 'Bulk Exam application2',
                        'message' => $final_str_invoice,
                    );
                    $this->Emailsending->mailsend_attch($info_arr_invoice, $files_invoice);

                }

                $update_data_log = array(
                    'status'       => $status,
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                    'approve_id'   => $id,
                );

                log_bulk_admin($log_title = "Bulk Admin NEFT For BCBF approved Successfully", $log_message = serialize($update_data_log));

                //$data['success'] = 'Bulk Admin NEFT approved Successfully';

            } elseif ($this->input->post('action') == "Rejected") {
                // update member exam table.
                $reject_status = 0;
                $mem_exam_str  = implode(",", $mem_exam_id_arr);
                $desc          = 'Payment Failed - Rejected by Admin';
                $this->db->query("update member_exam set pay_status = 0, modified_on = '" . $updated_date . "' where id IN (" . $mem_exam_str . ")");

                // update bulk payment transaction table
                $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
                $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

                // update exam invoice table
                $update_exam_invoice_reject = array('transaction_no' => '', 'modified_on' => $updated_date);
                $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

                //insert in log table
                $update_data_log = array(
                    'status'       => $reject_status,
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                    'approve_id'   => $id,
                );

                log_bulk_admin($log_title = "Bulk Admin NEFT For BCBF Rejected Successfully", $log_message = serialize($update_data_log));

                $data['success'] = 'Bulk Admin NEFT Rejected Successfully';

            }

            $json_res = json_encode($data);
            echo $json_res;

        } elseif (($chk_exm_cd != 101
            || $chk_exm_cd != 1010
            || $chk_exm_cd != 10100
            || $chk_exm_cd != 101000
            || $chk_exm_cd != 1010000
            || $chk_exm_cd != 10100000) && sizeof($unique_exm_cd) == 1) {

            //if($action == "Approved"){
            if ($this->input->post('action') == "Approved") {

                $i = 0;
                foreach ($member_array as $member_record) {
                    $venue_code  = $member_record['venueid'];
                    $exam_date   = $member_record['exam_date'];
                    $center_code = $member_record['center_code'];
                    $exam_time   = $member_record['time'];
                    $sub_code    = $member_record['sub_cd'];
                    $type        = 'bulk';
                    $capacity    = check_capacity_bulk_approve($venue_code, $exam_date, $exam_time, $center_code);
                    //echo $capacity;
                    //echo "<br/>";
                    if ($capacity != 0) {
                        // capacity available
                        $sub_details = array("exam_code" => $member_record['exm_cd'], "center_code" => $center_code, "venue_code" => $venue_code, "exam_date" => $exam_date, "exam_time" => $exam_time, "mem_mem_no" => $member_record['mem_mem_no'], "admitcard_id" => $member_record['admitcard_id'], "sub_code" => $member_record['sub_cd'], 'exam_period' => $member_record['exm_prd'], 'member_exam_id' => $member_record['mem_exam_id'], 'mem_type' => $member_record['mem_type']);
                        $sub_arr[]   = $sub_details;
                        $i++;

                        $update_data = array(
                            'regnumber'   => $member_record['mem_mem_no'],
                            'venue_code'  => $member_record['venueid'],
                            'exam_date'   => $member_record['exam_date'],
                            'center_code' => $member_record['center_code'],
                            'exam_time'   => $member_record['time'],
                            'sub_code'    => $member_record['sub_cd'],
                        );
                        log_bulk_admin($log_title = "Capacity available", $log_message = serialize($update_data));

                    } else {
                        // capacity full
                        $sub_arr = array();
                        $flag    = 1;
                        /*echo "Capacity full";
                        echo "<br/>";*/

                        $update_data_one = array(
                            'regnumber'   => $member_record['mem_mem_no'],
                            'venue_code'  => $member_record['venueid'],
                            'exam_date'   => $member_record['exam_date'],
                            'center_code' => $member_record['center_code'],
                            'exam_time'   => $member_record['time'],
                            'sub_code'    => $member_record['sub_cd'],
                            'approve_id'  => $id,
                        );
                        log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                        $data['success'] = 'error1';
                        break;

                    }
                    if ($flag == 1) {
                        break;
                    }

                } // end of member array forloop

                if ($flag == 1) {
                    /*echo "<br/>";
                    echo "Flag one";
                    echo "<br/>";*/
                    $reject_status = 0;
                    $mem_exam_str  = implode(",", $mem_exam_id_arr);
                    $desc          = 'Payment Failed - Rejected by Admin';

                    $this->db->query("update member_exam set pay_status = 0 where id IN (" . $mem_exam_str . ")");

                    // update bulk payment transaction table
                    $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
                    $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

                    // update exam invoice table
                    $update_exam_invoice_reject = array('transaction_no' => '');
                    $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

                    // update admit card details table
                    $this->db->query("update admit_card_details set remark = 4 where mem_exam_id IN (" . $mem_exam_str . ")");

                    //insert in log table
                    $update_data_one = array(
                        'utr_no'     => $utr_no,
                        'approve_id' => $id,
                    );
                    log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                    $data['success'] = 'error2';
                    //break;

                }

                // below code execute if capacity is available for all member in runnig batch and allocate seatnumber
                if (count($member_array) > 0 && $flag == 0 && count($member_array) == count($sub_arr)) {
                    $j = 0;
                    foreach ($sub_arr as $sub_details) {
                        //$password = random_password();
                        $v_code       = $sub_details['venue_code'];
                        $e_date       = $sub_details['exam_date'];
                        $e_time       = $sub_details['exam_time'];
                        $sub_code     = $sub_details['sub_code'];
                        $exam_code    = $sub_details['exam_code'];
                        $mem_mem_no   = $sub_details['mem_mem_no'];
                        $admitcard_id = $sub_details['admitcard_id'];
                        $exam_period  = $sub_details['exam_period'];
                        $center_code  = $sub_details['center_code'];

                        // get venue details
                        $get_venue_details = $this->master_model->getRecords('venue_master', array('venue_code' => $v_code, 'exam_date' => $e_date, 'session_time' => $e_time, 'center_code' => $center_code));

                        $seat_allocation = getseat_bulk($exam_code, $center_code, $v_code, $e_date, $e_time, $exam_period, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);

                        //$seat_allocation = 2;
                        if ($seat_allocation != '') {
                            // update admit_card_detail table
                            //$update_seatno = array('seat_identification'=>$seat_allocation,'pwd' => $password);
                            $update_seatno = array('seat_identification' => $seat_allocation);
                            $this->master_model->updateRecord('admit_card_details', $update_seatno, array('admitcard_id' => $admitcard_id));
                            $j++;
                            $arr_cnt[] = $sub_details['admitcard_id'];
                            $mem_cnt[] = $sub_details['mem_mem_no'];

                            $update_data_two = array(
                                'regnumber'   => $mem_mem_no,
                                'venue_code'  => $v_code,
                                'exam_date'   => $e_date,
                                'center_code' => $center_code,
                                'exam_time'   => $e_time,
                                'sub_code'    => $sub_code,
                            );
                            log_bulk_admin($log_title = "Seat allocate successfully", $log_message = serialize($update_data_two));

                            //echo "seat allocation done=>".$password." # ".$mem_mem_no;
                            //echo "<br/>";
                        } else {
                            // allocation fail
                            $arr_cnt = array();
                            $mem_cnt = array();
                            //echo "seat no not generated";
                            //echo "<br/>";
                            $data['success']   = 'error3';
                            $update_data_three = array(
                                'regnumber'   => $mem_mem_no,
                                'venue_code'  => $v_code,
                                'exam_date'   => $e_date,
                                'center_code' => $center_code,
                                'exam_time'   => $e_time,
                                'sub_code'    => $sub_code,
                            );
                            log_bulk_admin($log_title = "Seat not allocate ", $log_message = serialize($update_data_three));
                        }
                    } // end of forloop of sub_arr
                } else {

                    $update_data_one = array(
                        'approve_id' => $id,
                    );
                    log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                    $data['success'] = 'error4';

                    //echo "capacity not available";
                    //echo "<br/>";
                }

                /*exit;*/
                /*echo "<pre>";
                print_r($member_array);
                echo "<br/>";
                print_r($sub_arr);
                echo "<br/>";
                print_r($arr_cnt);
                echo "<br/>";
                print_r($mem_cnt);
                echo "<br/>";
                echo "count of member_array. ".count($member_array);
                echo "<br/>";
                echo "Count of sub_arr     . ".count($sub_arr);
                echo "<br/>";
                echo "Count of arr_arr     . ".count($arr_cnt);
                echo "<br/>";
                echo "Count of mem_cnt     . ".count($mem_cnt);
                echo "<br/>";
                echo "Value of flag        . ".$flag;
                echo "<br/>";
                echo "*****************************";
                echo "<br/>";
                print_r(array_unique($mem_cnt));
                echo "<br/>";
                exit;*/

                // Update required table below
                if (count($sub_arr) == count($member_array) && $flag == 0 && count($member_array) > 0) {
                    // update bulk_payment_transaction table
                    $data['success'] = 'success';
                    $status          = 1;
                    $desc            = 'Payment Success - Approved by Admin';
                    $update_data     = array(
                        'status'       => $status,
                        'UTR_no'       => $utr_no,
                        'pay_count'    => $mem_count,
                        'amount'       => $payment_amt,
                        'date'         => date("Y-m-d H:i:s", strtotime($payment_date)),
                        'description'  => $desc,
                        'updated_date' => $updated_date,
                    );
                    $this->master_model->updateRecord('bulk_payment_transaction', $update_data, array('id' => $id));

                    $new_mem_regid = array();

                    foreach ($sub_arr as $record) {
                        // update member_exam table
                        $exam_period             = $record['exam_period'];
                        $update_member_exam_date = array('pay_status' => $status, 'modified_on' => $updated_date);
                        $this->master_model->updateRecord('member_exam', $update_member_exam_date, array('id' => $record['member_exam_id']));

                        // update admit_card_detail table
                        $update_seatno_remark = array('remark' => 1, 'modified_on' => $updated_date);
                        $this->master_model->updateRecord('admit_card_details', $update_seatno_remark, array('admitcard_id' => $record['admitcard_id']));

                        //Check user is old OR fresh
                        $user_stat = check_user_stat($record['mem_mem_no']);
                        /*$user_stat = 1; // old user
                        $user_stat = 0; // fresh user*/
                        if ($user_stat == 0) {

                            $new_mem_regid[] = $record['mem_mem_no'];
                            $new_password    = $this->generate_random_password();
                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            $encPass = $aes->encrypt($new_password);

                            $memregid = $record['mem_mem_no'];

                            if ($record['mem_mem_no'] == '42' || $record['mem_type'] == 'DB') {
                                $memregnumber = generate_DBF_memreg($memregid);
                            } else {
                                $memregnumber = generate_NM_memreg($memregid);
                            }
                            // update member registration table
                            $update_data_member_tbl = array(
                                'regnumber'   => $memregnumber,
                                'usrpassword' => $encPass,
                                'isactive'    => '1',
                                'createdon'   => $updated_date,

                            );
                            $this->master_model->updateRecord('member_registration', $update_data_member_tbl, array('regid' => $memregid));

                            // update member_exam table
                            $update_data_member_exam_tbl = array(
                                'regnumber'   => $memregnumber,
                                'modified_on' => $updated_date,

                            );
                            $this->master_model->updateRecord('member_exam', $update_data_member_exam_tbl, array('regnumber' => $memregid));

                            // update admit card detail table
                            $update_data_admit_card_tbl = array(
                                'mem_mem_no'  => $memregnumber,
                                // 'mem_type'    => 'NM',
                                'modified_on' => $updated_date,

                            );
                            $this->master_model->updateRecord('admit_card_details', $update_data_admit_card_tbl, array('mem_mem_no' => $memregid));

                            $log_update_new_mem = array(
                                'regnumber' => $memregnumber,
                                'regid'     => $memregid,
                            );
                            log_bulk_admin($log_title = "Bulk Reg No. generated successfully after NEFT Approval.", $log_message = serialize($log_update_new_mem));

                            //update uploaded file names which will include generated registration number
                            //get cuurent saved file names from DB
                            $currentpics                = $this->master_model->getRecords('member_registration', array('regid' => $memregid), 'scannedphoto, scannedsignaturephoto, idproofphoto');
                            $scannedphoto_file          = '';
                            $scannedsignaturephoto_file = '';
                            $idproofphoto_file          = '';

                            if (count($currentpics) > 0) {
                                $currentphotos              = $currentpics[0];
                                $scannedphoto_file          = $currentphotos['scannedphoto'];
                                $scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
                                $idproofphoto_file          = $currentphotos['idproofphoto'];

                            }
                            $upd_files  = array();
                            $photo_file = 'p_' . $memregnumber . '.jpg';
                            $sign_file  = 's_' . $memregnumber . '.jpg';
                            $proof_file = 'pr_' . $memregnumber . '.jpg';

                            if (!empty($scannedphoto_file)) {
                                if (@rename("./uploads/photograph/" . $scannedphoto_file, "./uploads/photograph/" . $photo_file)) {
                                    $upd_files['scannedphoto'] = $photo_file;
                                }
                            }
                            if (!empty($scannedsignaturephoto_file)) {
                                if (@rename("./uploads/scansignature/" . $scannedsignaturephoto_file, "./uploads/scansignature/" . $sign_file)) {
                                    $upd_files['scannedsignaturephoto'] = $sign_file;
                                }
                            }
                            if (!empty($idproofphoto_file)) {
                                if (@rename("./uploads/idproof/" . $idproofphoto_file, "./uploads/idproof/" . $proof_file)) {
                                    $upd_files['idproofphoto'] = $proof_file;
                                }
                            }

                            if (count($upd_files) > 0) {
                                log_bulk_admin($log_title = "Bulk Member Images Updated successfully after NEFT Approval.", $log_message = serialize($upd_files));

                                $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $memregid));
                            }
                        }

                    } // end of forloop

                    // Generate admitcard pdf call
                    $this->db->group_by('mem_mem_no,exm_cd');
                    $this->db->where_in('mem_exam_id', $mem_exam_id_arr);
                    $member_array_admitcard = $this->master_model->getRecords('admit_card_details', array('remark' => '1', 'record_source' => 'bulk'), 'mem_mem_no,exm_cd,exm_prd');
                    foreach ($member_array_admitcard as $member_array_record) {
                        $attchpath_admitcard = genarate_admitcard_bulk($member_array_record['mem_mem_no'], $member_array_record['exm_cd'], $member_array_record['exm_prd']);
                        //echo ">>". $attchpath_admitcard;
                        //echo "<br/>";

                        // Send email template of admitcard pdf to user
                        if ($attchpath_admitcard != '') {

                            $member_info_new = $this->master_model->getRecords('member_registration', array('regnumber' => $member_array_record['mem_mem_no']));

                            //Query to get exam details
                            $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period', 'left');
                            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                            $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                            $exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $member_array_record['mem_mem_no']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

                            $username         = $member_info_new[0]['firstname'] . ' ' . $member_info_new[0]['middlename'] . ' ' . $member_info_new[0]['lastname'];
                            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

                            $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                            $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);

                            //Query to get Medium
                            $this->db->where('exam_code', $member_array_record['exm_cd']);
                            $this->db->where('exam_period', $exam_info[0]['exam_period']);
                            $this->db->where('medium_code', $exam_info[0]['exam_medium']);
                            $this->db->where('medium_delete', '0');
                            $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

                            if ($exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} elseif ($exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';}

                            if (in_array($member_info_new[0]['regid'], $new_mem_regid)) {
                                // email for new member

                                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                                $key = $this->config->item('pass_key');
                                $aes = new CryptAES();
                                $aes->set_key(base64_decode($key));
                                $aes->require_pkcs5();
                                $decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));

                                $emailerstr  = $this->master_model->getRecords('emailer', array('emailer_name' => 'new_non_member_bulk'));
                                $newstring1  = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                                $newstring2  = str_replace("#REG_NUM#", "" . $member_array_record['mem_mem_no'] . "", $newstring1);
                                $newstring3  = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                                $newstring4  = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                                $newstring6  = str_replace("#ADD1#", "" . $member_info_new[0]['address1'] . "", $newstring4);
                                $newstring7  = str_replace("#ADD2#", "" . $member_info_new[0]['address2'] . "", $newstring6);
                                $newstring8  = str_replace("#ADD3#", "" . $member_info_new[0]['address3'] . "", $newstring7);
                                $newstring9  = str_replace("#ADD4#", "" . $member_info_new[0]['address4'] . "", $newstring8);
                                $newstring10 = str_replace("#DISTRICT#", "" . $member_info_new[0]['district'] . "", $newstring9);
                                $newstring11 = str_replace("#CITY#", "" . $member_info_new[0]['city'] . "", $newstring10);
                                $newstring12 = str_replace("#STATE#", "" . $member_info_new[0]['state_name'] . "", $newstring11);
                                $newstring13 = str_replace("#PINCODE#", "" . $member_info_new[0]['pincode'] . "", $newstring12);
                                $newstring14 = str_replace("#EMAIL#", "" . $member_info_new[0]['email'] . "", $newstring13);
                                $newstring15 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
                                $newstring16 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
                                $newstring17 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
                                $newstring18 = str_replace("#MODE#", "" . $mode . "", $newstring17);
                                //$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
                                $newstring20 = str_replace("#PASS#", "" . $decpass . "", $newstring18);
                                //$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
                                #-----------------------------------------E-learning msg ---------------------------------------------------------#
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

                                $final_str_pdf = $newstring21;
                                #-----------------------------------------E-learning msg end ----------------------------------------------------------#
                                //$final_str_pdf = "This is mail for new member";

                                $files_pdf    = array($attchpath_admitcard);
                                $info_arr_pdf = array( //'to'=>$member_info_new[0]['email'],
                                    'to'      => 'pawansing.pardeshi@esds.co.in',
                                    'from'    => 'noreply@iibf.org.in',
                                    'subject' => 'Bulk Exam application',
                                    'message' => $final_str_pdf,
                                );
                                $this->Emailsending->mailsend_attch($info_arr_pdf, $files_pdf);
                            } elseif (!in_array($member_info_new[0]['regid'], $new_mem_regid)) {
                                // email for old member

                                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                                $key = $this->config->item('pass_key');
                                $aes = new CryptAES();
                                $aes->set_key(base64_decode($key));
                                $aes->require_pkcs5();
                                $decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));

                                $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'old_non_member_bulk'));
                                $newstring1 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                                $newstring2 = str_replace("#REG_NUM#", "" . $member_array_record['mem_mem_no'] . "", $newstring1);
                                $newstring3 = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                                $newstring4 = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                                //$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
                                $newstring6  = str_replace("#ADD1#", "" . $member_info_new[0]['address1'] . "", $newstring4);
                                $newstring7  = str_replace("#ADD2#", "" . $member_info_new[0]['address2'] . "", $newstring6);
                                $newstring8  = str_replace("#ADD3#", "" . $member_info_new[0]['address3'] . "", $newstring7);
                                $newstring9  = str_replace("#ADD4#", "" . $member_info_new[0]['address4'] . "", $newstring8);
                                $newstring10 = str_replace("#DISTRICT#", "" . $member_info_new[0]['district'] . "", $newstring9);
                                $newstring11 = str_replace("#CITY#", "" . $member_info_new[0]['city'] . "", $newstring10);
                                $newstring12 = str_replace("#STATE#", "" . $member_info_new[0]['state_name'] . "", $newstring11);
                                $newstring13 = str_replace("#PINCODE#", "" . $member_info_new[0]['pincode'] . "", $newstring12);
                                $newstring14 = str_replace("#EMAIL#", "" . $member_info_new[0]['email'] . "", $newstring13);
                                $newstring15 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
                                $newstring16 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
                                $newstring17 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
                                $newstring18 = str_replace("#MODE#", "" . $mode . "", $newstring17);
                                //$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
                                $newstring20 = str_replace("#PASS#", "" . $decpass . "", $newstring18);
                                //$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);

                                #-----------------------------------------E-learning msg ---------------------------------------------------------#
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

                                $final_str_pdf = $newstring21;
                                #-----------------------------------------E-learning msg end ----------------------------------------------------------#

                                //$final_str_pdf = "This is mail for old member";

                                $files_pdf    = array($attchpath_admitcard);
                                $info_arr_pdf = array( //'to'=>$member_info_new[0]['email'],
                                    'to'      => 'pawansing.pardeshi@esds.co.in',
                                    'from'    => 'noreply@iibf.org.in',
                                    'subject' => 'Bulk Exam application1',
                                    'message' => $final_str_pdf,
                                );
                                $this->Emailsending->mailsend_attch($info_arr_pdf, $files_pdf);
                            }

                        }
                    }

                    // Generate exam invoice call
                    $getinvoice_id = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $id), 'invoice_id');
                    $invoiceNumber = bulk_generate_exam_invoice_number($getinvoice_id[0]['invoice_id']);
                    if ($invoiceNumber) {
                        $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                    } else {
                        $invoiceNumber = '';
                    }

                    $update_data_invoice = array('invoice_no' => $invoiceNumber, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $utr_no);
                    $this->db->where('pay_txn_id', $id);
                    $this->master_model->updateRecord('exam_invoice', $update_data_invoice, array('receipt_no' => $id));

                    $attchpath_examinvoice = generate_bulk_examinvoice($id);
                    //echo "##". $attchpath_examinvoice;
                    //echo "<br/>";

                    $this->db->join('bulk_payment_transaction', 'bulk_payment_transaction.inst_code = bulk_accerdited_master.institute_code');
                    $this->db->where('bulk_payment_transaction.id', $id);
                    $bank_info = $this->master_model->getRecords('bulk_accerdited_master', '', 'email');

                    // Send email template of exam invoice to bank
                    if ($attchpath_examinvoice != '') {
                        $final_str_invoice = "Please check attach invoice";
                        $files_invoice     = array($attchpath_examinvoice);
                        $info_arr_invoice  = array( //'to'=>$bank_info[0]['email'],
                            'to'      => 'pawansing.pardeshi@esds.co.in',
                            'from'    => 'noreply@iibf.org.in',
                            'subject' => 'Bulk Exam application2',
                            'message' => $final_str_invoice,
                        );
                        $this->Emailsending->mailsend_attch($info_arr_invoice, $files_invoice);
                    }

                } // end of if

            } elseif ($this->input->post('action') == "Rejected") {
                // update member exam table.
                $reject_status = 0;
                $mem_exam_str  = implode(",", $mem_exam_id_arr);
                $desc          = 'Payment Failed - Rejected by Admin';
                $this->db->query("update member_exam set pay_status = 0, modified_on = '" . $updated_date . "' where id IN (" . $mem_exam_str . ")");

                // update bulk payment transaction table
                $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
                $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

                // update exam invoice table
                $update_exam_invoice_reject = array('transaction_no' => '', 'modified_on' => $updated_date);
                $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

                // update admit card details table
                $this->db->query("update admit_card_details set remark = 4, modified_on = '" . $updated_date . "' where mem_exam_id IN (" . $mem_exam_str . ")");

                //insert in log table

                $update_data_log = array(
                    'status'       => $reject_status,
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                    'approve_id'   => $id,
                );

                log_bulk_admin($log_title = "Bulk Admin NEFT Rejected Successfully", $log_message = serialize($update_data_log));

                $data['success'] = 'Bulk Admin NEFT Rejected Successfully';

            }

            $json_res = json_encode($data);
            echo $json_res;

        }

    } // end of function

    // function to approve/reject NEFT transaction -
    public function approveNeftTransactions_9dec2020()
    {
        $data            = array();
        $mem_exam_id_arr = array();
        $sub_arr         = array();
        $arr_cnt         = array();
        $mem_cnt         = array();
        $flag            = 0;
        $chk_exm_cd_arr  = array();
        $seat_flg_chk    = 1;

        $utr_no       = $this->input->post('utr_no'); // post parameter
        $id           = $this->input->post('id'); // post parameter
        $mem_count    = $this->input->post('mem_count'); // post parameter
        $payment_amt  = $this->input->post('payment_amt'); // post parameter
        $payment_date = $this->input->post('payment_date'); // post parameter

        /*$utr_no = 123458745; // post parameter
        $id = 1; // post parameter
        $mem_count = 5; // post parameter
        $payment_amt = 35005; // post parameter
        $payment_date = date('Y-m-d'); // post parameter
        $action = "Approved";*/

        $updated_date = date('Y-m-d H:i:s');
        $status       = '';

        // Fetch all member_exam_id
        $memexamidlst = $this->master_model->getRecords('bulk_member_payment_transaction', array('ptid' => $id));
        foreach ($memexamidlst as $memexamids) {
            $mem_exam_id_arr[] = $memexamids['memexamid'];
        }
        //fetch all record for which we want to check capacity
        $this->db->where_in('mem_exam_id', $mem_exam_id_arr);
        $member_array = $this->master_model->getRecords('admit_card_details', array('remark' => 2, 'record_source' => 'bulk'));

        /*echo $this->db->last_query();
        echo '<br/>';

        echo '<pre>';
        print_r($member_array);
        exit;*/

        // Query to get exam code
        $this->db->where_in('id', $mem_exam_id_arr);
        $exam_code = $this->master_model->getRecords('member_exam', '', 'exam_code');
        foreach ($exam_code as $exam_code) {
            $chk_exm_cd_arr[] = $exam_code['exam_code'];
        }
        $unique_exm_cd = array_unique($chk_exm_cd_arr);
        $chk_exm_cd    = $unique_exm_cd[0];
        //echo ">>".$chk_exm_cd;
        //exit;
        if (($chk_exm_cd == 101
            || $chk_exm_cd == 1010
            || $chk_exm_cd == 10100
            || $chk_exm_cd == 101000
            || $chk_exm_cd == 1010000
            || $chk_exm_cd == 10100000 || $chk_exm_cd == 996) && sizeof($unique_exm_cd) == 1) {

            if ($this->input->post('action') == "Approved") {

                // update bulk_payment_transaction table
                $data['success'] = 'success';
                $status          = 1;
                $desc            = 'Payment Success - Approved by Admin';
                $update_data     = array(
                    'status'       => $status,
                    'UTR_no'       => $utr_no,
                    'pay_count'    => $mem_count,
                    'amount'       => $payment_amt,
                    'date'         => date("Y-m-d H:i:s", strtotime($payment_date)),
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                );
                $this->master_model->updateRecord('bulk_payment_transaction', $update_data, array('id' => $id));

                // update member_exam table
                //$exam_period = $record['exam_period'];
                $update_member_exam_date = array('pay_status' => $status, 'modified_on' => $updated_date);

                for ($i = 0; $i < sizeof($mem_exam_id_arr); $i++) {
                    $this->master_model->updateRecord('member_exam', $update_member_exam_date, array('id' => $mem_exam_id_arr[$i]));
                    $member_exam = $this->master_model->getRecords('member_exam', array('id' => $mem_exam_id_arr[$i]), 'regnumber');
                    foreach ($member_exam as $member_exam_rec) {
                        $user_stat = check_user_stat($member_exam_rec['regnumber']);
                        /*$user_stat = 1; // old user
                        $user_stat = 0; // fresh user*/
                        if ($user_stat == 0) {
                            $new_mem_regid[] = $member_exam_rec['regnumber'];
                            $new_password    = $this->generate_random_password();
                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            $encPass = $aes->encrypt($new_password);

                            $memregid     = $member_exam_rec['regnumber'];
                            $memregnumber = generate_NM_memreg($memregid);

                            // update member registration table
                            $update_data_member_tbl = array(
                                'regnumber'   => $memregnumber,
                                'usrpassword' => $encPass,
                                'isactive'    => '1',
                                'createdon'   => $updated_date,
                            );
                            $this->master_model->updateRecord('member_registration', $update_data_member_tbl, array('regid' => $memregid));

                            // update member_exam table
                            $update_data_member_exam_tbl = array(
                                'regnumber'   => $memregnumber,
                                'modified_on' => $updated_date,
                            );
                            $this->master_model->updateRecord('member_exam', $update_data_member_exam_tbl, array('regnumber' => $memregid));

                            $log_update_new_mem = array(
                                'regnumber' => $memregnumber,
                                'regid'     => $memregid,
                            );
                            log_bulk_admin($log_title = "Bulk Reg No. generated successfully after NEFT Approval.", $log_message = serialize($log_update_new_mem));

                            //update uploaded file names which will include generated registration number
                            //get cuurent saved file names from DB
                            $currentpics                = $this->master_model->getRecords('member_registration', array('regid' => $memregid), 'scannedphoto, scannedsignaturephoto, idproofphoto');
                            $scannedphoto_file          = '';
                            $scannedsignaturephoto_file = '';
                            $idproofphoto_file          = '';

                            if (count($currentpics) > 0) {
                                $currentphotos              = $currentpics[0];
                                $scannedphoto_file          = $currentphotos['scannedphoto'];
                                $scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
                                $idproofphoto_file          = $currentphotos['idproofphoto'];

                            }
                            $upd_files  = array();
                            $photo_file = 'p_' . $memregnumber . '.jpg';
                            $sign_file  = 's_' . $memregnumber . '.jpg';
                            $proof_file = 'pr_' . $memregnumber . '.jpg';

                            if (!empty($scannedphoto_file)) {
                                if (@rename("./uploads/photograph/" . $scannedphoto_file, "./uploads/photograph/" . $photo_file)) {
                                    $upd_files['scannedphoto'] = $photo_file;
                                }
                            }
                            if (!empty($scannedsignaturephoto_file)) {
                                if (@rename("./uploads/scansignature/" . $scannedsignaturephoto_file, "./uploads/scansignature/" . $sign_file)) {
                                    $upd_files['scannedsignaturephoto'] = $sign_file;
                                }
                            }
                            if (!empty($idproofphoto_file)) {
                                if (@rename("./uploads/idproof/" . $idproofphoto_file, "./uploads/idproof/" . $proof_file)) {
                                    $upd_files['idproofphoto'] = $proof_file;
                                }
                            }

                            if (count($upd_files) > 0) {
                                log_bulk_admin($log_title = "Bulk Member Images Updated successfully after NEFT Approval.", $log_message = serialize($upd_files));

                                $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $memregid));
                            }
                        }
                    }

                }

                // Generate exam invoice call
                $getinvoice_id = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $id), 'invoice_id');
                $invoiceNumber = bulk_generate_exam_invoice_number($getinvoice_id[0]['invoice_id']);
                if ($invoiceNumber) {
                    $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                } else {
                    $invoiceNumber = '';
                }

                $update_data_invoice = array('invoice_no' => $invoiceNumber, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $utr_no);
                $this->db->where('pay_txn_id', $id);
                $this->master_model->updateRecord('exam_invoice', $update_data_invoice, array('receipt_no' => $id));

                $attchpath_examinvoice = generate_bulk_examinvoice($id);

                $this->db->join('bulk_payment_transaction', 'bulk_payment_transaction.inst_code = bulk_accerdited_master.institute_code');
                $this->db->where('bulk_payment_transaction.id', $id);
                $bank_info = $this->master_model->getRecords('bulk_accerdited_master', '', 'email');

                // Send email template of exam invoice to bank
                if ($attchpath_examinvoice != '') {
                    $final_str_invoice = "Please check attach invoice";
                    $files_invoice     = array($attchpath_examinvoice);
                    $info_arr_invoice  = array('to' => $bank_info[0]['email'],
                        //'to'=>'pawansing.pardeshi@esds.co.in',
                        'from'                          => 'noreply@iibf.org.in',
                        'subject'                       => 'Bulk Exam application2',
                        'message'                       => $final_str_invoice,
                    );
                    $this->Emailsending->mailsend_attch($info_arr_invoice, $files_invoice);

                }

                $update_data_log = array(
                    'status'       => $status,
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                    'approve_id'   => $id,
                );

                log_bulk_admin($log_title = "Bulk Admin NEFT For BCBF approved Successfully", $log_message = serialize($update_data_log));

                //$data['success'] = 'Bulk Admin NEFT approved Successfully';

            } elseif ($this->input->post('action') == "Rejected") {
                // update member exam table.
                $reject_status = 0;
                $mem_exam_str  = implode(",", $mem_exam_id_arr);
                $desc          = 'Payment Failed - Rejected by Admin';
                $this->db->query("update member_exam set pay_status = 0, modified_on = '" . $updated_date . "' where id IN (" . $mem_exam_str . ")");

                // update bulk payment transaction table
                $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
                $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

                // update exam invoice table
                $update_exam_invoice_reject = array('transaction_no' => '', 'modified_on' => $updated_date);
                $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

                //insert in log table
                $update_data_log = array(
                    'status'       => $reject_status,
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                    'approve_id'   => $id,
                );

                log_bulk_admin($log_title = "Bulk Admin NEFT For BCBF Rejected Successfully", $log_message = serialize($update_data_log));

                $data['success'] = 'Bulk Admin NEFT Rejected Successfully';

            }

            $json_res = json_encode($data);
            echo $json_res;

        } else {

            //if($action == "Approved"){
            if ($this->input->post('action') == "Approved") {

                $i = 0;
                foreach ($member_array as $member_record) {
                    $venue_code  = $member_record['venueid'];
                    $exam_date   = $member_record['exam_date'];
                    $center_code = $member_record['center_code'];
                    $exam_time   = $member_record['time'];
                    $sub_code    = $member_record['sub_cd'];
                    $type        = 'bulk';
                    $capacity    = check_capacity_bulk_approve($venue_code, $exam_date, $exam_time, $center_code);
                    //echo $capacity;
                    //echo "<br/>";
                    if ($capacity != 0) {
                        // capacity available
                        $sub_details = array("exam_code" => $member_record['exm_cd'], "center_code" => $center_code, "venue_code" => $venue_code, "exam_date" => $exam_date, "exam_time" => $exam_time, "mem_mem_no" => $member_record['mem_mem_no'], "admitcard_id" => $member_record['admitcard_id'], "sub_code" => $member_record['sub_cd'], 'exam_period' => $member_record['exm_prd'], 'member_exam_id' => $member_record['mem_exam_id']);
                        $sub_arr[]   = $sub_details;
                        $i++;

                        $update_data = array(
                            'regnumber'   => $member_record['mem_mem_no'],
                            'venue_code'  => $member_record['venueid'],
                            'exam_date'   => $member_record['exam_date'],
                            'center_code' => $member_record['center_code'],
                            'exam_time'   => $member_record['time'],
                            'sub_code'    => $member_record['sub_cd'],
                        );
                        log_bulk_admin($log_title = "Capacity available", $log_message = serialize($update_data));

                    } else {
                        // capacity full
                        $sub_arr = array();
                        $flag    = 1;
                        /*echo "Capacity full";
                        echo "<br/>";*/

                        $update_data_one = array(
                            'regnumber'   => $member_record['mem_mem_no'],
                            'venue_code'  => $member_record['venueid'],
                            'exam_date'   => $member_record['exam_date'],
                            'center_code' => $member_record['center_code'],
                            'exam_time'   => $member_record['time'],
                            'sub_code'    => $member_record['sub_cd'],
                            'approve_id'  => $id,
                        );
                        log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                        $data['success'] = 'error1';
                        break;

                    }
                    if ($flag == 1) {
                        break;
                    }

                } // end of member array forloop

                if ($flag == 1) {
                    /*echo "<br/>";
                    echo "Flag one";
                    echo "<br/>";*/
                    $reject_status = 0;
                    $mem_exam_str  = implode(",", $mem_exam_id_arr);
                    $desc          = 'Payment Failed - Rejected by Admin';

                    $this->db->query("update member_exam set pay_status = 0 where id IN (" . $mem_exam_str . ")");

                    // update bulk payment transaction table
                    $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
                    $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

                    // update exam invoice table
                    $update_exam_invoice_reject = array('transaction_no' => '');
                    $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

                    // update admit card details table
                    $this->db->query("update admit_card_details set remark = 4 where mem_exam_id IN (" . $mem_exam_str . ")");

                    //insert in log table
                    $update_data_one = array(
                        'utr_no'     => $utr_no,
                        'approve_id' => $id,
                    );
                    log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                    $data['success'] = 'error2';
                    //break;

                }

                /*echo 'member_array>>'. count($member_array);
                echo '<br/>';
                echo 'flag>>'.$flag;
                echo '<br/>';
                echo 'sub arr>>'.count($sub_arr);exit;*/
                // below code execute if capacity is available for all member in runnig batch and allocate seatnumber
                if (count($member_array) > 0 && $flag == 0 && count($member_array) == count($sub_arr)) {
                    $j = 0;
                    foreach ($sub_arr as $sub_details) {
                        //$password = random_password();
                        $v_code       = $sub_details['venue_code'];
                        $e_date       = $sub_details['exam_date'];
                        $e_time       = $sub_details['exam_time'];
                        $sub_code     = $sub_details['sub_code'];
                        $exam_code    = $sub_details['exam_code'];
                        $mem_mem_no   = $sub_details['mem_mem_no'];
                        $admitcard_id = $sub_details['admitcard_id'];
                        $exam_period  = $sub_details['exam_period'];
                        $center_code  = $sub_details['center_code'];

                        // get venue details
                        $get_venue_details = $this->master_model->getRecords('venue_master', array('venue_code' => $v_code, 'exam_date' => $e_date, 'session_time' => $e_time, 'center_code' => $center_code));

                        $seat_allocation = getseat_bulk($exam_code, $center_code, $v_code, $e_date, $e_time, $exam_period, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);

                        //$seat_allocation = 2;
                        if ($seat_allocation != '') {
                            // update admit_card_detail table
                            //$update_seatno = array('seat_identification'=>$seat_allocation,'pwd' => $password);
                            $update_seatno = array('seat_identification' => $seat_allocation);
                            $this->master_model->updateRecord('admit_card_details', $update_seatno, array('admitcard_id' => $admitcard_id));
                            $j++;
                            $arr_cnt[] = $sub_details['admitcard_id'];
                            $mem_cnt[] = $sub_details['mem_mem_no'];

                            $update_data_two = array(
                                'regnumber'   => $mem_mem_no,
                                'venue_code'  => $v_code,
                                'exam_date'   => $e_date,
                                'center_code' => $center_code,
                                'exam_time'   => $e_time,
                                'sub_code'    => $sub_code,
                            );
                            log_bulk_admin($log_title = "Seat allocate successfully", $log_message = serialize($update_data_two));

                            //echo "seat allocation done=>".$password." # ".$mem_mem_no;
                            //echo "<br/>";
                        } else {
                            // allocation fail
                            $seat_flg_chk = 0;
                            $arr_cnt      = array();
                            $mem_cnt      = array();
                            //echo "seat no not generated";
                            //echo "<br/>";
                            $data['success']   = 'error3';
                            $update_data_three = array(
                                'regnumber'   => $mem_mem_no,
                                'venue_code'  => $v_code,
                                'exam_date'   => $e_date,
                                'center_code' => $center_code,
                                'exam_time'   => $e_time,
                                'sub_code'    => $sub_code,
                            );
                            log_bulk_admin($log_title = "Seat not allocate ", $log_message = serialize($update_data_three));
                        }
                    } // end of forloop of sub_arr
                } else {

                    $update_data_one = array(
                        'approve_id' => $id,
                    );
                    log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                    $data['success'] = 'error4';

                    //echo "capacity not available";
                    //echo "<br/>";
                }

                /*exit;*/
                /*echo "<pre>";
                print_r($member_array);
                echo "<br/>";
                print_r($sub_arr);
                echo "<br/>";
                print_r($arr_cnt);
                echo "<br/>";
                print_r($mem_cnt);
                echo "<br/>";
                echo "count of member_array. ".count($member_array);
                echo "<br/>";
                echo "Count of sub_arr     . ".count($sub_arr);
                echo "<br/>";
                echo "Count of arr_arr     . ".count($arr_cnt);
                echo "<br/>";
                echo "Count of mem_cnt     . ".count($mem_cnt);
                echo "<br/>";
                echo "Value of flag        . ".$flag;
                echo "<br/>";
                echo "*****************************";
                echo "<br/>";
                print_r(array_unique($mem_cnt));
                echo "<br/>";
                exit;*/

                // Update required table below
                if (count($sub_arr) == count($member_array) && $flag == 0 && count($member_array) > 0 && $seat_flg_chk == 1) {
                    // update bulk_payment_transaction table
                    $data['success'] = 'success';
                    $status          = 1;
                    $desc            = 'Payment Success - Approved by Admin';
                    $update_data     = array(
                        'status'       => $status,
                        'UTR_no'       => $utr_no,
                        'pay_count'    => $mem_count,
                        'amount'       => $payment_amt,
                        'date'         => date("Y-m-d H:i:s", strtotime($payment_date)),
                        'description'  => $desc,
                        'updated_date' => $updated_date,
                    );
                    $this->master_model->updateRecord('bulk_payment_transaction', $update_data, array('id' => $id));

                    $new_mem_regid = array();

                    foreach ($sub_arr as $record) {
                        // update member_exam table
                        $exam_period             = $record['exam_period'];
                        $update_member_exam_date = array('pay_status' => $status, 'modified_on' => $updated_date);
                        $this->master_model->updateRecord('member_exam', $update_member_exam_date, array('id' => $record['member_exam_id']));

                        // update admit_card_detail table
                        $update_seatno_remark = array('remark' => 1, 'modified_on' => $updated_date);
                        $this->db->where('seat_identification !=', '');
                        $this->master_model->updateRecord('admit_card_details', $update_seatno_remark, array('admitcard_id' => $record['admitcard_id']));

                        $this->db->where('regnumber', $record['mem_mem_no']);
                        $chk_dra = $this->master_model->getRecords('dra_members', '', 'regid');
                        if (count($chk_dra) > 0) {
                            $user_stat = 1;
                        } else {
                            $user_stat = check_user_stat($record['mem_mem_no']);
                        }

                        /*$user_stat = 1; // old user
                        $user_stat = 0; // fresh user*/
                        if ($user_stat == 0) {

                            $new_mem_regid[] = $record['mem_mem_no'];
                            $new_password    = $this->generate_random_password();
                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            $encPass = $aes->encrypt($new_password);

                            $memregid = $record['mem_mem_no'];
                            //$memregnumber = generate_NM_memreg($memregid);

                            if ($record['exam_code'] == $this->config->item('examCodeDBF')) {
                                $memregnumber = generate_DBF_memreg($memregid);
                            } else {
                                $memregnumber = generate_NM_memreg($memregid);
                            }

                            // update member registration table
                            $update_data_member_tbl = array(
                                'regnumber'   => $memregnumber,
                                'usrpassword' => $encPass,
                                'isactive'    => '1',
                                'createdon'   => $updated_date,

                            );
                            $this->master_model->updateRecord('member_registration', $update_data_member_tbl, array('regid' => $memregid));

                            // update member_exam table
                            $update_data_member_exam_tbl = array(
                                'regnumber'   => $memregnumber,
                                'modified_on' => $updated_date,

                            );
                            $this->master_model->updateRecord('member_exam', $update_data_member_exam_tbl, array('regnumber' => $memregid));

                            // update admit card detail table
                            $update_data_admit_card_tbl = array(
                                'mem_mem_no'  => $memregnumber,
                                // 'mem_type'    => 'NM',
                                'modified_on' => $updated_date,

                            );
                            $this->master_model->updateRecord('admit_card_details', $update_data_admit_card_tbl, array('mem_mem_no' => $memregid));

                            $log_update_new_mem = array(
                                'regnumber' => $memregnumber,
                                'regid'     => $memregid,
                            );
                            log_bulk_admin($log_title = "Bulk Reg No. generated successfully after NEFT Approval.", $log_message = serialize($log_update_new_mem));

                            //update uploaded file names which will include generated registration number
                            //get cuurent saved file names from DB
                            $currentpics                = $this->master_model->getRecords('member_registration', array('regid' => $memregid), 'scannedphoto, scannedsignaturephoto, idproofphoto');
                            $scannedphoto_file          = '';
                            $scannedsignaturephoto_file = '';
                            $idproofphoto_file          = '';

                            if (count($currentpics) > 0) {
                                $currentphotos              = $currentpics[0];
                                $scannedphoto_file          = $currentphotos['scannedphoto'];
                                $scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
                                $idproofphoto_file          = $currentphotos['idproofphoto'];

                            }
                            $upd_files  = array();
                            $photo_file = 'p_' . $memregnumber . '.jpg';
                            $sign_file  = 's_' . $memregnumber . '.jpg';
                            $proof_file = 'pr_' . $memregnumber . '.jpg';

                            if (!empty($scannedphoto_file)) {
                                if (@rename("./uploads/photograph/" . $scannedphoto_file, "./uploads/photograph/" . $photo_file)) {
                                    $upd_files['scannedphoto'] = $photo_file;
                                }
                            }
                            if (!empty($scannedsignaturephoto_file)) {
                                if (@rename("./uploads/scansignature/" . $scannedsignaturephoto_file, "./uploads/scansignature/" . $sign_file)) {
                                    $upd_files['scannedsignaturephoto'] = $sign_file;
                                }
                            }
                            if (!empty($idproofphoto_file)) {
                                if (@rename("./uploads/idproof/" . $idproofphoto_file, "./uploads/idproof/" . $proof_file)) {
                                    $upd_files['idproofphoto'] = $proof_file;
                                }
                            }

                            if (count($upd_files) > 0) {
                                log_bulk_admin($log_title = "Bulk Member Images Updated successfully after NEFT Approval.", $log_message = serialize($upd_files));

                                $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $memregid));
                            }
                        }

                    } // end of forloop

                    // Generate admitcard pdf call
                    $this->db->group_by('mem_mem_no,exm_cd');
                    $this->db->where_in('mem_exam_id', $mem_exam_id_arr);
                    $member_array_admitcard = $this->master_model->getRecords('admit_card_details', array('remark' => '1', 'record_source' => 'bulk'), 'mem_mem_no,exm_cd,exm_prd');
                    foreach ($member_array_admitcard as $member_array_record) {
                        $attchpath_admitcard = genarate_admitcard_bulk($member_array_record['mem_mem_no'], $member_array_record['exm_cd'], $member_array_record['exm_prd']);
                        //echo ">>". $attchpath_admitcard;
                        //echo "<br/>";

                        // Send email template of admitcard pdf to user
                        if ($attchpath_admitcard != '') {

                            $member_info_new = $this->master_model->getRecords('member_registration', array('regnumber' => $member_array_record['mem_mem_no']));

                            //Query to get exam details
                            $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period', 'left');
                            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                            $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                            $exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $member_array_record['mem_mem_no']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

                            $username         = $member_info_new[0]['firstname'] . ' ' . $member_info_new[0]['middlename'] . ' ' . $member_info_new[0]['lastname'];
                            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

                            $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                            $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);

                            //Query to get Medium
                            $this->db->where('exam_code', $member_array_record['exm_cd']);
                            $this->db->where('exam_period', $exam_info[0]['exam_period']);
                            $this->db->where('medium_code', $exam_info[0]['exam_medium']);
                            $this->db->where('medium_delete', '0');
                            $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

                            if ($exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} elseif ($exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';}

                            if (in_array($member_info_new[0]['regid'], $new_mem_regid)) {
                                // email for new member

                                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                                $key = $this->config->item('pass_key');
                                $aes = new CryptAES();
                                $aes->set_key(base64_decode($key));
                                $aes->require_pkcs5();
                                $decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));

                                $emailerstr  = $this->master_model->getRecords('emailer', array('emailer_name' => 'new_non_member_bulk'));
                                $newstring1  = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                                $newstring2  = str_replace("#REG_NUM#", "" . $member_array_record['mem_mem_no'] . "", $newstring1);
                                $newstring3  = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                                $newstring4  = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                                $newstring6  = str_replace("#ADD1#", "" . $member_info_new[0]['address1'] . "", $newstring4);
                                $newstring7  = str_replace("#ADD2#", "" . $member_info_new[0]['address2'] . "", $newstring6);
                                $newstring8  = str_replace("#ADD3#", "" . $member_info_new[0]['address3'] . "", $newstring7);
                                $newstring9  = str_replace("#ADD4#", "" . $member_info_new[0]['address4'] . "", $newstring8);
                                $newstring10 = str_replace("#DISTRICT#", "" . $member_info_new[0]['district'] . "", $newstring9);
                                $newstring11 = str_replace("#CITY#", "" . $member_info_new[0]['city'] . "", $newstring10);
                                $newstring12 = str_replace("#STATE#", "" . $member_info_new[0]['state_name'] . "", $newstring11);
                                $newstring13 = str_replace("#PINCODE#", "" . $member_info_new[0]['pincode'] . "", $newstring12);
                                $newstring14 = str_replace("#EMAIL#", "" . $member_info_new[0]['email'] . "", $newstring13);
                                $newstring15 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
                                $newstring16 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
                                $newstring17 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
                                $newstring18 = str_replace("#MODE#", "" . $mode . "", $newstring17);
                                //$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
                                $newstring20 = str_replace("#PASS#", "" . $decpass . "", $newstring18);
                                //$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
                                #-----------------------------------------E-learning msg ---------------------------------------------------------#
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

                                $final_str_pdf = $newstring21;
                                #-----------------------------------------E-learning msg end ----------------------------------------------------------#
                                //$final_str_pdf = "This is mail for new member";

                                $files_pdf    = array($attchpath_admitcard);
                                $info_arr_pdf = array('to' => $member_info_new[0]['email'],
                                    //'to'=>'pawansing.pardeshi@esds.co.in',
                                    'from'                     => 'noreply@iibf.org.in',
                                    'subject'                  => 'Bulk Exam application',
                                    'message'                  => $final_str_pdf,
                                );
                                $this->Emailsending->mailsend_attch($info_arr_pdf, $files_pdf);
                            } elseif (!in_array($member_info_new[0]['regid'], $new_mem_regid)) {
                                // email for old member

                                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                                $key = $this->config->item('pass_key');
                                $aes = new CryptAES();
                                $aes->set_key(base64_decode($key));
                                $aes->require_pkcs5();
                                $decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));

                                $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'old_non_member_bulk'));
                                $newstring1 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                                $newstring2 = str_replace("#REG_NUM#", "" . $member_array_record['mem_mem_no'] . "", $newstring1);
                                $newstring3 = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                                $newstring4 = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                                //$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
                                $newstring6  = str_replace("#ADD1#", "" . $member_info_new[0]['address1'] . "", $newstring4);
                                $newstring7  = str_replace("#ADD2#", "" . $member_info_new[0]['address2'] . "", $newstring6);
                                $newstring8  = str_replace("#ADD3#", "" . $member_info_new[0]['address3'] . "", $newstring7);
                                $newstring9  = str_replace("#ADD4#", "" . $member_info_new[0]['address4'] . "", $newstring8);
                                $newstring10 = str_replace("#DISTRICT#", "" . $member_info_new[0]['district'] . "", $newstring9);
                                $newstring11 = str_replace("#CITY#", "" . $member_info_new[0]['city'] . "", $newstring10);
                                $newstring12 = str_replace("#STATE#", "" . $member_info_new[0]['state_name'] . "", $newstring11);
                                $newstring13 = str_replace("#PINCODE#", "" . $member_info_new[0]['pincode'] . "", $newstring12);
                                $newstring14 = str_replace("#EMAIL#", "" . $member_info_new[0]['email'] . "", $newstring13);
                                $newstring15 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
                                $newstring16 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
                                $newstring17 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
                                $newstring18 = str_replace("#MODE#", "" . $mode . "", $newstring17);
                                //$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
                                $newstring20 = str_replace("#PASS#", "" . $decpass . "", $newstring18);
                                //$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);

                                #-----------------------------------------E-learning msg ---------------------------------------------------------#
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

                                $final_str_pdf = $newstring21;
                                #-----------------------------------------E-learning msg end ----------------------------------------------------------#

                                //$final_str_pdf = "This is mail for old member";

                                $files_pdf    = array($attchpath_admitcard);
                                $info_arr_pdf = array('to' => $member_info_new[0]['email'],
                                    //'to'=>'pawansing.pardeshi@esds.co.in',
                                    'from'                     => 'noreply@iibf.org.in',
                                    'subject'                  => 'Bulk Exam application1',
                                    'message'                  => $final_str_pdf,
                                );
                                $this->Emailsending->mailsend_attch($info_arr_pdf, $files_pdf);
                            }

                        }
                    }

                    // Generate exam invoice call
                    $getinvoice_id = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $id), 'invoice_id');
                    $invoiceNumber = bulk_generate_exam_invoice_number($getinvoice_id[0]['invoice_id']);
                    if ($invoiceNumber) {
                        $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                    } else {
                        $invoiceNumber = '';
                    }

                    $update_data_invoice = array('invoice_no' => $invoiceNumber, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $utr_no);
                    $this->db->where('pay_txn_id', $id);
                    $this->master_model->updateRecord('exam_invoice', $update_data_invoice, array('receipt_no' => $id));

                    $attchpath_examinvoice = generate_bulk_examinvoice($id);
                    //echo "##". $attchpath_examinvoice;
                    //echo "<br/>";

                    $this->db->join('bulk_payment_transaction', 'bulk_payment_transaction.inst_code = bulk_accerdited_master.institute_code');
                    $this->db->where('bulk_payment_transaction.id', $id);
                    $bank_info = $this->master_model->getRecords('bulk_accerdited_master', '', 'email');

                    // Send email template of exam invoice to bank
                    if ($attchpath_examinvoice != '') {
                        $final_str_invoice = "Please check attach invoice";
                        $files_invoice     = array($attchpath_examinvoice);
                        $info_arr_invoice  = array('to' => $bank_info[0]['email'],
                            //'to'=>'pawansing.pardeshi@esds.co.in',
                            'from'                          => 'noreply@iibf.org.in',
                            'subject'                       => 'Bulk Exam application2',
                            'message'                       => $final_str_invoice,
                        );
                        $this->Emailsending->mailsend_attch($info_arr_invoice, $files_invoice);
                    }

                } // end of if

            } elseif ($this->input->post('action') == "Rejected") {
                // update member exam table.
                $reject_status = 0;
                $mem_exam_str  = implode(",", $mem_exam_id_arr);
                $desc          = 'Payment Failed - Rejected by Admin';
                $this->db->query("update member_exam set pay_status = 0, modified_on = '" . $updated_date . "' where id IN (" . $mem_exam_str . ")");

                // update bulk payment transaction table
                $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
                $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

                // update exam invoice table
                $update_exam_invoice_reject = array('transaction_no' => '', 'modified_on' => $updated_date);
                $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

                // update admit card details table
                $this->db->query("update admit_card_details set remark = 4, modified_on = '" . $updated_date . "' where mem_exam_id IN (" . $mem_exam_str . ")");

                //insert in log table

                $update_data_log = array(
                    'status'       => $reject_status,
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                    'approve_id'   => $id,
                );

                log_bulk_admin($log_title = "Bulk Admin NEFT Rejected Successfully", $log_message = serialize($update_data_log));

                $data['success'] = 'Bulk Admin NEFT Rejected Successfully';

            }

            $json_res = json_encode($data);
            echo $json_res;

        }

    } // end of function

    // function to approve/reject NEFT transaction -
    public function approveNeftTransactions()
    {
        $data            = array();
        $mem_exam_id_arr = array();
        $sub_arr         = array();
        $arr_cnt         = array();
        $mem_cnt         = array();
        $flag            = 0;
        $chk_exm_cd_arr  = array();

        $utr_no       = $this->input->post('utr_no'); // post parameter
        $id           = $this->input->post('id'); // post parameter
        $mem_count    = $this->input->post('mem_count'); // post parameter
        $payment_amt  = $this->input->post('payment_amt'); // post parameter
        $payment_date = $this->input->post('payment_date'); // post parameter

        /*$utr_no = 123458745; // post parameter
        $id = 1; // post parameter
        $mem_count = 5; // post parameter
        $payment_amt = 35005; // post parameter
        $payment_date = date('Y-m-d'); // post parameter
        $action = "Approved";*/

        $updated_date = date('Y-m-d H:i:s');
        $status       = '';

        // Fetch all member_exam_id
        $memexamidlst = $this->master_model->getRecords('bulk_member_payment_transaction', array('ptid' => $id));
        foreach ($memexamidlst as $memexamids) {
            $mem_exam_id_arr[] = $memexamids['memexamid'];
        }
        //fetch all record for which we want to check capacity
        $this->db->where_in('mem_exam_id', $mem_exam_id_arr);
        $member_array = $this->master_model->getRecords('admit_card_details', array('remark' => 2, 'record_source' => 'bulk'));

        // echo $this->db->last_query();
        // echo '<br/>';

        // echo '<pre>';
        // print_r($member_array);
        // exit;

        // Query to get exam code
        $this->db->where_in('id', $mem_exam_id_arr);
        $exam_code = $this->master_model->getRecords('member_exam', '', 'exam_code');
        foreach ($exam_code as $exam_code) {
            $chk_exm_cd_arr[] = $exam_code['exam_code'];
        }
        $unique_exm_cd = array_unique($chk_exm_cd_arr);
        $chk_exm_cd    = $unique_exm_cd[0];
        //echo ">>".$chk_exm_cd;
        //exit;
        
        if (($chk_exm_cd == 101
            || $chk_exm_cd == 1046

            || in_array($chk_exm_cd, $this->elearning_course_code)
            || $chk_exm_cd == 1010
            || $chk_exm_cd == 10100
            || $chk_exm_cd == 101000
            || $chk_exm_cd == 1010000
            || $chk_exm_cd == 10100000) && sizeof($unique_exm_cd) == 1) /*  || $chk_exm_cd == 996 */ {
            if ($this->input->post('action') == "Approved") {

                // update bulk_payment_transaction table
                $data['success'] = 'success';
                $status          = 1;
                $desc            = 'Payment Success - Approved by Admin';
                $update_data     = array(
                    'status'       => $status,
                    'UTR_no'       => $utr_no,
                    'pay_count'    => $mem_count,
                    'amount'       => $payment_amt,
                    'date'         => date("Y-m-d H:i:s", strtotime($payment_date)),
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                );
                $this->master_model->updateRecord('bulk_payment_transaction', $update_data, array('id' => $id));

                // update member_exam table
                //$exam_period = $record['exam_period'];
                $update_member_exam_date = array('pay_status' => $status, 'modified_on' => $updated_date);

                for ($i = 0; $i < sizeof($mem_exam_id_arr); $i++) {
                    $this->master_model->updateRecord('member_exam', $update_member_exam_date, array('id' => $mem_exam_id_arr[$i]));
                    $member_exam = $this->master_model->getRecords('member_exam', array('id' => $mem_exam_id_arr[$i]), 'regnumber');
                    foreach ($member_exam as $member_exam_rec) {
                        
                        $user_stat = check_user_stat($member_exam_rec['regnumber']);
                  
                        /*$user_stat = 1; // old user
                        $user_stat = 0; // fresh user*/
                        if ($user_stat == 0) {
                            
                            $new_mem_regid[] = $member_exam_rec['regnumber'];
                            $new_password    = $this->generate_random_password();
                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            $encPass = $aes->encrypt($new_password);

                            $memregid     = $member_exam_rec['regnumber'];
                            $memregnumber = generate_NM_memreg($memregid);

                            // update member registration table
                            $update_data_member_tbl = array(
                                'regnumber'   => $memregnumber,
                                'usrpassword' => $encPass,
                                'isactive'    => '1',
                                'createdon'   => $updated_date,
                            );
                            $this->master_model->updateRecord('member_registration', $update_data_member_tbl, array('regid' => $memregid));

                            // update member_exam table
                            $update_data_member_exam_tbl = array(
                                'regnumber'   => $memregnumber,
                                'modified_on' => $updated_date,
                            );
                            $this->master_model->updateRecord('member_exam', $update_data_member_exam_tbl, array('regnumber' => $memregid));

                            $log_update_new_mem = array(
                                'regnumber' => $memregnumber,
                                'regid'     => $memregid,
                            );
                            log_bulk_admin($log_title = "Bulk Reg No. generated successfully after NEFT Approval.", $log_message = serialize($log_update_new_mem));

                            //update uploaded file names which will include generated registration number
                            //get cuurent saved file names from DB
                            $currentpics                = $this->master_model->getRecords('member_registration', array('regid' => $memregid), 'scannedphoto, scannedsignaturephoto, idproofphoto,empidproofphoto,declaration,bank_bc_id_card');
                            $scannedphoto_file          = '';
                            $scannedsignaturephoto_file = '';
                            $idproofphoto_file          = '';
                            $empidproofphoto_file ='';
                            $declaration_file = '';
                            $bank_bc_id_card_file = '';
                            
                            if (count($currentpics) > 0) {
                                $currentphotos              = $currentpics[0];
                                $scannedphoto_file          = $currentphotos['scannedphoto'];
                                $scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
                                $idproofphoto_file          = $currentphotos['idproofphoto'];
                                $empidproofphoto_file = $currentphotos['empidproofphoto']; //priyanka d -20-8-24 --fedai bulk
                                $declaration_file          = $currentphotos['declaration'];
                                $bank_bc_id_card_file          = $currentphotos['bank_bc_id_card'];

                            }
                            $upd_files  = array();
                            $photo_file = 'p_' . $memregnumber . '.jpg';
                            $sign_file  = 's_' . $memregnumber . '.jpg';
                            $proof_file = 'pr_' . $memregnumber . '.jpg';
                            $emp_file  = 'empr_' . $memregnumber . '.jpg';
                            $declare_file = 'declarationr_' . $memregnumber . '.jpg';
                            $bank_bc_id_card_new_filename = 'bank_bc_id_card_' . $memregnumber . '.jpg';

                            if (!empty($scannedphoto_file)) {
                                if (@rename("./uploads/photograph/" . $scannedphoto_file, "./uploads/photograph/" . $photo_file)) {
                                    $upd_files['scannedphoto'] = $photo_file;
                                }
                            }
                            if (!empty($scannedsignaturephoto_file)) {
                                if (@rename("./uploads/scansignature/" . $scannedsignaturephoto_file, "./uploads/scansignature/" . $sign_file)) {
                                    $upd_files['scannedsignaturephoto'] = $sign_file;
                                }
                            }
                            if (!empty($idproofphoto_file)) {
                                if (@rename("./uploads/idproof/" . $idproofphoto_file, "./uploads/idproof/" . $proof_file)) {
                                    $upd_files['idproofphoto'] = $proof_file;
                                }
                            }
                            if (!empty($empidproofphoto_file)) {
                                if (@rename("./uploads/empidproof/" . $empidproofphoto_file, "./uploads/empidproof/" . $emp_file)) {
                                    $upd_files['empidproofphoto'] = $emp_file;
                                }
                            }
                            if (!empty($declaration_file)) {
                                if (@rename("./uploads/declaration/" . $declaration_file, "./uploads/declaration/" . $declare_file)) {
                                    $upd_files['empidproofphoto'] = $declare_file;
                                }
                            }

                            if($chk_exm_cd==1046)
                            {
                                if (!empty($bank_bc_id_card_file)) {
                                    if (@rename("./uploads/empidproof/" . $bank_bc_id_card_file, "./uploads/empidproof/" . $bank_bc_id_card_new_filename)) {
                                        $upd_files['bank_bc_id_card'] = $bank_bc_id_card_new_filename;
                                    }
                                }
                            }

                            if (count($upd_files) > 0) {
                                log_bulk_admin($log_title = "Bulk Member Images Updated successfully after NEFT Approval.", $log_message = serialize($upd_files));
                               // echo'<pre>';print_r($upd_files);
                                $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $memregid));
                               // echo $this->db->last_query();exit;
                            }
                        }
                    }

                }

                // Generate exam invoice call
                $getinvoice_id = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $id), 'invoice_id');
                $invoiceNumber = bulk_generate_exam_invoice_number($getinvoice_id[0]['invoice_id']);
                if ($invoiceNumber) {
                    $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                } else {
                    $invoiceNumber = '';
                }

                $update_data_invoice = array('invoice_no' => $invoiceNumber, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $utr_no);
                $this->db->where('pay_txn_id', $id);
                $this->master_model->updateRecord('exam_invoice', $update_data_invoice, array('receipt_no' => $id));

                $attchpath_examinvoice = generate_bulk_examinvoice($id);

                $this->db->join('bulk_payment_transaction', 'bulk_payment_transaction.inst_code = bulk_accerdited_master.institute_code');
                $this->db->where('bulk_payment_transaction.id', $id);
                $bank_info = $this->master_model->getRecords('bulk_accerdited_master', '', 'email');

                // Send email template of exam invoice to bank
                if ($attchpath_examinvoice != '') {
                    /*$final_str_invoice = "Please check attach invoice";
                $files_invoice=array($attchpath_examinvoice);
                $info_arr_invoice=array('to'=>$bank_info[0]['email'],
                //'to'=>'pawansing.pardeshi@esds.co.in',
                'from'=>'noreply@iibf.org.in',
                'subject'=>'Bulk Exam application2',
                'message'=>$final_str_invoice
                );
                $this->Emailsending->mailsend_attch($info_arr_invoice,$files_invoice);*/

                }

                $update_data_log = array(
                    'status'       => $status,
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                    'approve_id'   => $id,
                );

                log_bulk_admin($log_title = "Bulk Admin NEFT For BCBF approved Successfully", $log_message = serialize($update_data_log));

                //$data['success'] = 'Bulk Admin NEFT approved Successfully';

            } 
            elseif ($this->input->post('action') == "Rejected") {
                // update member exam table.
                $reject_status = 0;
                $mem_exam_str  = implode(",", $mem_exam_id_arr);
                $desc          = 'Payment Failed - Rejected by Admin';
                $this->db->query("update member_exam set pay_status = 0, modified_on = '" . $updated_date . "' where id IN (" . $mem_exam_str . ")");

                // update bulk payment transaction table
                $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
                $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

                // update exam invoice table
                $update_exam_invoice_reject = array('transaction_no' => '', 'modified_on' => $updated_date);
                $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

                //insert in log table
                $update_data_log = array(
                    'status'       => $reject_status,
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                    'approve_id'   => $id,
                );

                log_bulk_admin($log_title = "Bulk Admin NEFT For BCBF Rejected Successfully", $log_message = serialize($update_data_log));

                $data['success'] = 'Bulk Admin NEFT Rejected Successfully';

            }

            $json_res = json_encode($data);
            echo $json_res;

        } else {
            // echo'<pre>';print_r($member_array);
            //if($action == "Approved"){
            if ($this->input->post('action') == "Approved") {

                $i = 0;
                foreach ($member_array as $member_record) {
                    $venue_code  = $member_record['venueid'];
                    $exam_date   = $member_record['exam_date'];
                    $center_code = $member_record['center_code'];
                    $exam_time   = $member_record['time'];
                    $sub_code    = $member_record['sub_cd'];
                    $type        = 'bulk';
                    $capacity    = check_capacity_bulk_approve($venue_code, $exam_date, $exam_time, $center_code);
                    // echo $capacity;
                    // echo "<br/>";//die;
                    if ($capacity != 0) {
                        // capacity available
                        $sub_details = array("exam_code" => $member_record['exm_cd'], "center_code" => $center_code, "venue_code" => $venue_code, "exam_date" => $exam_date, "exam_time" => $exam_time, "mem_mem_no" => $member_record['mem_mem_no'], "admitcard_id" => $member_record['admitcard_id'], "sub_code" => $member_record['sub_cd'], 'exam_period' => $member_record['exm_prd'], 'member_exam_id' => $member_record['mem_exam_id']);
                        $sub_arr[]   = $sub_details;
                        $i++;

                        $update_data = array(
                            'regnumber'   => $member_record['mem_mem_no'],
                            'venue_code'  => $member_record['venueid'],
                            'exam_date'   => $member_record['exam_date'],
                            'center_code' => $member_record['center_code'],
                            'exam_time'   => $member_record['time'],
                            'sub_code'    => $member_record['sub_cd'],
                        );
                        log_bulk_admin($log_title = "Capacity available" . $id, $log_message = serialize($update_data));

                    } else {
                        // capacity full
                        $sub_arr = array();
                        $flag    = 1;
                        echo "Capacity full";
                        echo "<br/>";

                        $update_data_one = array(
                            'regnumber'   => $member_record['mem_mem_no'],
                            'venue_code'  => $member_record['venueid'],
                            'exam_date'   => $member_record['exam_date'],
                            'center_code' => $member_record['center_code'],
                            'exam_time'   => $member_record['time'],
                            'sub_code'    => $member_record['sub_cd'],
                            'approve_id'  => $id,
                        );
                        log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                        $data['success'] = 'error1';
                        break;

                    }
                    if ($flag == 1) {
                        break;
                    }

                } // end of member array forloop
                // ECHO '****';DIE;
                if ($flag == 1) {
                    /*echo "<br/>";
                    echo "Flag one";
                    echo "<br/>";*/
                    $reject_status = 0;
                    $mem_exam_str  = implode(",", $mem_exam_id_arr);
                    $desc          = 'Payment Failed - Rejected by Admin';

                    $this->db->query("update member_exam set pay_status = 0 where id IN (" . $mem_exam_str . ")");

                    // update bulk payment transaction table
                    $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
                    $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

                    // update exam invoice table
                    $update_exam_invoice_reject = array('transaction_no' => '');
                    $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

                    // update admit card details table
                    $this->db->query("update admit_card_details set remark = 4 where mem_exam_id IN (" . $mem_exam_str . ")");

                    //insert in log table
                    $update_data_one = array(
                        'utr_no'     => $utr_no,
                        'approve_id' => $id,
                    );
                    log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                    $data['success'] = 'error2';
                    //break;

                }

                /*echo 'member_array>>'. count($member_array);
                echo '<br/>';
                echo 'flag>>'.$flag;
                echo '<br/>';
                echo 'sub arr>>'.count($sub_arr);*/
                // below code execute if capacity is available for all member in runnig batch and allocate seatnumber
                if (count($member_array) > 0 && $flag == 0 && count($member_array) == count($sub_arr)) {
                    $j = 0;
                    foreach ($sub_arr as $sub_details) {
                        //$password = random_password();
                        $v_code       = $sub_details['venue_code'];
                        $e_date       = $sub_details['exam_date'];
                        $e_time       = $sub_details['exam_time'];
                        $sub_code     = $sub_details['sub_code'];
                        $exam_code    = $sub_details['exam_code'];
                        $mem_mem_no   = $sub_details['mem_mem_no'];
                        $admitcard_id = $sub_details['admitcard_id'];
                        $exam_period  = $sub_details['exam_period'];
                        $center_code  = $sub_details['center_code'];

                        // get venue details
                        $get_venue_details = $this->master_model->getRecords('venue_master', array('venue_code' => $v_code, 'exam_date' => $e_date, 'session_time' => $e_time, 'center_code' => $center_code));

                        $seat_allocation = getseat_bulk($exam_code, $center_code, $v_code, $e_date, $e_time, $exam_period, $sub_code, $get_venue_details[0]['session_capacity'], $admitcard_id);

                        //$seat_allocation = 2;
                        if ($seat_allocation != '') {
                            // update admit_card_detail table
                            //$update_seatno = array('seat_identification'=>$seat_allocation,'pwd' => $password);
                            $update_seatno = array('seat_identification' => $seat_allocation);
                            $this->master_model->updateRecord('admit_card_details', $update_seatno, array('admitcard_id' => $admitcard_id));
                            $j++;
                            $arr_cnt[] = $sub_details['admitcard_id'];
                            $mem_cnt[] = $sub_details['mem_mem_no'];

                            $update_data_two = array(
                                'regnumber'   => $mem_mem_no,
                                'venue_code'  => $v_code,
                                'exam_date'   => $e_date,
                                'center_code' => $center_code,
                                'exam_time'   => $e_time,
                                'sub_code'    => $sub_code,
                            );
                            log_bulk_admin($log_title = "Seat allocate successfully " . $id, $log_message = serialize($update_data_two));

                            //echo "seat allocation done=>".$password." # ".$mem_mem_no;
                            //echo "<br/>";
                        } 
                        else {
                            // allocation fail
                            $arr_cnt = array();
                            $mem_cnt = array();
                            //echo "seat no not generated";
                            //echo "<br/>";
                            $data['success']   = 'error3';
                            $update_data_three = array(
                                'regnumber'   => $mem_mem_no,
                                'venue_code'  => $v_code,
                                'exam_date'   => $e_date,
                                'center_code' => $center_code,
                                'exam_time'   => $e_time,
                                'sub_code'    => $sub_code,
                            );
                            log_bulk_admin($log_title = "Seat not allocate " . $id, $log_message = serialize($update_data_three));
                        }
                    } // end of forloop of sub_arr
                } else {

                    $update_data_one = array(
                        'approve_id' => $id,
                    );
                    log_bulk_admin($log_title = "Capacity not available (Receipt No : " . $id . ",UTR No :" . $utr_no . ")", $log_message = serialize($update_data_one));

                    $data['success'] = 'Error4 : Capacity not available';

                    //echo "capacity not available";
                    //echo "<br/>";
                }

              

                // Update required table below
                if (count($sub_arr) == count($member_array) && $flag == 0 && count($member_array) > 0) {
                    // update bulk_payment_transaction table
                    $data['success'] = 'success';
                    $status          = 1;
                    $desc            = 'Payment Success - Approved by Admin';
                    $update_data     = array(
                        'status'       => $status,
                        'UTR_no'       => $utr_no,
                        'pay_count'    => $mem_count,
                        'amount'       => $payment_amt,
                        'date'         => date("Y-m-d H:i:s", strtotime($payment_date)),
                        'description'  => $desc,
                        'updated_date' => $updated_date,
                    );
                    $this->master_model->updateRecord('bulk_payment_transaction', $update_data, array('id' => $id));

                    $new_mem_regid = array();

                    foreach ($sub_arr as $record) {
                        // update member_exam table
                        $exam_period             = $record['exam_period'];
                        $update_member_exam_date = array('pay_status' => $status, 'modified_on' => $updated_date);
                        $this->master_model->updateRecord('member_exam', $update_member_exam_date, array('id' => $record['member_exam_id']));

                        $member_exam_info = $this->master_model->getRecords('member_exam', array('id' => $record['member_exam_id']));
                        // update admit_card_detail table
                        $update_seatno_remark = array('remark' => 1, 'modified_on' => $updated_date);
                        $this->master_model->updateRecord('admit_card_details', $update_seatno_remark, array('admitcard_id' => $record['admitcard_id']));

                        $this->db->where('regnumber', $record['mem_mem_no']);
                        $chk_dra = $this->master_model->getRecords('dra_members', '', 'regid');
                        if (count($chk_dra) > 0) {
                            $user_stat = 1;
                        } else {
                            $user_stat = check_user_stat($record['mem_mem_no']);
                        }

                        /*$user_stat = 1; // old user
                        $user_stat = 0; // fresh user*/
                        if ($user_stat == 0) {

                            $new_mem_regid[] = $record['mem_mem_no'];
                            $new_password    = $this->generate_random_password();
                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            $encPass = $aes->encrypt($new_password);

                            $memregid = $record['mem_mem_no'];
                            //$memregnumber = generate_NM_memreg($memregid);

                            if ($record['exam_code'] == $this->config->item('examCodeDBF')) {
                                $memregnumber = generate_DBF_memreg($memregid);
                            } else {
                                $memregnumber = generate_NM_memreg($memregid);
                            }

                            if ($member_exam_info[0]['member_type'] != 'O' && $member_exam_info[0]['member_type'] != 'o') {
                                // update member registration table
                                $update_data_member_tbl = array(
                                    'regnumber'   => $memregnumber,
                                    'usrpassword' => $encPass,
                                    'isactive'    => '1',
                                    'createdon'   => $updated_date,

                                );
                                $this->master_model->updateRecord('member_registration', $update_data_member_tbl, array('regid' => $memregid));

                                // update member_exam table
                                $update_data_member_exam_tbl = array(
                                    'regnumber'   => $memregnumber,
                                    'modified_on' => $updated_date,

                                );
                                $this->master_model->updateRecord('member_exam', $update_data_member_exam_tbl, array('regnumber' => $memregid));

                                // update admit card detail table
                                $update_data_admit_card_tbl = array(
                                    'mem_mem_no'  => $memregnumber,
                                    // 'mem_type'    => 'NM',
                                    'modified_on' => $updated_date,

                                );
                                $this->master_model->updateRecord('admit_card_details', $update_data_admit_card_tbl, array('mem_mem_no' => $memregid));
                            }

                            $log_update_new_mem = array(
                                'regnumber' => $memregnumber,
                                'regid'     => $memregid,
                            );
                            log_bulk_admin($log_title = "Bulk Reg No. generated successfully after NEFT Approval." . $id, $log_message = serialize($log_update_new_mem));

                            //update uploaded file names which will include generated registration number
                            //get cuurent saved file names from DB
                            if ($member_exam_info[0]['member_type'] != 'O' && $member_exam_info[0]['member_type'] != 'o') {
                                $currentpics                = $this->master_model->getRecords('member_registration', array('regid' => $memregid), 'scannedphoto, scannedsignaturephoto, idproofphoto, bank_bc_id_card');
                                $scannedphoto_file          = '';
                                $scannedsignaturephoto_file = '';
                                $idproofphoto_file          = '';
                                $bank_bc_id_card_file          = '';

                                if (count($currentpics) > 0) {
                                    $currentphotos              = $currentpics[0];
                                    $scannedphoto_file          = $currentphotos['scannedphoto'];
                                    $scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
                                    $idproofphoto_file          = $currentphotos['idproofphoto'];
                                    $bank_bc_id_card_file          = $currentphotos['bank_bc_id_card'];

                                }
                                $upd_files  = array();
                                $photo_file = 'p_' . $memregnumber . '.jpg';
                                $sign_file  = 's_' . $memregnumber . '.jpg';
                                $proof_file = 'pr_' . $memregnumber . '.jpg';
                                $bank_bc_id_card_new_filename = 'bank_bc_id_card_' . $memregnumber . '.jpg';

                                if (!empty($scannedphoto_file)) {
                                    if (@rename("./uploads/photograph/" . $scannedphoto_file, "./uploads/photograph/" . $photo_file)) {
                                        $upd_files['scannedphoto'] = $photo_file;
                                    }
                                }
                                if (!empty($scannedsignaturephoto_file)) {
                                    if (@rename("./uploads/scansignature/" . $scannedsignaturephoto_file, "./uploads/scansignature/" . $sign_file)) {
                                        $upd_files['scannedsignaturephoto'] = $sign_file;
                                    }
                                }
                                if (!empty($idproofphoto_file)) {
                                    if (@rename("./uploads/idproof/" . $idproofphoto_file, "./uploads/idproof/" . $proof_file)) {
                                        $upd_files['idproofphoto'] = $proof_file;
                                    }
                                }

                                if($member_exam_info[0]['exam_code']==1055 || $member_exam_info[0]['exam_code']==1056){
                                    if (!empty($bank_bc_id_card_file)) {
                                        if (@rename("./uploads/empidproof/" . $bank_bc_id_card_file, "./uploads/empidproof/" . $bank_bc_id_card_new_filename)) {
                                            $upd_files['bank_bc_id_card'] = $bank_bc_id_card_new_filename;
                                        }
                                    }
                                }

                                if (count($upd_files) > 0) {
                                    log_bulk_admin($log_title = "Bulk Member Images Updated successfully after NEFT Approval." . $id, $log_message = serialize($upd_files));

                                    $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $memregid));
                                }
                            }
                        }

                        if($member_exam_info[0]['exam_code']==1009 && $member_exam_info[0]['member_type'] != 'O' && $member_exam_info[0]['member_type'] != 'o'){

                            $memregid     = $member_exam_info[0]['regnumber'];

                            $currentpics                = $this->master_model->getRecords('member_registration', array('regnumber' => $memregid), 'scannedphoto, scannedsignaturephoto, idproofphoto,empidproofphoto,declaration');
                            $empidproofphoto_file          = '';
                            $declaration_file = '';
                        

                            if (count($currentpics) > 0) {
                                $currentphotos              = $currentpics[0];
                            
                                $empidproofphoto_file = $currentphotos['empidproofphoto']; //priyanka d -20-8-24 --fedai bulk
                                $declaration_file          = $currentphotos['declaration'];

                            }
                            $upd_files  = array();
                        
                            $emp_file  = 'empr_' . $memregid . '.jpg';
                            $declare_file = 'declaration_' . $memregid . '.jpg';

                            
                            if (!empty($empidproofphoto_file)) {
                                if (@rename("./uploads/empidproof/" . $empidproofphoto_file, "./uploads/empidproof/" . $emp_file)) {
                                    $upd_files['empidproofphoto'] = $emp_file;
                                }
                            }
                            if (!empty($declaration_file)) {
                                if (@rename("./uploads/declaration/" . $declaration_file, "./uploads/declaration/" . $declare_file)) {
                                    $upd_files['declaration'] = $declare_file;
                                }
                            }
                            if (count($upd_files) > 0) {
                                log_bulk_admin($log_title = "Bulk Fedai Member Images Updated successfully after NEFT Approval.", $log_message = serialize($upd_files));

                                $this->master_model->updateRecord('member_registration', $upd_files, array('regnumber' => $memregid));
                            }
                        
                        }
                    } // end of forloop

                    // Update admit card pdf name call/ set table
                    $this->db->group_by('mem_mem_no,exm_cd');
                    $this->db->where_in('mem_exam_id', $mem_exam_id_arr);
                    $member_array_admitcard = $this->master_model->getRecords('admit_card_details', array('remark' => '1', 'record_source' => 'bulk'), 'mem_mem_no,exm_cd,exm_prd');
                    foreach ($member_array_admitcard as $member_array_record) {
                        $attchpath_admitcard = genarate_admitcard_bulk($member_array_record['mem_mem_no'], $member_array_record['exm_cd'], $member_array_record['exm_prd']);

                    }

                    // Generate exam invoice call
                    $getinvoice_id = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $id), 'invoice_id');
                    $invoiceNumber = bulk_generate_exam_invoice_number($getinvoice_id[0]['invoice_id']);
                    if ($invoiceNumber) {
                        $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                    } else {
                        $invoiceNumber = '';
                    }

                    $update_data_invoice = array('invoice_no' => $invoiceNumber, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'), 'transaction_no' => $utr_no);
                    $this->db->where('pay_txn_id', $id);
                    $this->master_model->updateRecord('exam_invoice', $update_data_invoice, array('receipt_no' => $id));

                    $attchpath_examinvoice = generate_bulk_examinvoice($id);
                    //echo "##". $attchpath_examinvoice;
                    //echo "<br/>";

                    $this->db->join('bulk_payment_transaction', 'bulk_payment_transaction.inst_code = bulk_accerdited_master.institute_code');
                    $this->db->where('bulk_payment_transaction.id', $id);
                    $bank_info = $this->master_model->getRecords('bulk_accerdited_master', '', 'email');

                    // Send email template of exam invoice to bank
                    if ($attchpath_examinvoice != '') {
                        /*$final_str_invoice = "Please check attach invoice";
                    $files_invoice=array($attchpath_examinvoice);
                    $info_arr_invoice=array('to'=>$bank_info[0]['email'],
                    //'to'=>'pawansing.pardeshi@esds.co.in',
                    'from'=>'noreply@iibf.org.in',
                    'subject'=>'Bulk Exam application2',
                    'message'=>$final_str_invoice
                    );
                    $this->Emailsending->mailsend_attch($info_arr_invoice,$files_invoice);*/
                    }

                } // end of if

            } elseif ($this->input->post('action') == "Rejected") {
                // update member exam table.
                $reject_status = 0;
                $mem_exam_str  = implode(",", $mem_exam_id_arr);
                $desc          = 'Payment Failed - Rejected by Admin';
                $this->db->query("update member_exam set pay_status = 0, modified_on = '" . $updated_date . "' where id IN (" . $mem_exam_str . ")");

                // update bulk payment transaction table
                $update_payment_transaction_reject = array('status' => $reject_status, 'updated_date' => $updated_date, 'description' => $desc);
                $this->master_model->updateRecord('bulk_payment_transaction', $update_payment_transaction_reject, array("id" => $id));

                // update exam invoice table
                $update_exam_invoice_reject = array('transaction_no' => '', 'modified_on' => $updated_date);
                $this->master_model->updateRecord('exam_invoice', $update_exam_invoice_reject, array("pay_txn_id" => $id));

                // update admit card details table
                $this->db->query("update admit_card_details set remark = 4, modified_on = '" . $updated_date . "' where mem_exam_id IN (" . $mem_exam_str . ")");

                //insert in log table

                $update_data_log = array(
                    'status'       => $reject_status,
                    'description'  => $desc,
                    'updated_date' => $updated_date,
                    'approve_id'   => $id,
                );

                log_bulk_admin($log_title = "Bulk Admin NEFT Rejected Successfully", $log_message = serialize($update_data_log));

                $data['success'] = 'Bulk Admin NEFT Rejected Successfully';

            }

            $json_res = json_encode($data);
            echo $json_res;

        }

    } // end of function

    public function bulk_generate_admitcard()
    {
        $utr_no       = $this->input->post('utr_no'); // post parameter
        $id           = $this->input->post('id'); // post parameter
        $mem_count    = $this->input->post('mem_count'); // post parameter
        $payment_amt  = $this->input->post('payment_amt'); // post parameter
        $payment_date = $this->input->post('payment_date'); // post parameter

        log_bulk_admin($log_title = "Bulk admitcard first recursive call parameter. " . $id, $log_message = serialize($utr_no . '|' . $id . '|' . $mem_count . '|' . $payment_amt . '|' . $payment_date));

        // Fetch all member_exam_id
        $memexamidlst = $this->master_model->getRecords('bulk_member_payment_transaction', array('ptid' => $id));
        foreach ($memexamidlst as $memexamids) {
            $mem_exam_id_arr[] = $memexamids['memexamid'];
        }

        $this->bulk_generate_admitcard_recursiv($utr_no, $id, $mem_count, $payment_amt, $payment_date, $mem_exam_id_arr);
    }

    public function bulk_generate_admitcard_recursiv($utr_no, $id, $mem_count, $payment_amt, $payment_date, $mem_exam_id_arr)
    {
        $str_id = implode(',', $mem_exam_id_arr);
        log_bulk_admin($log_title = "Bulk admitcard second recursive call parameter. " . $id, $log_message = serialize($utr_no . '|' . $id . '|' . $mem_count . '|' . $payment_amt . '|' . $payment_date . '|' . $str_id));

        $data           = array();
        $sub_arr        = array();
        $arr_cnt        = array();
        $mem_cnt        = array();
        $flag           = 0;
        $chk_exm_cd_arr = array();

        $utr_no       = $this->input->post('utr_no'); // post parameter
        $id           = $this->input->post('id'); // post parameter
        $mem_count    = $this->input->post('mem_count'); // post parameter
        $payment_amt  = $this->input->post('payment_amt'); // post parameter
        $payment_date = $this->input->post('payment_date'); // post parameter

        $updated_date = date('Y-m-d H:i:s');
        $status       = '';

        $data['success'] = 'success';
        $new_mem_regid   = array();

        // Generate admitcard pdf call
        $this->db->group_by('mem_mem_no,exm_cd');
        $this->db->where_in('mem_exam_id', $mem_exam_id_arr);
        $this->db->where('bulk_recursive_call', 0);
        $this->db->limit(1);

        $member_array_record = $this->master_model->getRecords('admit_card_details', array('remark' => '1', 'record_source' => 'bulk'), 'mem_mem_no,exm_cd,exm_prd,admitcard_id');
        if (count($member_array_record) > 0) {

            $attchpath_admitcard = genarate_admitcard_bulk_pdffile($member_array_record[0]['mem_mem_no'], $member_array_record[0]['exm_cd'], $member_array_record[0]['exm_prd']);

            // Send email template of admitcard pdf to user
            if ($attchpath_admitcard != '') {

                $update_data_invoice = array('bulk_recursive_call' => 1);
                $this->master_model->updateRecord('admit_card_details', $update_data_invoice, array('admitcard_id' => $member_array_record[0]['admitcard_id']));

                $last_query = $this->db->last_query();
                log_bulk_admin($log_title = "Bulk admitcard recursive call update. " . $id, $log_message = $last_query);

                $member_info_new = $this->master_model->getRecords('member_registration', array('regnumber' => $member_array_record[0]['mem_mem_no']));

                //Query to get exam details
                $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period', 'left');
                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $member_array_record[0]['mem_mem_no']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

                $username         = $member_info_new[0]['firstname'] . ' ' . $member_info_new[0]['middlename'] . ' ' . $member_info_new[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

                $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);

                //Query to get Medium
                $this->db->where('exam_code', $member_array_record[0]['exm_cd']);
                $this->db->where('exam_period', $exam_info[0]['exam_period']);
                $this->db->where('medium_code', $exam_info[0]['exam_medium']);
                $this->db->where('medium_delete', '0');
                $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

                if ($exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} elseif ($exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';}

                if ($member_info_new[0]['regid'] == $member_array_record[0]['mem_mem_no']) {
                    // email for new member

                    include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                    $key = $this->config->item('pass_key');
                    $aes = new CryptAES();
                    $aes->set_key(base64_decode($key));
                    $aes->require_pkcs5();
                    $decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));

                    $emailerstr  = $this->master_model->getRecords('emailer', array('emailer_name' => 'new_non_member_bulk'));
                    $newstring1  = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                    $newstring2  = str_replace("#REG_NUM#", "" . $member_array_record[0]['mem_mem_no'] . "", $newstring1);
                    $newstring3  = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                    $newstring4  = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                    $newstring6  = str_replace("#ADD1#", "" . $member_info_new[0]['address1'] . "", $newstring4);
                    $newstring7  = str_replace("#ADD2#", "" . $member_info_new[0]['address2'] . "", $newstring6);
                    $newstring8  = str_replace("#ADD3#", "" . $member_info_new[0]['address3'] . "", $newstring7);
                    $newstring9  = str_replace("#ADD4#", "" . $member_info_new[0]['address4'] . "", $newstring8);
                    $newstring10 = str_replace("#DISTRICT#", "" . $member_info_new[0]['district'] . "", $newstring9);
                    $newstring11 = str_replace("#CITY#", "" . $member_info_new[0]['city'] . "", $newstring10);
                    $newstring12 = str_replace("#STATE#", "" . $member_info_new[0]['state_name'] . "", $newstring11);
                    $newstring13 = str_replace("#PINCODE#", "" . $member_info_new[0]['pincode'] . "", $newstring12);
                    $newstring14 = str_replace("#EMAIL#", "" . $member_info_new[0]['email'] . "", $newstring13);
                    $newstring15 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
                    $newstring16 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
                    $newstring17 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
                    $newstring18 = str_replace("#MODE#", "" . $mode . "", $newstring17);
                    //$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
                    $newstring20 = str_replace("#PASS#", "" . $decpass . "", $newstring18);
                    //$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);
                    #-----------------------------------------E-learning msg ---------------------------------------------------------#
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

                    $final_str_pdf = $newstring21;
                    #-----------------------------------------E-learning msg end ----------------------------------------------------------#
                    //$final_str_pdf = "This is mail for new member";

                    $files_pdf    = array($attchpath_admitcard);
                    $info_arr_pdf = array('to' => $member_info_new[0]['email'],
                        //'to'=>'pawansing.pardeshi@esds.co.in',
                        'from'                     => 'noreply@iibf.org.in',
                        'subject'                  => 'Bulk Exam application',
                        'message'                  => $final_str_pdf,
                    );
                    $this->Emailsending->mailsend_attch($info_arr_pdf, $files_pdf);

                    log_bulk_admin($log_title = "Mail send1.", $log_message = $member_info_new[0]['email']);

                } elseif ($member_info_new[0]['regid'] != $member_array_record[0]['mem_mem_no']) {
                    // email for old member
                    //echo 'OLd member';
                    include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                    $key = $this->config->item('pass_key');
                    $aes = new CryptAES();
                    $aes->set_key(base64_decode($key));
                    $aes->require_pkcs5();
                    $decpass = $aes->decrypt(trim($member_info_new[0]['usrpassword']));

                    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'old_non_member_bulk'));
                    $newstring1 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                    $newstring2 = str_replace("#REG_NUM#", "" . $member_array_record[0]['mem_mem_no'] . "", $newstring1);
                    $newstring3 = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                    $newstring4 = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                    //$newstring5 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",$newstring4);
                    $newstring6  = str_replace("#ADD1#", "" . $member_info_new[0]['address1'] . "", $newstring4);
                    $newstring7  = str_replace("#ADD2#", "" . $member_info_new[0]['address2'] . "", $newstring6);
                    $newstring8  = str_replace("#ADD3#", "" . $member_info_new[0]['address3'] . "", $newstring7);
                    $newstring9  = str_replace("#ADD4#", "" . $member_info_new[0]['address4'] . "", $newstring8);
                    $newstring10 = str_replace("#DISTRICT#", "" . $member_info_new[0]['district'] . "", $newstring9);
                    $newstring11 = str_replace("#CITY#", "" . $member_info_new[0]['city'] . "", $newstring10);
                    $newstring12 = str_replace("#STATE#", "" . $member_info_new[0]['state_name'] . "", $newstring11);
                    $newstring13 = str_replace("#PINCODE#", "" . $member_info_new[0]['pincode'] . "", $newstring12);
                    $newstring14 = str_replace("#EMAIL#", "" . $member_info_new[0]['email'] . "", $newstring13);
                    $newstring15 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
                    $newstring16 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
                    $newstring17 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
                    $newstring18 = str_replace("#MODE#", "" . $mode . "", $newstring17);
                    //$newstring19 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",$newstring18);
                    $newstring20 = str_replace("#PASS#", "" . $decpass . "", $newstring18);
                    //$final_str_pdf = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",$newstring20);

                    #-----------------------------------------E-learning msg ---------------------------------------------------------#
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

                    $final_str_pdf = $newstring21;
                    #-----------------------------------------E-learning msg end ----------------------------------------------------------#

                    //$final_str_pdf = "This is mail for old member";

                    $files_pdf    = array($attchpath_admitcard);
                    $info_arr_pdf = array('to' => $member_info_new[0]['email'],
                        //'to'=>'prafull.tupe@esds.co.in',
                        'from'                     => 'noreply@iibf.org.in',
                        'subject'                  => 'Bulk Exam application1',
                        'message'                  => $final_str_pdf,
                    );
                    $this->Emailsending->mailsend_attch($info_arr_pdf, $files_pdf);
                    log_bulk_admin($log_title = "Mail send2.", $log_message = $member_info_new[0]['email']);
                    //echo $this->email->print_debugger();
                    //echo $final_str_pdf;
                    //echo '<br/>';
                }

            }

            $this->bulk_generate_admitcard_recursiv($utr_no, $id, $mem_count, $payment_amt, $payment_date, $mem_exam_id_arr);

        } else {
            exit;
        }

    } // end of function

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

    public function check_user()
    {
        $a = check_user_stat();
    }

}
