<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BulkTransaction_test extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('institute_id') && !$this->session->userdata('bulk_admin')) {
			redirect('bulk/Banklogin');
		}	
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
				$this->load->helper('bulk_proforma_invoice_helper');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->elearning_course_code =  [528,529,530,531,534];
		// bulk_userActivity_log($log_title, $log_desc = "",$inst_id = "", $rId = NULL, $regNo = NULL)
	}
	
	
	    
	// function run for old table for exam code 101
	public function getadmitcardpdfsp($member_id,$exam_code){
		try{
			/*if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}*/
	
			
			$exam_code = $exam_code;
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info');
			$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			if(!empty($result))
			{
			if($exam_code != 101){
				if($result->vendor_code == 3){
					$vcenter = '3';
				}elseif($result->vendor_code == 1){
					$vcenter = '1';
				}
			}elseif($exam_code == 101){
				$this->db->select('center_code'); 
				$this->db->from('sify_center');
				$scenter = $this->db->get();
				$sifyresult = $scenter->result();
				foreach($sifyresult as $sifyresult){
					$sifycenter[] = $sifyresult->center_code;
				}
				
				$this->db->select('center_code'); 
				$this->db->from('nseit_center');
				$ncenter = $this->db->get();
				$nseitresult = $ncenter->result();
				foreach($nseitresult as $nseitresult){
					$nseitcenter[] = $nseitresult->center_code;
				}
				
				if(in_array($result->center_code, $nseitcenter)){
					$vcenter = '3';
				}
				if(in_array($result->center_code, $sifycenter)){
					$vcenter = '1';
				}
			}
			
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_info');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate);
			
			$html=$this->load->view('admitcardpdf', $data, true);
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");  
		}	
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function naar_getadmitcardpdfsp()
	{
		 $member_id = $this->uri->segment(4);
		 $exam_code = $this->uri->segment(5);
		 $exam_period = $this->uri->segment(6);
		
		
		$this->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code,insname');
		$this->db->from('admit_card_details');
		$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
		$this->db->order_by("admitcard_id", "desc");
		$member_record = $this->db->get();
		$member_result = $member_record->row();
		
		// echo $this->db->last_query();die;
		
		if(sizeof($member_result) == 0){
			return '';
			exit;
		}else{
		
		if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){
			$vcenter = $member_result->vendor_code;
		}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){
			$vcenter = '0';
		}
		
		$medium_code = $member_result->m_1;
		
		$this->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$this->db->from('admit_card_details');
		$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
		$this->db->group_by('venueid');
		$this->db->order_by("exam_date", "asc");
		$venue_record = $this->db->get();
		$venue_result = $venue_record->result();
		// echo $this->db->last_query();die;
		$this->db->select('description');
		$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		// echo $this->db->last_query();die;
		$this->db->select('subject_description,admit_card_details.exam_date,time,venueid,seat_identification');
		$this->db->from('admit_card_details');
		$this->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3)');
		$this->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period,'record_source'=>'bulk'));
		$this->db->where('pwd!=','');
		$this->db->where('seat_identification!=','');
		$this->db->where('remark',1);
		$this->db->order_by("admit_card_details.exam_date", "asc");
		$subject = $this->db->get();
		$subject_result = $subject->result();
		
		// echo $this->db->last_query();die;
		
		$pdate = $subject->result();
		foreach($pdate as $pdate){
			$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); ;
			$examdate = explode("-",$exdate);
			$examdatearr[] = $examdate[1];
		}
		
		$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); 
		$examdate = explode("-",$exdate);
		$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
		
		
		
		if($medium_code == 'ENGLISH' || $medium_code == 'E'){
			$medium_code_lng = 'E';
		}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
			$medium_code_lng = 'H';
		}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
			$medium_code_lng = 'A';
		}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
			$medium_code_lng = 'G';
		}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
			$medium_code_lng = 'K';
		}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
			$medium_code_lng = 'L';
		}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
			$medium_code_lng = 'M';
		}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
			$medium_code_lng = 'N';
		}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
			$medium_code_lng = 'O';
		}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
			$medium_code_lng = 'S';
		}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
			$medium_code_lng = 'T';
		}
		
		$this->db->where("isactive", '1');

    	$memberDetails = $this->master_model->getRecords('member_registration', array('regnumber' => $member_id));

    	// added by pooja mane for associated institute name display in bulk admit cards - 2024-8-20
    	$this->db->where("isactive", '1');
    	$this->db->join('institution_master','institution_master.institude_id = member_registration.associatedinstitute');
    	$instituteDetails = $this->master_model->getRecords('member_registration', array('regnumber' => $member_id),'name as associatedinstitute');
    	// $instituteDetails = $institute->row();
    	// added by pooja mane for associated institute name display in bulk admit cards end - 2024-8-20
    	// echo $this->db->last_query();die;
    	

		$this->db->select('medium_description');
		$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
		$medium_result = $medium->row();
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period,'memberDetails'=>$memberDetails,"instituteDetails"=>$instituteDetails);
		
		// print_r($data);die;
		if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 1015 || $exam_code == 1017 || $exam_code == 1018 || $exam_code == 1026 || $exam_code == 1027 || $exam_code == 1029 || $exam_code == 1028 ||$exam_code == 1030 || $exam_code == 1033 || $exam_code == 1034 || $exam_code == 1043 || $exam_code == 1048){
			// ECHO '***';die;
			$html=$this->load->view('remote_admitcardpdf_attach', $data, true);
		}else{
			// echo "<pre>";
			$html=$this->load->view('bulk_admitcardpdf_attach', $data, true);
			// print_r($html);die;
		}

		$this->load->library('m_pdf');
		$pdf = $this->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		//$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
		$pdf->Output($pdfFilePath, "D"); 
		
		}
			
	}
	
	
	public function transactions()
	{
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'bulk/bank_dashboard"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">Transactions</li>
		 </ol>';
		$data['middle_content']	= 'bulk/transaction/transactions';
		
		//$data  = array('middle_content' => 'bulk/transaction/transactions','breadcrumb' => $data["breadcrumb"]);
       	$this->load->view('bulk/bulk_common_view', $data);
		
		//$this->load->view('bulk/common_view',$data);
		
		//$this->load->view('bulk/transaction/transactions',$data);
	}
	
	// function to get list of All transactions-
	public function getTransactions()
	{
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		
		$session_arr = check_session();
		
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		
		$reg_no = '';
		
		$where = '';
		if($value != "")
		{
			$temp_where = array();
			
			$post_data = explode('~',$value);
			
			//print_r($post_data); die();
			
			if(count($post_data) > 0)
			{
				$reg_no			= isset($post_data[0]) ? $post_data[0] : '';
				$txn_no 		= isset($post_data[1]) ? $post_data[1] : '';
				$from_date 		= isset($post_data[2]) ? $post_data[2] : '';
				$to_date 		= isset($post_data[3]) ? $post_data[3] : '';
				$payment_mode 	= isset($post_data[4]) ? $post_data[4] : '';
				$payment_status = isset($post_data[5]) ? $post_data[5] : '';
				/*$inst_code 		= isset($post_data[6]) ? $post_data[6] : '';
				$exam_period 	= isset($post_data[7]) ? $post_data[7] : '';
				$exam_code 		= isset($post_data[8]) ? $post_data[8] : '';*/
				
				if($reg_no != "")
				{
					$temp_where[] = 'member_registration.regnumber = "'.$reg_no.'"';
				}
				
				if($txn_no != "")
				{
					$temp_where[] = 'bulk_payment_transaction.transaction_no = "'.$txn_no.'" OR bulk_payment_transaction.UTR_no = "'.$txn_no.'"';
				}
				
				if($from_date != "" && $to_date == "")
				{
					$temp_where[] = 'DATE(bulk_payment_transaction.date) = "'.$from_date.'"';
				}
				else if($from_date != "" && $to_date != "")
				{
					$temp_where[] = '(DATE(bulk_payment_transaction.date) BETWEEN "'.$from_date.'" AND "'.$to_date.'")';
				}
				
				if($payment_mode != "")
				{
					$temp_where[] = 'gateway = "'.$payment_mode.'"';
				}
				
				if($payment_status != "")
				{
					$temp_where[] = 'bulk_payment_transaction.status = "'.$payment_status.'"';
				}
				else
				{
					$temp_where[] = '(bulk_payment_transaction.status = "0" OR bulk_payment_transaction.status = "1")';	// status = success or fail	
				}
				
				/*if($inst_code != "")
				{
					$temp_where[] = 'bulk_payment_transaction.inst_code = "'.$inst_code.'"';
				}*/
				
				if( !empty($temp_where))
				{
					$where .= implode(" AND ", $temp_where);		


				}
			}
		}
		else
		{
			$where .= '(status = "0" OR status = "1")';	// status = success or fail
		}
		
	 	$select = 'bulk_payment_transaction.id,bulk_payment_transaction.exam_code,bulk_payment_transaction.exam_period,gateway,bulk_payment_transaction.inst_code,bulk_payment_transaction.receipt_no,status,bulk_payment_transaction.transaction_no,UTR_no,DATE_FORMAT(date,"%d-%m-%Y") As pay_date,pay_count AS member_count,amount,exam_invoice.disc_amt,exam_invoice.tds_amt,bulk_accerdited_master.institute_name AS inst_name';	
		
		$this->db->join('bulk_accerdited_master','bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code','LEFT');
		
		if($reg_no != "")
		{
			$this->db->join('bulk_member_payment_transaction','bulk_member_payment_transaction.ptid = bulk_payment_transaction.id','LEFT');
			$this->db->join('member_exam','member_exam.id = bulk_member_payment_transaction.memexamid','LEFT');
			$this->db->join('member_registration','member_registration.regnumber = member_exam.regnumber','LEFT');
			$this->db->where('member_registration.isactive = "1"');
		}
		
		/* Display only current logged in institute transactions */
		//$instdata = $this->session->userdata('dra_institute');
		//$instcode = $instdata['institute_code'];
		$instcode = trim($this->session->userdata('institute_id'),"_Admin");
		if( !empty( $instcode ) ) { 
			$where .= ' AND bulk_payment_transaction.inst_code = '.$instcode;
		}
		
		//do not count records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
		//$where .= ' AND date(bulk_payment_transaction.date) > "2017-01-08"';
		
		$this->db->where($where);
		$this->db->where("bulk_payment_transaction.exam_code !=",'997');
		// get total record count for pagination
		$total_row = $this->UserModel->getRecordCount("bulk_payment_transaction","","");
		
		//$data['query1'] = $this->db->last_query();
		//exit;
		
		// get transaction records
		$this->db->join('bulk_accerdited_master','bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code','LEFT');
		
		$this->db->join('exam_invoice','exam_invoice.pay_txn_id = bulk_payment_transaction.id','LEFT');
		$this->db->where('exam_invoice.app_type = "Z"'); // app_type 'Z' in exam_invoice table is for Bulk exam module
		
		if($reg_no != "")
		{
			$this->db->join('bulk_member_payment_transaction','bulk_member_payment_transaction.ptid = bulk_payment_transaction.id','LEFT');
			$this->db->join('member_exam','member_exam.id = bulk_member_payment_transaction.memexamid','LEFT');
			$this->db->join('member_registration','member_registration.regnumber = member_exam.regnumber','LEFT');
			$this->db->where('member_registration.isactive = "1"');
		}
		/* Display only current logged in institute transactions */
		//$instdata = $this->session->userdata('dra_institute');
		//$instcode = $instdata['institute_code'];
		/*$instcode = $this->session->userdata('institute_id');
		if( !empty( $instcode ) ) {
			$where .= ' AND bulk_payment_transaction.inst_code = '.$instcode;
		}*/
		
		// transactions order by date in descending order -
		$sortkey = 'updated_date';
		$sortval = 'DESC';
		//do not show records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
		//$where .= ' AND date(bulk_payment_transaction.date) > "2017-01-08"';
		$this->db->where($where);
		
		$res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', $sortkey, $sortval, $per_page, $start);
		//print_r( $this );
		
		//echo $this->db->last_query();
		//exit; 
		
		$data['query'] = $this->db->last_query();
		
		if($res)
		{
			$result = $res->result_array();
			
			$result_new = array();
			
			foreach($result as $row)
			{
				$pay_status = $row['status'];
				
				foreach($row as $key => $value)
				{
					if($key == "status" && $value == 1) // status = 1 for Success
					{
						$row['status'] = '<span class="label label-success">Success</span>';
					}
					else if($key == "status" && $value == 0) // status = 0 for Error
					{
						$row['status'] = '<span class="label label-danger">Fail</span>';	
					}
					else if($key == "gateway" && $value == "1") // gateway = 1 for NEFT/RTGS
					{
						$row['transaction_no'] = $row['UTR_no'];	
					}
					
					// if transaction no is empty -
					if($key == "transaction_no" || $key == "UTR_no")
					{
						if($row['transaction_no'] == "" && $row['UTR_no'] == "")
						{
							$row['transaction_no'] = 'NA';
						}
					}
					
					// if bankcode is empty -
					/*if($key == "bankcode")
					{
						if($row['bankcode'] == "")
						{
							$row['bankcode'] = 'NA';	
						}
					}*/
					
					// get reg nos. for each payment transaction -
					$reg_no_list = array();
					
					$select = 'member_registration.regnumber';	
					$this->db->join('member_exam','member_exam.id = bulk_member_payment_transaction.memexamid','LEFT');
					$this->db->join('member_registration','member_registration.regnumber = member_exam.regnumber','LEFT');
					$this->db->where('member_registration.isactive = "1"');
					$this->db->where('bulk_member_payment_transaction.ptid = '.$row['id']);
					$res = $this->UserModel->getRecords("bulk_member_payment_transaction", $select, '', '', '', '', '', '');
					$result2 = $res->result_array();
					
					/*$select = 'member_exam.regnumber';	
					$this->db->join('member_exam','member_exam.id = bulk_member_payment_transaction.memexamid','LEFT');
					$this->db->where('bulk_member_payment_transaction.ptid = '.$row['id']);
					$res = $this->UserModel->getRecords("bulk_member_payment_transaction", $select, '', '', '', '', '', '');
					$result2 = $res->result_array();*/
					
					//$data['query3'] = $this->db->last_query();
					
					foreach($result2 as $row2)
					{
						$reg_no_list[] = $row2['regnumber'];	
					}
					
					//$reg_nos = implode(",", $reg_no_list);
					
					$reg_nos = "";
					$cnt = 0;
					if(count($reg_no_list) > 0 && $reg_no_list[0] != "")	// check if more than 1 reg. no.
					{
						$temp_arr = array();
						foreach($reg_no_list as $r)
						{
							$temp_list = '';
							
							$cnt++;
							$temp_list .= $r;
							if($cnt % 2 == 0)
								$temp_list .= "<br>";	// display 2 reg. nos. each line
								
							$temp_arr[] = $temp_list;
						}
						$reg_nos .= implode(',', $temp_arr);
					}
					else
					{
						$reg_nos .= "-";
					}
					$row['paid_reg_nos'] = $reg_nos;
				}
				
				$result_new[] = $row;
				
				// action -
				$action = '<a href="'.base_url().'bulk/BulkTransaction/view_inst_receipt/'.base64_encode($row['id']).'" target="_blank">Receipt</a>';
				
				/******************* code added for GST Changes, by Bhagwan Sahane, on 07-07-2017 ***************/
				
				
				
					// get invoice image for this transaction
				$invoice_img_path = $date_invoice = '';
				
				$this->db->where('exam_invoice.app_type = "Z"');
				$exam_invoice = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $row['id']),'invoice_image,date_of_invoice,gstin_no,invoice_no');
				if(count($exam_invoice) > 0)
				{
					// get invoice image path
					$invoice_image = $exam_invoice[0]['invoice_image'];
					if($invoice_image)
					{
						$invoice_img_path = $invoice_image;
					}
					
					// get invoice date
					$date_invoice = $exam_invoice[0]['date_of_invoice'];					
				}
				
				/*if($invoice_img_path != '')
				{
					if($exam_invoice[0]['date_of_invoice'] < '2020-12-31 23:59:59'){
					$action .= ' <br> | <a href="'.base_url().'uploads/bulkexaminvoice/supplier/'.$invoice_img_path.'" target="_blank">Invoice</a>';
					}else{
						if($exam_invoice[0]['gstin_no'] == ''){
						$action .= ' <br> | <a href="'.base_url().'uploads/bulkexaminvoice/supplier/'.$invoice_img_path.'" target="_blank">Invoice</a>';
						}
					}
				}*/
				## Code added by Pratibha
				$str_invoice_no = str_replace("/","_",$exam_invoice[0]['invoice_no']);
				if($invoice_img_path != '')
				{
					if($date_invoice < '2021-01-01' && $date_invoice !=''){
						$action .= ' <br> <a href="'.base_url().'uploads/bulkexaminvoice/supplier/'.$invoice_img_path.'" target="_blank">Invoice</a> ';
					}else{
						if($exam_invoice[0]['gstin_no'] == ''){
							$action .= ' <br> | <a href="'.base_url().'uploads/bulkexaminvoice/supplier/'.$invoice_img_path.'" target="_blank">Invoice</a>';
						}else{
							$action .= ' <br> | <a href="'.base_url().'bulk/BulkTransaction/getInvoice/'.base64_encode($str_invoice_no).'" target="_blank">E-Invoice<span class="hide">'.$date_invoice.'</span></a>';
						}
					}
				}
				
				
				/******************* eof code added for GST Changes, by Bhagwan Sahane, on 07-07-2017 ***************/
			  
				// admit card -
				if(isset($row['exam_code']) && $row['exam_code'] != "" && isset($this->elearning_course_code) && count($this->elearning_course_code) > 0){
					//&& ! in_array($row['exam_code'], $this->elearning_course_code)
					if($pay_status == 1) // if payment status is Success
					{
						$action .= ' <br> | <a href="'.base_url().'bulk/BulkTransaction/mem_admit_card_list/'.base64_encode($row['id']).'" target="_blank">Admit Card</a>';
					}
				}
				
				
				$data['action'][] = $action;
			}
			
			$data['result'] = $result_new;
			
			if(count($result_new))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
				
			$url = base_url()."bulk/BulkTransaction/getTransactions/";
			//$total_row = count($result_new);
			$config = pagination_init($url,$total_row, $per_page, 2);
			$this->pagination->initialize($config);
			
			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if(($start+$per_page)>$total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start+$per_page;
			
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';
			$data['index'] = $start+1;
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
	
	// function to view DRA institute payment receipt -
	public function view_inst_receipt($txn_id)
	{
		$txn_id = base64_decode($txn_id);
		
		$select = 'bulk_payment_transaction.id,bulk_payment_transaction.inst_code,receipt_no,gateway,transaction_no,UTR_no,amount,DATE_FORMAT(updated_date,"%d-%m-%Y") As date,exam_period,status,bulk_accerdited_master.institute_name AS inst_name,bulk_accerdited_master.email AS inst_email'; 
		$this->db->join('bulk_accerdited_master','bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code','LEFT');
		$this->db->where('bulk_payment_transaction.id = "'.$txn_id.'"');
		$res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');
		
		$result = $res->result_array();
		
		$data['result'] = $result[0];
		// echo "<pre>";
		// print_r($data);die;
		 
		$this->load->view('bulk/transaction/view_inst_receipt',$data);
	}
	
	// function to view member receipt list for DRA payment -
	public function mem_receipt_list($txn_id)
	{
		$txn_id = base64_decode($txn_id);
		
		$data['txn_id'] = $txn_id;
		
		// get payment transaction details -
		$select = 'bulk_payment_transaction.id,bulk_payment_transaction.inst_code,receipt_no,transaction_no,pay_count,amount,DATE_FORMAT(date,"%d-%m-%Y") As date,exam_period,status,bulk_accerdited_master.institute_name AS inst_name,bulk_accerdited_master.email AS inst_email'; 
		$this->db->join('bulk_accerdited_master','bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code','LEFT');
		$this->db->where('bulk_payment_transaction.id = "'.$txn_id.'"');
		$res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');
		
		$txn_result = $res->result_array();
		
		$total_amount = $txn_result[0]['amount'];
		
		$data['total_amount'] = $total_amount;
		
		// get list of all members for this payment transaction -
		$select = 'member_registration.regid,member_registration.regnumber,member_registration.firstname,member_registration.lastname,member_registration.email,member_exam.exam_fee';	
		$this->db->join('member_exam','member_exam.id = bulk_member_payment_transaction.memexamid','LEFT');
		$this->db->join('member_registration','member_registration.regnumber = member_exam.regnumber','LEFT');
		$this->db->where('bulk_member_payment_transaction.ptid = '.$txn_id);
		$this->db->where('member_registration.isactive = ', '1');
		$res = $this->UserModel->getRecords("bulk_member_payment_transaction", $select, '', '', '', '', '', '');
	
		//echo ">>".$this->db->last_query();
		$result = $res->result_array();
		$data['result'] = $result;
		 
		$this->load->view('bulk/transaction/mem_receipt_list',$data);
	}
	
	// function to get member receipt details -
	public function mem_receipt($txn_id,$mem_id)
	{
		$txn_id = base64_decode($txn_id);
		$mem_id = base64_decode($mem_id);
		
		// get payment transaction details -
		$select = 'bulk_payment_transaction.id,bulk_payment_transaction.inst_code,receipt_no,transaction_no,pay_count,amount,DATE_FORMAT(updated_date,"%d-%m-%Y") As date,exam_period,status, gateway, UTR_no, bulk_accerdited_master.institute_name AS inst_name,bulk_accerdited_master.email AS inst_email'; 
		$this->db->join('bulk_accerdited_master','bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code','LEFT');
		$this->db->where('bulk_payment_transaction.id = "'.$txn_id.'"');
		$res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');
		
		$txn_result = $res->result_array();
		

		$data['txn_details'] = $txn_result[0];
		
		// get list of all members for this payment transaction -
		$select = 'member_registration.regid,member_registration.regnumber,member_registration.firstname,member_registration.lastname,email,member_exam.exam_fee, member_exam.exam_center_code,member_exam.exam_code, member_exam.exam_medium';	
		$this->db->join('member_exam','member_exam.id = bulk_member_payment_transaction.memexamid','LEFT');
		$this->db->join('member_registration','member_registration.regnumber = member_exam.regnumber','LEFT');
		$this->db->where('member_registration.isactive = "1"');
		$this->db->where('bulk_member_payment_transaction.ptid = '.$txn_id.' AND member_registration.regid = '.$mem_id);
		$res = $this->UserModel->getRecords("bulk_member_payment_transaction", $select, '', '', '', '', '', '');
		
		$mem_result = $res->result_array();
		$memresult = $mem_result[0];
		
		$memresult['centername'] = '';
		$memresult['mediumname'] = '';
		if( $memresult ) {
			$mediumcode = $memresult['exam_medium'];
			$centercode = $memresult['exam_center_code'];
			$mediumname = $this->master_model->getValue('medium_master',array('medium_code'=>$mediumcode), 'medium_description');
			$centername = $this->master_model->getValue('center_master',array('center_code'=>$centercode,'exam_name'=>$memresult['exam_code']), 'center_name');
			//echo $this->db->last_query();
			$memresult['centername'] = $centername;
			$memresult['mediumname'] = $mediumname;
		}
		
		$data['mem_details'] = $memresult;
		 
		$this->load->view('bulk/transaction/mem_receipt',$data);
	}
	
	// function to view Bank institute Admit Card List -
	public function mem_admit_card_list($txn_id)
	{
		$txn_id = base64_decode($txn_id);
		
		$data['txn_id'] = $txn_id;
		
		// get list of all members for this payment transaction -
		$select = 'member_registration.regid,member_registration.regnumber,member_registration.firstname,member_registration.middlename,member_registration.lastname,member_registration.email,member_registration.mobile,member_exam.exam_code,member_exam.exam_period,member_registration.bank_emp_id,member_exam.institute_id';	
		$this->db->join('member_exam','member_exam.id = bulk_member_payment_transaction.memexamid','LEFT');
		$this->db->join('member_registration','member_registration.regnumber = member_exam.regnumber','LEFT');
		$this->db->where('member_registration.isactive = "1"');
		$this->db->where('bulk_member_payment_transaction.ptid = '.$txn_id);
		$res = $this->UserModel->getRecords("bulk_member_payment_transaction", $select, '', '', '', '', '', '');
		
		// echo $this->db->last_query(); die();
		
		$result = $res->result_array();
		// echo '<pre>';
		// print_r($result );die;

		if(isset($_POST['submit_excel']) && $_POST['submit_excel'] != "")
		{
			// Excel file name for download 
			$fileName = "Admitcard_details_".$result[0]['exam_code']."_".$result[0]['exam_period']."_.xls"; 
			 
			// Column names 
			$fields = array('Sr. No.', 'Exam Code', 'Exam Period','Member Number','Member Name','Employee ID','Email','Mobile','Institute Code', 'Reapeter'); 
			 
			// Display column names as first row 
			$excelData = implode("\t", array_values($fields)) . "\n"; 
			 
			// Fetch records from database 
			if($result)
			{ 
			    // Output each row of the data 
			    $i=1;
			    foreach($result as $record)
			    { 
			    	//echo'<pre>';print_r($record);DIE;
			    	$this->db->select('app_category');
					$this->db->where('regnumber', $record['regnumber']);
					$this->db->where('exam_code', $record['exam_code']);
					$this->db->where('exam_period', $record['exam_period']);
					$repeater =  $this->master_model->getRecords("member_exam");

					if($repeater[0]['app_category'] == 'B1_2' || $repeater[0]['app_category'] == 'B2_1')
					{ $reapeter_flag = 'Yes'; } 
					else
					{ $reapeter_flag = 'No'; }

			    	$member_name = $record['firstname'].' '.$record['middlename'].' '.$record['lastname']; 

			    	$lineData = array($i, $record['exam_code'], $record['exam_period'], $record['regnumber'],$member_name, $record['bank_emp_id'],$record['email'],$record['mobile'],$record['institute_id'],  $reapeter_flag); 
			        array_walk($lineData, 'filterData'); 
			        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
			        $i++;
			    } 
			}
			else
			{ 
			    $excelData .= 'No records found...'. "\n";
			}
			// Headers for download 
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=\"$fileName\""); 
			 
			// Render excel data 
			echo $excelData; 
			 
			exit;
		}
		
		// exit;
		$data['result'] = $result;
		$data['exam_code'] = '';
		if (count($result)) {

			$data['exam_code'] = $result[0]['exam_code'];
		}
		 
		$this->load->view('bulk/admin/transaction/mem_admit_card_list',$data);
	}
	
	public function neft_transactions()
	{
		$this->session->set_userdata('field','');
		$this->session->set_userdata('value','');
		$this->session->set_userdata('per_page','');
		$this->session->set_userdata('sortkey','');
		$this->session->set_userdata('sortval','');
		
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="'.base_url().'bulk/bank_dashboard"><i class="fa fa-home"></i> Home</a></li>
			  <li class="active">NEFT Details</li>
		 </ol>';
		 
		//$this->load->view('bulk/transaction/neft_transactions',$data);

		  $this->db->where('state_delete', '0');
		$data['states'] = $this->master_model->getRecords('state_master');
		
		$data['middle_content']	= 'bulk/transaction/neft_transactions_test';
		
       	$this->load->view('bulk/bulk_common_view', $data);
	}
	
	// function to get list of NEFT transactions-
	public function getNeftTransactions()
	{
		$data['result'] = array();
		$data['action'] = array();
		$data['pay_action'] = array();//pooja 2025-01-16
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		$pay_action = '-';
		// echo '*******';die;
		$session_arr = check_session();
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		
		// transactions order by date in descending order -
		$sortkey = 'created_date';
		$sortval = 'DESC';
		
		// get total record count for pagination
		$this->db->select('count(*) as tot');
		$this->db->join('bulk_accerdited_master','bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code','LEFT');
		$this->db->join('exam_master','exam_master.exam_code = bulk_payment_transaction.exam_code','LEFT');
		$this->db->where("bulk_payment_transaction.inst_code",trim($this->session->userdata('institute_id'),"_Admin"));
		$this->db->where('gateway = "1"');
		
		//do not count records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
		//$this->db->where('date(bulk_payment_transaction.date) > ','2017-01-08');
		$this->db->where("bulk_payment_transaction.exam_code !=",'997');
		$resArr = $this->db->get("bulk_payment_transaction");
		
		if($resArr)
		{
			$result = $resArr->result_array();
		}
		$total_row = $result[0]["tot"];
		
		$data['query1'] = $this->db->last_query();
		
		$select = 'bulk_payment_transaction.id,bulk_payment_transaction.exam_code,bulk_payment_transaction.exam_period,bulk_payment_transaction.inst_code,UTR_no AS transaction_no,exam_master.description AS Bulk,pay_count AS member_count,amount,exam_invoice.disc_amt,exam_invoice.tds_amt,DATE_FORMAT(date,"%d-%m-%Y") As pay_date,bulk_payment_transaction.created_date AS added_date,bulk_payment_transaction.updated_date,bulk_payment_transaction.exam_period,status,bulk_accerdited_master.institute_name AS inst_name'; // "Bulk" is Application in NEFT Transactions table in Bulk Admin (Hard coded)
		$this->db->join('exam_invoice','exam_invoice.pay_txn_id = bulk_payment_transaction.id','LEFT');
		$this->db->join('bulk_accerdited_master','bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code','LEFT');
		$this->db->join('exam_master','exam_master.exam_code = bulk_payment_transaction.exam_code','LEFT');
		$this->db->where('gateway = "1"');
		$this->db->where('exam_invoice.app_type = "Z"');
		//do not include records which are added before date 08-01-2017 /*added this condition on 17-01-2017*/
		//$this->db->where('date(bulk_payment_transaction.date) > ','2017-01-08');
		
		/* Display only current logged in institute transactions */
		//$instdata = $this->session->userdata('dra_institute');
		//$instcode = $instdata['institute_code'];
		$instcode = trim($this->session->userdata('institute_id'),"_Admin");
		if( !empty( $instcode ) ) {
			//$where .= ' AND bulk_payment_transaction.inst_code = '.$instcode;
			$this->db->where('bulk_payment_transaction.inst_code = "'.$instcode.'"');
		}
		$this->db->where("bulk_payment_transaction.exam_code !=",'997');
		$res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', $sortkey, $sortval, $per_page, $start);
	
		$data['query'] = $this->db->last_query();
		
		if($res)
		{
			$result = $res->result_array();

			//start : code added by vishal for 994 exam
			foreach($result as $key=> $row2)
			{
				if ($row2['state']!='') {
					$result[$key]['transaction_no']=$row2['transaction_no']."<br>".$row2['state'];
				}
			}

			//end : code added by vishal for 994 exam
			
			$data['result'] = $result;
			//print_r($data);exit;
			foreach($result as $row)
			{
				// $row['status'] = '1';
				// check if approved by Bulk Admin -
				if($row['status'] == 3)	// status = 3 for Applied
				{
					
					// $show_update_urt_btn=true;
					if (in_array($row['exam_code'], $this->elearning_course_code) && $this->session->userdata('is_admin')=='no') {
						$show_update_urt_btn=true;
					}

						if($row['exam_code'] != 1015 && $show_update_urt_btn) {
						$action = '<a href="'.base_url().'bulk/BulkTransaction/view_inst_pr_invoice/'.$row['id'].'" target="_blank">Proforma Invoice</a></span> |  <br> <span data-id="'.$row['id'].'"><a href="javascript:void(0)" onclick="confirmVerify('.$row['id'].');">Update UTR </a></span>  |<br> <span data-id="'.$row['id'].'"><a href="'.base_url().'bulk/BulkTransaction/performa_invoice_details/'.base64_encode($row['id']).'" target="_blank">View more </a></span>';
						}else{
						$action = '<a href="'.base_url().'bulk/BulkTransaction/view_inst_pr_invoice/'.$row['id'].'" target="_blank">Proforma Invoice</a></span> |  <br> <span data-id="'.$row['id'].'"><a href="'.base_url().'bulk/BulkTransaction/performa_invoice_details/'.base64_encode($row['id']).'" target="_blank">View more </a></span>';
						}
						// $action = '<span class="label label-warning" style="padding:10px;" >Pay Now</span>';
						$action = '<a href="'.base_url().'bulk/BulkTransaction_test/getPayment/'.base64_encode($row['id']).'" target="_blank" class="btn btn-warning" target="_blank" style="margin:2px;">Pay Now</a>';				
				}
				else if($row['status'] == 1) // status = 1 for Success
				{
					$action = '<span class="label label-success">Approved</span>';	
				}
				else if($row['status'] == 0) // status = 0 for Rejected
				{
					$action = '<span class="label label-danger">Rejected</span>';	
				}
				if($row['status'] == 3)	// status = 3 for Applied
				{
					// $action = '<span class="label label-warning">Pay Now</span>';	
				}
				$data['action'][] = $action;
				if($row['exam_code']!='')
				{
					$get_exam_code=$this->master_model->getRecords('multiple_exam_period', array(
					'exam_code' => $row['exam_code']
				 ) , 'actul_exam_code');
					 if(count($get_exam_code)>0)
					 {
						$data['exam_code']=$get_exam_code[0]['actul_exam_code'];
					 }
				}
				//Start pooja 2025-01-16
				if($row['status'] == 3)	// status = 3 for Applied
				{ 
					$pay_action = '<span class="label label-warning">Pay Now</span>';	 }
				else if($row['status'] == 1) // status = 1 for Success
				{
					$pay_action = '<span class="label label-success">Paid</span>';	
				}
				// $data['pay_action'][] = $pay_action;
				//End pooja 2025-01-16
			}
			
			
			if(count($result))
			{
				$data['success'] = 'Success';
				$data['pay_action'] = 'test123';
				
				$url = base_url()."bulk/BulkTransaction/getNeftTransactions/";
				//$total_row = count($result);
				$config = pagination_init($url,$total_row, $per_page, 2);
				$this->pagination->initialize($config);
				
				$str_links = $this->pagination->create_links();
				$data["links"] = $str_links;
				if(($start+$per_page)>$total_row)
					$end_of_total = $total_row;
				else
					$end_of_total = $start+$per_page;
				
				$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';
				$data['index'] = $start+1;
			}
			else
				$data['success'] = '';
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}

	/*Start: Function Created by pooja mane for Bulk Payment page 2025-01-16*/
	public function getPayment($payTransId = 0)
	{
		$id = base64_decode($payTransId);

		$resultarr = array();
		$regNosToPay = '';
		// $select = 'dra_members.regid';

		$select = 'bulk_payment_transaction.id,UTR_no AS transaction_no,exam_master.description AS Bulk,pay_count AS member_count,amount,DATE_FORMAT(date,"%d-%m-%Y") As date,exam_period,bulk_payment_transaction.exam_code,status,bulk_accerdited_master.institute_name AS inst_name,gstin_no';
		$this->db->join('bulk_accerdited_master','bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code','LEFT');
		$this->db->join('exam_master','exam_master.exam_code = bulk_payment_transaction.exam_code','LEFT');
		$this->db->where('gateway = "1" AND bulk_payment_transaction.id = "'.$id.'"');
		$res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');
		$result2 = $res->result_array();
		// echo $this->db->last_query();die;
		if (count($result2) > 0) {
			foreach ($result2 as $row2) {
				$gstin_no = $row2['gstin_no'];
			}

			// if (isset($reg_no_list) && count($reg_no_list) > 0) {
			// 	$regNosToPay = implode('|', $reg_no_list);
			// 	$regNosToPay = base64_encode($regNosToPay);
			// }
		}

		$arr_payment_transaction_data = $this->master_model->getRecords('bulk_payment_transaction', array('id' => $id));
		// echo $this->db->last_query();die;
		if (count($arr_payment_transaction_data) > 0) {
			$tot_fee     = $arr_payment_transaction_data[0]['amount'];
			$exam_code   = $arr_payment_transaction_data[0]['exam_code'];
			$exam_period = $arr_payment_transaction_data[0]['exam_period'];

			$data["gstin_no"]       = $gstin_no;
			$data["regNosToPay"]    = $regNosToPay;
			$data["tot_fee"]        = base64_encode($tot_fee);
			$data["exam_code"]      = base64_encode($exam_code);
			$data["exam_period"]    = base64_encode($exam_period);
			$data["payTransId"]     = $payTransId;
			$data["middle_content"] = 'make_online_payment';

			// echo "<pre>"; print_r(base64_decode($regNosToPay)); exit;
			// /* send active exams for display in sidebar */
			// log_dra_user($log_title = "Send data to make_online_payment View", $log_message = serialize($data));
			/* Log activity */
			// $log_title = "Send data to make_online_payment View";
			// $log_message = serialize($data);
			// $inst_id = $this->session->userdata['institute_id'];
			// bulk_storedUserActivity($log_title, $log_message, $inst_id, '', '');

			// $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
			// $res = $this->master_model->getRecords("dra_exam_master a");
			// $data['active_exams'] = $res;
			$this->load->view('bulk/transaction/common_view', $data);
		} else {
			$this->session->set_flashdata('error', 'Payment details not found!!('.$payTransId.')');//die;
			redirect(base_url() . 'bulk/BulkTransaction_test/neft_transactions');
		}
	}
	/*End : Function Created by pooja mane for Bulk Payment page 2025-01-16*/

	/*Start : Function Created by pooja mane for Bulk make payment online 2025-01-16*/
	public function make_payment() 
	{	
		$state_code=$GsTIN_no=$centre_id='';
		$state_code=$GsTIN_no='';
		echo '<pre>';print_r($_POST);die;
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			$isTDS  = isset($_POST['TDS']) && $_POST['TDS'] == 'Yes' ? $_POST['TDS'] : 'No';

			$this->form_validation->set_rules('center_id','Center GsTIN no','required');
			if ($isTDS == 'Yes') {
				$this->form_validation->set_rules('tds_type','IT TDS Percentage','required');
			}
			
			$payTransId  = base64_decode($_POST['pay_id']);

      		$paymentTransInfo = $this->master_model->getRecords('bulk_payment_transaction',array('id' => $payTransId));

			// echo $this->db->last_query();die;
	      	if (count($paymentTransInfo) <= 0) {
	      	$this->session->set_flashdata('error', 'Payment/Proformo details not found.');
			    redirect(base_url() . 'bulk/BulkTransaction_test/neft_transactions');
	      	} 

	      	if (isset($paymentTransInfo[0]['status']) && $paymentTransInfo[0]['status'] == 1 ) {
	      		$this->session->set_flashdata('error', 'Payment has already been completed for this transaction.');
			    redirect(base_url() . 'bulk/BulkTransaction_test/neft_transactions');
	      	}  

			if($this->form_validation->run()==TRUE) 
			{	
				$beforeTDSamount = base64_decode($_POST['tot_fee']);
				$afterTDSamount  = base64_decode($_POST['final_amount']);

				$gst_amount      = base64_decode($_POST['gst_amount']);
				
				$tds_type        = $_POST['tds_type'];

				$tds_amount = 0;
				$tds_type   = 0;
				
				$amount      = $beforeTDSamount;

				if ($isTDS == 'Yes') {
					$tds_type    = $_POST['tds_type'];
					$tds_amount  = base64_decode($_POST['tds_amount']);
					$amount      = $afterTDSamount;
				}

				$update_data = array( 'isTDS'               => $isTDS,
							          'tds_type'            => $tds_type,
							          'tds_amount'          => $tds_amount,
							          'amount'              => $amount
							        );
				// $payTransId ='0';
				$update_query = $this->master_model->updateRecord('bulk_payment_transaction', $update_data, array('id' => $payTransId,'status' => 3));
				$this->db->limit(1);
				$instdata = $this->master_model->getRecords('bulk_accerdited_master',array('institute_code'=>$this->session->userdata('institute_id')));
				// print_r($instdata);die;
				if(isset($update_query))
				{
					/* Log activity */
					// bulk_userActivity_log($log_title, $log_desc = "",$inst_id = "", $rId = NULL, $regNo = NULL)
					$log_title = 'IS_TDS, TDS TYPE & TDS_AMT has been updated for ID :'.$payTransId;
					$log_data = array('updated_data' => $update_query);
					$log_desc = serialize($log_data);
					$inst_id = $this->session->userdata('institute_id');
					bulk_userActivity_log($log_title, $log_desc, $inst_id, '', '');
				}
				
				$dra_mem_list    = base64_decode($_POST['regNosToPay']);
				$dra_mem_array   = explode("|",$dra_mem_list);
				// echo'<pre>';print_r($instdata);die;
				// echo $this->db->last_query();die;
				$examcode        = base64_decode($_POST['exam_code']);
				$examperiod      = base64_decode($_POST['exam_period']);
				$inst_code       = $this->session->userdata('institute_id');	
			
				$exam_month_year = $this->master_model->getRecords('misc_master',array('exam_code'=>$examcode,'exam_period'=>$examperiod));
				
				if( count($exam_month_year) > 0 ) {				
					$ref4 = $examcode.$exam_month_year[0]['exam_month'];
				}
				else
				{
					$ref4 = "";
				}

				$center_data=$this->input->post('center_id');
				if($center_data == "-")
				{
					$GsTIN_no='-';
					$state_code=$instdata['ste_code'];
				}
				elseif(empty($center_data))
				{
					$GsTIN_no='-';
					$state_code=$instdata['ste_code'];
				}
				elseif($center_data == "Institute")
				{
					$GsTIN_no=$instdata['gstin_no'];
					$state_code=$instdata['ste_code'];
				}
				else
				{
					$c_data=explode('_', $center_data);
					if($c_data[0] != '')
					{
						$GsTIN_no=$c_data[0];
					}
					else
					{
						$GsTIN_no=$instdata['gstin_no'];
					}
					$centre_id=$c_data[1];
					// if($c_data[1] != '')
					// {
					// 	$this->db->select('agency_center.*,city_master.city_name');
					// 	$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	
					// 	$this->db->join('city_master','city_master.id=agency_center.city','left');
					// 	$this->db->group_by('agency_center.center_id');		
					// 	$agency_center = $this->master_model->getRecords('agency_center',array('center_id'=>$c_data[1]));
						
					// 	$state_code=$agency_center[0]['state'];
					// }
					// else
					// {
					// 	$state_code=$instdata['ste_code'];
					// }
				}
			  
			    $regnumber_arr = array();
				$total_member_cnt = count($dra_mem_array);
				$free_member_cnt = 0;
				foreach($dra_mem_array as $memexamid)
				{
					$this->db->join('dra_member_exam','dra_member_exam.regid = dra_members.regid');
					$dra_member_exam = $this->master_model->getRecords('dra_members',array('dra_member_exam.id'=>$memexamid),'regnumber, fee_paid_flag'); //'dra_member_exam.exam_fee !='=>0,
					//echo $this->db->last_query();
					
					if($dra_member_exam[0]['fee_paid_flag'] != 'F') //CONDITION ADDED BY SAGAR ON 07-10-2020
					{ 
						$regnumber_arr[] = $dra_member_exam[0]['regnumber'];
					}
					else { $free_member_cnt++; }
				}
			
				//print_r($regnumber_arr); die;
				$app_category = array();
					
				$unit_R  = $base_total_R  = 0;
				$unit_S1 = $base_total_S1 = 0;
				$unit_B1 = $base_total_B1 = 0;
				
				if(count($regnumber_arr) > 0)
				{
					foreach($regnumber_arr as $regnumber_arr)
					{						
						if($regnumber_arr != '')
						{
							$dra_eligible_master = $this->master_model->getRecords('dra_eligible_master',array('member_no'=>$regnumber_arr,'exam_code'=>$examcode,'eligible_period'=>$examperiod),'app_category');
							
							if(count($dra_eligible_master) > 0)
							{								
								if($dra_eligible_master[0]['app_category'] == 'R' || $dra_eligible_master[0]['app_category'] == 'B1')
								{
									$unit_R=$unit_R+1;
									
								}
								elseif($dra_eligible_master[0]['app_category'] == 'S1')
								{
									$unit_S1=$unit_S1+1	;
								}
								else
								{
									$unit_R=$unit_R+1;
								}
							}
							else
							{								
								$unit_R=$unit_R+1;
							}
						}
						else
						{
							$unit_R=$unit_R+1;
						}
						
						if(isset($dra_eligible_master[0]['app_category']))
						{
							$app_category[] = $dra_eligible_master[0]['app_category'];
						}
						else
						{
							$app_category[] = 'B1';
						}
					}
				}
			  //end swati=============
			  
			  // Create transaction
	          //START : ADDED BY PRIYANKA W AND SAGAR ON 2023-08-23 TO PREVENT THE WRONG EXAM PERIOD ISSUE
	          // $ins_examperiod  = $examperiod;
	          // $chk_exam_period = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $examcode),'exam_period');
	          // if(count($chk_exam_period) > 0)
	          // {
	          //   if($chk_exam_period[0]['exam_period'] != $examperiod)
	          //   {
	          //     $ins_examperiod = $chk_exam_period[0]['exam_period'];
	          //   }
	          // }
	          //END : ADDED BY PRIYANKA W AND SAGAR ON 2023-08-23 TO PREVENT THE WRONG EXAM PERIOD ISSUE
				
				$pt_id           = $paymentTransInfo[0]['id'];					
				$MerchantOrderNo = $paymentTransInfo[0]['receipt_no']; //dra_sbi_order_id($pt_id);
				
				$custom_field = $MerchantOrderNo."^iibfexam^iibfbulk^".$ref4;
				$custom_field_billdesk = $pt_id . "-IIBF_BULK-" . $MerchantOrderNo;
				
				/* Start Dyanamic Fees allocation - Bhushan */
				// get logged in institute details from session
				$instdata = $this->session->userdata('dra_institute');
				$instStateCode = $instdata['ste_code'];
				
				//get state name, state_no from state master by state code
				$draInstState = $this->master_model->getRecords('state_master',array('state_code'=>$instStateCode,'state_delete'=>'0'));
				
				$totol_amt = $total_igst_amt = $total_cgst_amt = $total_sgst_amt = $cgst_rate = $cgst_amt = $sgst_rate = $sgst_amt = $igst_rate = $igst_amt = $cs_total = $igst_total = $cess = 0;

				$fee_amt = $repeater_fee_amount = $fresh_fee_amount = 0;
				$pg_name = 'billdesk';
				
				// insert the dra member id in 'dra_member_payment_transaction' table
				foreach ($dra_mem_array as $dra_mem_id)
				{
					/* Get reg id   */
					$this->db->where('id', $dra_mem_id);
					$getRegId = $this->master_model->getRecords('dra_member_exam', '', 'regid, fee_paid_flag');
					$RegId = $getRegId[0]['regid'];
					$fee_paid_flag = $getRegId[0]['fee_paid_flag'];			

					/* Get Member Number  */
					$registrationtype = "NM";
					$this->db->where('regid', $RegId);
					$getMemberNo = $this->master_model->getRecords('dra_members', '', 'regnumber,registrationtype');
					$member_no = $getMemberNo[0]['regnumber']; 
					$registrationtype = $getMemberNo[0]['registrationtype']; // NM,O
					
					/* Get Group Code */
					$group_code = "B1"; 
					if($member_no != 0 && $member_no != "")
					{
						$this->db->where('member_no', $member_no);
						$getGrpCd = $this->master_model->getRecords('dra_eligible_master', '', 'app_category');
						if(count($getGrpCd)>0)
						{
							$group_code = $getGrpCd[0]['app_category']; 
							if($group_code != "")
							{
								if($group_code == "R")
								{
									$group_code = "B1";
								}
							}
              else { $group_code = "B1"; }
						}
						else
						{
							$group_code = "B1";
						}
					}

					if($fee_paid_flag != 'F')
					{
						//get fees details from fee master
						$this->db->select('dra_fee_master.*');
						$this->db->where('dra_fee_master.member_category',$registrationtype);
						$this->db->where('dra_fee_master.group_code',$group_code);
						$this->db->where('dra_fee_master.exempt','NE'); 
						$this->db->where('dra_fee_master.exam_code',$examcode);
						$this->db->where('dra_fee_master.exam_period',$examperiod);
						$dra_fee_master=$this->master_model->getRecords('dra_fee_master',array('dra_fee_master.fee_delete'=>'0'));
						/* echo $this->db->last_query(); exit; */
						$totol_amt = $totol_amt + $dra_fee_master[0]['fee_amount'];
						
						if($state_code == 'MAH')
						{
							$total_cgst_amt = $total_cgst_amt + $dra_fee_master[0]['cgst_amt'];
							$total_sgst_amt = $total_sgst_amt + $dra_fee_master[0]['sgst_amt'];
						}
						else
						{
							$total_igst_amt = $total_igst_amt + $dra_fee_master[0]['igst_amt'];
						}
						// Added By Anil on 19 Dec 2024 
						if($dra_fee_master[0]['group_code'] == 'S1') { 
							$repeater_fee_amount = $dra_fee_master[0]['fee_amount'];				
						} else if ($dra_fee_master[0]['group_code'] == 'B1') {
							$fresh_fee_amount = $dra_fee_master[0]['fee_amount'];
						} 
						// Added By Anil on 19 Dec 2024
					}
				}
			
				$fee_amt = $totol_amt; // Total amount without any GST
				$tax_type = '';
				
				if($state_code == 'MAH')
				{
					//set a rate (e.g 9%,9% or 18%)
					$cgst_rate = $this->config->item('cgst_rate');
					$sgst_rate = $this->config->item('sgst_rate');
					//set an amount as per rate
					$cgst_amt = $total_cgst_amt;
					$sgst_amt = $total_sgst_amt;
					$cs_total = $amount;
					$tax_type = 'Intra';
				}
				else
				{
					//set a rate (e.g 9%,9% or 18%)
					$igst_rate = $this->config->item('igst_rate');
					$igst_amt = $total_igst_amt;
					$igst_total = $amount;
					$tax_type = 'Inter';
				}	
				
				$no_of_members_payment = count($dra_mem_array);
				
				if($fee_amt > 0) //Added By Anil on 19 Dec 2024
				{

					if($fresh_fee_amount <= 0){
						$fresh_fee_amount = 1200;
					}
					if($repeater_fee_amount <= 0){
						$repeater_fee_amount = 1200;
					} 
				
					$invoice_insert_array = array(
					'pay_txn_id' => $pt_id,
					'receipt_no' => $MerchantOrderNo,
					'exam_code' => $examcode,
					'exam_period' =>  $ins_examperiod, // $examperiod,
					'state_of_center' => $state_code,
					'institute_code' => $instdata['institute_code'],
					'institute_name' => $instdata['institute_name'],
					'app_type' => 'I', // I for DRA Exam Invoice
					'tax_type' => $tax_type, 
					'service_code' => $this->config->item('exam_service_code'),
					//'gstin_no' => $instdata['gstin_no'],
					'gstin_no' => $GsTIN_no,
					'qty' => count($dra_mem_array) - $free_member_cnt, //$no_of_members_payment,
					'state_code' => $draInstState[0]['state_no'],
					'state_name' => $draInstState[0]['state_name'],
					'fresh_fee' => $fresh_fee_amount, //1200, // Added By Anil on 19 Dec 2024
					'rep_fee' => $repeater_fee_amount, //1200, // Added By Anil on 19 Dec 2024
					'fresh_count' => $unit_R,
					'rep_count' => $unit_S1,
					'fee_amt' => $fee_amt,
					'cgst_rate' => $cgst_rate,
					'cgst_amt' => $cgst_amt,
					'sgst_rate' => $sgst_rate,
					'sgst_amt' => $sgst_amt,
					'igst_rate' => $igst_rate,
					'igst_amt' => $igst_amt,
					'cs_total' => $cs_total,
					'igst_total' => $igst_total,
					'tds_amt' => $tds_amount,
					'cess' => $cess,
					'exempt' => $draInstState[0]['exempt'],
					'created_on' => date('Y-m-d H:i:s')
					);
					
					$dra_exam_invoice_data=$this->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pt_id,'exam_code'=>$examcode,'exam_period'=>$examperiod,'app_type'=>'I'));

					$inv_id = $this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
					
					
					log_dra_user($module='ExamInvoice', '', $activity = 'Add',$log_title = "Add DRA Exam Invoice Successful", $log_message = serialize($invoice_insert_array), $flag='Success');


					if ($pg_name == 'billdesk')
		      {
		        $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $inv_id, $inv_id, '', 'iibfdra/Version_2/TrainingBatches/handle_billdesk_response', '', '', '', $custom_field_billdesk);

		        if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE')
		        {
		        	$userarr = array('pt_id' => $pt_id, 'receipt_no' => $MerchantOrderNo);
        			$this->session->set_userdata('SESSION_MEMBER_DATA', $userarr);

		          $data['bdorderid']      = $billdesk_res['bdorderid'];
		          $data['token']          = $billdesk_res['token'];
		          $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
		          $data['returnUrl']      = $billdesk_res['returnUrl'];
		          $data['bulk_payment_flag'] = 'IIBF_BULK_DRA';////
		          $this->load->view('pg_billdesk/pg_billdesk_form', $data);
		        }
		        else
		        {
		          echo '1'; exit;
		          $this->session->set_flashdata('error', 'Transaction failed...!');
		          redirect(base_url() . 'iibfdra/Version_2/TrainingBatches/proforma_invoice_payment');
		        }
		      }
				}
			}
			else
			{ 
				$this->session->set_flashdata('error', validation_errors());
				// $data['validation_errors'] = validation_errors();
				redirect(base_url() . 'iibfdra/Version_2/TrainingBatches/proforma_invoice_payment');
			}	
		}	
		else
		{ 
			$this->session->set_flashdata('error', 'Something went wrong...!');
			redirect(base_url() . 'iibfdra/Version_2/TrainingBatches/proforma_invoice_payment');
		}	
	}
	/*Start : Function Created by pooja mane for Bulk make payment online 2025-01-16*/

	public function performa_invoice_details(){
		$data = array();
		$id = base64_decode($this->uri->segment(4));
		
		$this->db->where('ptid',$id);
		$this->db->join('bulk_member_payment_transaction', 'bulk_member_payment_transaction.memexamid = admit_card_details.mem_exam_id');
		$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,mam_nam_1,exm_cd,exm_prd,reapeter_flag');
		
		// echo $this->db->last_query();die;
		
		$data = array('result'=>$sql);

		if(isset($_POST['submit_excel']) && $_POST['submit_excel'] != ""){
 
			// Excel file name for download 
			$fileName = "performa_invoice_details_" . date('Y-m-d') . ".xls"; 
			 
			// Column names 
			$fields = array('Sr. No.', 'Member Name', 'Member Number', 'Employee ID', 'Exam Code', 'Exam Period', 'Reapeter'); 
			 
			// Display column names as first row 
			$excelData = implode("\t", array_values($fields)) . "\n"; 
			 
			// Fetch records from database 
			 
			if($sql){ 
			    // Output each row of the data 
			    $i=1;
			    foreach($sql as $record){ 
			    	$member_reg_data = $this->master_model->getRecords('member_registration',array('regnumber'=>$record['mem_mem_no']),'bank_emp_id');
			        $reapeter_flag = ($record['reapeter_flag'] == 'Y')?'Yes':'No'; 
			        $lineData = array($i, $record['mam_nam_1'], $record['mem_mem_no'], $member_reg_data[0]['bank_emp_id'], $record['exm_cd'], $record['exm_prd'], $reapeter_flag); 
			        array_walk($lineData, 'filterData'); 
			        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
			        $i++;
			    } 
			}else{ 
			    $excelData .= 'No records found...'. "\n"; 
			} 
			 
			// Headers for download 
			header("Content-Type: application/vnd.ms-excel"); 
			header("Content-Disposition: attachment; filename=\"$fileName\""); 
			 
			// Render excel data 
			echo $excelData; 
			 
			exit;
			  
		}
		
		$this->load->view('bulk/admin/transaction/performa_details',$data);
	}

	public function filterData(&$str){ 
	    $str = preg_replace("/\t/", "\\t", $str); 
	    $str = preg_replace("/\r?\n/", "\\n", $str); 
	    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
	} 
	
	// function to get NEFT transaction details by id -
	public function getNeftTransactionDetails()
	{
		$data['result'] = array();
		$data['success'] = '';
		
		$id = $this->input->post('id');
		
		// "Bulk" is Application in NEFT Transactions table in Bulk Admin (Hard coded)
		$select = 'bulk_payment_transaction.id,UTR_no AS transaction_no,exam_master.description AS Bulk,pay_count AS member_count,amount,DATE_FORMAT(date,"%d-%m-%Y") As date,exam_period,bulk_payment_transaction.exam_code,status,bulk_accerdited_master.institute_name AS inst_name'; 
		$this->db->join('bulk_accerdited_master','bulk_accerdited_master.institute_code = bulk_payment_transaction.inst_code','LEFT');
		$this->db->join('exam_master','exam_master.exam_code = bulk_payment_transaction.exam_code','LEFT');
		$this->db->where('gateway = "1" AND bulk_payment_transaction.id = "'.$id.'"');
		$res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');
		
		if($res)
		{
			$result = $res->result_array();
			
			if(count($result))
				$data['success'] = 'success';
			else
				$data['success'] = '';
			
			$data['result'] = $result;
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
	
	// function to update NEFT transaction -
	public function updateNeftTransaction()
	{
		//print_r($_POST); die();
		
		$data['result'] = array();
		$data['success'] = '';
		
		$id = $this->input->post('id'); // post parameter
		$utr_no = $this->input->post('utr_no'); // post parameter
		$payment_date = date("Y-m-d H:i:s", strtotime($this->input->post('payment_date'))); // post parameter
		
		$this->db->where('UTR_no',$utr_no);
		$validate_utr_no = $this->Master_model->getRecords('bulk_payment_transaction','','id');
		
		if(count($validate_utr_no) > 0){
			$data['success'] = 'error1';
			$log_title = "Entered UTR No alrady present";
			$json_res = json_encode($data);
		}else{
		
		// get existing NEFT UTR No. and NEFT date
		$select = 'UTR_no,date,exam_code'; 
		$this->db->where('bulk_payment_transaction.id = "'.$id.'"');
		$res = $this->UserModel->getRecords("bulk_payment_transaction", $select, '', '', '', '', '', '');
		//echo $this->db->last_query(); die();
		if($res)
		{
			$result_select = $res->result_array();
			foreach($result_select as $row_select)
			{
				$old_UTR_no = $row_select['UTR_no'];
				$old_date = $row_select['date'];
			}
			$old_data = array('UTR_no' => $old_UTR_no, 'date' => $old_date);
			
			$updated_data = array('UTR_no' => $utr_no, 'date' => $payment_date);
			
			// update new NEFT UTR No. and NEFT date
			$update_payment_transaction_reject = array('UTR_no' => $utr_no, 'date' => $payment_date);
			//start : code added by vishal for 994 exam
			if ($row_select['exam_code']=='994' || $row_select['exam_code']=='1056') {
				$update_payment_transaction_reject = array('UTR_no' => $utr_no, 'date' => $payment_date,'state'=>$this->input->post('state'));
			}
			//end : code added by vishal for 994 exam

			$result_update = $this->master_model->updateRecord('bulk_payment_transaction',$update_payment_transaction_reject,array("id"=>$id));
			if(count($result_update))
			{
				$data['success'] = 'success';
				$log_title = "NEFT UTR No. Updated Successfully. (Receipt No : ".$id.",UTR No :".$utr_no.")";
			}
			else
			{
				$data['success'] = 'error';
				$log_title = "NEFT UTR No. Update Error. (Receipt No : ".$id.",UTR No :".$utr_no.")";
			}
		}
		else
		{
			$data['success'] = 'error';
			$log_title = "NEFT not found. (Receipt No : ".$id.",UTR No :".$utr_no.")";	
		}
		
		/* Log activity */
		$log_data = array('old_data' => $old_data, 'updated_data' => $updated_data);
		$log_message = serialize($log_data);
		//$rId = $this->session->userdata('regid');
		//$regNo = $this->session->userdata('mregnumber_applyexam');
		$inst_id = $this->session->userdata['institute_id'];
		bulk_storedUserActivity($log_title, $log_message, $inst_id, '', '');
		
		$post_data = array('UTR_no' => $utr_no, 'date' => $this->input->post('payment_date'));
		$data['result'] = $post_data;
		
		$json_res = json_encode($data);
		
		}
		echo $json_res;
	}
	
	
	function pb_amtinword($amt){
	   $number = $amt;
	   $no = round($number);
	   $point = round($number - $no, 2) * 100;
	   $hundred = null;
	   $digits_1 = strlen($no);
	   $i = 0;
	   $str = array();
	   $words = array('0' => '', '1' => 'One', '2' => 'Two',
		'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
		'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
		'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
		'13' => 'Thirteen', '14' => 'Fourteen',
		'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
		'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
		'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
		'60' => 'Sixty', '70' => 'Seventy',
		'80' => 'Eighty', '90' => 'Ninety');
	   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
	   while ($i < $digits_1) {
		 $divider = ($i == 2) ? 10 : 100;
		 $number = floor($no % $divider);
		 $no = floor($no / $divider);
		 $i += ($divider == 10) ? 1 : 2;
		 if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? 'and ' : null;
			$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
		 } else $str[] = null;
	  }
	  $str = array_reverse($str);
	  $result = implode('', $str);
	  $points = ($point) ?
		"." . $words[$point / 10] . " " . 
			  $words[$point = $point % 10] : '';
			
	 return $result;
	}
	
	public function view_inst_invoice($txn_id){
		$data = array();
		$pay_txn_id = base64_decode($txn_id);
		$invoice_info = $this->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id,'app_type'=>'Z'));
		
		
		$institute_info = $this->master_model->getRecords('bulk_accerdited_master',array('institute_code'=>$invoice_info[0]['institute_code']),'institute_name,address1,address2,address3,address4,address5,address6,ste_code,gstin_no');
	
		$state_info = $this->master_model->getRecords('state_master',array('state_code'=>$institute_info[0]['ste_code']),'state_name,state_no');
		
		$net_amt = $invoice_info[0]['fee_amt'] - $invoice_info[0]['disc_amt'];
		
		if($institute_info[0]['ste_code'] == 'MAH'){
			$wordamt = $this->pb_amtinword(intval($invoice_info[0]['cs_total']));
		}elseif($institute_info[0]['ste_code'] != 'MAH'){
			$wordamt = $this->pb_amtinword(intval($invoice_info[0]['igst_total']));
		}
		
		$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
		$address = $institute_info[0]['address1']." ".$institute_info[0]['address2']." ".$institute_info[0]['address3']." ".$institute_info[0]['address4']." ".$institute_info[0]['address5']." ".$institute_info[0]['address6'];
		
		$data = array('wmt'=>$wordamt,'invoice_no'=>$invoice_info[0]['invoice_no'],'date_of_invoice'=>$date_of_invoice,'transaction_no'=>$invoice_info[0]['transaction_no'],'recepient_name'=>$institute_info[0]['institute_name'],'address'=>$address,'institute_state'=>$state_info[0]['state_name'],'institute_state_code'=>$state_info[0]['state_no'],'institute_gstn'=>$institute_info[0]['gstin_no'],'fee_amount'=>$invoice_info[0]['fee_amt'],'discount_amt'=>$invoice_info[0]['disc_amt'],'net_amt'=>number_format($net_amt, 2, '.', ''),'ste_code'=>$institute_info[0]['ste_code'],'cgst_rate'=>$invoice_info[0]['cgst_rate'],'cgst_amt'=>$invoice_info[0]['cgst_amt'],'sgst_rate'=>$invoice_info[0]['sgst_rate'],'sgst_amt'=>$invoice_info[0]['sgst_amt'],'cs_total'=>$invoice_info[0]['cs_total'],'igst_total'=>$invoice_info[0]['igst_total']); 
				
		$this->load->view('bulk/transaction/print_inst_receipt',$data);
	}
		
		
		
		public function view_inst_pr_invoice(){
			$txn_id= $this->uri->segment(4, 0);
			
			
		$data = array();
		$pay_txn_id = $txn_id;
		$invoice_info = $this->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pay_txn_id,'app_type'=>'Z'));
		

		$institute_info = $this->master_model->getRecords('bulk_accerdited_master',array('institute_code'=>$invoice_info[0]['institute_code']),'institute_name,address1,address2,address3,address4,address5,address6,ste_code,gstin_no');
	
		$state_info = $this->master_model->getRecords('state_master',array('state_code'=>$institute_info[0]['ste_code']),'state_name,state_no');
		
		//$net_amt = $invoice_info[0]['fee_amt'] - $invoice_info[0]['disc_amt'];
		
		$net_amt = $invoice_info[0]['fee_amt'];
		
		if($institute_info[0]['ste_code'] == 'MAH'){
			$wordamt = $this->pb_amtinword(intval($invoice_info[0]['cs_total']));
		}elseif($institute_info[0]['ste_code'] != 'MAH'){
			$wordamt = $this->pb_amtinword(intval($invoice_info[0]['igst_total']));
		}
		
		$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['created_on']));

		$address = $institute_info[0]['address1']." ".$institute_info[0]['address2']." ".$institute_info[0]['address3']." ".$institute_info[0]['address4']." ".$institute_info[0]['address5']." ".$institute_info[0]['address6'];
		
		$this->db->where('ptid',$invoice_info[0]['pay_txn_id']);
		$query1 = $this->master_model->getRecords('bulk_member_payment_transaction','','memexamid');
		
		$this->db->where('exam_code',1015);
		$this->db->where('id',$query1[0]['memexamid']);
		$query2 = $this->master_model->getRecords('member_exam','','exam_center_code');
		
		$this->db->where('exam_name',1015);
		$this->db->where('center_code',$query2[0]['exam_center_code']);
		$center_name = $this->master_model->getRecords('center_master','','center_name');
		
		$data = array('wmt'=>$wordamt,'invoice_no'=>$invoice_info[0]['invoice_no'],'date_of_invoice'=>$date_of_invoice,'transaction_no'=>$invoice_info[0]['transaction_no'],'recepient_name'=>$institute_info[0]['institute_name'],'address'=>$address,'institute_state'=>$state_info[0]['state_name'],'institute_state_code'=>$state_info[0]['state_no'],'institute_gstn'=>$institute_info[0]['gstin_no'],'fee_amount'=>$invoice_info[0]['fee_amt'],'discount_amt'=>$invoice_info[0]['disc_amt'],'net_amt'=>number_format($net_amt, 2, '.', ''),'ste_code'=>$institute_info[0]['ste_code'],'cgst_rate'=>$invoice_info[0]['cgst_rate'],'cgst_amt'=>$invoice_info[0]['cgst_amt'],'sgst_rate'=>$invoice_info[0]['sgst_rate'],'sgst_amt'=>$invoice_info[0]['sgst_amt'],'cs_total'=>$invoice_info[0]['cs_total'],'igst_total'=>$invoice_info[0]['igst_total'],'invoice_number'=>$txn_id,'igst_rate'=>$invoice_info[0]['igst_rate'],'igst_amt'=>$invoice_info[0]['igst_amt'],'center_name'=>$center_name[0]['center_name'],'exam_code'=>$invoice_info[0]['exam_code']); 
		//print_r($data);		
		$this->load->view('bulk/transaction/print_inst_receipt_proforma',$data);
	}
	
	/* Function to download E-invoice added on 13 Jul 2021*/
	public function getInvoice()
	{
	
		$inv_no = base64_decode($this->uri->segment(4));
		//echo $inv_no;
		## Test invoice no
		//$inv_no = 'EDN_20-21_000310';
		//$inv_no = 'EX_21-22_078142';
		## Live Url
		$service_url = 'http://10.10.233.76:8083/irnapi/getDataByDocNo/'.$inv_no;
		$curl = curl_init($service_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$curl_response = curl_exec($curl);
		curl_close($curl);
    	$json_objekat=json_decode($curl_response);
		//print_r($json_objekat); exit;
		$file_cont=base64_decode($json_objekat->signedPdf);
		
		if(strlen($file_cont) > 0)
		{
			header('Set-Cookie: fileLoading=true'); 
			header('Content-Type: application/pdf');
			header('Content-Length:'.strlen($file_cont));
			header('Content-disposition: attachment; filename=invoice.pdf');
			header('Content-Transfer-Encoding: Binary');
			echo $file_cont;
		}
		else
		{
			echo 'Invoice Not available/generated';
		}

		//$this->session->set_flashdata('success','E-invoice downloaded successfully.');
		//$data['success'] = 'E-invoice downloaded successfully.';
		//redirect(base_url().'bulk/BulkTransaction/transactions');

	}
}
?>