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

    public function index(){
        
        $data['middle_content'] = 'faculty_list';
        $this->load->view('iibfdra/Version_2/admin/faculty/faculty_list',$data);
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

        $select = $this->db->query("SELECT faculty_id, faculty_number, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration1, work_exp2, emp_id2, gross_duration2, work_exp3, emp_id3, gross_duration3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, inst_name as institute_name, faculty_master.status 
            FROM faculty_master LEFT JOIN dra_inst_registration ON dra_inst_registration.id = institute_id
            WHERE faculty_master.is_deleted = 0");

        ## Total number of records with filtering
        $records = $select->result_array();
        $total_records = count($records);

        //echo $this->db->last_query(); die;


        ## Total number of records without filtering
        $select2     = "SELECT faculty_id, faculty_number, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration1, work_exp2, emp_id2, gross_duration2, work_exp3, emp_id3, gross_duration3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, inst_name as institute_name, faculty_master.status 
            FROM faculty_master LEFT JOIN dra_inst_registration ON institute_id = dra_inst_registration.id
            WHERE faculty_master.is_deleted = 0"
            .$searchQuery;

        $select3 = $this->db->query($select2);

        //echo $this->db->last_query();

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
                $status='<span class="label label-success w80 custom_disp_label"  data-href="'.base_url("faculty/active_inactive/".base64_encode($faculty['faculty_id'])).'">Active</span>';
            }
            else if($faculty['status']=="Inactive"){ 
                //onclick="return change_status(this,event,\''.$status_alert.'\', \''.$active.'\')"
                $status='<span class="label label-danger w80 custom_disp_label"   data-href="'.base_url("faculty/active_inactive/".base64_encode($faculty['faculty_id'])).'">Inactive</span>';
            }
            else{
                //onclick="return change_status(this,event,\''.$status_alert.'\', \''.$active.'\')"
                $status='<span class="label label-warning w80 custom_disp_label"   data-href="'.base_url("faculty/active_inactive/".base64_encode($faculty['faculty_id'])).'">In Review</span>';
            }

            $url  = '<div class="text-center"><a class="btn-link" href="'.base_url("iibfdra/Version_2/admin/faculty_master/faculty_view/".base64_encode($faculty['faculty_id'])).'" title="Edit" ><i class="fa fa-pencil"></i> </a></div>';
            /* '&nbsp;&nbsp;&nbsp;<a class="btn-link" href="'.base_url("iibfdra/Version_2/admin/faculty_master/faculty_edit/".base64_encode($faculty['faculty_id'])).'" title="Edit" ><i class="fa fa-pencil"></i></a>'; */

        
            $data[] = array(
                "sr"=>$sr, 
                "faculty_id"=>$faculty['faculty_id'],
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

    public function faculty_add(){ exit;
        
        if(isset($_POST) && count($_POST) > 0)
        { 
            $this->form_validation->set_rules('institute_id', 'Institute', 'required');
            $this->form_validation->set_rules('salutation', 'Salutation', 'required');
            $this->form_validation->set_rules('faculty_name', 'Faculty Name', 'required');
            //$this->form_validation->set_rules('faculty_photo', 'Faculty Photo', 'required');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'required');
            $this->form_validation->set_rules('academic_qualification', 'Academic Qualification', 'required');
            //print_r($_FILES); //die;
            //print_r($_POST); die;
            if($this->form_validation->run() == TRUE)
            {
               //print_r($_POST);
               //print_r($_FILES); die;
                $institute_id = trim($this->input->post('institute_id'));
                $faculty_number     = trim($this->input->post('faculty_number_hidden'));
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
                $gross_duration   = $this->input->post('gross_duration');
                
                $work_exp_iibf   = trim($this->input->post('work_exp_iibf'));
                $DRA_training_faculty_exp   = trim($this->input->post('DRA_training_faculty_exp'));
                $start_date   = trim($this->input->post('start_date'));
                $end_date   = trim($this->input->post('end_date'));
                $session_interested_in   = trim($this->input->post('session_interested_in'));
                $softskills_banking_exp   = trim($this->input->post('softskills_banking_exp'));
                $training_activities_exp   = trim($this->input->post('training_activities_exp'));

                if (!empty($_FILES['faculty_photo']['name']))
                {
                    $config['upload_path'] = 'uploads/faculty_photo/'; 
                    $config['allowed_types'] = 'gif|jpg|png|jpeg'; 

                    $this->load->library('upload', $config); 
                    $this->upload->initialize($config); 

                    if($this->upload->do_upload('faculty_photo')){ 
                        // Uploaded file data 
                        $fileData = $this->upload->data(); 
                        $uploadData['file_name'] = $fileData['file_name']; 
                        $uploadData['uploaded_on'] = date("Y-m-d H:i:s"); 
                        $uploaded_id = 1;
                        $faculty_photo      = $uploadData['file_name'];
                        //echo '</br>'.$fileData['file_name'];
                     
                    }else{ 
                        //echo $this->upload->display_errors(); 
                        $errorUploadType .= $_FILES['faculty_photo']['name'].' | ';  
                        $uploaded_id = 0;
                    } 
                }

                //print_r($_FILES['pan_photo']['name']);

                if (!empty($_FILES['pan_photo']['name']))
                {
                    $config['upload_path'] = 'uploads/pan_photo/'; 
                    $config['allowed_types'] = 'gif|jpg|png|jpeg'; 

                    $this->load->library('upload', $config); 
                    $this->upload->initialize($config); 

                    if($this->upload->do_upload('pan_photo')){ 
                        // Uploaded file data 
                        $fileData = $this->upload->data(); 
                        $uploadData['file_name'] = $fileData['file_name']; 
                        $uploadData['uploaded_on'] = date("Y-m-d H:i:s"); 
                        $uploaded_id = 1;
                        $pan_photo      = $uploadData['file_name'];
                        //echo '</br>'.$fileData['file_name'];
                     
                    }else{ 
                        //echo $this->upload->display_errors(); 
                        $errorUploadType .= $_FILES['pan_photo']['name'].' | ';  
                        $uploaded_id = 0;
                    } 
                }

                if(count($work_exp) > 0){
                    $work_exp1 = isset($work_exp[0])?$work_exp[0]:'';
                    $work_exp2 = isset($work_exp[1])?$work_exp[1]:'';
                    $work_exp3 = isset($work_exp[2])?$work_exp[2]:'';
                }

                if(count($emp_id) > 0){
                    $emp_id1 = isset($emp_id[0])?$emp_id[0]:'';
                    $emp_id2 = isset($emp_id[1])?$emp_id[1]:'';
                    $emp_id3 = isset($emp_id[2])?$emp_id[2]:'';
                }

                if(count($gross_duration) > 0){
                    $gross_duration1 = isset($gross_duration[0])?$gross_duration[0]:'';
                    $gross_duration2 = isset($gross_duration[1])?$gross_duration[1]:'';
                    $gross_duration3 = isset($gross_duration[2])?$gross_duration[2]:'';
                }
                
                $arr_insert = array(
                    'institute_id'   => $institute_id,
                    'faculty_number' => $faculty_number,
                    'salutation'     => $salutation,
                    'faculty_name'   => $faculty_name,
                    'faculty_photo'  => $faculty_photo, 
                    'pan_no'         => $pan_no,
                    'pan_photo'      => $pan_photo, 
                    'dob'            => $dob,
                    'base_location'  => $base_location,
                    'academic_qualification'=> $academic_qualification,
                    'personal_qualification'=> $personal_qualification,
                    'work_exp1'      => $work_exp1,
                    'work_exp2'      => $work_exp2,
                    'work_exp3'      => $work_exp3,
                    'emp_id1'        => $emp_id1,
                    'emp_id2'       =>  $emp_id2,
                    'emp_id3'       =>  $emp_id3,
                    'gross_duration1'=> $gross_duration1,
                    'gross_duration2'=>  $gross_duration2,
                    'gross_duration3'=>  $gross_duration3,
                    'work_exp_iibf' => $work_exp_iibf,
                    'DRA_training_faculty_exp' => $DRA_training_faculty_exp,
                    'start_date'   => $start_date,
                    'end_date'   => $end_date,
                    'session_interested_in' => $session_interested_in,
                    'softskills_banking_exp' => $softskills_banking_exp,
                    'training_activities_exp' => $training_activities_exp,
                    'status'        => 'In Review',
                    'created_by_id' =>$this->UserData['id'],
                    'created_on'    => date('Y-m-d H:i:s')
                );
                //print_r($arr_insert); die;
                $inserted_id = $this->master_model->insertRecord('faculty_master',$arr_insert,true);

                //echo $this->db->last_query();die;
                //echo $inserted_id; die;
                                
                if($inserted_id > 0)
                {              
                    // $logs_data = array(

                    //             'action_taken'        => 'Add',
                    //             'module_name'         => $this->module_title,
                    //             'logs_description'    => serialize($arr_insert),
                    //             'logs_previous_data'  => serialize($arr_insert),
                    //             'action_performed_by' =>$this->user_id,  
                    //             'ip_address'          => $this->ip,
                    //             'domain_id'           => $this->domain_id, 
                    //             'created_on'          =>  date('Y-m-d H:i:s')  
                    //         );
                    // create_log();
                   //Activity Log
                   $this->session->set_flashdata('success_message',$this->module_title." Added Successfully.");
                    redirect(base_url('iibfdra/Version_2/admin/faculty_master'));
                }

                else
                {
                    $this->session->set_flashdata('error_message'," Something Went Wrong While Adding The ".ucfirst($this->module_title).".");
                }
                //redirect(base_url('iibfdra/Version_2/faculty'));
            }  
         
        }

        $inst_qry = $this->db->query("SELECT dra_inst_registration_id, institute_name FROM dra_accerdited_master WHERE dra_inst_registration_id != 0"); 

        $data['institute_data'] = $inst_qry->result_array();

        $qry = $this->db->query("SELECT faculty_number 
            FROM faculty_master ORDER BY faculty_id DESC 
            LIMIT 0,1"); //WHERE is_deleted = 0 

        $records = $qry->result_array();
        //print_r($records);
        if(count($records)>0){
            $faculty_number = $records[0]['faculty_number']+1;
        }
        else{
            $faculty_number = '1';
        }
        if($faculty_number <= 9){
            $data['faculty_number'] = '000'.$faculty_number;
        }
        else{
            $data['faculty_number'] = '00'.$faculty_number;
        }
        //die;
        $data['faculty_number_hidden'] = $faculty_number;

        $data['institute_id'] = $this->session->userdata['dra_institute']['dra_inst_registration_id'];
        $data['action'] = 'add';

        $this->load->view('iibfdra/Version_2/admin/faculty/faculty_add',$data);
    }

     public function faculty_edit($faculty_id){ exit;

        $faculty_id = base64_decode($faculty_id);

        $qry = $this->db->query("SELECT faculty_id, institute_id, faculty_number, salutation, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration1, work_exp2, emp_id2, gross_duration2, work_exp3, emp_id3, gross_duration3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND faculty_id = ".$faculty_id);

        $data['faculty_data'] = $records = $qry->result_array();
        //$data['institute_id'] = $this->session->userdata['dra_institute']['dra_inst_registration_id'];

        if(isset($_POST) && count($_POST) > 0)
        { 
            //print_r($_POST); die;
            $this->form_validation->set_rules('salutation', 'Salutation', 'required');
            $this->form_validation->set_rules('faculty_name', 'Faculty Name', 'required');
            //$this->form_validation->set_rules('faculty_photo', 'Faculty Photo', 'required');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'required');
            $this->form_validation->set_rules('academic_qualification', 'Academic Qualification', 'required');
            
            if($this->form_validation->run() == TRUE)
            {
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
                $gross_duration   = $this->input->post('gross_duration');
                
                $work_exp_iibf   = trim($this->input->post('work_exp_iibf'));
                $DRA_training_faculty_exp   = trim($this->input->post('DRA_training_faculty_exp'));
                $start_date   = trim($this->input->post('start_date'));
                $end_date   = trim($this->input->post('end_date'));
                $session_interested_in   = trim($this->input->post('session_interested_in'));
                $softskills_banking_exp   = trim($this->input->post('softskills_banking_exp'));
                $training_activities_exp   = trim($this->input->post('training_activities_exp'));

                if (!empty($_FILES['faculty_photo']['name']))
                {
                    $config['upload_path'] = 'uploads/faculty_photo/'; 
                    $config['allowed_types'] = 'gif|jpg|png|jpeg'; 

                    $this->load->library('upload', $config); 
                    $this->upload->initialize($config); 

                    if($this->upload->do_upload('faculty_photo')){ 
                        // Uploaded file data 
                        $fileData = $this->upload->data(); 
                        $uploadData['file_name'] = $fileData['file_name']; 
                        $uploadData['uploaded_on'] = date("Y-m-d H:i:s"); 
                        $uploaded_id = 1;
                        $faculty_photo      = $uploadData['file_name'];
                        //echo '</br>'.$fileData['file_name'];
                     
                    }else{ 
                        //echo $this->upload->display_errors(); 
                        $errorUploadType .= $_FILES['faculty_photo']['name'].' | ';  
                        $uploaded_id = 0;
                    } 
                }
                else{
                    $faculty_photo = trim($this->input->post('old_faculty_photo_image'));
                }

                if (!empty($_FILES['pan_photo']['name']))
                {
                    $config['upload_path'] = 'uploads/pan_photo/'; 
                    $config['allowed_types'] = 'gif|jpg|png|jpeg'; 

                    $this->load->library('upload', $config); 
                    $this->upload->initialize($config); 

                    if($this->upload->do_upload('pan_photo')){ 
                        // Uploaded file data 
                        $fileData = $this->upload->data(); 
                        $uploadData['file_name'] = $fileData['file_name']; 
                        $uploadData['uploaded_on'] = date("Y-m-d H:i:s"); 
                        $uploaded_id = 1;
                        $pan_photo      = $uploadData['file_name'];
                        //echo '</br>'.$fileData['file_name'];
                     
                    }else{ 
                        //echo $this->upload->display_errors(); 
                        $errorUploadType .= $_FILES['pan_photo']['name'].' | ';  
                        $uploaded_id = 0;
                    } 
                }
                else{
                    $pan_photo = trim($this->input->post('old_pan_photo_image'));
                }

                if(count($work_exp) > 0){
                    $work_exp1 = isset($work_exp[0])?$work_exp[0]:'';
                    $work_exp2 = isset($work_exp[1])?$work_exp[1]:'';
                    $work_exp3 = isset($work_exp[2])?$work_exp[2]:'';
                }

                if(count($emp_id) > 0){
                    $emp_id1 = isset($emp_id[0])?$emp_id[0]:'';
                    $emp_id2 = isset($emp_id[1])?$emp_id[1]:'';
                    $emp_id3 = isset($emp_id[2])?$emp_id[2]:'';
                }

                if(count($gross_duration) > 0){
                    $gross_duration1 = isset($gross_duration[0])?$gross_duration[0]:'';
                    $gross_duration2 = isset($gross_duration[1])?$gross_duration[1]:'';
                    $gross_duration3 = isset($gross_duration[2])?$gross_duration[2]:'';
                }
                
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
                    'academic_qualification'=> $academic_qualification,
                    'personal_qualification'=> $personal_qualification,
                    'work_exp1'      => $work_exp1,
                    'work_exp2'      => $work_exp2,
                    'work_exp3'      => $work_exp3,
                    'emp_id1'        => $emp_id1,
                    'emp_id2'       =>  $emp_id2,
                    'emp_id3'       =>  $emp_id3,
                    'gross_duration1'=> $gross_duration1,
                    'gross_duration2'=>  $gross_duration2,
                    'gross_duration3'=>  $gross_duration3,
                    'work_exp_iibf' => $work_exp_iibf,
                    'DRA_training_faculty_exp' => $DRA_training_faculty_exp,
                    'start_date  ' => $start_date,
                    'end_date  ' => $end_date,
                    'session_interested_in' => $session_interested_in,
                    'softskills_banking_exp' => $softskills_banking_exp,
                    'training_activities_exp' => $training_activities_exp,
                    'updated_by_id' =>$this->session->userdata['dra_institute']['dra_inst_registration_id'],
                    'updated_on'    => date('Y-m-d H:i:s')
                );
                $upd_where = array('faculty_id' => $faculty_id);
                //print_r($arr_insert); die;
                $updated_id = $this->master_model->updateRecord('faculty_master',$arr_upd,$upd_where);

                //echo $this->db->last_query(); die;
                                
                if($updated_id > 0)
                {              
                    /*$logs_data = array(

                                'action_taken'        => 'Add',
                                'module_name'         => $this->module_title,
                                'logs_description'    => serialize($arr_insert),
                                'logs_previous_data'  => serialize($arr_insert),
                                'action_performed_by' =>$this->user_id,  
                                'ip_address'          => $this->ip,
                                'domain_id'           => $this->domain_id, 
                                'created_on'          =>  date('Y-m-d H:i:s')  
                            );*/

                    // create_log();
                   /*Activity Log*/
                   $this->session->set_flashdata('success_message',$this->module_title." Updated Successfully.");
                    redirect(base_url('iibfdra/Version_2/admin/faculty_master'));
                }

                else
                {
                    $this->session->set_flashdata('error_message'," Something Went Wrong While Adding The ".ucfirst($this->module_title).".");
                }
            }  
          
        }
        
        $data['action'] = 'edit';
        //$data['middle_content'] = 'faculty_add';
        $this->load->view('iibfdra/Version_2/admin/faculty/faculty_add',$data);
    }

    public function faculty_view($faculty_id){

        $faculty_id = base64_decode($faculty_id);

        $qry = $this->db->query("SELECT faculty_id, institute_id, faculty_number, salutation, faculty_name, faculty_photo, dob, pan_no, pan_photo, base_location, academic_qualification, personal_qualification, work_exp1, emp_id1, gross_duration1, work_exp2, emp_id2, gross_duration2, work_exp3, emp_id3, gross_duration3, work_exp_iibf, DRA_training_faculty_exp, start_date, end_date, session_interested_in, softskills_banking_exp, training_activities_exp, status 
            FROM faculty_master 
            WHERE is_deleted = 0
            AND faculty_id = ".$faculty_id);

        $data['faculty_data'] = $faculty_data = $qry->result_array();
       

        $logqry = $this->db->query("SELECT faculty_id, status, reason, created_on
            FROM faculty_status_logs
            WHERE faculty_id = ".$faculty_id."
            AND   status = 'Inactive'");

        $data['log_data'] = $records1 = $logqry->result_array();

        $data['action'] = 'view';
        $this->load->view('iibfdra/Version_2/admin/faculty/faculty_view',$data);
    }

    public function change_status(){
        $status = $_POST['status'];
        $reason = $_POST['reason'];
        $faculty_id = $_POST['faculty_id'];
        //echo $status;
        if($status == 'Active'){
            //echo $_POST['pan_no_hidden'];
            $this->db->where('pan_no',$_POST['pan_no_hidden']);
            //$this->db->where('institute_id',@$_POST['institute_id']);
            $this->db->where('status','Active');
            $this->db->where('is_deleted','0');
            $pan_no_data = $this->master_model->getRecords('faculty_master');

            $this->db->select('dra_inst_registration.inst_name');
            $this->db->join('dra_inst_registration','faculty_master.institute_id=dra_inst_registration.id');
            $faculty_data = $this->master_model->getRecords('faculty_master', array('faculty_id' => $faculty_id));

            //print_r($pan_no_data);

            if(count($pan_no_data) > 0){
                echo '2---'.$faculty_data[0]['inst_name'];
                exit(0);
            }
        }
        
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
    
        echo $updated_id.'---';
    }

    public function check_pan_no(){
        
        if(@$_POST['action'] == 'add'){
            $this->db->where('pan_no',@$_POST['pan_no']);
            //$this->db->where('institute_id',@$_POST['institute_id']);
            $this->db->where('status','Active');
            $this->db->where('is_deleted','0');
            $pan_no_data = $this->master_model->getRecords('faculty_master');
            //echo count($pan_no_data);
        }
        else{
            $this->db->where('pan_no',@$_POST['pan_no']);
            $this->db->where('faculty_id <>',@$_POST['faculty_id']);
            //$this->db->where('institute_id',@$_POST['institute_id']);
            $this->db->where('status','Active');
            $this->db->where('is_deleted','0');
            $pan_no_data = $this->master_model->getRecords('faculty_master');
            //echo count($pan_no_data);
        }
        
        if(count($pan_no_data) == 0){
            $output = array(
                'success' => true   
            );
            
            echo json_encode($output);
        }


    }

    public function check_faculty_status()
    {
        $faculty_id = $_POST['prim_id'];
        $faculty_query = $this->db->query("SELECT id, batch_code
            FROM agency_batch 
            WHERE is_deleted = 0
            AND   (first_faculty = ".$faculty_id." OR sec_faculty = ".$faculty_id." OR additional_first_faculty = ".$faculty_id." OR additional_sec_faculty = ".$faculty_id.")
            GROUP BY id");

        $faculty_data = $faculty_query->result_array();

        $str1 = '';
        if(count($faculty_data)){
            foreach ($faculty_data as $key => $value) {
                $str1.= $value['batch_code'].',';
            }
            rtrim($str1,',');
            $str.=' Can not Inactive the Faculty as Faculty is Referred in '.$str1.' this Batch.';
        }
        
        echo $str;
    }
}
