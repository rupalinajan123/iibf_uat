<?php
defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
class JBIMS_dashboard extends CI_Controller {
	
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
		$this->load->model('JBIMSmodel');
		
	}
	
	//self listing
	public function self(){
	    
	        $this->db->join('JBIMS_candidates','JBIMS_candidates.id=JBIMS_payment_transaction.ref_id');
	        $this->db->join('exam_invoice','exam_invoice.pay_txn_id=JBIMS_payment_transaction.id and exam_invoice.member_no=JBIMS_payment_transaction.member_regnumber');
	        $this->db->order_by("date desc");
		    $res_arr=$this->master_model->getRecords('JBIMS_payment_transaction',array('JBIMS_candidates.sponsor'=>'self','exam_invoice.app_type'=>'J','JBIMS_payment_transaction.status'=>1,'JBIMS_candidates.isactive'=>'1','JBIMS_candidates.regnumber !='=>0));
		   //echo $this->db->last_query(); die;
		   //echo "<pre>"; print_r($res_arr); exit;
	       $data['self_list'] = $res_arr;
		   $this->load->view('JBIMS_dashboard/self_list',$data);
	}
	
	public function bank_paid(){
	    
	        $this->db->join('JBIMS_candidates','JBIMS_candidates.id=JBIMS_payment_transaction.ref_id');
	        $this->db->join('exam_invoice','exam_invoice.pay_txn_id=JBIMS_payment_transaction.id and exam_invoice.member_no=JBIMS_payment_transaction.member_regnumber');
	        $this->db->order_by("date desc");
		    $res_arr=$this->master_model->getRecords('JBIMS_payment_transaction',array('JBIMS_candidates.sponsor'=>'bank','JBIMS_payment_transaction.status'=>1,'exam_invoice.app_type'=>'J','JBIMS_candidates.isactive'=>'1','JBIMS_candidates.regnumber !='=>0));
		   //echo "<pre>"; print_r($res_arr); exit;
		  // echo $this->db->last_query(); die;
	       $data['bank_list'] = $res_arr;
		   $this->load->view('JBIMS_dashboard/bankpaid_list',$data);
	}
	
	public function bank_unpaid(){
	       
	       $this->db->order_by("createdon desc");
		   $res_arr=$this->master_model->getRecords('JBIMS_candidates',array('JBIMS_candidates.sponsor'=>'bank','isactive'=>'1','regnumber!='=>0,'createdon >'=>'2021-05-15'));
		  // echo "<pre>"; print_r($res_arr); exit;
	       $data['bank_list'] = $res_arr;
		   $this->load->view('JBIMS_dashboard/bankunpaid_list',$data);
	}
	
	public function bank_invoice(){
	    
	    $order_no=base64_decode($this->uri->segment('3')); 
					 	  
          $exam_invoice=  $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$order_no));
		 $invoice_image = $exam_invoice[0]['invoice_image'];

		 $path="http://iibf.esdsconnect.com/uploads/JBIMSinvoice/user/".$invoice_image;
		 
		$file=$pdf->Output($path,'D');
	}
	
	public function idproofs(){
	     $flag=($this->uri->segment('3'));
	    $jid=base64_decode($this->uri->segment('4')); 
					 	  
          $candidate=  $this->master_model->getRecords('JBIMS_candidates',array('regnumber'=>$jid));
		 $photograph = $candidate[0]['photograph'];
		 $signature = $candidate[0]['signature'];
		 $idproof = $candidate[0]['idproof'];
            if($flag =='P'){
                $path="http://iibf.teamgrowth.net/uploads/JBIMS/photograph/".$photograph;
            }else if($flag =='S'){
                $path="http://iibf.teamgrowth.net/uploads/JBIMS/signature/".$signature;
            }else if($flag =='I'){
                $path="http://iibf.teamgrowth.net/uploads/JBIMS/idproof/".$idproof;
            }
	
		 //echo $path;die;
		$file=$pdf->Output($path,'D');
	}
	
	//download self invoices
	public function self_invoice(){
	    
	    $order_no=base64_decode($this->uri->segment('3')); 
					 	  
          $exam_invoice=  $this->master_model->getRecords('exam_invoice',array('receipt_no'=>$order_no));
		 $invoice_image = $exam_invoice[0]['invoice_image'];

		 $path="http://iibf.esdsconnect.com/uploads/JBIMSinvoice/user/".$invoice_image;
		 
		$file=$pdf->Output($path,'D');
	}
	
	//download self member pdf 
	public function self_pdf()
	{	
	    
		$order_no=base64_decode($this->uri->segment('3')); 
		
		   
		$this->db->join('JBIMS_candidates','JBIMS_candidates.id=JBIMS_payment_transaction.ref_id');
		$user_info_details=$this->master_model->getRecords('JBIMS_payment_transaction',array('receipt_no'=>$order_no));
		
		if(empty($user_info_details)){
			redirect(base_url().'JBIMS_dashboard/self');
		}
			
		//echo '<pre>';print_r($user_info_details);die;
		if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
		$imagePath = 'http://iibf.esdsconnect.com/uploads/JBIMS/photograph/'.$user_info_details[0]['photograph'];
		$imagePath1 = 'http://iibf.esdsconnect.com/uploads/JBIMS/signature/'.$user_info_details[0]['signature'];
		 $imagePath2 = 'http://iibf.esdsconnect.com/uploads/JBIMS/idproof/'.$user_info_details[0]['idproof'];
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
					<td class="tablecontent2" width="51%">Membership No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['regnumber'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['name'].'</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">Gender : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['gender'].'</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">IIBF Membership No: </td>
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
			$file=$pdf->Output('exam_JBIMSself_'.$order_no.'.pdf','D');
           
	}
	
		//download bank paid member pdf 
	public function bankpaid_pdf()
	{	
	    
		$order_no=base64_decode($this->uri->segment('3')); 
		
		   
		$this->db->join('JBIMS_candidates','JBIMS_candidates.id=JBIMS_payment_transaction.ref_id');
		$user_info_details=$this->master_model->getRecords('JBIMS_payment_transaction',array('receipt_no'=>$order_no));
		
		if(empty($user_info_details)){
			redirect(base_url().'JBIMS_dashboard/bank_paid');
		}
			
		//echo '<pre>';print_r($user_info_details);die;
		if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
		$imagePath = 'http://iibf.esdsconnect.com/uploads/JBIMS/photograph/'.$user_info_details[0]['photograph'];
		$imagePath1 = 'http://iibf.esdsconnect.com/uploads/JBIMS/signature/'.$user_info_details[0]['signature'];
		 $imagePath2 = 'http://iibf.esdsconnect.com/uploads/JBIMS/idproof/'.$user_info_details[0]['idproof'];
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
					<td class="tablecontent2" width="51%">Membership No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['regnumber'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['name'].'</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">Gender : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['gender'].'</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">IIBF Membership No: </td>
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
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.ucfirst($user_info_details[0]['sponsor_bank_name']).'</td>
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
			$file=$pdf->Output('exam_JBIMSbank_'.$order_no.'.pdf','D');
           
	}
	
	//downlod pdf for bank mannually
	public function bankunpaid_pdf(){
	    $rid=base64_decode($this->uri->segment('3'));
	    $user_info_details=$this->master_model->getRecords('JBIMS_candidates',array('regnumber'=>$rid));
		
		if(empty($user_info_details)){
			redirect(base_url().'JBIMS_dashboard/bankunpaid_pdf');
		}
			
		//echo '<pre>';print_r($user_info_details);die;
		//if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
		$imagePath = base_url().'uploads/JBIMS/photograph/'.$user_info_details[0]['photograph'];
		$imagePath1 = base_url().'uploads/JBIMS/signature/'.$user_info_details[0]['signature'];
		$imagePath2 = base_url().'uploads/JBIMS/idproof/'.$user_info_details[0]['idproof'];
		
									
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
					<td class="tablecontent2" width="51%">Membership No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['regnumber'].'</td>
				</tr>
				
				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['name'].'</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">Gender : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.$user_info_details[0]['gender'].'</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">IIBF Membership No: </td>
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
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> '.ucfirst($user_info_details[0]['sponsor_bank_name']).'</td>
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
			$file=$pdf->Output('exam_JBIMSbank_'.$rid.'.pdf','D');
			
			
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
		SELECT name, gender, current_role, regnumber, dob, bday, bmonth, byear, iibf_membership_no, address1, address2, address3, address4, city, state, pincode_address, std_code, phone_no, mobile_no, email_id, alt_email_id, graduation, post_graduation, special_qualification, name_employer, position, work_from_month, work_from_year, work_to_month, work_to_year, till_present, work_experiance, payment, gstin_no, agree, sponsor, sponsor_bank_name, bank_address1, bank_address2, bank_address3, bank_address4, bank_city, bank_state, bank_pincode, sponsor_email, sponsor_contact_person, sponsor_contact_designation, sponsor_contact_std, sponsor_contact_phone, sponsor_contact_mobile, sponsor_contact_email, gst_bank_name, gst_no, isactive, createdon FROM JBIMS_candidates WHERE isactive='".$isactive."' AND DATE(createdon) BETWEEN '".$from_date."' AND '".$end_date."'
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
		 
		$this->load->view('JBIMS_dashboard/report');
 }
	
}

?>