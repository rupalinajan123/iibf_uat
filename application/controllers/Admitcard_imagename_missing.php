<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admitcard_imagename_missing extends CI_Controller {

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
		
	} 
	
	
	public function settle(){
		$period_arr = array();
		$start_point  = 0;
		$end_point    = 10000;
		$current_date ='2020-12-0';
		//$current_date = date('Y-m-d', strtotime('-1 day'));
		//$current_date = date('Y-m-d');
		$this->db->where(" (created_at) = '".$current_date."'");
		$this->db->where("module_type","Admitcard_imagename_missing");
		$is_cron_exists = $this->Master_model->getRecords('cron_limit'); 
	  	if(count($is_cron_exists)  > 0 && !empty($is_cron_exists)){
			$start_point = count($is_cron_exists)*$end_point;
		}
		$this->cron_add($start_point,$end_point,$current_date);
		
			
		
		$today_date = date('Y-m-d');
		$previous_date = date('Y-m-d', strtotime('-1 day'));
		
		$this->db->select('exam_period');
		$this->db->group_by('exam_period');
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date OR '$previous_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$activation = $this->master_model->getRecords('exam_activation_master','','exam_code,exam_period');
		
		if(count($activation) >0)
 		{
 			foreach($activation as $record)
 			{
 				$period_arr[]=$record['exam_period'];
 			}
 		}
		
		if(count($period_arr) > 0){
			
			$this->db->where('remark',1);
			$this->db->where('admitcard_image','');
			//$this->db->where('exm_cd',$res['exam_code']);
			$this->db->where_in('exm_prd',$period_arr);
			$this->db->where('date(created_on)',$current_date);
			$this->db->limit($end_point,$start_point);
			$eligible = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_mem_no,mem_exam_id,exm_cd,exm_prd');
			
			if(count($eligible) > 0){
				
				foreach($eligible as $record){
					$this->db->where('admitcard_id',$record['admitcard_id']);
					$admit_card_image_name_missing = $this->master_model->getRecords('admit_card_image_name_missing');
					if(count($admit_card_image_name_missing) <= 0){
						$insert_arr = array(
											'admitcard_id' => $record['admitcard_id'],
											'mem_mem_no' => $record['mem_mem_no'],
											'mem_exam_id' => $record['mem_exam_id'],
											'exm_cd' => $record['exm_cd'],
											'exm_prd' => $record['exm_prd']
											);
						$last_id = $this->master_model->insertRecord('admit_card_image_name_missing',$insert_arr);
					}
				}
			}else{
				
				$this->db->where('remark',1);
				$this->db->where('date(created_on)',$current_date);
				$cnt = $this->master_model->getRecords('admit_card_details','','admitcard_id');
				
				
				$this->db->where('module_type','Admitcard_imagename_missing');
				$this->db->where('date(created_at)',$current_date);
				$this->db->order_by('id','desc');
				$start_limit = $this->master_model->getRecords('cron_limit','','start_point');
				
				if($start_limit[0]['start_point'] >= count($cnt)){
					$arr_update = array('created_at' => '0000-00-00');
					$this->master_model->updateRecord('cron_limit',$arr_update,array('created_at' => $current_date,'module_type'=>'Admitcard_imagename_missing'));
				}  
			}
		}
	}
	
	public function settle_image_name(){
		$this->db->where('is_settle',0);
		
		$admit_card_image_name_missing = $this->master_model->getRecords('admit_card_image_name_missing');
		
		foreach($admit_card_image_name_missing as $res){
			$admitcard_image = $res['exm_cd'].'_'.$res['exm_prd'].'_'.$res['mem_mem_no'].'.pdf';
			$update_arr_admit = array(
								'admitcard_image' => $admitcard_image
							);
			$this->master_model->updateRecord('admit_card_details',$update_arr_admit,array('admitcard_id'=>$res['admitcard_id']));
			
		}
		
		
		
		$this->db->where('is_settle',0);
		$this->db->limit(0,5);
		$this->db->group_by(array("mem_mem_no", "exm_cd", "exm_prd"));  
		$admit_card = $this->master_model->getRecords('admit_card_image_name_missing');
		
		foreach($admit_card as $admit_card){
			
			$this->db->where('mem_exam_id',$admit_card['mem_exam_id']);
			$this->db->where('pwd','');
			$sql = $this->master_model->getRecords('admit_card_details','','remark,pwd');
			if(count($sql) > 0){
				$password = random_password(); 
				$update_admit_pwd = array('pwd' => $password);
				$this->master_model->updateRecord('admit_card_details',$update_admit_pwd,array('mem_exam_id'=>$admit_card['mem_exam_id'])); 
			}
			
			
			
			$path = genarate_admitcard_custom_new($admit_card['mem_mem_no'],$admit_card['exm_cd'],$admit_card['exm_prd']); 
			
			$this->db->where('mem_exam_id',$admit_card['mem_exam_id']);
			$image_name = $this->master_model->getRecords('admit_card_details','','admitcard_image');
			
			$attachpath = "uploads/admitcardpdf/".$image_name['admitcard_image']; 
			
			if (file_exists($attachpath)){
				
				$update_admit_settle = array('is_settle' => 1);
				$this->master_model->updateRecord('admit_card_image_name_missing',$update_admit_settle,array('mem_exam_id'=>$admit_card['mem_exam_id'])); 
				
				$this->custom_admitcardpdf_send_mail($admit_card['mem_mem_no'],$admit_card['exm_prd']);
				
			}
		}
	}
	
	public function custom_admitcardpdf_send_mail($member,$prd){      
		     
		
		$this->db->distinct('mem_mem_no');   
		$this->db->where('remark',1);
		$this->db->where('exm_prd',$prd);
		$this->db->where('admitcard_image !=','');
		$this->db->where('mem_mem_no',$member);
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
			//$attachpath = "uploads/IIBF_ADMIT_CARD_510360428.pdf";   
			$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email,mobile');   
			$info_arr=array('to'=>$email[0]['email'],
							//'to'=>'pardeshipawansing@gmail.com',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Revised Admit Letter',
							'message'=>$final_str
						); 
			$files=array($attachpath);
			if($this->Emailsending->mailsend_attch($info_arr,$files)){
				
			}
		}
	}
	
	public function cron_add($start_point,$end_point,$current_date){
		$insert_limit = array(
								'start_point' => $start_point,
								'end_point'   => $end_point,
								'module_type' => 'Admitcard_imagename_missing',
								'created_at'=>$current_date
								);
		$this->Master_model->insertRecord('cron_limit',$insert_limit);
		
		
 	}
	

}


