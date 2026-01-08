<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ExamCount extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		if($this->session->id==""){
			redirect('admin/ExamCountDashboard/Login');
		}		
		$this->load->model('UserModel');
		$this->UserID=$this->session->id;
		//$this->load->helper('TAPortal');
		   
        $this->load->model('Master_model');
        $this->UserID = $this->session->id;
        
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->helper('general_helper');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('KYC_Log_model');
	}
	
	
	public function index()
	{ 
		
		
		$data=array();
	
		$this->load->view('admin/ExamCountDashboard/report_dashboard',$data);
		
	}


 public function dowanload()
    {
		
		
		if(isset($_POST['btnSubmit']))
		{
				if(isset($_POST['exm_prd']) &&isset($_POST['exm_cd']) )
			{
				$exm_cd=implode(',',$_POST['exm_cd']);
				$exm_prd=implode(',',$_POST['exm_prd']);
	
	$subquery = $this->db->query(" SELECT DISTINCT(exam_date)  FROM `subject_master` WHERE `exam_code` IN (".$exm_cd.") AND `exam_period` IN (".$exm_prd.")");
	//$subquery = $this->db->query(" SELECT DISTINCT(exam_date)  FROM `subject_master` WHERE `exam_code` IN (".$exm_cd.") ");

			$subdate = $subquery->result_array();
			//print_r($subdate);
		if(!empty($subdate))
		{
			foreach($subdate  as $val)
			{
				$exam_date[]=$val['exam_date'];
			}
		$datearray=implode("','",$exam_date);
		
	if($_SESSION['username']=='NseitAdmin')
	{
		$query = $this->db->query(	"
SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 
as occupied
  FROM `admit_card_details` RIGHT JOIN venue_master
 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.")  AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."')and venue_master.vendor_code  = '3'
GROUP BY 
venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");	
}elseif($_SESSION['username']=='SifyAdmin')
{
$query = $this->db->query(	"
SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 
as occupied
  FROM `admit_card_details` RIGHT JOIN venue_master
 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.")  AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."') and venue_master.vendor_code  = '1'
GROUP BY 
venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");	
}elseif($_SESSION['username']=='AmitVendor')
{ 
$query = $this->db->query(	"
SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 
as occupied
  FROM `admit_card_details` RIGHT JOIN venue_master
 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.")  AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."') 
GROUP BY 
venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");
//and venue_master.vendor_code  = '1' by chaitali	
}

			/*	$query = $this->db->query(" 
				 SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 
 as occupied FROM venue_master LEFT JOIN 
 admit_card_details ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND admit_card_details.remark = 1 AND admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.exm_prd  IN (".$exm_prd.")  Where venue_master.exam_date  IN ('".$datearray."') GROUP BY 
 venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ORDER BY venue_name ASC");*/
				$reg_count = $query->result_array();
				//echo $this->db->last_query();exit;
			
				if(!empty($reg_count))
				{
					$data['Total_registration']=$reg_count ;
			        $data['exm_cd']=$exm_cd;
					$data['exm_prd']=$exm_prd;
				}
			}else
			{
				$data['Total_registration']=array() ;
			    $data['exm_cd']='';
				 $data['exm_prd']='';
			}
			}
			 $this->load->view('admin/ExamCountDashboard/exam_venue_count', $data);
		}else
		{
				$data['Total_registration']=array();
			        $data['exm_cd']='';
					$data['exm_prd']='';
			 $this->load->view('admin/ExamCountDashboard/exam_venue_count', $data);
		}
	}
	
	
public function download_CSV($exm_prd,$exm_cd)
{
		 $csv = " Examination Counts (Center Wise ) - ".date('Y-m-d')." \n\n";
		 $csv.= "Vendor code,Center code,Center name,Venue code,Venue name,Exam date,Session time,Session capacity,Registered count,Balance capacity,Occupied \n";	 $exm_cd=$exm_prd='';
		$exm_prd=$this->uri->segment(4);
		$exm_cd=$this->uri->segment(5);

	$subquery = $this->db->query(" SELECT DISTINCT(exam_date)  FROM `subject_master` WHERE `exam_code` IN (".$exm_cd.") AND `exam_period` IN (".$exm_prd.")");

			$subdate = $subquery->result_array();
			if(!empty($subdate))
			{
			foreach($subdate  as $val)
			{
				$exam_date[]=$val['exam_date'];
			}
		$datearray=implode("','",$exam_date);
	$query = $this->db->query(	"
SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 
as occupied
  FROM `admit_card_details` RIGHT JOIN venue_master
 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.exm_prd  IN (".$exm_prd.") AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."')
GROUP BY 
venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");
			
			/*	$query = $this->db->query(" 
				 SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 
 as occupied FROM venue_master LEFT JOIN 
 admit_card_details ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND admit_card_details.remark = 1 AND admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.exm_prd  IN (".$exm_prd.")  Where venue_master.exam_date  IN ('".$datearray."') GROUP BY 
 venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ORDER BY venue_name ASC");*/
				$result = $query->result_array();
				
			
		foreach($result as $record)
		{
			
						 $center_name = $this->Master_model->getRecords("center_master", array(
												'center_code' => $record['center_code'],
												), 'center_name', array(
												'center_name' => 'ASC'
												));
									if(!empty($center_name))
									{
										$center_name= $center_name[0]['center_name'];
									}
			// print_r($record);exit;
			 $csv.= $record['vendor_code'].','.$record['center_code'].','.$center_name.','.$record['venue_code'].',"'.$record['venue_name'].'",'.$record['exam_date'].','.$record['session_time'].','.$record['session_capacity'].','.$record['registered_count'].','.$record['balance_capacity'].','.round($record['occupied'],2) .'%'."\n";
		}
			}
        $filename = "Examination_Counts_(center_wise)_".date('Y-m-d').".csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
	}


 public function get_multiple_exam()
	{


        if (isset($_POST["period"]) && !empty($_POST["period"]))
		{
            $period = $this->input->post('period');
			
			$array_period = explode(',', $period);
			//check specail exam also
		$exam_activation=  $exam_activation_nr=$exam_activation_sp=$new_array=array();



			
			//print_r($array_period );

			 $C_date =date('Y-m-d');
		 $date =  date('Y-m-d', strtotime($C_date .' -15 day'));
		// $exam_date=date('Y-m-d')-5;
		 
		 $this->db->where_in('exam_period',$array_period);
		   $exam_activation_nr = $this->Master_model->getRecords("exam_activation_master", array('exam_to_date >=' => $date),'exam_code');
		   
$this->db->where_in('period',$array_period);
		   $exam_period = $this->Master_model->getRecords("special_exam_dates");
							   
					if(!empty($exam_period))
					{
						
						foreach($exam_period as $val )
						{
							$array_date[]=$val['examination_date'];
						}
						$select="DISTINCT(`exam_code`)";
						 $this->db->where_in('examination_date',$array_date);
		   $exam_activation_sp = $this->Master_model->getRecords("member_exam",'',$select);	
		   
		 
}
$exam_activation1=array_merge($exam_activation_nr,$exam_activation_sp);

//print_r($exam_activation1);
foreach($exam_activation1 as $val)
{
	$new_array[]=$val['exam_code'];
}
 $exam_activation = array_unique($new_array);
						
                if(!empty($exam_activation))
				{
									 $i=1;
								   foreach($exam_activation as $val)
								   {
									  
									   			$exam_name = $this->Master_model->getRecords("exam_master", array(
												'exam_code' => $val
												), 'description,exam_category', array(
												'description' => 'ASC'
												));
						
										if(!empty($exam_name))
										{
										
										  echo '<option value="' . $val. '">' . $exam_name[0]['description'] . '</option>';
								
             
             
										}
										$i++;
									}
									
							   }else {
							   
					
                    echo '-No result found-';
                }

        }
    }

}