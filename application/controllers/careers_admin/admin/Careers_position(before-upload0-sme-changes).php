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
			$this->load->helper('directory');
			$this->load->library('PHPExcel');
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
			$this->db->order_by('c.careers_id', 'DESC');
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
				
				if($from_date != "" && $to_date != "" && $position != ""){
					$this->db->where('DATE(c.createdon) >=', $from_date);
					$this->db->where('DATE(c.createdon) <=', $to_date);
					$this->db->where('c.position_id',$position);
				}else if($from_date != "" && $to_date != ""){
					$this->db->where('DATE(c.createdon) >=', $from_date);
					$this->db->where('DATE(c.createdon) <=', $to_date);
				}else if($from_date != "" && $position != ""){
					$this->db->where('DATE(c.createdon)', $from_date); 
					$this->db->where('c.position_id',$position);
				}else if($to_date != "" && $position != ""){
					$this->db->where('DATE(c.createdon)', $to_date); 
					$this->db->where('c.position_id',$position);
				}else if($from_date != ""){
					$this->db->where('DATE(c.createdon) >=', $from_date);
					$this->db->where('DATE(c.createdon) <=', $from_date);
				}else if($to_date != ""){
					$this->db->where('DATE(c.createdon) >=', $to_date);
					$this->db->where('DATE(c.createdon) <=', $to_date);
				}else if($position != ""){
					$this->db->where('c.position_id',$position);
				}
				
				$this->db->where('c.active_status', '1');
				$this->db->order_by('c.careers_id', 'DESC');
				$sql = $this->master_model->getRecords("careers_registration c");
				
				//echo $this->db->last_query();

				$data['reuest_list'] = $sql;
				$data['list_count']  = count($sql);
				
				$this->load->view('careers_admin/admin/careers_position_list',$data);
			}
			elseif(isset($_GET['is_excel']) && $_GET['is_excel']=='yes' )
			{
				$position  = $this->input->get('position');
				
				$from_date = $this->input->get('from_date');
				$to_date   = $this->input->get('to_date');
				
				$this->db->select('m.id,c.position_id,c.careers_id,c.firstname,c.middlename,c.lastname,c.email,c.mobile,c.alternate_mobile,c.scannedphoto,c.scannedsignaturephoto,c.submit_date,c.bank_education,c.dateofbirth,c.CAIIB_qualification,c.addressline1,c.addressline2,c.city,c.state,c.pincode,c.ess_college_name,c.retired_working,c.exp_in_bank,c.designation,c.general_subjects,c.specialisation,c.it_subjects,c.other_subjects,c.exp_in_bank,c.educational_qualification,m.position');
				$this->db->join('careers_position_master m','m.id=c.position_id','LEFT');
				
				if($from_date != "" && $to_date != "" && $position != "") {
					$this->db->where('DATE(c.createdon) >=', $from_date);
					$this->db->where('DATE(c.createdon) <=', $to_date);
					$this->db->where('c.position_id',$position);
				}else if($from_date != "" && $to_date != ""){
					$this->db->where('DATE(c.createdon) >=', $from_date);
					$this->db->where('DATE(c.createdon) <=', $to_date);
				}else if($from_date != "" && $position != ""){
					$this->db->where('DATE(c.createdon)', $from_date); 
					$this->db->where('c.position_id',$position);
				}else if($to_date != "" && $position != ""){
					$this->db->where('DATE(c.createdon)', $to_date); 
					$this->db->where('c.position_id',$position);
				}else if($from_date != ""){
					$this->db->where('DATE(c.createdon) >=', $from_date);
					$this->db->where('DATE(c.createdon) <=', $from_date);
				}else if($to_date != ""){
					$this->db->where('DATE(c.createdon) >=', $to_date);
					$this->db->where('DATE(c.createdon) <=', $to_date);
				} else if($position != "") {
					$this->db->where('c.position_id',$position);
				}
				
				$this->db->where('c.active_status', '1');
				$this->db->order_by('c.careers_id', 'DESC');
				$excelData = $this->master_model->getRecords("careers_registration c");
				
				// Load Excel library
				$objPHPExcel = new PHPExcel();

				// Add data to the Excel sheet
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sr.No.');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Candidate Name');
				$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Email');
				$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Contact No.');
				if ($position == 13) {
					$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Date of Birth');
					$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Educational Qualification');
					$objPHPExcel->getActiveSheet()->setCellValue('G1', 'CAIIB Qualification');
					$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Address');
					$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Bank/Educational Institute');
					$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Organization Name');
					$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Retired/Working');
					$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Work experience');
					$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Designation');
					$objPHPExcel->getActiveSheet()->setCellValue('N1', 'General Banking Subjects');
					$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Specialized Banking Subjects');
					$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Information Technology Subjects');
					$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Other Banking Subjects');
					$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Position');
					$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Application Date');
					$colRange = 'A1:S1';	
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Position');
					$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Application Date');
					$colRange = 'A1:F1';
				}

				// Apply styles to header row
				$headerStyle = array(
				    'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
				    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '333333')),
				    'borders' => array(
				        'allborders' => array(
				            'style' => PHPExcel_Style_Border::BORDER_THIN,
				            'color' => array('rgb' => '000000'),
				        ),
				    ),
				);

				$objPHPExcel->getActiveSheet()->getStyle($colRange)->applyFromArray($headerStyle);

				$row = 2;

				foreach ($excelData as $key => $item) {
					$positionName = $item['position'];
					
					if ($item['alternate_mobile'] != '') {
						$mobile = $item['mobile'].' , '.$item['alternate_mobile'];
					} else {
						$mobile = $item['mobile'];
					}


					$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $key+1);
				    $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $item['firstname']." ".$item['middlename']." ".$item['lastname']);
				    $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $item['email']);
				    $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $mobile);

					if ($position == 13) 
					{

						$address = $item['addressline1'].', '.$item['addressline2'].' '.$item['city'].' '.$item['state'].' '.$item['pincode'];

						$experience = explode(',',$item['exp_in_bank']);

						if($item['bank_education']=='bank')
						{ 
							$bankEducation = ucfirst($item['bank_education']); 
						} 
						else 
						{ 
							$bankEducation = 'Educational Institute'; 
						}

				    	$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $item['dateofbirth']);
					    $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $item['educational_qualification']);
					    $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, ucfirst($item['CAIIB_qualification']));
					    $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $address);
					    $objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $bankEducation);
					    $objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $item['ess_college_name']);
					    $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $item['retired_working']);
					    $objPHPExcel->getActiveSheet()->setCellValue('L' . $row, $experience[0].' Year '.$experience[1].' Month');
					    $objPHPExcel->getActiveSheet()->setCellValue('M' . $row, $item['designation']);
					    $objPHPExcel->getActiveSheet()->setCellValue('N' . $row, $item['general_subjects']);
					    $objPHPExcel->getActiveSheet()->setCellValue('O' . $row, $item['specialisation']);
					    $objPHPExcel->getActiveSheet()->setCellValue('P' . $row, $item['it_subjects']);
					    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $row, $item['other_subjects']);
					    $objPHPExcel->getActiveSheet()->setCellValue('R' . $row, $item['position']);
					    $objPHPExcel->getActiveSheet()->setCellValue('S' . $row, $item['submit_date']);
				    }
				    else {
				    	$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $item['position']);
					    $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $item['submit_date']);	
				    }
				    // Add more columns as needed
				    $row++;
				}

				// Set column widths to auto
				foreach (range('A',$objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
				    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
				}

				// Set headers for download
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="'.$positionName.'-Report.xls"');
				header('Cache-Control: max-age=0');

				//echo $this->db->last_query();

				// Save Excel file to PHP output
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save('php://output');

				// $data['reuest_list'] = $sql;
				// $this->load->view('careers_admin/admin/careers_position_list',$data);
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
		

		public function pdf_record($career_id=0, $position_id=0, $from_date=0, $to_date=0)
		{  
			/* $id = $this->uri->segment(5);
				$url_position_id = $this->uri->segment(6);
				if($url_position_id != '')
				{
				$position_id = $position = $this->uri->segment(6);
				$this->db->where('careers_id',$id);
				}
				else
				{
				$position_id = isset($_GET['position']) ? $_GET['position'] : ''; 
				$position    = isset($_GET['position']) ? $_GET['position'] : '';  
				$from_date   = isset($_GET['from_date']) ? $_GET['from_date'] : ''; //$_GET['from_date'];
				$to_date     = isset($_GET['to_date']) ? $_GET['to_date'] : ''; //$_GET['to_date'];
				$this->db->where('DATE(createdon) >=', $from_date);
				$this->db->where('DATE(createdon) <=', $to_date);
				$this->db->where('active_status', '1');
			} */
			
			$force_open_flag = 0;

			 
			if(isset($_GET['position']))
		    {
	    		$position_id = $_GET['position'];
			}
			if(isset($_GET['from_date']))
	    	{
				$from_date = $_GET['from_date'];
			}
			if(isset($_GET['to_date']))
	    	{
				$to_date = $_GET['to_date'];
			}

			$this->db->where('position_id',$position_id);
 
			$this->db->where('active_status','1');
			if($career_id != '0') 
			{ 
				$this->db->where('careers_id',$career_id); $force_open_flag = 1; 
			}
			if($from_date != '0') 
			{ 
				$this->db->where('DATE(createdon) >=', $from_date); 
			}
			if($to_date != '0') 
			{ 
				$this->db->where('DATE(createdon) <=', $to_date); 
			}
			
			$sql = $this->master_model->getRecords("careers_registration",'','careers_id');
			
			 //echo "<br> QRY : ".$this->db->last_query();
			// echo "<br> Record Count : ".count($sql); exit;
			
			if ($position_id == 13) 
			{ 
				$this->db->where('careers_id',$career_id);
				$rst = $this->master_model->getRecords("careers_registration");
				// echo "<pre>";  print_r($rst); exit;
				$pdf_html = $this->getPDFhtml($rst,$career_id,$position_id); 
				
				$pdf = $this->m_pdf->load();
					
				$pdfFilePath = $rst[0]['firstname'].'_'.$position_id.'_'.$pdf_career_id.".pdf";
				
				$pdf->WriteHTML($pdf_html);
				$pdf->SetCompression(false);
				$pdf->SetDisplayMode('real');
				$pdf->SetDisplayMode('default');
				$pdf->SetAutoPageBreak(true);
				
				if($force_open_flag == 1) 
				{ 
					$path = $pdf->Output($pdfFilePath, "D"); 
				}
				else 
				{ 
					$path = $pdf->Output('uploads/Careers_Data/'.$pdfFilePath, "F");  
				}
				$pdf_generated_cnt++; exit;
			}

			$pdf_generated_cnt = $cnt = 0;

			if(count($sql) > 0)
			{
				$file_arr = array();
				foreach($sql as $rec)
				{
					$pdf_career_id = $rec['careers_id'];
					$this->db->where('careers_id',$pdf_career_id);
					$this->db->where('active_status', '1');

					if($position_id != 7 && $position_id != 14 && $position_id != 12){
						$this->db->where('pay_status', '1');
					} 
					
					
					$rst = $this->master_model->getRecords("careers_registration");
					
					$this->db->select('m.id,m.course_name,c.careers_id,c.specialisation,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class, q.aggregate_marks_obtained, q.aggregate_max_marks, q.percentage, q.name_subject_of_course');
					$this->db->join('careers_registration c','c.careers_id=q.careers_id','LEFT');
					$this->db->join('careers_course_mst m','m.course_code=q.course_code','LEFT');
					$this->db->where('c.careers_id',$pdf_career_id);
					$this->db->where('active_status', '1');
					//$this->db->where('q.careers_id', '42');
					$qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");
				//echo "SQL>" . $this->db->last_query();
					//print_r($qualification_arr);exit;
					
					$this->db->select('c.careers_id,e.careers_id as employ_id,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date');
					$this->db->join('careers_registration c','c.careers_id=e.careers_id','LEFT');
					$this->db->where('c.careers_id',$pdf_career_id);
					$this->db->where('c.active_status', '1');
					$emp_hist_arr = $this->master_model->getRecords('careers_employment_hist e');
					//print_r($emp_hist_arr); 
					if($position_id == 1) { 
						$this->db->select('transaction_no,receipt_no,amount');
						$this->db->where('member_regnumber',$rst[0]["reg_id"]);
					//	$this->db->where('c.active_status', '1');
						$payment_transaction = $this->master_model->getRecords('payment_transaction');
					}

					$mother_name = $religion = $physical_disbaility = $physical_disbaility_desc = '';
					
					if($position_id == 7 || $position_id == 14 || $position_id == 12){
						$this->db->where('careers_id',$rst[0]["careers_id"]); 
						$career_other_details = $this->master_model->getRecords('career_other_details');
						if($career_other_details){
							$mother_name = $career_other_details[0]['mother_name'];
							$religion = $career_other_details[0]['religion'];
							$physical_disbaility = $career_other_details[0]['physical_disbaility'];
							$physical_disbaility_desc = $career_other_details[0]['physical_disbaility_desc'];
						}
					}
					
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
					
					$application_title = '';
					if($position_id == 1) { $application_title = "Junior Executive"; }
					else if($position_id == 2) { $application_title = "Assistant Director (IT)"; }
					else if($position_id == 3) { $application_title = "Assistant Director (Academics)"; }
					else if($position_id == 4) { $application_title = "Director (Training) on Contract"; }
					else if($position_id == 5) { $application_title = "Chief Executive Officer"; }
					else if($position_id == 6) { $application_title = "Deputy Director Accounts"; }
					else if($position_id == 7) { $application_title = "Faculty Member on Contract"; }
					else if($position_id == 9) { $application_title = "Deputy Director (IT)"; }
					else if($position_id == 10) { $application_title = "Research Associate"; }
					else if($position_id == 11) { $application_title = "Director on contract basis"; }
					//else if($position_id == 12) { $application_title = "Head PDC EZ"; }
					else if($position_id == 12) { $application_title = "Head PDC (WZ) - MUMBAI on contract basis"; }
					else if($position_id == 7) { $application_title = "Faculty Member on contract basis (HRM)"; }
					else if($position_id == 14) { $application_title = "Faculty Member (Information Technology)"; }
					
					$html.= '	<tr>                    
					<td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
					<td width="50%">'.$application_title.'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>PHOTO:</strong></td>              
					<td width="50%"><img  class="column" width="70px" height="70px" align="right" src="'.base_url().'uploads/photograph/'.$rst[0]['scannedphoto'].'"id="thumb" />
					</tr>';
						if($position_id == 1 ) {
					//		echo'<pre>';print_r($payment_transaction);
						    $html.= '
        					<tr>                    
        					<td width="50%"><strong>ID:</strong></td>
        					<td width="50%">'.$rst[0]["reg_id"].'</td>
        					</tr>';
							$html.= '
        					<tr>                    
        					<td width="50%"><strong>Amount:</strong></td>
        					<td width="50%">Rs. '.$payment_transaction[0]["amount"].'</td>
        					</tr>';
							$html.= '
        					<tr>                    
        					<td width="50%"><strong>TRANSACTION ID:</strong></td>
        					<td width="50%">'.$payment_transaction[0]["transaction_no"].'</td>
        					</tr>';
							$html.= '
        					<tr>                    
        					<td width="50%"><strong>RECEIPT ID:</strong></td>
        					<td width="50%">'.$payment_transaction[0]["receipt_no"].'</td>
        					</tr>';
						}
					$html.= '
					<tr>                    
					<td width="50%"><strong>NAME:</strong></td>
					<td width="50%">'.$rst[0]["sel_namesub"].' '.$rst[0]["firstname"].' '.$rst[0]['middlename'].' '.$rst[0]['lastname'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>FATHERS/SPOUSE NAME:</strong></td>
					<td width="50%">'.$rst[0]["father_husband_name"].'</td>
					</tr>';
					
					if($position_id == 7 || $position_id == 14 || $position_id == 12){
						$html.= '<tr>                    
						<td width="50%"><strong>MOTHER NAME :</strong></td>
						<td width="50%">'.$mother_name.'</td>
						</tr>';
					} 

					$html.= '<tr>                    
					<td width="50%"><strong>DATE OF BIRTH:</strong></td>
					<td width="50%">'.$rst[0]['dateofbirth'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>GENDER:</strong></td>
					<td width="50%">'.$rst[0]['gender'].'</td>
					</tr>';
					
					if($position_id == 7 || $position_id == 14 || $position_id == 12){
						$html.= '<tr>                    
						<td width="50%"><strong>RELIGION :</strong></td>
						<td width="50%">'.$religion.'</td>
						</tr>';
					} 

					$html.= '<tr>                    
					<td width="50%"><strong>EMAIL ID:</strong></td>
					<td width="50%">'.$rst[0]['email'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>MARITAL STATUS:</strong></td>
					<td width="50%">'.$rst[0]['marital_status'].'</td>
					</tr>';
					
					if($position_id == 7 || $position_id == 14 || $position_id == 12){
						$html.= '<tr>                    
						<td width="50%"><strong>PHYSICALLY DISBAILITY :</strong></td> 
						<td width="50%">'.$physical_disbaility.'</td>
						</tr>';

						if($physical_disbaility == 'yes')
						{
							$html.= '<tr>                    
							<td width="50%"><strong>TYPE OF DISBAILITY :</strong></td> 
							<td width="50%">'.$physical_disbaility_desc.'</td>
							</tr>';
						}
					} 

					$html.= '<tr>                    
					<td width="50%"><strong>MOBILE NO:</strong></td>
					<td width="50%">'.$rst[0]['mobile'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>ALTERNATE MOBILE NO:</strong></td>
					<td width="50%">'.$rst[0]['alternate_mobile'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>PAN NO:</strong></td>
					<td width="50%">'.$rst[0]['pan_no'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>AADHAR CARD NO:</strong></td>
					<td width="50%">'.$rst[0]['aadhar_card_no'].'</td>
					</tr>
					
					<tr><td style="color: #66d9ff"><h4><strong>COMMUNICATION ADDRESS</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>COMMUNICATION ADDRESS:</strong></td>
					<td width="50%">'.$rst[0]['addressline1'].', '.$rst[0]['addressline2'].', '.$rst[0]['addressline3'].', '.$rst[0]['addressline4'].'<br>'.$rst[0]['district'].', '.$rst[0]['city'].'<br>'.$rst[0]['state'].'<br>'.$rst[0]['pincode'].'</td>
					</tr>';
					
					if($position_id != 7 && $position_id != 14 && $position_id != 12){
					$html.= '<tr>                    
					<td width="50%"><strong>CONTACT NUMBER:</strong></td>
					<td width="50%">'.$rst[0]['contact_number'].'</td>
					</tr>';
					}
					
					$html.= '<tr><td style="color: #66d9ff"><h4><strong>PERMANENT ADDRESS</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>PERMANENT ADDRESS:</strong></td>
					<td width="50%">'.$rst[0]['addressline1_pr'].', '.$rst[0]['addressline2_pr'].', '.$rst[0]['addressline3_pr'].', '.$rst[0]['addressline4_pr'].'<br>'.$rst[0]['district_pr'].', '.$rst[0]['city_pr'].'<br>'.$rst[0]['state_pr'].'<br>'.$rst[0]['pincode_pr'].'</td>
					</tr>';
					
					if($position_id != 7 && $position_id != 14 && $position_id != 12){
					$html.= '<tr>                    
					<td width="50%"><strong>CONTACT NUMBER:</strong></td>
					<td width="50%">'.$rst[0]['contact_number_pr'].'</td>
					</tr>';
					}
					
					if($position_id == 1 || $position_id == 2)
					{
						$html.= '<tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION</strong></h4></td><td></td></tr>'; 
					
						$html.= '<tr><td colspan="2">The date of passing eligibility examination will be the date appearing on the marksheet issued by the University/Institute. The percentage marks shall be arrived at by dividing the total marks obtained by the candidate in all the subjects in all semesters / years by aggregate maximum marks in all the subjects irrespective of optional/additional optional subject, if any. The fraction of percentage so arrived will be ignored i.e. 59.99% will be treated as less than 60%.</td></tr>'; 
					}
					$html.= '<tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION (ESSENTIAL)</strong></h4></td><td></td></tr>'; 
					                  
					
					foreach ($rst as $row) 
					{    
						if($position_id == 1 || $position_id == 2 || $position_id == 3 || $position_id == 6 || $position_id == 9 || $position_id == 7 || $position_id == 14 || $position_id == 12)
						{

							$html.= '<tr>                    
							<td width="50%"><strong>';
							if($position_id == 3 )
							$html.= 'Name of course (Post Graduate):';
							else
							$html.= 'NAME OF COURSE:';
							$html.= '</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['ess_course_name'].'</div></td>
							</tr>';
						}	
						
						else if($position_id == 7 || $position_id == 12 || $position_id == 14)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>NAME OF COURSE(POST GRADUATE):</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['ess_course_name'].'</div></td>
							</tr>
							
							<tr>                    
							<td width="50%"><strong>IF POST GRADUATION - STREAM & SUBJECT :</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['ess_pg_stream_subject'].'</div></td>
							</tr>';
						}
						else if($position_id == 10)
						{
							$html.= '	<tr>                    
							<td width="50%"><strong>DEGREE:</strong></td>                          
							<td width="50%"><div style="word-break:break-all;">'.$row['ess_course_name'].'</div></td>
							</tr>';
						}
						else if($position_id == 11 || $position_id == 7 || $position_id == 14 || $position_id == 12)
						{
							$html.= '	<tr>                    
							<td width="50%"><strong>GRADUATION STREAM:</strong></td>                          
							<td width="50%"><div style="word-break:break-all;">'.$row['ess_course_name'].'</div></td>
							</tr>';
						}
						/* else if($position_id == 4 || $position_id == 6)
							{
							$html.= '<tr>                    
							<td width="50%"><strong>NAME OF COURSE(POST GRADUATE):</strong></td>
							<td width="50%"> <div style="word-break:break-all;">'.$row['ess_course_name'].'</div></td>
							</tr>'; 
						} */
						
						/* if($position_id == 6)
							{
							$html.= '	<tr>                    
							<td width="50%"><strong>SUBJECT:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['deputy_subject'].'</div></td>
							</tr>';//<td width="50%">'.$row['deputy_subject'].'</td>
						} */
						if($position_id == 1)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>SUBJECT:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['ess_subject'].'</div></td>
							</tr>';
						}
						if($position_id == 1 || $position_id == 7 || $position_id == 14 || $position_id == 12)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['ess_college_name'].'</div></td>
							</tr>';
						}
						if($position_id == 1 || $position_id == 7 || $position_id == 14 || $position_id == 12)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>UNIVERSITY:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['ess_university'].'</div></td>
							</tr>';
						}
						if($position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 11)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>SUBJECT:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['ess_subject'].'</div></td>
							</tr>';
						}
						
						if($position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 11)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
							<td width="50%">'.$row['ess_college_name'].'</td>
							</tr>';
						}
						else if($position_id == 7 || $position_id == 12 || $position_id == 14)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>COLLEGE/ INSTITUTION NAME AND ADDRESS:</strong></td>
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
						else if($position_id == 6)
						{
							$html.= '	<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">'.$row['ess_university'].'</td>
							</tr>';
						}
						
						if($position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 11 || $position_id == 7 || $position_id == 12 || $position_id == 14)
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
						
						if($position_id == 10 || $position_id == 1)
						{
							$html.= '	<tr>                    
							<td width="50%"><strong>DATE OF COMPLETION OF THE DEGREE:</strong></td>
							<td width="50%">'.$row['ess_degree_completion_date'].'</td>
							</tr>';
						}
						
						if($position_id == 1 || $position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 7 || $position_id == 12 || $position_id == 14)
						{
							$html.= '	<tr>                    
							<td width="50%"><strong>AGGREGATE MARKS OBTAINED:</strong></td>
							<td width="50%">'.$row['ess_aggregate_marks_obtained'].'</td>
							</tr>
							
							<tr>                    
							<td width="50%"><strong>AGGREGATE MAXIMUM MARKS:</strong></td>
							<td width="50%">'.$row['ess_aggregate_max_marks'].'</td>
							</tr>';
						}
						
						if($position_id== 3)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">'.$row['ess_grade_marks'].'</td>
							</tr>';
						}
						else if($position_id == 2 || $position_id == 6 || $position_id == 9 || $position_id == 10 || $position_id == 11 || $position_id == 7 || $position_id == 12 || $position_id == 14)
						{
							$html.= '	<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">'.$row['ess_percentage'].'</td>
							</tr>';
						}
						/* else if($position_id == 1 || $position_id == 4 || $position_id == 6)
							{
							$html.= '<tr>                    
							<td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
							<td width="50%">'.$row['ess_grade_marks'].'</td>
							</tr>';
						} */
						
						if($position_id == 1 || $position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 7 || $position_id == 12 || $position_id == 14)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>CLASS/GRADE:</strong></td>
							<td width="50%">'.$row['ess_class'].'</td>
							</tr>'; 
						}                  
					}
					
					if($position_id == 7 || $position_id == 12 || $position_id == 3 || $position_id == 14)
						{
						$html.= '<tr><td style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td><td></td></tr>';
						
						$html.= '<tr>                    
						<td width="50%"><strong>CAIIB:</strong></td>
						<td width="50%">CAIIB</td>
						</tr>
						
						<tr>                    
						<td width="50%"><strong>YEAR OF PASSING:</strong></td>
						<td width="50%">'.$row['year_of_passing'].'</td>
						</tr>
						
						<tr>                    
						<td width="50%"><strong>MEMBERSHIP NUMBER:</strong></td>
						<td width="50%">'.$row['membership_number'].'</td>
						</tr>';
					}
					
					$html.= '<tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION (DESIRABLE)</strong></h4></td><td></td></tr>';  
					
				//	print_r($qualification_arr);exit;
					foreach ($qualification_arr as $row) 
					{   
						if($position_id == 2 || $position_id == 9)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>NAME OF CERTIFICATION COURSE:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['course_name'].'</div></td>
							</tr>';
						}
						else if($position_id == 10)
						{
							$course_code_nm = '';
							$course_code_arr = explode(",",$row['college_name']);
							if(count($course_code_arr) > 0)
							{
								foreach($course_code_arr as $course_code)
								{
									$course_code_nm .= '> '.$course_code.'<br>';
								}
							}
							
							$html.= '	<tr>                    
							<td colspan="2">'.$course_code_nm.'</td>
							</tr>';
						}
						else
						{    
							$html.= '<tr>                    
							<td width="50%"><strong>NAME OF COURSE:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['course_name'].'</div></td>
							</tr>';
						}
						
						if($position_id == 7 || $position_id == 12 || $position_id == 14)
						{
							$html.= '	<tr>                    
							<td width="50%"><strong>NAME & SUBJECT OF THE COURSE:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['name_subject_of_course'].'</div></td>
							</tr>';
						}
						
						/* if($position_id == 6)
							{
							$html.= '	<tr>                    
							<td width="50%"><strong>SPECIALISATION:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['specialisation'].'</div></td>
							</tr>';
						} */
						
						
						if($position_id == 3 || $position_id == 1 || $position_id == 6)  
						{
							$html.= '<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">'.$row['college_name'].'</td>
							</tr>';
						}
						else if($position_id == 6 || $position_id == 1)  
						{
							$html.= '<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">'.$row['university'].'</td>
							</tr>';
						}
						else if($position_id == 2 || $position_id == 9 || $position_id == 11 || $position_id == 7 || $position_id == 12 || $position_id == 14)
						{
							$html.= '	<tr>                    
							<td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
							<td width="50%">'.$row['college_name'].'</td>
							</tr>';
						}
						
						if($position_id == 2 || $position_id == 9 || $position_id == 7 || $position_id == 12 || $position_id == 14)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>UNIVERSITY:</strong></td>
							<td width="50%">'.$row['university'].'</td>
							</tr>';
						}
						else if($position_id == 11)
						{
							$html.= '	<tr>                    
							<td width="50%"><strong>UNIVERSITY/INSTITUTE:</strong></td>
							<td width="50%">'.$row['university'].'</td>
							</tr>';
						}
						
						if($position_id != 10)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>PERIOD:</strong></td>
							<td width="50%">'.$row['from_date']." to ".$row['to_date'].'</td>
							</tr>';
						}
						
						if($position_id == 2 || $position_id == 9 || $position_id == 7 || $position_id == 12 || $position_id == 1 || $position_id == 14)
						{
							$html.= '	<tr>                    
							<td width="50%"><strong>AGGREGATE MARKS OBTAINED:</strong></td>
							<td width="50%">'.$row['aggregate_marks_obtained'].'</td>
							</tr>
							
							<tr>                    
							<td width="50%"><strong>AGGREGATE MAXIMUM MARKS:</strong></td>
							<td width="50%">'.$row['aggregate_max_marks'].'</td>
							</tr>';
						}
						
						if($position_id == 3 || $position_id == 1 || $position_id == 6)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">'.$row['grade_marks'].'</td>
							</tr>';
						}
						else if($position_id == 1 || $position_id == 2 || $position_id == 6 || $position_id == 9 || $position_id == 11 || $position_id == 7 || $position_id == 12 || $position_id == 14)
						{
							$html.= '<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">'.$row['percentage'].'</td>
							</tr>';
						}
						/* else if($position_id == 1 || $position_id == 4 || $position_id == 6)
							{
							$html.= '	<tr>                    
							<td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
							<td width="50%">'.$row['grade_marks'].'</td>
							</tr>';
						} */
						
						if($position_id == 1 || $position_id == 2 || $position_id == 9 || $position_id == 7 || $position_id == 12 || $position_id == 14)/*  || $position_id == 1 || $position_id == 4 || $position_id == 6 */
						{
							$html.= '<tr>                    
							<td width="50%"><strong>CLASS/GRADE:</strong></td>
							<td width="50%">'.$row['class'].'</td>
							</tr>';  
						}                  
					}
					
					$html.= '<tr><td style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY</strong></h4></td><td></td></tr>';
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
						<td width="50%"><strong>RESPONSIBILITIES:</strong></td>
						<td width="50%">'.$rest['responsibilities'].'</td>
						</tr>'; 
						$html.= '<tr>                    
						<td width="50%"><strong>PERIOD:</strong></td>
						<td width="50%">'.$rest['job_from_date']." to ".$rest['job_to_date'].'</td>
						</tr>'; 
					}    
					
					if($position_id == 7 || $position_id == 12 || $position_id == 14)
					{
						$html.= '<tr>                    
						<td width="50%"><strong>EXPERIENCE AS FACULTY IN BANKING/FINANCIAL INSTITUTION:</strong></td>
						<td width="50%">'.$rst[0]['exp_in_bank'].'</td>
						</tr>';
						
						$html.= '<tr>                    
						<td width="50%"><strong>PUBLISHED ARTICLES/BOOKS:</strong></td>
						<td width="50%">'.$rst[0]['publication_of_books'].'</td>
						</tr>'; 
						
						$html.= '<tr>                    
						<td width="50%"><strong>EXPERIENCE IN ONE OR MORE COVERING THE FUNCTIONAL AREAS:</strong></td>
						<td width="50%">'.$rst[0]['exp_in_functional_area'].'</td>
						</tr>'; 
					}
					
					$html.= '	<tr><td style="color: #66d9ff"><h4><strong>LANGUAGES, EXTRACURRICULAR, ACHIEVEMENTS </strong></h4></td><td></td></tr>';
					
					if($position_id == 3)
					{
						$html.= '<tr>                    
						<td width="50%"><strong>EXPERIENCE AS FACULTY IN BANKING/FINANCIAL INSTITUTION:</strong></td>
						<td width="50%">'.$rst[0]['exp_in_bank'].'</td>
						</tr>';
						
						$html.= '<tr>                    
						<td width="50%"><strong>PUBLISHED ARTICLES/BOOKS:</strong></td>
						<td width="50%">'.$rst[0]['publication_of_books'].'</td>
						</tr>'; 
					}
					$html.= '
					<tr>                    
					<td width="50%"><strong>LANGUAGES KNOWN 1:</strong></td>
					<td width="50%">'.$rst[0]['languages_known'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES OPTIONS 1:</strong></td>
					<td width="50%">'.$rst[0]['languages_option'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES KNOWN 2:</strong></td>
					<td width="50%">'.$rst[0]['languages_known1'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES OPTIONS 2:</strong></td>
					<td width="50%">'.$rst[0]['languages_option1'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES KNOWN 3:</strong></td>
					<td width="50%">'.$rst[0]['languages_known2'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES OPTIONS 3:</strong></td>
					<td width="50%">'.$rst[0]['languages_option2'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EXTRACURRICULAR:</strong></td>
					<td width="50%">'.$rst[0]['extracurricular'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>HOBBIES:</strong></td>
					<td width="50%">'.$rst[0]['hobbies'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>ACHIEVEMENTS:</strong></td>
					<td width="50%">'.$rst[0]['achievements'].'</td>
					</tr>'; 
					
					if($position_id == 2 || $position_id == 3 || $position_id == 6 || $position_id == 9 || $position_id == 10)
					{
						$html.= '<tr>                    
						<td width="50%"><strong>DECLARATION: Have you ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification:</strong></td>
						<td width="50%">'.$rst[0]['declaration1'].'</td>
						</tr>'; 
						if($rst[0]['declaration1'] == 'Yes')
						{
							$html.= '<tr>                    
							<td width="50%"><strong>DECLARATION NOTE:</strong></td>
							<td width="50%">'.$rst[0]['declaration_note'].'</td>
							</tr>';                    
						}
					}
					
					if($position_id == 10 || $position_id == 7 || $position_id == 12 || $position_id == 14)
					{
						$html.= '	<tr><td style="color: #66d9ff"><h4><strong>REFERENCE</strong></h4></td><td></td></tr>
						<tr><td colspan="2">Candidates are instructed to provide two professional references. (References of family members, relatives & friends will not be considered)</td></tr>';
					}
					
					$html.= '	<tr><td style="color: #66d9ff"><h4><strong>PROFESSIONAL REFERENCE 1</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>NAME:</strong></td>
					<td width="50%">'.$rst[0]['refname_one'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>DESIGNATION:</strong></td>
					<td width="50%">'.$rst[0]['refdesignation_one'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>ORGANISATION:</strong></td>
					<td width="50%">'.$rst[0]['reforganisation_one'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
					<td width="50%">'.$rst[0]['refaddressline_one'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EMAIL ID:</strong></td>
					<td width="50%">'.$rst[0]['refemail_one'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>MOBILE:</strong></td>
					<td width="50%">'.$rst[0]['refmobile_one'].'</td>
					</tr>';                                                         
					$html.= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE 2</strong></h4></td><td></td></tr>';  
					$html.= '<tr>                    
					<td width="50%"><strong>NAME:</strong></td>
					<td width="50%">'.$rst[0]['refname_two'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>DESIGNATION:</strong></td>
					<td width="50%">'.$rst[0]['refdesignation_two'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>ORGANISATION:</strong></td>
					<td width="50%">'.$rst[0]['reforganisation_two'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
					<td width="50%">'.$rst[0]['refaddressline_two'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EMAIL ID:</strong></td>
					<td width="50%">'.$rst[0]['refemail_two'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>MOBILE:</strong></td>
					<td width="50%">'.$rst[0]['refmobile_two'].'</td>
					</tr>
					
					<tr><td style="color: #66d9ff"><h4><strong>OTHER INFORMATION</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>ANY OTHER INFORMATION THAT THE CANDIDATE WOULD LIKE TO ADD:</strong></td>
					<td width="50%"><div style="word-break:break-all;">'.$rst[0]['comment'].'</div></td>
					</tr>
					
					';

					if($position_id != 7 && $position_id != 12 && $position_id != 14) {
						$html.= '<tr>                    
					<td width="50%"><strong>DECLARATION : I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated without any notice.</strong></td>
					<td width="50%">'.$rst[0]['declaration2'].'</td>
					</tr>';
					}

					if($position_id == 7 || $position_id == 14) {
						$html.= '<tr>                    
					<td width="50%"><strong>DECLARATION : I declare that all statements made in this application are true, complete and correct to the best  of  my  knowledge  and  belief . I also declare that I  have  not  suppressed  any  material  fact(s)/information.  I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying  any  of  the  eligibility  criteria  according  to  the  requirements  of  the  related  advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</strong></td>
					<td width="50%">YES</td>
					</tr>';
					}

					if($position_id == 12) {
						$html.= '<tr>                    
					<td width="50%"><strong>DECLARATION : I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</strong></td>
					<td width="50%">YES</td>
					</tr>';
					}

					if($position_id ==1) {
						$html.= '<tr>                    
					<td width="50%"><strong>DECLARATION 2: I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leaves the service of the Institute before the expiry of the said period, a sum of Rs. 1,00,000/- (Rupees One Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Junior Executive dated 17-11-2022.</strong></td>
					<td width="50%">YES</td>
					</tr>';
					}
					if($position_id==3){
						$html.= '<tr>                    
					<td width="50%"><strong>DECLARATION 2:  I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leave the service of the Institute before the expiry of the said period, a sum of Rs. 2,00,000/- (Rupees Two Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Assistant Director (Academics) dated 17.11.2022.</strong></td>
					<td width="50%">YES</td>
					</tr>';
					}
					if($position_id==2){
						$html.= '<tr>                    
					<td width="50%"><strong>DECLARATION 2:  I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leave the service of the Institute before the expiry of the said period, a sum of Rs. 2,00,000/- (Rupees Two Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Assistant Director (IT) dated 17.11.2022</strong></td>
					<td width="50%">YES</td>
					</tr>';
					}
					if($position_id==6){
						$html.= '<tr>                    
					<td width="50%"><strong>DECLARATION 2:   I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leave the service of the Institute before the expiry of the said period, a sum of Rs. 2,00,000/- (Rupees Two Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Deputy Director (Accounts) dated 17.11.2022.</strong></td>
					<td width="50%">YES</td>
					</tr>';
					}
					if($position_id==7 || $position_id==14 || $position_id==12){
						$html.= '<tr>                    
					<td width="50%"><strong>DECLARATION 2:   I hereby agree that any legal proceedings in respect of any matter or claims or disputes arising out of application or out of said advertisement can be instituted by me at Mumbai only and shall have sole and exclusive jurisdiction to try any cause / dispute. I undertake to abide by all the terms and conditions of the advertisement given by the Indian Institute of Banking & Finance</strong></td>
					<td width="50%">YES</td>
					</tr>';
					$html.= '<tr>                    
					<td width="50%"><strong>DECLARATION 3:   I further certify that no disciplinary / vigilance proceedings are either pending or contemplated against me and that there are no legal cases pending against me. There have been no major / minor penalty</strong></td>
					<td width="50%">YES</td>
					</tr>';
					}
					$html.= '<tr><td style="color: #66d9ff"><h4><strong>PLACE AND DATE</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>PLACE:</strong></td>
					<td width="50%">'.$rst[0]['place'].'</td>
					</tr>
					
					<tr>
					<td width="50%"><strong>DATE:</strong></td>
					<td width="50%">'.$rst[0]['submit_date'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>SIGNATURE:</strong></td>
					<td><img width="70px" height="70px" src="'.base_url().'uploads/scansignature/'.$rst[0]['scannedsignaturephoto'].'" id="thumb" /></td>
					</tr>';                                                                                                                 
					
					$html.= '</tbody>
					</table>
					<div id="reason_form" style="display: none">';                                                     
					
					// echo $html; exit;
					// print_r($html);                                                   
					
					$pdf = $this->m_pdf->load();
					
					$pdfFilePath = $rst[0]['firstname'].'_'.$rst[0]['lastname'].'_'.$rst[0]['submit_date'].'_'.$position_id.'_'.$pdf_career_id.".pdf";

					$file_dir_name = date('Ymd');
					$directory_name = "./uploads/Careers_Data/".$file_dir_name;
					//mkdir($directory_name); 
					if (!file_exists($directory_name))
					{
						mkdir($directory_name);
					}

					$uri_segments_all = explode("/", $_SERVER['REQUEST_URI']); 
					$uri_segments_all = end($uri_segments_all);
					//die;
					if($cnt > 7) {  
						redirect(site_url('careers_admin/admin/Careers_position/'.$uri_segments_all)); 
					}
					
					
					$car_pdf_path = 'uploads/Careers_Data/'.$file_dir_name.'/'; 
					
					$file_arr[] = $pdfFilePath;
					
					if (!file_exists($car_pdf_path.$pdfFilePath) && $force_open_flag != 1) 
					{   						
						$pdf->WriteHTML($html); 
						$pdf->SetCompression(false);
						$pdf->SetDisplayMode('real');
						$pdf->SetDisplayMode('default');
						$pdf->SetAutoPageBreak(true); 
						$path = $pdf->Output('uploads/Careers_Data/'.$file_dir_name."/".$pdfFilePath, "F");   
						$cnt++;
					}else{
						$pdf->WriteHTML($html);
						$pdf->SetCompression(false);
						$pdf->SetDisplayMode('real');
						$pdf->SetDisplayMode('default');
						$pdf->SetAutoPageBreak(true);
					} 
					
					if($force_open_flag == 1) { 
						$path = $pdf->Output($pdfFilePath, "D"); 
					} 
					
					$pdf_generated_cnt++;
 
				}

				//print_r($file_arr);die;
				//print_r($file_dir_name); 
				//$zip = new ZipArchive(); // Load zip library  

				$file_dir_name = date('Ymd'); 
				$zip_name = 'career_files_'.date("YmdHis").rand().".zip";

				//file directory creation 
		    	$zip_folder_path = "uploads/Careers_Data/".$file_dir_name."/zip";
		    	$directory_name = "./uploads/Careers_Data/".$file_dir_name."/zip";
		    	//mkdir($directory_name); 
				if (!file_exists($directory_name))
		    	{
		    		mkdir($directory_name);
		    	}

				if (file_exists($zip_folder_path."/".$zip_name)) {
					@unlink($zip_folder_path."/".$zip_name); 
				}
				

				$zip = new ZipArchive;

				if ($zip->open($zip_folder_path . '/' . $zip_name, ZipArchive::CREATE) === TRUE) { 
					if (count($file_arr) > 0) {
						foreach ($file_arr as $file) {
							if($file != ""){
								$path = "./uploads/Careers_Data/".$file_dir_name."/".$file;
								if (file_exists($path)) {
									$filename_parts = explode('/', $path);  // Split the filename up by the '/' character
									$zip->addFile($path, end($filename_parts));  
								}
							} 
						}
					}
					$zip->close(); 
					  
				} 
				
				//START : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE
				  	$all_directories = $this->get_directory_list("./uploads/Careers_Data/");
				  	//print_r($all_directories);die;
				 	//echo count($all_directories);
					if(count($all_directories) > 0)
					{
						foreach($all_directories as $dir)
						{
							$explode_arr = explode("_", $dir, 2);
							//echo $explode_arr[0]."==".$dir;die;
							$chk_dir = str_replace("/","",$explode_arr[0]);
							//echo $chk_dir;die;
							if($chk_dir != date('Ymd'))
							{
								//echo "<br> Delete : ".$dir;
								$this->rmdir_recursive("uploads/Careers_Data/".$chk_dir);
							}
						}
					}				
				//END : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE

					if (count($file_arr) > 0){
						redirect(base_url('uploads/Careers_Data/'.$file_dir_name.'/zip/'.$zip_name));
					}
				
			}

			//echo "<br> PDF Generated Count : ".$pdf_generated_cnt;
			
			//redirect(base_url().'careers_admin/admin/Careers_position/career_position_list');
		}
		

		private function getPDFhtml($rst,$career_id,$position_id)
		{
			// <tr>                    
            //  <td width="50%"><strong>CV:</strong></td>
            //  <td><a href="'.base_url().'uploads/uploadcv/'.$rst[0]['uploadcv'].'" target="_blank" id="thumb" />Download</a>
            //  </td>
            // </tr>

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
					
					$application_title = 'SME ';

					$html.= '	<tr>                    
					<td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
					<td width="50%">'.$application_title.'</td>
					</tr>';

					$experience = explode(',',$rst[0]['exp_in_bank']);

					if($rst[0]['bank_education']=='bank')
					{ 
						$bankEducation = ucfirst($rst[0]['bank_education']); 
					} 
					else 
					{ 
						$bankEducation = 'Educational Institute'; 
					}

					$html.= '
					<tr>                    
					<td width="50%"><strong>NAME:</strong></td>
					<td width="50%">'.$rst[0]["firstname"].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>DATE OF BIRTH:</strong></td>
					<td width="50%">'.$rst[0]['dateofbirth'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EMAIL ID:</strong></td>
					<td width="50%">'.$rst[0]['email'].'</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>MOBILE NO:</strong></td>
					<td width="50%">'.$rst[0]['mobile'].'</td>
					</tr>
						
					<tr>                    
                      <td width="50%"><strong>Educational Qualification:</strong></td>
                      <td width="50%">'.$rst[0]['educational_qualification'].'</td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>CAIIB Qualification :</strong></td>
                      <td width="50%">'.ucfirst($rst[0]['CAIIB_qualification']).'</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>ADDRESS:</strong></td>
                      <td width="50%">'.$rst[0]['addressline1'].',<br>'.$rst[0]['addressline2'].' '.'<br>'.$rst[0]['city'].'<br>'.$rst[0]['state'].'<br>'.$rst[0]['pincode'].'</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Bank/Educational Institute:</strong></td>
                      <td width="50%">'.$bankEducation.'</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Organization Name:</strong></td>
                      <td width="50%">'.$rst[0]['ess_college_name'].'</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Retired/Working:</strong></td>
                      <td width="50%">'.ucfirst($rst[0]['retired_working']).'</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Total year of Work experience:</strong></td>
                      <td width="50%">'.$experience[0].' Year '.$experience[1].' Month</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Designation:</strong></td>
                      <td width="50%">'.$rst[0]['designation'].'</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>General:</strong></td>
                      <td>'.$rst[0]['general_subjects'].'</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Specialisation:</strong></td>
                      <td>'.$rst[0]['specialisation'].'</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Information Technology:</strong></td>
                      <td>'.$rst[0]['it_subjects'].'</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Other:</strong></td>
                      <td>'.$rst[0]['other_subjects'].'</td>
                    </tr>';
                    
                    $html.= '</tbody>
					</table>
					<div id="reason_form" style="display: none">';  

                return $html;    
		}

		/* public function pdf_ceo()
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
			print_r($qualification_arr);
			
			
			
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
			
			if($position_id == 6)
      {
			$html.= '<tr>                    
			<td width="50%"><strong>SUBJECT:</strong></td>
			<div style="word-break:break-all;">
			<td width="50%">'.$row['deputy_subject'].'</td>
			</div>
			</tr>';
      }
			
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
			
			if($position_id == 6)
			{
			$html.= '<tr>                    
			<td width="50%"><strong>SPECIALISATION:</strong></td>
			<div style="word-break:break-all;">
			<td width="50%">'.$row['specialisation'].'</td>
			</div>
			</tr>';
			}
			
			
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
			// $data['position'] = $position;
			redirect(base_url().'careers_admin/admin/Careers_position/career_position_list');
		}*/
		
		/*public function pdf_dda()
			{ 
			//$id = $this->uri->segment(5);die;
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
      $this->db->where('active_status', '1');
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
		}*/
		
		/*public function pdf_faculty()
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
			$this->db->where('active_status', '1');
			$sql = $this->master_model->getRecords("careers_registration",'','careers_id');
			//print_r($sql);die;
			
			foreach($sql as $rec)
			{
      $this->db->where('careers_id',$rec['careers_id']);
      $rst = $this->master_model->getRecords("careers_registration");
      //print_r($rst);die;
      $this->db->select('m.id,m.course_name,c.careers_id,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class');
      $this->db->join('careers_registration c','c.careers_id=q.careers_id','LEFT');
      $this->db->join('careers_course_mst m','m.course_code=q.course_code','LEFT');
      $this->db->where('c.careers_id',$rec['careers_id']);
      $this->db->where('c.active_status', '1');
      $qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");
      
			
      $this->db->select('c.careers_id,e.careers_id as employ_id,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date');
      $this->db->join('careers_registration c','c.careers_id=e.careers_id','LEFT');
      $this->db->where('c.careers_id',$rec['careers_id']);
      $this->db->where('c.active_status', '1');
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
		}*/



		/* GET ALL FOLDER LISTING FROM REQUIRED FOLDER  */
		function get_directory_list($dir_name)
		{
			return $this->array_sort_ascending(directory_map('./'.$dir_name, 1)); // This is use to get all folders and files from current directory excluding subfolders
		}
		
		/* SORT ARRAY IN ASCENDING ORDER USING VALUES NOT KEY */
		function array_sort_ascending($array)
		{
			if($array != "") { sort($array); /* sort() - sort arrays in ascending order. rsort() - sort arrays in descending order. */ }
			return $array;
		}
		
		/* RECURSIVE FUNCTION TO DELETE ALL SUB FILES AND FOLDER FROM REQUIRED FOLDER */
		function rmdir_recursive($dir) 
		{
			foreach(scandir($dir) as $file) 
			{
				if ('.' === $file || '..' === $file) continue;
				if (is_dir("$dir/$file")) 
				{
					$this->rmdir_recursive("$dir/$file");
				}
				else unlink("$dir/$file");
			}
			rmdir($dir);
		}

	}
?>