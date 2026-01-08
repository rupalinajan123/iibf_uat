<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Blended_custom extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->helper('blended_invoice_custom_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
    }
    public function index()
    { 
		$attachpath   = '';
		$zone_code = 'CO';
		// Member 1
		$invoiceNumber = '337309';
		$MerchantOrderNo = '900493378';
		$reg_id = '1595';
		$applicationNo = '500194643';
		$training_type = 'Physical Classroom';
		$mem_gstin_no = '19AAACS8577K3ZK';
		
		$get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id');
			
		$emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));
		$user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $applicationNo,'isactive'=>'1'),'email,mobile');
	   
		$Qry = $this->db->query("SELECT program_code, program_name, center_name, venue_name, start_date, end_date FROM blended_registration WHERE blended_id = '".$reg_id."' LIMIT 1");
		$detailsArr        = $Qry->row_array();
		$program_code = $detailsArr['program_code'];
		$program_name = $detailsArr['program_name'];
		$center_name  = $detailsArr['center_name'];
		$venue_name   = $detailsArr['venue_name'];
		$start_date1  = $detailsArr['start_date'];
		$end_date1    = $detailsArr['end_date'];
		$start_date   = date("d-M-Y", strtotime($start_date1));
		$end_date     = date("d-M-Y", strtotime($end_date1));
		$newstring    = str_replace("#program_name#","".$program_name."",$emailerstr[0]['emailer_text']);
		$newstring1   = str_replace("#training_type#","".$training_type."",$newstring);
		$newstring2   = str_replace("#center_name#","".$center_name."",$newstring1);
		$newstring3   = str_replace("#venue_name#","".$venue_name."",$newstring2);
		$newstring4   = str_replace("#start_date#","".$start_date."",$newstring3);
		$newstring5   = str_replace("#end_date#", "".$end_date."",$newstring4);
		
		/* Set Email sending options */
		$info_arr          = array(
			//'to' => $user_info[0]['email'],
			'to' => 'bhushan.amrutkar@esds.co.in',
			'from' => $emailerstr[0]['from'],
			'subject' => $emailerstr[0]['subject'],
			'message' => $newstring5
		);
		
		$getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $get_user_regnum_info[0]['id']));
		
		/* Invoice Number Genarate Functinality */
		if (count($getinvoice_number) > 0)
		{
			//if($invoiceNumber){$invoiceNumber = $this->config->item('blended_invoice_T'.$zone_code.'_prefix').$invoiceNumber;}
			
			/* Invoice Genarate Function */
			$attachpath = genarate_blended_invoice_custom($getinvoice_number[0]['invoice_id'],$zone_code,$program_name,$mem_gstin_no);
			
			
			
			
		}
		
		
		if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) 
		{
			echo 'Email Send..!!!';
		} 
	}
	
	public function igst()
	{
		$select = 'c.blended_id,c.member_no,c.gstin_no,c.program_name,c.zone_code,b.id as pay_txn_id,b.receipt_no';
		$this->db->join('payment_transaction b','b.ref_id=c.blended_id','LEFT');
		$blended_course_data = $this->Master_model->getRecords('blended_registration c',array(' batch_code' => 'B-1537','pay_type' => 10,'pay_status' => 1,'status' => '1'),$select);
		echo "<br> 1 SQL => ".$this->db->last_query();
		//echo "<pre>"; print_r($blended_course_data); echo "</pre>";
		
		if(count($blended_course_data))
		{
			foreach($blended_course_data as $blended_course)
			{					
				$pay_txn_id = $blended_course['pay_txn_id'];
				$receipt_no = $blended_course['receipt_no'];
				$program_name = $blended_course['program_name'];
				$mem_gstin_no = $blended_course['gstin_no'];
				$zone_code = $blended_course['zone_code'];
				$member_no = $blended_course['member_no'];
				$blended_id = $blended_course['blended_id'];
				
				// get invoice details for this blended course payment transaction by id and receipt_no
				$this->db->where('transaction_no !=','');
				$this->db->where('app_type','T');
				$this->db->where('receipt_no',$receipt_no);
				$blended_course_invoice_data = $this->Master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id));
				echo "<br>2 SQL => ".$this->db->last_query();
				if(count($blended_course_invoice_data))
				{
					//echo "<pre>invoice=>"; print_r($blended_course_invoice_data); echo "</pre>";
					foreach($blended_course_invoice_data as $blended_course_invoice)
					{
						echo "<br><br> Invoice_id => ".$invoice_id = $blended_course_invoice['invoice_id'];
						$attachpath = genarate_blended_invoice_custom($invoice_id,$zone_code,$program_name,$mem_gstin_no);
						
						$emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'user_blended_email'));
						$user_info = $this->Master_model->getRecords('member_registration',array('regnumber'=> $member_no,'isactive'=>'1'),'email,mobile');
					   
						$Qry = $this->db->query("SELECT program_code, program_name, center_name, venue_name, start_date, end_date FROM blended_registration WHERE blended_id = '".$blended_id."' LIMIT 1");
						$detailsArr        = $Qry->row_array();
						$training_type = 'Physical Classroom';
						$program_code = $detailsArr['program_code'];
						$program_name = $detailsArr['program_name'];
						$center_name  = $detailsArr['center_name'];
						$venue_name   = $detailsArr['venue_name'];
						$start_date1  = $detailsArr['start_date'];
						$end_date1    = $detailsArr['end_date'];
						$start_date   = date("d-M-Y", strtotime($start_date1));
						$end_date     = date("d-M-Y", strtotime($end_date1));
						
						$newstring    = str_replace("#program_name#","".$program_name."",$emailerstr[0]['emailer_text']);
						$newstring1   = str_replace("#training_type#","".$training_type."",$newstring);
						$newstring2   = str_replace("#center_name#","".$center_name."",$newstring1);
						$newstring3   = str_replace("#venue_name#","".$venue_name."",$newstring2);
						$newstring4   = str_replace("#start_date#","".$start_date."",$newstring3);
						$newstring5   = str_replace("#end_date#", "".$end_date."",$newstring4);
						
						/* Set Email sending options */
						$info_arr          = array(
							'to' => $user_info[0]['email'],
							//'to' => 'bhushan.amrutkar@esds.co.in',
							'from' => $emailerstr[0]['from'],
							'subject' => $emailerstr[0]['subject'],
							'message' => $newstring5
						);
						if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) 
						{
							echo '<br>Email Send..!!! =>'.$user_info[0]['email'];
						} 
						
					}
				}
			}
		}
	}
}
