<?php
/*Controller class Inspector Master.
  * @copyright    Copyright (c) 2018 ESDS Software Solution Private.
  * @author       Aayusha Kapadni 
  * @package      Controller
  * @updated      2019-06-24 by Manoj 
  */

defined('BASEPATH') or exit('No direct script access allowed');
class BatchMIS extends CI_Controller
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
    $batchQry = $this->db->query("SELECT a.batch_code, ac.institute_code, ac.institute_name, a.batch_from_date, a.batch_to_date, a.total_candidates, a.batch_status, i.inspector_name
			FROM agency_batch a 
			LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id
			LEFT JOIN agency_inspector_master i ON a.inspector_id = i.id");

    $data['batch'] = $batch = $batchQry->result_array();

    $this->load->view('iibfdra/Version_2/admin/batch/batchMIS', $data);
  }

  public function get_batch_mis()
  {
    //print_r($_POST); die();
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
    $whr = '';

    ## Custom Field value
    $from_date   = @$_POST['from_date'];
    $to_date = @$_POST['to_date'];

    /*if ($from_date != '') {
      $searchQuery .= " AND a.batch_from_date >= '" . $from_date . "'";
    }
    if ($to_date != '') {
      $searchQuery .= " AND a.batch_to_date <= '" . $to_date . "'";
    }*/

    if ($from_date != '' && $to_date != '') 
    {
      $searchQuery .= " AND ((a.batch_from_date >= '" . $from_date . "' AND a.batch_from_date <= '" . $to_date . "') OR (a.batch_to_date >= '" . $from_date . "' AND a.batch_to_date <= '" . $to_date . "'))";
    }
    else
    {
      if ($from_date != '') 
      {
        $searchQuery .= " AND (a.batch_from_date >= '" . $from_date . "' OR a.batch_to_date >= '" . $from_date . "')";
      }

      if ($to_date != '') 
      {
        $searchQuery .= " AND (a.batch_from_date <= '" . $to_date . "' OR a.batch_to_date <= '" . $to_date . "')";
      }
    }

    if ($searchValue != '') {
      $searchValue = str_replace("'", '"', $searchValue);
      $searchQuery .= " AND (a.batch_code like '%" . $searchValue . "%' or ac.institute_name like '%" . $searchValue . "%' or a.batch_from_date like '%" . $searchValue . "%' or a.batch_to_date like '%" . $searchValue . "%' or a.total_candidates like '%" . $searchValue . "%' or a.batch_status like '%" . $searchValue . "%' or i.inspector_name like '%" . $searchValue . "%') ";
    }

    $DRA_Version_2_instId = $this->config->item('DRA_Version_2_instId');
    $DRA_Version_2_instIdStr = implode(',', $DRA_Version_2_instId);

    $select = $this->db->query("SELECT a.id, a.batch_code, ac.institute_code, ac.institute_name, a.batch_from_date, a.batch_to_date, a.total_candidates, a.batch_status, i.inspector_name 
            FROM agency_batch a 
            LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
            LEFT JOIN agency_inspector_master i ON a.inspector_id = i.id
            WHERE a.is_deleted = 0
            AND   ac.dra_inst_registration_id IN (" . $DRA_Version_2_instIdStr . ")" . $searchQuery);

    ## Total number of records with filtering
    $records = $select->result_array();
    $total_records = count($records);

    ## Total number of records without filtering
    $select2 = "SELECT a.id, a.batch_code, ac.institute_code, ac.institute_name, a.batch_from_date, a.batch_to_date, a.total_candidates, a.batch_status, i.inspector_name
            FROM agency_batch a 
            LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
            LEFT JOIN agency_inspector_master i ON a.inspector_id = i.id 
            WHERE a.is_deleted = 0
            AND   ac.dra_inst_registration_id IN (" . $DRA_Version_2_instIdStr . ")"
      . $searchQuery;

    $select3 = $this->db->query($select2);

    if (isset($_POST['export_table'])) {
      $agencyQuery = $select2;
    } else {
      //$Query = $select2." limit ".$row.",".$rowperpage;
      ## Fetch records
      $agencyQuery = $select2 . " ORDER BY " . $columnIndex . "   " . $columnSortOrder . "  LIMIT " . $row . " ," . $rowperpage . " ";
    }


    $agency_query = $this->db->query($agencyQuery);
    $agency_list = $agency_query->result_array();
    $totalRecordwithFilter = count($records);
    //echo $this->db->last_query();die();

    $data = array();

    $csv = '<style>
                table, th, td {
                  border: 1px solid black;
                  border-collapse: collapse;
                }
            </style>';

    if (isset($_POST['export_table'])) {

      $csv .= '<table class="table">  
                    <tr>  
                        <th>Sr.</th>  
                        <th>Batch Code</th>  
                        <th>Institute Name</th>  
                        <th>Batch From Date</th>  
                        <th>Batch To Date</th>  
                        <th>Total Candidates</th>  
                        <th>Status</th>  
                        <th>Inspector Name</th>  
                    </tr>';
    }

    $sr = $_POST['start'];

    //print_r($agency_list); die;
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

        $csv .= '<tr>  
                            <td>' . $sr . '</td> 
                            <td>' . $agency['batch_code'] . '</td>  
                            <td>' . $agency['institute_name'] . '</td>  
                            <td>' . $agency['batch_from_date'] . '</td>  
                            <td>' . $agency['batch_to_date'] . '</td>  
                            <td>' . $agency['total_candidates'] . '</td>  
                            <td>' . $status . '</td>  
                            <td>' . $agency['inspector_name'] . '</td>  
                        </tr>';

        $filename = 'BatchMIS_' . date('Y-m-d') . '.xls';
      } else {
        $data[] = array(
          "sr" => $sr,
          "batch_id" => $agency['id'],
          "batch_code" => $agency['batch_code'],
          "institute_name" => $agency['institute_name'],
          "batch_from_date" => $agency['batch_from_date'],
          "batch_to_date" => $agency['batch_to_date'],
          "total_candidates" => $agency['total_candidates'],
          "status" => $status,
          "inspector_name" => $agency['inspector_name']
        );
      }
    }

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
        "iTotalRecords" => $total_records,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
      );

      echo json_encode($output);
    }
  }

  public function export_to_excel()
  {
    $batch_id = $_POST['batch_id'];

    $batchQry = $this->db->query("SELECT a.id, a.batch_code, ac.institute_code, ac.institute_name, a.batch_from_date, a.batch_to_date, a.total_candidates, a.batch_status, i.inspector_name
            FROM agency_batch a 
            LEFT JOIN dra_accerdited_master ac ON a.agency_id = ac.dra_inst_registration_id 
            LEFT JOIN agency_inspector_master i ON a.inspector_id = i.id 
            WHERE a.is_deleted = 0" . $searchQuery);

    $data['batch'] = $batch = $batchQry->result_array();
  }
}
