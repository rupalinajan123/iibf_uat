<?php
/*Controller class Inspector Master.
  * @copyright    Copyright (c) 2018 ESDS Software Solution Private.
  * @author       Aayusha Kapadni 
  * @package      Controller
  * @updated      2019-06-24 by Manoj 
  */

defined('BASEPATH') or exit('No direct script access allowed');
class BatchSummary extends CI_Controller
{
  public $UserID;
  public $UserData;
  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('dra_admin')) {
      redirect('iibfdra/Version_2/admin/Login');
    }
    $this->UserData = $this->session->userdata('dra_admin');
    $this->UserID   = $this->UserData['id'];
    $this->load->model('UserModel');
    $this->load->model('Master_model');
    $this->load->helper('master_helper');
    $this->load->helper('pagination_helper');
    $this->load->library('pagination');
    $this->load->helper('general_helper');
  }

  public function index()
  {
    // $batchQry = $this->db->query("SELECT a.batch_code, ac.institute_code, ac.institute_name, a.batch_from_date, a.batch_to_date, a.total_candidates, a.batch_status, i.inspector_name
		// 	FROM agency_batch a 
		// 	LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id
		// 	LEFT JOIN agency_inspector_master i ON a.inspector_id = i.id");

    // $data['batch'] = $batch = $batchQry->result_array();

    $agencyQry = $this->db->query("SELECT a.id, a.batch_code, a.created_on, a.updated_on, a.contact_person_name, ac.institute_code, ac.institute_name, a.batch_from_date, a.batch_to_date, CONCAT_WS(', ', fm1.faculty_name, fm2.faculty_name, fm3.faculty_name, fm4.faculty_name) AS faculty_names, COUNT(dbi.id) AS inspection_count, GROUP_CONCAT(DISTINCT DATE_FORMAT(dbi.created_on, '%Y-%m-%d')) AS inspection_dates, 
    SUM(TIMESTAMPDIFF(MINUTE, dbi.created_on, dbi.inspection_start_time)) AS total_inspection_time_minutes, 
    AVG(TIMESTAMPDIFF(MINUTE, dbi.created_on, dbi.inspection_start_time)) AS average_inspection_time,
    GROUP_CONCAT(DISTINCT aim.inspector_name) AS inspected_by,

    COUNT(DISTINCT dm.regid) AS total_registered_candidate,
    COUNT(DISTINCT CASE WHEN dm.hold_release = 'Manual Hold' OR dm.hold_release = 'Auto Hold' THEN dm.regid END) AS total_hold_candidate,

    -- (SELECT COUNT(dm.regid) FROM dra_members dm WHERE dm.batch_id = a.id) AS total_registered_candidate,
    -- (SELECT COUNT(dm.regid) FROM dra_members dm WHERE dm.batch_id = a.id AND (dm.hold_release = 'Manual Hold' OR dm.hold_release = 'Auto Hold')) AS total_hold_candidate,

    (
        SUM(CASE WHEN dbi.teaching_quality_interaction_with_candidates = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_quality_softskill_session = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.candidates_attentiveness = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.DRA_attitude_behaviour = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_interaction_with_faculty = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_response_to_queries = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_effectiveness = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.curriculum_covered = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_delivery = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_coordination = 'Poor' THEN 1 ELSE 0 END)
    ) AS total_poor_counts,

    (
        SUM(CASE WHEN dbi.teaching_quality_interaction_with_candidates = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_quality_softskill_session = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.candidates_attentiveness = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.DRA_attitude_behaviour = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_interaction_with_faculty = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_response_to_queries = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_effectiveness = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.curriculum_covered = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_delivery = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_coordination = 'Good' THEN 1 ELSE 0 END)
    ) AS total_good_counts,

    (
        SUM(CASE WHEN dbi.teaching_quality_interaction_with_candidates = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_quality_softskill_session = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.candidates_attentiveness = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.DRA_attitude_behaviour = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_interaction_with_faculty = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_response_to_queries = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_effectiveness = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.curriculum_covered = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_delivery = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_coordination = 'Excellent' THEN 1 ELSE 0 END)
    ) AS total_excellent_counts,

    (
        SUM(CASE WHEN dbi.teaching_quality_interaction_with_candidates = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_quality_softskill_session = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.candidates_attentiveness = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.DRA_attitude_behaviour = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_interaction_with_faculty = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_response_to_queries = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_effectiveness = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.curriculum_covered = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_delivery = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_coordination = 'Average' THEN 1 ELSE 0 END)
    ) AS total_average_counts,

    a.total_candidates, a.batch_status
            FROM agency_batch a 
            LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
            LEFT JOIN agency_inspector_master i ON a.inspector_id = i.id
            JOIN faculty_master fm1 ON a.first_faculty = fm1.faculty_id
            JOIN faculty_master fm2 ON a.sec_faculty = fm2.faculty_id
            JOIN faculty_master fm3 ON a.additional_first_faculty = fm3.faculty_id
            JOIN faculty_master fm4 ON a.additional_sec_faculty = fm4.faculty_id
            LEFT JOIN dra_batch_inspection dbi ON a.id = dbi.batch_id
            LEFT JOIN agency_inspector_master aim ON dbi.inspector_id = aim.id
            LEFT JOIN dra_members dm ON a.id = dm.batch_id
            WHERE a.is_deleted = 0 " . $searchQuery . "GROUP BY a.id ORDER BY a.batch_from_date DESC");


    $data['agency'] = $agency = $agencyQry->result_array();

    
    //   FROM agency_inspector_master 
    //   WHERE is_active = '1' AND is_delete = '0'
    //   ");

    // $data['inspectors'] = $inspectors = $insQry->result_array();

    // echo $this->db->last_query();
    // echo "<pre>"; print_r($data); exit;
    $data['agency'] = $this->Master_model->getRecords('dra_accerdited_master');
  
    $data['inspectors']= $this->Master_model->getRecords('agency_inspector_master');
    $this->load->view('iibfdra/Version_2/admin/batch_summary/batch_summary', $data);
  }

  public function get_batch_mis()
  {
    $draw = @$_POST['draw'];
    $row = @$_POST['start'];
    $rowperpage = @$_POST['length']; // Rows display per page
    $columnIndex = @$_POST['order'][0]['column']; // Column index
    $columnName = @$_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = @$_POST['order'][0]['dir']; // asc or desc
    $searchValue = @$_POST['search']['value']; // Search VALUES
    
    ## Search 
    $searchQuery = " ";
    $whr = '';

    ## Custom Field value
    $from_date   = @$_POST['from_date'];
    $to_date = @$_POST['to_date'];

    $agencyvalue = isset($_POST['agencyvalue']) ? $_POST['agencyvalue'] : '';
    $inspectorvalue = isset($_POST['inspectorvalue']) ? $_POST['inspectorvalue'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if ($from_date != '' && $to_date != '' && $agencyvalue != '' && $inspectorvalue != '' && $status != '') {
      $searchQuery .= " AND ((a.batch_from_date >= '" . $from_date . "' AND a.batch_from_date <= '" . $to_date . "') OR (a.batch_to_date >= '" . $from_date . "' AND a.batch_to_date <= '" . $to_date . "')) AND (ac.id = ".$agencyvalue." AND a.inspector_id = ".$inspectorvalue." AND a.batch_status = '".$status."' ) ";
    }
    else if ($from_date != '' && $to_date != '' && $agencyvalue != '' && $inspectorvalue != '') {
      $searchQuery .= " AND ((a.batch_from_date >= '" . $from_date . "' AND a.batch_from_date <= '" . $to_date . "') OR (a.batch_to_date >= '" . $from_date . "' AND a.batch_to_date <= '" . $to_date . "')) AND (ac.id = ".$agencyvalue." AND a.inspector_id = ".$inspectorvalue." ) ";
    }
    else if ($from_date != '' && $to_date != '' && $agencyvalue != '') {
      $searchQuery .= " AND ((a.batch_from_date >= '" . $from_date . "' AND a.batch_from_date <= '" . $to_date . "') OR (a.batch_to_date >= '" . $from_date . "' AND a.batch_to_date <= '" . $to_date . "')) AND (ac.id = ".$agencyvalue.") ";
    }
    else if ($from_date != '' && $to_date != '' && $inspectorvalue != '') {
      $searchQuery .= " AND ((a.batch_from_date >= '" . $from_date . "' AND a.batch_from_date <= '" . $to_date . "') OR (a.batch_to_date >= '" . $from_date . "' AND a.batch_to_date <= '" . $to_date . "')) AND (ac.id = ".$agencyvalue." AND a.inspector_id = ".$inspectorvalue." ) ";
    }
    else if ($from_date != '' && $to_date != '' && $status != '') {
      $searchQuery .= " AND ((a.batch_from_date >= '" . $from_date . "' AND a.batch_from_date <= '" . $to_date . "') OR (a.batch_to_date >= '" . $from_date . "' AND a.batch_to_date <= '" . $to_date . "')) AND (a.batch_status = '".$status."' ) ";
    } 
    else if ($agencyvalue != '' && $inspectorvalue != '' && $status != '') {
      $searchQuery .= " AND (ac.id = ".$agencyvalue." AND a.inspector_id = ".$inspectorvalue." AND a.batch_status = '".$status."' ) ";
    }
    else if ($agencyvalue != '' && $inspectorvalue != '') {
      $searchQuery .= " AND (ac.id = ".$agencyvalue." AND a.inspector_id = ".$inspectorvalue.") ";
    }
    else if ($agencyvalue != '' && $status != '') {
      $searchQuery .= " AND (ac.id = ".$agencyvalue." AND a.batch_status = '".$status."' ) ";
    }
    else if ($inspectorvalue != '' && $status != '') {
      $searchQuery .= " AND (a.inspector_id = ".$inspectorvalue." AND a.batch_status = '".$status."' ) ";
    }
    else if ($from_date != '' && $to_date != '') {
      $searchQuery .= " AND ((a.batch_from_date >= '" . $from_date . "' AND a.batch_from_date <= '" . $to_date . "') OR (a.batch_to_date >= '" . $from_date . "' AND a.batch_to_date <= '" . $to_date . "'))";
    }
    else if ($from_date != '') {
      $searchQuery .= " AND (a.batch_from_date >= '" . $from_date . "' OR a.batch_to_date >= '" . $from_date . "')";
    }
    else if ($to_date != '') {
      $searchQuery .= " AND (a.batch_from_date <= '" . $to_date . "' OR a.batch_to_date <= '" . $to_date . "')";
    } 
    else if ($agencyvalue != '') {
      $searchQuery .= " AND (ac.id = ".$agencyvalue." ) ";
    }
    else if ($inspectorvalue != '') {
      $searchQuery .= " AND (a.inspector_id = ".$inspectorvalue.") ";
    }
    else if ($status != '') {
      $searchQuery .= " AND (a.batch_status = '".$status."' ) ";
    }   

    if ($searchValue != '') {
      $searchValue = str_replace("'",'"',$searchValue);
      $searchQuery .= " AND (a.batch_code like '%" . $searchValue . "%' or ac.institute_name like '%" . $searchValue . "%' or a.batch_from_date like '%" . $searchValue . "%' or a.batch_to_date like '%" . $searchValue . "%' or a.total_candidates like '%" . $searchValue . "%' or a.batch_status like '%" . $searchValue . "%' or a.contact_person_name like '%" . $searchValue . "%' or i.inspector_name like '%" . $searchValue . "%') ";
    }

    /*$select = $this->db->query("SELECT a.id, a.batch_code, ac.institute_code, ac.institute_name, a.batch_from_date, a.batch_to_date, a.total_candidates, a.batch_status, i.inspector_name 
            FROM agency_batch a 
            LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
            LEFT JOIN agency_inspector_master i ON a.inspector_id = i.id
            WHERE a.is_deleted = 0 " . $searchQuery);*/

    ## Total number of records with filtering
    // $records = $select->result_array();
    // $total_records = count($records);

    // COUNT(dm.regid) AS total_registered_candidate
    
    ## Total number of records without filtering
    $select2 = "SELECT a.id, a.batch_code, a.created_on, a.updated_on, a.contact_person_name, ac.institute_code, ac.institute_name, a.batch_from_date, a.batch_to_date, CONCAT_WS(', ', fm1.faculty_name, fm2.faculty_name, fm3.faculty_name, fm4.faculty_name) AS faculty_names, COUNT(dbi.id) AS inspection_count, GROUP_CONCAT(DISTINCT DATE_FORMAT(dbi.created_on, '%Y-%m-%d')) AS inspection_dates, 
    
    IF(dbi.inspection_start_time != '0000-00-00 00:00:00', SUM(TIMESTAMPDIFF(MINUTE, dbi.inspection_start_time, dbi.created_on)), '0') AS total_inspection_time_minutes,
    IF(dbi.inspection_start_time != '0000-00-00 00:00:00', AVG(TIMESTAMPDIFF(MINUTE, dbi.inspection_start_time, dbi.created_on)), '0') AS average_inspection_time,

    GROUP_CONCAT(DISTINCT aim.inspector_name) AS inspected_by,

    COUNT(DISTINCT dm.regid) AS total_registered_candidate,
    COUNT(DISTINCT CASE WHEN dm.hold_release = 'Manual Hold' OR dm.hold_release = 'Auto Hold' THEN dm.regid END) AS total_hold_candidate,

    -- (SELECT COUNT(dm.regid) FROM dra_members dm WHERE dm.batch_id = a.id) AS total_registered_candidate,
    -- (SELECT COUNT(dm.regid) FROM dra_members dm WHERE dm.batch_id = a.id AND (dm.hold_release = 'Manual Hold' OR dm.hold_release = 'Auto Hold')) AS total_hold_candidate,

    (
        SUM(CASE WHEN dbi.teaching_quality_interaction_with_candidates = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_quality_softskill_session = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.candidates_attentiveness = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.DRA_attitude_behaviour = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_interaction_with_faculty = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_response_to_queries = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_effectiveness = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.curriculum_covered = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_delivery = 'Poor' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_coordination = 'Poor' THEN 1 ELSE 0 END)
    ) AS total_poor_counts,

    (
        SUM(CASE WHEN dbi.teaching_quality_interaction_with_candidates = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_quality_softskill_session = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.candidates_attentiveness = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.DRA_attitude_behaviour = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_interaction_with_faculty = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_response_to_queries = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_effectiveness = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.curriculum_covered = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_delivery = 'Good' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_coordination = 'Good' THEN 1 ELSE 0 END)
    ) AS total_good_counts,

    (
        SUM(CASE WHEN dbi.teaching_quality_interaction_with_candidates = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_quality_softskill_session = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.candidates_attentiveness = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.DRA_attitude_behaviour = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_interaction_with_faculty = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_response_to_queries = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_effectiveness = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.curriculum_covered = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_delivery = 'Excellent' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_coordination = 'Excellent' THEN 1 ELSE 0 END)
    ) AS total_excellent_counts,

    (
        SUM(CASE WHEN dbi.teaching_quality_interaction_with_candidates = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_quality_softskill_session = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.candidates_attentiveness = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.DRA_attitude_behaviour = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_interaction_with_faculty = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.learning_quality_response_to_queries = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.teaching_effectiveness = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.curriculum_covered = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_delivery = 'Average' THEN 1 ELSE 0 END) +
        SUM(CASE WHEN dbi.overall_compliance_training_coordination = 'Average' THEN 1 ELSE 0 END)
    ) AS total_average_counts,

    a.total_candidates, a.batch_status
            FROM agency_batch a 
            LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
            LEFT JOIN agency_inspector_master i ON a.inspector_id = i.id
            JOIN faculty_master fm1 ON a.first_faculty = fm1.faculty_id
            JOIN faculty_master fm2 ON a.sec_faculty = fm2.faculty_id
            JOIN faculty_master fm3 ON a.additional_first_faculty = fm3.faculty_id
            JOIN faculty_master fm4 ON a.additional_sec_faculty = fm4.faculty_id
            LEFT JOIN dra_batch_inspection dbi ON a.id = dbi.batch_id
            LEFT JOIN agency_inspector_master aim ON dbi.inspector_id = aim.id
            LEFT JOIN dra_members dm ON a.id = dm.batch_id
            WHERE a.is_deleted = 0 " . $searchQuery." ".
            "GROUP BY a.id";

    $select3 = $this->db->query($select2);
    $agency_list = $select3->result_array();
    $totalRecordwithFilter = count($agency_list);
    
    if (isset($_POST['export_table'])) {
      $agencyQuery = $select2 . " ORDER BY a.batch_from_date DESC";
    } else {
      //$Query = $select2." limit ".$row.",".$rowperpage;
      ## Fetch records
      $agencyQuery = $select2 . " ORDER BY " . $columnIndex . "   " . $columnSortOrder . "  LIMIT " . $row . " ," . $rowperpage . " ";
    }

    $agency_query = $this->db->query($agencyQuery);
    $agency_list  = $select3->result_array();
    // $totalRecordwithFilter = count($agency_list);
    // echo "<pre>"; print_r($agency_list); exit;
    // echo $this->db->last_query();die();

    $data = array();

    $csv = '<style>
                table, th, td {
                  border: 1px solid black;
                  border-collapse: collapse;
                }
            </style>';

    if (isset($_POST['export_table'])) {
   // print_r($_POST['export_table']);
      $csv .= '<table class="table">  
                    <tr>  
                        <th>Sr.</th>  
                        <th>Batch Code</th>  
                        <th>Agency Code</th>
                        <th>Agency Name</th>             
                        <th>Batch Submit Date and Time</th>
                        <th>Batch Approve Date and Time</th>  
                        <th>Batch From Date</th>  
                        <th>Batch To Date</th>
                        <th>Batch Co-ordinator</th>
                        <th>Faculties</th>
                        <th>Total Inspections</th>
                        <th>Dates of Inspection</th>
                        <th>Total Time Spend On Inspection(Min)</th>  
                        <th>Average Inspection Time Per Visit(Min)</th>
                        <th>Inspected By</th>
                        <th>Total Candidates</th>  
                        <th>Total Candidates Registered</th>
                        <th>Total Candidates Hold</th>
                        <th>Total Candidates Eligible For Exam</th>  
                        <th>Batch Status</th>  
                        <th>Assessment/Rating(Poor)</th>
                        <th>Assessment/Rating(Average)</th>
                        <th>Assessment/Rating(Good)</th>
                        <th>Assessment/Rating(Excellent)</th>
                    </tr>';
    }

    $sr = $_POST['start'];

    //echo "<pre>"; print_r($agency_list); exit;
    // echo $this->db->last_query();die();
    foreach ($agency_list as $key => $agency) {
      //$sr = $key + 1;
      $sr++;
      $status = '';
      if ($agency['batch_status'] == "In Review") {

        $status = '<span class="statusi">In Review</span>';
      }
      if ($agency['batch_status'] == "Final Review") {

        $status = '<span class="statusf">Final Review</span>';
      }
      if ($agency['batch_status'] == "Re-Submitted") {

        $status = '<span class="statusrs">Re-Submitted</span>';
      }
      if ($agency['batch_status'] == "Batch Error") {

        $status = '<span class="statusbe">Batch Error</span>';
      }
      if ($agency['batch_status'] == "Approved") {

        $status = '<span class="statusa">Go Ahead</span>';
      }
      if ($agency['batch_status'] == "Rejected") {

        $status = '<span class="statusr">Rejected</span>';
      }
      if ($agency['batch_status'] == "Hold") {

        $status = '<span class="statush">Hold</span>';
      }
      if ($agency['batch_status'] == "UnHold") {
        $status = '<span class="statuuh">UnHold</span>';
      }
      if ($agency['batch_status'] == "Cancelled") {
        $status = '<span class="statusc">Cancelled</span>';
      }

      if (isset($_POST['export_table'])) {
       //echo "<pre>"; print_r($agency); exit;
//        echo '<tr>  
//         <td>' . $sr . '</td> 
//         <td>' . $agency['batch_code'] . '</td>
//         <td>' . $agency['institute_code'] . '</td>  
//         <td>' . $agency['institute_name'] . '</td>
//         <td>' . ($agency['created_on'] != '' ? date('Y-m-d H:i:s', strtotime($agency['created_on'])) : '') . '</td>
//         <td>' . ($agency['updated_on'] != '' ? date('Y-m-d H:i:s', strtotime($agency['updated_on'])) : '') . '</td>  
//         <td>' . $agency['batch_from_date'] . '</td>  
//         <td>' . $agency['batch_to_date'] . '</td>
//         <td>' . $agency['contact_person_name'] . '</td>
//         <td>' . $agency['faculty_names'] . '</td>
//         <td>' . $agency['inspection_count'] . '</td>
//         <td>' . $agency['inspection_dates'] . '</td>
//         <td>' . $agency['total_inspection_time_minutes'] . '</td>
//         <td>' . $agency['average_inspection_time'] . '</td>
//         <td>' . $agency['inspected_by'] . '</td>
//         <td>' . $agency['total_candidates'] . '</td>  
//         <td>' . $agency['total_registered_candidate'] . '</td>
//         <td>' . $agency['total_hold_candidate'] . '</td>
//         <td>' . ($agency['total_registered_candidate'] - $agency['total_hold_candidate']) . '</td>  
//         <td>' . $agency['batch_status'] . '</td>  
//         <td>' . $agency['total_poor_counts'] . '</td>
//         <td>' . $agency['total_average_counts'] . '</td>
//         <td>' . $agency['total_good_counts'] . '</td>
//         <td>' . $agency['total_excellent_counts'] . '</td>  
//       </tr>';
// exit;
        $csv .= '<tr>  
        <td>' . $sr . '</td> 
        <td>' . $agency['batch_code'] . '</td>
        <td>' . $agency['institute_code'] . '</td>  
        <td>' . $agency['institute_name'] . '</td>
        <td>' . ($agency['created_on'] != '' ? date('Y-m-d H:i:s', strtotime($agency['created_on'])) : '') . '</td>
        <td>' . ($agency['updated_on'] != '' ? date('Y-m-d H:i:s', strtotime($agency['updated_on'])) : '') . '</td>  
        <td>' . $agency['batch_from_date'] . '</td>  
        <td>' . $agency['batch_to_date'] . '</td>
        <td>' . $agency['contact_person_name'] . '</td>
        <td>' . $agency['faculty_names'] . '</td>
        <td>' . $agency['inspection_count'] . '</td>
        <td>' . $agency['inspection_dates'] . '</td>
        <td>' . $agency['total_inspection_time_minutes'] . '</td>
        <td>' . $agency['average_inspection_time'] . '</td>
        <td>' . $agency['inspected_by'] . '</td>
        <td>' . $agency['total_candidates'] . '</td>  
        <td>' . $agency['total_registered_candidate'] . '</td>
        <td>' . $agency['total_hold_candidate'] . '</td>
        <td>' . ($agency['total_registered_candidate'] - $agency['total_hold_candidate']) . '</td>  
        <td>' . $agency['batch_status'] . '</td>  
        <td>' . $agency['total_poor_counts'] . '</td>
        <td>' . $agency['total_average_counts'] . '</td>
        <td>' . $agency['total_good_counts'] . '</td>
        <td>' . $agency['total_excellent_counts'] .'</td>  
      </tr>';
                    
        $filename = 'Batch-Summary-' . date('Y-m-d') . '.xls';
      } else {

        $data[] = array(
          "sr" => $sr,
          "batch_code" => $agency['batch_code'],
          "institute_code" => $agency['institute_code'],
          "institute_name" => $agency['institute_name'],
          "submit_date" => $agency['created_on']!='' ? date('Y-m-d H:i:s',strtotime($agency['created_on'])):'',
          "approve_date" => $agency['updated_on']!='' ? date('Y-m-d H:i:s',strtotime($agency['updated_on'])):'',
          "batch_from_date" => $agency['batch_from_date'],
          "batch_to_date" => $agency['batch_to_date'],
          "contact_person_name" => $agency['contact_person_name'],
          "faculty_names" => $agency['faculty_names'],
          "inspection_count" => $agency['inspection_count'],
          "inspection_dates" => $agency['inspection_dates'],
          "total_inspection_time_minutes" => $agency['total_inspection_time_minutes'],
          "average_inspection_time" => $agency['average_inspection_time'],
          "inspected_by" => $agency['inspected_by'],
          "total_candidates" => $agency['total_candidates'],
          "total_registered_candidate" => $agency['total_registered_candidate'],
          "total_hold_candidate" => $agency['total_hold_candidate'],
          "total_eligible_candidate" => $agency['total_registered_candidate']-$agency['total_hold_candidate'],
          "batch_status" => $agency['batch_status'],
          "total_poor_counts" => $agency['total_poor_counts'],
          "total_average_counts" => $agency['total_average_counts'],
          "total_good_counts" => $agency['total_good_counts'],
          "total_excellent_counts" => $agency['total_excellent_counts']
        );
          //echo $data; exit;
      }
    }
    // echo $csv; exit;
    if (isset($_POST['export_table'])) {
      //$csv.= '</table>';
      header("Content-Disposition: attachment; filename=\"$filename\"");
      header("Content-Type: application/vnd.ms-excel");
      $csv_handler = fopen('php://output', 'w');
      fwrite($csv_handler, $csv);
      fclose($csv_handler);
    } else {
      $output = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecordwithFilter,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
      );
      echo json_encode($output);
    }
  }

  // public function export_to_excel()
  // {
  //   $batch_id = $_POST['batch_id'];

  //   $batchQry = $this->db->query("SELECT a.id, a.batch_code, ac.institute_code, ac.institute_name, a.batch_from_date, a.batch_to_date, a.total_candidates, a.batch_status, i.inspector_name
  //           FROM agency_batch a 
  //           LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
  //           LEFT JOIN agency_inspector_master i ON a.inspector_id = i.id 
  //           WHERE a.is_deleted = 0" . $searchQuery);

  //   $data['batch'] = $batch = $batchQry->result_array();
  // }
}
