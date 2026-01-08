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

        if($searchValue != '')
        {
          $searchValue = str_replace("'",'"',$searchValue);
          $faculty_id_search = str_replace("F","",$searchValue);
          $searchQuery .= " AND (faculty_number like '%".$faculty_id_search."%' or faculty_code like '%".$faculty_id_search."%' or faculty_name like '%".$searchValue."%' or pan_no like '%".$searchValue."%' or base_location like '%".$searchValue."%' or dob like '%".$searchValue."%' or languages like '%".$searchValue."%' or status like '%".$searchValue."%') ";
    }

    $DRA_Version_2_instId = $this->config->item('DRA_Version_2_instId');
    $DRA_Version_2_instIdStr = implode(',', $DRA_Version_2_instId);

        $select = $this->db->query("SELECT faculty_id, faculty_number, faculty_use_status, faculty_code,faculty_name,languages, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration_month1, gross_duration_year1, work_exp2, emp_id2, gross_duration_month2, gross_duration_year3, work_exp3, emp_id3, gross_duration_month3, gross_duration_year3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND   institute_id =" . $this->agency_id . " 
            AND   institute_id IN (" . $DRA_Version_2_instIdStr . ")");

    ## Total number of records with filtering
    $records = $select->result_array();
    $total_records = count($records);


    ## Total number of records without filtering
        $select2  = "SELECT faculty_id, faculty_number, faculty_use_status, faculty_code, faculty_name, languages, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration_month1, gross_duration_year1, work_exp2, emp_id2, gross_duration_month2, gross_duration_year3, work_exp3, emp_id3, gross_duration_month3, gross_duration_year3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
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
                AND   (first_faculty = ".$faculty['faculty_id']." OR sec_faculty = ".$faculty['faculty_id']." OR additional_first_faculty = ".$faculty['faculty_id']." OR additional_sec_faculty = ".$faculty['faculty_id'].") 
                AND CURDATE() BETWEEN batch_from_date AND batch_to_date");

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
        
        $faculty_use_status = "In Use";
        if ( $faculty['faculty_use_status'] == 'NotInUse' ) {
          $faculty_use_status = "Not In Use";          
        }     

        $data[] = array(
                "sr"=>$sr,
                "faculty_id"=>$faculty['faculty_id'],
                // "faculty_number"=>'F'.$faculty['faculty_number'],
                "faculty_number"=>$faculty['faculty_code'],
                "faculty_name"=>$faculty['faculty_name'],
                "languages"=>$faculty['languages'],
                "dob"=>$faculty['dob'],
                "base_location"=>$faculty['base_location'],
                "pan_no"=>$faculty['pan_no'],
                "current_batches"=>rtrim($str,', '),
                "faculty_use_status"=>$faculty_use_status,
                "status"=>$status,
                "action"=>$url
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
    $data['error'] = '';
		$faculty_data = $this->master_model->getRecords('faculty_master', '', 'faculty_number', array('faculty_number' => 'DESC'), 0, 1);

    if(count($faculty_data) > 0) { $last_faculty_number = $faculty_data[0]['faculty_number'] + 1;
    } else {
      $last_faculty_number = 1;
    }
    $faculty_number_new = sprintf('%04d', $last_faculty_number);

    if (isset($_POST) && count($_POST) > 0 && $faculty_number_new != "" && $faculty_number_new != 'F0000') {

      $add_type         = $this->input->post('add_type');  
      $ext_faculty_code = $this->input->post('db_faculty_code');

      if ($add_type != 'normal' && $add_type != '') 
      {
        $faculty_add_status = $this->insert_faculty_data($ext_faculty_code);
        if ( $faculty_add_status ) {
            $this->session->set_flashdata('success_message',$this->module_title." Added Successfully.");
            redirect(base_url('iibfdra/Version_2/faculty'));   
        }
        $this->session->set_flashdata('error_message'," Something Went Wrong While Adding The ".ucfirst($this->module_title).".");
        redirect(base_url('iibfdra/Version_2/faculty'));  
      }  

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
              $arr_lang  = $this->input->post('language');
              $languages = implode(',',$arr_lang);
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

              $faculty_code = 'F'.$faculty_number.'B';

              if ($session_interested_in == 1) {
                $faculty_code = 'F'.$faculty_number.'B';
              } elseif ($session_interested_in == 2) {
                $faculty_code = 'F'.$faculty_number.'S';
              } else {
                $faculty_code = 'F'.$faculty_number.'D';
              }
              
              if (!empty($_FILES['faculty_photo']['name']))
              {
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
                  'faculty_code'   => $faculty_code,
            'salutation'     => $salutation,
                  'languages'      => $languages,
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

              if($inserted_id > 0)
              {            
                $agency_id=$this->session->userdata['dra_institute']['dra_inst_registration_id'];
                $qry = $this->db->query("SELECT institute_name FROM dra_accerdited_master a WHERE dra_inst_registration_id = ".$agency_id);
                $res = $qry->result_array();

            $logs_arr = array(
              'faculty_id'          => $inserted_id,
              'action_taken'        => 'Add faculty - by '.$res[0]['institute_name'],
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

  private function insert_faculty_data( $ext_faculty_code ) 
    {
        $this->db->where('faculty_code',$ext_faculty_code);
        $existing_faculty_data = $this->master_model->getRecords('faculty_master');
        // echo "<pre>".$ext_faculty_codes; print_r($existing_faculty_data);exit;   
        if ( count($existing_faculty_data) > 0 ) 
        {
            $arr_insert = array(
              'institute_id'   => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
              'faculty_number' => $existing_faculty_data[0]['faculty_number'],
              'faculty_code'   => $existing_faculty_data[0]['faculty_code'],
              'salutation'     => $existing_faculty_data[0]['salutation'],
              'languages'      => $existing_faculty_data[0]['languages'],
              'faculty_name'   => $existing_faculty_data[0]['faculty_name'],
              'faculty_photo'  => $existing_faculty_data[0]['faculty_photo'], 
              'pan_no'         => $existing_faculty_data[0]['pan_no'],
              'pan_photo'      => $existing_faculty_data[0]['pan_photo'], 
              'dob'            => $existing_faculty_data[0]['dob'],
              'base_location'  => $existing_faculty_data[0]['base_location'],
              'academic_qualification'=> $existing_faculty_data[0]['academic_qualification'],
              'personal_qualification'=> $existing_faculty_data[0]['personal_qualification'],
              'work_exp1'      => $existing_faculty_data[0]['work_exp1'],
              'work_exp2'      => $existing_faculty_data[0]['work_exp2'],
              'work_exp3'      => $existing_faculty_data[0]['work_exp3'],
              'emp_id1'        => $existing_faculty_data[0]['emp_id1'],
              'emp_id2'       =>  $existing_faculty_data[0]['emp_id2'],
              'emp_id3'       =>  $existing_faculty_data[0]['emp_id3'],
              'gross_duration_month1'=> $existing_faculty_data[0]['gross_duration_month1'],
              'gross_duration_month2'=>  $existing_faculty_data[0]['gross_duration_month2'],
              'gross_duration_month3'=>  $existing_faculty_data[0]['gross_duration_month3'],
              'gross_duration_year1'=> $existing_faculty_data[0]['gross_duration_year1'],
              'gross_duration_year2'=>  $existing_faculty_data[0]['gross_duration_year2'],
              'gross_duration_year3'=>  $existing_faculty_data[0]['gross_duration_year3'],
              'work_exp_iibf' => $existing_faculty_data[0]['work_exp_iibf'],
              'DRA_training_faculty_exp' => $existing_faculty_data[0]['DRA_training_faculty_exp'],
              'start_date'   => $existing_faculty_data[0]['start_date'],
              'end_date'   => $existing_faculty_data[0]['end_date'],
              'session_interested_in' =>  $existing_faculty_data[0]['session_interested_in'],
              'softskills_banking_exp' => $existing_faculty_data[0]['softskills_banking_exp'],
              'training_activities_exp' => $existing_faculty_data[0]['training_activities_exp'],
              'status'        => 'In Review',
              'created_by_id' =>$this->session->userdata['dra_institute']['dra_inst_registration_id'],
              'created_on'    => date('Y-m-d H:i:s')
            );

            $inserted_id = $this->master_model->insertRecord('faculty_master',$arr_insert,true);  

            if ($inserted_id) 
            {
                $agency_id=$this->session->userdata['dra_institute']['dra_inst_registration_id'];
                $qry = $this->db->query("SELECT institute_name FROM dra_accerdited_master a WHERE dra_inst_registration_id = ".$agency_id);
                $res = $qry->result_array();
                
                $logs_arr = array(
                    'faculty_id'          => $inserted_id,
                    'action_taken'        => 'Add faculty - by '.$res[0]['institute_name'],
                    'module_name'         => $this->module_title,
                    'logs_description'    => serialize($arr_insert),
                    'logs_previous_data'  => serialize($arr_insert),
                    'ip_address'          => $this->input->ip_address(),
                    'created_by_id'       => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
                    'created_on'          => date('Y-m-d H:i:s')  
                );
              
                $audit_id = $this->master_model->insertRecord('faculty_status_logs',$logs_arr);
                return true;
            }
            return false;    
        }       
        return false;
    }

    public function getData()
    {
        $arr_result['status'] = 'error';
        $arr_result['msg']    = 'Faculty details not found.';
        $arr_result['data']   = [];

        $action       = $this->input->post('action');
        $faculty_code = trim($this->input->post('faculty_code'));
        $institute_id = $this->input->post('institute_id');

        $notInstituteqry = $this->db->query("SELECT * FROM faculty_master WHERE is_deleted = 0 AND (faculty_code = '".$faculty_code."' OR pan_no = '".$faculty_code."') AND institute_id != ".$institute_id);
        
        $arr_faculty = $notInstituteqry->result_array();
        
        if (count($arr_faculty) > 0) 
        {
            $instituteQry = $this->db->query("SELECT * FROM faculty_master WHERE is_deleted = 0 AND (faculty_code = '".$faculty_code."' OR pan_no = '".$faculty_code."') AND institute_id = ".$institute_id);
            
            $arr_institute_faculty = $instituteQry->result_array();

            if ( count($arr_institute_faculty) > 0 ) 
            {
                $arr_result['msg'] = 'This Faculty is already exist.';
            }
            else
            {
                $work_exp_html = $this->build_work_exp_html($arr_faculty);       
                $arr_result['status']        = 'success';
                $arr_result['msg']           = 'Faculty details found succesfully.';
                $arr_result['work_exp_html'] = $work_exp_html;
                $arr_result['data']          = $arr_faculty;
            }
        }

        echo json_encode($arr_result);
    }
    
    private function build_work_exp_html($arr_faculty)
    {
        $work_exp_html = '';
        if ( trim($arr_faculty[0]['work_exp1']) != '' ) 
        {
            $work_exp_one             = $arr_faculty[0]['work_exp1'];
            $emp_id_one               = $arr_faculty[0]['emp_id1'];
            $gross_duration_month_one = $arr_faculty[0]['gross_duration_month1'];
            $gross_duration_year_one  = $arr_faculty[0]['gross_duration_year1'];

            $work_exp_html .= '<div class="col-sm-12">';
            $work_exp_html .= '  <div class="form-group">';
            $work_exp_html .= '     <label for="roleid" class="col-sm-3 control-label"></label>';
            $work_exp_html .= '     <div class="col-sm-2">';
            $work_exp_html .= '         <input type="text" class="form-control" id="work_exp0" name="work_exp[]" placeholder="e.g. ABC Bank/ABC Agency"  value="'.$work_exp_one.'" data-parsley-errors-container="#work_exp_error0" data-parsley-required >';
            $work_exp_html .= '         <span class="note-error" id="work_exp_error0"></span>';
            $work_exp_html .= '     </div>';

            $work_exp_html .= '     <div class="col-sm-3">';
            $work_exp_html .= '         <input type="text" class="form-control" id="emp_id0" name="emp_id[]" placeholder="e.g. Manager - ABC123" value="'.$emp_id_one.'" data-parsley-errors-container="#emp_id_error0" data-parsley-required >';
            $work_exp_html .= '         <span class="note-error" id="emp_id_error0"  ></span>';
            $work_exp_html .= '     </div>';
            
            $work_exp_html .= '     <div class="col-sm-2">';
            $work_exp_html .= '         <input type="text" class="form-control" id="gross_duration_year0" name="gross_duration_year[]" placeholder="e.g. 10"  value="'.$gross_duration_year_one.'" maxlength="2" data-parsley-errors-container="#gross_duration_year_error0" data-parsley-required>';
            $work_exp_html .= '         <span class="note-error" id="gross_duration_year_error0" ></span>';
            $work_exp_html .= '     </div>';

            $work_exp_html .= '     <div class="col-sm-2">';
            $work_exp_html .= '         <input type="text" class="form-control" id="gross_duration_month0" name="gross_duration_month[]" placeholder="e.g. 1"  value="'.$gross_duration_month_one.'" maxlength="2" data-parsley-errors-container="#gross_duration_month_error0" data-parsley-required>';
            $work_exp_html .= '         <span class="note-error" id="gross_duration_month_error0" ></span>';
            $work_exp_html .= '     </div>';
            
            $work_exp_html .= '   </div>';
            $work_exp_html .= '</div>';
            // echo trim($arr_faculty[0]['work_exp2']); exit;
        }
        
        if ( $arr_faculty[0]['work_exp2'] != '' ) 
        {  
            $work_exp_two             = $arr_faculty[0]['work_exp2'];
            $emp_id_two               = $arr_faculty[0]['emp_id2'];
            $gross_duration_month_two = $arr_faculty[0]['gross_duration_month2'];
            $gross_duration_year_two  = $arr_faculty[0]['gross_duration_year2'];

            $work_exp_html .= '<div class="col-sm-12" id="textbox-label">';
            $work_exp_html .= '  <div class="form-group">';
            $work_exp_html .= '     <label for="roleid" class="col-sm-3 control-label"></label>';
            $work_exp_html .= '     <div class="col-sm-2">';
            $work_exp_html .= '         <input type="text" class="form-control" id="work_exp1" name="work_exp[]" placeholder="e.g. ABC Bank/ABC Agency"  value="'.$work_exp_two.'" data-parsley-errors-container="#work_exp_error0" data-parsley-required >';
            $work_exp_html .= '         <span class="note-error" id="work_exp_error0"></span>';
            $work_exp_html .= '     </div>';

            $work_exp_html .= '     <div class="col-sm-3">';
            $work_exp_html .= '         <input type="text" class="form-control" id="emp_id1" name="emp_id[]" placeholder="e.g. Manager - ABC123" value="'.$emp_id_two.'" data-parsley-errors-container="#emp_id_error0" data-parsley-required >';
            $work_exp_html .= '         <span class="note-error" id="emp_id_error0"  ></span>';
            $work_exp_html .= '     </div>';
            
            $work_exp_html .= '     <div class="col-sm-2">';
            $work_exp_html .= '         <input type="text" class="form-control" id="gross_duration_year1" name="gross_duration_year[]" placeholder="e.g. 10"  value="'.$gross_duration_year_two.'" maxlength="2" data-parsley-errors-container="#gross_duration_year_error0" data-parsley-required>';
            $work_exp_html .= '         <span class="note-error" id="gross_duration_year_error0" ></span>';
            $work_exp_html .= '     </div>';

            $work_exp_html .= '     <div class="col-sm-2">';
            $work_exp_html .= '         <input type="text" class="form-control" id="gross_duration_month1" name="gross_duration_month[]" placeholder="e.g. 1"  value="'.$gross_duration_month_two.'" maxlength="2" data-parsley-errors-container="#gross_duration_month_error0" data-parsley-required>';
            $work_exp_html .= '         <span class="note-error" id="gross_duration_month_error0" ></span>';
            $work_exp_html .= '     </div>';
            
            $work_exp_html .= '   </div>';
            $work_exp_html .= '</div>';       
        }
        
        if ( trim($arr_faculty[0]['work_exp3']) != '' ) 
        {
            $work_exp_three             = $arr_faculty[0]['work_exp3'];
            $emp_id_three               = $arr_faculty[0]['emp_id3'];
            $gross_duration_month_three = $arr_faculty[0]['gross_duration_month3'];
            $gross_duration_year_three  = $arr_faculty[0]['gross_duration_year3'];

            $work_exp_html .= '<div class="col-sm-12" id="textbox-label">';  
            $work_exp_html .= '   <div class="form-group">';
            $work_exp_html .= '     <label for="roleid" class="col-sm-3 control-label"></label>';
            $work_exp_html .= '     <div class="col-sm-2">';
            $work_exp_html .= '         <input type="text" class="form-control" id="work_exp1" name="work_exp[]" placeholder="e.g. ABC Bank/ABC Agency"  value="'.$work_exp_three.'" data-parsley-errors-container="#work_exp_error0" data-parsley-required >';
            $work_exp_html .= '         <span class="note-error" id="work_exp_error0"></span>';
            $work_exp_html .= '     </div>';

            $work_exp_html .= '     <div class="col-sm-3">';
            $work_exp_html .= '         <input type="text" class="form-control" id="emp_id1" name="emp_id[]" placeholder="e.g. Manager - ABC123" value="'.$emp_id_three.'" data-parsley-errors-container="#emp_id_error0" data-parsley-required >';
            $work_exp_html .= '         <span class="note-error" id="emp_id_error0"  ></span>';
            $work_exp_html .= '     </div>';
            
            $work_exp_html .= '     <div class="col-sm-2">';
            $work_exp_html .= '         <input type="text" class="form-control" id="gross_duration_year1" name="gross_duration_year[]" placeholder="e.g. 10"  value="'.$gross_duration_year_three.'" maxlength="2" data-parsley-errors-container="#gross_duration_year_error0" data-parsley-required>';
            $work_exp_html .= '         <span class="note-error" id="gross_duration_year_error0" ></span>';
            $work_exp_html .= '     </div>';
            
            $work_exp_html .= '     <div class="col-sm-2">';
            $work_exp_html .= '         <input type="text" class="form-control" id="gross_duration_month1" name="gross_duration_month[]" placeholder="e.g. 1"  value="'.$gross_duration_month_three.'" maxlength="2" data-parsley-errors-container="#gross_duration_month_error0" data-parsley-required>';
            $work_exp_html .= '         <span class="note-error" id="gross_duration_month_error0" ></span>';
            $work_exp_html .= '     </div>';
            
            $work_exp_html .= '   </div>';
            $work_exp_html .= '</div>';
        }
        return $work_exp_html;
    }
    
  public function faculty_edit($faculty_id)
  {
    $data['error'] = '';
    $faculty_id = base64_decode($faculty_id);

        $qry = $this->db->query("SELECT faculty_id, faculty_number, faculty_code, languages, salutation, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration_month1, gross_duration_year1, work_exp2, emp_id2, gross_duration_month2, gross_duration_year2, work_exp3, emp_id3, gross_duration_month3, gross_duration_year3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND faculty_id = " . $faculty_id);

    $data['faculty_data'] = $records = $qry->result_array();
    $data['institute_id'] = $this->session->userdata['dra_institute']['dra_inst_registration_id'];

    $edit_faculty_code =  $records[0]['faculty_code'];

    $prev_faculty_data = $records[0];
    
    $faculty_qry = $this->db->query("SELECT * FROM faculty_master WHERE is_deleted = 0 AND faculty_code = '" . $edit_faculty_code."'");

    $fac_records = $faculty_qry->result_array();

    $faculty_code_count = count($fac_records); 

    // faculty_code
    if (isset($_POST) && count($_POST) > 0) {
      


      $this->form_validation->set_rules('salutation', 'Salutation', 'required');
      $this->form_validation->set_rules('faculty_name', 'Faculty Name', 'required');
      //$this->form_validation->set_rules('faculty_photo', 'Faculty Photo', 'required');
      $this->form_validation->set_rules('pan_no', 'PAN No', 'required');
      $this->form_validation->set_rules('academic_qualification', 'Academic Qualification', 'required');

      if ($this->form_validation->run() == TRUE) 
      {        
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


        $faculty_code   = $this->input->post('faculty_code');
        $faculty_number = $this->input->post('faculty_number');
        if ($session_interested_in == 1) {
            $faculty_code = 'F'.$faculty_number.'B';
        } elseif ($session_interested_in == 2) {
            $faculty_code = 'F'.$faculty_number.'S';
        } else {
            $faculty_code = 'F'.$faculty_number.'D';
        }  
        // echo $faculty_code; exit;
        $arr_lang  = $this->input->post('language');
        // echo "<pre>"; print_r($_POST); exit;
        $languages = implode(',',$arr_lang);
                // echo $languages;  exit;
        $arr_upd = array(
          //'institute_id'   => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
          //'faculty_number' => $faculty_number,
          'salutation'     => $salutation,
          'faculty_name'   => $faculty_name,
                    'faculty_code'   => $faculty_code,
                    'languages'       => $languages,
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

                // echo $this->db->last_query(); die;
                $agency_id = $this->session->userdata['dra_institute']['dra_inst_registration_id'];
                $qry = $this->db->query("SELECT institute_name FROM dra_accerdited_master a WHERE dra_inst_registration_id = ".$agency_id);
                $res = $qry->result_array();

        if ($updated_id > 0) {

          $changed_fields = $this->getUpdatedFacultyData($_POST,$prev_faculty_data);
          $changed_fields_message = implode(', ', $changed_fields);
          
          if (trim($changed_fields_message) != '') {
            $log_message = 'The following fields were updated by the institute: ' . $changed_fields_message; 
          } else {
             $log_message = 'No changes were made by the institute during the edit process.';
          }
          
          if($status == 'Re-Submitted') {
                    $logs_arr = array(
                      'faculty_id'          => $faculty_id,
                      'action_taken'        => $status.' faculty - by '.$res[0]['institute_name'],
                      'module_name'         => $this->module_title,
                      'reason'              => $log_message,
                      'logs_description'    => serialize($arr_upd),
                      'logs_previous_data'  => serialize($records),
                      'ip_address'          => $this->input->ip_address(),
                      'created_by_id'       => $this->session->userdata['dra_institute']['dra_inst_registration_id'],
                      'created_on'          => date('Y-m-d H:i:s')  
                    );
                  }
                  else
                  {
                    $logs_arr = array(
                      'faculty_id'          => $faculty_id,
                      'action_taken'        => 'Edit faculty - by '.$res[0]['institute_name'],
                      'module_name'         => $this->module_title,
                      'reason'              => $log_message,
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

    $data['action']         = 'edit';
    $data['faculty_count']  = $faculty_code_count;
    $data['middle_content'] = 'faculty_add';
    $this->load->view('iibfdra/Version_2/common_view', $data);
  }

  private function getUpdatedFacultyData($request_data,$original_data)
  {
    $updated_fields = array(); // Array to store only updated fields
    $changed_fields = array();
    // echo "<pre>"; print_r($request_data);
    // echo "<pre>"; print_r($original_data); exit;
    if (isset($request_data['work_exp']) && count($request_data['work_exp']) > 0) {
      foreach ($request_data['work_exp'] as $work_exp_key => $work_exp) {
        $work_exp_key=$work_exp_key+1;
        $request_data['work_exp'.$work_exp_key] = $work_exp;         
      }
    }

    if (isset($request_data['emp_id']) && count($request_data['emp_id']) > 0) {
      foreach ($request_data['emp_id'] as $emp_id_key => $emp_id) {
        $emp_id_key=$emp_id_key+1;
        $request_data['emp_id'.$emp_id_key] = $emp_id;         
      }
    }  

    if (isset($request_data['gross_duration_month']) && count($request_data['gross_duration_month']) > 0) {
      foreach ($request_data['gross_duration_month'] as $gross_duration_month_key => $gross_duration_month) {
        $gross_duration_month_key=$gross_duration_month_key+1;
        $request_data['gross_duration_month'.$gross_duration_month_key] = $gross_duration_month;         
      }
    }  

    if (isset($request_data['gross_duration_year']) && count($request_data['gross_duration_year']) > 0) {
      foreach ($request_data['gross_duration_year'] as $gross_duration_year_key => $gross_duration_year) {
        $gross_duration_year_key=$gross_duration_year_key+1;
        $request_data['gross_duration_year'.$gross_duration_year_key] = $gross_duration_year;         
      }
    }   

    if (isset($request_data['language']) && count($request_data['language']) > 0) {
      $request_data['languages'] = implode(',',$request_data['language']);         
    }  

    unset($request_data['work_exp']);
    unset($request_data['emp_id']);
    unset($request_data['gross_duration_month']);
    unset($request_data['gross_duration_year']);  
    unset($request_data['btn_submit']);
    unset($request_data['language']);
    unset($request_data['faculty_id']);

    // echo "<pre>"; print_r($request_data);
    // echo "<pre>"; print_r($original_data); exit;

    // Only proceed if form is submitted
    if (isset($request_data) && count($request_data) > 0) 
    {
      // Compare each form input with original data, if changed, add to the array
      if ($original_data['salutation'] != $request_data['salutation']) {
          $updated_fields['Salutation'] = $request_data['salutation'];
          $changed_fields[] = 'Salutation';
      }

      if ($original_data['faculty_name'] != $request_data['faculty_name']) {
          $updated_fields['Faculty name'] = $request_data['faculty_name'];
          $changed_fields[] = 'Faculty name';
      }

      if ($original_data['pan_no'] != $request_data['pan_no']) {
          $updated_fields['Pan no'] = $request_data['pan_no'];
          $changed_fields[] = 'Pan no';
      }

      if ($original_data['dob'] != $request_data['dob']) {
          $updated_fields['Birth Date'] = $request_data['dob'];
          $changed_fields[] = 'Birth Date';
      }

      if ($original_data['base_location'] != $request_data['base_location']) {
          $updated_fields['Base location'] = $request_data['base_location'];
          $changed_fields[] = 'Base location';
      }

      if ($original_data['academic_qualification'] != $request_data['academic_qualification']) {
          $updated_fields['Academic Qualification(s) with year'] = $request_data['academic_qualification'];
          $changed_fields[] = 'Academic Qualification(s) with year';        
      }

      if ($original_data['personal_qualification'] != $request_data['personal_qualification']) {
          $updated_fields['Professional Qualification'] = $request_data['personal_qualification'];
          $changed_fields[] = 'Professional Qualification';
      }

      if ($original_data['work_exp_iibf'] != $request_data['work_exp_iibf']) {
          $updated_fields['Work Experience in IIBF'] = $request_data['work_exp_iibf'];
          $changed_fields[] = 'Work Experience in IIBF';
      }

      if ($original_data['DRA_training_faculty_exp'] != $request_data['DRA_training_faculty_exp']) {
          $updated_fields['Experience as Faculty in DRA Training'] = $request_data['DRA_training_faculty_exp'];
          $changed_fields[] = 'Experience as Faculty in DRA Training';
      }

      if ($original_data['start_date'] != $request_data['start_date']) {
          $updated_fields['Period of Association with the agency(Year)'] = $request_data['start_date'];
          $changed_fields[] = 'Period of Association with the agency(Year)';
      }

      if ($original_data['end_date'] != $request_data['end_date']) {
          $updated_fields['Period of Association with the agency(Month)'] = $request_data['end_date'];
          $changed_fields[] = 'Period of Association with the agency(Month)';
      }

      if ($original_data['session_interested_in'] != $request_data['session_interested_in']) {
          $updated_fields['Interested to take sessions'] = $request_data['session_interested_in'];
          $changed_fields[] = 'Interested to take sessions';
      }

      if ($original_data['softskills_banking_exp'] != $request_data['softskills_banking_exp']) {
          $updated_fields['Qualification / Experience in Soft Skill'] = $request_data['softskills_banking_exp'];
          $changed_fields[] = 'Qualification / Experience in Soft Skill';
      }

      if ($original_data['training_activities_exp'] != $request_data['training_activities_exp']) {
          $updated_fields['Experience/Comments on training specific activities'] = $request_data['training_activities_exp'];
          $changed_fields[] = 'Experience/Comments on training specific activities';
      }

      if (isset($request_data['work_exp1']) && $original_data['work_exp1'] != $request_data['work_exp1']) {
          $updated_fields['Work Experience 1'] = $request_data['work_exp1'];
          $changed_fields[] = 'Work Experience 1';
      }

      if (isset($request_data['work_exp2']) && $original_data['work_exp2'] != $request_data['work_exp2']) {
          $updated_fields['Work Experience 2'] = $request_data['work_exp2'];
          $changed_fields[] = 'Work Experience 2';
      }

      if (isset($request_data['work_exp3']) && $original_data['work_exp3'] != $request_data['work_exp3']) {
          $updated_fields['Work Experience 3'] = $request_data['work_exp3'];
          $changed_fields[] = 'Work Experience 3';
      }

      if (isset($request_data['emp_id1']) && $original_data['emp_id1'] != $request_data['emp_id1']) {
          $updated_fields['Employee Id 1'] = $request_data['emp_id1'];
          $changed_fields[] = 'Employee Id 1';
      }

      if (isset($request_data['emp_id2']) && $original_data['emp_id2'] != $request_data['emp_id2']) {
          $updated_fields['Employee Id 2'] = $request_data['emp_id2'];
          $changed_fields[] = 'Employee Id 2';
      }

      if (isset($request_data['emp_id3']) && $original_data['emp_id3'] != $request_data['emp_id3']) {
          $updated_fields['Employee Id 3'] = $request_data['emp_id3'];
          $changed_fields[] = 'Employee Id 3';
      }

      if (isset($request_data['gross_duration_month1']) && $original_data['gross_duration_month1'] != $request_data['gross_duration_month1']) {
          $updated_fields['Gross Duration Month 1'] = $request_data['gross_duration_month1'];
          $changed_fields[] = 'Gross Duration Month 1';
      }

      if (isset($request_data['gross_duration_month2']) && $original_data['gross_duration_month2'] != $request_data['gross_duration_month2']) {
          $updated_fields['Gross Duration Month 2'] = $request_data['gross_duration_month2'];
          $changed_fields[] = 'Gross Duration Month 2';
      }

      if (isset($request_data['gross_duration_month3']) && $original_data['gross_duration_month3'] != $request_data['gross_duration_month3']) {
          $updated_fields['Gross Duration Month 3'] = $request_data['gross_duration_month3'];
          $changed_fields[] = 'Gross Duration Month 3';
      }

      if (isset($request_data['gross_duration_year1']) && $original_data['gross_duration_year1'] != $request_data['gross_duration_year1']) {
          $updated_fields['Gross Duration Year 1'] = $request_data['gross_duration_year1'];
          $changed_fields[] = 'Gross Duration Year 1';
      }

      if (isset($request_data['gross_duration_year2']) && $original_data['gross_duration_year2'] != $request_data['gross_duration_year2']) {
          $updated_fields['Gross Duration Year 2'] = $request_data['gross_duration_year2'];
          $changed_fields[] = 'Gross Duration Year 2';
      }

      if (isset($request_data['gross_duration_year3']) && $original_data['gross_duration_year3'] != $request_data['gross_duration_year3']) {
          $updated_fields['Gross Duration Year 3'] = $request_data['gross_duration_year3'];
          $changed_fields[] = 'Gross Duration Year 3';
      }

      // Example for faculty_photo, check if file uploaded
      if (!empty($_FILES['faculty_photo']['name'])) {
          // Handle file upload logic here...
          $updated_fields['Faculty Photo'] = $_FILES['faculty_photo']['name'];
          $changed_fields[] = 'Faculty Photo';
      }

      // Check if pan_photo has been changed
      if (!empty($_FILES['pan_photo']['name'])) {
          // Handle file upload logic here...
          $updated_fields['PAN Photo'] = $_FILES['pan_photo']['name'];
          $changed_fields[] = 'PAN Photo';
      }

      // Compare languages
      $arr_lang = $this->input->post('language');
      $languages = implode(',', $arr_lang);
      if ($original_data['languages'] != $languages) {
          $updated_fields['Language(s) proficient with'] = $languages;
          $changed_fields[] = 'Language(s) proficient with';
      }
    }
    return $changed_fields; 
    // echo "<pre>"; print_r($updated_fields); exit;
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

        $qry = $this->db->query("SELECT faculty_id, faculty_number, faculty_use_status, faculty_code,languages, salutation, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration_month1, gross_duration_year1, work_exp2, emp_id2, gross_duration_month2, gross_duration_year2, work_exp3, emp_id3, gross_duration_month3, gross_duration_year3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
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

  public function change_status()
    {
        $status       = $_POST['status'];
        $reason       = $_POST['reason'];
        $faculty_id   = $_POST['faculty_id'];
        $institute_id = $_POST['institute_id'];
         
        $arr_response           = [];    
        $arr_response['status'] = 'error';

        
        $batch_qry = $this->db->query("SELECT batch_code
            FROM agency_batch 
            WHERE is_deleted = 0
            AND (first_faculty = ".$faculty_id." OR sec_faculty = ".$faculty_id." OR additional_first_faculty = ".$faculty_id." OR additional_sec_faculty = ".$faculty_id.") 
            AND CURDATE() BETWEEN batch_from_date AND batch_to_date");

        $batch_data = $batch_qry->result_array();
        if (count($batch_data) < 1 ) 
        {
            $this->db->select('dra_inst_registration.inst_name');
            $this->db->join('dra_inst_registration','faculty_master.institute_id=dra_inst_registration.id');
            $faculty_data = $this->master_model->getRecords('faculty_master', array('faculty_id' => $faculty_id));
            
            $upd_arr =  array(
                            'faculty_use_status' => $status,
                            'updated_by_id'      => $this->UserData['id'],
                            'updated_on'         => date('Y-m-d H:i:s')
                        );
            
            $where = array('faculty_id' => $faculty_id);
            $updated_id = $this->master_model->updateRecord('faculty_master',$upd_arr,$where);
            
            if ($updated_id) 
            {
                $agency_id = $this->session->userdata['dra_institute']['dra_inst_registration_id'];
                $qry       = $this->db->query("SELECT institute_name FROM dra_accerdited_master a WHERE dra_inst_registration_id = ".$agency_id);
                $res = $qry->result_array();

                $logStatus = 'In Use';
                if ($status == 'NotInUse') {
                    $logStatus = 'Not In Use';
                }

                $logs_arr = array(
                          'faculty_id'          => $faculty_id,
                          'action_taken'        => $logStatus.' faculty - by '.$res[0]['institute_name'],
                          'reason'              => $reason,
                          'ip_address'          => $this->input->ip_address(),
                          'created_by_id'       => $agency_id,
                          'created_on'          => date('Y-m-d H:i:s')  
                        );

                $inserted_id = $this->master_model->insertRecord('faculty_status_logs',$logs_arr);   
                
                $arr_response['status'] = 'success';
            }   
        }
        else
        {
            $arr_response['status'] = 'faculty_error';
        }
        
        echo json_encode($arr_response); exit;
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

        if(@$_POST['action'] == 'add')
        {
      //$this->db->where('institute_id',@$_POST['institute_id']);
            $this->db->where('pan_no',@$_POST['pan_no']);
            // $this->db->where(" (status = 'Active' OR institute_id = '".$_POST['institute_id']."') ");
            // $this->db->where(" (institute_id = '".$_POST['institute_id']."') ");
            $this->db->where('is_deleted','0');
      $pan_no_data = $this->master_model->getRecords('faculty_master');
      //echo count($pan_no_data);
    } else {
      $this->db->where('faculty_id',@$_POST['faculty_id']);  
      $faculty_code_data = $this->master_model->getRecords('faculty_master');
      // print_r($faculty_code_data); exit;
      $this->db->where('pan_no',@$_POST['pan_no']);
      // $this->db->where('faculty_id <>',@$_POST['faculty_id']);
      $this->db->where('faculty_code <>',$faculty_code_data[0]['faculty_code']);
      // $this->db->where(" (status = 'Active' OR institute_id = '".$_POST['institute_id']."') ");
      // $this->db->where(" (institute_id = '".$_POST['institute_id']."') ");
      $this->db->where('is_deleted','0');
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
