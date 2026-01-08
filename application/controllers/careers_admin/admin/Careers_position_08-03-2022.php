<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Careers_position extends CI_Controller 
{  
  public $UserID;     
  public function __construct()
  {
    parent::__construct();
    if(!$this->session->userdata('career_admin')) 
    {
      redirect('careers_admin/admin/Login');
    }
    else
    {
      $UserData = $this->session->userdata('career_admin');
    }
    $this->UserData = $this->session->userdata('career_admin');
    $this->UserID   = $this->UserData['id'];
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
  
  public function career_position_list()
  { 
    $this->db->select('id,position');
    $career_position         = $this->master_model->getRecords("careers_position_master");
    $data['career_position'] = $career_position; 

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
    
    $this->session->set_userdata('field','');
    $this->session->set_userdata('value','');
    $this->session->set_userdata('per_page','');
    $this->session->set_userdata('sortkey','');
    $this->session->set_userdata('sortval','');
    
    //$data = $this->getUserInfo();
    $res_arr            = array();
    $data["breadcrumb"] = '<ol class="breadcrumb"> 
    <li><a href="'.base_url().'careers_admin/admin/Careers_position/career_position_list">
    <i class="fa fa-home"></i> Summary Report</a></li>
    <li class="active"><a href="'.base_url().'careers_admin/admin/Careers_position/career_position_list">Career Admin</a></li>
    </ol>';   
    
    
    $this->db->select('m.id,c.position_id,c.careers_id,c.firstname,c.middlename,c.lastname,c.email,c.mobile,c.alternate_mobile,c.scannedphoto,c.scannedsignaturephoto,c.submit_date,m.position');
    $this->db->join('careers_position_master m','m.id=c.position_id','LEFT');
    $this->db->where('c.active_status', '1');
    $sql = $this->master_model->getRecords("careers_registration c");
   // print_r($sql);die;
  
    $i=0;
    foreach($sql as $rec)
    { 
      $data_arr[$i]['careers_id']            = $rec['careers_id'];
      $data_arr[$i]['firstname']             = $rec['firstname'];
      $data_arr[$i]['middlename']            = $rec['middlename'];
      $data_arr[$i]['lastname']              = $rec['lastname'];
      $data_arr[$i]['email']                 = $rec['email'];
      $data_arr[$i]['mobile']                = $rec['mobile'];    
      $data_arr[$i]['alternate_mobile']      = $rec['alternate_mobile'];   
      $data_arr[$i]['scannedphoto']          = $rec['scannedphoto'];
      $data_arr[$i]['scannedsignaturephoto'] = $rec['scannedsignaturephoto']; 
      $data_arr[$i]['submit_date']           = $rec['submit_date'];
      $data_arr[$i]['position']              = $rec['position']; 
      $data_arr[$i]['position_id']           = $rec['position_id']; 
      
      $this->db->where('careers_id', $rec['careers_id']);
      $careers_edu_qualification = $this->master_model->getRecords("careers_edu_qualification");
      $edu_arr                   = array();

      foreach($careers_edu_qualification as $res)
      {
        $this->db->where('course_code', $res['course_code']);
        $careers_course_mst = $this->master_model->getRecords("careers_course_mst");
        foreach($careers_course_mst as $val)
        {
          $edu_arr[]                 = $val['course_name'];
          $data_arr[$i]['education'] = implode(',',$edu_arr); 
        }
      }
      $i++;
    }

    $data['reuest_list'] = $data_arr; 
   // print_r($data);die;
   
    if(isset($_GET['submit']))
    {
      $position  = $this->input->get('position');
      
      $from_date = $this->input->get('from_date');
      $to_date   = $this->input->get('to_date');

      
      $this->db->select('id,position');
      $career_position = $this->master_model->getRecords("careers_position_master");
      
      $this->db->select('m.id,c.position_id,c.careers_id,c.firstname,c.middlename,c.lastname,c.email,c.mobile,c.alternate_mobile,c.scannedphoto,c.scannedsignaturephoto,c.submit_date,m.position');
      $this->db->join('careers_position_master m','m.id=c.position_id','LEFT');
      $this->db->where('c.position_id',$position);
      $this->db->where('DATE(c.createdon) >=', $from_date);
      $this->db->where('DATE(c.createdon) <=', $to_date);
 	  $this->db->where('c.active_status', '1');
      $sql = $this->master_model->getRecords("careers_registration c");

      $data['reuest_list'] = $sql;
     $this->load->view('careers_admin/admin/careers_position_list',$data);
    }

    else
    {
      $this->load->view('careers_admin/admin/careers_position_list',$data);
    }
  }

  public function pdf()
  { 
    ini_set("memory_limit", "-1");

    $id = $this->uri->segment(5);
  
    $this->db->where('careers_id',$id);
    $rst = $this->master_model->getRecords("careers_registration");

    $this->db->select('m.id,m.position,c.position_id,c.careers_id,c.firstname,c.middlename,c.lastname,c.email,c.submit_date,c.mobile,c.scannedphoto,c.alternate_mobile,c.scannedsignaturephoto');
    $this->db->join('careers_position_master m','m.id=c.position_id','LEFT');
    //$this->db->join('careers_edu_qualification q','q.careers_id=c.careers_id','LEFT');
    //$this->db->join('careers_course_mst m','m.course_code=q.course_code','LEFT');
    $this->db->order_by('c.careers_id','ASC');
	 $this->db->where('c.active_status', '1');
    $res_arr = $this->master_model->getRecords("careers_registration c");
    //print_r($res_arr);die;

    
    $html = '<h1 style="text-align:center">CANDIDATE LIST</h1>';
    $html.= '<div class="table-responsive ">
            <table width="900" id="listitems2" class="table table-bordered table-striped dataTables-example" style="overflow: wrap" border="1" style="border-collapse: collapse">
            <tbody>';
    $html.= '<thead>
                <tr>
                  <th id="srNo" style="text-align: center">Sr.</th>
                  <th id="id" style="text-align: center">Candidate Name</th>
                  <th id="email" style="text-align: center">Email</th> 
                  <th id="contact" style="text-align: center">Contact</th>
                  <th id="career_pos" style="text-align: center">Career Position</th>  
                  <th id="photo" style="text-align: center">Photo</th>   
                  <th id="sign" style="text-align: center">Signature</th>
                  <th id="action" style="text-align: center">Submit Date</th>
                </tr>
                </thead>';
        $html.= '<tbody class="no-bd-y" id="list2">';       
        $k = 1;
        if(count($res_arr) > 0)
        {
          foreach($res_arr as $res)
          {
            $html.= '<tr><td>'.$k.' </td>';
            $html.= '<td>'.$res['firstname']." ".$res['middlename']." ".$res['lastname'].' </td>';
            $html.= '<td>'.$res['email'].' </td>';
            $html.= '<td>'.$res['mobile']." , ".$res['alternate_mobile'].' </td>';
            $html.= '<td>'.$res['position'].' </td>';
            $html.= '<td><img width="70px" height="70px" src="'.base_url().'uploads/photograph/'.$res['scannedphoto'].'"></img></td>';
            $html.= '<td><img width="70px" height="70px" src="'.base_url().'uploads/scansignature/'.$res['scannedsignaturephoto'].'"></img></td>';
            $html.= '<td>'.$res['submit_date'].' </td>';
            $k++; 
          }  
        }
    $html.= '</tbody>
            </table>
            <div id="reason_form" style="display: none">';                                                     

    $pdf         = $this->m_pdf->load();
    $pdfFilePath = $rst[0]['mobile'].".pdf";
    
    $pdf->WriteHTML($html);
    $pdf->SetCompression(false);
    $pdf->SetDisplayMode('real');
    $pdf->SetDisplayMode('default');
    
    $pdf->SetAutoPageBreak(true);

    
    $path = $pdf->Output($pdfFilePath, "D"); 

    $this->load->view('careers_admin/admin/pdf_view',$path);
  }

 /*public function exportData() 
  {
    $act_stat = 1;
    $p_id = $this->uri->segment(5);	
	
	  $csv_header = "Id,Position,First Name,Middle Name,Last Name,Father's/Husband's Name,Date of birth,Gender,Email,Marital Status,Mobile,Alternate Mobile,PAN No.,Aadhar Card No.,Address1,Address2,Address3,Address4,District,City,State,Pincode,Contact No.,Permanent Address1,Permanent Address2,Permanent Address3,Permanent Address4,District,City,State,Pincode,Contact No.,Exam center,Name of course(Essential),Subject,College Name and Address,University,Period from,Period to,Grade,Class,Name of course(Desirable),College Name and Address,University,Period from,Period to,Grade,Class,Organization,Designation,Responsibilities,Period From,Period To,Languages Known,Language option,Languages Known1,Language option1,Languages Known2,Language option2,Extracurricular,Hobbies,Achievements,Declaration,Referance Name,Address,Email,Mobile,Referance Name,Address,Email,Mobile,Comment,Declaration,Place,Date,Declaration Note";
	
	  if(isset($p_id)) 
    {
      $this->db->where('position_id',$p_id );
    }

    $this->db->select('c.careers_id,p.position,c.firstname,c.middlename,c.lastname,c.father_husband_name,c.dateofbirth,c.gender,c.email,c.marital_status,c.mobile,c.alternate_mobile,c.pan_no,c.aadhar_card_no,c.addressline1,c.addressline2,c.addressline3,c.addressline4,c.district,c.city,c.state,c.pincode,c.contact_number,c.addressline1_pr,c.addressline2_pr,c.addressline3_pr,c.addressline4_pr,c.district_pr,c.city_pr,c.state_pr,c.pincode_pr,c.contact_number_pr,c.exam_center,c.ess_course_name,c.ess_subject,c.ess_college_name,c.ess_university,c.ess_from_date,c.ess_to_date,c.ess_grade_marks,c.ess_class,m.course_name,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date,c.languages_known,c.languages_option,c.languages_known1,c.languages_option1,c.languages_known2,c.languages_option2,,c.extracurricular,c.hobbies,c.achievements,c.declaration1,c.refname_one,c.refaddressline_one,c.refemail_one,c.refmobile_one,c.refname_two,c.refaddressline_two,c.refemail_two,c.refmobile_two,c.comment,c.declaration2,c.place,c.submit_date,c.declaration_note');

    $this->db->join('careers_edu_qualification q','q.careers_id=c.careers_id','LEFT');
    $this->db->join('careers_course_mst m','m.course_code=q.course_code','LEFT');
    

    $this->db->join('careers_employment_hist e','e.careers_id=c.careers_id','LEFT');
    $this->db->join('careers_position_master p','p.id=c.position_id','LEFT');

    $this->db->where('c.active_status=', $act_stat);
     
    $sql = $this->master_model->getRecords("careers_registration c");
  
    $i=0;
    foreach($sql as $rec)
    { 
      $data_arr[$i]['careers_id']            = $rec['careers_id'];
      $data_arr[$i]['firstname']             = $rec['firstname'];
      $data_arr[$i]['middlename']            = $rec['middlename'];
      $data_arr[$i]['lastname']              = $rec['lastname'];
      $data_arr[$i]['email']                 = $rec['email'];
      $data_arr[$i]['mobile']                = $rec['mobile'];    
      $data_arr[$i]['alternate_mobile']      = $rec['alternate_mobile'];   
      $data_arr[$i]['submit_date']           = $rec['submit_date'];
      $data_arr[$i]['position']              = $rec['position']; 
      $data_arr[$i]['position']              = $rec['position']; 
      
      $this->db->where('careers_id', $rec['careers_id']);
      $careers_edu_qualification = $this->master_model->getRecords("careers_edu_qualification");
      $edu_arr                   = array();

      foreach($careers_edu_qualification as $res)
      {
        $this->db->where('course_code', $res['course_code']);
        $careers_course_mst = $this->master_model->getRecords("careers_course_mst");
        foreach($careers_course_mst as $val)
        {
          $edu_arr[]                 = $val['course_name'];
          $data_arr[$i]['education'] = implode(',',$edu_arr); 
        }
      }
        $this->db->where('careers_id', $rec['careers_id']);
        $this->db->select('organization,designation,responsibilities');
        $careers_employment_hist = $this->master_model->getRecords("careers_employment_hist");
       
        foreach($careers_employment_hist as $rst)
        {
          $careers_employment_hist_arr[]                 = $rst['organization'];
          $careers_employment_hist_arr[]                 = $rst['designation'];
          $careers_employment_hist_arr[]                 = $rst['responsibilities'];

          $data_arr[$i]['employment'] = implode(',',$careers_employment_hist_arr); 
        }
      $i++;
    }	
	  header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=\"candidate-list".".csv\"");
    header("Pragma: no-cache");
    header("Expires: 0");
    $handle = fopen('php://output', 'w');

    //headers
    	
    fwrite($handle, $csv_header);
	  fwrite($handle, "\n");
    
    foreach ($sql as $data) 
    {
      fputcsv($handle, $data);
    }
    fclose($handle);
    exit;
  }*/

  public function pdf_record()
  { 
    //$id = $this->uri->segment(5);
    $url_position_id = $this->uri->segment(6);
    if($url_position_id != '')
    {
			$position_id = $position = $this->uri->segment(6);
    }
    else
    {
      $position_id = $_GET['position']; 
      $position    = $_GET['position']; 
    }
  
    //$position  = $_GET['position']; 
    $from_date = $_GET['from_date'];
    $to_date   = $_GET['to_date'];
  
    $this->db->where('position_id',$position);
		if($from_date != "") { $this->db->where('DATE(createdon) >=', $from_date); }
    if($to_date != "") { $this->db->where('DATE(createdon) <=', $to_date); }
		$this->db->where('active_status', '1');
    $sql = $this->master_model->getRecords("careers_registration",'','careers_id');
		
		/* echo $this->db->last_query();
		echo '1'; exit; */
    foreach($sql as $rec)
    {
   
    //$position_id = $this->uri->segment(6);
    
    $this->db->where('careers_id',$rec['careers_id']);
		$this->db->where('active_status', '1');
    $rst = $this->master_model->getRecords("careers_registration");

    $this->db->select('m.id,m.course_name,c.careers_id,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class');
    $this->db->join('careers_registration c','c.careers_id=q.careers_id','LEFT');
    $this->db->join('careers_course_mst m','m.course_code=q.course_code','LEFT');
    $this->db->where('c.careers_id',$rec['careers_id']);
    $this->db->where('active_status', '1');
    $qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");
    

    $this->db->select('c.careers_id,e.careers_id as employ_id,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date');
    $this->db->join('careers_registration c','c.careers_id=e.careers_id','LEFT');
    $this->db->where('c.careers_id',$rec['careers_id']);
    $this->db->where('c.active_status', '1');
    $emp_hist_arr = $this->master_model->getRecords('careers_employment_hist e');

    $html= '<style>
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


    $html.= '<h1 style="text-align:center">APPLICATION</h1>';
    
    $html.= '<div class="table-responsive ">
              <table class="table table-bordered wikitable tabela" style="overflow: wrap">
              <tbody>';

    if($position_id == 1)
    {
      $html.= '<br><br>
              <tr>                    
                <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
                <td width="50%">Junior Executive</td>
              </tr> ';
    }
    if($position_id == 2)
    {
      $html.= '<br><br>
              <tr>                    
                <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
                <td width="50%">Assistant Director (IT) </td>
              </tr> ';
    }
    if($position_id == 3)
    {
      $html.= '<br><br>
              <tr>                    
                <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
                <td width="50%">Assistant Director (Accounts)</td>
              </tr> ';
    }
    if($position_id == 4)
    {
    $html.= '<br><br>
            <tr>                    
              <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
              <td width="50%">Director (Training) on Contract </td>
            </tr> ';
    }
    $html.= '<tr>      
                <td width="50%"><strong>PHOTO:</strong></td>              
                <td width="50%"><img  class="column" width="70px" height="70px" align="right" src="'.base_url().'uploads/photograph/'.$rst[0]['scannedphoto'].'"id="thumb" /><br><br><br></td>
            </tr>'; 
    $html.= '<br><br>
            <tr>                    
              <td width="50%"><strong>NAME:</strong></td>
              <td width="50%">'.$rst[0]["sel_namesub"].$rst[0]["firstname"].' '.$rst[0]['middlename'].' '.$rst[0]['lastname'].'</td>
            </tr> ';
    $html.= '<br><br>
            <tr>                    
              <td width="50%"><strong>FATHERS/HUSBANDS NAME:</strong></td>
              <td width="50%">'.$rst[0]["father_husband_name"].'</td>
            </tr> ';
    $html.= '<tr>                    
              <td width="50%"><strong>DATE OF BIRTH:</strong></td>
              <td width="50%">'.$rst[0]['dateofbirth'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>GENDER:</strong></td>
              <td width="50%">'.$rst[0]['gender'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>EMAIL ID:</strong></td>
              <td width="50%">'.$rst[0]['email'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>MARITAL STATUS:</strong></td>
              <td width="50%">'.$rst[0]['marital_status'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>MOBILE:</strong></td>
              <td width="50%">'.$rst[0]['mobile'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>ALTERNATE MOBILE:</strong></td>
              <td width="50%">'.$rst[0]['alternate_mobile'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>PAN NO:</strong></td>
              <td width="50%">'.$rst[0]['pan_no'].'</td>
            </tr>';

    $html.= '<tr>                    
                <td width="50%"><strong>AADHAR CARD NO:</strong></td>
                <td width="50%">'.$rst[0]['aadhar_card_no'].'</td>
              </tr>';

    $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>COMMUNICATION ADDRESS</strong></h4></td><td></td></tr>';                     
    $html.= '<tr>                    
                <td width="50%"><strong>ADDRESS:</strong></td>
                <td width="50%">'.$rst[0]['addressline1'].' ,'.$rst[0]['addressline2'].' ,'.$rst[0]['addressline3'].' ,'.$rst[0]['addressline4'].'<br>'.$rst[0]['district'].' ,'.$rst[0]['city'].'<br>'.$rst[0]['state'].'<br>'.$rst[0]['pincode'].'</td>
            </tr>';
    $html.= '<tr>                    
                <td width="50%"><strong>CONTACT NUMBER:</strong></td>
                <td width="50%">'.$rst[0]['contact_number'].'</td>
              </tr>';
    
    $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>PERMANENT ADDRESS</strong></h4></td><td></td></tr>';  
    $html.= '<tr>                    
                <td width="50%"><strong>ADDRESS:</strong></td>
                <td width="50%">'.$rst[0]['addressline1_pr'].' ,'.$rst[0]['addressline2_pr'].' ,'.$rst[0]['addressline3_pr'].' ,'.$rst[0]['addressline4_pr'].'<br>'.$rst[0]['district_pr'].' ,'.$rst[0]['city_pr'].'<br>'.$rst[0]['state_pr'].'<br>'.$rst[0]['pincode_pr'].'</td>
            </tr>';

    $html.= '<tr>                    
                <td width="50%"><strong>CONTACT NUMBER:</strong></td>
                <td width="50%">'.$rst[0]['contact_number_pr'].'</td>
            </tr>';

    if(!empty($rst[0]['exam_center']))
    {
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EXAM CENTER</strong></h4></td><td></td></tr>';
      $html.= '<tr>                    
              <td width="50%"><strong>EXAM CENTER:</strong></td>
              <td width="50%">'.$rst[0]['exam_center'].'</td>
            </tr>';
    }                    

    $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION(ESSENTIAL)</strong></h4></td><td></td></tr>';  
    foreach ($rst as $row) 
    {    
      if($position_id == 1 || $position_id == 2 || $position_id == 3)
      {
        $html.= '<tr>                    
                    <td width="50%"><strong>NAME OF COURSE:</strong></td>
                    <div style="word-break:break-all;">
                    <td width="50%">'.$row['ess_course_name'].'</td>
                    </div>
                </tr>';
      }
      if($position_id == 4)
      {
        $html.= '<tr>                    
                    <td width="50%"><strong>NAME OF COURSE(POST GRADUATE):</strong></td>
                    <div style="word-break:break-all;">
                    <td width="50%">'.$row['ess_course_name'].'</td>
                    </div>
                </tr>'; 
      }

      if($position_id == 1 || $position_id == 2)
      {
        $html.= '<tr>                    
                  <td width="50%"><strong>SUBJECT:</strong></td>
                  <div style="word-break:break-all;">
                    <td width="50%">'.$row['ess_subject'].'</td>
                  </div>
                </tr>';
      }
      if($position_id == 1 || $position_id == 2 || $position_id == 4)
      {
        $html.= '<tr>                    
                  <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                  <td width="50%">'.$row['ess_college_name'].'</td>
                </tr>';
      }

      if($position_id == 3)
      {
        $html.= '<tr>                    
                  <td width="50%"><strong>INSTITUTE NAME:</strong></td>
                  <td width="50%">'.$row['ess_college_name'].'</td>
                </tr>';
      }
      if($position_id == 1 || $position_id == 2 || $position_id == 4)
      {
        $html.= '<tr>                    
                  <td width="50%"><strong>UNIVERSITY:</strong></td>
                  <td width="50%">'.$row['ess_university'].'</td>
                </tr>';
      }

      $html.= '<tr>                    
                <td width="50%"><strong>PERIOD:</strong></td>
                <td width="50%">'.$row['ess_from_date']." to ".$row['ess_to_date'].'</td>
              </tr>';

      if($position_id == 2 || $position_id== 3)
      {
        $html.= '<tr>                    
                  <td width="50%"><strong>PERCENTAGE:</strong></td>
                  <td width="50%">'.$row['ess_grade_marks'].'</td>
                </tr>';
      }
      if($position_id == 1 || $position_id == 4)
      {
        $html.= '<tr>                    
                  <td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
                  <td width="50%">'.$row['ess_grade_marks'].'</td>
                </tr>';
      }
        
      if($position_id == 1 || $position_id == 2 || $position_id == 4)
      {
        $html.= '<tr>                    
                    <td width="50%"><strong>CLASS:</strong></td>
                    <td width="50%">'.$row['ess_class'].'</td>
                </tr>'; 
        }                  
    }

    if($position_id == 4)
    {
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td><td></td></tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>CAIIB:</strong></td>
                <td width="50%">CAIIB</td>
              </tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>YEAR OF PASSING:</strong></td>
                <td width="50%">'.$row['year_of_passing'].'</td>
              </tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>MEMBERSHIP NUMBER:</strong></td>
                <td width="50%">'.$row['membership_number'].'</td>
              </tr>';
    }

    $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION(DESIRABLE)</strong></h4></td><td></td></tr>';  
    foreach ($qualification_arr as $row) 
    {    
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME OF COURSE:</strong></td>
                  <div style="word-break:break-all;">
                  <td width="50%">'.$row['course_name'].'</td>
                  </div>
                </tr>';

        if($position_id == 1 || $position_id == 2 || $position_id == 4)
        {
          $html.= '<tr>                    
                    <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                    <td width="50%">'.$row['college_name'].'</td>
                  </tr>';
        }
        if($position_id == 3)  
        {
          $html.= '<tr>                    
                    <td width="50%"><strong>INSTITUTE NAME:</strong></td>
                    <td width="50%">'.$row['college_name'].'</td>
                  </tr>';
        }

        if($position_id == 1 || $position_id == 2 || $position_id == 4)
        {
          $html.= '<tr>                    
                    <td width="50%"><strong>UNIVERSITY:</strong></td>
                    <td width="50%">'.$row['university'].'</td>
                  </tr>';
        }
        $html.= '<tr>                    
                  <td width="50%"><strong>PERIOD:</strong></td>
                  <td width="50%">'.$row['from_date']." to ".$row['to_date'].'</td>
                </tr>';

        if($position_id == 1 || $position_id == 4)
        {
          $html.= '<tr>                    
                    <td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
                    <td width="50%">'.$row['grade_marks'].'</td>
                  </tr>';
        }
        else
        {
          $html.= '<tr>                    
                    <td width="50%"><strong>PERCENTAGE:</strong></td>
                    <td width="50%">'.$row['grade_marks'].'</td>
                  </tr>';
        }
        if($position_id == 1 || $position_id == 2 || $position_id == 4)
        {
          $html.= '<tr>                    
                    <td width="50%"><strong>CLASS:</strong></td>
                    <td width="50%">'.$row['class'].'</td>
                  </tr>';  
        }                  
    }

    $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY</strong></h4></td><td></td></tr>';
    foreach ($emp_hist_arr as $rest)
    {                    
      $html.= '<tr>                    
                <td width="50%"><strong>NAME OF THE ORGANIZATION:</strong></td>
                <td width="50%">'.$rest['organization'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>DESIGNATION:</strong></td>
                <td width="50%">'.$rest['designation'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>RESPOSIBILITIES:</strong></td>
                <td width="50%">'.$rest['responsibilities'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>PERIOD:</strong></td>
                <td width="50%">'.$rest['job_from_date']." to ".$rest['job_to_date'].'</td>
              </tr>'; 
    }    

    $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>LANGUAGES, EXTRACURRICULAR, ACHIEVEMENTS</strong></h4></td><td></td></tr>';                
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES KNOWN 1:</strong></td>
                <td width="50%">'.$rst[0]['languages_known'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES OPTIONS 1:</strong></td>
                <td width="50%">'.$rst[0]['languages_option'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES KNOWN 2:</strong></td>
                <td width="50%">'.$rst[0]['languages_known1'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES OPTIONS 2:</strong></td>
                <td width="50%">'.$rst[0]['languages_option1'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES KNOWN 3:</strong></td>
                <td width="50%">'.$rst[0]['languages_known2'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES OPTIONS 3:</strong></td>
                <td width="50%">'.$rst[0]['languages_option2'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>EXTRACURRICULAR:</strong></td>
                <td width="50%">'.$rst[0]['extracurricular'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>HOBBIES:</strong></td>
                <td width="50%">'.$rst[0]['hobbies'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>ACHIEVEMENTS:</strong></td>
                <td width="50%">'.$rst[0]['achievements'].'</td>
              </tr>'; 
    if($rst[0]['declaration1'] == 'Yes')
    {
      $html.= '<tr>                    
                  <td width="50%"><strong>DECLARATION: Have your ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification:</strong></td>
                  <td width="50%">'.$rst[0]['declaration1'].'</td>
                </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>DECLARATION NOTE:</strong></td>
                <td width="50%">'.$rst[0]['declaration_note'].'</td>
              </tr>';                    
    }
    else
    {
      $html.= '<tr>                    
                <td width="50%"><strong>DECLARATION: Have your ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification:</strong></td>
                <td width="50%">'.$rst[0]['declaration1'].'</td>
              </tr>'; 
    }
                      
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE ONE</strong></h4></td><td></td></tr>';                    
      $html.= '<tr>                    
                  <td width="50%"><strong>NAME:</strong></td>
                  <td width="50%">'.$rst[0]['refname_one'].'</td>
                </tr>';   
      $html.= '<tr>                    
                  <td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
                  <td width="50%">'.$rst[0]['refaddressline_one'].'</td>
                </tr>';
      $html.= '<tr>                    
                  <td width="50%"><strong>EMAIL ID:</strong></td>
                  <td width="50%">'.$rst[0]['refemail_one'].'</td>
                </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>MOBILE:</strong></td>
                <td width="50%">'.$rst[0]['refmobile_one'].'</td>
              </tr>';                                                         
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE TWO</strong></h4></td><td></td></tr>';  
      $html.= '<tr>                    
                <td width="50%"><strong>NAME:</strong></td>
                <td width="50%">'.$rst[0]['refname_two'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
                <td width="50%">'.$rst[0]['refaddressline_two'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>EMAIL ID:</strong></td>
                <td width="50%">'.$rst[0]['refemail_two'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>MOBILE:</strong></td>
                <td width="50%">'.$rst[0]['refmobile_two'].'</td>
              </tr>';

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>OTHER INFORMATION</strong></h4></td><td></td></tr> ';
      $html.= '<tr>                    
                  <td width="50%" style="word-break:break-all; display: inline-block;"><strong>ANY OTHER INFORMATION THAT THE CANDIDATE WOULD LIKE TO ADD:</strong></td>
                  <td width="50%">
                          <div style="word-break:break-all;">
                            '.$rst[0]['comment'].'
                          </div>
                  </td>
              </tr>';                 
      $html.= '<tr>                    
                    <td width="50%"><strong>DECLARATION: I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated without any notice.</strong></td>
                     <td width="50%">'.$rst[0]['declaration2'].'</td>
              </tr>';    
      
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>PLACE AND DATE</strong></h4></td><td></td></tr> ';
      $html.= '<tr>                    
                      <td width="50%"><strong>PLACE:</strong></td>
                      <td width="50%">'.$rst[0]['place'].'</td>
                  </tr>';
      $html.= '<tr>                    
                        <td width="50%"><strong>DATE:</strong></td>
                        <td width="50%">'.$rst[0]['submit_date'].'</td>
                      </tr>';  
      $html.= '<tr>                    
                    <td width="50%"><strong>SIGNATURE:</strong></td>
                    <td><img width="70px" height="70px" src="'.base_url().'uploads/scansignature/'.$rst[0]['scannedsignaturephoto'].'" id="thumb" />
                    </td>
            </tr>';                                                                                                                 
      $html.= '</tbody>
                  </table>
                 <div id="reason_form" style="display: none">';                                                     

      $pdf = $this->m_pdf->load();
    
      $pdfFilePath = $rst[0]['firstname'].'_'.$rst[0]['lastname'].'_'.$rst[0]['submit_date'].".pdf";
    
      $pdf->WriteHTML($html);
      $pdf->SetCompression(false);
      $pdf->SetDisplayMode('real');
      $pdf->SetDisplayMode('default');
      $pdf->SetAutoPageBreak(true);
      
      $path = $pdf->Output('uploads/Careers_Data/'.$pdfFilePath, "F"); 
    }
    redirect(base_url().'careers_admin/admin/Careers_position/career_position_list');
  }

  public function pdf_ceo()
  { 
  	
    $url_position_id = $this->uri->segment(6);
    if($url_position_id != '')
    {
      $position_id = $this->uri->segment(6);
    }
    else
    {
      $position_id = $_GET['position']; 
      $position    = $_GET['position']; 
    }
  
    $position  = $_GET['position']; 
    $from_date = $_GET['from_date'];
    $to_date   = $_GET['to_date'];
  
    $this->db->where('position_id',$position);
    $this->db->where('DATE(createdon) >=', $from_date);
    $this->db->where('DATE(createdon) <=', $to_date);
	 $this->db->where('active_status', '1');
    $sql = $this->master_model->getRecords("careers_registration",'','careers_id');
	//echo "1>".$this->db->last_query();
    //exit;
	foreach($sql as $rec)
    {
    	
    
    //$position_id = $this->uri->segment(6);
    
    $this->db->where('careers_id',$rec['careers_id']);
	 $this->db->where('active_status', '1');
    $rst = $this->master_model->getRecords("careers_registration");
//echo "2>".$this->db->last_query();
    $this->db->select('m.id,m.course_name,c.careers_id,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class');
    $this->db->join('careers_registration c','c.careers_id=q.careers_id','LEFT');
    $this->db->join('careers_course_mst m','m.course_code=q.course_code','LEFT');
    $this->db->where('c.careers_id',$rec['careers_id']);
    $this->db->where('c.active_status', '1');
    $qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");
    //echo "3>".$this->db->last_query();




    $this->db->select('c.careers_id,e.careers_id as employ_id,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date,e.experience_as_principal,e.experience_as_faculty');
    $this->db->join('careers_registration c','c.careers_id=e.careers_id','LEFT');
    $this->db->where('c.careers_id',$rec['careers_id']);
    $this->db->where('c.active_status', '1');
    $emp_hist_arr = $this->master_model->getRecords('careers_employment_hist e');



    $html= '<style>
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

    $html.= '<h1 style="text-align:center">APPLICATION</h1>';
    
    $html.= '<div class="table-responsive ">
              <table class="table table-bordered wikitable tabela" style="overflow: wrap">
              <tbody>';

    $html.= '<br><br>
            <tr>                    
              <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
              <td width="50%">Chief Executive Officer</td>
            </tr> ';
    
    $html.= '<tr>      
              <td width="50%"><strong>PHOTO:</strong></td>              
              <td width="50%"><img  class="column" width="70px" height="70px" align="right" src="'.base_url().'uploads/photograph/'.$rst[0]['scannedphoto'].'"id="thumb" /><br><br><br></td>
            </tr>'; 
    $html.= '<br><br>
            <tr>                    
              <td width="50%"><strong>NAME:</strong></td>
              <td width="50%">'.$rst[0]["sel_namesub"].$rst[0]["firstname"].' '.$rst[0]['middlename'].' '.$rst[0]['lastname'].'</td>
            </tr> ';
    $html.= '<br><br>
            <tr>                    
              <td width="50%"><strong>FATHERS/HUSBANDS NAME:</strong></td>
              <td width="50%">'.$rst[0]["father_husband_name"].'</td>
            </tr> ';
    $html.= '<tr>                    
              <td width="50%"><strong>DATE OF BIRTH:</strong></td>
              <td width="50%">'.$rst[0]['dateofbirth'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>GENDER:</strong></td>
              <td width="50%">'.$rst[0]['gender'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>EMAIL ID:</strong></td>
              <td width="50%">'.$rst[0]['email'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>MARITAL STATUS:</strong></td>
              <td width="50%">'.$rst[0]['marital_status'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>MOBILE:</strong></td>
              <td width="50%">'.$rst[0]['mobile'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>ALTERNATE MOBILE:</strong></td>
              <td width="50%">'.$rst[0]['alternate_mobile'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>PAN NO:</strong></td>
              <td width="50%">'.$rst[0]['pan_no'].'</td>
            </tr>';
    $html.= '<tr>                    
              <td width="50%"><strong>AADHAR CARD NO:</strong></td>
              <td width="50%">'.$rst[0]['aadhar_card_no'].'</td>
            </tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES KNOWN 1:</strong></td>
                <td width="50%">'.$rst[0]['languages_known'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES OPTIONS 1:</strong></td>
                <td width="50%">'.$rst[0]['languages_option'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES KNOWN 2:</strong></td>
                <td width="50%">'.$rst[0]['languages_known1'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES OPTIONS 2:</strong></td>
                <td width="50%">'.$rst[0]['languages_option1'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES KNOWN 3:</strong></td>
                <td width="50%">'.$rst[0]['languages_known2'].'</td>
              </tr>'; 
      $html.= '<tr>                    
                <td width="50%"><strong>LANGUAGES OPTIONS 3:</strong></td>
                <td width="50%">'.$rst[0]['languages_option2'].'</td>
              </tr>'; 

    $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>COMMUNICATION ADDRESS</strong></h4></td><td></td></tr>';                     
    $html.= '<tr>                    
              <td width="50%"><strong>ADDRESS:</strong></td>
              <td width="50%">'.$rst[0]['addressline1'].' ,'.$rst[0]['addressline2'].' ,'.$rst[0]['addressline3'].' ,'.$rst[0]['addressline4'].'<br>'.$rst[0]['district'].' ,'.$rst[0]['city'].'<br>'.$rst[0]['state'].'<br>'.$rst[0]['pincode'].'</td>
            </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>CONTACT NUMBER:</strong></td>
                <td width="50%">'.$rst[0]['contact_number'].'</td>
              </tr>';
    
    $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>PERMANENT ADDRESS</strong></h4></td><td></td></tr>';  
    $html.= '<tr>                    
              <td width="50%"><strong>ADDRESS:</strong></td>
              <td width="50%">'.$rst[0]['addressline1_pr'].' ,'.$rst[0]['addressline2_pr'].' ,'.$rst[0]['addressline3_pr'].' ,'.$rst[0]['addressline4_pr'].'<br>'.$rst[0]['district_pr'].' ,'.$rst[0]['city_pr'].'<br>'.$rst[0]['state_pr'].'<br>'.$rst[0]['pincode_pr'].'</td>
            </tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>CONTACT NUMBER:</strong></td>
                <td width="50%">'.$rst[0]['contact_number_pr'].'</td>
              </tr>';                 

    $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION(ESSENTIAL)</strong></h4></td><td></td></tr>';  
    foreach ($rst as $row) 
    {    
      
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME OF COURSE(POST GRADUATE):</strong></td>
                  <div style="word-break:break-all;">
                    <td width="50%">'.$row['ess_course_name'].'</td>
                  </div>
                </tr>'; 

        $html.= '<tr>                    
                  <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                  <td width="50%">'.$row['ess_college_name'].'</td>
                </tr>';

        $html.= '<tr>                    
                  <td width="50%"><strong>UNIVERSITY:</strong></td>
                  <td width="50%">'.$row['ess_university'].'</td>
                </tr>';

        $html.= '<tr>                    
                  <td width="50%"><strong>PERIOD:</strong></td>
                  <td width="50%">'.$row['ess_from_date']." to ".$row['ess_to_date'].'</td>
                </tr>';

        $html.= '<tr>                    
                  <td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
                  <td width="50%">'.$row['ess_grade_marks'].'</td>
                </tr>';

         $html.= '<tr>                    
                    <td width="50%"><strong>CLASS:</strong></td>
                    <td width="50%">'.$row['ess_class'].'</td>
                  </tr>';               
    }

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td><td></td></tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>CAIIB:</strong></td>
                <td width="50%">CAIIB</td>
              </tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>YEAR OF PASSING:</strong></td>
                <td width="50%">'.$row['year_of_passing'].'</td>
              </tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>MEMBERSHIP NUMBER:</strong></td>
                <td width="50%">'.$row['membership_number'].'</td>
              </tr>';

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>PhD(IN BANKING OR FINANCE)</strong></h4></td><td></td></tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>PhD(IN BANKING OR FINANCE):</strong></td>
                <td width="50%">'.$row['ph_d'].'</td>
              </tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>NAME OF RESEARCH TOPIC:</strong></td>
                <td width="50%">'.$row['phd_course'].'</td>
              </tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>UNIVERSITY:</strong></td>
                <td width="50%">'.$row['phd_university'].'</td>
              </tr>';

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION(DESIRABLE)</strong></h4></td><td></td></tr>';  
      foreach ($qualification_arr as $row) 
      {    
          $html.= '<tr>                    
                    <td width="50%"><strong>NAME OF COURSE:</strong></td>
                    <div style="word-break:break-all;">
                    <td width="50%">'.$row['course_name'].'</td>
                    </div>
                  </tr>';

          
            $html.= '<tr>                    
                      <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                      <td width="50%">'.$row['college_name'].'</td>
                    </tr>';
          
            $html.= '<tr>                    
                      <td width="50%"><strong>UNIVERSITY:</strong></td>
                      <td width="50%">'.$row['university'].'</td>
                    </tr>';
          
          $html.= '<tr>                    
                    <td width="50%"><strong>PERIOD:</strong></td>
                    <td width="50%">'.$row['from_date']." to ".$row['to_date'].'</td>
                  </tr>';

          
            $html.= '<tr>                    
                      <td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
                      <td width="50%">'.$row['grade_marks'].'</td>
                    </tr>';
          
          
            $html.= '<tr>                    
                      <td width="50%"><strong>CLASS:</strong></td>
                      <td width="50%">'.$row['class'].'</td>
                    </tr>';                   
      }

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>PUBLICATION</strong></h4></td><td></td></tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>PUBLICATION OF BOOKS:</strong></td>
                <td width="50%">'.$rst[0]['publication_of_books'].'</td>
              </tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>PUBLICATION OF ARTICLES(GIVE LATEST, NOT MORE THAT TEN):</strong></td>
                <td width="50%">'.$rst[0]['publication_of_articles'].'</td>
              </tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>AREA OF SPECIALIZATION:</strong></td>
                <td width="50%">'.$rst[0]['area_of_specialization'].'</td>
              </tr>';

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY</strong></h4></td><td></td></tr>';
      foreach ($emp_hist_arr as $rest)
      {                    
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME OF THE ORGANIZATION:</strong></td>
                  <td width="50%">'.$rest['organization'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>DESIGNATION:</strong></td>
                  <td width="50%">'.$rest['designation'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>RESPOSIBILITIES:</strong></td>
                  <td width="50%">'.$rest['responsibilities'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>PERIOD:</strong></td>
                  <td width="50%">'.$rest['job_from_date']." to ".$rest['job_to_date'].'</td>
                </tr>'; 
      }  

      $html.= '<tr>                    
                <td width="50%"><strong>EXPERIENCE AS PRINCIPAL/DIRECTOR OF A BANKING STAFF TRANING COLLEGE/CENTRE/MANAGEMENT INSTITUTION:</strong></td>
                <td width="50%">'.$rest['experience_as_principal'].'</td>
              </tr>'; 

      $html.= '<tr>                    
                <td width="50%"><strong>EXPERIENCE AS FACULTY,PROFESSOR,LECTURER:</strong></td>
                <td width="50%">'.$rest['experience_as_faculty'].'</td>
              </tr>';
    

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE ONE</strong></h4></td><td></td></tr>';                    
      $html.= '<tr>                    
                <td width="50%"><strong>NAME:</strong></td>
                <td width="50%">'.$rst[0]['refname_one'].'</td>
              </tr>';   
      $html.= '<tr>                    
                <td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
                <td width="50%">'.$rst[0]['refaddressline_one'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>EMAIL ID:</strong></td>
                <td width="50%">'.$rst[0]['refemail_one'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>MOBILE:</strong></td>
                <td width="50%">'.$rst[0]['refmobile_one'].'</td>
              </tr>';                                                         
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE TWO</strong></h4></td><td></td></tr>';  
      $html.= '<tr>                    
                <td width="50%"><strong>NAME:</strong></td>
                <td width="50%">'.$rst[0]['refname_two'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
                <td width="50%">'.$rst[0]['refaddressline_two'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>EMAIL ID:</strong></td>
                <td width="50%">'.$rst[0]['refemail_two'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>MOBILE:</strong></td>
                <td width="50%">'.$rst[0]['refmobile_two'].'</td>
              </tr>';  

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>OTHER INFORMATION</strong></h4></td><td></td></tr> ';
      $html.= '<tr>                    
                <td width="50%" style="word-break:break-all; display: inline-block;"><strong>1.Earliest date of Joining if Selected:</strong></td>
                <td width="50%">
                  <div style="word-break:break-all;">'.$rst[0]['earliest_date_of_joining'].'</div>
                </td>
              </tr>';  

      $html.= '<tr>                    
                <td width="50%" style="word-break:break-all; display: inline-block;"><strong>2.Why do you consider yourself suitable of the post of CEO of this Institute :</strong></td>
                <td width="50%">
                  <div style="word-break:break-all;">'.$rst[0]['suitable_of_the_post_of_CEO'].'</div>
                </td>
                  </tr>'; 
      $html.= '<tr>                    
                  <td width="50%" style="word-break:break-all; display: inline-block;"><strong>3. Any other information that the candidate would like to add:</strong></td>
                  <td width="50%">
                          <div style="word-break:break-all;">
                            '.$rst[0]['comment'].'
                          </div>
                      </td>
                  </tr>'; 

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>DECLARATION</strong></h4></td><td></td></tr>'; 
      $html.= '<tr>                    
                    <td width="50%"><strong>DECLARATION: <br>I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated without any notice.</strong></td>
                     <td width="50%">'.$rst[0]['declaration2'].'</td>
              </tr>';    
      
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>PLACE AND DATE</strong></h4></td><td></td></tr> ';
      $html.= '<tr>                    
                      <td width="50%"><strong>PLACE:</strong></td>
                      <td width="50%">'.$rst[0]['place'].'</td>
                  </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>DATE:</strong></td>
                <td width="50%">'.$rst[0]['submit_date'].'</td>
              </tr>';  
      $html.= '<tr>                    
                    <td width="50%"><strong>SIGNATURE:</strong></td>
                    <td><img width="70px" height="70px" src="'.base_url().'uploads/scansignature/'.$rst[0]['scannedsignaturephoto'].'" id="thumb" />
                    </td>
            </tr>';                                                                                                                 
      $html.= '</tbody>
                  </table>
                 <div id="reason_form" style="display: none">';                                                     

      $pdf = $this->m_pdf->load();
      $pdfFilePath = $rst[0]['firstname'].'_'.$rst[0]['lastname'].'_'.$rst[0]['submit_date'].".pdf";
    
      $pdf->WriteHTML($html);
      $pdf->SetCompression(false);
      $pdf->SetDisplayMode('real');
      $pdf->SetDisplayMode('default');
      $pdf->SetAutoPageBreak(true);
      
      $path = $pdf->Output('uploads/Careers_Data/'.$pdfFilePath, "F"); 
    }
    redirect(base_url().'careers_admin/admin/Careers_position/career_position_list');
  }

  /*public function pdf_dda()
  { 
    //$id = $this->uri->segment(5);
    $url_position_id = $this->uri->segment(6);
    if($url_position_id != '')
    {
      $position_id = $this->uri->segment(6);
    }
    else
    {
      $position_id = $_GET['position']; 
      $position    = $_GET['position']; 
    }
  
    $position  = $_GET['position']; 
    $from_date = $_GET['from_date'];
    $to_date   = $_GET['to_date'];
  
    $this->db->where('position_id',$position);  
    $this->db->where('DATE(createdon) >=', $from_date);
    $this->db->where('DATE(createdon) <=', $to_date);
    $sql = $this->master_model->getRecords("careers_registration",'','careers_id');
    //print_r($sql);die;
   
    foreach($sql as $rec)
    {
      $act_stat = 1;
      
      $this->db->where('careers_id',$rec['careers_id']);
      $rst = $this->master_model->getRecords("careers_registration");
      //print_r($rst);die;
      $this->db->select('m.id,m.course_name,c.careers_id,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class');
      $this->db->join('careers_registration c','c.careers_id=q.careers_id','LEFT');
      $this->db->join('careers_course_mst m','m.course_code=q.course_code','LEFT');
      $this->db->where('c.careers_id',$rec['careers_id']);
      $this->db->where('active_status', $act_stat);
      $qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");
      

      $this->db->select('c.careers_id,e.careers_id as employ_id,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date');
      $this->db->join('careers_registration c','c.careers_id=e.careers_id','LEFT');
      $this->db->where('c.careers_id',$rec['careers_id']);
      $this->db->where('active_status', $act_stat);
      $emp_hist_arr = $this->master_model->getRecords('careers_employment_hist e');
      //print_r($emp_hist_arr);die;

      $html= '<style>
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
      $html.= '<h1 style="text-align:center">APPLICATION</h1>';
    
      $html.= '<div class="table-responsive ">
              <table class="table table-bordered wikitable tabela" style="overflow: wrap">
              <tbody>';
      $html.= '<br><br>
              <tr>                    
                <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
                <td width="50%">Deputy Director Academics</td>
              </tr> ';
      $html.= '<tr>      
                <td width="50%"><strong>PHOTO:</strong></td>              
                <td width="50%"><img  class="column" width="70px" height="70px" align="right" src="'.base_url().'uploads/photograph/'.$rst[0]['scannedphoto'].'"id="thumb" /><br><br><br></td>
              </tr>'; 
      $html.= '<br><br>
              <tr>                    
                <td width="50%"><strong>NAME:</strong></td>
                <td width="50%">'.$rst[0]["sel_namesub"].$rst[0]["firstname"].' '.$rst[0]['middlename'].' '.$rst[0]['lastname'].'</td>
              </tr> ';
      $html.= '<br><br>
              <tr>                    
                <td width="50%"><strong>FATHERS/HUSBANDS NAME:</strong></td>
                <td width="50%">'.$rst[0]["father_husband_name"].'</td>
              </tr> ';
      $html.= '<tr>                    
                <td width="50%"><strong>DATE OF BIRTH:</strong></td>
                <td width="50%">'.$rst[0]['dateofbirth'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>GENDER:</strong></td>
                <td width="50%">'.$rst[0]['gender'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>EMAIL ID:</strong></td>
                <td width="50%">'.$rst[0]['email'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>MARITAL STATUS:</strong></td>
                <td width="50%">'.$rst[0]['marital_status'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>MOBILE:</strong></td>
                <td width="50%">'.$rst[0]['mobile'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>ALTERNATE MOBILE:</strong></td>
                <td width="50%">'.$rst[0]['alternate_mobile'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>PAN NO:</strong></td>
                <td width="50%">'.$rst[0]['pan_no'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>AADHAR CARD NO:</strong></td>
                <td width="50%">'.$rst[0]['aadhar_card_no'].'</td>
              </tr>';

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>COMMUNICATION ADDRESS</strong></h4></td><td></td></tr>';                
      $html.= '<tr>                    
                <td width="50%"><strong>ADDRESS:</strong></td>
                <td width="50%">'.$rst[0]['addressline1'].' ,'.$rst[0]['addressline2'].' ,'.$rst[0]['addressline3'].' ,'.$rst[0]['addressline4'].'<br>'.$rst[0]['district'].' ,'.$rst[0]['city'].'<br>'.$rst[0]['state'].'<br>'.$rst[0]['pincode'].'</td>
              </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>CONTACT NUMBER:</strong></td>
                  <td width="50%">'.$rst[0]['contact_number'].'</td>
                </tr>';
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>PERMANENT ADDRESS</strong></h4></td><td></td></tr>';  
      $html.= '<tr>                    
                <td width="50%"><strong>ADDRESS:</strong></td>
                <td width="50%">'.$rst[0]['addressline1_pr'].' ,'.$rst[0]['addressline2_pr'].' ,'.$rst[0]['addressline3_pr'].' ,'.$rst[0]['addressline4_pr'].'<br>'.$rst[0]['district_pr'].' ,'.$rst[0]['city_pr'].'<br>'.$rst[0]['state_pr'].'<br>'.$rst[0]['pincode_pr'].'</td>
              </tr>';

        $html.= '<tr>                    
                  <td width="50%"><strong>CONTACT NUMBER:</strong></td>
                  <td width="50%">'.$rst[0]['contact_number_pr'].'</td>
                </tr>';

      if(!empty($rst[0]['exam_center']))
      {
        $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EXAM CENTER</strong></h4></td><td></td></tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>EXAM CENTER:</strong></td>
                  <td width="50%">'.$rst[0]['exam_center'].'</td>
                </tr>';
      }                    

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION(ESSENTIAL)</strong></h4></td><td></td></tr>';  
      foreach ($rst as $row) 
      {    
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME OF COURSE(POST GRADUATE):</strong></td>
                  <div style="word-break:break-all;">
                    <td width="50%">'.$row['ess_course_name'].'</td>
                  </div>
                  </tr>'; 
        $html.= '<tr>                    
                    <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                    <td width="50%">'.$row['ess_college_name'].'</td>
                  </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>UNIVERSITY:</strong></td>
                  <td width="50%">'.$row['ess_university'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>PERIOD:</strong></td>
                  <td width="50%">'.$row['ess_from_date']." to ".$row['ess_to_date'].'</td>
                </tr>';
        $html.= '<tr>                    
                    <td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
                    <td width="50%">'.$row['ess_grade_marks'].'</td>
                  </tr>';       
        $html.= '<tr>                    
                  <td width="50%"><strong>CLASS:</strong></td>
                  <td width="50%">'.$row['ess_class'].'</td>
                </tr>';                  
      }
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td><td></td></tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>CAIIB:</strong></td>
                <td width="50%">CAIIB</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>YEAR OF PASSING:</strong></td>
                <td width="50%">'.$rst[0]['year_of_passing'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>MEMBERSHIP NUMBER:</strong></td>
                <td width="50%">'.$rst[0]['membership_number'].'</td>
              </tr>';
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION(DESIRABLE)</strong></h4></td><td></td></tr>';  
      foreach ($qualification_arr as $row) 
      {    
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME OF COURSE:</strong></td>
                  <div style="word-break:break-all;">
                    <td width="50%">'.$row['course_name'].'</td>
                  </div>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                  <td width="50%">'.$row['college_name'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>UNIVERSITY:</strong></td>
                  <td width="50%">'.$row['university'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>PERIOD:</strong></td>
                  <td width="50%">'.$row['from_date']." to ".$row['to_date'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
                  <td width="50%">'.$row['grade_marks'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>CLASS:</strong></td>
                  <td width="50%">'.$row['class'].'</td>
                </tr>';  
      }              
      
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY</strong></h4></td><td></td></tr>';
      foreach ($emp_hist_arr as $rest)
      {                    
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME OF THE ORGANIZATION:</strong></td>
                  <td width="50%">'.$rest['organization'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>DESIGNATION:</strong></td>
                  <td width="50%">'.$rest['designation'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>RESPOSIBILITIES:</strong></td>
                  <td width="50%">'.$rest['responsibilities'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>PERIOD:</strong></td>
                  <td width="50%">'.$rest['job_from_date']." to ".$rest['job_to_date'].'</td>
                </tr>'; 
        }   

        $html.= '<tr>                    
                <td width="50%"><strong>EXPERIENCE AS FACULTY IN BANKING/FINANCIAL INSTITUTION:</strong></td>
                <td width="50%">'.$rst[0]['exp_in_bank'].'</td>
              </tr>'; 

      $html.= '<tr>                    
                <td width="50%"><strong>EXPERIENCE IN ONE OR MORE COVERING THE FUNCTIONAL AREAS:</strong></td>
                <td width="50%">'.$rst[0]['exp_in_functional_area'].'</td>
              </tr>';  
      
        $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>LANGUAGES, EXTRACURRICULAR, ACHIEVEMENTS</strong></h4></td><td></td></tr>';                
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES KNOWN 1:</strong></td>
                  <td width="50%">'.$rst[0]['languages_known'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES OPTIONS 1:</strong></td>
                  <td width="50%">'.$rst[0]['languages_option'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES KNOWN 2:</strong></td>
                  <td width="50%">'.$rst[0]['languages_known1'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES OPTIONS 2:</strong></td>
                  <td width="50%">'.$rst[0]['languages_option1'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES KNOWN 3:</strong></td>
                  <td width="50%">'.$rst[0]['languages_known2'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES OPTIONS 3:</strong></td>
                  <td width="50%">'.$rst[0]['languages_option2'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>EXTRACURRICULAR(GAMES/MEMBERSHIP/ASSOCIATION):</strong></td>
                  <td width="50%">'.$rst[0]['extracurricular'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>HOBBIES:</strong></td>
                  <td width="50%">'.$rst[0]['hobbies'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>ACHIEVEMENTS:</strong></td>
                  <td width="50%">'.$rst[0]['achievements'].'</td>
                </tr>'; 
        if($rst[0]['declaration1'] == 'Yes')
        {
          $html.= '<tr>                    
                    <td width="50%"><strong>DECLARATION: Have your ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification:</strong></td>
                    <td width="50%">'.$rst[0]['declaration1'].'</td>
                  </tr>'; 
          $html.= '<tr>                    
                    <td width="50%"><strong>DECLARATION NOTE:</strong></td>
                    <td width="50%">'.$rst[0]['declaration_note'].'</td>
                  </tr>';                    
        }
        else
        {
          $html.= '<tr>                    
                    <td width="50%"><strong>DECLARATION: Have your ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification:</strong></td>
                    <td width="50%">'.$rst[0]['declaration1'].'</td>
                  </tr>'; 
        }
                                                                        
    $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE ONE</strong></h4></td><td></td></tr>';                    
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME:</strong></td>
                  <td width="50%">'.$rst[0]['refname_one'].'</td>
                  </tr>';   
        $html.= '<tr>                    
                  <td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
                  <td width="50%">'.$rst[0]['refaddressline_one'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>EMAIL ID:</strong></td>
                  <td width="50%">'.$rst[0]['refemail_one'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>MOBILE:</strong></td>
                  <td width="50%">'.$rst[0]['refmobile_one'].'</td>
                </tr>';                                                         
        $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE TWO</strong></h4></td><td></td></tr>';  
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME:</strong></td>
                  <td width="50%">'.$rst[0]['refname_two'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
                  <td width="50%">'.$rst[0]['refaddressline_two'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>EMAIL ID:</strong></td>
                  <td width="50%">'.$rst[0]['refemail_two'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>MOBILE:</strong></td>
                  <td width="50%">'.$rst[0]['refmobile_two'].'</td>
                </tr>';
        $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>OTHER INFORMATION</strong></h4></td><td></td></tr> ';
        $html.= '<tr>                    
                  <td width="50%" style="word-break:break-all; display: inline-block;"><strong>ANY OTHER INFORMATION THAT THE CANDIDATE WOULD LIKE TO ADD:</strong></td>
                  <td width="50%">
                    <div style="word-break:break-all;">'.$rst[0]['comment'].'</div>
                  </td>
                </tr>';                 
        $html.= '<tr>                    
                  <td width="50%"><strong>DECLARATION: I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated without any notice.</strong></td>
                  <td width="50%">'.$rst[0]['declaration2'].'</td>
                </tr>';    
        $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>PLACE AND DATE</strong></h4></td><td></td></tr> ';
        $html.= '<tr>                    
                  <td width="50%"><strong>PLACE:</strong></td>
                  <td width="50%">'.$rst[0]['place'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>DATE:</strong></td>
                  <td width="50%">'.$rst[0]['submit_date'].'</td>
                </tr>';  
        $html.= '<tr>                    
                  <td width="50%"><strong>SIGNATURE:</strong></td>
                  <td><img width="70px" height="70px" src="'.base_url().'uploads/scansignature/'.$rst[0]['scannedsignaturephoto'].'" id="thumb" />
                  </td>
                </tr>';
        $html.= '</tbody>
                </table>
                <div id="reason_form" style="display: none">';     
        $pdf = $this->m_pdf->load();
    
        $pdfFilePath = $rst[0]['firstname'].'_'.$rst[0]['lastname'].'_'.$rst[0]['submit_date'].".pdf";
      
        $pdf->WriteHTML($html);
        $pdf->SetCompression(false);
        $pdf->SetDisplayMode('real');
        $pdf->SetDisplayMode('default');
        $pdf->SetAutoPageBreak(true);
    
        $path = $pdf->Output('uploads/Careers_Data/'.$pdfFilePath, "F"); 
      }
      redirect(base_url().'careers_admin/admin/Careers_position/career_position_list');
    }
  } 
*/
  public function pdf_faculty()
  { 
    //$id = $this->uri->segment(5);
    $url_position_id = $this->uri->segment(6);
    if($url_position_id != '')
    {
      $position_id = $this->uri->segment(6);
    }
    else
    {
      $position_id = $_GET['position']; 
      $position    = $_GET['position']; 
    }
  
    $position  = $_GET['position']; 
    $from_date = $_GET['from_date'];
    $to_date   = $_GET['to_date'];
  
    $this->db->where('position_id',$position);  
    $this->db->where('DATE(createdon) >=', $from_date);
    $this->db->where('DATE(createdon) <=', $to_date);
    $sql = $this->master_model->getRecords("careers_registration",'','careers_id');
    //print_r($sql);die;
   
    foreach($sql as $rec)
    {
      $act_stat = 1;
      
      $this->db->where('careers_id',$rec['careers_id']);
      $rst = $this->master_model->getRecords("careers_registration");
      //print_r($rst);die;
      $this->db->select('m.id,m.course_name,c.careers_id,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class');
      $this->db->join('careers_registration c','c.careers_id=q.careers_id','LEFT');
      $this->db->join('careers_course_mst m','m.course_code=q.course_code','LEFT');
      $this->db->where('c.careers_id',$rec['careers_id']);
      $this->db->where('active_status', $act_stat);
      $qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");
      

      $this->db->select('c.careers_id,e.careers_id as employ_id,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date');
      $this->db->join('careers_registration c','c.careers_id=e.careers_id','LEFT');
      $this->db->where('c.careers_id',$rec['careers_id']);
      $this->db->where('active_status', $act_stat);
      $emp_hist_arr = $this->master_model->getRecords('careers_employment_hist e');
      //print_r($emp_hist_arr);die;

      $html= '<style>
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
      $html.= '<h1 style="text-align:center">APPLICATION</h1>';
    
      $html.= '<div class="table-responsive ">
              <table class="table table-bordered wikitable tabela" style="overflow: wrap">
              <tbody>';
      $html.= '<br><br>
              <tr>                    
                <td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
                <td width="50%">Faculty Member on contract</td>
              </tr> ';
      $html.= '<tr>      
                <td width="50%"><strong>PHOTO:</strong></td>              
                <td width="50%"><img  class="column" width="70px" height="70px" align="right" src="'.base_url().'uploads/photograph/'.$rst[0]['scannedphoto'].'"id="thumb" /><br><br><br></td>
              </tr>'; 
      $html.= '<br><br>
              <tr>                    
                <td width="50%"><strong>NAME:</strong></td>
                <td width="50%">'.$rst[0]["sel_namesub"].$rst[0]["firstname"].' '.$rst[0]['middlename'].' '.$rst[0]['lastname'].'</td>
              </tr> ';
      $html.= '<br><br>
              <tr>                    
                <td width="50%"><strong>FATHERS/HUSBANDS NAME:</strong></td>
                <td width="50%">'.$rst[0]["father_husband_name"].'</td>
              </tr> ';
      $html.= '<tr>                    
                <td width="50%"><strong>DATE OF BIRTH:</strong></td>
                <td width="50%">'.$rst[0]['dateofbirth'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>GENDER:</strong></td>
                <td width="50%">'.$rst[0]['gender'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>EMAIL ID:</strong></td>
                <td width="50%">'.$rst[0]['email'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>MARITAL STATUS:</strong></td>
                <td width="50%">'.$rst[0]['marital_status'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>MOBILE:</strong></td>
                <td width="50%">'.$rst[0]['mobile'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>ALTERNATE MOBILE:</strong></td>
                <td width="50%">'.$rst[0]['alternate_mobile'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>PAN NO:</strong></td>
                <td width="50%">'.$rst[0]['pan_no'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>AADHAR CARD NO:</strong></td>
                <td width="50%">'.$rst[0]['aadhar_card_no'].'</td>
              </tr>';

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>COMMUNICATION ADDRESS</strong></h4></td><td></td></tr>';                
      $html.= '<tr>                    
                <td width="50%"><strong>ADDRESS:</strong></td>
                <td width="50%">'.$rst[0]['addressline1'].' ,'.$rst[0]['addressline2'].' ,'.$rst[0]['addressline3'].' ,'.$rst[0]['addressline4'].'<br>'.$rst[0]['district'].' ,'.$rst[0]['city'].'<br>'.$rst[0]['state'].'<br>'.$rst[0]['pincode'].'</td>
              </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>CONTACT NUMBER:</strong></td>
                  <td width="50%">'.$rst[0]['contact_number'].'</td>
                </tr>';
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>PERMANENT ADDRESS</strong></h4></td><td></td></tr>';  
      $html.= '<tr>                    
                <td width="50%"><strong>ADDRESS:</strong></td>
                <td width="50%">'.$rst[0]['addressline1_pr'].' ,'.$rst[0]['addressline2_pr'].' ,'.$rst[0]['addressline3_pr'].' ,'.$rst[0]['addressline4_pr'].'<br>'.$rst[0]['district_pr'].' ,'.$rst[0]['city_pr'].'<br>'.$rst[0]['state_pr'].'<br>'.$rst[0]['pincode_pr'].'</td>
              </tr>';

        $html.= '<tr>                    
                  <td width="50%"><strong>CONTACT NUMBER:</strong></td>
                  <td width="50%">'.$rst[0]['contact_number_pr'].'</td>
                </tr>';

      if(!empty($rst[0]['exam_center']))
      {
        $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EXAM CENTER</strong></h4></td><td></td></tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>EXAM CENTER:</strong></td>
                  <td width="50%">'.$rst[0]['exam_center'].'</td>
                </tr>';
      }                    

      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION(ESSENTIAL)</strong></h4></td><td></td></tr>';  
      foreach ($rst as $row) 
      {    
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME OF COURSE(POST GRADUATE):</strong></td>
                  <div style="word-break:break-all;">
                    <td width="50%">'.$row['ess_course_name'].'</td>
                  </div>
                  </tr>'; 
        $html.= '<tr>                    
                    <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                    <td width="50%">'.$row['ess_college_name'].'</td>
                  </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>UNIVERSITY:</strong></td>
                  <td width="50%">'.$row['ess_university'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>PERIOD:</strong></td>
                  <td width="50%">'.$row['ess_from_date']." to ".$row['ess_to_date'].'</td>
                </tr>';
        $html.= '<tr>                    
                    <td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
                    <td width="50%">'.$row['ess_grade_marks'].'</td>
                  </tr>';       
        $html.= '<tr>                    
                  <td width="50%"><strong>CLASS:</strong></td>
                  <td width="50%">'.$row['ess_class'].'</td>
                </tr>';                  
      }
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td><td></td></tr>';

      $html.= '<tr>                    
                <td width="50%"><strong>CAIIB:</strong></td>
                <td width="50%">CAIIB</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>YEAR OF PASSING:</strong></td>
                <td width="50%">'.$rst[0]['year_of_passing'].'</td>
              </tr>';
      $html.= '<tr>                    
                <td width="50%"><strong>MEMBERSHIP NUMBER:</strong></td>
                <td width="50%">'.$rst[0]['membership_number'].'</td>
              </tr>';
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION(DESIRABLE)</strong></h4></td><td></td></tr>';  
      foreach ($qualification_arr as $row) 
      {    
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME OF COURSE:</strong></td>
                  <div style="word-break:break-all;">
                    <td width="50%">'.$row['course_name'].'</td>
                  </div>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
                  <td width="50%">'.$row['college_name'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>UNIVERSITY:</strong></td>
                  <td width="50%">'.$row['university'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>PERIOD:</strong></td>
                  <td width="50%">'.$row['from_date']." to ".$row['to_date'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
                  <td width="50%">'.$row['grade_marks'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>CLASS:</strong></td>
                  <td width="50%">'.$row['class'].'</td>
                </tr>';  
      }              
      
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY</strong></h4></td><td></td></tr>';
      foreach ($emp_hist_arr as $rest)
      {                    
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME OF THE ORGANIZATION:</strong></td>
                  <td width="50%">'.$rest['organization'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>DESIGNATION:</strong></td>
                  <td width="50%">'.$rest['designation'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>RESPOSIBILITIES:</strong></td>
                  <td width="50%">'.$rest['responsibilities'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>PERIOD:</strong></td>
                  <td width="50%">'.$rest['job_from_date']." to ".$rest['job_to_date'].'</td>
                </tr>'; 
        }   

        $html.= '<tr>                    
                <td width="50%"><strong>EXPERIENCE AS FACULTY IN BANKS/FINANCIAL INSTITUTION:</strong></td>
                <td width="50%">'.$rst[0]['exp_in_bank'].'</td>
              </tr>'; 

      $html.= '<tr>                    
                <td width="50%"><strong>PUBLISHED ARTICLES/BOOKS:</strong></td>
                <td width="50%">'.$rst[0]['publication_of_books'].'</td>
              </tr>';  
      
        $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>LANGUAGES, EXTRACURRICULAR, ACHIEVEMENTS</strong></h4></td><td></td></tr>';                
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES KNOWN 1:</strong></td>
                  <td width="50%">'.$rst[0]['languages_known'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES OPTIONS 1:</strong></td>
                  <td width="50%">'.$rst[0]['languages_option'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES KNOWN 2:</strong></td>
                  <td width="50%">'.$rst[0]['languages_known1'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES OPTIONS 2:</strong></td>
                  <td width="50%">'.$rst[0]['languages_option1'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES KNOWN 3:</strong></td>
                  <td width="50%">'.$rst[0]['languages_known2'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>LANGUAGES OPTIONS 3:</strong></td>
                  <td width="50%">'.$rst[0]['languages_option2'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>EXTRACURRICULAR(GAMES/MEMBERSHIP/ASSOCIATION):</strong></td>
                  <td width="50%">'.$rst[0]['extracurricular'].'</td>
                </tr>'; 
        $html.= '<tr>                    
                  <td width="50%"><strong>HOBBIES:</strong></td>
                  <td width="50%">'.$rst[0]['hobbies'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>ACHIEVEMENTS:</strong></td>
                  <td width="50%">'.$rst[0]['achievements'].'</td>
                </tr>'; 
                                                                        
      $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE ONE</strong></h4></td><td></td></tr>';                    
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME:</strong></td>
                  <td width="50%">'.$rst[0]['refname_one'].'</td>
                  </tr>';   
        $html.= '<tr>                    
                  <td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
                  <td width="50%">'.$rst[0]['refaddressline_one'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>EMAIL ID:</strong></td>
                  <td width="50%">'.$rst[0]['refemail_one'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>MOBILE:</strong></td>
                  <td width="50%">'.$rst[0]['refmobile_one'].'</td>
                </tr>';                                                         
        $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE TWO</strong></h4></td><td></td></tr>';  
        $html.= '<tr>                    
                  <td width="50%"><strong>NAME:</strong></td>
                  <td width="50%">'.$rst[0]['refname_two'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
                  <td width="50%">'.$rst[0]['refaddressline_two'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>EMAIL ID:</strong></td>
                  <td width="50%">'.$rst[0]['refemail_two'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>MOBILE:</strong></td>
                  <td width="50%">'.$rst[0]['refmobile_two'].'</td>
                </tr>';
        $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>OTHER INFORMATION</strong></h4></td><td></td></tr> ';
        $html.= '<tr>                    
                  <td width="50%" style="word-break:break-all; display: inline-block;"><strong>ANY OTHER INFORMATION THAT THE CANDIDATE WOULD LIKE TO ADD:</strong></td>
                  <td width="50%">
                    <div style="word-break:break-all;">'.$rst[0]['comment'].'</div>
                  </td>
                </tr>';                 
        $html.= '<tr>                    
                  <td width="50%"><strong>DECLARATION: I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated without any notice.</strong></td>
                  <td width="50%">'.$rst[0]['declaration2'].'</td>
                </tr>';    
        $html.= '<br><tr><td style="color: #66d9ff"><h4><strong>PLACE AND DATE</strong></h4></td><td></td></tr> ';
        $html.= '<tr>                    
                  <td width="50%"><strong>PLACE:</strong></td>
                  <td width="50%">'.$rst[0]['place'].'</td>
                </tr>';
        $html.= '<tr>                    
                  <td width="50%"><strong>DATE:</strong></td>
                  <td width="50%">'.$rst[0]['submit_date'].'</td>
                </tr>';  
        $html.= '<tr>                    
                  <td width="50%"><strong>SIGNATURE:</strong></td>
                  <td><img width="70px" height="70px" src="'.base_url().'uploads/scansignature/'.$rst[0]['scannedsignaturephoto'].'" id="thumb" />
                  </td>
                </tr>';
        $html.= '</tbody>
                </table>
                <div id="reason_form" style="display: none">';     
        $pdf = $this->m_pdf->load();
    
        $pdfFilePath = $rst[0]['firstname'].'_'.$rst[0]['lastname'].'_'.$rst[0]['submit_date'].".pdf";
      
        $pdf->WriteHTML($html);
        $pdf->SetCompression(false);
        $pdf->SetDisplayMode('real');
        $pdf->SetDisplayMode('default');
        $pdf->SetAutoPageBreak(true);
    
        $path = $pdf->Output('uploads/Careers_Data/'.$pdfFilePath, "F"); 
      }
      redirect(base_url().'careers_admin/admin/Careers_position/career_position_list');
  }}
  

?>