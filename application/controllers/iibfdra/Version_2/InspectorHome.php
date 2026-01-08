<?php
defined('BASEPATH') or exit('No direct script access allowed');

class InspectorHome extends CI_Controller
{
  public $InspData;
  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('dra_inspector'))
    {
      redirect('iibfdra/Version_2/InspectorLogin');
    }
    $this->InstData = $this->session->userdata('dra_inspector');
    $this->load->helper('master_helper');
    $this->load->model('master_model');
    $this->load->model('UserModel');
    $this->load->helper('pagination_helper');
    $this->load->library('pagination');
    $this->load->helper('general_helper');
  }

  public function index()
  {
    $this->dashboard();
  }

  public function dashboard()
  {
    $drainspdata = $this->session->userdata('dra_inspector');
    $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
    $res = $this->master_model->getRecords("dra_exam_master a");
    $data = array('active_exams' => $res, 'middle_content' => 'inspector_dashboard', 'drainspdata' => $drainspdata, 'dashboard' => 'inspector');
    $this->load->view('iibfdra/Version_2/common_view_inspector', $data);
  }

  public function batches()
  { 
  	// print_r($this->InstData['inspector_name']); exit;
    $data['middle_content']  = 'drabatch_list';
    $data['dashboard'] = 'inspector';
    $this->load->view('iibfdra/Version_2/common_view_inspector', $data);
  }

  public function batch_list()
  {
    ## Read value
    $draw = @$_POST['draw'];
    $row = @$_POST['start'];
    $rowperpage = @$_POST['length']; // Rows display per page
    $columnIndex = @$_POST['order'][0]['column']; // Column index
    $columnName = @$_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = @$_POST['order'][0]['dir']; // asc or desc
    $searchValue = @$_POST['search']['value']; // Search VALUES
    //echo $searchValue;
    ## Search 
    $searchQuery = " ";

    if ($searchValue != '')
    {
      $searchValue = str_replace("'", '"', $searchValue);
      $searchQuery .= " AND (b.batch_code like '%" . $searchValue . "%' or b.batch_type like '%" . $searchValue . "%' or b.batch_online_offline_flag like '%" . $searchValue . "%' or b.batch_name like '%" . $searchValue . "%' or b.batch_from_date like '%" . $searchValue . "%' or b.batch_to_date like '%" . $searchValue . "%' or b.batch_status like '%" . $searchValue . "%' or b.batch_active_period like '%" . $searchValue . "%' or b.total_candidates like '%" . $searchValue . "%' or c1.city_name like '%" . $searchValue . "%' or c.location_name like '%" . $searchValue . "%') ";
    }

    $selectCount = "SELECT b.id, COUNT(bi.id) as reported, b.agency_id, b.center_id, b.inspector_id, b.batch_code, b.batch_type, b.batch_online_offline_flag, b.hours, b.batch_name, b.batch_from_date, b.batch_to_date, b.batch_status, b.batch_active_period, b.total_candidates, c1.city_name, b.created_on
            FROM agency_batch b 
            LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id AND bi.inspector_id = ".$this->InstData['id']." 
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE b.is_deleted = 0";

    if ($this->InstData['inspector_name'] != "DRA Cell") {
			$selectCount .= " AND   b.inspector_id =" . $this->InstData['id'];	    	
    }

    $selectCount .= " GROUP BY b.id";

    $select = $this->db->query($selectCount);

    /*$select = $this->db->query("SELECT b.id, b.agency_id, b.center_id, b.inspector_id, b.batch_code, b.batch_type, b.batch_online_offline_flag, b.hours, b.batch_name, b.batch_from_date, b.batch_to_date, b.batch_status, b.batch_active_period, b.total_candidates, c1.city_name, b.created_on
            FROM agency_batch b 
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE b.is_deleted = 0
            AND   b.inspector_id =" . $this->InstData['id']);*/

    ## Total number of records with filtering
    $records = $select->result_array();
    $total_records = count($records);
    
    // echo $this->db->last_query(); exit;
    
    // $selectData = "SELECT b.id, b.agency_id, b.center_id, b.inspector_id, b.batch_code, b.batch_type, b.batch_online_offline_flag, b.hours, b.batch_name, b.batch_from_date, b.batch_to_date, b.batch_status, b.batch_active_period, b.total_candidates, c1.city_name, b.created_on
    
    $selectData = "SELECT b.batch_code, COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, b.hours, c1.city_name, b.holidays,b.timing_from, b.timing_to, b.batch_from_date, b.total_candidates, b.training_medium, b.created_on, b.batch_status, b.id, b.agency_id, b.center_id, b.inspector_id,  b.batch_type, b.batch_online_offline_flag,  b.batch_name,  b.batch_to_date, b.batch_type, b.batch_online_offline_flag, b.batch_active_period
            FROM agency_batch b 
            LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id AND bi.inspector_id = ".$this->InstData['id']."
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE is_deleted = 0";

		if ($this->InstData['inspector_name'] != "DRA Cell") {
			$selectData .= " AND   b.inspector_id = " . $this->InstData['id']
      . $searchQuery;	    	
    } else {
    	$selectData .= " ".$searchQuery;
    }      

    ## Total number of records without filtering
    /*$select2 = "SELECT b.id, b.agency_id, b.center_id, b.inspector_id, b.batch_code, b.batch_type, b.batch_online_offline_flag, b.hours, b.batch_name, b.batch_from_date, b.batch_to_date, b.batch_status, b.batch_active_period, b.total_candidates, c1.city_name, b.created_on
            FROM agency_batch b 
            LEFT JOIN agency_center c ON b.center_id = c.center_id
            LEFT JOIN city_master c1 ON c1.id = c.location_name
            WHERE is_deleted = 0
            AND   b.inspector_id = " . $this->InstData['id']
      . $searchQuery;*/
    $select2 = $selectData." GROUP BY b.id ";   
    $select3 = $this->db->query($select2);

    ## Total number of records with filtering
    $records = $select3->result_array();
    $totalRecordwithFilter = count($records);
    
    ## Fetch records
    $agencyQuery = $select2 . " ORDER BY " . $columnName . "   " . $columnSortOrder . "  LIMIT " . $row . " ," . $rowperpage . " ";
    // echo $agencyQuery; exit;
    $agency_query = $this->db->query($agencyQuery);
    $agency_list = $agency_query->result_array();

    // echo $this->db->last_query(); exit;

    /* echo '<br>'.$columnIndex;
    echo '<br>'.$columnName;
    echo '<br>'.$columnSortOrder;
    echo '<br>'.$this->db->last_query(); */

    $data = array();

    //print_r($admin_user_list); die;
    foreach ($agency_list as $key => $agency)
    {
      $sr = $key + 1;

      if ($agency['batch_status'] == "In Review")
      {

        $status = '<span class="statusi">In Review</span>';
      }
      if ($agency['batch_status'] == "Final Review")
      {

        $status = '<span class="statusf">Final Review</span>';
      }
      if ($agency['batch_status'] == "Re-Submitted")
      {

        $status = '<span class="statusrs">Re-Submitted</span>';
      }
      if ($agency['batch_status'] == "Batch Error")
      {

        $status = '<span class="statusbe">Batch Error</span>';
      }
      if ($agency['batch_status'] == "Approved")
      {

        $status = '<span class="statusa">Go Ahead</span>';
      }
      if ($agency['batch_status'] == "Rejected")
      {

        $status = '<span class="statusr">Rejected</span>';
      }
      if ($agency['batch_status'] == "Hold")
      {

        $status = '<span class="statush">Hold</span>';
      }
      if ($agency['batch_status'] == "UnHold")
      {

        $status = '<span class="statuuh">UnHold</span>';
      }
      if ($agency['batch_status'] == "Cancelled")
      {

        $status = '<span class="statusc">Cancelled</span>';
      }


      $url = '<a href="' . base_url("iibfdra/Version_2/inspectorHome/view/" . base64_encode($agency['id'])) . '">View </a>';

      //$members = $this->master_model->getRecords('dra_members',array('batch_id'=>$agency['id']));
      $this->db->select('regid');
      $rst = $this->db->get_where('dra_members', array('batch_id' => $agency['id']));
      $members = $rst->num_rows();
      //echo $this->db->last_query();

      $date_after_2days = date('Y-m-d', strtotime($agency['batch_from_date'] . ' + 2 days'));
      $current_date = date('Y-m-d');
      //&& count($members) < $agency['total_candidates'] && $current_date <= $date_after_2days

      if ($agency['batch_type'] == 'C')
      {
        $batch_type = '<span class="typec">Combined</span>';
      }
      else
      {
        $batch_type = '<span class="types">Separate(' . $agency['hours'] . ')</span>';
      }

      if ($agency['city_name'] == "")
      {
        $city_name = ucfirst($agency['location_name']);
      }
      else
      {
        $city_name = ucfirst($agency['city_name']);
      }

      $from_to_date = date("d-M-Y", strtotime($agency['batch_from_date'])) . ' To <br>' . date("d-M-Y", strtotime($agency['batch_to_date']));

      $overall_compliance_list = $agency['overall_compliance_list'];
      $overall_compliance_str  = '';

        if (!empty($overall_compliance_list)) {
          // Split the string into an array using ',' as a delimiter
          $arr_overall_compliance = explode(',', $overall_compliance_list);

          // Check if the array has elements
          if (count($arr_overall_compliance) > 0) {
            foreach ($arr_overall_compliance as $key => $overall_compliance_value) {
              // Trim spaces and add numbering with a dot and a space
              $overall_compliance_str .= ($key + 1) . '. ' . trim($overall_compliance_value) . ' ';
            }
          }
        }

      $data[] = array(
        "sr" => $sr,
        "id"=>$agency['id'],
        "batch_code" => $agency['batch_code'],
        "reported" => $agency['reported'].'<br>'.$overall_compliance_str,
        "training_medium"=>$agency['training_medium'],
        "holiday"=>str_replace(',',' ',$agency['holidays']),
        "training_timings"=>$agency['timing_from'].'-'.$agency['timing_to'],
        "no_of_registered_candidates"=>$agency['total_candidates'],
        "hours" => $agency['hours'],
        "city_name" => $city_name,
        "from_to_date" => $from_to_date,
        "maximum_capacity"=>$agency['total_candidates'],
        "created_on" => date("d-m-Y", strtotime($agency['created_on'])),
        "status" => $status,
        "action" => $url,
      );
    }

    $output = array(
      "draw" => intval($draw),
      "iTotalRecords" => $total_records,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
    );

    echo json_encode($output);
  }

  // function to view batch Details-
  /*public function view($batch_id)
	{
		$data['batch_id'] = $batch_id = base64_decode($batch_id);
		$this->load->model('UserModel');

		// Accept and Reject Ajency 
		if(isset($_REQUEST['action_status'])) {	
			//AP = Active period
			if($_REQUEST['action_status'] == 'REPORT'){
				
				//$this->load->helper('general_agency_helper');
				$dra_inspector = $this->session->userdata('dra_inspector');	
				$user_type_flag = $drauserdata['roleid'];	
				$updated_date = date('Y-m-d H:i:s');
				if($user_type_flag == 1){		
					$user_type = 'A';				
				}else{
					$user_type = 'R';		
				}
								
				$date = date('Y-m-d H:i:s');
				$tmp_nm = strtotime($date).rand(0,100);
				$new_filename = 'dra_inspector_report_'.$tmp_nm;
                $config=array('upload_path'=>'./uploads/inspector_report_self/' ,
						'allowed_types'=>'pdf|PDF|doc|DOC|docx|DOCX|txt|TXT|jpg|png|jpeg|JPG|PNG|JPEG',
						'file_name'=>$new_filename);
						$this->upload->initialize($config);

				//File Uploadation of inspector report
                if($_FILES['inspector_report']['name'] != '')
                {
				 
				  $inspector_report=$_FILES['inspector_report']['name'];
				  if($this->upload->do_upload('inspector_report'))
                    {
                    	//echo  'report';die;
                        $dt1=$this->upload->data();
						$inspector_report = $dt1['file_name'];												
						$update_data = array(							
								'inspector_report_self'	=> $inspector_report,						
								'updated_on'		=> $updated_date,
								'updated_by' 		=> $dra_inspector['id'] 
								);			
						//print_r($update_data); die;								
								
						$data['error'] = '';
						$this->master_model->updateRecord('agency_batch',$update_data,array('id' => $batch_id));
						//log_dra_admin($log_title = "DRA Admin Upload Inspection Report", $log_message = serialize($update_data));
						
						//log_dra_agency_batch_detail($log_title = "DRA Admin Upload Inspection Report",$batch_id,serialize($update_data));
						
                    }
                    else
                    {		
                    	//echo 'else'; 			
                        $error =  $this->upload->display_errors();
						$data['error'] = $error;
						//print_r($error);die;
                    }
                }
				
			}
			
		}
		
		$this->db->select("dra_inst_registration.*,dra_inst_registration.id as institute_id,agency_center.location_name,dra_medium_master.medium_description,agency_batch.*,state_master.state_name,city_master.city_name,cs.city_name as cityname, f1.faculty_name as first_faculty_name, f1.academic_qualification as first_faculty_qualification, f2.faculty_name as sec_faculty_name, f2.academic_qualification as sec_faculty_qualification, f3.faculty_name as add_first_faculty_name, f3.academic_qualification as add_first_faculty_qualification, f4.faculty_name as add_sec_faculty_name, f4.academic_qualification as add_sec_faculty_qualification");	
		$this->db->join('agency_center','agency_batch.center_id=agency_center.center_id','LEFT');
		$this->db->join('city_master as cs','agency_center.location_name=cs.id','LEFT');
		$this->db->join('city_master','agency_batch.city=city_master.id','LEFT');		
		$this->db->join('state_master','state_master.state_code=agency_batch.state_code','LEFT');
		$this->db->join('dra_inst_registration','agency_batch.agency_id=dra_inst_registration.id','LEFT');
		$this->db->join('dra_medium_master','dra_medium_master.medium_code=agency_batch.training_medium','LEFT');		
		$this->db->join('faculty_master f1','agency_batch.first_faculty=f1.faculty_id','left');
		$this->db->join('faculty_master f2','agency_batch.sec_faculty=f2.faculty_id','left');
		$this->db->join('faculty_master f3','agency_batch.additional_first_faculty=f3.faculty_id','left');
		$this->db->join('faculty_master f4','agency_batch.additional_sec_faculty=f4.faculty_id','left');
		$this->db->where('agency_batch.id = "'.$batch_id.'"');
		$this->db->where('agency_center.center_display_status','1'); // to hide centers and batches.		
		$res = $this->master_model->getRecords("agency_batch");	
		//echo "<pre>";print_r($res); echo "</pre>"; die;
		
		$data['result'] = $res[0];
		
		$center_id = $res[0]['center_id'];
		$location_name = $res[0]['location_name']; // change done on 11 feb 2019  as per discussion with sonal 	
		$city_id = $res[0]['city'];
		
		if($res[0]['batch_online_offline_flag'] == 0)
		{
			$this->db->join('agency_inspector_master','agency_inspector_master.id=agency_inspector_center.inspector_id','LEFT');			
			$this->db->where('agency_inspector_center.city = "'.$location_name.'"');	
			$this->db->where('agency_inspector_center.is_delete != 1');	
			$this->db->where('agency_inspector_master.is_delete != 1');
			$this->db->where('agency_inspector_master.is_active',1);
			$res_inspector = $this->master_model->getRecords("agency_inspector_center");	
		}
		else
		{
			$this->db->select('id as inspector_id, inspector_name');
			$this->db->where('is_delete != 1');
			$this->db->where('is_active',1);
			$this->db->where('batch_online_offline_flag',1);
			$res_inspector = $this->master_model->getRecords("agency_inspector_master");
		}
		//echo $this->db->last_query();
		//echo "<pre>";print_r($res_inspector); echo "</pre>"; die;
		$data['result_inspector'] = $res_inspector;
		
		$this->db->select('dra_accerdited_master.institute_code, dra_accerdited_master.institute_name,agency_batch.batch_code,agency_batch.batch_name,max(agency_batch.id) max_id,agency_inspector_master.id, agency_inspector_master.inspector_name, agency_inspector_master.inspector_mobile, agency_inspector_master.inspector_email, agency_inspector_master.inspector_designation,agency_inspector_center.inspector_id, agency_inspector_center.center_id, agency_inspector_center.state, agency_inspector_center.city');
		$this->db->join('agency_inspector_master','agency_inspector_master.id=agency_inspector_center.inspector_id','LEFT');		
		$this->db->join('agency_batch','agency_inspector_master.id=agency_batch.inspector_id ');
		$this->db->join('dra_accerdited_master','agency_batch.agency_id=dra_accerdited_master.dra_inst_registration_id','LEFT');	
		$this->db->where('agency_inspector_center.city = "'.$location_name.'"');	
		$this->db->where('agency_inspector_center.is_delete != 1');	
		$this->db->where('agency_inspector_master.is_delete != 1');
		$this->db->where('agency_inspector_master.is_active',1);
		$this->db->where('agency_batch.id !=',$batch_id);
		$this->db->order_by('agency_inspector_center.id', 'desc');
		$this->db->limit(5,0);
		$this->db->group_by('agency_inspector_master.id'); 
		$res_inspectors = $this->master_model->getRecords("agency_inspector_center");
		//echo $this->db->last_query(); die;
		$resultarr = array();
		if( $res_inspectors) 
		{
			foreach( $res_inspectors as $result ) 
			{
				$batch = $this->master_model->getRecords('agency_batch',array('id'=>$result['max_id']), 'agency_batch.batch_code,agency_batch.batch_name,agency_batch.batch_from_date, agency_batch.batch_to_date, agency_batch.timing_from, agency_batch.timing_to');	
				if(COUNT($batch) > 0)
				{
					$batch =$batch[0];
					$result['batch_code'] = $batch['batch_code'];
					$result['batch_name'] = $batch['batch_name'];
					$result['batch_from_date'] = $batch['batch_from_date'];
					$result['batch_to_date'] = $batch['batch_to_date'];
					$result['timing_from'] = $batch['timing_from'];
					$result['timing_to'] = $batch['timing_to'];
					$resultarr[] = $result; 
				}
				else
				{
					$result['batch_code'] = '';
					$result['batch_name'] = '';
					$result['batch_from_date'] = '';
					$result['batch_to_date'] = '';
					$result['timing_from'] = '';
					$result['timing_to'] = '';
					$resultarr[] = $result; 
				}
			}	
		}	

		$data['result_inspectors'] = $resultarr;
		
		// Code to fetch Candidate list 
		$this->db->join('dra_medium_master','dra_medium_master.medium_code=dra_members.medium_of_exam','LEFT');
		$this->db->where('dra_members.batch_id = "'.$batch_id.'"');	
		$this->db->where('dra_members.isdeleted = 0');
		$res_student = $this->master_model->getRecords("dra_members");		
		$data['result_student'] = $res_student;
		
		// Code to fetch Admin logs  
		$this->db->join('dra_admin','dra_agency_batch_adminlogs.userid=dra_admin.id','LEFT');
		$this->db->where('dra_agency_batch_adminlogs.batch_id',$batch_id);
		$this->db->order_by('dra_agency_batch_adminlogs.date','DESC');
		$res_logs = $this->master_model->getRecords("dra_agency_batch_adminlogs");
		$data['agency_batch_logs'] = $res_logs;	
		//print_r($data['agency_batch_logs']); die;
		
		// check is any candidate applied for exam and payed fee		
		$this->db->join('dra_members','dra_members.regid=dra_member_exam.regid','INNER');
		$this->db->where('dra_members.batch_id = "'.$batch_id.'"');
		$where = '(dra_member_exam.pay_status="1" or dra_member_exam.pay_status = "3")';
		$this->db->where($where);
		$res_student_exam_apply_chk = $this->master_model->getRecordCount("dra_member_exam");	
		if($res_student_exam_apply_chk > 0 ){
			$data['is_applied'] = 1;
		}else{
			$data['is_applied'] = 0;
		}	
		 
		########## START : CODE ADDED BY SAGAR ON 21-08-2020 ###################
		$online_batch_user_details = array(); 
		if(isset($res[0]['batch_online_offline_flag']) && $res[0]['batch_online_offline_flag'] != "")
		{
			if($res[0]['batch_online_offline_flag'] == 1)
			{
				$this->db->where('agency_id', $res[0]['agency_id']);
				$this->db->where('batch_id', $batch_id);
				$online_batch_user_details = $this->master_model->getRecords('agency_online_batch_user_details');
			}
		}
		$data['online_batch_user_details'] = $online_batch_user_details;
		$data['middle_content'] = 'inspector_batch_detail';
		########## END : CODE ADDED BY SAGAR ON 21-08-2020 ###################
		
		$this->load->view('iibfdra/Version_2/common_view_inspector',$data);
		
	}*/



  //view batch details
  public function view($id='')
  {
    $batch_id = base64_decode($id);
    $batchId = intval($batch_id);


    $batch_reject_text = array();
    $login_agency = $this->session->userdata('dra_institute');
    $agency_id = $login_agency['dra_inst_registration_id'];

    $this->db->select('agency_batch.*,c2.location_name as offline_location_name,dra_inst_registration.inst_name,agency_center.location_name,agency_center.state,agency_center.district,agency_center.city,agency_inspector_master.inspector_name,state_master.state_name,city_master.city_name, f1.salutation as first_faculty_salutation,cm1.city_name as offline_city_name, f1.faculty_code as first_faculty_code,f1.faculty_name as first_faculty_name, f1.academic_qualification as first_faculty_qualification, f2.salutation as sec_faculty_salutation, f2.faculty_code as sec_faculty_code, f2.faculty_name as sec_faculty_name, f2.academic_qualification as sec_faculty_qualification, f3.faculty_code as add_first_faculty_code,f3.salutation as add_first_faculty_salutation, f3.faculty_name as add_first_faculty_name, f3.academic_qualification as add_first_faculty_qualification, f4.faculty_code as add_sec_faculty_code, f4.salutation as add_sec_faculty_salutation, f4.faculty_name as add_sec_faculty_name, f4.academic_qualification as add_sec_faculty_qualification');
    $this->db->join('agency_center', 'agency_batch.center_id=agency_center.center_id', 'left');
    $this->db->join('agency_center c2','agency_batch.batch_center_id=c2.center_id','left');
    $this->db->join('dra_inst_registration', 'agency_batch.agency_id=dra_inst_registration.id', 'left');
    $this->db->join('state_master', 'agency_center.state=state_master.state_code', 'left');
    $this->db->join('city_master', 'city_master.id=agency_center.location_name', 'left');
    $this->db->join('city_master as cs','agency_center.location_name=cs.id','LEFT');
    $this->db->join('city_master cm1','cm1.id=c2.location_name','left');    
    $this->db->where('agency_center.center_display_status', '1'); // added by Manoj on 19 mar 2019 to hide centers related batch from list	
    $this->db->join('agency_inspector_master', 'agency_inspector_master.id=agency_batch.inspector_id', 'left');
    $this->db->join('faculty_master f1', 'agency_batch.first_faculty=f1.faculty_id', 'left');
    $this->db->join('faculty_master f2', 'agency_batch.sec_faculty=f2.faculty_id', 'left');
    $this->db->join('faculty_master f3', 'agency_batch.additional_first_faculty=f3.faculty_id', 'left');
    $this->db->join('faculty_master f4', 'agency_batch.additional_sec_faculty=f4.faculty_id', 'left');
    $batchDetails = $this->master_model->getRecords('agency_batch', array('agency_batch.id' => $batchId));

    $this->db->select('agency_batch_rejection.rejection');
    $this->db->order_by("agency_batch_rejection.created_on", "DESC");
    $this->db->limit(1);
    $reason = $this->master_model->getValue('agency_batch_rejection', array('batch_id' => $batchId), 'rejection');

    ########## START : CODE ADDED BY SAGAR ON 04-09-2020 ###################
    $online_batch_user_details = array();
    if (isset($batchDetails[0]['batch_online_offline_flag']) && $batchDetails[0]['batch_online_offline_flag'] != "")
    {
      if ($batchDetails[0]['batch_online_offline_flag'] == 1)
      {
        $this->db->where('agency_id', $batchDetails[0]['agency_id']);
        $this->db->where('batch_id', $batchId);
        $online_batch_user_details = $this->master_model->getRecords('agency_online_batch_user_details');

        //echo $this->db->last_query(); die;
      }
    }

    $tenth_members_count = 0; $twelth_members_count = 0; $graduate_members_count = 0;

    if($batchDetails[0]['hours'] == 100)
    { 
      $this->db->select('regid');
      $tenth_rst = $this->db->get_where('dra_members',array('batch_id'=>$batchId,'qualification'=>'tenth'));
      $tenth_members_count = $tenth_rst->num_rows();
      // echo $tenth_members_count; exit;
      $data['tenth_members_count'] = $tenth_members_count;

      $this->db->select('regid');
      $twelth_rst = $this->db->get_where('dra_members',array('batch_id'=>$batchId,'qualification'=>'twelth'));
      $twelth_members_count = $twelth_rst->num_rows();
      $data['twelth_members_count'] = $twelth_members_count;
    }
    
    $this->db->select('regid');
    $this->db->where('batch_id', $batchId);
    $this->db->group_start(); // Start a group for the OR condition
    $this->db->where('qualification', 'graduate');
    $this->db->or_where('qualification', 'post_graduate');
    $this->db->group_end(); // End the group for the OR condition
    $graduate_rst = $this->db->get('dra_members');
    $graduate_members_count = $graduate_rst->num_rows();
    $data['graduate_members_count'] = $graduate_members_count; 

    //print_r($online_batch_user_details); die;
    //echo $this->db->last_query(); die;
    $data['online_batch_user_details'] = $online_batch_user_details;
    ########## END : CODE ADDED BY SAGAR ON 04-09-2020 ###################

    // Code to fetch Admin logs  
    $this->db->join('dra_admin', 'dra_agency_batch_adminlogs.userid=dra_admin.id', 'LEFT');
    $this->db->where('dra_agency_batch_adminlogs.batch_id', $batch_id);
    $this->db->order_by('dra_agency_batch_adminlogs.date', 'DESC');
    $res_logs = $this->master_model->getRecords("dra_agency_batch_adminlogs");
    $data['agency_batch_logs'] = $res_logs;
    //echo $this->db->last_query(); die;

    $data['middle_content']  = 'inspector_batch_detail';
    $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
    $res = $this->master_model->getRecords("dra_exam_master a");
    //echo $this->db->last_query(); die;

    $this->db->order_by('dra_userlogs.date', 'DESC');
    $data['activity_logs'] = $activity_logs = $this->master_model->getRecords("dra_userlogs", array('module' => 'TrainingBatches', 'prim_key' => $batch_id));
    //echo $this->db->last_query(); die;

    $this->db->order_by('created_on', 'DESC');
    $data['batch_checklist_logs'] = $batch_checklist_logs = $this->master_model->getRecords(" dra_agency_batch_checklistlogs", array('batch_id' => $batch_id));
    //echo $this->db->last_query(); die;

    $data['active_exams'] = $res;
    $data['batchDetails'] = $batchDetails;
    $data['reason'] = $reason;
    $this->load->view('iibfdra/Version_2/common_view_inspector', $data);
    //}
  }

  public function show_batch_inspection_report($batch_id = '')
  {  
    $login_inspector = $this->session->userdata('dra_inspector');
    $inspector_id   = $login_inspector['id'];
    $inspector_name = $login_inspector['inspector_name'];

    $DRA_Version_2_instId = $this->config->item('DRA_Version_2_instId');
    // echo $batch_id; exit;
    $this->db->select('a.institute_name, b.id, count(dm.regid) AS registered_candidate, b.agency_id, b.batch_code, b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_photo as first_faculty_photo, f1.faculty_code as first_faculty_code, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name,f2.faculty_photo as sec_faculty_photo, f2.faculty_code as sec_faculty_code, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name,f3.faculty_photo as add_first_faculty_photo, f3.faculty_code as add_first_faculty_code, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name,f4.faculty_photo as add_sec_faculty_photo, f4.faculty_code as add_sec_faculty_code, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on');
    
    // $this->db->order_by('b.id', 'DESC');
    $this->db->join('dra_accerdited_master a', 'b.agency_id = a.dra_inst_registration_id', 'left');
    // Subquery to order dra_members
    $subQuery = '(SELECT * FROM dra_members ORDER BY regid DESC) AS dm';
    $this->db->join($subQuery, 'b.id = dm.batch_id', 'left');
    // $this->db->join('dra_members dm', 'b.id = dm.batch_id', 'left');
    $this->db->join('faculty_master f1', 'b.first_faculty=f1.faculty_id', 'left');
    $this->db->join('faculty_master f2', 'b.sec_faculty=f2.faculty_id', 'left');
    $this->db->join('faculty_master f3', 'b.additional_first_faculty=f3.faculty_id', 'left');
    $this->db->join('faculty_master f4', 'b.additional_sec_faculty=f4.faculty_id', 'left');

    $this->db->where(" (b.batch_status = 'Approved' OR b.batch_status = 'Hold') ");

    if ($inspector_name == 'DRA Cell') {
    	$where1 = array('b.is_deleted' => 0, 'b.id' => $batch_id);	
    } else {
    	$where1 = array('b.is_deleted' => 0, 'b.id' => $batch_id, 'b.inspector_id' => $inspector_id);
    }
        
    // $where1 = array('b.is_deleted' => 0, 'b.id' => $batch_id, 'b.inspector_id' => $inspector_id);

    // $where1 = array('b.is_deleted' => 0, 'b.inspector_id' => $inspector_id);
    $this->db->where("b.batch_from_date <= CURDATE() AND b.batch_to_date >= CURDATE()");
    $this->db->where_in('b.agency_id', $DRA_Version_2_instId);

    $data['batch'] = $batch = $this->master_model->getRecords('agency_batch b', $where1);
    if(count($batch) == 0) { redirect(site_url('iibfdra/Version_2/InspectorHome/inspection_report')); }

    $this->db->select('a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.id, b.agency_id, b.batch_code, b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on');
    
    $this->db->order_by('b.id', 'DESC');
    $this->db->join('dra_accerdited_master a', 'b.agency_id = a.dra_inst_registration_id', 'left');
    $this->db->join('agency_inspector_master ai', 'b.inspector_id = ai.id', 'left');
    $this->db->join('dra_batch_inspection bi', 'b.id = bi.batch_id AND bi.inspector_id', 'left');
    // LEFT JOIN agency_inspector_master ai ON ai.id = b.inspector_id
    // LEFT JOIN dra_batch_inspection bi ON bi.batch_id = b.id AND bi.inspector_id = ".$this->InstData['id']."
    $this->db->join('faculty_master f1', 'b.first_faculty=f1.faculty_id', 'left');
    $this->db->join('faculty_master f2', 'b.sec_faculty=f2.faculty_id', 'left');
    $this->db->join('faculty_master f3', 'b.additional_first_faculty=f3.faculty_id', 'left');
    $this->db->join('faculty_master f4', 'b.additional_sec_faculty=f4.faculty_id', 'left');

    $this->db->where(" (b.batch_status = 'Approved' OR b.batch_status = 'Hold') ");

    if ($inspector_name == 'DRA Cell') {
      $where1 = array('b.is_deleted' => 0);  
    } else {
      $where1 = array('b.is_deleted' => 0, 'b.inspector_id' => $inspector_id);
    }
    $this->db->where("b.batch_from_date <= CURDATE() AND b.batch_to_date >= CURDATE()");
    $this->db->group_by('b.id');
    $data['all_batch'] = $batch = $this->master_model->getRecords('agency_batch b', $where1);
    
    $batch_login_details = $this->master_model->getRecords('agency_online_batch_user_details', array('batch_id' => $batch_id));

    $data['batch_login_details'] = $batch_login_details;

    $agency = $this->db->query("SELECT agency_batch.agency_id, dra_accerdited_master.institute_code
          FROM agency_batch LEFT JOIN  dra_accerdited_master ON agency_batch.agency_id = dra_accerdited_master.dra_inst_registration_id
          WHERE agency_batch.id=" . $batch_id);

    $agency_data = $agency->result_array();

    $query = "SELECT dra_members.regid, dra_members.training_id, concat(dra_members.namesub, ' ', dra_members.firstname, ' ', dra_members.middlename, ' ', dra_members.lastname) as name, dra_members.dateofbirth, dra_members.mobile_no, dra_members.scannedphoto,dra_members.idproofphoto,dra_members.quali_certificate,dra_members.hold_release,dra_candidate_inspection.attendance,dra_candidate_inspection.qualification_verify,dra_candidate_inspection.photo_verify,
      COUNT(CASE WHEN dra_candidate_inspection.attendance = 'Present' THEN 1 END) AS present_count,
      COUNT(CASE WHEN dra_candidate_inspection.attendance = 'Absent' THEN 1 END) AS absent_count
      FROM dra_members 
      LEFT JOIN dra_candidate_inspection ON dra_members.regid = dra_candidate_inspection.candidate_id 
      WHERE dra_members.batch_id = " . $batch_id . "
      AND dra_members.inst_code = " . $agency_data[0]['institute_code']." 
      group by dra_members.regid 
      order by dra_members.regid DESC"; //ACTUAL QUERY

    $result = $this->db->query($query);
    $arr_batch_candidates = $result->result_array();


    $inspe_query = "SELECT 
      c.created_on as date_time, 
      SUM(case when c.attendance='Present' then 1 else 0 end) as present_cnt,
      SUM(case when c.attendance='Absent' then 1 else 0 end) as absent_cnt
      FROM dra_candidate_inspection c  
      WHERE c.batch_id = ".$batch_id."
      GROUP BY c.batch_inspection_id"; //ORDER BY m.regid DESC
      
    $inspe_result = $this->db->query($inspe_query);  
    $inspe_batch  = $inspe_result->result_array();


    // echo "<pre>"; print_r($arr_batch_candidates); exit;
    $query1  = "SELECT count(1) cnt FROM dra_batch_inspection WHERE batch_id = " . $batch_id;
    $result1 = $this->db->query($query1);
    $batch1  = $result1->result_array();

    if ($batch1[0]['cnt'] > 0)
    {
      $inspection_no = $batch1[0]['cnt'] + 1;
    }
    else
    {
      $inspection_no = 1;
    }
    // echo $batch_id; exit;


    $batch_attendance = $this->master_model->getRecords("dra_batch_attendance",array('batch_id'=>$batch_id));
    $data['batch_attendance'] = $batch_attendance;  

    $qry = $this->db->query("SELECT * FROM dra_agency_batch_adminlogs l WHERE batch_id = ".$batch_id." ORDER BY l.date DESC");
    $data['agency_batch_logs'] = $res_logs = $qry->result_array();  
    
    // echo "<pre>"; print_r($batch_attendance); exit;
    if(isset($_POST) && COUNT($_POST) > 0)
    {      
      $login_inspector = $this->session->userdata('dra_inspector');
      $inspector_id = $login_inspector['id'];
      $btch_id = $this->input->post('batch_id');

      $file_error_flag = 0;
      $file_error_msg  = '';
      if (!empty($_FILES['attachment']['name']))
      {
        if ($_FILES['attachment']['size'] == 0)
        {
          $file_error_flag = 1;
          $file_error_msg  = 'File is empty.. Please upload valid file.'; 
        }
        else
        {
          $config['upload_path'] = 'uploads/inspection_report';
          //$config['allowed_types'] = '*'; 
          $config['allowed_types'] = 'txt|doc|docx|pdf|png|jpg|jpeg';

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if ($this->upload->do_upload('attachment'))
          {
            // Uploaded file data 
            $fileData = $this->upload->data();
            $uploadData['file_name'] = $fileData['file_name'];
            $uploadData['uploaded_on'] = date("Y-m-d H:i:s");
            $attachment      = $uploadData['file_name'];
            //echo '</br>'.$fileData['file_name'];
          }
          else
          {
            $file_error_flag = 1;
            $file_error_msg  = $this->upload->display_errors();
            // $this->session->set_flashdata('error',$this->upload->display_errors());
            // redirect(base_url() . 'iibfdra/InspectorHome/show_batch_inspection_report/'.$btch_id);
            
            // $result['message'] = $this->upload->display_errors();
            // echo json_encode($result);
            // exit;
            //$this->session->set_flashdata('error',$this->upload->display_errors());
            //redirect(base_url().'iibfdra/inspectorHome/inspection_report');
            //$errorUploadType .= $_FILES['attachment']['name'].' | ';  
          }
        }
      }

      //echo '****'; die;

      if($file_error_flag == 1)
      {
        $data['file_error_msg'] = $file_error_msg;
      }
      else
      {
        $inspection_start_time = !empty($this->input->post('inspection_start_time')) ? $this->input->post('inspection_start_time') : '';

        $teaching_quality_interaction_with_candidates = !empty($this->input->post('teaching_quality_interaction_with_candidates')) ? $this->input->post('teaching_quality_interaction_with_candidates') : '';

        $teaching_quality_softskill_session = !empty($this->input->post('teaching_quality_softskill_session')) ? $this->input->post('teaching_quality_softskill_session') : '';

        $candidates_attentiveness = !empty($this->input->post('candidates_attentiveness')) ? $this->input->post('candidates_attentiveness') : '';

        $DRA_attitude_behaviour = !empty($this->input->post('DRA_attitude_behaviour')) ? $this->input->post('DRA_attitude_behaviour') : '';

        $learning_quality_interaction_with_faculty = !empty($this->input->post('learning_quality_interaction_with_faculty')) ? $this->input->post('learning_quality_interaction_with_faculty') : '';

        $learning_quality_response_to_queries = !empty($this->input->post('learning_quality_response_to_queries')) ? $this->input->post('learning_quality_response_to_queries') : '';

        $teaching_effectiveness = !empty($this->input->post('teaching_effectiveness')) ? $this->input->post('teaching_effectiveness') : '';

        $curriculum_covered = !empty($this->input->post('curriculum_covered')) ? $this->input->post('curriculum_covered') : '';

        $overall_compliance_training_delivery = !empty($this->input->post('overall_compliance_training_delivery')) ? $this->input->post('overall_compliance_training_delivery') : '';

        $overall_compliance_training_coordination = !empty($this->input->post('overall_compliance_training_coordination')) ? $this->input->post('overall_compliance_training_coordination') : '';

        $other_observations = !empty($this->input->post('other_observations')) ? $this->input->post('other_observations') : '';

        $overall_observation = !empty($this->input->post('overall_observation')) ? $this->input->post('overall_observation') : '';

        $overall_compliance = !empty($this->input->post('overall_compliance')) ? $this->input->post('overall_compliance') : '';

        // print_r($_POST); die;

        $query1 = "SELECT count(1) cnt FROM dra_batch_inspection WHERE batch_id = " . $this->input->post('batch_id'); //." AND inspector_id = ".$inspector_id

        $result1 = $this->db->query($query1);
        $batch1 = $result1->result_array();

        if ($batch1[0]['cnt'] > 0)
        {
          $inspection_no = $batch1[0]['cnt'] + 1;
        }
        else
        {
          $inspection_no = 1;
        }

        $get_last_record = $this->master_model->getRecords('dra_batch_inspection', array('agency_id' => $this->input->post('agency_id'), 'batch_id' => $this->input->post('batch_id'), 'inspector_id' => $inspector_id), 'id, agency_id, batch_id, inspector_id, inspection_no, inspection_start_time, created_on', array('created_on' => 'DESC'));

        if (count($get_last_record) > 0)
        {
          $dateTimeObject1 = date_create($get_last_record[0]['created_on']);
          $dateTimeObject2 = date_create(date('Y-m-d H:i:s'));

          // Calculating the difference between DateTime Objects 
          $interval = date_diff($dateTimeObject1, $dateTimeObject2);
          $min = $interval->days * 24 * 60;
          $min += $interval->h * 60;
          $min += $interval->i;

          if ($get_last_record[0]['inspection_start_time'] == $inspection_start_time)
          {
            $file_error_flag = 1;
            $file_error_msg  = 'Duplicate form submission';

            // $this->session->set_flashdata('error','Duplicate form submission');
            // redirect(base_url() . 'iibfdra/InspectorHome/show_batch_inspection_report/'.$btch_id);
            
            // $result['message'] = "Duplicate form submission";
            // echo json_encode($result);
            // exit;
          }
          else if ($min < 1)
          {
            $file_error_flag = 1;
            $file_error_msg  = 'Wait for 1 min and submit the form again';

            // $this->session->set_flashdata('error','Wait for 1 min and submit the form again');
            // redirect(base_url() . 'iibfdra/InspectorHome/show_batch_inspection_report/'.$btch_id);
            

            // $result['message'] = "Wait for 1 min and submit the form again";
            // echo json_encode($result);
            // exit;
          }
        }

        // if($file_error_flag == 1)
        if(false)
        {
          $data['file_error_msg'] = $file_error_msg;
        }
        else
        {
          $insert_data = array(
            'agency_id' => $this->input->post('agency_id'),
            'batch_id' => $this->input->post('batch_id'),
            'inspector_id' => $inspector_id,
            'inspection_no' => $inspection_no,
            'candidates_loggedin' => $this->input->post('candidates_loggedin'),
            'platform_name' => $this->input->post('platform_name'),
            'multiple_login_same_name' => $this->input->post('multiple_login_same_name'),
            'instrument_name' => $this->input->post('instrument_name'),
            'issues' => $this->input->post('issues'),
            'training_session' => $this->input->post('training_session'),
            //'candidates_connected' => $this->input->post('candidates_connected'),
            'session_candidates' => $this->input->post('session_candidates'),
            'training_session_plan' => $this->input->post('training_session_plan'),
            //'actual_batch_coordinator' => $this->input->post('actual_batch_coordinator'),
            //'diff_batch_coordinator' => $this->input->post('diff_batch_coordinator'),
            'attendance_sheet_updated' => $this->input->post('attendance_sheet_updated'),
            'attendance_mode' => $this->input->post('attendance_mode'),
            'attendance_shown' => $this->input->post('attendance_shown'),
            'candidate_count_device' => $this->input->post('candidate_count_device'),
            'actual_faculty' => $this->input->post('actual_faculty'),
            'faculty_taking_session' => $this->input->post('faculty_taking_session'),
            'name_qualification' => $this->input->post('name_qualification'),
            'no_of_days' => $this->input->post('no_of_days'),
            'reason_of_change_in_faculty' => $this->input->post('reason_of_change_in_faculty'),
            'experience_teaching_training_BFSI_sector' => $this->input->post('experience_teaching_training_BFSI_sector'),
            'faculty_language' => $this->input->post('faculty_language'),
            'faculty_session_time' => $this->input->post('faculty_session_time'),
            'two_faculty_taking_session' => $this->input->post('two_faculty_taking_session'),
            'faculty_language_understandable' => $this->input->post('faculty_language_understandable'),
            'whiteboard_ppt_pdf_used' => $this->input->post('whiteboard_ppt_pdf_used'),
            'session_on_etiquettes' => $this->input->post('session_on_etiquettes'),
            'faculty_trainees_conversant' => $this->input->post('faculty_trainees_conversant'),
            'handbook_on_debt_recovery' => $this->input->post('handbook_on_debt_recovery'),
            'other_study_materials' => $this->input->post('other_study_materials'),
            'candidates_recognise' => $this->input->post('candidates_recognise'),
            'training_conduction' => $this->input->post('training_conduction'),
            'batch_coordinator_available' => $this->input->post('batch_coordinator_available'),
            'coordinator_available_name' => $this->input->post('coordinator_available_name'),
            'current_coordinator_available_name' => $this->input->post('current_coordinator_available_name'),
            'any_irregularity' => $this->input->post('any_irregularity'),
            //'assessment' => $this->input->post('assessment'),
            'teaching_quality_interaction_with_candidates' => $teaching_quality_interaction_with_candidates,
            'teaching_quality_softskill_session' => $teaching_quality_softskill_session,
            'candidates_attentiveness' => $candidates_attentiveness,
            'DRA_attitude_behaviour'  => $DRA_attitude_behaviour,
            'learning_quality_interaction_with_faculty' => $learning_quality_interaction_with_faculty,
            'learning_quality_response_to_queries' => $learning_quality_response_to_queries,
            'teaching_effectiveness' => $teaching_effectiveness,
            'curriculum_covered' => $curriculum_covered,
            'overall_compliance_training_delivery' => $overall_compliance_training_delivery,
            'overall_compliance_training_coordination' => $overall_compliance_training_coordination,
            'other_observations' => $other_observations,
            'overall_observation' => $overall_observation,
            'overall_compliance' => $overall_compliance,
            'attachment'  => $attachment,
            'inspection_start_time' => $inspection_start_time,
            'created_by'   => $inspector_id,
            'created_on' => date('Y-m-d H:i:s')
          );

          // echo "<pre>"; print_r($insert_data); exit;
          $inserted_id = $this->master_model->insertRecord('dra_batch_inspection',$insert_data,true);
          // echo $this->db->last_query(); 
          if ($inserted_id > 0)
          {
            // $result['flag'] = "success";
            // $result['inserted_id'] = $inserted_id;

            $batch_inspection_id = $this->db->insert_id();

            $remarkArr = $_POST['remark'];
            $attendanceArr = $_POST['attendance'];

            $qualiRemarkArr = $_POST['quali_remark'];
            $qualiVerifyArr = $_POST['quali_verify'];

            $photoRemarkArr = $_POST['photo_remark'];
            $photoVerifyArr = $_POST['photo_verify'];

            // $idProofRemarkArr = $_POST['idproof_remark'];
            // $idproofVerifyArr = $_POST['idproof_verify'];

            // print_r($remarkArr);
            // echo '<pre>';
            // print_r($_POST); exit;

            if (count($remarkArr) > 0)
            {
              foreach ($remarkArr as $key => $remark)
              {
                if ($remark[0] != '')
                {
                  $insert_child_data = array(
                    'batch_id' => $this->input->post('batch_id'),
                    'batch_inspection_id' => $batch_inspection_id,
                    'candidate_id' => $key,
                    'inspector_id' => $inspector_id,
                    'remark'  => $remark[0],
                    'created_by'   => $inspector_id,
                    'created_on' => date('Y-m-d H:i:s')
                  );

                  $inserted_child_id = $this->master_model->insertRecord('dra_candidate_inspection',$insert_child_data);
                  //echo '****'.$this->db->last_query();
                }
              }
            }

            if ( count($qualiRemarkArr) > 0 )
            {
              foreach ($qualiRemarkArr as $key => $qualiremark)
              {
                if ($qualiremark[0] != '')
                {
                  $where = array(
                    'batch_id' => $this->input->post('batch_id'),
                    'batch_inspection_id' => $batch_inspection_id,
                    'candidate_id' => $key
                  );
                  
                  $quali_candidate_data = $this->master_model->getRecords('dra_candidate_inspection', $where);

                  if (count($quali_candidate_data) > 0)
                  {
                    $update_data = array('qualification_remark' => $qualiremark[0]);
                    $upd_child_id = $this->master_model->updateRecord('dra_candidate_inspection', $update_data, $where);
                    //echo '---'.$this->db->last_query();
                  }
                  else
                  {
                    $insert_qualichild_data = array(
                      'batch_id' => $this->input->post('batch_id'),
                      'batch_inspection_id' => $batch_inspection_id,
                      'candidate_id' => $key,
                      'inspector_id' => $inspector_id,
                      'qualification_remark'  => $qualiremark[0],
                      'created_by'   => $inspector_id,
                      'created_on' => date('Y-m-d H:i:s')
                    );

                    $inserted_qualichild_id = $this->master_model->insertRecord('dra_candidate_inspection', $insert_qualichild_data);
                  }
                }
              }
            }

            /*------- Candidate Photo Remark---------*/
            if ( count($photoRemarkArr) > 0 )
            {
              foreach ($photoRemarkArr as $key => $photoremark)
              {
                if ($photoremark[0] != '')
                {
                  $where = array(
                    'batch_id' => $this->input->post('batch_id'),
                    'batch_inspection_id' => $batch_inspection_id,
                    'candidate_id' => $key
                  );
                  
                  $photo_candidate_data = $this->master_model->getRecords('dra_candidate_inspection', $where);

                  if (count($photo_candidate_data) > 0)
                  {
                    $update_data = array('photo_remark' => $photoremark[0]);
                    $upd_child_id = $this->master_model->updateRecord('dra_candidate_inspection', $update_data, $where);
                  }
                  else
                  {
                    $insert_qualichild_data = array(
                      'batch_id' => $this->input->post('batch_id'),
                      'batch_inspection_id' => $batch_inspection_id,
                      'candidate_id' => $key,
                      'inspector_id' => $inspector_id,
                      'photo_remark'  => $photoremark[0],
                      'created_by'   => $inspector_id,
                      'created_on' => date('Y-m-d H:i:s')
                    );

                    $inserted_qualichild_id = $this->master_model->insertRecord('dra_candidate_inspection', $insert_qualichild_data);
                  }
                }
              }
            }

            /*------- ID Proof Remark---------*/
            /*if ( count($idProofRemarkArr) > 0 )
            {
              foreach ($idProofRemarkArr as $key => $idProofremark)
              {
                if ($idProofremark[0] != '')
                {
                  $where = array(
                    'batch_id' => $this->input->post('batch_id'),
                    'batch_inspection_id' => $batch_inspection_id,
                    'candidate_id' => $key
                  );
                  
                  $idProof_candidate_data = $this->master_model->getRecords('dra_candidate_inspection', $where);

                  if (count($idProof_candidate_data) > 0)
                  {
                    $update_data = array('idproof_remark' => $idProofremark[0]);
                    $upd_child_id = $this->master_model->updateRecord('dra_candidate_inspection', $update_data, $where);
                  }
                  else
                  {
                    $insert_qualichild_data = array(
                      'batch_id' => $this->input->post('batch_id'),
                      'batch_inspection_id' => $batch_inspection_id,
                      'candidate_id' => $key,
                      'inspector_id' => $inspector_id,
                      'idproof_remark'  => $idProofremark[0],
                      'created_by'   => $inspector_id,
                      'created_on' => date('Y-m-d H:i:s')
                    );

                    $inserted_qualichild_id = $this->master_model->insertRecord('dra_candidate_inspection', $insert_qualichild_data);
                  }
                }
              }
            }*/

            if (count($attendanceArr) > 0)
            {
              foreach ($attendanceArr as $key => $attendance)
              {

                $where = array(
                  'batch_id' => $this->input->post('batch_id'),
                  'batch_inspection_id' => $batch_inspection_id,
                  'candidate_id' => $key
                );
                $candidate_data = $this->master_model->getRecords('dra_candidate_inspection', $where);

                if (count($candidate_data) > 0)
                {
                  $update_data  = array('attendance' => $attendance[0]);
                  $upd_child_id = $this->master_model->updateRecord('dra_candidate_inspection', $update_data, $where);
                  //echo '---'.$this->db->last_query();
                }
                else
                {
                  $insert_child_data = array(
                    'batch_id' => $this->input->post('batch_id'),
                    'batch_inspection_id' => $batch_inspection_id,
                    'candidate_id' => $key,
                    'inspector_id' => $inspector_id,
                    'attendance'  => $attendance[0],
                    'created_by'   => $inspector_id,
                    'created_on' => date('Y-m-d H:i:s')
                  );

                  $inserted_child_id = $this->master_model->insertRecord('dra_candidate_inspection', $insert_child_data);
                  //echo '+++'.$this->db->last_query();
                }
              }
            }

            /*------------------------------------------ Qualification Verify --------------------------------------------------*/

            if (count($qualiVerifyArr) > 0)
            {
              foreach ($qualiVerifyArr as $key => $qualiVerify)
              {
                $where = array(
                  'batch_id' => $this->input->post('batch_id'),
                  'batch_inspection_id' => $batch_inspection_id,
                  'candidate_id' => $key
                );

                $qualiveri_candidate_data = $this->master_model->getRecords('dra_candidate_inspection', $where);

                if (count($qualiveri_candidate_data) > 0)
                {
                  $update_data = array('qualification_verify' => $qualiVerify[0]);
                  $upd_childquali_id = $this->master_model->updateRecord('dra_candidate_inspection', $update_data, $where);
                }
                else
                {
                  $insert_qualichild_data = array(
                    'batch_id' => $this->input->post('batch_id'),
                    'batch_inspection_id' => $batch_inspection_id,
                    'candidate_id' => $key,
                    'inspector_id' => $inspector_id,
                    'qualification_verify'  => $qualiVerify[0],
                    'created_by'   => $inspector_id,
                    'created_on' => date('Y-m-d H:i:s')
                  );

                  $inserted_childquali_id = $this->master_model->insertRecord('dra_candidate_inspection', $insert_qualichild_data);
                  //echo '+++'.$this->db->last_query();
                }

                if ($qualiVerify[0] == 'Incorrect') 
                {
                  $candidate_data = $this->master_model->getRecords('dra_members', array('regid' => $key));
                  
                  if ($candidate_data[0]['hold_release'] == 'Release') 
                  {
                    $update_data = array('hold_release' => 'Auto Hold');
                    $res = $this->master_model->updateRecord('dra_members', $update_data,  array('regid' => $key));

                    if ($photoVerifyArr[$key][0] == 'Incorrect') {
                      $logTitle  = 'The candidate is put on HOLD due to an invalid Qualification Certificate, Candidate photo.';
                      $form_type = 'invalid_all';
                    } else {
                      $logTitle  = 'The candidate is put on HOLD due to an invalid Qualification Certificate.';
                      $form_type = 'invalid_qual';
                    }
                    
                    if($res) 
                    {
                      $add_cand_log['action']          = 'Update';   
                      $add_cand_log['form_type']       = $form_type;               
                      $add_cand_log['candidate_id']    = $key;
                      $add_cand_log['log_title']       = $logTitle;
                      $add_cand_log['log_decription']  = '';
                      $add_cand_log['status']          = 'success';
                      $add_cand_log['is_read']         = '0';
                      $add_cand_log['created_by_type'] = 'inspector';
                      $add_cand_log['created_by']      = $this->InstData['inspector_name'];
                      $add_cand_log['created_on']      = date("Y-m-d H:i:s");
                      $this->master_model->insertRecord('dra_candidate_logs',$add_cand_log);
                    } 
                  }
                }
                else
                {
                  if ($qualiVerify[0] == 'Correct') 
                  {
                    $candidate_data = $this->master_model->getRecords('dra_members', array('regid' => $key));
                  
                    if ($candidate_data[0]['hold_release'] != 'Release' && $candidate_data[0]['hold_release'] != 'Manual Hold' && ($photoVerifyArr[$key][0] == 'Correct' || $photoVerifyArr[$key][0] == '')) 
                    {
                      $update_data = array('hold_release' => 'Release');
                      $res = $this->master_model->updateRecord('dra_members', $update_data,  array('regid' => $key));

                      $this->db->where('form_type','EditOtherDetails Function');
                      $this->db->where('candidate_id',$key);
                      $this->db->where('is_read','0'); 
                      $this->db->update('dra_candidate_logs', array('is_read' => '1'));

                      $qualiLogTitle  = 'The candidate is put on Release due to a valid Qualification Certificate, Candidate Photo.';

                      if($res) 
                      {
                        $add_cand_log['action']          = 'Update';   
                        $add_cand_log['form_type']       = 'form3';               
                        $add_cand_log['candidate_id']    = $key;
                        $add_cand_log['log_title']       = $qualiLogTitle;
                        $add_cand_log['log_decription']  = '';
                        $add_cand_log['status']          = 'success';
                        $add_cand_log['is_read']         = '0';
                        $add_cand_log['created_by_type'] = 'inspector';
                        $add_cand_log['created_by']      = $this->InstData['inspector_name'];
                        $add_cand_log['created_on']      = date("Y-m-d H:i:s");
                        $this->master_model->insertRecord('dra_candidate_logs',$add_cand_log);
                      } 
                    }   
                  }  
                }
              }
            }

            /*----------------------------------------- Photo verify ----------------------------------------------*/
            if (count($photoVerifyArr) > 0)
            {
              foreach ($photoVerifyArr as $key => $photoVerify)
              {

                $where = array(
                  'batch_id' => $this->input->post('batch_id'),
                  'batch_inspection_id' => $batch_inspection_id,
                  'candidate_id' => $key
                );

                $photoveri_candidate_data = $this->master_model->getRecords('dra_candidate_inspection', $where);

                if (count($photoveri_candidate_data) > 0)
                {
                  $update_data = array('photo_verify' => $photoVerify[0]);
                  $upd_childphoto_id = $this->master_model->updateRecord('dra_candidate_inspection', $update_data, $where);
                }
                else
                {
                  $insert_photochild_data = array(
                    'batch_id' => $this->input->post('batch_id'),
                    'batch_inspection_id' => $batch_inspection_id,
                    'candidate_id' => $key,
                    'inspector_id' => $inspector_id,
                    'photo_verify'  => $photoVerify[0],
                    'created_by'   => $inspector_id,
                    'created_on' => date('Y-m-d H:i:s')
                  );

                  $inserted_childphoto_id = $this->master_model->insertRecord('dra_candidate_inspection', $insert_photochild_data);
                }

                if ($photoVerify[0] == 'Incorrect') 
                {
                  $candidate_data = $this->master_model->getRecords('dra_members', array('regid' => $key));

                  if ($candidate_data[0]['hold_release'] == 'Release') 
                  {
                    if ($qualiVerifyArr[$key][0] == 'Incorrect') {
                      $logTitle  = 'The candidate is put on HOLD due to an invalid Qualification Certificate, Candidate photo.';
                      $form_type = 'invalid_all';
                    } else {
                      $logTitle  = 'The candidate is put on HOLD due to an invalid Candidate Photo.';
                      $form_type = 'invalid_photo';
                    }

                    $update_data = array('hold_release' => 'Auto Hold');
                    $res = $this->master_model->updateRecord('dra_members', $update_data,array('regid' => $key));

                    if($res) 
                    {
                      $add_cand_log['action']          = 'Update';   
                      $add_cand_log['form_type']       = $form_type;               
                      $add_cand_log['candidate_id']    = $key;
                      $add_cand_log['log_title']       = $logTitle;
                      $add_cand_log['log_decription']  = '';
                      $add_cand_log['status']          = 'success';
                      $add_cand_log['is_read']         = '0';
                      $add_cand_log['created_by_type'] = 'inspector';
                      $add_cand_log['created_by']      = $this->InstData['inspector_name'];
                      $add_cand_log['created_on']      = date("Y-m-d H:i:s");
                      $this->master_model->insertRecord('dra_candidate_logs ',$add_cand_log);
                    } 
                  }
                }
                else
                {
                  if ($photoVerify[0] == 'Correct') 
                  {
                    $candidate_data = $this->master_model->getRecords('dra_members',array('regid' => $key));
                  
                    if ($candidate_data[0]['hold_release'] != 'Release' && $candidate_data[0]['hold_release'] != 'Manual Hold' && ($qualiVerifyArr[$key][0] == 'Correct' || $qualiVerifyArr[$key][0] == '')) 
                    {
                      $update_data = array('hold_release' => 'Release');
                      $res = $this->master_model->updateRecord('dra_members',$update_data,array('regid' => $key));

                      $qualiLogTitle  = 'The candidate is put on Release due to a valid Qualification Certificate, Candidate Photo.';
                      
                      if($res) 
                      {
                        $add_cand_log['action']          = 'Update';   
                        $add_cand_log['form_type']       = 'form4';               
                        $add_cand_log['candidate_id']    = $key;
                        $add_cand_log['log_title']       = $qualiLogTitle;
                        $add_cand_log['log_decription']  = '';
                        $add_cand_log['status']          = 'success';
                        $add_cand_log['is_read']         = '0';
                        $add_cand_log['created_by_type'] = 'inspector';
                        $add_cand_log['created_by']      = $this->InstData['inspector_name'];
                        $add_cand_log['created_on']      = date("Y-m-d H:i:s");
                        $this->master_model->insertRecord('dra_candidate_logs ',$add_cand_log);
                      } 
                    }   
                  } 
                }
              }
            }
            
            /*----------------------------------------- ID-Proof verify ----------------------------------------------*/
            /*if (count($idproofVerifyArr) > 0)
            {
              foreach ($idproofVerifyArr as $key => $idproofVerify)
              {
                $where = array(
                  'batch_id' => $this->input->post('batch_id'),
                  'batch_inspection_id' => $batch_inspection_id,
                  'candidate_id' => $key
                );

                $idproofveri_candidate_data = $this->master_model->getRecords('dra_candidate_inspection', $where);

                if (count($idproofveri_candidate_data) > 0)
                {
                  $update_data = array('idproof_verify' => $idproofVerify[0]);
                  $upd_childidproof_id = $this->master_model->updateRecord('dra_candidate_inspection', $update_data, $where);
                }
                else
                {
                  $insert_idproofchild_data = array(
                    'batch_id' => $this->input->post('batch_id'),
                    'batch_inspection_id' => $batch_inspection_id,
                    'candidate_id' => $key,
                    'inspector_id' => $inspector_id,
                    'idproof_verify'  => $idproofVerify[0],
                    'created_by'   => $inspector_id,
                    'created_on' => date('Y-m-d H:i:s')
                  );

                  $inserted_childphoto_id = $this->master_model->insertRecord('dra_candidate_inspection', $insert_photochild_data);
                }

                if ($idproofVerify[0] == 'Incorrect') 
                {
                  $candidate_data = $this->master_model->getRecords('dra_members', array('regid' => $key));

                  if ($candidate_data[0]['hold_release'] == 'Release') 
                  {
                    if ($qualiVerifyArr[$key][0] == 'Incorrect' && $photoVerifyArr[$key][0] == 'Incorrect') {
                      $logTitle  = 'The candidate is put on HOLD due to an invalid Qualification Certificate, Candidate photo & ID Proof.';
                      $form_type = 'invalid_all';
                    } elseif ($qualiVerifyArr[$key][0] == 'Incorrect' && $photoVerifyArr[$key][0] == 'Correct') {
                      $logTitle  = 'The candidate is put on HOLD due to an invalid Qualification Certificate & Candidate ID Proof.';
                      $form_type = 'invalid_qual_idproof';
                    } elseif ($qualiVerifyArr[$key][0] == 'Correct' && $photoVerifyArr[$key][0] == 'Incorrect') {
                      $logTitle  = 'The candidate is put on HOLD due to an invalid Candidate Photo & ID Proof.';
                      $form_type = 'invalid_photo_idproof';
                    } else {
                      $logTitle  = 'The candidate is put on HOLD due to an invalid Candidate ID Proof.';
                      $form_type = 'invalid_idproof';
                    }

                    $update_data = array('hold_release' => 'Auto Hold');
                    $res = $this->master_model->updateRecord('dra_members', $update_data,  array('regid' => $key));

                    if($res) 
                    {
                      $add_cand_log['action']          = 'Update';   
                      $add_cand_log['form_type']       = $form_type;               
                      $add_cand_log['candidate_id']    = $key;
                      $add_cand_log['log_title']       = $logTitle;
                      $add_cand_log['log_decription']  = '';
                      $add_cand_log['status']          = 'success';
                      $add_cand_log['is_read']         = '0';
                      $add_cand_log['created_by_type'] = 'inspector';
                      $add_cand_log['created_by']      = $this->InstData['inspector_name'];
                      $add_cand_log['created_on']      = date("Y-m-d H:i:s");
                      $this->master_model->insertRecord('dra_candidate_logs ',$add_cand_log);
                    } 
                  }
                }
                else
                {
                  if ($idproofVerify[0] == 'Correct') 
                  {
                    $candidate_data = $this->master_model->getRecords('dra_members',array('regid' => $key));
                  
                    if ($candidate_data[0]['hold_release'] != 'Release' && $qualiVerifyArr[$key][0] == 'Correct' && $photoVerifyArr[$key][0] == 'Correct') 
                    {
                      $update_data = array('hold_release' => 'Release');
                      $res = $this->master_model->updateRecord('dra_members',$update_data,array('regid' => $key));

                      if($res) 
                      {
                        $add_cand_log['action']          = 'Update';   
                        $add_cand_log['form_type']       = 'form4';               
                        $add_cand_log['candidate_id']    = $key;
                        $add_cand_log['log_title']       = 'The candidate is put on Release due to a valid Candidate ID Proof.';
                        $add_cand_log['log_decription']  = '';
                        $add_cand_log['status']          = 'success';
                        $add_cand_log['is_read']         = '0';
                        $add_cand_log['created_by_type'] = 'inspector';
                        $add_cand_log['created_by']      = $this->InstData['inspector_name'];
                        $add_cand_log['created_on']      = date("Y-m-d H:i:s");
                        $this->master_model->insertRecord('dra_candidate_logs ',$add_cand_log);
                      } 
                    }   
                  } 
                }
              }
            }*/



            $qry = $this->db->query("SELECT ci.batch_id, ci.candidate_id, count(ci.candidate_id) AS absent_cnt, ci.attendance, ab.hours 
            FROM dra_candidate_inspection ci LEFT JOIN agency_batch ab ON ab.id = ci.batch_id
            WHERE batch_id = ".$this->input->post('batch_id')." AND ci.attendance = 'Absent' group by ci.candidate_id"); 
            $res = $qry->result_array(); 

            //print_r($res);die;
            if(count($res) > 0)
            {
              foreach ($res as $key => $value) 
              {
                //echo '<br>50 hours++++++candidate_id --:'.$value['candidate_id '].'--hours:'.$value['hours'].'--absent_cnt--'.$value['absent_cnt'];
                if ($value['hours'] == 50 && $value['absent_cnt'] >= 3)
                {
                  $candidate_data = $this->master_model->getRecords('dra_members', array('regid' => $value['candidate_id']));
                  if ($candidate_data[0]['hold_release'] == 'Release') 
                  {
                    //echo '<br>candidate_id --:'.$value['candidate_id '];
                    $update_data = array('hold_release' => 'Auto Hold');
                    $res = $this->master_model->updateRecord('dra_members', $update_data,  array('regid' => $value['candidate_id']));
                    
                    if ($res) 
                    {
                      $add_cand_log['action']          = 'Update';   
                      $add_cand_log['form_type']       = 'form3';               
                      $add_cand_log['candidate_id']    = $value['candidate_id'];
                      $add_cand_log['log_title']       = 'The candidate is put on HOLD due to inadequate Attendance.';
                      $add_cand_log['log_decription']  = '';
                      $add_cand_log['status']          = 'success';
                      $add_cand_log['is_read']         = '0';
                      $add_cand_log['created_by_type'] = 'inspector';
                      $add_cand_log['created_by']      = $this->InstData['inspector_name'];
                      $add_cand_log['created_on']      = date("Y-m-d H:i:s");
                      $this->master_model->insertRecord('dra_candidate_logs ',$add_cand_log);
                    }
                  }  
                }
                else if ($value['hours'] == 100 && $value['absent_cnt'] >= 5)
                {
                  $candidate_data = $this->master_model->getRecords('dra_members', array('regid' => $value['candidate_id']));
                  if ($candidate_data[0]['hold_release'] == 'Release') 
                  {
                    $update_data = array('hold_release' => 'Auto Hold');
                    $res = $this->master_model->updateRecord('dra_members', $update_data,  array('regid' => $value['candidate_id']));
                    
                    $add_cand_log['action']          = 'Update';   
                    $add_cand_log['form_type']       = 'form3';               
                    $add_cand_log['candidate_id']    = $value['candidate_id'];
                    $add_cand_log['log_title']       = 'The candidate is put on HOLD due to inadequate Attendance.';
                    $add_cand_log['log_decription']  = '';
                    $add_cand_log['status']          = 'success';
                    $add_cand_log['is_read']         = '0';
                    $add_cand_log['created_by_type'] = 'inspector';
                    $add_cand_log['created_by']      = $this->InstData['inspector_name'];
                    $add_cand_log['created_on']      = date("Y-m-d H:i:s");
                    $this->master_model->insertRecord('dra_candidate_logs ',$add_cand_log);
                  }
                }
              }
            }

            $this->session->set_flashdata('success','Inspection Report Saved Successfully.');
            redirect(base_url() . 'iibfdra/Version_2/InspectorHome/show_batch_inspection_report/'.$btch_id);
          }
          else
          {
            $data['file_error_msg'] = 'Error occurred.When submitting an inspection form.';
          }
        }  
      }
    }

    $data['batch_id']             = $batch_id;
    $data['inspection_no']        = $inspection_no;
    $data['arr_batch_candidates'] = $arr_batch_candidates;
    $data['arr_inspe_batch']      = $inspe_batch;
    $data['middle_content']       = 'batch_inspector_report_view';
    $this->load->view('iibfdra/Version_2/common_view_inspector', $data);
  }

  public function inspection_report()
  {
    $login_inspector = $this->session->userdata('dra_inspector');
    $inspector_id   = $login_inspector['id'];
    $inspector_name = $login_inspector['inspector_name'];

    $DRA_Version_2_instId = $this->config->item('DRA_Version_2_instId');

    $this->db->select('a.institute_name, a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name,b.id, b.agency_id, b.batch_code, b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on');
    
    $this->db->order_by('b.id', 'DESC');
    $this->db->join('agency_inspector_master ai', 'b.inspector_id = ai.id', 'left');
    $this->db->join('dra_batch_inspection bi', 'b.id = bi.batch_id', 'left');
    $this->db->join('dra_accerdited_master a', 'b.agency_id = a.dra_inst_registration_id', 'left');
    $this->db->join('faculty_master f1', 'b.first_faculty=f1.faculty_id', 'left');
    $this->db->join('faculty_master f2', 'b.sec_faculty=f2.faculty_id', 'left');
    $this->db->join('faculty_master f3', 'b.additional_first_faculty=f3.faculty_id', 'left');
    $this->db->join('faculty_master f4', 'b.additional_sec_faculty=f4.faculty_id', 'left');

    $this->db->where(" (b.batch_status = 'Approved' OR b.batch_status = 'Hold') ");
    if ($inspector_name == 'DRA Cell') {
    	$where1 = array('b.is_deleted' => 0);	
    } else {
    	$where1 = array('b.is_deleted' => 0, 'b.inspector_id' => $inspector_id);
    }
    
    
    $this->db->where("b.batch_from_date <= CURDATE() AND b.batch_to_date >= CURDATE()");
    $this->db->where_in('b.agency_id', $DRA_Version_2_instId);
    $this->db->group_by('b.id');
    $data['batch'] = $batch = $this->master_model->getRecords('agency_batch b', $where1);

    // echo $this->db->last_query(); exit;
    $data['middle_content'] = 'inspector_report';
    $this->load->view('iibfdra/Version_2/common_view_inspector', $data);
  }

  public function save_inspection_report()//not in use
  {
    exit;
    $result['flag'] = "error";
    $result['message'] = "";

    $login_inspector = $this->session->userdata('dra_inspector');
    $inspector_id = $login_inspector['id'];
    $btch_id = $this->input->post('batch_id');
    // print_r($_POST); die;

    //print_r($_FILES); die;

    $file_error_flag = 0;
    $file_error_msg = '';
    if (!empty($_FILES['attachment']['name']))
    {
      if ($_FILES['attachment']['size'] == 0)
      {
        $result['message'] = "File is empty.. Please upload valid file.";
        echo json_encode($result);
        exit;        
      }
      else
      {
        //echo 'if';
        $config['upload_path'] = 'uploads/inspection_report';
        //$config['allowed_types'] = '*'; 
        $config['allowed_types'] = 'txt|doc|docx|pdf|png|jpg|jpeg';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('attachment'))
        {
          // Uploaded file data 
          $fileData = $this->upload->data();
          $uploadData['file_name'] = $fileData['file_name'];
          $uploadData['uploaded_on'] = date("Y-m-d H:i:s");
          $attachment      = $uploadData['file_name'];
          //echo '</br>'.$fileData['file_name'];

        }
        else
        {
          $result['message'] = $this->upload->display_errors();
          echo json_encode($result);
          exit;
          //$this->session->set_flashdata('error',$this->upload->display_errors());
          //redirect(base_url().'iibfdra/Version_2/inspectorHome/inspection_report');
          //$errorUploadType .= $_FILES['attachment']['name'].' | ';  
        }
      }
    }

    //echo '****'; die;

    $inspection_start_time = !empty($this->input->post('inspection_start_time')) ? $this->input->post('inspection_start_time') : '';

    $teaching_quality_interaction_with_candidates = !empty($this->input->post('teaching_quality_interaction_with_candidates')) ? $this->input->post('teaching_quality_interaction_with_candidates') : '';

    $teaching_quality_softskill_session = !empty($this->input->post('teaching_quality_softskill_session')) ? $this->input->post('teaching_quality_softskill_session') : '';

    $candidates_attentiveness = !empty($this->input->post('candidates_attentiveness')) ? $this->input->post('candidates_attentiveness') : '';

    $DRA_attitude_behaviour = !empty($this->input->post('DRA_attitude_behaviour')) ? $this->input->post('DRA_attitude_behaviour') : '';

    $learning_quality_interaction_with_faculty = !empty($this->input->post('learning_quality_interaction_with_faculty')) ? $this->input->post('learning_quality_interaction_with_faculty') : '';

    $learning_quality_response_to_queries = !empty($this->input->post('learning_quality_response_to_queries')) ? $this->input->post('learning_quality_response_to_queries') : '';

    $teaching_effectiveness = !empty($this->input->post('teaching_effectiveness')) ? $this->input->post('teaching_effectiveness') : '';

    $curriculum_covered = !empty($this->input->post('curriculum_covered')) ? $this->input->post('curriculum_covered') : '';

    $overall_compliance_training_delivery = !empty($this->input->post('overall_compliance_training_delivery')) ? $this->input->post('overall_compliance_training_delivery') : '';

    $overall_compliance_training_coordination = !empty($this->input->post('overall_compliance_training_coordination')) ? $this->input->post('overall_compliance_training_coordination') : '';

    $other_observations = !empty($this->input->post('other_observations')) ? $this->input->post('other_observations') : '';

    $overall_observation = !empty($this->input->post('overall_observation')) ? $this->input->post('overall_observation') : '';

    $overall_compliance = !empty($this->input->post('overall_compliance')) ? $this->input->post('overall_compliance') : '';

    //print_r($_POST); die;

    $query1 = "SELECT count(1) cnt FROM dra_batch_inspection WHERE batch_id = " . $this->input->post('batch_id'); //." AND inspector_id = ".$inspector_id

    $result1 = $this->db->query($query1);
    $batch1 = $result1->result_array();

    if ($batch1[0]['cnt'] > 0)
    {
      $inspection_no = $batch1[0]['cnt'] + 1;
    }
    else
    {
      $inspection_no = 1;
    }

    $get_last_record = $this->master_model->getRecords('dra_batch_inspection', array('agency_id' => $this->input->post('agency_id'), 'batch_id' => $this->input->post('batch_id'), 'inspector_id' => $inspector_id), 'id, agency_id, batch_id, inspector_id, inspection_no, inspection_start_time, created_on', array('created_on' => 'DESC'));

    if (count($get_last_record) > 0)
    {
      $dateTimeObject1 = date_create($get_last_record[0]['created_on']);
      $dateTimeObject2 = date_create(date('Y-m-d H:i:s'));

      // Calculating the difference between DateTime Objects 
      $interval = date_diff($dateTimeObject1, $dateTimeObject2);
      $min = $interval->days * 24 * 60;
      $min += $interval->h * 60;
      $min += $interval->i;

      if ($get_last_record[0]['inspection_start_time'] == $inspection_start_time)
      {
        $result['message'] = "Duplicate form submission";
        echo json_encode($result);
        exit;
      }
      else if ($min < 1)
      {
        $result['message'] = "Wait for 1 min and submit the form again";
        echo json_encode($result);
        exit;
      }
    }

    $insert_data = array(
      'agency_id' => $this->input->post('agency_id'),
      'batch_id' => $this->input->post('batch_id'),
      'inspector_id' => $inspector_id,
      'inspection_no' => $inspection_no,
      'candidates_loggedin' => $this->input->post('candidates_loggedin'),
      'platform_name' => $this->input->post('platform_name'),
      'multiple_login_same_name' => $this->input->post('multiple_login_same_name'),
      'instrument_name' => $this->input->post('instrument_name'),
      'issues' => $this->input->post('issues'),
      'training_session' => $this->input->post('training_session'),
      //'candidates_connected' => $this->input->post('candidates_connected'),
      'session_candidates' => $this->input->post('session_candidates'),
      'training_session_plan' => $this->input->post('training_session_plan'),
      //'actual_batch_coordinator' => $this->input->post('actual_batch_coordinator'),
      //'diff_batch_coordinator' => $this->input->post('diff_batch_coordinator'),
      'attendance_sheet_updated' => $this->input->post('attendance_sheet_updated'),
      'attendance_mode' => $this->input->post('attendance_mode'),
      'attendance_shown' => $this->input->post('attendance_shown'),
      'candidate_count_device' => $this->input->post('candidate_count_device'),
      'actual_faculty' => $this->input->post('actual_faculty'),
      'faculty_taking_session' => $this->input->post('faculty_taking_session'),
      'name_qualification' => $this->input->post('name_qualification'),
      'no_of_days' => $this->input->post('no_of_days'),
      'reason_of_change_in_faculty' => $this->input->post('reason_of_change_in_faculty'),
      'experience_teaching_training_BFSI_sector' => $this->input->post('experience_teaching_training_BFSI_sector'),
      'faculty_language' => $this->input->post('faculty_language'),
      'faculty_session_time' => $this->input->post('faculty_session_time'),
      'two_faculty_taking_session' => $this->input->post('two_faculty_taking_session'),
      'faculty_language_understandable' => $this->input->post('faculty_language_understandable'),
      'whiteboard_ppt_pdf_used' => $this->input->post('whiteboard_ppt_pdf_used'),
      'session_on_etiquettes' => $this->input->post('session_on_etiquettes'),
      'faculty_trainees_conversant' => $this->input->post('faculty_trainees_conversant'),
      'handbook_on_debt_recovery' => $this->input->post('handbook_on_debt_recovery'),
      'other_study_materials' => $this->input->post('other_study_materials'),
      'candidates_recognise' => $this->input->post('candidates_recognise'),
      'training_conduction' => $this->input->post('training_conduction'),
      'batch_coordinator_available' => $this->input->post('batch_coordinator_available'),
      'coordinator_available_name' => $this->input->post('coordinator_available_name'),
      'current_coordinator_available_name' => $this->input->post('current_coordinator_available_name'),
      'any_irregularity' => $this->input->post('any_irregularity'),
      //'assessment' => $this->input->post('assessment'),
      'teaching_quality_interaction_with_candidates' => $teaching_quality_interaction_with_candidates,
      'teaching_quality_softskill_session' => $teaching_quality_softskill_session,
      'candidates_attentiveness' => $candidates_attentiveness,
      'DRA_attitude_behaviour'  => $DRA_attitude_behaviour,
      'learning_quality_interaction_with_faculty' => $learning_quality_interaction_with_faculty,
      'learning_quality_response_to_queries' => $learning_quality_response_to_queries,
      'teaching_effectiveness' => $teaching_effectiveness,
      'curriculum_covered' => $curriculum_covered,
      'overall_compliance_training_delivery' => $overall_compliance_training_delivery,
      'overall_compliance_training_coordination' => $overall_compliance_training_coordination,
      'other_observations' => $other_observations,
      'overall_observation' => $overall_observation,
      'overall_compliance' => $overall_compliance,
      'attachment'  => $attachment,
      'inspection_start_time' => $inspection_start_time,
      'created_by'   => $inspector_id,
      'created_on' => date('Y-m-d H:i:s')
    );

    //print_r($insert_data);
    $inserted_id = $this->master_model->insertRecord('dra_batch_inspection', $insert_data);
    //echo $this->db->last_query(); 
    if ($inserted_id)
    {
      $result['flag'] = "success";
      $result['inserted_id'] = $inserted_id;

      $batch_inspection_id = $this->db->insert_id();

      $remarkArr = $_POST['remark'];
      $attendanceArr = $_POST['attendance'];

      /*print_r($remarkArr);
      echo '</br>';
      print_r($attendanceArr);*/

      if (count($remarkArr) > 0)
      {
        foreach ($remarkArr as $key => $remark)
        {
          if ($remark[0] != '')
          {
            $insert_child_data = array(
              'batch_id' => $this->input->post('batch_id'),
              'batch_inspection_id' => $batch_inspection_id,
              'candidate_id' => $key,
              'inspector_id' => $inspector_id,
              'remark'  => $remark[0],
              'created_by'   => $inspector_id,
              'created_on' => date('Y-m-d H:i:s')
            );

            $inserted_child_id = $this->master_model->insertRecord('dra_candidate_inspection', $insert_child_data);

            //echo '****'.$this->db->last_query();
          }
        }
      }

      if (count($attendanceArr) > 0)
      {
        foreach ($attendanceArr as $key => $attendance)
        {

          $where = array(
            'batch_id' => $this->input->post('batch_id'),
            'batch_inspection_id' => $batch_inspection_id,
            'candidate_id' => $key
          );
          $candidate_data = $this->master_model->getRecords('dra_candidate_inspection', $where);

          if (count($candidate_data) > 0)
          {
            $update_data = array('attendance' => $attendance[0]);
            $upd_child_id = $this->master_model->updateRecord('dra_candidate_inspection', $update_data, $where);
            //echo '---'.$this->db->last_query();
          }
          else
          {
            $insert_child_data = array(
              'batch_id' => $this->input->post('batch_id'),
              'batch_inspection_id' => $batch_inspection_id,
              'candidate_id' => $key,
              'inspector_id' => $inspector_id,
              'attendance'  => $attendance[0],
              'created_by'   => $inspector_id,
              'created_on' => date('Y-m-d H:i:s')
            );

            $inserted_child_id = $this->master_model->insertRecord('dra_candidate_inspection', $insert_child_data);
            //echo '+++'.$this->db->last_query();
          }
        }
      }
    }

    /* $qry = $this->db->query("SELECT SUM(case when c.attendance='Absent' then 1 else 0 end) as absent_cnt, a.hours, c.candidate_id
      FROM dra_candidate_inspection c LEFT JOIN agency_batch a ON a.id = c.batch_id
      group by c.candidate_id"); */
    
    $qry = $this->db->query("SELECT ci.batch_id, ci.candidate_id, count(ci.candidate_id) AS absent_cnt, ci.attendance, ab.hours 
    FROM dra_candidate_inspection ci LEFT JOIN agency_batch ab ON ab.id = ci.batch_id
    WHERE batch_id = ".$this->input->post('batch_id')." AND ci.attendance = 'Absent' group by ci.candidate_id"); 
    $res = $qry->result_array(); 

    //print_r($res);die;
    if(count($res) > 0)
    {
      foreach ($res as $key => $value) 
      {
        //echo '<br>50 hours++++++candidate_id --:'.$value['candidate_id '].'--hours:'.$value['hours'].'--absent_cnt--'.$value['absent_cnt'];
        if ($value['hours'] == 50 && $value['absent_cnt'] >= 3)
        {
          //echo '<br>candidate_id --:'.$value['candidate_id '];
          $update_data = array('hold_release' => 'Auto Hold');
          $res = $this->master_model->updateRecord('dra_members', $update_data,  array('regid' => $value['candidate_id']));
          //echo '<br>'.$this->db->last_query();
        }
        else if ($value['hours'] == 100 && $value['absent_cnt'] >= 5)
        {
          //echo '<br>100 hours***********candidate_id --:'.$value['candidate_id '];
          $update_data = array('hold_release' => 'Auto Hold');
          $res = $this->master_model->updateRecord('dra_members', $update_data,  array('regid' => $value['candidate_id']));
          //echo '<br>'.$this->db->last_query();
        }
      }
    }
    //die();
    //echo $inserted_id;
    echo json_encode($result);
  }

  public function get_candidate_data()
  {
    $login_inspector = $this->session->userdata('dra_inspector');
    $inspector_id = $login_inspector['id'];

    $batch_id = $_POST['batch_id'];

    $query1 = "SELECT count(1) cnt FROM dra_batch_inspection WHERE batch_id = " . $batch_id; //." AND inspector_id = ".$inspector_id

    $result1 = $this->db->query($query1);
    $batch1 = $result1->result_array();

    if ($batch1[0]['cnt'] > 0)
    {
      $inspection_no = $batch1[0]['cnt'] + 1;
    }
    else
    {
      $inspection_no = 1;
    }

    $agency = $this->db->query("SELECT agency_batch.agency_id, dra_accerdited_master.institute_code
	        FROM agency_batch LEFT JOIN  dra_accerdited_master ON agency_batch.agency_id = dra_accerdited_master.dra_inst_registration_id
	        WHERE agency_batch.id=" . $batch_id);

    $agency_data = $agency->result_array();

    $query = "SELECT regid, training_id, concat(namesub, ' ', firstname, ' ', middlename, ' ', lastname) as name, dateofbirth, mobile_no, scannedphoto FROM dra_members WHERE batch_id = " . $batch_id . "
			AND inst_code = " . $agency_data[0]['institute_code']; //ACTUAL QUERY

    $result = $this->db->query($query);
    $batch = $result->result_array();

    $batch_login_details = $this->master_model->getRecords('agency_online_batch_user_details', array('batch_id' => $batch_id));

    $str1 = '';
    $str1 .= '<table border="solid 1%">';
    $str1 .= '<thead>';
    $str1 .= '<tr>';
    $str1 .= '<th width="5%">Login Id</th>';
    $str1 .= '<th width="5%">Password</th>';
    $str1 .= '</tr>';
    $str1 .= '</thead>';
    $str1 .= '<tbody>';
    foreach ($batch_login_details as $key => $value)
    {
      $str1 .= '<tr>';
      $str1 .= '<td width="5%">' . $value['login_id'] . '</td>';
      $str1 .= '<td width="5%">' . base64_decode($value['password']) . '</td>';
      $str1 .= '</tr>';
    }
    $str1 .= '</tbody>';
    $str1 .= '</table>';

    echo $inspection_no . ':::::' . json_encode($batch) . ':::::' . $str1;
  }
 

  public function batch_inspection_report()
  {
    $inspector_id = $this->InstData['id'];
    $inspector_name = $this->InstData['inspector_name'];

    $DRA_Version_2_instId = $this->config->item('DRA_Version_2_instId');
    $DRA_Version_2_instIdStr = implode(',', $DRA_Version_2_instId);

    $selectQuery = "SELECT a.institute_name,a.short_inst_name,COUNT(bi.id) as reported,GROUP_CONCAT(bi.overall_compliance) as overall_compliance_list, ai.inspector_name, b.agency_id,b.id,b.batch_code, b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_id as first_faculty_id, f1.faculty_name as first_faculty_name,f1.faculty_code as first_faculty_code,f1.faculty_photo as first_faculty_photo, f2.faculty_id as sec_faculty_id, f2.faculty_name as sec_faculty_name, f2.faculty_code as sec_faculty_code,f2.faculty_photo as sec_faculty_photo, f3.faculty_id as add_first_faculty_id, f3.faculty_name as add_first_faculty_name, f3.faculty_code as add_first_faculty_code,f3.faculty_photo as add_first_faculty_photo, f4.faculty_id as add_sec_faculty_id, f4.faculty_name as add_sec_faculty_name, f4.faculty_code as add_sec_faculty_code,f4.faculty_photo as add_sec_faculty_photo, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule, b.created_on
	        FROM agency_batch b 
          LEFT JOIN agency_inspector_master ai ON b.inspector_id = ai.id
          LEFT JOIN  dra_batch_inspection bi ON b.id = bi.batch_id
          LEFT JOIN  dra_accerdited_master a ON b.agency_id = a.dra_inst_registration_id
          LEFT JOIN faculty_master f1 ON b.first_faculty=f1.faculty_id 
          LEFT JOIN faculty_master f2 ON b.sec_faculty=f2.faculty_id
	        LEFT JOIN faculty_master f3 ON b.additional_first_faculty=f3.faculty_id
	        LEFT JOIN faculty_master f4 ON b.additional_sec_faculty=f4.faculty_id
	        WHERE (b.batch_status = 'Approved' OR b.batch_status = 'Cancelled' OR b.batch_status = 'Hold') 
	        AND b.agency_id IN (" . $DRA_Version_2_instIdStr . ")";
	        
	  if ($inspector_name != 'DRA Cell') {    
      $selectQuery .=	" AND   b.id IN (SELECT batch_id FROM dra_batch_inspection WHERE inspector_id = '".$inspector_id."') ";   	
	  }

    /*if ($inspector_name == 'DRA Cell') {
      $selectQuery .= " AND b.is_deleted = 0 "; 
    } else {
      $selectQuery .= " AND b.is_deleted = 0 AND b.inspector_id = ".$inspector_id;
    }*/
		
	  /* $selectQuery .= " AND  EXISTS (SELECT i.batch_id FROM dra_batch_inspection i WHERE b.id = i.batch_id)
	        ORDER BY b.id DESC"; */
    $selectQuery .= " GROUP BY b.id ORDER BY b.id DESC";

    $batchQry = $this->db->query($selectQuery);

    $data['batch'] = $batch = $batchQry->result_array();
    // echo $this->db->last_query(); exit;
    $data['middle_content'] = 'batch_inspection_report';
    $this->load->view('iibfdra/Version_2/common_view_inspector', $data);
  }


  public function get_data()
  {

    $batch_id = $_POST['batch_id'];

    $login_inspector = $this->session->userdata('dra_inspector');
    $inspector_id = $login_inspector['id'];

    $query1 = "SELECT * FROM dra_batch_inspection WHERE batch_id = " . $batch_id . " AND inspector_id =" . $inspector_id; //ACTUAL QUERY

    $result1 = $this->db->query($query1);
    $batch_insp = $result1->result_array();
    $width = '30%';
    $str = '';

    $str .= '<tr>';
    $str .= '<td style="min-width:80px;white-space: nowrap !important;"><strong>Sr</strong></td>';
    $str .= '<td style="min-width:150px;"><strong>Title</strong></td>';

    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td style="min-width:150px;"><strong>Inspection No:' . $batch['inspection_no'] . '</strong></td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="10%"><strong>Inspection Start Date/Time</strong></td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td><strong>' . $batch['inspection_start_time'] . '</strong></td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="10%"><strong>Inspection End Date/Time</strong></td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td><strong>' . $batch['created_on'] . '</strong></td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="5%"><strong>Inspection Name/ID</strong></td>';
    foreach ($batch_insp as $key => $batch)
    {
      $insp = $this->master_model->getRecords('agency_inspector_master', array('id' => $batch['inspector_id']));
      $str .= '<td><strong>' . $insp[0]['inspector_name'] . '/ ' . $insp[0]['id'] . '</strong></td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">1</td>';
    $str .= '<td width="' . $width . '">Number of candidates logged-in at start of visit to the platform (excluding self / faculty/ coordinator or any other administrator)</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['candidates_loggedin'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">2</td>';
    $str .= '<td width="' . $width . '">Whether the declared Link / Platform for the training got changed (Yes / No). If Yes, mention the Link / Name of the Platform for the training purpose.</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['platform_name'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">3</td>';
    $str .= '<td width="' . $width . '">Whether there are multiple logins with same name (Yes / No)? If Yes, how many such multiple logins are there?</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['multiple_login_same_name'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">4</td>';
    $str .= '<td width="' . $width . '">Whether log-ins with instrument name (Samsung/oppo etc) is there (Yes / No). If Yes, how many such log-ins?</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['instrument_name'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">5</td>';
    $str .= '<td width="' . $width . '">Whether any issues were faced while logging-in onto the Online Platform (e.g. wrong log-in credentials / waited for more than 2 minutes in waiting room / taking you into a platform of a different link / only buffering for minutes etc.)</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['issues'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">6</td>';
    $str .= '<td width="' . $width . '">Whether virtual recording is ‘On’ or “not On” or started after your joining / insisting for the same. In case the session recording is not on, mention the reason of such situation.</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['training_session'] . '</td>';
    }
    $str .= '</tr>';

    /*$str.='<tr>';
		$str.='<td width="3%" style="white-space: nowrap !important;">7</td>';
		$str.='<td width="'.$width.'">Number of candidates connected/login to the platform on start of inspection</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['candidates_connected'].'</td>';
		}
		$str.='</tr>';*/

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">7</td>';
    $str .= '<td width="' . $width . '">Training Details</td>';
    $str .= '<td></td>';
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">(i) No. of candidates available during training sessions</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['session_candidates'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">(ii) Is the training going on as per session plan shared by the Agency (can be confirmed from the Faculty)</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['training_session_plan'] . '</td>';
    }
    $str .= '</tr>';

    /*$str.='<tr>';
		$str.='<td width="3%" style="white-space: nowrap !important;">9</td>';
		$str.='<td width="'.$width.'">Whether Name of Batch Coordinator is displayed on the platform (Yes - enter the relevant information / No)</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['actual_batch_coordinator'].'</td>';
		}
		$str.='</tr>';

		$str.='<tr>';
		$str.='<td width="3%" style="white-space: nowrap !important;">10</td>';
		$str.='<td width="'.$width.'">Coordinator is same as allotted or not (Yes/ No) if not mention the name of the co-ordinator</td>';
		foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['diff_batch_coordinator'].'</td>';
		}
		$str.='</tr>';*/

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">8</td>';
    $str .= '<td width="' . $width . '">Attendance</td>';
    $str .= '<td></td>';
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">i. Whether Attendance Sheet is updated by the Agency till the time of inspection (Yes / No).</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['attendance_sheet_updated'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">ii. Mode of taking attendance (Online / Screen Shot / Manual calling etc.)</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['attendance_mode'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">iii. Whether the Attendance Sheet is shown promptly to the Inspector on demand (Yes / No).</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['attendance_shown'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">9</td>';
    $str .= '<td width="' . $width . '">Is there any group of candidates attending the sessions through a single device? (loptop/Mobile/PC/Big screen/monitor)
                    please mention the candidate count and device)</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['candidate_count_device'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">10</td>';
    $str .= '<td width="' . $width . '">Faculty Details</td>';
    $str .= '<td></td>';
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">a) Whether Name / Code of Faculty is displayed on the platform (Yes / No).</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['actual_faculty'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">b) Name / Code of Faculty taking session</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['faculty_taking_session'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">c) If the Faculty who is taking session is different from the declared one, please mention:
	         <br>i. Name and Qualification (highest) of the Faculty</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['name_qualification'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '"><br>ii. No. of days / sessions she/he has taken / will take</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['no_of_days'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '"><br>iii. Reason of such change in faculty</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['reason_of_change_in_faculty'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '"><br>iv. Whether the Faculty is having earlier experience in teaching / training in BFSI sector (mention in brief).</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['experience_teaching_training_BFSI_sector'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">d) Language in which the Faculty is taking the session</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['faculty_language'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">e) The Faculty is taking sessions for how many hrs/min per day</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['faculty_session_time'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">f) Whether there are minimum 2 faculties are taking sessions to complete the 50 / 100 hours training.</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['two_faculty_taking_session'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">g) Whether the language(s) used by the Faculty is understandable by the candidates (can be confirmed from the participants).</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['faculty_language_understandable'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">h) Whether the online training tools like whiteboard / PPT / PDF / Documents are used while delivering lectures.</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['whiteboard_ppt_pdf_used'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">11</td>';
    $str .= '<td width="' . $width . '">Whether the faculty (in case of new faculty only) and all the candidates have attended preparatory / briefing session on the etiquettes of the upcoming DRA training (Yes / No).</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['session_on_etiquettes'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">12</td>';
    $str .= '<td width="' . $width . '">Whether the faculty and trainees were conversant with the process of on-line training.</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['faculty_trainees_conversant'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">13</td>';
    $str .= '<td width="' . $width . '">Whether the candidates could recognise the name of the training providing agency / institution (Yes / No).</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['candidates_recognise'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">14</td>';
    $str .= '<td width="' . $width . '">Whether candidates were given "Handbook on debt recovery" by the concerned agency.</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['handbook_on_debt_recovery'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">15</td>';
    $str .= '<td width="' . $width . '">Whether candidates are provided with other study materials in word/pdf format by the agency).</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['other_study_materials'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">16</td>';
    $str .= '<td width="' . $width . '">Whether the training was conducted without any interruption/ disturbances/ noises?</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['training_conduction'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">17</td>';
    $str .= '<td width="' . $width . '">Batch Coordinator</td>';
    $str .= '<td></td>';
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">a) Whether Name of Batch Coordinator is displayed on the virtual platform with Batch Code (Yes / No).</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['batch_coordinator_available'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">b) Name / Code of the Coordinator is available in the Session</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['coordinator_available_name'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">c) The Coordinator is whether originally allotted or not (Yes/ No). In case No, mention the name and contact no. of the available Coordinator.</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['current_coordinator_available_name'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">18</td>';
    $str .= '<td width="' . $width . '">Any irregularity(ies) consistently / frequently persist despite repetitive reminders for rectification</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['any_irregularity'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">19</td>';
    $str .= '<td width="' . $width . '">Assessment / rating (viz. 1-Poor / 2-Average / 3-Good / 4-Excellent) consequent to overall impression during visit to the virtual training session</td>';
    $str .= '<td></td>';
    /*foreach($batch_insp as $key => $batch){
			$str.='<td>'.$batch['assessment'].'</td>';
		}*/
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">a) Quality of Teaching:
           <br>i. Level of interaction with candidates
           </td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['teaching_quality_interaction_with_candidates'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">
           ii. Understanding with curiosity while teaching (especially  during soft-skill session)</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['teaching_quality_softskill_session'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">b) Candidates attentiveness and participation</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['candidates_attentiveness'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">c) Candidates Attitude and their Behaviour</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['DRA_attitude_behaviour'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">d) Quality of learning by DRAs:
                <br>i.  Interaction with Faculty</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['learning_quality_interaction_with_faculty'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">
                ii. Response to queries made by faculty / inspector </td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['learning_quality_response_to_queries'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">e) Effectiveness of training</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['teaching_effectiveness'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">f) Curriculum covered with reference to the Syllabus</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['curriculum_covered'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">g) Overall compliance on:
                i.  Training delivery</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['overall_compliance_training_delivery'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;"></td>';
    $str .= '<td width="' . $width . '">
                ii. Training coordination</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['overall_compliance_training_coordination'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">20</td>';
    $str .= '<td width="' . $width . '">Any other observations with respect to non-adherence to the conditions stipulated by IIBF for conducting on-line DRA Training</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['other_observations'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">21</td>';
    $str .= '<td width="' . $width . '">Overall Observation of the Inspector on the training of the DRA Batch</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['overall_observation'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">22</td>';
    $str .= '<td width="' . $width . '">Over all compliance</td>';
    foreach ($batch_insp as $key => $batch)
    {
      $str .= '<td>' . $batch['overall_compliance'] . '</td>';
    }
    $str .= '</tr>';

    $str .= '<tr>';
    $str .= '<td width="3%" style="white-space: nowrap !important;">23</td>';
    $str .= '<td width="' . $width . '">Attachment</td>';
    foreach ($batch_insp as $key => $batch)
    {
      if (!empty($batch['attachment']))
      {
        $str .= '<td><a href="' . base_url('uploads/inspection_report/' . $batch['attachment']) . '" target="_blank">View</a></td>';
      }
      else
      {
        $str .= '<td></td>';
      }

      //$str.='<td></td>';
    }
    $str .= '</tr>';

    $agency = $this->db->query("SELECT agency_batch.agency_id, dra_accerdited_master.institute_code
	        FROM agency_batch LEFT JOIN  dra_accerdited_master ON agency_batch.agency_id = dra_accerdited_master.dra_inst_registration_id
	        WHERE agency_batch.id=" . $batch_id);

    $agency_data = $agency->result_array();

    $query = "SELECT m.regid, m.training_id, concat(m.namesub, ' ', m.firstname, ' ', m.middlename, ' ', m.lastname) as name, m.dateofbirth, m.mobile_no, m.hold_release, m.scannedphoto,m.idproofphoto,m.quali_certificate,
			SUM(case when c.attendance='Present' then 1 else 0 end) as present_cnt,
			SUM(case when c.attendance='Absent' then 1 else 0 end) as absent_cnt,
			TRIM(BOTH '| ' FROM GROUP_CONCAT(c.remark SEPARATOR '| ')) AS remark,
      TRIM(BOTH '| ' FROM GROUP_CONCAT(c.qualification_verify SEPARATOR '| ')) AS qualification_verify,
      TRIM(BOTH '| ' FROM GROUP_CONCAT(c.qualification_remark SEPARATOR '| ')) AS qualification_remark,
      TRIM(BOTH '| ' FROM GROUP_CONCAT(c.photo_verify SEPARATOR '| ')) AS photo_verify,
      TRIM(BOTH '| ' FROM GROUP_CONCAT(c.photo_remark SEPARATOR '| ')) AS photo_remark
			FROM dra_members m LEFT JOIN dra_candidate_inspection c ON m.regid = c.candidate_id  
			WHERE m.batch_id = " . $batch_id . "
			AND inst_code = " . $agency_data[0]['institute_code'] . "
			GROUP BY m.regid
			ORDER BY m.regid DESC"; //ACTUAL QUERY

    $result = $this->db->query($query);
    $batch = $result->result_array();

    $inspe_query = "SELECT
      DATE_FORMAT(c.created_on, '%d-%b-%Y %H:%i:%s') AS date_time,  
      SUM(case when c.attendance='Present' then 1 else 0 end) as present_cnt,
      SUM(case when c.attendance='Absent' then 1 else 0 end) as absent_cnt
      FROM dra_candidate_inspection c  
      WHERE c.batch_id = ".$batch_id."
      GROUP BY c.batch_inspection_id"; //ORDER BY m.regid DESC
      
    $inspe_result = $this->db->query($inspe_query);  
    $inspe_batch  = $inspe_result->result_array();
    
    foreach ($batch as $key => $value)
    {
      $rmrk = explode('| ', $value['remark']);
      $remark1 = '';
      //print_r($rmrk);
      foreach ($rmrk as $key1 => $val1)
      {
        if ($val1 != '')
        {
          $a = $key1 + 1;
          $remark1 .= $a . ') ' . $val1 . '<br>';
        }
      }
      $batch[$key]['remark'] = $remark1;

      $qualification_rmrk = explode('| ', $value['qualification_remark']);
      $qualification_remark1 = '';
      //print_r($rmrk);
      foreach ($qualification_rmrk as $key2 => $val2)
      {
        if ($val2 != '')
        {
          $b = $key2 + 1;
          $qualification_remark1 .= $b . ') ' . $val2 . '<br>';
        }
      }
      $batch[$key]['qualification_remark'] = $qualification_remark1;


      $photo_rmrk = explode('| ', $value['photo_remark']);
      $photo_remark1 = '';
      //print_r($rmrk);
      foreach ($photo_rmrk as $key7 => $val7)
      {
        if ($val7 != '')
        {
          $y = $key7 + 1;
          $photo_remark1 .= $y . ') ' . $val7 . '<br>';
        }
      }
      $batch[$key]['photo_remark'] = $photo_remark1;

    
      $qualification_verify = explode('| ', $value['qualification_verify']);
      $qualification_verify1 = '';
      //print_r($rmrk);
      foreach ($qualification_verify as $key3 => $val3)
      {
        if ($val3 != '')
        {
          $c = $key3 + 1;
          $qualification_verify1 .= $c . ') ' . $val3 . '<br>';
        }
      }
      $batch[$key]['qualification_verify'] = $qualification_verify1;

      $photo_verify = explode('| ', $value['photo_verify']);
      $photo_verify1 = '';
      //print_r($rmrk);
      foreach ($photo_verify as $key8 => $val8)
      {
        if ($val8 != '')
        {
          $x = $key8 + 1;
          $photo_verify1 .= $x . ') ' . $val8 . '<br>';
        }
      }
      $batch[$key]['photo_verify'] = $photo_verify1;
    }

    $batch_login_details = $this->master_model->getRecords('agency_online_batch_user_details', array('batch_id' => $batch_id));

    $str1 = '';
    $str1 .= '<table border="solid 1%">';
    $str1 .= '<thead>';
    $str1 .= '<tr>';
    $str1 .= '<th width="5%">Login Id</th>';
    $str1 .= '<th width="5%">Password</th>';
    $str1 .= '</tr>';
    $str1 .= '</thead>';
    $str1 .= '<tbody>';
    foreach ($batch_login_details as $key => $value)
    {
      $str1 .= '<tr>';
      $str1 .= '<td width="5%">' . $value['login_id'] . '</td>';
      $str1 .= '<td width="5%">' . base64_decode($value['password']) . '</td>';
      $str1 .= '</tr>';
    }
    $str1 .= '</tbody>';
    $str1 .= '</table>';

    echo $str . ':::::' . json_encode($batch) . ':::::' . $str1.':::::'.json_encode($inspe_batch);
  }

  public function export_to_pdf()
  {
    $batch_id = $_POST['batch_id'];

    $login_inspector = $this->session->userdata('dra_inspector');
    $inspector_id = $login_inspector['id'];

    $this->db->select('a.institute_name, b.id, b.agency_id, b.batch_code, b.batch_name, b.batch_from_date, b.batch_to_date, b.online_training_platform, b.timing_from, b.timing_to, b.hours, b.training_medium, b.total_candidates, f1.faculty_name as first_faculty_name, f2.faculty_name as sec_faculty_name, f3.faculty_name as add_first_faculty_name, f4.faculty_name as add_sec_faculty_name, b.contact_person_name, b.contact_person_phone, b.alt_contact_person_name, b.alt_contact_person_phone, b.platform_link, b.batch_online_offline_flag, b.training_schedule');
    $where = array('b.batch_status' => 'Approved', 'b.is_deleted' => 0, 'b.created_on LIKE' => '%2023%');
    $this->db->order_by('b.id', 'DESC');
    $this->db->join('dra_accerdited_master a', 'b.agency_id = a.dra_inst_registration_id', 'left');
    $this->db->join('faculty_master f1', 'b.first_faculty=f1.faculty_id', 'left');
    $this->db->join('faculty_master f2', 'b.sec_faculty=f2.faculty_id', 'left');
    $this->db->join('faculty_master f3', 'b.additional_first_faculty=f3.faculty_id', 'left');
    $this->db->join('faculty_master f4', 'b.additional_sec_faculty=f4.faculty_id', 'left');

    $where1 = array('b.batch_status' => 'Approved', 'b.is_deleted' => 0, 'b.id' => $batch_id);
    $batch = $this->master_model->getRecords('agency_batch b', $where1);

    $data['batch_data'] = $batch[0];

    $query1 = $this->db->query("SELECT * FROM dra_batch_inspection WHERE batch_id = " . $batch_id . " AND inspector_id =" . $inspector_id); //ACTUAL QUERY

    $data['batch_insp'] = $batch_insp = $query1->result_array();

    $agency = $this->db->query("SELECT agency_batch.agency_id, dra_accerdited_master.institute_code, institute_name
	        FROM agency_batch LEFT JOIN  dra_accerdited_master ON agency_batch.agency_id = dra_accerdited_master.dra_inst_registration_id
	        WHERE agency_batch.id=" . $batch_id);

    $data['agency_data'] = $agency_data = $agency->result_array();

    $candidateQry = $this->db->query("SELECT m.regid, m.training_id, concat(m.namesub, ' ', m.firstname, ' ', m.middlename, ' ', m.lastname) as name, m.dateofbirth, m.mobile_no, m.scannedphoto,
			SUM(case when c.attendance='Present' then 1 else 0 end) as present_cnt,
			SUM(case when c.attendance='Absent' then 1 else 0 end) as absent_cnt,
			GROUP_CONCAT(DISTINCT c.remark SEPARATOR '| ') remark
			FROM dra_members m LEFT JOIN dra_candidate_inspection c ON m.regid = c.candidate_id  
			WHERE m.batch_id = " . $batch_id . "
			AND inst_code = " . $agency_data[0]['institute_code'] . "
			GROUP BY m.regid
			ORDER BY m.regid DESC"); //ACTUAL QUERY

    $batch = $candidateQry->result_array();

    foreach ($batch as $key => $value)
    {
      $rmrk = explode('| ', $value['remark']);
      $remark = '';
      foreach ($rmrk as $key1 => $val1)
      {
        $j = $key1 + 1;
        if ($val1 != '')
        {
          $remark .= $j . ') ' . $val1 . '<br>';
        }
      }
      $batch[$key]['remark'] = $remark;
    }
    //print_r($batch);
    //echo $remark;die;
    $data['batch_candidates'] = $batch;

    $data['batch_login_details'] = $batch_login_details = $this->master_model->getRecords('agency_online_batch_user_details', array('batch_id' => $batch_id));

    /*$str1 = '';
		$str1.='<table border="solid 1%">';
        $str1.='<thead>';
        $str1.='<tr>';
        $str1.='<th width="5%">Login Id</th>';
        $str1.='<th width="5%">Password</th>';
        $str1.='</tr>';
        $str1.='</thead>';
        $str1.='<tbody>';
        foreach ($batch_login_details as $key => $value) {
        	$str1.='<tr>';
        	$str1.='<td width="5%">'.$value['login_id'].'</td>';
        	$str1.='<td width="5%">'.base64_decode($value['password']).'</td>';
        	$str1.='</tr>';
        }
        $str1.='</tbody>';
        $str1.='</table>';

        $data['str1'] = $str1;
*/
    $html = $this->load->view('iibfdra/Version_2/batch_inspection_report_pdf', $data, true);
    //echo $html; die;
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();
    $pdfFilePath = 'batch_inspection_report_pdf_' . $batch1[0]['batch_code'] . ".pdf";
    //generate the PDF from the given html
    $pdf->WriteHTML($html);
    //download it.
    $pdf->Output($pdfFilePath, "D");
  }

  public function editprofile()
  {

    if ($this->InstData)
    {
      $institutedata = $this->InstData;
      $instid = $institutedata['id'];

      $instRes = $this->master_model->getRecords('dra_accerdited_master', array('dra_inst_registration_id' => $instid));

      $instdata = array();
      if (count($instRes))
      {
        $instdata = $instRes[0];
      }
      if (isset($_POST['btnSubmit']))
      {
        $this->form_validation->set_rules('instname', 'Institute Name', 'trim|required');
        $this->form_validation->set_rules('addressline1', 'Address line1', 'trim|required');
        $this->form_validation->set_rules('addressline2', 'Address line 2', 'trim|required');
        $this->form_validation->set_rules('pincode', 'Pin Code', 'trim|required|min_length[6]|max_length[6]');
        $this->form_validation->set_rules('mobile', 'Mobile No', 'trim|required|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('contact_person', 'Contact Person', 'trim|required');
        $this->form_validation->set_rules('contactp_designation', 'Contact Person Designation', 'trim|required');

        if ($this->form_validation->run() == TRUE)
        {
          $update_data = array(
            'institute_name'    => $this->input->post('instname'),
            'address1'        => $this->input->post('addressline1'),
            'address2'        => $this->input->post('addressline2'),
            'address3'        => $this->input->post('addressline3'),
            'address4'        => $this->input->post('addressline4'),
            'pin_code'        => $this->input->post('pincode'),
            'mobile'        => $this->input->post('mobile'),
            'email'          => $this->input->post('email'),
            'coord_name'      => $this->input->post('contact_person'),
            'designation'      => $this->input->post('contactp_designation')
          );

          if ($this->master_model->updateRecord('dra_accerdited_master', $update_data, array('id' => $instid)))
          {
            $desc['updated_data'] = $update_data;
            $desc['old_data'] = $institutedata;
            log_dra_user($log_title = "DRA Institute Edit Profile Successful", $log_message = serialize($desc));
            $this->session->set_flashdata('success', 'Profile updated successfully');
            redirect(base_url() . 'iibfdra/Version_2/InstituteHome/editprofile');
          }
          else
          {
            $desc['updated_data'] = $update_data;
            $desc['old_data'] = $institutedata;
            log_dra_user($log_title = "DRA Institute Edit Profile Unsuccessful", $log_message = serialize($desc));
            $this->session->set_flashdata('error', 'Error occured while updating the profile');
            redirect(base_url() . 'iibfdra/Version_2/InstituteHome/editprofile');
          }
        }
      }
      else
      {
        $data['validation_errors'] = validation_errors();
      }
      /* send active exams for display in sidebar */
      $this->db->where('state_master.state_delete', '0');
      $states = $this->master_model->getRecords('state_master');

      $city_name = "";
      $city_id = $instdata['address6'];
      if (is_numeric($city_id))
      {
        $this->db->where('city_master.city_delete', '0');
        $this->db->where('city_master.id', $city_id);
        $city_name_sql = $this->master_model->getRecords('city_master');
        $city_name = strtoupper($city_name_sql[0]['city_name']);
      }
      else
      {
        $city_name = strtoupper($instdata['address6']);
      }

      $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
      $res = $this->master_model->getRecords("dra_exam_master a");

      $data = array(
        'middle_content' => 'institute_editprofile',  'states' => $states,
        'city_name' => $city_name, 'instdata' => $instdata, 'active_exams' => $res
      );

      $this->load->view('iibfdra/Version_2/common_view', $data);
    }
    else
    {
      redirect('iibfdra/Version_2/InstituteLogin');
    }
  }

  ##------------------------Transaction Details done by (PRAFULL)----------------##
  public function transaction()
  {
  }
  /* Change Password */
  public function changepass()
  {
    $data['error'] = '';
    if (isset($_POST['btn_password']))
    {
      $this->form_validation->set_rules('current_pass', 'Current Password', 'required|xss_clean');
      $this->form_validation->set_rules('txtnpwd', 'New Password', 'required|xss_clean');
      $this->form_validation->set_rules('txtrpwd', 'Re-type new password', 'required|xss_clean|matches[txtnpwd]');
      if ($this->form_validation->run())
      {
        $current_pass = $this->input->post('current_pass');
        $new_pass = $this->input->post('txtnpwd');
        if ($current_pass == $new_pass)
        {
          $this->session->set_flashdata('error', 'Current password and new password cannot be same.');
          redirect(base_url() . 'iibfdra/Version_2/InstituteHome/changepass/');
        }
        $instdata = $this->session->userdata('dra_institute');
        //print_r($instdata);
        $instcode = $instdata['institute_code'];

        $row = $this->master_model->getRecordCount('dra_accerdited_master', array('password' => md5(trim($current_pass)), 'institute_code' => $instcode));
        if ($row == 0)
        {
          $this->session->set_flashdata('error', 'Current Password is Wrong.');
          redirect(base_url() . 'iibfdra/Version_2/InstituteHome/changepass/');
        }
        else
        {
          $input_array = array('password' => md5(trim($new_pass)));
          $this->master_model->updateRecord('dra_accerdited_master', $input_array, array('institute_code' => $instcode, 'dra_inst_registration_id' => $instdata['dra_inst_registration_id']));

          //echo $this->db->last_query(); die;
          //$this->session->unset_userdata('password');
          //$this->session->set_userdata("password",base64_encode($new_pass));
          $instsessdata = $this->session->userdata('dra_institute');
          $instsessdata['password'] = md5(trim($new_pass));
          $this->session->set_userdata("dra_institute", $instsessdata);

          log_dra_user($log_title = "DRA Institute Password Changed Successful", $log_message = serialize($input_array));

          $this->session->set_flashdata('success', 'Password Changed successfully.');
          redirect(base_url() . 'iibfdra/Version_2/InstituteHome/changepass/');
        }
      }
      else
      {
        $data['validation_errors'] = validation_errors();
      }
    }
    /* send active exams for display in sidebar */
    $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
    $res = $this->master_model->getRecords("dra_exam_master a");
    $data = array('middle_content' => 'change_pass', 'active_exams' => $res);
    $this->load->view('iibfdra/Version_2/common_view', $data);
  }

  public function faculty_view($faculty_id)
  {

    $faculty_id = base64_decode($faculty_id);

    $qry = $this->db->query("SELECT faculty_id, institute_id, faculty_number, salutation, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration_month1, gross_duration_year1, work_exp2, emp_id2, gross_duration_year2, gross_duration_month2, gross_duration_year3, work_exp3, emp_id3, gross_duration_month3, gross_duration_year3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND faculty_id = " . $faculty_id);

    $data['faculty_data'] = $faculty_data = $qry->result_array();
    //print_r($faculty_data);

    $logqry = $this->db->query("SELECT faculty_id, action_taken, reason, created_on
            FROM faculty_status_logs
            WHERE faculty_id = " . $faculty_id . "
            ORDER BY created_on DESC"); //AND   action_taken != 'Add'

    $data['log_data'] = $records1 = $logqry->result_array();

    $data['request_from'] = 'Inspector';
    $data['action'] = 'view';
    $data['middle_content'] = 'faculty_view';
    $this->load->view('iibfdra/Version_2/common_view_inspector', $data);
  }

public function change_password()
  {
   
      if ($this->input->post('btnSubmit')) {
        // $id = $this->input->post('inspector_id');
        $login_inspector = $this->session->userdata('dra_inspector');
        $inspector_id = $login_inspector['id'];
         //print_r($inspector_id);
          // Form validation 
          $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]|max_length[10]');
          $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');
  
          if ($this->form_validation->run()) {
           // echo"hu";
              $new_password = $this->input->post('new_password');
              //print_r($new_password);
              // $confirm_password = $this->input->post('confirm_password');
              $hashed_password = md5($new_password);
             // print_r($confirm_password);
             
                $update_data = array(
                  'password' => $hashed_password,
                  'plain_password' => $new_password,
                  'added_by' => $this->UserID,
                  'updated_on' => date("Y-m-d H:i:s")
              );
              print_r($update_data);
             // $result = $this->Master_model->updateRecord('agency_inspector_master', $update_data, array('id' => $inspector_id));
            $this->master_model->updateRecord('agency_inspector_master', $update_data, array('id' => $inspector_id));
        
           
              log_dra_admin($log_title = "Change password Successfully updated", $log_message = serialize($update_data));
       
              $this->session->set_flashdata('success', 'Password updated successfully!');
              redirect(base_url().'iibfdra/Version_2/InspectorHome');
          } else {
              // Display validation errors
              $data['error_msg'] = validation_errors();
          }
      }
  
      $data['breadcrumb'] = '<ol class="breadcrumb"><li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
                  <li><a href="'.base_url().'iibfdra/Version_2/InspectorLogin/'.$this->router->fetch_class().'">Change Password</a></li>
              
          </ol>';            
  
      $data['middle_content'] = 'inspector_change_pass';
      $this->load->view('iibfdra/Version_2/common_view_inspector', $data);
  }
}
