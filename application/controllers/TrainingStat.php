<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TrainingStat extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('Master_model');
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->library('email');
		$this->load->model('Emailsending');		
		
		error_reporting(E_ALL);
		ini_set("display_errors", 1);

    }
    public function index()
	{ 
		  //check traning is activated or not 
		  $batch_code='';
		 $training_list=array();
		 $today_date = date('Y-m-d');		
		//$this->db->where('program_activation_delete','0');
		// $this->db->where("'$today_date' BETWEEN program_reg_from_date AND program_reg_to_date");
		 $batch_list= $this->master_model->getRecords('blended_program_activation_master');
		if (isset($_POST['btnSearch'])) 
		{
			$batch_code = $this->input->post('batch_code');
			$this->db->where('blended_registration.batch_code',$batch_code);
			$this->db->where('pay_status',1);
			$training_list = $this->master_model->getRecords('blended_registration');
		}
		$data['training_list'] = $training_list;
		$data['batch_code'] = $batch_code;
		$data['batch_list'] = $batch_list;
		$this->load->view('trainingstat/member_list',$data);
	}
	
	/* public function member_count()
	{ 
		$batch_code = $this->uri->segment(5);
	
	
		if (isset($_POST['btnSearch'])) 
		{
			$program_code = $_POST["program_code"];
			$batch_code = $_POST["batch_code"];
			$training_type = $_POST['training_type'];
			$zone_code = $_POST['zone_code'];
			$center_code = $_POST['center_code'];
			
			if($program_code != ""){$this->db->where('program_code', $program_code);}
			if($zone_code != ""){$this->db->where('zone_code', $zone_code);}
			if($center_code != ""){$this->db->where('center_code', $center_code);}
			if($batch_code != ""){$this->db->where('batch_code', $batch_code);}
			if($training_type != ""){$this->db->where('training_type', $training_type);}
			
			$this->db->where('blended_registration.batch_code',$batch_code);
			$this->db->where('blended_registration.pay_status',1);
			$this->db->where('payment_transaction.status',1);
			$this->db->where('exam_invoice.app_type','T');
			$this->db->where('payment_transaction.pay_type',10);
			$this->db->where('exam_invoice.invoice_image !=', '');
			$this->db->where('payment_transaction.transaction_no !=', '');
			$this->db->join('payment_transaction','blended_registration.blended_id = payment_transaction.ref_id', 'left');
			$this->db->join('exam_invoice', 'payment_transaction.id = exam_invoice.pay_txn_id', 'left');
			$mem_info = $this->master_model->getRecords('blended_registration','','createdon,blended_registration.member_no,program_code,batch_code,zone_code,training_type,blended_registration.center_code,start_date,end_date,invoice_image,attempt');
			echo $this->db->last_query(); die;
			$data['mem_info'] = $mem_info;
			$this->load->view('trainingstat/member_list',$data);
		}
		else
		{
			$this->db->where('blended_registration.batch_code',$batch_code);
			$this->db->where('blended_registration.pay_status',1);
			$this->db->where('payment_transaction.status',1);
			$this->db->where('exam_invoice.app_type','T');
			$this->db->where('payment_transaction.pay_type',10);
			$this->db->where('exam_invoice.invoice_image !=', '');
			$this->db->where('payment_transaction.transaction_no !=', '');
			$this->db->join('payment_transaction','blended_registration.blended_id = payment_transaction.ref_id', 'left');
			$this->db->join('exam_invoice', 'payment_transaction.id = exam_invoice.pay_txn_id', 'left');
			$mem_info = $this->master_model->getRecords('blended_registration','','createdon,blended_registration.member_no,program_code,batch_code,zone_code,training_type,blended_registration.center_code,start_date,end_date,invoice_image,attempt');
			echo $this->db->last_query(); die;
		  	$data['mem_info'] = $mem_info;
		  	$this->load->view('trainingstat/member_list',$data);
		}  
    }
	
	public function unpaid_count()
	{
		if (isset($_POST['btnSearch'])) 
		{
			$program_code = $_POST["program_code"];
			$batch_code = $_POST["batch_code"];
			$training_type = $_POST['training_type'];
			$zone_code = $_POST['zone_code'];
			$center_code = $_POST['center_code'];
			
			if($program_code != ""){$this->db->where('program_code', $program_code);}
			if($zone_code != ""){$this->db->where('zone_code', $zone_code);}
			if($center_code != ""){$this->db->where('center_code', $center_code);}
			if($batch_code != ""){$this->db->where('batch_code', $batch_code);}
			if($training_type != ""){$this->db->where('training_type', $training_type);}
			
			
			$this->db->where('blended_registration.pay_status',1);
			$this->db->where('blended_registration.batch_code',$batch_code);
			$this->db->where('blended_registration.fee',0);
			
			$mem_info = $this->master_model->getRecords('blended_registration');
			//echo $this->db->last_query(); die;
			$data['mem_info'] = $mem_info;
			$this->load->view('trainingstat/unpaid_list',$data);
		}
		else
		{
			$this->db->where('blended_registration.pay_status',1);
			$this->db->where('blended_registration.fee',0);
			$mem_info = $this->master_model->getRecords('blended_registration');
		  	$data['mem_info'] = $mem_info;
		  	$this->load->view('trainingstat/unpaid_list',$data);
		}  
    
	}*/
	
	/*public function send_mail()
	{
		$regnumber = $this->uri->segment(3);
		$batchcode = $this->uri->segment(4);
		$email = '';
		$MerchantOrderNo= '';
		$institution_name ='';
		$attachpath="";
		$reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=>$regnumber,'batch_code' =>$batchcode,'pay_status' => 1)); 
		$email = 'prafull.tupe@esds.co.in';//$reg_info[0]['email'];
		 
	$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('ref_id'=>$reg_info[0]['blended_id'],'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount,receipt_no');
	$MerchantOrderNo= $payment_infoArr[0]['receipt_no'];
	//echo $this->db->last_query(); 
	
	//print_r($payment_infoArr); die;
	$examinvoice=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo));
	//echo $this->db->last_query();
	//print_r($examinvoice); die;
	 $invoice_image=explode('_', $examinvoice[0]['invoice_image']);
                
                    if($invoice_image[1] == 'TNZ'){
                      $attachpath ='uploads/blended_invoice/user/NZ/'.$examinvoice[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TSZ'){
                      $attachpath ='uploads/blended_invoice/user/SZ/'.$examinvoice[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TEZ'){
                      $attachpath ='uploads/blended_invoice/user/EZ/'.$examinvoice[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TCO'){
                      $attachpath ='uploads/blended_invoice/user/CO/'.$examinvoice[0]['invoice_image'];
                    }else{
                      $attachpath ='uploads/blended_invoice/user/CO/'.$examinvoice[0]['invoice_image'];
                    }
//echo $attachpath ; die;
									$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_emailer_client'));
									if(count($emailerSelfStr) > 0)
									{
										$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
										
										$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
										$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
										$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
										
										$institution_master = $this->master_model->getRecords('institution_master');
										$states             = $this->master_model->getRecords('state_master');
										$designation        = $this->master_model->getRecords('designation_master');
										if(count($designation)){ 
										 foreach($designation as $designation_row){
											if($reg_info[0]['designation']==$designation_row['dcode']){
												$designation_name = $designation_row['dname'];}
												} 
											}
										if(count($institution_master)){ 
										
										  foreach($institution_master as $institution_row){ 
											if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
											
												$institution_name = $institution_row['name'];}
											  }
											}
										
										if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
										if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
										if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
										
										$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
										
										if(count($qualificationArr)) 
										{
											$specify_qualification = $qualificationArr[0]['name'];
										}
										
										$training_type = $reg_info[0]['training_type'];
										
										if($training_type=="PC")
										{
											$training_type='Physical Classroom';
											$venue_name   = $reg_info[0]['venue_name'];
										}
										else
										{
											$training_type='Virtual Classes';
											$venue_name   = "-";
										}
										$center_name  = $reg_info[0]['center_name'];
										
										$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
										$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
										
										if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
										$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
										$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
										$selfstr3 = str_replace("#center_name#", "".$center_name."",  $selfstr2);
										$selfstr4 = str_replace("#venue_name#", "".$venue_name."", $selfstr3);
										$selfstr5 = str_replace("#start_date#", "".$start_date."", $selfstr4);
										$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
										
										$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);	
										$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr7);
										$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);
										$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);
										$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);
										$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);
										
										$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);
										$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);
										$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);
										$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);
										$selfstr17 = str_replace("#designation#", "".$designation_name."",  $selfstr16);
										$selfstr18 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr17);
										$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr18);
										$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);
										$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);
										$selfstr22 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr21);
										$selfstr23 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr22);
										$selfstr24 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr23);
										$selfstr25 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr24);
										$selfstr26 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr25);
										$selfstr27 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr26);
										$selfstr28 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr27);
										$selfstr29 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr28);
										$selfstr30 = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr29);
										$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr30);
										$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);
										$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);
										$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $selfstr33);
									$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
									  $final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
										
										$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "' LIMIT 1 ");
										//echo $this->db->last_query(); die;
										$emailsArr    = $emailsQry->row_array();
										 $emails  = $emailsArr['emails'];
										
										$self_mail_arr = array(
										'to'=>$email,
										
									//'to'=>'nirmala.menezes@yahoo.co.in',
										'from'=>$emailerSelfStr[0]['from'],
										'subject'=> $final_sub.' - <strong>Venue Correction',
										'message'=>$final_selfstr);	
											if($this->Emailsending->mailsend_attch($self_mail_arr,$attachpath))
											{
											$this->session->set_flashdata('success','Acknowledgment mail is sent successfully.');
												redirect('TrainingStat/');
												//echo $email. ' '.$regnumber; 
												}
												
									}	
									redirect('TrainingStat/');
		
	
	}*/
	
	public function send_mail()
	{
		$regnumber = $_POST['member_no'];
		$batchcode = $_POST['batch_id'];
		$batch_type=$_POST['batch_type'];
		$email = '';
		$MerchantOrderNo= '';
		$institution_name ='';
		$attachpath="";
		if($batch_type=='PC' || $batch_type=='VP')
		{
		$reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=>$regnumber,'batch_code' =>$batchcode,'pay_status' => 1)); 
		$email = $reg_info[0]['email'];
		 
		$payment_infoArr=$this->master_model->getRecords('payment_transaction',array('ref_id'=>$reg_info[0]['blended_id'],'member_regnumber'=>$reg_info[0]['member_no']),'transaction_no,date,amount,receipt_no');
		
		$MerchantOrderNo= $payment_infoArr[0]['receipt_no'];
		//echo $this->db->last_query(); 
		
		//print_r($payment_infoArr); die;
		$examinvoice=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo));
		//echo $this->db->last_query();
		//print_r($examinvoice); die;
		 $invoice_image=explode('_', $examinvoice[0]['invoice_image']);
                
                    if($invoice_image[1] == 'TNZ'){
                      $attachpath ='uploads/blended_invoice/user/NZ/'.$examinvoice[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TSZ'){
                      $attachpath ='uploads/blended_invoice/user/SZ/'.$examinvoice[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TEZ'){
                      $attachpath ='uploads/blended_invoice/user/EZ/'.$examinvoice[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TCO'){
                      $attachpath ='uploads/blended_invoice/user/CO/'.$examinvoice[0]['invoice_image'];
                    }else{
                      $attachpath ='uploads/blended_invoice/user/CO/'.$examinvoice[0]['invoice_image'];
                    }
//echo $attachpath ; die;
									$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_emailer_client'));
									if(count($emailerSelfStr) > 0)
									{
										$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
										
										$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
										$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
										$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
										
										$institution_master = $this->master_model->getRecords('institution_master');
										$states             = $this->master_model->getRecords('state_master');
										$designation        = $this->master_model->getRecords('designation_master');
										if(count($designation)){ 
										 foreach($designation as $designation_row){
											if($reg_info[0]['designation']==$designation_row['dcode']){
												$designation_name = $designation_row['dname'];}
												} 
											}
										if(count($institution_master)){ 
										
										  foreach($institution_master as $institution_row){ 
											if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
											
												$institution_name = $institution_row['name'];}
											  }
											}
										
										if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
										if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
										if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
										
										$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
										
										if(count($qualificationArr)) 
										{
											$specify_qualification = $qualificationArr[0]['name'];
										}
										
										$training_type = $reg_info[0]['training_type'];
										
										if($training_type=="PC")
										{
											$training_type='Physical Classroom';
											$venue_name   = $reg_info[0]['venue_name'];
										}
										else
										{
											$training_type='Virtual Classes';
											$venue_name   = "-";
										}
										$center_name  = $reg_info[0]['center_name'];
										
										$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
										$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
										
										if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
										$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
										$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
										$selfstr3 = str_replace("#center_name#", "".$center_name."",  $selfstr2);
										$selfstr4 = str_replace("#venue_name#", "".$venue_name."", $selfstr3);
										$selfstr5 = str_replace("#start_date#", "".$start_date."", $selfstr4);
										$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
										
										$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);	
										$selfstr8 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr7);
										$selfstr9 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr8);
										$selfstr10 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr9);
										$selfstr11 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr10);
										$selfstr12 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr11);
										
										$selfstr13 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr12);
										$selfstr14 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr13);
										$selfstr15 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr14);
										$selfstr16 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr15);
										$selfstr17 = str_replace("#designation#", "".$designation_name."",  $selfstr16);
										$selfstr18 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr17);
										$selfstr19 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr18);
										$selfstr20 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr19);
										$selfstr21 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr20);
										$selfstr22 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr21);
										$selfstr23 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr22);
										$selfstr24 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr23);
										$selfstr25 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr24);
										$selfstr26 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr25);
										$selfstr27 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr26);
										$selfstr28 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr27);
										$selfstr29 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr28);
										$selfstr30 = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr29);
										$selfstr31 = str_replace("#TRANSACTION_NO#", "".$payment_infoArr[0]['transaction_no']."",  $selfstr30);
										$selfstr32 = str_replace("#AMOUNT#", "".$payment_infoArr[0]['amount']."",  $selfstr31);
										$selfstr33 = str_replace("#STATUS#", "Transaction Successful",  $selfstr32);
										$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_infoArr[0]['date']))."",  $selfstr33);
										$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
									   $final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
										
										$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "' LIMIT 1 ");
										//echo $this->db->last_query(); die;
										$emailsArr    = $emailsQry->row_array();
										 $emails  = $emailsArr['emails'];
										$self_mail_arr = array(
										'to'=>$email,
										'from'=>$emailerSelfStr[0]['from'],
										'subject'=> $final_sub.' - <strong>Venue Correction',
										'message'=>$final_selfstr);	
										if($this->mailsend_attch($self_mail_arr,$attachpath))
											{
												$data_arr=array('success'=>'Acknowledgment mail is sent successfully','ans'=>1);	
											}
											else
											{
												$data_arr=array('error'=>'Error While Sending Mail','ans'=>0);	
											}
									}	
		}
		else if($batch_type=='VC')
		{
			 if (!empty($regnumber)) {
	 						$this->db->limit(1,0);
						    $reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=> $regnumber,'batch_code' => $batchcode,'pay_status' => 1)); 
							//echo $this->db->last_query();exit;
							}
							$emails = $reg_info[0]['email'];
							$last_id=$reg_info[0]['blended_id'];					
						//echo $this->db->last_query(); die;	
					if($reg_info[0]['member_no'] == $regnumber)
					{
						$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_virtual_emailer_client'));
						if(count($emailerSelfStr) > 0)
						{
							$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
							$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
							$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
							$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
							$institution_master = $this->master_model->getRecords('institution_master');
							$states             = $this->master_model->getRecords('state_master');
							$designation        = $this->master_model->getRecords('designation_master');
							if(count($designation)){
							 foreach($designation as $designation_row){
								if($reg_info[0]['designation']==$designation_row['dcode']){
									$designation_name = $designation_row['dname'];}} 
								}
							if(count($institution_master)){
							  foreach($institution_master as $institution_row){ 	
								if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
									$institution_name = $institution_row['name'];}
								  }
								}
							if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
							if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
							if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
							$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
							if(count($qualificationArr)){
								$specify_qualification = $qualificationArr[0]['name'];
							}
							$training_type = $reg_info[0]['training_type'];
							if($training_type=="PC"){
								$training_type='Physical Classroom';
							}
							else{
								$training_type='Virtual Classes';
							}
							$venue_name   = "-";
							$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
							$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
							if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
							$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
							$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
							$selfstr3 = str_replace("#center_name#", "".$reg_info[0]['center_name']."",  $selfstr2);
							$selfstr4 = str_replace("#venue_name#", "".$venue_name."",  $selfstr3);
							$selfstr5 = str_replace("#start_date#", "".$start_date."",  $selfstr4);
							$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
							$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);
							$selfstr8 = str_replace("#fees#", "0",  $selfstr7);
							$selfstr9 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr8);
							$selfstr10 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr9);
							$selfstr11 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr10);
							$selfstr12 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr11);
							$selfstr13 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr12);
							$selfstr14 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr13);
							$selfstr15 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr14);
							$selfstr16 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr15);
							$selfstr17 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr16);
							$selfstr18 = str_replace("#designation#", "".$designation_name."",  $selfstr17);
							$selfstr19 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr18);
							$selfstr20 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr19);
							$selfstr21 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr20);
							$selfstr22 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr21);
							$selfstr23 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr22);
							$selfstr24 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr23);
							$selfstr25 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr24);
							$selfstr26 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr25);
							$selfstr27 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr26);
							$selfstr28 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr27);
							$selfstr29 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr28);
							$selfstr30 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr29);
							$final_selfstr = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr30);
							$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
							$final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
							/* Get Client Emails Details */
							$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");
							$emailsArr    = $emailsQry->row_array();
							//$emails  = $emailsArr['emails'];
							$self_mail_arr = array('to'=>$emails,
							'from'=>$emailerSelfStr[0]['from'],
							'subject'=>$final_sub,
							'message'=>$final_selfstr);					
							if($this->mailsend_attch($self_mail_arr,$attachpath))
							{
							$data_arr=array('success'=>'Acknowledgment mail is sent successfully','ans'=>1);	
							}
							else
							{
							$data_arr=array('error'=>'Error While Sending Mail','ans'=>0);	
							}
						}
				}
		}
			echo json_encode($data_arr);
	}
	
	public function send_mail_vc()
	{
		$regnumber = $this->uri->segment(5);
		$program_code = $this->uri->segment(6);
		$last_id='';
		$emails='';
		$attachpath=""; 
					 if (!empty($regnumber)) {
	 						$this->db->limit(1,0);
                            $reg_info = $this->Master_model->getRecords('blended_registration',array('member_no'=> $regnumber,'program_code' => $program_code,'pay_status' => 1,'fee' => 0)); }
							$emails = 'Swati.Watpade@esds.co.in';//$reg_info[0]['email'];
							$last_id=$reg_info[0]['blended_id'];					
						//echo $this->db->last_query(); die;	
					if($reg_info[0]['member_no'] == $regnumber)
					{
						$emailerSelfStr=$this->master_model->getRecords('emailer',array('emailer_name'=>'blended_virtual_emailer_client'));
						if(count($emailerSelfStr) > 0)
						{
							$designation_name=$undergraduate_name=$graduate_name=$postgraduate_name=$specify_qualification=$institution_name=$pay="";
							$undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
							$graduate=$this->master_model->getRecords('qualification', array('type' => 'GR'));
							$postgraduate=$this->master_model->getRecords('qualification', array('type'=> 'PG'));
							$institution_master = $this->master_model->getRecords('institution_master');
							$states             = $this->master_model->getRecords('state_master');
							$designation        = $this->master_model->getRecords('designation_master');
							if(count($designation)){
							 foreach($designation as $designation_row){
								if($reg_info[0]['designation']==$designation_row['dcode']){
									$designation_name = $designation_row['dname'];}} 
								}
							if(count($institution_master)){
							  foreach($institution_master as $institution_row){ 	
								if($reg_info[0]['associatedinstitute']==$institution_row['institude_id']){
									$institution_name = $institution_row['name'];}
								  }
								}
							if($reg_info[0]['qualification']=='U'){$undergraduate_name = 'Under Graduate';}
							if($reg_info[0]['qualification']=='G'){$graduate_name = 'Graduate';}
							if($reg_info[0]['qualification']=='P'){$postgraduate_name = 'Post Graduate';}	
							$qualificationArr=$this->master_model->getRecords('qualification',array('qid'=>$reg_info[0]['specify_qualification']),'name','','1');
							if(count($qualificationArr)){
								$specify_qualification = $qualificationArr[0]['name'];
							}
							$training_type = $reg_info[0]['training_type'];
							if($training_type=="PC"){
								$training_type='Physical Classroom';
							}
							else{
								$training_type='Virtual Classes';
							}
							$venue_name   = "-";
							$start_date   = date("d-M-Y", strtotime($reg_info[0]['start_date']));
							$end_date     = date("d-M-Y", strtotime($reg_info[0]['end_date']));
							
							if($reg_info[0]['pay_status'] == 1){ $pay = "Success";}
							
							$selfstr1 = str_replace("#regnumber#", "".$reg_info[0]['member_no']."",  $emailerSelfStr[0]['emailer_text']);
							$selfstr2 = str_replace("#program_name#", "".$reg_info[0]['program_name']."",  $selfstr1);
							$selfstr3 = str_replace("#center_name#", "".$reg_info[0]['center_name']."",  $selfstr2);
							$selfstr4 = str_replace("#venue_name#", "".$venue_name."",  $selfstr3);
							$selfstr5 = str_replace("#start_date#", "".$start_date."",  $selfstr4);
							$selfstr6 = str_replace("#end_date#", "".$end_date."",  $selfstr5);
							$selfstr7 = str_replace("#training_type#", "".$training_type."",  $selfstr6);
							$selfstr8 = str_replace("#fees#", "0",  $selfstr7);
							$selfstr9 = str_replace("#name#", "".$reg_info[0]['namesub']." ".$reg_info[0]['firstname']." ".$reg_info[0]['middlename']." ".$reg_info[0]['lastname'],  $selfstr8);
							$selfstr10 = str_replace("#address1#", "".$reg_info[0]['address1']."",  $selfstr9);
							$selfstr11 = str_replace("#address2#", "".$reg_info[0]['address2']."",  $selfstr10);
							$selfstr12 = str_replace("#address3#", "".$reg_info[0]['address3']."",  $selfstr11);
							$selfstr13 = str_replace("#address4#", "".$reg_info[0]['address4']."",  $selfstr12);
							$selfstr14 = str_replace("#district#", "".$reg_info[0]['district']."",  $selfstr13);
							$selfstr15 = str_replace("#city#", "".$reg_info[0]['city']."",  $selfstr14);
							$selfstr16 = str_replace("#state#", "".$reg_info[0]['state']."",  $selfstr15);
							$selfstr17 = str_replace("#pincode#", "".$reg_info[0]['pincode']."",  $selfstr16);
							$selfstr18 = str_replace("#designation#", "".$designation_name."",  $selfstr17);
							$selfstr19 = str_replace("#associatedinstitute#", "".$institution_name."",  $selfstr18);
							$selfstr20 = str_replace("#dateofbirth#", "".$reg_info[0]['dateofbirth']."",  $selfstr19);
							$selfstr21 = str_replace("#email#", "".$reg_info[0]['email']."",  $selfstr20);
							$selfstr22 = str_replace("#mobile#", "".$reg_info[0]['mobile']."",  $selfstr21);
							$selfstr23 = str_replace("#res_stdcode#", "".$reg_info[0]['res_stdcode']."",  $selfstr22);
							$selfstr24 = str_replace("#residential_phone#", "".$reg_info[0]['residential_phone']."",  $selfstr23);
							$selfstr25 = str_replace("#stdcode#", "".$reg_info[0]['stdcode']."",  $selfstr24);
							$selfstr26 = str_replace("#office_phone#", "".$reg_info[0]['office_phone']."",  $selfstr25);
							$selfstr27 = str_replace("#qualification#", "".$undergraduate_name.$graduate_name.$postgraduate_name."",  $selfstr26);
							$selfstr28 = str_replace("#specify_qualification#", "".$specify_qualification."",  $selfstr27);
							$selfstr29 = str_replace("#emergency_name#", "".$reg_info[0]['emergency_name']."",  $selfstr28);
							$selfstr30 = str_replace("#emergency_contact_no#", "".$reg_info[0]['emergency_contact_no']."",  $selfstr29);
							$final_selfstr = str_replace("#blood_group#", "".$reg_info[0]['blood_group']."",  $selfstr30);
							$s1 = str_replace("#program_code#", "".$reg_info[0]['program_code'],$emailerSelfStr[0]['subject']);
									  $final_sub = str_replace("#Batch_code#", "".$reg_info[0]['batch_code']."",  $s1);
							
							/* Get Client Emails Details */
							$emailsQry = $this->db->query("SELECT emails FROM offline_email_master WHERE zone_code = '" . $reg_info[0]['zone_code'] . "' AND program_code = '" . $reg_info[0]['program_code'] . "' AND batch_code = '" . $reg_info[0]['batch_code'] . "'AND isdelete = 0 LIMIT 1 ");
							
							
							$emailsArr    = $emailsQry->row_array();
							//$emails  = $emailsArr['emails'];
							$self_mail_arr = array(



							'to'=>$emails,

							
							//'to'=>'kyciibf@gmail.com',
							'from'=>$emailerSelfStr[0]['from'],
							'subject'=>$final_sub,
							'message'=>$final_selfstr);					
							
							if($this->mailsend_attch($self_mail_arr,$attachpath))
											{
												$this->session->set_flashdata('success','Acknowledgment mail is sent successfully.');
										redirect('trainingstat/TrainingStat/unpaid_count');
												//echo $regnumber;
												//echo '**ghjgfhj'; 
												//echo  $emails;
												}
											
						}
					}

	}
	
		public function download_CSV($batch_code,$training_type,$center_name)
	{
		 $csv = " Blended Course member registration details for ".$center_name."  ".$batch_code." ".$training_type." \n\n";
		 $csv.= "Sr no.,Membership no.,Name sub,First Name,Last Name,Bank Name,Email,Mobile,Fee,Attempt \n";//Column headers
	

	$subquery = $this->db->query(" SELECT member_no,namesub,firstname,lastname,name,email,mobile,fee,attempt  FROM `blended_registration` LEFT JOIN institution_master ON blended_registration.associatedinstitute=institution_master.institude_id WHERE   `training_type` LIKE '".$training_type."' AND `batch_code` LIKE '".$batch_code."' AND `pay_status` = 1
");

			$result = $subquery->result_array();
			
	
			if(!empty($result))
			{
				$i=1;
		foreach($result as $record)
		{
			
					
			// print_r($record);exit;
			 $csv.= $i.','.$record['member_no'].','.$record['namesub'].',"'.$record['firstname'].'",'.$record['lastname'].','.$record['name'].','.$record['email'].','.$record['mobile'].','.$record['fee'].','.$record['attempt']."\n";
			 $i++;
		}
	}
        $filename = "Blended_course_member_registration_details_for_".$center_name."_".$batch_code."_".$training_type.".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
	}


	  public function cc_member_list()
    {
	
	
	#--------------------contcat classes --------------------------#
		//SELECT *  FROM `contact_classes_registration` WHERE `program_code` LIKE '20' AND `program_prd` = 417 AND `pay_status` = 1
		$course_info=array();
	//	$this->db->where('course_code', $courcecode);
		$this->db->group_by('course_code,exam_prd'); 
	
 		$course_info = $this->master_model->getRecords('contact_classes_cource_activation_master','','course_code,exam_prd');
	foreach($course_info  as $val)
	{
		$program_code[]=$val['course_code'];
		$program_prd[]=$val['exam_prd'];
		
	}

	
		$this->db->where_in('program_code',$program_code);
		$this->db->where_in('program_prd',$program_prd);
		$this->db->where('pay_status',1);
          $cc_mem_info = $this->master_model->getRecords('contact_classes_registration');
	
	
	#-------------------end -contact classes -------------------------#
	
		  
		    $data['cc_mem_info'] = $cc_mem_info;
		//	$data['cc_sub_info'] = $cc_sub_info;
			
			$this->load->view('trainingstat/cc_member_list',$data);
		

        //  $this->load->view('admin/finquest_dashboard/dashboard');
    }
		  public function cc_subject_list()
    {
	
	
	#--------------------contcat classes --------------------------#
		
		$cc_subject=$this->db->query(	"SELECT contact_classes_Subject_registration.`sub_code`,contact_classes_Subject_registration.`sub_name`,contact_classes_Subject_registration.`center_code`,count(contact_classes_Subject_registration.`id`)as total_reg,capacity FROM `contact_classes_Subject_registration` INNER JOIN contact_classes_subject_master ON contact_classes_subject_master.exam_prd=contact_classes_Subject_registration.`program_prd` AND contact_classes_subject_master.sub_code=contact_classes_Subject_registration.`sub_code` AND contact_classes_subject_master.center_code=contact_classes_Subject_registration.`center_code` WHERE contact_classes_Subject_registration.`program_prd`IN(219,803) AND contact_classes_Subject_registration.`program_code` IN(20,21,60) group by `program_code`,`program_prd`,`sub_code`,`center_code`");
						$cc_subject_list=  $cc_subject->result_array();
		  
	
	#-------------------end -contact classes -------------------------#
	
		  
		    $data['cc_subject_list'] = $cc_subject_list;
		//	$data['cc_sub_info'] = $cc_sub_info;
			
			$this->load->view('trainingstat/cc_subject_count',$data);
		

        //  $this->load->view('admin/finquest_dashboard/dashboard');
    }	
	public function mailsend_attch($info_arr,$path)	{	
	$this->Emailsending->setting_smtp();	
	$this->email->from('logs@iibf.esdsconnect.com',"IIBF"); 	
	$this->email->to($info_arr['to']);	
	$this->email->reply_to('noreply@iibf.org.in', 'IIBF');	
	$this->email->cc('vratesh@iibf.org.in');	
	//$this->email->bcc('chaitali.jadhav@esds.co.in');		
	$this->email->subject($info_arr['subject']);		
	$this->email->message($info_arr['message']);		
	if(is_array($path))			{	
	foreach($path as $row)				
	{					
	$this->email->attach($row);	
	}	
	}			
	else		
		
		{	
		if($path!=NULL || $path!='')
			{			
		$this->email->attach($path);
		}		
		}			
		if($this->email->send())	
			{			
		$this->email->clear(TRUE);	
		return true;	
		}					
		}
#-----------------------Deactive batch--------------------------------#
public function Deactive_batch()
{
	//echo '000';
	if(isset($_GET['batch_code']))
	{
		$batch_code=$_GET['batch_code'];
	
	
	//Offline mail table update 
	  $this->master_model->updateRecord('offline_email_master', array('isdelete' =>1) , array('batch_code' =>$batch_code, 'isdelete' =>0));
	  
	 //blended_dates
	   $this->master_model->updateRecord('blended_dates', array('isdelete' =>1) , array('batch_code' =>$batch_code, 'isdelete' =>0));
	   
	  //blended_fee_master
	  $this->master_model->updateRecord('blended_fee_master', array('fee_delete' =>1) , array('batch_code' =>$batch_code, 'fee_delete' =>0));

	//blended_program_activation_master
	 $this->master_model->updateRecord('blended_program_activation_master', array('program_activation_delete' =>1) , array('batch_code' =>$batch_code, 'program_activation_delete' =>0));
	 
	 //blended_venue_master
	 $this->master_model->updateRecord('blended_venue_master', array('isdeleted' =>1) , array('batch_code' =>$batch_code, 'isdeleted' =>0));

	}
}
}
