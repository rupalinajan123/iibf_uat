<!-- 
	Created BY Pooja Mane : 27-07-2022
	Shared UAT : 25-08-2022
	Made Live : 21-09-2022
 -->
<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Count_list extends CI_Controller
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
		$array = array('isdeleted' => '0', 'isactive' => '1');
		$this->db->where($array);
		// $this->db->where('isactive = 1');
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('member_registration');
		$data['count1'] = $count1 = $total_scribe_reg[0]['rows']; //total scribe


		/*TOTAL APPROVED SCRIBE APPLICATION*/
		$this->db->select('count(*) as rows');
		$array = array('isdeleted' => '0', 'isactive' => '1', 'registrationtype' => 'O');
		$this->db->where($array);
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('member_registration');
		$data['count3'] = $count3 = $total_scribe_reg[0]['rows']; //approved scribe

		/*TOTAL REJECTED SCRIBE APPLICATION*/
		$this->db->select('count(*) as rows');
		$array = array('isdeleted' => '0', 'isactive' => '1', 'registrationtype' => 'NM');
		$this->db->where($array);
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('member_registration');
		$data['count5'] = $count5 = $total_scribe_reg[0]['rows']; //rejected scribe

		/*DAILY OR TODAY'S APPLICATION DETAILS*/
		$today = date('Y-m-d');

		/*TOTAL SCRIBE APPLICATION TODAY*/
		$this->db->select('count(*) as rows');
		$array = array('DATE(createdon)' => $today, 'isdeleted' => '0', 'isactive' => '1');
		$this->db->where($array);
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('member_registration');
		$data['count7'] = $count7 = $total_scribe_reg[0]['rows']; //total scribe
		// echo $this->db->last_query();
		// die;


		/*TOTAL APPROVED SCRIBE APPLICATION TODAY*/
		$this->db->select('count(*) as rows');
		$array = array('DATE(createdon)' => $today, 'isdeleted' => '0', 'isactive' => '1', 'registrationtype' => 'O');
		// $this->db->like('modified_on', $today);
		$this->db->where($array);
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('member_registration');
		$data['count9'] = $count9 = $total_scribe_reg[0]['rows']; //approved scribe
		//echo $this->db->last_query();die;

		/*TOTAL REJECTED SCRIBE APPLICATION TODAY*/
		$this->db->select('count(*) as rows');
		$array = array('DATE(createdon)' => $today, 'isdeleted' => '0', 'isactive' => '1', 'registrationtype' => 'NM');
		$this->db->where($array);
		// $this->db->like('modified_on', $today);
		$data['total_scribe_reg'] = $total_scribe_reg = $this->master_model->getRecords('member_registration');
		$data['count11'] = $count11 = $total_scribe_reg[0]['rows']; //rejected scribe

		/* ================= DRA RELEASE COUNT ================= */
		$this->db->select('COUNT(*) as rows');
		$this->db->from('dra_members');
		$this->db->where('hold_release', 'release');

		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('DATE(createdon) >=', $from_date);
			$this->db->where('DATE(createdon) <=', $to_date);
		}

		$dra = $this->db->get()->result_array();
		$data['dra_release_count'] = $dra[0]['rows'];

		/* ================= BCBF RELEASE COUNT ================= */
		$this->db->select('COUNT(cand.candidate_id) as rows');
		$this->db->from('iibfbcbf_agency_centre_batch acb');
		$this->db->join(
			'iibfbcbf_batch_candidates cand',
			'cand.batch_id = acb.batch_id',
			'inner'
		);

		$this->db->where('acb.batch_status', 3);
		$this->db->where('cand.hold_release_status', '3');
		$this->db->where('cand.is_deleted', '0');

		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('acb.batch_end_date >=', $from_date);
			$this->db->where('acb.batch_end_date <=', $to_date);
		}

		$bcbf = $this->db->get()->result_array();
		$data['bcbf_release_count'] = $bcbf[0]['rows'];

		$this->load->view('count_dashboard/dashboard', $data);
	}


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

		$this->load->view('count_dashboard/search_view', $data);
	}
	/*QUERY OPTION IN SCRIBE MODULE END BY POOJA MANE : 29-11-2022*/

	public function count()
	{
		$from_date = $to_date = '';

		/* ================= GET FILTER VALUES ================= */
		if ($this->input->post('btnSearch') || $this->input->post('download')) {

			if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
				$from_date = $_POST['from_date'];
				$to_date   = $_POST['to_date'];
			}
		}

		/* ================= TOTAL COUNT ================= */
		$this->db->select('COUNT(*) as rows');
		$this->db->from('member_registration');

		$this->db->where([
			'isdeleted' => '0',
			'isactive'  => '1'
		]);

		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('DATE(createdon) >=', $from_date);
			$this->db->where('DATE(createdon) <=', $to_date);
		}

		$total = $this->db->get()->result_array();
		$data['count1'] = $total[0]['rows'];


		/* ================= MEMBER COUNT ================= */
		$this->db->select('COUNT(*) as rows');
		$this->db->from('member_registration');

		$this->db->where([
			'isdeleted'        => '0',
			'isactive'         => '1',
			'registrationtype' => 'O'
		]);

		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('DATE(createdon) >=', $from_date);
			$this->db->where('DATE(createdon) <=', $to_date);
		}

		$member = $this->db->get()->result_array();
		$data['count3'] = $member[0]['rows'];


		/* ================= NON-MEMBER COUNT ================= */
		$this->db->select('COUNT(*) as rows');
		$this->db->from('member_registration');

		$this->db->where([
			'isdeleted'        => '0',
			'isactive'         => '1',
			'registrationtype' => 'NM'
		]);

		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('DATE(createdon) >=', $from_date);
			$this->db->where('DATE(createdon) <=', $to_date);
		}

		$non_member = $this->db->get()->result_array();
		$data['count5'] = $non_member[0]['rows'];


		/* ================= PASS DATES BACK TO VIEW ================= */
		$data['from_date'] = $from_date;
		$data['to_date']   = $to_date;


		/* ================= DRA RELEASE COUNT ================= */
		$this->db->select('COUNT(*) as rows');
		$this->db->from('dra_members');
		$this->db->where('hold_release', 'release');

		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('DATE(createdon) >=', $from_date);
			$this->db->where('DATE(createdon) <=', $to_date);
		}

		$dra = $this->db->get()->result_array();
		$data['dra_release_count'] = $dra[0]['rows'];

		/* ================= BCBF RELEASE COUNT ================= */
		$this->db->select('COUNT(cand.candidate_id) as rows');
		$this->db->from('iibfbcbf_agency_centre_batch acb');
		$this->db->join(
			'iibfbcbf_batch_candidates cand',
			'cand.batch_id = acb.batch_id',
			'inner'
		);

		$this->db->where('acb.batch_status', 3);
		$this->db->where('cand.hold_release_status', '3');
		$this->db->where('cand.is_deleted', '0');

		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('acb.batch_end_date >=', $from_date);
			$this->db->where('acb.batch_end_date <=', $to_date);
		}

		$bcbf = $this->db->get()->result_array();
		$data['bcbf_release_count'] = $bcbf[0]['rows'];



		/* ================= DOWNLOAD CSV ================= */
		// if ($this->input->post('download')) {

		// 	$this->db->select('*');
		// 	$this->db->from('scribe_registration s');
		// 	$this->db->join('qualification q', 'q.qid = s.specify_qualification', 'left');
		// 	$this->db->where('s.mobile_scribe !=', 0);

		// 	if (!empty($from_date) && !empty($to_date)) {
		// 		$this->db->where('DATE(s.createdon) >=', $from_date);
		// 		$this->db->where('DATE(s.createdon) <=', $to_date);
		// 	}

		// 	$result = $this->db->get()->result_array();

		// 	header('Content-Type: text/csv');
		// 	header('Content-Disposition: attachment; filename=scribe_registration.csv');

		// 	echo "Sr.No,URN,Exam Name,Scribe Name,Created Date\n";
		// 	$i = 1;
		// 	foreach ($result as $row) {
		// 		echo $i++ . ",";
		// 		echo $row['scribe_uid'] . ",";
		// 		echo $row['exam_name'] . ",";
		// 		echo $row['name_of_scribe'] . ",";
		// 		echo $row['createdon'] . "\n";
		// 	}
		// 	exit;
		// }

		/* ================= LOAD VIEW ================= */
		$this->load->view('count_dashboard/countlist', $data);
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
}
