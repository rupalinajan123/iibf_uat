<?php
/*
 * Controller Name	:	Duel Seat alocation
 * Created By		:	Prafull Tupe
 * Created Date		:	10-10-2019
 * Last Update 		:   10-10-2019
*/

defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_Dual_Seat  extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Master_model');
		$this->load->model('log_model');
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}
	
	/*
	  To Settle the exam invoice automatically
	*/
	public function index()
	{
		//$current_date ="2019-09-25";	
		$period_arr=array();
		$current_date =date('Y-m-d');	
		$this->db->select('exam_period');
		$this->db->group_by('exam_period');
		$this->db->where("'$current_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date ");
		$result = $this->master_model->getRecords('exam_activation_master');
		
		if(count($result) >0)
		{
			foreach($result as $record)
			{
				$period_arr[]=$record['exam_period'];
			}
		}
		if(count($period_arr) > 0)
		{
			$this->db->select('count(seat_identification) as cnt,center_code,venueid,exam_date,time,seat_identification');
			$this->db->where('seat_identification !=','');
			$this->db->where('remark','1');
			$this->db->where_in('exm_prd',$period_arr);
			$this->db->where("date(created_on) ='".$current_date."'");
			$this->db->group_by('center_code,venueid,exam_date,time,seat_identification');
			$this->db->having('cnt > 1');
			$this->db->order_by('venueid,exam_date,time,seat_identification');
			$result = $this->master_model->getRecords('admit_card_details');
			if(count($result) > 0)
			{
				foreach($result as $record)
				{
					$this->db->select('admitcard_id,mem_exam_id,mem_mem_no,exm_prd,exm_cd,sub_cd,center_code,venueid,exam_date,time,seat_identification,created_on');
					$this->db->where('center_code',$record['center_code']);
					$this->db->where('venueid',$record['venueid']);
					$this->db->where('exam_date',$record['exam_date']);
					$this->db->where('seat_identification',$record['seat_identification']);
					$this->db->where('time',$record['time']);
					$this->db->order_by('created_on','DESC');
					$admit_result = $this->master_model->getRecords('admit_card_details');
					if(count($admit_result) > 0)
					{
						for($i=0;$i < count($admit_result)-1;$i++)
						{
							$this->db->select('session_capacity');
							$this->db->where('venue_code',$admit_result[$i]['venueid']);
							$this->db->where('session_time',$admit_result[$i]['time']);
							$this->db->where('center_code',$admit_result[$i]['center_code']);
							$this->db->where('exam_date',$admit_result[$i]['exam_date']);
							$get_venue_capacity = $this->master_model->getRecords('venue_master');
							$session_capacity=$get_venue_capacity[0]['session_capacity']+5;
							
							$this->db->where('venue_code',$admit_result[$i]['venueid']);
							$this->db->where('session',$admit_result[$i]['time']);
							$this->db->where('center_code',$admit_result[$i]['center_code']);
							$this->db->where('date',$admit_result[$i]['exam_date']);
							$this->db->where('exam_code',$admit_result[$i]['exm_cd']);
							$this->db->where('exam_period',$admit_result[$i]['exm_prd']);
							$seat_allocation = $this->master_model->getRecords('seat_allocation');
							if(count($get_venue_capacity) > 0)
							{
								if($session_capacity > count($seat_allocation ))
								{
									$update_data=array('admit_card_id'=>'0');
									$this->master_model->updateRecord('seat_allocation', $update_data, array('admit_card_id' => $admit_result[$i]['admitcard_id']));
							
									$seat_number = getseat($admit_result[$i]['exm_cd'],$admit_result[$i]['center_code'],$admit_result[$i]['venueid'], $admit_result[$i]['exam_date'],$admit_result[$i]['time'],$admit_result[$i]['exm_prd'],$admit_result[$i]['sub_cd'], $session_capacity, $admit_result[$i]['admitcard_id']);
									
									if($seat_number!='' && $seat_number!=000)
									{
										$update_data=array('seat_identification'=>$seat_number);
										$this->master_model->updateRecord('admit_card_details', $update_data, array('admitcard_id' => $admit_result[$i]['admitcard_id']));
										
										//insert data for Mail to be send from pawan's cron...
										 $inser_array = array(	'admitcard_id' =>  $admit_result[$i]['admitcard_id'],
																			'mem_mem_no' => $admit_result[$i]['mem_mem_no'],
																			'mem_exam_id' => $admit_result[$i]['mem_exam_id'],
																			'exm_cd' => $admit_result[$i]['exm_cd'],
																			'exm_prd' => $admit_result[$i]['exm_prd']);
																		$inser_id = $this->master_model->insertRecord('admit_card_image_name_missing', $inser_array, true);
									}
								}
							}	
						}
					}
				}
			}
		}
	}
 	
public function index_custom()
	{
		$current_date ="2019-11-03";	
		$period_arr=array();
		//$current_date =date('Y-m-d');	
		$this->db->select('exam_period');
		$this->db->group_by('exam_period');
		$this->db->where("'$current_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date ");
		$result = $this->master_model->getRecords('exam_activation_master');
		
		
		if(count($result) >0)
		{
			foreach($result as $record)
			{
				$period_arr[]=$record['exam_period'];
			}
		}
		
		if(count($period_arr) > 0)
		{ 
			$this->db->select('count(seat_identification) as cnt,center_code,venueid,exam_date,time,seat_identification');
			$this->db->where('seat_identification !=','');
			$this->db->where('remark','1');
			$this->db->where_in('exm_prd',$period_arr);
			//$this->db->where('exm_prd',219);
			//$this->db->where("date(created_on) ='".$current_date."'");
			$this->db->group_by('center_code,venueid,exam_date,time,seat_identification');
			$this->db->having('cnt > 1');
			$this->db->order_by('venueid,exam_date,time,seat_identification');
			$result = $this->master_model->getRecords('admit_card_details');
			

			if(count($result) > 0)
			{
				foreach($result as $record)
				{
					$this->db->select('admitcard_id,mem_exam_id,mem_mem_no,exm_prd,exm_cd,sub_cd,center_code,venueid,exam_date,time,seat_identification,created_on');
					$this->db->where('center_code',$record['center_code']);
					$this->db->where('venueid',$record['venueid']);
					$this->db->where('exam_date',$record['exam_date']);
					$this->db->where('seat_identification',$record['seat_identification']);
					$this->db->where('time',$record['time']);
					$this->db->order_by('created_on','DESC');
					$admit_result = $this->master_model->getRecords('admit_card_details');
					if(count($admit_result) > 0)
					{
						for($i=0;$i < count($admit_result)-1;$i++)
						{
							$this->db->select('session_capacity');
							$this->db->where('venue_code',$admit_result[$i]['venueid']);
							$this->db->where('session_time',$admit_result[$i]['time']);
							$this->db->where('center_code',$admit_result[$i]['center_code']);
							$this->db->where('exam_date',$admit_result[$i]['exam_date']);
							$get_venue_capacity = $this->master_model->getRecords('venue_master');
							$session_capacity=$get_venue_capacity[0]['session_capacity']+5;
							
							$this->db->where('venue_code',$admit_result[$i]['venueid']);
							$this->db->where('session',$admit_result[$i]['time']);
							$this->db->where('center_code',$admit_result[$i]['center_code']);
							$this->db->where('date',$admit_result[$i]['exam_date']);
							$this->db->where('exam_code',$admit_result[$i]['exm_cd']);
							$this->db->where('exam_period',$admit_result[$i]['exm_prd']);
							$seat_allocation = $this->master_model->getRecords('seat_allocation');
							
	
							
							if(count($get_venue_capacity) > 0)
							{
								if($session_capacity > count($seat_allocation ))
								{
									$update_data=array('admit_card_id'=>'0');
									$this->master_model->updateRecord('seat_allocation', $update_data, array('admit_card_id' => $admit_result[$i]['admitcard_id']));
							
									$seat_number = getseat($admit_result[$i]['exm_cd'],$admit_result[$i]['center_code'],$admit_result[$i]['venueid'], $admit_result[$i]['exam_date'],$admit_result[$i]['time'],$admit_result[$i]['exm_prd'],$admit_result[$i]['sub_cd'], $session_capacity, $admit_result[$i]['admitcard_id']);
									
									if($seat_number!='' && $seat_number!=000)
									{
										$update_data=array('seat_identification'=>$seat_number);
										$this->master_model->updateRecord('admit_card_details', $update_data, array('admitcard_id' => $admit_result[$i]['admitcard_id']));
										
										//insert data for Mail to be send from pawan's cron...
										 $inser_array = array(	'admitcard_id' =>  $admit_result[$i]['admitcard_id'],
																			'mem_mem_no' => $admit_result[$i]['mem_mem_no'],
																			'mem_exam_id' => $admit_result[$i]['mem_exam_id'],
																			'exm_cd' => $admit_result[$i]['exm_cd'],
																			'exm_prd' => $admit_result[$i]['exm_prd']);
																		$inser_id = $this->master_model->insertRecord('admit_card_image_name_missing', $inser_array, true);
									}
								}
							}	
						}
					}
				}
			}
		}
	}

 	public function cron_add($start_point,$end_point,$current_date)
 	{
 	 		$insert_limit = array(
										'start_point' => $start_point,
										'end_point'   => $end_point,
										'created_at'=>$current_date
									);
			$this->Master_model->insertRecord('cron_limit',$insert_limit);
 	}
 	
 	
 	
}