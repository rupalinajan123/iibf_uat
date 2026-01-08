<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Fee extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model');		
		$this->load->model('log_model');
	}
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
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			 $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
		else
				$ipaddress = 'UNKNOWN';
		return $ipaddress;
}
	//get fee as per the cenrer selection (Prafull)	
	public function getFee()
	{
		$centerCode= $_POST['centerCode'];
		$eprid=$_POST['eprid'];
		$excd=$_POST['excd'];
		$grp_code=$_POST['grp_code'];
		$memcategory=$_POST['mtype'];
		$elearning_flag=$_POST['elearning_flag'];
		//$memcategory=$this->session->userdata('memtype');
		
		//Prameter should be in following format
		//1) Center Code 2)Exam period 3)exam code 4)Group ccode 5) member type (eg, '495','117','8','B1','O')
		
		echo displayExamFee($centerCode,$eprid,$excd,$grp_code,$memcategory,$elearning_flag);
		
		
		/*if($centerCode!="" && $eprid!="" && $excd!="" && $grp_code!="")
		{ 
			$getstate=$this->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			if(count($getstate) > 0)
			{
				if($grp_code=='')
				{
					$grp_code='B1';
				}
				 $today_date=date('Y-m-d');
				// $today_date='2017-08-15';
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$this->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$this->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$this->session->userdata('memtype'),'exam_period'=>$eprid,'group_code'=>$grp_code));
				//echo $this->db->last_query();exit;
				if(count($getfees) > 0)
				{
					if($getstate[0]['state_code']=='MAH')
					{
						echo $getfees[0]['cs_tot'];
					}
					else
					{
						echo $getfees[0]['igst_tot'];
					}
				}
			}
		}*/
		exit;
	}
	
	public function splgetFee()
	{
		$centerCode= $_POST['centerCode'];
		$eprid=$_POST['eprid'];
		$excd=$_POST['excd'];
		$grp_code=$_POST['grp_code'];
		$memcategory=$_POST['mtype'];
		echo spldisplayExamFee($centerCode,$eprid,$excd,$grp_code,$memcategory);
		
		exit;
	} 
	
	public function getFeeEL()
	{
		$centerCode= $_POST['centerCode'];
		$eprid=$_POST['eprid'];
		$excd=$_POST['excd'];
		//$grp_code=$_POST['grp_code'];
		$memcategory=$_POST['mtype'];
		$elearning_flag=$_POST['elearning_flag'];
		echo displayExamFeeEL($centerCode,$eprid,$excd,$memcategory,$elearning_flag);
		
		exit;
	}
	
	### Function added by pratibha to fetch E- learnign Fees - 19-Feb-2021
	public function getElearningFees()
	{
		$total_fees = 0;
		
		if(isset($_POST) && count($_POST) > 0)
		{
			$selected_subject_code_arr = $this->input->post('selected_subject_code');
			if(count($selected_subject_code_arr) > 0)
			{
				$this->db->where_in('fm.subject_code', $selected_subject_code_arr, FALSE);
				$all_subject_fee_data = $this->master_model->getRecords('spm_elearning_fee_master fm',array(), 'fm.subject_code, fm.fee_amount, fm.sgst_amt, fm.cgst_amt, fm.igst_amt, fm.cs_tot, fm.igst_tot');
        //echo $this->db->last_query();
								
				$all_subject_fee_arr = array();
				if(count($all_subject_fee_data) > 0)
				{
					foreach($all_subject_fee_data as $all_subject_res)
					{
						$all_subject_fee_arr[$all_subject_res['subject_code']] = $all_subject_res;
					}
				}
        
        //print_r($all_subject_fee_arr);
        //print_r($selected_subject_code_arr);
				
				foreach($selected_subject_code_arr as $res)
				{
					if(array_key_exists($res, $all_subject_fee_arr))
					{ 
						$total_fees = $total_fees + $all_subject_fee_arr[$res]['igst_tot'];
					}
				}
			}
		}
		
		$result['total_fees'] = number_format((float)$total_fees, 2, '.', '');
		echo json_encode($result);
	}

}

