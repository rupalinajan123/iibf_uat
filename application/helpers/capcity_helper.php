<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
  	function capacity($sel_center = NULL, $sel_venue = NULL, $sel_date = NULL, $sel_time = NULL)
	{
				$middle_str='';
				$flag=$seat_count=0;
				$CI = & get_instance();
				$seat_number=$last_id=$new_seat_cnt='';
				//$CI->load->model('my_model');
				if($sel_center !=NULL && $sel_venue !=NULL && $sel_date !=NULL && $sel_time !=NULL)
				{
				##### check if seat number alredy exist in seat allocation table for admit card id######
				$seat_count=$CI->master_model->getRecords('seat_allocation',array('center_code'=>$sel_center,'venue_code'=>$sel_venue,'date'=>$sel_date,'session'=>$sel_time));
				if(count($seat_count) > 0)
				{
					$CI->db->select('session_capacity');
					$venue_capacity = $CI->Master_model->getRecords('venue_master',array('center_code'=>$sel_center,'venue_code'=>$sel_venue,'exam_date'=>$sel_date,'session_time'=>$sel_time));	
					if(count($venue_capacity) >0)
					{
						/*** an integer to check ***/
						$int = count($seat_count);
						/*** lower limit of the int ***/
						$min = $venue_capacity[0]['session_capacity']-5;
						/*** upper limit of the int ***/
						$max = $venue_capacity[0]['session_capacity'];
						if ( in_array($int, range($min,$max)) ) 
						{
							/* Get Center Name*/
							$CI->db->select('center_name');
							$center_name=$CI->master_model->getRecords('center_master',array('center_code'=>$sel_center));
							/* Get Venue Name*/
							$CI->db->select('venue_name');
							$venue_name=$CI->master_model->getRecords('venue_master',array('venue_code'=>$sel_venue));
				
					  $check_exist = $CI->Master_model->getRecords('venue_alert',array('center_code'=>$sel_center,'venue_code'=>$sel_venue,'exam_date'=>$sel_date,'session_time'=>$sel_time,'is_deleted'=>'1'));
						if(count($check_exist) >0)
						{
								 $inser_array = array(
								'center_code' => $sel_center,
								'venue_code' => $sel_venue,
								'exam_date' => $sel_date,
								'session_time' => $sel_time
								);
							$inser_id = $CI->master_model->insertRecord('venue_alert', $inser_array, true);
							$middle_str.='<tr>
								<td align="center">'.$sel_center .'</td>
								<td align="center">'.ucfirst($center_name[0]['center_name']).'</td>
							   <td align="center">'.$sel_venue.'</td>
								<td align="center">'.$venue_name[0]['venue_name'].'</td>
								<td align="center">'.$sel_date.'</td>
								<td align="center">'.$sel_time.'</td>
								<td align="center">'.$venue_capacity[0]['session_capacity'].'</td>
								<td align="center">'.count($seat_count).'</td>
								<td align="center">'.($venue_capacity[0]['session_capacity']-count($seat_count)).'</td>
							  </tr>';
							   $update_array['mail_send']='1';
							  $CI->master_model->updateRecord('venue_alert',$update_array,array('id'=>$inser_id));
						}
						else
						{
							$check_exist = $CI->Master_model->getRecords('venue_alert',array('center_code'=>$sel_center,'venue_code'=>$sel_venue,'exam_date'=>$sel_date,'session_time'=>$sel_time));
							if(count($check_exist)<=0)
							{
								 $inser_array = array(
								'center_code' => $sel_center,
								'venue_code' => $sel_venue,
								'exam_date' => $sel_date,
								'session_time' => $sel_time
								);
								$inser_id = $CI->master_model->insertRecord('venue_alert', $inser_array, true);
								$middle_str.='<tr>
								<td align="center">'.$sel_center .'</td>
								<td align="center">'.ucfirst($center_name[0]['center_name']).'</td>
							   <td align="center">'.$sel_venue.'</td>
								<td align="center">'.$venue_name[0]['venue_name'].'</td>
								<td align="center">'.$sel_date.'</td>
								<td align="center">'.$sel_time.'</td>
								<td align="center">'.$venue_capacity[0]['session_capacity'].'</td>
								<td align="center">'.count($seat_count).'</td>
								<td align="center">'.($venue_capacity[0]['session_capacity']-count($seat_count)).'</td>
							  </tr>';
							  $update_array['mail_send']='1';
							  $CI->master_model->updateRecord('venue_alert',$update_array,array('id'=>$inser_id));
						}
							else
							{
							$check_reminder = $CI->Master_model->getRecords('venue_alert',array('center_code'=>$sel_center,'venue_code'=>$sel_venue,'exam_date'=>$sel_date,'session_time'=>$sel_time,'mail_send'=>'1','reminder_mail_send'=>'0'));
							{
								if(count($check_reminder) > 0)
								{
									$reminder_date=date('Y-m-d',strtotime($check_exist [0]['created_at']. ' + 4 days'));
									$current_date=date('Y-m-d');
									if($reminder_date==$current_date)
									{
										$middle_str.='<tr bgcolor="#F59292">
										<td align="center">'.$sel_center .'</td>
										<td align="center">'.ucfirst($center_name[0]['center_name']).'</td>
									   <td align="center">'.$sel_venue.'</td>
										<td align="center">'.$venue_name[0]['venue_name'].'</td>
										<td align="center">'.$sel_date.'</td>
										<td align="center">'.$sel_time.'</td>
										<td align="center">'.$venue_capacity[0]['session_capacity'].'</td>
										<td align="center">'.count($seat_count).'</td>
										<td align="center">'.($venue_capacity[0]['session_capacity']-count($seat_count)).'</td>
									  </tr>';
									  $update_array['reminder_mail_send']='1';
									 $CI->master_model->updateRecord('venue_alert',$update_array,array('id'=>$check_reminder[0]['id']));
									}
								}
							}
						}
					}
				}
			}
		}
	}
	return $middle_str;	
}
	

	
/* End of file Capicity helper.php */
/* Location: ./application/helpers/checkactiveexam_helper.php */