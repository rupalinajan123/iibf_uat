<?php
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/
defined('BASEPATH') or exit('No direct script access allowed');
class Careers_position extends CI_Controller
{
	public $UserID;
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('career_admin')) {
			redirect('careers_admin/admin/Login');
		} else {
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
		$this->db->where('id!=', 5);
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

		$this->session->set_userdata('field', '');
		$this->session->set_userdata('value', '');
		$this->session->set_userdata('per_page', '');
		$this->session->set_userdata('sortkey', '');
		$this->session->set_userdata('sortval', '');

		//$data = $this->getUserInfo();
		$res_arr            = array();
		$data["breadcrumb"] = '<ol class="breadcrumb"> 
			<li><a href="' . base_url() . 'careers_admin/admin/Careers_position/career_position_list">
			<i class="fa fa-home"></i> Summary Report</a></li>
			<li class="active"><a href="' . base_url() . 'careers_admin/admin/Careers_position/career_position_list">Career Admin</a></li>
			</ol>';


		$this->db->select('m.id,c.position_id,c.careers_id,c.firstname,c.middlename,c.lastname,c.email,c.mobile,c.alternate_mobile,c.scannedphoto,c.scannedsignaturephoto,c.createdon,m.position');
		$this->db->join('careers_position_master m', 'm.id=c.position_id', 'LEFT');
		$this->db->where('c.active_status', '1');
		$this->db->where('c.position_id!=', 5);
		$this->db->order_by('c.careers_id', 'DESC');
		$sql = $this->master_model->getRecords("careers_registration c");

		$i = 0;
		foreach ($sql as $rec) {
			$data_arr[$i]['careers_id']            = $rec['careers_id'];
			$data_arr[$i]['firstname']             = $rec['firstname'];
			$data_arr[$i]['middlename']            = $rec['middlename'];
			$data_arr[$i]['lastname']              = $rec['lastname'];
			$data_arr[$i]['email']                 = $rec['email'];
			$data_arr[$i]['mobile']                = $rec['mobile'];
			$data_arr[$i]['alternate_mobile']      = $rec['alternate_mobile'];
			$data_arr[$i]['scannedphoto']          = $rec['scannedphoto'];
			$data_arr[$i]['scannedsignaturephoto'] = $rec['scannedsignaturephoto'];
			$data_arr[$i]['createdon']             = $rec['createdon'];
			$data_arr[$i]['position']              = $rec['position'];
			$data_arr[$i]['position_id']           = $rec['position_id'];

			$this->db->where('careers_id', $rec['careers_id']);
			$careers_edu_qualification = $this->master_model->getRecords("careers_edu_qualification");
			$edu_arr                   = array();

			foreach ($careers_edu_qualification as $res) {
				$this->db->where('course_code', $res['course_code']);
				$careers_course_mst = $this->master_model->getRecords("careers_course_mst");
				foreach ($careers_course_mst as $val) {
					$edu_arr[]                 = $val['course_name'];
					$data_arr[$i]['education'] = implode(',', $edu_arr);
				}
			}
			$i++;
		}

		$data['reuest_list'] = $data_arr;
		// print_r($data);die;

		if (isset($_GET['submit'])) {
			$position  = $this->input->get('position');

			$from_date = $this->input->get('from_date');
			$to_date   = $this->input->get('to_date');


			$this->db->select('id,position');
			$career_position = $this->master_model->getRecords("careers_position_master");

			$this->db->select('m.id,c.position_id,c.careers_id,c.firstname,c.middlename,c.lastname,c.email,c.mobile,c.alternate_mobile,c.scannedphoto,c.scannedsignaturephoto,c.createdon,m.position');
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
			$data['list_count']  = count($sql);

			$this->load->view('careers_admin/admin/careers_position_list', $data);
		} elseif (isset($_GET['is_excel']) && $_GET['is_excel'] == 'yes') {
			$position  = $this->input->get('position');
			$from_date = $this->input->get('from_date');
			$to_date   = $this->input->get('to_date');

			if ($position != 5) {
				$this->db->select('m.id,cr.position_id,cr.careers_id,cr.firstname,cr.middlename,cr.lastname,cr.father_husband_name,cr.marital_status,cr.email,cr.pan_no,cr.aadhar_card_no,cr.mobile,cr.alternate_mobile,cr.scannedphoto,cr.scannedsignaturephoto,cr.submit_date,cr.bank_education,cr.dateofbirth,cr.CAIIB_qualification,cr.languages_known,cr.languages_option,cr.languages_known1,cr.languages_option1,cr.languages_known2,cr.languages_option2,cr.ess_subject,cr.addressline1,cr.addressline2,cr.addressline1_pr,cr.addressline2_pr,cr.city,cr.state,cr.pincode,cr.ess_college_name,cr.year_of_passing,cr.membership_number,cr.retired_working,cr.exp_in_bank,cr.designation,cr.general_subjects,cr.specialisation,cr.it_subjects,cr.other_subjects,cr.exp_in_bank,cr.educational_qualification,cr.refname_one,cr.refaddressline_one,cr.reforganisation_one,cr.refdesignation_one,cr.refemail_one,cr.refmobile_one,cr.refname_two,cr.refaddressline_two,cr.reforganisation_two,cr.refdesignation_two,cr.refemail_two,cr.refmobile_two,cr.publication_of_books,cr.publication_of_articles,cr.area_of_specialization,cr.earliest_date_of_joining,cr.exp_in_functional_area,cr.subject_handled,cr.comment,m.position,cod.spouse_name,ceq.college_name,cpg.post_qua_name,cpg.post_gra_sub,cpg.post_gra_college_name,cpg.post_gra_university,cpg.post_gra_from_date,cpg.post_gra_to_date,cpg.post_aggregate_marks_obtained,cpg.post_gra_aggregate_max_marks,cpg.post_gra_percentage,cpg.post_gra_class');
				$this->db->join('careers_position_master m', 'm.id=cr.position_id', 'LEFT');
			}
			$this->db->join('career_other_details AS cod', 'cr.careers_id = cod.careers_id', 'left');
			$this->db->join('careers_edu_qualification AS ceq', 'cr.careers_id = ceq.careers_id', 'left');
			$this->db->join('career_pg_details AS cpg', 'cr.careers_id = cpg.careers_id', 'left');

			/*if ($position == 5) {
				$this->db->select('cr.*,m.position');
				$this->db->from('careers_registration AS cr');
				$this->db->join('careers_position_master m', 'm.id=cr.position_id', 'LEFT');
				$this->db->where('cr.active_status', '1'); 
				
				// $this->db->join('career_other_details AS cod', 'cr.careers_id = cod.careers_id', 'left');
				// $this->db->join('careers_edu_qualification AS ceq', 'cr.careers_id = ceq.careers_id', 'left');
				// $this->db->join('careers_employment_hist AS ceh', 'cr.careers_id = ceh.careers_id', 'left');
				// $this->db->join('careers_qual_hist AS cq', 'cr.careers_id = cq.careers_id', 'left');
				// $this->db->join('careers_org_hist AS co', 'cr.careers_id = co.careers_id', 'left');
				// $this->db->join('careers_exp_org_hist AS ceo', 'cr.careers_id = ceo.careers_id', 'left');
				// $this->db->join('careers_exp_bank_hist AS ceb', 'cr.careers_id = ceb.careers_id', 'left');

			}*/

			if ($from_date != "" && $to_date != "" && $position != "") {
				$this->db->where('DATE(cr.createdon) >=', $from_date);
				$this->db->where('DATE(cr.createdon) <=', $to_date);
				$this->db->where('cr.position_id', $position);
			} else if ($from_date != "" && $to_date != "") {
				$this->db->where('DATE(cr.createdon) >=', $from_date);
				$this->db->where('DATE(cr.createdon) <=', $to_date);
			} else if ($from_date != "" && $position != "") {
				$this->db->where('DATE(cr.createdon)', $from_date);
				$this->db->where('cr.position_id', $position);
			} else if ($to_date != "" && $position != "") {
				$this->db->where('DATE(cr.createdon)', $to_date);
				$this->db->where('cr.position_id', $position);
			} else if ($from_date != "") {
				$this->db->where('DATE(cr.createdon) >=', $from_date);
				$this->db->where('DATE(cr.createdon) <=', $from_date);
			} else if ($to_date != "") {
				$this->db->where('DATE(cr.createdon) >=', $to_date);
				$this->db->where('DATE(cr.createdon) <=', $to_date);
			} else if ($position != "") {
				$this->db->where('cr.position_id', $position);
			}

			/*if ($position == 5) {
				$query  = $this->db->get();
				$excelData = $query->result_array();
			}

			if ($position != 5) {
				$this->db->where('cr.active_status', '1');
				$this->db->order_by('cr.careers_id', 'DESC');
				$excelData = $this->master_model->getRecords("careers_registration cr");
			}*/

			$this->db->where('cr.active_status', '1');
			$this->db->order_by('cr.careers_id', 'DESC');
			$excelData = $this->master_model->getRecords("careers_registration cr");
			// echo $this->db->last_query();
			// die;

			// Load Excel library
			$objPHPExcel = new PHPExcel();

			// Add data to the Excel sheet
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Sr.No.');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Candidate Name');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Email');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Contact No.');

			if ($position == 5 || $position == 3 || $position == 7 || $position == 12 || $position == 14 || $position == 19) {
				$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Marital Status');
				$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Spouse Name');
				$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Father/Husband Name');
				$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Mother Name');
				$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Birth Date');
				$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Alternate Mobile Number');
				$objPHPExcel->getActiveSheet()->setCellValue('K1', 'PAN Number');
				$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Aadhaar Card Number');
				$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Communication Address');
				$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Permanent Address');
				$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Educational Qualification I - Post Graduation');
				$objPHPExcel->getActiveSheet()->getStyle('O1')->getAlignment()->setWrapText(true);
				// $objPHPExcel->getActiveSheet()->setCellValue('P1', 'Educational Qualification II: Additional Qualifications/Certification');
				// $objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->setCellValue('P1', 'CAIIB Year of Passing');
				$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Membership Number');
				// $objPHPExcel->getActiveSheet()->setCellValue('R1', 'Ph.D (IN BANKING OR FINANCE)');
				$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Desirable');
				$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Employment History');
				// $objPHPExcel->getActiveSheet()->setCellValue('V1', 'Organization Details');
				$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Experience as Principal / Director of a banking staff training college / centre / management institution');
				$objPHPExcel->getActiveSheet()->getStyle('T1')->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->setCellValue('U1', 'Experience as Faculty');
				$objPHPExcel->getActiveSheet()->setCellValue('V1', 'Languages');
				$objPHPExcel->getActiveSheet()->setCellValue('W1', 'Professional Reference I');
				$objPHPExcel->getActiveSheet()->setCellValue('X1', 'Professional Reference II');
				$objPHPExcel->getActiveSheet()->setCellValue('Y1', 'Publication of Books');
				// $objPHPExcel->getActiveSheet()->setCellValue('AC1', 'Publication of articles (give latest, not more than ten)');
				// $objPHPExcel->getActiveSheet()->getStyle('AC1')->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->setCellValue('Z1', 'Area of Specialisation');
				// $objPHPExcel->getActiveSheet()->setCellValue('AD1', 'Earliest date of joining if selected');
				// $objPHPExcel->getActiveSheet()->setCellValue('AE1', 'Why do you consider yourself suitable for the post of CEO of this Institute');
				// $objPHPExcel->getActiveSheet()->getStyle('AE1')->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->setCellValue('AA1', 'Any other information that the candidate would like to add');
				$objPHPExcel->getActiveSheet()->getStyle('AA1')->getAlignment()->setWrapText(true);

				$colRange = 'A1:AA1';
			} elseif ($position == 13) {
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
					$mobile = $item['mobile'] . ' , ' . $item['alternate_mobile'];
				} else {
					$mobile = $item['mobile'];
				}


				$objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $key + 1);
				$objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $item['firstname'] . " " . $item['middlename'] . " " . $item['lastname']);
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $item['email']);
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $mobile);

				if ($position == 5 || $position == 3 || $position == 7 || $position == 12 || $position == 14 || $position == 19) {

					$comm_address = $item['addressline1'] . ', ' . $item['addressline2'] . ', ' . $item['addressline3'] . ', ' . $item['addressline4'] . ', ' . $item['city'] . ' ' . $item['state'] . ' ' . $item['pincode'];

					$perm_address = $item['addressline1_pr'] . ', ' . $item['addressline2_pr'] . ', ' . $item['addressline3_pr'] . ', ' . $item['addressline4_pr'] . ', ' . $item['city_pr'] . ' ' . $item['state_pr'] . ' ' . $item['pincode_pr'];

					$this->db->where('career_other_details.careers_id', $item['careers_id']);
					$career_other_data = $this->master_model->getRecords("career_other_details");
					$this->db->where('career_pg_details.careers_id', $item['careers_id']);
					$career_pg_details = $this->master_model->getRecords("career_pg_details");

					$post_qualification_data = '';
					$post_qualification_data .= ' 1. Qualification : ' . $career_pg_details[0]['post_qua_name'] . "\n";
					$post_qualification_data .= ' 2. Post Graduation Subject : ' . $career_pg_details[0]['post_gra_sub'] . "\n";
					$post_qualification_data .= ' 3. College/Institution : ' . $career_pg_details[0]['post_gra_college_name'] . "\n";
					$post_qualification_data .= ' 4. University : ' . $career_pg_details[0]['post_gra_university'] . "\n";
					$post_qualification_data .= ' 5. Period : ' . $career_pg_details[0]['post_gra_from_date'] . ' to ' . $career_pg_details[0]['post_gra_to_date'] . "\n";
					$post_qualification_data .= ' 6. Percentage Obtained : ' . $career_pg_details[0]['post_gra_percentage'] . "\n";
					$post_qualification_data .= ' 7. Class/Grade : ' . $career_pg_details[0]['post_gra_class'] . "\n";


					$is_currently_service = 'Yes';
					if ($career_other_data[0]['vrs_register_date'] != '') {
						$is_currently_service = 'No';
					}

					$employment_data = '';
					$employment_data .= ' 1. Whether currently in service : ' . $is_currently_service . "\n";

					if ($is_currently_service == 'Yes') {
						$employment_data .= ' 2. Name of the Present Organisation : ' . $career_other_data[0]['name_of_present_organization'] . "\n";
						$employment_data .= ' 3. Period : ' . $career_other_data[0]['service_from_date'] . "\n";
						$employment_data .= ' 4. Communication Address of the Organisation : ' . $career_other_data[0]['comm_address_of_org'] . "\n";
						$employment_data .= ' 5. Current Designation/Post Held : ' . $career_other_data[0]['curr_designation'] . "\n";
						$employment_data .= ' 6. Current Responsibilities : ' . $career_other_data[0]['any_other_details'] . "\n";
						$employment_data .= '-----------------------------------------------------------------------------' . "\n\n";
					} else {
						$employment_data .= ' 2. Date of Superannuation/VRS/Resignation : ' . $career_other_data[0]['vrs_register_date'] . "\n";
						$employment_data .= ' 3. Reason for Resignation/Leaving : ' . $career_other_data[0]['reason_of_resign'] . "\n";
						$employment_data .= '-----------------------------------------------------------------------------' . "\n\n";
					}

					$this->db->where('careers_employment_hist.careers_id', $item['careers_id']);
					$career_employment_data = $this->master_model->getRecords("careers_employment_hist");

					if (isset($career_employment_data) && count($career_employment_data) > 0) {
						foreach ($career_employment_data as $key => $employment_data_value) {
							if ($key > 0) {
								$employment_data .= '-----------------------------------------------------------------------------' . "\n\n";
							}

							$employment_data .= ' 1. Name of the Previous Organisation : ' . $employment_data_value['organization'] . "\n";
							$employment_data .= ' 2. Period : ' . $employment_data_value['job_from_date'] . ' to ' . $employment_data_value['job_to_date'] . "\n";
							$employment_data .= ' 3. Previous Designation/Post Held : ' . $employment_data_value['designation'] . "\n";
							$employment_data .= ' 4. Previous Responsibilities : ' . $employment_data_value['responsibilities'] . "\n";
						}
					}

					$this->db->where('careers_org_hist.careers_id', $item['careers_id']);
					$career_organization_data = $this->master_model->getRecords("careers_org_hist");

					$organization_data = '';

					if (isset($career_organization_data) && count($career_organization_data) > 0) {
						foreach ($career_organization_data as $akey => $organization_data_val) {
							if ($akey > 0) {
								$organization_data .= '-----------------------------------------------------------------------------' . "\n\n";
							}

							$organization_data .= ' 1. Name of the Organisation : ' . $organization_data_val['org_organization'] . "\n";
							$organization_data .= ' 2. Period : ' . $organization_data_val['job_from_date'] . ' to ' . $organization_data_val['job_from_date'] . "\n";
							$organization_data .= ' 3. Designation : ' . $organization_data_val['designation'] . "\n";
							$organization_data .= ' 4. Responsibilities : ' . $organization_data_val['responsibilities'] . "\n";
						}
					}

					$this->db->where('careers_employment_hist.careers_id', $item['careers_id']);
					$careers_employment_hist = $this->master_model->getRecords("careers_employment_hist");

					$exp_organization_data = '';

					if (isset($careers_employment_hist) && count($careers_employment_hist) > 0) {
						foreach ($careers_employment_hist as $bkey => $exp_organization_data_val) {
							if ($bkey > 0) {
								$exp_organization_data .= '-----------------------------------------------------------------------------' . "\n\n";
							}

							$exp_organization_data .= ' 1. Name of the Organisation : ' . $exp_organization_data_val['organization'] . "\n";
							$exp_organization_data .= ' 2. Period : ' . $exp_organization_data_val['job_from_date'] . ' to ' . $exp_organization_data_val['job_to_date'] . "\n";
							$exp_organization_data .= ' 3. Designation : ' . $exp_organization_data_val['designation'] . "\n";
							$exp_organization_data .= ' 4. Responsibilities : ' . $exp_organization_data_val['responsibilities'] . "\n";
						}
					}

					$this->db->where('careers_registration.careers_id', $item['careers_id']);
					$careers_registration = $this->master_model->getRecords("careers_registration");

					$exp_faculty_data = '';

					if (isset($careers_registration) && count($careers_registration) > 0) {
						foreach ($careers_registration as $ckey => $exp_faculty_data_val) {
							if ($ckey > 0) {
								$exp_faculty_data .= '-----------------------------------------------------------------------------' . "\n\n";
							}

							$exp_faculty_data .= ' 1. Experience as Faculty : ' . $exp_faculty_data_val['exp_in_bank'] . "\n";
							$exp_faculty_data .= ' 2. Period : ' . $exp_faculty_data_val['exp_faculty_from_date'] . ' to ' . $exp_faculty_data_val['exp_faculty_to_date'] . "\n";
							$exp_faculty_data .= ' 3. Subjects Handled : ' . $exp_faculty_data_val['subject_handled'] . "\n";
							$exp_faculty_data .= ' 4. Area of Specialisation : ' . $exp_faculty_data_val['exp_in_functional_area'] . "\n";
							$exp_faculty_data .= ' 5. Membership of Professional Associations : ' . $career_other_data[0]['professional_ass'] . "\n";
						}
					}


					$this->db->where('careers_qual_hist.careers_id', $item['careers_id']);
					$career_qualificartion_data = $this->master_model->getRecords("careers_qual_hist");

					$additional_qualification_data = '';

					if (isset($career_qualificartion_data) && count($career_qualificartion_data) > 0) {
						foreach ($career_qualificartion_data as $dkey => $qualificartion_data) {
							if ($dkey > 0) {
								$additional_qualification_data .= '-----------------------------------------------------------------------------' . "\n\n";
							}

							$additional_qualification_data .= ' 1. Qualification : ' . $qualificartion_data['cer_qua_name'] . "\n";
							$additional_qualification_data .= ' 2. Post Graduation Subject : ' . $qualificartion_data['cer_gra_sub'] . "\n";
							$additional_qualification_data .= ' 3. College/Institution : ' . $qualificartion_data['cer_college_name'] . "\n";
							$additional_qualification_data .= ' 4. University : ' . $qualificartion_data['cer_university'] . "\n";
							$additional_qualification_data .= ' 5. Period : ' . $qualificartion_data['cer_from_date'] . ' to ' . $qualificartion_data['cer_to_date'] . "\n";
							$additional_qualification_data .= ' 6. Percentage Obtained : ' . $qualificartion_data['cer_percentage'] . "\n";
							$additional_qualification_data .= ' 7. Class/Grade : ' . $qualificartion_data['cer_class'] . "\n";
						}
					}

					$phd_banking_finance_data  = '';
					$phd_banking_finance_data .= ' 1. Name of Research Topic : ' . $item['phd_course'] . "\n";
					$phd_banking_finance_data .= ' 2. University : ' . $item['phd_university'] . "\n";
					$phd_banking_finance_data .= ' 3. Period : ' . $career_other_data[0]['phd_from_date'] . ' to ' . $career_other_data[0]['phd_to_date'] . "\n";



					$this->db->where('careers_edu_qualification.careers_id', $item['careers_id']);
					$career_desirable_qualificartion_data = $this->master_model->getRecords("careers_edu_qualification");

					$desirable_qualification_data  = '';
					$desirable_qualification_data .= ' 1. Name of course : ' . $career_desirable_qualificartion_data[0]['course_code'] . "\n";
					$desirable_qualification_data .= ' 2. Specialisation : ' . $career_desirable_qualificartion_data[0]['name_subject_of_course'] . "\n";
					$desirable_qualification_data .= ' 3. College Name and Address : ' . $career_desirable_qualificartion_data[0]['college_name'] . "\n";
					$desirable_qualification_data .= ' 4. University : ' . $career_desirable_qualificartion_data[0]['university'] . "\n";
					$desirable_qualification_data .= ' 5. Period : ' . $career_desirable_qualificartion_data[0]['from_date'] . ' to ' . $career_desirable_qualificartion_data[0]['from_date'] . "\n";
					
					$desirable_qualification_data .= ' 6. Percentage Obtained : ' . $career_desirable_qualificartion_data[0]['percentage'] . "\n";
					$desirable_qualification_data .= ' 7. Class : ' . $career_desirable_qualificartion_data[0]['class'] . "\n";


					// $this->db->where('careers_registration.careers_id', $item['careers_id']);
					// $careers_registration = $this->master_model->getRecords("careers_registration");
					$language = '';

					$language .= $item['languages_known'] . ' : ' . $item['languages_option'] . "\n";
					$language .= $item['languages_known1'] . ' : ' . $item['languages_option1'] . "\n";
					$language .= $item['languages_known2'] . ' : ' . $item['languages_option2'] . "\n";

					$professional_ref1  = '';
					$professional_ref1 .= ' 1. Name : ' . $item['refname_one'] . "\n";
					$professional_ref1 .= ' 2. Complete Address : ' . $item['refaddressline_one'] . "\n";
					$professional_ref1 .= ' 3. Organisation (If employed) : ' . $item['reforganisation_one'] . "\n";
					$professional_ref1 .= ' 4. Designation : ' . $item['refdesignation_one'] . "\n";
					$professional_ref1 .= ' 5. Email Id : ' . $item['refemail_one'] . "\n";
					$professional_ref1 .= ' 6. Mobile Number : ' . $item['refmobile_one'] . "\n";

					$professional_ref2  = '';
					$professional_ref2 .= ' 1. Name : ' . $item['refname_two'] . "\n";
					$professional_ref2 .= ' 2. Complete Address : ' . $item['refaddressline_two'] . "\n";
					$professional_ref2 .= ' 3. Organisation (If employed) : ' . $item['reforganisation_two'] . "\n";
					$professional_ref2 .= ' 4. Designation : ' . $item['refdesignation_two'] . "\n";
					$professional_ref2 .= ' 5. Email Id : ' . $item['refemail_two'] . "\n";
					$professional_ref2 .= ' 6. Mobile Number : ' . $item['refmobile_two'] . "\n";

					$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $item['marital_status']);
					$objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $career_other_data[0]['spouse_name']);
					$objPHPExcel->getActiveSheet()->setCellValue('G' . $row, ucfirst($item['father_husband_name']));
					$objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $career_other_data[0]['mother_name']);
					$objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $item['dateofbirth']);
					$objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $item['alternate_mobile']);
					$objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $item['pan_no']);
					$objPHPExcel->getActiveSheet()->setCellValue('L' . $row, $item['aadhar_card_no']);
					$objPHPExcel->getActiveSheet()->setCellValue('M' . $row, $comm_address);
					$objPHPExcel->getActiveSheet()->getStyle('M' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->setCellValue('N' . $row, $perm_address);
					$objPHPExcel->getActiveSheet()->getStyle('N' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('N')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('O' . $row, $post_qualification_data);
					$objPHPExcel->getActiveSheet()->getStyle('O' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('O')->getAlignment()->setWrapText(true);

					// $objPHPExcel->getActiveSheet()->setCellValue('P' . $row, $additional_qualification_data);
					// $objPHPExcel->getActiveSheet()->getStyle('P' . $row)->getAlignment()->setWrapText(true);
					// $objPHPExcel->getActiveSheet()->getStyle('P')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('P' . $row, $item['year_of_passing']);
					$objPHPExcel->getActiveSheet()->setCellValue('Q' . $row, $item['membership_number']);

					// $objPHPExcel->getActiveSheet()->setCellValue('R' . $row, $phd_banking_finance_data);
					// $objPHPExcel->getActiveSheet()->getStyle('R' . $row)->getAlignment()->setWrapText(true);
					// $objPHPExcel->getActiveSheet()->getStyle('R')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('R' . $row, $desirable_qualification_data);
					$objPHPExcel->getActiveSheet()->getStyle('R' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('R')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('S' . $row, $employment_data);
					$objPHPExcel->getActiveSheet()->getStyle('S' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('S')->getAlignment()->setWrapText(true);

					// $objPHPExcel->getActiveSheet()->setCellValue('U' . $row, $organization_data);
					// $objPHPExcel->getActiveSheet()->getStyle('U' . $row)->getAlignment()->setWrapText(true);
					// $objPHPExcel->getActiveSheet()->getStyle('U')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('T' . $row, $exp_organization_data);
					$objPHPExcel->getActiveSheet()->getStyle('T' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('T')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('U' . $row, $exp_faculty_data);
					$objPHPExcel->getActiveSheet()->getStyle('U' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('U')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('V' . $row, $language);
					$objPHPExcel->getActiveSheet()->getStyle('V' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('V')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('W' . $row, $professional_ref1);
					$objPHPExcel->getActiveSheet()->getStyle('W' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('W')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('X' . $row, $professional_ref2);
					$objPHPExcel->getActiveSheet()->getStyle('X' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('X')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('Y' . $row, $item['publication_of_books']);
					$objPHPExcel->getActiveSheet()->getStyle('Y' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('Y')->getAlignment()->setWrapText(true);

					// $objPHPExcel->getActiveSheet()->setCellValue('AC' . $row, $item['publication_of_articles']);
					// $objPHPExcel->getActiveSheet()->getStyle('AC' . $row)->getAlignment()->setWrapText(true);
					// $objPHPExcel->getActiveSheet()->getStyle('AC')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('Z' . $row, $item['exp_in_functional_area']);
					$objPHPExcel->getActiveSheet()->getStyle('Z' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('Z')->getAlignment()->setWrapText(true);

					// $objPHPExcel->getActiveSheet()->setCellValue('AE' . $row, $item['earliest_date_of_joining']);
					// $objPHPExcel->getActiveSheet()->getStyle('AE' . $row)->getAlignment()->setWrapText(true);
					// $objPHPExcel->getActiveSheet()->getStyle('AE')->getAlignment()->setWrapText(true);

					// $objPHPExcel->getActiveSheet()->setCellValue('AF' . $row, $item['suitable_of_the_post_of_CEO']);
					// $objPHPExcel->getActiveSheet()->getStyle('AF' . $row)->getAlignment()->setWrapText(true);
					// $objPHPExcel->getActiveSheet()->getStyle('AF')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()->setCellValue('AA' . $row, $item['comment']);
					$objPHPExcel->getActiveSheet()->getStyle('AA' . $row)->getAlignment()->setWrapText(true);
					$objPHPExcel->getActiveSheet()->getStyle('AA')->getAlignment()->setWrapText(true);

					$objPHPExcel->getActiveSheet()
						->getStyle('A1:' . $objPHPExcel->getActiveSheet()->getHighestColumn() . $objPHPExcel->getActiveSheet()->getHighestRow())
						->getAlignment()
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				} elseif ($position == 13) {
					$address = $item['addressline1'] . ', ' . $item['addressline2'] . ' ' . $item['city'] . ' ' . $item['state'] . ' ' . $item['pincode'];

					$experience = explode(',', $item['exp_in_bank']);

					if ($item['bank_education'] == 'bank') {
						$bankEducation = ucfirst($item['bank_education']);
					} else {
						$bankEducation = 'Educational Institute';
					}

					$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $item['dateofbirth']);
					$objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $item['educational_qualification']);
					$objPHPExcel->getActiveSheet()->setCellValue('G' . $row, ucfirst($item['CAIIB_qualification']));
					$objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $address);
					$objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $bankEducation);
					$objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $item['ess_college_name']);
					$objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $item['retired_working']);
					$objPHPExcel->getActiveSheet()->setCellValue('L' . $row, $experience[0] . ' Year ' . $experience[1] . ' Month');
					$objPHPExcel->getActiveSheet()->setCellValue('M' . $row, $item['designation']);
					$objPHPExcel->getActiveSheet()->setCellValue('N' . $row, $item['general_subjects']);
					$objPHPExcel->getActiveSheet()->setCellValue('O' . $row, $item['specialisation']);
					$objPHPExcel->getActiveSheet()->setCellValue('P' . $row, $item['it_subjects']);
					$objPHPExcel->getActiveSheet()->setCellValue('Q' . $row, $item['other_subjects']);
					$objPHPExcel->getActiveSheet()->setCellValue('R' . $row, $item['position']);
					$objPHPExcel->getActiveSheet()->setCellValue('S' . $row, $item['submit_date']);
				} else {
					$objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $item['position']);
					$objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $item['submit_date']);
				}
				// Add more columns as needed
				$row++;
			}

			if ($position == 5 || $position == 3 || $position == 7 || $position == 12 || $position == 14 || $position == 19) {
				$fixedWidth = 50;
				$fixedSmallWidth = 25; // Define your desired width (e.g., 15)

				// Loop through the column letters from A to L
				foreach (range('A', 'N') as $columnID) {
					// Apply the setWidth method to each column dimension individually
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setWidth($fixedSmallWidth);
				}
				$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth($fixedSmallWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth($fixedWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth($fixedWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth($fixedWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth($fixedWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth($fixedWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth($fixedWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth($fixedWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setWidth($fixedWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setWidth($fixedWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setWidth($fixedWidth);
				$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth($fixedWidth);
				// $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth($fixedWidth);
				// $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth($fixedWidth);
				// $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth($fixedWidth);
				// $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth($fixedWidth);
				// $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth($fixedWidth);
				// $objPHPExcel->getActiveSheet()->getStyle('AF')->getAlignment()->setWrapText(true);
				// $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth($fixedWidth);

				// $fixedWidthColumns = ['O', 'P', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG'];
				$fixedWidthColumns = ['O', 'P', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA'];

				$fixedWidth = 50;
				// Set column widths to auto
				$highestCol = $objPHPExcel->getActiveSheet()->getHighestDataColumn();

				foreach (range('A', $highestCol) as $col) {

					// Skip the columns that already have fixed settings
					if (in_array($col, $fixedWidthColumns)) {
						continue;
					}

					// Apply AutoSize to the remaining columns
					$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
				}
			}

			// Set headers for download
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $positionName . '-Report.xls"');
			header('Cache-Control: max-age=0');

			//echo $this->db->last_query();

			// Save Excel file to PHP output
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');

			// $data['reuest_list'] = $sql;
			// $this->load->view('careers_admin/admin/careers_position_list',$data);
		} else {
			$this->load->view('careers_admin/admin/careers_position_list', $data);
		}
	}

	public function pdf()
	{
		ini_set("memory_limit", "-1");

		$id = $this->uri->segment(5);

		$this->db->where('careers_id', $id);
		$rst = $this->master_model->getRecords("careers_registration");

		$this->db->select('m.id,m.position,c.position_id,c.careers_id,c.firstname,c.middlename,c.lastname,c.email,c.submit_date,c.mobile,c.scannedphoto,c.alternate_mobile,c.scannedsignaturephoto');
		$this->db->join('careers_position_master m', 'm.id=c.position_id', 'LEFT');
		$this->db->order_by('c.careers_id', 'ASC');
		$this->db->where('c.active_status', '1');
		$res_arr = $this->master_model->getRecords("careers_registration c");
		//print_r($res_arr);die;


		$html = '<h1 style="text-align:center">CANDIDATE LIST</h1>';
		$html .= '<div class="table-responsive ">
			<table width="900" id="listitems2" class="table table-bordered table-striped dataTables-example" style="overflow: wrap" border="1" style="border-collapse: collapse">
			<tbody>';
		$html .= '<thead>
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
		$html .= '<tbody class="no-bd-y" id="list2">';
		$k = 1;
		if (count($res_arr) > 0) {
			foreach ($res_arr as $res) {
				$html .= '<tr><td>' . $k . ' </td>';
				$html .= '<td>' . $res['firstname'] . " " . $res['middlename'] . " " . $res['lastname'] . ' </td>';
				$html .= '<td>' . $res['email'] . ' </td>';
				$html .= '<td>' . $res['mobile'] . " , " . $res['alternate_mobile'] . ' </td>';
				$html .= '<td>' . $res['position'] . ' </td>';
				$html .= '<td><img width="70px" height="70px" src="' . base_url() . 'uploads/photograph/' . $res['scannedphoto'] . '"></img></td>';
				$html .= '<td><img width="70px" height="70px" src="' . base_url() . 'uploads/scansignature/' . $res['scannedsignaturephoto'] . '"></img></td>';
				$html .= '<td>' . $res['submit_date'] . ' </td>';
				$k++;
			}
		}
		$html .= '</tbody>
			</table>
			<div id="reason_form" style="display: none">';

		$pdf         = $this->m_pdf->load();
		$pdfFilePath = $rst[0]['mobile'] . ".pdf";

		$pdf->WriteHTML($html);
		$pdf->SetCompression(false);
		$pdf->SetDisplayMode('real');
		$pdf->SetDisplayMode('default');

		$pdf->SetAutoPageBreak(true);


		$path = $pdf->Output($pdfFilePath, "D");

		$this->load->view('careers_admin/admin/pdf_view', $path);
	}



	public function pdf_record($career_id = 0, $position_id = 0, $from_date = 0, $to_date = 0)
	{


		$force_open_flag = 0;


		if (isset($_GET['position'])) {
			$position_id = $_GET['position'];
		}
		if (isset($_GET['from_date'])) {
			$from_date = $_GET['from_date'];
		}
		if (isset($_GET['to_date'])) {
			$to_date = $_GET['to_date'];
		}

		$this->db->where('position_id', $position_id);

		$this->db->where('active_status', '1');
		if ($career_id != '0') {
			$this->db->where('careers_id', $career_id);
			$force_open_flag = 1;
		}
		if ($from_date != '0') {
			$this->db->where('DATE(createdon) >=', $from_date);
		}
		if ($to_date != '0') {
			$this->db->where('DATE(createdon) <=', $to_date);
		}
		if ($position_id == 1)
			$this->db->where('pay_status', '1');
		$sql = $this->master_model->getRecords("careers_registration", '', 'careers_id');

		//echo "<br> QRY : ".$this->db->last_query();exit;
		// echo "<br> Record Count : ".count($sql); exit;

		if ($position_id == 13) {
			if ($career_id > 0) {
				$this->db->where('careers_id', $career_id);
			}

			$rst = $this->master_model->getRecords("careers_registration");
			// echo "<pre>";  print_r($rst); exit;
			$pdf_html = $this->getPDFhtml($rst, $career_id, $position_id);

			$pdf = $this->m_pdf->load();

			$pdfFilePath = $rst[0]['firstname'] . '_' . $position_id . '_' . $pdf_career_id . ".pdf";

			$pdf->WriteHTML($pdf_html);
			$pdf->SetCompression(false);
			$pdf->SetDisplayMode('real');
			$pdf->SetDisplayMode('default');
			$pdf->SetAutoPageBreak(true);

			if ($force_open_flag == 1) {
				$path = $pdf->Output($pdfFilePath, "D");
			} else {
				$path = $pdf->Output('uploads/Careers_Data/' . $pdfFilePath, "F");
			}
			$pdf_generated_cnt++;
			exit;
		}

		//	echo $this->db->last_query();
		//echo "<br> Record Count : ".count($sql); exit;
		$pdf_generated_cnt = $cnt = 0;

		if (count($sql) > 0) {
			$file_arr = array();
			foreach ($sql as $rec) {
				$pdf_career_id = $rec['careers_id'];
				$this->db->where('careers_id', $pdf_career_id);
				$this->db->where('active_status', '1');

				if ($position_id != 7 &&  $position_id != 15 && $position_id != 16 && $position_id != 14 && $position_id != 12) {
					$this->db->where('pay_status', '1');
				}

				$head_title = 'APPLICATION ';

				if ($position_id  == 7) {
					$head_title = 'Faculty Member - PDC (NZ) New Delhi on contract basis';
				} else if ($position_id  == 12) {
					$head_title = 'Application for the post of Head PDC (SZ) - MUMBAI on contract basis';
				} else if ($position_id  == 14) {
					$head_title = 'Application for the post of Faculty Member (Information Technology) on contract basis';
				} else if ($position_id  == 15) {
					$head_title = 'In-charge, Development Centre, Lucknow on contract basis';
				} else if ($position_id  == 17) {
					$head_title = 'In-charge, Development Centre, Bengaluru on contract basis';
				} else if ($position_id  == 16) {
					$head_title = 'In-charge, Development Centre, Guwahati on contract basis';
				} else if ($position_id  == 8) {
					$head_title = 'Corporate Development Officer On contract basis';
				} else if ($position_id  == 1) {
					$head_title = 'for the post of Junior Executive';
				} else if ($position_id  == 5) {
					$head_title = 'for the post of Chief Executive Officer';
				} else if ($position_id  == 19) {
					$head_title = 'Resident Engineer on Retainer Basis (On Contract)';
				}


				$rst = $this->master_model->getRecords("careers_registration");
				//echo "SQL>" . $this->db->last_query();exit;
				$this->db->select('m.id,m.course_name,c.careers_id,c.specialisation,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class, q.aggregate_marks_obtained, q.aggregate_max_marks, q.percentage, q.name_subject_of_course');
				$this->db->join('careers_registration c', 'c.careers_id=q.careers_id', 'LEFT');
				$this->db->join('careers_course_mst m', 'm.course_code=q.course_code', 'LEFT');
				$this->db->where('c.careers_id', $pdf_career_id);
				$this->db->where('active_status', '1');
				//$this->db->where('q.careers_id', '42');
				$qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");

				//print_r($qualification_arr);exit;

				$this->db->select('c.careers_id,e.careers_id as employ_id,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date');
				$this->db->join('careers_registration c', 'c.careers_id=e.careers_id', 'LEFT');
				$this->db->where('c.careers_id', $pdf_career_id);
				$this->db->where('c.active_status', '1');
				$emp_hist_arr = $this->master_model->getRecords('careers_employment_hist e');
				//print_r($emp_hist_arr); 

				$this->db->where('state_code', $rst[0]["state_pr"]);
				$stateDetails = $this->master_model->getRecords('state_master');

				if ($position_id == 1) {
					$this->db->select('transaction_no,receipt_no,amount');
					$this->db->where('member_regnumber', $rst[0]["reg_id"]);
					$this->db->where('pay_type', '22');
					$this->db->where('status', '1');
					$payment_transaction = $this->master_model->getRecords('payment_transaction');
				}
				if ($position_id == 1)
					$this->db->where('pay_status', '1');

				if ($position_id == 3 || $position_id == 4 || $position_id == 12 || $position_id == 7 || $position_id == 14 || $position_id == 15 || $position_id == 16  || $position_id == 17  || $position_id == 1 || $position_id == 8 || $position_id == 5 || $position_id == 19) {
					$this->db->where('careers_id', $pdf_career_id);
					$rst = $this->master_model->getRecords("careers_registration");
					// echo "<pre>";  print_r($rst); exit;

					if ($position_id == 5) {
						$pdf_html = $this->getCustomCEOPDFhtml($rst, $pdf_career_id, $position_id, $emp_hist_arr);
					} else {
						$pdf_html = $this->getCustomPDFhtml($rst, $pdf_career_id, $position_id, $emp_hist_arr);
					}

					//echo $pdf_html;die;

					$pdf = $this->m_pdf->load();

					$pdfFilePath = $rst[0]['firstname'] . '_' . $position_id . '_' . $pdf_career_id . ".pdf";

					$pdf->WriteHTML($pdf_html);
					$pdf->SetCompression(false);
					$pdf->SetDisplayMode('real');
					$pdf->SetDisplayMode('default');
					$pdf->SetAutoPageBreak(true);

					if ($force_open_flag == 1) {
						$path = $pdf->Output($pdfFilePath, "D");
					} else {
						$path = $pdf->Output('uploads/Careers_Data/' . $pdfFilePath, "F");
					}
					$pdf_generated_cnt++; //exit;
				}

				$spouse_name = $mother_name = $religion = $physical_disbaility = $physical_disbaility_desc = $post_qua_name = $post_gra_sub = $post_gra_college_name = $post_gra_university = $post_gra_from_date = $post_gra_to_date = $post_aggregate_marks_obtained = $post_gra_aggregate_max_marks = $post_gra_percentage = $post_gra_class = $postcer_qua_name_gra_from_date = $cer_gra_sub = $cer_college_name = $cer_university = $cer_from_date = $cer_to_date = $cer_marks_obtained = $cer_aggregate_max_marks = $cer_percentage = $cer_class = $desirable_course_name = $desirable_college_name = $desirable_name_subject_of_course = $desirable_specialisation = $desirable_university = $desirable_from_date = $desirable_to_date = $desirable_degree_completion_date = $desirable_aggregate_marks_obtained = $desirable_aggregate_max_marks = $desirable_percentage = $desirable_grade_marks = $desirable_class = $whether_in_service = $vrs_register_date = $reason_of_resign = $name_of_present_organization = $service_from_date = $comm_address_of_org = $curr_designation = $any_other_details = $professional_ass = '';

				if ($position_id == 7 || $position_id == 14 || $position_id == 12 || $position_id == 15 || $position_id == 16 || $position_id == 17 || $position_id == 1 || $position_id == 19) {
					$this->db->where('careers_id', $rst[0]["careers_id"]);
					$career_other_details = $this->master_model->getRecords('career_other_details');
					if ($career_other_details) {
						$spouse_name = $career_other_details[0]['spouse_name'];
						if ($spouse_name == '') $spouse_name = '-';
						$mother_name = $career_other_details[0]['mother_name'];
						$religion = $career_other_details[0]['religion'];
						$physical_disbaility = $career_other_details[0]['physical_disbaility'];
						$physical_disbaility_desc = $career_other_details[0]['physical_disbaility_desc'];
						// $post_qua_name = $career_other_details[0]['post_qua_name'];
						// $post_gra_sub = $career_other_details[0]['post_gra_sub'];
						// $post_gra_college_name = $career_other_details[0]['post_gra_college_name'];
						// $post_gra_university = $career_other_details[0]['post_gra_university'];

						// $post_gra_from_date = $career_other_details[0]['post_gra_from_date'];
						// $post_gra_to_date = $career_other_details[0]['post_gra_to_date'];


						// if ($post_gra_from_date != "" && $post_gra_from_date != "0000-00-00") {
						// 	$post_gra_from_date = date("d-m-Y", strtotime($post_gra_from_date));
						// } else {
						// 	$post_gra_from_date = '';
						// }


						// if ($post_gra_to_date != "" && $post_gra_to_date != "0000-00-00") {
						// 	$post_gra_to_date = date("d-m-Y", strtotime($post_gra_to_date));
						// } else {
						// 	$post_gra_to_date = '';
						// }

						// $post_aggregate_marks_obtained = $career_other_details[0]['post_aggregate_marks_obtained'];
						// $post_gra_aggregate_max_marks = $career_other_details[0]['post_gra_aggregate_max_marks'];
						// $post_gra_percentage = $career_other_details[0]['post_gra_percentage'];
						// $post_gra_class = $career_other_details[0]['post_gra_class'];
						$cer_qua_name = $career_other_details[0]['cer_qua_name'];
						$cer_gra_sub = $career_other_details[0]['cer_gra_sub'];
						$cer_college_name = $career_other_details[0]['cer_college_name'];
						$cer_university = $career_other_details[0]['cer_university'];

						$cer_from_date = $career_other_details[0]['cer_from_date'];
						if ($cer_from_date != "" && $cer_from_date != "0000-00-00") {
							$cer_from_date = date("d-m-Y", strtotime($cer_from_date));
						} else {
							$cer_from_date = '';
						}

						$cer_to_date = $career_other_details[0]['cer_to_date'];
						if ($cer_to_date != "" && $cer_to_date != "0000-00-00") {
							$cer_to_date = date("d-m-Y", strtotime($cer_to_date));
						} else {
							$cer_to_date = '';
						}


						$cer_marks_obtained = $career_other_details[0]['cer_marks_obtained'];
						$cer_aggregate_max_marks = $career_other_details[0]['cer_aggregate_max_marks'];
						$cer_percentage = $career_other_details[0]['cer_percentage'];
						$cer_class = $career_other_details[0]['cer_class'];

						$whether_in_service = $career_other_details[0]['whether_in_service'];

						$vrs_register_date = $career_other_details[0]['vrs_register_date'];
						if ($vrs_register_date != "" && $vrs_register_date != "0000-00-00") {
							$vrs_register_date = date("d-m-Y", strtotime($vrs_register_date));
						} else {
							$vrs_register_date = '';
						}

						$reason_of_resign = $career_other_details[0]['reason_of_resign'];
						$name_of_present_organization = $career_other_details[0]['name_of_present_organization'];

						$service_from_date = $career_other_details[0]['service_from_date'];
						if ($service_from_date != "" && $service_from_date != "0000-00-00") {
							$service_from_date = date("d-m-Y", strtotime($service_from_date));
						} else {
							$service_from_date = '';
						}

						$comm_address_of_org = $career_other_details[0]['comm_address_of_org'];
						$curr_designation = $career_other_details[0]['curr_designation'];
						$any_other_details = $career_other_details[0]['any_other_details'];

						$professional_ass = $career_other_details[0]['professional_ass'];
					}

					$this->db->select('m.id,m.course_name,q.*');
					$this->db->join('careers_course_mst m', 'm.course_code=q.course_code', 'LEFT');
					$this->db->where('careers_id', $pdf_career_id);
					$desirable_qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");
					if ($desirable_qualification_arr) {
						$desirable_course_name = $desirable_qualification_arr[0]['course_name'];
						$desirable_college_name = $desirable_qualification_arr[0]['college_name'];
						$desirable_name_subject_of_course = $desirable_qualification_arr[0]['name_subject_of_course'];
						$desirable_specialisation = $desirable_qualification_arr[0]['specialisation'];
						$desirable_university = $desirable_qualification_arr[0]['university'];
						$desirable_from_date = $desirable_qualification_arr[0]['from_date'];
						$desirable_to_date = $desirable_qualification_arr[0]['to_date'];
						$desirable_degree_completion_date = $desirable_qualification_arr[0]['degree_completion_date'];
						$desirable_aggregate_marks_obtained = $desirable_qualification_arr[0]['aggregate_marks_obtained'];
						$degree_completion_date = $desirable_qualification_arr[0]['degree_completion_date'];
						$desirable_aggregate_max_marks = $desirable_qualification_arr[0]['aggregate_max_marks'];
						$desirable_percentage = $desirable_qualification_arr[0]['percentage'];
						$desirable_grade_marks = $desirable_qualification_arr[0]['grade_marks'];
						$desirable_class = $desirable_qualification_arr[0]['class'];
						// print_r($desirable_qualification_arr);
						// exit;
					}


					//added by rupali
					$this->db->where('careers_id', $rst[0]["careers_id"]);
					$career_pg_details = $this->master_model->getRecords('career_pg_details');
					if ($career_pg_details) {
						$post_qua_name = $career_pg_details[0]['post_qua_name'];
						$post_gra_sub = $career_pg_details[0]['post_gra_sub'];
						$post_gra_college_name = $career_pg_details[0]['post_gra_college_name'];
						$post_gra_university = $career_pg_details[0]['post_gra_university'];

						$post_gra_from_date = $career_pg_details[0]['post_gra_from_date'];
						$post_gra_to_date = $career_pg_details[0]['post_gra_to_date'];


						if ($post_gra_from_date != "" && $post_gra_from_date != "0000-00-00") {
							$post_gra_from_date = date("d-m-Y", strtotime($post_gra_from_date));
						} else {
							$post_gra_from_date = '';
						}


						if ($post_gra_to_date != "" && $post_gra_to_date != "0000-00-00") {
							$post_gra_to_date = date("d-m-Y", strtotime($post_gra_to_date));
						} else {
							$post_gra_to_date = '';
						}

						$post_aggregate_marks_obtained = $career_pg_details[0]['post_aggregate_marks_obtained'];
						$post_gra_aggregate_max_marks = $career_pg_details[0]['post_gra_aggregate_max_marks'];
						$post_gra_percentage = $career_pg_details[0]['post_gra_percentage'];
						$post_gra_class = $career_pg_details[0]['post_gra_class'];
					}
				}

				// print_r($career_pg_details);
				// exit;

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


				$html .= '<h1 style="text-align:center">' . $head_title . '</h1>';
				$html .= '<div class="table-responsive ">
					<table class="table table-bordered wikitable tabela" style="overflow: wrap">
					<tbody>';

				$application_title = '';
				if ($position_id == 1) {
					$application_title = "Junior Executive";
				} else if ($position_id == 2) {
					$application_title = "Assistant Director (IT)";
				} else if ($position_id == 3) {
					$application_title = "Assistant Director (Academics)";
				} else if ($position_id == 4) {
					$application_title = "Director (Training) on Contract";
				} else if ($position_id == 5) {
					$application_title = "Chief Executive Officer";
				} else if ($position_id == 6) {
					$application_title = "Deputy Director Accounts";
				} else if ($position_id == 7) {
					$application_title = "Faculty Member (HRM) on contract basis";
				} else if ($position_id == 9) {
					$application_title = "Deputy Director (IT)";
				} else if ($position_id == 10) {
					$application_title = "Research Associate";
				} else if ($position_id == 11) {
					$application_title = "Director on contract basis";
				}
				//else if($position_id == 12) { $application_title = "Head PDC EZ"; }
				else if ($position_id == 12) {
					$application_title = "Head PDC (WZ) - MUMBAI on contract basis";
				} else if ($position_id == 14) {
					$application_title = "Faculty Member (Information Technology) on contract basis";
				} else if ($position_id == 15) {
					$application_title = "In-charge, Development Centre, Lucknow on contract basis";
				} else if ($position_id == 16) {
					$application_title = "In-charge, Development Centre, Guwahati on contract basis";
				} else if ($position_id == 17) {
					$application_title = "In-charge, Development Centre, Bengaluru on contract basis";
				} else if ($position_id == 19) {
					$application_title = "Resident Engineer on Retainer Basis (On Contract)";
				}

				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>BASIC DETAILS</strong></h4></td></tr>
					<tr>                    
					<td width="50%"><strong>Application for the post of:</strong></td>
					<td width="50%">' . $application_title . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>PHOTO:</strong></td>              
					<td width="50%"><img  class="column" width="70px" height="70px" align="right" src="' . base_url() . 'uploads/photograph/' . $rst[0]['scannedphoto'] . '"id="thumb" />
					</tr>';
				if ($position_id == 1) {

					$html .= '
        					<tr>                    
        					<td width="50%"><strong>ID:</strong></td>
        					<td width="50%">' . $rst[0]["reg_id"] . '</td>
        					</tr>';
					$html .= '
        					<tr>                    
        					<td width="50%"><strong>Amount:</strong></td>
        					<td width="50%">Rs. ' . $payment_transaction[0]["amount"] . '</td>
        					</tr>';
					$html .= '
        					<tr>                    
        					<td width="50%"><strong>TRANSACTION ID:</strong></td>
        					<td width="50%">' . $payment_transaction[0]["transaction_no"] . '</td>
        					</tr>';
					$html .= '
        					<tr>                    
        					<td width="50%"><strong>RECEIPT ID:</strong></td>
        					<td width="50%">' . $payment_transaction[0]["receipt_no"] . '</td>
        					</tr>';
				}
				$html .= '
					<tr>                    
					<td width="50%"><strong>Name:</strong></td>
					<td width="50%">' . $rst[0]["sel_namesub"] . ' ' . $rst[0]["firstname"] . ' ' . $rst[0]['middlename'] . ' ' . $rst[0]['lastname'] . '</td>
					</tr>  
					<tr>                    
					<td width="50%"><strong>Marital Status:</strong></td>
					<td width="50%">' . $rst[0]['marital_status'] . '</td>
					</tr>
					<tr>                    
					<td width="50%"><strong>Spouse\'s Name:</strong></td>
					<td width="50%">' . $spouse_name . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Father\'s Name:</strong></td>
					<td width="50%">' . $rst[0]["father_husband_name"] . '</td>
					</tr>
					';



				if ($position_id == 7 || $position_id == 14 || $position_id == 12 || $position_id == 1 || $position_id == 15 || $position_id == 16  || $position_id == 17 || $position_id == 19) {
					$html .= '<tr>                    
						<td width="50%"><strong>Mother\'s Name :</strong></td>
						<td width="50%">' . $mother_name . '</td>
						</tr>';
				}

				$html .= '<tr>                    
					<td width="50%"><strong>Age as on 01.02.2025:</strong></td>
					<td width="50%">' . $rst[0]['dateofbirth'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Gender:</strong></td>
					<td width="50%">' . $rst[0]['gender'] . '</td>
					</tr>';

				if ($position_id == 7 || $position_id == 14 || $position_id == 12 || $position_id == 1  || $position_id == 15 || $position_id == 16  || $position_id == 17 || $position_id == 19) {
					$html .= '<tr>                    
						<td width="50%"><strong>Religion :</strong></td>
						<td width="50%">' . $religion . '</td>
						</tr>';
				}

				$html .= '<tr>                    
					<td width="50%"><strong>Email Id:</strong></td>
					<td width="50%">' . $rst[0]['email'] . '</td>
					</tr>
					
					';

				if ($position_id == 7 || $position_id == 14 || $position_id == 12  || $position_id == 1  || $position_id == 15  || $position_id == 16   || $position_id == 17 || $position_id == 19) {
					$html .= '<tr>                    
						<td width="50%"><strong>Are you a person with Physical Disability :</strong></td> 
						<td width="50%">' . ucwords($physical_disbaility) . '</td>
						</tr>';

					if ($physical_disbaility == 'yes') {
						$html .= '<tr>                    
							<td width="50%"><strong>Type of Disability:</strong></td> 
							<td width="50%">' . $physical_disbaility_desc . '</td>
							</tr>';
					}
				}

				$html .= '<tr>                    
					<td width="50%"><strong>Mobile Number:</strong></td>
					<td width="50%">' . $rst[0]['mobile'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Alternate Mobile Number:</strong></td>
					<td width="50%">' . $rst[0]['alternate_mobile'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>PAN Number:</strong></td>
					<td width="50%">' . $rst[0]['pan_no'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Aadhar Card Number:</strong></td>
					<td width="50%">' . $rst[0]['aadhar_card_no'] . '</td>
					</tr>

					<tr><td colspan="2" style="color: #66d9ff"><h4><strong>COMMUNICATION ADDRESS</strong></h4></td></tr>
					<tr>                    
					<td width="50%"><strong>COMMUNICATION ADDRESS:</strong></td>
					<td width="50%">' . $rst[0]['addressline1'] . ', ' . $rst[0]['addressline2'] . ', ' . $rst[0]['addressline3'] . ', ' . $rst[0]['addressline4'] . '<br>' . $rst[0]['district'] . ', ' . $rst[0]['city'] . '<br>' . $rst[0]['state'] . '<br>' . $rst[0]['pincode'] . '</td>
					</tr> 

					<tr><td colspan="2" style="color: #66d9ff"><h4><strong>PERMANENT ADDRESS</strong></h4></td></tr>
					<tr>                    
					<td width="50%"><strong>PERMANENT ADDRESS:</strong></td>
					<td width="50%">' . $rst[0]['addressline1_pr'] . ', ' . $rst[0]['addressline2_pr'] . ', ' . $rst[0]['addressline3_pr'] . ', ' . $rst[0]['addressline4_pr'] . '<br>' . $rst[0]['district_pr'] . ', ' . $rst[0]['city_pr'] . '<br>' . $stateDetails[0]['state_name'] . '<br>' . $rst[0]['pincode_pr'] . '</td>
					</tr> </table>';

				$html .= '
					<table class="table table-bordered wikitable tabela" style="overflow: wrap">
					<tbody>
					<tr>
					<td colspan="10" style="color: #66d9ff"><h4 style="text-align:center;"><strong>EDUCATIONAL QUALIFICATION</strong></h4>
					<br><strong style="color: #444">ESSENTIAL</strong>
					</td></tr>
					<tr><td><strong>Name</strong></td><td><strong>Graduation degree Name</strong></td>
					<td ><strong>Graduation Stream & Subject</strong></td><td><strong>College/Institution Name</strong></td>
					<td><strong>University Name</strong></td><td><strong>Period</strong></td><td><strong>Aggregate Marks Obtained</strong></td>
					<td><strong>Aggregate Maximum Marks</strong></td>
					<td><strong>Final Percentage</strong></td><td><strong>Class/Grade</strong></td>
					<tr>
					<td ><note>EDUCATIONAL Qualification 1 - Academic (Graduation Onwards)</note></td>
					<td >' . $rst[0]['ess_course_name'] . '</td>
					<td >' . $rst[0]['ess_pg_stream_subject'] . '</td>
					<td >' . $rst[0]['ess_college_name'] . '</td>
					<td >' . $rst[0]['ess_university'] . '</td>
					<td >' . date("d-m-Y", strtotime($rst[0]['ess_from_date'])) . " TO " . date("d-m-Y", strtotime($rst[0]['ess_to_date'])) . '</td>
					<td >' . $rst[0]['ess_aggregate_marks_obtained'] . '</td>
					<td >' . $rst[0]['ess_aggregate_max_marks'] . '</td>
					<td >' . $rst[0]['ess_percentage'] . '</td>
					<td >' . $rst[0]['ess_class'] . '</td>
					</tr>
						';
				if ($post_qua_name != '') {
					$html .= '<tr>
							<td ><note>Educational Qualification 2 - Post Graduation</note></td>
							<td >' . $post_qua_name . '</td>
							<td >' . $post_gra_sub . '</td>
							<td >' . $post_gra_college_name . '</td>
							<td >' . $post_gra_university . '</td>
							<td >' . $post_gra_from_date . " To " . $post_gra_to_date . '</td>
							<td >' . $post_aggregate_marks_obtained . '</td>
							<td >' . $post_gra_aggregate_max_marks . '</td>
							<td >' . $post_gra_percentage . '</td>
							<td >' . $post_gra_class . '</td>
							</tr>';
				}
				if ($cer_qua_name != '') {
					$html .= '
							<tr>
							<td ><note>Educational Qualification 3: Additional Qualifications/Certification</note></td>
							<td >' . $cer_qua_name . '</td>
							<td >' . $cer_gra_sub . '</td>
							<td >' . $cer_college_name . '</td>
							<td >' . $cer_university . '</td>
							<td >' . $cer_from_date . " To " . $cer_to_date . '</td>
							<td >' . $cer_marks_obtained . '</td>
							<td >' . $cer_aggregate_max_marks . '</td>
							<td >' . $cer_percentage . '</td>
							<td >' . $cer_class . '</td>
							</tr>
							';
				}
				$html .= '</tbody>
					</table>
					
			          
					';
				$html .= '<table class="table table-bordered wikitable tabela" style="overflow: wrap;margin-top:10px;"><tbody>';
				if ($position_id == 7 || $position_id == 12 || $position_id == 3 || $position_id == 14 || $position_id == 8) {
					$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td></tr>';
					$html .= '<tr>                    
						<td width="50%"><strong>CAIIB:</strong></td>
						<td width="50%">CAIIB</td>
						</tr>
						
						<tr>                    
						<td width="50%"><strong>Year of passing:</strong></td>
						<td width="50%">' . $rst[0]['year_of_passing'] . '</td>
						</tr>
						
						<tr>                    
						<td width="50%"><strong>Membership Number:</strong></td>
						<td width="50%">' . $rst[0]['membership_number'] . '</td>
						</tr>';
				}
				if ($desirable_course_name != '') {
					$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>DESIRABLE</strong></h4></td></tr>
						<tr>                    
						<td width="50%"><strong>Course Name:</strong></td>
						<td width="50%">' . $desirable_course_name . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Specialisation:</strong></td>
						<td width="50%">' . $desirable_name_subject_of_course . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>College Name and Address:</strong></td>
						<td width="50%">' . $desirable_college_name . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>University:</strong></td>
						<td width="50%">' . $desirable_university . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Period:</strong></td>
						<td width="50%">' . $desirable_from_date . " To " . $desirable_to_date . '</td>
						</tr>';
					/*$html.= '<tr>                    
						<td width="50%"><strong>Date of completion of the Degree:</strong></td>
						<td width="50%">'.$degree_completion_date.'</td>
						</tr>';*/
					$html .= '<tr>                    
						<td width="50%"><strong>Aggregate Marks Obtained:</strong></td>
						<td width="50%">' . $desirable_aggregate_marks_obtained . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Aggregate Maximum Marks:</strong></td>
						<td width="50%">' . $desirable_aggregate_max_marks . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Percentage:</strong></td>
						<td width="50%">' . $desirable_percentage . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Class/Grade:</strong></td>
						<td width="50%">' . $desirable_class . '</td>
						</tr>
						';
				}
				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY (from Recent employment to Oldest employment) - Last 5 positions held with role & responsibilities in detail</strong></h4></td></tr>';
				if ($emp_hist_arr) {
					foreach ($emp_hist_arr as $rest) {
						$html .= '<tr>                    
								<td width="50%"><strong>Name of the Organization/Employer/Bank:</strong></td>
								<td width="50%">' . $rest['organization'] . '</td>
								</tr>';
						$html .= '<tr>                    
								<td width="50%"><strong>Period:</strong></td>
								<td width="50%">' . date("d-m-Y", strtotime($rest['job_from_date'])) . " To " . date("d-m-Y", strtotime($rest['job_to_date'])) . '</td>
								</tr>';
						$html .= '<tr>                    
								<td width="50%"><strong>Last Designation/Last Post Held:</strong></td>
								<td width="50%">' . $rest['designation'] . '</td>
								</tr>';
						$html .= '<tr>                    
								<td width="50%"><strong>Responsibilities/Nature of Duties Performed:</strong></td>
								<td width="50%">' . $rest['responsibilities'] . '</td>
								</tr>';
					}
				}
				if ($position_id != 1) {
					$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Whether In Service or not?</strong></h4></td></tr>
									<tr>                    
									<td width="50%"><strong>Whether In Service?:</strong></td>
									<td width="50%">' . ucwords($whether_in_service) . '</td>
									</tr>';

					if ($whether_in_service == "no") {
						$html .= '<tr>                    
										<td width="50%"><strong>Date of Superannuation/VRS/Resignation etc:</strong></td>
										<td width="50%">' . $vrs_register_date . '</td>
										</tr>
										<tr>                    
										<td width="50%"><strong>Reason for Resignation/Leaving:</strong></td>
										<td width="50%">' . $reason_of_resign . '</td>
										</tr>';
					} else if ($whether_in_service == 'yes') {
						$html .= '<tr>                    
										<td width="50%"><strong>Name of the Present Organization:</strong></td>
										<td width="50%">' . $name_of_present_organization . '</td>
										</tr>
										<tr>                    
										<td width="50%"><strong>Period:</strong></td>
										<td width="50%">' . $service_from_date . '</td>
										</tr>
										<tr>                    
										<td width="50%"><strong>Communication Address of the Organization:</strong></td>
										<td width="50%">' . $comm_address_of_org . '</td>
										</tr>
										<tr>                    
										<td width="50%"><strong>Designation/Post Held:</strong></td>
										<td width="50%">' . $curr_designation . '</td>
										</tr>
										<tr>                    
										<td width="50%"><strong>Any Other Details:</strong></td>
										<td width="50%">' . $any_other_details . '</td>
										</tr>';
					}
				}
				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Experience as Faculty</strong></h4></td></tr>';

				if ($position_id == 7 || $position_id == 12 || $position_id == 14  || $position_id == 15 || $position_id == 16  || $position_id == 17) {
					$html .= '<tr>                    
						<td width="50%"><strong>Experience as Faculty:</strong></td>
						<td width="50%">' . $rst[0]['exp_in_bank'] . '</td>
						</tr>';



					$html .= '<tr>                    
						<td width="50%"><strong>Published Articles/Books:</strong></td>
						<td width="50%">' . $rst[0]['publication_of_books'] . '</td>
						</tr>';

					$html .= '<tr>                    
						<td width="50%"><strong>Membership of Professional Associations:</strong></td>
						<td width="50%">' . $professional_ass . '</td>
						</tr>';
				}

				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Languages, Extracurricular, Achievements</strong></h4></td></tr>';

				$html .= '
					<tr>                    
					<td width="50%"><strong>Languages Known 1:</strong></td>
					<td width="50%">' . $rst[0]['languages_known'] . '</td>
					</tr>
					';
				if ($position_id == 12 || $position_id == 3 || $position_id == 4  || $position_id == 15) {
					$html .= '<tr>                    
						<td width="50%"><strong>Area of Specialisation:</strong></td>
						<td width="50%">' . $rst[0]['exp_in_functional_area'] . '</td>
						</tr>';
				}
				$html .= '
					<tr>                    
					<td width="50%"><strong>Languages Options 1:</strong></td>
					<td width="50%">' . $rst[0]['languages_option'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Languages Known 2:</strong></td>
					<td width="50%">' . $rst[0]['languages_known1'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Languages Options 2:</strong></td>
					<td width="50%">' . $rst[0]['languages_option1'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Languages Known 3:</strong></td>
					<td width="50%">' . $rst[0]['languages_known2'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Languages Options 3:</strong></td>
					<td width="50%">' . $rst[0]['languages_option2'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Extracurricular (Games / Membership / Association):</strong></td>
					<td width="50%">' . $rst[0]['extracurricular'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Hobbies:</strong></td>
					<td width="50%">' . $rst[0]['hobbies'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Achievements:</strong></td>
					<td width="50%">' . $rst[0]['achievements'] . '</td>
					</tr>';

				if ($position_id == 10 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14  || $position_id == 15 || $position_id == 19) {
					$html .= '	<tr><td colspan="2" style="color: #66d9ff"><h4><strong>REFERENCES</strong></h4></td></tr>
						<tr><td colspan="2">Candidates are instructed to provide two professional references. (References of family members, relatives & friends will not be considered)</td></tr>';
				}

				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Professional Reference 1</strong></h4></td></tr>
					<tr>                    
					<td width="50%"><strong>Name:</strong></td>
					<td width="50%">' . $rst[0]['refname_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Complete Address:</strong></td>
					<td width="50%">' . $rst[0]['refaddressline_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Organisation (If employed):</strong></td>
					<td width="50%">' . $rst[0]['reforganisation_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Designation:</strong></td>
					<td width="50%">' . $rst[0]['refdesignation_one'] . '</td>
					</tr>  

					<tr>                    
					<td width="50%"><strong>Email Id:</strong></td>
					<td width="50%">' . $rst[0]['refemail_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Mobile:</strong></td>
					<td width="50%">' . $rst[0]['refmobile_one'] . '</td>
					</tr>';


				$html .= '<br><tr><td colspan="2" style="color: #66d9ff"><h4><strong>Professional Reference 2</strong></h4></td></tr>';
				$html .= '<tr>                    
					<td width="50%"><strong>Name:</strong></td>
					<td width="50%">' . $rst[0]['refname_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Complete Address:</strong></td>
					<td width="50%">' . $rst[0]['refaddressline_two'] . '</td>
					</tr>

					<tr>                    
					<td width="50%"><strong>Organisation (If employed):</strong></td>
					<td width="50%">' . $rst[0]['reforganisation_two'] . '</td>
					</tr>

					<tr>                    
					<td width="50%"><strong>Designation:</strong></td>
					<td width="50%">' . $rst[0]['refdesignation_two'] . '</td>
					</tr> 

					<tr>                    
					<td width="50%"><strong>Email Id:</strong></td>
					<td width="50%">' . $rst[0]['refemail_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Mobile:</strong></td>
					<td width="50%">' . $rst[0]['refmobile_two'] . '</td>
					</tr>
					
					<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Other Information</strong></h4></td></tr>
					<tr>                    
					<td width="50%"><strong>Any other information that the candidate would like to add:</strong></td>
					<td width="50%"><div style="word-break:break-all;">' . $rst[0]['comment'] . '</div></td>
					</tr> 
					';




				if ($position_id == 7 || $position_id == 14 || $position_id == 12  || $position_id == 15 || $position_id == 19) {
					$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Declaration</strong></h4></td></tr>';

					$html .= '<tr>                    
					<td width="50%"><strong>Declaration 1 : I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</strong></td>
					<td width="50%">YES</td>
					</tr>';

					$html .= '<tr>                    
					<td width="50%"><strong>Declaration 2:   I hereby agree that any legal proceedings in respect of any matter or claims or disputes arising out of application or out of said advertisement can be instituted by me at Mumbai only and shall have sole and exclusive jurisdiction to try any cause / dispute. I undertake to abide by all the terms and conditions of the advertisement given by the Indian Institute of Banking & Finance</strong></td>
					<td width="50%">YES</td>
					</tr>';
					$html .= '<tr>                    
					<td width="50%"><strong>Declaration 3:   I further certify that no disciplinary / vigilance proceedings are either pending or contemplated against me and that there are no legal cases pending against me. There have been no major / minor penalty</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}

				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>UPLOAD</strong></h4></td></tr>';

				$html .= '<tr>                    
					<td width="50%"><strong>Signature:</strong></td>
					<td><img width="70px" height="70px" src="' . base_url() . 'uploads/scansignature/' . $rst[0]['scannedsignaturephoto'] . '" id="thumb" /></td>
					</tr>
					';

				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Place and Date</strong></h4></td></tr>';

				$html .= '<tr>                    
					<td width="50%"><strong>Place:</strong></td>
					<td width="50%">' . $rst[0]['place'] . '</td>
					</tr> 
					<tr>
					<td width="50%"><strong>Date:</strong></td>
					<td width="50%">' . date("d-m-Y", strtotime($rst[0]['submit_date'])) . '</td>
					</tr>
					';



				if ($position_id != 7 &&  $position_id != 15 && $position_id != 16 && $position_id != 14 && $position_id != 12  && $position_id != 15  && $position_id != 1 && $position_id != 19) {
					$html .= '<tr>                    
					<td width="50%"><strong>CONTACT NUMBER:</strong></td>
					<td width="50%">' . $rst[0]['contact_number'] . '</td>
					</tr>';
				}
				if ($position_id == 2) {
					$html .= '<tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION</strong></h4></td><td></td></tr>';

					$html .= '<tr><td colspan="2">The date of passing eligibility examination will be the date appearing on the marksheet issued by the University/Institute. The percentage marks shall be arrived at by dividing the total marks obtained by the candidate in all the subjects in all semesters / years by aggregate maximum marks in all the subjects irrespective of optional/additional optional subject, if any. The fraction of percentage so arrived will be ignored i.e. 59.99% will be treated as less than 60%.</td></tr>';
				}

				/*$html.= '<tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION (ESSENTIAL)</strong></h4></td><td></td></tr>'; */


				foreach ($rst as $row) {
					if ($position_id == 1 || $position_id == 2 || $position_id == 3 || $position_id == 6 || $position_id == 9 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 14 || $position_id == 12  || $position_id == 15 || $position_id == 19) {

						$html .= '<tr>                    
							<td width="50%"><strong>';
						if ($position_id == 3)
							$html .= 'Name of course (Post Graduate):';
						else
							$html .= 'NAME OF COURSE:';
						$html .= '</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_course_name'] . '</div></td>
							</tr>';
					} else if ($position_id == 7 || $position_id == 12 || $position_id == 14  || $position_id == 15) {
						$html .= '<tr>                    
							<td width="50%"><strong>NAME OF COURSE(POST GRADUATE):</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_course_name'] . '</div></td>
							</tr>
							
							<tr>                    
							<td width="50%"><strong>IF POST GRADUATION - STREAM & SUBJECT :</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_pg_stream_subject'] . '</div></td>
							</tr>';
					} else if ($position_id == 10) {
						$html .= '	<tr>                    
							<td width="50%"><strong>DEGREE:</strong></td>                          
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_course_name'] . '</div></td>
							</tr>';
					} else if ($position_id == 11 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 14 || $position_id == 12  || $position_id == 15) {
						$html .= '	<tr>                    
							<td width="50%"><strong>GRADUATION STREAM:</strong></td>                          
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_course_name'] . '</div></td>
							</tr>';
					}

					if ($position_id == 1) {
						$html .= '<tr>                    
							<td width="50%"><strong>SUBJECT:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_subject'] . '</div></td>
							</tr>';
					}
					if ($position_id == 1 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 14 || $position_id == 12) {
						$html .= '<tr>                    
							<td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_college_name'] . '</div></td>
							</tr>';
					}
					if ($position_id == 1 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 14 || $position_id == 12) {
						$html .= '<tr>                    
							<td width="50%"><strong>UNIVERSITY:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_university'] . '</div></td>
							</tr>';
					}
					if ($position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 11) {
						$html .= '<tr>                    
							<td width="50%"><strong>SUBJECT:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_subject'] . '</div></td>
							</tr>';
					}

					if ($position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 11) {
						$html .= '<tr>                    
							<td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
							<td width="50%">' . $row['ess_college_name'] . '</td>
							</tr>';
					} else if ($position_id == 7 || $position_id == 12 || $position_id == 14) {
						$html .= '<tr>                    
							<td width="50%"><strong>COLLEGE/ INSTITUTION NAME AND ADDRESS:</strong></td>
							<td width="50%">' . $row['ess_college_name'] . '</td>
							</tr>';
					}

					if ($position_id == 3) {
						$html .= '<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">' . $row['ess_college_name'] . '</td>
							</tr>';
					} else if ($position_id == 6) {
						$html .= '	<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">' . $row['ess_university'] . '</td>
							</tr>';
					}

					if ($position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 11 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
						$html .= '<tr>                    
							<td width="50%"><strong>UNIVERSITY:</strong></td>
							<td width="50%">' . $row['ess_university'] . '</td>
							</tr>';
					}

					$html .= '<tr>                    
						<td width="50%"><strong>PERIOD:</strong></td>
						<td width="50%">' . $row['ess_from_date'] . " to " . $row['ess_to_date'] . '</td>
						</tr>';

					if ($position_id == 10 || $position_id == 1) {
						$html .= '	<tr>                    
							<td width="50%"><strong>DATE OF COMPLETION OF THE DEGREE:</strong></td>
							<td width="50%">' . $row['ess_degree_completion_date'] . '</td>
							</tr>';
					}

					if ($position_id == 1 || $position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
						$html .= '	<tr>                    
							<td width="50%"><strong>AGGREGATE MARKS OBTAINED:</strong></td>
							<td width="50%">' . $row['ess_aggregate_marks_obtained'] . '</td>
							</tr>
							
							<tr>                    
							<td width="50%"><strong>AGGREGATE MAXIMUM MARKS:</strong></td>
							<td width="50%">' . $row['ess_aggregate_max_marks'] . '</td>
							</tr>';
					}

					if ($position_id == 3) {
						$html .= '<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">' . $row['ess_grade_marks'] . '</td>
							</tr>';
					} else if ($position_id == 2 || $position_id == 6 || $position_id == 9 || $position_id == 10 || $position_id == 11 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
						$html .= '	<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">' . $row['ess_percentage'] . '</td>
							</tr>';
					}
					/* else if($position_id == 1 || $position_id == 4 || $position_id == 6)
							{
							$html.= '<tr>                    
							<td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
							<td width="50%">'.$row['ess_grade_marks'].'</td>
							</tr>';
						} */

					if ($position_id == 1 || $position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14 || $position_id == 19) {
						$html .= '<tr>                    
							<td width="50%"><strong>CLASS/GRADE:</strong></td>
							<td width="50%">' . $row['ess_class'] . '</td>
							</tr>';
					}
				}

				if ($position_id == 7 || $position_id == 12 || $position_id == 3 || $position_id == 14 || $position_id == 19) {
					$html .= '<tr><td style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td><td></td></tr>';

					$html .= '<tr>                    
						<td width="50%"><strong>CAIIB:</strong></td>
						<td width="50%">CAIIB</td>
						</tr>
						
						<tr>                    
						<td width="50%"><strong>YEAR OF PASSING:</strong></td>
						<td width="50%">' . $row['year_of_passing'] . '</td>
						</tr>
						
						<tr>                    
						<td width="50%"><strong>MEMBERSHIP NUMBER:</strong></td>
						<td width="50%">' . $row['membership_number'] . '</td>
						</tr>';
				}

				$html .= '<tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION (DESIRABLE)</strong></h4></td><td></td></tr>';

				//	print_r($qualification_arr);exit;
				foreach ($qualification_arr as $row) {
					if ($position_id == 2 || $position_id == 9) {
						$html .= '<tr>                    
							<td width="50%"><strong>NAME OF CERTIFICATION COURSE:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['course_name'] . '</div></td>
							</tr>';
					} else if ($position_id == 10) {
						$course_code_nm = '';
						$course_code_arr = explode(",", $row['college_name']);
						if (count($course_code_arr) > 0) {
							foreach ($course_code_arr as $course_code) {
								$course_code_nm .= '> ' . $course_code . '<br>';
							}
						}

						$html .= '	<tr>                    
							<td colspan="2">' . $course_code_nm . '</td>
							</tr>';
					} else {
						/*$html.= '<tr>                    
							<td width="50%"><strong>NAME OF COURSE:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['course_name'].'</div></td>
							</tr>';*/
					}

					if ($position_id == 7 || $position_id == 12 || $position_id == 14 || $position_id == 19) {
						$html .= '	<tr>                    
							<td width="50%"><strong>NAME & SUBJECT OF THE COURSE:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['name_subject_of_course'] . '</div></td>
							</tr>';
					}

					/* if($position_id == 6)
							{
							$html.= '	<tr>                    
							<td width="50%"><strong>SPECIALISATION:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['specialisation'].'</div></td>
							</tr>';
						} */


					if ($position_id == 3 || $position_id == 1 || $position_id == 6) {
						$html .= '<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">' . $row['college_name'] . '</td>
							</tr>';
					} else if ($position_id == 6 || $position_id == 1) {
						$html .= '<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">' . $row['university'] . '</td>
							</tr>';
					} else if ($position_id == 2 || $position_id == 9 || $position_id == 11 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14 || $position_id == 19) {
						$html .= '	<tr>                    
							<td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
							<td width="50%">' . $row['college_name'] . '</td>
							</tr>';
					}

					if ($position_id == 2 || $position_id == 9 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14 || $position_id == 19) {
						$html .= '<tr>                    
							<td width="50%"><strong>UNIVERSITY:</strong></td>
							<td width="50%">' . $row['university'] . '</td>
							</tr>';
					} else if ($position_id == 11) {
						$html .= '	<tr>                    
							<td width="50%"><strong>UNIVERSITY/INSTITUTE:</strong></td>
							<td width="50%">' . $row['university'] . '</td>
							</tr>';
					}

					if ($position_id != 10) {
						$html .= '<tr>                    
							<td width="50%"><strong>PERIOD:</strong></td>
							<td width="50%">' . $row['from_date'] . " to " . $row['to_date'] . '</td>
							</tr>';
					}

					if ($position_id == 2 || $position_id == 9 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 1 || $position_id == 14 || $position_id == 19) {
						$html .= '	<tr>                    
							<td width="50%"><strong>AGGREGATE MARKS OBTAINED:</strong></td>
							<td width="50%">' . $row['aggregate_marks_obtained'] . '</td>
							</tr>
							
							<tr>                    
							<td width="50%"><strong>AGGREGATE MAXIMUM MARKS:</strong></td>
							<td width="50%">' . $row['aggregate_max_marks'] . '</td>
							</tr>';
					}

					if ($position_id == 3 || $position_id == 1 || $position_id == 6) {
						$html .= '<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">' . $row['grade_marks'] . '</td>
							</tr>';
					} else if ($position_id == 1 || $position_id == 2 || $position_id == 6 || $position_id == 9 || $position_id == 11 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14 || $position_id == 19) {
						$html .= '<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">' . $row['percentage'] . '</td>
							</tr>';
					}
					/* else if($position_id == 1 || $position_id == 4 || $position_id == 6)
							{
							$html.= '	<tr>                    
							<td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
							<td width="50%">'.$row['grade_marks'].'</td>
							</tr>';
						} */

					if ($position_id == 1 || $position_id == 2 || $position_id == 9 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14 || $position_id == 19)/*  || $position_id == 1 || $position_id == 4 || $position_id == 6 */ {
						$html .= '<tr>                    
							<td width="50%"><strong>CLASS/GRADE:</strong></td>
							<td width="50%">' . $row['class'] . '</td>
							</tr>';
					}
				}

				$html .= '<tr><td style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY</strong></h4></td><td></td></tr>';
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
						<td width="50%"><strong>RESPONSIBILITIES:</strong></td>
						<td width="50%">' . $rest['responsibilities'] . '</td>
						</tr>';
					$html .= '<tr>                    
						<td width="50%"><strong>PERIOD:</strong></td>
						<td width="50%">' . $rest['job_from_date'] . " to " . $rest['job_to_date'] . '</td>
						</tr>';
				}

				if ($position_id == 7 || $position_id == 12 || $position_id == 14 || $position_id == 19) {
					$html .= '<tr>                    
						<td width="50%"><strong>EXPERIENCE AS FACULTY IN BANKING/FINANCIAL INSTITUTION:</strong></td>
						<td width="50%">' . $rst[0]['exp_in_bank'] . '</td>
						</tr>';

					$html .= '<tr>                    
						<td width="50%"><strong>PUBLISHED ARTICLES/BOOKS:</strong></td>
						<td width="50%">' . $rst[0]['publication_of_books'] . '</td>
						</tr>';

					$html .= '<tr>                    
						<td width="50%"><strong>EXPERIENCE IN ONE OR MORE COVERING THE FUNCTIONAL AREAS:</strong></td>
						<td width="50%">' . $rst[0]['exp_in_functional_area'] . '</td>
						</tr>';
				}

				$html .= '	<tr><td style="color: #66d9ff"><h4><strong>LANGUAGES, EXTRACURRICULAR, ACHIEVEMENTS </strong></h4></td><td></td></tr>';

				if ($position_id == 3) {
					$html .= '<tr>                    
						<td width="50%"><strong>EXPERIENCE AS FACULTY IN BANKING/FINANCIAL INSTITUTION:</strong></td>
						<td width="50%">' . $rst[0]['exp_in_bank'] . '</td>
						</tr>';

					$html .= '<tr>                    
						<td width="50%"><strong>PUBLISHED ARTICLES/BOOKS:</strong></td>
						<td width="50%">' . $rst[0]['publication_of_books'] . '</td>
						</tr>';
				}

				$html .= '<tr>                    
					<td width="50%"><strong>LANGUAGES OPTIONS 1:</strong></td>
					<td width="50%">' . $rst[0]['languages_option'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES KNOWN 2:</strong></td>
					<td width="50%">' . $rst[0]['languages_known1'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES OPTIONS 2:</strong></td>
					<td width="50%">' . $rst[0]['languages_option1'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES KNOWN 3:</strong></td>
					<td width="50%">' . $rst[0]['languages_known2'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES OPTIONS 3:</strong></td>
					<td width="50%">' . $rst[0]['languages_option2'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EXTRACURRICULAR:</strong></td>
					<td width="50%">' . $rst[0]['extracurricular'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>HOBBIES:</strong></td>
					<td width="50%">' . $rst[0]['hobbies'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>ACHIEVEMENTS:</strong></td>
					<td width="50%">' . $rst[0]['achievements'] . '</td>
					</tr>';

				if ($position_id == 2 || $position_id == 3 || $position_id == 6 || $position_id == 9 || $position_id == 10) {
					$html .= '<tr>                    
						<td width="50%"><strong>DECLARATION: Have you ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification:</strong></td>
						<td width="50%">' . $rst[0]['declaration1'] . '</td>
						</tr>';
					if ($rst[0]['declaration1'] == 'Yes') {
						$html .= '<tr>                    
							<td width="50%"><strong>DECLARATION NOTE:</strong></td>
							<td width="50%">' . $rst[0]['declaration_note'] . '</td>
							</tr>';
					}
				}

				if ($position_id == 10 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14 || $position_id == 19) {
					$html .= '	<tr><td style="color: #66d9ff"><h4><strong>REFERENCE</strong></h4></td><td></td></tr>
						<tr><td colspan="2">Candidates are instructed to provide two professional references. (References of family members, relatives & friends will not be considered)</td></tr>';
				}

				$html .= '	<tr><td style="color: #66d9ff"><h4><strong>PROFESSIONAL REFERENCE 1</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>NAME:</strong></td>
					<td width="50%">' . $rst[0]['refname_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>DESIGNATION:</strong></td>
					<td width="50%">' . $rst[0]['refdesignation_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>ORGANISATION:</strong></td>
					<td width="50%">' . $rst[0]['reforganisation_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
					<td width="50%">' . $rst[0]['refaddressline_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EMAIL ID:</strong></td>
					<td width="50%">' . $rst[0]['refemail_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>MOBILE:</strong></td>
					<td width="50%">' . $rst[0]['refmobile_one'] . '</td>
					</tr>';
				$html .= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE 2</strong></h4></td><td></td></tr>';
				$html .= '<tr>                    
					<td width="50%"><strong>NAME:</strong></td>
					<td width="50%">' . $rst[0]['refname_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>DESIGNATION:</strong></td>
					<td width="50%">' . $rst[0]['refdesignation_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>ORGANISATION:</strong></td>
					<td width="50%">' . $rst[0]['reforganisation_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
					<td width="50%">' . $rst[0]['refaddressline_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EMAIL ID:</strong></td>
					<td width="50%">' . $rst[0]['refemail_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>MOBILE:</strong></td>
					<td width="50%">' . $rst[0]['refmobile_two'] . '</td>
					</tr>
					
					<tr><td style="color: #66d9ff"><h4><strong>OTHER INFORMATION</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>ANY OTHER INFORMATION THAT THE CANDIDATE WOULD LIKE TO ADD:</strong></td>
					<td width="50%"><div style="word-break:break-all;">' . $rst[0]['comment'] . '</div></td>
					</tr>
					
					';

				if ($position_id != 7 &&  $position_id != 15 && $position_id != 16 && $position_id != 12 && $position_id != 14 && $position_id != 19) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION : I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated without any notice.</strong></td>
					<td width="50%">' . $rst[0]['declaration2'] . '</td>
					</tr>';
				}

				if ($position_id == 7 || $position_id == 14 || $position_id == 19) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION : I declare that all statements made in this application are true, complete and correct to the best  of  my  knowledge  and  belief . I also declare that I  have  not  suppressed  any  material  fact(s)/information.  I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying  any  of  the  eligibility  criteria  according  to  the  requirements  of  the  related  advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}

				if ($position_id == 12) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION : I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}

				if ($position_id == 1) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION 2: I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leaves the service of the Institute before the expiry of the said period, a sum of Rs. 1,00,000/- (Rupees One Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Junior Executive dated 17-11-2022.</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}
				if ($position_id == 3) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION 2:  I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leave the service of the Institute before the expiry of the said period, a sum of Rs. 2,00,000/- (Rupees Two Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Assistant Director (Academics) dated 17.11.2022.</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}
				if ($position_id == 2) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION 2:  I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leave the service of the Institute before the expiry of the said period, a sum of Rs. 2,00,000/- (Rupees Two Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Assistant Director (IT) dated 17.11.2022</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}
				if ($position_id == 6) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION 2:   I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leave the service of the Institute before the expiry of the said period, a sum of Rs. 2,00,000/- (Rupees Two Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Deputy Director (Accounts) dated 17.11.2022.</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}
				if ($position_id == 7 || $position_id == 14 || $position_id == 12 || $position_id == 19) {
					$html .= '<tr>                    
						<td width="50%"><strong>DECLARATION 2:   I hereby agree that any legal proceedings in respect of any matter or claims or disputes arising out of application or out of said advertisement can be instituted by me at Mumbai only and shall have sole and exclusive jurisdiction to try any cause / dispute. I undertake to abide by all the terms and conditions of the advertisement given by the Indian Institute of Banking & Finance</strong></td>
						<td width="50%">YES</td>
						</tr>';
					$html .= '<tr>                    
						<td width="50%"><strong>DECLARATION 3:   I further certify that no disciplinary / vigilance proceedings are either pending or contemplated against me and that there are no legal cases pending against me. There have been no major / minor penalty</strong></td>
						<td width="50%">YES</td>
						</tr>';
				}
				$html .= '<tr><td style="color: #66d9ff"><h4><strong>PLACE AND DATE</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>PLACE:</strong></td>
					<td width="50%">' . $rst[0]['place'] . '</td>
					</tr>
					
					<tr>
					<td width="50%"><strong>DATE:</strong></td>
					<td width="50%">' . $rst[0]['submit_date'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>SIGNATURE:</strong></td>
					<td><img width="70px" height="70px" src="' . base_url() . 'uploads/scansignature/' . $rst[0]['scannedsignaturephoto'] . '" id="thumb" /></td>
					</tr>';


				$html .= '</tbody>
					</table>
					<div id="reason_form" style="display: none">';

				// echo $html; exit;
				// print_r($html);                                                   

				$pdf = $this->m_pdf->load();

				$pdfFilePath = $rst[0]['firstname'] . '_' . $rst[0]['lastname'] . '_' . $rst[0]['submit_date'] . '_' . $position_id . '_' . $pdf_career_id . ".pdf";

				$file_dir_name = date('Ymd');
				$directory_name = "./uploads/Careers_Data/" . $file_dir_name;
				//mkdir($directory_name); 
				if (!file_exists($directory_name)) {
					mkdir($directory_name);
				}

				$uri_segments_all = explode("/", $_SERVER['REQUEST_URI']);
				$uri_segments_all = end($uri_segments_all);
				//die;
				if ($cnt > 7) {
					redirect(site_url('careers_admin/admin/Careers_position/' . $uri_segments_all));
				}


				$car_pdf_path = 'uploads/Careers_Data/' . $file_dir_name . '/';

				$file_arr[] = $pdfFilePath;

				if (!file_exists($car_pdf_path . $pdfFilePath) && $force_open_flag != 1) {
					$pdf->WriteHTML($html);
					$pdf->SetCompression(false);
					$pdf->SetDisplayMode('real');
					$pdf->SetDisplayMode('default');
					$pdf->SetAutoPageBreak(true);
					$path = $pdf->Output('uploads/Careers_Data/' . $file_dir_name . "/" . $pdfFilePath, "F");
					$cnt++;
				} else {
					$pdf->WriteHTML($html);
					$pdf->SetCompression(false);
					$pdf->SetDisplayMode('real');
					$pdf->SetDisplayMode('default');
					$pdf->SetAutoPageBreak(true);
				}

				if ($force_open_flag == 1) {
					$path = $pdf->Output($pdfFilePath, "D");
				}

				$pdf_generated_cnt++;
			}

			//print_r($file_arr);die;
			//print_r($file_dir_name); 
			//$zip = new ZipArchive(); // Load zip library  

			$file_dir_name = date('Ymd');
			$zip_name = 'career_files_' . date("YmdHis") . rand() . ".zip";

			//file directory creation 
			$zip_folder_path = "uploads/Careers_Data/" . $file_dir_name . "/zip";
			$directory_name = "./uploads/Careers_Data/" . $file_dir_name . "/zip";
			//mkdir($directory_name); 
			if (!file_exists($directory_name)) {
				mkdir($directory_name);
			}

			if (file_exists($zip_folder_path . "/" . $zip_name)) {
				@unlink($zip_folder_path . "/" . $zip_name);
			}


			$zip = new ZipArchive;

			if ($zip->open($zip_folder_path . '/' . $zip_name, ZipArchive::CREATE) === TRUE) {
				if (count($file_arr) > 0) {
					foreach ($file_arr as $file) {
						if ($file != "") {
							$path = "./uploads/Careers_Data/" . $file_dir_name . "/" . $file;
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
			if (count($all_directories) > 0) {
				foreach ($all_directories as $dir) {
					$explode_arr = explode("_", $dir, 2);
					//echo $explode_arr[0]."==".$dir;die;
					$chk_dir = str_replace("/", "", $explode_arr[0]);
					//echo $chk_dir;die;
					if ($chk_dir != date('Ymd')) {
						//echo "<br> Delete : ".$dir;
						$this->rmdir_recursive("uploads/Careers_Data/" . $chk_dir);
					}
				}
			}
			//END : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE

			if (count($file_arr) > 0) {
				redirect(base_url('uploads/Careers_Data/' . $file_dir_name . '/zip/' . $zip_name));
			}
		}

		//echo "<br> PDF Generated Count : ".$pdf_generated_cnt;

		//redirect(base_url().'careers_admin/admin/Careers_position/career_position_list');
	}

	public function pdf_record_old($career_id = 0, $position_id = 0, $from_date = 0, $to_date = 0)
	{


		$force_open_flag = 0;


		if (isset($_GET['position'])) {
			$position_id = $_GET['position'];
		}
		if (isset($_GET['from_date'])) {
			$from_date = $_GET['from_date'];
		}
		if (isset($_GET['to_date'])) {
			$to_date = $_GET['to_date'];
		}

		$this->db->where('position_id', $position_id);

		$this->db->where('active_status', '1');
		if ($career_id != '0') {
			$this->db->where('careers_id', $career_id);
			$force_open_flag = 1;
		}
		if ($from_date != '0') {
			$this->db->where('DATE(createdon) >=', $from_date);
		}
		if ($to_date != '0') {
			$this->db->where('DATE(createdon) <=', $to_date);
		}

		$sql = $this->master_model->getRecords("careers_registration", '', 'careers_id');

		//echo "<br> QRY : ".$this->db->last_query();exit;
		// echo "<br> Record Count : ".count($sql); exit;

		if ($position_id == 13) {
			$this->db->where('careers_id', $career_id);
			$rst = $this->master_model->getRecords("careers_registration");
			// echo "<pre>";  print_r($rst); exit;
			$pdf_html = $this->getPDFhtml($rst, $career_id, $position_id);

			$pdf = $this->m_pdf->load();

			$pdfFilePath = $rst[0]['firstname'] . '_' . $position_id . '_' . $pdf_career_id . ".pdf";

			$pdf->WriteHTML($pdf_html);
			$pdf->SetCompression(false);
			$pdf->SetDisplayMode('real');
			$pdf->SetDisplayMode('default');
			$pdf->SetAutoPageBreak(true);

			if ($force_open_flag == 1) {
				$path = $pdf->Output($pdfFilePath, "D");
			} else {
				$path = $pdf->Output('uploads/Careers_Data/' . $pdfFilePath, "F");
			}
			$pdf_generated_cnt++;
			exit;
		}



		$pdf_generated_cnt = $cnt = 0;

		if (count($sql) > 0) {
			$file_arr = array();
			foreach ($sql as $rec) {
				$pdf_career_id = $rec['careers_id'];
				$this->db->where('careers_id', $pdf_career_id);
				$this->db->where('active_status', '1');

				if ($position_id != 7 &&  $position_id != 15 && $position_id != 16 && $position_id != 14 && $position_id != 12) {
					$this->db->where('pay_status', '1');
				}

				$head_title = 'APPLICATION';

				if ($position_id  == 7) {
					$head_title = 'Application for the post of Faculty Member (HRM) on contract basis';
				} else if ($position_id  == 12) {
					$head_title = 'Application for the post of Head PDC (SZ) - MUMBAI on contract basis';
				} else if ($position_id  == 14) {
					$head_title = 'Application for the post of Faculty Member (Information Technology) on contract basis';
				}


				$rst = $this->master_model->getRecords("careers_registration");
				//echo "SQL>" . $this->db->last_query();exit;
				$this->db->select('m.id,m.course_name,c.careers_id,c.specialisation,q.college_name,q.university,q.from_date,q.to_date,q.grade_marks,q.class, q.aggregate_marks_obtained, q.aggregate_max_marks, q.percentage, q.name_subject_of_course');
				$this->db->join('careers_registration c', 'c.careers_id=q.careers_id', 'LEFT');
				$this->db->join('careers_course_mst m', 'm.course_code=q.course_code', 'LEFT');
				$this->db->where('c.careers_id', $pdf_career_id);
				$this->db->where('active_status', '1');
				//$this->db->where('q.careers_id', '42');
				$qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");

				//print_r($qualification_arr);exit;

				$this->db->select('c.careers_id,e.careers_id as employ_id,e.organization,e.designation,e.responsibilities,e.job_from_date,e.job_to_date');
				$this->db->join('careers_registration c', 'c.careers_id=e.careers_id', 'LEFT');
				$this->db->where('c.careers_id', $pdf_career_id);
				$this->db->where('c.active_status', '1');
				$emp_hist_arr = $this->master_model->getRecords('careers_employment_hist e');
				//print_r($emp_hist_arr); 

				$this->db->where('state_code', $rst[0]["state_pr"]);
				$stateDetails = $this->master_model->getRecords('state_master');

				if ($position_id == 1) {
					$this->db->select('transaction_no,receipt_no,amount');
					$this->db->where('member_regnumber', $rst[0]["reg_id"]);
					//	$this->db->where('c.active_status', '1');
					$payment_transaction = $this->master_model->getRecords('payment_transaction');
				}
				if ($position_id == 3 || $position_id == 4 || $position_id == 12 || $position_id == 7 || $position_id == 15 || $position_id == 16) {
					$this->db->where('careers_id', $career_id);
					$rst = $this->master_model->getRecords("careers_registration");
					// echo "<pre>";  print_r($rst); exit;
					$pdf_html = $this->getCustomPDFhtml($rst, $career_id, $position_id, $emp_hist_arr);

					$pdf = $this->m_pdf->load();

					$pdfFilePath = $rst[0]['firstname'] . '_' . $position_id . '_' . $pdf_career_id . ".pdf";

					$pdf->WriteHTML($pdf_html);
					$pdf->SetCompression(false);
					$pdf->SetDisplayMode('real');
					$pdf->SetDisplayMode('default');
					$pdf->SetAutoPageBreak(true);

					if ($force_open_flag == 1) {
						$path = $pdf->Output($pdfFilePath, "D");
					} else {
						$path = $pdf->Output('uploads/Careers_Data/' . $pdfFilePath, "F");
					}
					$pdf_generated_cnt++;
					exit;
				}

				$spouse_name = $mother_name = $religion = $physical_disbaility = $physical_disbaility_desc = $post_qua_name = $post_gra_sub = $post_gra_college_name = $post_gra_university = $post_gra_from_date = $post_gra_to_date = $post_aggregate_marks_obtained = $post_gra_aggregate_max_marks = $post_gra_percentage = $post_gra_class = $postcer_qua_name_gra_from_date = $cer_gra_sub = $cer_college_name = $cer_university = $cer_from_date = $cer_to_date = $cer_marks_obtained = $cer_aggregate_max_marks = $cer_percentage = $cer_class = $desirable_course_name = $desirable_college_name = $desirable_name_subject_of_course = $desirable_specialisation = $desirable_university = $desirable_from_date = $desirable_to_date = $desirable_degree_completion_date = $desirable_aggregate_marks_obtained = $desirable_aggregate_max_marks = $desirable_percentage = $desirable_grade_marks = $desirable_class = $whether_in_service = $vrs_register_date = $reason_of_resign = $name_of_present_organization = $service_from_date = $comm_address_of_org = $curr_designation = $any_other_details = $professional_ass = '';

				if ($position_id == 7 || $position_id == 14 || $position_id == 12 || $position_id == 15 || $position_id == 1) {
					$this->db->where('careers_id', $rst[0]["careers_id"]);
					$career_other_details = $this->master_model->getRecords('career_other_details');
					if ($career_other_details) {
						$spouse_name = $career_other_details[0]['spouse_name'];
						if ($spouse_name == '') $spouse_name = '-';
						$mother_name = $career_other_details[0]['mother_name'];
						$religion = $career_other_details[0]['religion'];
						$physical_disbaility = $career_other_details[0]['physical_disbaility'];
						$physical_disbaility_desc = $career_other_details[0]['physical_disbaility_desc'];
						$post_qua_name = $career_other_details[0]['post_qua_name'];
						$post_gra_sub = $career_other_details[0]['post_gra_sub'];
						$post_gra_college_name = $career_other_details[0]['post_gra_college_name'];
						$post_gra_university = $career_other_details[0]['post_gra_university'];

						$post_gra_from_date = $career_other_details[0]['post_gra_from_date'];
						$post_gra_to_date = $career_other_details[0]['post_gra_to_date'];


						if ($post_gra_from_date != "" && $post_gra_from_date != "0000-00-00") {
							$post_gra_from_date = date("d-m-Y", strtotime($post_gra_from_date));
						} else {
							$post_gra_from_date = '';
						}


						if ($post_gra_to_date != "" && $post_gra_to_date != "0000-00-00") {
							$post_gra_to_date = date("d-m-Y", strtotime($post_gra_to_date));
						} else {
							$post_gra_to_date = '';
						}

						$post_aggregate_marks_obtained = $career_other_details[0]['post_aggregate_marks_obtained'];
						$post_gra_aggregate_max_marks = $career_other_details[0]['post_gra_aggregate_max_marks'];
						$post_gra_percentage = $career_other_details[0]['post_gra_percentage'];
						$post_gra_class = $career_other_details[0]['post_gra_class'];
						$cer_qua_name = $career_other_details[0]['cer_qua_name'];
						$cer_gra_sub = $career_other_details[0]['cer_gra_sub'];
						$cer_college_name = $career_other_details[0]['cer_college_name'];
						$cer_university = $career_other_details[0]['cer_university'];

						$cer_from_date = $career_other_details[0]['cer_from_date'];
						if ($cer_from_date != "" && $cer_from_date != "0000-00-00") {
							$cer_from_date = date("d-m-Y", strtotime($cer_from_date));
						} else {
							$cer_from_date = '';
						}

						$cer_to_date = $career_other_details[0]['cer_to_date'];
						if ($cer_to_date != "" && $cer_to_date != "0000-00-00") {
							$cer_to_date = date("d-m-Y", strtotime($cer_to_date));
						} else {
							$cer_to_date = '';
						}


						$cer_marks_obtained = $career_other_details[0]['cer_marks_obtained'];
						$cer_aggregate_max_marks = $career_other_details[0]['cer_aggregate_max_marks'];
						$cer_percentage = $career_other_details[0]['cer_percentage'];
						$cer_class = $career_other_details[0]['cer_class'];

						$whether_in_service = $career_other_details[0]['whether_in_service'];

						$vrs_register_date = $career_other_details[0]['vrs_register_date'];
						if ($vrs_register_date != "" && $vrs_register_date != "0000-00-00") {
							$vrs_register_date = date("d-m-Y", strtotime($vrs_register_date));
						} else {
							$vrs_register_date = '';
						}

						$reason_of_resign = $career_other_details[0]['reason_of_resign'];
						$name_of_present_organization = $career_other_details[0]['name_of_present_organization'];

						$service_from_date = $career_other_details[0]['service_from_date'];
						if ($service_from_date != "" && $service_from_date != "0000-00-00") {
							$service_from_date = date("d-m-Y", strtotime($service_from_date));
						} else {
							$service_from_date = '';
						}

						$comm_address_of_org = $career_other_details[0]['comm_address_of_org'];
						$curr_designation = $career_other_details[0]['curr_designation'];
						$any_other_details = $career_other_details[0]['any_other_details'];

						$professional_ass = $career_other_details[0]['professional_ass'];
					}

					$this->db->select('m.id,m.course_name,q.*');
					$this->db->join('careers_course_mst m', 'm.course_code=q.course_code', 'LEFT');
					$this->db->where('careers_id', $pdf_career_id);
					$desirable_qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");
					if ($desirable_qualification_arr) {
						$desirable_course_name = $desirable_qualification_arr[0]['course_name'];
						$desirable_college_name = $desirable_qualification_arr[0]['college_name'];
						$desirable_name_subject_of_course = $desirable_qualification_arr[0]['name_subject_of_course'];
						$desirable_specialisation = $desirable_qualification_arr[0]['specialisation'];
						$desirable_university = $desirable_qualification_arr[0]['university'];
						$desirable_from_date = $desirable_qualification_arr[0]['from_date'];
						$desirable_to_date = $desirable_qualification_arr[0]['to_date'];


						$desirable_degree_completion_date = $desirable_qualification_arr[0]['degree_completion_date'];
						$desirable_aggregate_marks_obtained = $desirable_qualification_arr[0]['aggregate_marks_obtained'];
						$degree_completion_date = $desirable_qualification_arr[0]['degree_completion_date'];
						$desirable_aggregate_max_marks = $desirable_qualification_arr[0]['aggregate_max_marks'];
						$desirable_percentage = $desirable_qualification_arr[0]['percentage'];
						$desirable_grade_marks = $desirable_qualification_arr[0]['grade_marks'];
						$desirable_class = $desirable_qualification_arr[0]['class'];
					}
				}

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


				$html .= '<h1 style="text-align:center">' . $head_title . '</h1>';
				$html .= '<div class="table-responsive ">
					<table class="table table-bordered wikitable tabela" style="overflow: wrap">
					<tbody>';

				$application_title = '';
				if ($position_id == 1) {
					$application_title = "Junior Executive";
				} else if ($position_id == 2) {
					$application_title = "Assistant Director (IT)";
				} else if ($position_id == 3) {
					$application_title = "Assistant Director (Academics)";
				} else if ($position_id == 4) {
					$application_title = "Director (Training) on Contract";
				} else if ($position_id == 5) {
					$application_title = "Chief Executive Officer";
				} else if ($position_id == 6) {
					$application_title = "Deputy Director Accounts";
				} else if ($position_id == 7) {
					$application_title = "Faculty Member (HRM) on contract basis";
				} else if ($position_id == 9) {
					$application_title = "Deputy Director (IT)";
				} else if ($position_id == 10) {
					$application_title = "Research Associate";
				} else if ($position_id == 11) {
					$application_title = "Director on contract basis";
				}
				//else if($position_id == 12) { $application_title = "Head PDC EZ"; }
				else if ($position_id == 12) {
					$application_title = "Head PDC (WZ) - MUMBAI on contract basis";
				} else if ($position_id == 14) {
					$application_title = "Faculty Member (Information Technology) on contract basis";
				} else if ($position_id == 15) {
					$application_title = "In-charge, Development Centre, Lucknow on contract basis";
				} else if ($position_id == 16) {
					$application_title = "In-charge, Development Centre, Guwahati on contract basis";
				}

				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>BASIC DETAILS</strong></h4></td></tr>
					<tr>                    
					<td width="50%"><strong>Application for the post of:</strong></td>
					<td width="50%">' . $application_title . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>PHOTO:</strong></td>              
					<td width="50%"><img  class="column" width="70px" height="70px" align="right" src="' . base_url() . 'uploads/photograph/' . $rst[0]['scannedphoto'] . '"id="thumb" />
					</tr>';
				if ($position_id == 1) {

					$html .= '
        					<tr>                    
        					<td width="50%"><strong>ID:</strong></td>
        					<td width="50%">' . $rst[0]["reg_id"] . '</td>
        					</tr>';
					$html .= '
        					<tr>                    
        					<td width="50%"><strong>Amount:</strong></td>
        					<td width="50%">Rs. ' . $payment_transaction[0]["amount"] . '</td>
        					</tr>';
					$html .= '
        					<tr>                    
        					<td width="50%"><strong>TRANSACTION ID:</strong></td>
        					<td width="50%">' . $payment_transaction[0]["transaction_no"] . '</td>
        					</tr>';
					$html .= '
        					<tr>                    
        					<td width="50%"><strong>RECEIPT ID:</strong></td>
        					<td width="50%">' . $payment_transaction[0]["receipt_no"] . '</td>
        					</tr>';
				}
				$html .= '
					<tr>                    
					<td width="50%"><strong>Name:</strong></td>
					<td width="50%">' . $rst[0]["sel_namesub"] . ' ' . $rst[0]["firstname"] . ' ' . $rst[0]['middlename'] . ' ' . $rst[0]['lastname'] . '</td>
					</tr>  
					<tr>                    
					<td width="50%"><strong>Marital Status:</strong></td>
					<td width="50%">' . $rst[0]['marital_status'] . '</td>
					</tr>
					<tr>                    
					<td width="50%"><strong>Spouse\'s Name:</strong></td>
					<td width="50%">' . $spouse_name . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Father\'s Name:</strong></td>
					<td width="50%">' . $rst[0]["father_husband_name"] . '</td>
					</tr>
					';



				if ($position_id == 7 || $position_id == 14 || $position_id == 12 || $position_id == 1 || $position_id == 15) {
					$html .= '<tr>                    
						<td width="50%"><strong>Mother\'s Name :</strong></td>
						<td width="50%">' . $mother_name . '</td>
						</tr>';
				}

				$html .= '<tr>                    
					<td width="50%"><strong>Age as on 01.10.2024:</strong></td>
					<td width="50%">' . $rst[0]['dateofbirth'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Gender:</strong></td>
					<td width="50%">' . $rst[0]['gender'] . '</td>
					</tr>';

				if ($position_id == 7 || $position_id == 14 || $position_id == 12 || $position_id == 1  || $position_id == 15) {
					$html .= '<tr>                    
						<td width="50%"><strong>Religion :</strong></td>
						<td width="50%">' . $religion . '</td>
						</tr>';
				}

				$html .= '<tr>                    
					<td width="50%"><strong>Email Id:</strong></td>
					<td width="50%">' . $rst[0]['email'] . '</td>
					</tr>
					
					';

				if ($position_id == 7 || $position_id == 14 || $position_id == 12  || $position_id == 1  || $position_id == 15) {
					$html .= '<tr>                    
						<td width="50%"><strong>Are you a person with Physical Disability :</strong></td> 
						<td width="50%">' . ucwords($physical_disbaility) . '</td>
						</tr>';

					if ($physical_disbaility == 'yes') {
						$html .= '<tr>                    
							<td width="50%"><strong>Type of Disability:</strong></td> 
							<td width="50%">' . $physical_disbaility_desc . '</td>
							</tr>';
					}
				}

				$html .= '<tr>                    
					<td width="50%"><strong>Mobile No.:</strong></td>
					<td width="50%">' . $rst[0]['mobile'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Alternate Mobile No.:</strong></td>
					<td width="50%">' . $rst[0]['alternate_mobile'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>PAN Number:</strong></td>
					<td width="50%">' . $rst[0]['pan_no'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Aadhar Card Number:</strong></td>
					<td width="50%">' . $rst[0]['aadhar_card_no'] . '</td>
					</tr>

					<tr><td colspan="2" style="color: #66d9ff"><h4><strong>COMMUNICATION ADDRESS</strong></h4></td></tr>
					<tr>                    
					<td width="50%"><strong>COMMUNICATION ADDRESS:</strong></td>
					<td width="50%">' . $rst[0]['addressline1'] . ', ' . $rst[0]['addressline2'] . ', ' . $rst[0]['addressline3'] . ', ' . $rst[0]['addressline4'] . '<br>' . $rst[0]['district'] . ', ' . $rst[0]['city'] . '<br>' . $rst[0]['state'] . '<br>' . $rst[0]['pincode'] . '</td>
					</tr> 

					<tr><td colspan="2" style="color: #66d9ff"><h4><strong>PERMANENT ADDRESS</strong></h4></td></tr>
					<tr>                    
					<td width="50%"><strong>PERMANENT ADDRESS:</strong></td>
					<td width="50%">' . $rst[0]['addressline1_pr'] . ', ' . $rst[0]['addressline2_pr'] . ', ' . $rst[0]['addressline3_pr'] . ', ' . $rst[0]['addressline4_pr'] . '<br>' . $rst[0]['district_pr'] . ', ' . $rst[0]['city_pr'] . '<br>' . $stateDetails[0]['state_name'] . '<br>' . $rst[0]['pincode_pr'] . '</td>
					</tr> </table>';

				$html .= '
					<table class="table table-bordered wikitable tabela" style="overflow: wrap">
					<tbody>
					<tr>
					<td colspan="10" style="color: #66d9ff"><h4><strong>EDUCATIONAL QUALIFICATION</strong></h4>
					<br><strong style="color: #444">ESSENTIAL</strong>
					</td></tr>
					<tr><td><strong>Name</strong></td><td><strong>Graduation degree Name</strong></td>
					<td ><strong>Graduation Stream & Subject</strong></td><td><strong>College/Institution Name</strong></td>
					<td><strong>University Name</strong></td><td><strong>Period</strong></td><td><strong>Aggregate Marks Obtained</strong></td>
					<td><strong>Aggregate Maximum Marks</strong></td>
					<td><strong>Final Percentage</strong></td><td><strong>Class/Grade</strong></td>
					<tr>
					<td ><note>EDUCATIONAL Qualification 1 - Academic (Graduation Onwards)</note></td>
					<td >' . $rst[0]['ess_course_name'] . '</td>
					<td >' . $rst[0]['ess_pg_stream_subject'] . '</td>
					<td >' . $rst[0]['ess_college_name'] . '</td>
					<td >' . $rst[0]['ess_university'] . '</td>
					<td >' . date("d-m-Y", strtotime($rst[0]['ess_from_date'])) . " TO " . date("d-m-Y", strtotime($rst[0]['ess_to_date'])) . '</td>
					<td >' . $rst[0]['ess_aggregate_marks_obtained'] . '</td>
					<td >' . $rst[0]['ess_aggregate_max_marks'] . '</td>
					<td >' . $rst[0]['ess_percentage'] . '</td>
					<td >' . $rst[0]['ess_class'] . '</td>
					</tr>
						';
				if ($post_qua_name != '') {
					$html .= '<tr>
							<td ><note>Educational Qualification 2 - Post Graduation</note></td>
							<td >' . $post_qua_name . '</td>
							<td >' . $post_gra_sub . '</td>
							<td >' . $post_gra_college_name . '</td>
							<td >' . $post_gra_university . '</td>
							<td >' . $post_gra_from_date . " To " . $post_gra_to_date . '</td>
							<td >' . $post_aggregate_marks_obtained . '</td>
							<td >' . $post_gra_aggregate_max_marks . '</td>
							<td >' . $post_gra_percentage . '</td>
							<td >' . $post_gra_class . '</td>
							</tr>';
				}
				if ($cer_qua_name != '') {
					$html .= '
							<tr>
							<td ><note>Educational Qualification 3: Additional Qualifications/Certification</note></td>
							<td >' . $cer_qua_name . '</td>
							<td >' . $cer_gra_sub . '</td>
							<td >' . $cer_college_name . '</td>
							<td >' . $cer_university . '</td>
							<td >' . $cer_from_date . " To " . $cer_to_date . '</td>
							<td >' . $cer_marks_obtained . '</td>
							<td >' . $cer_aggregate_max_marks . '</td>
							<td >' . $cer_percentage . '</td>
							<td >' . $cer_class . '</td>
							</tr>
							';
				}
				$html .= '</tbody>
					</table>
					
			          
					';
				$html .= '<table class="table table-bordered wikitable tabela" style="overflow: wrap;margin-top:10px;"><tbody>';
				if ($position_id == 7 || $position_id == 12 || $position_id == 3 || $position_id == 14) {
					$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td></tr>';
					$html .= '<tr>                    
						<td width="50%"><strong>CAIIB:</strong></td>
						<td width="50%">CAIIB</td>
						</tr>
						
						<tr>                    
						<td width="50%"><strong>Year of passing:</strong></td>
						<td width="50%">' . $rst[0]['year_of_passing'] . '</td>
						</tr>
						
						<tr>                    
						<td width="50%"><strong>Membership Number:</strong></td>
						<td width="50%">' . $rst[0]['membership_number'] . '</td>
						</tr>';
				}
				if ($desirable_course_name != '') {
					$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>DESIRABLE QUALIFICATION</strong></h4></td></tr>
						<tr>                    
						<td width="50%"><strong>Name of course:</strong></td>
						<td width="50%">' . $desirable_course_name . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Subject:</strong></td>
						<td width="50%">' . $desirable_name_subject_of_course . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>College Name and Address:</strong></td>
						<td width="50%">' . $desirable_college_name . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>University:</strong></td>
						<td width="50%">' . $desirable_university . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Period:</strong></td>
						<td width="50%">' . $desirable_from_date . " To " . $desirable_to_date . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Date of completion of the Degree:</strong></td>
						<td width="50%">' . $degree_completion_date . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Aggregate Marks Obtained:</strong></td>
						<td width="50%">' . $desirable_aggregate_marks_obtained . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Aggregate Maximum Marks:</strong></td>
						<td width="50%">' . $desirable_aggregate_max_marks . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Percentage:</strong></td>
						<td width="50%">' . $desirable_percentage . '</td>
						</tr>
						<tr>                    
						<td width="50%"><strong>Class/Grade:</strong></td>
						<td width="50%">' . $desirable_class . '</td>
						</tr>
						';
				}
				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY (from Recent employment to Oldest employment) - Last 5 positions held with role & responsibilities in detail</strong></h4></td></tr>';
				if ($emp_hist_arr) {
					foreach ($emp_hist_arr as $rest) {
						$html .= '<tr>                    
								<td width="50%"><strong>Name of the Organization/Employer/Bank:</strong></td>
								<td width="50%">' . $rest['organization'] . '</td>
								</tr>';
						$html .= '<tr>                    
								<td width="50%"><strong>Period:</strong></td>
								<td width="50%">' . date("d-m-Y", strtotime($rest['job_from_date'])) . " To " . date("d-m-Y", strtotime($rest['job_to_date'])) . '</td>
								</tr>';
						$html .= '<tr>                    
								<td width="50%"><strong>Designation:</strong></td>
								<td width="50%">' . $rest['designation'] . '</td>
								</tr>';
						$html .= '<tr>                    
								<td width="50%"><strong>Responsibilities/Nature of Duties Performed:</strong></td>
								<td width="50%">' . $rest['responsibilities'] . '</td>
								</tr>';
					}
				}
				if ($position_id != 1) {
					$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Whether In Service or not?</strong></h4></td></tr>
									<tr>                    
									<td width="50%"><strong>Whether In Service?:</strong></td>
									<td width="50%">' . ucwords($whether_in_service) . '</td>
									</tr>';

					if ($whether_in_service == "no") {
						$html .= '<tr>                    
										<td width="50%"><strong>Date of Superannuation/VRS/Resignation etc:</strong></td>
										<td width="50%">' . $vrs_register_date . '</td>
										</tr>
										<tr>                    
										<td width="50%"><strong>Reason for Resignation/Leaving:</strong></td>
										<td width="50%">' . $reason_of_resign . '</td>
										</tr>';
					} else if ($whether_in_service == 'yes') {
						$html .= '<tr>                    
										<td width="50%"><strong>Name of the Present Organization:</strong></td>
										<td width="50%">' . $name_of_present_organization . '</td>
										</tr>
										<tr>                    
										<td width="50%"><strong>Period:</strong></td>
										<td width="50%">' . $service_from_date . '</td>
										</tr>
										<tr>                    
										<td width="50%"><strong>Communication Address of the Organization:</strong></td>
										<td width="50%">' . $comm_address_of_org . '</td>
										</tr>
										<tr>                    
										<td width="50%"><strong>Designation/Post Held:</strong></td>
										<td width="50%">' . $curr_designation . '</td>
										</tr>
										<tr>                    
										<td width="50%"><strong>Any Other Details:</strong></td>
										<td width="50%">' . $any_other_details . '</td>
										</tr>';
					}
				}
				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Experience as Faculty</strong></h4></td></tr>';

				if ($position_id == 7 || $position_id == 12 || $position_id == 14  || $position_id == 15) {
					$html .= '<tr>                    
						<td width="50%"><strong>Experience as Faculty:</strong></td>
						<td width="50%">' . $rst[0]['exp_in_bank'] . '</td>
						</tr>';



					$html .= '<tr>                    
						<td width="50%"><strong>Published Articles/Books:</strong></td>
						<td width="50%">' . $rst[0]['publication_of_books'] . '</td>
						</tr>';

					$html .= '<tr>                    
						<td width="50%"><strong>Membership of Professional Associations:</strong></td>
						<td width="50%">' . $professional_ass . '</td>
						</tr>';
				}

				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Languages, Extracurricular, Achievements</strong></h4></td></tr>';

				$html .= '
					<tr>                    
					<td width="50%"><strong>Languages Known 1:</strong></td>
					<td width="50%">' . $rst[0]['languages_known'] . '</td>
					</tr>
					';
				if ($position_id == 12 || $position_id == 3 || $position_id == 4  || $position_id == 15) {
					$html .= '<tr>                    
						<td width="50%"><strong>Area of Specialisation:</strong></td>
						<td width="50%">' . $rst[0]['exp_in_functional_area'] . '</td>
						</tr>';
				}
				$html .= '
					<tr>                    
					<td width="50%"><strong>Languages Options 1:</strong></td>
					<td width="50%">' . $rst[0]['languages_option'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Languages Known 2:</strong></td>
					<td width="50%">' . $rst[0]['languages_known1'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Languages Options 2:</strong></td>
					<td width="50%">' . $rst[0]['languages_option1'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Languages Known 3:</strong></td>
					<td width="50%">' . $rst[0]['languages_known2'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Languages Options 3:</strong></td>
					<td width="50%">' . $rst[0]['languages_option2'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Extracurricular (Games / Membership / Association):</strong></td>
					<td width="50%">' . $rst[0]['extracurricular'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Hobbies:</strong></td>
					<td width="50%">' . $rst[0]['hobbies'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Achievements:</strong></td>
					<td width="50%">' . $rst[0]['achievements'] . '</td>
					</tr>';

				if ($position_id == 10 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14  || $position_id == 15) {
					$html .= '	<tr><td colspan="2" style="color: #66d9ff"><h4><strong>REFERENCES</strong></h4></td></tr>
						<tr><td colspan="2">Candidates are instructed to provide two professional references. (References of family members, relatives & friends will not be considered)</td></tr>';
				}

				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Professional Reference 1</strong></h4></td></tr>
					<tr>                    
					<td width="50%"><strong>Name:</strong></td>
					<td width="50%">' . $rst[0]['refname_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Complete Address:</strong></td>
					<td width="50%">' . $rst[0]['refaddressline_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Organisation (If employed):</strong></td>
					<td width="50%">' . $rst[0]['reforganisation_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Designation:</strong></td>
					<td width="50%">' . $rst[0]['refdesignation_one'] . '</td>
					</tr>  

					<tr>                    
					<td width="50%"><strong>Email Id:</strong></td>
					<td width="50%">' . $rst[0]['refemail_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Mobile:</strong></td>
					<td width="50%">' . $rst[0]['refmobile_one'] . '</td>
					</tr>';


				$html .= '<br><tr><td colspan="2" style="color: #66d9ff"><h4><strong>Professional Reference 2</strong></h4></td></tr>';
				$html .= '<tr>                    
					<td width="50%"><strong>Name:</strong></td>
					<td width="50%">' . $rst[0]['refname_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Complete Address:</strong></td>
					<td width="50%">' . $rst[0]['refaddressline_two'] . '</td>
					</tr>

					<tr>                    
					<td width="50%"><strong>Organisation (If employed):</strong></td>
					<td width="50%">' . $rst[0]['reforganisation_two'] . '</td>
					</tr>

					<tr>                    
					<td width="50%"><strong>Designation:</strong></td>
					<td width="50%">' . $rst[0]['refdesignation_two'] . '</td>
					</tr> 

					<tr>                    
					<td width="50%"><strong>Email Id:</strong></td>
					<td width="50%">' . $rst[0]['refemail_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>Mobile:</strong></td>
					<td width="50%">' . $rst[0]['refmobile_two'] . '</td>
					</tr>
					
					<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Other Information</strong></h4></td></tr>
					<tr>                    
					<td width="50%"><strong>Any other information that the candidate would like to add:</strong></td>
					<td width="50%"><div style="word-break:break-all;">' . $rst[0]['comment'] . '</div></td>
					</tr> 
					';




				if ($position_id == 7 || $position_id == 14 || $position_id == 12  || $position_id == 15) {
					$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Declaration</strong></h4></td></tr>';

					$html .= '<tr>                    
					<td width="50%"><strong>Declaration 1 : I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</strong></td>
					<td width="50%">YES</td>
					</tr>';

					$html .= '<tr>                    
					<td width="50%"><strong>Declaration 2:   I hereby agree that any legal proceedings in respect of any matter or claims or disputes arising out of application or out of said advertisement can be instituted by me at Mumbai only and shall have sole and exclusive jurisdiction to try any cause / dispute. I undertake to abide by all the terms and conditions of the advertisement given by the Indian Institute of Banking & Finance</strong></td>
					<td width="50%">YES</td>
					</tr>';
					$html .= '<tr>                    
					<td width="50%"><strong>Declaration 3:   I further certify that no disciplinary / vigilance proceedings are either pending or contemplated against me and that there are no legal cases pending against me. There have been no major / minor penalty</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}

				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>UPLOAD</strong></h4></td></tr>';

				$html .= '<tr>                    
					<td width="50%"><strong>Signature:</strong></td>
					<td><img width="70px" height="70px" src="' . base_url() . 'uploads/scansignature/' . $rst[0]['scannedsignaturephoto'] . '" id="thumb" /></td>
					</tr>
					';

				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Place and Date</strong></h4></td></tr>';

				$html .= '<tr>                    
					<td width="50%"><strong>Place:</strong></td>
					<td width="50%">' . $rst[0]['place'] . '</td>
					</tr> 
					<tr>
					<td width="50%"><strong>Date:</strong></td>
					<td width="50%">' . date("d-m-Y", strtotime($rst[0]['submit_date'])) . '</td>
					</tr>
					';

				/*if($position_id == 1 ) {
					       //echo'<pre>';print_r($payment_transaction);
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
						}*/



				if ($position_id != 7 &&  $position_id != 15 && $position_id != 16 && $position_id != 14 && $position_id != 12  && $position_id != 15) {
					$html .= '<tr>                    
					<td width="50%"><strong>CONTACT NUMBER:</strong></td>
					<td width="50%">' . $rst[0]['contact_number'] . '</td>
					</tr>';
				}
				if ($position_id == 1 || $position_id == 2) {
					$html .= '<tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION</strong></h4></td><td></td></tr>';

					$html .= '<tr><td colspan="2">The date of passing eligibility examination will be the date appearing on the marksheet issued by the University/Institute. The percentage marks shall be arrived at by dividing the total marks obtained by the candidate in all the subjects in all semesters / years by aggregate maximum marks in all the subjects irrespective of optional/additional optional subject, if any. The fraction of percentage so arrived will be ignored i.e. 59.99% will be treated as less than 60%.</td></tr>';
				}

				/*$html.= '<tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION (ESSENTIAL)</strong></h4></td><td></td></tr>'; */


				foreach ($rst as $row) {
					if ($position_id == 1 || $position_id == 2 || $position_id == 3 || $position_id == 6 || $position_id == 9 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 14 || $position_id == 12  || $position_id == 15) {

						$html .= '<tr>                    
							<td width="50%"><strong>';
						if ($position_id == 3)
							$html .= 'Name of course (Post Graduate):';
						else
							$html .= 'NAME OF COURSE:';
						$html .= '</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_course_name'] . '</div></td>
							</tr>';
					} else if ($position_id == 7 || $position_id == 12 || $position_id == 14  || $position_id == 15) {
						$html .= '<tr>                    
							<td width="50%"><strong>NAME OF COURSE(POST GRADUATE):</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_course_name'] . '</div></td>
							</tr>
							
							<tr>                    
							<td width="50%"><strong>IF POST GRADUATION - STREAM & SUBJECT :</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_pg_stream_subject'] . '</div></td>
							</tr>';
					} else if ($position_id == 10) {
						$html .= '	<tr>                    
							<td width="50%"><strong>DEGREE:</strong></td>                          
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_course_name'] . '</div></td>
							</tr>';
					} else if ($position_id == 11 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 14 || $position_id == 12  || $position_id == 15) {
						$html .= '	<tr>                    
							<td width="50%"><strong>GRADUATION STREAM:</strong></td>                          
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_course_name'] . '</div></td>
							</tr>';
					}

					if ($position_id == 1) {
						$html .= '<tr>                    
							<td width="50%"><strong>SUBJECT:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_subject'] . '</div></td>
							</tr>';
					}
					if ($position_id == 1 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 14 || $position_id == 12) {
						$html .= '<tr>                    
							<td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_college_name'] . '</div></td>
							</tr>';
					}
					if ($position_id == 1 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 14 || $position_id == 12) {
						$html .= '<tr>                    
							<td width="50%"><strong>UNIVERSITY:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_university'] . '</div></td>
							</tr>';
					}
					if ($position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 11) {
						$html .= '<tr>                    
							<td width="50%"><strong>SUBJECT:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['ess_subject'] . '</div></td>
							</tr>';
					}

					if ($position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 11) {
						$html .= '<tr>                    
							<td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
							<td width="50%">' . $row['ess_college_name'] . '</td>
							</tr>';
					} else if ($position_id == 7 || $position_id == 12 || $position_id == 14) {
						$html .= '<tr>                    
							<td width="50%"><strong>COLLEGE/ INSTITUTION NAME AND ADDRESS:</strong></td>
							<td width="50%">' . $row['ess_college_name'] . '</td>
							</tr>';
					}

					if ($position_id == 3) {
						$html .= '<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">' . $row['ess_college_name'] . '</td>
							</tr>';
					} else if ($position_id == 6) {
						$html .= '	<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">' . $row['ess_university'] . '</td>
							</tr>';
					}

					if ($position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 11 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
						$html .= '<tr>                    
							<td width="50%"><strong>UNIVERSITY:</strong></td>
							<td width="50%">' . $row['ess_university'] . '</td>
							</tr>';
					}

					$html .= '<tr>                    
						<td width="50%"><strong>PERIOD:</strong></td>
						<td width="50%">' . $row['ess_from_date'] . " to " . $row['ess_to_date'] . '</td>
						</tr>';

					if ($position_id == 10 || $position_id == 1) {
						$html .= '	<tr>                    
							<td width="50%"><strong>DATE OF COMPLETION OF THE DEGREE:</strong></td>
							<td width="50%">' . $row['ess_degree_completion_date'] . '</td>
							</tr>';
					}

					if ($position_id == 1 || $position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
						$html .= '	<tr>                    
							<td width="50%"><strong>AGGREGATE MARKS OBTAINED:</strong></td>
							<td width="50%">' . $row['ess_aggregate_marks_obtained'] . '</td>
							</tr>
							
							<tr>                    
							<td width="50%"><strong>AGGREGATE MAXIMUM MARKS:</strong></td>
							<td width="50%">' . $row['ess_aggregate_max_marks'] . '</td>
							</tr>';
					}

					if ($position_id == 3) {
						$html .= '<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">' . $row['ess_grade_marks'] . '</td>
							</tr>';
					} else if ($position_id == 2 || $position_id == 6 || $position_id == 9 || $position_id == 10 || $position_id == 11 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
						$html .= '	<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">' . $row['ess_percentage'] . '</td>
							</tr>';
					}
					/* else if($position_id == 1 || $position_id == 4 || $position_id == 6)
							{
							$html.= '<tr>                    
							<td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
							<td width="50%">'.$row['ess_grade_marks'].'</td>
							</tr>';
						} */

					if ($position_id == 1 || $position_id == 2 || $position_id == 9 || $position_id == 10 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
						$html .= '<tr>                    
							<td width="50%"><strong>CLASS/GRADE:</strong></td>
							<td width="50%">' . $row['ess_class'] . '</td>
							</tr>';
					}
				}

				if ($position_id == 7 || $position_id == 12 || $position_id == 3 || $position_id == 14) {
					$html .= '<tr><td style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td><td></td></tr>';

					$html .= '<tr>                    
						<td width="50%"><strong>CAIIB:</strong></td>
						<td width="50%">CAIIB</td>
						</tr>
						
						<tr>                    
						<td width="50%"><strong>YEAR OF PASSING:</strong></td>
						<td width="50%">' . $row['year_of_passing'] . '</td>
						</tr>
						
						<tr>                    
						<td width="50%"><strong>MEMBERSHIP NUMBER:</strong></td>
						<td width="50%">' . $row['membership_number'] . '</td>
						</tr>';
				}

				$html .= '<tr><td style="color: #66d9ff"><h4><strong>EDUCATION QUALIFICATION (DESIRABLE)</strong></h4></td><td></td></tr>';

				//	print_r($qualification_arr);exit;
				foreach ($qualification_arr as $row) {
					if ($position_id == 2 || $position_id == 9) {
						$html .= '<tr>                    
							<td width="50%"><strong>NAME OF CERTIFICATION COURSE:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['course_name'] . '</div></td>
							</tr>';
					} else if ($position_id == 10) {
						$course_code_nm = '';
						$course_code_arr = explode(",", $row['college_name']);
						if (count($course_code_arr) > 0) {
							foreach ($course_code_arr as $course_code) {
								$course_code_nm .= '> ' . $course_code . '<br>';
							}
						}

						$html .= '	<tr>                    
							<td colspan="2">' . $course_code_nm . '</td>
							</tr>';
					} else {
						/*$html.= '<tr>                    
							<td width="50%"><strong>NAME OF COURSE:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['course_name'].'</div></td>
							</tr>';*/
					}

					if ($position_id == 7 || $position_id == 12 || $position_id == 14) {
						$html .= '	<tr>                    
							<td width="50%"><strong>NAME & SUBJECT OF THE COURSE:</strong></td>
							<td width="50%"><div style="word-break:break-all;">' . $row['name_subject_of_course'] . '</div></td>
							</tr>';
					}

					/* if($position_id == 6)
							{
							$html.= '	<tr>                    
							<td width="50%"><strong>SPECIALISATION:</strong></td>
							<td width="50%"><div style="word-break:break-all;">'.$row['specialisation'].'</div></td>
							</tr>';
						} */


					if ($position_id == 3 || $position_id == 1 || $position_id == 6) {
						$html .= '<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">' . $row['college_name'] . '</td>
							</tr>';
					} else if ($position_id == 6 || $position_id == 1) {
						$html .= '<tr>                    
							<td width="50%"><strong>INSTITUTE NAME:</strong></td>
							<td width="50%">' . $row['university'] . '</td>
							</tr>';
					} else if ($position_id == 2 || $position_id == 9 || $position_id == 11 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
						$html .= '	<tr>                    
							<td width="50%"><strong>COLLEGE NAME AND ADDRESS:</strong></td>
							<td width="50%">' . $row['college_name'] . '</td>
							</tr>';
					}

					if ($position_id == 2 || $position_id == 9 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
						$html .= '<tr>                    
							<td width="50%"><strong>UNIVERSITY:</strong></td>
							<td width="50%">' . $row['university'] . '</td>
							</tr>';
					} else if ($position_id == 11) {
						$html .= '	<tr>                    
							<td width="50%"><strong>UNIVERSITY/INSTITUTE:</strong></td>
							<td width="50%">' . $row['university'] . '</td>
							</tr>';
					}

					if ($position_id != 10) {
						$html .= '<tr>                    
							<td width="50%"><strong>PERIOD:</strong></td>
							<td width="50%">' . $row['from_date'] . " to " . $row['to_date'] . '</td>
							</tr>';
					}

					if ($position_id == 2 || $position_id == 9 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 1 || $position_id == 14) {
						$html .= '	<tr>                    
							<td width="50%"><strong>AGGREGATE MARKS OBTAINED:</strong></td>
							<td width="50%">' . $row['aggregate_marks_obtained'] . '</td>
							</tr>
							
							<tr>                    
							<td width="50%"><strong>AGGREGATE MAXIMUM MARKS:</strong></td>
							<td width="50%">' . $row['aggregate_max_marks'] . '</td>
							</tr>';
					}

					if ($position_id == 3 || $position_id == 1 || $position_id == 6) {
						$html .= '<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">' . $row['grade_marks'] . '</td>
							</tr>';
					} else if ($position_id == 1 || $position_id == 2 || $position_id == 6 || $position_id == 9 || $position_id == 11 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
						$html .= '<tr>                    
							<td width="50%"><strong>PERCENTAGE:</strong></td>
							<td width="50%">' . $row['percentage'] . '</td>
							</tr>';
					}
					/* else if($position_id == 1 || $position_id == 4 || $position_id == 6)
							{
							$html.= '	<tr>                    
							<td width="50%"><strong>GRADE/PERCENTAGE:</strong></td>
							<td width="50%">'.$row['grade_marks'].'</td>
							</tr>';
						} */

					if ($position_id == 1 || $position_id == 2 || $position_id == 9 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14)/*  || $position_id == 1 || $position_id == 4 || $position_id == 6 */ {
						$html .= '<tr>                    
							<td width="50%"><strong>CLASS/GRADE:</strong></td>
							<td width="50%">' . $row['class'] . '</td>
							</tr>';
					}
				}

				$html .= '<tr><td style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY</strong></h4></td><td></td></tr>';
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
						<td width="50%"><strong>RESPONSIBILITIES:</strong></td>
						<td width="50%">' . $rest['responsibilities'] . '</td>
						</tr>';
					$html .= '<tr>                    
						<td width="50%"><strong>PERIOD:</strong></td>
						<td width="50%">' . $rest['job_from_date'] . " to " . $rest['job_to_date'] . '</td>
						</tr>';
				}

				if ($position_id == 7 || $position_id == 12 || $position_id == 14) {
					$html .= '<tr>                    
						<td width="50%"><strong>EXPERIENCE AS FACULTY IN BANKING/FINANCIAL INSTITUTION:</strong></td>
						<td width="50%">' . $rst[0]['exp_in_bank'] . '</td>
						</tr>';

					$html .= '<tr>                    
						<td width="50%"><strong>PUBLISHED ARTICLES/BOOKS:</strong></td>
						<td width="50%">' . $rst[0]['publication_of_books'] . '</td>
						</tr>';

					$html .= '<tr>                    
						<td width="50%"><strong>EXPERIENCE IN ONE OR MORE COVERING THE FUNCTIONAL AREAS:</strong></td>
						<td width="50%">' . $rst[0]['exp_in_functional_area'] . '</td>
						</tr>';
				}

				$html .= '	<tr><td style="color: #66d9ff"><h4><strong>LANGUAGES, EXTRACURRICULAR, ACHIEVEMENTS </strong></h4></td><td></td></tr>';

				if ($position_id == 3) {
					$html .= '<tr>                    
						<td width="50%"><strong>EXPERIENCE AS FACULTY IN BANKING/FINANCIAL INSTITUTION:</strong></td>
						<td width="50%">' . $rst[0]['exp_in_bank'] . '</td>
						</tr>';

					$html .= '<tr>                    
						<td width="50%"><strong>PUBLISHED ARTICLES/BOOKS:</strong></td>
						<td width="50%">' . $rst[0]['publication_of_books'] . '</td>
						</tr>';
				}

				$html .= '<tr>                    
					<td width="50%"><strong>LANGUAGES OPTIONS 1:</strong></td>
					<td width="50%">' . $rst[0]['languages_option'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES KNOWN 2:</strong></td>
					<td width="50%">' . $rst[0]['languages_known1'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES OPTIONS 2:</strong></td>
					<td width="50%">' . $rst[0]['languages_option1'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES KNOWN 3:</strong></td>
					<td width="50%">' . $rst[0]['languages_known2'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>LANGUAGES OPTIONS 3:</strong></td>
					<td width="50%">' . $rst[0]['languages_option2'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EXTRACURRICULAR:</strong></td>
					<td width="50%">' . $rst[0]['extracurricular'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>HOBBIES:</strong></td>
					<td width="50%">' . $rst[0]['hobbies'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>ACHIEVEMENTS:</strong></td>
					<td width="50%">' . $rst[0]['achievements'] . '</td>
					</tr>';

				if ($position_id == 2 || $position_id == 3 || $position_id == 6 || $position_id == 9 || $position_id == 10) {
					$html .= '<tr>                    
						<td width="50%"><strong>DECLARATION: Have you ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification:</strong></td>
						<td width="50%">' . $rst[0]['declaration1'] . '</td>
						</tr>';
					if ($rst[0]['declaration1'] == 'Yes') {
						$html .= '<tr>                    
							<td width="50%"><strong>DECLARATION NOTE:</strong></td>
							<td width="50%">' . $rst[0]['declaration_note'] . '</td>
							</tr>';
					}
				}

				if ($position_id == 10 || $position_id == 7 || $position_id == 15 || $position_id == 16 || $position_id == 12 || $position_id == 14) {
					$html .= '	<tr><td style="color: #66d9ff"><h4><strong>REFERENCE</strong></h4></td><td></td></tr>
						<tr><td colspan="2">Candidates are instructed to provide two professional references. (References of family members, relatives & friends will not be considered)</td></tr>';
				}

				$html .= '	<tr><td style="color: #66d9ff"><h4><strong>PROFESSIONAL REFERENCE 1</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>NAME:</strong></td>
					<td width="50%">' . $rst[0]['refname_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>DESIGNATION:</strong></td>
					<td width="50%">' . $rst[0]['refdesignation_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>ORGANISATION:</strong></td>
					<td width="50%">' . $rst[0]['reforganisation_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
					<td width="50%">' . $rst[0]['refaddressline_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EMAIL ID:</strong></td>
					<td width="50%">' . $rst[0]['refemail_one'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>MOBILE:</strong></td>
					<td width="50%">' . $rst[0]['refmobile_one'] . '</td>
					</tr>';
				$html .= '<br><tr><td style="color: #66d9ff"><h4><strong>REFERENCE 2</strong></h4></td><td></td></tr>';
				$html .= '<tr>                    
					<td width="50%"><strong>NAME:</strong></td>
					<td width="50%">' . $rst[0]['refname_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>DESIGNATION:</strong></td>
					<td width="50%">' . $rst[0]['refdesignation_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>ORGANISATION:</strong></td>
					<td width="50%">' . $rst[0]['reforganisation_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>COMPLETE ADDRESS:</strong></td>
					<td width="50%">' . $rst[0]['refaddressline_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EMAIL ID:</strong></td>
					<td width="50%">' . $rst[0]['refemail_two'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>MOBILE:</strong></td>
					<td width="50%">' . $rst[0]['refmobile_two'] . '</td>
					</tr>
					
					<tr><td style="color: #66d9ff"><h4><strong>OTHER INFORMATION</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>ANY OTHER INFORMATION THAT THE CANDIDATE WOULD LIKE TO ADD:</strong></td>
					<td width="50%"><div style="word-break:break-all;">' . $rst[0]['comment'] . '</div></td>
					</tr>
					
					';

				if ($position_id != 7 &&  $position_id != 15 && $position_id != 16 && $position_id != 12 && $position_id != 14) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION : I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated without any notice.</strong></td>
					<td width="50%">' . $rst[0]['declaration2'] . '</td>
					</tr>';
				}

				if ($position_id == 7 || $position_id == 14) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION : I declare that all statements made in this application are true, complete and correct to the best  of  my  knowledge  and  belief . I also declare that I  have  not  suppressed  any  material  fact(s)/information.  I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying  any  of  the  eligibility  criteria  according  to  the  requirements  of  the  related  advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}

				if ($position_id == 12) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION : I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}

				if ($position_id == 1) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION 2: I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leaves the service of the Institute before the expiry of the said period, a sum of Rs. 1,00,000/- (Rupees One Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Junior Executive dated 17-11-2022.</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}
				if ($position_id == 3) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION 2:  I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leave the service of the Institute before the expiry of the said period, a sum of Rs. 2,00,000/- (Rupees Two Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Assistant Director (Academics) dated 17.11.2022.</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}
				if ($position_id == 2) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION 2:  I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leave the service of the Institute before the expiry of the said period, a sum of Rs. 2,00,000/- (Rupees Two Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Assistant Director (IT) dated 17.11.2022</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}
				if ($position_id == 6) {
					$html .= '<tr>                    
					<td width="50%"><strong>DECLARATION 2:   I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leave the service of the Institute before the expiry of the said period, a sum of Rs. 2,00,000/- (Rupees Two Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Deputy Director (Accounts) dated 17.11.2022.</strong></td>
					<td width="50%">YES</td>
					</tr>';
				}
				if ($position_id == 7 || $position_id == 14 || $position_id == 12) {
					$html .= '<tr>                    
						<td width="50%"><strong>DECLARATION 2:   I hereby agree that any legal proceedings in respect of any matter or claims or disputes arising out of application or out of said advertisement can be instituted by me at Mumbai only and shall have sole and exclusive jurisdiction to try any cause / dispute. I undertake to abide by all the terms and conditions of the advertisement given by the Indian Institute of Banking & Finance</strong></td>
						<td width="50%">YES</td>
						</tr>';
					$html .= '<tr>                    
						<td width="50%"><strong>DECLARATION 3:   I further certify that no disciplinary / vigilance proceedings are either pending or contemplated against me and that there are no legal cases pending against me. There have been no major / minor penalty</strong></td>
						<td width="50%">YES</td>
						</tr>';
				}
				$html .= '<tr><td style="color: #66d9ff"><h4><strong>PLACE AND DATE</strong></h4></td><td></td></tr>
					<tr>                    
					<td width="50%"><strong>PLACE:</strong></td>
					<td width="50%">' . $rst[0]['place'] . '</td>
					</tr>
					
					<tr>
					<td width="50%"><strong>DATE:</strong></td>
					<td width="50%">' . $rst[0]['submit_date'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>SIGNATURE:</strong></td>
					<td><img width="70px" height="70px" src="' . base_url() . 'uploads/scansignature/' . $rst[0]['scannedsignaturephoto'] . '" id="thumb" /></td>
					</tr>';


				$html .= '</tbody>
					</table>
					<div id="reason_form" style="display: none">';

				// echo $html; exit;
				// print_r($html);                                                   

				$pdf = $this->m_pdf->load();

				$pdfFilePath = $rst[0]['firstname'] . '_' . $rst[0]['lastname'] . '_' . $rst[0]['submit_date'] . '_' . $position_id . '_' . $pdf_career_id . ".pdf";

				$file_dir_name = date('Ymd');
				$directory_name = "./uploads/Careers_Data/" . $file_dir_name;
				//mkdir($directory_name); 
				if (!file_exists($directory_name)) {
					mkdir($directory_name);
				}

				$uri_segments_all = explode("/", $_SERVER['REQUEST_URI']);
				$uri_segments_all = end($uri_segments_all);
				//die;
				if ($cnt > 7) {
					redirect(site_url('careers_admin/admin/Careers_position/' . $uri_segments_all));
				}


				$car_pdf_path = 'uploads/Careers_Data/' . $file_dir_name . '/';

				$file_arr[] = $pdfFilePath;

				if (!file_exists($car_pdf_path . $pdfFilePath) && $force_open_flag != 1) {
					$pdf->WriteHTML($html);
					$pdf->SetCompression(false);
					$pdf->SetDisplayMode('real');
					$pdf->SetDisplayMode('default');
					$pdf->SetAutoPageBreak(true);
					$path = $pdf->Output('uploads/Careers_Data/' . $file_dir_name . "/" . $pdfFilePath, "F");
					$cnt++;
				} else {
					$pdf->WriteHTML($html);
					$pdf->SetCompression(false);
					$pdf->SetDisplayMode('real');
					$pdf->SetDisplayMode('default');
					$pdf->SetAutoPageBreak(true);
				}

				if ($force_open_flag == 1) {
					$path = $pdf->Output($pdfFilePath, "D");
				}

				$pdf_generated_cnt++;
			}

			//print_r($file_arr);die;
			//print_r($file_dir_name); 
			//$zip = new ZipArchive(); // Load zip library  

			$file_dir_name = date('Ymd');
			$zip_name = 'career_files_' . date("YmdHis") . rand() . ".zip";

			//file directory creation 
			$zip_folder_path = "uploads/Careers_Data/" . $file_dir_name . "/zip";
			$directory_name = "./uploads/Careers_Data/" . $file_dir_name . "/zip";
			//mkdir($directory_name); 
			if (!file_exists($directory_name)) {
				mkdir($directory_name);
			}

			if (file_exists($zip_folder_path . "/" . $zip_name)) {
				@unlink($zip_folder_path . "/" . $zip_name);
			}


			$zip = new ZipArchive;

			if ($zip->open($zip_folder_path . '/' . $zip_name, ZipArchive::CREATE) === TRUE) {
				if (count($file_arr) > 0) {
					foreach ($file_arr as $file) {
						if ($file != "") {
							$path = "./uploads/Careers_Data/" . $file_dir_name . "/" . $file;
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
			if (count($all_directories) > 0) {
				foreach ($all_directories as $dir) {
					$explode_arr = explode("_", $dir, 2);
					//echo $explode_arr[0]."==".$dir;die;
					$chk_dir = str_replace("/", "", $explode_arr[0]);
					//echo $chk_dir;die;
					if ($chk_dir != date('Ymd')) {
						//echo "<br> Delete : ".$dir;
						$this->rmdir_recursive("uploads/Careers_Data/" . $chk_dir);
					}
				}
			}
			//END : REMOVE ALL EXISTING DIRECTORIES OF PREVIOUS DATE

			if (count($file_arr) > 0) {
				redirect(base_url('uploads/Careers_Data/' . $file_dir_name . '/zip/' . $zip_name));
			}
		}

		//echo "<br> PDF Generated Count : ".$pdf_generated_cnt;

		//redirect(base_url().'careers_admin/admin/Careers_position/career_position_list');
	}


	private function getPDFhtml($rst, $career_id, $position_id)
	{
		// <tr>                    
		//  <td width="50%"><strong>CV:</strong></td>
		//  <td><a href="'.base_url().'uploads/uploadcv/'.$rst[0]['uploadcv'].'" target="_blank" id="thumb" />Download</a>
		//  </td>
		// </tr>
		$this->db->where('state_code', $rst[0]["state"]);
		$stateDetails = $this->master_model->getRecords('state_master');

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


		$html .= '<h1 style="text-align:center">APPLICATION For the Post of Junior Executive</h1>';
		$html .= '<div class="table-responsive ">
					<table class="table table-bordered wikitable tabela" style="overflow: wrap">
					<tbody>';

		$application_title = 'SME ';

		$html .= '	<tr>                    
					<td width="50%"><strong>APPLIED FOR POSITION:</strong></td>
					<td width="50%">' . $application_title . '</td>
					</tr>';

		$experience = explode(',', $rst[0]['exp_in_bank']);

		if ($rst[0]['bank_education'] == 'bank') {
			$bankEducation = ucfirst($rst[0]['bank_education']);
		} else {
			$bankEducation = 'Educational Institute';
		}

		$html .= '
					<tr>                    
					<td width="50%"><strong>NAME:</strong></td>
					<td width="50%">' . $rst[0]["firstname"] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>DATE OF BIRTH:</strong></td>
					<td width="50%">' . $rst[0]['dateofbirth'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>EMAIL ID:</strong></td>
					<td width="50%">' . $rst[0]['email'] . '</td>
					</tr>
					
					<tr>                    
					<td width="50%"><strong>MOBILE NO:</strong></td>
					<td width="50%">' . $rst[0]['mobile'] . '</td>
					</tr>
						
					<tr>                    
                      <td width="50%"><strong>Educational Qualification:</strong></td>
                      <td width="50%">' . $rst[0]['educational_qualification'] . '</td>
                    </tr>

                    <tr>                    
                      <td width="50%"><strong>CAIIB Qualification :</strong></td>
                      <td width="50%">' . ucfirst($rst[0]['CAIIB_qualification']) . '</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>ADDRESS:</strong></td>
                      <td width="50%">' . $rst[0]['addressline1'] . ',<br>' . $rst[0]['addressline2'] . ' ' . '<br>' . $rst[0]['city'] . '<br>' . $stateDetails[0]['state_name'] . '<br>' . $rst[0]['pincode'] . '</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Bank/Educational Institute:</strong></td>
                      <td width="50%">' . ucfirst($rst[0]['bank_education']) . '</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Organization Name:</strong></td>
                      <td width="50%">' . $rst[0]['ess_college_name'] . '</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Retired/Working:</strong></td>
                      <td width="50%">' . ucfirst($rst[0]['retired_working']) . '</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Total year of Work experience:</strong></td>
                      <td width="50%">' . $experience[0] . ' Year ' . $experience[1] . ' Month</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Designation:</strong></td>
                      <td width="50%">' . $rst[0]['designation'] . '</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>General:</strong></td>
                      <td>' . $rst[0]['general_subjects'] . '</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Specialisation:</strong></td>
                      <td>' . $rst[0]['specialisation'] . '</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Information Technology:</strong></td>
                      <td>' . $rst[0]['it_subjects'] . '</td>
                    </tr>
                    <tr>                    
                      <td width="50%"><strong>Other:</strong></td>
                      <td>' . $rst[0]['other_subjects'] . '</td>
                    </tr>';

		$html .= '</tbody>
					</table>
					<div id="reason_form" style="display: none">';

		return $html;
	}

	private function getCustomPDFhtml($rst, $career_id, $position_id, $emp_hist_arr)
	{
		$spouse_name = $mother_name = $religion = $physical_disbaility = $physical_disbaility_desc = $post_qua_name = $post_gra_sub = $post_gra_college_name = $post_gra_university = $post_gra_from_date = $post_gra_to_date = $post_aggregate_marks_obtained = $post_gra_aggregate_max_marks = $post_gra_percentage = $post_gra_class = $postcer_qua_name_gra_from_date = $cer_gra_sub = $cer_college_name = $cer_university = $cer_from_date = $cer_to_date = $cer_marks_obtained = $cer_aggregate_max_marks = $cer_percentage = $cer_class = $desirable_course_name = $desirable_college_name = $desirable_name_subject_of_course = $desirable_specialisation = $desirable_university = $desirable_from_date = $desirable_to_date = $desirable_degree_completion_date = $desirable_aggregate_marks_obtained = $desirable_aggregate_max_marks = $desirable_percentage = $desirable_grade_marks = $desirable_class = $whether_in_service = $vrs_register_date = $reason_of_resign = $name_of_present_organization = $service_from_date = $comm_address_of_org = $curr_designation = $any_other_details = $professional_ass = $subject_handled = $exp_faculty_from_date = $exp_faculty_to_date = '';

		$careers_qual_hist_arr = array();

		//if($position_id == 7 || $position_id == 14 || $position_id == 12)
		{
			$this->db->where('careers_id', $rst[0]["careers_id"]);
			$career_other_details = $this->master_model->getRecords('career_other_details');
			if ($career_other_details) {
				$spouse_name = $career_other_details[0]['spouse_name'];
				$mother_name = $career_other_details[0]['mother_name'];
				$religion = $career_other_details[0]['religion'];
				$physical_disbaility = $career_other_details[0]['physical_disbaility'];
				$physical_disbaility_desc = $career_other_details[0]['physical_disbaility_desc'];
				// $post_qua_name = $career_other_details[0]['post_qua_name'];
				// $post_gra_sub = $career_other_details[0]['post_gra_sub'];
				// $post_gra_college_name = $career_other_details[0]['post_gra_college_name'];
				// $post_gra_university = $career_other_details[0]['post_gra_university'];

				// $post_gra_from_date = $career_other_details[0]['post_gra_from_date'];
				// $post_gra_to_date = $career_other_details[0]['post_gra_to_date'];

				// $subject_handled = $career_other_details[0]['subject_handled'];

				$exp_faculty_from_date = $career_other_details[0]['exp_faculty_from_date'];

				$exp_faculty_to_date = $career_other_details[0]['exp_faculty_to_date'];


				// if ($post_gra_from_date != "" && $post_gra_from_date != "0000-00-00") {
				// 	$post_gra_from_date = date("d-m-Y", strtotime($post_gra_from_date));
				// } else {
				// 	$post_gra_from_date = '';
				// }


				// if ($post_gra_to_date != "" && $post_gra_to_date != "0000-00-00") {
				// 	$post_gra_to_date = date("d-m-Y", strtotime($post_gra_to_date));
				// } else {
				// 	$post_gra_to_date = '';
				// }
				// //post_gra_percentage
				// $post_aggregate_marks_obtained = $career_other_details[0]['post_aggregate_marks_obtained'];
				// $post_gra_aggregate_max_marks = $career_other_details[0]['post_gra_aggregate_max_marks'];
				// $post_gra_percentage = $career_other_details[0]['post_gra_percentage'];
				// $post_gra_class = $career_other_details[0]['post_gra_class'];
				$cer_qua_name = $career_other_details[0]['cer_qua_name'];
				$cer_gra_sub = $career_other_details[0]['cer_gra_sub'];
				$cer_college_name = $career_other_details[0]['cer_college_name'];
				$cer_university = $career_other_details[0]['cer_university'];

				$cer_from_date = $career_other_details[0]['cer_from_date'];
				if ($cer_from_date != "" && $cer_from_date != "0000-00-00") {
					$cer_from_date = date("d-m-Y", strtotime($cer_from_date));
				} else {
					$cer_from_date = '';
				}

				$cer_to_date = $career_other_details[0]['cer_to_date'];
				if ($cer_to_date != "" && $cer_to_date != "0000-00-00") {
					$cer_to_date = date("d-m-Y", strtotime($cer_to_date));
				} else {
					$cer_to_date = '';
				}


				$cer_marks_obtained = $career_other_details[0]['cer_marks_obtained'];
				$cer_aggregate_max_marks = $career_other_details[0]['cer_aggregate_max_marks'];
				$cer_percentage = $career_other_details[0]['cer_percentage'];
				$cer_class = $career_other_details[0]['cer_class'];

				$whether_in_service = $career_other_details[0]['whether_in_service'];

				$vrs_register_date = $career_other_details[0]['vrs_register_date'];
				if ($vrs_register_date != "" && $vrs_register_date != "0000-00-00") {
					$vrs_register_date = date("d-m-Y", strtotime($vrs_register_date));
				} else {
					$vrs_register_date = '';
				}

				$reason_of_resign = $career_other_details[0]['reason_of_resign'];
				$name_of_present_organization = $career_other_details[0]['name_of_present_organization'];

				$service_from_date = $career_other_details[0]['service_from_date'];
				if ($service_from_date != "" && $service_from_date != "0000-00-00") {
					$service_from_date = date("d-m-Y", strtotime($service_from_date));
				} else {
					$service_from_date = '';
				}

				$comm_address_of_org = $career_other_details[0]['comm_address_of_org'];
				$curr_designation = $career_other_details[0]['curr_designation'];
				$any_other_details = $career_other_details[0]['any_other_details'];

				$professional_ass = $career_other_details[0]['professional_ass'];
			}

			$this->db->where('careers_id', $career_id);
			$careers_qual_hist_arr = $this->master_model->getRecords("careers_qual_hist");

			$this->db->select('m.id,m.course_name,q.*');
			$this->db->join('careers_course_mst m', 'm.course_code=q.course_code', 'LEFT');
			$this->db->where('careers_id', $career_id);
			$desirable_qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");

			if ($desirable_qualification_arr) {
				$desirable_course_name            = $desirable_qualification_arr[0]['course_name'];
				$desirable_college_name           = $desirable_qualification_arr[0]['college_name'];
				$desirable_name_subject_of_course = $desirable_qualification_arr[0]['name_subject_of_course'];
				$desirable_specialisation         = $desirable_qualification_arr[0]['specialisation'];
				$desirable_university             = $desirable_qualification_arr[0]['university'];

				$desirable_period = '';
				$desirable_from_date              = $career_other_details[0]['des_from_date'];
				$desirable_to_date                = $career_other_details[0]['des_to_date'];

				if ($desirable_from_date != '' && $desirable_to_date != '') {
					$desirable_period = $desirable_from_date . ' To ' . $desirable_to_date;
				} else {
					$desirable_period = $desirable_from_date . ' ' . $desirable_to_date;
				}

				$desirable_degree_completion_date = $desirable_qualification_arr[0]['degree_completion_date'];
				$desirable_aggregate_marks_obtained = $desirable_qualification_arr[0]['aggregate_marks_obtained'];
				$desirable_aggregate_max_marks = $desirable_qualification_arr[0]['aggregate_max_marks'];
				$desirable_percentage = $desirable_qualification_arr[0]['percentage'];
				$desirable_grade_marks = $desirable_qualification_arr[0]['grade_marks'];
				$desirable_class = $desirable_qualification_arr[0]['class'];
			}

			//added by rupali
			$this->db->where('careers_id', $rst[0]["careers_id"]);
			$career_pg_details = $this->master_model->getRecords('career_pg_details');
			if ($career_pg_details) {
				$post_qua_name = $career_pg_details[0]['post_qua_name'];
				$post_gra_sub = $career_pg_details[0]['post_gra_sub'];
				$post_gra_college_name = $career_pg_details[0]['post_gra_college_name'];
				$post_gra_university = $career_pg_details[0]['post_gra_university'];

				$post_gra_from_date = $career_pg_details[0]['post_gra_from_date'];
				$post_gra_to_date = $career_pg_details[0]['post_gra_to_date'];


				if ($post_gra_from_date != "" && $post_gra_from_date != "0000-00-00") {
					$post_gra_from_date = date("d-m-Y", strtotime($post_gra_from_date));
				} else {
					$post_gra_from_date = '';
				}


				if ($post_gra_to_date != "" && $post_gra_to_date != "0000-00-00") {
					$post_gra_to_date = date("d-m-Y", strtotime($post_gra_to_date));
				} else {
					$post_gra_to_date = '';
				}

				$post_aggregate_marks_obtained = $career_pg_details[0]['post_aggregate_marks_obtained'];
				$post_gra_aggregate_max_marks = $career_pg_details[0]['post_gra_aggregate_max_marks'];
				$post_gra_percentage = $career_pg_details[0]['post_gra_percentage'];
				$post_gra_class = $career_pg_details[0]['post_gra_class'];
			}
		}
		$head_title = 'Application';

		if ($position_id  == 7) {
			$head_title = '  Faculty Member - PDC (NZ), New Delhi on contract basis';
		} else if ($position_id  == 12) {
			$head_title = 'Application for the post of Head PDC (SZ) - Chennai on contract basis';
		} else if ($position_id  == 4) {
			$head_title = 'Application for the post of Director (Training) on contract basis';
		} else if ($position_id  == 3) {
			$head_title = 'Application for the post of Director (Academics) on contract basis';
		} else if ($position_id  == 14) {
			$head_title = 'Faculty Member – Corporate Office, Mumbai on contract basis';
		} else if ($position_id  == 15) {
			$head_title = 'In-charge, Development Centre, Lucknow on contract basis';
		} else if ($position_id  == 16) {
			$head_title = 'In-charge, Development Centre, Guwahati on contract basis';
		} else if ($position_id  == 17) {
			$head_title = 'In-charge, Development Centre, Bengaluru on contract basis';
		} else if ($position_id  == 1) {
			$head_title .= ' for the post of Junior Executive';
		} else if ($position_id  == 8) {
			$head_title = ' Corporate Development Officer - On contract basis';
		} else if ($position_id  == 19) {
			$head_title = ' Resident Engineer on Retainer Basis (On Contract)';
		}
		$this->db->where('state_code', $rst[0]["state"]);
		$stateDetails = $this->master_model->getRecords('state_master');

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
            font-size: 12px;
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


		$html .= '<h1 style="text-align:center">' . $head_title . '</h1>';
		$html .= '<img style="float:right;" class="column" width="70px" height="70px" align="right" src="' . base_url() . 'uploads/photograph/' . $rst[0]['scannedphoto'] . '"id="thumb" /><br><br><br><br>';

		$html .= '<div class="table-responsive ">
            <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
            <tbody>';

		$application_title = '';
		if ($position_id == 3) {
			$application_title = "Assistant Director (Academics)";
		} else if ($position_id == 4) {
			$application_title = "Director (Training) on Contract";
		} else if ($position_id == 12) {
			$application_title = "Head PDC (WZ) - MUMBAI on contract basis";
		}



		$html .= '
	  	<tr>
            <td colspan="10" style="color: #66d9ff"><h4><strong>BASIC DETAILS</strong></h4>
		</tr>
            <tr>                    
            <td width="50%"><strong>Name:</strong></td>
            <td width="50%">' . $rst[0]["sel_namesub"] . ' ' . $rst[0]["firstname"] . ' ' . $rst[0]['middlename'] . ' ' . $rst[0]['lastname'] . '</td>
            </tr>  
            ';
		if ($position_id == 1) {
			$this->db->select('transaction_no,receipt_no,amount');
			$this->db->where('member_regnumber', $rst[0]["reg_id"]);
			//	$this->db->where('c.active_status', '1');
			$payment_transaction = $this->master_model->getRecords('payment_transaction');

			/*$html.= '
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
                    </tr>';*/
		}
		$html .= '
	  		<tr>                    
				<td width="50%"><strong>Marital Status:</strong></td>
				<td width="50%">' . $rst[0]['marital_status'] . '</td>
            </tr>
            <tr>                    
            <td width="50%"><strong>Spouse\'s Name:</strong></td>
            <td width="50%">' . $spouse_name . '</td>
            </tr>  
            ';
		$html .= '
            <tr>                    
            <td width="50%"><strong>Father\'s Name:</strong></td>
            <td width="50%">' . $rst[0]["father_husband_name"] . '</td>
            </tr>  
            ';
		$html .= '
            <tr>                    
            <td width="50%"><strong>Mother\'s Name:</strong></td>
            <td width="50%">' . $mother_name . '</td>
            </tr>  
            ';

		$bday = new DateTime($rst[0]['dateofbirth']); //dd.mm.yyyy
		$today = new DateTime('2025-02-01'); // Current date
		$diff = $today->diff($bday);

		$html .= '<tr>                    
            <td width="50%"><strong>Date Of Birth:</strong></td>
            <td width="50%">' . $rst[0]['dateofbirth'] . '</td>
            </tr>
            <tr>                    
            <td width="50%"><strong>Age as on 01.02.2025:</strong></td>
            <td width="50%">' . $diff->y . ' years, ' . $diff->m . ' month ,' . $diff->d . ' days</td>
            </tr>
            
            <tr>                    
            <td width="50%"><strong>Gender:</strong></td>
            <td width="50%">' . $rst[0]['gender'] . '</td>
            </tr>';

		if ($position_id != 5) {
			$html .= '<tr>                    
	              <td width="50%"><strong>Religion :</strong></td>
	              <td width="50%">' . $religion . '</td>
	            </tr>';
		}

		$html .= '<tr>                    
              <td width="50%"><strong>Email Id :</strong></td>
              <td width="50%">' . $rst[0]['email'] . '</td>
            </tr>';

		if ($position_id != 5) {
			$html .= '<tr>                    
		            <td width="50%"><strong>Are you a person with Physical Disability:</strong></td>
		            <td width="50%">' . ucfirst($physical_disbaility) . '</td>
		            </tr>';

			if ($physical_disbaility == 'yes') {
				$html .= '<tr>                    
		                <td width="50%"><strong>Type of Disability:</strong></td> 
		                <td width="50%">' . $physical_disbaility_desc . '</td>
		                </tr>';
			}
		}


		$html .= '	
            <tr>                    
            <td width="50%"><strong>Mobile Number:</strong></td>
            <td width="50%">' . $rst[0]['mobile'] . '</td>
            </tr>
            <tr>                    
            <td width="50%"><strong>Alternate Mobile Number:</strong></td>
            <td width="50%">' . $rst[0]['alternate_mobile'] . '</td>
            </tr>
            
            <tr>                    
            <td width="50%"><strong>PAN Number:</strong></td>
            <td width="50%">' . $rst[0]['pan_no'] . '</td>
            </tr>
            
            <tr>                    
            <td width="50%"><strong>Aadhar Card Number:</strong></td>
            <td width="50%">' . $rst[0]['aadhar_card_no'] . '</td>
            </tr>
            <tr>                    
            <td width="50%"><strong>COMMUNICATION ADDRESS:</strong></td>
            <td width="50%">' . $rst[0]['addressline1'] . ', ' . $rst[0]['addressline2'] . ', ' . $rst[0]['addressline3'] . ', ' . $rst[0]['addressline4'] . '<br>' . $rst[0]['district'] . ', ' . $rst[0]['city'] . '<br>' . $rst[0]['state'] . '<br>' . $rst[0]['pincode'] . '</td>
            </tr> 
            <tr>                    
            <td width="50%"><strong>PERMANENT ADDRESS:</strong></td>
            <td width="50%">' . $rst[0]['addressline1_pr'] . ', ' . $rst[0]['addressline2_pr'] . ', ' . $rst[0]['addressline3_pr'] . ', ' . $rst[0]['addressline4_pr'] . '<br>' . $rst[0]['district_pr'] . ', ' . $rst[0]['city_pr'] . '<br>' . $stateDetails[0]['state_name'] . '<br>' . $rst[0]['pincode_pr'] . '</td>
            </tr>

          
            </tbody>
            </table>
			<br><br>
            ';


		/*	  <tr>                    
            <td width="50%"><strong>Exam Center:</strong></td>
            <td width="50%">' . $rst[0]['exam_center'] . '</td>
            </tr>
			if($position_id == 12){
              $html.= '<tr>                    
              <td width="50%"><strong>Are you a person with Physically Disability :</strong></td> 
              <td width="50%">'.ucwords($physical_disbaility).'</td>
              </tr>';

              if($physical_disbaility == 'yes')
              {
                $html.= '<tr>                    
                <td width="50%"><strong>Type of Disability:</strong></td> 
                <td width="50%">'.$physical_disbaility_desc.'</td>
                </tr>';
              }
            } 
            */

		if ($position_id != 5) {
			$html .= '
		            <table class="table table-bordered wikitable tabela" style="overflow: wrap">
		            <tbody>
		            <tr>
			            <td colspan="10" style="color: #66d9ff"><h4 style="text-align:center;"><strong>EDUCATIONAL QUALIFICATION</strong></h4>
			            <br><strong style="color: #444">ESSENTIAL</strong>
			            </td></tr>
			            <tr><td><strong>Name</strong></td><td><strong>Qualification</strong></td>
			            <td ><strong>Subject</strong></td><td><strong>College/Institution </strong></td>
			            <td><strong>University </strong></td><td><strong>Period</strong></td><td><strong>Aggregate Marks Obtained</strong></td>
			            <td><strong>Aggregate Maximum Marks</strong></td>
			            <td><strong>Final Percentage</strong></td><td><strong>Class/Grade</strong></td>
		            
		            <tr>
			            <td ><note>Educational Qualification I - Academic (Graduation Onwards)</note></td>
			            <td >' . $rst[0]['ess_course_name'] . '</td>
			            <td >' . $rst[0]['ess_pg_stream_subject'] . '</td>
			            <td >' . $rst[0]['ess_college_name'] . '</td>
			            <td >' . $rst[0]['ess_university'] . '</td>
			            <td >' . date("d-m-Y", strtotime($rst[0]['ess_from_date'])) . " TO " . date("d-m-Y", strtotime($rst[0]['ess_to_date'])) . '</td>
			            <td >' . $rst[0]['ess_aggregate_marks_obtained'] . '</td>
			            <td >' . $rst[0]['ess_aggregate_max_marks'] . '</td>
			            <td >' . $rst[0]['ess_percentage'] . '</td>
			            <td >' . $rst[0]['ess_class'] . '</td>
		            </tr>

		            <tr>
		            <td ><note>Educational Qualification II - Post Graduation</note></td>
		            <td >' . $post_qua_name . '</td>
		            <td >' . $post_gra_sub . '</td>
		            <td >' . $post_gra_college_name . '</td>
		            <td >' . $post_gra_university . '</td>
		            <td >' . $post_gra_from_date . " To " . $post_gra_to_date . '</td>
		            <td >' . $post_aggregate_marks_obtained . '</td>
		            <td >' . $post_gra_aggregate_max_marks . '</td>
		            <td >' . $post_gra_percentage . '</td>
		            <td >' . $post_gra_class . '</td>
		            </tr>';

			// <tr>
			// <td ><note>Educational Qualification 3: Additional Qualifications/Certification</note></td>
			// <td >' . $cer_qua_name . '</td>
			// <td >' . $cer_gra_sub . '</td>
			// <td >' . $cer_college_name . '</td>
			// <td >' . $cer_university . '</td>
			// <td >' . $cer_from_date . " To " . $cer_to_date . '</td>
			// <td >' . $cer_marks_obtained . '</td>
			// <td >' . $cer_aggregate_max_marks . '</td>
			// <td >' . $cer_percentage . '</td>
			// <td >' . $cer_class . '</td>
			// </tr>';


			if (!empty($career_pg_details)) {
				foreach ($career_pg_details as $add) {
					$html .= '
            <tr>
                <td><note> Additional Educational Qualification</note></td>
                <td>' . $add["post_qua_name"] . '</td>
                <td>' . $add["post_gra_sub"] . '</td>
                <td>' . $add["post_gra_college_name"] . '</td>
                <td>' . $add["post_gra_university"] . '</td>
                <td>' . $add["post_gra_from_date"] . ' To ' . $add["post_gra_to_date"] . '</td>
				<td>' . $add["post_aggregate_marks_obtained"] . '</td> 
				<td>' . $add["post_gra_aggregate_max_marks"] . '</td>
                <td>' . $add["post_gra_percentage"] . '</td>
                <td>' . $add["post_gra_class"] . '</td>
            </tr>';
				}
			}
			$html .= '      </tbody>
		            </table> <br><br>
		            ';
		}


		if ($position_id == 5) {
			// START -- EDUCATIONAL QUALIFICATION DETAILS
			$html .= '
		            <table class="table table-bordered wikitable tabela" style="overflow: wrap">
		            <tbody>
		            <tr>
			            <td colspan="10" style="color: #66d9ff"><h4 style="text-align:center;"><strong>EDUCATIONAL QUALIFICATION</strong></h4>
			            <br><strong style="color: #444">ESSENTIAL</strong>
			            </td>
			        </tr>
		            <tr>
			            <td><strong>Name</strong></td>
			            <td><strong>Qualification</strong></td>
			            <td ><strong>Subject</strong></td>
			            <td><strong>College/Institution </strong></td>
			            <td><strong>University </strong></td>
			            <td><strong>Period</strong></td>
			            <td><strong>Percentage Obtained</strong></td>
			            <td><strong>Class/Grade</strong></td>
		            </tr>
		            
		            <tr>
			            <td ><note>Educational Qualification I - Post Graduation</note></td>
			            <td >' . $post_qua_name . '</td>
			            <td >' . $post_gra_sub . '</td>
			            <td >' . $post_gra_college_name . '</td>
			            <td >' . $post_gra_university . '</td>
			            <td >' . $post_gra_from_date . " To " . $post_gra_to_date . '</td>
			            <td >' . $post_gra_percentage . '</td> 
			            <td >' . $post_gra_class . '</td>
		            </tr>';

			if ($careers_qual_hist_arr) {
				foreach ($careers_qual_hist_arr as $res) {
					$html .= '
		            		<tr>
					            <td> <note> Educational Qualification 2: Additional Qualifications/Certification</note> </td>
					            <td>' . $res["cer_qua_name"] . '</td>
					            <td>' . $res["cer_gra_sub"] . '</td>
					            <td>' . $res["cer_college_name"] . '</td>
					            <td>' . $res["cer_university"] . '</td>
					            <td>' . $res["cer_from_date"] . " To " . $res["cer_to_date"] . '</td> 
					            <td>' . $res["cer_percentage"] . '</td>
					            <td>' . $res["cer_class"] . '</td>
				            </tr>';
				}
			}

			// FOREACH FOR ADDITIONAL EDUCATIONAL QUALIFICATIONS


			$html .= '</tbody>
		            </table> <br><br>';

			// END -- EDUCATIONAL QUALIFICATION DETAILS

			// START -- CAIIB Section
			$html .= '<table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
				            	<tbody>
				            <tr>
				            <td colspan="10" style="color: #66d9ff"><h4><strong>CAIIB</strong></h4> 
				            </td></tr>
				            <tr>                    
				            <td width="50%"><strong>CAIIB:</strong></td>
				            <td width="50%">' . $rst[0]["ess_subject"] . '</td>
				            </tr>  
				            ';
			if ($rst[0]["ess_subject"] == "Yes") {
				$html .= '<tr>                    
				            	<td width="50%"><strong>Membership Number:</strong></td>
				            	<td width="50%">' . $rst[0]["membership_number"] . '</td>
				            	</tr>';
			}
			// END -- CAIIB Section

			// START -- PHD Section	
			$phd_period = '';

			if ($career_other_details[0]["phd_from_date"] != '' && $career_other_details[0]["phd_to_date"] != '') {
				$phd_period = $career_other_details[0]["phd_from_date"] . ' To ' . $career_other_details[0]["phd_to_date"];
			} else {
				$phd_period = $career_other_details[0]["phd_from_date"] . ' ' . $career_other_details[0]["phd_to_date"];
			}

			$html .= '
		              		<tr>
				            <td colspan="10" style="color: #66d9ff"><h4><strong>Ph.D (IN BANKING OR FINANCE)</strong></h4> 
				            </td></tr>
				            <tr>                    
				            <td width="50%"><strong>Name of Research Topic:</strong></td>
				            <td width="50%">' . $rst[0]["phd_course"] . '</td>
				            </tr> 
				            <tr>                    
				            <td width="50%"><strong>University:</strong></td>
				            <td width="50%">' . $rst[0]["phd_university"] . '</td>
				            </tr> 
				            <tr>                    
				            <td width="50%"><strong>Period:</strong></td>
				            <td width="50%">' . $phd_period . '</td>
				            </tr> 
            			</tbody>
            		</table> <br><br>';
			// END -- PHD Section

		}

		// START -- DESIRABLE Section
		if ($position_id == 5) {
			$course_lable_name = 'Name of course';
			$subject_lable_name = 'Specialisation';
		} else {
			$course_lable_name = 'Name';
			$subject_lable_name = 'Subject';
		}
		// if ($position_id == 7 || $position_id == 8 || $position_id == 12 || $position_id == 14 || $position_id == 15 || $position_id == 16  || $position_id == 17 || $position_id == 3) {
		if (!empty($desirable_qualification_arr)) {
			$html .= '
            <table class="table table-bordered wikitable tabela" style="overflow: wrap">
            	<tbody>
				
            		<tr>
            			<td colspan="10" style="color: #66d9ff"><h4><strong>DESIRABLE QUALIFICATION</strong></h4></td>
            		</tr>
            		<tr>
					
            			<td><strong>' . $course_lable_name . '</strong></td>
            			<td><strong>' . $subject_lable_name . '</strong></td>
            			<td ><strong>College Name and Address</strong></td>
            			<td><strong>University</strong></td>
			            <td><strong>Period</strong></td>';
			if ($position_id != 5) {
				$html .= '<td><strong>Aggregate Marks Obtained</strong></td>
			            	<td><strong>Aggregate Maximum Marks</strong></td>';
			}

			$html .= '<td><strong> Percentage</strong></td>
			            <td><strong>Class/Grade</strong></td>
            		</tr>';
			foreach ($desirable_qualification_arr as $row) {
				$html .= '
                <tr>
                    <td>' . $row['course_name'] . '</td>
                    <td>' . $row['subject_name'] . '</td>
                    <td>' . $row['college_name'] . '</td>
                    <td>' . $row['university'] . '</td>
                    <td>' . $row['period'] . '</td>';

				if ($position_id != 5) {
					$html .= '
                    <td>' . $row['aggregate_marks_obt'] . '</td>
                    <td>' . $row['aggregate_marks_max'] . '</td>';
				}

				$html .= '
                    <td>' . $row['percentage'] . '</td>
                    <td>' . $row['class_grade'] . '</td>
                </tr>';
			}
			$html .= '</tbody>
            </table> <br><br>';
		}


		// END -- DESIRABLE Section    


		if ($position_id == 7 || $position_id == 8 || $position_id == 12 || $position_id == 14 || $position_id == 15 || $position_id == 16  || $position_id == 17 || $position_id == 3) {
			$html .= '<table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap;margin-top:10px;"><tbody>';
			$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td></tr>';
			$html .= '
              <tr>                    
              <td width="50%"><strong>Year of passing:</strong></td>
              <td width="50%">' . $rst[0]['year_of_passing'] . '</td>
              </tr>
              
              <tr>                    
              <td width="50%"><strong>Membership Number:</strong></td>
              <td width="50%">' . $rst[0]['membership_number'] . '</td>
              </tr></tbody></table>';
		}

		if ($emp_hist_arr) {
			$html .= '
              <table class="table table-bordered wikitable tabela" style="overflow: wrap">
              <tbody>
              <tr>
              <td colspan="4" style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY - Last 5 positions held with role & responsibilities in detail</strong></h4>
              
              </td></tr>
              <tr><td><strong>Name of the Organisation/Employer/Bank</strong></td><td><strong>Period</strong></td>
              <td ><strong>Last Designation/Post Held</strong></td><td><strong>Responsibilities/Nature of Duties
              Performed</strong></td>
              </tr>
              ';
			foreach ($emp_hist_arr as $rest) {
				$html .= '<tr>
              
                  <td >' . $rest['organization'] . '</td>
                  <td >' . date("d-m-Y", strtotime($rest['job_from_date'])) . " To " . date("d-m-Y", strtotime($rest['job_to_date'])) . '</td>
                  <td >' . $rest['designation'] . '</td>
                  <td >' . $rest['responsibilities'] . '</td>
                  </tr>';
			}
			$html .= '
              </tbody>
              </table> <br><br>';
		}

		if ($position_id != 1) {
			$html .= '<table class="table table-bordered wikitable tabela" style="overflow: wrap;margin-top:10px;"><tbody>';

			$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>SERVICE DETAILS</strong></h4></td></tr>
              <tr>                    
              <td width="50%"><strong>Whether In Service?:</strong></td>
              <td width="50%">' . ucwords($whether_in_service) . '</td>
              </tr>';

			if ($whether_in_service == "no") {
				$html .= '<tr>                    
                <td width="50%"><strong>Date of Superannuation/VRS/Resignation etc:</strong></td>
                <td width="50%">' . $vrs_register_date . '</td>
                </tr>
                <tr>                    
                <td width="50%"><strong>Reason for Resignation/Leaving:</strong></td>
                <td width="50%">' . $reason_of_resign . '</td>
                </tr>';
			} else if ($whether_in_service == "yes") {
				$html .= '<tr>                    
                <td width="50%"><strong>Name of the Present Organisation:</strong></td>
                <td width="50%">' . $name_of_present_organization . '</td>
                </tr>
                <tr>                    
                <td width="50%"><strong>Period:</strong></td>
                <td width="50%">' . $service_from_date . '</td>
                </tr>
                <tr>                    
                <td width="50%"><strong>Communication Address of the Organization:</strong></td>
                <td width="50%">' . $comm_address_of_org . '</td>
                </tr>
                <tr>                    
                <td width="50%"><strong>Designation/Post Held:</strong></td>
                <td width="50%">' . $curr_designation . '</td>
                </tr>
                <tr>                    
                <td width="50%"><strong>Any Other Details:</strong></td>
                <td width="50%">' . $any_other_details . '</td>
                </tr>';
			}
			if ($position_id == 12 || $position_id == 3 || $position_id == 4 || $position_id == 7 || $position_id == 14 || $position_id == 15 || $position_id == 16 || $position_id == 7) {
				$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>Experience as Faculty</strong></h4></td></tr>';


				$html .= '<tr>                    
              <td width="50%"><strong>Experience as Faculty:</strong></td>
              <td width="50%">' . $rst[0]['exp_in_bank'] . '</td>
              </tr>';


				$html .= '<tr>                    
              <td width="50%"><strong>Period:</strong></td>
              <td width="50%">From ' . $exp_faculty_from_date . ' To ' . $exp_faculty_to_date . '</td>
              </tr>';

				$html .= '<tr>                    
              <td width="50%"><strong>Subject Handled:</strong></td>
			  <td width="50%">' . $rst[0]['subject_handled'] . '</td>
              </tr>';

				//if ($position_id == 12 || $position_id == 3 || $position_id == 4 || $position_id == 7 || $position_id == 14 || $position_id == 15 || $position_id == 16 || $position_id == 7)
				{
					$html .= '<tr>                    
				<td width="50%"><strong>Area of Specialisation:</strong></td>
				<td width="50%">' . $rst[0]['exp_in_functional_area'] . '</td>
				</tr>';
				}

				$html .= '<tr>                    
              <td width="50%"><strong>Published Articles/Books:</strong></td>
              <td width="50%">' . $rst[0]['publication_of_books'] . '</td>
              </tr>';

				$html .= '<tr>                    
              <td width="50%"><strong>Membership of Professional Associations:</strong></td>
              <td width="50%">' . $professional_ass . '</td>
              </tr>';
			}
			$html .= '</tbody></table> <br><br>';
		}



		// $html .= '


		//     <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
		//     <tbody>
		// 	<tr><td colspan="2" style="color: #66d9ff"><h4><strong>LANGUAGES, EXTRACURRICULAR ACTIVITIES, ACHIEVEMENTS, HOBBIES</strong></h4></td></tr>
		//     <tr>                    
		//     <td width="40%" ><strong>Languages Known:</strong></td>
		//     <td><strong>Read</strong></td>
		//     <td><strong>Write</strong></td>
		//     <td><strong>Speak</strong></td>					
		//     </tr>';
		// $languages_option_read = $languages_option_write = $languages_option_speak = '';

		// if (strpos($rst[0]['languages_option'], 'Read') !== false)
		// 	$languages_option_read = $rst[0]['languages_known'];
		// if (strpos($rst[0]['languages_option'], 'Write') !== false)
		// 	$languages_option_write = $rst[0]['languages_known'];
		// if (strpos($rst[0]['languages_option'], 'Speak') !== false)
		// 	$languages_option_speak = $rst[0]['languages_known'];

		// $languages_option_read1 = $languages_option_write1 = $languages_option_speak1 = '';

		// if (strpos($rst[0]['languages_option1'], 'Read') !== false)
		// 	$languages_option_read1 = $rst[0]['languages_known1'];
		// if (strpos($rst[0]['languages_option1'], 'Write') !== false)
		// 	$languages_option_write1 = $rst[0]['languages_known1'];
		// if (strpos($rst[0]['languages_option1'], 'Speak') !== false)
		// 	$languages_option_speak1 = $rst[0]['languages_known1'];

		// $languages_option_read2 = $languages_option_write2 = $languages_option_speak2 = '';

		// if (strpos($rst[0]['languages_option2'], 'Read') !== false)
		// 	$languages_option_read2 = $rst[0]['languages_known2'];
		// if (strpos($rst[0]['languages_option2'], 'Write') !== false)
		// 	$languages_option_write2 = $rst[0]['languages_known2'];
		// if (strpos($rst[0]['languages_option2'], 'Speak') !== false)
		// 	$languages_option_speak2 = $rst[0]['languages_known2'];

		// if ($languages_option_read != '' || $languages_option_write != '' || $languages_option_speak) {
		// 	$html .= '
		//       <tr>
		//       <td></td>
		//       <td>' . $languages_option_read . '</td>
		//       <td>' . $languages_option_write . '</td>
		//       <td>' . $languages_option_speak . '</td>
		//       </tr>
		//       ';
		// }
		// if ($languages_option_read1 != '' || $languages_option_write1 != '' || $languages_option_speak1) {
		// 	$html .= '
		//       <tr>
		//       <td></td>
		//       <td>' . $languages_option_read1 . '</td>
		//       <td>' . $languages_option_write1 . '</td>
		//       <td>' . $languages_option_speak1 . '</td>
		//       </tr>
		//       ';
		// }
		// if ($languages_option_read2 != '' || $languages_option_write2 != '' || $languages_option_speak2) {
		// 	$html .= '
		//       <tr>
		//       <td></td>
		//       <td>' . $languages_option_read2 . '</td>
		//       <td>' . $languages_option_write2 . '</td>
		//       <td>' . $languages_option_speak2 . '</td>
		//       </tr>
		//       ';
		// }
		// $html .= '
		//     </tbody>
		//     </table>

		//     ';


		$html .= '    
            <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
            	<tbody>
	            	<tr>
	          			<td colspan="4" style="color: #66d9ff"><h4><strong>LANGUAGES</strong></h4></td>
	          		</tr>
            		<tr>                    
            			<td width="40%" ><strong>Languages Known:</strong></td>
			            <td><strong>Read</strong></td>
			            <td><strong>Write</strong></td>
			            <td><strong>Speak</strong></td>					
            		</tr>';

		$languages_option_read = $languages_option_write = $languages_option_speak = 'No';

		if (strpos($rst[0]['languages_option'], 'Read') !== false)
			$languages_option_read = 'Yes';
		if (strpos($rst[0]['languages_option'], 'Write') !== false)
			$languages_option_write = 'Yes';
		if (strpos($rst[0]['languages_option'], 'Speak') !== false)
			$languages_option_speak = 'Yes';

		$languages_option_read1 = $languages_option_write1 = $languages_option_speak1 = 'No';

		if (strpos($rst[0]['languages_option1'], 'Read') !== false)
			$languages_option_read1 = 'Yes';
		if (strpos($rst[0]['languages_option1'], 'Write') !== false)
			$languages_option_write1 = 'Yes';
		if (strpos($rst[0]['languages_option1'], 'Speak') !== false)
			$languages_option_speak1 = 'Yes';

		$languages_option_read2 = $languages_option_write2 = $languages_option_speak2 = 'No';

		if (strpos($rst[0]['languages_option2'], 'Read') !== false)
			$languages_option_read2 = 'Yes';
		if (strpos($rst[0]['languages_option2'], 'Write') !== false)
			$languages_option_write2 = 'Yes';
		if (strpos($rst[0]['languages_option2'], 'Speak') !== false)
			$languages_option_speak2 = 'Yes';

		$langNo = 1;
		if ($rst[0]['languages_known'] != '') {
			$html .= '
	              <tr>
		              <td>' . $rst[0]['languages_known'] . '</td>
		              <td>' . $languages_option_read . '</td>
		              <td>' . $languages_option_write . '</td>
		              <td>' . $languages_option_speak . '</td>
	              </tr>
	              ';
		}

		if ($rst[0]['languages_known1'] != '') {
			$html .= '
	              <tr>
		              <td>' . $rst[0]['languages_known1'] . '</td>
		              <td>' . $languages_option_read1 . '</td>
		              <td>' . $languages_option_write1 . '</td>
		              <td>' . $languages_option_speak1 . '</td>
	              </tr>
	              ';
		}

		if ($rst[0]['languages_known2'] != '') {
			$html .= '
	              <tr>
		              <td>' . $rst[0]['languages_known2'] . '</td>
		              <td>' . $languages_option_read2 . '</td>
		              <td>' . $languages_option_write2 . '</td>
		              <td>' . $languages_option_speak2 . '</td>
	              </tr>
	              ';
		}

		$html .= '</tbody> </table><br>';



		$html .= '
            <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap;font-size: 12px;">
            <tr>                    
            <td width="50%" style="border: 1px solid #A2A9B1;"><strong>Extracurricular Activities:</strong></td>
            <td width="50%" style="border: 1px solid #A2A9B1;">' . $rst[0]['extracurricular'] . '</td>
            </tr>
			 <tr>                    
            <td width="50%" style="border: 1px solid #A2A9B1;"><strong>Outstanding Achievements / Awards (if any):</strong></td>
            <td width="50%" style="border: 1px solid #A2A9B1;">' . $rst[0]['achievements'] . '</td>
            </tr>
            <tr>                    
            <td width="50%" style="border: 1px solid #A2A9B1;"><strong>Hobbies:</strong> </td>
            <td width="50%" style="border: 1px solid #A2A9B1;">' . $rst[0]['hobbies'] . '</td>
            </tr>
           
            </table> <br><br>';

		//if($position_id == 12)
		{
			$html .= '
              <table class="table table-bordered wikitable tabela" style="overflow: wrap">
              <tbody>
              <tr>
              <td colspan="10" style="color: #66d9ff"><h4><strong>PROFESSIONAL REFERENCES</strong></h4>
              <br>Candidates are instructed to provide two professional references. (References of family members, relatives & friends will not be considered)
              </td></tr>
              <tr><td><strong>Name</strong></td><td><strong>Address</strong></td>
              <td ><strong>Organisation (If employed)</strong></td><td><strong>Designation</strong></td>
              <td><strong>Email Id</strong></td><td><strong>Mobile</strong></td>
              
              <tr>
              
              <td >' . $rst[0]['refname_one'] . '</td>
              <td >' . $rst[0]['refaddressline_one'] . '</td>
              <td >' . $rst[0]['reforganisation_one'] . '</td>
              <td >' . $rst[0]['refdesignation_one'] . '</td>
              <td >' . $rst[0]['refemail_one'] . '</td>
              <td >' . $rst[0]['refmobile_one'] . '</td>
              </tr>
              <tr>
              
              <td >' . $rst[0]['refname_two'] . '</td>
              <td >' . $rst[0]['refaddressline_two'] . '</td>
              <td >' . $rst[0]['reforganisation_two'] . '</td>
              <td >' . $rst[0]['refdesignation_two'] . '</td>
              <td >' . $rst[0]['refemail_two'] . '</td>
              <td >' . $rst[0]['refmobile_two'] . '</td>
              </tr>

              </tbody>
              </table> <br><br>';
		}

		$html .= '
            <table class="table table-bordered wikitable tabela" style="overflow: wrap">
              <tbody>
            <tr><td colspan="2" style="color: #66d9ff"><h4><strong>OTHER INFORMATION</strong></h4></td></tr>
            <tr>                    
            <td width="50%"><strong>Any other information that the candidate would like to add:</strong></td>
            <td width="50%"><div style="word-break:break-all;">' . $rst[0]['comment'] . '</div></td>
            </tr> <br><br>
           
            ';

		$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>DECLARATION</strong></h4></td></tr>';



		if ($position_id == 1) {

			$html .= '<tr>                    
            <td width="50%"><strong>Declaration 1 : I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criteria according to the requirements of the related advertisement of Indian Institute Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated without any notice.</strong></td>
            <td width="50%">YES</td>
            </tr>';

			$html .= '<tr>                    
            <td width="50%"><strong>Declaration 2:    I undertake to execute an agreement to the effect that I will serve the Institute for a minimum period of Two (2) years (active service) from the date of joining the Institute. In case I leaves the service of the Institute before the expiry of the said period, a sum of Rs. 1,00,000/- (Rupees One Lakh only) will have to be paid by me to the Institute as mentioned in the recruitment notification for the post of Junior Executive dated 01-10-2024.</strong></td>
            <td width="50%">YES</td>
            </tr>';
		} else {
			$html .= '<tr>                    
            <td width="50%"><strong>Declaration 1 : I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criterias according to the requirements of the related advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</strong></td>
            <td width="50%">YES</td>
            </tr>';

			$html .= '<tr>                    
            <td width="50%"><strong>Declaration 2:   I hereby agree that any legal proceedings in respect of any matter or claims or disputes arising out of the application or out of the said advertisement can be instituted by me at Mumbai only and shall have sole and exclusive jurisdiction to try any cause / dispute. I undertake to abide by all the terms and conditions of the advertisement given by the Indian Institute of Banking & Finance.</strong></td>
            <td width="50%">YES</td>
            </tr>';
			$html .= '<tr>                    
            <td width="50%"><strong>Declaration 3:   I further certify that no disciplinary / vigilance proceedings are either pending or contemplated against me and that there are no legal cases pending against me. There have been no major / minor penalty.</strong></td>
            <td width="50%">YES</td>
            </tr>';
		}
		// $html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>UPLOAD</strong></h4></td></tr>';

		$html .= '<tr>                    
            <td width="50%"><strong>Signature:</strong></td>
            <td><img width="70px" height="70px" src="' . base_url() . 'uploads/scansignature/' . $rst[0]['scannedsignaturephoto'] . '" id="thumb" /></td>
            </tr><br><br>
            ';

		$html .= '<tr><td colspan="2" style="color: #66d9ff"><h4><strong>PLACE AND DATE</strong></h4></td></tr>';

		$html .= '<tr>                    
            <td width="50%"><strong>Place:</strong></td>
            <td width="50%">' . $rst[0]['place'] . '</td>
            </tr> 
            <tr>
            <td width="50%"><strong>Date:</strong></td>
            <td width="50%">' . date("d-m-Y", strtotime($rst[0]['submit_date'])) . '</td>
            </tr>
            ';

		$html .= '</tbody>
            </table>
            <div id="reason_form" style="display: none">';
		//echo $html ;exit;
		return $html;
	}

	private function getCustomCEOPDFhtml($rst, $career_id, $position_id, $emp_hist_arr)
	{
		$spouse_name = $mother_name = $religion = $physical_disbaility = $physical_disbaility_desc = $post_qua_name = $post_gra_sub = $post_gra_college_name = $post_gra_university = $post_gra_from_date = $post_gra_to_date = $post_aggregate_marks_obtained = $post_gra_aggregate_max_marks = $post_gra_percentage = $post_gra_class = $postcer_qua_name_gra_from_date = $cer_gra_sub = $cer_college_name = $cer_university = $cer_from_date = $cer_to_date = $cer_marks_obtained = $cer_aggregate_max_marks = $cer_percentage = $cer_class = $desirable_course_name = $desirable_college_name = $desirable_name_subject_of_course = $desirable_specialisation = $desirable_university = $desirable_from_date = $desirable_to_date = $desirable_degree_completion_date = $desirable_aggregate_marks_obtained = $desirable_aggregate_max_marks = $desirable_percentage = $desirable_grade_marks = $desirable_class = $whether_in_service = $vrs_register_date = $reason_of_resign = $name_of_present_organization = $service_from_date = $comm_address_of_org = $curr_designation = $any_other_details = $professional_ass = $subject_handled = $exp_faculty_from_date = $exp_faculty_to_date = '';

		$careers_qual_hist_arr = array();

		//if($position_id == 7 || $position_id == 14 || $position_id == 12)
		{
			$this->db->where('careers_id', $rst[0]["careers_id"]);
			$career_other_details = $this->master_model->getRecords('career_other_details');
			if ($career_other_details) {
				$spouse_name = $career_other_details[0]['spouse_name'];
				$mother_name = $career_other_details[0]['mother_name'];
				$religion = $career_other_details[0]['religion'];
				$physical_disbaility = $career_other_details[0]['physical_disbaility'];
				$physical_disbaility_desc = $career_other_details[0]['physical_disbaility_desc'];
				$post_qua_name = $career_other_details[0]['post_qua_name'];
				$post_gra_sub = $career_other_details[0]['post_gra_sub'];
				$post_gra_college_name = $career_other_details[0]['post_gra_college_name'];
				$post_gra_university = $career_other_details[0]['post_gra_university'];

				$post_gra_from_date = $career_other_details[0]['post_gra_from_date'];
				$post_gra_to_date = $career_other_details[0]['post_gra_to_date'];

				$subject_handled = $career_other_details[0]['subject_handled'];

				$exp_faculty_from_date = $career_other_details[0]['exp_faculty_from_date'];

				$exp_faculty_to_date = $career_other_details[0]['exp_faculty_to_date'];


				// if ($post_gra_from_date != "" && $post_gra_from_date != "0000-00-00") {
				// 	$post_gra_from_date = date("d-m-Y", strtotime($post_gra_from_date));
				// } else {
				// 	$post_gra_from_date = '';
				// }


				// if ($post_gra_to_date != "" && $post_gra_to_date != "0000-00-00") {
				// 	$post_gra_to_date = date("d-m-Y", strtotime($post_gra_to_date));
				// } else {
				// 	$post_gra_to_date = '';
				// }
				//post_gra_percentage
				$post_aggregate_marks_obtained = $career_other_details[0]['post_aggregate_marks_obtained'];
				$post_gra_aggregate_max_marks = $career_other_details[0]['post_gra_aggregate_max_marks'];
				$post_gra_percentage = $career_other_details[0]['post_gra_percentage'];
				$post_gra_class = $career_other_details[0]['post_gra_class'];
				$cer_qua_name = $career_other_details[0]['cer_qua_name'];
				$cer_gra_sub = $career_other_details[0]['cer_gra_sub'];
				$cer_college_name = $career_other_details[0]['cer_college_name'];
				$cer_university = $career_other_details[0]['cer_university'];

				$cer_from_date = $career_other_details[0]['cer_from_date'];
				if ($cer_from_date != "" && $cer_from_date != "0000-00-00") {
					$cer_from_date = date("d-m-Y", strtotime($cer_from_date));
				} else {
					$cer_from_date = '';
				}

				$cer_to_date = $career_other_details[0]['cer_to_date'];
				if ($cer_to_date != "" && $cer_to_date != "0000-00-00") {
					$cer_to_date = date("d-m-Y", strtotime($cer_to_date));
				} else {
					$cer_to_date = '';
				}


				$cer_marks_obtained = $career_other_details[0]['cer_marks_obtained'];
				$cer_aggregate_max_marks = $career_other_details[0]['cer_aggregate_max_marks'];
				$cer_percentage = $career_other_details[0]['cer_percentage'];
				$cer_class = $career_other_details[0]['cer_class'];

				$whether_in_service = $career_other_details[0]['whether_in_service'];

				$whether_in_service = 'yes';
				if ($career_other_details[0]['vrs_register_date'] != '') {
					$whether_in_service = 'no';
				}

				$vrs_register_date = $career_other_details[0]['vrs_register_date'];
				if ($vrs_register_date != "" && $vrs_register_date != "0000-00-00") {
					$vrs_register_date = date("d-m-Y", strtotime($vrs_register_date));
				} else {
					$vrs_register_date = '';
				}

				$reason_of_resign = $career_other_details[0]['reason_of_resign'];
				$name_of_present_organization = $career_other_details[0]['name_of_present_organization'];

				$service_from_date = $career_other_details[0]['service_from_date'];
				if ($service_from_date != "" && $service_from_date != "0000-00-00") {
					$service_from_date = date("d-m-Y", strtotime($service_from_date));
				} else {
					$service_from_date = '';
				}

				$comm_address_of_org = $career_other_details[0]['comm_address_of_org'];
				$curr_designation = $career_other_details[0]['curr_designation'];
				$any_other_details = $career_other_details[0]['any_other_details'];

				$professional_ass = $career_other_details[0]['professional_ass'];
			}

			$this->db->where('careers_id', $career_id);
			$careers_qual_hist_arr = $this->master_model->getRecords("careers_qual_hist");

			$this->db->select('m.id,m.course_name,q.*');
			$this->db->join('careers_course_mst m', 'm.course_code=q.course_code', 'LEFT');
			$this->db->where('careers_id', $career_id);
			$desirable_qualification_arr = $this->master_model->getRecords("careers_edu_qualification q");

			if ($desirable_qualification_arr) {
				$desirable_course_name            = $desirable_qualification_arr[0]['course_name'];
				$desirable_college_name           = $desirable_qualification_arr[0]['college_name'];
				$desirable_name_subject_of_course = $desirable_qualification_arr[0]['name_subject_of_course'];
				$desirable_specialisation         = $desirable_qualification_arr[0]['specialisation'];
				$desirable_university             = $desirable_qualification_arr[0]['university'];

				$desirable_period = '';
				$desirable_from_date              = $career_other_details[0]['des_from_date'];
				$desirable_to_date                = $career_other_details[0]['des_to_date'];

				if ($desirable_from_date != '' && $desirable_to_date != '') {
					$desirable_period = $desirable_from_date . ' To ' . $desirable_to_date;
				} else {
					$desirable_period = $desirable_from_date . ' ' . $desirable_to_date;
				}

				$desirable_degree_completion_date = $desirable_qualification_arr[0]['degree_completion_date'];
				$desirable_aggregate_marks_obtained = $desirable_qualification_arr[0]['aggregate_marks_obtained'];
				$desirable_aggregate_max_marks = $desirable_qualification_arr[0]['aggregate_max_marks'];
				$desirable_percentage = $desirable_qualification_arr[0]['percentage'];
				$desirable_grade_marks = $desirable_qualification_arr[0]['grade_marks'];
				$desirable_class = $desirable_qualification_arr[0]['class'];
			}
		}

		$head_title = ' Application for Chief Executive Officer';

		$this->db->where('state_code', $rst[0]["state"]);
		$stateDetails = $this->master_model->getRecords('state_master');

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
            font-size: 12px;
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


		$html .= '<h1 style="text-align:center">' . $head_title . '</h1>';
		$html .= '<img style="float:right;" class="column" width="90px" height="90px" align="right" src="' . base_url() . 'uploads/photograph/' . $rst[0]['scannedphoto'] . '"id="thumb" /><br><br><br><br><br>';

		$html .= '<div class="table-responsive ">
            <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
            <tbody>';

		$application_title = 'Chief Executive Officer';

		$html .= '
	  	<tr>
            <td colspan="10" style="color: #66d9ff"><h4><strong>BASIC DETAILS</strong></h4>
		</tr>
            <tr>                    
            <td width="50%"><strong>Name:</strong></td>
            <td width="50%">' . $rst[0]["sel_namesub"] . ' ' . $rst[0]["firstname"] . ' ' . $rst[0]['middlename'] . ' ' . $rst[0]['lastname'] . '</td>
            </tr>  
            ';
		if ($position_id == 1) {
			$this->db->select('transaction_no,receipt_no,amount');
			$this->db->where('member_regnumber', $rst[0]["reg_id"]);
			//	$this->db->where('c.active_status', '1');
			$payment_transaction = $this->master_model->getRecords('payment_transaction');

			/*$html.= '
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
                    </tr>';*/
		}
		$html .= '
	  		<tr>                    
				<td width="50%"><strong>Marital Status:</strong></td>
				<td width="50%">' . $rst[0]['marital_status'] . '</td>
            </tr>
            <tr>                    
            <td width="50%"><strong>Spouse\'s Name:</strong></td>
            <td width="50%">' . $spouse_name . '</td>
            </tr>  
            ';
		$html .= '
            <tr>                    
            <td width="50%"><strong>Father\'s Name:</strong></td>
            <td width="50%">' . $rst[0]["father_husband_name"] . '</td>
            </tr>  
            ';
		$html .= '
            <tr>                    
            <td width="50%"><strong>Mother\'s Name:</strong></td>
            <td width="50%">' . $mother_name . '</td>
            </tr>  
            ';

		$bday = new DateTime($rst[0]['dateofbirth']); //dd.mm.yyyy
		$today = new DateTime('2026-01-31'); // Current date
		$diff = $today->diff($bday);

		$html .= '<tr>                    
            <td width="50%"><strong>Date Of Birth:</strong></td>
            <td width="50%">' . $rst[0]['dateofbirth'] . '</td>
            </tr>
            <tr>                    
            <td width="50%"><strong>Age as on 31.01.2026:</strong></td>
            <td width="50%">' . $diff->y . ' years, ' . $diff->m . ' month ,' . $diff->d . ' days</td>
            </tr>
            
            <tr>                    
            <td width="50%"><strong>Gender:</strong></td>
            <td width="50%">' . $rst[0]['gender'] . '</td>
            </tr>';

		$html .= '<tr>                    
              <td width="50%"><strong>Email Id :</strong></td>
              <td width="50%">' . $rst[0]['email'] . '</td>
            </tr>';

		$html .= '	
            <tr>                    
            <td width="50%"><strong>Mobile Number:</strong></td>
            <td width="50%">' . $rst[0]['mobile'] . '</td>
            </tr>
            <tr>                    
            <td width="50%"><strong>Alternate Mobile Number:</strong></td>
            <td width="50%">' . $rst[0]['alternate_mobile'] . '</td>
            </tr>
            
            <tr>                    
            <td width="50%"><strong>PAN Number:</strong></td>
            <td width="50%">' . $rst[0]['pan_no'] . '</td>
            </tr>
            
            <tr>                    
            <td width="50%"><strong>Aadhar Card Number:</strong></td>
            <td width="50%">' . $rst[0]['aadhar_card_no'] . '</td>
            </tr>
            <tr>                    
            <td width="50%"><strong>COMMUNICATION ADDRESS:</strong></td>
            <td width="50%">' . $rst[0]['addressline1'] . ', ' . $rst[0]['addressline2'] . ', ' . $rst[0]['addressline3'] . ', ' . $rst[0]['addressline4'] . '<br>' . $rst[0]['district'] . ', ' . $rst[0]['city'] . '<br>' . $rst[0]['state'] . '<br>' . $rst[0]['pincode'] . '</td>
            </tr> 
            <tr>                    
            <td width="50%"><strong>PERMANENT ADDRESS:</strong></td>
            <td width="50%">' . $rst[0]['addressline1_pr'] . ', ' . $rst[0]['addressline2_pr'] . ', ' . $rst[0]['addressline3_pr'] . ', ' . $rst[0]['addressline4_pr'] . '<br>' . $rst[0]['district_pr'] . ', ' . $rst[0]['city_pr'] . '<br>' . $stateDetails[0]['state_name'] . '<br>' . $rst[0]['pincode_pr'] . '</td>
            </tr>          
            </tbody>
            </table><br>
            ';

		// START -- EDUCATIONAL QUALIFICATION DETAILS
		$html .= '
	            <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
	            <tbody>
	            <tr>
		            <td colspan="8" style="color: #66d9ff"><h4 style="overflow: wrap;"><strong>EDUCATIONAL QUALIFICATION</strong></h4>
		            <br><strong style="color: #444">ESSENTIAL</strong>
		            </td>
		        </tr>
	            <tr>
		            <td><strong>Name</strong></td>
		            <td><strong>Qualification</strong></td>
		            <td ><strong>Subject</strong></td>
		            <td><strong>College/Institution </strong></td>
		            <td><strong>University </strong></td>
		            <td><strong>Period</strong></td>
		            <td><strong>Percentage Obtained</strong></td>
		            <td><strong>Class/Grade</strong></td>
	            </tr>
	            
	            <tr>
		            <td ><note>Educational Qualification I - Post Graduation</note></td>
		            <td >' . $post_qua_name . '</td>
		            <td >' . $post_gra_sub . '</td>
		            <td >' . $post_gra_college_name . '</td>
		            <td >' . $post_gra_university . '</td>
		            <td >' . $post_gra_from_date . " To " . $post_gra_to_date . '</td>
		            <td >' . $post_gra_percentage . '</td> 
		            <td >' . $post_gra_class . '</td>
	            </tr>';

		if (isset($careers_qual_hist_arr) && count($careers_qual_hist_arr) > 0) {
			foreach ($careers_qual_hist_arr as $qual_hist) {
				$html .= '
	            		<tr>
				            <td> <note> Educational Qualification 2: Additional Qualifications/Certification</note> </td>
				            <td>' . $qual_hist["cer_qua_name"] . '</td>
				            <td>' . $qual_hist["cer_gra_sub"] . '</td>
				            <td>' . $qual_hist["cer_college_name"] . '</td>
				            <td>' . $qual_hist["cer_university"] . '</td>
				            <td>' . $qual_hist["cer_from_date"] . " To " . $qual_hist["cer_to_date"] . '</td> 
				            <td>' . $qual_hist["cer_percentage"] . '</td>
				            <td>' . $qual_hist["cer_class"] . '</td>
			            </tr>';
			}
		} else {
			$html .= '	<tr>
						            <td colspan="8"><h4 style="text-align : center;">No Details</td>
						        </tr>';
		}

		$html .= '</tbody>
	            </table><br>';
		// END -- EDUCATIONAL QUALIFICATION DETAILS

		// START -- CAIIB Section
		$html .= '<table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
		            	<tbody>
		            		<tr>
		            			<td colspan="10" style="color: #66d9ff"><h4><strong>CAIIB</strong></h4></td>
		            		</tr>
		            	<tr>                    
				            <td width="50%"><strong>CAIIB:</strong></td>
				            <td width="50%">' . $rst[0]["ess_subject"] . '</td>
		            	</tr>';

		if ($rst[0]["ess_subject"] == "Yes") {
			$html .= '<tr>                    
					            	<td width="50%"><strong>Membership Number:</strong></td>
					            	<td width="50%">' . $rst[0]["membership_number"] . '</td>
		            			  </tr>';
		}
		// END -- CAIIB Section

		// START -- PHD Section	
		$phd_period = '';

		if ($career_other_details[0]["phd_from_date"] != '' && $career_other_details[0]["phd_to_date"] != '') {
			$phd_period = $career_other_details[0]["phd_from_date"] . ' To ' . $career_other_details[0]["phd_to_date"];
		} else {
			$phd_period = $career_other_details[0]["phd_from_date"] . ' ' . $career_other_details[0]["phd_to_date"];
		}

		$html .= '<br>
              		<tr>
		            	<td colspan="10" style="color: #66d9ff"><h4><strong>Ph.D (IN BANKING OR FINANCE)</strong></h4></td>
		            </tr>
		            <tr>                    
			            <td width="50%"><strong>Name of Research Topic:</strong></td>
			            <td width="50%">' . $rst[0]["phd_course"] . '</td>
		            </tr> 
		            <tr>                    
			            <td width="50%"><strong>University:</strong></td>
			            <td width="50%">' . $rst[0]["phd_university"] . '</td>
		            </tr> 
		            <tr>                    
			            <td width="50%"><strong>Period:</strong></td>
			            <td width="50%">' . $phd_period . '</td>
		            </tr> 
    			</tbody>
    		</table><br>';
		// END -- PHD Section


		// START -- DESIRABLE Section
		if ($position_id == 5) {
			$course_lable_name = 'Name of course';
			$subject_lable_name = 'Specialisation';
		} else {
			$course_lable_name = 'Name';
			$subject_lable_name = 'Subject';
		}

		$html .= '
            <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
            	<tbody>
            		<tr>
            			<td colspan="10" style="color: #66d9ff"><h4><strong>DESIRABLE QUALIFICATION</strong></h4></td>
            		</tr>
            		<tr>
            			<td><strong>' . $course_lable_name . '</strong></td>
            			<td><strong>' . $subject_lable_name . '</strong></td>
            			<td ><strong>College Name and Address</strong></td>
            			<td><strong>University</strong></td>
			            <td><strong>Period</strong></td>
			            <td><strong> Percentage</strong></td>
			            <td><strong>Class/Grade</strong></td>
            		</tr>';

		if ($desirable_course_name != '' || $desirable_name_subject_of_course != '' || $desirable_college_name != '' || $desirable_university != '' || $desirable_period != '' || $desirable_percentage != '' || $desirable_class != '') {
			$html .= '	<tr>
				            <td>' . $desirable_course_name . '</td>
				            <td>' . $desirable_name_subject_of_course . '</td>
				            <td>' . $desirable_college_name . '</td>
				            <td>' . $desirable_university . '</td>
				            <td>' . $desirable_period . '</td>
				            <td>' . $desirable_percentage . '</td>
				            <td>' . $desirable_class . '</td>
			        	</tr>';
		} else {
			$html .= '  <tr>
          					<td colspan="7"><h4 style="text-align : center;">No Details</td>
          				</tr>';
		}


		$html .= ' </tbody> </table><br>';
		// END -- DESIRABLE Section    

		// START --  EMPLOYMENT HISTORY
		$html .= '
          <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
          	<tbody>
          		<tr>
          			<td colspan="4" style="color: #66d9ff"><h4><strong>EMPLOYMENT HISTORY  - Last 5 positions held with role & responsibilities in detail</strong></h4></td>
          		</tr>
          		<tr>
          			<td><strong>Name of the Organisation</strong></td>
          			<td><strong>Period</strong></td>
          			<td ><strong>Designation/Post Held</strong></td>
          			<td><strong>Responsibilities</strong></td>
          		</tr>';
		if (isset($emp_hist_arr) && count($emp_hist_arr) > 0) {
			foreach ($emp_hist_arr as $emp_hist) {

				// START -- PHD Section	
				$org_period = '';

				if ($emp_hist['job_from_date'] != '' && $emp_hist['job_to_date'] != '') {
					$org_period = $emp_hist['job_from_date'] . ' To ' . $emp_hist['job_to_date'];
				} else {
					$org_period = $emp_hist['job_from_date'] . ' ' . $emp_hist['job_to_date'];
				}

				$html .= '<tr>
	          
	              <td>' . $emp_hist['organization'] . '</td>
	              <td>' . $org_period . '</td>
	              <td>' . $emp_hist['designation'] . '</td>
	              <td>' . $emp_hist['responsibilities'] . '</td>
	              </tr>';
			}
		} else {
			$html .= '  <tr>
          					<td colspan="4"><h4 style="text-align : center;">No Details</td>
          				</tr>';
		}

		$html .= '
          	</tbody>
          </table><br>';
		// END --  EMPLOYMENT HISTORY

		// START --  CURRENT EMPLOYMENT HISTORY
		$html .= '<table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap;margin-top:10px;">
					<tbody>';

		$html .= '		<tr>
							<td colspan="2" style="color: #66d9ff"><h4><strong>CURRENT EMPLOYMENT DETAILS</strong></h4></td>
						</tr>
          				<tr>                    
          					<td width="50%"><strong>Whether In Service?:</strong></td>
          					<td width="50%">' . ucwords($whether_in_service) . '</td>
          				</tr>';

		if ($whether_in_service == "no") {
			$html .= '
			<tr>                    
	            <td width="50%"><strong>Date of Superannuation/VRS/Resignation etc:</strong></td>
	            <td width="50%">' . $vrs_register_date . '</td>
            </tr>
            <tr>                    
	            <td width="50%"><strong>Reason for Resignation/Leaving:</strong></td>
	            <td width="50%">' . $reason_of_resign . '</td>
            </tr>';
		} else if ($whether_in_service == "yes") {
			$html .= '
			<tr>                    
	            <td width="50%"><strong>Name of the Present Organisation:</strong></td>
	            <td width="50%">' . $name_of_present_organization . '</td>
            </tr>
            <tr>                    
	            <td width="50%"><strong>Period:</strong></td>
	            <td width="50%">' . $service_from_date . '</td>
            </tr>
            <tr>                    
	            <td width="50%"><strong>Communication Address of the Organization:</strong></td>
	            <td width="50%">' . $comm_address_of_org . '</td>
            </tr>
            <tr>                    
	            <td width="50%"><strong>Designation/Post Held:</strong></td>
	            <td width="50%">' . $curr_designation . '</td>
            </tr>
            <tr>                    
	            <td width="50%"><strong>Responsibilities:</strong></td>
	            <td width="50%">' . $any_other_details . '</td>
            </tr>';
		}
		// END --  CURRENT EMPLOYMENT HISTORY

		// START -- FACULTY EXPERIENCE DETAILS

		// END -- FACULTY EXPERIENCE DETAILS

		$html .= '</tbody></table><br>';


		// START --  ORGANISATION HISTORY
		$this->db->where('careers_org_hist.careers_id', $rst[0]['careers_id']);
		$career_organization_data = $this->master_model->getRecords("careers_org_hist");

		$html .= '
          <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
          	<tbody>
          		<tr>
          			<td colspan="4" style="color: #66d9ff"><h4><strong>OTHER ORGANISATION</strong></h4></td>
          		</tr>
          		<tr>
          			<td><strong>Name of the Organisation</strong></td>
          			<td><strong>Period</strong></td>
          			<td ><strong>Designation</strong></td>
          			<td><strong>Responsibilities</strong></td>
          		</tr>';

		if (isset($career_organization_data) && count($career_organization_data) > 0) {
			foreach ($career_organization_data as $organization_data) {

				// START -- PHD Section	
				$orgnsn_period = '';

				if ($organization_data['job_from_date'] != '' && $organization_data['job_to_date'] != '') {
					$orgnsn_period = $organization_data['job_from_date'] . ' To ' . $organization_data['job_to_date'];
				} else {
					$orgnsn_period = $organization_data['job_from_date'] . ' ' . $organization_data['job_to_date'];
				}

				$html .= '
						<tr>
			              <td >' . $organization_data['org_organization'] . '</td>
			              <td >' . $orgnsn_period . '</td>
			              <td >' . $organization_data['designation'] . '</td>
			              <td >' . $organization_data['responsibilities'] . '</td>
	              		</tr>';
			}
		} else {
			$html .= '  <tr>
          					<td colspan="4"><h4 style="text-align : center;">No Details</td>
          				</tr>';
		}

		$html .= '
          	</tbody>
          </table><br>';
		// END --  ORGANISATION HISTORY


		// START --  EXPERIENCE AS PRINCIPAL
		$this->db->where('careers_exp_org_hist.careers_id', $rst[0]['careers_id']);
		$career_exp_principal_data = $this->master_model->getRecords("careers_exp_org_hist");

		$html .= '
          <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
          	<tbody>
          		<tr>
          			<td colspan="4" style="color: #66d9ff"><h4><strong>EXPERIENCE AS PRINCIPAL / DIRECTOR OF A BANKING STAFF TRAINING COLLEGE / CENTRE/ MANAGMENT INSTITUTION</strong></h4></td>
          		</tr>
          		<tr>
          			<td><strong>Name of the Organisation</strong></td>
          			<td><strong>Period</strong></td>
          			<td ><strong>Designation</strong></td>
          			<td><strong>Responsibilities</strong></td>
          		</tr>';

		if (isset($career_exp_principal_data) && count($career_exp_principal_data) > 0) {
			foreach ($career_exp_principal_data as $exp_principal_data) {

				// START -- PHD Section	
				$exp_period = '';

				if ($exp_principal_data['job_from_date'] != '' && $exp_principal_data['job_to_date'] != '') {
					$exp_period = $exp_principal_data['job_from_date'] . ' To ' . $exp_principal_data['job_to_date'];
				} else {
					$exp_period = $exp_principal_data['job_from_date'] . ' ' . $exp_principal_data['job_to_date'];
				}

				$html .= '
					<tr>
						<td >' . $exp_principal_data['org_organization'] . '</td>
						<td >' . $exp_period . '</td>
						<td >' . $exp_principal_data['designation'] . '</td>
						<td >' . $exp_principal_data['responsibilities'] . '</td>
	              	</tr>';
			}
		} else {
			$html .= '  <tr>
          					<td colspan="4"><h4 style="text-align : center;">No Details</td>
          				</tr>';
		}

		$html .= '
          	</tbody>
          </table><br>';
		// END --  EXPERIENCE AS PRINCIPAL  


		// START --  EXPERIENCE AS FACULTY
		$this->db->where('careers_exp_bank_hist.careers_id', $rst[0]['careers_id']);
		$career_exp_faculty_data = $this->master_model->getRecords("careers_exp_bank_hist");

		$html .= '
          <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
          	<tbody>
          		<tr>
          			<td colspan="5" style="color: #66d9ff"><h4><strong>EXPERIENCE AS FACULTY</strong></h4></td>
          		</tr>
          		<tr>
          			<td><strong>Experience as Faculty</strong></td>
          			<td><strong>Period</strong></td>
          			<td ><strong>Subjects Handled</strong></td>
          			<td><strong>Area of Specialisation</strong></td>
          			<td><strong>Membership of Professional Associations</strong></td>
          		</tr>';

		if (isset($career_exp_faculty_data) && count($career_exp_faculty_data) > 0) {
			foreach ($career_exp_faculty_data as $exp_faculty_data) {

				// START -- PHD Section	
				$exp_fac_period = '';

				if ($exp_faculty_data['exp_faculty_from_date'] != '' && $exp_faculty_data['exp_faculty_to_date'] != '') {
					$exp_fac_period = $exp_faculty_data['exp_faculty_from_date'] . ' To ' . $exp_faculty_data['exp_faculty_to_date'];
				} else {
					$exp_fac_period = $exp_faculty_data['exp_faculty_from_date'] . ' ' . $exp_faculty_data['exp_faculty_to_date'];
				}

				$html .= '
					<tr>
						<td >' . $exp_faculty_data['exp_in_bank'] . '</td>
						<td >' . $exp_fac_period . '</td>
						<td >' . $exp_faculty_data['subject_handled'] . '</td>
						<td >' . $exp_faculty_data['exp_in_functional_area'] . '</td>
						<td >' . $exp_faculty_data['professional_ass'] . '</td>
	              	</tr>';
			}
		} else {
			$html .= '  <tr>
          					<td colspan="5"><h4 style="text-align : center;">No Details</td>
          				</tr>';
		}

		$html .= '
          	</tbody>
          </table><br>';
		// END --  EXPERIENCE AS FACULTY  

		// START LANGUAGE  
		$html .= '    
            <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
            	<tbody>
	            	<tr>
	          			<td colspan="4" style="color: #66d9ff"><h4><strong>LANGUAGES</strong></h4></td>
	          		</tr>
            		<tr>                    
            			<td width="40%" ><strong>Languages Known:</strong></td>
			            <td><strong>Read</strong></td>
			            <td><strong>Write</strong></td>
			            <td><strong>Speak</strong></td>					
            		</tr>';

		$languages_option_read = $languages_option_write = $languages_option_speak = 'No';

		if (strpos($rst[0]['languages_option'], 'Read') !== false)
			$languages_option_read = 'Yes';
		if (strpos($rst[0]['languages_option'], 'Write') !== false)
			$languages_option_write = 'Yes';
		if (strpos($rst[0]['languages_option'], 'Speak') !== false)
			$languages_option_speak = 'Yes';

		$languages_option_read1 = $languages_option_write1 = $languages_option_speak1 = 'No';

		if (strpos($rst[0]['languages_option1'], 'Read') !== false)
			$languages_option_read1 = 'Yes';
		if (strpos($rst[0]['languages_option1'], 'Write') !== false)
			$languages_option_write1 = 'Yes';
		if (strpos($rst[0]['languages_option1'], 'Speak') !== false)
			$languages_option_speak1 = 'Yes';

		$languages_option_read2 = $languages_option_write2 = $languages_option_speak2 = 'No';

		if (strpos($rst[0]['languages_option2'], 'Read') !== false)
			$languages_option_read2 = 'Yes';
		if (strpos($rst[0]['languages_option2'], 'Write') !== false)
			$languages_option_write2 = 'Yes';
		if (strpos($rst[0]['languages_option2'], 'Speak') !== false)
			$languages_option_speak2 = 'Yes';

		$langNo = 1;
		if ($rst[0]['languages_known'] != '') {
			$html .= '
	              <tr>
		              <td>' . $rst[0]['languages_known'] . '</td>
		              <td>' . $languages_option_read . '</td>
		              <td>' . $languages_option_write . '</td>
		              <td>' . $languages_option_speak . '</td>
	              </tr>
	              ';
		}

		if ($rst[0]['languages_known1'] != '') {
			$html .= '
	              <tr>
		              <td>' . $rst[0]['languages_known1'] . '</td>
		              <td>' . $languages_option_read1 . '</td>
		              <td>' . $languages_option_write1 . '</td>
		              <td>' . $languages_option_speak1 . '</td>
	              </tr>
	              ';
		}

		if ($rst[0]['languages_known2'] != '') {
			$html .= '
	              <tr>
		              <td>' . $rst[0]['languages_known2'] . '</td>
		              <td>' . $languages_option_read2 . '</td>
		              <td>' . $languages_option_write2 . '</td>
		              <td>' . $languages_option_speak2 . '</td>
	              </tr>
	              ';
		}

		$html .= '</tbody> </table><br>';


		// STRAT -- REFERENCES
		$html .= '
              <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
              	<tbody>
              		<tr>
              			<td colspan="10" style="color: #66d9ff"><h4><strong>PROFESSIONAL REFERENCES</strong></h4>
              				<br>Candidates are instructed to provide two professional references. (References of family members, relatives & friends will not be considered)
              			</td>
              		</tr>
	              	<tr>
	              		<td><strong>Name</strong></td>
	              		<td><strong>Complete Address</strong></td>
	              		<td ><strong>Organisation (If employed)</strong></td>
	              		<td><strong>Designation</strong></td>
	              		<td><strong>Email Id</strong></td>
	              		<td><strong>Mobile</strong></td>
              		</tr>
					<tr>
						<td>' . $rst[0]['refname_one'] . '</td>
						<td>' . $rst[0]['refaddressline_one'] . '</td>
						<td>' . $rst[0]['reforganisation_one'] . '</td>
						<td>' . $rst[0]['refdesignation_one'] . '</td>
						<td>' . $rst[0]['refemail_one'] . '</td>
						<td>' . $rst[0]['refmobile_one'] . '</td>
					</tr>

					<tr>
						<td>' . $rst[0]['refname_two'] . '</td>
						<td>' . $rst[0]['refaddressline_two'] . '</td>
						<td>' . $rst[0]['reforganisation_two'] . '</td>
						<td>' . $rst[0]['refdesignation_two'] . '</td>
						<td>' . $rst[0]['refemail_two'] . '</td>
						<td>' . $rst[0]['refmobile_two'] . '</td>
					</tr>
				</tbody>
			</table><br>';
		// END -- REFERENCES

		// START -- PUBLICATION 
		$html .= '
            <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap;">
            	<tbody>
	            	<tr>
	          			<td colspan="4" style="color: #66d9ff"><h4><strong>PUBLICATION</strong></h4> </td>
	          		</tr>

	            	<tr>                    
			            <td width="50%" style="border: 1px solid #A2A9B1;"><strong>Publication of Books:</strong></td>
			            <td width="50%" style="border: 1px solid #A2A9B1;">' . $rst[0]['publication_of_books'] . '</td>
	            	</tr>
				 	<tr>                    
			            <td width="50%" style="border: 1px solid #A2A9B1;"><strong>Publication of articles (give latest, not more than ten):</strong></td>
			            <td width="50%" style="border: 1px solid #A2A9B1;">' . $rst[0]['publication_of_articles'] . '</td>
	            	</tr>
	            	<tr>                    
			            <td width="50%" style="border: 1px solid #A2A9B1;"><strong>Area of Specialisation:</strong> </td>
			            <td width="50%" style="border: 1px solid #A2A9B1;">' . $rst[0]['area_of_specialization'] . '</td>
		            </tr>
		        </tbody>    
            </table><br>';
		// END -- PUBLICATION

		// START -- OTHER INFORMATION 
		$html .= '
            <table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
              	<tbody>
            		<tr>
            			<td colspan="2" style="color: #66d9ff"><h4><strong>OTHER INFORMATION</strong></h4></td>
            		</tr>
            		<tr>                    
            			<td width="50%"><strong>Earliest date of joining if selected:</strong></td>
            			<td width="50%"><div style="word-break:break-all;">' . $rst[0]['earliest_date_of_joining'] . '</div></td>
            		</tr>
            		<tr>                    
            			<td width="50%"><strong>Why do you consider yourself suitable for the post of CEO of this Institute:</strong></td>
            			<td width="50%"><div style="word-break:break-all;">' . $rst[0]['suitable_of_the_post_of_CEO'] . '</div></td>
            		</tr>
            		<tr>                    
            			<td width="50%"><strong>Any other information that the candidate would like to add:</strong></td>
            			<td width="50%"><div style="word-break:break-all;">' . $rst[0]['comment'] . '</div></td>
            		</tr>
            	</tbody>
            </table>	 
            ';

		$html .= '<br><table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
              			<tbody>
              				<tr>
              					<td colspan="2" style="color: #66d9ff"><h4><strong>DECLARATION</strong></h4></td>
              				</tr>';

		$html .= '
					<tr>                    
			            <td width="50%"><strong>Declaration 1 :  I declare that all statements made in this application are true, complete and correct to the best of my knowledge and belief . I also declare that I have not suppressed any material fact(s)/information. I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying any of the eligibility criterias according to the requirements of the related advertisement of Indian Institute of Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated with immediate effect and the losses occured can be accounted on me.</strong></td>
			            <td width="50%">YES</td>
		            </tr> 
            	';

		$html .= '<tr>                    
		            <td width="50%"><strong>Signature:</strong></td>
		            <td><img width="70px" height="70px" src="' . base_url() . 'uploads/scansignature/' . $rst[0]['scannedsignaturephoto'] . '" id="thumb" /></td>
		          </tr>
            	</tbody>
            </table>
            ';

		$html .= '<br><table width="100%" class="table table-bordered wikitable tabela" style="overflow: wrap">
              		<tbody>
						<tr><td colspan="2" style="color: #66d9ff"><h4><strong>PLACE AND DATE</strong></h4></td></tr>';

		$html .= '<tr>                    
            <td width="50%"><strong>Place:</strong></td>
            <td width="50%">' . $rst[0]['place'] . '</td>
            </tr> 
            <tr>
            <td width="50%"><strong>Date:</strong></td>
            <td width="50%">' . date("d-m-Y", strtotime($rst[0]['createdon'])) . '</td>
            </tr>
            ';
		$html .= '</tbody>
            </table>
            <div id="reason_form" style="display: none">';
		// echo  $html; exit;
		return $html;
	}
	/* GET ALL FOLDER LISTING FROM REQUIRED FOLDER  */
	function get_directory_list($dir_name)
	{
		return $this->array_sort_ascending(directory_map('./' . $dir_name, 1)); // This is use to get all folders and files from current directory excluding subfolders
	}

	/* SORT ARRAY IN ASCENDING ORDER USING VALUES NOT KEY */
	function array_sort_ascending($array)
	{
		if ($array != "") {
			sort($array); /* sort() - sort arrays in ascending order. rsort() - sort arrays in descending order. */
		}
		return $array;
	}

	/* RECURSIVE FUNCTION TO DELETE ALL SUB FILES AND FOLDER FROM REQUIRED FOLDER */
	function rmdir_recursive($dir)
	{
		foreach (scandir($dir) as $file) {
			if ('.' === $file || '..' === $file) continue;
			if (is_dir("$dir/$file")) {
				$this->rmdir_recursive("$dir/$file");
			} else unlink("$dir/$file");
		}
		rmdir($dir);
	}
}
