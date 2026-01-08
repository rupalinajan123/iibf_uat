<?php

defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Faculty extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('dra_institute')) {
      redirect('iibfdra/Version_2/InstituteLogin');
    }
    $this->load->library('upload');
    $this->load->library('session');
    $this->load->helper('upload_helper');
    $this->load->helper('master_helper');
    $this->load->model('UserModel');
    $this->load->model('master_model');
    $this->load->helper('pagination_helper');
    $this->load->library('pagination');
    $this->load->model('log_model');
    $this->load->helper('general_helper');
    $this->load->helper('dra_seatallocation_helper');
    $this->module_title = 'Faculty';

    $login_agency = $this->session->userdata('dra_institute');
    $this->agency_id = $login_agency['dra_inst_registration_id'];
  }

  public function index()
  {

    $data['middle_content'] = 'faculty_list';
    $this->load->view('iibfdra/Version_2/common_view', $data);
  }

  public function faculty_list()
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

    if ($searchValue != '') {
      $searchValue = str_replace("'", '"', $searchValue);
      $faculty_id_search = str_replace("F", "", $searchValue);
      $searchQuery .= " AND (faculty_number like '%" . $faculty_id_search . "%' or faculty_name like '%" . $searchValue . "%' or pan_no like '%" . $searchValue . "%' or base_location like '%" . $searchValue . "%' or dob like '%" . $searchValue . "%' or status like '%" . $searchValue . "%') ";
    }

    $DRA_Version_2_instId = $this->config->item('DRA_Version_2_instId');
    $DRA_Version_2_instIdStr = implode(',', $DRA_Version_2_instId);

    $select = $this->db->query("SELECT faculty_id, faculty_number, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration_month1, gross_duration_year1, work_exp2, emp_id2, gross_duration_month2, gross_duration_year3, work_exp3, emp_id3, gross_duration_month3, gross_duration_year3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND   institute_id =" . $this->agency_id . " 
            AND   institute_id IN (" . $DRA_Version_2_instIdStr . ")");

    ## Total number of records with filtering
    $records = $select->result_array();
    $total_records = count($records);


    ## Total number of records without filtering
    $select2     = "SELECT faculty_id, faculty_number, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration_month1, gross_duration_year1, work_exp2, emp_id2, gross_duration_month2, gross_duration_year3, work_exp3, emp_id3, gross_duration_month3, gross_duration_year3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND   institute_id =" . $this->agency_id . "
            AND   institute_id IN (" . $DRA_Version_2_instIdStr . ")".$searchQuery;

    $select3 = $this->db->query($select2);

    ## Total number of records with filtering
    $records = $select3->result_array();
    $totalRecordwithFilter = count($records);

    ## Fetch records
    $facultyQuery = $select2 . " ORDER BY " . $columnIndex . "   " . $columnSortOrder . "  LIMIT " . $row . " ," . $rowperpage . " ";

    $faculty_query = $this->db->query($facultyQuery);
    $faculty_list = $faculty_query->result_array();
    //echo $this->db->last_query();
    if ($searchValue != '') {
      //echo $this->db->last_query();
    }

    $data = array();

    //print_r($admin_user_list); die;
    $sr = $_POST['start'];

    if (count($faculty_list) > 0) {
      foreach ($faculty_list as $key => $faculty) {

        $sr++;

        $alert = 'Do you really want to delete this record ?';
        $status_alert = "Do you really want to change the status of this record ?";
        $check_status = 'yes';
        $controller = 'faculty';
        $func = 'check_faculty_status';

        $active = 'Active';
        $inactive = 'Inactive';
        $status = '';

        $batch_qry = $this->db->query("SELECT batch_code, batch_name
                FROM agency_batch 
                WHERE is_deleted = 0
                AND   (first_faculty = " . $faculty['faculty_id'] . " OR sec_faculty = " . $faculty['faculty_id'] . " OR additional_first_faculty = " . $faculty['faculty_id'] . " OR additional_sec_faculty = " . $faculty['faculty_id'] . ")");

        $batch_data = $batch_qry->result_array();

        $str  = '';
        if (count($batch_data) > 0) {
          foreach ($batch_data as $key => $value) {
            $str .= $value['batch_code'] . ', ';
          }
        }

        if ($faculty['status'] == "Active") {
          //onclick="return change_status(this,event,\''.$status_alert.'\', \''.$inactive.'\')"
          $status = '<span class="label label-success w80 custom_disp_label" data-href="' . base_url("faculty/active_inactive/" . base64_encode($faculty['faculty_id'])) . '">Active</span>';
        } else if ($faculty['status'] == "Inactive") {
          //onclick="return change_status(this,event,\''.$status_alert.'\', \''.$active.'\')"
          $status = '<span class="label label-danger w80 custom_disp_label"  data-href="' . base_url("faculty/active_inactive/" . base64_encode($faculty['faculty_id'])) . '">Inactive</span>';
        } else if ($faculty['status'] == "Re-Submitted") {
          //onclick="return change_status(this,event,\''.$status_alert.'\', \''.$active.'\')"
          $status = '<span class="label label-warning w80 custom_disp_label"  data-href="' . base_url("faculty/active_inactive/" . base64_encode($faculty['faculty_id'])) . '">Re-Submitted</span>';
        } else {
          //onclick="return change_status(this,event,\''.$status_alert.'\', \''.$active.'\')" 
          $status = '<span class="label label-info w80 custom_disp_label"  data-href="' . base_url("faculty/active_inactive/" . base64_encode($faculty['faculty_id'])) . '">In Review</span>';
        }

        $url  = ' <a class="btn-link" href="' . base_url("iibfdra/Version_2/faculty/faculty_view/" . base64_encode($faculty['faculty_id'])) . '" title="View details" ><i class="fa fa-eye"></i> </a>&nbsp;&nbsp; <a class="btn-link" href="' . base_url("iibfdra/Version_2/faculty/faculty_edit/" . base64_encode($faculty['faculty_id'])) . '" title="Edit" ><i class="fa fa-pencil"></i></a>';

        /*'&nbsp;&nbsp;&nbsp;<a onclick="return confirm_action(this,event, \''.$alert.'\', \''.$check_status.'\', \''.$controller.'\', '.$faculty['faculty_id'].', \''.$func.'\')" class="btn-link" href="'.base_url("iibfdra/Version_2/faculty/faculty_delete/".base64_encode($faculty['faculty_id'])).'" title="Delete" ><i class="fa fa-trash"></i></a>'; */

        $data[] = array(
          "sr" => $sr,
          "faculty_id" => $faculty['faculty_id'],
          "faculty_number" => 'F' . $faculty['faculty_number'],
          "faculty_name" => $faculty['faculty_name'],
          "dob" => $faculty['dob'],
          "base_location" => $faculty['base_location'],
          "pan_no" => $faculty['pan_no'],
          "current_batches" => rtrim($str, ', '),
          "status" => $status,
          "action" => $url
        );
      }
    }

    $output = array(
      "draw" => intval($draw),
      "iTotalRecords" => $total_records,
      "iTotalDisplayRecords" => $totalRecordwithFilter,
      "aaData" => $data
    );

    echo json_encode($output);
  }

  public function faculty_add()
  {
    //$qry = $this->db->query("SELECT faculty_number 
    //FROM faculty_master ORDER BY faculty_id DESC 
    //LIMIT 0,1"); //WHERE is_deleted = 0 

    $data['error'] = '';

    $faculty_data = $this->master_model->getRecords('faculty_master', '', 'faculty_number', array('faculty_id' => 'DESC'), 0, 1);

    if (count($faculty_data) > 0) {
      $last_faculty_number = $faculty_data[0]['faculty_number'] + 1;
    } else {
      $last_faculty_number = 1;
    }

    $faculty_number_new = sprintf('%04d', $last_faculty_number);

    if (isset($_POST) && count($_POST) > 0 && $faculty_number_new != "" && $faculty_number_new != 'F0000') {
      $this->form_validation->set_rules('salutation', 'Salutation', 'required');
      $this->form_validation->set_rules('faculty_name', 'Faculty Name', 'required');
      //$this->form_validation->set_rules('faculty_photo', 'Faculty Photo', 'required');
      $this->form_validation->set_rules('pan_no', 'PAN No', 'required');
      $this->form_validation->set_rules('academic_qualification', 'Academic Qualification', 'required');
      //print_r($_FILES); //die;
      //print_r($_POST); //die;
      if ($this->form_validation->run() == TRUE) {
        //print_r($_POST);
        //print_r($_FILES); die;

        $faculty_number     = trim($faculty_number_new);
        //echo $faculty_number; die;
        $salutation     = trim($this->input->post('salutation'));
        $faculty_name     = trim($this->input->post('faculty_name'));
        $pan_no    = $this->input->post('pan_no');
        $dob   = trim($this->input->post('dob'));
        $base_location   = trim($this->input->post('base_location'));
        $academic_qualification   = trim($this->input->post('academic_qualification'));
        $personal_qualification   = trim($this->input->post('personal_qualification'));
        $work_exp   = $this->input->post('work_exp');
        $emp_id   = $this->input->post('emp_id');
        $gross_duration_month   = $this->input->post('gross_duration_month');
        $gross_duration_year   = $this->input->post('gross_duration_year');      

        $work_exp_iibf   = trim($this->input->post('work_exp_iibf'));
        $DRA_training_faculty_exp   = trim($this->input->post('DRA_training_faculty_exp'));
        $start_date   = trim($this->input->post('start_date'));
        $end_date   = trim($this->input->post('end_date'));
        $session_interested_in   = trim($this->input->post('session_interested_in'));
        $softskills_banking_exp   = trim($this->input->post('softskills_banking_exp'));
        $training_activities_exp   = trim($this->input->post('training_activities_exp'));

        if (!empty($_FILES['faculty_photo']['name'])) {
          $config['upload_path'] = 'uploads/faculty_photo/';
          $config['allowed_types'] = 'jpg|png|jpeg';
          $config['file_name'] = 'faculty_photo_' . rand() . '.' . strtolower(pathinfo($_FILES['faculty_photo']['name'], PATHINFO_EXTENSION));

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if ($this->upload->do_upload('faculty_photo')) {
            // Uploaded file data 
            $fileData = $this->upload->data();
            $uploadData['file_name'] = $fileData['file_name'];
            $uploadData['uploaded_on'] = date("Y-m-d H:i:s");
            $uploaded_id = 1;
            $faculty_photo      = $uploadData['file_name'];
            //echo '</br>'.$fileData['file_name'];

          } else {
            //echo $this->upload->display_errors(); 
            $errorUploadType .= $_FILES['faculty_photo']['name'] . ' | ';
            $uploaded_id = 0;
          }
        }

        //print_r($_FILES['pan_photo']['name']);

        if (!empty($_FILES['pan_photo']['name'])) {

          $config['upload_path'] = 'uploads/pan_photo/';
          $config['allowed_types'] = 'jpg|png|jpeg';
          $config['file_name'] = 'faculty_pan_' . rand() . '.' . strtolower(pathinfo($_FILES['pan_photo']['name'], PATHINFO_EXTENSION));

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if ($this->upload->do_upload('pan_photo')) {
            // Uploaded file data 
            $fileData = $this->upload->data();
            $uploadData['file_name'] = $fileData['file_name'];
            $uploadData['uploaded_on'] = date("Y-m-d H:i:s");
            $uploaded_id = 1;
            $pan_photo      = $uploadData['file_name'];
            //echo '</br>'.$fileData['file_name'];

          } else {
            //echo $this->upload->display_errors(); 
            $errorUploadType .= $_FILES['pan_photo']['name'] . ' | ';
            $uploaded_id = 0;
          }
        }

        if (count($work_exp) > 0) {
          $work_exp1 = isset($work_exp[0]) ? $work_exp[0] : '';
          $work_exp2 = isset($work_exp[1]) ? $work_exp[1] : '';
          $work_exp3 = isset($work_exp[2]) ? $work_exp[2] : '';
        }

        if (count($emp_id) > 0) {
          $emp_id1 = isset($emp_id[0]) ? $emp_id[0] : '';
          $emp_id2 = isset($emp_id[1]) ? $emp_id[1] : '';
          $emp_id3 = isset($emp_id[2]) ? $emp_id[2] : '';
        }

        if(count($gross_duration_month) > 0){
          $gross_duration_month1 = isset($gross_duration_month[0])?$gross_duration_month[0]:'';
          $gross_duration_month2 = isset($gross_duration_month[1])?$gross_duration_month[1]:'';
          $gross_duration_month3 = isset($gross_duration_month[2])?$gross_duration_month[2]:'';
        }

        if(count($gross_duration_year) > 0){
          $gross_duration_year1 = isset($gross_duration_year[0])?$gross_duration_year[0]:'';
          $gross_duration_year2 = isset($gross_duration_year[1])?$gross_duration_year[1]:'';
          $gross_duration_year3 = isset($gross_duration_year[2])?$gross_duration_year[2]:'';
        }
        

        if($faculty_photo == "" || $pan_photo == "") 
        {
          if($faculty_photo == "") { $data['error'] = " Please upload the faculty photo."; }
          else if($pan_photo == "") { $data['error'] = " Please upload the PAN photo."; }
        }
        else
        {
          $arr_insert = array(
            'institute_id'   => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
            'faculty_number' => $faculty_number,
            'salutation'     => $salutation,
            'faculty_name'   => $faculty_name,
            'faculty_photo'  => $faculty_photo,
            'pan_no'         => $pan_no,
            'pan_photo'      => $pan_photo,
            'dob'            => $dob,
            'base_location'  => $base_location,
            'academic_qualification' => $academic_qualification,
            'personal_qualification' => $personal_qualification,
            'work_exp1'      => $work_exp1,
            'work_exp2'      => $work_exp2,
            'work_exp3'      => $work_exp3,
            'emp_id1'        => $emp_id1,
            'emp_id2'       =>  $emp_id2,
            'emp_id3'       =>  $emp_id3,
            'gross_duration_month1'=> $gross_duration_month1,
            'gross_duration_month2'=>  $gross_duration_month2,
            'gross_duration_month3'=>  $gross_duration_month3,
            'gross_duration_year1'=> $gross_duration_year1,
            'gross_duration_year2'=>  $gross_duration_year2,
            'gross_duration_year3'=>  $gross_duration_year3,
            'work_exp_iibf' => $work_exp_iibf,
            'DRA_training_faculty_exp' => $DRA_training_faculty_exp,
            'start_date'   => $start_date,
            'end_date'   => $end_date,
            'session_interested_in' => $session_interested_in,
            'softskills_banking_exp' => $softskills_banking_exp,
            'training_activities_exp' => $training_activities_exp,
            'status'        => 'In Review',
            'created_by_id' => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
            'created_on'    => date('Y-m-d H:i:s')
          );
          //print_r($arr_insert); //die;
          $inserted_id = $this->master_model->insertRecord('faculty_master', $arr_insert, true);

          //echo $this->db->last_query();die;
          //echo $inserted_id; die;

          if ($inserted_id > 0) {
            $logs_arr = array(
              'faculty_id'          => $inserted_id,
              'action_taken'        => 'Add',
              'module_name'         => $this->module_title,
              'logs_description'    => serialize($arr_insert),
              'logs_previous_data'  => serialize($arr_insert),
              'ip_address'          => $this->input->ip_address(),
              'created_by_id'       => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
              'created_on'          => date('Y-m-d H:i:s')  
            );
            // create_log();

            $audit_id = $this->master_model->insertRecord('faculty_status_logs',$logs_arr);
          
            //Activity Log
            $this->session->set_flashdata('success_message', $this->module_title . " Added Successfully.");
            redirect(base_url('iibfdra/Version_2/faculty'));
          } else {
            $this->session->set_flashdata('error_message', " Something Went Wrong While Adding The " . ucfirst($this->module_title) . ".");
          }
          //redirect(base_url('iibfdra/Version_2/faculty'));
        }
      }
    }

    $data['institute_id'] = $this->session->userdata['dra_institute']['dra_inst_registration_id'];
    $data['action'] = 'add';

    $data['middle_content'] = 'faculty_add';
    $this->load->view('iibfdra/Version_2/common_view', $data);
  }

  public function faculty_edit($faculty_id)
  {
    $data['error'] = '';
    $faculty_id = base64_decode($faculty_id);

    $qry = $this->db->query("SELECT faculty_id, faculty_number, salutation, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration_month1, gross_duration_year1, work_exp2, emp_id2, gross_duration_month2, gross_duration_year2, work_exp3, emp_id3, gross_duration_month3, gross_duration_year3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND faculty_id = " . $faculty_id);

    $data['faculty_data'] = $records = $qry->result_array();
    $data['institute_id'] = $this->session->userdata['dra_institute']['dra_inst_registration_id'];

    if (isset($_POST) && count($_POST) > 0) {
      //print_r($_POST); die;
      $this->form_validation->set_rules('salutation', 'Salutation', 'required');
      $this->form_validation->set_rules('faculty_name', 'Faculty Name', 'required');
      //$this->form_validation->set_rules('faculty_photo', 'Faculty Photo', 'required');
      $this->form_validation->set_rules('pan_no', 'PAN No', 'required');
      $this->form_validation->set_rules('academic_qualification', 'Academic Qualification', 'required');

      if ($this->form_validation->run() == TRUE) {
        // print_r($_FILES); die;
        //$faculty_number     = trim($this->input->post('faculty_number_hidden'));
        $salutation     = trim($this->input->post('salutation'));
        $faculty_name     = trim($this->input->post('faculty_name'));
        $pan_no    = $this->input->post('pan_no');
        $dob   = trim($this->input->post('dob'));
        $base_location   = trim($this->input->post('base_location'));
        $academic_qualification   = trim($this->input->post('academic_qualification'));
        $personal_qualification   = trim($this->input->post('personal_qualification'));
        $work_exp   = $this->input->post('work_exp');
        $emp_id   = $this->input->post('emp_id');
        $gross_duration_month   = $this->input->post('gross_duration_month');
        $gross_duration_year   = $this->input->post('gross_duration_year');      

        $work_exp_iibf   = trim($this->input->post('work_exp_iibf'));
        $DRA_training_faculty_exp   = trim($this->input->post('DRA_training_faculty_exp'));
        $start_date   = trim($this->input->post('start_date'));
        $end_date   = trim($this->input->post('end_date'));
        $session_interested_in   = trim($this->input->post('session_interested_in'));
        $softskills_banking_exp   = trim($this->input->post('softskills_banking_exp'));
        $training_activities_exp   = trim($this->input->post('training_activities_exp'));
        $prev_status = $this->input->post('prev_status');

        if (!empty($_FILES['faculty_photo']['name'])) {
          $faculty_photo = 'faculty_photo_' . rand() . '.' . strtolower(pathinfo($_FILES['faculty_photo']['name'], PATHINFO_EXTENSION));

          $config = array();
          $config['upload_path'] = 'uploads/faculty_photo/';
          $config['allowed_types'] = "gif|jpg|png|jpeg";
          $config['overwrite'] = TRUE;
          $config['file_name'] = $faculty_photo;

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if ($this->upload->do_upload('faculty_photo')) {
            // Uploaded file data 
            $fileData = $this->upload->data();
            $uploadData['file_name'] = $fileData['file_name'];
            $uploadData['uploaded_on'] = date("Y-m-d H:i:s");
            $uploaded_id = 1;
            $faculty_photo      = $uploadData['file_name'];
            //echo '</br>'.$fileData['file_name'];

          } else {
            //echo $this->upload->display_errors(); 
            $errorUploadType .= $_FILES['faculty_photo']['name'] . ' | ';
            $uploaded_id = 0;
          }
        } else {
          $faculty_photo = trim($this->input->post('old_faculty_photo_image'));
        }

        if (!empty($_FILES['pan_photo']['name'])) {

          $pan_photo = 'faculty_pan_' . rand() . '.' . strtolower(pathinfo($_FILES['pan_photo']['name'], PATHINFO_EXTENSION));

          $config = array();
          $config['upload_path'] = 'uploads/pan_photo/';
          $config['allowed_types'] = "gif|jpg|png|jpeg";
          $config['overwrite'] = TRUE;
          $config['file_name'] = $pan_photo;

          $this->load->library('upload', $config);
          $this->upload->initialize($config);

          if ($this->upload->do_upload('pan_photo')) {
            // Uploaded file data 
            $fileData = $this->upload->data();
            $uploadData['file_name'] = $fileData['file_name'];
            $uploadData['uploaded_on'] = date("Y-m-d H:i:s");
            $uploaded_id = 1;
            $pan_photo = $uploadData['file_name'];
            //echo '</br>'.$fileData['file_name'];
          } else {
            //echo $this->upload->display_errors(); 
            $errorUploadType .= $_FILES['pan_photo']['name'] . ' | ';
            $uploaded_id = 0;
          }
        } else {
          $pan_photo = trim($this->input->post('old_pan_photo_image'));
        }

        if (count($work_exp) > 0) {
          $work_exp1 = isset($work_exp[0]) ? $work_exp[0] : '';
          $work_exp2 = isset($work_exp[1]) ? $work_exp[1] : '';
          $work_exp3 = isset($work_exp[2]) ? $work_exp[2] : '';
        }

        if (count($emp_id) > 0) {
          $emp_id1 = isset($emp_id[0]) ? $emp_id[0] : '';
          $emp_id2 = isset($emp_id[1]) ? $emp_id[1] : '';
          $emp_id3 = isset($emp_id[2]) ? $emp_id[2] : '';
        }

        if(count($gross_duration_month) > 0){
          $gross_duration_month1 = isset($gross_duration_month[0])?$gross_duration_month[0]:'';
          $gross_duration_month2 = isset($gross_duration_month[1])?$gross_duration_month[1]:'';
          $gross_duration_month3 = isset($gross_duration_month[2])?$gross_duration_month[2]:'';
        }

        if(count($gross_duration_year) > 0){
          $gross_duration_year1 = isset($gross_duration_year[0])?$gross_duration_year[0]:'';
          $gross_duration_year2 = isset($gross_duration_year[1])?$gross_duration_year[1]:'';
          $gross_duration_year3 = isset($gross_duration_year[2])?$gross_duration_year[2]:'';
        }

        //Added by Priyanka W for new status.
        if ($prev_status == 'Inactive') {
          $status = 'Re-Submitted';
        } else {
          $status = $prev_status;
        }
        //Added by Priyanka W for new status.

        $arr_upd = array(
          //'institute_id'   => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
          //'faculty_number' => $faculty_number,
          'salutation'     => $salutation,
          'faculty_name'   => $faculty_name,
          'faculty_photo'  => $faculty_photo,
          'pan_no'         => $pan_no,
          'pan_photo'      => $pan_photo,
          'dob'            => $dob,
          'base_location'  => $base_location,
          'academic_qualification' => $academic_qualification,
          'personal_qualification' => $personal_qualification,
          'work_exp1'      => $work_exp1,
          'work_exp2'      => $work_exp2,
          'work_exp3'      => $work_exp3,
          'emp_id1'        => $emp_id1,
          'emp_id2'       =>  $emp_id2,
          'emp_id3'       =>  $emp_id3,
          'gross_duration_month1'=> $gross_duration_month1,
          'gross_duration_month2'=>  $gross_duration_month2,
          'gross_duration_month3'=>  $gross_duration_month3,
          'gross_duration_year1'=> $gross_duration_year1,
          'gross_duration_year2'=>  $gross_duration_year2,
          'gross_duration_year3'=>  $gross_duration_year3,
          'work_exp_iibf' => $work_exp_iibf,
          'DRA_training_faculty_exp' => $DRA_training_faculty_exp,
          'start_date  ' => $start_date,
          'end_date  ' => $end_date,
          'session_interested_in' => $session_interested_in,
          'softskills_banking_exp' => $softskills_banking_exp,
          'training_activities_exp' => $training_activities_exp,
          'status' => $status,
          'updated_by_id' => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
          'updated_on'    => date('Y-m-d H:i:s')
        );
        $upd_where = array('faculty_id' => $faculty_id);
        //print_r($arr_insert); die;
        $updated_id = $this->master_model->updateRecord('faculty_master', $arr_upd, $upd_where);

        //echo $this->db->last_query(); die;

        if ($updated_id > 0) {
          if($status == 'Re-Submitted'){
                    $logs_arr = array(
                      'faculty_id'          => $faculty_id,
                      'action_taken'        => $status,
                      'module_name'         => $this->module_title,
                      'logs_description'    => serialize($arr_upd),
                      'logs_previous_data'  => serialize($records),
                      'ip_address'          => $this->input->ip_address(),
                      'created_by_id'       => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
                      'created_on'          => date('Y-m-d H:i:s')  
                    );
                  }
                  else{

                    $logs_arr = array(
                      'faculty_id'          => $faculty_id,
                      'action_taken'        => 'Edit',
                      'module_name'         => $this->module_title,
                      'logs_description'    => serialize($arr_insert),
                      'logs_previous_data'  => serialize($arr_insert),
                      'ip_address'          => $this->input->ip_address(),
                      'created_by_id'       => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
                      'created_on'          => date('Y-m-d H:i:s')  
                    );
                  }
              
                  $audit_id = $this->master_model->insertRecord('faculty_status_logs',$logs_arr);
          // create_log();
          /*Activity Log*/
          $this->session->set_flashdata('success_message', $this->module_title . " Updated Successfully.");
          redirect(base_url('iibfdra/Version_2/faculty'));
        } else {
          $this->session->set_flashdata('error_message', " Something Went Wrong While Adding The " . ucfirst($this->module_title) . ".");
        }
      }
    }

    $data['action'] = 'edit';
    $data['middle_content'] = 'faculty_add';
    $this->load->view('iibfdra/Version_2/common_view', $data);
  }

  public function faculty_delete($faculty_id)
  {
    exit;
    $faculty_id = base64_decode($faculty_id);

    $arr_update  = array(
      "is_deleted" => 1
    );

    $arr_where  = array(
      "faculty_id" => $faculty_id
    );

    $last_update_id = $this->master_model->updateRecord('faculty_master', $arr_update, $arr_where);
    //echo $this->db->last_query();

    if ($last_update_id > 0) {
      $this->session->set_flashdata('success_message', $this->module_title . " Deleted Successfully.");
    } else {
      $this->session->set_flashdata('success_message', $this->module_title . " Something Went Wrong.");
    }
    redirect(base_url('iibfdra/Version_2/faculty'));
  }

  public function faculty_view($faculty_id)
  {

    $faculty_id = base64_decode($faculty_id);

    $qry = $this->db->query("SELECT faculty_id, faculty_number, salutation, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration_month1, gross_duration_year1, work_exp2, emp_id2, gross_duration_month2, gross_duration_year2, work_exp3, emp_id3, gross_duration_month3, gross_duration_year3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND faculty_id = " . $faculty_id);

    $data['faculty_data'] = $records = $qry->result_array();

    $logqry = $this->db->query("SELECT faculty_id, action_taken, reason, created_on
            FROM faculty_status_logs
            WHERE faculty_id = ".$faculty_id."
            ORDER BY created_on DESC");//AND   action_taken != 'Add'

    $data['log_data'] = $records1 = $logqry->result_array();

    
    $data['action'] = 'view';
    $data['middle_content'] = 'faculty_add';
    $this->load->view('iibfdra/Version_2/common_view', $data);
  }

  public function check_faculty_status()
  {
    $faculty_id = $_POST['prim_id'];
    $faculty_query = $this->db->query("SELECT id, batch_code
            FROM agency_batch 
            WHERE is_deleted = 0
            AND   (first_faculty = " . $faculty_id . " OR sec_faculty = " . $faculty_id . " OR additional_first_faculty = " . $faculty_id . " OR additional_sec_faculty = " . $faculty_id . ")
            GROUP BY id");

    $faculty_data = $faculty_query->result_array();

    $str1 = '';
    if (count($faculty_data)) {
      foreach ($faculty_data as $key => $value) {
        $str1 .= $value['batch_code'] . ',';
      }
      rtrim($str1, ',');
      $str .= ' Can not delete the Faculty as Faculty is Referred in ' . $str1 . ' this Batch.';
    }

    echo $str;
  }

  public function removeFile()
  {
    $faculty_id = $this->input->post('faculty_id');
    $img = $this->input->post('img');
    //echo $faculty_id.'---'.$img;
    if ($img == 'faculty_photo') {
      $arr_update = array(
        'faculty_photo' => '',
      );
    } else {
      $arr_update = array(
        'pan_photo' => '',
      );
    }

    $arr_where  = array(
      "faculty_id" => $faculty_id
    );
    $last_update_id = $this->master_model->updateRecord('faculty_master', $arr_update, $arr_where);
    //echo $this->db->last_query();
    echo $last_update_id;
  }

  public function check_pan_no()
  {

    if (@$_POST['action'] == 'add') {
      $this->db->where('pan_no', @$_POST['pan_no']);
      //$this->db->where('institute_id',@$_POST['institute_id']);
      //$this->db->where('status','Active');
      $this->db->where(" (status = 'Active' OR institute_id = '".$_POST['institute_id']."') ");
      $this->db->where('is_deleted', '0');
      $pan_no_data = $this->master_model->getRecords('faculty_master');
      //echo count($pan_no_data);
    } else {
      $this->db->where('pan_no', @$_POST['pan_no']);
      $this->db->where('faculty_id <>', @$_POST['faculty_id']);
      //$this->db->where('institute_id',@$_POST['institute_id']);
      //$this->db->where('status','Active');
      $this->db->where(" (status = 'Active' OR institute_id = '".$_POST['institute_id']."') ");
      $this->db->where('is_deleted', '0');
      $pan_no_data = $this->master_model->getRecords('faculty_master');
      //echo count($pan_no_data);
    }

    if (count($pan_no_data) == 0) {
      $output = array(
        'success' => true
      );

      echo json_encode($output);
    }
  }
}
