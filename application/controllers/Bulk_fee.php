<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bulk_fee extends CI_Controller {

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

	

	//get fee as per the cenrer selection (Prafull)	

	public function getFee()

	{

		$centerCode= $_POST['centerCode'];

		$eprid=$_POST['eprid'];

		$excd=$_POST['excd'];

		$grp_code=$_POST['grp_code'];

		$memcategory=$_POST['mtype'];

		$elearning_flag=$_POST['elearning_flag'];

		$discount_flag = $_POST['discount_flag'];
		
		$free_paid_flag = $_POST['free_paid_flag'];
		

		//$memcategory=$this->session->userdata('memtype');

		

		//Prameter should be in following format

		//1) Center Code 2)Exam period 3)exam code 4)Group ccode 5) member type (eg, '495','117','8','B1','O')

		if($this->session->userdata('institute_id') == "17171"){			
			$gst_str = bulk_displayExamFee($centerCode,$eprid,$excd,$grp_code,$memcategory,$elearning_flag,$discount_flag,$free_paid_flag);
			echo str_replace(" + GST as applicable", "", $gst_str); 
		}else{
			echo bulk_displayExamFee($centerCode,$eprid,$excd,$grp_code,$memcategory,$elearning_flag,$discount_flag,$free_paid_flag);
		} 

		

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



}



