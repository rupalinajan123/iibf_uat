<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admitcard_dualseatno extends CI_Controller {

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
	
	public function find_number(){
		
		//SELECT `center_code`,`venueid`,`exam_date`,`time`,`seat_identification` FROM `admit_card_details` WHERE `seat_identification` != '' AND remark = 1 and `exm_prd` = '218' GROUP BY `center_code`,`venueid`,`exam_date`,`time`,`seat_identification` HAVING COUNT(`seat_identification`) > 1 ORDER BY `center_code`,`venueid`,`exam_date`,`time`,`seat_identification`
		echo 'pawan123';
		echo '<br/>';
		
		
		$this->db->where('remark',1);
		$this->db->where('seat_identification !=','');
		$this->db->where('exm_prd',219);
		$this->db->where('exm_cd',$this->config->item('examCodeJaiib'));
		$this->db->group_by(array("center_code", "venueid", "exam_date","time","seat_identification"));
		$this->db->having('COUNT(`seat_identification`) > 1');
		//$this->db->order_by("id", "asc");  
		//$this->db->limit(1);
		$eligible = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_mem_no,mem_exam_id,exm_cd,exm_prd,center_code`,`venueid`,`exam_date`,`time`,`seat_identification');
		
		echo $this->db->last_query();
		
	}
	

}


