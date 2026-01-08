<!-- 
	Created BY Pooja Mane : 27-07-2022
	Shared UAT : 25-08-2022
	Made Live : 21-09-2022
 -->
<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Scribe_list extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//$this->load->helper(array('form', 'url'));
		//this->load->helper('page');
		/* Load form validation library */
		//$this->load->library('upload');
		//$this->load->library('email');
		//$this->load->library('pagination');
		//$this->load->library('table');	

		$this->load->library('form_validation');
		$this->load->model('Master_model');
		$this->load->library('session');
		$this->load->model('Emailsending');

		error_reporting(E_ALL); // Report all errors
		ini_set("error_reporting", E_ALL); // Same as error_reporting(E_ALL);
	}

	public function index()
	{
		/*TOTAL SCRIBE APPLICATION*/
		$this->db->select('count(*) as rows');
		$this->db->where('mobile_scribe != 0');
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('scribe_registration');
		$data['count1'] = $count1 = $total_scribe_reg[0]['rows']; //total scribe

		/*TOTAL SPECIAL APPLICATION*/
		$this->db->select('count(*) as rows');
		$this->db->where('mobile_scribe = 0');
		$data['total_special'] = $total_special = $this->master_model->getRecords('scribe_registration');
		$data['count2'] = $count2 = $total_special[0]['rows']; // total special


		/*TOTAL APPROVED SCRIBE APPLICATION*/
		$this->db->select('count(*) as rows');
		$this->db->where('mobile_scribe != 0');
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('scribe_registration', array('scribe_approve' => '1'));
		$data['count3'] = $count3 = $total_scribe_reg[0]['rows']; //approved scribe


		/*TOTAL SPECIAL APPROVED APPLICATION*/
		$this->db->select('count(*) as rows');
		$this->db->where('mobile_scribe = 0');
		$data['total_special'] = $total_special = $this->master_model->getRecords('scribe_registration', array('scribe_approve' => '1'));
		$data['count4'] = $count4 = $total_special[0]['rows']; //approved special


		/*TOTAL REJECTED SCRIBE APPLICATION*/
		$this->db->select('count(*) as rows');
		$this->db->where('mobile_scribe != 0');
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('scribe_registration', array('scribe_approve' => '3'));
		$data['count5'] = $count5 = $total_scribe_reg[0]['rows']; //rejected scribe


		/*TOTAL SPECIAL REJECTED APPLICATION*/
		$this->db->select('count(*) as rows');
		$this->db->where('mobile_scribe = 0');
		$data['total_special'] = $total_special = $this->master_model->getRecords('scribe_registration', array('scribe_approve' => '3'));
		$data['count6'] = $count6 = $total_special[0]['rows']; //rejected special

		/*DAILY OR TODAY'S APPLICATION DETAILS*/
		$today = date('Y-m-d');

		/*TOTAL SCRIBE APPLICATION TODAY*/
		$this->db->select('count(*) as rows');
		$array = array('created_on' => $today, 'mobile_scribe !=' => '0');
		$this->db->where($array);
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('scribe_registration');
		$data['count7'] = $count7 = $total_scribe_reg[0]['rows']; //total scribe
		//echo $this->db->last_query();die;

		/*TOTAL SPECIAL APPLICATION TODAY*/
		$this->db->select('count(*) as rows');
		$array = array('created_on' => $today, 'mobile_scribe' => '0');
		$this->db->where($array);
		$data['total_special'] = $total_special = $this->master_model->getRecords('scribe_registration');
		$data['count8'] = $count8 = $total_special[0]['rows']; // total special


		/*TOTAL APPROVED SCRIBE APPLICATION TODAY*/
		$this->db->select('count(*) as rows');
		$array = array('mobile_scribe !=' => '0');
		$this->db->like('modified_on', $today);
		$this->db->where($array);
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('scribe_registration', array('scribe_approve' => '1'));
		$data['count9'] = $count9 = $total_scribe_reg[0]['rows']; //approved scribe
		//echo $this->db->last_query();die;

		/*TOTAL SPECIAL APPROVED APPLICATION TODAY*/
		$this->db->select('count(*) as rows');
		$array = array('mobile_scribe ' => '0');
		$this->db->where($array);
		$this->db->like('modified_on', $today);
		$data['total_special'] = $total_special = $this->master_model->getRecords('scribe_registration', array('scribe_approve' => '1'));
		$data['count10'] = $count10 = $total_special[0]['rows']; //approved special

		//echo $this->db->last_query();die;

		/*TOTAL REJECTED SCRIBE APPLICATION TODAY*/
		$this->db->select('count(*) as rows');
		$array = array('mobile_scribe !=' => '0');
		$this->db->where($array);
		$this->db->like('modified_on', $today);
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('scribe_registration', array('scribe_approve' => '3'));
		$data['count11'] = $count11 = $total_scribe_reg[0]['rows']; //rejected scribe
		//echo $this->db->last_query();die;

		/*TOTAL SPECIAL REJECTED APPLICATION TODAY*/
		$this->db->select('count(*) as rows');
		$array = array('mobile_scribe !=' => '0');
		$this->db->where($array);
		$this->db->like('modified_on', $today);
		$data['total_special'] = $total_special = $this->master_model->getRecords('scribe_registration', array('scribe_approve' => '3'));
		$data['count12'] = $count12 = $total_special[0]['rows']; //rejected special
		//echo $this->db->last_query();die;
		$this->load->view('scribe_dashboard/dashboard', $data);
	}

	/*QUERY OPTION IN SCRIBE MODULE BY POOJA MANE : 29-11-2022*/
	public function SearchQry()
	{
		$key = $result = $value = '';
		$data = array();
		//echo $_POST['Search'];
		/*echo $_POST['searchBy'];
			echo $_POST['SearchVal'];die;*/
		/*search query result*/

		$data['searchBy'] = $data['searchValue'] = '';
		if (isset($_POST['Search'])) {
			$data['searchBy'] = $key = $_POST['searchBy'];
			$data['searchValue'] = $value = trim($_POST['SearchVal']);

			/*ON URN SELECTION*/
			if ($key == '01') {
				$this->db->where('scribe_uid', $value);
			}

			/*ON MEMBER NO SELECTION*/
			if ($key == '02') {
				$this->db->where('regnumber', $value);
			}

			/*FOR EXAM SELECTION*/
			if ($key == '03') {
				$this->db->where('exam_name', $value);
			}

			/*FOR EXAM DATE SELECTION*/
			if ($key == '04') {
				$this->db->where('exam_date', $value);
				$this->db->where('exam_date !=', '0000-00-00');
			}

			/*FOR Name of Scribe*/
			if ($key == '05') {
				$this->db->where(" (name_of_scribe LIKE '%" . $value . "%') ");
			}

			/*FOR Scribe Mobile Number*/
			if ($key == '06') {
				$this->db->where(" (mobile_scribe LIKE '%" . $value . "%') ");
			}

			$data['result'] = $this->master_model->getRecords("scribe_registration");
			//echo $this->db->last_query();					
		}

		$this->load->view('scribe_dashboard/search_view', $data);
	}
	/*QUERY OPTION IN SCRIBE MODULE END BY POOJA MANE : 29-11-2022*/

	public function scribe()
	{

		$from_date = $to_date = $created_on = $exam_name = '';
		/*LISTING CONDITION OF APPLIED FILTERS 01-12-2022*/
		if (isset($_POST['btnSearch'])) {

			if (!empty($_POST['created_on'])) {
				$created_on = $_POST['created_on'];
				$this->db->where("s.created_on = '$created_on'");
			}
			if (!empty($_POST['exam_name'])) {
				$exam_name = $_POST['exam_name'];
				$this->db->where("s.exam_name = '$exam_name'");
			}
			if (!empty($_POST['from_date'] && $_POST['to_date'])) {
				$from_date = $_POST['from_date'];
				$to_date = $_POST['to_date'];
				$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date'");
			}
		}/*LISTING CONDITION OF APPLIED FILTERS END 01-12-2022*/

		/*WHOLE LISITING */
		$this->db->where("s.mobile_scribe != 0");
		$this->db->group_by('s.scribe_uid');
		$this->db->order_by('id', 'DESC');
		$scribe_show = $this->master_model->getRecords('scribe_registration s');
		// echo $this->db->last_query();
		// print_r($scribe_show);die;
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['scribe_show'] = $scribe_show;
		$this->load->view('scribe_dashboard/scribelist', $data);

		/*DOWNLOAD BASED ON TWO DATES 16/08/2022*/
		if (isset($_POST['download'])) {

			$csv = "Scribe registration details\n\n";
			$csv .= "Sr.No, URN, Member no,  Member Name,Email,Mobile,Exam Name,Subject,  Center Name, Scribe Name, Scribe Mobile no,Educational qualification,Employment details,Photo ID Number,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date , Created date, Status\n"; //Column headers


			/*LISTING CONDITION ON APPLIED FILTERS 01-12-2022*/
			if (!empty($_POST['created_on'])) {
				$created_on = $_POST['created_on'];
				$this->db->where("s.created_on = '$created_on'");
			}
			if (!empty($_POST['exam_name'])) {
				$exam_name = $_POST['exam_name'];
				$this->db->where("s.exam_name = '$exam_name'");
			}
			if (!empty($_POST['from_date'] && $_POST['to_date'])) {
				$from_date = $_POST['from_date'];
				$to_date = $_POST['to_date'];
				$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date'");
			}
			/*LISTING CONDITION ON APPLIED FILTERS END 01-12-2022*/

			$this->db->select('*');
			$this->db->group_by('s.scribe_uid');
			$this->db->join('qualification', 'qualification.qid = s.specify_qualification');
			$this->db->where("s.mobile_scribe != 0");
			$this->db->order_by('scribe_uid', 'DESC');
			$scribe_show = $this->master_model->getRecords('scribe_registration s');

			if (!empty($scribe_show)) {
				$i = 1;
				foreach ($scribe_show as $record) {
					if ($record['scribe_approve'] == 1) {
						$status = 'APPROVED';
					} elseif ($record['scribe_approve'] == 2) {
						$status = 'PENDING';
					} elseif ($record['scribe_approve'] == 3) {
						if ($record['remark'] == 3) {
							$status = 'CANCELED';
						} else {
							$status = 'REJECTED';
						}
					} elseif ($record['scribe_approve'] == 0) {
						$status = 'NEW';
					} else {
						$status  = '-';
					}
					$regnumber = $record['regnumber'];
					$exam_code = $record['exam_code'];
					$subject_code = $record['subject_code'];
					$exam_date = $record['exam_date'];

					$this->db->select('vendor_code');
					$this->db->where('mem_mem_no', $regnumber);
					$this->db->where('exm_cd', $exam_code);
					$this->db->where('sub_cd', $subject_code);
					$this->db->where('exam_date', $exam_date);
					$this->db->group_by('vendor_code');
					$vendor_code = $this->master_model->getRecords('admit_card_details');
					print_r($vendor_code);
					die;

					$csv .= $i . ',' . $record['scribe_uid'] . ',' . $record['regnumber'] . ',' . $record['firstname'] . ',' . $record['email'] . ',' . $record['mobile'] . ',' . $record['exam_name'] . ',' . $record['subject_name'] . ',"' . $record['center_name'] . '",' . $record['name_of_scribe'] . ',' . $record['mobile_scribe'] . ',' . $record['name'] . ',' . $record['emp_details_scribe'] . ',' . $record['photoid_no'] . ',' . $record['visually_impaired'] . ',' . $record['orthopedically_handicapped'] . ',' . $record['cerebral_palsy'] . ',' . $record['exam_date'] . ',' . $record['created_on'] . ',' . $status . "\n";
					$i++;
					//echo'<pre>';print_r($csv);die;
				}
			}
			$filename = "Scribe registration details.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
			die;
		}
		/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/
	}

	/*VIEW SCRIBE APPLIATION DETAILS POOJA MANE:11/08/2022*/
	public function approved_list()
	{
		$from_date = $to_date = '';
		$where = 'mobile_scribe != 0';
		//echo "approved";die;
		/*SEARCH BASED ON TWO DATES 16/08/2022*/

		if (isset($_POST['btnSearch1'])) {
			if (isset($_POST['from_date']) && $_POST['from_date'] != "") {
				$from_date = date("Y-m-d", strtotime($_POST['from_date']));
			} else {
				$from_date = '';
			}
			if (isset($_POST['to_date']) && $_POST['to_date'] != "") {
				$to_date = date("Y-m-d", strtotime($_POST['to_date']));
			} else {
				$to_date = '';
			}


			if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
			{
				$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date' AND mobile_scribe != 0");
				$this->db->order_by('id', 'DESC');
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '1'));
				//echo $this->db->last_query();//die;
			} else {
				$this->db->where("mobile_scribe != 0");
				$this->db->order_by('id', 'DESC');
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '1'));
				//echo $this->db->last_query();die;
			}
		} else {
			/*WHOLE LISITING */
			$this->db->where("mobile_scribe != 0");
			$this->db->order_by('id', 'DESC');
			$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '1'));
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['scribe_show'] = $scribe_show;
			//$this->load->view('scribe_dashboard/approved_rejected_list',$data);
		}
		/*END SEARCH BASED ON TWO DATES 16/08/2022*/
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['scribe_show'] = $scribe_show;
		$this->load->view('scribe_dashboard/approved_rejected_list', $data);

		//$where .= "scribe_approve = 1";
		//echo $this->db->last_query();die;
		//print_r($scribe_show);die;


		/*DOWNLOAD BASED ON TWO DATES 16/08/2022*/
		if (isset($_POST['download1'])) {

			$csv = "Scribe Approved details \n\n";
			$csv .= "Sr.No, URN, Member no,  Member Name,Email,Mobile,Exam Name,Subject,  Center Name, Scribe Name, Scribe Mobile no,Educational qualification,Employment details,Photo ID Number,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date , Created date\n"; //Column headers


			if (isset($_POST['from_date']) && $_POST['from_date'] != "") {
				$from_date = date("Y-m-d", strtotime($_POST['from_date']));
			} else {
				$from_date = '';
			}
			if (isset($_POST['to_date']) && $_POST['to_date'] != "") {
				$to_date = date("Y-m-d", strtotime($_POST['to_date']));
			} else {
				$to_date = '';
			}
			/*print_r($from_date);
				print_r($to_date);
				die;*/
			$this->db->select('*');
			$this->db->group_by('s.scribe_uid');
			$this->db->join('qualification', 'qualification.qid = s.specify_qualification');

			if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
			{
				/*$where = "s.created_on BETWEEN '".$from_date."' AND '".$to_date."' AND scribe_approve = 1 ";*/
				$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date'");
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '1'));
			} else {
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '1'));
			}
			//echo $this->db->last_query();die;
			//print_r($scribe_show);die;
			if (!empty($scribe_show)) {
				$i = 1;
				foreach ($scribe_show as $record) {
					// print_r($record);exit;
					$csv .= $i . ',' . $record['scribe_uid'] . ',' . $record['regnumber'] . ',' . $record['firstname'] . ',' . $record['email'] . ',' . $record['mobile'] . ',' . $record['exam_name'] . ',' . $record['subject_name'] . ',"' . $record['center_name'] . '",' . $record['name_of_scribe'] . ',' . $record['mobile_scribe'] . ',' . $record['name'] . ',' . $record['emp_details_scribe'] . ',' . $record['photoid_no'] . ',' . $record['visually_impaired'] . ',' . $record['orthopedically_handicapped'] . ',' . $record['cerebral_palsy'] . ',' . $record['exam_date'] . ',' . $record['created_on'] . "\n";
					$i++;
					//print_r($csv);die;
				}
			}
			$filename = "Scribe Approved details.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
			die;
		}
		/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/
	}

	/*VIEW SCRIBE APPLIATION DETAILS POOJA MANE:11/08/2022*/
	public function rejected_list()
	{
		$from_date = $to_date = '';
		//echo "reject";die;
		/*SEARCH BASED ON TWO DATES 16/08/2022*/
		$where = '';
		if (isset($_POST['btnSearch2'])) {
			if (isset($_POST['from_date']) && $_POST['from_date'] != "") {
				$from_date = date("Y-m-d", strtotime($_POST['from_date']));
			} else {
				$from_date = '';
			}
			if (isset($_POST['to_date']) && $_POST['to_date'] != "") {
				$to_date = date("Y-m-d", strtotime($_POST['to_date']));
			} else {
				$to_date = '';
			}


			if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
			{
				$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date' AND mobile_scribe != 0");
				$this->db->order_by('id', 'DESC');
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '3'));
			} else {
				$this->db->where("mobile_scribe != 0");
				$this->db->order_by('id', 'DESC');
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '3'));
			}
		}
		/*END SEARCH BASED ON TWO DATES 16/08/2022*/ else {
			/*WHOLE LISITING */
			$this->db->where("mobile_scribe != 0");
			$this->db->order_by('id', 'DESC');
			$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '3'));
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['scribe_show'] = $scribe_show;
		}
		//echo $this->db->last_query();die;	

		//$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '3'));
		//print_r($scribe_show);die;
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['scribe_show'] = $scribe_show;
		$this->load->view('scribe_dashboard/approved_rejected_list', $data);

		/*DOWNLOAD BASED ON TWO DATES 16/08/2022*/
		if (isset($_POST['download2'])) {

			$csv = "Scribe Rejected details \n\n";
			$csv .= "Sr.No, URN, Member no,  Member Name,Email,Mobile,Exam Name,Subject,  Center Name, Scribe Name, Scribe Mobile no,Educational qualification,Employment details,Photo ID Number,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date , Created date\n"; //Column headers

			if (isset($_POST['from_date']) && $_POST['from_date'] != "") {
				$from_date = date("Y-m-d", strtotime($_POST['from_date']));
			} else {
				$from_date = '';
			}
			if (isset($_POST['to_date']) && $_POST['to_date'] != "") {
				$to_date = date("Y-m-d", strtotime($_POST['to_date']));
			} else {
				$to_date = '';
			}
			/*print_r($from_date);
				print_r($to_date);
				die;*/
			$this->db->select('*');
			$this->db->group_by('s.scribe_uid');
			$this->db->join('qualification', 'qualification.qid = s.specify_qualification');

			if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
			{
				/*$where = "s.created_on BETWEEN '".$from_date."' AND '".$to_date."' AND scribe_approve = 1 ";*/
				$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date' AND mobile_scribe != 0");
				$this->db->group_by('s.id');
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '3'));
			} else {
				$this->db->where("mobile_scribe != 0");
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '3'));
			}
			/*echo $this->db->last_query();//die;	
			    print_r($scribe_show);die;*/
			if (!empty($scribe_show)) {
				$i = 1;
				foreach ($scribe_show as $record) {
					// print_r($record);exit;
					$csv .= $i . ',' . $record['scribe_uid'] . ',' . $record['regnumber'] . ',' . $record['firstname'] . ',' . $record['email'] . ',' . $record['mobile'] . ',' . $record['exam_name'] . ',' . $record['subject_name'] . ',"' . $record['center_name'] . '",' . $record['name_of_scribe'] . ',' . $record['mobile_scribe'] . ',' . $record['name'] . ',' . $record['emp_details_scribe'] . ',' . $record['photoid_no'] . ',' . $record['visually_impaired'] . ',' . $record['orthopedically_handicapped'] . ',' . $record['cerebral_palsy'] . ',' . $record['exam_date'] . ',' . $record['created_on'] . "\n";
					$i++;
					//print_r($csv);die;
				}
			}
			$filename = "Scribe Rejected details.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
			die;
		}
		/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/
	}


	/*VIEW SCRIBE APPLIATION DETAILS POOJA MANE:04/08/2022*/
	public function view($id)
	{
		//echo "string";die;
		$id = $this->uri->segment(4);
		$name = $this->db->query("SELECT CONCAT(firstname,' ', lastname) AS name FROM scribe_registration where 'id' = $id");
		$data['fullname'] = $name->result_array();

		$res_arr = $this->master_model->getRecords("scribe_registration", array('id' => $id));
		$specify_qualification = $res_arr[0]['specify_qualification'];

		$this->db->select('name as education');
		$this->db->join('scribe_registration s', 's.specify_qualification = q.qid');
		$this->db->group_by('name');
		$data['specify_qualification'] = $specify_qualification = $this->master_model->getRecords('qualification q', array('s.id' => $id));
		//print_r($specify_qualification);die;

		//get Reason from reason table
		$scribe_uid = $res_arr[0]['scribe_uid'];
		$this->db->select('reason_description');
		$reasons = $this->master_model->getRecords("rejection_reasons", array('scribe_uid' => $scribe_uid));
		//print_r($reasons);die;
		$data['reasons'] = $reasons;
		$data['reuest_list'] = $res_arr;
		//print_r($data['fullname'])	;die;	
		$this->load->view('scribe_dashboard/view_scribe', $data);
	}

	/*EDIT SCRIBE DETAILS Pooja Mane : 03/11/2022*/
	public function update_details()
	{
		//GET POSTED VALUES
		$email = $_POST['email'];
		$scribe_uid = $_POST['scribe_uid'];

		//if mail not empty then update
		if (!empty($_POST['email'] && $_POST['scribe_uid'])) {
			$arr_update = array('email' => $email);
			$arr_where = array("scribe_uid" => $scribe_uid);
			$result = $this->master_model->updateRecord('scribe_registration', $arr_update, $arr_where);
			//$this->session->set_flashdata('success','Email Updated');
			if ($result > 0) {
				$this->session->set_flashdata('success', 'Email Updated');
				echo $email;
				//return $email;
			} else {
				$this->session->set_flashdata('error', 'Something went wrong');
				return false;
			}
		} else {
			$this->session->set_flashdata('error', 'Something went wrong');
			return false;
		}
	}
	/*EDIT SCRIBE DETAILS END Pooja Mane : 03/11/2022*/

	/*APPROVE SCRIBE APPLICATION POOJA MANE: 08/08/2022 */
	public function approve()
	{

		//$reason = $this->input->post('reject_reason');
		//$id = $this->input->post('id');
		$id = $this->uri->segment('4');
		//print_r($id);die;
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			$arr_data = $this->master_model->getRecords('scribe_registration');
			/*print_r($arr_data);
	            echo "<br>";
	            print_r($arr_data[0]['mobile_scribe']);*/
			//die;
			if (empty($arr_data)) {
				$this->session->set_flashdata('error', 'Invalid Selection Of Record');
				redirect(base_url() . 'scribe_dashboard/Scribe_list');
			}

			$arr_update = array('scribe_approve' => '1');

			$arr_where = array("id" => $id);
			$result = $this->master_model->updateRecord('scribe_registration', $arr_update, $arr_where);

			if ($result > 0) {
				$this->session->set_flashdata('success', 'Application Approved Successfully.');

				/*APPROVE SCRIBE APPLICATION MAIL POOJA MANE: 10/08/2022 */
				$regnumber = $arr_data[0]['regnumber'];
				$exam_code = $arr_data[0]['exam_code'];
				$scribe_info = $this->master_model->getRecords('scribe_registration', array('regnumber' => $regnumber, 'exam_code' => $exam_code, 'scribe_approve' => '1', "id" => $id));
				//
				//echo '<pre>';print_r($scribe_info);die;
				$name = $scribe_info[0]['firstname'];
				$exam_name = $scribe_info[0]['exam_name'];
				$exam_date = $scribe_info[0]['exam_date'];
				$scribe_uid = $scribe_info[0]['scribe_uid'];
				//$scribe_name = $scribe_info[0]['scribe_name'];
				$subject_name = $scribe_info[0]['subject_name'];
				$center_name = $scribe_info[0]['center_name'];
				$email = $scribe_info[0]['email'];
				$name_of_scribe = $scribe_info[0]['name_of_scribe'];
				$mobile_scribe = $scribe_info[0]['mobile_scribe'];
				$applied_date = $scribe_info[0]['created_on'];
				$photoid_no = $scribe_info[0]['photoid_no'];
				$created_on = $scribe_info[0]['created_on'];
				$date = date("Y-m-d", strtotime($applied_date));
				$final_str = '';
				$today = date('Y-m-d H:i:s');
				if ($scribe_info[0]['special_assistance']) {
					$special = "Special Assistance/Extra Time ";
				} else {
					$special = "";
				}

				if ($scribe_info[0]['extra_time']) {
					$extra = " Extra Time";
				} else {
					$extra = "";
				}

				if ($special && $extra == "") {
					$scribe = "Scribe ";
				} else {
					$scribe = "";
				}

				//print_r($today);die;

				if (!empty($scribe_info)) {

					if (!$mobile_scribe) {
						/*SPEIAL APPROVAL EMAIL FORMAT*/
						$final_str .= 'Dear ' . $name . ',';
						$final_str .= '<br/><br/>';
						$final_str .= 'With reference to your application number : ' . $scribe_uid . ',';
						$final_str .= '<br/>';
						$final_str .= 'You have been granted permission for Special Assistance/Extra time.';
						$final_str .= '<br/>';
						$final_str .= 'Exam Date: ' . $exam_date;
						$final_str .= '<br/>';
						$final_str .= 'Subject Name: ' . $subject_name;
						$final_str .= '<br/><br/>';
						$final_str .= 'You are requested to carry and produce the following documents at the Examination Venue without fail: ';
						$final_str .= '<br/><br/>';
						$final_str .= '1)Printout of this e-mail.';
						$final_str .= '<br/>';
						$final_str .= '2)Admit Letter issued by the Institute for the Examination';
						$final_str .= '<br/>';
						$final_str .= '3)Attested copy of disability certificate.';
						$final_str .= '<br/><br/>';
						$final_str .= 'You are required to be present along with the Scribe at least 30 minutes prior to the start of the examination and submit these documents to the Centre Authorities.';
						$final_str .= '<br/><br/>';
						$final_str .= 'Thanks and Regards,';
						$final_str .= '<br/>';
						$final_str .= 'Indian Institute of Banking & Finance';
						/*SPEIAL APPROVAL EMAIL FORMAT END*/

						$info_arr = array(
							//'to'=>'pooja.mane@esds.co.in',
							//'to'=>'harshu.joy26@gmail.com',
							'to' => $email,
							'from' => 'noreply@iibf.org.in',
							'subject' => 'Your application for special assistance/extra time dated on ' . $date,
							'message' => $final_str
						);
					} else {
						/*SCRIBE APPROVAL EMAIL FORMAT*/
						$final_str .= 'Dear ' . $name . ',';
						$final_str .= '<br/><br/>';
						$final_str .= 'With reference to your application number :' . $scribe_uid . ',';
						$final_str .= '<br/>';
						$final_str .= 'You have been granted permission for Scribe based on the scanned copy of declaration and disability certificate submitted by you for the following date and subject:';
						$final_str .= '<br/>';
						$final_str .= 'Exam Date:' . $exam_date;
						$final_str .= '<br/>';
						$final_str .= 'Subject Name:' . $subject_name;
						$final_str .= '<br/><br/>';
						$final_str .= 'Your scribe details are given below:';
						$final_str .= '<br/><br/>';
						$final_str .= 'Scribe Name:' . $name_of_scribe;
						$final_str .= '<br/>';
						$final_str .= 'Scribe ID proof:' . $photoid_no;
						$final_str .= '<br/><br/>';
						$final_str .= 'You are requested to carry and produce the following documents at the Examination Venue without fail:';
						$final_str .= '<br/><br/>';
						$final_str .= '1)Printout of this e-mail permitting you to use the Scribe';
						$final_str .= '<br/>';
						$final_str .= '2)Admit Letter issued by the Institute for the Examination';
						$final_str .= '<br/>';
						$final_str .= '3)Original declaration form along with attested copy of disability certificate';
						$final_str .= '<br/>';
						$final_str .= '4) Original ID Proof of the Scribe';
						$final_str .= '<br/><br/>';
						$final_str .= 'You are required to be present along with the Scribe at least 30 minutes prior to the start of the examination and submit these documents to the Centre Authorities.';
						$final_str .= '<br/><br/>';
						$final_str .= 'Thanks and Regards,';
						$final_str .= '<br/>';
						$final_str .= 'Indian Institute of Banking & Finance';

						$info_arr = array(
							//'to'=>'pooja.mane@esds.co.in',
							//'to'=>'harshu.joy26@gmail.com',
							'to' => $email,
							'from' => 'noreply@iibf.org.in',
							'subject' => 'Your application for scribe dated on ' . $date,
							'message' => $final_str
						);
					}

					// print_r($final_str);die;

					if ($this->Emailsending->mailsend_attch($info_arr, '')) {
						$update_data  = array(
							'email_flag' => '1', //Email flag= 2 on approval 
							'remark' => '1',
							'modified_on' => $today
						);

						$this->master_model->updateRecord(
							'scribe_registration',
							$update_data,
							array(
								'regnumber' => $regnumber,
								'exam_code' => $exam_code,
								'remark' => '1',
								'scribe_approve' => '1',
								'id' => $id
							)
						);
						//echo $this->db->last_query();die;
					}
				}
			} else {
				$this->session->set_flashdata('error', 'Oops,Something Went Wrong While Approving Application.');
			}
			//print_r($final_str);print_r($mobile_scribe);die;die;
		} else {
			$this->session->set_flashdata('error', 'Invalid Selection Of Record');
		}
		//echo "string";

		if ($arr_data[0]['mobile_scribe'] == '0') {
			//print_r($arr_data[0]['mobile_scribe']);die;
			redirect(base_url() . 'scribe_dashboard/Scribe_list/special');
		} else {
			//print_r($arr_data[0]['mobile_scribe']);die;
			redirect(base_url() . 'scribe_dashboard/Scribe_list/Scribe');
		}
	}

	/*REJECT SCRIBE APPLICATION POOJA MANE: 08/08/2022 */
	public function reject()
	{
		//echo "reject";//die;
		//Get Rejection Details
		$reject_reason = $this->input->post('reject_reason');
		$id = $this->input->post('id');
		$reason1 = $this->input->post('reason1');
		$reason2 = $this->input->post('reason2');
		$reason3 = $this->input->post('reason3');
		//print_r($_POST);die;
		//print_r($reject_reason);echo "string2";die;
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			$arr_data = $this->master_model->getRecords('scribe_registration');

			if (empty($arr_data)) {
				$this->session->set_flashdata('error', 'Invalid Selection Of Record');
				redirect(base_url() . 'scribe_dashboard/Scribe_list');
			}
			$arr_update = array('scribe_approve' => '3');

			$arr_where = array("id" => $id);
			$result = $this->master_model->updateRecord('scribe_registration', $arr_update, $arr_where);


			/*REJECT SCRIBE APPLICATION MAIL POOJA MANE: 10/08/2022 */
			if ($result > 0) {
				$this->session->set_flashdata('success', 'Application Rejected Successfully.');

				$regnumber = $arr_data[0]['regnumber'];
				$exam_code = $arr_data[0]['exam_code'];
				$scribe_info = $this->master_model->getRecords('scribe_registration', array('regnumber' => $regnumber, 'exam_code' => $exam_code, 'scribe_approve' => '3', "id" => $id));

				$scribe_uid = $scribe_info[0]['scribe_uid'];
				$name = $scribe_info[0]['firstname'];
				$exam_name = $scribe_info[0]['exam_name'];
				$subject_name = $scribe_info[0]['subject_name'];
				$exam_date = $scribe_info[0]['exam_date'];
				$center_name = $scribe_info[0]['center_name'];
				$email = $scribe_info[0]['email'];
				$name_of_scribe = $scribe_info[0]['name_of_scribe'];
				$mobile_scribe = $scribe_info[0]['mobile_scribe'];
				$applied_date = $scribe_info[0]['created_on'];
				$date = date("Y-m-d", strtotime($applied_date));
				$final_str = '';
				$today = date('Y-m-d H:i:s');
				$reason = $reject_reason;
				if ($scribe_info[0]['special_assistance']) {
					$special = "Special Assistance/Extra Time ";
				} else {
					$special = "";
				}
				if ($scribe_info[0]['extra_time']) {
					$extra = "Extra Time";
				} else {
					$extra = "";
				}
				if ($special && $extra == "") {
					$scribe = "Scribe ";
				} else {
					$scribe = "";
				}

				//print_r($today);die;

				if (!empty($scribe_info)) {

					if ($mobile_scribe) {
						/*********SCRIBE REJECTION  MAIL FORMAT*********/
						$final_str .= 'Dear ' . $name . ',';
						$final_str .= '<br/><br/>';
						$final_str .= 'With reference to your application number : ' . $scribe_uid . ',';
						$final_str .= '<br/>';
						$final_str .= 'Exam Date: ' . $exam_date;
						$final_str .= '<br/>';
						$final_str .= 'Subject Name: ' . $subject_name;
						$final_str .= '<br/><br/>';
						$final_str .= 'You have not been granted permission for Scribe because of the following reasons: ';
						$final_str .= '<br/>';
						$final_str .= '<ol>';
						if ($reason1) {
							$final_str .= '<li>' . $reason1 . '</li>';
							$final_str .= '<br/>';
						}
						if ($reason2) {
							$final_str .= '<li>' . $reason2 . '</li>';
							$final_str .= '<br/>';
						}
						if ($reason3) {
							$final_str .= '<li>' . $reason3 . '</li>';
							$final_str .= '<br/>';
						}
						if ($reason) {
							$final_str .= '<li> Any Other reason :' . $reason . '</li>';
							$final_str .= '<br/>';
						};
						$final_str .= '</ol>';
						$final_str .= 'Please apply again or contact MSS department.';
						$final_str .= '<br/><br/>';
						$final_str .= 'Thanks and Regards,';
						$final_str .= '<br/>';
						$final_str .= 'Indian Institute of Banking & Finance';

						$info_arr = array(
							'to' => $email,
							//'to'=>'harshu.joy26@gmail.com', 
							//'to'=>'pooja.mane@esds.co.in', 
							'from' => 'noreply@iibf.org.in',
							'subject' => 'Your application for scribe dated on ' . $date,
							'message' => $final_str
						);
					} else {
						/*********SPECIAL REJECTION  MAIL FORMAT*********/
						$final_str .= 'Dear ' . $name . ',';
						$final_str .= '<br/><br/>';
						$final_str .= 'With reference to your application number : ' . $scribe_uid . ',';
						$final_str .= '<br/>';
						$final_str .= 'Exam Date: ' . $exam_date;
						$final_str .= '<br/>';
						$final_str .= 'Subject Name: ' . $subject_name;
						$final_str .= '<br/><br/>';
						$final_str .= 'You have not been granted permission for Special Assistance/Extra Time because of the following reasons: ';
						$final_str .= '<ol>';
						if ($reason1) {
							$final_str .= '<li>' . $reason1 . '</li>';
						}
						if ($reason) {
							$final_str .= '<li> Any Other reason : ' . $reason . '</li>';
							$final_str .= '<br/>';
						};
						$final_str .= '</ol>';
						$final_str .= 'Please apply again or contact MSS department.';
						$final_str .= '<br/><br/>';
						$final_str .= 'Thanks and Regards,';
						$final_str .= '<br/>';
						$final_str .= 'Indian Institute of Banking & Finance';

						$info_arr = array(
							'to' => $email,
							//'to'=>'harshu.joy26@gmail.com', 
							//'to'=>'pooja.mane@esds.co.in', 
							'from' => 'noreply@iibf.org.in',
							'subject' => 'Your application for special Assistance/extra time  dated on ' . $date,
							'message' => $final_str
						);
					}
					//print_r($final_str) ;die;
					if ($reason1) {
						$insert_info = array(
							'scribe_uid' => $scribe_uid,
							'reason_description' => str_replace('\"', '"', $reason1)
						);
						$add_reason = $this->master_model->insertRecord('rejection_reasons', $insert_info);
					}
					if ($reason2) {
						$insert_info = array(
							'scribe_uid' => $scribe_uid,
							'reason_description' => str_replace('\"', '"', $reason2)
						);
						$add_reason = $this->master_model->insertRecord('rejection_reasons', $insert_info);
					}
					if ($reason3) {
						$insert_info = array(
							'scribe_uid' => $scribe_uid,
							'reason_description' => str_replace('\"', '"', $reason3)
						);
						$add_reason = $this->master_model->insertRecord('rejection_reasons', $insert_info);
					}
					if ($reject_reason) {
						$insert_info = array(
							'scribe_uid' => $scribe_uid,
							'reason_description' => str_replace('\"', '"', $reject_reason)
						);
						$add_reason = $this->master_model->insertRecord('rejection_reasons', $insert_info);
					}



					if ($this->Emailsending->mailsend_attch($info_arr, '')) {
						$update_data  = array(
							'email_flag' => '1' //Email flag= 1 on rejection of application.

						);

						$this->master_model->updateRecord(
							'scribe_registration',
							$update_data,
							array(
								'regnumber' => $regnumber,
								'exam_code' => $exam_code,
								'remark' => '1',
								'scribe_approve' => '3'
							)
						);

						$arr_update = array('modified_on' => $today, 'remark' => '0');

						$arr_where = array("id" => $id);
						$this->master_model->updateRecord('scribe_registration', $arr_update, $arr_where);
					}
				}
			} else {
				$this->session->set_flashdata('error', 'Oops,Something Went Wrong While rejecting Application.');
			}
		} else {
			$this->session->set_flashdata('error', 'Invalid Selection Of Record');
		}
		if ($arr_data[0]['mobile_scribe'] == '0') {
			//print_r($arr_data[0]['mobile_scribe']);die;
			redirect(base_url() . 'scribe_dashboard/Scribe_list/special');
		} else {
			//print_r($arr_data[0]['mobile_scribe']);die;
			redirect(base_url() . 'scribe_dashboard/Scribe_list/Scribe');
		}
	}

	/*SEARCH SCRIBE LIST*/
	public function Search()
	{
		$from_date = $to_date = '';
		if (isset($_POST['from_date']) && $_POST['from_date'] != "") {
			$from_date = date("Y-m-d", strtotime($_POST['from_date']));
		} else {
			$from_date = '';
		}

		if (isset($_POST['to_date']) && $_POST['to_date'] != "") {
			$to_date = date("Y-m-d", strtotime($_POST['to_date']));
		} else {
			$to_date = '';
		}
	}

	/*SPECIAL ASSISTANCE DASHBOARD FUNCTIONS*/
	/*VIEW  SPECIAL SCRIBE APPROVED LIST POOJA MANE:28/08/2022*/
	public function special()
	{

		$from_date = $to_date = $created_on = $exam_name = '';

		/*LISTING CONDITIONS OF APPLIED FILTERS 01-12-2022*/
		if (isset($_POST['btnSearch'])) {

			if (!empty($_POST['created_on'])) {
				$created_on = $_POST['created_on'];
				$this->db->where("s.created_on = '$created_on'");
			}
			if (!empty($_POST['exam_name'])) {
				$exam_name = $_POST['exam_name'];
				$this->db->where("s.exam_name = '$exam_name'");
			}
			if (!empty($_POST['from_date'] && $_POST['to_date'])) {
				$from_date = $_POST['from_date'];
				$to_date = $_POST['to_date'];
				$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date'");
			}
		}/*LISTING CONDITION OF APPLIED FILTERS END 01-12-2022*/

		/*WHOLE LISITING */
		$this->db->where("s.mobile_scribe = 0");
		$this->db->group_by('s.scribe_uid');
		$this->db->order_by('id', 'DESC');
		$scribe_show = $this->master_model->getRecords('scribe_registration s');

		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['scribe_show'] = $scribe_show;
		$this->load->view('scribe_dashboard/specialList', $data);

		/*DOWNLOAD BASED ON TWO DATES 16/08/2022*/
		if (isset($_POST['download'])) {
			//echo "string";die;
			$csv = "Special registration details\n\n";
			$csv .= "Sr.No, URN, Member no, Member Name,Email,Mobile,Exam Name,Subject, Center Name,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date ,Special Assistance, Extra Time, Created date, Description, Status\n"; //Column headers

			/*LISTING CONDITION ON APPLIED FILTERS 01-12-2022*/
			if (!empty($_POST['created_on'])) {
				$created_on = $_POST['created_on'];
				$this->db->where("s.created_on = '$created_on'");
			}
			if (!empty($_POST['exam_name'])) {
				$exam_name = $_POST['exam_name'];
				$this->db->where("s.exam_name = '$exam_name'");
			}
			if (!empty($_POST['from_date'] && $_POST['to_date'])) {
				$from_date = $_POST['from_date'];
				$to_date = $_POST['to_date'];
				$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date'");
			}
			/*LISTING CONDITION ON APPLIED FILTERS END 01-12-2022*/

			$this->db->select('*');
			$this->db->group_by('s.scribe_uid');
			$this->db->where("s.mobile_scribe = 0");
			$this->db->order_by('scribe_uid', 'DESC');
			$scribe_show = $this->master_model->getRecords('scribe_registration s');
			//echo $this->db->last_query();die;
			if (!empty($scribe_show)) {
				$i = 1;
				foreach ($scribe_show as $record) {
					if ($record['scribe_approve'] == 1) {
						$status = 'APPROVED';
					} elseif ($record['scribe_approve'] == 2) {
						$status = 'PENDING';
					} elseif ($record['scribe_approve'] == 3) {
						if ($record['remark'] == 3) {
							$status = 'CANCELED';
						} else {
							$status = 'REJECTED';
						}
					} elseif ($record['scribe_approve'] == 0) {
						$status = 'NEW';
					} else {
						$status  = '-';
					}

					$csv .= $i . ',' . $record['scribe_uid'] . ',' . $record['regnumber'] . ',' . $record['firstname'] . ',' . $record['email'] . ',' . $record['mobile'] . ',' . $record['exam_name'] . ',' . $record['subject_name'] . ',"' . $record['center_name'] . '",' . $record['visually_impaired'] . ',' . $record['orthopedically_handicapped'] . ',' . $record['cerebral_palsy'] . ',' . $record['exam_date'] . ',' . $record['special_assistance'] . ',' . $record['extra_time'] . ',' . $record['created_on'] . ',' . $status . ',' . $record['description'] . "\n";
					$i++;
				}
			}
			$filename = "Special registration details.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
			die;
		}
		/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/
	}

	/*VIEW  SPECIAL SCRIBE APPROVED LIST POOJA MANE:29/08/2022*/
	public function special_approved_list()
	{
		$from_date = $to_date = '';
		$where = '';
		$where .= "`mobile_scribe` = '0'";
		$where .= " AND `scribe_approve` = '1'";
		//echo "approved";die;
		/*SEARCH BASED ON TWO DATES 29/08/2022*/

		if (isset($_POST['btnSearch1'])) {
			if (isset($_POST['from_date']) && $_POST['from_date'] != "") {
				$from_date = date("Y-m-d", strtotime($_POST['from_date']));
			} else {
				$from_date = '';
			}
			if (isset($_POST['to_date']) && $_POST['to_date'] != "") {
				$to_date = date("Y-m-d", strtotime($_POST['to_date']));
			} else {
				$to_date = '';
			}


			if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
			{
				$where .= "AND s.created_on BETWEEN '" . $from_date . "' AND '" . $to_date . "' ";
				$this->db->order_by('id', 'DESC');
				$scribe_show = $this->master_model->getRecords('scribe_registration s', $where);
				//echo $this->db->last_query();//die;
			} else {
				$this->db->order_by('id', 'DESC');
				$scribe_show = $this->master_model->getRecords('scribe_registration s', $where);
				//echo $this->db->last_query();die;
			}
			//echo $this->db->last_query();die;
		} else {
			$this->db->order_by('id', 'DESC');
			$scribe_show = $this->master_model->getRecords('scribe_registration s', $where);
		}
		/*END SEARCH BASED ON TWO DATES 29/08/2022*/

		/*WHOLE LISITING */
		//$where .= "scribe_approve = 1";
		//echo $this->db->last_query();die;
		//print_r($scribe_show);die;
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['scribe_show'] = $scribe_show;
		$this->load->view('scribe_dashboard/special_approved_rejected', $data);

		/*DOWNLOAD BASED ON TWO DATES 29/08/2022*/
		if (isset($_POST['download1'])) {

			$csv = "Special Approved details \n\n";
			$csv .= "Sr.No, URN, Member no,  Member Name,Email,Mobile,Exam Name,Subject,  Center Name,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date ,Special Assistance, Extra Time, Created date, Description\n"; //Column headers

			if (isset($_POST['from_date']) && $_POST['from_date'] != "") {
				$from_date = date("Y-m-d", strtotime($_POST['from_date']));
			} else {
				$from_date = '';
			}
			if (isset($_POST['to_date']) && $_POST['to_date'] != "") {
				$to_date = date("Y-m-d", strtotime($_POST['to_date']));
			} else {
				$to_date = '';
			}
			/*print_r($from_date);
				print_r($to_date);
				die;*/
			$this->db->select('*');
			$this->db->group_by('s.scribe_uid');
			//$this->db->join('qualification','qualification.qid = s.specify_qualification');

			if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
			{
				/*$where = "s.created_on BETWEEN '".$from_date."' AND '".$to_date."' AND scribe_approve = 1 ";*/
				$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date' AND mobile_scribe = 0");
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '1'));
			} else {
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '1', 'mobile_scribe' => '0'));
			}
			//echo $this->db->last_query();
			//print_r($scribe_show);die;
			if (!empty($scribe_show)) {
				$i = 1;
				foreach ($scribe_show as $record) {
					// print_r($record);exit;
					$csv .= $i . ',' . $record['scribe_uid'] . ',' . $record['regnumber'] . ',' . $record['firstname'] . ',' . $record['email'] . ',' . $record['mobile'] . ',' . $record['exam_name'] . ',' . $record['subject_name'] . ',"' . $record['center_name'] . '",' . $record['visually_impaired'] . ',' . $record['orthopedically_handicapped'] . ',' . $record['cerebral_palsy'] . ',' . $record['exam_date'] . ',' . $record['special_assistance'] . ',' . $record['extra_time'] . ',' . $record['created_on'] . ',' . $record['description'] . "\n";
					$i++;
				}
			}
			$filename = "Special Approved details.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
			die;
		}
		/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/
	}
	/*VIEW  SPECIAL SCRIBE REJECTED LIST POOJA MANE:30/08/2022*/
	public function special_rejected_list()
	{
		$from_date = $to_date = '';
		$where = '';
		$where .= "`mobile_scribe` = '0'";
		$where .= " AND `scribe_approve` = '3'";
		//echo "approved";die;
		/*SEARCH BASED ON TWO DATES 29/08/2022*/

		if (isset($_POST['btnSearch2'])) {
			//echo "string";die;
			if (isset($_POST['from_date']) && $_POST['from_date'] != "") {
				$from_date = date("Y-m-d", strtotime($_POST['from_date']));
			} else {
				$from_date = '';
			}
			if (isset($_POST['to_date']) && $_POST['to_date'] != "") {
				$to_date = date("Y-m-d", strtotime($_POST['to_date']));
			} else {
				$to_date = '';
			}


			if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
			{
				$where .= "AND s.created_on BETWEEN '" . $from_date . "' AND '" . $to_date . "' ";
				$this->db->order_by('id', 'DESC');
				$scribe_show = $this->master_model->getRecords('scribe_registration s', $where);
				//echo'date';echo $this->db->last_query();//die;
			} else {
				$this->db->order_by('id', 'DESC');
				$scribe_show = $this->master_model->getRecords('scribe_registration s', $where);
				//echo'nodate';echo $this->db->last_query();die;
			}
			//echo $this->db->last_query();die;
		} else {
			//echo'all';
			$this->db->order_by('id', 'DESC');
			$scribe_show = $this->master_model->getRecords('scribe_registration s', $where);
		}
		/*END SEARCH BASED ON TWO DATES 29/08/2022*/

		/*WHOLE LISITING */
		//$where .= "scribe_approve = 1";
		//echo $this->db->last_query();die;
		//print_r($scribe_show);die;
		$data['from_date'] = $from_date;
		$data['to_date'] = $to_date;
		$data['scribe_show'] = $scribe_show;
		$this->load->view('scribe_dashboard/special_approved_rejected', $data);

		/*DOWNLOAD BASED ON TWO DATES 29/08/2022*/
		if (isset($_POST['download2'])) {

			$csv = "Scribe Rejected details \n\n";
			$csv .= "Sr.No, URN, Member no,  Member Name,Email,Mobile,Exam Name,Subject,  Center Name,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date ,Special Assistance, Extra Time, Created date, Description\n"; //Column headers

			if (isset($_POST['from_date']) && $_POST['from_date'] != "") {
				$from_date = date("Y-m-d", strtotime($_POST['from_date']));
			} else {
				$from_date = '';
			}
			if (isset($_POST['to_date']) && $_POST['to_date'] != "") {
				$to_date = date("Y-m-d", strtotime($_POST['to_date']));
			} else {
				$to_date = '';
			}
			/*print_r($from_date);
				print_r($to_date);
				die;*/
			$this->db->select('*');
			$this->db->group_by('s.scribe_uid');
			//$this->db->join('qualification','qualification.qid = s.specify_qualification');

			if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
			{
				/*$where = "s.created_on BETWEEN '".$from_date."' AND '".$to_date."' AND scribe_approve = 1 ";*/
				$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date'");
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '3', 'mobile_scribe' => '0'));
			} else {
				$scribe_show = $this->master_model->getRecords('scribe_registration s', array('scribe_approve' => '3', 'mobile_scribe' => '0'));
			}
			//echo $this->db->last_query();die;
			//print_r($scribe_show);die;
			if (!empty($scribe_show)) {
				$i = 1;
				foreach ($scribe_show as $record) {
					// print_r($record);exit;
					$csv .= $i . ',' . $record['scribe_uid'] . ',' . $record['regnumber'] . ',' . $record['firstname'] . ',' . $record['email'] . ',' . $record['mobile'] . ',' . $record['exam_name'] . ',' . $record['subject_name'] . ',"' . $record['center_name'] . '",' . $record['visually_impaired'] . ',' . $record['orthopedically_handicapped'] . ',' . $record['cerebral_palsy'] . ',' . $record['exam_date'] . ',' . $record['special_assistance'] . ',' . $record['extra_time'] . ',' . $record['created_on'] . ',' . $record['description'] . "\n";
					$i++;
					//print_r($csv);die;
				}
			}
			$filename = "Special Rejected details.csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename=' . $filename);
			$csv_handler = fopen('php://output', 'w');
			fwrite($csv_handler, $csv);
			fclose($csv_handler);
			die;
		}
		/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/
	}

	/*MAIL SENDING*/
	public function approve_mail()
	{

		//$reason = $this->input->post('reject_reason');
		//$id = $this->input->post('id');
		$id = $this->uri->segment('4');
		//print_r($id);die;
		if (is_numeric($id)) {
			$this->db->where('id', $id);
			$arr_data = $this->master_model->getRecords('scribe_registration', array('scribe_approve' => '1'));

			if (empty($arr_data)) {
				$this->session->set_flashdata('error', 'Invalid Selection Of Record');
				redirect(base_url() . 'scribe_dashboard/Scribe_list');
			}

			if (!empty($arr_data)) {
				$this->session->set_flashdata('success', 'Approval Mail Sent Successfully');

				/*APPROVE SCRIBE APPLICATION MAIL POOJA MANE: 10/08/2022 */
				$regnumber = $arr_data[0]['regnumber'];
				$exam_code = $arr_data[0]['exam_code'];
				$scribe_info = $this->master_model->getRecords('scribe_registration', array('regnumber' => $regnumber, 'exam_code' => $exam_code, 'scribe_approve' => '1', "id" => $id));
				//echo '<pre>';print_r($scribe_info);die;
				$name = $scribe_info[0]['firstname'];
				$exam_name = $scribe_info[0]['exam_name'];
				$exam_date = $scribe_info[0]['exam_date'];
				$scribe_uid = $scribe_info[0]['scribe_uid'];
				//$scribe_name = $scribe_info[0]['scribe_name'];
				$subject_name = $scribe_info[0]['subject_name'];
				$center_name = $scribe_info[0]['center_name'];
				$email = $scribe_info[0]['email'];
				$name_of_scribe = $scribe_info[0]['name_of_scribe'];
				$mobile_scribe = $scribe_info[0]['mobile_scribe'];
				$applied_date = $scribe_info[0]['created_on'];
				$photoid_no = $scribe_info[0]['photoid_no'];
				$created_on = $scribe_info[0]['created_on'];
				$date = date("Y-m-d", strtotime($applied_date));
				$final_str = '';
				$email_flag = $scribe_info[0]['email_flag'];
				//print_r($email_flag);
				$email_flag += 1;
				$today = date('Y-m-d H:i:s');
				if ($scribe_info[0]['special_assistance']) {
					$special = "Special Assistance/Extra Time ";
				} else {
					$special = "";
				}

				if ($scribe_info[0]['extra_time']) {
					$extra = " Extra Time";
				} else {
					$extra = "";
				}

				if ($special && $extra == "") {
					$scribe = "Scribe ";
				} else {
					$scribe = "";
				}

				//print_r($today);die;

				if (!empty($scribe_info)) {

					if (!$mobile_scribe) {
						/*SPEIAL APPROVAL EMAIL FORMAT*/
						$final_str .= 'Dear ' . $name . ',';
						$final_str .= '<br/><br/>';
						$final_str .= 'With reference to your application number : ' . $scribe_uid . ',';
						$final_str .= '<br/>';
						$final_str .= 'You have been granted permission for Special Assistance/Extra time.';
						$final_str .= '<br/>';
						$final_str .= 'Exam Date: ' . $exam_date;
						$final_str .= '<br/>';
						$final_str .= 'Subject Name: ' . $subject_name;
						$final_str .= '<br/><br/>';
						$final_str .= 'You are requested to carry and produce the following documents at the Examination Venue without fail: ';
						$final_str .= '<br/><br/>';
						$final_str .= '1)Printout of this e-mail.';
						$final_str .= '<br/>';
						$final_str .= '2)Admit Letter issued by the Institute for the Examination';
						$final_str .= '<br/>';
						$final_str .= '3)Attested copy of disability certificate.';
						$final_str .= '<br/><br/>';
						$final_str .= 'You are required to be present along with the Scribe at least 30 minutes prior to the start of the examination and submit these documents to the Centre Authorities.';
						$final_str .= '<br/><br/>';
						$final_str .= 'Thanks and Regards,';
						$final_str .= '<br/>';
						$final_str .= 'Indian Institute of Banking & Finance';
						/*SPEIAL APPROVAL EMAIL FORMAT END*/

						$info_arr = array(
							//'to'=>'pooja.mane@esds.co.in',
							//'to'=>'harshu.joy26@gmail.com',
							'to' => $email,
							'from' => 'noreply@iibf.org.in',
							'subject' => 'Your application for special assistance/extra time dated on ' . $date,
							'message' => $final_str
						);
					} else {
						/*SCRIBE APPROVAL EMAIL FORMAT*/
						$final_str .= 'Dear ' . $name . ',';
						$final_str .= '<br/><br/>';
						$final_str .= 'With reference to your application number :' . $scribe_uid . ',';
						$final_str .= '<br/>';
						$final_str .= 'You have been granted permission for Scribe based on the scanned copy of declaration and disability certificate submitted by you for the following date and subject:';
						$final_str .= '<br/>';
						$final_str .= 'Exam Date:' . $exam_date;
						$final_str .= '<br/>';
						$final_str .= 'Subject Name:' . $subject_name;
						$final_str .= '<br/><br/>';
						$final_str .= 'Your scribe details are given below:';
						$final_str .= '<br/><br/>';
						$final_str .= 'Scribe Name:' . $name_of_scribe;
						$final_str .= '<br/>';
						$final_str .= 'Scribe ID proof:' . $photoid_no;
						$final_str .= '<br/><br/>';
						$final_str .= 'You are requested to carry and produce the following documents at the Examination Venue without fail:';
						$final_str .= '<br/><br/>';
						$final_str .= '1)Printout of this e-mail permitting you to use the Scribe';
						$final_str .= '<br/>';
						$final_str .= '2)Admit Letter issued by the Institute for the Examination';
						$final_str .= '<br/>';
						$final_str .= '3)Original declaration form along with attested copy of disability certificate';
						$final_str .= '<br/>';
						$final_str .= '4) Original ID Proof of the Scribe';
						$final_str .= '<br/><br/>';
						$final_str .= 'You are required to be present along with the Scribe at least 30 minutes prior to the start of the examination and submit these documents to the Centre Authorities.';
						$final_str .= '<br/><br/>';
						$final_str .= 'Thanks and Regards,';
						$final_str .= '<br/>';
						$final_str .= 'Indian Institute of Banking & Finance';

						$info_arr = array(
							//'to'=>'pooja.mane@esds.co.in',
							//'to'=>'harshu.joy26@gmail.com',
							'to' => $email,
							'from' => 'noreply@iibf.org.in',
							'subject' => 'Your application for scribe dated on ' . $date,
							'message' => $final_str
						);
					}

					if ($this->Emailsending->mailsend_attch($info_arr, '')) {
						$update_data  = array(
							'email_flag' => $email_flag, //Email flag updation
							'modified_on' => $today
						);

						$this->master_model->updateRecord(
							'scribe_registration',
							$update_data,
							array('regnumber' => $regnumber, 'exam_code' => $exam_code, 'scribe_approve' => '1', 'scribe_uid' => $scribe_uid)
						);
						//echo $this->db->last_query();die;
						/*$arr_update = array('modified_on' => $today,'remark'=>'1');
		
		            			$arr_where = array("id" => $id);
		            			$this->master_model->updateRecord('scribe_registration',$arr_update,$arr_where);*/
						/*
								print_r($email_flag);die;*/
					}
				}
			} else {
				$this->session->set_flashdata('error', 'Oops,Something Went Wrong While Approving Application.');
			}
			//print_r($final_str);print_r($mobile_scribe);die;die;
		} else {
			$this->session->set_flashdata('error', 'Invalid Selection Of Record');
		}
		//echo "string";

		if ($arr_data[0]['mobile_scribe'] == '0') {
			//print_r($arr_data[0]['mobile_scribe']);die;
			redirect(base_url() . 'scribe_dashboard/Scribe_list/special_approved_list');
		} else {
			//print_r($arr_data[0]['mobile_scribe']);die;
			redirect(base_url() . 'scribe_dashboard/Scribe_list/approved_list');
		}
	}


	public function reject_mail()
	{

		$id = $this->uri->segment('4');

		if (is_numeric($id)) {
			$this->db->where('id', $id);
			$arr_data = $this->master_model->getRecords('scribe_registration');

			if (empty($arr_data)) {
				$this->session->set_flashdata('error', 'Invalid Selection Of Record');
				redirect(base_url() . 'scribe_dashboard/Scribe_list');
			}

			/*REJECT SCRIBE APPLICATION MAIL POOJA MANE: 10/08/2022 */
			if ($arr_data > 0) {
				$this->session->set_flashdata('success', 'Rejection Mail Sent!');

				$regnumber = $arr_data[0]['regnumber'];
				$exam_code = $arr_data[0]['exam_code'];
				$scribe_info = $this->master_model->getRecords('scribe_registration', array('regnumber' => $regnumber, 'exam_code' => $exam_code, 'scribe_approve' => '3', "id" => $id));

				$scribe_uid = $scribe_info[0]['scribe_uid'];
				$name = $scribe_info[0]['firstname'];
				$exam_name = $scribe_info[0]['exam_name'];
				$subject_name = $scribe_info[0]['subject_name'];
				$exam_date = $scribe_info[0]['exam_date'];
				$center_name = $scribe_info[0]['center_name'];
				$email = $scribe_info[0]['email'];
				$name_of_scribe = $scribe_info[0]['name_of_scribe'];
				$mobile_scribe = $scribe_info[0]['mobile_scribe'];
				$applied_date = $scribe_info[0]['created_on'];
				$date = date("Y-m-d", strtotime($applied_date));
				$final_str = '';
				$email_flag = $scribe_info[0]['email_flag'];
				$email_flag += 1;
				$today = date('Y-m-d H:i:s');
				//$reason = $reject_reason;
				if ($scribe_info[0]['special_assistance']) {
					$special = "Special Assistance/Extra Time ";
				} else {
					$special = "";
				}
				if ($scribe_info[0]['extra_time']) {
					$extra = "Extra Time";
				} else {
					$extra = "";
				}
				if ($special && $extra == "") {
					$scribe = "Scribe ";
				} else {
					$scribe = "";
				}

				//print_r($date);die;
				$reasons = $this->master_model->getRecords('rejection_reasons', array('scribe_uid' => $scribe_uid));
				$reason1 = '';
				$reason2 = '';
				$reason3 = '';
				$reason4 = '';
				//print_r(count($reasons));die;
				if (count($reasons) == '1') {
					$reason = $reasons[0]['reason_description'];
					if (
						$reason !== "Scribe form is not properly filled-in/visible" &&
						$reason !== "Handicap Certificate is not correct/properly visible" &&
						$reason !== "Online Information not filled/filled improperly" &&
						$reason !== "Disability Certificate is not valid/clearly visible"
					) {
						$reason4 = $reason;
					} else {
						$reason1 = $reason;
					}
				}

				if (count($reasons) == '2') {
					//$reason1 = $reasons[0]['reason_description'];
					$reason = $reasons[0]['reason_description'];
					if (
						$reason !== "Scribe form is not properly filled-in/visible" &&
						$reason !== "Handicap Certificate is not correct/properly visible" &&
						$reason !== "Online Information not filled/filled improperly" &&
						$reason !== "Disability Certificate is not valid/clearly visible"
					) {
						$reason4 = $reason;
					} else {
						$reason1 = $reason;
					}

					//$reason2 = $reasons[1]['reason_description'];
					$reason = $reasons[1]['reason_description'];
					if (
						$reason !== "Scribe form is not properly filled-in/visible" &&
						$reason !== "Handicap Certificate is not correct/properly visible" &&
						$reason !== "Online Information not filled/filled improperly"
					) {
						$reason4 = $reason;
					} else {
						$reason2 = $reason;
					}
				}

				if (count($reasons) == '3') {
					//echo "string";
					$reason = $reasons[0]['reason_description'];
					if (
						$reason != "Scribe form is not properly filled-in/visible" &&
						$reason != "Handicap Certificate is not correct/properly visible" &&
						$reason != "Online Information not filled/filled improperly"
					) {
						$reason4 = $reason;
					} else {
						$reason1 = $reason;
					}

					$reason = $reasons[1]['reason_description'];
					if (
						$reason !== "Scribe form is not properly filled-in/visible" &&
						$reason !== "Handicap Certificate is not correct/properly visible" &&
						$reason !== "Online Information not filled/filled improperly"
					) {
						$reason4 = $reason;
					} else {
						$reason2 = $reason;
					}

					$reason = $reasons[2]['reason_description'];
					if (
						$reason !== "Scribe form is not properly filled-in/visible" &&
						$reason !== "Handicap Certificate is not correct/properly visible" &&
						$reason !== "Online Information not filled/filled improperly"
					) {
						$reason4 = $reason;
					} else {
						$reason3 = $reason;
					}
				}

				if (count($reasons) == '4') {
					$reason1 = $reasons[0]['reason_description'];
					$reason2 = $reasons[1]['reason_description'];
					$reason3 = $reasons[2]['reason_description'];
					$reason4 = $reasons[3]['reason_description'];
				}

				if (!empty($scribe_info)) {

					if ($mobile_scribe) {
						/*********SCRIBE REJECTION  MAIL FORMAT*********/
						$final_str .= 'Dear ' . $name . ',';
						$final_str .= '<br/><br/>';
						$final_str .= 'With reference to your application number : ' . $scribe_uid . ',';
						$final_str .= '<br/>';
						$final_str .= 'Exam Date: ' . $exam_date;
						$final_str .= '<br/>';
						$final_str .= 'Subject Name: ' . $subject_name;
						$final_str .= '<br/><br/>';
						$final_str .= 'You have not been granted permission for Scribe because of the following reasons: ';
						$final_str .= '<br/>';
						$final_str .= '<ol>';
						if (!empty($reason1)) {
							$final_str .= '<li>' . $reason1 . '</li>';
							$final_str .= '<br/>';
						}
						if (!empty($reason2)) {
							$final_str .= '<li>' . $reason2 . '</li>';
							$final_str .= '<br/>';
						}
						if (!empty($reason3)) {
							$final_str .= '<li>' . $reason3 . '</li>';
							$final_str .= '<br/>';
						}
						if (!empty($reason4)) {
							$final_str .= '<li> Any Other reason :' . $reason4 . '</li>';
							$final_str .= '<br/>';
						};
						$final_str .= '</ol>';
						$final_str .= 'Please apply again or contact MSS department.';
						$final_str .= '<br/><br/>';
						$final_str .= 'Thanks and Regards,';
						$final_str .= '<br/>';
						$final_str .= 'Indian Institute of Banking & Finance';
						//print_r($final_str);die;

						$info_arr = array(
							'to' => $email,
							//'to'=>'pooja.mane@esds.co.in', 
							'from' => 'noreply@iibf.org.in',
							'subject' => 'Your application for scribe dated on ' . $date,
							'message' => $final_str
						);
					} else {
						/*********SPECIAL REJECTION  MAIL FORMAT*********/
						$final_str .= 'Dear ' . $name . ',';
						$final_str .= '<br/><br/>';
						$final_str .= 'With reference to your application number : ' . $scribe_uid . ',';
						$final_str .= '<br/>';
						$final_str .= 'Exam Date: ' . $exam_date;
						$final_str .= '<br/>';
						$final_str .= 'Subject Name: ' . $subject_name;
						$final_str .= '<br/><br/>';
						$final_str .= 'You have not been granted permission for Special Assistance/Extra Time because of the following reasons: ';
						$final_str .= '<ol>';
						if ($reason1) {
							$final_str .= '<li>' . $reason1 . '</li>';
						}
						if ($reason4) {
							$final_str .= '<li> Any Other reason : ' . $reason4 . '</li>';
							$final_str .= '<br/>';
						};
						$final_str .= '</ol>';
						$final_str .= 'Please apply again or contact MSS department.';
						$final_str .= '<br/><br/>';
						$final_str .= 'Thanks and Regards,';
						$final_str .= '<br/>';
						$final_str .= 'Indian Institute of Banking & Finance';
						//print_r($final_str);die;
						$info_arr = array(
							'to' => $email,
							//'to'=>'pooja.mane@esds.co.in', 
							'from' => 'noreply@iibf.org.in',
							'subject' => 'Your application for special Assistance/extra time  dated on ' . $date,
							'message' => $final_str
						);
					}
					//print_r($final_str) ;die;

					if ($this->Emailsending->mailsend_attch($info_arr, '')) {
						$update_data  = array(
							'email_flag' => $email_flag, //Email flag updation
							'modified_on' => $today,
							'remark' => '0'
						);

						$this->master_model->updateRecord(
							'scribe_registration',
							$update_data,
							array(
								'regnumber' => $regnumber,
								'exam_code' => $exam_code,
								'scribe_approve' => '3',
								'scribe_uid' => $scribe_uid
							)
						);
						//echo $this->db->last_query();die;
					}
				}
			} else {
				$this->session->set_flashdata('error', 'Oops,Something Went Wrong While rejecting Application.');
			}
		} else {
			$this->session->set_flashdata('error', 'Invalid Selection Of Record');
		}
		if ($arr_data[0]['mobile_scribe'] == '0') {
			//print_r($arr_data[0]['mobile_scribe']);die;
			redirect(base_url() . 'scribe_dashboard/Scribe_list/special_rejected_list');
		} else {
			//print_r($arr_data[0]['mobile_scribe']);die;
			redirect(base_url() . 'scribe_dashboard/Scribe_list/rejected_list');
		}
	}
}
