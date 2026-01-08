<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Career_admin extends CI_Controller
{
  public $UserID;
  public function __construct()
  {
    parent::__construct();
    if (!$this->session->userdata('career_admin')) {
      //$this->session->set_flashdata('error','Invalid');
      redirect('careers_admin/admin/Login');
    } else {
      $UserData = $this->session->userdata('career_admin');

      /*if($UserData['admin_user_type'] == 'Cheker')
			{
				redirect('careers_admin/admin/Login');
			}*/
    }
    $this->UserData = $this->session->userdata('career_admin');
    $this->UserID = $this->UserData['id'];
    $this->load->model('UserModel');
    $this->load->model('Master_model');
    $this->load->helper('master_helper');
    $this->load->helper('pagination_helper');
    $this->load->library('pagination');
    $this->load->helper('general_helper');
    $this->load->library('email');
    $this->load->model('Emailsending');
    $this->load->library('upload');
    $this->load->library('m_pdf');
  }

  public function career_admin_list()
  {
    $data['result']  = array();
    $data['action']  = array();
    $data['links']   = '';
    $data['success'] = '';
    $field           = '';
    $value           = '';
    $sortkey         = '';
    $sortval         = '';
    $per_page        = '';
    $limit           = 10;
    $start           = 0;
    $data_arr        = array();
    $edu_arr         = array();

    $this->db->select('id,position');
    $career_position         = $this->master_model->getRecords("careers_position_master");
    $data['career_position'] = $career_position;

    $this->session->set_userdata('field', '');
    $this->session->set_userdata('value', '');
    $this->session->set_userdata('per_page', '');
    $this->session->set_userdata('sortkey', '');
    $this->session->set_userdata('sortval', '');

    //$data = $this->getUserInfo();
    $act_stat = 1;
    $res_arr  = array();
    $data["breadcrumb"] = '<ol class="breadcrumb"> 
		<li><a href="' . base_url() . 'careers_admin/admin/Career_admin/career_admin_list">
		<i class="fa fa-home"></i> Candidate List</a></li>
		<li class="active"><a href="' . base_url() . 'careers_admin/admin/Career_admin/career_admin_list">Career Admin</a></li>
		</ol>';

    $this->db->select('m.id,c.position_id,c.careers_id,m.course_name,c.firstname,c.middlename,c.lastname,c.email,c.mobile,c.alternate_mobile,c.submit_date,mm.position');
    $this->db->join('careers_edu_qualification q', 'q.careers_id=c.careers_id', 'LEFT');
    $this->db->join('careers_course_mst m', 'm.course_code=q.course_code', 'LEFT');
    $this->db->join('careers_position_master mm', 'mm.id=c.position_id', 'LEFT');
    $this->db->where('active_status=', $act_stat);
    $this->db->order_by('c.careers_id', 'ASC');
    $res_arr = $this->master_model->getRecords("careers_registration c");

    $this->db->select('c.position_id,c.careers_id,c.firstname,c.middlename,c.lastname,c.email,c.mobile,c.alternate_mobile,c.submit_date,m.position');
    $this->db->join('careers_position_master m', 'm.id=c.position_id', 'LEFT');
    $this->db->where('active_status=', $act_stat);
    $this->db->order_by('c.careers_id', 'DESC');
    $sql = $this->master_model->getRecords("careers_registration c");
    $i = 0;
    foreach ($sql as $rec) {
      $data_arr[$i]['careers_id']       = $rec['careers_id'];
      $data_arr[$i]['firstname']        = $rec['firstname'];
      $data_arr[$i]['middlename']       = $rec['middlename'];
      $data_arr[$i]['lastname']         = $rec['lastname'];
      $data_arr[$i]['email']            = $rec['email'];
      $data_arr[$i]['mobile']           = $rec['mobile'];
      $data_arr[$i]['alternate_mobile'] = $rec['alternate_mobile'];
      $data_arr[$i]['alternate_mobile'] = $rec['alternate_mobile'];
      $data_arr[$i]['position_id']      = $rec['position_id'];
      $data_arr[$i]['submit_date']      = $rec['submit_date'];
      $data_arr[$i]['position']         = $rec['position'];

      $this->db->where('careers_id', $rec['careers_id']);
      $careers_edu_qualification = $this->master_model->getRecords("careers_edu_qualification");
      $edu_arr                   = array();
      foreach ($careers_edu_qualification as $res) {
        $this->db->where('course_code', $res['course_code']);
        $careers_course_mst = $this->master_model->getRecords("careers_course_mst");
        foreach ($careers_course_mst as $val) {
          $edu_arr[]                 = $val['course_name'];
          // $data_arr[$i]['education'] = implode(',',$edu_arr); 
        }
      }
      $i++;
    }

    $data['reuest_list'] = $data_arr;
    //print_r($data_arr);die;

    if (isset($_GET['submit'])) {
      $position  = $this->input->get('position');
      $from_date = $this->input->get('from_date');
      $to_date   = $this->input->get('to_date');

      $this->db->select('id,position');
      $career_position = $this->master_model->getRecords("careers_position_master");

      $this->db->select('m.id,c.position_id,c.careers_id,c.firstname,c.middlename,c.lastname,c.email,c.mobile,c.alternate_mobile,c.scannedphoto,c.scannedsignaturephoto,c.submit_date,m.position');
      $this->db->join('careers_position_master m', 'm.id=c.position_id', 'LEFT');

      if ($from_date != "" && $to_date != "" && $position != "") {
        $this->db->where('DATE(c.createdon) >=', $from_date);
        $this->db->where('DATE(c.createdon) <=', $to_date);
        $this->db->where('c.position_id', $position);
      } else if ($from_date != "" && $to_date != "") {
        $this->db->where('DATE(c.createdon) >=', $from_date);
        $this->db->where('DATE(c.createdon) <=', $to_date);
      } else if ($from_date != "" && $position != "") {
        $this->db->where('DATE(c.createdon)', $from_date);
        $this->db->where('c.position_id', $position);
      } else if ($to_date != "" && $position != "") {
        $this->db->where('DATE(c.createdon)', $to_date);
        $this->db->where('c.position_id', $position);
      } else if ($from_date != "") {
        $this->db->where('DATE(c.createdon) >=', $from_date);
        $this->db->where('DATE(c.createdon) <=', $from_date);
      } else if ($to_date != "") {
        $this->db->where('DATE(c.createdon) >=', $to_date);
        $this->db->where('DATE(c.createdon) <=', $to_date);
      } else if ($position != "") {
        $this->db->where('c.position_id', $position);
      }

      $this->db->where('c.active_status', '1');
      $this->db->order_by('c.careers_id', 'DESC');
      $sql = $this->master_model->getRecords("careers_registration c");

      //echo $this->db->last_query();

      $data['reuest_list'] = $sql;


      $this->load->view('careers_admin/admin/career_admin_list', $data);
    } else {
      $this->load->view('careers_admin/admin/career_admin_list', $data);
    }
  }

  public function pdf()
  {  //echo "string";die;
    $act_stat    = 1;
    $id          = $this->uri->segment(5);
    $position_id = $this->uri->segment(6);

    $this->db->where('careers_id', $id);
    $rst = $this->master_model->getRecords("careers_registration");

    $this->db->select('m.id,m.course_name,c.careers_id,c.specialisation,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class');
    $this->db->join('careers_registration c', 'c.careers_id=q.careers_id', 'LEFT');
    $this->db->join('careers_course_mst m', 'm.course_code=q.course_code', 'LEFT');
    $this->db->where('c.careers_id', $id);
    $qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");


    $this->db->select('c.careers_id,e.careers_id as employ_id,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date');
    $this->db->join('careers_registration c', 'c.careers_id=e.careers_id', 'LEFT');
    $this->db->where('c.careers_id', $id);
    $emp_hist_arr = $this->master_model->getRecords('careers_employment_hist e');

    /*print_r($rst);
    echo "<br><br>";
    print_r($qualification_arr);
    echo "<br><br>";
    print_r($emp_hist_arr);die;*/

    $html = '<style>
   .wikitable tbody tr th, table.jquery-tablesorter thead tr th.headerSort, .header-cell {
   background: #009999;
   color: white;
   font-family: "Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace;
   font-weight: bold;
   font-size: 100pt;
   }
   .wikitable, table.jquery-tablesorter {
   box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
   }
   .tabela, .wikitable {
   border: 1px solid #A2A9B1;
   border-collapse: collapse; 
   }
   .tabela tbody tr td, .wikitable tbody tr td {
   padding: 5px 10px 5px 10px;
   border: 1px solid #A2A9B1;
   border-collapse: collapse;
   }
   .config-value 
   {
   font-family: "Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace;
   font-size:13pt; 
   background: white; 
   font-weight: bold;
   }
    .column 
    {
      float: right;
    }
    img { text-align: right }

   </style>';


    $html .= '<h1 style="text-align:center">APPLICATION</h1>';

    $html .= '<div class="table-responsive ">
              <table class="table table-bordered wikitable tabela" style="overflow: wrap">
              <tbody>';

    if ($position_id == 1) {
      $html .= '<br><br>
            <tr>                    
              <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
              <td width="50%">Junior Executive</td>
            </tr> ';
    }
    if ($position_id == 2) {
      $html .= '<br><br>
            <tr>                    
              <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
              <td width="50%">Assistant Director (IT) </td>
            </tr> ';
    }
    if ($position_id == 3) {
      $html .= '<br><br>
            <tr>                    
              <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
              <td width="50%">Assistant Director (Accounts)</td>
            </tr> ';
    }
    if ($position_id == 4) {
      $html .= '<br><br>
            <tr>                    
              <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
              <td width="50%">Director (Training) on Contract </td>
            </tr> ';
    }
    $html .= '<tr>      
                <td width="50%"><strong>PHOTO:</strong></td>              
                <td width="50%"><img  class="column" width="70px" height="70px" align="right" src="' . base_url() . 'uploads/photograph/' . $rst[0]['scannedphoto'] . '"id="thumb" /><br><br><br></td>
            </tr>';
    $html .= '<br><br>
            <tr>                    
              <td width="50%"><strong>NAME:</strong></td>
              <td width="50%">' . $rst[0]["sel_namesub"] . $rst[0]["firstname"] . ' ' . $rst[0]['middlename'] . ' ' . $rst[0]['lastname'] . '</td>
            </tr> ';
    $html .= '<br><br>
            <tr>                    
              <td width="50%"><strong>FATHERS/HUSBANDS NAME:</strong></td>
              <td width="50%">' . $rst[0]["father_husband_name"] . '</td>
            </tr> ';
    $html .= '<tr>                    
              <td width="50%"><strong>DATE OF BIRTH:</strong></td>
              <td width="50%">' . $rst[0]['dateofbirth'] . '</td>
            </tr>';
    $html .= '<tr>                    
              <td width="50%"><strong>GENDER:</strong></td>
              <td width="50%">' . $rst[0]['gender'] . '</td>
            </tr>';
    $html .= '<tr>                    
              <td width="50%"><strong>EMAIL ID:</strong></td>
              <td width="50%">' . $rst[0]['email'] . '</td>
            </tr>';
    $html .= '<tr>                    
              <td width="50%"><strong>MARITAL STATUS:</strong></td>
              <td width="50%">' . $rst[0]['marital_status'] . '</td>
            </tr>';
    $html .= '<tr>                    
              <td width="50%"><strong>MOBILE:</strong></td>
              <td width="50%">' . $rst[0]['mobile'] . '</td>
            </tr>';
    $html .= '<tr>                    
              <td width="50%"><strong>ALTERNATE MOBILE:</strong></td>
              <td width="50%">' . $rst[0]['alternate_mobile'] . '</td>
            </tr>';
    $html .= '<tr>                    
              <td width="50%"><strong>PAN NO:</strong></td>
              <td width="50%">' . $rst[0]['pan_no'] . '</td>
            </tr>';

    $html .= '<tr>                    
                <td width="50%"><strong>AADHAR CARD NO:</strong></td>
                <td width="50%">' . $rst[0]['aadhar_card_no'] . '</td>
              </tr>';


    $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>COMMUNICATION ADDRESS</strong></h4></td><td></td></tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>ADDRESS:</strong></td>
                <td width="50%">' . $rst[0]['addressline1'] . ' ,' . $rst[0]['addressline2'] . ' ,' . $rst[0]['addressline3'] . ' ,' . $rst[0]['addressline4'] . '<br>' . $rst[0]['district'] . ' ,' . $rst[0]['city'] . '<br>' . $rst[0]['state'] . '<br>' . $rst[0]['pincode'] . '</td>
            </tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>CONTACT NUMBER:</strong></td>
                <td width="50%">' . $rst[0]['contact_number'] . '</td>
              </tr>';


    $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>PERMANENT ADDRESS</strong></h4></td><td></td></tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>ADDRESS:</strong></td>
                <td width="50%">' . $rst[0]['addressline1_pr'] . ' ,' . $rst[0]['addressline2_pr'] . ' ,' . $rst[0]['addressline3_pr'] . ' ,' . $rst[0]['addressline4_pr'] . '<br>' . $rst[0]['district_pr'] . ' ,' . $rst[0]['city_pr'] . '<br>' . $rst[0]['state_pr'] . '<br>' . $rst[0]['pincode_pr'] . '</td>
            </tr>';

    $html .= '<tr>                    
                <td width="50%"><strong>CONTACT NUMBER:</strong></td>
                <td width="50%">' . $rst[0]['contact_number_pr'] . '</td>
              </tr>';

    if (!empty($rst[0]['exam_center'])) {
      $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>EXAM CENTER</strong></h4></td><td></td></tr>';
      $html .= '<tr>                    
              <td width="50%"><strong>EXAM CENTER:</strong></td>
              <td width="50%">' . $rst[0]['exam_center'] . '</td>
            </tr>';
    }

    $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION(ESSENTIAL)</strong></h4></td><td></td></tr>';
    foreach ($rst as $row) {
      if ($position_id == 1 || $position_id == 2 || $position_id == 3) {
        $html .= '<tr>                    
                    <td width="50%"><strong>NAME OF COURSE:</strong></td>
                    <div style="word-break:break-all;">
                    <td width="50%">' . $row['ess_course_name'] . '</td>
                    </div>
                </tr>';
      }
      if ($position_id == 4) {
        $html .= '<tr>                    
                    <td width="50%"><strong>NAME OF COURSE(POST GRADUATE):</strong></td>
                    <div style="word-break:break-all;">
                    <td width="50%">' . $row['ess_course_name'] . '</td>
                    </div>
                </tr>';
      }
      if ($position_id == 6) {
        $html .= '<tr>                    
                        <td width="50%"><strong>SUBJECT:</strong></td>
                        <div style="word-break:break-all;">
                        <td width="50%">' . $row['deputy_subject'] . '</td>
                        </div>
                  </tr>';
      }
      if ($position_id == 1 || $position_id == 2) {
        $html .= '<tr>                    
                        <td width="50%"><strong>SUBJECT:</strong></td>
                        <div style="word-break:break-all;">
                        <td width="50%">' . $row['ess_subject'] . '</td>
                        </div>
                  </tr>';
      }
      if ($position_id == 1 || $position_id == 2 || $position_id == 4) {
        $html .= '<tr>                    
                        <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                        <td width="50%">' . $row['ess_college_name'] . '</td>
                  </tr>';
      }

      if ($position_id == 3) {
        $html .= '<tr>                    
                      <td width="50%"><strong>INSTITUTE NAME:</strong></td>
                      <td width="50%">' . $row['ess_college_name'] . '</td>
                  </tr>';
      }

      if ($position_id == 1 || $position_id == 2 || $position_id == 4) {
        $html .= '<tr>                    
                      <td width="50%"><strong>UNIVERSITY:</strong></td>
                      <td width="50%">' . $row['ess_university'] . '</td>
                  </tr>';
      }
      $html .= '<tr>                    
                          <td width="50%"><strong>PERIOD:</strong></td>
                          <td width="50%">' . $row['ess_from_date'] . " to " . $row['ess_to_date'] . '</td>
                        </tr>';

      if ($position_id == 2 || $position_id == 3) {
        $html .= '<tr>                    
                      <td width="50%"><strong>PERCENTAGE:</strong></td>
                      <td width="50%">' . $row['ess_grade_marks'] . '</td>
                  </tr>';
      }
      if ($position_id == 1 || $position_id == 4) {
        $html .= '<tr>                    
                      <td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
                      <td width="50%">' . $row['ess_grade_marks'] . '</td>
                    </tr>';
      }

      if ($position_id == 1 || $position_id == 2 || $position_id == 4) {
        $html .= '<tr>                    
                        <td width="50%"><strong>CLASS:</strong></td>
                        <td width="50%">' . $row['ess_class'] . '</td>
                  </tr>';
      }
    }

    if ($position_id == 4) {
      $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td><td></td></tr>';

      $html .= '<tr>                    
                    <td width="50%"><strong>CAIIB:</strong></td>
                    <td width="50%">CAIIB</td>
              </tr>';

      $html .= '<tr>                    
                    <td width="50%"><strong>YEAR OF PASSING:</strong></td>
                    <td width="50%">' . $row['year_of_passing'] . '</td>
              </tr>';

      $html .= '<tr>                    
                  <td width="50%"><strong>MEMBERSHIP NUMBER:</strong></td>
                  <td width="50%">' . $row['membership_number'] . '</td>
              </tr>';
    }

    $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION(DESIRABLE)</strong></h4></td><td></td></tr>';
    foreach ($qualification_arr as $row) {
      $html .= '<tr>                    
                    <td width="50%"><strong>NAME OF COURSE:</strong></td>
                    <div style="word-break:break-all;">
                    <td width="50%">' . $row['course_name'] . '</td>
                    </div>
                </tr>';


      if ($position_id == 6) {
        $html .= '<tr>                    
                        <td width="50%"><strong>SPECIALISATION:</strong></td>
                        <div style="word-break:break-all;">
                        <td width="50%">' . $row['specialisation'] . '</td>
                        </div>
                    </tr>';
      }

      if ($position_id == 1 || $position_id == 2 || $position_id == 4) {
        $html .= '<tr>                    
                      <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                      <td width="50%">' . $row['college_name'] . '</td>
                  </tr>';
      }
      if ($position_id == 3) {
        $html .= '<tr>                    
                      <td width="50%"><strong>INSTITUTE NAME:</strong></td>
                      <td width="50%">' . $row['college_name'] . '</td>
                  </tr>';
      }

      if ($position_id == 1 || $position_id == 2 || $position_id == 4) {
        $html .= '<tr>                    
                      <td width="50%"><strong>UNIVERSITY:</strong></td>
                      <td width="50%">' . $row['university'] . '</td>
                    </tr>';
      }
      $html .= '<tr>                    
                    <td width="50%"><strong>PERIOD:</strong></td>
                    <td width="50%">' . $row['from_date'] . " to " . $row['to_date'] . '</td>
                </tr>';

      if ($position_id == 1 || $position_id == 4) {
        $html .= '<tr>                    
                      <td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
                      <td width="50%">' . $row['grade_marks'] . '</td>
                  </tr>';
      } else {
        $html .= '<tr>                    
                    <td width="50%"><strong>PERCENTAGE:</strong></td>
                    <td width="50%">' . $row['grade_marks'] . '</td>
                  </tr>';
      }
      if ($position_id == 1 || $position_id == 2 || $position_id == 4) {
        $html .= '<tr>                    
                    <td width="50%"><strong>CLASS:</strong></td>
                    <td width="50%">' . $row['class'] . '</td>
                  </tr>';
      }
    }

    $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY</strong></h4></td><td></td></tr>';
    foreach ($emp_hist_arr as $rest) {
      $html .= '<tr>                    
                <td width="50%"><strong>NAME OF THE ORGANIZATION:</strong></td>
                <td width="50%">' . $rest['organization'] . '</td>
              </tr>';
      $html .= '<tr>                    
                <td width="50%"><strong>DESIGNATION:</strong></td>
                <td width="50%">' . $rest['designation'] . '</td>
              </tr>';
      $html .= '<tr>                    
                <td width="50%"><strong>RESPOSIBILITIES:</strong></td>
                <td width="50%">' . $rest['responsibilities'] . '</td>
              </tr>';
      $html .= '<tr>                    
                <td width="50%"><strong>PERIOD:</strong></td>
                <td width="50%">' . $rest['job_from_date'] . " to " . $rest['job_to_date'] . '</td>
              </tr>';
    }

    $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>LANGUAGES, EXTRACURRICULAR, ACHIEVEMENTS</strong></h4></td><td></td></tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>LANGUAGES KNOWN 1:</strong></td>
                <td width="50%">' . $rst[0]['languages_known'] . '</td>
              </tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>LANGUAGES OPTIONS 1:</strong></td>
                <td width="50%">' . $rst[0]['languages_option'] . '</td>
              </tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>LANGUAGES KNOWN 2:</strong></td>
                <td width="50%">' . $rst[0]['languages_known1'] . '</td>
              </tr>';

    $html .= '<tr>                    
                <td width="50%"><strong>LANGUAGES OPTIONS 2:</strong></td>
                <td width="50%">' . $rst[0]['languages_option1'] . '</td>
              </tr>';

    $html .= '<tr>                    
                <td width="50%"><strong>LANGUAGES KNOWN 3:</strong></td>
                <td width="50%">' . $rst[0]['languages_known2'] . '</td>
              </tr>';

    $html .= '<tr>                    
                <td width="50%"><strong>LANGUAGES OPTIONS 3:</strong></td>
                <td width="50%">' . $rst[0]['languages_option2'] . '</td>
              </tr>';

    $html .= '<tr>                    
                <td width="50%"><strong>EXTRACURRICULAR:</strong></td>
                <td width="50%">' . $rst[0]['extracurricular'] . '</td>
              </tr>';


    $html .= '<tr>                    
                <td width="50%"><strong>HOBBIES:</strong></td>
                <td width="50%">' . $rst[0]['hobbies'] . '</td>
              </tr>';

    $html .= '<tr>                    
                <td width="50%"><strong>ACHIEVEMENTS:</strong></td>
                <td width="50%">' . $rst[0]['achievements'] . '</td>
              </tr>';

    if ($rst[0]['declaration1'] == 'Yes') {
      $html .= '<tr>                    
                <td width="50%"><strong>DECLARATION: Have your ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification:</strong></td>
                <td width="50%">' . $rst[0]['declaration1'] . '</td>
              </tr>';
      $html .= '<tr>                    
                <td width="50%"><strong>DECLARATION NOTE:</strong></td>
                <td width="50%">' . $rst[0]['declaration_note'] . '</td>
              </tr>';
    } else {
      $html .= '<tr>                    
                <td width="50%"><strong>DECLARATION: Have your ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification:</strong></td>
                <td width="50%">' . $rst[0]['declaration1'] . '</td>
              </tr>';
    }

    $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE ONE</strong></h4></td><td></td></tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>NAME:</strong></td>
                <td width="50%">' . $rst[0]['refname_one'] . '</td>
              </tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
                <td width="50%">' . $rst[0]['refaddressline_one'] . '</td>
              </tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>EMAIL ID:</strong></td>
                <td width="50%">' . $rst[0]['refemail_one'] . '</td>
              </tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>MOBILE:</strong></td>
                <td width="50%">' . $rst[0]['refmobile_one'] . '</td>
              </tr>';
    $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE TWO</strong></h4></td><td></td></tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>NAME:</strong></td>
                <td width="50%">' . $rst[0]['refname_two'] . '</td>
              </tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
                <td width="50%">' . $rst[0]['refaddressline_two'] . '</td>
              </tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>EMAIL ID:</strong></td>
                <td width="50%">' . $rst[0]['refemail_two'] . '</td>
              </tr>';
    $html .= '<tr>                    
                <td width="50%"><strong>MOBILE:</strong></td>
                <td width="50%">' . $rst[0]['refmobile_two'] . '</td>
              </tr>';

    $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>OTHER INFORMATION</strong></h4></td><td></td></tr> ';
    $html .= '<tr>                    
                <td width="50%" style="word-break:break-all; display: inline-block;"><strong>ANY OTHER INFORMATION THAT THE CANDIDATE WOULD LIKE TO ADD:</strong></td>
                <td width="50%">
                          <div style="word-break:break-all;">
                            ' . $rst[0]['comment'] . '
                          </div>
                </td>
              </tr>';

    $html .= '<tr>                    
              <td width="50%"><strong>DECLARATION: I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated without any notice.</strong></td>
              <td width="50%">' . $rst[0]['declaration2'] . '</td>
          </tr>';

    $html .= '<br><tr><td style="color: #66d9ff"><h4><strong>PLACE AND DATE</strong></h4></td><td></td></tr> ';
    $html .= '<tr>                    
                  <td width="50%"><strong>PLACE:</strong></td>
                  <td width="50%">' . $rst[0]['place'] . '</td>
                </tr>';
    $html .= '<tr>                    
                  <td width="50%"><strong>DATE:</strong></td>
                  <td width="50%">' . $rst[0]['submit_date'] . '</td>
              </tr>';
    $html .= '<tr>                    
                    <td width="50%"><strong>SIGNATURE:</strong></td>
                    <td><img width="70px" height="70px" src="' . base_url() . 'uploads/scansignature/' . $rst[0]['scannedsignaturephoto'] . '" id="thumb" />
                    </td>
            </tr>';
    $html .= '</tbody>
                  </table>
                 <div id="reason_form" style="display: none">';


    $pdf = $this->m_pdf->load();

    $pdfFilePath = $rst[0]['firstname'] . '_' . $rst[0]['lastname'] . '_' . $rst[0]['submit_date'] . ".pdf";

    $pdf->WriteHTML($html);
    $pdf->SetCompression(false);
    $pdf->SetDisplayMode('real');
    $pdf->SetDisplayMode('default');
    $pdf->SetAutoPageBreak(true);

    $path = $pdf->Output('uploads/Careers_Data/' . $pdfFilePath, "D");
    $path = $pdf->Output('uploads/Careers_Data/' . $pdfFilePath, "F");
  }



  //check deatils of request
  public function request_detail()
  {
    ini_set('display_errors', 1);
    $id = $this->uri->segment(5);

    if ($id) {
      $id = base64_decode($id);
      $this->db->where('careers_id', $id);
      $rst = $this->master_model->getRecords("careers_registration");
      // echo "<pre>"; print_r($rst); exit;
      $position_id = isset($rst[0]['position_id']) ? $rst[0]['position_id'] : '';

      // Master tbl array
      $this->db->select('careers_id,firstname,middlename,lastname,father_husband_name,dateofbirth,gender,email,marital_status,mobile,scannedphoto,scannedsignaturephoto,alternate_mobile,pan_no,aadhar_card_no,addressline1,addressline2,addressline3,addressline4,district,city,state,pincode,exam_center,refname_one,refaddressline_one,refemail_one,refmobile_one,refname_two,refaddressline_two,refemail_two,refmobile_two,comment,place,submit_date,active_status,specialisation,educational_qualification,CAIIB_qualification,bank_education,retired_working,designation,it_subjects,general_subjects,other_subjects,uploadcv,ess_college_name,exp_in_functional_area,exp_in_bank');
      $this->db->where('careers_id', $id);
      $careers_registration_arr = $this->master_model->getRecords("careers_registration");
      // echo "<pre>"; print_r($careers_registration_arr); exit;

      if ($position_id != 13) {
        // Professional certification
        // Bellow code commented from gaurav bacause careers_prof_cert table is not present on databse
        /*$this->db->select('c.careers_id,p.careers_id as prof_cert_id,p.pro_course_name,p.pro_college_name,p.pro_university,p.pro_from_date,p.pro_to_date,p.pro_grade_marks,p.pro_class');
        $this->db->join('careers_prof_cert p','p.careers_id=c.careers_id','LEFT');
        $this->db->where('c.careers_id',$id);
        $professional_cert_arr = $this->master_model->getRecords("careers_registration c");*/

        // echo "<pre>"; print_r($professional_cert_arr); exit;


        // Education array
        $this->db->select('m.id,m.course_name,c.careers_id,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class');
        $this->db->join('careers_registration c', 'c.careers_id=q.careers_id', 'LEFT');
        $this->db->join('careers_course_mst m', 'm.course_code=q.course_code', 'LEFT');
        $this->db->where('c.careers_id', $id);
        $qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");


        // Job Arr
        // Bellow code commented from gaurav bacause careers_prof_cert table is not present on databse
        /*$this->db->select('c.careers_id,j.careers_id,j.pro_course_name,j.pro_college_name,j.pro_university,j.pro_from_date,j.pro_to_date,j.pro_grade_marks,j.pro_grade_marks,j.pro_class');
        $this->db->join('careers_registration c','c.careers_id=j.careers_id','LEFT');
        $this->db->where('c.careers_id',$id);
        $job_arr = $this->master_model->getRecords("careers_prof_cert j");*/

        //Employment history
        $this->db->select('c.careers_id,e.careers_id as employ_id,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date');
        $this->db->join('careers_registration c', 'c.careers_id=e.careers_id', 'LEFT');
        $this->db->where('c.careers_id', $id);
        $emp_hist_arr = $this->master_model->getRecords('careers_employment_hist e');


        $data['qualification_arr']        = $qualification_arr;
        $data['rst']                      = $rst;
        $data['emp_hist_arr']             = $emp_hist_arr;
        // $data['job_arr']                  = $job_arr;
        // $data['professional_cert_arr']    = $professional_cert_arr;  
      } else {
        $data['qualification_arr']        = array();
        $data['rst']                      = array();
        $data['emp_hist_arr']             = array();
        // $data['job_arr']                  = array();
        // $data['professional_cert_arr']    = array();
      }

      $data['careers_registration_arr'] = $careers_registration_arr;
      $data['position_id']              = $position_id;

      $this->load->view('careers_admin/admin/career_admin_view_list', $data);
    }
  }

  public function zip_file()
  {

    $mask = '*.*';
    $path  = glob('./uploads/Careers_Data/' . $mask);


    $todaydirectory = "./uploads/Careers_Data/";
    $zipname        = "career_pdf_data.zip";
    $zip            = new ZipArchive; //inbuilt
    $zip->open($todaydirectory . $zipname, ZipArchive::CREATE);


    foreach ($path as $k => $v) {
      $photo_to_add   = $v;
      $new_photo      = substr($photo_to_add, strrpos($photo_to_add, '/') + 1);
      $photo_zip_flg  = $zip->addFile($photo_to_add, $new_photo);
    }
    $zip->close();
    redirect(base_url() . 'careers_admin/admin/Careers_position/career_position_list');
  }
}
