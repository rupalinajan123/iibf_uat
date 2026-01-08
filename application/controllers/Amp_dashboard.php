<?php
defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
class Amp_dashboard extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
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
		$this->load->model('Ampmodel');
		$this->load->helper('directory');
		$this->load->helper('file');
		 

		$this->delete_amp_dashboard_old_files_folder_from_server();
		
		
		
	}
	
	//self listing
	public function self(){
	    
	    $app_type=array('M','KM');
	    $this->db->where_in('app_type', $app_type);
	        $this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
	        $this->db->join('exam_invoice','exam_invoice.pay_txn_id=amp_payment_transaction.id and exam_invoice.member_no=amp_payment_transaction.member_regnumber');
	        $this->db->order_by("date desc");
		    $res_arr=$this->master_model->getRecords('amp_payment_transaction',array('amp_candidates.sponsor'=>'self','amp_payment_transaction.status'=>1,'amp_candidates.isactive'=>'1','amp_candidates.regnumber !='=>0));
		   //echo $this->db->last_query(); die;
		   //echo "<pre>"; print_r($res_arr); exit;
	       $data['self_list'] = $res_arr;

		   if(isset($_POST) && !empty($_POST)) {
			
				if($_POST['download_data']=='Invoice') {
					$this->download_bulk_invoice('self',$_POST['download_data_year']);
				}
				else if($_POST['download_data']=='ExamForm') {
					$this->download_bulk_examForm('self',$_POST['download_data_year']);
				}
		   }
		   $this->load->view('amp_dashboard/self_list',$data);
	}
	
	public function bank_paid(){
	    
	        $this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
	        $this->db->join('exam_invoice','exam_invoice.pay_txn_id=amp_payment_transaction.id and exam_invoice.member_no=amp_payment_transaction.member_regnumber');
	        $this->db->order_by("date desc");
		    $res_arr=$this->master_model->getRecords('amp_payment_transaction',array('amp_candidates.sponsor'=>'bank','amp_payment_transaction.status'=>1,'exam_invoice.app_type'=>'M','amp_candidates.isactive'=>'1','amp_candidates.regnumber !='=>0));
		   //echo "<pre>"; print_r($res_arr); exit;
		  // echo $this->db->last_query(); die;
	       $data['bank_list'] = $res_arr;

		   if(isset($_POST) && !empty($_POST)) {
			
				if($_POST['download_data']=='Invoice') {
					$this->download_bulk_invoice('bank_paid',$_POST['download_data_year']);
				}
				else if($_POST['download_data']=='ExamForm') {
					$this->download_bulk_examForm('bank_paid',$_POST['download_data_year']);
				}
	   		}

		   $this->load->view('amp_dashboard/bankpaid_list',$data);
	}
	
	public function bank_unpaid(){
	       
	       $this->db->order_by("createdon desc");
        	$this->db->where('regnumber NOT IN (SELECT member_regnumber FROM amp_payment_transaction)');
		   $res_arr=$this->master_model->getRecords('amp_candidates',array('amp_candidates.sponsor'=>'bank','isactive'=>'1','regnumber!='=>0,'createdon >'=>'2021-05-15'));
		  // 
	       $data['bank_list'] = $res_arr;

		   if(isset($_POST) && !empty($_POST)) {
			//echo "<pre>"; print_r($_POST); exit;
			if($_POST['download_data']=='ExamForm') {
				$this->download_bulk_examForm('bank_unpaid',$_POST['download_data_year']);
			}
		   }
		   $this->load->view('amp_dashboard/bankunpaid_list',$data);
	}
	
	public function bank_invoice(){
	    
	    $order_no=base64_decode($this->uri->segment('3')); 
					 	  
          $exam_invoice=  $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$order_no));
		 $invoice_image = $exam_invoice[0]['invoice_image'];

		 $path="http://iibf.esdsconnect.com/uploads/ampinvoice/user/".$invoice_image;
		 
		$file=$pdf->Output($path,'D');
	}
	
	//download self invoices
	public function self_invoice(){
	    
	    $order_no=base64_decode($this->uri->segment('3')); 
					 	  
          $exam_invoice=  $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$order_no));
		 $invoice_image = $exam_invoice[0]['invoice_image'];

		 $path="http://iibf.esdsconnect.com/uploads/ampinvoice/user/".$invoice_image;
		 
		$file=$pdf->Output($path,'D');
	}
	
	//download self member pdf 
	public function self_pdf()
	{	
	    
		$order_no=base64_decode($this->uri->segment('3')); 
		
		   
		$this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
		$user_info_details=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$order_no));
		
		if(empty($user_info_details)){
			redirect(base_url().'Amp_dashboard/self');
		}
			
		//echo '<pre>';print_r($user_info_details);die;
		if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
		$imagePath = base_url().'uploads/amp/photograph/'.$user_info_details[0]['photograph'];
		$imagePath1 = base_url().'uploads/amp/signature/'.$user_info_details[0]['signature'];
		 $imagePath2 = base_url().'uploads/amp/idproof/'.$user_info_details[0]['idproof'];
		if(strtolower($user_info_details[0]['payment'])=='full'){
			$payment = 'Full Paid';
		}else{
			$payment =  ucfirst($user_info_details[0]['payment']).' Installment';
		}
									
		$html='<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">         
		<tbody>
			<tr><td colspan="4" align="left">&nbsp;</td> </tr>
			<tr>
				<td colspan="4" align="center" height="25">
				<span id="1001a1" class="alert"></span>
				</td>
			</tr>

		<tr style="border-bottom:solid 1px #000;"> 
			<td colspan="4" height="1" align="center" ><img src="'.base_url().'assets/images/logo1.png"></td>
		</tr>
		<tr></tr>
		<tr><td style="text-align:center"><strong><h3>Exam Enrolment Acknowledgement</h3></strong></td></tr>	   
		<tr><td style="text-align:right"><img src="'.$imagePath.'" height="100" width="100" /></td>
		</tr>
		<tr>
			<td colspan="4">
			</hr>

			<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
				<tbody>
				<tr>
							<td class="tablecontent2" width="51%">Enrolment No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['regnumber'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['name'].'</td>
				</tr>
				<tr>
							<td class="tablecontent2" width="51%">IIBF Registration No: </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['iibf_membership_no'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Date of Birth : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.date('d-M-Y',strtotime($user_info_details[0]['dob'])).'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Address : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['address1'].' '.$user_info_details[0]['address2'].' '.$user_info_details[0]['address3'].' '.$user_info_details[0]['address4'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Pincode : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['pincode_address'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Mobile Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['mobile_no'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Email ID : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['email_id'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Payment : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$payment.'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Amount : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['amount'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Sponsor : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.ucfirst($user_info_details[0]['sponsor']).'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['transaction_no'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Status : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$status.'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Date : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['date'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Id Proof : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="'.$imagePath2.'" height="100" width="100" /></td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Signature : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="'.$imagePath1.'" height="100" width="100" /></td>
				</tr>
				
				</tbody>
			</table>
			
			</td>
		</tr>
			</tbody>
		</table>';
		//echo $html;die;//
			//this the the PDF filename that user will get to download
			$pdfFilePath = 'exam'.'.pdf';
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
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$file=$pdf->Output('exam_ampself_'.$order_no.'.pdf','D');
           
	}
	
	//download bank paid member pdf 
	public function bankpaid_pdf()
	{	
	    
		$order_no=base64_decode($this->uri->segment('3')); 
		
		   
		$this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
		$user_info_details=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$order_no));
		
		if(empty($user_info_details)){
			redirect(base_url().'Amp_dashboard/bank_paid');
		}
			
		//echo '<pre>';print_r($user_info_details);die;
		if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}

		$imagePath = base_url().'uploads/amp/photograph/'.$user_info_details[0]['photograph'];
		$imagePath1 = base_url().'uploads/amp/signature/'.$user_info_details[0]['signature'];
		$imagePath2 = base_url().'uploads/amp/idproof/'.$user_info_details[0]['idproof'];

		if(strtolower($user_info_details[0]['payment'])=='full'){
			$payment = 'Full Paid';
		}else{
			$payment =  ucfirst($user_info_details[0]['payment']).' Installment';
		}
									
		$html='<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">         
				<tbody>
				<tr><td colspan="4" align="left">&nbsp;</td> </tr>
				<tr>
					<td colspan="4" align="center" height="25">
					<span id="1001a1" class="alert"></span>
					</td>
				</tr>

				<tr style="border-bottom:solid 1px #000;"> 
					<td colspan="4" height="1" align="center" ><img src="'.base_url().'assets/images/logo1.png"></td>
				</tr>
				<tr></tr>
				<tr><td style="text-align:center"><strong><h3>Exam Enrolment Acknowledgement</h3></strong></td></tr>	   
				<tr><td style="text-align:right"><img src="'.$imagePath.'" height="100" width="100" /></td>
				</tr>
				<tr>
				<td colspan="4">
				</hr>

				<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
				<tbody>
				<tr>
					<td class="tablecontent2" width="51%">Enrolment No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['regnumber'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['name'].'</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">IIBF Registration No: </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['iibf_membership_no'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Date of Birth : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.date('d-M-Y',strtotime($user_info_details[0]['dob'])).'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Address : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['address1'].' '.$user_info_details[0]['address2'].' '.$user_info_details[0]['address3'].' '.$user_info_details[0]['address4'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Pincode : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['pincode_address'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Mobile Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['mobile_no'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Email ID : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['email_id'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Payment : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$payment.'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Amount : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['amount'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Sponsor : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.ucfirst($user_info_details[0]['sponsor']).'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['transaction_no'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Status : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$status.'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Transaction Date : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['date'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Id Proof : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="'.$imagePath2.'" height="100" width="100" /></td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Signature : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="'.$imagePath1.'" height="100" width="100" /></td>
				</tr>
				
				</tbody>
				</table>
				
				</td>
				</tr>
				</tbody>
			</table>';
			//echo $html;die;//
			//this the the PDF filename that user will get to download
			$pdfFilePath = 'exam'.'.pdf';
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
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$file=$pdf->Output('exam_ampbank_'.$order_no.'.pdf','D');
           
	}
	
	//downlod pdf for bank mannually
	public function bankunpaid_pdf(){
	    $rid=base64_decode($this->uri->segment('3'));
	    $user_info_details=$this->master_model->getRecords('amp_candidates',array('regnumber'=>$rid));
		
		if(empty($user_info_details)){
			redirect(base_url().'Amp_dashboard/bankunpaid_pdf');
		}
			
		//echo '<pre>';print_r($user_info_details);die;
		//if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
		$imagePath = base_url().'uploads/amp/photograph/'.$user_info_details[0]['photograph'];
		$imagePath1 = base_url().'uploads/amp/signature/'.$user_info_details[0]['signature'];
		$imagePath2 = base_url().'uploads/amp/idproof/'.$user_info_details[0]['idproof'];
		
									
		$html='<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">         
		<tbody>
			<tr><td colspan="4" align="left">&nbsp;</td> </tr>
			<tr>
				<td colspan="4" align="center" height="25">
				<span id="1001a1" class="alert"></span>
				</td>
			</tr>

			<tr style="border-bottom:solid 1px #000;"> 
				<td colspan="4" height="1" align="center" ><img src="'.base_url().'assets/images/logo1.png"></td>
			</tr>
			<tr></tr>
			<tr><td style="text-align:center"><strong><h3>Exam Enrolment Acknowledgement</h3></strong></td></tr>	   
			<tr><td style="text-align:right"><img src="'.$imagePath.'" height="100" width="100" /></td>
			</tr>
			<tr>
				<td colspan="4">
				</hr>

				<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
					<tbody>
					<tr>
					<td class="tablecontent2" width="51%">Enrolment No : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['regnumber'].'</td>
					</tr>
					
					<tr>
						<td class="tablecontent2" width="51%">Name : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['name'].'</td>
					</tr>
					<tr>
					<td class="tablecontent2" width="51%">IIBF Registration No: </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['iibf_membership_no'].'</td>
					</tr>
					
					<tr>
						<td class="tablecontent2" width="51%">Date of Birth : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.date('d-M-Y',strtotime($user_info_details[0]['dob'])).'</td>
					</tr>
					
					<tr>
						<td class="tablecontent2" width="51%">Address : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['address1'].' '.$user_info_details[0]['address2'].' '.$user_info_details[0]['address3'].' '.$user_info_details[0]['address4'].'</td>
					</tr>
					
					<tr>
						<td class="tablecontent2" width="51%">Pincode : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['pincode_address'].'</td>
					</tr>
					
					<tr>
						<td class="tablecontent2" width="51%">Mobile Number : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['mobile_no'].'</td>
					</tr>
					
					<tr>
						<td class="tablecontent2" width="51%">Email ID : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['email_id'].'</td>
					</tr>
					
				
					
				
					
					<tr>
						<td class="tablecontent2" width="51%">Sponsor : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.ucfirst($user_info_details[0]['sponsor']).'</td>
					</tr>
					
					
					
					<tr>
						<td class="tablecontent2" width="51%">Status : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> Successfully Registered</td>
					</tr>
					
					
					
					<tr>
						<td class="tablecontent2" width="51%">Id Proof : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="'.$imagePath2.'" height="100" width="100" /></td>
					</tr>
					
					<tr>
						<td class="tablecontent2" width="51%">Signature : </td>
						<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="'.$imagePath1.'" height="100" width="100" /></td>
					</tr>
					
					</tbody>
				</table>
				
				</td>
			</tr>
		</tbody>
		</table>';
		//echo $html;die;
			//this the the PDF filename that user will get to download
			$pdfFilePath = 'exam'.'.pdf';
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
			$pdf->WriteHTML($stylesheet,1);
			$pdf->WriteHTML($html,2);
			$file=$pdf->Output('exam_ampbank_'.$rid.'.pdf','D');
			
			
	}

	//Report of jbims 
	public function Report(){
       if($this->input->post('submit'))
		{
			$from_date = $this->input->post('from_date');//'2019-07-01';
			$end_date = $this->input->post('to_date');//'2019-07-31';
			$isactive='1';
			
			$this->load->dbutil();
			$this->load->helper('file');
			$this->load->helper('download');
			$delimiter = ",";
			$newline = "\r\n";
			$filename = "Report.csv";
			$query = "
			SELECT  name, regnumber, dob, bday, bmonth, byear, iibf_membership_no, address1, address2, address3, address4, city, state, pincode_address, std_code, phone_no, mobile_no, email_id, alt_email_id, graduation, post_graduation, special_qualification, name_employer, position, work_from_month, work_from_year, work_to_month, work_to_year, till_present, work_experiance, payment, gstin_no, agree, sponsor, sponsor_bank_name, bank_address1, bank_address2, bank_address3, bank_address4, bank_city, bank_state, bank_pincode, sponsor_email, sponsor_contact_person, sponsor_contact_designation, sponsor_contact_std, sponsor_contact_phone, sponsor_contact_mobile, sponsor_contact_email, isactive, createdon FROM amp_candidates WHERE isactive='".$isactive."' AND DATE(createdon) BETWEEN '".$from_date."' AND '".$end_date."'
			";
			$result1 = $this->db->query($query);
			//echo $this->db->last_query(); die;
			$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
			//$this->db->empty_table('center_stat'); 
			force_download($filename, $data);
		} 

	    $this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		 
		$this->load->view('amp_dashboard/report');
 	}
	
	
	public function download_bulk_invoice($type,$yearRange)
	{ 
		
		//$year = date('Y');
		$yearRangeParts = explode('To',$yearRange);
		$from_date = date('Y-m-d',strtotime($yearRangeParts[0]));
		$to_date = date('Y-m-d',strtotime($yearRangeParts[1]));
		//	print_r($yearRangeParts[0]);
		if($type == 'self'){
			$app_type=array('M','KM');
		    $this->db->where_in('app_type', $app_type);
		    $this->db->where('createdon >=',$from_date);
	        $this->db->where('createdon <=',$to_date);
	        $this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
	        $this->db->join('exam_invoice','exam_invoice.pay_txn_id=amp_payment_transaction.id and exam_invoice.member_no=amp_payment_transaction.member_regnumber');
	        $this->db->order_by("date desc");
		    $result=$this->master_model->getRecords('amp_payment_transaction',array('amp_candidates.sponsor'=>'self','amp_payment_transaction.status'=>1,'amp_candidates.isactive'=>'1','amp_candidates.regnumber !='=>0));
			
			//echo $this->db->last_query();exit;
		}
		else if($type == 'bank_paid'){
			//$this->db->like('createdon',$year);
			$this->db->where('createdon >=',$from_date);
	        $this->db->where('createdon <=',$to_date);
			$this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
	        $this->db->join('exam_invoice','exam_invoice.pay_txn_id=amp_payment_transaction.id and exam_invoice.member_no=amp_payment_transaction.member_regnumber');
	        $this->db->order_by("date desc");
		    $result=$this->master_model->getRecords('amp_payment_transaction',array('amp_candidates.sponsor'=>'bank','amp_payment_transaction.status'=>1,'exam_invoice.app_type'=>'M','amp_candidates.isactive'=>'1','amp_candidates.regnumber !='=>0));
		
			//echo $this->db->last_query();
		}
		else{
			//$this->db->like('createdon',$year);
			$this->db->where('createdon >=',$from_date);
	        $this->db->where('createdon <=',$to_date);
			$this->db->order_by("createdon desc");
		   	$result=$this->master_model->getRecords('amp_candidates',array('amp_candidates.sponsor'=>'bank','isactive'=>'1','regnumber!='=>0));
		   //	echo $this->db->last_query();exit;
		}
		//echo $this->db->last_query();
		//echo $this->db->last_query();
		//echo '<pre>',print_r($result),'</pre>';exit;
		
		if($result)
		{	
			$directory_name = "./uploads/ampinvoice/user/";	
			if (!file_exists($directory_name))
			{
				die('Directory Not Found');
			}	

			$files = array();
			foreach ($result as $val) 
			{
				//$files[] = $directory_name.$val['invoice_image'];
			}
			
			$currentdate = date('Y-m-dH:i:s');
			$cron_file_dir = "./uploads/cronfiles_pg/".date("Ymd");;
			//echo $currentdate;die;
			$zipname = 'AMP_invoice_'.$currentdate.'';
			$directory = $cron_file_dir.'/'.$zipname;
			if (file_exists($directory))
				{
				array_map('unlink', glob($directory . "/*.*"));
				rmdir($directory);
				$dir_flg = mkdir($directory, 0700);
				}
				else
				{
				$dir_flg = mkdir($directory, 0700);
				}
			$zip = new ZipArchive;
        	$zip->open($directory . '.zip', ZipArchive::CREATE);
				
			foreach ($result as $val) {
				copy($directory_name.$val['invoice_image'], $directory.'/'.$val['invoice_image']);
				$file_to_add = $directory . "/" . $val['invoice_image'];
                    $file_to_add1 = substr($file_to_add, strrpos($file_to_add, '/') + 1);
                     $zip->addFile($file_to_add, $file_to_add1);
				//$zip->addFile($file,basename($file));
			}
			//die;
			$zip->close();	

			if(file_exists( $cron_file_dir.'/'.$zipname.'.zip'))
			{
				// push to download the zip
				header('Content-type: application/zip');
				header('Content-Disposition: attachment; filename="'.$zipname.'.zip'.'"');
				readfile($cron_file_dir.'/'.$zipname.'.zip'); 
				//remove zip file is exists in temp path
				unlink($cron_file_dir.'/'.$zipname.'.zip');
				//$rs = $this->delete_directory($pdfFilePath);   
			}	
			
			exit;				
		}
		else 
		{
			//exit;
			$this->session->set_flashdata('error','No record found.');
			redirect(base_url().'Amp_dashboard/'.$type);
            exit;
		}  
		
	}

	public function download_bulk_examForm($type,$yearRange)
	{ 	
		$year = date('Y');
		$yearRangeParts = explode('To',$yearRange);
		$from_date = date('Y-m-d',strtotime($yearRangeParts[0]));
		$to_date = date('Y-m-d',strtotime($yearRangeParts[1]));
		
		if($type == 'self'){
			$this->db->where('amp_candidates.createdon >=',$from_date);
			$this->db->where('amp_candidates.createdon <=',$to_date);
			$this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
			$result=$this->master_model->getRecords('amp_payment_transaction');
		}
		else if($type == 'bank_paid'){
			//$this->db->like('createdon',$year);
			$this->db->where('createdon >=',$from_date);
			$this->db->where('createdon <=',$to_date);
			$this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
	        $this->db->join('exam_invoice','exam_invoice.pay_txn_id=amp_payment_transaction.id and exam_invoice.member_no=amp_payment_transaction.member_regnumber');
	        $this->db->order_by("date desc");
		    $result=$this->master_model->getRecords('amp_payment_transaction',array('amp_candidates.sponsor'=>'bank','amp_payment_transaction.status'=>1,'exam_invoice.app_type'=>'M','amp_candidates.isactive'=>'1','amp_candidates.regnumber !='=>0));
		}
		else{
			//$this->db->like('createdon',$year);
			$this->db->where('createdon >=',$from_date);
			$this->db->where('createdon <=',$to_date);
			$this->db->order_by("createdon desc");
		   	$result=$this->master_model->getRecords('amp_candidates',array('amp_candidates.sponsor'=>'bank','isactive'=>'1','regnumber!='=>0));
			 //  echo $this->db->last_query();exit;
			 
		}
		
		$html='';

		//print_r($result); die;

		if($result)
		{	
			$files = array();
			foreach ($result as $val) 
			{
				if($val['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
				
				$data['imagePath'] = $imagePath = base_url().'uploads/amp/photograph/'.$val['photograph'];
				$data['imagePath1'] = $imagePath1 = base_url().'uploads/amp/signature/'.$val['signature'];
				$data['imagePath2'] = $imagePath2 = base_url().'uploads/amp/idproof/'.$val['idproof'];

				if(strtolower($val['payment'])=='full'){
					$data['payment'] = $payment = 'Full Paid';
				}else{
					$data['payment'] = $payment =  ucfirst($val['payment']).' Installment';
				}
				//$files[] = $directory_name.$val['invoice_image'];

				$data['user_info_details']=$val;

				$html = $this->load->view('amp_dashboard/self_examForm_download', $data, true);
				//$pdfFilePath = 'exam_form_'.$val['id'].'.pdf';

				$new_dir = $this->create_directories('./uploads/amp_dashboard/bulk_exam_form/'.date("Ymd"));
				$pdfFilePath = $new_dir.'/exam_form_'.$val['regnumber'].'.pdf';

				$files[] = $pdfFilePath;
				//echo $html;//die;
				//load mPDF library
				
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
				$this->load->library('m_pdf');
				$pdf = $this->m_pdf->load();
				//$pdf->SetHTMLHeader($header);
				$pdf->WriteHTML($html);
				$pdf->Output($pdfFilePath,"F");

			}
			
			$currentdate = date('Y-m-dH:i:s');
			//echo $currentdate;die;
			$cron_file_dir = "./uploads/cronfiles_pg/".date("Ymd");;
			$zipname = 'AMP_ExamForm_'.$currentdate.'';

			$directory = $cron_file_dir.'/'.$zipname;
			if (file_exists($directory))
				{
				array_map('unlink', glob($directory . "/*.*"));
				rmdir($directory);
				$dir_flg = mkdir($directory, 0700);
				}
				else
				{
				$dir_flg = mkdir($directory, 0700);
				}

			$zip = new ZipArchive;
			$zip->open($zipname, ZipArchive::CREATE);
				
			$zip->open($directory . '.zip', ZipArchive::CREATE);
				
			foreach ($files as $file) {

				//$new_dir = $this->create_directories('./uploads/amp_dashboard/bulk_exam_form/'.date("Ymd"));
				//$pdfFilePath = './uploads/amp_dashboard/bulk_exam_form/'.date("Ymd").'/exam_form_'.$val['regnumber'].'.pdf';
				
				copy($file, $directory.'/'.basename($file));
				$file_to_add = $directory . "/" . basename($file);
                    $file_to_add1 = substr($file_to_add, strrpos($file_to_add, '/') + 1);
                     $zip->addFile($file_to_add, $file_to_add1);
				//$zip->addFile($file,basename($file));
			}
			//die;
			$zip->close();	

			if(file_exists( $cron_file_dir.'/'.$zipname.'.zip'))
			{
				// push to download the zip
				header('Content-type: application/zip');
				header('Content-Disposition: attachment; filename="'.$zipname.'.zip"');
				readfile($cron_file_dir.'/'.$zipname.'.zip'); 
				//remove zip file is exists in temp path
				//unlink($cron_file_dir.'/'.$zipname.'.zip');
				//$rs = $this->delete_directory($pdfFilePath);   
			}
			exit;					
		}
		else 
		{
			$this->session->set_flashdata('error','No record found.');
			redirect(base_url().'Amp_dashboard/'.$type);
            exit;
		} 
		
	}


	function delete_amp_dashboard_old_files_folder_from_server(){
	 	//$this->create_directories('./uploads/amp_dashboard/bulk_exam_form/'.date("Ymd"));
	 	//$get_directory_list = $this->get_directory_list('uploads/amp_dashboard/bulk_exam_form/');
	 	//print_r($get_directory_list);
	 	$get_directory_list = $this->delete_old_folders('uploads/amp_dashboard/bulk_exam_form/');
	 	 
	}

	function create_directories($directory_path='')
	{ 
	    $directory_path = str_replace("./","",$directory_path); 
	    $directory_path_arr = explode("/",$directory_path); 
	    $chk_dir_path = './';

	    if(count($directory_path_arr) > 0) 
	    { 
	      $i = 0; 
	      foreach($directory_path_arr as $res) 
	      { 
	        if($i > 0)
	        { 
	        	$chk_dir_path .= "/"; 
	        } 
	        $chk_dir_path .= $res; 
	        if(!is_dir($chk_dir_path))
	        { 
	          $dir = mkdir($chk_dir_path,0755);         
	          $myfile = fopen($chk_dir_path."/index.php", "w") or die("Unable to open file!"); 
	          $txt = ""; fwrite($myfile, $txt); fclose($myfile); 
	        }
	        $i++;
	      }
	    } 
	    return $chk_dir_path; 
	}

    /* GET ALL FOLDER LISTING FROM REQUIRED FOLDER  */ 
    function get_directory_list($dir_name) 
    { 
      return directory_map('./'.$dir_name, 1); // This is use to get all folders and files from current directory excluding subfolders 
    } 

    function delete_old_folders($dir_name='') 
    {   
      if($dir_name != "" && is_dir($dir_name)) 
      { 
        $directory_list = $this->get_directory_list($dir_name); 
        //print_r($directory_list); 
        //exit; 
        if(count($directory_list) > 0) 
        { 
          foreach($directory_list as $directory_res) 
          { 
          	$directory_res = rtrim($directory_res, '/\/'); //exit;
            if($directory_res != date("Ymd")) 
            { 
              if(is_dir($dir_name.$directory_res)) 
              { 
                delete_files($dir_name.$directory_res, true); // delete all files/folders 
                rmdir($dir_name.$directory_res); 
              } 
            } 
          } 
        } 
      } 
    } 

}

?>