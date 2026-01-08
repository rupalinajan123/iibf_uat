<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caiib_121 extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	custom_examinvoice_send_mail * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct()
	{
		 parent::__construct(); 
		 //load mPDF library
		 //$this->load->library('m_pdf');
		
		 $this->load->library('email');
		 $this->load->model('Emailsending');
		 //$this->load->model('Emailsending_123');
		 //$this->load->helper('bulk_admitcard_helper');
		 $this->load->helper('custom_contact_classes_invoice_helper');
		 $this->load->helper('custom_admitcard_helper');
		 //$this->load->helper('bulk_check_helper');
		 //$this->load->helper('bulk_seatallocation_helper');
		 $this->load->helper('bulk_invoice_helper');
		 $this->load->helper('bulk_admitcard_helper');
		 $this->load->helper('custom_invoice_helper');
		 $this->load->helper('blended_invoice_custom_helper');
		 $this->load->helper('bulk_calculate_tds_discount_helper');
		 $this->load->helper('bulk_proforma_invoice_helper');
		 $this->load->helper('getregnumber_helper');
		 
		 
		
	} 
	
public function sbicallback()
{ 
			
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php Caiib_121 sbicallback			
	 $yesterday = '2021-05-17';      
	 $sql = "SELECT receipt_no FROM `payment_transaction` Where status = 2 AND exam_code != 991  AND date LIKE '%".$yesterday."%'"; 
	 
	 
	 
	 $record = $this->db->query($sql);
	 if($record->num_rows()){
		 $i=1;
		foreach ($record->result_array() as $c_row){
			
			$responsedata = sbiqueryapi($c_row['receipt_no']);
			$receipt_no=$c_row['receipt_no'];
			$encData=implode('|',$responsedata);
			$resp_data = json_encode($responsedata);
				
			$resp_array = array('receipt_no'    => $c_row['receipt_no'],
								'txn_status'     => $responsedata[2],
								'txn_data'         => $encData.'&CALLBACK=C_S2S',
								'response_data' => $resp_data,
								'remark'         => '',
								'resp_date'     => date('Y-m-d H:i:s'), 
								);
			$this->master_model->insertRecord('pending_payment_1jan21_to_17may21', $resp_array); 
			
			if($responsedata[2] != 'SUCCESS' && $responsedata[2] != 'REFUND'){
				if($responsedata[2] == ''){
					$tra_status = 'FAIL';
				}else{
					$tra_status = $responsedata[2];
				}
				$update_arr = array('status'=>0,'transaction_details'=>$tra_status,'callback'=>'PSS2S');
				$where_arr = array('receipt_no'=>$c_row['receipt_no']);
				$this->master_model->updateRecord('payment_transaction',$update_arr,$where_arr);
				
				echo $i.' ) '. $this->db->last_query(); 
				echo '<br/>';
				$i++;
				
			}
			
		}
	}
			
}

public function find_unsuccess(){
	$exm_arr = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));
	
	$this->db->where('chk_flg',0);
	//$this->db->where('regnumber',500034455); 
	//$this->db->limit(1);
	$sql = $this->master_model->getRecords('unsuccessful_member_caiib');
	foreach($sql as $rec){
		$this->db->where('status',1);
		$this->db->where_in('exam_code',$exm_arr);
		$this->db->where('pay_type',2);
		$this->db->where('member_regnumber',$rec['regnumber']);
		$this->db->where('date >','2021-04-05 00:00:00');
		$payment = $this->master_model->getRecords('payment_transaction','','id,status,exam_code');
		
		if($payment[0]['status'] == 1){
			$update_arr = array('chk_flg'=>1);
			$update_whr = array('id'=>$rec['id']);
			$this->master_model->updateRecord('unsuccessful_member_caiib',$update_arr,$update_whr);
		}else{
			
			//get member email id
			$this->db->where('regnumber',$rec['regnumber']);
			$member = $this->master_model->getRecords('member_registration','','email');
			
			// get venue selection detail
			$this->db->where('remark !=',1);
			$this->db->where_in('exm_cd',$exm_arr);
			$this->db->where('exm_prd',121);
			$this->db->where('mem_mem_no',$rec['regnumber']);
			$venue = $this->master_model->getRecords('admit_card_details','','center_code,center_name,exm_cd,sub_cd,venueid,venue_name,venpin,exam_date,time');
			//echo $this->db->last_query();
			//echo '<br/>';
			
			$insert_arr = array();
			foreach($venue as $venue_rec){
				$insert_arr = array(
									'regnumber'=>$rec['regnumber'],
									'email'=>$member[0]['email'],
									'center_code'=>$venue_rec['center_code'],
									'center_name'=>$venue_rec['center_name'],
									'exm_cd'=>$venue_rec['exm_cd'],
									'sub_cd'=>$venue_rec['sub_cd'],
									'venueid'=>$venue_rec['venueid'],
									'venue_name'=>$venue_rec['venue_name'],
									'venpin'=>$venue_rec['venpin'],
									'exam_date'=>$venue_rec['exam_date'],
									'time'=>$venue_rec['time']
									
				);
				
				$this->master_model->insertRecord('unsuccessful_member_venue_caiib', $insert_arr);
				//echo $this->db->last_query();
				//echo '<br/>'; 
			}
			
			//echo $this->db->last_query();
			
			$update_arr1 = array('chk_flg'=>2);
			$update_whr1 = array('id'=>$rec['id']);
			$this->master_model->updateRecord('unsuccessful_member_caiib',$update_arr1,$update_whr1);
		}
	}
}	
	
public function centerstat(){ 
		$exam_code = 101;
		$exam_period = '587';        
		$result=$this->master_model->getRecords('center_master',array('exam_name'=>$exam_code,'exam_period'=>$exam_period),'center_code,center_name,exam_period');
		foreach($result as $record){
		//echo '<br>',$record['center_code'];
			//$this->db->where('institute_id',0);
			$reg = $this->master_model->getRecords('member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_center_code'=>$record['center_code'],"pay_status"=>1));//, "examination_date"=>2018-05-12
			//echo '<br>',$this->db->last_query();
			$insert_array = array( 
								'exam_code' =>$exam_code,
								'center_code'=>$record['center_code'],
								'center_name'=>$record['center_name'],
								'exam_period'=>$exam_period,
								'register_count'=>sizeof($reg)
							);  
							
			$last_id = $this->master_model->insertRecord('center_stat',$insert_array,true); 
							
		}
		//echo '<pre>',print_r($last_id);
		//exit;
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "filename_you_wish.csv";
		$query = "SELECT * FROM center_stat";
		$result1 = $this->db->query($query);
		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
		//$this->db->empty_table('center_stat'); 
		force_download($filename, $data);
		
	}

public function chk_naar(){
	$payment_id = $this->uri->segment(3);
	
	$this->db->where('id',$payment_id);	
	$query = $this->master_model->getRecords('bulk_payment_transaction','','pay_count');
	
	$this->db->where('pay_txn_id',$payment_id);	
	$this->db->where('exam_code',1015);
	$this->db->where('exam_period',29);
	$invoice = $this->master_model->getRecords('exam_invoice','','qty');

	
	$this->db->where('ptid',$payment_id);	
	$query_one = $this->master_model->getRecords('bulk_member_payment_transaction');
	
	$this->db->where('id',$query_one[0]['memexamid']);
	$query_two = $this->master_model->getRecords('member_exam','','exam_center_code');
	
	$this->db->where('exam_center_code',$query_two[0]['exam_center_code']);
	$this->db->where('exam_code',1015);
	$this->db->where('exam_period',29);
	$this->db->where('pay_status',1);
	$query_three = $this->master_model->getRecords('member_exam','','id');
	
	echo $this->db->last_query();
	echo '<br/>';
	
	
	
	$application_cnt = sizeof($query_three);
	
	echo 'payment : '.$query[0]['pay_count'];
	echo '<br/>';
	echo 'Exam : '.$application_cnt;
	echo '<br/>';
	echo 'invoice : '.$invoice[0]['qty'];
	echo '<br/>';
	
	if($query[0]['pay_count'] == $application_cnt){
		echo 'OK';
	}else{
		echo 'NOT OK';	
	}
	echo '<br/>';
	echo '*******************';
	echo '<br/>';
	if($invoice[0]['qty'] == $application_cnt){
		echo 'INVOICE OK';
	}else{
		echo 'INVOICE NOT OK';	
	}
	
	
}

public function subject_missing_R(){                      
		$member_array = array();  
		$exarr = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));  
		//$exarr = array(21); 
		$this->db->select('mem_mem_no');  
		$this->db->distinct('mem_mem_no');  
		$this->db->where_in('exm_cd',$exarr); 
		$this->db->where('exm_prd',121);
		$this->db->where('remark','1');
		$this->db->where('app_update','0');
		//$this->db->where('mem_mem_no',500193052);
		$this->db->where('created_on >= ','2021-05-12 00:00:01');   
		$this->db->where('created_on <= ','2021-05-17 23:59:59');         
		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd,mem_exam_id'); 
		
		//echo $this->db->last_query();
		//exit;  
		
		foreach($admit_card as $member_no){ 
			$app_arr = array('R');
			$this->db->where('member_no',$member_no['mem_mem_no']);
			$this->db->where_in('exam_code',$exarr);
			$this->db->where('eligible_period',121);
			$this->db->where('app_category','R'); 
			$member_rec = $this->master_model->getRecords('eligible_master','','id');
			$member_rec_cnt = count($member_rec);
			
			if($member_rec_cnt != 0){
				$this->db->where('mem_mem_no',$member_no['mem_mem_no']);
				$this->db->where_in('exm_cd',$exarr);
				$this->db->where('exm_prd',121);
				$this->db->where('remark','1');
				$this->db->where('app_update','0');
				$admit_card = $this->master_model->getRecords('admit_card_details','','admitcard_id');
				$admit_card_cnt = count($admit_card);
				//echo '>>'. $admit_card_cnt;
				if($member_no['exm_cd'] != 992){
					if($admit_card_cnt != 3){
						$member_array[] = $member_no['mem_exam_id'];
					}
				}elseif($member_no['exm_cd'] == 992){
					if($admit_card_cnt != 2){
						$member_array[] = $member_no['mem_exam_id'];
					}
				}
				
			}
		} 
		
		echo "<pre>";
		print_r($member_array);
	}
	
public function subject_missing_F(){            
		$member_array = array();   
		$exarr = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));    
		$this->db->select('mem_mem_no');  
		$this->db->distinct('mem_mem_no');
		$this->db->where_in('exm_cd',$exarr);
		$this->db->where('exm_prd',121); 
		//$this->db->where('mem_mem_no',200047209); 
		$this->db->where('remark','1');
		$this->db->where('app_update','0');
		$this->db->where('created_on >= ','2021-05-12 00:00:01');         
		$this->db->where('created_on <= ','2021-05-17 23:59:59');  
		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd,mem_exam_id');  
		
		foreach($admit_card as $member_no){
			$app_arr = array('F');
			$this->db->where('member_no',$member_no['mem_mem_no']);
			$this->db->where_in('exam_code',$exarr);
			$this->db->where('eligible_period',121);
			//$this->db->where_in('app_category',$app_arr); 
			$this->db->where('exam_status','F'); 
			$this->db->where('app_category !=','R'); 
			$member_rec = $this->master_model->getRecords('eligible_master','','id');
			$member_rec_cnt = count($member_rec);
			
			if($member_rec_cnt != 0){ 
				$this->db->where('mem_mem_no',$member_no['mem_mem_no']);
				$this->db->where_in('exm_cd',$exarr);
				$this->db->where('exm_prd',121);
				$this->db->where('remark','1');
				$this->db->where('app_update','0');
				$admit_card = $this->master_model->getRecords('admit_card_details','','admitcard_id');
				$admit_card_cnt = count($admit_card);
				
				/* echo '########################';
				echo '1 > '.$member_rec_cnt;
				echo '<br/>';
				echo '2 > '.$admit_card_cnt; */
				
				if($member_no['exm_cd'] != 992){
					if($member_rec_cnt != $admit_card_cnt){
						$member_array[] = $member_no['mem_exam_id'];
					}
				}elseif($member_no['exm_cd'] == 992){
					if($admit_card_cnt != 2){
						$member_array[] = $member_no['mem_exam_id'];
					}
				}
			
			}
		}
		
		echo "<pre>";
		print_r($member_array);  
	}
	
public function subject_missing_fresh(){             
		$member_array = array();
		$exarr = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));     
		 //$exarr = array(21);   
		$this->db->select('mem_mem_no'); 
		$this->db->distinct('mem_mem_no');
		$this->db->where_in('exm_cd',$exarr);
		$this->db->where('exm_prd',121); 
		//$this->db->where('mem_mem_no','510453280');  
		$this->db->where('remark','1');
		$this->db->where('app_update','0');
		$this->db->where('created_on >= ','2021-05-12 00:00:01');          
		$this->db->where('created_on <= ','2021-05-17 23:59:59');    
		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd,mem_exam_id');
		
		foreach($admit_card as $member_no){
			$this->db->where('member_no',$member_no['mem_mem_no']);
			$this->db->where_in('exam_code',$exarr);
			$this->db->where('eligible_period',121);
			$member_rec = $this->master_model->getRecords('eligible_master','','id');
			$member_rec_cnt = count($member_rec);
			
			if($member_rec_cnt <= 0){ 
				$this->db->where('mem_mem_no',$member_no['mem_mem_no']);
				$this->db->where_in('exm_cd',$exarr);
				$this->db->where('exm_prd',121);
				$this->db->where('remark','1');
				$this->db->where('app_update','0');
				$admit_card = $this->master_model->getRecords('admit_card_details','','admitcard_id');
				$admit_card_cnt = count($admit_card);
				
				
				
				if($member_no['exm_cd'] != 992){
					if($admit_card_cnt != 3){
						$member_array[] = $member_no['mem_exam_id'];
					}
				}elseif($member_no['exm_cd'] == 992){
					if($admit_card_cnt != 2){
						$member_array[] = $member_no['mem_exam_id'];
					}
				}
				
				
			}
		}
		echo "<pre>";
		print_r($member_array);   
	}
	
	
public function member_exam_not_update(){ 
		
		$exam_arr = array();
		$exarr = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));
		$this->db->select('mem_exam_id');
		$this->db->distinct('mem_exam_id');
		//$this->db->where('exm_cd',21);
		$this->db->where_in('exm_cd',$exarr);
		$this->db->where('exm_prd',121);
		$this->db->where('remark','1');
		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_exam_id');
		
		foreach($admit_card as $admit_card_rec){
			
			$this->db->where('id',$admit_card_rec['mem_exam_id']);
			$exam = $this->master_model->getRecords('member_exam','','pay_status');
			
			if($exam[0]['pay_status'] != 1){
				$exam_arr[] = $admit_card_rec['mem_exam_id'];
			}
			
		}
		
		echo "<pre>";
		print_r($exam_arr);
}
	
public function remark_not_update(){
		
		$exam_arr = array();
		$exarr = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));
		$this->db->select('id');
		//$this->db->where('exam_code',21);
		$this->db->where_in('exam_code',$exarr);
		$this->db->where('exam_period',121);
		$this->db->where('pay_status','1');
		$exam = $this->master_model->getRecords('member_exam','','id');
		
		foreach($exam as $exam_rec){
			
			$this->db->where('mem_exam_id',$exam_rec['id']);
			//$this->db->where('exm_cd',21);
			$this->db->where_in('exm_cd',$exarr);
			$this->db->where('exm_prd',121);
			$admit_card = $this->master_model->getRecords('admit_card_details','','remark');
			
			if($admit_card[0]['remark'] == 2){
				$exam_arr[] = $exam_rec['id'];
			}
			
		}
		
		echo "<pre>";
		print_r($exam_arr);
		
	}
	
	
public function csv_demo(){
	$yesterday =  $this->uri->segment(3);
	$exm_cd_arr = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));
	
	
	// subject wise count
	$query="SELECT mem_mem_no, count(`mem_mem_no`) FROM `admit_card_details` WHERE `exm_cd` IN (".$this->config->item('examCodeCaiib').",."$this->config->item('examCodeCaiibElective63').",65,".$this->config->item('examCodeCaiibElective68').",".$this->config->item('examCodeCaiibElective69').",".$this->config->item('examCodeCaiibElective70').",".$this->config->item('examCodeCaiibElective71').") AND `exm_prd` = 121 AND `remark` = 1 AND created_on LIKE '%".$yesterday."%' group by mem_mem_no";
	$record = $this->db->query($query);
	
	$filename = 'caiib_'.$yesterday.'.csv';
	header("Content-Description: File Transfer");
	header("Content-Disposition: attachment; filename=$filename");
	header("Content-Type: application/csv; "); 

	// file creation
	$file = fopen('php://output', 'w');

	$header = array("mem_mem_no","subject_count");
	fputcsv($file, $header);

	foreach ($record->result_array() as $c_row){
	 fputcsv($file,$c_row);
	 
	}

	fclose($file);
	exit;	
}
	
public function daily_count(){
	$yesterday =  date("Y-m-d",strtotime("yesterday"));
	$exm_cd_arr = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),65,$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));
	
	
	// Member exam count
	$this->db->where_in('exam_code',$exm_cd_arr);   
	$this->db->where('exam_period',121); 
	$this->db->where('pay_status',1);  
	$this->db->like('created_on',$yesterday);         
	$member_exam = $this->master_model->getRecordCount('member_exam'); 
	
	// Get Payment status [Success]
	$this->db->where_in('exam_code',$exm_cd_arr);   
	$this->db->where('status',1);
	$this->db->where('pay_type',2);  
	$this->db->like('date',$yesterday);         
	$payment_transaction_success = $this->master_model->getRecordCount('payment_transaction'); 
	
	// Get Payment status [Fail]
	$this->db->where_in('exam_code',$exm_cd_arr);   
	$this->db->where('status',0);
	$this->db->where('pay_type',2);  
	$this->db->like('date',$yesterday);         
	$payment_transaction_fail = $this->master_model->getRecordCount('payment_transaction');
	
	// Get Payment status [pending]
	$this->db->where_in('exam_code',$exm_cd_arr);   
	$this->db->where('status',2);
	$this->db->where('pay_type',2);  
	$this->db->like('date',$yesterday);         
	$payment_transaction_pending = $this->master_model->getRecordCount('payment_transaction');
	
	// Get Payment status [Refund]
	$this->db->where_in('exam_code',$exm_cd_arr);   
	$this->db->where('status',3);
	$this->db->where('pay_type',2);  
	$this->db->like('date',$yesterday);         
	$payment_transaction_refund = $this->master_model->getRecordCount('payment_transaction');
	
	
	$total_transaction = $payment_transaction_success + $payment_transaction_fail + $payment_transaction_pending + $payment_transaction_refund;
	
	$link = 'https://iibf.esdsconnect.com/Caiib_121/csv_demo/'.$yesterday;
	
	
	$final_str = "Hello Sir"; 
	$final_str.= "<br/><br/>";
	$final_str.= 'Please check the CAIIB exam registration statistic for date "'.$yesterday.'". Total transaction done is "'.$total_transaction.'"
				<br/>
				Successful Exam application : '.$member_exam.'
				<br/><br/>
				Below are the payment status of all "'.$total_transaction.'" transaction
				<br/>
				Successful Transaction : '.$payment_transaction_success.'
				<br/>
				Fail Transaction: '.$payment_transaction_fail.'
				<br/>
				Pending Transaction: '.$payment_transaction_pending.'
				<br/>
				Refund Transaction : '.$payment_transaction_refund.'
				<br/><br/>
				Click on below link to download subject wise count file.
				<br/>
				<a href="'.$link.'">Click Here</a>
				'; 
	
	$final_str.= "<br/><br/>";
	$final_str.= "Regards,";
	$final_str.= "<br/>";
	$final_str.= "ESDS TEAM";
	
	//anishrivastava@iibf.org.in
	//assistantdirectorit3@iibf.org.in
	
	$to_arr = array('assistantdirectorit3@iibf.org.in','prafull.tupe@esds.co.in');
	//$to_arr = array('pawansing.pardeshi@esds.co.in','prafull.tupe@esds.co.in');
	
	$attachpath = ""; 
	$info_arr=array(//'to'=>$email[0]['email'],
					//'to'=>'raajpardeshi@gmail.com',
					'to'=>$to_arr,
					'from'=>'noreply@iibf.org.in',
					'subject'=>'CAIIB Registration Count',
					'message'=>$final_str
				);
	$files=array($attachpath);
	
	if($this->Emailsending->mailsend($info_arr)){
		echo 'mail sent';
		echo "<br/>"; 
	}
	
	
	
}


}


