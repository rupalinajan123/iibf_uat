<?php
/********************************************************************
 * Description: COMMON FUNCTION FOR SETTLEMNT OF JAAIB CANIDATES WHIC WERE REFUNDAED
 * Created BY: VISHAL P, 2022-10-18
 ********************************************************************/
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
defined('BASEPATH') or exit('No direct script access allowed');

class Csc_exam_settl_model extends CI_Model
{
    public function settle($member_no,$receipt_no,$status,$transaction_no,$received_merchant_id,$r_merchant_txn,$r_product_id,$r_csc_id,$file1)
    {
        $this->load->helper('master_helper');
        $this->load->model('master_model');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->load->helper('custom_admitcard_helper');
        $this->load->helper('custom_invoice_helper');

            $cron_file_path = "./uploads/rahultest/"; 
            $fp1 = fopen($cron_file_path . '/' . $file1, 'a');
			fwrite($fp1, "\n***** CSC CSV Cron Execution Started -  ***** \n");
            $reversVar='reverse';
            echo'<br>here1';
            $payment_data = $this->master_model->getRecords('payment_transaction',
                array('member_regnumber' => $member_no,
                    'receipt_no'         => $receipt_no,
                 //   'status'                 => $status
                ));

                $checkMemberExist = $this->master_model->getRecords('member_registration',
                array('regnumber' => $member_no,
                ));
                fwrite($fp1, "\n checkMemberExist=".json_encode($checkMemberExist)."\n");
                if (count($checkMemberExist) > 0) {
                    $member_no=$checkMemberExist[0]['regnumber'];
                }
                else {
                $applicationNo = generate_NM_memreg($member_no);
                echo'<br>new regnumber generated='.$applicationNo;
                $update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
                $this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$member_no));
                $member_no=$applicationNo;

                fwrite($fp1, "\n generate_NM_memreg=".($member_no)."\n");
                }
                echo $this->db->last_query();
             //   exit;
            if (count($payment_data) > 0) {
                echo'<br>here2';
                //check if member again applied for exam in between
                $this->db->join('admit_card_details','admit_card_details.mem_exam_id=payment_transaction.ref_id');
                $this->db->where('admit_card_details.exm_cd','991');
                $double_payment_data = $this->master_model->getRecords('payment_transaction',
                array('payment_transaction.member_regnumber' => $member_no,
                       'payment_transaction.status'                 => 1,
                       'payment_transaction.date > '            => $payment_data[0]['date'],

                ),'');

                if (count($double_payment_data) > 0) {
                    echo'double payment found';
                    $log_title   = "csc exam settle-candidate already applied for exam again:" . $payment_data[0]['member_regnumber'];
                            $log_message = serialize($double_payment_data);
                            $rId         = $double_payment_data[0]['admitcard_id'];
                            $regNo       = $payment_data[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                            fwrite($fp1, "\n double_payment_data=".json_encode($double_payment_data)."\n");
                    return $reversVar;
                }
                $update_data1 = array('status' => '1', 'transaction_details' => 'success - '.$r_merchant_txn,'transaction_no'=>$transaction_no,'member_regnumber'=>$member_no,'callback'=>'S2S','auth_code' => '0300','customer_id'=>$received_merchant_id,'product_id'=>$r_product_id,'csc_id'=>$r_csc_id,'bankcode'=>'csc','paymode'=>'wallet');

                $this->master_model->updateRecord('payment_transaction', $update_data1, array('id' => $payment_data[0]['id']));

                fwrite($fp1, "\n update payment_transaction=".json_encode($update_data1)."\n");
//exit;
                $this->master_model->updateRecord('member_exam', array('regnumber'=>$member_no), array('id' => $payment_data[0]['ref_id']));
                fwrite($fp1, "\n update member_exam=".json_encode($member_no)."\n");
                $admitupdate      = array('mem_mem_no'=>$member_no);

                $this->master_model->updateRecord('admit_card_details', $admitupdate, array('mem_exam_id' => $payment_data[0]['ref_id']));

                fwrite($fp1, "\n update admit_card_details=".json_encode($admitupdate)."\n");

                $invoice_data = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $payment_data[0]['receipt_no']));
                echo'<br><pre>';print_r($invoice_data);
                $attachpath = '';
                if (count($invoice_data) > 0 && $invoice_data[0]['invoice_no'] == '') {
                    echo'<br>here3';
                    $invoiceNumber = generate_exam_invoice_number($invoice_data[0]['invoice_id']);
                    echo'<br>invoiceNumber='.$invoiceNumber;
                    if ($invoiceNumber) {
                        $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;

                        $update_data = array('invoice_no' => $invoiceNumber,  'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'),'transaction_no'=>$transaction_no,'member_no'=>$member_no);
                        $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $payment_data[0]['receipt_no']));

                        $attachpath = genarate_exam_invoice($invoice_data[0]['invoice_id']);

                        fwrite($fp1, "\n genarate_exam_invoice=".json_encode($update_data)."\n");
                    }

                }
                echo'<br>here4';
                $this->master_model->updateRecord('member_exam', array('pay_status' => '1'), array('id' => $payment_data[0]['ref_id']));

                $exam_admicard_details = $this->master_model->getRecords('admit_card_details', array('mem_exam_id' => $payment_data[0]['ref_id']));
                

                if (count($exam_admicard_details) > 0) {
                    fwrite($fp1, "\n exam_admicard_details=".json_encode($exam_admicard_details)."\n");
                    echo'<br>here5';
                    $msg          = '';
                    $sub_flag     = 1;
                    $sub_capacity = 1;
                    $this->session->set_userdata('csc_venue_flag','P');
                    foreach ($exam_admicard_details as $row) {

                       
                            $session_time=$row['time'];
                          //  echo $row['venueid'].'=='.$row['exam_date'].'=='.$session_time.'=='.$row['center_code'];
                        $capacity = csc_check_capacity($row['venueid'], $row['exam_date'], $session_time, $row['center_code']);
                        if ($capacity == 0) {
                            echo '<br>nocapacity';
                            #########get message if capacity is full##########
                            $log_title   = "CSC exam settle-Capacity full id:" . $payment_data[0]['member_regnumber'];
                            $log_message = serialize($exam_admicard_details);
                            $rId         = $payment_data[0]['ref_id'];
                            $regNo       = $payment_data[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                            $final_str = 'Hello Priyanka <br/><br/>';
                            $final_str .= 'CSC exam capacity issue occured. <br/> Function name: csc_exam_settl_model/settle.<br/>Member number is- ' . $payment_data[0]['member_regnumber'];
                            $final_str .= 'Venue Code is- ' . $row['venueid'];
                            $final_str .= '<br/><br/>';
                            $final_str .= 'Regards,';
                            $final_str .= '<br/>';
                            $final_str .= 'ESDS TEAM';
                            $info_arr = array('to' => 'priyana.dhikale@esds.co.in',
                                'from'                 => 'noreply@iibf.org.in',
                                'subject'              => 'CSC Exam settel capacity issue occured',
                                'message'              => $final_str,
                            );
                            $this->Emailsending->mailsend_attch($info_arr);
                            
                            fwrite($fp1, "\n nocapacity=".json_encode($row)."\n");

                            return $reversVar;
                        }
                    }
                } else {
                    echo'<br>here6';
                    $log_title   = "CSC Exam settle -admit card details not found 1:" . $payment_data[0]['member_regnumber'];
                    $log_message = serialize($exam_admicard_details);
                    $rId         = $exam_admicard_details[0]['admitcard_id'];
                    $regNo       = $payment_data[0]['member_regnumber'];
                    storedUserActivity($log_title, $log_message, $rId, $regNo);

                    fwrite($fp1, "\n admit card details not found =".json_encode($exam_admicard_details)."\n");

                    return $reversVar;
                }
                echo'<br>here7';
                $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                $exam_info     = $this->master_model->getRecords('member_exam', array('regnumber' => $payment_data[0]['member_regnumber'], 'member_exam.id' => $payment_data[0]['ref_id']), 'member_exam.exam_code,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,');
                $admitcard_pdf = '';

                if (count($exam_admicard_details) > 0 && $capacity > 0) {
                    echo'<br>here8';
                    fwrite($fp1, "\n exam_admicard_details &&  capacity=".json_encode($exam_admicard_details)."\n");
                    $password = random_password();
                    foreach ($exam_admicard_details as $row) {
                        //echo $row['venueid'].'==';
                        $get_venue_details = $this->master_model->getRecords('venue_master', array('venue_code' => $row['venueid']));
                        if (count($get_venue_details) == 0) {
                            echo 'venue not found';
                            $log_title   = "CSC exam settle-venue not found:" . $payment_data[0]['member_regnumber'];
                            $log_message = serialize($exam_admicard_details);
                            $rId         = $exam_admicard_details[0]['admitcard_id'];
                            $regNo       = $payment_data[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                            fwrite($fp1, "\n venue not found'=".json_encode($row)."\n");

                            return $reversVar;
                        }

                        $admit_card_details = $this->master_model->getRecords('admit_card_details', array('venueid' => $row['venueid'], 'exam_date' => $row['exam_date'], 'time' => $row['time'], 'mem_exam_id' => $payment_data[0]['ref_id'], 'sub_cd' => $row['sub_cd']));

                        if (count($admit_card_details) == 0) {
                            echo'<br>here9';

                            fwrite($fp1, "\n admit_card_details not found'=".json_encode($row)."\n");

                            $log_title   = "csc exam settle-admit card details not found:" . $payment_data[0]['member_regnumber'];
                            $log_message = serialize($exam_admicard_details);
                            $rId         = $admit_card_details[0]['admitcard_id'];
                            $regNo       = $payment_data[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            return $reversVar;
                        }
                       // echo'<br><pre>';print_r($get_venue_details);exit;
                            echo $exam_info[0]['exam_code'].'=='.$exam_info[0]['exam_center_code'].'=='.$get_venue_details[0]['venue_code'].'=='. $get_venue_details[0]['exam_date'].'=='.$admit_card_details[0]['time'].'=='.$exam_info[0]['exam_period'].'=='.$row['sub_cd'].'=='.$get_venue_details[0]['session_capacity'].'=='.$admit_card_details[0]['admitcard_id'];

                        $seat_number = getseat($exam_info[0]['exam_code'], $exam_info[0]['exam_center_code'], $get_venue_details[0]['venue_code'], $get_venue_details[0]['exam_date'], $admit_card_details[0]['time'], $exam_info[0]['exam_period'], $row['sub_cd'], $get_venue_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);

                        if ($seat_number != '') {
                            echo'<br>here10';
                            fwrite($fp1, "\n seat_number'=".($seat_number)."\n");

                            $final_seat_number = $seat_number;
                            $update_data2      = array('pwd' => $password, 'seat_identification' => $final_seat_number, 'remark' => 1, 'modified_on' => date('Y-m-d H:i:s'),'mem_mem_no'=>$member_no);
                            $this->master_model->updateRecord('admit_card_details', $update_data2, array('admitcard_id' => $admit_card_details[0]['admitcard_id']));

                            fwrite($fp1, "\n update admit_card_details'=".json_encode($update_data2)."\n");

                        } else {
                            echo'<br>here11';
                            $admit_card_details = $this->master_model->getRecords('admit_card_details', array('admitcard_id' => $row['admitcard_id'], 'remark' => 1));
                            if (count($admit_card_details) > 0) {
                                
                                fwrite($fp1, "\n CSC exam settle-Seat number already allocated id=".json_encode($row)."\n");
                                
                                $log_title   = "CSC exam settle-Seat number already allocated id:" . $payment_data[0]['member_regnumber'];
                                $log_message = serialize($row);
                                $rId         = $admit_card_details[0]['admitcard_id'];
                                $regNo       = $payment_data[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                            } else {
                                echo'<br>here12';

                                fwrite($fp1, "\n CSC exam  settle-Fail user seat allocation id=".json_encode($row)."\n");
                                $log_title   = "CSC exam  settle-Fail user seat allocation id:" . $payment_data[0]['member_regnumber'];
                                $log_message = serialize($row);
                                $rId         = $payment_data[0]['member_regnumber'];
                                $regNo       = $payment_data[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                                return $reversVar;
                            }
                        }
                    }
                   // echo $member_no.'=='.$exam_info[0]['exam_code'].'=='.$exam_info[0]['exam_period'];
                    $admitcard_pdf = genarate_admitcard($member_no, $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
                    echo '<br>admitcard_pdf='.$admitcard_pdf;

                    fwrite($fp1, "\n CSC exam  settle-admitcard_pdf=".($admitcard_pdf)."\n");
                }

                if ($admitcard_pdf != '' && $attachpath != '') {
                        echo '<br>Invoice and admit card generated';
                    $result = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no), 'email');

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
                        fwrite($fp1, "\n CSC exam  settle-mail sent=".($final_str)."\n");
                      //  $this->Emailsending->mailsend_attch($info_arr, $files);
                    }

                }

            }
         
            return 1;
    }
}
