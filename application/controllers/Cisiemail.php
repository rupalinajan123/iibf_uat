<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cisiemail extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->library('email');
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->model('master_model');		
		$this->load->model('Emailsending');
		$this->load->model('log_model');
	
		//$this->load->model('chk_session');
	  //	$this->chk_session->chk_member_session();
	  
	  //accedd denied due to GST
		//$this->master_model->warning();
	}
	
	public function cisi_email(){ 
		
		$member_name = ''; 
		$address = '';
		$date = date('Y-m-d', strtotime('-1 day')); 
		//$date = ('2021-10-11');	
		$this->db->where('transaction_no !=','');
		$this->db->where('invoice_no !=','');
		$this->db->where('exam_code',993);
		$this->db->where('exam_period',997);
		$this->db->like('date_of_invoice',$date);
		$user_info=$this->master_model->getRecords('exam_invoice','','date_of_invoice,member_no');
		
		//echo $this->db->last_query();
		//exit;
		
		foreach($user_info as $res){
			$this->db->where('regnumber ',$res['member_no']);
			$this->db->where('isactive','1');
			$member_info = $this->master_model->getRecords('member_registration','','firstname,middlename,lastname,mobile,email,address1,address2,address3,address4,country,district,city,state');
			
			$member_name = $member_info[0]['firstname'].' '.$member_info[0]['middlename'].' '.$member_info[0]['lastname'];
			$address = $member_info[0]['address1'].' '.$member_info[0]['address2'].' '.$member_info[0]['address3'].' '.$member_info[0]['address4'].' '.$member_info[0]['city'];
			
			$insert_array = array(
									'member_no'=>$res['member_no'],
									'firstname'=>$member_info[0]['firstname'],
									'middlename'=>$member_info[0]['middlename'],
									'lastname'=>$member_info[0]['lastname'],
									'mobile'=>$member_info[0]['mobile'],
									'email'=>$member_info[0]['email'],
									'address1'=>$member_info[0]['address1'],
									'address2'=>$member_info[0]['address2'],
									'address3'=>$member_info[0]['address3'],
									'address4'=>$member_info[0]['address4'],
									'country'=>$member_info[0]['country'],
									'district'=>$member_info[0]['district'],
									'city'=>$member_info[0]['city'],
									'state'=>$member_info[0]['state'],
									'date_of_registration'=>$res['date_of_invoice']
									);
			
			
			$last_id = $this->master_model->insertRecord('cisi_email',$insert_array,true);
		} 
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download'); 
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "cisi.csv";
		$query = "SELECT * FROM cisi_email Where date_of_registration LIKE '%".$date."%' ";
		$result1 = $this->db->query($query);
		//$this->db->empty_table('center_stat'); 
		//force_download($filename, $data);
		if($result1->num_rows() > 0 )
		{
			$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
			$csv_handler = fopen ('uploads/cisi.csv','w');
			fwrite ($csv_handler,$data);
			fclose ($csv_handler);
			
			
			$attachpath = "uploads/cisi.csv";
			$final_str = 'Hello Sir/Madam <br/><br/>'; 
			$final_str.= 'CISI registration records of '.date('Y-m-d',strtotime("-1 days"));
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'ESDS TEAM'; 
			
			$arr_to = array('iibfdevp@esds.co.in','jd.exm@iibf.org.in','ad.exm1@iibf.org.in','dd.exm1@iibf.org.in');
			//$arr_to = array('iibfdevp@esds.co.in');
			$info_arr=array(	'to'=>$arr_to,
								'from'=>'noreply@iibf.org.in',
								'subject'=>'CISI Registration',
								'message'=>$final_str
							); 
							
			$files=array($attachpath);
			if($this->Emailsending->mailsend_attch($info_arr,$files)){
					echo "Mail send";
			} 
		}else{
		
			$final_str = 'Hello Sir/Madam <br/><br/>'; 
			$final_str.= 'There are no registration for the date '.date('Y-m-d',strtotime("-1 days"));
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'ESDS TEAM'; 
			
			$arr_to = array('iibfdevp@esds.co.in','jd.exm@iibf.org.in','ad.exm1@iibf.org.in','dd.exm1@iibf.org.in');
			//$arr_to = array('iibfdevp@esds.co.in');
			$info_arr=array('to'=>$arr_to,
								'from'=>'noreply@iibf.org.in',
								'subject'=>'CISI Registration last',
								'message'=>$final_str
							); 
							
			//$files=array($attachpath);
			if($this->Emailsending->mailsend($info_arr)){
					echo "No records Mail send";
			} 
		}
		
	}
	
	public function cisi_email_hit(){ 
		
		$member_name = ''; 
		$address = '';
		$date = date('2020-08-17'); 
		//$date = date('Y-m-d', strtotime('-1 day')); 
		
		
		$this->db->where('transaction_no !=','');
		$this->db->where('invoice_no !=','');
		$this->db->where('exam_code',993);
		$this->db->where('exam_period',997);
		$this->db->like('date_of_invoice',$date);
		$user_info=$this->master_model->getRecords('exam_invoice','','date_of_invoice,member_no');
		
		//echo $this->db->last_query();
		
		//exit;
		
		foreach($user_info as $res){
			$this->db->where('regnumber ',$res['member_no']);
			$this->db->where('isactive','1');
			$member_info = $this->master_model->getRecords('member_registration','','firstname,middlename,lastname,mobile,email,address1,address2,address3,address4,country,district,city,state');
			
			$member_name = $member_info[0]['firstname'].' '.$member_info[0]['middlename'].' '.$member_info[0]['lastname'];
			$address = $member_info[0]['address1'].' '.$member_info[0]['address2'].' '.$member_info[0]['address3'].' '.$member_info[0]['address4'].' '.$member_info[0]['city'];
			
			$insert_array = array(
									'member_no'=>$res['member_no'],
									'firstname'=>$member_info[0]['firstname'],
									'middlename'=>$member_info[0]['middlename'],
									'lastname'=>$member_info[0]['lastname'],
									'mobile'=>$member_info[0]['mobile'],
									'email'=>$member_info[0]['email'],
									'address1'=>$member_info[0]['address1'],
									'address2'=>$member_info[0]['address2'],
									'address3'=>$member_info[0]['address3'],
									'address4'=>$member_info[0]['address4'],
									'country'=>$member_info[0]['country'],
									'district'=>$member_info[0]['district'],
									'city'=>$member_info[0]['city'],
									'state'=>$member_info[0]['state'],
									'date_of_registration'=>$res['date_of_invoice']
									);
			
			
			//$last_id = $this->master_model->insertRecord('cisi_email',$insert_array,true);
		} 
		
		//$date = '2019-11-16';  
		
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download'); 
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "cisi_test.csv";
		$query = "SELECT * FROM cisi_email Where date_of_registration LIKE '%".$date."%' ";
		//$query = "SELECT * FROM cisi_email Where date_of_registration ";
		$result1 = $this->db->query($query);
		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
		//$this->db->empty_table('center_stat'); 
		//force_download($filename, $data);
		
		$csv_handler = fopen ('uploads/cisi_test.csv','w');
		fwrite ($csv_handler,$data);
		fclose ($csv_handler);
		
		
		$attachpath = "uploads/cisi_test.csv";
		//$attachpath = "uploads/admitcardpdf/1600_909_511000092.pdf"; 
		$final_str = 'Hello Sir/Madam <br/><br/>'; 
		$final_str.= 'CISI registration records of '.date('Y-m-d');
		//$final_str.= 'CISI registration records of 2019-11-16';
		$final_str.= '<br/><br/>';
		$final_str.= 'Regards,';
		$final_str.= '<br/>';
		$final_str.= 'ESDS TEAM'; 
		
		$arr_to = array('iibfdevp@esds.co.in','suhas@iibf.org.in','sgbhatia@iibf.org.in');
		//$arr_to = array('prafull.tupe@esds.co.in');
		
		$info_arr=array(//'to'=>$email[0]['email'],
							'to'=>$arr_to,
							'from'=>'noreply@iibf.org.in',
							'subject'=>'CISI Registration',
							'message'=>$final_str
						); 
						
		$files=array($attachpath);
		/*if($this->Emailsending->mailsend_attch($info_arr,$files)){
				echo "Mail send";
		}*/ 
		
		
	}

	public function cisi_email_custom(){ 
		
		$member_name = ''; 
		$address = '';
		//$date = date('Y-m-d', strtotime('-1 day')); 
		$date = ('2023-02-15');	
		$this->db->where('transaction_no !=','');
		$this->db->where('invoice_no !=','');
		$this->db->where('exam_code',993);
		$this->db->where('exam_period',997);
		//$this->db->like('date_of_invoice',$date);
		$this->db->where('date_of_invoice >= ',$date);
		$user_info=$this->master_model->getRecords('exam_invoice','','date_of_invoice,member_no');
		
		 //echo $this->db->last_query();echo'<br>';
		// exit;
		
		foreach($user_info as $res){
			$this->db->where('regnumber ',$res['member_no']);
			$this->db->where('isactive','1');
			$member_info = $this->master_model->getRecords('member_registration','','firstname,middlename,lastname,mobile,email,address1,address2,address3,address4,country,district,city,state');
			
			$member_name = $member_info[0]['firstname'].' '.$member_info[0]['middlename'].' '.$member_info[0]['lastname'];
			$address = $member_info[0]['address1'].' '.$member_info[0]['address2'].' '.$member_info[0]['address3'].' '.$member_info[0]['address4'].' '.$member_info[0]['city'];
			
			$insert_array = array(
									'member_no'=>$res['member_no'],
									'firstname'=>$member_info[0]['firstname'],
									'middlename'=>$member_info[0]['middlename'],
									'lastname'=>$member_info[0]['lastname'],
									'mobile'=>$member_info[0]['mobile'],
									'email'=>$member_info[0]['email'],
									'address1'=>$member_info[0]['address1'],
									'address2'=>$member_info[0]['address2'],
									'address3'=>$member_info[0]['address3'],
									'address4'=>$member_info[0]['address4'],
									'country'=>$member_info[0]['country'],
									'district'=>$member_info[0]['district'],
									'city'=>$member_info[0]['city'],
									'state'=>$member_info[0]['state'],
									'date_of_registration'=>$res['date_of_invoice']
									);
			
			
			$last_id = $this->master_model->insertRecord('cisi_email',$insert_array,true);
		} 
		// echo $this->db->last_query();echo'<br>';
		// exit;
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download'); 
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "cisi.csv";
		$query = "SELECT * FROM cisi_email Where date_of_registration LIKE '%".$date."%' ";
		$result1 = $this->db->query($query);
		//$this->db->empty_table('center_stat'); 
		//force_download($filename, $data);
		
		if($result1->num_rows() > 0 )
		{
			$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
			$csv_handler = fopen ('uploads/cisi.csv','w');
			fwrite ($csv_handler,$data);
			fclose ($csv_handler);
			
			
			$attachpath = "uploads/cisi.csv";
			$final_str = 'Hello Sir/Madam <br/><br/>'; 
			$final_str.= 'CISI registration records of '.date('Y-m-d',strtotime("-1 days"));
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'ESDS TEAM'; 
			
			//$arr_to = array('iibfdevp@esds.co.in','jd.exm@iibf.org.in','ad.exm1@iibf.org.in','dd.exm1@iibf.org.in');
			$arr_to = array('pooja.mane@esds.co.in');
			//$arr_to = array('iibfdevp@esds.co.in');
			$info_arr=array(	'to'=>$arr_to,
								'from'=>'noreply@iibf.org.in',
								'subject'=>'CISI Registration',
								'message'=>$final_str
							); 
							
			$files=array($attachpath);
			if($this->Emailsending->mailsend_attch($info_arr,$files)){
					echo "Mail send";
			} 
		}else{
		
			$final_str = 'Hello Sir/Madam <br/><br/>'; 
			$final_str.= 'There are no registration for the date '.date('Y-m-d',strtotime("-1 days"));
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'ESDS TEAM'; 
			
			//$arr_to = array('iibfdevp@esds.co.in','jd.exm@iibf.org.in','ad.exm1@iibf.org.in','dd.exm1@iibf.org.in');
			//$arr_to = array('iibfdevp@esds.co.in');
			$arr_to = array('pooja.mane@esds.co.in');
			$info_arr=array('to'=>$arr_to,
								'from'=>'noreply@iibf.org.in',
								'subject'=>'CISI Registration last',
								'message'=>$final_str
							); 
							
			//$files=array($attachpath);
			if($this->Emailsending->mailsend($info_arr)){
					echo "No records Mail send";
			} 
		}
		
	}
}