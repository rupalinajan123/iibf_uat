<?php
/********************************************************************
 * Description: COMMON FUNCTION FOR SETTLEMNT OF JAAIB CANIDATES WHIC WERE REFUNDAED
 * Created BY: VISHAL P, 2022-10-18
 ********************************************************************/

defined('BASEPATH') or exit('No direct script access allowed');

class Jaib_recovery_model extends CI_Model
{
    public function settle($member_no)
    {
        $this->load->helper('master_helper');
        $this->load->model('master_model');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->load->model('billdesk_pg_model');
        $this->load->model('billdesk_pg_model');

        $recovery_data = $this->master_model->getRecords('jaiib_exam_recovery_master', array('pay_status' => 1, 'member_no' => $member_no));

        if (count($recovery_data) > 0) {

            // $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($recovery_data[0]['new_receipt_no']);
            // if (count($qry_api_response) && array_key_exists('auth_status', $qry_api_response) && $qry_api_response['auth_status'] == '0300' || 1) {
            $payment_data = $this->master_model->getRecords('payment_transaction',
                array('member_regnumber' => $recovery_data[0]['member_no'],
                    'id'                     => $recovery_data[0]['pay_txn_id'],
                    'status'                 => '3',
                    'date >='                => '2022-09-01'), 'id,ref_id,member_regnumber');

            if (count($payment_data) > 0) {

                $update_data1 = array('status' => '1', 'transaction_no' => $recovery_data[0]['new_transaction_no'], 'receipt_no' => $recovery_data[0]['new_receipt_no'], 'transaction_details' => 'success - Transaction Successful');
                $this->master_model->updateRecord('payment_transaction', $update_data1, array('id' => $recovery_data[0]['pay_txn_id']));

                $invoice_data = $this->master_model->getRecords('exam_invoice', array('pay_txn_id' => $recovery_data[0]['pay_txn_id']));

                $attachpath = '';
                if (count($invoice_data) > 0 && $invoice_data[0]['invoice_no'] == '') {

                    $invoiceNumber = generate_exam_invoice_number($invoice_data[0]['invoice_id']);

                    if ($invoiceNumber) {
                        $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;

                        $update_data = array('invoice_no' => $invoiceNumber, 'receipt_no' => $recovery_data[0]['new_receipt_no'], 'transaction_no' => $recovery_data[0]['new_transaction_no'], 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                        $this->master_model->updateRecord('exam_invoice', $update_data, array('pay_txn_id' => $recovery_data[0]['pay_txn_id']));

                        $attachpath = genarate_exam_invoice($invoice_data[0]['invoice_id']);

                    }

                }

                $this->master_model->updateRecord('member_exam', array('pay_status' => '1'), array('id' => $payment_data[0]['ref_id']));

                $exam_admicard_details = $this->master_model->getRecords('admit_card_details', array('mem_exam_id' => $payment_data[0]['ref_id']));

                if (count($exam_admicard_details) > 0) {

                    $msg          = '';
                    $sub_flag     = 1;
                    $sub_capacity = 1;
                    foreach ($exam_admicard_details as $row) {
                        $capacity = check_capacity($row['venueid'], $row['exam_date'], $row['time'], $row['center_code']);
                        if ($capacity == 0) {
                            #########get message if capacity is full##########
                            $log_title   = "JAAIB settle-Capacity full id:" . $payment_data[0]['member_regnumber'];
                            $log_message = serialize($exam_admicard_details);
                            $rId         = $payment_data[0]['ref_id'];
                            $regNo       = $payment_data[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                            $final_str = 'Hello Vishal <br/><br/>';
                            $final_str .= 'JAIIB capacity issue occured. <br/> Function name: Jaib_recovery_model/settle.<br/>Member number is- ' . $payment_data[0]['member_regnumber'];
                            $final_str .= 'Venue Code is- ' . $row['venueid'];
                            $final_str .= '<br/><br/>';
                            $final_str .= 'Regards,';
                            $final_str .= '<br/>';
                            $final_str .= 'ESDS TEAM';
                            $info_arr = array('to' => 'vishal.phadol@esds.co.in',
                                'from'                 => 'noreply@iibf.org.in',
                                'subject'              => 'JAIIB capacity issue occured',
                                'message'              => $final_str,
                            );
                            $this->Emailsending->mailsend_attch($info_arr);

                        }
                    }
                } else {

                    $log_title   = "JAAIB settle-admit card details not found 1:" . $payment_data[0]['member_regnumber'];
                    $log_message = serialize($exam_admicard_details);
                    $rId         = $admit_card_details[0]['admitcard_id'];
                    $regNo       = $payment_data[0]['member_regnumber'];
                    storedUserActivity($log_title, $log_message, $rId, $regNo);

                }

                $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                $exam_info     = $this->master_model->getRecords('member_exam', array('regnumber' => $payment_data[0]['member_regnumber'], 'member_exam.id' => $payment_data[0]['ref_id']), 'member_exam.exam_code,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,');
                $admitcard_pdf = '';

                if (count($exam_admicard_details) > 0 && $capacity > 0) {
                    $password = random_password();
                    foreach ($exam_admicard_details as $row) {

                        $get_subject_details = $this->master_model->getRecords('venue_master', array('venue_code' => $row['venueid'], 'exam_date' => $row['exam_date'], 'session_time' => $row['time']));
                        if (count($get_subject_details) == 0) {
                            $log_title   = "JAAIB settle-venue not found:" . $payment_data[0]['member_regnumber'];
                            $log_message = serialize($exam_admicard_details);
                            $rId         = $admit_card_details[0]['admitcard_id'];
                            $regNo       = $payment_data[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                        }

                        $admit_card_details = $this->master_model->getRecords('admit_card_details', array('venueid' => $row['venueid'], 'exam_date' => $row['exam_date'], 'time' => $row['time'], 'mem_exam_id' => $payment_data[0]['ref_id'], 'sub_cd' => $row['sub_cd']));

                        if (count($admit_card_details) == 0) {
                            $log_title   = "JAAIB settle-admit card details not found:" . $payment_data[0]['member_regnumber'];
                            $log_message = serialize($exam_admicard_details);
                            $rId         = $admit_card_details[0]['admitcard_id'];
                            $regNo       = $payment_data[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                        }

                        $seat_number = getseat($exam_info[0]['exam_code'], $exam_info[0]['exam_center_code'], $get_subject_details[0]['venue_code'], $get_subject_details[0]['exam_date'], $get_subject_details[0]['session_time'], $exam_info[0]['exam_period'], $row['sub_cd'], $get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);

                        if ($seat_number != '') {
                            $final_seat_number = $seat_number;
                            $update_data2      = array('pwd' => $password, 'seat_identification' => $final_seat_number, 'remark' => 1, 'modified_on' => date('Y-m-d H:i:s'));
                            $this->master_model->updateRecord('admit_card_details', $update_data2, array('admitcard_id' => $admit_card_details[0]['admitcard_id']));

                        } else {
                            $admit_card_details = $this->master_model->getRecords('admit_card_details', array('admitcard_id' => $admit_card_details[0]['admitcard_id'], 'remark' => 1));
                            if (count($admit_card_details) > 0) {
                                $log_title   = "JAAIB settle-Seat number already allocated id:" . $payment_data[0]['member_regnumber'];
                                $log_message = serialize($exam_admicard_details);
                                $rId         = $admit_card_details[0]['admitcard_id'];
                                $regNo       = $payment_data[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                            } else {
                                $log_title   = "JAAIB settle-Fail user seat allocation id:" . $payment_data[0]['member_regnumber'];
                                $log_message = serialize($exam_admicard_details);
                                $rId         = $payment_data[0]['member_regnumber'];
                                $regNo       = $payment_data[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                            }
                        }
                    }
                    $admitcard_pdf = genarate_admitcard($recovery_data[0]['member_no'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
                }

                if ($admitcard_pdf != '' && $attachpath != '') {

                    $result = $this->master_model->getRecords('member_registration', array('regnumber' => $payment_data[0]['member_regnumber']), 'email');

                    $files     = array($attachpath, $admitcard_pdf);
                    $final_str = '';
                    $final_str .= 'Dear Candidate,' . "<br><br>";
                    $final_str .= 'You have successfully registered for the ' . $exam_info[0]['description'] . ' exam, please find attached admit card and invoice details';
                    if ($result[0]['email'] != '') {
                        $info_arr = array('to' => $result[0]['email'],
                            'cc'                   => 'iibfdevp@esds.co.in',
                            'from'                 => 'noreply@iibf.org.in',
                            'subject'              => 'Exam Enrollment Acknowledgment',
                            'message'              => $final_str,
                        );
                        $this->Emailsending->mailsend_attch($info_arr, $files);
                    }

                }

            }
            // }
        }

    }
}
