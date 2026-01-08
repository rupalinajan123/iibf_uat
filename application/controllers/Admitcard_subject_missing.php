<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admitcard_subject_missing extends CI_Controller {

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
	
	
		public function subject_missing_R(){ 
		$member_array = array();
		$exarr = array($this->config->item('examCodeCaiib')); 
		$this->db->select('mem_mem_no'); 
		$this->db->distinct('mem_mem_no');
		//$this->db->where('mem_mem_no',510360428);
		//$this->db->where('exm_cd',60);
		$this->db->where_in('exm_cd',$exarr); 
		$this->db->where('exm_prd',119);
		$this->db->where('remark','1');
		//$this->db->where('created_on >= ','2019-05-01 00:00:00');  
		//$this->db->where('created_on <= ','2019-05-10 23:59:59');
		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_mem_no');
		
		foreach($admit_card as $member_no){
			
			$app_arr = array('R');
			$this->db->where('member_no',$member_no['mem_mem_no']);
			//$this->db->where('member_no',510360428);
			$this->db->where('exam_code',$this->config->item('examCodeCaiib'));
			$this->db->where_in('exam_code',$exarr);
			$this->db->where('eligible_period',119);
			//$this->db->where('app_category !=','');
			$this->db->where_in('app_category',$app_arr); 
			//$this->db->where('app_category !=','V');
			//$this->db->where('app_category !=','D');
			//$this->db->where('app_category !=','P');
			$member_rec = $this->master_model->getRecords('eligible_master','','id');
			
			
			
			
			$member_rec_cnt = count($member_rec);
			
			if($member_rec_cnt != 0){
				$this->db->where('mem_mem_no',$member_no['mem_mem_no']);
				//$this->db->where('mem_mem_no',510137740);
				//$this->db->where('exm_cd',60);
				$this->db->where_in('exm_cd',$exarr);
				$this->db->where('exm_prd',119);
				$this->db->where('remark','1');
				$admit_card = $this->master_model->getRecords('admit_card_details','','admitcard_id');
				
				$admit_card_cnt = count($admit_card);
				
				if($admit_card_cnt != 3){
					$member_array[] = $member_no['mem_mem_no'];
				}
				
				/*if($member_rec_cnt != $admit_card_cnt){
					$member_array[] = $member_no['mem_mem_no'];
				}*/
			
			}else{
				
				$this->db->where('mem_mem_no',$member_no['mem_mem_no']);
				//$this->db->where('mem_mem_no',510359333);
				//$this->db->where('exm_cd',60);
				$this->db->where_in('exm_cd',$exarr);
				$this->db->where('exm_prd',119);
				$this->db->where('remark','1');
				$admit_card = $this->master_model->getRecords('admit_card_details','','admitcard_id');
				$admit_card_cnt = count($admit_card);
				
				if($admit_card_cnt > 3){
					$member_array[] = $member_no['mem_mem_no'];
				}
				
			}
			
		}
		
		echo "<pre>";
		print_r($member_array);
	}
	
		public function subject_missing_F(){ 
		$member_array = array();
		$exarr = array($this->config->item('examCodeCaiib'));  
		$this->db->select('mem_mem_no'); 
		$this->db->distinct('mem_mem_no');
		//$this->db->where('mem_mem_no',510360428);
		//$this->db->where('exm_cd',60);
		$this->db->where_in('exm_cd',$exarr); 
		$this->db->where('exm_prd',119);
		$this->db->where('remark','1');
		$this->db->where('created_on >= ','2019-05-01 00:00:00');       
		$this->db->where('created_on <= ','2019-05-10 23:59:59'); 
		$admit_card = $this->master_model->getRecords('admit_card_details','','mem_mem_no');
		
		foreach($admit_card as $member_no){
			
			//$query = '(app_category = R OR app_category = F)';
			$app_arr = array('F');
			$this->db->where('member_no',$member_no['mem_mem_no']);
			//$this->db->where('member_no',510360428);
			$this->db->where('exam_code',$this->config->item('examCodeCaiib'));
			$this->db->where_in('exam_code',$exarr);
			$this->db->where('eligible_period',119);
			$this->db->where_in('app_category',$app_arr); 
			$member_rec = $this->master_model->getRecords('eligible_master','','id');
			$member_rec_cnt = count($member_rec);
			
			if($member_rec_cnt != 0){
				$this->db->where('mem_mem_no',$member_no['mem_mem_no']);
				//$this->db->where('mem_mem_no',510137740);
				//$this->db->where('exm_cd',60);
				$this->db->where_in('exm_cd',$exarr);
				$this->db->where('exm_prd',119);
				$this->db->where('remark','1');
				$admit_card = $this->master_model->getRecords('admit_card_details','','admitcard_id');
				$admit_card_cnt = count($admit_card);
				
				if($member_rec_cnt != $admit_card_cnt){
					$member_array[] = $member_no['mem_mem_no'];
				}
			
			}else{
				
				$this->db->where('mem_mem_no',$member_no['mem_mem_no']);
				//$this->db->where('mem_mem_no',510359333);
				//$this->db->where('exm_cd',60);
				$this->db->where_in('exm_cd',$exarr);
				$this->db->where('exm_prd',119);
				$this->db->where('remark','1');
				$admit_card = $this->master_model->getRecords('admit_card_details','','admitcard_id');
				$admit_card_cnt = count($admit_card);
				
				if($admit_card_cnt > 3){
					$member_array[] = $member_no['mem_mem_no'];
				}
				
			}
			
		}
		
		echo "<pre>";
		print_r($member_array);
	}

}


