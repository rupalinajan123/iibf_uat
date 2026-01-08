<?php
 /*Controller class Faculty Master.
  * @copyright    Copyright (c) 2018 ESDS Software Solution Private.
  * @author       Priyanka Wadnere
  * @package      Controller
  */
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Faculty_master extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('dra_admin')) {
            redirect('iibfdra/admin/Login');
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

    public function index(){
        
        $data['middle_content'] = 'faculty_list';
        $this->load->view('iibfdra/admin/faculty/faculty_list',$data);
    }

    public function faculty_list(){

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
        
        if($searchValue != ''){
           $searchQuery .= " AND (faculty_name like '%".$searchValue."%' or pan_no like '%".$searchValue."%' or base_location like '%".$searchValue."%' or dob like '%".$searchValue."%') ";
        }

        $select = $this->db->query("SELECT faculty_id, faculty_number, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration1, work_exp2, emp_id2, gross_duration2, work_exp3, emp_id3, gross_duration3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, institute_name, status 
            FROM faculty_master LEFT JOIN dra_accerdited_master ON institute_id = dra_inst_registration_id
            WHERE is_deleted = 0");

        ## Total number of records with filtering
        $records = $select->result_array();
        $total_records = count($records);


        ## Total number of records without filtering
        $select2     = "SELECT faculty_id, faculty_number, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration1, work_exp2, emp_id2, gross_duration2, work_exp3, emp_id3, gross_duration3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, institute_name, status 
            FROM faculty_master LEFT JOIN dra_accerdited_master ON institute_id = dra_inst_registration_id
            WHERE is_deleted = 0"
            .$searchQuery;

        $select3 = $this->db->query($select2);

        ## Total number of records with filtering
        $records = $select3->result_array();
        $totalRecordwithFilter = count($records);

        ## Fetch records
        $facultyQuery = $select2." ORDER BY ". $columnIndex."   ".$columnSortOrder."  LIMIT ".$row." ,".$rowperpage." ";

        $faculty_query = $this->db->query($facultyQuery);
        $faculty_list = $faculty_query->result_array();
        //echo $this->db->last_query();
        if($searchValue != ''){
           //echo $this->db->last_query();
        }
        
        $data = array();
        $sr = $_POST['start'];

        //print_r($admin_user_list); die;
        foreach ($faculty_list as $key => $faculty) {

            $sr++;

            //$alert = 'Do you really want to delete this record ?';
            $status_alert = "Do you really want to change the status of this record ?";
            $check_status = 'yes';
            $controller = 'faculty';
            $func = '';

            $active = 'Active';
            $inactive = 'Inactive';
            $status = '';

            $batch_qry = $this->db->query("SELECT batch_code, batch_name
            FROM agency_batch 
            WHERE is_deleted = 0
            AND   (first_faculty = ".$faculty['faculty_id']." OR sec_faculty = ".$faculty['faculty_id']." OR additional_first_faculty = ".$faculty['faculty_id']." OR additional_sec_faculty = ".$faculty['faculty_id'].")");

            $batch_data = $batch_qry->result_array();

            $str  = '';
            if(count($batch_data) > 0){
                foreach ($batch_data as $key => $value) {
                    $str.=$value['batch_code'].', ';
                }
            }

            if($faculty['status']=="Active"){ 
                //onclick="return change_status(this,event,\''.$status_alert.'\', \''.$inactive.'\')"
                $status='<span class="btn btn-success btn-sm w80"  data-href="'.base_url("faculty/active_inactive/".base64_encode($faculty['faculty_id'])).'">Active</span>';
            }
            else if($faculty['status']=="Inactive"){ 
                //onclick="return change_status(this,event,\''.$status_alert.'\', \''.$active.'\')"
                $status='<span class="btn btn-danger btn-sm w80"   data-href="'.base_url("faculty/active_inactive/".base64_encode($faculty['faculty_id'])).'">Inactive</span>';
            }
            else{
                //onclick="return change_status(this,event,\''.$status_alert.'\', \''.$active.'\')"
                $status='<span class="btn btn-warning btn-sm w80"   data-href="'.base_url("faculty/active_inactive/".base64_encode($faculty['faculty_id'])).'">In Review</span>';
            }

            $url  = ' <a class="btn-link" href="'.base_url("iibfdra/admin/faculty_master/faculty_view/".base64_encode($faculty['faculty_id'])).'" title="View details" ><i class="fa fa-eye"></i> </a>&nbsp;';

        
            $data[] = array(
                "sr"=>$sr, 
                "faculty_code"=>'F'.$faculty['faculty_number'],
                "faculty_name"=>$faculty['faculty_name'],
                "dob"=>$faculty['dob'],
                "base_location"=>$faculty['base_location'],
                "pan_no"=>$faculty['pan_no'],
                "current_batches"=>rtrim($str,', '),
                "agency"=>$faculty['institute_name'],
                "status"=>$status,
                "action"=>$url
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

    public function faculty_view($faculty_id){

        $faculty_id = base64_decode($faculty_id);

        $qry = $this->db->query("SELECT faculty_id, faculty_number, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration1, work_exp2, emp_id2, gross_duration2, work_exp3, emp_id3, gross_duration3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND faculty_id = ".$faculty_id);

        $data['faculty_data'] = $records = $qry->result_array();

        $logqry = $this->db->query("SELECT faculty_id, status, reason, created_on
            FROM faculty_status_logs
            WHERE faculty_id = ".$faculty_id."
            AND   status = 'Inactive'");

        $data['log_data'] = $records1 = $logqry->result_array();

        $data['action'] = 'view';
        $this->load->view('iibfdra/admin/faculty/faculty_view',$data);
    }

    public function change_status(){
        $status = $_POST['status'];
        $reason = $_POST['reason'];
        $faculty_id = $_POST['faculty_id'];

        $upd_arr = array(
                         'status' => $status,
                         'updated_by_id' => $this->UserData['id'],
                         'updated_on' => date('Y-m-d H:i:s')
                     );
        $where = array('faculty_id' => $faculty_id);

        $updated_id = $this->master_model->updateRecord('faculty_master',$upd_arr,$where);

        $ins_arr = array('faculty_id' => $faculty_id,
                         'status' => $status,
                         'reason' => $reason,
                         'created_by_id' => $this->UserData['id'],
                         'created_on' => date('Y-m-d H:i:s')
                     );


        $inserted_id = $this->master_model->insertRecord('faculty_status_logs',$ins_arr);
    
        echo $updated_id;
    }
}

?>
