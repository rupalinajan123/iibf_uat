<?php
/*
 * Controller Name	:	Cron IPPB Exam Settlement
 * Created By		:	Priyanka Dhikale
 * Created Date		:	14-july-2023
 * Last Update 		:   14-july-2023
*/
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
defined('BASEPATH') or exit('No direct script access allowed');
class Cron_ippb_exam_settlement extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        	$this->load->library('upload');
			$this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('general_helper');
			$this->load->helper('master_helper');
			$this->load->model('master_model');		
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
			$this->load->helper('date');
			$this->load->model('billdesk_pg_model');
			$this->sms_template_id = '';
			$this->load->model('refund_after_capacity_full');
			$this->load->helper('update_image_name_helper');
      		date_default_timezone_set("Asia/Kolkata");
      //  

    }

  
	public function process()
    {
		$fromDateTime	=	date('Y-m-d H:i:s', strtotime('-45 minutes'));
		$toDateTime	=	date('Y-m-d H:i:s', strtotime('-30 minutes'));
        
		$exam_code=997;
        $status=1;
		$foundPendingPaySuccess=0;
		$records = $this->master_model->getRecords('payment_transaction', array( 'exam_code ' => $exam_code,'status' => $status, 'date >=' => $fromDateTime, 'date <=' => $toDateTime));

	/*$records = $this->master_model->getRecords('payment_transaction', array( 'exam_code ' => $exam_code,'status' => $status, 'date >=' => '2023-07-14:12:00:00'));*/
	//	echo '<pre>';print_r($exam_admicard_details);exit;

		$cron_file_path = "./uploads/rahultest/"; 
		$current_date=date('ymdhis');
		$file1 = "ippb_exam_settlment_logs_" . $current_date . ".txt";
		$fp = fopen($cron_file_path . '/' . $file1, 'a');
		echo 'Please check logs in '.$cron_file_path . '/' . $file1;
		$recordsFound=0;
        if (count($records)) {
			$final_str = 'Hello <br/><br/>';
			
            foreach ($records as $key => $c_row) {

				$rId = $c_row['ref_id'];
				$regNo = $c_row['member_regnumber'];
				$exam_admicard_details = $this->master_model->getRecords('admit_card_details', array( 'mem_exam_id' => $c_row['ref_id']));

				if(!empty($exam_admicard_details) && $exam_admicard_details[0]['admitcard_image']=='') {
						$exam_admicard_details=(array)$exam_admicard_details;
						$row	=	$exam_admicard_details[0];
						$recordsFound=1;
						echo $str = "\n processing for mem_exam_id ".$c_row['ref_id']."\n";
								fwrite($fp, $str);
						$member_no			=	$c_row['member_regnumber'];
						//$csc_txn	=	'N';//$value['transaction_no'];
						echo'<pre>request=';print_r($c_row);

						$responsedata = $this->billdesk_pg_model->billdeskqueryapi($c_row['receipt_no']);
						//echo '<pre>';print_r($responsedata);exit;
						$receipt_no = $c_row['receipt_no'];
						
						$refundInitiated=0;

						if(isset($responsedata['refundInfo']) && !empty($responsedata['refundInfo']))
							{
								$examInvoiceDetails = $this->master_model->getRecords('exam_invoice', array( 'receipt_no' => $c_row['receipt_no']));

								if(!empty($examInvoiceDetails) && $examInvoiceDetails[0]['invoice_image']!='') {

									$final_str 	.=	'IPPB - Refund initiated at billdesk end but invoice is generated in ESDS db. so need to do checker maker for receipt no - '.$c_row['receipt_no'].'<br>';
										echo $final_str;
									$desc	=	json_encode($c_row);
									$log_title   = "IPPB - Refund initiated at billdesk end but invoice is generated in ESDS db";
									$log_message = serialize($desc);
									
									storedUserActivity($log_title, $log_message, $rId, $regNo);

								}

								$refundInitiated=1;
							
									
							}
							if($refundInitiated==0 && isset($responsedata) && count($responsedata) > 0 && $responsedata['auth_status'] == '0300') {

								echo $str = "\n processing for receipt no $receipt_no\n";
								fwrite($fp, $str);

								$MerchantOrderNo = $c_row['receipt_no']; 
								$transaction_no  = $c_row['transaction_no'];
								$attachpath=$invoiceNumber=$admitcard_pdf='';

								$exam_code=$c_row['exam_code'];
								$reg_id=$c_row['member_regnumber'];

								
								$capacity=csc_check_capacity($row['venueid'],$row['exam_date'],$row['time'],$row['center_code']);
								if($capacity==0)
								{
									$log_title ="IPPB - nonreg Capacity full id:".$c_row['member_regnumber'];
									$log_message = serialize($row);
									
									storedUserActivity($log_title, $log_message, $rId, $regNo);

									$recordsFound=1;
									$final_str 	.=	'IPPB - capacity full. need to refund '.$c_row['receipt_no'].'<br>';
								}
								else {

									$this->db->or_where('regnumber',$reg_id);
									$this->db->or_where('regid',$reg_id);
									$this->db->where('excode',$exam_code);
									$user_info=$this->master_model->getRecords('member_registration',array());
									if($user_info[0]['regnumber'] !== ''){
										$applicationNo = $user_info[0]['regnumber'];
									}else{
										$applicationNo = generate_NM_memreg($reg_id);
									}

									######### payment Transaction ############
									$update_data = array('member_regnumber'=>$applicationNo,'auth_code' => '0300');
									$this->db->order_by('id','DESC');
									$this->db->limit(1);	
									$this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));

									######### Exam Invoice Transaction ############
									$update_data = array('transaction_no'=>$transaction_no);
									$this->db->where('receipt_no',$MerchantOrderNo);
									$this->db->where('exam_code',$exam_code );
									$this->db->order_by('invoice_id','DESC');
									$this->db->limit(1);	
									$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));

									########## Update Member Registration#############								
									// echo 'before update mem_reg'.$reg_id; 
									$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
									$this->db->order_by('regid','DESC');
									$this->db->limit(1);
									$this->db->or_where('regnumber',$reg_id);
									$this->db->or_where('regid',$reg_id);
									$this->db->where('excode',$exam_code);
									$this->master_model->updateRecord('member_registration',$update_mem_data,array());

									##########Update Member Exam#############
									$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
									$this->db->order_by('id','DESC');
									$this->db->limit(1);
									$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$reg_id));

									########## Generate Invoice #############
									$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$applicationNo),'transaction_no,date,amount,id');
									//get invoice	
									$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
									$invoice_get_query= $this->db->last_query();
									$log_title ="IPPB NONreg invoice get query :".$invoice_get_query;
									$log_message = serialize($getinvoice_number);
									
									storedUserActivity($log_title, $log_message, $rId, $regNo);

									if(count($getinvoice_number) > 0)
									{
										$invoiceNumber =generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
										}
										$update_data = array('invoice_no' => $invoiceNumber,'member_no'=>$applicationNo,'date_of_invoice'=>date('Y-m-d H:i:s'),'modified_on'=>date('Y-m-d H:i:s'));
										/*Add code trans_start & trans_complete :   */
										$this->db->where('pay_txn_id',$payment_info[0]['id']);
										$this->db->order_by('invoice_id','DESC');
										$this->db->limit(1);
										$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
										
										
										$invoice_update_query= $this->db->last_query();
										$log_title ="IPPB NONreg invoice update query :".$invoice_update_query;
										$log_message = serialize($update_data);
										
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
										
										$attachpath=genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
										$log_title ="IPPB NONreg invoice Img path";
										$log_message = serialize($attachpath);
										
										storedUserActivity($log_title, $log_message, $rId, $regNo);	
									}
									$this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period','left');
									$this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info=$this->master_model->getRecords('member_exam',array('member_exam.id'=>$c_row['ref_id']),'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

									$password=random_password();
									foreach($exam_admicard_details as $row)
									{
										$query='(exam_date = "0000-00-00" OR exam_date = "")';
										$this->db->where($query);
										$this->db->where('session_time=','');
										$this->db->where('venue_flag','P');
										$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$row['venueid'],'center_code'=>$row['center_code']));
										
										$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$row['venueid'],'exam_date'=>$row['exam_date'],'time'=>$row['time'],'mem_exam_id'=>$c_row['ref_id'],'sub_cd'=>$row['sub_cd']));
										
										//echo $this->db->last_query().'<br>';
										$seat_number=getseat($exam_info[0]['exam_code'],$exam_info[0]['exam_center_code'],$get_subject_details[0]['venue_code'],$admit_card_details[0]['exam_date'],$admit_card_details[0]['time'],$exam_info[0]['exam_period'],$row['sub_cd'],$get_subject_details[0]['session_capacity'],$admit_card_details[0]['admitcard_id']);
							
										if($seat_number!='')
										{
											$final_seat_number = $seat_number;
											$update_data = array('mem_mem_no'=>$applicationNo,'pwd' => $password,'seat_identification' => $final_seat_number,'remark'=>1,'modified_on'=>date('Y-m-d H:i:s'));
											$this->db->order_by('mem_exam_id','DESC');
											$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));

											##############Get Admit card#############
											$admitcard_pdf=genarate_admitcard($applicationNo,$exam_info[0]['exam_code'],$exam_info[0]['exam_period']);
											$log_title ="IPPB nonreg admit cart image path:";
											$log_message = serialize($admitcard_pdf);
											
											storedUserActivity($log_title, $log_message, $rId, $regNo);

											if($exam_info[0]['exam_mode']=='ON')
											{$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
											{$mode='Offline';}
											else{$mode='';}
											//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
											$month = date('Y')."-".substr($exam_info[0]['exam_month'],4);
											$exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
											//Query to get Medium	
											$this->db->where('exam_code',$exam_code);
											$this->db->where('exam_period',$exam_info[0]['exam_period']);
											$this->db->where('medium_code',$exam_info[0]['exam_medium']);
											$this->db->where('medium_delete','0');
											$medium=$this->master_model->getRecords('medium_master','','medium_description');
											//Query to get Payment details	
											
											//Query to get user details
											$this->db->join('state_master','state_master.state_code=member_registration.state');
											$this->db->or_where('regnumber',$reg_id);
											$this->db->or_where('regid',$reg_id);
											//$this->db->where('excode','997');
											$result=$this->master_model->getRecords('member_registration',array(),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,usrpassword');	
											
											########get Old image Name############
											$log_title ="IPPB nonreg OLD Image :".$reg_id;
											$log_message = serialize($result);
											
											storedUserActivity($log_title, $log_message, $rId, $regNo);	
											
											$upd_files = array();
											$photo_file = 'p_'.$applicationNo.'.jpg';
											$sign_file = 's_'.$applicationNo.'.jpg';
											$proof_file = 'pr_'.$applicationNo.'.jpg';
											
											
											$chk_photo = update_image_name("./uploads/photograph/", $result[0]['scannedphoto'], $photo_file); //update_image_name_helper.php
											if($chk_photo != "") { $upd_files['scannedphoto'] = $chk_photo; }
											
											
											$chk_sign = update_image_name("./uploads/scansignature/", $result[0]['scannedsignaturephoto'], $sign_file); //update_image_name_helper.php
											if($chk_sign != "") { $upd_files['scannedsignaturephoto'] = $chk_sign; }
											
											
											$chk_proof = update_image_name("./uploads/idproof/", $result[0]['idproofphoto'], $proof_file); //update_image_name_helper.php
											if($chk_proof != "") { $upd_files['idproofphoto'] = $chk_proof; }
											
											if(count($upd_files)>0)
											{
												$this->db->or_where('regnumber',$reg_id);
												$this->db->or_where('regid',$reg_id);
												$this->db->where('excode','997');
												$this->master_model->updateRecord('member_registration',$upd_files,array());
												$log_title ="IPPB nonreg PICS Update :".$reg_id;
												$log_message = serialize($this->db->last_query());
												
												storedUserActivity($log_title, $log_message, $rId, $regNo);	
											}
											else
											{
													$upd_files['scannedphoto'] = $photo_file;
													$upd_files['scannedsignaturephoto'] = $sign_file;	
													$upd_files['idproofphoto'] = $proof_file;
													$this->db->or_where('regnumber',$reg_id);
													$this->db->or_where('regid',$reg_id);
													$this->db->where('excode','997');
													$this->master_model->updateRecord('member_registration',$upd_files,array());
													$log_title ="IPPB nonreg MANUAL PICS Update :".$reg_id;
													$log_message = serialize($upd_files);
													
													storedUserActivity($log_title, $log_message, $rId, $regNo);	
											}
										}
										else
										{
											$log_title ="IPPB nonreg Fail user seat allocation id:".$applicationNo;
											$log_message = '';
											
											storedUserActivity($log_title, $log_message, $rId, $regNo);

											$recordsFound=1;
											$final_str 	.=	'IPPB -  nonreg Fail user seat allocation - recipt no. '.$c_row['receipt_no'].'<br>';
										}
									}
								}
								
							}
					
				}
				else {
					$recordsFound=1;
					$final_str 	.=	'IPPB - Admitcard record not found for IPPB payment recipt no. '.$c_row['receipt_no'].'<br>';
					
					
					$desc	=	json_encode($c_row);
					$log_title   = "IPPB - Admitcard record not found for IPPB payment";
					$log_message = serialize($desc);
					$rId         = $c_row['member_regnumber'];
					$regNo       = $c_row['member_regnumber'];
					storedUserActivity($log_title, $log_message, $rId, $regNo);
				}
				

            }
			if($recordsFound==1) { 
				$files=array();
					$final_str .= 'Regards,';
					$final_str .= '<br/>';
					$final_str .= 'ESDS TEAM';
				$info_arr = array('to' => 'priyanka.dhikale@esds.co.in',
						'from'                 => 'iibfdevp@esds.co.in',
						'subject'              => 'ippb settelment cron executed',
						'message'              => $final_str,
					);
					
				 	$mail_flag=$this->Emailsending->mailsend_attch($info_arr,$files);
					 if($mail_flag)
					 {
						echo'mail sent';
					 }
			}
			
					
        }

      

    }
	
	
	
}
