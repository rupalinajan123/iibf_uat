<?php
//defined('BASEPATH') OR exit('No direct script access allowed');

class Duplicate_member extends CI_Controller {
			
	public function __construct()
	{
			parent::__construct();
			$this->load->model('UserModel');
			$this->load->model('Master_model');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->helper('custom_admitcard_helper');
		
	}
	public function index()
	{	
    	## heck duplicate email
		$get_members = $this->db->query("SELECT regid,`regnumber`,member_registration.email,mobile,createdon
 FROM member_registration 
   INNER JOIN (SELECT email
               FROM   member_registration
               WHERE member_registration.regnumber != '' AND member_registration.isactive = '1' AND member_registration.registrationtype = 'O' AND member_registration.createdon > '2021-02-23'
               GROUP  BY email
               HAVING COUNT(email) > 1) dup
           ON member_registration.email = dup.email WHERE member_registration.regnumber != '' AND member_registration.isactive = '1' AND member_registration.registrationtype = 'O' AND member_registration.createdon > '2021-02-23'");
		if(!empty($get_members))
		{
			//print_r($get_members->result_array()); echo "herer";die;			
			foreach($get_members->result_array() as $row)
			{
				$regid  = $row['regid'];
				$regnumber  = $row['regnumber'];
				$email  = $row['email'];
				$mobile  = $row['mobile'];
				$createdon  = $row['createdon'];
							
				## check if record exist
				$get_row = $this->master_model->getRecords('duplicate_registrations',array('regnumber'=>$regnumber),'regid');
				//echo $this->db->last_query();die;
				if(empty($get_row))	
				{	
					## check payment transaction entires
					$get_details = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber),'status,transaction_no,receipt_no');
					//echo $this->db->last_query();die;
					$rec_count  = count($get_details);
					if(!empty($get_details) && $rec_count > 0)	
					{	
						foreach($get_details as $rowdata)
						{
							if($rowdata['status'] == '1')
								$status = '1';
							$transaction_no = $rowdata['transaction_no'];
							$receipt_no = $rowdata['receipt_no'];
						}
					}else{ $status = '0';
					$transaction_no = '';
					$receipt_no = '';
					}
					$insert_array = array(
										'regid' => $regid,
										'regnumber' => $regnumber,
										'email' => $email,
										'mobile' => $mobile,
										'created_on'=>$createdon,
										'status'=>$status,
										'count_flag'=>$rec_count,
										'transaction_no'=>$transaction_no,
										'receipt_no'=>$receipt_no
										);
										
					$insert_id = $this->master_model->insertRecord('duplicate_registrations', $insert_array);					
					//echo $this->db->last_query(); die;
				}
			}//foreach
		}//if	
			
	}
	public function dup_mobile()
	{	
    	## heck duplicate mobile
		$get_members = $this->db->query("SELECT regid,`regnumber`,member_registration.email,member_registration.mobile,createdon
 FROM member_registration 
   INNER JOIN (SELECT mobile
               FROM   member_registration
               WHERE member_registration.regnumber != '' AND member_registration.isactive = '1' AND member_registration.registrationtype = 'O' AND member_registration.createdon > '2021-02-23'
               GROUP  BY mobile
               HAVING COUNT(mobile) > 1) dup
           ON member_registration.mobile = dup.mobile WHERE member_registration.regnumber != '' AND member_registration.isactive = '1' AND member_registration.registrationtype = 'O' AND member_registration.createdon > '2021-02-23'");
		if(!empty($get_members))
		{
			foreach($get_members->result_array() as $row)
			{
				$regid  = $row['regid'];
				$regnumber  = $row['regnumber'];
				$email  = $row['email'];
				$mobile  = $row['mobile'];
				$createdon  = $row['createdon'];
				## check if record exist
				$get_row = $this->master_model->getRecords('duplicate_registrations',array('regnumber'=>$regnumber),'regid');
				if(empty($get_row))	
				{	
					## check payment transaction entires
					$get_details = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber),'status,transaction_no,receipt_no');
					//echo $this->db->last_query();die;
					$rec_count  = count($get_details);
					if(!empty($get_details) && $rec_count > 0)	
					{	
						foreach($get_details as $rowdata)
						{
							if($rowdata['status'] == '1')
								$status = '1';
							$transaction_no = $rowdata['transaction_no'];
							$receipt_no = $rowdata['receipt_no'];
						}
					}else{ $status = '0';
					$transaction_no = '';
					$receipt_no = '';
					}
					$insert_array = array(
										'regid' => $regid,
										'regnumber' => $regnumber,
										'email' => $email,
										'mobile' => $mobile,
										'created_on'=>$createdon,
										'status'=>$status,
										'count_flag'=>$rec_count,
										'transaction_no'=>$transaction_no,
										'receipt_no'=>$receipt_no
										);
					$insert_id = $this->master_model->insertRecord('duplicate_registrations', $insert_array);					
					//echo $this->db->last_query(); die;
				}
			}//foreach
		}//if	
			
	}

	
	public function member_dbf()
	{	
    	## heck duplicate email
		$get_members = $this->db->query("SELECT regid,`regnumber`,member_registration.email,mobile,createdon
 FROM member_registration 
   INNER JOIN (SELECT email
               FROM   member_registration
               WHERE member_registration.regnumber != '' AND member_registration.isactive = '1' AND member_registration.registrationtype = 'DB' AND member_registration.createdon > '2021-02-01'
               GROUP  BY email
               HAVING COUNT(email) > 1) dup
           ON member_registration.email = dup.email WHERE member_registration.regnumber != '' AND member_registration.isactive = '1' AND member_registration.registrationtype = 'DB' AND member_registration.createdon > '2021-02-01'");
		if(!empty($get_members))
		{
			//print_r($get_members->result_array()); echo "herer";die;			
			foreach($get_members->result_array() as $row)
			{
				$regid  = $row['regid'];
				$regnumber  = $row['regnumber'];
				$email  = $row['email'];
				$mobile  = $row['mobile'];
				$createdon  = $row['createdon'];
							
				## check if record exist
				$get_row = $this->master_model->getRecords('duplicate_registrations',array('regnumber'=>$regnumber),'regid');
				//echo $this->db->last_query();die;
				if(empty($get_row))	
				{	
					## check payment transaction entires
					$get_details = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber),'status,transaction_no,receipt_no');
					//echo $this->db->last_query();die;
					$rec_count  = count($get_details);
					if(!empty($get_details) && $rec_count > 0)	
					{	
						foreach($get_details as $rowdata)
						{
							if($rowdata['status'] == '1')
								$status = '1';
							$transaction_no = $rowdata['transaction_no'];
							$receipt_no = $rowdata['receipt_no'];
						}
					}else{ $status = '0';
					$transaction_no = '';
					$receipt_no = '';
					}
					$insert_array = array(
										'regid' => $regid,
										'regnumber' => $regnumber,
										'email' => $email,
										'mobile' => $mobile,
										'created_on'=>$createdon,
										'status'=>$status,
										'count_flag'=>$rec_count,
										'transaction_no'=>$transaction_no,
										'receipt_no'=>$receipt_no
										);
										
					$insert_id = $this->master_model->insertRecord('duplicate_registrations_DB&F', $insert_array);					
					//echo $this->db->last_query(); die;
				}
			}//foreach
		}//if	
			
	}
	
	
	
	
	
	}	
?>
