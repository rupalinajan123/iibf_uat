<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Custome_image_function_hit extends CI_Controller {

	private $USERDATA=array();		

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
		
	} 
	

	


	//auto mail sending of invoices and admitcrad

	public function settlement_mail_send(){  

		$record_details = $this->master_model->getRecords('exam_invoice_settlement',array('refund_case'=> '0','email_send'=>'0' ),'id,exam_code,exam_period,member_regnumber,receipt_no');

        if(count($record_details) > 0){
  
        	foreach($record_details as $record_details){ 

        // send mail for invoice		
		$receipt_array = array($record_details['receipt_no']);    
		$this->db->where_in('receipt_no',$receipt_array); 
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
			
			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['member_no'],'isactive'=>'1'),'email'); 
			
			$info_arr=array('to'=>$email[0]['email'],
							//'to'=>'chaitali.jadhav@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Exam Enrollment Acknowledgement',
							'message'=>$final_str
						);
						
			
			$files=array($attachpath);
			
			if(file_exists($attachpath)){

				if($this->Emailsending->mailsend_attch($info_arr,$files)){
				
				
				$update_data = array(
	          	'email_send'=>1,
	             ); 
			    $sql= $this->master_model->updateRecord('exam_invoice_settlement',$update_data,array('id' => $record_details['id']));

			    }
			
			}else{
				
			}
					
		 }

		//send mail of admitcrad
		if($record_details['exam_code'] != 101 || $record_details['exam_code'] != 45 || $record_details['exam_code'] != 57){


		$member_array = array($record_details['member_regnumber']);             
		
		$this->db->distinct('mem_mem_no');   
		$this->db->where('remark',1);
		$this->db->where('exm_prd',$record_details['exam_period']);
		$this->db->where('admitcard_image !=','');
		$this->db->where_in('mem_mem_no',$member_array);
		$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image,exm_cd'); 
		    
		foreach($sql as $rec){ 
			
			$this->db->where('exam_code',$rec['exm_cd']);
			$exam_name = $this->master_model->getRecords('exam_master','','description');
			
			$final_str = 'Hello Sir/Madam <br/><br/>';
			
			$final_str.= 'Please check your new attached revised admit card letter for '.$exam_name[0]['description'].' examination';   
			$final_str.= '<br/><br/>';
			$final_str.= 'Regards,';
			$final_str.= '<br/>';
			$final_str.= 'IIBF TEAM'; 
			  
			$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
			
			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email,mobile');   
			$info_arr=array('to'=>$email[0]['email'],
							//'to'=>'chaitali.jadhav@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Revised Admit Letter',
							'message'=>$final_str
						); 
			$files=array($attachpath);
			if(file_exists($attachpath)){
			if($this->Emailsending->mailsend_attch($info_arr,$files)){
				
				 $update_data = array(
	          	 'email_send'=>1,
	             ); 
			     $sql= $this->master_model->updateRecord('exam_invoice_settlement',$update_data,array('id' => $record_details['id']));

			     }
			  }else{
			  	
			  }
	        }
	       }

	       }
        } else{
        	
        } 

	}

//image name generated but image notavailable
	public function find_images(){
		$start_point  = 0;
		$end_point    = 500;
		$current_date =date('Y-m-d');
		/*Cron LIMIT */
		$this->db->where(" (created_at) = '".$current_date."'");
		$this->db->where(" module_type",'Image_not_generated');
		$is_cron_exists = $this->Master_model->getRecords('cron_limit'); 
	  	if(count($is_cron_exists)  > 0 && !empty($is_cron_exists))
		{
			$start_point = count($is_cron_exists)*$end_point;
			 
		}
		$this->cron_add($start_point,$end_point,$current_date);
		/*Cron LIMIT */
					      $this->db->where('date >=','2019-10-01');
					      $this->db->where('date <=','2019-10-11');
			              $this->db->limit($end_point,$start_point);
		$payment = $this->master_model->getRecords('payment_transaction',array('status'=>1),'receipt_no');   
		
		$oldfilepath_user=$oldfilepath_supplier='';
		if(count($payment) > 0){

		foreach($payment as $payment){ 
			                 
			                
			$record_details = $this->master_model->getRecords('exam_invoice',array('receipt_no'=> $payment['receipt_no'],'invoice_image !='=>''),'invoice_id,invoice_image,receipt_no,app_type');
            if(count($record_details) > 0){
            	if($record_details[0]['app_type'] =='A' || $record_details[0]['app_type'] =='H'){

            		$oldfilepath_supplier ='uploads/drainvoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='B'){

            		$oldfilepath_user ='uploads/bnqinvoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='C'){
            		$oldfilepath_user ='uploads/dupcertinvoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='D'){

            		$oldfilepath_user ='uploads/dupicardinvoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='E'){
            		
            		
            		$invoice_image=explode('_', $record_details[0]['invoice_image']);
                    if($invoice_image[0] == 'TUNZ'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/user/NZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[0] == 'TUSZ'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/user/SZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[0] == 'TUEZ'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/user/EZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[0] == 'TUCO'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/user/CO/'.$record_details[0]['invoice_image'];
                    }else{
                    	$oldfilepath_user ='uploads/contact_classes_invoice/user/CO/'.$record_details[0]['invoice_image'];
                    }

            	}elseif($record_details[0]['app_type'] =='F'){

            		$oldfilepath_user ='uploads/finquestinvoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='I'){

            		$oldfilepath_supplier ='uploads/draexaminvoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='K' || $record_details[0]['app_type'] =='L' || $record_details[0]['app_type'] =='O'){

            		$oldfilepath_user ='uploads/examinvoice/user/'.$record_details[0]['invoice_image'];
            	
            	}elseif($record_details[0]['app_type'] =='M'){

            		$oldfilepath_user ='uploads/ampinvoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='N'){

            		$oldfilepath_user ='uploads/reginvoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='P'){

            		$oldfilepath_user ='uploads/cpdinvoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='R'){
            		$oldfilepath_user ='uploads/reginvoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='T'){

            		$invoice_image=explode('_', $record_details[0]['invoice_image']);
            		
                    if($invoice_image[1] == 'TNZ'){
                    	$oldfilepath_user ='uploads/blended_invoice/user/NZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TSZ'){
                    	$oldfilepath_user ='uploads/blended_invoice/user/SZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TEZ'){
                    	$oldfilepath_user ='uploads/blended_invoice/user/EZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TCO'){
                    	$oldfilepath_user ='uploads/blended_invoice/user/CO/'.$record_details[0]['invoice_image'];
                    }else{
                    	$oldfilepath_user ='uploads/blended_invoice/user/CO/'.$record_details[0]['invoice_image'];
                    }


            	}elseif($record_details[0]['app_type'] =='V'){
            		$oldfilepath_user ='uploads/vision_invoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='W'){
            		$oldfilepath_user ='uploads/agency_renewal_invoice/user/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='Z'){
            		$oldfilepath_user ='uploads/bulkexaminvoice/user/'.$record_details[0]['invoice_image'];

            	}


				if(!file_exists($oldfilepath_user))
					{     
						$exist = $this->master_model->getRecords('exam_invoice_images',array('invoice_id'=>$record_details[0]['invoice_id'],'type'=>'user'),'invoice_id'); 
						if(count($exist)>0){
							//echo "data exist";
						}else{
							$insert_arr= array(
							'invoice_id' => $record_details[0]['invoice_id'],
							'invoice_image' => $record_details[0]['invoice_image'],
							'receipt_no' => $record_details[0]['receipt_no'],
							'app_type' => $record_details[0]['app_type'],
							'type'=> 'user'
						);
						$this->master_model->insertRecord('exam_invoice_images',$insert_arr,true);

						}
						
						
					}

				if($record_details[0]['app_type'] =='A' || $record_details[0]['app_type'] =='H'){

            		$oldfilepath_supplier ='uploads/drainvoice/supplier/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='B'){

					$oldfilepath_user ='uploads/bnqinvoice/supplier/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='C'){

            		$oldfilepath_user ='uploads/dupcertinvoice/supplier/'.$record_details[0]['invoice_image'];
            		
            	}elseif($record_details[0]['app_type'] =='D'){

            			$oldfilepath_user ='uploads/dupicardinvoice/supplier/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='E'){

            		$invoice_image=explode('_', $record_details[0]['invoice_image']);
                    if($invoice_image[0] == 'TUNZ'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/supplier/NZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[0] == 'TUSZ'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/supplier/SZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[0] == 'TUEZ'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/supplier/EZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[0] == 'TUCO'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/supplier/CO/'.$record_details[0]['invoice_image'];
                    }else{
                    	$oldfilepath_user ='uploads/contact_classes_invoice/supplier/CO/'.$record_details[0]['invoice_image'];
                    }


            	}elseif($record_details[0]['app_type'] =='F'){

            		$oldfilepath_user ='uploads/finquestinvoice/supplier/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='I'){

            		$oldfilepath_supplier ='uploads/draexaminvoice/supplier/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='K' || $record_details[0]['app_type'] =='L' || $record_details[0]['app_type'] =='O'){

            		$oldfilepath_supplier ='uploads/examinvoice/supplier/'.$record_details[0]['invoice_image'];
            	
            	}elseif($record_details[0]['app_type'] =='M'){

            		$oldfilepath_user ='uploads/ampinvoice/supplier/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='N'){

            		$oldfilepath_user ='uploads/reginvoice/supplier/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='P'){

            		 $oldfilepath_user ='uploads/cpdinvoice/supplier/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='R'){

            		$oldfilepath_user ='uploads/reginvoice/supplier/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='T'){
                    
                    $invoice_image=explode('_', $record_details[0]['invoice_image']);
                    if($invoice_image[1] == 'TNZ'){
                    	$oldfilepath_user ='uploads/blended_invoice/supplier/NZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TSZ'){
                    	$oldfilepath_user ='uploads/blended_invoice/supplier/SZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TEZ'){
                    	$oldfilepath_user ='uploads/blended_invoice/supplier/EZ/'.$record_details[0]['invoice_image'];
                    }elseif($invoice_image[1] == 'TCO'){
                    	$oldfilepath_user ='uploads/blended_invoice/supplier/CO/'.$record_details[0]['invoice_image'];
                    }else{
                    	$oldfilepath_user ='uploads/blended_invoice/supplier/CO/'.$record_details[0]['invoice_image'];
                    }
            		
            	}elseif($record_details[0]['app_type'] =='V'){

            		$oldfilepath_user ='uploads/vision_invoice/supplier/'.$record_details[0]['invoice_image'];

            	}elseif($record_details[0]['app_type'] =='W'){

            		$oldfilepath_user ='uploads/agency_renewal_invoice/supplier/'.$record_details[0]['invoice_image'];

            	}
            	elseif($record_details[0]['app_type'] =='Z'){
            		$oldfilepath_user ='uploads/bulkexaminvoice/supplier/'.$record_details[0]['invoice_image'];

            	}
					
            	
				if(!file_exists($oldfilepath_supplier))
					{

						$exist = $this->master_model->getRecords('exam_invoice_images',array('invoice_id'=>$record_details[0]['invoice_id'],'type'=>'supplier'),'invoice_id'); 
						if(count($exist)>0){
							//echo "data exist";
						}else{
						$insert_arr= array(
							'invoice_id' => $record_details[0]['invoice_id'],
							'invoice_image' => $record_details[0]['invoice_image'],
							'receipt_no' => $record_details[0]['receipt_no'],
							'app_type' => $record_details[0]['app_type'],
							'type'=> 'supplier'
						);
						$this->master_model->insertRecord('exam_invoice_images',$insert_arr,true);
					}
						
					}
            }

		}

		}else{
			       $arr_update = array('created_at' => '0000-00-00');
					$this->master_model->updateRecord('cron_limit',$arr_update,array('created_at' => $current_date,'module_type'=>'Image_not_generated'));
		}
				
	}

	public function cron_add($start_point,$end_point,$current_date)
 	{
 	 
	 		$insert_limit = array(
										'start_point' => $start_point,
										'end_point'   => $end_point,
										'module_type' => 'Image_not_generated',
										'created_at'=>$current_date
									);
			$this->Master_model->insertRecord('cron_limit',$insert_limit);
 		 
 	}


 	//auto mail sending of image generated of invociecs

	public function image_generate_mail_send(){  

		$record_details = $this->master_model->getRecords('exam_invoice_images',array('image_status'=> '1','email_send'=>'0' ),'id,invoice_id,invoice_image,receipt_no,app_type,type');

		

        if(count($record_details) > 0){
  
        	foreach($record_details as $record_details){ 

        // send mail for invoice		
		$receipt_array = array($record_details['receipt_no']);    
		$this->db->where_in('receipt_no',$receipt_array); 
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
            if($record_details['app_type'] =='A' || $record_details['app_type'] =='H'){

            		$oldfilepath_supplier ='uploads/drainvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='B'){

            		$oldfilepath_user ='uploads/bnqinvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='C'){
            		$oldfilepath_user ='uploads/dupcertinvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='D'){

            		$oldfilepath_user ='uploads/dupicardinvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='E'){
            		
            		
            		$invoice_image=explode('_', $record_details['invoice_image']);
                    if($invoice_image[0] == 'TUNZ'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/'.$record_details['type'].'/NZ/'.$record_details['invoice_image'];
                    }elseif($invoice_image[0] == 'TUSZ'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/'.$record_details['type'].'/SZ/'.$record_details['invoice_image'];
                    }elseif($invoice_image[0] == 'TUEZ'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/'.$record_details['type'].'/EZ/'.$record_details['invoice_image'];
                    }elseif($invoice_image[0] == 'TUCO'){
                    	$oldfilepath_user ='uploads/contact_classes_invoice/'.$record_details['type'].'/CO/'.$record_details['invoice_image'];
                    }else{
                    	$oldfilepath_user ='uploads/contact_classes_invoice/'.$record_details['type'].'/CO/'.$record_details['invoice_image'];
                    }

            	}elseif($record_details['app_type'] =='F'){

            		$oldfilepath_user ='uploads/finquestinvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='I'){

            		$oldfilepath_supplier ='uploads/draexaminvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='K' || $record_details['app_type'] =='L' || $record_details['app_type'] =='O'){

            		$oldfilepath_user ='uploads/examinvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];
            	
            	}elseif($record_details['app_type'] =='M'){

            		$oldfilepath_user ='uploads/ampinvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='N'){

            		$oldfilepath_user ='uploads/reginvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='P'){

            		$oldfilepath_user ='uploads/cpdinvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='R'){
            		$oldfilepath_user ='uploads/reginvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='T'){

            		$invoice_image=explode('_', $record_details['invoice_image']);
            		
                    if($invoice_image[1] == 'TNZ'){
                    	$oldfilepath_user ='uploads/blended_invoice/'.$record_details['type'].'/NZ/'.$record_details['invoice_image'];
                    }elseif($invoice_image[1] == 'TSZ'){
                    	$oldfilepath_user ='uploads/blended_invoice/'.$record_details['type'].'/SZ/'.$record_details['invoice_image'];
                    }elseif($invoice_image[1] == 'TEZ'){
                    	$oldfilepath_user ='uploads/blended_invoice/'.$record_details['type'].'/EZ/'.$record_details['invoice_image'];
                    }elseif($invoice_image[1] == 'TCO'){
                    	$oldfilepath_user ='uploads/blended_invoice/'.$record_details['type'].'/CO/'.$record_details['invoice_image'];
                    }else{
                    	$oldfilepath_user ='uploads/blended_invoice/'.$record_details['type'].'/CO/'.$record_details['invoice_image'];
                    }


            	}elseif($record_details['app_type'] =='V'){
            		$oldfilepath_user ='uploads/vision_invoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='W'){
            		$oldfilepath_user ='uploads/agency_renewal_invoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}elseif($record_details['app_type'] =='Z'){
            		$oldfilepath_user ='uploads/bulkexaminvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            	}

			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['member_no'],'isactive'=>'1'),'email'); 
			
			$info_arr=array(//'to'=>$email[0]['email'],
							'to'=>'prafull.tupe@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Exam Enrollment Acknowledgement',
							'message'=>$final_str
						);
						
			
			$files=array($oldfilepath_user);
			
			if(file_exists($oldfilepath_user)){

				if($this->Emailsending->mailsend_attch($info_arr,$files)){
				
				
				$update_data = array(
	          	'email_send'=>1,
	             ); 
			    $sql= $this->master_model->updateRecord('exam_invoice_images',$update_data,array('id' => $record_details['id']));

			    }
			
			}else{
				
			}
					
		 }

		die;
	       }
        } else{
        	
        } 

	}
    //auto image generate function
	public function auto_invocie_image_generate(){

		$record_details = $this->master_model->getRecords('exam_invoice_images',array('image_status'=> '0'),'id,invoice_id,invoice_image,receipt_no,app_type,type');
		
        if(count($record_details) > 0){
  
        	foreach($record_details as $record_details){ 

        		if($record_details['app_type'] =='K' || $record_details['app_type'] =='L' || $record_details['app_type'] =='O'){
        			
        			$arr = array($record_details['invoice_id']);    
					 for($i=0;$i<sizeof($arr);$i++){
					 	 $path = custom_genarate_exam_invoice_newdesign($arr[$i]);
					 	
					 	 $oldfilepath_user ='uploads/examinvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

					 	 $files=array($oldfilepath_user);
			
						if(file_exists($oldfilepath_user)){

							$update_data = array(
				          	'image_status'=>1,
				             ); 
						     $sql= $this->master_model->updateRecord('exam_invoice_images',$update_data,array('id' => $record_details['id']));

						    }
						
						}
					 }elseif($record_details['app_type'] =='R' || $record_details['app_type'] =='N'){

            		 $oldfilepath_user ='uploads/reginvoice/'.$record_details['type'].'/'.$record_details['invoice_image'];

            		 $arr = array($record_details['invoice_id']);  
					 
					 for($i=0;$i<=0;$i++){
					 	echo $path = custom_genarate_reg_invoice_new($arr[$i]);
					 	
					 	 $files=array($oldfilepath_user);
			
						if(file_exists($oldfilepath_user)){

							$update_data = array(
				          	'image_status'=>1,
				             ); 
						     $sql= $this->master_model->updateRecord('exam_invoice_images',$update_data,array('id' => $record_details['id']));

						    }
						
						}

            	 } 
        		}
        	}
        }
	
}