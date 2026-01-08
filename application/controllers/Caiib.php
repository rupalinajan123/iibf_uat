<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CAIIB extends CI_Controller {
	public function __construct(){
//exit;
		parent::__construct();

		$this->load->model('Master_model');
		$this->load->model('log_model');
		$this->load->model('Emailsending');

		 $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->helper('blended_invoice_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
		$this->load->helper('custom_admitcard_helper');
      //  $this->load->model('Emailsending');
       // $this->load->model('log_model');
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}


//Attempt update for blended traning 

public function checkDate()
{
		$subject_code=array(169);
		//find all pay status 1 members
		 $this->db->where('exm_prd','219');
		 $this->db->where_IN('sub_cd',$subject_code);
		 $this->db->where('exam_date !=','2019-12-22');
		 $this->db->where('remark',1);
		$this->db->limit('1');
		$details=$this->master_model->getRecords('admit_card_details');
		
		if(count($details) > 0)
		{
			foreach($details as $row)
			{
				echo 'Combination='.$row['center_code'].'|'.$row['venueid'].'|'.$row['exam_date'].'|'.$row['time'].'|'.$row['sub_cd'];
				echo '<br>';
				//get total seat count from seat allocation
				 $this->db->where('center_code',$row['center_code']);
				 $this->db->where_IN('venue_code',$row['venueid']);
				 $this->db->where('date',$row['exam_date']);
				 $this->db->where('session',$row['time']);
				 $this->db->where_IN('subject_code',$subject_code);
				 $seat_details=$this->master_model->getRecords('seat_allocation');
				if(count($seat_details) > 0)
				{
					echo 'Total Seat Allocation='.count($seat_details);
					echo '<br>';
				}	
				///get venue capacity
				 $this->db->where('center_code',$row['center_code']);
				 $this->db->where_IN('venue_code',$row['venueid']);
				 $this->db->where('exam_date','2019-12-22');
				 $this->db->where('session_time',$row['time']);
				 $venue_details=$this->master_model->getRecords('venue_master');
				if(count($venue_details) > 0)
				{
					echo 'session_capacity='.$venue_details[0]['session_capacity'];
					echo '<br>';	
				}
				
				//check total no. of registration
				 $this->db->where('center_code',$row['center_code']);
				 $this->db->where_IN('venue_code',$row['venueid']);
				 $this->db->where('date','2019-12-22');
				 $this->db->where('session',$row['time']);
				 $this->db->order_by('seat_no','DESC');
				 $toal_actual_seat_allocation=$this->master_model->getRecords('seat_allocation');
				 if(count($toal_actual_seat_allocation) >0)
				 {
					 echo 'toal_actual_seat_allocation='.count($toal_actual_seat_allocation);
					 echo '<br>';
					 if($toal_actual_seat_allocation[0]['seat_no'] <= 99)
					 {
					 	$new_seat_no=$toal_actual_seat_allocation[0]['seat_no']+1;
					 	$new_seat_no='0'.$new_seat_no;
					 }
					 else
					 {
					 	$new_seat_no=$toal_actual_seat_allocation[0]['seat_no']+1;
					 }
					 $update_array = array('exam_date'=>'2019-12-22','seat_identification'=>$new_seat_no);
					 $this->master_model->updateRecord('admit_card_details',$update_array,array('admitcard_id'=>$row['admitcard_id']));	
					 
					 $seat_update_array = array('date'=>'2019-12-22','seat_no'=>$new_seat_no);
					$this->master_model->updateRecord('seat_allocation',$seat_update_array,array('admit_card_id'=>$row['admitcard_id']));	
					
					 
					echo genarate_admitcard_custom_new($row['mem_mem_no'],$this->config->item('examCodeCaiib'),'219');  
					echo '<br>';
					echo 'Seat has been allocated for Admit_card_id.'.$row['admitcard_id'].' please check';
					 
					 
				 }
			}
		}
		else
		{
			echo 'No Record Found';
		}
	}	
}


