<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admitcard_remark extends CI_Controller 
{
	public function __construct()
	{
		 parent::__construct(); 

		 $this->load->model('Master_model');
		 $this->load->library('email');
		 $this->load->model('Emailsending');
	}
	public function remark_find()
	{	
		$period_arr = array();
		$start_point  = 0;
		$end_point    = 1500;
		//$current_date ='2019-09-23';
		//$current_date = date('Y-m-d', strtotime('-1 day'));
		$current_date = date('Y-m-d');
		
		$this->db->where(" (created_at) = '".$current_date."'");
		$this->db->where("module_type","Admitcard_remark_update");
		$is_cron_exists = $this->Master_model->getRecords('cron_limit'); 
	  	if(count($is_cron_exists)  > 0 && !empty($is_cron_exists))
		{
			$start_point = count($is_cron_exists)*$end_point;
		}
		$this->cron_add($start_point,$end_point,$current_date);
		
		$today_date = date('Y-m-d');
		$previous_date = date('Y-m-d', strtotime('-1 day'));
		
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date OR '$previous_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$activation = $this->master_model->getRecords('exam_activation_master','','exam_code,exam_period');
		if(count($activation) >0)
 		{
 			foreach($activation as $record)
 			{
 				$period_arr[]=$record['exam_period'];
 			}
 		}
		if(count($period_arr) > 0)
		{
			$this->db->where('remark','2');
			$this->db->where('admitcard_image !=','');
			$this->db->where('pwd !=','');
			$this->db->where('seat_identification !=','');
			$this->db->where('admitcard_image !=','');
			//$this->db->where('exm_cd',$res['exam_code']);
			//$this->db->where('exm_prd',$res['exam_period']);
			$this->db->where_in('exm_prd',$period_arr);
			$this->db->where('date(created_on)',$current_date);
			$this->db->limit($end_point,$start_point);
			$eligible = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_mem_no,mem_exam_id,exm_cd,exm_prd');
			
			if(count($eligible) > 0)
			{
				
				foreach($eligible as $record)
				{
					$this->db->where('admitcard_id',$record['admitcard_id']);
					$admit_card_remark_missing = $this->master_model->getRecords('admit_card_remark_update');
					
					if(count($admit_card_remark_missing) <= 0)
					{
						$insert_arr = array(
											'admitcard_id' => $record['admitcard_id'],
											'mem_mem_no' => $record['mem_mem_no'],
											'mem_exam_id' => $record['mem_exam_id'],
											'exm_cd' => $record['exm_cd'],
											'exm_prd' => $record['exm_prd']
											);
						$last_id = $this->master_model->insertRecord('admit_card_remark_update',$insert_arr);
					}
				}
			}
			else
			{
					$arr_update = array('created_at' => '0000-00-00');
					$this->master_model->updateRecord('cron_limit',$arr_update,array('created_at' => $current_date,'module_type'=>'Admitcard_remark_update'));
				
			}
		}
	}
	
	public function remark_settle()
	{
		$this->db->where('is_settle',0);
		//$this->db->limit(0,5);
		$admit_card_remark_update = $this->master_model->getRecords('admit_card_remark_update');
		
		foreach($admit_card_remark_update as $res)
		{
			$update = array('remark' => '1');
			$this->master_model->updateRecord('admit_card_details',$update,array('admitcard_id'=>$res['admitcard_id']));
			
			$update_is_settle = array('is_settle' => '1');
			$this->master_model->updateRecord('admit_card_remark_update',$update_is_settle,array('admitcard_id'=>$res['admitcard_id']));
		}
	}
	
	public function cron_add($start_point,$end_point,$current_date)
	{
		$insert_limit = array(
								'start_point' => $start_point,
								'end_point'   => $end_point,
								'module_type' => 'Admitcard_remark_update',
								'created_at'=>$current_date
								);
		$this->Master_model->insertRecord('cron_limit',$insert_limit);
		
 	}
}


