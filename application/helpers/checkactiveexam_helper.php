<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */

 	function check_exam_activate($exam_code = NULL)
	{
		$flag=0;
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($exam_code !=NULL)
		{
			$today_date=date('Y-m-d');
			$CI->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
			$exam_list=$CI->master_model->getRecords('exam_activation_master',array('exam_code'=>$exam_code));
			//return $CI->db->last_query();exit;
			if(count($exam_list) > 0)
			{
				$flag=1;
			}
		}
		return $flag;
	}
	
/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/checkactiveexam_helper.php */