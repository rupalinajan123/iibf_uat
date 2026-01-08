<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DupCert_eligible_rec extends CI_Controller 
{
			
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->library('email');
		 $this->load->model('Emailsending');
		$this->load->library('Excel');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
	
	
	public function index()
	{
		
		$this->db->limit(50000);
		$this->db->distinct('member_no');
		//$member_no = 845101268;
		//$this->db->where_in('member_no', $member_no);
		$validmem = $this->master_model->getRecords('duplicate_cert_eligible_records',array('remark' => '0'));
		//echo $this->db->last_query(); die;

		if(!empty($validmem))
		{
			foreach($validmem as $res)
			{
				$member_no = $res['member_no'];
				$id = $res['id'];
				
				$result_data_mem = $this->master_model->getRecordCount('member_registration',array('regnumber'=>$member_no));
				//echo $this->db->last_query(); die; 
				if($result_data_mem > 0 )
				{
					$update_data = array('remark'=>'1' , 'record_count' => $result_data_mem );
					$this->master_model->updateRecord('duplicate_cert_eligible_records',$update_data,array('id'=>$id));
				}
				else if($result_data_mem == 0)
				{  
					$result_data_dra = $this->master_model->getRecordCount('dra_members',array('regnumber'=>$member_no));
					if($result_data_dra > 0 )
					{
						$update_data = array('remark'=>'1' , 'record_count' => $result_data_mem );
						$this->master_model->updateRecord('duplicate_cert_eligible_records',$update_data,array('id'=>$id));
					}
					else{
						
						$update_data = array('remark'=>'3' , 'record_count' => '0' );
						$this->master_model->updateRecord('duplicate_cert_eligible_records',$update_data,array('id'=>$id));
					}
				}
			}//foreach
		}//if
		
	}

	
	
	
	
}
