<?php

defined('BASEPATH') OR exit('No direct script access allowed');



class ExamVenueDashboard extends CI_Controller

{

    

    public function __construct()

    {

        parent::__construct();

		//exit;

       /* if ($this->session->id == "") {

            redirect('admin/Login');

        }*/

        

        $this->load->model('UserModel');

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

		//print_r($_POST);

		$exm_cd=$exm_prd=$exam_date='';

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

	

		$query = $this->db->query(	"

SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 

as occupied

  FROM `admit_card_details` RIGHT JOIN venue_master

 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.")  AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."')

GROUP BY 

venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");	

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

			 //$this->load->view('admin/exam_venue_count', $data);
			 $this->load->view('admin/venue_master/exam_venue_dashboard', $data);

		}else

		{

				$data['Total_registration']=array();

			        $data['exm_cd']='';

					$data['exm_prd']='';

			 //$this->load->view('admin/exam_venue_count', $data);
			 $this->load->view('admin/venue_master/exam_venue_dashboard', $data);

		}

	}

	
	public function upload_csv()
	{
		$duplicate_venue=$oldcapacity_greater=array();
		$count_upadted=0;
		$count_upadted_not=0;
		$csv_file_read='';
		$data['error'] = $data['success'] = '';
		if($_FILES['userfile']['name'] != "")
		{
			$file_ext = strtolower(pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION));
			 $new_filename = 'file_name_test_'.rand().date('YmdHis');
			
                        $userfile = $this->upload_file("userfile", array('csv'),$new_filename, "./uploads/venue_master/", "csv");
                        if($userfile['response'] == 'error')
                        {
                            $data['error'] = $userfile['message'];
                           
						}
                        else if($userfile['response'] == 'success')
                        {
                            $data['success'] = 'File Uploaded successfully.';
							  $csv_file_read = $userfile['message']; 
                          
						}
		}
		 $csv_file = "./uploads/venue_master/".$csv_file_read; 
		if(file_exists($csv_file))
		{ 
			 
			$handle = fopen($csv_file, 'r');
			if (($handle = fopen($csv_file, "r")) !== FALSE) {
		   fgetcsv($handle);   
		   while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
			  
			 $num = count($data); 
			for ($c=0; $c < $num; $c++) {
			  $col[$c] = $data[$c];
			}
			
			 $originalDate = mysqli_real_escape_string($this->db->conn_id,$col[1]);
			 $newDate = date("Y-m-d", strtotime($originalDate));
			 $vendor_code = mysqli_real_escape_string($this->db->conn_id,$col[0]);
			 $exam_date = $newDate; 
			 $center_code = mysqli_real_escape_string($this->db->conn_id,$col[2]); 
			 $session_time = mysqli_real_escape_string($this->db->conn_id,$col[3]); 
			 $session_capacity = mysqli_real_escape_string($this->db->conn_id,$col[4]); 
			 $venue_code = mysqli_real_escape_string($this->db->conn_id,$col[5]); 
			 $venue_name = mysqli_real_escape_string($this->db->conn_id,trim($col[6])); 
			 $venue_addr1 = mysqli_real_escape_string($this->db->conn_id,trim($col[7]));  
			 $venue_addr2 = mysqli_real_escape_string($this->db->conn_id,trim($col[8]));  
			 $venue_addr3 = mysqli_real_escape_string($this->db->conn_id,trim($col[9])); 
			 $venue_addr4 = mysqli_real_escape_string($this->db->conn_id,trim($col[10])); 
			 $venue_addr5 = mysqli_real_escape_string($this->db->conn_id,trim($col[11])); 
			 $venue_pincode = mysqli_real_escape_string($this->db->conn_id,trim($col[12])); 
			 $pwd_enabled = mysqli_real_escape_string($this->db->conn_id,trim($col[13])); 
			
			$this->session->set_userdata('enduserinfo', $session_capacity);
			
			
				if(!empty($session_time))
				{
					
					if(strpos($session_time, '.') == true)
					{
						$time = str_ireplace(".",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						$last_id = $query['venue_master_id'];
						//log query
						$log_message = serialize($query);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
						//print_r($logs); exit;
						$this->master_model->updateRecord('venue_master_replace_time', $logs, array(
						'venue_master_id'=>$last_id
							));						
						//$this->master_model->insertRecord('venue_master_replace_time', $logs,true);
									 
					
					}
					else if(strpos($session_time, '::') == true)
					{
						$time = str_ireplace("::",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//log query
						$last_id = $query['venue_master_id'];
						//log query
						$log_message = serialize($query);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
						//print_r($logs); exit;
						$this->master_model->updateRecord('venue_master_replace_time', $logs, array(
						'venue_master_id'=>$last_id
							));						
						
						
					}else if(strpos($session_time, '.:') == true)
					{
						$time = str_ireplace(".:",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//log query
						$last_id = $query['venue_master_id'];
						//log query
						$log_message = serialize($query);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
						//print_r($logs); exit;
						$this->master_model->updateRecord('venue_master_replace_time', $logs, array(
						'venue_master_id'=>$last_id
							));						
						
					}
					else{
						
						$time = $session_time;
						
					}
					
				}
				
				//Time check in csv
		
					
						//checks on venue duplication
						$old_venue_details = $this->master_model->getRecords('venue_master',
							array('center_code'=>$center_code,
								'venue_code'=>$venue_code,
								'exam_date'=>$exam_date,
								'session_time'=>$time
							));	
					//echo $this->db->last_query();
					//print_r($old_venue_details); die;
 			if(isset($_POST['btnAdd']))
			{	
				
					if(count($old_venue_details)<1)	
					{
						
					$query = "INSERT INTO venue_master(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
				
					 
					$s     = mysql_query($query);
					
					}
					else if(count($old_venue_details)>=1)
					{
						
						$duplicate_venue[]=$old_venue_details;
					}
					 
			}
			else if(isset($_POST['btnUpdate']))
			{ 
				if(count($old_venue_details)==1)
				{
					if($old_venue_details[0]['session_capacity'] <=$session_capacity)
					{
						$update_data = array('session_capacity'=>$session_capacity);
						$this->master_model->updateRecord('venue_master',$update_data, array('center_code'=>$center_code,
							'venue_code'=>$venue_code,
							'exam_date'=>$exam_date,
							'session_time'=>$time
						));
						
						$count_upadted++;
					}elseif($old_venue_details[0]['session_capacity'] >=$session_capacity)
					{
						$oldcapacity_greater = $old_venue_details[0]['session_capacity'] ;
						foreach($oldcapacity_greater as $val2)
						{
								$query = "INSERT INTO venue_master_less_capacity(exam_date, center_code, session_time, old_capacity ,new_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$old_venue_details[0]['session_capacity']."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
										$s     = mysql_query($query);
								print_r($s);
								echo $this->db->last_query(); die;
								//echo $val2['venue_code'].' '.$val2['exam_date'].' '.$val2['center_code'].' '.$val2['session_time'].' '.$val2['session_capacity'];
								//echo '<br>';
						
						}
					}
					$count_upadted_not++;
				}elseif(count($old_venue_details)>1)
				{
					//$count_upadted_not++;
					$duplicate_venue[]=$old_venue_details;
				
				}
				
			}
		}
		fclose($handle);
		foreach($duplicate_venue as $rec2)
		{
			foreach($rec2 as $val2)
			{
				$query = "INSERT INTO venue_master_duplicate_record(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
				
				//echo $val2['venue_code'].' '.$val2['exam_date'].' '.$val2['center_code'].' '.$val2['session_time'].' '.$val2['session_capacity'];
				//echo '<br>';
			}
		}
		
	}
	
	}
		
		$data['total_count_dup'] = $total_count_dup = $this->Master_model->getRecordCount('venue_master_duplicate_record','');
		$data['total_count_time'] = $total_count_time = $this->Master_model->getRecordCount('venue_master_replace_time','');
		$data['total_count_capacity'] = $total_count_time = $this->Master_model->getRecordCount('venue_master_less_capacity','');
		
		$this->load->view('admin/venue_master/upload_csv', $data);
               

	}

	public function download_CSV($exm_prd,$exm_cd)

	{

		 $csv = " Examination Counts (Center Wise ) - ".date('Y-m-d')." \n\n";

$csv.= "Vendor code,Exam date,Center code,Session time,Session capacity,Venue code,Venue name,venue_add1,venue_add2,venue_add3,venue_add4,venue_add5,venue_pin_code,PWD_ENABLED,Center name,Registered count,Balance capacity,Occupied \n";

		 //$csv.= "Vendor code,Center code,Center name,Venue code,Venue name,Exam date,Session time,Session capacity,Registered count,Balance capacity,Occupied \n";	 $exm_cd=$exm_prd='';

		 $exm_prd=$this->uri->segment(5);

		 $exm_cd=$this->uri->segment(6);



	$subquery = $this->db->query(" SELECT DISTINCT(exam_date)  FROM `subject_master` WHERE `exam_code` IN (".$exm_cd.") AND `exam_period` IN (".$exm_prd.")");



			$subdate = $subquery->result_array();

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

 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.exm_prd  IN (".$exm_prd.") AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."') and venue_master.vendor_code  = '3'

GROUP BY 

venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");

}

elseif($_SESSION['username']=='SifyAdmin')

{

			

	$query = $this->db->query(	"

SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 

as occupied

  FROM `admit_card_details` RIGHT JOIN venue_master

 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.exm_prd  IN (".$exm_prd.") AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."') and venue_master.vendor_code  = '1'

GROUP BY 

venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");}

else

{

	

	

	$query = $this->db->query(	"

SELECT 

venue_master.pwd_enabled,

venue_master.venue_pincode,venue_master.venue_addr5,venue_master.venue_addr4,venue_master.venue_addr3,venue_master.venue_addr2,venue_master.venue_addr1,venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 

as occupied

  FROM `admit_card_details` RIGHT JOIN venue_master

 ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND  admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.`exam_date` IN ('".$datearray."') AND (remark NOT IN(0,2,3,4) and admit_card_details.record_source='online' Or remark NOT IN(0,3,4) and admit_card_details.record_source='Bulk') where venue_master.exam_date  IN ('".$datearray."')

GROUP BY 

venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ");



//echo $this->db->last_query();

//exit;

	}		

			/*	$query = $this->db->query(" 

				 SELECT venue_master.vendor_code,venue_master.center_code,venue_master.exam_date,venue_master.venue_code,venue_master.venue_name,venue_master.session_time,venue_master.session_capacity,COUNT(admit_card_details.admitcard_id) AS registered_count,venue_master.session_capacity - COUNT(admit_card_details.admitcard_id) AS balance_capacity,(COUNT(admit_card_details.admitcard_id))* 100/session_capacity 

 as occupied FROM venue_master LEFT JOIN 

 admit_card_details ON admit_card_details.center_code = venue_master.center_code AND admit_card_details.venueid = venue_master.venue_code AND admit_card_details.time = venue_master.session_time AND admit_card_details.exam_date = venue_master.exam_date AND admit_card_details.remark = 1 AND admit_card_details.exm_cd IN (".$exm_cd.") AND admit_card_details.exm_prd  IN (".$exm_prd.")  Where venue_master.exam_date  IN ('".$datearray."') GROUP BY 

 venue_master.center_code,venue_master.venue_code,venue_master.exam_date,venue_master.session_time ORDER BY venue_name ASC");*/

				$result = $query->result_array();

				

			

		foreach($result as $record)

		{

			

						 $center_name = $this->Master_model->getRecords("center_master", array(

												'center_code' => $record['center_code'],'exam_period'=>$exm_prd

												), 'center_name', array(

												'center_name' => 'ASC'

												));

									if(!empty($center_name))

									{

										$center_name= $center_name[0]['center_name'];

									}

			// print_r($record);exit; 

			

		

			 $csv.= $record['vendor_code'].','.$record['exam_date'].','.$record['center_code'].',"'.$record['session_time'].'",'.$record['session_capacity'].','.$record['venue_code'].','.$record['venue_name'].','.str_replace(","," ",$record['venue_addr1']).','.str_replace(","," ",$record['venue_addr2']).','.str_replace(","," ",$record['venue_addr3']).','.str_replace(","," ",$record['venue_addr4']).','.str_replace(","," ",$record['venue_addr5']).','.$record['venue_pincode'].','.$record['pwd_enabled'].','.$center_name.','. 

			 $record['registered_count'].','.$record['balance_capacity'].','.round($record['occupied'],2) .'%'."\n";

	

			// $csv.= $record['vendor_code'].','.$record['center_code'].','.$center_name.','.$record['venue_code'].',"'.$record['venue_name'].'",'.$record['exam_date'].','.$record['session_time'].','.$record['session_capacity'].','.$record['registered_count'].','.$record['balance_capacity'].','.round($record['occupied'],2) .'%'."\n";

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

 
public function upload_file($input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0)
        {
            $flag = 0;
            if($is_multiple == 0) { $path_img = $_FILES[$input_name]['name']; }
            else { $path_img = $_FILES[$input_name]['name'][$cnt]; }
           
            $ext_img = pathinfo($path_img, PATHINFO_EXTENSION);
            $valid_ext_arr = $valid_arr;
           
            if(!in_array(strtolower($ext_img),$valid_ext_arr))
            {
                $flag=1;
            }
           
            if($flag == 0)
            {
                $chk_upload_dir = './uploads';
                if(!is_dir($chk_upload_dir))
                {
                    $dir = mkdir($chk_upload_dir,0755);
                   
                    $myfile0 = fopen($chk_upload_dir."/index.php", "w") or die("Unable to open file!");
                    $txt0 = "";
                    fwrite($myfile0, $txt0);               
                    fclose($myfile0);
                }
               
                if(is_dir($upload_path)){ }
                else
                {
                    $dir=mkdir($upload_path,0755);
                   
                    $myfile = fopen($upload_path."/index.php", "w") or die("Unable to open file!");
                    $txt = "";
                    fwrite($myfile, $txt);               
                    fclose($myfile);
                }   
               
                $file=$_FILES;   
                if($is_multiple == 0) { $_FILES['file_upload']['name'] = $file[$input_name]['name']; }
                else { $_FILES['file_upload']['name'] = $file[$input_name]['name'][$cnt]; }
               
                $filename = $new_file_name;
                $path = $_FILES['file_upload']['name'];
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));                           
                $final_img = $filename.".".$ext;                   
               
                $config['file_name']     = $final_img;
                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = $allowed_types;
               
                $this->upload->initialize($config);                   
               
                if($is_multiple == 0)
                {
                    $_FILES['file_upload']['type']=$file[$input_name]['type'];
                    $_FILES['file_upload']['tmp_name']=$file[$input_name]['tmp_name'];
                    $_FILES['file_upload']['error']=$file[$input_name]['error'];
                    $_FILES['file_upload']['size']=$file[$input_name]['size'];
                }
                else
                {
                    $_FILES['file_upload']['type']=$file[$input_name]['type'][$cnt];
                    $_FILES['file_upload']['tmp_name']=$file[$input_name]['tmp_name'][$cnt];
                    $_FILES['file_upload']['error']=$file[$input_name]['error'][$cnt];
                    $_FILES['file_upload']['size']=$file[$input_name]['size'][$cnt];
                }
               
                if($this->upload->do_upload('file_upload'))
                {
                    $data=$this->upload->data();
                    return array('response'=>'success','message' => $final_img);
                }
                else
                {
                    return array('response'=>'error','message' => $this->upload->display_errors());
                }
            }
            else
            {
                return array('response'=>'error','message' => "Please upload valid ".str_replace('|',' | ',$allowed_types)." extension image.");
            }
        }
	
public function session_time_check($session_time)
{
	 $session_time = $this->session->userdata['session_time'];
	if(!empty($session_time))
				{
					
					if(strpos($session_time, '.') == true)
					{
						$time = str_ireplace(".",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//log query
						$log_message = serialize($s);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
								
						$this->master_model->insertRecord('venue_master_replace_time', $logs,true);
							return true;		 
					
					}
					else if(strpos($session_time, '::') == true)
					{
						$time = str_ireplace("::",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//log query
						$log_message = serialize($s);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
								
						$this->master_model->insertRecord('venue_master_replace_time', $logs,true);
						return true;
						
					}else if(strpos($session_time, '.:') == true)
					{
						$time = str_ireplace(".:",":",$session_time);
						//replaced time query add
						$query = "INSERT INTO venue_master_replace_time(exam_date, center_code, session_time, session_capacity, venue_code, venue_name, venue_addr1, venue_addr2, venue_addr3, venue_addr4, venue_addr5, venue_pincode, pwd_enabled, vendor_code) VALUES ('".$exam_date."','".$center_code."','".$time."','".$session_capacity."','".$venue_code."','".$venue_name."','".$venue_addr1."','".$venue_addr2."','".$venue_addr3."','".$venue_addr4."','".$venue_addr5."','".$venue_pincode."','".$pwd_enabled."','".$vendor_code."')";
						$s     = mysql_query($query);
						//log query
						$log_message = serialize($s);
						$logs = array(
								'title' =>'Replace Time',
								'description' =>$log_message);
								
						$this->master_model->insertRecord('venue_master_replace_time', $logs,true);
						return true;
					}
					else{
						
						$time = $session_time;
						return true;
					}
					
				}
				//echo $time; exit;
}

public function error_report()
{
	
	 $csv = "Exam date,Center code,Session time,Session capacity,Venue code,Venue name,venue_add1,venue_add2,venue_add3,venue_add4,venue_add5,venue_pin_code,PWD_ENABLED,Vendor code\n";//Column headers
		
		
		$query = $this->db->query("SELECT * FROM `venue_master_replace_time");
		$result = $query->result_array();
		foreach($result as $record)
		{
			
			// print_r($record);exit;
			 $csv.=$record['exam_date'].','.$record['center_code'].','.$record['session_time'].',"'.$record['session_capacity'].'","'.$record['venue_code'].'","'.$record['venue_name'].'",'.$record['venue_addr1'].','.$record['venue_addr2'].',"'.$record['venue_addr3'].'","'.$record['venue_addr4'].'","'.$record['venue_addr5'].'","'.$record['venue_pincode'].'","'.$record['pwd_enabled'].'",'.$record['vendor_code']."\n";
		}
		$sr ++;
		$filename = "error_report.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
}
public function duplicate_record()
{
	 $csv = "Exam date,Center code,Session time,Session capacity,Venue code,Venue name,venue_add1,venue_add2,venue_add3,venue_add4,venue_add5,venue_pin_code,PWD_ENABLED,Vendor code\n";//Column headers
		
		
		$query = $this->db->query("SELECT * FROM `venue_master_duplicate_record");
		$result = $query->result_array();
		foreach($result as $record)
		{
			
			// print_r($record);exit;
			 $csv.=$record['exam_date'].','.$record['center_code'].','.$record['session_time'].',"'.$record['session_capacity'].'","'.$record['venue_code'].'","'.$record['venue_name'].'",'.$record['venue_addr1'].','.$record['venue_addr2'].',"'.$record['venue_addr3'].'","'.$record['venue_addr4'].'","'.$record['venue_addr5'].'","'.$record['venue_pincode'].'","'.$record['pwd_enabled'].'",'.$record['vendor_code']."\n";
		}
		$sr ++;
		$filename = "duplicate_record.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
}

public function capacity_error()
{
	 $csv = "Exam date,Center code,Session time,Old capacity,New Capacity,Venue code,Venue name,venue_add1,venue_add2,venue_add3,venue_add4,venue_add5,venue_pin_code,PWD_ENABLED,Vendor code\n";//Column headers
		
		
		$query = $this->db->query("SELECT * FROM `venue_master_less_capacity");
		$result = $query->result_array();
		foreach($result as $record)
		{
			
			
			 $csv.=$record['exam_date'].','.$record['center_code'].','.$record['session_time'].',"'.$record['old_capacity'].'","'.$record['new_capacity'].'","'.$record['venue_code'].'","'.$record['venue_name'].'",'.$record['venue_addr1'].','.$record['venue_addr2'].',"'.$record['venue_addr3'].'","'.$record['venue_addr4'].'","'.$record['venue_addr5'].'","'.$record['venue_pincode'].'","'.$record['pwd_enabled'].'",'.$record['vendor_code']."\n";
		}
		$sr ++;
		$filename = "capacity_error.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
}
public function delete_data()
{
	$time_table = $this->db->query("DELETE FROM `venue_master_replace_time`");
	$dup_table = $this->db->query("DELETE FROM `venue_master_duplicate_record`");
	$capacity_table = $this->db->query("DELETE FROM `venue_master_less_capacity`");
	
	
}	
}

