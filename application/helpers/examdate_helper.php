<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */

 	//get fee as per the cenrer selection (Prafull)	
 function allowed_examdate($regnumber = NULL, $exam_code = NULL, $exam_date= NULL)
{
	 	$CI = & get_instance();
        $flag = 0;
        $today_date = date('Y-m-d');
      	$applied_exam_date['exam_date']=$exam_date;
	
	 if (count($applied_exam_date) > 0)
            {
            $CI ->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $getapplied_exam_code = $CI ->master_model->getRecords('member_exam', array(
                'regnumber' => $regnumber,
                'pay_status' => '1'
            ) , 'member_exam.exam_code,member_exam.exam_period');
			//echo $CI->db->last_query();exit;
		    if (count($getapplied_exam_code) <= 0)
                {
                $CI ->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $CI ->db->where('bulk_isdelete', '0');
                $CI ->db->where('institute_id!=', '');
                $getapplied_exam_code = $CI ->master_model->getRecords('member_exam', array(
                    'regnumber' => $regnumber,
                    'pay_status' => '2'
                ) , 'member_exam.exam_code,member_exam.exam_period');
                };
		
			
		    if (count($getapplied_exam_code) > 0)
                {
                foreach($getapplied_exam_code as $exist_ex_code)
                    {
                   $CI->db->select('exam_date');
					$getapplied_exam_date = $CI ->master_model->getRecords('admit_card_details', array(
                        'exm_cd' => $exist_ex_code['exam_code'],
						'mem_mem_no'=>$regnumber,
						'exm_prd'=>$exist_ex_code['exam_period'],
                        'remark' => '1'
                    ));
					
				    if (count($getapplied_exam_date) > 0)
                        {
                        foreach($getapplied_exam_date as $exist_ex_date)
                            {
							
                            foreach($applied_exam_date as $sel_ex_date)
                                {
								
							   if ($sel_ex_date == $exist_ex_date['exam_date'])
                                    {
                                    $flag = 1;
                                    break;
                                    }
                                }

                            if ($flag == 1)
                                {
                                break;
                                }
                            }
                        }
                    }
                }
            }

        return $flag;
}
	
	

/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/checkactiveexam_helper.php */