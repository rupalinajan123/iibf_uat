<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sendadmitcardmail extends CI_Controller 
{
	public function __construct()
	{
		 parent::__construct(); 
		 $this->load->model('Master_model');
		 $this->load->library('email');
		 $this->load->model('Emailsending');
		 $this->load->helper('custom_contact_classes_invoice_helper');
		 $this->load->helper('custom_admitcard_helper');
		 $this->load->helper('bulk_invoice_helper');
		 $this->load->helper('bulk_admitcard_helper');
		 $this->load->helper('custom_invoice_helper');
		 $this->load->helper('blended_invoice_custom_helper');
		 $this->load->helper('bulk_calculate_tds_discount_helper');
		 $this->load->helper('bulk_proforma_invoice_helper');
		 $this->load->helper('renewal_invoice_helper');
		 error_reporting(E_ALL);
	}
	public function generateAndSend(){     
		
		$exam_period = 121;
		$mem_exam_id = array(5394481);
		## Code to get member regnumber from admin_card_details
		for($i=0;$i<count($mem_exam_id);$i++)
		{
			$this->db->where('mem_exam_id',$mem_exam_id[$i]);
			$result = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd,admitcard_image'); 			
			echo $path = genarate_admitcard_custom_new($result[0]['mem_mem_no'],$result[0]['exm_cd'],$exam_period);  
			echo "<br/>"; 
			## Code to send mail
			$this->db->where('exam_code',$result[0]['exm_cd']);
			$exam_name = $this->master_model->getRecords('exam_master','','description');
			if($result[0]['admitcard_image'] != "")
			{
				$final_str = 'Hello Sir/Madam <br/><br/>';
				$final_str.= 'Please check your admit card letter for '.$exam_name[0]['description'].' examination';   
				$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
				  
				$attachpath = "uploads/admitcardpdf/".$result[0]['admitcard_image'];  
				$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$result[0]['mem_mem_no'],'isactive'=>'1'),'email,mobile');   
				$info_arr=array('to'=>$email[0]['email'],
								'from'=>'noreply@iibf.org.in',
								'subject'=>'Revised Admit Letter',
								'message'=>$final_str
							); 
				$files=array($attachpath);
				if($this->Emailsending->mailsend_attch($info_arr,$files)){
					echo "Mail send to ==> ".$result[0]['mem_mem_no'];
					echo "<br/>";  
					$log_data['mem_mem_no'] = $result[0]['mem_mem_no'];
					$log_data['exam_id'] = $mem_exam_id[$i];
					$log_data['email'] = $email[0]['email'];
					$log_data['mail_sent'] = 'Yes';
					$this->db->insert('admit_card_settlement', $log_data);
				}
			}else{
				echo "No admit card found for ".$result[0]['mem_mem_no'];
				$log_data['mem_mem_no'] = $result[0]['mem_mem_no'];
				$log_data['exam_id'] = $mem_exam_id[$i];
				$log_data['email'] = $email[0]['email'];
				$log_data['mail_sent'] = 'No';
				$this->db->insert('admit_card_settlement', $log_data);
			}
		}
	}
	public function generateAndSendNew(){     
		
		$exam_period = 121; 
		$this->db->where('mail_send',0);
		$this->db->where('admit_update',1);
		$this->db->limit(20);
		$mem_exam_id = $this->master_model->getRecords('cs2c_invoice_settelment','','member_exam_id');
		//echo $this->db->last_query();
		//print_r($mem_exam_id);
		//exit;
		foreach($mem_exam_id as $rec)
		{
			## Code to get member regnumber from admin_card_details
			$this->db->where('mem_exam_id',$rec['member_exam_id']);
			$result = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd,admitcard_image'); 			
			echo $path = genarate_admitcard_custom_new($result[0]['mem_mem_no'],$result[0]['exm_cd'],$exam_period);  
			echo "<br/>"; 
			## Code to send mail
			$this->db->where('exam_code',$result[0]['exm_cd']);
			$exam_name = $this->master_model->getRecords('exam_master','','description');
			if($result[0]['admitcard_image'] != "")
			{
				$final_str = 'Hello Sir/Madam <br/><br/>';
				$final_str.= 'Please check your admit card letter for '.$exam_name[0]['description'].' examination';   
				$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
				  
				$attachpath = "uploads/admitcardpdf/".$result[0]['admitcard_image'];  
				$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$result[0]['mem_mem_no'],'isactive'=>'1'),'email,mobile');   
				$info_arr=array('to'=>$email[0]['email'],
								'from'=>'noreply@iibf.org.in',
								'subject'=>'Revised Admit Letter',
								'message'=>$final_str
							); 
				$files=array($attachpath);
				if($this->Emailsending->mailsend_attch($info_arr,$files)){
					echo "Mail send to ==> ".$result[0]['mem_mem_no'];
					echo "<br/>";  
					$update_data = array('mail_send'=>'1');
					$this->master_model->updateRecord('cs2c_invoice_settelment',$update_data,array('member_exam_id'=>$rec['member_exam_id']));	
					//echo $this->db->last_query();
					$log_data['mem_mem_no'] = $result[0]['mem_mem_no'];
					$log_data['exam_id'] = $rec['member_exam_id'];
					$log_data['email'] = $email[0]['email'];
					$log_data['mail_sent'] = 'Yes';
					$this->db->insert('admit_card_settlement', $log_data);
				}
			}else{
				echo "No admit card found for ".$result[0]['mem_mem_no'];
				$log_data['mem_mem_no'] = $result[0]['mem_mem_no'];
				$log_data['exam_id'] = $rec['member_exam_id'];
				$log_data['email'] = $email[0]['email'];
				$log_data['mail_sent'] = 'No';
				$this->db->insert('admit_card_settlement', $log_data);
			}
		}
	}
	public function processPending(){     
		
		$exam_period = 121; 
		$img_name = '';
		$this->db->where('mail_sent',0);
		$this->db->limit(30);
		$mem_exam_id = $this->master_model->getRecords('admitcard_pending','','member_exam_id');
		echo $this->db->last_query();
		//print_r($mem_exam_id);
		//exit;
		foreach($mem_exam_id as $rec)
		{
			## Code to get member regnumber from admin_card_details
			$this->db->where('mem_exam_id',$rec['member_exam_id']);
			$result = $this->master_model->getRecords('admit_card_details','','mem_mem_no,exm_cd,admitcard_image'); 			
			echo $path = genarate_admitcard_custom_new($result[0]['mem_mem_no'],$result[0]['exm_cd'],$exam_period);  
			echo "<br/>"; 
			
			## Update admit card image
			if($result[0]['admitcard_image'] == "")
			{
				$img  = explode("uploads/admitcardpdf/",$path);
				echo "<br/>".$img_name = $img[1];
				$update_data = array('admitcard_image'=>$img_name);
				$this->master_model->updateRecord('admit_card_details',$update_data,array('mem_exam_id'=>$rec['member_exam_id']));	
				echo $this->db->last_query();
			}else{
				$img_name = $result[0]['admitcard_image'];
			}
			
			## Code to send mail
			$this->db->where('exam_code',$result[0]['exm_cd']);
			$exam_name = $this->master_model->getRecords('exam_master','','description');
			if($img_name != "")
			{
				$final_str = 'Hello Sir/Madam <br/><br/>';
				$final_str.= 'Please check your admit card letter for '.$exam_name[0]['description'].' examination';   
				$final_str.= '<br/><br/>';
				$final_str.= 'Regards,';
				$final_str.= '<br/>';
				$final_str.= 'IIBF TEAM'; 
				  
				$attachpath = "uploads/admitcardpdf/".$result[0]['admitcard_image'];  
				$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$result[0]['mem_mem_no'],'isactive'=>'1'),'email,mobile');   
				$info_arr=array('to'=>$email[0]['email'],
								'from'=>'noreply@iibf.org.in',
								'subject'=>'Revised Admit Letter',
								'message'=>$final_str
							); 
				$files=array($attachpath);
				if($this->Emailsending->mailsend_attch($info_arr,$files)){
					echo "Mail send to ==> ".$result[0]['mem_mem_no'];
					echo "<br/>";  
					$update_data = array('mail_sent'=>'1','email'=>$email[0]['email']);
					$this->master_model->updateRecord('admitcard_pending',$update_data,array('member_exam_id'=>$rec['member_exam_id']));	
					//echo $this->db->last_query();
					$log_data['mem_mem_no'] = $result[0]['mem_mem_no'];
					$log_data['exam_id'] = $rec['member_exam_id'];
					$log_data['email'] = $email[0]['email'];
					$log_data['mail_sent'] = 'Yes';
					$this->db->insert('admit_card_settlement', $log_data);
				}
			}else{
				echo "No admit card found for ".$result[0]['mem_mem_no'];
				$update_data = array('mail_sent'=>'0','email'=>$email[0]['email']);
				$this->master_model->updateRecord('admitcard_pending',$update_data,array('member_exam_id'=>$rec['member_exam_id']));	
			}
		}
	}
}
?>