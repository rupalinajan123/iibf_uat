<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Dwnletter_sagar extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct(); 
			//load mPDF library
			//$this->load->library('m_pdf');
			//echo CI_VERSION;
			//echo '<br/>';
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
			
			
			//exit;
			ini_set('max_execution_time', '0');
			error_reporting(E_ALL);			
		} 
		
		/** FUNCTION ADDED BY SAGAR ON 08-10-2020 TO GENERATE DRA MEMBER ADMIT CARD ***/
		public function GenerateDraMemberAdmitCard()
		{
			//exit;
			$mem_mem_no = array(801475722);
			$exm_cd = '45'; 
			$exm_prd = '777';	
			foreach($mem_mem_no as $res)
			{
				//echo "<br>".$res;
				echo "<br>".$attchpath_admitcard = genarate_admitcard_dra_custom($res,$exm_cd,$exm_prd);
			}
		}
	}	