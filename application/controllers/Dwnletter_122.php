<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dwnletter_122 extends CI_Controller {
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
		 $this->load->model('Master_model');
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

public function find_unsuccess(){
	$exm_arr = array(21,42,992);
	
	$this->db->where('chk_flg',0);
	//$this->db->where('regnumber',500034455); 
	//$this->db->limit(1);
	$sql = $this->master_model->getRecords('unsuccessful_member');
	foreach($sql as $rec){
		$this->db->where('status',1);
		$this->db->where_in('exam_code',$exm_arr);
		$this->db->where('pay_type',2);
		$this->db->where('member_regnumber',$rec['regnumber']);
		$this->db->where('date >','2021-02-23 00:00:00');
		$payment = $this->master_model->getRecords('payment_transaction','','id,status,exam_code');
		
		if($payment[0]['status'] == 1){
			$update_arr = array('chk_flg'=>1);
			$update_whr = array('id'=>$rec['id']);
			$this->master_model->updateRecord('unsuccessful_member',$update_arr,$update_whr);
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
			
			foreach($venue as $venue_rec){
				$insert_arr = array(
									'regnumber'=>$rec['regnumber'],
									'email'=>$member[0]['email'],
									'center_code'=>$venue[0]['center_code'],
									'center_name'=>$venue[0]['center_name'],
									'exm_cd'=>$venue[0]['exm_cd'],
									'sub_cd'=>$venue[0]['sub_cd'],
									'venueid'=>$venue[0]['venueid'],
									'venue_name'=>$venue[0]['venue_name'],
									'venpin'=>$venue[0]['venpin'],
									'exam_date'=>$venue[0]['exam_date'],
									'time'=>$venue[0]['time']
									
				);
				
				$this->master_model->insertRecord('unsuccessful_member_venue', $insert_arr);
			}
			
			//echo $this->db->last_query();
			
			$update_arr1 = array('chk_flg'=>2,'email'=>$member[0]['email']);
			$update_whr1 = array('id'=>$rec['id']);
			$this->master_model->updateRecord('unsuccessful_member',$update_arr1,$update_whr1);
		}
	}
}

public function find_unsuccess_27apr(){
	$exm_arr = array(21,42,992);
	
	$this->db->where('chk_flg',2);
	$this->db->where('email !=','');
	//$this->db->limit(1);
	$sql = $this->master_model->getRecords('unsuccessful_member_1');
	//echo $this->db->last_query(); 
	//exit;
	foreach($sql as $rec){
		
			//get member email id
			$this->db->where('regnumber',$rec['regnumber']);
			$member = $this->master_model->getRecords('member_registration','','email');
			
			// get venue selection detail 
			$this->db->where('remark !=',1);
			$this->db->where_in('exm_cd',$exm_arr);
			$this->db->where('exm_prd',121);
			$this->db->where('mem_mem_no',$rec['regnumber']);
			$venue = $this->master_model->getRecords('admit_card_details','','center_code,center_name,exm_cd,sub_cd,venueid,venue_name,venpin,exam_date,time');
			
			
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
				
				$this->master_model->insertRecord('unsuccessful_member_venue', $insert_arr); 
				//echo $this->db->last_query(); 
				//echo '<br/>';
			}
			
			//echo $this->db->last_query();
			
			$update_arr1 = array('chk_flg'=>3);
			$update_whr1 = array('id'=>$rec['id']);
			$this->master_model->updateRecord('unsuccessful_member_1',$update_arr1,$update_whr1);
		
	}
}	

public function sendmail_positive_member_jaiiib121_553(){ 
	
	$this->db->where('mail_send',0);
	$this->db->where('exam_code',21);
	//$this->db->limit(1);
	$this->db->group_by('mem_exam_id');
	$sql = $this->master_model->getRecords('jaiib_settel_capacity_11april2021_558_test','','regnumber,mem_exam_id,exam_code'); 
	
	$i=1;	
	foreach($sql as $rec){ 
		
		$final_str = 'Dear Candidate <br/><br/>';
		$final_str.= 'This is with reference to your JAIIB exam application for May-2021';   
		$final_str.= '<br/><br/>';
		$final_str.= 'Admit letter is issued to you and the same is available under your profile and you can download the same.';   
		$final_str.= '<br/><br/>';
		$final_str.= 'Regards,';
		$final_str.= '<br/>';
		$final_str.= 'IIBF TEAM'; 
		  
		$attachpath = ''; 
		
		$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['regnumber'],'isactive'=>'1'),'email,mobile');    
		$info_arr=array('to'=>$email[0]['email'], 
						//'to'=>'pawansing.pardeshi@esds.co.in',
						'from'=>'noreply@iibf.org.in',
						'subject'=>'Regarding your JAIIB exam application for May-2021',
						'message'=>$final_str
					); 
		$files=array($attachpath);
		if($this->Emailsending->mailsend_attch($info_arr,$files)){
			$update_arr = array('mail_send'=>1);
			$update_whr = array('mem_exam_id'=>$rec['mem_exam_id']);
			$this->master_model->updateRecord('jaiib_settel_capacity_11april2021_558_test',$update_arr,$update_whr);
			echo $i.')'. $this->db->last_query();
			echo '<br/>';
		}
		$i++;
	}
}

public function sendmail_positive_member_jaiiib121_76(){ 
	
	$this->db->where('mail_send',0);  
	$this->db->where('exam_code',21);
	//$this->db->limit(1);
	$this->db->group_by('mem_exam_id');
	$sql = $this->master_model->getRecords('jaiib_settel_capacity_106_sette_unsettel','','regnumber,mem_exam_id,exam_code'); 
	
	$i=1;	
	foreach($sql as $rec){ 
		
		$final_str = 'Dear Candidate <br/><br/>';
		$final_str.= 'This is with reference to your JAIIB exam application for May-2021';   
		$final_str.= '<br/><br/>';
		$final_str.= 'Admit letter is issued to you and the same is available under your profile and you can download the same.';   
		$final_str.= '<br/><br/>';
		$final_str.= 'Regards,';
		$final_str.= '<br/>';
		$final_str.= 'IIBF TEAM'; 
		  
		$attachpath = ''; 
		
		$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['regnumber'],'isactive'=>'1'),'email,mobile');    
		$info_arr=array('to'=>$email[0]['email'], 
						//'to'=>'pawansing.pardeshi@esds.co.in',
						'from'=>'noreply@iibf.org.in',
						'subject'=>'Regarding your JAIIB exam application for May-2021',
						'message'=>$final_str
					); 
		$files=array($attachpath);
		if($this->Emailsending->mailsend_attch($info_arr,$files)){
			$update_arr = array('mail_send'=>1);
			$update_whr = array('mem_exam_id'=>$rec['mem_exam_id']);
			$this->master_model->updateRecord('jaiib_settel_capacity_106_sette_unsettel',$update_arr,$update_whr);
			echo $i.')'. $this->db->last_query();
			echo '<br/>'; 
		}
		$i++;
	}
}

public function sendmail_subjectmissing_member_jaiiib121_315(){ 
	
	$member_array = array(510496284,510486041,510497655,510486007,510474101,510491952,510491975,510491449,510492296,510484297);
	$arr_size=sizeof($member_array);	
	for($i=0;$i<$arr_size;$i++){ 
		
		$final_str = 'Dear Candidate <br/><br/>';
		$final_str.= 'This is with reference to your JAIIB exam application for May-2021';   
		$final_str.= '<br/><br/>';
		$final_str.= 'Admit letter is issued to you and the same is available under your profile and you can download the same.';   
		$final_str.= '<br/><br/>';
		$final_str.= 'Regards,';
		$final_str.= '<br/>';
		$final_str.= 'IIBF TEAM'; 
		  
		$attachpath = '';  
		
		$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_array[$i],'isactive'=>'1'),'email,mobile');    
		$info_arr=array('to'=>$email[0]['email'], 
						//'to'=>'pawansing.pardeshi@esds.co.in',
						'from'=>'noreply@iibf.org.in',
						'subject'=>'Regarding your JAIIB exam application for May-2021',
						'message'=>$final_str
					); 
		$files=array($attachpath);
		if($this->Emailsending->mailsend_attch($info_arr,$files)){
			
			echo $i.') '. $member_array[$i];
			echo '<br/>';
		}
		
	}
}

public function sendmail_positive_member_jaiiib121_movement(){ 
	
	$this->db->where('mail_send',0);
	$this->db->where('exam_code',21);
	//$this->db->limit(1);
	$this->db->group_by('mem_exam_id');
	$sql = $this->master_model->getRecords('jaiib_settel_capacity_11april2021_558_move','','regnumber,mem_exam_id,exam_code'); 
	
	$i=1;	
	foreach($sql as $rec){ 
		
		$final_str = 'Dear Candidate <br/><br/>';
		$final_str.= 'This is with reference to your JAIIB exam application for May-2021';   
		$final_str.= '<br/><br/>';
		$final_str.= 'In this regard we would like to state that your JAIIB exam application is still under processing.';   
		$final_str.= '<br/><br/>';
		$final_str.= 'We will try to accommodate you at the same centre/venue or nearest centre/venue based on availability.';   
		$final_str.= '<br/><br/>';
		$final_str.= 'Admit letter will be emailed to your email ID registered with IIBF and the same will also be available for download in your profile by 15-Apr-2021';   
		$final_str.= '<br/><br/>';
		$final_str.= 'Regards,';
		$final_str.= '<br/>';
		$final_str.= 'IIBF TEAM'; 
		  
		$attachpath = ''; 
		
		$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['regnumber'],'isactive'=>'1'),'email,mobile');    
		$info_arr=array('to'=>$email[0]['email'], 
						//'to'=>'pawansing.pardeshi@esds.co.in',
						'from'=>'noreply@iibf.org.in',
						'subject'=>'Regarding your JAIIB exam application for May-2021',
						'message'=>$final_str
					); 
		$files=array($attachpath);
		if($this->Emailsending->mailsend_attch($info_arr,$files)){
			$update_arr = array('mail_send'=>1);
			$update_whr = array('mem_exam_id'=>$rec['mem_exam_id']);
			$this->master_model->updateRecord('jaiib_settel_capacity_11april2021_558_move',$update_arr,$update_whr);
			echo $i.')'. $this->db->last_query();
			echo '<br/>'; 
		}
		$i++;
	}
}

	
public function no_payment_10(){  
    $arr = array();
    
    $sql = "SELECT DISTINCT receipt_no FROM `jaiib_settel_capacity` ";  
    
    $record = $this->db->query($sql);
    if($record->num_rows()){
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
            $this->master_model->insertRecord('jaiib_settel_capacity_check_sbi', $resp_array);
			
			
            
        }
    }
}
	
	
public function wrong_invoice_number_settel(){ 
	$arr = array(3180546,3180570,3180889,3180954,3180997,3181071,3181081,3181117,3181209,3181213,3181254,3181255,3181371,3182017,3182036,3182115,3182134,3182142,3182202,3182214,3182236,3182271);  
	
	for($i=0;$i<sizeof($arr);$i++){
		$invoiceNumber =$this->generate_exam_invoice_number_wrong_invoice($arr[$i]);
		$invoiceNumber='EX/21-22/'.$invoiceNumber;
		//echo $invoiceNumber;
		
		$this->db->where('invoice_id',$arr[$i]);
		$sql = $this->Master_model->getRecords('exam_invoice','','invoice_id,member_no,invoice_image');
		
		//EX/20-21/283012
		//510303561_EX_20-21_283012.jpg
		
		//EX/20-21/283014
		//510432217_EX_20-21_283014.jpg
		
		$new_str = str_replace('/','_',$invoiceNumber);
		$invoice_name = $sql[0]['member_no'].'_'.$new_str.'.jpg';
		
		$update_arr = array('invoice_no'=>$invoiceNumber,'invoice_image'=>$invoice_name);
		$where_arr = array('invoice_id'=>$arr[$i]);
		$this->master_model->updateRecord('exam_invoice',$update_arr,$where_arr);
		
		echo $this->db->last_query();
		echo '<br/>'; 
		
		
	}
}
function generate_exam_invoice_number_wrong_invoice($invoice_id= NULL)
{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			//$last_id = str_pad($CI->master_model->insertRecord('config_exam_invoice_31_3_2020',$insert_info,true), 6, "0", STR_PAD_LEFT);;
			$last_id = str_pad($CI->master_model->insertRecord('config_exam_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	
public function update_venue_nseit(){
	
	
	$exam_date_arr = array('2021-05-02','2021-05-09','2021-05-16'); 
	
	$this->db->where('record_update',0);
	//$this->db->limit(2);
	$sql = $this->Master_model->getRecords('additional_venue_nseit_2apr21');
	foreach($sql as $rec){ 
		
		// Date one update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one = $this->Master_model->getRecords('venue_master');
		$date_one_new_capacity = $venue_date_one[0]['session_capacity'] + $rec['date_one'];
		
		if($date_one_new_capacity > 0){
		
			$update_date_one = array('session_capacity'=>$date_one_new_capacity);
			$where_date_one = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code']);
			$this->master_model->updateRecord('venue_master',$update_date_one,$where_date_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date two update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two = $this->Master_model->getRecords('venue_master');
		$date_two_new_capacity = $venue_date_two[0]['session_capacity'] + $rec['date_two'];
		
		if($date_two_new_capacity > 0){
		
			$update_date_two = array('session_capacity'=>$date_two_new_capacity);
			$where_date_two = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code']);
			$this->master_model->updateRecord('venue_master',$update_date_two,$where_date_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date three update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three = $this->Master_model->getRecords('venue_master');
		$date_three_new_capacity = $venue_date_three[0]['session_capacity'] + $rec['date_three'];
		
		if($date_three_new_capacity > 0){
		
			$update_date_three = array('session_capacity'=>$date_three_new_capacity);
			$where_date_three = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code']);
			$this->master_model->updateRecord('venue_master',$update_date_three,$where_date_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		
		}
		
		$update_nseit = array('record_update'=>1);
		$where_nseit = array('id'=>$rec['id']);
		$this->master_model->updateRecord('additional_venue_nseit_2apr21',$update_nseit,$where_nseit); 
		
		echo $this->db->last_query();
		echo '<br/>';
		
	}
}
	
public function update_venue_sify(){
	
	$exam_date_arr = array('2021-05-02','2021-05-09','2021-05-16'); 
	
	$this->db->where('record_update',0);
	$this->db->limit(1);
	$sql = $this->Master_model->getRecords('additional_venue_sify_2apr21');
	
	foreach($sql as $rec){
		echo $rec['venue_code'];exit;
		// Date one time one update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','8.30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_one = $this->Master_model->getRecords('venue_master');
		$date_one_time_one_new_capacity = $venue_date_one_time_one[0]['session_capacity'] + $rec['date_one_time_one'];
		
		if($date_one_time_one_new_capacity > 0){
		
			$update_date_one_time_one = array('session_capacity'=>$date_one_time_one_new_capacity);
			$where_date_one_time_one = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'8.30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_one,$where_date_one_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date one time two update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','11.15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_two = $this->Master_model->getRecords('venue_master');
		$date_one_time_two_new_capacity = $venue_date_one_time_two[0]['session_capacity'] + $rec['date_one_time_two'];
		
		if($date_one_time_two_new_capacity > 0){
		
			$update_date_one_time_two = array('session_capacity'=>$date_one_time_two_new_capacity);
			$where_date_one_time_two = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'11.15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_two,$where_date_one_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date one time three update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','2.00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_three = $this->Master_model->getRecords('venue_master');
		$date_one_time_three_new_capacity = $venue_date_one_time_three[0]['session_capacity'] + $rec['date_one_time_three'];
		
		if($date_one_time_three_new_capacity > 0){
		
			$update_date_one_time_three = array('session_capacity'=>$date_one_time_three_new_capacity);
			$where_date_one_time_three = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'2.00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_three,$where_date_one_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		
		// Date two time one update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','8.30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_one = $this->Master_model->getRecords('venue_master');
		$date_two_time_one_new_capacity = $venue_date_two_time_one[0]['session_capacity'] + $rec['date_two_time_one'];
		
		if($date_two_time_one_new_capacity > 0){
		
			$update_date_two_time_one = array('session_capacity'=>$date_two_time_one_new_capacity);
			$where_date_two_time_one = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'8.30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_one,$where_date_two_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date two time two update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','11.15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_two = $this->Master_model->getRecords('venue_master');
		$date_two_time_two_new_capacity = $venue_date_two_time_two[0]['session_capacity'] + $rec['date_two_time_two'];
		
		if($date_two_time_two_new_capacity > 0){
		
			$update_date_two_time_two = array('session_capacity'=>$date_two_time_two_new_capacity);
			$where_date_two_time_two = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'11.15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_two,$where_date_two_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date two time three update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','2.00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_three = $this->Master_model->getRecords('venue_master');
		$date_two_time_three_new_capacity = $venue_date_two_time_three[0]['session_capacity'] + $rec['date_two_time_three'];
		
		if($date_two_time_three_new_capacity > 0){
		
			$update_date_two_time_three = array('session_capacity'=>$date_two_time_three_new_capacity);
			$where_date_two_time_three = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'2.00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_three,$where_date_two_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date three time one update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','8.30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_one = $this->Master_model->getRecords('venue_master');
		$date_three_time_one_new_capacity = $venue_date_three_time_one[0]['session_capacity'] + $rec['date_three_time_one'];
		
		if($date_three_time_one_new_capacity > 0){
		
			$update_date_three_time_one = array('session_capacity'=>$date_three_time_one_new_capacity);
			$where_date_three_time_one = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'8.30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_one,$where_date_three_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		// Date three time two update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','11.15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_two = $this->Master_model->getRecords('venue_master');
		$date_three_time_two_new_capacity = $venue_date_three_time_two[0]['session_capacity'] + $rec['date_three_time_two'];
		
		if($date_three_time_two_new_capacity > 0){
		
			$update_date_three_time_two = array('session_capacity'=>$date_three_time_two_new_capacity);
			$where_date_three_time_two = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'11.15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_two,$where_date_three_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		// Date three time three update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','2.00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_three = $this->Master_model->getRecords('venue_master');
		$date_three_time_three_new_capacity = $venue_date_three_time_three[0]['session_capacity'] + $rec['date_three_time_three'];
		
		if($date_three_time_three_new_capacity > 0){
		
			$update_date_three_time_three = array('session_capacity'=>$date_three_time_three_new_capacity);
			$where_date_three_time_three = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'2.00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_three,$where_date_three_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		$update_nseit = array('record_update'=>1);
		$where_nseit = array('id'=>$rec['id']);
		$this->master_model->updateRecord('additional_venue_sify_2apr21',$update_nseit,$where_nseit); 
		
		echo $this->db->last_query();
		echo '<br/>';
		
		
	
	}
}
public function update_venue_9april21(){ 
	
	$exam_date_arr = array('2021-05-02','2021-05-09','2021-05-16'); 
	
	$this->db->where('record_update',0);
	//$this->db->limit(1);
	$sql = $this->Master_model->getRecords('additional_venue_9april21');
	
	//echo $this->db->last_query();
	//exit;
	
	foreach($sql as $rec){
		//echo $rec['venue_code'];exit;
		// Date one time one update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','8.30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_one = $this->Master_model->getRecords('venue_master');
		$date_one_time_one_new_capacity = $venue_date_one_time_one[0]['session_capacity'] + $rec['date_one_time_one'];
		
		if($date_one_time_one_new_capacity > 0){
		
			$update_date_one_time_one = array('session_capacity'=>$date_one_time_one_new_capacity);
			$where_date_one_time_one = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'8.30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_one,$where_date_one_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date one time two update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','11.15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_two = $this->Master_model->getRecords('venue_master');
		$date_one_time_two_new_capacity = $venue_date_one_time_two[0]['session_capacity'] + $rec['date_one_time_two'];
		
		if($date_one_time_two_new_capacity > 0){
		
			$update_date_one_time_two = array('session_capacity'=>$date_one_time_two_new_capacity);
			$where_date_one_time_two = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'11.15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_two,$where_date_one_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date one time three update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','2.00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_three = $this->Master_model->getRecords('venue_master');
		$date_one_time_three_new_capacity = $venue_date_one_time_three[0]['session_capacity'] + $rec['date_one_time_three'];
		
		if($date_one_time_three_new_capacity > 0){
		
			$update_date_one_time_three = array('session_capacity'=>$date_one_time_three_new_capacity);
			$where_date_one_time_three = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'2.00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_three,$where_date_one_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		
		// Date two time one update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','8.30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_one = $this->Master_model->getRecords('venue_master');
		$date_two_time_one_new_capacity = $venue_date_two_time_one[0]['session_capacity'] + $rec['date_two_time_one'];
		
		if($date_two_time_one_new_capacity > 0){
		
			$update_date_two_time_one = array('session_capacity'=>$date_two_time_one_new_capacity);
			$where_date_two_time_one = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'8.30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_one,$where_date_two_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date two time two update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','11.15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_two = $this->Master_model->getRecords('venue_master');
		$date_two_time_two_new_capacity = $venue_date_two_time_two[0]['session_capacity'] + $rec['date_two_time_two'];
		
		if($date_two_time_two_new_capacity > 0){
		
			$update_date_two_time_two = array('session_capacity'=>$date_two_time_two_new_capacity);
			$where_date_two_time_two = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'11.15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_two,$where_date_two_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date two time three update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','2.00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_three = $this->Master_model->getRecords('venue_master');
		$date_two_time_three_new_capacity = $venue_date_two_time_three[0]['session_capacity'] + $rec['date_two_time_three'];
		
		if($date_two_time_three_new_capacity > 0){
		
			$update_date_two_time_three = array('session_capacity'=>$date_two_time_three_new_capacity);
			$where_date_two_time_three = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'2.00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_three,$where_date_two_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date three time one update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','8.30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_one = $this->Master_model->getRecords('venue_master');
		$date_three_time_one_new_capacity = $venue_date_three_time_one[0]['session_capacity'] + $rec['date_three_time_one'];
		
		if($date_three_time_one_new_capacity > 0){
		
			$update_date_three_time_one = array('session_capacity'=>$date_three_time_one_new_capacity);
			$where_date_three_time_one = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'8.30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_one,$where_date_three_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		// Date three time two update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','11.15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_two = $this->Master_model->getRecords('venue_master');
		$date_three_time_two_new_capacity = $venue_date_three_time_two[0]['session_capacity'] + $rec['date_three_time_two'];
		
		if($date_three_time_two_new_capacity > 0){
		
			$update_date_three_time_two = array('session_capacity'=>$date_three_time_two_new_capacity);
			$where_date_three_time_two = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'11.15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_two,$where_date_three_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		// Date three time three update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','2.00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_three = $this->Master_model->getRecords('venue_master');
		$date_three_time_three_new_capacity = $venue_date_three_time_three[0]['session_capacity'] + $rec['date_three_time_three'];
		
		if($date_three_time_three_new_capacity > 0){
		
			$update_date_three_time_three = array('session_capacity'=>$date_three_time_three_new_capacity);
			$where_date_three_time_three = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'2.00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_three,$where_date_three_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		$update_nseit = array('record_update'=>1);
		$where_nseit = array('id'=>$rec['id']);
		$this->master_model->updateRecord('additional_venue_9april21',$update_nseit,$where_nseit); 
		
		echo $this->db->last_query();
		echo '<br/>';
		
		
	
	}
}
public function update_venue_9april21_movement(){ 
	
	$exam_date_arr = array('2021-05-02','2021-05-09','2021-05-16'); 
	
	$this->db->where('record_update',0);
	$this->db->limit(1);
	$sql = $this->Master_model->getRecords('movement_venue');
	
	//echo $this->db->last_query();
	//exit;
	
	foreach($sql as $rec){
		//echo $rec['venue_code'];exit;
		// Date one time one update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','8.30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_one = $this->Master_model->getRecords('venue_master');
		$date_one_time_one_new_capacity = $venue_date_one_time_one[0]['session_capacity'] + $rec['date_one_time_one'];
		
		if($date_one_time_one_new_capacity > 0){
		
			$update_date_one_time_one = array('session_capacity'=>$date_one_time_one_new_capacity);
			$where_date_one_time_one = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'8.30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_one,$where_date_one_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date one time two update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','11.15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_two = $this->Master_model->getRecords('venue_master');
		$date_one_time_two_new_capacity = $venue_date_one_time_two[0]['session_capacity'] + $rec['date_one_time_two'];
		
		if($date_one_time_two_new_capacity > 0){
		
			$update_date_one_time_two = array('session_capacity'=>$date_one_time_two_new_capacity);
			$where_date_one_time_two = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'11.15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_two,$where_date_one_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date one time three update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','2.00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_three = $this->Master_model->getRecords('venue_master');
		$date_one_time_three_new_capacity = $venue_date_one_time_three[0]['session_capacity'] + $rec['date_one_time_three'];
		
		if($date_one_time_three_new_capacity > 0){
		
			$update_date_one_time_three = array('session_capacity'=>$date_one_time_three_new_capacity);
			$where_date_one_time_three = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'2.00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_three,$where_date_one_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		
		// Date two time one update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','8.30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_one = $this->Master_model->getRecords('venue_master');
		$date_two_time_one_new_capacity = $venue_date_two_time_one[0]['session_capacity'] + $rec['date_two_time_one'];
		
		if($date_two_time_one_new_capacity > 0){
		
			$update_date_two_time_one = array('session_capacity'=>$date_two_time_one_new_capacity);
			$where_date_two_time_one = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'8.30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_one,$where_date_two_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date two time two update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','11.15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_two = $this->Master_model->getRecords('venue_master');
		$date_two_time_two_new_capacity = $venue_date_two_time_two[0]['session_capacity'] + $rec['date_two_time_two'];
		
		if($date_two_time_two_new_capacity > 0){
		
			$update_date_two_time_two = array('session_capacity'=>$date_two_time_two_new_capacity);
			$where_date_two_time_two = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'11.15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_two,$where_date_two_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date two time three update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','2.00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_three = $this->Master_model->getRecords('venue_master');
		$date_two_time_three_new_capacity = $venue_date_two_time_three[0]['session_capacity'] + $rec['date_two_time_three'];
		
		if($date_two_time_three_new_capacity > 0){
		
			$update_date_two_time_three = array('session_capacity'=>$date_two_time_three_new_capacity);
			$where_date_two_time_three = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'2.00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_three,$where_date_two_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date three time one update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','8.30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_one = $this->Master_model->getRecords('venue_master');
		$date_three_time_one_new_capacity = $venue_date_three_time_one[0]['session_capacity'] + $rec['date_three_time_one'];
		
		if($date_three_time_one_new_capacity > 0){
		
			$update_date_three_time_one = array('session_capacity'=>$date_three_time_one_new_capacity);
			$where_date_three_time_one = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'8.30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_one,$where_date_three_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		// Date three time two update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','11.15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_two = $this->Master_model->getRecords('venue_master');
		$date_three_time_two_new_capacity = $venue_date_three_time_two[0]['session_capacity'] + $rec['date_three_time_two'];
		
		if($date_three_time_two_new_capacity > 0){
		
			$update_date_three_time_two = array('session_capacity'=>$date_three_time_two_new_capacity);
			$where_date_three_time_two = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'11.15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_two,$where_date_three_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		// Date three time three update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','2.00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_three = $this->Master_model->getRecords('venue_master');
		$date_three_time_three_new_capacity = $venue_date_three_time_three[0]['session_capacity'] + $rec['date_three_time_three'];
		
		if($date_three_time_three_new_capacity > 0){
		
			$update_date_three_time_three = array('session_capacity'=>$date_three_time_three_new_capacity);
			$where_date_three_time_three = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'2.00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_three,$where_date_three_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		$update_nseit = array('record_update'=>1);
		$where_nseit = array('id'=>$rec['id']);
		$this->master_model->updateRecord('movement_venue',$update_nseit,$where_nseit); 
		
		echo $this->db->last_query();
		echo '<br/>';
		
		
	
	}
}
public function update_venue_9april21_movement_one(){ 
	
	$exam_date_arr = array('2021-05-02','2021-05-09','2021-05-16'); 
	
	$this->db->where('record_update',0);
	$this->db->limit(1);
	$sql = $this->Master_model->getRecords('movement_venue');
	
	//echo $this->db->last_query();
	//exit;
	
	foreach($sql as $rec){
		//echo $rec['venue_code'];exit;
		// Date one time one update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','8:30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_one = $this->Master_model->getRecords('venue_master');
		$date_one_time_one_new_capacity = $venue_date_one_time_one[0]['session_capacity'] + $rec['date_one_time_one'];
		
		if($date_one_time_one_new_capacity > 0){
		
			$update_date_one_time_one = array('session_capacity'=>$date_one_time_one_new_capacity);
			$where_date_one_time_one = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'8:30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_one,$where_date_one_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date one time two update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','11:15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_two = $this->Master_model->getRecords('venue_master');
		$date_one_time_two_new_capacity = $venue_date_one_time_two[0]['session_capacity'] + $rec['date_one_time_two'];
		
		if($date_one_time_two_new_capacity > 0){
		
			$update_date_one_time_two = array('session_capacity'=>$date_one_time_two_new_capacity);
			$where_date_one_time_two = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'11:15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_two,$where_date_one_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date one time three update
		$this->db->where('exam_date','2021-05-02');
		$this->db->where('session_time','2:00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_one_time_three = $this->Master_model->getRecords('venue_master');
		$date_one_time_three_new_capacity = $venue_date_one_time_three[0]['session_capacity'] + $rec['date_one_time_three'];
		
		if($date_one_time_three_new_capacity > 0){
		
			$update_date_one_time_three = array('session_capacity'=>$date_one_time_three_new_capacity);
			$where_date_one_time_three = array('exam_date'=>'2021-05-02','venue_code'=>$rec['venue_code'],'session_time'=>'2:00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_one_time_three,$where_date_one_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		
		// Date two time one update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','8:30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_one = $this->Master_model->getRecords('venue_master');
		$date_two_time_one_new_capacity = $venue_date_two_time_one[0]['session_capacity'] + $rec['date_two_time_one'];
		
		if($date_two_time_one_new_capacity > 0){
		
			$update_date_two_time_one = array('session_capacity'=>$date_two_time_one_new_capacity);
			$where_date_two_time_one = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'8:30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_one,$where_date_two_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date two time two update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','11:15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_two = $this->Master_model->getRecords('venue_master');
		$date_two_time_two_new_capacity = $venue_date_two_time_two[0]['session_capacity'] + $rec['date_two_time_two'];
		
		if($date_two_time_two_new_capacity > 0){
		
			$update_date_two_time_two = array('session_capacity'=>$date_two_time_two_new_capacity);
			$where_date_two_time_two = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'11:15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_two,$where_date_two_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date two time three update
		$this->db->where('exam_date','2021-05-09');
		$this->db->where('session_time','2:00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_two_time_three = $this->Master_model->getRecords('venue_master');
		$date_two_time_three_new_capacity = $venue_date_two_time_three[0]['session_capacity'] + $rec['date_two_time_three'];
		
		if($date_two_time_three_new_capacity > 0){
		
			$update_date_two_time_three = array('session_capacity'=>$date_two_time_three_new_capacity);
			$where_date_two_time_three = array('exam_date'=>'2021-05-09','venue_code'=>$rec['venue_code'],'session_time'=>'2:00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_two_time_three,$where_date_two_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		
		// Date three time one update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','8:30 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_one = $this->Master_model->getRecords('venue_master');
		$date_three_time_one_new_capacity = $venue_date_three_time_one[0]['session_capacity'] + $rec['date_three_time_one'];
		
		if($date_three_time_one_new_capacity > 0){
		
			$update_date_three_time_one = array('session_capacity'=>$date_three_time_one_new_capacity);
			$where_date_three_time_one = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'8:30 AM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_one,$where_date_three_time_one); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		// Date three time two update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','11:15 AM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_two = $this->Master_model->getRecords('venue_master');
		$date_three_time_two_new_capacity = $venue_date_three_time_two[0]['session_capacity'] + $rec['date_three_time_two'];
		
		if($date_three_time_two_new_capacity > 0){
		
			$update_date_three_time_two = array('session_capacity'=>$date_three_time_two_new_capacity);
			$where_date_three_time_two = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'11:15 AM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_two,$where_date_three_time_two); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		// Date three time three update
		$this->db->where('exam_date','2021-05-16');
		$this->db->where('session_time','2:00 PM');
		$this->db->where('venue_code',$rec['venue_code']);
		$venue_date_three_time_three = $this->Master_model->getRecords('venue_master');
		$date_three_time_three_new_capacity = $venue_date_three_time_three[0]['session_capacity'] + $rec['date_three_time_three'];
		
		if($date_three_time_three_new_capacity > 0){
		
			$update_date_three_time_three = array('session_capacity'=>$date_three_time_three_new_capacity);
			$where_date_three_time_three = array('exam_date'=>'2021-05-16','venue_code'=>$rec['venue_code'],'session_time'=>'2:00 PM');
			$this->master_model->updateRecord('venue_master',$update_date_three_time_three,$where_date_three_time_three); 
		
			echo $this->db->last_query();
			echo '<br/>';
		}
		
		$update_nseit = array('record_update'=>1);
		$where_nseit = array('id'=>$rec['id']);
		$this->master_model->updateRecord('movement_venue',$update_nseit,$where_nseit); 
		
		echo $this->db->last_query();
		echo '<br/>';
		
		
	
	}
}
	
public function cs2c_invoice_settelment(){
	
	
	
	$query="SELECT c.receipt_no FROM `payment_transaction` `a` LEFT JOIN `exam_invoice` `c` ON a.receipt_no = c.receipt_no WHERE c.created_on <= NOW() - INTERVAL 30 MINUTE AND a.pay_type = 2 AND a.status != 1 and c.invoice_no != '' and c.exam_period = '121' ORDER BY `c`.`created_on` DESC LIMIT 1 ";
	$record = $this->db->query($query);
	if($record->num_rows()){
		foreach ($record->result_array() as $c_row){
			
			//echo $c_row['receipt_no'];
			//exit; 
			
			
			
			$this->db->where('receipt_no',$c_row['receipt_no']);
			$this->db->where('transaction_no !=','');
			$this->db->where('invoice_no !=','');
			$this->db->where('invoice_image !=','');
			$this->db->where('exam_period',121);
			$invoice = $this->Master_model->getRecords('exam_invoice','','invoice_id,transaction_no,member_no,pay_txn_id');
			
			
			$this->db->where('receipt_no',$c_row['receipt_no']);
			$this->db->order_by("id", "desc"); 
			$this->db->limit(1); 
			$payment = $this->Master_model->getRecords('payment_transaction','','status,id,ref_id');
			
			$payment_update_remark = array('status'=>'1','transaction_no'=>$invoice[0]['transaction_no'],'transaction_details'=>'SUCCESS','callback'=>'c_S2S');
			$payment_where = array('id'=>$payment[0]['id'],'receipt_no'=>$c_row['receipt_no']);
			$this->master_model->updateRecord('payment_transaction',$payment_update_remark,$payment_where);
			
			$this->db->where('id',$payment[0]['ref_id']);
			$member_exam = $this->Master_model->getRecords('member_exam','','pay_status,id');
			
			if($member_exam[0]['pay_status'] == 0){
				$member_exam_update_remark = array('pay_status'=>'1');
				$member_exam_remark = array('id'=>$payment[0]['ref_id']);
				$this->master_model->updateRecord('member_exam',$member_exam_update_remark,$member_exam_remark); 
			}
			
			$invoice_insert_array = array('receipt_no'=>$c_row['receipt_no'],'member_exam_id'=>$payment[0]['ref_id']);
			$this->master_model->insertRecord('cs2c_invoice_settelment', $invoice_insert_array);
		}
	}
	
}
	
// settel subject missing admit card
public function subject_missing_member_settlement_new_121(){      
	$Settel = array(); 
	$missing = array();
	$capacity_full = array(); 
	
	//$arr_id = array(5557116,5558335);    
	$arr_id = array(5785778);      
	
	
	$size = sizeof($arr_id); 
	for($i=0;$i<$size;$i++){ 
		
		// chk how much row has seat number
		$this->db->where('remark',1);
		$this->db->where('exm_prd',121);
		$this->db->where('mem_exam_id',$arr_id[$i]);
		$sql1 = $this->master_model->getRecords('admit_card_details','','mem_exam_id,admitcard_id');
		
		if(count($sql1) > 0){
		echo '**';
		echo '<br/>';
			$this->db->where('mem_exam_id',$sql1[0]['mem_exam_id']);
			$this->db->group_by('sub_cd,mem_exam_id');
			$admit_card_details = $this->master_model->getRecords('admit_card_details');
			
			
			if(!empty($admit_card_details))
			{
				foreach($admit_card_details as $val)
				{
					if($val['pwd']!='')
					{
						$password =$val['pwd'];
					}	
				}
			}
			
			if($password == '')
			{
				$password = $this->random_password(); 
			}
			/********End of Password Code********/
			
			if(!empty($admit_card_details))
			{
				echo 'Total recode found in admit card table FIRST :<br>';
				echo '>>'. count($admit_card_details);
				//exit;
				
				foreach($admit_card_details as $val)
				{ 
					if($val['seat_identification']=='')
					{
						
						//get the  seat number from the seat allocation table 2
						$this->db->order_by("seat_no", "desc"); 
						$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));
						if(!empty($seat_allocation))
						{
							//check venue_capacity
							$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
								'session_time'=>$val['time'],
								'center_code'=>$val['center_code'],
								'exam_date'=>$val['exam_date']));  
				
							$venue_capacity=$venue_capacity[0]['session_capacity']+5;    
							if(!empty($venue_capacity))
							{
								if($seat_allocation[0]['seat_no']<=$venue_capacity)
								{
									$seat_no=$seat_allocation[0]['seat_no'];
									//inset new recode with append  seat number
									$seat_no=$seat_no+1;
									if($seat_no<10)
									{
										$seat_no='00'.$seat_no;
									}
									elseif($seat_no>10 && $seat_no<100)
									{
										$seat_no='0'.$seat_no;
									}
									$invoice_insert_array = array(
										'seat_no' => $seat_no,
										'exam_code' => $val['exm_cd'],
										'venue_code'=>$val['venueid'],
										'session'=>$val['time'],
										'center_code'=>$val['center_code'],
										'date'=>$val['exam_date'],
										'exam_period'=>$val['exm_prd'],
										'subject_code'=>$val['sub_cd'],
										'admit_card_id'=>$val['admitcard_id'],
										'createddate'=>date('Y-m-d H:i:s')
									);
									if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
									{	
						
										$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
										$update_info = array(
											'seat_identification' => $seat_no,
											'modified_on'=>$val['created_on'],
											'admitcard_image'=>$admitcard_image,
											'pwd' => $password, 
											'remark'=>1,
										);
										if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
										{
											echo '<br>Recode updated sucessfully in admit card<br>';
											echo 'Settel.'.$sql1[0]['mem_exam_id'];
											echo '<br/>';
											$Settel['settel'][] =$sql1[0]['mem_exam_id'];
											echo '<pre>';
											print_r($Settel);
											echo '</pre>';
										}else
										{
											echo '<br>Recode Not updated sucessfully in admit card<br>';
										}
									}
								}else
								{
									echo '<br>Capacity has been full<br>';
									
									//$capacity_full['capacity'][] =$sql1[0]['mem_exam_id'];
									$capacity_full['capacity'][] = $val['admitcard_id'];
									echo '<pre>';
									//print_r($capacity_full);
									echo '</pre>';
								}
							}else
							{
								echo '<br>Venue not present in venue master123<br>';
							}
						}else
						{
							$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
								'session_time'=>$val['time'],
								'center_code'=>$val['center_code'],
								'exam_date'=>$val['exam_date']));
								echo $this->db->last_query();
							if(!empty($venue_capacity))
							{
								if($seat_allocation[0]['seat_no']<=$venue_capacity[0]['session_capacity'])
								{
									//inset new recode with oo1
									$seat_no='001';
									$invoice_insert_array = array(
										'seat_no' => $seat_no,
										'exam_code' => $val['exm_cd'],
										'venue_code'=>$val['venueid'],
										'session'=>$val['time'],
										'center_code'=>$val['center_code'],
										'date'=>$val['exam_date'],
										'exam_period'=>$val['exm_prd'],
										'subject_code'=>$val['sub_cd'],
										'admit_card_id'=>$val['admitcard_id'],
										'createddate'=>date('Y-m-d H:i:s')
									);
									if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
									{
										echo 'Seat alloation primary key :<br>';
										echo $inser_id;
										$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
										$update_info = array(
											'seat_identification' => $seat_no,
											'modified_on'=>$val['created_on'],
											'admitcard_image'=>$admitcard_image,
											'pwd' => $password, 
											'remark'=>1,
										);
										if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
										{
											echo 'Recode updated sucessfully in admit card<br>';
											echo 'Settel.'.$sql1[0]['mem_exam_id'];
											echo '<br/>';
											$Settel['settel'][] =$sql1[0]['mem_exam_id'];
											echo '<pre>';
											print_r($Settel);
											echo '</pre>';
										}else
										{
											echo 'Recode Not updated sucessfully in admit card<br>';
										}
									}
								}else
								{
									echo '<br>Capacity has been full-2<br>';
									//$capacity_full['capacity'][] =$sql1[0]['mem_exam_id'];
									$capacity_full['capacity'][] = $val['admitcard_id'];
									echo '<pre>';
									//print_r($capacity_full);
									echo '</pre>';
								}
							}else
							{
								echo '<br>Venue not present in venue master234<br>';
							}
						}}
					}
				}
		}else{
			echo 'Invalid number '.$arr_id[$i];
			echo '<br/>';
		}
		
	} // end of for
	
	$capacity = array_map("unserialize", array_unique(array_map("serialize", $capacity_full['capacity'])));
	echo '<pre>';
	print_r($capacity);
	echo '</pre>';
	echo '<br/>';
	echo $str = implode(",",$capacity);
}
	
// settel  admit card whoes all subject not update	
public function member_settlement_new_121(){   
	$Settel = array();  
	$missing = array();
	$capacity_full = array();
	
	$arr_id = array(5546669);       
	
	$size = sizeof($arr_id);
	for($i=0;$i<$size;$i++){ 
		
		// chk how much row has seat number
		$this->db->where('remark',1);
		$this->db->where('mem_exam_id',$arr_id[$i]);
		$sql1 = $this->master_model->getRecords('admit_card_details');
		
		
		if(count($sql1) <= 0){
			
			$this->db->where('mem_exam_id',$arr_id[$i]);
			$this->db->group_by('sub_cd,mem_exam_id');
			$admit_card_details = $this->master_model->getRecords('admit_card_details');
			
			
			if(!empty($admit_card_details))
			{
				foreach($admit_card_details as $val)
				{
					if($val['pwd']!='')
					{
						$password =$val['pwd'];
					}	
				}
			}
			
			if($password == '')
			{
				$password = $this->random_password(); 
			}
			/********End of Password Code********/
			
			if(!empty($admit_card_details))
			{
				echo 'Total recode found in admit card table FIRST :<br>';
				echo count($admit_card_details);
				//exit;
				
				foreach($admit_card_details as $val)
				{ 
					if($val['seat_identification']=='')
					{
						//get the  seat number from the seat allocation table 2
						$this->db->order_by("seat_no", "desc"); 
						$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));
						if(!empty($seat_allocation))
						{
							//check venue_capacity
							$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
								'session_time'=>$val['time'],
								'center_code'=>$val['center_code'],
								'exam_date'=>$val['exam_date'])); 
				
							$venue_capacity=$venue_capacity[0]['session_capacity']+100;  
							if(!empty($venue_capacity))
							{
								if($seat_allocation[0]['seat_no']<=$venue_capacity)
								{
									$seat_no=$seat_allocation[0]['seat_no'];
									//inset new recode with append  seat number
									$seat_no=$seat_no+1;
									if($seat_no<10)
									{
										$seat_no='00'.$seat_no;
									}
									elseif($seat_no>10 && $seat_no<100)
									{
										$seat_no='0'.$seat_no;
									}
									$invoice_insert_array = array(
										'seat_no' => $seat_no,
										'exam_code' => $val['exm_cd'],
										'venue_code'=>$val['venueid'],
										'session'=>$val['time'],
										'center_code'=>$val['center_code'],
										'date'=>$val['exam_date'],
										'exam_period'=>$val['exm_prd'],
										'subject_code'=>$val['sub_cd'],
										'admit_card_id'=>$val['admitcard_id'],
										'createddate'=>date('Y-m-d H:i:s')
									);
									if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
									{	
						
										$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
										$update_info = array(
											'seat_identification' => $seat_no,
											'modified_on'=>$val['created_on'],
											'admitcard_image'=>$admitcard_image,
											'pwd' => $password, 
											'remark'=>1,
										);
										if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
										{
											echo '<br>Recode updated sucessfully in admit card<br>';
											echo 'Settel.'.$arr_id[$i];
											echo '<br/>';
											$Settel['settel'][] =$arr_id[$i];
											echo '<pre>';
											print_r($Settel);
											echo '</pre>';
										}else
										{
											echo '<br>Recode Not updated sucessfully in admit card<br>';
										}
									}
								}else
								{
									echo '<br>Capacity has been full<br>';
									
									$capacity_full['capacity'][] =$arr_id[$i];
									echo '<pre>';
									//print_r($capacity_full);
									echo '</pre>';
								}
							}else
							{
								echo '<br>Venue not present in venue master123<br>';
							}
						}else
						{
							$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
								'session_time'=>$val['time'],
								'center_code'=>$val['center_code'],
								'exam_date'=>$val['exam_date']));
								echo $this->db->last_query();
							if(!empty($venue_capacity))
							{
								if($seat_allocation[0]['seat_no']<=$venue_capacity[0]['session_capacity'])
								{
									//inset new recode with oo1
									$seat_no='001';
									$invoice_insert_array = array(
										'seat_no' => $seat_no,
										'exam_code' => $val['exm_cd'],
										'venue_code'=>$val['venueid'],
										'session'=>$val['time'],
										'center_code'=>$val['center_code'],
										'date'=>$val['exam_date'],
										'exam_period'=>$val['exm_prd'],
										'subject_code'=>$val['sub_cd'],
										'admit_card_id'=>$val['admitcard_id'],
										'createddate'=>date('Y-m-d H:i:s')
									);
									if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
									{
										echo 'Seat alloation primary key :<br>';
										echo $inser_id;
										$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
										$update_info = array(
											'seat_identification' => $seat_no,
											'modified_on'=>$val['created_on'],
											'admitcard_image'=>$admitcard_image,
											'pwd' => $password, 
											'remark'=>1,
										);
										if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
										{
											echo 'Recode updated sucessfully in admit card<br>';
											echo 'Settel.'.$arr_id[$i];
											echo '<br/>';
											$Settel['settel'][] =$arr_id[$i];
											echo '<pre>';
											print_r($Settel);
											echo '</pre>';
										}else
										{
											echo 'Recode Not updated sucessfully in admit card<br>';
										}
									}
								}else
								{
									echo '<br>Capacity has been full<br>';
									$capacity_full['capacity'][] =$arr_id[$i];
									echo '<pre>';
									//print_r($capacity_full);
									echo '</pre>';
								}
							}else
							{
								echo '<br>Venue not present in venue master234<br>';
							}
						}}
					}
				}
		} 
		else{
			
			echo 'Record already settle '.$arr_id[$i];
			echo '<br/>'; 
			
			
		}
	} // end of for
	
	$capacity = array_map("unserialize", array_unique(array_map("serialize", $capacity_full['capacity'])));
	echo '<pre>';
	print_r($capacity);
	echo '</pre>';
	echo '<br/>';
	echo $str = implode(",",$capacity);
}
// settel  admit card whoes all subject not update	
public function member_settlement_new_121_11april(){  
	$Settel = array();  
	$missing = array();
	$capacity_full = array();
	
	$arr_id = array(5539155);      
	
	$size = sizeof($arr_id);
	for($i=0;$i<$size;$i++){ 
		
		// chk how much row has seat number
		$this->db->where('remark',1);
		$this->db->where('mem_exam_id',$arr_id[$i]);
		$sql1 = $this->master_model->getRecords('admit_card_details');
		
		
		if(count($sql1) <= 0){
			
			$this->db->where('mem_exam_id',$arr_id[$i]);
			$this->db->group_by('sub_cd,mem_exam_id');
			$admit_card_details = $this->master_model->getRecords('admit_card_details');
			
			
			if(!empty($admit_card_details))
			{
				foreach($admit_card_details as $val)
				{
					if($val['pwd']!='')
					{
						$password =$val['pwd'];
					}	
				}
			}
			
			if($password == '')
			{
				$password = $this->random_password(); 
			}
			/********End of Password Code********/
			
			if(!empty($admit_card_details))
			{
				echo 'Total recode found in admit card table FIRST :<br>';
				echo count($admit_card_details);
				//exit;
				
				foreach($admit_card_details as $val)
				{ 
					if($val['seat_identification']=='')
					{
						//get the  seat number from the seat allocation table 2
						$this->db->order_by("seat_no", "desc"); 
						$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));
						if(!empty($seat_allocation))
						{
							//check venue_capacity
							$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
								'session_time'=>$val['time'],
								'center_code'=>$val['center_code'],
								'exam_date'=>$val['exam_date'])); 
				
							$venue_capacity=$venue_capacity[0]['session_capacity'];  
							if(!empty($venue_capacity))
							{
								if($seat_allocation[0]['seat_no']<=$venue_capacity)
								{
									$seat_no=$seat_allocation[0]['seat_no'];
									//inset new recode with append  seat number
									$seat_no=$seat_no+1;
									if($seat_no<10)
									{
										$seat_no='00'.$seat_no;
									}
									elseif($seat_no>10 && $seat_no<100)
									{
										$seat_no='0'.$seat_no;
									}
									$invoice_insert_array = array(
										'seat_no' => $seat_no,
										'exam_code' => $val['exm_cd'],
										'venue_code'=>$val['venueid'],
										'session'=>$val['time'],
										'center_code'=>$val['center_code'],
										'date'=>$val['exam_date'],
										'exam_period'=>$val['exm_prd'],
										'subject_code'=>$val['sub_cd'],
										'admit_card_id'=>$val['admitcard_id'],
										'createddate'=>date('Y-m-d H:i:s')
									);
									if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
									{	
						
										$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
										$update_info = array(
											'seat_identification' => $seat_no,
											'modified_on'=>$val['created_on'],
											'admitcard_image'=>$admitcard_image,
											'pwd' => $password, 
											'remark'=>1,
										);
										if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
										{
											echo '<br>Recode updated sucessfully in admit card<br>';
											echo 'Settel.'.$arr_id[$i];
											echo '<br/>';
											$Settel['settel'][] =$arr_id[$i];
											echo '<pre>';
											print_r($Settel);
											echo '</pre>';
										}else
										{
											echo '<br>Recode Not updated sucessfully in admit card<br>';
										}
									}
								}else
								{
									echo '<br>Capacity has been full<br>';
									
									$capacity_full['capacity'][] =$arr_id[$i];
									echo '<pre>';
									//print_r($capacity_full);
									echo '</pre>';
								}
							}else
							{
								echo '<br>Venue not present in venue master123<br>';
							}
						}else
						{
							$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
								'session_time'=>$val['time'],
								'center_code'=>$val['center_code'],
								'exam_date'=>$val['exam_date']));
								echo $this->db->last_query();
							if(!empty($venue_capacity))
							{
								if($seat_allocation[0]['seat_no']<=$venue_capacity[0]['session_capacity'])
								{
									//inset new recode with oo1
									$seat_no='001';
									$invoice_insert_array = array(
										'seat_no' => $seat_no,
										'exam_code' => $val['exm_cd'],
										'venue_code'=>$val['venueid'],
										'session'=>$val['time'],
										'center_code'=>$val['center_code'],
										'date'=>$val['exam_date'],
										'exam_period'=>$val['exm_prd'],
										'subject_code'=>$val['sub_cd'],
										'admit_card_id'=>$val['admitcard_id'],
										'createddate'=>date('Y-m-d H:i:s')
									);
									if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
									{
										echo 'Seat alloation primary key :<br>';
										echo $inser_id;
										$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
										$update_info = array(
											'seat_identification' => $seat_no,
											'modified_on'=>$val['created_on'],
											'admitcard_image'=>$admitcard_image,
											'pwd' => $password, 
											'remark'=>1,
										);
										if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
										{
											echo 'Recode updated sucessfully in admit card<br>';
											echo 'Settel.'.$arr_id[$i];
											echo '<br/>';
											$Settel['settel'][] =$arr_id[$i];
											echo '<pre>';
											print_r($Settel);
											echo '</pre>';
										}else
										{
											echo 'Recode Not updated sucessfully in admit card<br>';
										}
									}
								}else
								{
									echo '<br>Capacity has been full<br>';
									$capacity_full['capacity'][] =$arr_id[$i];
									echo '<pre>';
									//print_r($capacity_full);
									echo '</pre>';
								}
							}else
							{
								echo '<br>Venue not present in venue master234<br>';
							}
						}}
					}
				}
		} 
		else{
			
			echo 'Record already settle '.$arr_id[$i];
			echo '<br/>'; 
			
			
		}
	} // end of for
	
	$capacity = array_map("unserialize", array_unique(array_map("serialize", $capacity_full['capacity'])));
	echo '<pre>';
	print_r($capacity);
	echo '</pre>';
	echo '<br/>';
	echo $str = implode(",",$capacity);
}
//settel payment transaction tabl(invoice generate and payment transaction not update)	
public function jaiib_121_settelment_payment(){ 
		$member_array = array(4876333,4876336,4876341,4876346,4876348,4876350,4876352,4876354,4876379,4876388,4876390,4876393,4876402,4876409,4876417,4876429,4876431,4876473,4876484,4876522,4876551,4876553,4876683,4876696,4876704,4876708,4876727,4876730,4876731,4876732,4876733,4876734,4876735,4876737,4876738,4876739,4876743,4876752,4876755,4876756,4876758,4876759,4876760,4876761,4876762,4876768,4876769,4876772,4876793,4876810,4876822,4876842,4876858,4876867,4876921,4876930,4876931,4876937,4876940,4876950,4876964,4876991,4877011,4877025,4877031,4877066,4877086,4877144,4877155,4877163,4877165,4877167,4877168,4877171,4877173,4877176,4877178,4877188,4877231,4877314,4877320,4877326,4877331,4877357,4877366,4877368,4877401,4877414,4877426,4877432,4877433,4877441,4877459,4877470,4877503,4877508,4877512,4877520,4877527,4877529,4877536,4877551,4877555,4877556,4877558,4877559,4877560,4877562,4877563,4877565,4877568,4877571,4877572,4877576);
		$size = sizeof($member_array);
		for($i=0;$i<$size;$i++){
			$this->db->where('pay_txn_id',$member_array[$i]);
			$this->db->where('transaction_no !=','');
			$this->db->where('invoice_no !=','');
			$this->db->where('invoice_image !=','');
			$invoice = $this->Master_model->getRecords('exam_invoice','','invoice_id,transaction_no,member_no');
			
			if(count($invoice) > 0){
				$this->db->where('id',$member_array[$i]);
				$payment = $this->Master_model->getRecords('payment_transaction','','status,id');
				
				if($payment[0]['status'] != 3){
					
					$payment_update_remark = array('status'=>'1','transaction_no'=>$invoice[0]['transaction_no']);
					$payment_admit_remark = array('id'=>$member_array[$i]);
					$this->master_model->updateRecord('payment_transaction',$payment_update_remark,$payment_admit_remark); 
				}else{
					echo 'invalid number : '.$member_array[$i];	
					echo '<br/>';
				}
			}
			
			
		}
	}
	
// update member exam (payment present but member exam not update)	
public function jaiib_121_settelment_member_exam(){   
		$member_array = array(5532854,5532858,5532866,5532881,5532902,5532920,5532931,5532939,5532969,5532987,5533039,5533050,5533056,5533057,5533060,5533061,5533063,5533066,5533072,5533068,5533079,5533115,5533183,5533189,5533191,5533192,5533216,5533224,5533225,5533257,5533266,5533277,5533281,5533282,5533288,5533307,5533314,5533339,5533342,5533347,5533358,5533363,5533364,5533370,5533377,5533382,5533383,5533379,5533386,5533385,5533389,5533388,5533393,5533399,5533397,5533401,5533422);
		$size = sizeof($member_array);
		for($i=0;$i<$size;$i++){
			$this->db->where('ref_id',$member_array[$i]);
			$this->db->where('status',1);
			$invoice = $this->Master_model->getRecords('payment_transaction','','id,ref_id');
			
			if($member_array[$i]!= 0){
				
				if(count($invoice) > 0){
					$this->db->where('id',$member_array[$i]);
					$payment = $this->Master_model->getRecords('member_exam','','pay_status,id');
					
					if($payment[0]['pay_status'] == 0){
						
						$payment_update_remark = array('pay_status'=>'1');
						$payment_admit_remark = array('id'=>$member_array[$i]);
						$this->master_model->updateRecord('member_exam',$payment_update_remark,$payment_admit_remark); 
					}else{
						echo 'invalid number : '.$member_array[$i];	
						echo '<br/>';
					}
				}
			}
			
			
			
			
		}
	}
	
	
	public function res_exam(){
		$member_arr = array('510236045','510188308','510455021','510397409','510060242','510119444','510468149','510037815','510133232','300037214','510460547','510233821','510339644','510476597','500176513','500047635','510450872','510198752','510465260','510239407','510032512','510454185','510144894','510448028','510419958','510292336','510447229','510170496','510119198','510412403','510421236','510325121','510433257','510012094','510434771','510079340','510386396','500025012','510371522','510205982','510189502','510415828','500109453','510266188','510448614','510366836','510348249','510316323','510028382','510463480','510451557','500200507','510326268','510426721','510426331','510331868','510290749','510252169','510461475','510479267','510442794','500080159','510328196','500184614','510421046','510346464','510267903','510436624','510369060','510036196','510259920','510422642','510376152','510473577','510036495','510286160','510370762','500182084','510271677','510466633','510203788','510453050','510445337','510393229','510366586','510431654','510423523','510410844','510250212','510146560','510315033','510195301','510318034','510448149','510301658','500051303','510432063','510432443','510266425','510107616','510394302','700022544','510329745','510462857','510019864','510220527','500211137','510063672','510409778','510293199','510421425','510311361','510388825','500185010','510426533','510426082','510476491','510358831','510396643','510137606','510477511','510366259','510251671','510144143','510182114','510169420','510431509','510435647','510385617','510262157','510446167','500133622','510324665','510214850','510464715','510465797','510294228','510391558','510458876','510015915','510284772','500147163','510159510','510320287','510458920','510401617','510302407','510244365','510148809','500186013','510456120','510344671','510315200','510413504','510169292','510412085','510322143','510169309','510388870','510109750','500115232','510439084','510434096','510261919','510473314','510478261','510452647','510185799','500110484','510481163','510402162','510404935','500182699','510396789','510201171','510401592','510392129','510185585','510098537','510016717','500040206','510456370','510115026','510459531','510477348','510315943','510214176','510306849','510418412','510013515','500053578','510381559','500167023','510394003','510257743','510443269','510370523','510426162','510381752','510463268','510469606','510117729','510453133','500180380','510339752','500188337','510418171','510211497','510203784','510224439','510343719','510381516','510288363','510391841','510347817','510249753','510183533','510356643','510458862','510467754','510286534','510458164','510478222','500132578','510443536','510445202','510348575','510389412','510477167','510192921','510385075','510467162','510092880');
		
		$size = sizeof($member_arr);
		for($i=0;$i<$size;$i++){
			
			$this->db->where('mem_mem_no',$member_arr[$i]);
			$sql = $this->Master_model->getRecords('reschedule_220','','covid_certificate');
			
			rename("./uploads/reschudule/".$sql[0]['covid_certificate'],"./uploads/reschudule_new/".$sql[0]['covid_certificate']);
			
			
		}
	}
	
	public function caiib_center_change(){
		
		$this->load->dbutil(); 
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "caiib_center_change.csv";
		$query = "SELECT exm_cd, exm_prd, mem_type, g_1, mem_mem_no, mam_nam_1, email, mobile, center_code, center_name, sub_cd, sub_dsc, venueid, venue_name, CONCAT(`venueadd1`,', ',`venueadd2`,', ',`venueadd3`,', ',`venueadd4`,', ',`venueadd5`) AS VENUE_ADDRESS, venpin, seat_identification, pwd, exam_date, time, mode, m_1, scribe_flag, vendor_code FROM `admit_card_details`, `member_registration` WHERE `exm_cd` IN (".$this->config->item('examCodeCaiib').",62,63,64,65,66,67,".$this->config->item('examCodeCaiibElective68').",69,70,".$this->config->item('examCodeCaiibElective71').",72) AND exm_prd = 220 AND remark = 1 AND `created_on` > '2020-12-08 00:00:00' AND `app_update` = '1' AND member_registration.regnumber = admit_card_details.mem_mem_no AND isactive = '1' ";
		$result1 = $this->db->query($query);
		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
		//$this->db->empty_table('center_stat'); 
		force_download($filename, $data);
	}
	
	
	public function admit_update_42_220(){  
	
	/*/usr/local/bin/php /home/supp0rttest/public_html/index.php dwnletter_122 admit_update_42_220*/  
		
		$migration_date = date('Y-m-d H:i:s');
		
		//$this->db->limit(1500);
		//$marr = array('700026053','700026059');
		//$this->db->where_in('mem_mem_no',$marr);
		//$this->db->limit(1); 
		$temp_admit_card_data = $this->Master_model->getRecords('admitcard_info_dbf_120_bulk',array('exm_cd'=>'42','admitcard_id'=>'0'));
		if(count($temp_admit_card_data) > 0){
			foreach($temp_admit_card_data as $admit_row){
				
				// Member Exam Applicatoin Update
				$update_member_app = array('exam_period'=>220,
											'exam_center_code'=>$admit_row['center_code'],
											'created_on'=>$migration_date
										);
				$where_member_app = array('regnumber'=>$admit_row['mem_mem_no'],
											'exam_code'=>'42',
											'exam_period'=>'220',
											'pay_status'=>'1'
										);
				$this->master_model->updateRecord('member_exam',$update_member_app,$where_member_app);
				// Admit Card Mail Table Update
				
				$this->db->where('center_code',$admit_row['center_code']);
				$this->db->where('exam_name',42);
				$getcenter  = $this->Master_model->getRecords('center_master','','center_name');
				$admit_update_data = array('center_code'=>$admit_row['center_code'],
											'center_name'=>$getcenter[0]['center_name'],
											'exm_prd'=>220,
											'venueid'=>$admit_row['venueid'],
											'venue_name'=>$admit_row['venueadd1'],
											'venueadd1'=>$admit_row['venueadd2'],
											'venueadd2'=>$admit_row['venueadd2'],
											'venueadd3'=>$admit_row['venueadd3'],
											'venueadd4'=>$admit_row['venueadd4'],
											'venueadd5'=>$admit_row['venueadd5'],
											'venpin'=>$admit_row['venpin'],
											'exam_date'=>$admit_row['date'],
											'time'=>$admit_row['time'],
											'created_on'=>$migration_date,
									);
				$where_admit_card_arr = array('mem_mem_no'=>$admit_row['mem_mem_no'],
												'exm_cd'=>'42',
												'exm_prd'=>'220',
												'sub_cd'=>$admit_row['sub_cd'],
												'remark'=>'1'
											);	
				$this->master_model->updateRecord('admit_card_details',$admit_update_data,$where_admit_card_arr);
				// Get admitcard_id from orginal admit card tabele
				$admitcard_id = '';
				$where_primary_key = array('mem_mem_no'=>$admit_row['mem_mem_no'],
									'exm_cd'=>'42',
									'exm_prd'=>'220',
									'sub_cd'=>$admit_row['sub_cd'],
									'remark'=>'1'
								);
				$select_col = 'admitcard_id';
				$get_primary_key = $this->Master_model->getRecords('admit_card_details',$where_primary_key,$select_col);
				$admitcard_id = $get_primary_key[0]['admitcard_id'];
				// Seat allocation update
				$set_update_data = array('venue_code'=>$admit_row['venueid'],
											'session'=>$admit_row['time'],
											'center_code'=>$admit_row['center_code'],
											'date'=>$admit_row['date'],
											'exam_period'=>220,
											'createddate'=>$migration_date
										);
				$where_set_all = array('admit_card_id'=>$admitcard_id,
										'exam_code'=>'42',
										'exam_period'=>'220'
										);
				$this->master_model->updateRecord('seat_allocation',$set_update_data,$where_set_all);
				
				// Admit Card Sample table update
				$admit_update_remark = array('admitcard_id'=>'1');
				$where_admit_remark = array('mem_mem_no'=>$admit_row['mem_mem_no'],
												'exm_cd'=>'42',
												//'exm_prd'=>'220',
												'sub_cd'=>$admit_row['sub_cd'],
												'admitcard_id'=>'0'
											);
				$this->master_model->updateRecord('admitcard_info_dbf_120_bulk',$admit_update_remark,$where_admit_remark); 
				
				echo $admit_row['mem_mem_no'].'==';
			}
		}
	}
	
	public function jkcenter220(){
		$member_arr = array('500198925','500200509','500208639','500208714','500210314','500211408','500211938','510014516','510015602','510042565','510042575','510045573','510054348','510054352','510081526','510089112','510094777','510104014','510104476','510109042','510117345','510121345','510139620','510144122','510145120','510157775','510167882','510201000','510207905','510208891','510225896','510231080','510236112','510237962','510249403','510250341','510252319','510299527','510299898','510300581','510308242','510308470','510313561','510316355','510317442','510323888','510329327','510330321','510340105','510343172','510347955','510349100','510352775','510361185','510365910','510366102','510366517','510372689','510372924','510373155','510373686','510373929','510374287','510377978','510380695','510381666','510381682','510382801','510388245','510389603','510389608','510390508','510391379','510391382','510392092','510392165','510394036','510394215','510394924','510395495','510397646','510398389','510399211','510400287','510401804','510402162','510404394','510404449','510404900','510409068','510409081','510412111','510413189','510414292','510415172','510417076','510419043','510420433','510420847','510421996','510422044','510422152','510422785','510422815','510423798','510423828','510424807','510425636','510425865','510427606','510428592','510431743','510431756','510431861','510431909','510431955','510431971','510432782','510432788','510433614','510433825','510435425','510437270','510437621','510438032','510438061','510438070','510439084','510439120','510440942','510441002','510441443','510441844','510442583','510442938','510442997','510443056','510443141','510443172','510443465','510444488','510444588','510444599','510444627','510444657','510445425','510445483','510445538','510446431','510446533','510447082','510447324','510447380','510447855','510447977','510447987','510448073','510448074','510448168','510448184','510448204','510448638','510448990','510347857');
		$si = sizeof($member_arr);
		for($i=0;$i<$si;$i++){
			
			$this->db->where('regnumber',$member_arr[$i]);
			$sql = $this->Master_model->getRecords('member_registration','','address1,address2,address3,address4,pincode');
			
			$update_arr_memberexam = array(
										'mem_adr_1'=>$sql[0]['address1'],
										'mem_adr_2'=>$sql[0]['address2'],
										'mem_adr_3'=>$sql[0]['address3'],
										'mem_adr_4'=>$sql[0]['address4'],
										'mem_pin_cd'=>$sql[0]['pincode'],
									);
									
			$this->master_model->updateRecord('admit_card_details',$update_arr_memberexam,array('mem_mem_no'=>$member_arr[$i],'remark'=>'1','exm_cd'=>21,'exm_prd'=>220,'mem_exam_id'=>0));
			
			echo $i.'>>'.$this->db->last_query();
			echo '<br/>'; 
			
		} 
	} 
	
	public function hhh(){
		echo '<pre>';
		print_r($_SERVER);
	}
	
	public function jaiib_cnt(){
		
		$this->db->distinct('mem_mem_no');
		$this->db->where('app_update','1');
		$reg_done= $this->master_model->getRecords('admit_card_details','','mem_mem_no');
		
		echo 'Total registration done '.count($reg_done);
		echo '<br/>';
		echo '<br/>';
		
		$this->db->distinct('mem_mem_no');
		$this->db->where('remark',0);
		$pending_mem= $this->master_model->getRecords('admit_card_details_jaiib','','mem_mem_no');
		
		echo 'Total pending member '.count($pending_mem);
		echo '<br/>';
		echo '<br/>';
		
		$this->db->distinct('mem_mem_no');
		$this->db->where('remark',4);
		$migrate_mem= $this->master_model->getRecords('admit_card_details_jaiib','','mem_mem_no');
		
		echo 'Total migrated member '.count($migrate_mem);
		echo '<br/>';
		echo '<br/>';
		
	}
	
	public function exampdf()
	{	
		
		$order_no = $this->uri->segment(3);	
		$this->db->join('amp_candidates','amp_candidates.id=amp_payment_transaction.ref_id');
		$user_info_details=$this->master_model->getRecords('amp_payment_transaction',array('receipt_no'=>$order_no));
		
		if(empty($user_info_details)){
			redirect(base_url().'amp/self');
		}
			
		//echo '<pre>';print_r($user_info_details);die;
		if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
		$imagePath = base_url().'uploads/amp/photograph/'.$user_info_details[0]['photograph'];
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
		<tr><td style="text-align:right"><img src="'.$imagePath.'" height="100" width="100" /></td></tr>
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
				
				</tbody>
			</table>
			
			</td>
		</tr>
	</tbody>
</table>';
	//echo $html;die;
			//this the the PDF filename that user will get to download
			$pdfFilePath = 'exam'.$user_info_details[0]['member_regnumber'].'.pdf';
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
			$pdf->Output($pdfFilePath,'D');
	}
	
	public function chk(){
		$pa_flag = 1;
		
		echo $system_date = date("H:i:s");
		if($system_date > '23:50:00' && $system_date < '23:59:59'){ 
			echo 'one';
			echo '<br/>';
			$pa_flag = 0;
		}
		
		if($system_date > '00:00:01' && $system_date < '00:10:00'){
			echo 'two'; 
			echo '<br/>';
			$pa_flag = 0;
		}
		
		if($pa_flag == 1){ 
			echo 'in';
		}else{
			echo 'out';
		}	
	}
	
	public function recovery_dashboard(){
		$this->db->select_sum('pay_amount');
		$this->db->where('pay_status','1');
		$sql = $this->master_model->getRecords('exam_invoice_without_gst');
		
		$total_amount = 332208.00;
		echo 'Total amount to be recoverd : 332208.00';
		echo '<br/>';
		echo 'Total amount collected till date :'. $sql[0]['pay_amount'];
		echo '<br/>';
		$pending_amt = $total_amount - $sql[0]['pay_amount'];
		echo 'Pending amount till date :'.$pending_amt;
		echo '<br/>';
		echo '<br/>';
		echo '<br/>';
		
		echo 'Total candidate : 1511';
		echo '<br/>';
		$total_candidate = 1511;
		$this->db->where('pay_status','1');
		$total_pay = $this->master_model->getRecords('exam_invoice_without_gst','','invoice_id');
		echo 'Total candidate pay fee till date : '.count($total_pay);
		$remaining_candidate = $total_candidate - count($total_pay);
		echo '<br/>';
		echo 'Remaining candidate till date :'.$remaining_candidate;
		echo '<br/>';
		echo '<br/>';
		echo '************************';
		
		echo '<br/>';
		echo '<strong>5 july candidate</strong>';
		echo '<br/>';
		echo '<br/>';
		
		$member_array = array(510465562,510295016,510430791,510160400,500027891,510428453,510211721,510140463,510370777,500039942,510150441,510051226,510145994,510172488,510356488,500152809,510409035,510271812,510430635,510419279,801497177,510373999,510272542,500117650,510332378,510165934,510449856,510296872,500076152,510213869,510215404,510471003,510113708,510191303,510414121,510325450,510073489,510388756,510103905,7457938,510441018,510136637,510130519,510232554,510160407,510363148,510175577,510182600,500113563,510292716,500077511,510345388,510030706,801414117,500161443,510089584,510407125,510383562,510251906,500167188,510043011,510450338,500214273,510247915,801497316,510166877,510102184,801497355,400011669,510328509,510339734,510290694,510130658,510287876,7581349,510359258,500071490,801497401,500167169,801469543,801029530,510153916,500199413,801497440,510169735,510077455,510295263,801497546,510380972,510357392,510202393,801497610,801497618,510429545,510428357,510427155,510429166,510250871,510279789,801497651,500016433,510311941,500173310,510177689,510138804,510298872,510009609,500052468,500181236,801497748,510185407,500190336,510239751,510370198,510305748,500019109,510210046,801497927,500191507,510138583,200057152,500187034,801498012,510413960,500045636,510079887,510411214,510136339,510041180,510071123,500134044,510444113,510394299,510398900,510221892,510353283,510203322,510238823,801498135,510236338,510426695,510275878,510432783,510014387,510340185,510417213,510085129,510291739,801498192,510128472,510223988,801498312,500205312,801498364,510099694,500167779,510242024,801443785,510158657,510055381,510148329,801498586,510369782,510104258,510445273,510152244,510163086,510288512,510221902,510238912,510373064,801498661,510213928,500118406,510335450,510280714,510116401,500188240,801498829,510101539,510422567,510018713,500215118,510354696,510471894,500180924,500186733,510145551,500168547,510430339,510452820,100003079,510301125,500018769,6126019,801499080,510395421,510178186,100092319,500177865,510371364,510136215,510220971,510284752,510063563,510144201,500178208,510398631,510163492,100041660,510206814,510432409,801499271,7372222,3814863,510390663,510195236,500049421,510030839,510234718,510189851,510312708,100080766,500100569,100080775,510361483,510336009,510471904,510046539,500179105,510219731,500158196,510310893,510138309,801499429,510027753,500032464,510405784,510284943,801499514,510151529,510454418,500063557,510042188,500151939,510360441,801499562,500152075,510128790,510152658,510171950,801011619,500183845,801499635,500098409,510194204,500049278,500087177,510149254,500185858,510157599,510031331,510250490,510017914,400005803,510355938,500033632,500021825,510377252,100080194,510193454,510014535,500113042,500053497,510358137,500172724,510303310,500038940,510395438,500043087,510338389,510013154,510136702,6822921,510332400,510273823,510314897,510394540,510255121,510081816,510245975,7340873,510344415,510225156,200086092,510050102,500002985,510041663,510163535,510434272,510106413,801500083,500051000,7131292,510359396,510416345,510288633,801247441,510328869,500107924,510409636,510160858,510385775,500120376,510302869,500131612,510289964,510285412,510389330,500054792,510250652,510470260,510003879,801299655,510345404,510140370,7492015,500129954,510351801,510355535,510092859,510300264,500172412,510160061,510335333,510299126,510446014,500107684,500169456,510469832,510450940,510471270,510199431,510126279,510107766,7423985,510227725,510140375,500083007,7658266,510101529,500191400,500094223,500032086,510367521,510205553,510355099,510276727,100006802,500182573,510372707,500027645,510462653,510359336,510160572,510137124,510038884,510233526,510096822,801415346,500001800,510141467,100091438,500161340,510223000,500180797,510470986,510186496,510279453,7542270,510183876,801501160,500000231,500065268,510029568,510073931,500145105,500202052,510467996,510450192,510178967,510102722,510281667,510185082,510451919,510055270,510332741,801501361,510013407,510290320,510357656,510365427,510389179,801501389,510408158,510388705,510437366,500013068,510049105,500087185,510178560,510406750,510471035,510358995,801501454,510075424,510207512,510431076,510188793,100091652,510068442,510299164,510254708,400068401,500190782,510291874,200053369,300037232,510059021,510457706,510335298,500140729,510429063,510388848,510328088,500103433,510422344,500035336,510357141,510424248,510401334,510208643,510166074,510379831,801012564,510289425,510282532,500027118,801501672,500005010,510436441,510142464,510454753,510367645,510435371,500036298,510275737,500039127,510136218,510386948,510387499,510350347,510137921,510133561,510114874,500115496,510372953,500163449,500195620,510027399,510120804,510237515,200089648,510169282,500194970,801502174,510233952,801482006,510470126);
		
		$this->db->where('pay_status','1');
		$this->db->where_in('member_no',$member_array);
		$total_pay_5 = $this->master_model->getRecords('exam_invoice_without_gst','','invoice_id,member_no');
		$paymem = array();
		foreach($total_pay_5 as $memrec){
			$paymem[]=$memrec['member_no'];
		}
		
		$this->db->where('remark','1');
		$this->db->where('exam_date','2020-07-05');
		$this->db->where_in('mem_mem_no',$paymem);
		$admit_5 = $this->master_model->getRecords('admit_card_details','','admitcard_id');
		//echo '>>'. count($admit_5);
		$july = '479'; 
		
		echo 'Total 5 july candidate : 479';
		echo '<br/>';
		echo 'Candidate who pays fee till date :'.count($admit_5);
		echo '<br/>';
		$remaining_candidate_july = $july - count($admit_5);
		echo 'Remaining candidate till date : '.$remaining_candidate_july;
		
	}
	
	public function calc_gst_amt(){
		//$this->db->where('invoice_id','2525403'); 
		$sql = $this->master_model->getRecords('exam_invoice_without_gst','','invoice_id,fee_amt,cgst_amt,sgst_amt,cs_total');
		
		foreach($sql as $rec){
			$gst_amt = $rec['cs_total'] - $rec['fee_amt'];
			$gst_amt_one = $rec['cgst_amt'] + $rec['sgst_amt'];
			if($gst_amt == $gst_amt_one){
				$update_data = array('amt_diff' => $gst_amt);
				$this->master_model->updateRecord('exam_invoice_without_gst',$update_data,array('invoice_id'=>$rec['invoice_id']));
			}
		}
	}
	
	public function settle(){
		
		$invoice_id = '2507705';
		
		$this->db->like('description',$invoice_id);
		$sql = $this->master_model->getRecords('userlogs','','description');
		
		print_r($sql);
		
		
	}
	
	
	public function gendbnum(){
		echo 'hYYiDD';exit; 
		$regno_arr = array(2060932);
		
		for($i=0;$i<sizeof($regno_arr);$i++){
			echo $regno_arr[$i];
			echo '<br/>';
			$applicationNo = generate_DBF_memreg($regno_arr[$i]);
			
			$update_data = array('pay_status' => '1','regnumber'=>$applicationNo);
			$this->master_model->updateRecord('member_exam',$update_data,array('regnumber'=>$regno_arr[$i]));
			
			$update_mem_data = array('isactive' =>'1','regnumber'=>$applicationNo);
			$this->master_model->updateRecord('member_registration',$update_mem_data,array('regid'=>$regno_arr[$i]));
		}
	}
	
	public function update_admitcard(){
		$this->db->limit(30);
		$sql = $this->master_model->getRecords('number',array('flg'=>'N'));
		
		/*echo '<pre>';
		print_r($sql);
		exit;*/
		
		foreach($sql as $rec){
			
			echo $rec['exam_id'];
			echo '<br/>';
			
			$admitcard_image = '42_120_'.$rec['mem_num'].'.pdf';
			
			$update_data = array('mem_mem_no' => $rec['mem_num'],'remark'=>1,'admitcard_image'=>$admitcard_image);
			$this->master_model->updateRecord('admit_card_details',$update_data,array('mem_exam_id'=>$rec['exam_id'],'exm_cd'=>42,'exm_prd'=>120));
			
			echo $this->db->last_query();
			echo '<br/>';
			
			$update_mem_data = array('flg'=>'Y');
			$this->master_model->updateRecord('number',$update_mem_data,array('mem_num'=>$rec['mem_num']));
			echo $this->db->last_query();
			echo '<br/>';
			
			echo '***********************************';
			echo '<br/>';
		}
	}
	
	public function update_member(){
		
		$this->db->limit(0,2);
		$sql = $this->master_model->getRecords('number',array('flg'=>'N'));
		
		/*echo '<pre>';
		print_r($sql);
		exit;*/
		
		foreach($sql as $rec){
			
			echo $rec['mem_num'];
			echo '<br/>';
			
			$new_password=$this->generate_random_password();
			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('pass_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			echo $encPass = $aes->encrypt($new_password);
			echo '<br/>';
			
			//exit;
			
			$currentpics = $this->master_model->getRecords('member_registration', array('regnumber'=>$rec['mem_num']), 'scannedphoto, scannedsignaturephoto, idproofphoto'); 
			$scannedphoto_file = '';
			$scannedsignaturephoto_file = '';
			$idproofphoto_file = '';
				
			if( count($currentpics) > 0 ) {
				$currentphotos = $currentpics[0];
				$scannedphoto_file = $currentphotos['scannedphoto'];
				$scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
				$idproofphoto_file = $currentphotos['idproofphoto'];
				
			}
			$upd_files = array();
			$photo_file = 'p_'.$rec['mem_num'].'.jpg';
			$sign_file = 's_'.$rec['mem_num'].'.jpg';
			$proof_file = 'pr_'.$rec['mem_num'].'.jpg';
			
			if( !empty( $scannedphoto_file ) ) { 
				if(@ rename("./uploads/photograph/".$scannedphoto_file,"./uploads/photograph/".$photo_file))
				{	
					$upd_files['scannedphoto'] = $photo_file;	
				}
			}
			if( !empty( $scannedsignaturephoto_file ) ) { 
				if(@ rename("./uploads/scansignature/".$scannedsignaturephoto_file,"./uploads/scansignature/".$sign_file))
				{	
					$upd_files['scannedsignaturephoto'] = $sign_file;	
				}
			}
			if( !empty( $idproofphoto_file ) ) { 
				if(@ rename("./uploads/idproof/".$idproofphoto_file,"./uploads/idproof/".$proof_file))
				{	
					$upd_files['idproofphoto'] = $proof_file;	
				}
			}
			
			$upd_files['usrpassword'] = $encPass;	
			
			echo '<pre>';
			print_r($upd_files);
			echo '<br/>';
			
			if(count($upd_files)>0)
			{ 
				$this->master_model->updateRecord('member_registration',$upd_files,array('regnumber'=>$rec['mem_num']));
				echo $this->db->last_query();
				echo '<br/>';
			}
			
			
			$update_mem_data = array('flg'=>'Y');
			$this->master_model->updateRecord('number',$update_mem_data,array('mem_num'=>$rec['mem_num']));
			echo $this->db->last_query();
			echo '<br/>';
			
			echo '*****************************************';
			echo '<br/>';
			
			
		}
	}
	
	function generate_random_password($length = 8, $level = 2) // function to generate new password
	{
		list($usec, $sec) = explode(' ', microtime());
		srand((float) $sec + ((float) $usec * 100000));
		$validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
		$validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$validchars[3] = "0123456789_!@#*()-=+abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#*()-=+";
		$password = "";
		$counter = 0;
		while ($counter < $length) {
		$actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
		if (!strstr($password, $actChar)) {
			$password .= $actChar;
				$counter++;
			}
		}
		return $password;
	}
	
function random_password($length = 6){
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
	$password = substr( str_shuffle( $chars ), 0, $length );
	//echo  $password;
	return $password;
}	
public function member_settlement_new(){       
	$member_no = 510335414;  
	$mem_exam_id = 5698315;  
	$exam_code = $this->config->item('examCodeCaiib');  
	$exam_prd = 121;
	$password = $this->random_password();     
	//check in admit card  table
	$this->db->group_by('sub_cd,mem_exam_id');
	$admit_card_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$mem_exam_id,'mem_mem_no'=>$member_no,'exm_cd'=>$exam_code,
	'exm_prd'=>$exam_prd)); 
	echo $this->db->last_query();
	
	/********Password Code********/
	if(!empty($admit_card_details))
	{
		foreach($admit_card_details as $val)
		{
			if($val['pwd']!='')
			{
				$password =$val['pwd'];
			}	
		}
	}
	if($password == '')
	{
		$password = $this->random_password(); 
	}
	/********End of Password Code********/
	
	if(!empty($admit_card_details))
	{
		echo 'Total recode found in admit card table :<br>';
		echo count($admit_card_details);
		foreach($admit_card_details as $val)
		{
			if($val['seat_identification']=='')
			{
				//get the  seat number from the seat allocation table 2
				$this->db->order_by("seat_no", "desc"); 
				$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));
				if(!empty($seat_allocation))
				{
					//check venue_capacity
					$venue_capacity=$this->master_model->getRecords('venue_master',array('venue_code'=>$val['venueid'],
					'session_time'=>$val['time'],
					'center_code'=>$val['center_code'],
					'exam_date'=>$val['exam_date']));
					//echo  $this->db->last_query();
					$venue_capacity=$venue_capacity[0]['session_capacity'];
					if(!empty($venue_capacity))
					{
						//if(count($seat_allocation)<=$venue_capacity)
						if($seat_allocation[0]['seat_no']<=$venue_capacity)
						{
							$seat_no=$seat_allocation[0]['seat_no'];
							//inset new recode with append  seat number
							$seat_no=$seat_no+1;
							if($seat_no<10)
							{
								$seat_no='00'.$seat_no;
							}
							elseif($seat_no>10 && $seat_no<100)
							{
								$seat_no='0'.$seat_no;
							}
							$invoice_insert_array = array(
								'seat_no' => $seat_no,
								'exam_code' => $val['exm_cd'],
								'venue_code'=>$val['venueid'],
								'session'=>$val['time'],
								'center_code'=>$val['center_code'],
								'date'=>$val['exam_date'],
								'exam_period'=>$val['exm_prd'],
								'subject_code'=>$val['sub_cd'],
								'admit_card_id'=>$val['admitcard_id'],
								'createddate'=>date('Y-m-d H:i:s')
							);
				if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
				{	
					$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
					$update_info = array(
						'seat_identification' => $seat_no,
						'modified_on'=>$val['created_on'],
						'admitcard_image'=>$admitcard_image,
						'pwd' => $password, 
						'remark'=>1,
					);
					if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
					{
						echo '<br>Recode updated sucessfully in admit card<br>';
					}else
					{
						echo '<br>Recode Not updated sucessfully in admit card<br>';
					}
				}
			}else
			{
				echo '<br>Capacity has been full<br>';
			}
		}else
		{
			echo '<br>Venue not present in venue master123<br>';
		}
	}
	else
	{
		$venue_capacity=$this->master_model->getRecords('venue_master',array('venue_code'=>$val['venueid'],
			'session_time'=>$val['time'],
			'center_code'=>$val['center_code'],
			'exam_date'=>$val['exam_date']));
			echo $this->db->last_query();
		if(!empty($venue_capacity))
		{
			if($seat_allocation[0]['seat_no']<=$venue_capacity[0]['session_capacity'])
			{
				//inset new recode with oo1
				$seat_no='001';
				$invoice_insert_array = array(
					'seat_no' => $seat_no,
					'exam_code' => $val['exm_cd'],
					'venue_code'=>$val['venueid'],
					'session'=>$val['time'],
					'center_code'=>$val['center_code'],
					'date'=>$val['exam_date'],
					'exam_period'=>$val['exm_prd'],
					'subject_code'=>$val['sub_cd'],
					'admit_card_id'=>$val['admitcard_id'],
					'createddate'=>date('Y-m-d H:i:s')
				);
				if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array))
				{
					echo 'Seat alloation primary key :<br>';
					echo $inser_id;
					//update the admit card table :
					$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
					$update_info = array(
						'seat_identification' => $seat_no,
						'modified_on'=>$val['created_on'],
						'admitcard_image'=>$admitcard_image,
						'pwd' => $password, 
						'remark'=>1,
					);
					if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id'])))
					{
						echo 'Recode updated sucessfully in admit card<br>';
					}else
					{
						echo 'Recode Not updated sucessfully in admit card<br>';
					}
				}
			}else
			{
				echo '<br>Capacity has been full<br>';
			}
		}else
		{
			echo '<br>Venue not present in venue master234<br>';
		}
	}}
}
}
}
public function naar_settel(){       
	
	$memregid = 2636522;     
	 
	$memregnumber = generate_NM_memreg($memregid);  
	
	$new_password=$this->generate_random_password();
	include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
	$key = $this->config->item('pass_key');
	$aes = new CryptAES();
	$aes->set_key(base64_decode($key));
	$aes->require_pkcs5();
	$encPass = $aes->encrypt($new_password);
	
	
	$update_data_member_tbl = array(
						'regnumber'=> $memregnumber,
						'usrpassword'=>$encPass,
						'isactive'=> '1'
					);	
	$this->master_model->updateRecord('member_registration',$update_data_member_tbl, array('regid' => $memregid));
	
	
	// update member_exam table 
	$update_data_member_exam_tbl = array(
		'regnumber'=> $memregnumber,
	);	
	$this->master_model->updateRecord('member_exam',$update_data_member_exam_tbl, array('regnumber' => $memregid));
	
	// update admit card detail table
	$update_data_admit_card_tbl = array(
		'mem_mem_no'=> $memregnumber,
		'mem_type'=> 'NM'
	);
	$this->master_model->updateRecord('admit_card_details',$update_data_admit_card_tbl, array('mem_mem_no' => $memregid));
	
	
	//update uploaded file names which will include generated registration number
	//get cuurent saved file names from DB
	$currentpics = $this->master_model->getRecords('member_registration', array('regid'=>$memregid), 'scannedphoto, scannedsignaturephoto, idproofphoto'); 
	$scannedphoto_file = '';
	$scannedsignaturephoto_file = '';
	$idproofphoto_file = '';
		
	if( count($currentpics) > 0 ) {
		$currentphotos = $currentpics[0];
		$scannedphoto_file = $currentphotos['scannedphoto'];
		$scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
		$idproofphoto_file = $currentphotos['idproofphoto'];
		
	}
	$upd_files = array();
	$photo_file = 'p_'.$memregnumber.'.jpg';
	$sign_file = 's_'.$memregnumber.'.jpg';
	$proof_file = 'pr_'.$memregnumber.'.jpg';
	
	if( !empty( $scannedphoto_file ) ) { 
		if(@ rename("./uploads/photograph/".$scannedphoto_file,"./uploads/photograph/".$photo_file))
		{	
			$upd_files['scannedphoto'] = $photo_file;	
		}
	}
	if( !empty( $scannedsignaturephoto_file ) ) { 
		if(@ rename("./uploads/scansignature/".$scannedsignaturephoto_file,"./uploads/scansignature/".$sign_file))
		{	
			$upd_files['scannedsignaturephoto'] = $sign_file;	
		}
	}
	if( !empty( $idproofphoto_file ) ) { 
		if(@ rename("./uploads/idproof/".$idproofphoto_file,"./uploads/idproof/".$proof_file))
		{	
			$upd_files['idproofphoto'] = $proof_file;	
		}
	}
	
	if(count($upd_files)>0)
	{ 
		$this->master_model->updateRecord('member_registration',$upd_files,array('regid'=>$memregid));
	}
	
} 
public function subject_missing_R(){                 
		$member_array = array();  
		$exarr = array(21);  
		//$exarr = array(21); 
		$this->db->select('mem_mem_no');  
		$this->db->distinct('mem_mem_no');  
		$this->db->where_in('exm_cd',$exarr); 
		$this->db->where('exm_prd',121);
		$this->db->where('remark','1');
		$this->db->where('app_update','0');
		//$this->db->where('mem_mem_no',510331312);
		$this->db->where('created_on >= ','2021-03-30 00:00:01');   
		$this->db->where('created_on <= ','2021-03-30 23:59:59');         
		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd,mem_exam_id');   
		
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
		$exarr = array(21);    
		$this->db->select('mem_mem_no');  
		$this->db->distinct('mem_mem_no');
		$this->db->where_in('exm_cd',$exarr);
		$this->db->where('exm_prd',121); 
		//$this->db->where('mem_mem_no',200047209); 
		$this->db->where('remark','1');
		$this->db->where('app_update','0');
		$this->db->where('created_on >= ','2021-03-30 00:00:01');         
		$this->db->where('created_on <= ','2021-03-30 23:59:59');  
		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd,mem_exam_id');  
		
		foreach($admit_card as $member_no){
			$app_arr = array('F');
			$this->db->where('member_no',$member_no['mem_mem_no']);
			$this->db->where_in('exam_code',$exarr);
			$this->db->where('eligible_period',121);
			//$this->db->where_in('app_category',$app_arr); 
			$this->db->where('exam_status','F'); 
			$this->db->where('app_category !=','R'); 
			$member_rec = $this->master_model->getRecords('eligible_master_121_first','','id');
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
		$exarr = array(21);    
		 //$exarr = array(21);   
		$this->db->select('mem_mem_no'); 
		$this->db->distinct('mem_mem_no');
		$this->db->where_in('exm_cd',$exarr);
		$this->db->where('exm_prd',121); 
		//$this->db->where('mem_mem_no','510453280'); 
		$this->db->where('remark','1');
		$this->db->where('app_update','0');
		$this->db->where('created_on >= ','2021-03-30 00:00:01');        
		$this->db->where('created_on <= ','2021-03-30 23:59:59');    
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
public function custom_exam_invoice_new_design_swati(){     
		 $arr = array('2807348'); // add invoice id 
		 for($i=0;$i<sizeof($arr);$i++){
		 	echo $path = custom_genarate_exam_invoice_newdesign($arr[$i]);
		 	echo "<br/>"; 
		 } 
	}
	
public function custom_examinvoice_send_mail_temp(){ 
	$this->db->where('email_send', 0);
		$this->db->limit(20,0);
		$record = $this->master_model->getRecords('generate_new_invocie');
		
		if(count($record) > 0){
		foreach($record as $res){
		   
		$this->db->where_in('invoice_id',$res['invoice_id']); 
		$sql = $this->master_model->getRecords('exam_invoice','','invoice_id,invoice_image,member_no,exam_code');
		
		$exam_name = $this->master_model->getRecords('exam_master',array('exam_code'=>$sql[0]['exam_code']),'description');
		
		$final_str = "Hello Sir/Madam"; 
		$final_str.= "<br/><br/>";
		$final_str.= 'Please check your invoice receipt for '.$exam_name[0]['description'].' exam registration.'; 
		$final_str.= "<br/><br/>";
		$final_str.= "Regards,";
		$final_str.= "<br/>";
		$final_str.= "IIBF TEAM";
		
		foreach($sql as $rec){ 
			$attachpath = "uploads/examinvoice/user/".$rec['invoice_image'];
			//$attachpath = "uploads/IIBF_ADMIT_CARD_510033421.pdf";
			echo $attachpath."<br/>";
			//$attachpath = "uploads/examinvoice/user/".$rec['invoice_image'];
			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['member_no'],'isactive'=>'1'),'email'); 
			//echo ">>".$email[0]['email'];  
			//exit;
			$info_arr=array('to'=>$email[0]['email'],
							//'to'=>'swati.watpade@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Exam Enrollment Acknowledgement',
							'message'=>$final_str
						);
						
			
			$files=array($attachpath);
			
			if($this->Emailsending->mailsend_attch($info_arr,$files)){
				
				echo "Mail send to => ".$rec['invoice_id'];
				echo "<br/>"; 
				$update_data = array('email_send'=> 1 );
			$this->master_model->updateRecord('generate_new_invocie',$update_data,array('id'=>$res['id']));	
			
			}
			
		}
	}
}else{
		 	echo "all Done.stop";
		 } 
		
}
public function jaiib_capacity_chk_31march21(){   
	
		$admitid_arr = array(5339319,5350622,5350623,5354532,5354533,5361908,5361909,5361914,5361915,5372794,5376163,5376166,5380151,5380153,5381770,5382400,5383544,5385650,5385651,5386201,5386689,5388028,5390387,5390388,5393678,5393680,5397624,5397626,5398316,5398318,5400975,5404520,5404521,5404697,5404698,5405484,5405485,5407246,5407247,5407692,5481473,5481474,5486804,5487281,5513561,5516401,5516402,5357210,5357211,5396406,5396407,5307091,5316847,5336713,5340258,5340259,5344059,5344060,5347283,5347284,5348691,5348692,5349048,5357400,5357402,5360463,5360464,5361812,5362456,5373640,5373641,5374541,5374543,5375471,5375472,5376038,5378900,5383766,5384190,5385644,5385645,5385744,5388523,5388571,5388572,5390809,5390810,5391536,5392536,5392537,5392770,5394062,5394825,5394827,5395156,5395157,5396013,5397561,5397563,5398114,5401924,5402050,5402246,5402247,5402414,5402443,5402666,5402667,5403693,5403694,5403741,5404668,5404669,5409051,5409052,5354287,5354288,5360162,5384195,5416999,5424218,5424385,5427916,5431724,5438956,5440064,5480929,5482009,5482010,5526024);
		
		
		
		$this->db->where_in('admitcard_id',$admitid_arr);
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>'',
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('jaiib_settel_capacity_31march21', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
	
}
public function jaiib_capacity_chk_31march21_after_capacity_increase(){   
	
		$admitid_arr = array(5339319,5350622,5350623,5354532,5354533,5361908,5361909,5361914,5361915,5372794,5376163,5376166,5380151,5380153,5381770,5382400,5383544,5385650,5385651,5386201,5386689,5388028,5390387,5390388,5393678,5393680,5397624,5397626,5398316,5398318,5400975,5404520,5404521,5404697,5404698,5405484,5405485,5407246,5407247,5407692,5481473,5481474,5486804,5487281,5513561,5516401,5516402,5357210,5357211,5396406,5396407,5307091,5316847,5336713,5340258,5340259,5344059,5344060,5347283,5347284,5348691,5348692,5349048,5357400,5357402,5360463,5360464,5361812,5362456,5373640,5373641,5374541,5374543,5375471,5375472,5376038,5378900,5383766,5384190,5385644,5385645,5385744,5388523,5388571,5388572,5390809,5390810,5391536,5392536,5392537,5392770,5394062,5394825,5394827,5395156,5395157,5396013,5397561,5397563,5398114,5401924,5402050,5402246,5402247,5402414,5402443,5402666,5402667,5403693,5403694,5403741,5404668,5404669,5409051,5409052,5354287,5354288,5360162,5384195,5416999,5424218,5424385,5427916,5431724,5438956,5440064,5480929,5482009,5482010,5526024);
		
		
		
		$this->db->where_in('admitcard_id',$admitid_arr); 
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>'',
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('jaiib_settel_capacity_31march21_after_capacity_increase', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
	
}
public function jaiib_capacity_chk_31march21_after_capacity_increase_and_seat(){   
	
		$admitid_arr = array(5307091,5316847,5336713,5339319,5344059,5344060,5347284,5348691,5348692,5349048,5350622,5354287,5354288,5354532,5354533,5357210,5357211,5357400,5357402,5360463,5361812,5361908,5361909,5361914,5361915,5362456,5372794,5373640,5373641,5374541,5374543,5375471,5375472,5376163,5376166,5378900,5380151,5380153,5381770,5382400,5383544,5383766,5384190,5384195,5385644,5385645,5386201,5386689,5388028,5388523,5388571,5388572,5390387,5390388,5390809,5390810,5391536,5392770,5393680,5394062,5394825,5394827,5395156,5395157,5396013,5396407,5397563,5397626,5398114,5398316,5398318,5401924,5402050,5402246,5402247,5402414,5402443,5402667,5403693,5403694,5403741,5404520,5404521,5404668,5404669,5404697,5404698,5405484,5405485,5407246,5407247,5409051,5409052,5424218,5424385,5431724,5438956,5440064,5480929,5481473,5481474,5482009,5482010,5486804,5487281,5516401);
		
		
		 
		$this->db->where_in('admitcard_id',$admitid_arr); 
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>'',
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('jaiib_settel_capacity_31march21_after_capacity_increase_and_seat', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
	
}
public function jaiib_capacity_chk_9april2021_after_capacity_increase(){   
	
		$admitid_arr = array(5307089,5307090,5307091,5316845,5316846,5316847,5336711,5336712,5336713,5339317,5339318,5339319,5344058,5344059,5344060,5347282,5347283,5347284,5348690,5348691,5348692,5349047,5349048,5349049,5350621,5350622,5350623,5354286,5354287,5354288,5354531,5354532,5354533,5357209,5357210,5357211,5357400,5357401,5357402,5360462,5360463,5360464,5361811,5361812,5361813,5361913,5361914,5361915,5361907,5361908,5361909,5362454,5362455,5362456,5372792,5372793,5372794,5373639,5373640,5373641,5374540,5374541,5374543,5375470,5375471,5375472,5376157,5376163,5376166,5378900,5378901,5378902,5380151,5380152,5380153,5381768,5381769,5381770,5382399,5382400,5382402,5383542,5383543,5383544,5383764,5383765,5383766,5384189,5384190,5384194,5384195,5384196,5385643,5385644,5385645,5386199,5386200,5386201,5386687,5386688,5386689,5388026,5388027,5388028,5388521,5388522,5388523,5388570,5388571,5388572,5390386,5390387,5390388,5390808,5390809,5390810,5391534,5391535,5391536,5392767,5392770,5392773,5393678,5393679,5393680,5394061,5394062,5394823,5394825,5394827,5395155,5395156,5395157,5396011,5396013,5396015,5396406,5396407,5396408,5397561,5397562,5397563,5397624,5397625,5397626,5398114,5398116,5398314,5398316,5398318,5401922,5401923,5401924,5402046,5402048,5402050,5402245,5402246,5402247,5402411,5402413,5402414,5402442,5402443,5402444,5402666,5402667,5402668,5403692,5403693,5403694,5403740,5403741,5404519,5404520,5404521,5404668,5404669,5404670,5404696,5404697,5404698,5405483,5405484,5405485,5407243,5407246,5407247,5409051,5409052,5409053,5424214,5424217,5424218,5424383,5424384,5424385,5431724,5431725,5431726,5438954,5438956,5440063,5440064,5440065,5480927,5480928,5480929,5481472,5481473,5481474,5482008,5482009,5482010,5486803,5486804,5486805,5487280,5487281,5487282,5516400,5516401,5516402);
		
		
		 
		$this->db->where_in('admitcard_id',$admitid_arr); 
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no,pwd,seat_identification');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>'',
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'pwd'=>$admit_rec['pwd'],
								'seat_number'=>$admit_rec['seat_identification'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								); 
			
			$this->master_model->insertRecord('jaiib_settel_capacity_9april21_after_capacity_increase', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
	
}
public function jaiib_settel_capacity_106_sette_unsettel(){   
	
		$admitid_arr = array(5307089,5307090,5307091,5316845,5316846,5316847,5336711,5336712,5336713,5339317,5339318,5339319,5344058,5344059,5344060,5347282,5347283,5347284,5348690,5348691,5348692,5349047,5349048,5349049,5350621,5350622,5350623,5354286,5354287,5354288,5354531,5354532,5354533,5357209,5357210,5357211,5357400,5357401,5357402,5360462,5360463,5360464,5361811,5361812,5361813,5361913,5361914,5361915,5361907,5361908,5361909,5362454,5362455,5362456,5372792,5372793,5372794,5373639,5373640,5373641,5374540,5374541,5374543,5375470,5375471,5375472,5376157,5376163,5376166,5378900,5378901,5378902,5380151,5380152,5380153,5381768,5381769,5381770,5382399,5382400,5382402,5383542,5383543,5383544,5383764,5383765,5383766,5384189,5384190,5384194,5384195,5384196,5385643,5385644,5385645,5386199,5386200,5386201,5386687,5386688,5386689,5388026,5388027,5388028,5388521,5388522,5388523,5388570,5388571,5388572,5390386,5390387,5390388,5390808,5390809,5390810,5391534,5391535,5391536,5392767,5392770,5392773,5393678,5393679,5393680,5394061,5394062,5394823,5394825,5394827,5395155,5395156,5395157,5396011,5396013,5396015,5396406,5396407,5396408,5397561,5397562,5397563,5397624,5397625,5397626,5398114,5398116,5398314,5398316,5398318,5401922,5401923,5401924,5402046,5402048,5402050,5402245,5402246,5402247,5402411,5402413,5402414,5402442,5402443,5402444,5402666,5402667,5402668,5403692,5403693,5403694,5403740,5403741,5404519,5404520,5404521,5404668,5404669,5404670,5404696,5404697,5404698,5405483,5405484,5405485,5407243,5407246,5407247,5409051,5409052,5409053,5424214,5424217,5424218,5424383,5424384,5424385,5431724,5431725,5431726,5438954,5438956,5440063,5440064,5440065,5480927,5480928,5480929,5481472,5481473,5481474,5482008,5482009,5482010,5486803,5486804,5486805,5487280,5487281,5487282,5516400,5516401,5516402);
		
		
		 
		$this->db->where_in('admitcard_id',$admitid_arr); 
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no,pwd,seat_identification');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>'',
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'pwd'=>$admit_rec['pwd'],
								'seat_number'=>$admit_rec['seat_identification'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								); 
			
			$this->master_model->insertRecord('jaiib_settel_capacity_106_sette_unsettel', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
	
}
public function jaiib_capacity_chk(){ 
	
	$this->db->where('chk_flag',0);
	$this->db->where('status',0);
	//$this->db->limit(1); 
	$sql = $this->master_model->getRecords('jaiib_settelment','','receipt_no,id');
	$i=1;
	foreach($sql as $rec){ 
		
		$this->db->where('receipt_no',$rec['receipt_no']);
		$payment = $this->master_model->getRecords('payment_transaction','','ref_id');
		
		$this->db->where('mem_exam_id',$payment[0]['ref_id']);
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>$rec['receipt_no'],
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venue_code'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'session_time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('jaiib_settel_capacity', $insert_array);
			/*echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';*/
			
		}
		
		$update_arr = array('chk_flag'=>'1');
		$where_arr = array('id'=>$rec['id']);
		$this->master_model->updateRecord('jaiib_settelment',$update_arr,$where_arr);
		echo $i.' >> '. $this->db->last_query();
		echo '<br/>';
		$i++;
	}
}
public function jaiib_capacity_chk_2(){ 
	
	$this->db->where('chk_flag',0);
	$this->db->where('status',0);
	//$this->db->limit(1); 
	$sql = $this->master_model->getRecords('jaiib_settelment_after_allocation','','receipt_no,id');
	$i=1; 
	foreach($sql as $rec){ 
		
		$this->db->where('receipt_no',$rec['receipt_no']);
		$payment = $this->master_model->getRecords('payment_transaction','','ref_id');
		
		$this->db->where('mem_exam_id',$payment[0]['ref_id']);
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>$rec['receipt_no'],
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('jaiib_settel_capacity_after_allocation', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
		
		$update_arr = array('chk_flag'=>'1');
		$where_arr = array('id'=>$rec['id']);
		$this->master_model->updateRecord('jaiib_settelment_after_allocation',$update_arr,$where_arr);
		echo $i.' >> '. $this->db->last_query();
		echo '<br/>';
		$i++;
	}
}
public function jaiib_capacity_chk_2_eve(){ 
	
	$this->db->where('chk_flag',0);
	$this->db->where('status',0);
	//$this->db->limit(1); 
	$sql = $this->master_model->getRecords('jaiib_settelment_after_allocation_eve','','receipt_no,id');
	$i=1; 
	foreach($sql as $rec){ 
		
		$this->db->where('receipt_no',$rec['receipt_no']);
		$payment = $this->master_model->getRecords('payment_transaction','','ref_id');
		
		$this->db->where('mem_exam_id',$payment[0]['ref_id']);
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>$rec['receipt_no'],
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('jaiib_settel_capacity_after_allocation_eve', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
		
		$update_arr = array('chk_flag'=>'1');
		$where_arr = array('id'=>$rec['id']);
		$this->master_model->updateRecord('jaiib_settelment_after_allocation_eve',$update_arr,$where_arr);
		echo $i.' >> '. $this->db->last_query();
		echo '<br/>';
		$i++;
	}
}
public function jaiib_capacity_chk_3(){ 
	
	$this->db->where('chk_flag',0);
	$this->db->where('status',0);
	//$this->db->limit(1); 
	$sql = $this->master_model->getRecords('jaiib_settelment_2_after_allocation','','receipt_no,id');
	$i=1; 
	foreach($sql as $rec){ 
		
		$this->db->where('receipt_no',$rec['receipt_no']);
		$payment = $this->master_model->getRecords('payment_transaction','','ref_id');
		
		$this->db->where('mem_exam_id',$payment[0]['ref_id']);
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>$rec['receipt_no'],
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('jaiib_settel_capacity_2_after_allocation', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
		
		$update_arr = array('chk_flag'=>'1');
		$where_arr = array('id'=>$rec['id']);
		$this->master_model->updateRecord('jaiib_settelment_2_after_allocation',$update_arr,$where_arr);
		echo $i.' >> '. $this->db->last_query();
		echo '<br/>';
		$i++;
	}
}
public function jaiib_capacity_chk_3_eve(){ 
	
	$this->db->where('chk_flag',0);
	$this->db->where('status',0);
	//$this->db->limit(1); 
	$sql = $this->master_model->getRecords('jaiib_settelment_2_after_allocation_eve','','receipt_no,id');
	$i=1;  
	foreach($sql as $rec){ 
		
		$this->db->where('receipt_no',$rec['receipt_no']);
		$payment = $this->master_model->getRecords('payment_transaction','','ref_id');
		
		$this->db->where('mem_exam_id',$payment[0]['ref_id']);
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>$rec['receipt_no'],
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('jaiib_settel_capacity_2_after_allocation_eve', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
		
		$update_arr = array('chk_flag'=>'1');
		$where_arr = array('id'=>$rec['id']);
		$this->master_model->updateRecord('jaiib_settelment_2_after_allocation_eve',$update_arr,$where_arr);
		echo $i.' >> '. $this->db->last_query();
		echo '<br/>';
		$i++;
	}
}
public function jaiib_capacity_chk_2april21(){  
	
	$this->db->where('chk_flag',0);
	$this->db->where('status',0);
	//$this->db->limit(1); 
	$sql = $this->master_model->getRecords('jaiib_settelment_after_allocation_2april21','','receipt_no,id');
	$i=1; 
	foreach($sql as $rec){ 
		
		$this->db->where('receipt_no',$rec['receipt_no']);
		$payment = $this->master_model->getRecords('payment_transaction','','ref_id');
		
		$this->db->where('mem_exam_id',$payment[0]['ref_id']);
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>$rec['receipt_no'],
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('jaiib_settel_capacity_after_allocation_2april21', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
		
		$update_arr = array('chk_flag'=>'1'); 
		$where_arr = array('id'=>$rec['id']);
		$this->master_model->updateRecord('jaiib_settelment_after_allocation_2april21',$update_arr,$where_arr);
		echo $i.' >> '. $this->db->last_query();
		echo '<br/>';
		$i++;
	}
}
public function jaiib_capacity_chk_11april21(){   
	
	$this->db->where('chk_flag',0);
	$this->db->where('status',0);
	//$this->db->limit(1); 
	$sql = $this->master_model->getRecords('jaiib_settelment_after_allocation_2april21','','receipt_no,id');
	$i=1; 
	foreach($sql as $rec){ 
		
		$this->db->where('receipt_no',$rec['receipt_no']);
		$payment = $this->master_model->getRecords('payment_transaction','','ref_id');
		
		$this->db->where('mem_exam_id',$payment[0]['ref_id']);
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>$rec['receipt_no'],
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venueid'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('jaiib_settel_capacity_11april2021_558', $insert_array);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
			
		}
		
		$update_arr = array('chk_flag'=>'1'); 
		$where_arr = array('id'=>$rec['id']);
		$this->master_model->updateRecord('jaiib_settelment_after_allocation_2april21',$update_arr,$where_arr);
		echo $i.' >> '. $this->db->last_query();
		echo '<br/>';
		$i++;
	}
}
public function jaiib_capacity_chk_4april21(){  
	
	
	$this->db->where('status',0);
	//$this->db->limit(1); 
	$sql = $this->master_model->getRecords('4april_21_venue_unique'); 
	//echo $this->db->last_query();
	//exit;
	$i=1; 
	foreach($sql as $rec){ 
			
			$this->db->where('exam_date',$rec['exam_date']);
			$this->db->where('center_code',$rec['center_code']);
			$this->db->where('session_time',$rec['time']);
			$this->db->where('venue_code',$rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			//echo $this->db->last_query();
			//echo '<br/>';
			
			$this->db->where('venue_code',$rec['venueid']);
			$this->db->where('center_code',$rec['center_code']);
			$this->db->where('session',$rec['time']);
			$this->db->where('date',$rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			
			$extra = $venue_capacity[0]['session_capacity'] - $register_count;
		 
		    $update_arr = array('actual_capacity'=>$venue_capacity[0]['session_capacity'],'status'=>1,'register_count'=>$register_count,'extra'=>$extra);
			$where_arr = array('center_code'=>$rec['center_code'],'venueid'=>$rec['venueid'],'exam_date'=>$rec['exam_date'],'time'=>$rec['time']);
			
			$this->master_model->updateRecord('4april_21_venue_unique', $update_arr, $where_arr);
			echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';
		
		$i++;
	}
}
public function jaiib_capacity_chk_2_chaitali(){ 		$this->db->where('chk_flag',0);	$this->db->where('status',0);	$this->db->limit(500); 	$sql = $this->master_model->getRecords('jaiib_settelment_round_2','','receipt_no,id');	$i=1;	foreach($sql as $rec){ 				$this->db->where('receipt_no',$rec['receipt_no']);		$payment = $this->master_model->getRecords('payment_transaction','','ref_id');				$this->db->where('mem_exam_id',$payment[0]['ref_id']);		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');		$insert_array = array();		foreach($admit as $admit_rec){ 						$this->db->where('exam_date',$admit_rec['exam_date']);			$this->db->where('center_code',$admit_rec['center_code']);			$this->db->where('session_time',$admit_rec['time']);			$this->db->where('venue_code',$admit_rec['venueid']);			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');						$this->db->where('venue_code',$admit_rec['venueid']);			$this->db->where('center_code',$admit_rec['center_code']);			$this->db->where('session',$admit_rec['time']);			$this->db->where('date',$admit_rec['exam_date']);			$register_count = $this->master_model->getRecordCount('seat_allocation'); 						/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];			echo '<br/>';			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];			echo '<br/>';			echo 'Register count : '.$register_count;			echo '<br/>';			echo 'Member exam id : '.$admit_rec['mem_exam_id'];			echo '<br/>';			echo 'Member number : '.$admit_rec['mem_mem_no'];			echo '<br/>';			echo '<br/>';*/						$balance = $venue_capacity[0]['session_capacity'] - $register_count;						$insert_array = array(								'regnumber'=>$admit_rec['mem_mem_no'],								'mem_exam_id'=>$admit_rec['mem_exam_id'],								'receipt_no'=>$rec['receipt_no'],								'exam_code'=>$admit_rec['exm_cd'],								'center_code'=>$admit_rec['center_code'],								'venue_code'=>$admit_rec['venueid'],								'exam_date'=>$admit_rec['exam_date'],								'session_time'=>$admit_rec['time'],								'venue_capacity'=>$venue_capacity[0]['session_capacity'],								'resgister_count'=>$register_count,								'remaining_cnt'=>$balance								);						$this->master_model->insertRecord('jaiib_settel_capacity_round_2', $insert_array);			/*echo '<br/>';			echo $this->db->last_query();		    echo '<br/>';*/					}				$update_arr = array('chk_flag'=>'1');		$where_arr = array('id'=>$rec['id']);		$this->master_model->updateRecord('jaiib_settelment_round_2',$update_arr,$where_arr);		echo $i.' >> '. $this->db->last_query();		echo '<br/>';		$i++;	}}
public function other_exam_capacity_chk(){     
	
	$this->db->where('chk_flag',0);
	$this->db->where('status',0); 
	//$this->db->limit(1); 
	$sql = $this->master_model->getRecords('other_exam_settelment','','receipt_no,id');
	$i=1;
	foreach($sql as $rec){ 
		
		$this->db->where('receipt_no',$rec['receipt_no']);
		$payment = $this->master_model->getRecords('payment_transaction','','ref_id');
		
		$this->db->where('mem_exam_id',$payment[0]['ref_id']);
		$admit = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_exam_id,center_code,venueid,exam_date,time,exm_cd,mem_mem_no');
		$insert_array = array();
		foreach($admit as $admit_rec){ 
			
			$this->db->where('exam_date',$admit_rec['exam_date']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session_time',$admit_rec['time']);
			$this->db->where('venue_code',$admit_rec['venueid']);
			$venue_capacity = $this->master_model->getRecords('venue_master','','session_capacity');
			
			$this->db->where('venue_code',$admit_rec['venueid']);
			$this->db->where('center_code',$admit_rec['center_code']);
			$this->db->where('session',$admit_rec['time']);
			$this->db->where('date',$admit_rec['exam_date']);
			$register_count = $this->master_model->getRecordCount('seat_allocation'); 
			
			/*echo $admit_rec['exam_date']."**".$admit_rec['time']."**".$admit_rec['center_code']."**".$admit_rec['venueid'];
			echo '<br/>';
			echo 'Total capacity : '.$venue_capacity[0]['session_capacity'];
			echo '<br/>';
			echo 'Register count : '.$register_count;
			echo '<br/>';
			echo 'Member exam id : '.$admit_rec['mem_exam_id'];
			echo '<br/>';
			echo 'Member number : '.$admit_rec['mem_mem_no'];
			echo '<br/>';
			echo '<br/>';*/
			
			$balance = $venue_capacity[0]['session_capacity'] - $register_count;
			
			$insert_array = array(
								'regnumber'=>$admit_rec['mem_mem_no'],
								'mem_exam_id'=>$admit_rec['mem_exam_id'],
								'receipt_no'=>$rec['receipt_no'],
								'exam_code'=>$admit_rec['exm_cd'],
								'center_code'=>$admit_rec['center_code'],
								'venue_code'=>$admit_rec['venueid'],
								'exam_date'=>$admit_rec['exam_date'],
								'session_time'=>$admit_rec['time'],
								'venue_capacity'=>$venue_capacity[0]['session_capacity'],
								'resgister_count'=>$register_count,
								'remaining_cnt'=>$balance
								);
			
			$this->master_model->insertRecord('other_exam_settel_capacity', $insert_array);
			/*echo '<br/>';
			echo $this->db->last_query();
		    echo '<br/>';*/
			
		}
		
		$update_arr = array('chk_flag'=>'1');
		$where_arr = array('id'=>$rec['id']);
		$this->master_model->updateRecord('other_exam_settelment',$update_arr,$where_arr);
		echo $i.' >> '. $this->db->last_query();
		echo '<br/>';
		$i++;
	}
}
}
