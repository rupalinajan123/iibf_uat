<?php
/*
 * Module Name	:	Refund Request Initiation.
 * Author Name	:	Chaitali Jadhav
 */

// https://http://iibf.teamgrowth.net/creditnote/refundrequest/refundRequest.php
// /usr/local/bin/php /home/supp0rttest//public_html/application/controllers/creditnote/refundrequest.php
defined('BASEPATH') OR exit('No direct script access allowed');
class RefundRequest extends CI_Controller {


	private $USERDATA=array();		
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('creditnote_admin')) {
			redirect('creditnote/admin/Login');
		}else{
			$UserData = $this->session->userdata('creditnote_admin');
			if($UserData['admin_user_type'] == 'Checker'){
				redirect('creditnote/admin/Login');
			}
		}
		$this->UserData = $this->session->userdata('creditnote_admin');
		$this->UserID = $this->UserData['id'];
		$this->load->model('UserModel');
		$this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
		
	}
	
	public function index()
	{	
		$UserData = $this->session->userdata('creditnote_admin');
		$MakerData = $UserData['id'];
		$Makername = $UserData['name'];
		$transaction_details_arr = array();
		$transaction_no = $module_name = $pay_type = $exam_code = $invoice_no = $exam_period = $amount = $date_of_invoice = $member_regnumber = '';
		
		
		if (isset($_POST['btnGetDetails'])) 
		{
			$this->form_validation->set_rules('transaction_no','Transaction Number','required|callback_check_transactionduplication|xss_clean');
			if($this->form_validation->run() == TRUE)
			{
				/* Get Details from `payment_transaction` DB Table */
				$this->db->join('pay_type_master','pay_type_master.pay_type=payment_transaction.pay_type');
				$this->db->join('exam_invoice','exam_invoice.receipt_no=payment_transaction.receipt_no');
				$this->db->limit('1');
				$transaction_details = $this->master_model->getRecords('payment_transaction', array(
				'payment_transaction.transaction_no' => $this->input->post('transaction_no')  ,
				'status' => '1'
			),'module_name,pay_type_master.pay_type,exam_invoice.exam_code,exam_invoice.exam_period,exam_invoice.invoice_no,amount,member_regnumber,exam_invoice.date_of_invoice');
			
			if(count($transaction_details)>0)
			{
				$transaction_no=$this->input->post('transaction_no');
				$module_name =$transaction_details[0]['module_name'];
				$pay_type =$transaction_details[0]['pay_type'];
				$exam_code =$transaction_details[0]['exam_code'];
				$invoice_no =$transaction_details[0]['invoice_no'];
				$exam_period =$transaction_details[0]['exam_period'];
				$amount =$transaction_details[0]['amount'];
				$member_regnumber =$transaction_details[0]['member_regnumber'];
				$date_of_invoice =$transaction_details[0]['date_of_invoice'];
				
			 }
		   }
		} 
		else 
		{
			$data['validation_errors'] = '';
			/* Check Server-Side Validations */
			if(isset($_POST['btnSubmit']))
			{ 	
				$this->form_validation->set_rules('transaction_no','Transaction Number','required|callback_check_transactionduplication|xss_clean');
				$this->form_validation->set_rules('req_title', 'Title', 'trim|required|max_length[50]');
				$this->form_validation->set_rules('req_desc','Description','required');
				
				if ($this->form_validation->run() == TRUE)
				{
					$title = $this->input->post('req_title');
					$description = $this->input->post('req_desc');
					$transaction_no = $this->input->post('transaction_no');
					$reason = trim($this->input->post('req_reason1'));
					$req_module = $this->input->post('pay_type');
					$req_member_no = $this->input->post('req_member_no');
					
					if($this->input->post('req_exceptional_case')=='YES')
					{
						$req_exceptional_case = $this->input->post('req_exceptional_case');
					}
					else
					{
						$req_exceptional_case = 'NO';

					}
							
						if (isset($_FILES['scannedphoto1']['name']))
						{
			    				$img = 'scannedphoto1';
								$new_filename = $_FILES['scannedphoto1']['name'];
								$config = array(
									'upload_path' => './uploads/refund_request/',
									'allowed_types' => 'jpeg|jpg|png|gif',
									'file_name' => $new_filename,
									'max_size' => 0,
									'overwrite'=>TRUE
								);
								$this->upload->initialize($config);
							if ($this->upload->do_upload('scannedphoto1'))
							{
									$dt = $this->upload->data();
									$file = $dt['file_name'];
									$file_name = $dt['file_name'];
									$outputphoto1 = base_url() . "./uploads/refund_request/" . $file_name;
									
							}
							else
							{
									//$this->session->set_flashdata('error', 'Cancel refund Image  :' . $this->upload->display_errors());
									//echo $this->upload->display_errors();
							}
						}
						if (isset($_FILES['scannedphoto2']['name']))
						{
								$img = 'scannedphoto2';
								$new_filename = $_FILES['scannedphoto2']['name'];
								$config = array(
									'upload_path' => './uploads/refund_request/',
									'allowed_types' => 'jpeg|jpg|png|gif',
									'file_name' => $new_filename,
									'max_size' => 0,
									'overwrite'=>TRUE
								);
								$this->upload->initialize($config);
							if ($this->upload->do_upload('scannedphoto2'))
							{
									$dt = $this->upload->data();
									$file = $dt['file_name'];
									$file_name = $dt['file_name'];
									$outputphoto1 = base_url() . "./uploads/refund_request/" . $file_name;
									
							}
							else
							{
									//$this->session->set_flashdata('error', 'Cancel refund Image  :' . $this->upload->display_errors());
									//echo $this->upload->display_errors();
							}
						}
						if (isset($_FILES['scannedphoto3']['name']))
						{
								$img = 'scannedphoto3';
								$new_filename = $_FILES['scannedphoto3']['name'];
								$config = array(
									'upload_path' => './uploads/refund_request/',
									'allowed_types' => 'jpeg|jpg|png|gif',
									'file_name' => $new_filename,
									'max_size' => 0,
									'overwrite'=>TRUE
								);
								$this->upload->initialize($config);
							if ($this->upload->do_upload('scannedphoto3'))
							{
									$dt = $this->upload->data();
									$file = $dt['file_name'];
									$file_name = $dt['file_name'];
									$outputphoto1 = base_url() . "./uploads/refund_request/" . $file_name;
									//$this->session->set_userdata('imageinfo', $dt);
							}
							else
							{
									//$this->session->set_flashdata('error', 'Cancel refund Image  :' . $this->upload->display_errors());
									//echo $this->upload->display_errors();
							}
						}
						if (isset($_FILES['scannedphoto4']['name']))
						{
								$img = 'scannedphoto4';
								$new_filename = $_FILES['scannedphoto4']['name'];
								$config = array(
									'upload_path' => './uploads/refund_request/',
									'allowed_types' => 'jpeg|jpg|png|gif',
									'file_name' => $new_filename,
									'max_size' => 0,
									'overwrite'=>TRUE
								);
								$this->upload->initialize($config);
							if ($this->upload->do_upload('scannedphoto4'))
							{
									$dt = $this->upload->data();
									$file = $dt['file_name'];
									$file_name = $dt['file_name'];
									$outputphoto1 = base_url() . "./uploads/refund_request/" . $file_name;
									//$this->session->set_userdata('imageinfo', $dt);
							}
							else
							{
									//$this->session->set_flashdata('error', 'Cancel refund Image  :' . $this->upload->display_errors());
									//echo $this->upload->display_errors();
							}
						}
						$insert_array = array('req_title'=>$title,
												'req_desc'=>$description,
												'transaction_no'=>$transaction_no,
												'req_reason'=>$reason,
												'req_member_no'=>$req_member_no,
												'req_exceptional_case'=>$req_exceptional_case,
												'req_module' => $req_module,
												// 'image_name1' => $_FILES['scannedphoto1']['name'],
												// 'image_name2' => $_FILES['scannedphoto2']['name'],
												// 'image_name3' => $_FILES['scannedphoto3']['name'],
												// 'image_name4' => $_FILES['scannedphoto4']['name'],
												'req_maker_id' => $MakerData,
												'req_created_on'=>date('Y-m-d H:i:s')
											);
										
							/* Insert Details in `maker_checker` DB Table */
							 if($last_id = $this->master_model->insertRecord('maker_checker' , $insert_array , true))
							 {
								 
								 // Insert PK of maker_checker table in the config_maker_checker table
								 $config_insert_arr = array('maker_checker_id'=>$last_id);
								 $config_last_id = $this->master_model->insertRecord('config_maker_checker',$config_insert_arr, true);
								 
								 // Gen. and Update REQ ID
								 $req_id = 'REQ_'.$config_last_id;
								 $req_id_data = array('req_id' => $req_id);
								 $where = array('id' => $last_id);
                				 $this->master_model->updateRecord('maker_checker',$req_id_data,$where); 		


                			$this->db->join('pay_type_master','pay_type_master.pay_type=payment_transaction.pay_type');
							$this->db->join('exam_invoice','exam_invoice.receipt_no=payment_transaction.receipt_no');
							$this->db->limit('1');
								$transaction_details = $this->master_model->getRecords('payment_transaction', array(
								'payment_transaction.transaction_no' => $transaction_no  ,
								'status' => '1'
							),'module_name,pay_type_master.pay_type,exam_invoice.exam_code,exam_invoice.exam_period,exam_invoice.invoice_no,amount,exam_invoice.date_of_invoice');
							
							if(count($transaction_details)>0)
							{
								$invoice_no =$transaction_details[0]['invoice_no'];
								$amount =$transaction_details[0]['amount'];
								
							 }
                				
								// $config['mailtype'] = 'html';
								// $this->email->initialize($config);
        //         				$this->email->from('akshay.shirke@esds.co.in', 'Akshay');
								// $this->email->to('swati.watpade@esds.co.in');

								// $this->email->cc('akshay.shirke@esds.co.in');
								// //$this->email->bcc('them@their-example.com');

								// $this->email->subject('Refund Request initialize');
								 $message = '<html>Dear Checker,<br/><br/>Refund request having following details is initiated by '.$Makername.'<br><br>
								     1. Title: '.$title.'<br>
								     2. Transaction No: '.$transaction_no.'<br>
								     3.	Invoice No: '.$invoice_no.'<br>
								     4.	Amount: '.$amount.'<br>
								     5.	Exceptional Case(Yes/No): '.$req_exceptional_case.'<br><br>
								     Regards,<br>
									 IIBF

								 	</html>';

								// $this->email->send();
                				 $to_mail = 'pawansing.pardeshi@esds.co.in';
                				 $bcc = array('bhushan.amrutkar@esds.co.in');
		   
					            $info_arr = array('to'=>$to_mail,'from'=>'sonal.chavan@esds.co.in','bcc'=>$bcc,'subject'=>'Refund Request '.$req_id.' Initiated by '.$Makername,'message'=>$message);
					            $this->Emailsending->mailsend($info_arr);

                				 $logs_data = array(

									'date'=>date('Y-m-d H:i:s'),

									'title'=>'Refund request sent successfully.',

									'description'=>serialize($insert_array),

									'userid'=>$this->UserID,

									'ip'=>$this->input->ip_address()

								);
								
								/* Insert Details in `maker_checker_logs` DB Table */
								$this->master_model->insertRecord('maker_checker_logs',$logs_data);
								 
								 $this->session->set_flashdata('success', 'Refund request submited successfully.');
								 redirect(base_url().'creditnote/refundrequest/refundRequest');
								 
								 
							 }
							 else
							 {
							 	$logs_data = array(

									'date'=>date('Y-m-d H:i:s'),

									'title'=>'Error while sending refund request.',

									'description'=>serialize($insert_array),

									'userid'=>$this->UserID,

									'ip'=>$this->input->ip_address()

								);
								/* Insert Details in `maker_checker_logs` DB Table */
								$this->master_model->insertRecord('maker_checker_logs',$logs_data);

								$this->session->set_flashdata('error', 'Error while submiting refund request.');
								redirect(base_url().'creditnote/refundrequest/refundRequest');
							 }
						}	
						else
						{
							$this->session->set_flashdata('error', validation_errors());
							redirect(base_url().'creditnote/refundrequest/refundRequest');
						}
				}
		}
	
		$data['transaction_no'] =$transaction_no;
		$data['module_name'] =$module_name;
		$data['exam_code'] = $exam_code;
		$data['exam_period'] =$exam_period;
		$data['amount'] =$amount;
		$data['member_regnumber'] =$member_regnumber;
		$data['date_of_invoice'] = $date_of_invoice;
		$data['invoice_no'] = $invoice_no;
		$data['pay_type'] = $pay_type;
		$this->load->view('creditnote/refundrequest/refund_request_add',$data);
		
	}
	/* Validate Member Function */
	 public function check_transactionduplication($transaction_no)
    {
        if ($transaction_no != "") {
			
				$this->db->where('transaction_no',$transaction_no);
                $transaction_detail = $this->master_model->getRecordCount('payment_transaction', array(
                    'transaction_no' => $transaction_no
                     ));

                  $transaction_detail_exam_invoice = $this->master_model->getRecordCount('exam_invoice', array(
                    'transaction_no' => $transaction_no
                     ));
					 //print_r($transaction_details);
					 //echo $this->db->last_query(); die();
            if ($transaction_detail != "" && $transaction_detail_exam_invoice != "") {
			
            		$transaction_details = $this->master_model->getRecordCount('maker_checker', array(
                	'transaction_no' => $transaction_no
            ));
			//print_r($transaction_detail);
           //echo $this->db->last_query(); die();
            if ($transaction_details == 1) {
                $this->db->where('transaction_no',$transaction_no);
                $transaction_details1 = $this->master_model->getRecordCount('maker_checker', array(
                    'transaction_no' => $transaction_no
                     ));
					 //print_r($transaction_details);
					 //echo $this->db->last_query(); die();
                if ($transaction_details1 < 1) {
                    return true;
                } else {
                    
					$this->session->set_flashdata('error', 'The entered  transaction no already exist.');
					redirect(base_url().'creditnote/refundrequest/refundRequest');
                    return false;
                }
            } else {
                
                return true;
            }
        } else {
                 $this->session->set_flashdata('error', 'Invalid Transaction Number.');
					redirect(base_url().'creditnote/refundrequest/refundRequest');
                    return false;
        }
	  }else {
            return false;
        }
    }
	
	public function edit()
	{
		$data = array();
		$image_name1 = $image_name2 = $image_name3 =$image_name4 = '';
		if($this->uri->segment(5))
		{

		    $id = trim($this->uri->segment(5)); 
			$id = base64_decode($id);
			$id = intval($id);

		$this->db->select('m.id,m.req_id,m.req_title,m.req_desc,m.req_member_no,m.req_module,m.transaction_no,m.req_exceptional_case,m.req_reason,m.req_maker_id,m.req_exceptional_case,m.req_status,m.req_created_on,m.req_modified_on,m.image_name1,m.image_name2,m.image_name3,m.image_name4,m.credit_note_image,p.module_name,cl.maker_id,cl.checker_id,cl.description');
		$this->db->join('pay_type_master p','m.req_module=p.pay_type','LEFT');		
		$this->db->join('credit_note_list cl','m.req_id=cl.req_id','LEFT');	
		$this->db->where('m.id =',$id);	
		$this->db->group_by('m.req_id');
		$this->db->order_by('m.id','DESC');
		$page_info = $data['page_info'] = $this->master_model->getRecords("maker_checker m");	
		                               
		//$page_info = $data['page_info'] = $this->master_model->getRecords("maker_checker",array("id"=>$id));
			//print_r($page_info);
			$image_data = $this->master_model->getRecords("maker_checker",array("id"=>$id), 'image_name1,image_name2,image_name3,image_name4');
			
		if(isset($_POST['btnSubmit'])) 
		{
			$image_data = $this->master_model->getRecords("maker_checker",array("id"=>$id), 'image_name1,image_name2,image_name3,image_name4'); 
			//print_r($image_data); exit;
			$this->form_validation->set_rules('transaction_no','Transaction Number','required');
				$this->form_validation->set_rules('req_title', 'Title', 'trim|required|max_length[50]');
				$this->form_validation->set_rules('req_desc','Description','required');
				//$this->form_validation->set_rules('req_member_no', 'Member No', 'trim|required|numeric or empty|xss_clean');
				if ($this->form_validation->run() == TRUE)
					{
					
							$title = $this->input->post('req_title');
							$description = $this->input->post('req_desc');
							$transaction_no = $this->input->post('transaction_no');
							$reason = trim($this->input->post('req_reason'));
							//$req_module = $this->input->post('pay_type');
							$req_member_no = $this->input->post('req_member_no');
							if($this->input->post('req_exceptional_case')=='YES')
							{
								$req_exceptional_case = $this->input->post('req_exceptional_case');
							}
							else
							{
								$req_exceptional_case = 'NO';
								$reason = '';
							}
							if($this->input->post('req_status')==2)
							{
								$req_status = 6;

								$insert_data = array(	

								'checker_id'		=>$this->input->post('checker_id'),

								'maker_id'			=>strtoupper($this->input->post('maker_id')),
								
								'req_id'			=>strtoupper($this->input->post('req_id')),
								
								'action_status'		=>$req_status,

								'description'		=>'Request Resubmited',

								'created_on'		=>date('Y-m-d H:i:s'),

							);

				             $this->master_model->insertRecord('credit_note_list',$insert_data);

							}
							else
							{
								$req_status = $this->input->post('req_status');
							}
					
						//echo '***'.$title; 
							$update_data = array('req_title'=>$title,
													'req_desc'=>$description,
													'transaction_no'=>$transaction_no,
													'req_reason'=>$reason,
													'req_member_no'=>$req_member_no,
													'req_exceptional_case'=>$req_exceptional_case,
													//'req_module' => $req_module,
													// 'image_name1' => $image_data1,
													// 'image_name2' => $image_data2,
													// 'image_name3' => $image_data3,
													// 'image_name4' => $image_data4,
													'req_status' => $req_status,
													'req_modified_on'=>date('Y-m-d H:i:s')
											);
				if($this->master_model->updateRecord("maker_checker",$update_data,array('id'=>$id)))
				{ //$this->db->last_query(); exit;
					$logs_data = array(

									'date'=>date('Y-m-d H:i:s'),

									'title'=>'Request updated successfully.',

									'description'=>serialize($update_data),

									'userid'=>$this->UserID,

									'ip'=>$this->input->ip_address()

								);

					$this->master_model->insertRecord('maker_checker_logs',$logs_data);
					$this->session->set_flashdata('success','Request updated successfully');
					redirect(base_url().'creditnote/refundrequest/refundRequest/edit/'.base64_encode($id));	
				}
				 else {
						$logs_data = array(

									'date'=>date('Y-m-d H:i:s'),

									'title'=>'Request not updated.',

									'description'=>serialize($update_data),

									'userid'=>$this->UserID,

									'ip'=>$this->input->ip_address()

								);

					$this->master_model->insertRecord('maker_checker_logs',$logs_data);
						$this->session->set_flashdata('error','Request not updated.');
						redirect(base_url().'creditnote/refundrequest/refundRequest/edit/'.$id);	
					
					}
					}
					else
					{
						
					}
			}
		 
		}	
		$data['page_info'] = $page_info;
		$data['image_data'] = $image_data;
		$this->load->view('creditnote/refundrequest/refund_request_edit',$data);
		}
	
}
//******************* refund request form function ends here 