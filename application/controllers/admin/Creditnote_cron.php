<?php
/*
 	* Controller Name	:	Credit Note File Generation
 	* Created By		:	Pawan
 	* Created Date		:	04-10-2019
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Creditnote_cron extends CI_Controller {
			
	public function __construct(){
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->model('log_model');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	
	public function auto_credit_note_generation()
	{
		 
		ini_set("memory_limit", "-1");
        
		$this->db->where('credit_note_image',''); 
		$this->db->where('req_status',5);
		$this->db->where('credit_note_number', '');
		$this->db->where('sbi_refund_date !=', '0000-00-00');
		//$this->db->where('req_module !=','1')
		$this->db->order_by('sbi_refund_date', 'ASC');
		$this->db->limit(25,0);
		$sql = $this->master_model->getRecords('maker_checker','','transaction_no');
		
		//echo $this->db->last_query(); exit;
		foreach($sql as $rec)
		{
			//FOR BULK, SECOND PARAMETER MUST BE 1 //
			//FOR DRA, SECOND PARAMETER MUST BE 2 //need some static changes in helper also
			//FOR JBIMS, SECOND PARAMETER MUST BE 3 
			//FOR AMP, SECOND PARAMETER MUST BE 4 
			$path = generate_credit_note($rec['transaction_no'], 0);  //invoice_helper.php
		}
	}
	
	public function auto_credit_note_generation_custom()
	{  
		 //for custom
		$transaction_no_arr = array('XUR30746110881','XHD50918334048','XHD50919185287','XUTI0919546526','XAX60920000857','XAX60921043126','XSBI0907253533','XHD50922251121','XHD50922263133');  
		if(count($transaction_no_arr) > 0)
		{
			foreach($transaction_no_arr as $transaction_no)
			{
				//FOR BULK, SECOND PARAMETER MUST BE 1
				//FOR DRA, SECOND PARAMETER MUST BE 2 
				//FOR JBIMS, SECOND PARAMETER MUST BE 3 
				//FOR AMP, SECOND PARAMETER MUST BE 4 
				echo '<br>'.$path = generate_credit_note($transaction_no, 0);  //invoice_helper.php
			}
		} exit;  
	}
}