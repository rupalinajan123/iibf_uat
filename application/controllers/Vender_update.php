<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vender_update extends CI_Controller {

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

	

	} 

	

	public function update_vender_code()
	{
		//$this->db->limit(1);
		$this->db->where('status','0');
		$details=$this->master_model->getRecords('vender_tbl');
		//echo $this->db->last_query();exit;
		if(count($details) > 0)
		{
			foreach($details as $row)
			{
				$sql="UPDATE  `admit_card_details` set vendor_code=".$row['vendor_code']." WHERE `exm_cd` IN (".$this->config->item('examCodeJaiib').",".$this->config->item('examCodeDBF').",".$this->config->item('examCodeSOB').") AND `remark` = 1 AND `record_source` = 'Online' AND  exm_prd IN (220) AND app_update = '1' AND center_code =".$row['center_code']."  ";
				$query = $this->db->query($sql);
					echo $this->db->last_query();
					echo '<br>';
						
				if($query)
				{
					$update_arr = array(

									'status' => '1'
								
								);

			$this->master_model->updateRecord('vender_tbl',$update_arr,array('id '=>$row['id']));
				}
				//$this->db->where_in('exm_cd','21,42,992');
				///$this->db->where('remark','1');
				//$this->db->where('record_source','Online');
				//$this->db->where('exm_prd','220');
				//$this->db->where('app_update','1');
				//$this->db->where('center_code',$row['center_code']);
				//$details=$this->master_model->getRecords('admit_card_details');
				echo $this->db->last_query();
				
			}
		}
		/*$update_arr = array(

									'member_no' => $invoice_mem_no,

									'transaction_no'=>$payment_info[0]['transaction_no'],

									'invoice_no' => $invoice_number,

									'invoice_image' => $invoice_img_name,

									'date_of_invoice' =>$invoice_date_of_invoice,

									'modified_on' => $invoice_modified_on

								);

			

			$this->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$invoice_info[0]['invoice_id'],'receipt_no'=>$arr[$i]));*/
	}
	

}