<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkadmit extends CI_Controller {

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
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
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
		 
	} 
	
	public function admitcard()
	{
		$this->db->limit('5000');
		$result=$this->master_model->getRecords('jaiib_pass_sub',array('r_flag'=>0));
		if(count($result) > 0)
		{
			foreach($result as $row)
			{
				$getrecord=$this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$row['member_number'],'sub_cd'=>$row['subject_code'],'exm_cd'=>$this->config->item('examCodeJaiib'),'exm_prd'=>'219'));
					$update_data = array('r_flag' => 1);
					$this->master_model->updateRecord('jaiib_pass_sub',$update_data,array('id'=>$row['id']));	
				if(count($getrecord) > 0)
				{
					$update_data = array('find_status' => 1,'admit_card_id'=>$getrecord[0]['admitcard_id']);
					$this->master_model->updateRecord('jaiib_pass_sub',$update_data,array('id'=>$row['id']));	
				}
				
			}
			echo 'Total Record search='.(count($result));
			echo '<br>';
			echo 'Admi card Total Record found='.(count($this->master_model->getRecords('jaiib_pass_sub',array('find_status'=>1)))).'<br>';
			
		}
	}	
	
	public function admitcard_updated()
	{
		$this->db->limit('500');
		$result=$this->master_model->getRecords('jaiib_pass_sub',array('r_flag'=>0));
		if(count($result) > 0)
		{
			foreach($result as $row)
			{
				$getrecord=$this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$row['member_number'],'sub_cd'=>$row['subject_code'],'exm_cd'=>$this->config->item('examCodeJaiib'),'exm_prd'=>'219','remark'=>1));
					$update_data = array('r_flag' => 1);
					$this->master_model->updateRecord('jaiib_pass_sub',$update_data,array('id'=>$row['id']));	
				if(count($getrecord) > 0)
				{
					$update_data = array('find_status' => 1,'admit_card_id'=>$getrecord[0]['admitcard_id']);
					$this->master_model->updateRecord('jaiib_pass_sub',$update_data,array('id'=>$row['id']));	
				}
				
			}
			echo 'Total Record search='.(count($result));
			echo '<br>';
			echo 'Admi card Total Record found='.(count($this->master_model->getRecords('jaiib_pass_sub',array('find_status'=>1)))).'<br>';
			
		}
	}	
	
	
	public function removeAdmit()
	{
		$this->db->limit('500');
		$result=$this->master_model->getRecords('jaiib_pass_sub',array('find_status'=>1));
		//echo '<pre>';
		//print_r($result);
		//exit; 
		
		if(count($result) > 0)
		{
			foreach($result as $row)
			{
					$update_data = array('remark' => 0);
					$this->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$row['admit_card_id']));
					
					$update_data_arr = array('find_status' => 2);
					$this->master_model->updateRecord('jaiib_pass_sub',$update_data_arr,array('id'=>$row['id']));	
			}
			echo 'Total Record search='.(count($result));
		}
	}	
	
	
	public function getEmail()
	{
		$this->db->limit('5000');
		$result=$this->master_model->getRecords('jaiib_send_mail',array('r_flag'=>0));
		if(count($result) > 0)
		{
			foreach($result as $row)
			{
					$getEmailRecord=$this->master_model->getRecords('member_registration',array('isactive'=>'1','regnumber'=>$row['member_number']),'','email,mobile');
					if(count($getEmailRecord) > 0)
					{
						$update_data = array('r_flag' => 1,'email_id'=>$getEmailRecord[0]['email'],'mobile'=>$getEmailRecord[0]['mobile']);
						$this->master_model->updateRecord('jaiib_send_mail',$update_data,array('id'=>$row['id']));	
					}
			}
			echo 'Total Record search='.(count($result));
		}
	}	
	
	function jaiib_sendmail(){
		$this->db->limit('150');
		$result=$this->master_model->getRecords('jaiib_send_mail',array('mail_sent_flg'=>1));
		/*echo '<pre>';
		print_r($result);
		exit;*/
		$files = array();
		foreach($result as $rec){
			//echo $rec['email_id'];
			//echo '<br/>';
			$final_str = "Dear Candidate"; 
			$final_str.= "<br/><br/>";
			$final_str.= 'Please find attached your admit card and invoice for the JAIIB/DBF exam'; 
			$final_str.= "<br/><br/>";
			$final_str.= "Regards,";
			$final_str.= "<br/>";
			$final_str.= "IIBF TEAM";
			
			$this->db->where('exam_period','219');
			$this->db->where('invoice_no !=','');
			$this->db->where('invoice_image !=','');
			$this->db->where('transaction_no !=','');
			$this->db->where('member_no',$rec['member_number']);
			$exam_invoice = $this->master_model->getRecords('exam_invoice','','invoice_image');
			//echo '>>'. $exam_invoice[0]['invoice_image'];
			//echo '<br/>';
			
			$this->db->where('exm_prd','219');
			$this->db->where('remark',1);
			$this->db->where('admitcard_image !=','');
			$this->db->where('mem_mem_no',$rec['member_number']);
			$admitcard = $this->master_model->getRecords('admit_card_details','','admitcard_image');
			//echo '>>'. $admitcard[0]['admitcard_image'];
			//echo '<br/>';
			//echo $this->db->last_query();
			//echo '<br/>';
			
			
			$attachpath_invoice = "uploads/examinvoice/user/".$exam_invoice[0]['invoice_image'];
			$attachpath_admit = "uploads/admitcardpdf/".$admitcard[0]['admitcard_image'];
			/*echo $attachpath_invoice;
			echo '<br/>';
			echo $attachpath_admit;*/
			/*echo $rec['email_id'];
			echo '<br/>';
			exit;*/
			
			$info_arr=array('to'=>$rec['email_id'],
							//'to'=>'pawansing.pardeshi@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Exam Enrollment Acknowledgement',
							'message'=>$final_str
						);
			/*echo 'pawan';
			exit;	*/		
			$files=array($attachpath_invoice,$attachpath_admit);
			
			if($this->Emailsending->mailsend_attch_jaiib($info_arr,$files)){
				//echo 'here';
				$update_data = array('mail_sent_flg' => 2);
				$this->master_model->updateRecord('jaiib_send_mail',$update_data,array('id'=>$rec['id']));	
				//echo $this->db->last_query();
				//echo '<br/>';
				
			}
		}
	}
	
	
}
 
 	



