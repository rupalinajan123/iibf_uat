<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admitcard_seatno_missing extends CI_Controller {

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
	
	
	public function find_record(){
		/*$today_date = date('Y-m-d'); echo '<br/>';
		$previous_date = date('Y-m-d', strtotime('-1 day'));
		
		$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date OR '$previous_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
		$activation = $this->master_model->getRecords('exam_activation_master','','exam_code,exam_period');
		
		foreach($activation as $res){*/
			$this->db->where('remark',1);
			$this->db->where('seat_identification','');
			$this->db->where('exm_cd',1600);
			$this->db->where('exm_prd',908);
			$eligible = $this->master_model->getRecords('admit_card_details','','admitcard_id,mem_mem_no,mem_exam_id,exm_cd,exm_prd');
			
			if(count($eligible) > 0){
				
				foreach($eligible as $record){ 
					$this->db->where('admitcard_id',$record['admitcard_id']);
					$admit_card_seatno_missing = $this->master_model->getRecords('admit_card_seatno_missing');
					if(count($admit_card_seatno_missing) <= 0){
						$insert_arr = array(
											'admitcard_id' => $record['admitcard_id'],
											'mem_mem_no' => $record['mem_mem_no'],
											'mem_exam_id' => $record['mem_exam_id'],
											'exm_cd' => $record['exm_cd'],
											'exm_prd' => $record['exm_prd']
											);
						$last_id = $this->master_model->insertRecord('admit_card_seatno_missing',$insert_arr);
					}
				}
			}
		//}
	}
	
	public function member_settlement(){
		
		$this->db->where('is_settle',0);
		$admit_card_image_name_missing = $this->master_model->getRecords('admit_card_seatno_missing');
		
		foreach($admit_card_image_name_missing as $result){
			$member_no=$result['mem_mem_no'];
			$mem_exam_id=$result['mem_exam_id'];
			$exam_code=$result['exm_cd'];
			$exam_prd=$result['exm_prd'];
		
		//check in admit card  table
		$admit_card_details=$this->master_model->getRecords('admit_card_details',array('mem_exam_id'=>$mem_exam_id,'mem_mem_no'=>$member_no,'exm_cd'=>$exam_code,
		'exm_prd'=>$exam_prd));
		//print_r($admit_card_details); die;
		if(!empty($admit_card_details)){
			echo 'Total recode found in admit card table :<br>';
			echo count($admit_card_details);
			foreach($admit_card_details as $val){
				if($val['seat_identification']==''){
					//get the  seat number from the seat allocation table 2
					$seat_allocation=$this->master_model->getRecords('seat_allocation',array('venue_code'=>$val['venueid'],'session'=>$val['time'],'center_code'=>$val['center_code'],'date'=>$val['exam_date']));
					if(!empty($seat_allocation)){
					//check venue_capacity
						$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
						'session_time'=>$val['time'],
						'center_code'=>$val['center_code'],
						'exam_date'=>$val['exam_date']));
						//echo  $this->db->last_query();
						$venue_capacity=$venue_capacity[0]['session_capacity']+5;
						if(!empty($venue_capacity)){
				   			if(count($seat_allocation)<=$venue_capacity){
								$seat_no=count($seat_allocation);
								//inset new recode with append  seat number
								$seat_no=$seat_no+1;
								if($seat_no<10){
									$seat_no='00'.$seat_no;
								}elseif($seat_no>10 && $seat_no<100){
									$seat_no='0'.$seat_no;
								}
					
							$invoice_insert_array = array(
								'seat_no' => $seat_no,
								'exam_code' => $val['exm_cd'],
								'venue_code'=>$val['venueid'],
								'session'=>$val['time'],
								'center_code'=>$val['center_code'],
								'date'=>$val['exam_date'],
								'exam_period'=>$val['exm_prd'],
								'subject_code'=>$val['sub_cd'],
								'admit_card_id'=>$val['admitcard_id'],
								'createddate'=>date('Y-m-d H:i:s')
							);
					   		if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array)){	
								$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
								$update_info = array(
													'seat_identification' => $seat_no,
													'modified_on'=>$val['created_on'],
													'admitcard_image'=>$admitcard_image,
													'remark'=>1,
													);
				  
					  			if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id']))){
									echo '<br>Recode updated sucessfully in admit card<br>';
							
					   			}else{
									echo '<br>Recode Not updated sucessfully in admit card<br>';
					   			}
					   		}
					 	}else{
							echo '<br>Capacity has been full<br>';
						}
					}else{
						echo '<br>Venue not present in venue master<br>';
					}
					}else
					{
						$venue_capacity=$this->master_model->getRecords('venue_master',array(			        'venue_code'=>$val['venueid'],
						'session_time'=>$val['time'],
						'center_code'=>$val['center_code'],
						'exam_date'=>$val['exam_date']));
				if(!empty($venue_capacity)){
				   if(count($seat_allocation)<=$venue_capacity[0]['session_capacity']){
						//inset new recode with oo1
						$seat_no='001';
						$invoice_insert_array = array(
														'seat_no' => $seat_no,
														'exam_code' => $val['exm_cd'],
														'venue_code'=>$val['venueid'],
														'session'=>$val['time'],
														'center_code'=>$val['center_code'],
														'date'=>$val['exam_date'],
														'exam_period'=>$val['exm_prd'],
														'subject_code'=>$val['sub_cd'],
														'admit_card_id'=>$val['admitcard_id'],
														'createddate'=>date('Y-m-d H:i:s')
													);
						   if($this->master_model->insertRecord('seat_allocation', $invoice_insert_array)){
							   echo 'Seat alloation primary key :<br>';

							   echo $inser_id;
								$admitcard_image=$val['exm_cd'].'_'.$val['exm_prd'].'_'.$val['mem_mem_no'].'.pdf';
								$update_info = array(
														'seat_identification' => $seat_no,
														'modified_on'=>$val['created_on'],
														'admitcard_image'=>$admitcard_image,
														'remark'=>1,
													);
							   if($this->master_model->updateRecord('admit_card_details', $update_info,array('admitcard_id'=>$val['admitcard_id']))){
									echo 'Recode updated sucessfully in admit card<br>';
							   }else{
									echo 'Recode Not updated sucessfully in admit card<br>';
							   }
						}
					}else{
						echo '<br>Capacity has been full<br>';
					}
					}else{
							echo '<br>Venue not present in venue master<br>';
					}
				}
				}
			} 
		}
		
			$update_admit_settle = array('is_settle' => 1);
			$this->master_model->updateRecord('admit_card_seatno_missing',$update_admit_settle,array('id'=>$result['id']));
			
			 echo 'here';
			 exit; 
		}
		
		
	}

}


