<?php	
  /********************************************************************
		* Description: Controller for E-learning separate module 
		* Created BY: Sagar Matale
		* Created On: 10-02-2021
		* Update By: Sagar Matale
		* Updated on: 05-07-2021
	********************************************************************/
  
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class ApplyElearningGenerateInvoiceCustom extends CI_Controller 
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
			$this->chk_session->Check_mult_session();
			//exit; 
		}	
    
    public function generate_invoice()
		{	exit;
			/* $MerchantOrderNo = '903014944'; 
			$transaction_no = '8897096934627'; */
			
			$get_user_regnum=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo),'id, member_regnumber, ref_id, status');
			
			$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'member_regnumber'=>$get_user_regnum[0]['member_regnumber']),'exam_code, transaction_no, date, amount, id, status');
			
			//get invoice	
			$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$payment_info[0]['id']));
			
			if(count($getinvoice_number) > 0)
			{
				echo '<br>invoiceNumber : '.$invoiceNumber = generate_el_invoice_number($getinvoice_number[0]['invoice_id']);
				if($invoiceNumber)
				{
					//$invoiceNumber=$this->config->item('exam_invoice_no_prefix').$invoiceNumber;
					/* $cyear = date("y");
					$nyear = $cyear+1;
					$invoiceNumber='EL/'.$cyear.'-'.$nyear.'/'.$invoiceNumber; */

          //START : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
          if(date("Y-m-d") >= date("Y").'-04-01') { $cyear = date("y"); } else { $cyear = date('y') - 1; }
          $nyear = $cyear + 1;
          if($cyear.'-'.$nyear == '24-25' && $invoiceNumber >= 6860) { $invoiceNumber = $invoiceNumber + 3056; }
          $invoiceNumber = 'EL/' . $cyear . '-' . $nyear . '/' . str_pad($invoiceNumber,6,0,STR_PAD_LEFT);
          //END : THIS CODE WAS UPDATED BY SAGAR M ON 2024-04-16. EARLIER THE INVOICE YEAR FOLLOW THE CALENDER YEAR INSTEAD OF FINANCIAL YEAR
				}
				echo '<br>invoiceNumber : '.$invoiceNumber;
				
				$update_data = array('invoice_no' => $invoiceNumber,'transaction_no'=>$transaction_no,'date_of_invoice'=>'2021-11-19 13:14:26','modified_on'=>'2021-11-19 13:14:26');
				$this->db->where('pay_txn_id',$payment_info[0]['id']);
				$this->master_model->updateRecord('exam_invoice',$update_data,array('receipt_no'=>$MerchantOrderNo));
				
				echo '<br>Qry : '.$this->db->last_query();
				
				echo '<br>attachpath : '.$attachpath=genarate_el_invoice($getinvoice_number[0]['invoice_id']); 
				
				
			}	
		}
		
	} 				