<?php
defined('BASEPATH') or exit('No direct script access allowed');
/** SAGAR WALZADE : CUSTOM FUNCTIONS START */
function _pa($a)
{
	echo '<pre>';
	echo print_r($a, true);
	echo '</pre>';
}

function _lq()
{
	$CI = &get_instance();
	echo $CI->db->last_query();
}
/** SAGAR WALZADE : CUSTOM FUNCTIONS END */

/*Last Updated by : Pooja Mane On: 2023-12-30 */
class Kyc extends CI_Controller
{
	public $UserID;

	public function __construct()
	{
		parent::__construct();
		/*	if($this->session->userdata('kyc_id') == ""){
			redirect('admin/kyc/Login');
		}		*/

		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->UserID = $this->session->id;
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->library('email');
		$this->load->model('KYC_Log_model');
		$this->load->model('Emailsending');
		$this->load->model('Chk_KYC_session');
		$this->Chk_KYC_session->chk_recommender_session();
		ini_set('memory_limit', '1024M');
	}
	function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
	}
	public function index()
	{
	}

	/*Added by : Pooja Mane to show pending member list*/
	public function pending_member_list()
	{
		$count = 1;
		$kyc_start_date = '2017-06-01';
       	$this->db->where('DATE(editedon) !=','00-00-0000');
		$this->db->select('regnumber');
		$this->db->where('isactive','1');
		$this->db->where('kyc_status', '0');
		$this->db->limit('100');
		$members=$this->master_model->getRecords('member_registration ');
		// echo $this->db->last_query();die;

		foreach ($members as $regnumber)
		{

			$regnumber = $regnumber['regnumber'];
			$this->db->select('regnumber');
			$this->db->limit('1');
			$mem_kyc_info=$this->master_model->getRecords('member_kyc',array('regnumber'=>$regnumber));

			if(count($mem_kyc_info) == 0)
			{
				if(!in_array($regnumber,$str_regnumber)){
					$str_regnumber[] = $regnumber;
				}
			}

			$count++;
		}
		$data['str_regnumber'] = $str_regnumber;
		// print_r($str_regnumber);die;
		$this->load->view('admin/kyc/pending_member_list',$data);
	}

	/*Added by : Pooja Mane to show pending member list allocation*/
	public function pending_allocation_type()
	{
		$new_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id' => ''));
		

		if (count($new_allocated_member_list) > 0) {
			if ($new_allocated_member_list[0]['allotted_member_id'] == '') {
				redirect(base_url() . 'admin/kyc/Kyc/next_pending_allocation_type');
			}
		}

		$kyc_start_date = $this->config->item('kyc_start_date');
		$allocated_member_list = $members = array();
		$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''));

		//allocated_count
		if (count($allocated_member_list)) {

			if (count($allocated_member_list) > 0) {
				$data['count'] = $allocated_member_list[0]['allocated_count'];
				$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
			}

			foreach ($arraid as $row) {

				$this->db->select('exam_code');
				$exam_code = $this->master_model->getRecords("member_exam", array('regnumber' => $row,'pay_status'=> '1'));
				$exam_code = $exam_code[0]['exam_code'];

				$this->db->select('registrationtype');
				$reg_type = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
				$type=$reg_type[0]['registrationtype'];

				$this->db->where('isactive', '1');
				$this->db->where('kyc_status', '0');

				/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
				if($type =='NM' && $exam_code !='')
				{   //echo $type;die;
					$this->db->select('me.exam_code,member_registration.*');
					$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber');
					$this->db->where('me.exam_code', $exam_code);
					$this->db->group_by('me.regnumber');
					$this->db->where('me.pay_status', '1');
				}
				// Added exam code condition End Pooja Mane 2023-10-13

				$members = $this->master_model->getRecords("member_registration", array('member_registration.regnumber' => $row));
				$members_arr[] = $members;
			}

			$emptylistmsg = ' ';
			$data['emptylistmsg']	= $emptylistmsg;
			$data['result'] = call_user_func_array('array_merge', $members_arr);
			$data['reset']	= '1'; //flag for reset btn added by pooja mane on 11-04-23

			/* Start Code To Get Recent Allotted Member Total Count */
			$pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
			if (!empty($pagination_total_count)) {
				foreach ($pagination_total_count[0] as $k => $value) {
					if ($k == "pagination_total_count") {
						$data['totalRecCount'] = $value;
					}
					if ($k == "original_allotted_member_id") {
						$data['original_allotted_member_id'] = $value;
					}
				}
			}
			/* Close Code To Get Recent Allotted Member Total Count */

			$this->load->view('admin/kyc/pending_alocated_member', $data);
		} 
		else 
		{
			$date = '2017';//KYC PROCESS STARTED YEAR
			$count = 1;
			$kyc_start_date = '2017-06-01';

	       	$this->db->where('DATE(editedon) !=','00-00-0000');
			$this->db->select('regnumber');
			$this->db->where('isactive','1');
			$this->db->where('kyc_status', '0');
			$this->db->limit('100');
			$members=$this->master_model->getRecords('member_registration ');

			foreach ($members as $regnumber)
			{

				$regnumber = $regnumber['regnumber'];
				$this->db->select('regnumber');
				$this->db->limit('1');
				$mem_kyc_info=$this->master_model->getRecords('member_kyc',array('regnumber'=>$regnumber));

				if(count($mem_kyc_info) == 0)
				{
					if(!in_array($regnumber,$str_regnumber)){
						$str_regnumber[] = $regnumber;
					}
				}

				$count++;
			}
			$data['str_regnumber'] = $str_regnumber;

			$kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'Pending', 'user_type' => 'recommender'), 'original_allotted_member_id');
			
			if (count($kyc_data) > 0) 
			{
				foreach ($kyc_data  as $row) {
					$allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
				}
			}

			if (count($allocatedmemberarr) > 0) 
			{
				// get the column data in a single array
				$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
			}
			

			$this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
			$this->db->where_in('mr.regnumber', array_map('stripslashes', $str_regnumber));
			$this->db->where('mr.isactive', '1');
			$this->db->where('mr.kyc_status', '0');
			$this->db->where('mr.registrationtype', 'NM');
			$this->db->where('me.pay_status', '1');
			$this->db->join('member_exam me','me.regnumber = mr.regnumber');
			$this->db->join('exam_master e','e.exam_code = me.exam_code');
			$this->db->group_by('me.exam_code');
			$this->db->order_by('mr.regid','ASC');

			if (count($data_array) > 0) {
				$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
			};

			 $mem_list = $this->master_model->getRecords("member_registration mr");
			 $mem_exm_arr[] = $mem_list;
		 	 $data['mem_exm_arr'] = $mem_exm_arr;

			$this->load->view('admin/kyc/pending_allocation_type',$data);
		}
	}

	/*Added by : Pooja Mane to show next pending member list after first list completeion*/
	public function next_pending_allocation_type()
	{
		$date = '2017';//KYC PROCESS STARTED YEAR
		$count = 1;
		$kyc_start_date = '2017-06-01';
       	$this->db->where('DATE(editedon) !=','00-00-0000');
		$this->db->select('regnumber');
		$this->db->where('isactive','1');
		$this->db->where('kyc_status', '0');
		$this->db->limit('100');
		$members=$this->master_model->getRecords('member_registration ');

		foreach ($members as $regnumber)
		{

			$regnumber = $regnumber['regnumber'];
			$this->db->select('regnumber');
			$this->db->limit('1');
			$mem_kyc_info=$this->master_model->getRecords('member_kyc',array('regnumber'=>$regnumber));

			if(count($mem_kyc_info) == 0)
			{
				if(!in_array($regnumber,$str_regnumber)){
					$str_regnumber[] = $regnumber;
				}
			}

			$count++;
		}
		$data['str_regnumber'] = $str_regnumber;
		
		$this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
		$this->db->where_in('mr.regnumber', array_map('stripslashes', $str_regnumber));
		$this->db->where('mr.isactive', '1');
		$this->db->where('mr.kyc_status', '0');
		$this->db->where('mr.registrationtype', 'NM');
		$this->db->where('me.pay_status', '1');
		$this->db->join('member_exam me','me.regnumber = mr.regnumber');
		$this->db->join('exam_master e','e.exam_code = me.exam_code');
		$this->db->group_by('me.exam_code');
		$this->db->order_by('mr.regid','ASC');

		if (count($allocatedmemberarr) > 0) 
		{
			// get the column data in a single array
			$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
		}

		$data_array = array_merge($data_array, $recommendedmemberarr);

		if (count($recommendedmemberarr) > 0) {
			$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $recommendedmemberarr));
		};
		if (count($data_array) > 0) {
			$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
		};

		 $mem_list = $this->master_model->getRecords("member_registration mr");
		 $next_mem_exm_arr[] = $mem_list;
		 $data['next_mem_exm_arr'] = $next_mem_exm_arr;

		$this->load->view('admin/kyc/next_pending_allocation_type',$data);
	}

	/*Added by : Pooja Mane to allocate pending member list*/
	public function pending_allocated_list()
	{	
		$total_id = $recommendedmemberarr = array();
		$kyc_start_date = $this->config->item('kyc_start_date');

		$data['count'] = 0;
		$tilte = $allocated_count = '';
		$description = $emptylistmsg = '';
		$allocates_arr = $members_arr = $result = $array = $allocated_member_list = $data_array = array();
		$data['result'] = array();
		$regstr = $searchText = $searchBy = '';
		$searchBy_regtype = '';
		$today = date('Y-m-d H:i:s');
		$per_page = 100;
		// $per_page = 10;
		$last = 99;
		$start = 0;
		$list_type = 'Pending';
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		//$from_date = '';
		//$to_date = '';
		if ($this->input->post('regnumber') != '') {
			$searchBy = $this->input->post('regnumber');
		}
		if ($this->input->post('registrationtype') != '') {
			$searchBy_regtype = $this->input->post('registrationtype');
		}
		$registrationtype = '';
		$data['reg_no'] = ' ';
		if ($page != 0) {
			$start = $page - 1;
		}
		$allocates = array();
		$exam_code = $_POST['select_exm_cd'];

		$process_flag = 0;

		if (isset($_POST['selectby']))
		{

			if($_POST['selectby'] =='NM')
			{
				if(isset($_POST['select_exm_cd']) && $_POST['select_exm_cd']=='Select exam')
				{
					$process_flag = 0;
				}
				elseif(isset($_POST['select_exm_cd']) && $_POST['select_exm_cd']!='')
				{
					$process_flag = 1;
				}
			}else
			{
				$process_flag = 1;
			}

			if($process_flag=='1')
			{
					$type = $_POST['selectby'];
					// Need to travese with all allocated numbers.
					$kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'Pending'), 'original_allotted_member_id');
					
					$allocatedmemberarr = array();
					if (count($kyc_data) > 0) 
					{
						foreach ($kyc_data as $row) {
							$allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
						}
					}
					
					$date = '2017';//KYC PROCESS STARTED YEAR
					$count = 1;
					$kyc_start_date = '2017-06-01';
			
			       	$this->db->where('DATE(editedon) !=','00-00-0000');
					$this->db->select('regnumber');
					$this->db->where('isactive','1');
					$this->db->where('kyc_status', '0');
					$this->db->limit('100');
					$members=$this->master_model->getRecords('member_registration ');

					foreach ($members as $regnumber)
					{

						$regnumber = $regnumber['regnumber'];
						$this->db->select('regnumber');
						$this->db->limit('1');
						$mem_kyc_info=$this->master_model->getRecords('member_kyc',array('regnumber'=>$regnumber));

						if(count($mem_kyc_info) == 0)
						{
							if(!in_array($regnumber,$str_regnumber)){
								$str_regnumber[] = $regnumber;
							}
						}

						$count++;
					}
					$data['str_regnumber'] = $str_regnumber;
					$this->db->where_in('mr.regnumber', array_map('stripslashes', $str_regnumber));

					$this->db->where('mr.regnumber !=', '');
					$this->db->where('mr.isactive', '1');
					$this->db->where('mr.kyc_status', '0');
					$this->db->where('mr.registrationtype', $type);

					if($type =='NM')
					{   
						$this->db->select('exam_code');
						$this->db->join('member_exam me', 'me.regnumber = mr.regnumber','INNER');
						$this->db->where('me.exam_code', $exam_code);
						$this->db->group_by('me.regnumber');
						$this->db->where('me.pay_status', '1');
						$this->db->order_by('me.id','DESC');
					}
					// Added exam code condition End Pooja Mane 2023-10-13

				if (count($allocatedmemberarr) > 0) {
					// get the column data in a single array
					$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
				}

				//$data_array = array_merge($data_array, $recommendedmemberarr);
				if (count($data_array) > 0) {
					$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
				};

				$members = $this->master_model->getRecords("member_registration mr", array('mr.isactive' => '1'), 'mr.*', array('mr.regid' => 'ASC'), $start, $per_page);
				// echo $this->db->last_query();die;
				 
				$data['start'] = $start;

				$today = date("Y-m-d H:i:s");
				$row_count = $this->master_model->getRecordCount("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending'));

				if ($row_count == 0) 
				{
						$regstr = '';
						/* 
							Change by : Sagar Walzade (12-5-2022) - code start
							above loop removed due to page speed issue and we have used array_column function instead.
						*/
						$allocates_arr = array_column($members, 'regnumber');
						
						/* code end - sagar walzade */

						$allocated_count = count($allocates_arr);
						if (count($allocates_arr) > 0) {
							$regstr = implode(',', $allocates_arr);
						}

					if ($regstr != '') 
					{
						$insert_data = array(
							'user_type'			=> $this->session->userdata('role'),
							'user_id'				=> $this->session->userdata('kyc_id'),
							'allotted_member_id'	=> $regstr,
							'original_allotted_member_id'	=> $regstr,
							'allocated_count'     => $allocated_count,
							'allocated_list_count'     => '1',
							'date'	                => $today,
							'list_type'             => $list_type,
							'pagination_total_count ' => $allocated_count
						);

						$this->master_model->insertRecord('admin_kyc_users', $insert_data);
						//log activity 

						$tilte = 'Recommender New member list allocation';
						$description = 'Recommender has allocated ' . count($allocates_arr) . ' member';
						$user_id = $this->session->userdata('kyc_id');
						$this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);
					}
				}
			}
			else
			{
				$this->session->set_flashdata('error','Please select exam!!');
				redirect(base_url() . 'admin/kyc/Kyc/pending_allocation_type');
			}
		}

		$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''));


		//allocated_count
		if (count($allocated_member_list) > 0) 
		{
			$data['count'] = $allocated_member_list[0]['allocated_count'];
			$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);

			if (count($arraid) > 0) 
			{
				if ($searchBy != '' || $searchBy_regtype != '') 
				{
					if ($searchBy != '' && $searchBy_regtype != '') {
						$this->db->where('regnumber', $searchBy);
						$this->db->where('registrationtype', $searchBy_regtype);
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
					}
					///search by registration number
					else if ($searchBy != '') {
						$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
						$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
						$this->db->where('regnumber', $searchBy);
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
						//$row=$searchBy;
					}
					///search by registration type
					else if ($searchBy_regtype != '') {
						$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
						$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
						$this->db->where('registrationtype', $searchBy_regtype);
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
					}

					
					if (count($members) > 0) {
						foreach ($members as $row) {
							$members_arr[][] = $row;
						}
					}
				}
				else 
				{
					//default allocation list for 100 member
					foreach ($arraid as $row) {

						// $this->db->where('isactive', '1');
						// $this->db->where('kyc_status', '0');
						// $members = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
						// $members_arr[] = $members;
						$this->db->select('exam_code');
						$exam_code = $this->master_model->getRecords("member_exam", array('regnumber' =>$row['regnumber'],'pay_status'=> '1'));
						$exam_code = $exam_code[0]['exam_code'];

						$this->db->select('registrationtype');
						$reg_type = $this->master_model->getRecords("member_registration", array('regnumber' =>$row['regnumber']));
						$type=$reg_type[0]['registrationtype'];

						/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-18
						if($type =='NM' && $exam_code !='')
						{   
							$this->db->select('me.exam_code,member_registration.*');
							$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
							$this->db->where('me.exam_code', $exam_code);
							$this->db->where('me.pay_status', '1');
						}
						// Added exam code condition End Pooja Mane 2023-10-18
						$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
							$members = $this->master_model->getRecords("member_registration");
						// $members_arr[][] = $members;
					}
					if (count($members) > 0) {
						foreach ($members as $row) {
							$members_arr[][] = $row;
						}
					}
				} 
				
			}
		}

		$data['result'] = $members;

		$total_row = 100;
		$url = base_url() . "admin/kyc/Kyc/pending_allocated_list/";
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
			<li>Search</li>
			</ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;
		if (($start + $per_page) > $total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start + $per_page;
		if ($total_row)
			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
		else
			$data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';
		$data['index'] = $start + 1;
		
		/* Start Code To Get Recent Allotted Member Total Count */
		$pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
		if (!empty($pagination_total_count)) {
			foreach ($pagination_total_count[0] as $k => $value) {
				if ($k == "pagination_total_count") {
					$data['totalRecCount'] = $value;
				}
				if ($k == "original_allotted_member_id") {
					$data['original_allotted_member_id'] = $value;
				}
			}
		}
		/* Close Code To Get Recent Allotted Member Total Count */
		$emptylistmsg = ' No records available...!!<br />
			<a href=' . base_url() . 'admin/kyc/Kyc/pending_allocation_type/>Back</a>';
		$data['emptylistmsg']	= $emptylistmsg;
		$data['total_count'] = $allocated_count;
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration', array('isactive' => '1'), 'registrationtype', array('registrationtype' => 'ASC'));

		$this->load->view('admin/kyc/pending_alocated_member', $data);
	}

	public function next_pending_allocated_list()
	{
		
		if (isset($_POST['selectby'])) {
			$kyc_start_date = $this->config->item('kyc_start_date');
			$type = $_POST['selectby'];
			$data['count'] = 0;
			$tilte = $allocated_count = $emptylistmsg = $allotted_member_id = '';
			$description = '';
			$allocates_arr = $members_arr = $result = $array = $allocated_member_list = $recommendedmemberarr = array();
			$data['result'] = array();
			$regstr = $searchText = $searchBy = '';
			$searchBy_regtype = '';
			$today = date('Y-m-d H:i:s');
			$per_page = 100;
			// $per_page = 10;
			$last = 99;
			// $last = 9;
			$start = 0;
			$list_type = 'Pending';
			$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
			$check = $kyc_data = array();
			$date = date("Y-m-d H:i:s");
			$allocatedmemberarr = array();
			$exam_code = $_POST['select_exm_cd'];
			$process_flag = 0;

			$check = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id' => ''));

			if (count($check)) 
			{
				if ($check[0]['allotted_member_id'] == '') 
				{
					$kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'Pending', 'user_type' => 'recommender'), 'original_allotted_member_id');

					if (count($kyc_data) > 0) 
					{
						foreach ($kyc_data  as $row) {
							$allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
						}
					}

					$date = '2017';//KYC PROCESS STARTED YEAR
					$count = 1;
					$kyc_start_date = '2017-06-01';
					$this->db->select('regnumber');
					$this->db->where('isactive','1');
					$this->db->where('DATE(editedon) !=','00-00-0000');
					$this->db->where('kyc_status', '0');
					$this->db->limit('100');
					$members=$this->master_model->getRecords('member_registration ');

					foreach ($members as $regnumber)
					{

						$regnumber = $regnumber['regnumber'];
						$this->db->select('regnumber');
						$this->db->limit('1');
						$mem_kyc_info=$this->master_model->getRecords('member_kyc',array('regnumber'=>$regnumber));

						if(count($mem_kyc_info) == 0)
						{
							if(!in_array($regnumber,$str_regnumber)){
								$str_regnumber[] = $regnumber;
							}
						}

						$count++;
					}
					$data['str_regnumber'] = $str_regnumber;

					$this->db->where_in('mr.regnumber', array_map('stripslashes', $str_regnumber));
					$this->db->where('mr.regnumber !=', '');
					$this->db->where('mr.isactive', '1');
					$this->db->where('mr.kyc_status', '0');
					$this->db->where('mr.registrationtype', $type);

					// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-17
					if($type =='NM')
					{   
						$this->db->select('exam_code');
						$this->db->join('member_exam me', 'me.regnumber = mr.regnumber','INNER');
						$this->db->where('me.exam_code', $exam_code);
						$this->db->group_by('me.regnumber');
						$this->db->where('me.pay_status', '1');
						$this->db->order_by('me.id','DESC');
					}
					// Added exam code condition End Pooja Mane 2023-10-17

					if (count($allocatedmemberarr) > 0) {
						// get the column data in a single array
						$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
					}

					//$data_array = array_merge($data_array, $recommendedmemberarr);
					if (count($data_array) > 0) {
						$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
					}

					// SAGAR WALZADE : (Query optimisations) we are excluding members which is present in member_kyc table using left join with regnumber IS NULL
					// 3173 to 3185 & 3203 lines commented due to speed issue. and added below 2 lines instead of that code.
					$this->db->join('member_kyc as mk', 'mr.regnumber = mk.regnumber', 'left');
					$this->db->where('mk.regnumber IS NULL');
					// 3173 to 3185 & 3203 lines commented due to speed issue. and added above 2 lines instead of that code.

					$members = $this->master_model->getRecords("member_registration as mr", "", 'mr.*', array('mr.regid' => 'ASC'), $start, $per_page);

					//array1
					$array_string1 = $check[0]['original_allotted_member_id'];
					$allocates_arr1 = explode(',', $array_string1);
					foreach ($members as $row) {
						$allocates_arr[] .= $row['regnumber'];
					}

					$count = count($allocates_arr);
					$allocated_count = $count + $check[0]['allocated_count'];
					if (count($allocates_arr) > 0) {

						$allotted_member_id = implode(',', $allocates_arr);
					}
					$new_array = array_merge($allocates_arr1, $allocates_arr);
					$original_allotted_member_id = implode(',', $new_array);
					//get the  allocated list count
					if ($allotted_member_id == '') {
						$list_count = $check[0]['allocated_list_count'];
					} else {
						$list_count = $check[0]['allocated_list_count'] + 1;
					}

					$update_data = array(
						'user_type'						=> $this->session->userdata('role'),
						'user_id'							=> $this->session->userdata('kyc_id'),
						'allotted_member_id'		=> $allotted_member_id,
						'original_allotted_member_id'	=> $original_allotted_member_id,
						'allocated_count'    		  => $allocated_count,
						'allocated_list_count'     => $list_count,
						'date'	               			  => $today,
						'list_type'            		  => $list_type,
						'pagination_total_count ' => $count,
					);

					$this->db->where('list_type', 'Pending');
					$this->db->where('user_id', $this->session->userdata('kyc_id'));
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array('DATE(date)' => date('Y-m-d')));
					//log activity 
					$tilte = 'Recommender got next  New member list allocation ';
					$user_id = $this->session->userdata('kyc_id');
					$this->KYC_Log_model->create_log($tilte, $user_id, '', '', serialize($update_data));
				}

				$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''));

				//allocated_count
				if (count($allocated_member_list) > 0) {
					$data['count'] = $allocated_member_list[0]['allocated_count'];
					$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);

					if (count($arraid) > 0) {
						if ($searchBy != '' || $searchBy_regtype != '') {
							if ($searchBy != '' && $searchBy_regtype != '') {
								$this->db->where('regnumber', $searchBy);
								$this->db->where('registrationtype', $searchBy_regtype);
								$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
							}
							///search by registration number
							else if ($searchBy != '') {
								$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
								$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
								$this->db->where('regnumber', $searchBy);
								$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
								//$row=$searchBy;
							}
							///search by registration type
							else if ($searchBy_regtype != '') {
								$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
								$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
								$this->db->where('registrationtype', $searchBy_regtype);
								$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
							}
							if (count($members) > 0) {
								foreach ($members as $row) {
									$members_arr[][] = $row;
								}
							}
						} else {
							//default allocation list for 100 member
							foreach ($arraid as $row) {
								$this->db->where('isactive', '1');
								$this->db->where('kyc_status', '0');
								$this->db->where('registrationtype', $type);
								$members = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
								$members_arr[] = $members;
							}
						}
					}

					$data['result'] = call_user_func_array('array_merge', $members_arr);
				}
				$total_row = 100;
				$url = base_url() . "admin/kyc/Kyc/pending_allocated_list/";
				$config = pagination_init($url, $total_row, $per_page, 2);
				$this->pagination->initialize($config);
				$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
				$str_links = $this->pagination->create_links();
				//var_dump($str_links);
				$data["links"] = $str_links;

				if (($start + $per_page) > $total_row)
					$end_of_total = $total_row;
				else
					$end_of_total = $start + $per_page;

				if ($total_row)
					$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
				else
					$data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

				$data['index'] = $start + 1;

				$emptylistmsg = ' No records available...!!<br /> <a href=' . base_url() . 'admin/kyc/Kyc/next_pending_allocation_type/>Back</a>';

				/* Start Code To Get Recent Allotted Member Total Count */
				$pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Pending', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
				if (!empty($pagination_total_count)) {
					foreach ($pagination_total_count[0] as $k => $value) {
						if ($k == "pagination_total_count") {
							$data['totalRecCount'] = $value;
						}
						if ($k == "original_allotted_member_id") {
							$data['original_allotted_member_id'] = $value;
						}
					}
				}
				/* Close Code To Get Recent Allotted Member Total Count */

				$data['emptylistmsg']	= $emptylistmsg;
				$data['total_count'] = $count;
				$this->db->distinct('registrationtype');
				$data['mem_type'] = $this->master_model->getRecords('member_registration', array('isactive' => '1'), 'registrationtype', array('registrationtype' => 'ASC'));
				$this->load->view('admin/kyc/pending_alocated_member', $data);
			} else {
				redirect(base_url() . 'admin/kyc/Kyc/pending_allocated_list');
			}
		}
		else
		{

			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '0');

			if($type =='NM')
			{   
				$this->db->select('me.exam_code,member_registration.*');
				$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
				$this->db->where('me.exam_code', $exam_code);
				$this->db->where('me.pay_status', '1');
			}

			$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
			$members = $this->master_model->getRecords("member_registration");
		}
	}

	public function allocation_type()
	{
		$new_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));

		if (count($new_allocated_member_list) > 0) {
			if ($new_allocated_member_list[0]['allotted_member_id'] == '') {
				redirect(base_url() . 'admin/kyc/Kyc/next_allocation_type');
			}
		}
		$kyc_start_date = $this->config->item('kyc_start_date');
		$allocated_member_list = $members = array();
		$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));

		//allocated_count
		if (count($allocated_member_list)) {

			if (count($allocated_member_list) > 0) {
				$data['count'] = $allocated_member_list[0]['allocated_count'];
				$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
			}

			foreach ($arraid as $row) {
				$this->db->select('exam_code');
				$exam_code = $this->master_model->getRecords("member_exam", array('regnumber' => $row,'pay_status'=> '1'));
				$exam_code = $exam_code[0]['exam_code'];


				$this->db->select('registrationtype');
				$reg_type = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
				$type=$reg_type[0]['registrationtype'];


				$this->db->where('kyc_edit', '0');
				$this->db->where('isactive', '1');
				$this->db->where('kyc_status', '0');
				$this->db->where('DATE(createdon)!=', '00-00-0000');//&& 'DATE(createdon)>=', $kyc_start_date removed 

				/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
				if($type =='NM' && $exam_code !='')
				{   
					$this->db->select('me.exam_code,member_registration.*');
					$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber');
					$this->db->where('me.exam_code', $exam_code);
					$this->db->where('me.pay_status', '1');
				}
				// Added exam code condition End Pooja Mane 2023-10-13

				$members = $this->master_model->getRecords("member_registration", array('member_registration.regnumber' => $row));
				$members_arr[] = $members;

			}

			$emptylistmsg = ' ';
			$data['emptylistmsg']	= $emptylistmsg;
			$data['result'] = call_user_func_array('array_merge', $members_arr);
			$data['reset']	= '1'; //flag for reset btn added by pooja mane on 11-04-23

			/* Start Code To Get Recent Allotted Member Total Count */
			$pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
			if (!empty($pagination_total_count)) {
				foreach ($pagination_total_count[0] as $k => $value) {
					if ($k == "pagination_total_count") {
						$data['totalRecCount'] = $value;
					}
					if ($k == "original_allotted_member_id") {
						$data['original_allotted_member_id'] = $value;
					}
				}
			}
			/* Close Code To Get Recent Allotted Member Total Count */

			$this->load->view('admin/kyc/alocated_member', $data);
		} 
		else 
		{
			
			$kyc_start_date = $this->config->item('kyc_start_date');

			$kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'New', 'user_type' => 'recommender'), 'original_allotted_member_id');
			
			if (count($kyc_data) > 0) 
			{
				foreach ($kyc_data  as $row) {
					$allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
				}
			}

			$member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) ");

			if ($member_kyc->num_rows() > 0) {

				$member_kyc_data = $member_kyc->result_array();
				$recommendedmemberarr = array_column($member_kyc_data, 'regnumber');
				/* code end - sagar walzade */
			}
			

			/* to show list  for  3 days back dated data */
			$three_days_back = date('Y-m-d', strtotime("- 3 days"));
			$this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
			$this->db->where('mr.regnumber !=', '');
			$this->db->where('mr.kyc_edit', '0');
			$this->db->where('mr.isactive', '1');
			$this->db->where('mr.kyc_status', '0');
			$this->db->where('mr.registrationtype', 'NM');
			$this->db->where('DATE(mr.createdon) !=', '00-00-0000');
			$this->db->where('DATE(mr.createdon)>=', $kyc_start_date);
			$this->db->where('DATE(mr.createdon)<=', $three_days_back);
			$this->db->where('me.pay_status', '1');
			$this->db->join('member_exam me','me.regnumber = mr.regnumber');
			$this->db->join('exam_master e','e.exam_code = me.exam_code');
			$this->db->group_by('me.exam_code');
			$this->db->order_by('mr.regid','ASC');

			if (count($allocatedmemberarr) > 0) 
			{
				// get the column data in a single array
				$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
			}

			$data_array = array_merge($data_array, $recommendedmemberarr);

			if (count($recommendedmemberarr) > 0) {
				$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $recommendedmemberarr));
			};
			if (count($data_array) > 0) {
				$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
			};

			 $mem_list = $this->master_model->getRecords("member_registration mr");
			 $mem_exm_arr[] = $mem_list;
		 	 $data['mem_exm_arr'] = $mem_exm_arr;

			$this->load->view('admin/kyc/allocation_type',$data);
		}
	}
	////to show the new member list & allocate 100 member  	
	public function allocated_list()
	{
		
		$total_id = $recommendedmemberarr = array();
		$kyc_start_date = $this->config->item('kyc_start_date');

		$data['count'] = 0;
		$tilte = $allocated_count = '';
		$description = $emptylistmsg = '';
		$allocates_arr = $members_arr = $result = $array = $allocated_member_list = $data_array = array();
		$data['result'] = array();
		$regstr = $searchText = $searchBy = '';
		$searchBy_regtype = '';
		$today = date('Y-m-d H:i:s');
		$per_page = 100;
		// $per_page = 10;
		$last = 99;
		$start = 0;
		$list_type = 'New';
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		//$from_date = '';
		//$to_date = '';
		if ($this->input->post('regnumber') != '') {
			$searchBy = $this->input->post('regnumber');
		}
		if ($this->input->post('registrationtype') != '') {
			$searchBy_regtype = $this->input->post('registrationtype');
		}
		$registrationtype = '';
		$data['reg_no'] = ' ';
		if ($page != 0) {
			$start = $page - 1;
		}
		$allocates = array();
		$exam_code = $_POST['select_exm_cd'];

		$process_flag = 0;

		if (isset($_POST['selectby']))
		{

			if($_POST['selectby'] =='NM')
			{
				if(isset($_POST['select_exm_cd']) && $_POST['select_exm_cd']=='Select exam')
				{
					$process_flag = 0;
				}
				elseif(isset($_POST['select_exm_cd']) && $_POST['select_exm_cd']!='')
				{
					$process_flag = 1;
				}
			}else
			{
				$process_flag = 1;
			}

			if($process_flag=='1')
			{
					$type = $_POST['selectby'];
					// Need to travese with all allocated numbers.
					$kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'New'), 'original_allotted_member_id');

					$allocatedmemberarr = array();
					if (count($kyc_data) > 0) 
					{
						foreach ($kyc_data as $row) {
							$allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
						}
					}
					// get all recommended members to todays date
					$member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) ");

					if ($member_kyc->num_rows() > 0) {

						$member_kyc_data = $member_kyc->result_array();
						$recommendedmemberarr = array_column($member_kyc_data, 'regnumber');
						/* code end - sagar walzade */
					}
					/* to show list  for  3 days back dated data */
					$three_days_back = date('Y-m-d', strtotime("- 3 days"));
					$this->db->where('mr.regnumber !=', '');
					$this->db->where('mr.kyc_edit', '0');
					$this->db->where('mr.isactive', '1');
					$this->db->where('mr.kyc_status', '0');
					$this->db->where('mr.registrationtype', $type);
					$this->db->where('DATE(mr.createdon) !=', '00-00-0000');
					$this->db->where('DATE(mr.createdon)>=', $kyc_start_date);
					$this->db->where('DATE(mr.createdon)<=', $three_days_back);

					// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
					if($type =='NM')
					{   
						$this->db->select('exam_code');
						$this->db->join('member_exam me', 'me.regnumber = mr.regnumber','INNER');
						$this->db->where('me.exam_code', $exam_code);
						$this->db->where('me.pay_status', '1');
						$this->db->order_by('me.id','DESC');
					}
					// Added exam code condition End Pooja Mane 2023-10-13

				if (count($allocatedmemberarr) > 0) {
					// get the column data in a single array
					$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
				}

				$data_array = array_merge($data_array, $recommendedmemberarr);
				if (count($data_array) > 0) {
					$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
				};

				$members = $this->master_model->getRecords("member_registration mr", array('mr.isactive' => '1'), 'mr.*', array('mr.regid' => 'ASC'), $start, $per_page);


				$data['start'] = $start;

				$today = date("Y-m-d H:i:s");
				$row_count = $this->master_model->getRecordCount("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New'));

				if ($row_count == 0) 
				{
						$regstr = '';
						/* 
							Change by : Sagar Walzade (12-5-2022) - code start
							above loop removed due to page speed issue and we have used array_column function instead.
						*/
						$allocates_arr = array_column($members, 'regnumber');

						/* code end - sagar walzade */

						$allocated_count = count($allocates_arr);
						if (count($allocates_arr) > 0) {
							$regstr = implode(',', $allocates_arr);
						}
					if ($regstr != '') 
					{
						$insert_data = array(
							'user_type'			=> $this->session->userdata('role'),
							'user_id'				=> $this->session->userdata('kyc_id'),
							'allotted_member_id'	=> $regstr,
							'original_allotted_member_id'	=> $regstr,
							'allocated_count'     => $allocated_count,
							'allocated_list_count'     => '1',
							'date'	                => $today,
							'list_type'             => $list_type,
							'pagination_total_count ' => $allocated_count
						);

						$this->master_model->insertRecord('admin_kyc_users', $insert_data);
						//log activity 
						$tilte = 'Recommender New member list allocation';
						$description = 'Recommender has allocated ' . count($allocates_arr) . ' member';
						$user_id = $this->session->userdata('kyc_id');
						$this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);
					}
				}
			}
			else
			{
				$this->session->set_flashdata('error','Please select exam!!');
				redirect(base_url() . 'admin/kyc/Kyc/allocation_type');
			}
		}

		$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));

		//allocated_count
		if (count($allocated_member_list) > 0) 
		{
			$data['count'] = $allocated_member_list[0]['allocated_count'];
			$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);

			if (count($arraid) > 0) 
			{
				if ($searchBy != '' || $searchBy_regtype != '') 
				{
					if ($searchBy != '' && $searchBy_regtype != '') {
						$this->db->where('regnumber', $searchBy);
						$this->db->where('registrationtype', $searchBy_regtype);
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
					}
					///search by registration number
					else if ($searchBy != '') {
						$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
						$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
						$this->db->where('regnumber', $searchBy);
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
						//$row=$searchBy;
					}
					///search by registration type
					else if ($searchBy_regtype != '') {
						$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
						$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
						$this->db->where('registrationtype', $searchBy_regtype);
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
					}

					
					if (count($members) > 0) {
						foreach ($members as $row) {
							$members_arr[][] = $row;
						}
					}
				} else 
				{
					if (isset($_POST['reset'])) {
						$data['reset'] = $reset = '1'; //flag for reset btn added by pooja mane on 11-04-23
					}

					//search by pooja mane : 28-02-2023
					if (isset($_POST['btnSearch'])) {
						$key = $_POST['searchBy'];
						$value = str_replace(' ', '', $_POST['SearchVal']);

						if ($key == '01' && !empty($value)) {
							$this->db->where("regnumber = '$value'");
						}
					} //search end pooja mane : 28-02-2023

					if (in_array($value, $arraid))
					{
					  	$this->session->set_flashdata('success', $value . ' present in the current list');
					}
					else
					{
					    $today = date('Y-m-d');

					    $kyc_data = $this->db->query("SELECT * FROM admin_kyc_users WHERE user_type = 'recommender' AND DATE(date) LIKE '%$today%' AND find_in_set('".$value."', original_allotted_member_id)")->result_array();

					    if (count($kyc_data) > 0) 
						{
							$this->session->set_flashdata('error', $value . ' Member already allocated to other user');
						}
						
					}

					/* to show list  for  3 days back dated data */
					$three_days_back = date('Y-m-d', strtotime("- 3 days"));
					$this->db->where('kyc_edit', '0');
					$this->db->where('isactive', '1');
					$this->db->where('kyc_status', '0');
					$this->db->where('DATE(createdon) !=', '00-00-0000');
					$this->db->where('DATE(createdon)>=', $kyc_start_date);
					$this->db->where('DATE(createdon)<=', $three_days_back);
					/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
					if($type =='NM')
					{   
						$this->db->select('me.exam_code,member_registration.*');
						$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
						$this->db->where('me.exam_code', $exam_code);
						$this->db->where('me.pay_status', '1');
					}
					// Added exam code condition End Pooja Mane 2023-10-13
					$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
					$members = $this->master_model->getRecords("member_registration");

					//if member not present in alloted list- pooja mane : 28-02-2023
					if (isset($_POST['btnSearch'])) {
						if (count($members) == 0) {

							//check if alredy sent for kyc by 
							$members = $this->master_model->getRecords("member_kyc", array('regnumber' => $value, 'kyc_state'=>'1'));

							$kyc_done = $this->master_model->getRecords("member_registration", array('kyc_status' => '1', 'regnumber' => $value));//check is kyc is done

							$mem_exist= $this->master_model->getRecords("member_registration", array('regnumber' => $value,'isactive'=>'1'));//check if member does not exist

							if (count($kyc_done)) {
								$this->session->set_flashdata('success', 'KYC of ' . $value . ' this record is completed');

								/* to show list  for  3 days back dated data */
								$three_days_back = date('Y-m-d', strtotime("- 3 days"));
								$this->db->where('kyc_edit', '0');
								$this->db->where('isactive', '1');
								$this->db->where('kyc_status', '0');
								$this->db->where('DATE(createdon) !=', '00-00-0000');
								$this->db->where('DATE(createdon)>=', $kyc_start_date);
								$this->db->where('DATE(createdon)<=', $three_days_back);
								/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
								if($type =='NM')
								{   
									$this->db->select('me.exam_code,member_registration.*');
									$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
									$this->db->where('me.exam_code', $exam_code);
									$this->db->where('me.pay_status', '1');
								}
								// Added exam code condition End Pooja Mane 2023-10-13
								$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
								$members = $this->master_model->getRecords("member_registration");
								echo'4';echo $this->db->last_query().'<br><br>';
							} elseif (count($members)) {
								$this->session->set_flashdata('success', $value . ' this record is already submitted for KYC');

								/* to show list  for  3 days back dated data */
								$three_days_back = date('Y-m-d', strtotime("- 3 days"));
								$this->db->where('kyc_edit', '0');
								$this->db->where('isactive', '1');
								$this->db->where('kyc_status', '0');
								$this->db->where('DATE(createdon) !=', '00-00-0000');
								$this->db->where('DATE(createdon)>=', $kyc_start_date);
								$this->db->where('DATE(createdon)<=', $three_days_back);
								/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
								if($type =='NM')
								{   
									$this->db->select('me.exam_code,member_registration.*');
									$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
									$this->db->where('me.exam_code', $exam_code);
									$this->db->where('me.pay_status', '1');
								}
								// Added exam code condition End Pooja Mane 2023-10-13
								$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
								$members = $this->master_model->getRecords("member_registration");

							} elseif(count($mem_exist) == 0){

								$this->session->set_flashdata('error', '' . $value . ' Member number does not exist');
							}
							else {
								
								$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
								$today = date('Y-m-d');
							    $kyc_data = $this->db->query("SELECT * FROM admin_kyc_users WHERE user_type = 'recommender' AND DATE(date) LIKE '%$today%' AND find_in_set('".$value."', original_allotted_member_id)")->result_array();

							    if (count($kyc_data) > 0) 
								{
									$this->session->set_flashdata('error', $value . ' Member already allocated to other user');
								}
								elseif (!in_array($value, $arrstr && count($kyc_data) < 0)) {
									array_push($arrstr, $value);
									$this->session->set_flashdata('success', $value . ' Member added to the current list');
								}

								$allotted_member_id = implode(',', $arrstr);

								$update_data = array(
									'allotted_member_id' => $allotted_member_id
								);

								//Update searched member in alloted list- pooja mane : 02-02-2023
								$arr = $this->master_model->updateRecord('admin_kyc_users', $update_data, array('DATE(date)' => date('Y-m-d'),'list_type'=>'New','user_type'=>'recommender', 'user_id' => $this->session->userdata('kyc_id')));

								//log search member addition activity : pooja mane : 23-05-2023
								$tilte = 'Member added through custom search';
								$description = 'Recommender has added ' . $value . ' member';
								$user_id = $this->session->userdata('kyc_id');
								$result = $this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);

								//removed 3 days buffer condition
								if (count($kyc_data) == 0) 
								{
									$this->db->where("regnumber = '$value'");
								}
								
								$this->db->where('isactive', '1');
								$this->db->where('kyc_status', '0');
								$this->db->where('DATE(createdon) !=', '00-00-0000');
								/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
								if($type =='NM')
								{   
									$this->db->select('me.exam_code,member_registration.*');
									$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
									$this->db->where('me.exam_code', $exam_code);
									$this->db->where('me.pay_status', '1');
								}
								// Added exam code condition End Pooja Mane 2023-10-13
								$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arrstr));
								$members = $this->master_model->getRecords("member_registration");

							}
						}
					}
				}
			}

			foreach($members as $member)
			{
				
				$this->db->select('exam_code');
				$exam_code = $this->master_model->getRecords("member_exam", array('regnumber' =>$member['regnumber'],'pay_status'=> '1'));
				$exam_code = $exam_code[0]['exam_code'];

				$this->db->select('registrationtype');
				$reg_type = $this->master_model->getRecords("member_registration", array('regnumber' =>$member['regnumber']));
				$type=$reg_type[0]['registrationtype'];

				/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-18
				if($type =='NM' && $exam_code !='')
				{   
					$this->db->select('me.exam_code,member_registration.*');
					$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
					$this->db->where('me.exam_code', $exam_code);
					$this->db->where('me.pay_status', '1');
				}
				// Added exam code condition End Pooja Mane 2023-10-18
				$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
					$members_arr = $this->master_model->getRecords("member_registration");
			}
			//print_r($members);//die;
			//$data['result'] = $members;
		}
		$data['result'] = $members;
		//print_r($data['result']);//die;
		$total_row = 100;
		$url = base_url() . "admin/kyc/Kyc/allocated_list/";
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
			<li>Search</li>
			</ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;
		if (($start + $per_page) > $total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start + $per_page;
		if ($total_row)
			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
		else
			$data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';
		$data['index'] = $start + 1;
		//print_r(array_count_values($no));exit;
		
		/* Start Code To Get Recent Allotted Member Total Count */
		$pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
		if (!empty($pagination_total_count)) {
			foreach ($pagination_total_count[0] as $k => $value) {
				if ($k == "pagination_total_count") {
					$data['totalRecCount'] = $value;
				}
				if ($k == "original_allotted_member_id") {
					$data['original_allotted_member_id'] = $value;
				}
			}
		}
		/* Close Code To Get Recent Allotted Member Total Count */
		$emptylistmsg = ' No records available...!!<br />
			<a href=' . base_url() . 'admin/kyc/Kyc/allocation_type/>Back</a>';
		$data['emptylistmsg']	= $emptylistmsg;
		$data['total_count'] = $allocated_count;
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration', array('isactive' => '1'), 'registrationtype', array('registrationtype' => 'ASC'));
		// echo'<pre>';print_r($data['result']);die;
		$this->load->view('admin/kyc/alocated_member', $data);
	}
	public function next_allocation_type()
	{
		
		 $kyc_start_date = $this->config->item('kyc_start_date');
		 $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'New', 'user_type' => 'recommender'), 'original_allotted_member_id');
					
			if (count($kyc_data) > 0) 
			{
				foreach ($kyc_data  as $row) {
					$allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
				}
			}
			
			$member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) ");

				if ($member_kyc->num_rows() > 0) {

					$member_kyc_data = $member_kyc->result_array();
					$recommendedmemberarr = array_column($member_kyc_data, 'regnumber');
					/* code end - sagar walzade */
				}

				if (count($regnumbers) > 0) {
					$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $regnumbers));
				};

				/* to show list  for  3 days back dated data */
				$three_days_back = date('Y-m-d', strtotime("- 3 days"));
				$this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
				$this->db->where('mr.regnumber !=', '');
				$this->db->where('mr.kyc_edit', '0');
				$this->db->where('mr.isactive', '1');
				$this->db->where('mr.kyc_status', '0');
				$this->db->where('mr.registrationtype', 'NM');
				$this->db->where('DATE(mr.createdon) !=', '00-00-0000');
				$this->db->where('DATE(mr.createdon)>=', $kyc_start_date);
				$this->db->where('DATE(mr.createdon)<=', $three_days_back);
				$this->db->where('me.pay_status', '1');
				$this->db->join('member_exam me','me.regnumber = mr.regnumber');
				$this->db->join('exam_master e','e.exam_code = me.exam_code');
				$this->db->group_by('me.exam_code');
				$this->db->order_by('mr.regid','ASC');

				if (count($allocatedmemberarr) > 0) {
						// get the column data in a single array
						$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
					}

				$data_array = array_merge($data_array, $recommendedmemberarr);

				if (count($data_array) > 0) {
					$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
				};

				 $mem_list = $this->master_model->getRecords("member_registration mr");

 			$next_mem_exm_arr[] = $mem_list;
			$data['next_mem_exm_arr'] = $next_mem_exm_arr;

			$this->load->view('admin/kyc/next_allocation_type',$data);
	}
	//to get next 100 allocation ...for  same day
	public function next_allocated_list()
	{
		
		if (isset($_POST['selectby'])) {
			$kyc_start_date = $this->config->item('kyc_start_date');
			$type = $_POST['selectby'];
			$data['count'] = 0;
			$tilte = $allocated_count = $emptylistmsg = $allotted_member_id = '';
			$description = '';
			$allocates_arr = $members_arr = $result = $array = $allocated_member_list = $recommendedmemberarr = array();
			$data['result'] = array();
			$regstr = $searchText = $searchBy = '';
			$searchBy_regtype = '';
			$today = date('Y-m-d H:i:s');
			$per_page = 100;
			// $per_page = 10;
			$last = 99;
			// $last = 9;
			$start = 0;
			$list_type = 'New';
			$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
			$check = $kyc_data = array();
			$date = date("Y-m-d H:i:s");
			$allocatedmemberarr = array();
			$exam_code = $_POST['select_exm_cd'];
			$process_flag = 0;

			$check = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));

			if (count($check)) 
			{
				if ($check[0]['allotted_member_id'] == '') 
				{
					$kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'New', 'user_type' => 'recommender'), 'original_allotted_member_id');
					
					if (count($kyc_data) > 0) 
					{
						foreach ($kyc_data  as $row) {
							$allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
						}
					}

					// get all recommended members to todays date
					$member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) ");
					
					if ($member_kyc->num_rows() > 0) {

						$member_kyc_data = $member_kyc->result_array();
						$recommendedmemberarr = array_column($member_kyc_data, 'regnumber');
						/* code end - sagar walzade */
					}

					/* to show list  for  3 days back dated data */
					$three_days_back = date('Y-m-d', strtotime("- 3 days")); //date('Y-m-d');//date('Y-m-d', strtotime("- 3 days"));
					$this->db->where('mr.regnumber !=', '');
					$this->db->where('mr.kyc_edit', '0');
					$this->db->where('mr.isactive', '1');
					$this->db->where('mr.kyc_status', '0');
					$this->db->where('mr.registrationtype', $type);
					$this->db->where('DATE(mr.createdon) !=', '00-00-0000');
					$this->db->where('DATE(mr.createdon)>=', $kyc_start_date);
					$this->db->where('DATE(mr.createdon)<=', $three_days_back);

					// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-17
					if($type =='NM')
					{   
						$this->db->select('exam_code');
						$this->db->join('member_exam me', 'me.regnumber = mr.regnumber','INNER');
						$this->db->where('me.exam_code', $exam_code);
						$this->db->where('me.pay_status', '1');
						$this->db->order_by('me.id','DESC');
					}
					// Added exam code condition End Pooja Mane 2023-10-17

					if (count($allocatedmemberarr) > 0) {
						// get the column data in a single array
						$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
					}

					$data_array = array_merge($data_array, $recommendedmemberarr);
					if (count($data_array) > 0) {
						$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
					}

					// SAGAR WALZADE : (Query optimisations) we are excluding members which is present in member_kyc table using left join with regnumber IS NULL
					// 3173 to 3185 & 3203 lines commented due to speed issue. and added below 2 lines instead of that code.
					$this->db->join('member_kyc as mk', 'mr.regnumber = mk.regnumber', 'left');
					$this->db->where('mk.regnumber IS NULL');
					// 3173 to 3185 & 3203 lines commented due to speed issue. and added above 2 lines instead of that code.

					$members = $this->master_model->getRecords("member_registration as mr", "", 'mr.*', array('mr.regid' => 'ASC'), $start, $per_page);

					//array1
					$array_string1 = $check[0]['original_allotted_member_id'];
					$allocates_arr1 = explode(',', $array_string1);
					foreach ($members as $row) {
						$allocates_arr[] .= $row['regnumber'];
						//$reg[] = $row['regnumber'];
						//$regstr .= $row['regnumber'].',';
					}
					$count = count($allocates_arr);
					$allocated_count = $count + $check[0]['allocated_count'];
					if (count($allocates_arr) > 0) {

						$allotted_member_id = implode(',', $allocates_arr);
					}
					$new_array = array_merge($allocates_arr1, $allocates_arr);
					$original_allotted_member_id = implode(',', $new_array);
					//get the  allocated list count
					if ($allotted_member_id == '') {
						$list_count = $check[0]['allocated_list_count'];
					} else {
						$list_count = $check[0]['allocated_list_count'] + 1;
					}
					$update_data = array(
						'user_type'						=> $this->session->userdata('role'),
						'user_id'							=> $this->session->userdata('kyc_id'),
						'allotted_member_id'		=> $allotted_member_id,
						'original_allotted_member_id'	=> $original_allotted_member_id,
						'allocated_count'    		  => $allocated_count,
						'allocated_list_count'     => $list_count,
						'date'	               			  => $today,
						'list_type'            		  => $list_type,
						'pagination_total_count ' => $count,
					);

					$this->db->where('list_type', 'New');
					$this->db->where('user_id', $this->session->userdata('kyc_id'));
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array('DATE(date)' => date('Y-m-d')));
					//log activity 
					$tilte = 'Recommender got next  New member list allocation ';
					$user_id = $this->session->userdata('kyc_id');
					$this->KYC_Log_model->create_log($tilte, $user_id, '', '', serialize($update_data));
				}

				$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));
				
				//allocated_count
				if (count($allocated_member_list) > 0) {
					$data['count'] = $allocated_member_list[0]['allocated_count'];
					$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);

					//$data['result'] = $members;
					//$arraid=explode(',',$allocated_member_list[0]['allotted_member_id']);
					if (count($arraid) > 0) {
						if ($searchBy != '' || $searchBy_regtype != '') {
							if ($searchBy != '' && $searchBy_regtype != '') {
								$this->db->where('regnumber', $searchBy);
								$this->db->where('registrationtype', $searchBy_regtype);
								$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
							}
							///search by registration number
							else if ($searchBy != '') {
								$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
								$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
								$this->db->where('regnumber', $searchBy);
								$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
								//$row=$searchBy;
							}
							///search by registration type
							else if ($searchBy_regtype != '') {
								$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
								$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
								$this->db->where('registrationtype', $searchBy_regtype);
								$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
							}
							if (count($members) > 0) {
								foreach ($members as $row) {
									$members_arr[][] = $row;
								}
							}
						} else {
							//default allocation list for 100 member
							foreach ($arraid as $row) {
								/* to show list  for  3 days back dated data */
								$three_days_back = date('Y-m-d', strtotime("- 3 days")); //date('Y-m-d') ;//date('Y-m-d', strtotime("- 3 days"));
								$this->db->where('kyc_edit', '0');
								$this->db->where('isactive', '1');
								$this->db->where('kyc_status', '0');
								$this->db->where('registrationtype', $type);
								$this->db->where('DATE(createdon) !=', '00-00-0000');
								$this->db->where('DATE(createdon)>=', $kyc_start_date);
								$this->db->where('DATE(createdon)<=', $three_days_back);
								$members = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
								$members_arr[] = $members;
							}
						}
					}

					$data['result'] = call_user_func_array('array_merge', $members_arr);
				}
				$total_row = 100;
				$url = base_url() . "admin/kyc/Kyc/allocated_list/";
				$config = pagination_init($url, $total_row, $per_page, 2);
				$this->pagination->initialize($config);
				$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
				$str_links = $this->pagination->create_links();
				//var_dump($str_links);
				$data["links"] = $str_links;

				if (($start + $per_page) > $total_row)
					$end_of_total = $total_row;
				else
					$end_of_total = $start + $per_page;

				if ($total_row)
					$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
				else
					$data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

				$data['index'] = $start + 1;

				$emptylistmsg = ' No records available...!!<br /> <a href=' . base_url() . 'admin/kyc/Kyc/next_allocation_type/>Back</a>';

				/* Start Code To Get Recent Allotted Member Total Count */
				$pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
				if (!empty($pagination_total_count)) {
					foreach ($pagination_total_count[0] as $k => $value) {
						if ($k == "pagination_total_count") {
							$data['totalRecCount'] = $value;
						}
						if ($k == "original_allotted_member_id") {
							$data['original_allotted_member_id'] = $value;
						}
					}
				}
				/* Close Code To Get Recent Allotted Member Total Count */
				//print_r($data['result']);die;
				$data['emptylistmsg']	= $emptylistmsg;
				$data['total_count'] = $count;
				$this->db->distinct('registrationtype');
				$data['mem_type'] = $this->master_model->getRecords('member_registration', array('isactive' => '1'), 'registrationtype', array('registrationtype' => 'ASC'));
				$this->load->view('admin/kyc/alocated_member', $data);
			} else {
				
				redirect(base_url() . 'admin/kyc/Kyc/allocated_list');
			}
		}
		else
		{

			if (isset($_POST['reset'])) {
				$data['reset'] = $reset = '1'; //flag for reset btn added by pooja mane on 11-04-23
			}

			//search by pooja mane : 28-02-2023
			if (isset($_POST['btnSearch'])) {
				$key = $_POST['searchBy'];
				$value = str_replace(' ', '', $_POST['SearchVal']);

				if ($key == '01' && !empty($value)) {
					$this->db->where("regnumber = '$value'");
				}
			} //search end pooja mane : 28-02-2023

			/* to show list  for  3 days back dated data */
			$three_days_back = date('Y-m-d', strtotime("- 3 days"));
			$this->db->where('kyc_edit', '0');
			$this->db->where('isactive', '1');
			$this->db->where('kyc_status', '0');
			$this->db->where('DATE(createdon) !=', '00-00-0000');
			$this->db->where('DATE(createdon)>=', $kyc_start_date);
			$this->db->where('DATE(createdon)<=', $three_days_back);
			/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
			if($type =='NM')
			{   
				$this->db->select('me.exam_code,member_registration.*');
				$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
				$this->db->where('me.exam_code', $exam_code);
				$this->db->where('me.pay_status', '1');
			}
			// Added exam code condition End Pooja Mane 2023-10-13
			$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
			$members = $this->master_model->getRecords("member_registration");

			if (isset($_POST['btnSearch']) && count($members)) {
				$this->session->set_flashdata('success', $value . ' present in the current list');
			}

					//if member not present in alloted list- pooja mane : 28-02-2023
			if (isset($_POST['btnSearch'])) 
			{
				if (count($members) == 0) {
					//check if alredy sent for kyc by pooja mane : 28-02-2023
					$members = $this->master_model->getRecords("member_kyc", array('regnumber' => $value));
					
					$kyc_done = $this->master_model->getRecords("member_registration", array('kyc_status' => '1', 'regnumber' => $value));
					
					$mem_exist= $this->master_model->getRecords("member_registration", array('regnumber' => $value));
					//print_r($members['kyc_status']);die;
					if(isset($_POST['btnSearch']) && count($mem_exist) == 0)
					{

						$this->session->set_flashdata('error', '' . $value . ' Member number does not exist');
					}
					elseif (count($kyc_done)) 
					{
						$this->session->set_flashdata('success', 'KYC of ' . $value . ' this record is completed');

						/* to show list  for  3 days back dated data */
						$three_days_back = date('Y-m-d', strtotime("- 3 days"));
						$this->db->where('kyc_edit', '0');
						$this->db->where('isactive', '1');
						$this->db->where('kyc_status', '0');
						$this->db->where('DATE(createdon) !=', '00-00-0000');
						$this->db->where('DATE(createdon)>=', $kyc_start_date);
						$this->db->where('DATE(createdon)<=', $three_days_back);
						/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
						if($type =='NM')
						{   
							$this->db->select('me.exam_code,member_registration.*');
							$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
							$this->db->where('me.exam_code', $exam_code);
							$this->db->where('me.pay_status', '1');
						}
						// Added exam code condition End Pooja Mane 2023-10-13
						$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
						$members = $this->master_model->getRecords("member_registration");
						
					} 
					elseif (count($members)) 
					{
						$this->session->set_flashdata('success', $value . ' this record is already submitted for KYC');

						/* to show list  for  3 days back dated data */
						$three_days_back = date('Y-m-d', strtotime("- 3 days"));
						$this->db->where('kyc_edit', '0');
						$this->db->where('isactive', '1');
						$this->db->where('kyc_status', '0');
						$this->db->where('DATE(createdon) !=', '00-00-0000');
						$this->db->where('DATE(createdon)>=', $kyc_start_date);
						$this->db->where('DATE(createdon)<=', $three_days_back);
						/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
						if($type =='NM')
						{   
							$this->db->select('me.exam_code,member_registration.*');
							$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
							$this->db->where('me.exam_code', $exam_code);
							$this->db->where('me.pay_status', '1');
						}
						// Added exam code condition End Pooja Mane 2023-10-13
						$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
						$members = $this->master_model->getRecords("member_registration");
						
					} 
					else 
					{
						
						$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);

						if (!in_array($value, $arrstr)) {
							array_push($arrstr, $value);
							$this->session->set_flashdata('success', $value . ' Member added to the current list');
						}

						$allotted_member_id = implode(',', $arrstr);

						$update_data = array(
							'allotted_member_id' => $allotted_member_id
						);

						//Update searched member in alloted list- pooja mane : 02-02-2023
						$arr = $this->master_model->updateRecord('admin_kyc_users', $update_data, array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id')));

						//log search member addition activity : pooja mane : 23-05-2023
						$tilte = 'Member added through custom search';
						$description = 'Recommender has added ' . $value . ' member';
						$user_id = $this->session->userdata('kyc_id');
						$result = $this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);

						//removed 3 days buffer condition
						//$three_days_back = date('Y-m-d', strtotime("- 3 days"));
						$this->db->where("regnumber = '$value'");
						$this->db->where('kyc_edit', '0');
						$this->db->where('isactive', '1');
						$this->db->where('kyc_status', '0');
						$this->db->where('DATE(createdon) !=', '00-00-0000');
						/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
						if($type =='NM')
						{   
							$this->db->select('me.exam_code,member_registration.*');
							$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
							$this->db->where('me.exam_code', $exam_code);
							$this->db->where('me.pay_status', '1');
						}
						// Added exam code condition End Pooja Mane 2023-10-13
						$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
						$members = $this->master_model->getRecords("member_registration");

						
					}
				}
			}
		}
	}
	//dropdown for edited list
	public function edited_allocation_type()
	{
		$this->db->where('allotted_member_id=', '');
		//$this->db->or_where('original_allotted_member_id=','');

		$edit_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit'));
		//  echo $this->db->last_query();exit;
		//print_r($edit_allocated_member_list);exit;
		if (count($edit_allocated_member_list) > 0) {
			if ($edit_allocated_member_list[0]['allotted_member_id'] == '') {
				redirect(base_url() . 'admin/kyc/Kyc/next_edited_allocation_type');
			}
		}
		
		
		$emptylistmsg = '';
		$kyc_start_date = $this->config->item('kyc_start_date');
		$edited_member_list = $members = array();
		$edited_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''));
		
		//allocated_count
		if (count($edited_member_list)) {
				//print_r($edited_member_list);die;
			if (count($edited_member_list) > 0) {
				$data['count'] = $edited_member_list[0]['allocated_count'];
				$arraid = explode(',', $edited_member_list[0]['allotted_member_id']);
			}
			foreach ($arraid as $row) {
				
				$this->db->select('exam_code');
				$exam_code = $this->master_model->getRecords("member_exam", array('regnumber' => $row,'pay_status'=> '1'));
				$exam_code = $exam_code[0]['exam_code'];
				//print_r($exam_code);die;

				$this->db->select('registrationtype');
				$reg_type = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
				$type=$reg_type[0]['registrationtype'];

				$this->db->where('kyc_edit', '1');
				$this->db->where('isactive', '1');
				$this->db->where('kyc_status', '0');
				$this->db->where('DATE(createdon)!=', '00-00-0000');//&& 'DATE(createdon)>=', $kyc_start_date removed 

				/// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
				if($type =='NM' && $exam_code !='')
				{   
					$this->db->select('me.exam_code,member_registration.*');
					$this->db->join('member_exam me', 'me.regnumber = member_registration.regnumber','INNER');
					$this->db->where('me.exam_code', $exam_code);
					$this->db->where('me.pay_status', '1');
				}
				// Added exam code condition End Pooja Mane 2023-10-13
				$this->db->where_in('member_registration.regnumber', array_map('stripslashes', $arraid));
				$members = $this->master_model->getRecords("member_registration", array('member_registration.regnumber' => $row));
				$members_arr[] = $members;
				// echo $this->db->last_query();die;
				// print_r($members_arr);die;
			}

			/* Start Code To Get Recent Allotted Member Total Count */
			$pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
			if (!empty($pagination_total_count)) {
				foreach ($pagination_total_count[0] as $k => $value) {
					if ($k == "pagination_total_count") {
						$data['totalRecCount'] = $value;
					}
					if ($k == "original_allotted_member_id") {
						$data['original_allotted_member_id'] = $value;
					}
				}
			}
			/* Close Code To Get Recent Allotted Member Total Count */

			$data['result'] = call_user_func_array('array_merge', $members_arr);
			// if(get_client_ip() == '115.124.115.75'){
			// 	print_r($data['result']);
			// }
			//print_r($data['result']);//die;
			$this->load->view('admin/kyc/edited_list', $data);
		} 
		else 
		{

			//EXAM WISE ALLOCATION FOR NON-MEM ADDED BY POOJA MANE:2023-10-16
		 	$kyc_start_date = $this->config->item('kyc_start_date');
			// get all recommended members to todays date
            $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d')), 'allotted_member_id');

			$allocatedmemberarr = array();
			if (count($kyc_data) > 0) {
				foreach ($kyc_data as $row) {
					$allocatedmemberarr[] = explode(',', $row['allotted_member_id']);
				}
			}

			$member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) and kyc_state=1");

			if ($member_kyc->num_rows() > 0) {
				foreach ($member_kyc->result_array()  as $row) {
					$recommendedmemberarr[] = $row['regnumber'];
				}
			}

	        if (count($allocatedmemberarr) > 0) {
				// get the column data in a single array
				$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
			}
			// merge allocated member array with recommended members array
			//$data_array = array_merge($data_array, $recommendedmemberarr);
			if (count($recommendedmemberarr) > 0) {
				$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $recommendedmemberarr));
			}
			if (count($data_array) > 0) {
				$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
			}

	        $three_days_back = date('Y-m-d', strtotime("- 3 days"));
	        $this->db->select('e.exam_code,e.description, COUNT(DISTINCT(mr.regnumber)) as count');
			$this->db->where('mr.regnumber !=', '');
			$this->db->where('mr.kyc_edit', '1');
			$this->db->where('mr.isactive', '1');
			$this->db->where('mr.kyc_status', '0');
			$this->db->where('mr.registrationtype', 'NM');
			$this->db->where('DATE(mr.editedon) !=', '00-00-0000');
			//$this->db->where('DATE(mr.createdon)>=', $kyc_start_date);
			// $this->db->where('DATE(mr.createdon)<=', $three_days_back);
			$this->db->where('me.pay_status', '1');
			$this->db->join('member_exam me','me.regnumber = mr.regnumber');
			$this->db->join('exam_master e','e.exam_code = me.exam_code');
			$this->db->group_by('me.exam_code');
			$this->db->order_by('mr.regid','ASC');

 			$mem_list = $this->master_model->getRecords("member_registration mr");

			 $edited_mem_exm_arr[] = $mem_list;
			 $data['edited_mem_exm_arr'] = $edited_mem_exm_arr;
			 
			$this->load->view('admin/kyc/edited_allocation_type',$data);
		}
	}

	public function edited_list()
	{
		$type = '';
		$tilte = '';
		$description = $emptylistmsg = '';
		$allocates_arr = $members_arr = $result = $array = $kyc_data1 = $kyc_data2 = $kyc_data = array();
		$data['result'] = $recommendedmemberarr = $data_array = array();
		$regstr = $searchText = $searchBy = '';
		$searchBy_regtype = '';
		$today = date('Y-m-d H:i:s');
		$per_page = 100;
		// $per_page = 10;
		$last = 99;
		// $last = 9;
		$start = $data['count'] = '0';
		$list_type = 'Edit';
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		if ($this->input->post('regnumber') != '') {
			$searchBy = $this->input->post('regnumber');
		}
		if ($this->input->post('registrationtype') != '') {
			$searchBy_regtype = $this->input->post('registrationtype');
		}

		$registrationtype = '';
		$data['reg_no'] = ' ';

		if ($page != 0) {
			$start = $page - 1;
		}
		
		$exam_code = $_POST['select_exm_cd'];
		$allocates = array();
		//get  all  user loging today 
		if (isset($_POST['selectby'])) {
			$type = $_POST['selectby'];
			//$kyc_data = $this->master_model->getRecords("admin_kyc_users",array('DATE(date)'=>date('Y-m-d'),'list_type'=>'Edit' ),'allotted_member_id');
			$kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d')), 'allotted_member_id');

			$allocatedmemberarr = array();
			if (count($kyc_data) > 0) {
				foreach ($kyc_data as $row) {
					$allocatedmemberarr[] = explode(',', $row['allotted_member_id']);
				}
			}

			$member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) and kyc_state=1");
			//$member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) ");

			if ($member_kyc->num_rows() > 0) {
				foreach ($member_kyc->result_array()  as $row) {
					$recommendedmemberarr[] = $row['regnumber'];
				}
			}

			/* to show list  for  3 days back dated data */
			//$three_days_back = date('Y-m-d', strtotime("-3 days")); // ## condition removed by chaitali on 2021-10-15 on rucha mail. date('Y-m-d', strtotime("-3 days"));

			$this->db->where('mr.kyc_edit', '1');
			$this->db->where('mr.isactive', '1');
			$this->db->where('mr.kyc_status', '0');
			$this->db->where('mr.registrationtype', $type);
			$this->db->where('DATE(mr.editedon) !=', '00-00-0000');
			// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
			if($type =='NM')
			{   
				$this->db->select('exam_code');
				$this->db->join('member_exam me', 'me.regnumber = mr.regnumber','INNER');
				$this->db->where('me.exam_code', $exam_code);
				$this->db->where('me.pay_status', '1');
				$this->db->group_by('me.regnumber');
				$this->db->order_by('id','DESC');
			}
			
			if (count($allocatedmemberarr) > 0) {
				// get the column data in a single array
				$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
				
			}
			
			if (count($recommendedmemberarr) > 0) {
				$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $recommendedmemberarr));
			}

			//$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'), '', array('regid' => 'DESC'), $start, $per_page);
			$members = $this->master_model->getRecords("member_registration as mr", "", 'mr.*', array('mr.regid' => 'ASC','isactive' => '1'), $start, $per_page);

			$today = date("Y-m-d");
			$row_count = $this->master_model->getRecordCount("admin_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit'));
			
			if ($row_count == 0) {
				$regstr = '';
				foreach ($members as $row) {
					$regnumber = $row['regnumber'];
					$editedon = $row['editedon'];
					$images_editedon = $row['images_editedon'];

					// get recent recommended record of this member
					$kyc_data = $this->master_model->getRecords("member_kyc", array('regnumber' => $regnumber, 'kyc_state' => '2'), 'DATE(recommended_date),regnumber', array('kyc_id' => 'DESC'), '', 1);
					
					// check if record exist
					if (!empty($kyc_data)) {


						$recommended_date = $kyc_data[0]['DATE(recommended_date)'];

						if (strtotime($editedon) >= strtotime($recommended_date) || strtotime($images_editedon) >= strtotime($recommended_date)) {
							$allocates_arr[] = $regnumber;
						}
					} else {
						$allocates_arr[] = $regnumber;
					}


					//print_r($allocates_arr);exit;
					// check if user get alloctaed 100 members
					if (count($allocates_arr)  == 100) {
						break;
					}

				}

				//print_r($allocates_arr); die();
				$allocated_count = count($allocates_arr);
				if (count($allocates_arr)  > 0) {
					$regstr = implode(',', $allocates_arr);
				}
				//	print_r($regstr);exit;
				if ($regstr != '') {	// insert the allocated array list in table
					$insert_data = array(
						'user_type'			=> $this->session->userdata('role'),
						'user_id'				=> $this->session->userdata('kyc_id'),
						'allotted_member_id'	=> $regstr,
						'original_allotted_member_id'	=> $regstr,
						'allocated_count'     => $allocated_count,
						'allocated_list_count'     => '1',
						'date'	                => $today,
						'list_type'             => $list_type,
						'pagination_total_count ' => $allocated_count

					);
					$this->master_model->insertRecord('admin_kyc_users', $insert_data);
					//log activity 
					$tilte = 'Edited member list allocation';
					$description = 'Recommender has allocated ' . count($allocates_arr) . ' member';
					$user_id = $this->session->userdata('kyc_id');
					$this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);
				}
			}
		}

		//allocated_member_list
		$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''));
		
		if (count($allocated_member_list) > 0) {
			$data['count'] = $allocated_member_list[0]['allocated_count'];
			$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
			
			if (count($arraid) > 0) 
			{
				if ($searchBy != '' || $searchBy_regtype != '') 
				{
					if ($searchBy != '' && $searchBy_regtype != '') {
						$this->db->where('regnumber', $searchBy);
						$this->db->where('registrationtype', $searchBy_regtype);
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
						
					}
					///search by registration number
					else if ($searchBy != '') {
						$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
						$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
						$this->db->where('regnumber', $searchBy);
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
						
						//$row=$searchBy;
					}
					///search by registration type
					else if ($searchBy_regtype != '') {
						$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
						$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
						$this->db->where('registrationtype', $searchBy_regtype);
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
						
					}

					if (count($members) > 0) {
						foreach ($members as $row) {
							$members[][] = $row;
						}
					}
				}
				else
				{
					foreach ($arraid as $row) 
					{
						$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
						if($type =='NM')
						{   
							$this->db->select('exam_code,mr.*');
							$this->db->join('member_exam me', 'me.regnumber = mr.regnumber','INNER');
							$this->db->where('me.exam_code', $exam_code);
							$this->db->where('me.pay_status', '1');
							$this->db->group_by('me.regnumber');
							$this->db->order_by('id','DESC');
						}
						$this->db->where('mr.kyc_edit', '1');
						$this->db->where('mr.isactive', '1');
						$this->db->where('mr.kyc_status', '0');
						$this->db->where('mr.registrationtype', $type);
						$this->db->where('DATE(mr.editedon) !=', '00-00-0000');
						$this->db->where_in('mr.regnumber', array_map('stripslashes', $arrstr));
						$members = $this->master_model->getRecords("member_registration mr", array('mr.regnumber' => $row));
						$members_arr[] = $members;

					}
				}

			}
			
			$data['result'] = call_user_func_array('array_merge', $members_arr);
			
		
		}
		
		$total_row = 100;
		$url = base_url() . "admin/kyc/Kyc/edited_list/";
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;

		if (($start + $per_page) > $total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start + $per_page;

		if ($total_row)
			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
		else
			$data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

		$data['index'] = $start + 1;
		$emptylistmsg = ' No records available...!!<br />
	   <a href=' . base_url() . 'admin/kyc/Kyc/edited_allocation_type/>Back</a>';

		/* Start Code To Get Recent Allotted Member Total Count */
		$pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
		if (!empty($pagination_total_count)) {
			foreach ($pagination_total_count[0] as $k => $value) {
				if ($k == "pagination_total_count") {
					$data['totalRecCount'] = $value;
				}
				if ($k == "original_allotted_member_id") {
					$data['original_allotted_member_id'] = $value;
				}
			}
		}
		/* Close Code To Get Recent Allotted Member Total Count */

		$data['emptylistmsg']	= $emptylistmsg;
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration', array('isactive' => '1'), 'registrationtype', array('registrationtype' => 'ASC'));


		$this->load->view('admin/kyc/edited_list', $data);
	}

	public function next_edited_allocation_type()
	{
		//EXAM WISE ALLOCATION FOR NON-MEM ADDED BY POOJA MANE:2023-10-16
	 	$kyc_start_date = $this->config->item('kyc_start_date');
		// get all recommended members to todays date
        $kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d')), 'allotted_member_id');

		$allocatedmemberarr = array();
		if (count($kyc_data) > 0) {
			foreach ($kyc_data as $row) {
				$allocatedmemberarr[] = explode(',', $row['allotted_member_id']);
			}
		}

		$member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) and kyc_state=1");

		if ($member_kyc->num_rows() > 0) {
			foreach ($member_kyc->result_array()  as $row) {
				$recommendedmemberarr[] = $row['regnumber'];
			}
		}

        if (count($allocatedmemberarr) > 0) {
			// get the column data in a single array
			$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
		}
		// merge allocated member array with recommended members array
		//$data_array = array_merge($data_array, $recommendedmemberarr);
		if (count($recommendedmemberarr) > 0) {
			$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $recommendedmemberarr));
		}
		if (count($data_array) > 0) {
			$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
		}

        $three_days_back = date('Y-m-d', strtotime("- 3 days"));
        $this->db->select('e.exam_code,e.description, COUNT(mr.regnumber) as count');
		$this->db->where('mr.regnumber !=', '');
		$this->db->where('mr.kyc_edit', '1');
		$this->db->where('mr.isactive', '1');
		$this->db->where('mr.kyc_status', '0');
		$this->db->where('mr.registrationtype', 'NM');
		$this->db->where('DATE(mr.editedon) !=', '00-00-0000');
		//$this->db->where('DATE(mr.createdon)>=', $kyc_start_date);
		// $this->db->where('DATE(mr.createdon)<=', $three_days_back);
		$this->db->where('me.pay_status', '1');
		$this->db->join('member_exam me','me.regnumber = mr.regnumber');
		$this->db->join('exam_master e','e.exam_code = me.exam_code');
		$this->db->group_by('me.exam_code');
		$this->db->order_by('mr.regid','ASC');

		$mem_list = $this->master_model->getRecords("member_registration mr");
		$next_edit_exm_arr[] = $mem_list;
		$data['next_edit_exm_arr'] = $next_edit_exm_arr;


		$this->load->view('admin/kyc/next_edited_allocation_type',$data);
	}
	
	public function next_edited_list()
	{
		//print_r($_POST);//die;
		if (isset($_POST['selectby'])) {
			$kyc_start_date = $this->config->item('kyc_start_date');
			$type = $_POST['selectby'];
			$data['count'] = 0;
			$tilte = $allocated_count = $emptylistmsg = $allotted_member_id = '';
			$description = '';
			$allocates_arr = $members_arr = $result = $array = $allocated_member_list = $recommendedmemberarr = array();
			$data['result'] = array();
			$regstr = $searchText = $searchBy = '';
			$searchBy_regtype = '';
			$today = date('Y-m-d H:i:s');
			$per_page = 100;
			// $per_page = 10;
			$last = 99;
			// $last = 9;
			$start = 0;
			$list_type = 'Edit';
			$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
			$check = $kyc_data = array();
			$date = date("Y-m-d H:i:s");
			$allocatedmemberarr = array();
			$exam_code = $_POST['select_exm_cd'];
			$process_flag = 0;

			$check = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id' => ''));

			if (count($check)) 
			{
				if ($check[0]['allotted_member_id'] == '') 
				{
					$kyc_data = $this->master_model->getRecords("admin_kyc_users", array('DATE(date)' => date('Y-m-d')), 'allotted_member_id');

			$allocatedmemberarr = array();
			if (count($kyc_data) > 0) {
				foreach ($kyc_data as $row) {
					$allocatedmemberarr[] = explode(',', $row['allotted_member_id']);
				}
			}

			$member_kyc = $this->db->query("SELECT regnumber,kyc_id FROM member_kyc WHERE kyc_id IN ( SELECT MAX(kyc_id) FROM member_kyc GROUP BY regnumber ) and kyc_state=1");


			if ($member_kyc->num_rows() > 0) {
				foreach ($member_kyc->result_array()  as $row) {
					$recommendedmemberarr[] = $row['regnumber'];
				}
			}


			/* to show list  for  3 days back dated data */
			$three_days_back = date('Y-m-d', strtotime("-3 days")); // ## condition removed by chaitali on 2021-10-15 on rucha mail. date('Y-m-d', strtotime("-3 days"));

			$this->db->where('mr.kyc_edit', '1');
			$this->db->where('mr.isactive', '1');
			$this->db->where('mr.kyc_status', '0');
			$this->db->where('mr.registrationtype', $type);
			$this->db->where('DATE(mr.editedon) !=', '00-00-0000');
			//$this->db->where('DATE(editedon)<=',$three_days_back);	
			//$this->db->or_where('DATE(images_editedon)<=',$three_days_back);
			// Added exam code condition for NM exam wise allocation Pooja Mane 2023-10-13
			if($type =='NM')
			{   
				$this->db->select('exam_code');
				$this->db->join('member_exam me', 'me.regnumber = mr.regnumber','INNER');
				$this->db->where('me.exam_code', $exam_code);
				$this->db->where('me.pay_status', '1');
				$this->db->group_by('me.regnumber');
				$this->db->order_by('id','DESC');
			}
			// Added exam code condition End Pooja Mane 2023-10-13


			if (count($allocatedmemberarr) > 0) {
				// get the column data in a single array
				$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
				/*if(count($data_array) > 0)
					{
						$this->db->where_not_in('regnumber',array_map('stripslashes', $data_array));
					}*/
			}
			// merge allocated member array with recommended members array
			$data_array = array_merge($data_array, $recommendedmemberarr);
			if (count($data_array) > 0) {
				$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
			}

			//$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'), '', array('regid' => 'DESC'), $start, $per_page);
			$members = $this->master_model->getRecords("member_registration as mr", "", 'mr.*', array('mr.regid' => 'ASC','isactive' => '1'), $start, $per_page);

					//array1
					$array_string1 = $check[0]['original_allotted_member_id'];
					$allocates_arr1 = explode(',', $array_string1);
					foreach ($members as $row) {
						$allocates_arr[] .= $row['regnumber'];
						//$reg[] = $row['regnumber'];
						//$regstr .= $row['regnumber'].',';
					}
					$count = count($allocates_arr);
					$allocated_count = $count + $check[0]['allocated_count'];
					if (count($allocates_arr) > 0) {

						$allotted_member_id = implode(',', $allocates_arr);
					}
					$new_array = array_merge($allocates_arr1, $allocates_arr);
					$original_allotted_member_id = implode(',', $new_array);
					//get the  allocated list count
					if ($allotted_member_id == '') {
						$list_count = $check[0]['allocated_list_count'];
					} else {
						$list_count = $check[0]['allocated_list_count'] + 1;
					}
					$update_data = array(
						'user_type'						=> $this->session->userdata('role'),
						'user_id'							=> $this->session->userdata('kyc_id'),
						'allotted_member_id'		=> $allotted_member_id,
						'original_allotted_member_id'	=> $original_allotted_member_id,
						'allocated_count'    		  => $allocated_count,
						'allocated_list_count'     => $list_count,
						'date'	               			  => $today,
						'list_type'            		  => $list_type,
						'pagination_total_count ' => $count,
					);

					$this->db->where('list_type', 'Edit');
					$this->db->where('user_id', $this->session->userdata('kyc_id'));
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array('DATE(date)' => date('Y-m-d')));
					//log activity 
					$tilte = 'Recommender got next  New member list allocation ';
					$user_id = $this->session->userdata('kyc_id');
					$this->KYC_Log_model->create_log($tilte, $user_id, '', '', serialize($update_data));
				}

				$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''));

				//allocated_count
				if (count($allocated_member_list) > 0) {
					$data['count'] = $allocated_member_list[0]['allocated_count'];
					$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);

					//$data['result'] = $members;
					//$arraid=explode(',',$allocated_member_list[0]['allotted_member_id']);
					if (count($arraid) > 0) {
						if ($searchBy != '' || $searchBy_regtype != '') {
							if ($searchBy != '' && $searchBy_regtype != '') {
								$this->db->where('regnumber', $searchBy);
								$this->db->where('registrationtype', $searchBy_regtype);
								$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
							}
							///search by registration number
							else if ($searchBy != '') {
								$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
								$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
								$this->db->where('regnumber', $searchBy);
								$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
								//$row=$searchBy;
							}
							///search by registration type
							else if ($searchBy_regtype != '') {
								$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
								$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
								$this->db->where('registrationtype', $searchBy_regtype);
								$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
							}
							if (count($members) > 0) {
								foreach ($members as $row) {
									$members_arr[][] = $row;
								}
							}
						} else {
							//default allocation list for 100 member
							foreach ($arraid as $row) {
								/* to show list  for  3 days back dated data */
								$three_days_back = date('Y-m-d', strtotime("- 3 days")); //date('Y-m-d') ;//date('Y-m-d', strtotime("- 3 days"));
								$this->db->where('kyc_edit', '1');
								$this->db->where('isactive', '1');
								$this->db->where('kyc_status', '0');
								$this->db->where('registrationtype', $type);
								$this->db->where('DATE(editedon) !=', '00-00-0000');
								$members = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
								$members_arr[] = $members;
								
							}
						}
					}

					$data['result'] = call_user_func_array('array_merge', $members_arr);
				}
				//print_r($data['result']);//die;
				$total_row = 100;
				$url = base_url() . "admin/kyc/Kyc/edited_list/";
				$config = pagination_init($url, $total_row, $per_page, 2);
				$this->pagination->initialize($config);
				$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Search</li>
							   </ol>';
				$str_links = $this->pagination->create_links();
				//var_dump($str_links);
				$data["links"] = $str_links;

				if (($start + $per_page) > $total_row)
					$end_of_total = $total_row;
				else
					$end_of_total = $start + $per_page;

				if ($total_row)
					$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
				else
					$data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';

				$data['index'] = $start + 1;

				$emptylistmsg = ' No records available...!!<br /> <a href=' . base_url() . 'admin/kyc/Kyc/next_edited_allocation_type/>Back</a>';

				/* Start Code To Get Recent Allotted Member Total Count */
				$pagination_total_count = $this->master_model->getRecords("admin_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
				if (!empty($pagination_total_count)) {
					foreach ($pagination_total_count[0] as $k => $value) {
						if ($k == "pagination_total_count") {
							$data['totalRecCount'] = $value;
						}
						if ($k == "original_allotted_member_id") {
							$data['original_allotted_member_id'] = $value;
						}
					}
				}
				/* Close Code To Get Recent Allotted Member Total Count */
				//print_r($data['result']);die;
				$data['emptylistmsg']	= $emptylistmsg;
				$data['total_count'] = $count;
				$this->db->distinct('registrationtype');
				$data['mem_type'] = $this->master_model->getRecords('member_registration', array('isactive' => '1'), 'registrationtype', array('registrationtype' => 'ASC'));
				//die;
				//print_r($data);die;
				$this->load->view('admin/kyc/edited_member', $data);
			} else {
				
				redirect(base_url() . 'admin/kyc/Kyc/edited_list/');
			}
		}
	}

	//show the Dashboard
	public function dashboard()
	{
		$this->load->view('admin/kyc/dashboard');
	}
	//To check the checkbox value 
	public function edited_user()
	{
		$edited_user = $this->master_model->getRecords('member_registration', array('editedon' => date('Y-m-d'), 'isactive' => '1', 'kyc_status' => '0', 'kyc_edit' => '0'), 'regnumber');
	}

	/*
	- SAGAR WALZADE : Code start here
	- function use : Function to fetch member data to initiate KYC
	- Changes : declaration field added, and old function renamed into "edited_member_old"
	*/
	/* SAGAR WALZADE : Code end here */
	//To show the Edited member Recommender screen  
	public function edited_member($regnumber = NULL, $srno = NULL, $totalRecCount = NULL)
	{
		$success = $error = $description = '';
		$noarray = array();
		$oldfilepath = $file_path = $photo_file = '';
		if ($regnumber) 
		{
			$next_id = $sucess = $memregnumber = '';
			$data['result'] = $new_arrayid = array();
			$registrationtype = '';
			$data['reg_no'] = ' ';
			$employer = array();
			$field_count = 0;
			$data = $update_data = $old_user_data = array();
			$name = array();
			$state = '1';
			$today = date("Y-m-d H:i:s");
			$date = date("Y-m-d H:i:s");
			if (isset($_POST['btnSubmit'])) 
			{
				$select = 'regnumber,registrationtype,email,createdon';
				$data = $this->master_model->getRecords('member_registration', array('regnumber' => $regnumber, 'isactive' => '1', 'kyc_status' => '0', 'kyc_edit' => '1'), $select);
				if (isset($_POST['cbox'])) { $name = $this->input->post('cbox'); }

				$regnumber = $data[0]['regnumber'];

				// optional
				// echo "You chose the following color(s): <br />";

				$check_arr = array();
				if (count($name) > 0) 
				{
					foreach ($name as $cbox) 
					{
						// echo $cbox."<br />";
						$check_arr[] = $cbox;
					}
				}

				$msg = 'Edit your profile as :-';
				if (count($check_arr) > 0) 
				{
					if (in_array('name_checkbox', $check_arr)) { $name_checkbox = '1'; } 
					else 
					{
						$name_checkbox = '0';
						$field_count++;
						$update_data[] = 'Name';
						$msg .= 'Name,';
					}

					if (in_array('dob_checkbox', $check_arr)) { $dob_checkbox = '1'; } 
					else 
					{
						$dob_checkbox = '0';
						$field_count++;
						$update_data[] = 'DOB';
						$msg .= 'Date of Birth ,';
					}

					if ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM') 
					{
						if (in_array('emp_checkbox', $check_arr)) { $emp_checkbox = '1'; } 
						else { $emp_checkbox = '1'; }
					} 
					elseif ($data[0]['registrationtype'] == 'O' || $data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A') 
					{
						if (in_array('emp_checkbox', $check_arr)) { $emp_checkbox = '1'; } 
						else 
						{
							$emp_checkbox = '0';
							$field_count++;
							$update_data[] = 'Employer';
							$msg .= 'Employer,';
						}
					}

					if (in_array('photo_checkbox', $check_arr)) { $photo_checkbox = '1'; } 
					else 
					{
						$photo_checkbox = '0';
						$field_count++;
						$update_data[] = 'Photo';
						$msg .= 'Photo,';
					}

					if (in_array('sign_checkbox', $check_arr)) { $sign_checkbox = '1'; } 
					else 
					{
						$sign_checkbox = '0';
						$field_count++;
						$update_data[] = 'Sign';
						$msg .= 'Sign,';
					}

					if (in_array('idprf_checkbox', $check_arr)) { $idprf_checkbox = '1'; } 
					else 
					{
						$idprf_checkbox = '0';
						$field_count++;
						$update_data[] = 'Id-proof';
						$msg .= 'Id-proof';
					}

					if ($data[0]['registrationtype'] == 'O') 
					{
						if ($data[0]['createdon'] >= '2022-04-01') 
						{
							if (in_array('declaration_checkbox', $check_arr)) 
							{
								$declaration_checkbox = '1';
							} 
							else 
							{
								$declaration_checkbox = '0';
								$field_count++;
								$update_data[] = 'Declaration';
								$msg .= 'Declaration';
							}
						} 
						else 
						{
							if (in_array('declaration_checkbox', $check_arr)) 
							{
								$declaration_checkbox = '1';
							} 
							else 
							{
								$declaration_checkbox = '0';
								// no field_count is required here (declaration optional for old members)
							}
						}
					} 
					else { $declaration_checkbox = '0'; }
				} 
				else 
				{
					$name_checkbox = '0';
					$msg .= 'Name,';
					$field_count++;
					$update_data[] = 'Name';
					$dob_checkbox = '0';
					$msg .= 'Date of Birth ,';
					$field_count++;
					$update_data[] = 'DOB';
					if ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM') {
						$emp_checkbox = '1';
					} else {
						$emp_checkbox = '0';
						$msg .= 'Employer,';
						$field_count++;
						$update_data[] = 'Employer';
					}

					$photo_checkbox = '0';
					$msg .= 'Photo,';
					$field_count++;
					$update_data[] = 'Photo';
					$sign_checkbox = '0';
					$msg .= 'Sign,';
					$field_count++;
					$update_data[] = 'Sign';
					$idprf_checkbox = '0';
					$msg .= 'Id-proof,';
					$field_count++;
					$update_data[] = 'Id-proof';

					if ($data[0]['registrationtype'] == 'O') {
						if ($data[0]['createdon'] >= '2022-04-01') {
							$declaration_checkbox = '0';
							$msg .= 'Declaration';
							$field_count++;
							$update_data[] = 'Declaration';
						} else {
							$declaration_checkbox = '0';
							// no field_count is required here (declaration optional for old members)
						}
					} else {
						$declaration_checkbox = '0';
					}
				}

				$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
				$old_user_data = $this->master_model->getRecords('member_registration', array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);
				$email = $data[0]['email'];
				$insert_data = array(
					'regnumber' => $data[0]['regnumber'],
					'mem_type' => $data[0]['registrationtype'],
					'mem_name' => $name_checkbox,
					// 'email_address' => $data[0]['email'],
					'mem_dob' => $dob_checkbox,
					'mem_associate_inst' => $emp_checkbox,
					'mem_photo' => $photo_checkbox,
					'mem_sign' => $sign_checkbox,
					'mem_proof' => $idprf_checkbox,
					'mem_declaration' => $declaration_checkbox,
					'field_count' => $field_count,
					'old_data' => serialize($old_user_data),
					'kyc_status' => '0',
					'kyc_state' => $state,
					'recommended_by' => $this->session->userdata('kyc_id'),
					'user_type' => $this->session->userdata('role'),
					'recommended_date' => $today,
					'record_source' => 'Edit'
				);
				$last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);

				// log activity
				// get recommended fields data from member registration -

				$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
				$old_data = $this->master_model->getRecords("member_registration", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);
				$log_desc['old_data'] = $old_data;
				$log_desc['inserted_data'] = $insert_data;
				$description = serialize($log_desc);
				$userdata = $this->master_model->getRecords("member_registration", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				));

				// $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid,$regnumber, $description);

				if ($data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A') 
				{
					if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1') 
					{
						$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

						// $this->session->set_flashdata('success','(Previous record) '.$data[0]['regnumber']. ' sent to approver !!');
						// $this->session->set_flashdata('success','Record sent to approver !!');
						// $success='Record sent to approver !!';
						// redirect(base_url().'admin/kyc/Kyc/edited_member/'.$regnumber);

						$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
						$old_data = $this->master_model->getRecords("member_registration", array(
							'regnumber' => $regnumber,
							'isactive' => '1'
						), $select);
						$log_desc['old_data'] = $old_data;
						$log_desc['inserted_data'] = $insert_data;
						$description = serialize($log_desc);
						$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
					} 
					else 
					{

						// email send on recommend...
						// email to user

						/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommender_recommend_email'));
						$final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);

						// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

						$info_arr=array(
						'to'=> "kyciibf@gmail.com",
						'from'=> $emailerstr[0]['from'],
						'subject'=>$emailerstr[0]['subject'],
						'message'=>$final_str
						);*/
						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$userpass = $aes->decrypt($userdata[0]['usrpassword']);
						$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
						$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
						$msg = implode(',', $update_data);
						$emailerstr = $this->master_model->getRecords('emailer', array(
							'emailer_name' => 'recommendation_email_O'

						));
						$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
						$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
						$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
						$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

						// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

						// $info_arr = array(
						// 	//'to'=> "kyciibf@gmail.com",
						// 	'to' => 'iibfdevp@esds.co.in',//'to' => $userdata[0]['email'],
						// 	'from' => $emailerstr[0]['from'],
						// 	'subject' => $emailerstr[0]['subject'],
						// 	'message' => $final_str
						// );
						$arr_to = array('iibfdevp@esds.co.in');
						$info_arr = array(
							'to' => $arr_to,
							'from' => 'noreply@iibf.org.in',
							'subject' => 'member registration',
							'message' => $final_str
						);
						$info_arr = array(
							'to' => $email,
							'from' => 'noreply@iibf.org.in',
							'subject' => 'Download the admit letter',
							'message' => $final_str
						);
						if ($this->Emailsending->mailsend($info_arr)) {
							$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

							// $this->session->set_flashdata('success','KYC recommend for '.$data[0]['regnumber'].'  (previous record) & Email sent successfully !!');
							// $this->session->set_flashdata('success','KYC recommend for the candidate & Email send successfully !!');
							// $success='KYC recommend for the candidate & Email send successfully !!';
							// log activity
							// get recommended fields data from member registration -

							$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
							$old_data = $this->master_model->getRecords("member_registration", array(
								'regnumber' => $regnumber,
								'isactive' => '1'
							), $select);
							$log_desc['old_data'] = $old_data;
							$log_desc['inserted_data'] = $insert_data;
							$description = serialize($log_desc);
							$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

							// email log

							$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

							// get user details

							$userdata = $this->master_model->getRecords("member_registration", array(
								'regnumber' => $regnumber,
								'isactive' => '1'
							));

							// make recommended fields empty  -

							if (in_array('Name', $update_data)) {
								/*$updatedata=array(
								'namesub'=>'',
								'firstname'=>'',
								'middlename'=>'',
								'lastname'=>''
								);*/
								$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';

								//	$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

							}

							if (in_array('DOB', $update_data)) {
								$updatedata['dateofbirth'] = '0000-00-00';

								//	$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

							}

							if (in_array('Employer', $update_data)) {
								$updatedata['associatedinstitute'] = '';

								// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

							}

							// -------check for  photo -----------#

							if (in_array('Photo', $update_data)) {
								$updatedata['scannedphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'p');
								$noarray = explode('/p_', $oldfilepath);

								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $noarray[1];
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {

										$this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
								}
							}
							// -------end check for  photo -----------#

							// ------- check for  signature-----------#

							if (in_array('Sign', $update_data)) {

								$updatedata['scannedsignaturephoto'] = '';
								$oldfilepath = get_img_name($regnumber, 's');
								$noarray = explode('/s_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										//$this->KYC_Log_model->create_log('fail to delete Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
								}
							}

							// -------End check for  photo -----------#


							// ------- check for  idproof-----------#

							if (in_array('Id-proof', $update_data)) {

								$updatedata['idproofphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'pr');
								$noarray = explode('/pr_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $noarray[1];
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
								}
								// -------End  check for id proof -----------#

							}

							//blank the column with is been recommended  in member registration table
							if (!empty($updatedata)) {
								$this->db->where('isactive', '1');
								$this->master_model->updateRecord('member_registration', $updatedata, array(
									'regnumber' => $regnumber
								));
							}
						}
					}

					$member = $this->master_model->getRecords("admin_kyc_users", array(
						'DATE(date)' => date("Y-m-d"),
						'user_id' => $this->session->userdata('kyc_id'),
						'list_type' => 'Edit'
					));
					$arrayid = explode(',', $member[0]['allotted_member_id']);
					$index = array_search($regnumber, $arrayid, true);

					// get next record

					$currentid = $index;
					$nextid = $currentid + 1;
					if (array_key_exists($nextid, $arrayid)) {
						$next_id = $arrayid[$nextid];
					} else {
						$next_id = $arrayid[0];
					}

					// end of next record
					// unset the  current id index

					unset($arrayid[$index]);
					if (count($arrayid) > 0) {
						foreach ($arrayid as $row) {
							$new_arrayid[] = $row;
						}
					}

					if (count($new_arrayid) > 0) {
						$regstr = implode(',', $new_arrayid);
					} else {
						$regstr = '';
						$next_id = '';
					}

					$update_data = array(
						'allotted_member_id' => $regstr
					);
					$this->db->where('DATE(date)', date('Y-m-d'));
					$this->db->where('list_type', 'Edit');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array(
						'user_id' => $this->session->userdata('kyc_id')
					));
					/* Start Code To Showing Count On Member List*/
					if ($next_id == '') {
						$next_id = 0;
					}

					// $totalRecCount=$this->get_edited_allocation_type_cnt();

					if ($srno > $totalRecCount) {

						// $srno=$totalRecCount;

						$srno = 1;
					} else {
						$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
						$arr = array_slice($original_allotted_Arr, -$totalRecCount);
						$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
						$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
						$memberNo = $next_id;
						$updated_list_index = array_search($memberNo, $reversedArr_list);
						$srno = $updated_list_index;
					}

					redirect(base_url() . 'admin/kyc/Kyc/edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
					/* Close Code To Showing Count On Member List*/
				}
				elseif ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM') 
				{
					if ($name_checkbox == '1' && $dob_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1') 
					{
						$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

						// $this->session->set_flashdata('success','Record sent to approver !!');
						// $success='Record sent to approver !!';
						// redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);

						$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
						$old_data = $this->master_model->getRecords("member_registration", array(
							'regnumber' => $regnumber,
							'isactive' => '1'
						), $select);
						$log_desc['old_data'] = $old_data;
						$log_desc['inserted_data'] = $insert_data;
						$description = serialize($log_desc);
						$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
					} 
					else 
					{
						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$userpass = $aes->decrypt($userdata[0]['usrpassword']);
						$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
						$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
						$msg = implode(',', $update_data);
						$emailerstr = $this->master_model->getRecords('emailer', array(
							'emailer_name' => 'recommendation_email_NM'
						));
						$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
						$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
						$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
						$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);

						// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

						$info_arr = array(
							// 'to'=> "kyciibf@gmail.com",
							'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
							'from' => $emailerstr[0]['from'],
							'subject' => $emailerstr[0]['subject'],
							'message' => $final_str
						);
						if ($this->Emailsending->mailsend($info_arr)) {
							$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

							// $this->session->set_flashdata('success','KYC recommend for '.$data[0]['regnumber'].'  (previous record) & Email sent successfully !!');
							// $this->session->set_flashdata('success','KYC recommend for the candidate & Email send successfully !!');
							// $success='KYC recommend for the candidate & Email send successfully !!';
							// log activity
							// get recommended fields data from member registration -

							$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
							$old_data = $this->master_model->getRecords("member_registration", array(
								'regnumber' => $regnumber,
								'isactive' => '1'
							), $select);
							$log_desc['old_data'] = $old_data;
							$log_desc['inserted_data'] = $insert_data;
							$description = serialize($log_desc);
							$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

							// email log

							$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
							$userdata = $this->master_model->getRecords("member_registration", array(
								'regnumber' => $regnumber,
								'isactive' => '1'
							), 'reg_no');

							// make recommended fields empty  -

							if (in_array('Name', $update_data)) {
								/*$updatedata=array(
								'namesub'=>'',
								'firstname'=>'',
								'middlename'=>'',
								'lastname'=>''
								);*/
								$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
								$this->db->where('isactive', '1');
								$this->master_model->updateRecord('member_registration', $updatedata, array(
									'regnumber' => $regnumber
								));
							}

							if (in_array('DOB', $update_data)) {
								$updatedata['dateofbirth'] = '0000-00-00';

								// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

							}

							if (in_array('Employer', $update_data)) {
								$updatedata['associatedinstitute'] = '';

								// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

							}
							// -------check for  photo -----------#

							if (in_array('Photo', $update_data)) {
								$updatedata['scannedphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'p');
								$noarray = explode('/p_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $noarray[1];

									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete photo ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
								}
							}
							// -------end check for  photo -----------#

							// ------- check for  signature-----------#

							if (in_array('Sign', $update_data)) {

								$updatedata['scannedsignaturephoto'] = '';
								$oldfilepath = get_img_name($regnumber, 's');
								$noarray = explode('/s_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
								}
							}

							// -------End check for  photo -----------#


							// ------- check for  idproof-----------#

							if (in_array('Id-proof', $update_data)) {

								$updatedata['idproofphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'pr');
								$noarray = explode('/pr_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended idproof rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to deleted idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
								}
								// -------End  check for id proof -----------#


							}
						}
					}

					//bank the column with is been recommended  in member registration table
					if (!empty($updatedata)) {
						$this->db->where('isactive', '1');
						$this->master_model->updateRecord('member_registration', $updatedata, array(
							'regnumber' => $regnumber
						));
					}

					$member = $this->master_model->getRecords("admin_kyc_users", array(
						'DATE(date)' => date('Y-m-d'),
						'user_id' => $this->session->userdata('kyc_id'),
						'list_type' => 'Edit'
					));
					$arrayid = explode(',', $member[0]['allotted_member_id']);
					$index = array_search($regnumber, $arrayid, true);

					// get next record

					$currentid = $index;
					$nextid = $currentid + 1;
					if (array_key_exists($nextid, $arrayid)) {
						$next_id = $arrayid[$nextid];
					} else {
						$next_id = $arrayid[0];
					}

					// end of next record
					// unset the  current id index

					unset($arrayid[$index]);
					if (count($arrayid) > 0) {
						foreach ($arrayid as $row) {
							$new_arrayid[] = $row;
						}
					}

					if (count($new_arrayid) > 0) {
						$regstr = implode(',', $new_arrayid);
					} else {
						$regstr = '';
						$next_id = '';
					}

					$update_data = array(
						'allotted_member_id' => $regstr
					);
					$this->db->where('DATE(date)', date('Y-m-d'));
					$this->db->where('list_type', 'Edit');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array(
						'user_id' => $this->session->userdata('kyc_id')
					));
					/* Start Code To Showing Count On Member List*/
					if ($next_id == '') {
						$next_id = 0;
					}

					// $totalRecCount=$this->get_edited_allocation_type_cnt();

					if ($srno > $totalRecCount) {

						// $srno=$totalRecCount;

						$srno = 1;
					} else {
						$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
						$arr = array_slice($original_allotted_Arr, -$totalRecCount);
						$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
						$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
						$memberNo = $next_id;
						$updated_list_index = array_search($memberNo, $reversedArr_list);
						$srno = $updated_list_index;
					}

					redirect(base_url() . 'admin/kyc/Kyc/edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
					/* Close Code To Showing Count On Member List*/
				} 
				elseif ($data[0]['registrationtype'] == 'O') 
				{
					// Declaration mandatory for those users who are registered from 1 april 2022 
					// (its a date of declaration feature upload date on live)
					if ($data[0]['createdon'] >= '2022-04-01') 
					{
						if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1' && $declaration_checkbox == '1') {
							$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

							// $this->session->set_flashdata('success','(Previous record) '.$data[0]['regnumber']. ' sent to approver !!');
							// $this->session->set_flashdata('success','Record sent to approver !!');
							// $success='Record sent to approver !!';
							// redirect(base_url().'admin/kyc/Kyc/edited_member/'.$regnumber);

							$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
							$old_data = $this->master_model->getRecords("member_registration", array(
								'regnumber' => $regnumber,
								'isactive' => '1'
							), $select);
							$log_desc['old_data'] = $old_data;
							$log_desc['inserted_data'] = $insert_data;
							$description = serialize($log_desc);
							$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
						} else {

							// email send on recommend...
							// email to user

							/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommender_recommend_email'));
							$final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);

							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr=array(
							'to'=> "kyciibf@gmail.com",
							'from'=> $emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);*/
							include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

							$key = $this->config->item('pass_key');
							$aes = new CryptAES();
							$aes->set_key(base64_decode($key));
							$aes->require_pkcs5();
							$userpass = $aes->decrypt($userdata[0]['usrpassword']);
							$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							$msg = implode(',', $update_data);
							$emailerstr = $this->master_model->getRecords('emailer', array(
								'emailer_name' => 'recommendation_email_O'

							));
							$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
							$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
							$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
							$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr = array(

								//'to'=> "kyciibf@gmail.com",

								'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'message' => $final_str
							);
							if ($this->Emailsending->mailsend($info_arr)) {
								$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

								// $this->session->set_flashdata('success','KYC recommend for '.$data[0]['regnumber'].'  (previous record) & Email sent successfully !!');
								// $this->session->set_flashdata('success','KYC recommend for the candidate & Email send successfully !!');
								// $success='KYC recommend for the candidate & Email send successfully !!';
								// log activity
								// get recommended fields data from member registration -

								$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
								$old_data = $this->master_model->getRecords("member_registration", array(
									'regnumber' => $regnumber,
									'isactive' => '1'
								), $select);
								$log_desc['old_data'] = $old_data;
								$log_desc['inserted_data'] = $insert_data;
								$description = serialize($log_desc);
								$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

								// email log

								$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

								// get user details

								$userdata = $this->master_model->getRecords("member_registration", array(
									'regnumber' => $regnumber,
									'isactive' => '1'
								));

								// make recommended fields empty  -

								if (in_array('Name', $update_data)) {
									/*$updatedata=array(
									'namesub'=>'',
									'firstname'=>'',
									'middlename'=>'',
									'lastname'=>''
									);*/
									$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';

									//	$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								if (in_array('DOB', $update_data)) {
									$updatedata['dateofbirth'] = '0000-00-00';

									//	$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								if (in_array('Employer', $update_data)) {
									$updatedata['associatedinstitute'] = '';

									// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								// -------check for  photo -----------#

								if (in_array('Photo', $update_data)) {
									$updatedata['scannedphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'p');
									$noarray = explode('/p_', $oldfilepath);

									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $noarray[1];
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {

											$this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_p_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								}
								// -------end check for  photo -----------#

								// ------- check for  signature-----------#

								if (in_array('Sign', $update_data)) {

									$updatedata['scannedsignaturephoto'] = '';
									$oldfilepath = get_img_name($regnumber, 's');
									$noarray = explode('/s_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $noarray[1];
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_s_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

											//$this->KYC_Log_model->create_log('fail to delete Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								}

								// -------End check for  photo -----------#


								// ------- check for  idproof-----------#

								if (in_array('Id-proof', $update_data)) {

									$updatedata['idproofphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'pr');
									$noarray = explode('/pr_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $noarray[1];
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_pr_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
									// -------End  check for id proof -----------#

								}

								// ------- check for declaration -----------#

								if (in_array('Declaration', $update_data)) {

									$updatedata['declaration'] = '';
									$oldfilepath = get_img_name($regnumber, 'declaration');
									$noarray = explode('/declaration_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath;
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_declaration_' . $noarray[1];
										$description .= ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended declaration Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_declaration_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete declaration', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_declaration_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member declaration not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									}
									// -------End  check for declaration -----------#
								}

								//bank the column with is been recommended  in member registration table
								if (!empty($updatedata)) {
									$this->db->where('isactive', '1');
									$this->master_model->updateRecord('member_registration', $updatedata, array(
										'regnumber' => $regnumber
									));
								}
							}
						}
					} 
					else 
					{
						if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1') {
							$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

							// $this->session->set_flashdata('success','(Previous record) '.$data[0]['regnumber']. ' sent to approver !!');
							// $this->session->set_flashdata('success','Record sent to approver !!');
							// $success='Record sent to approver !!';
							// redirect(base_url().'admin/kyc/Kyc/edited_member/'.$regnumber);

							$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
							$old_data = $this->master_model->getRecords("member_registration", array(
								'regnumber' => $regnumber,
								'isactive' => '1'
							), $select);
							$log_desc['old_data'] = $old_data;
							$log_desc['inserted_data'] = $insert_data;
							$description = serialize($log_desc);
							$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
						} else {

							// email send on recommend...
							// email to user

							/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommender_recommend_email'));
							$final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);

							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr=array(
							'to'=> "kyciibf@gmail.com",
							'from'=> $emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);*/
							include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

							$key = $this->config->item('pass_key');
							$aes = new CryptAES();
							$aes->set_key(base64_decode($key));
							$aes->require_pkcs5();
							$userpass = $aes->decrypt($userdata[0]['usrpassword']);
							$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							$msg = implode(',', $update_data);
							$emailerstr = $this->master_model->getRecords('emailer', array(
								'emailer_name' => 'recommendation_email_O'

							));
							$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
							$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
							$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
							$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr = array(

								//'to'=> "kyciibf@gmail.com",

								'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'message' => $final_str
							);
							if ($this->Emailsending->mailsend($info_arr)) {
								$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

								// $this->session->set_flashdata('success','KYC recommend for '.$data[0]['regnumber'].'  (previous record) & Email sent successfully !!');
								// $this->session->set_flashdata('success','KYC recommend for the candidate & Email send successfully !!');
								// $success='KYC recommend for the candidate & Email send successfully !!';
								// log activity
								// get recommended fields data from member registration -

								$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
								$old_data = $this->master_model->getRecords("member_registration", array(
									'regnumber' => $regnumber,
									'isactive' => '1'
								), $select);
								$log_desc['old_data'] = $old_data;
								$log_desc['inserted_data'] = $insert_data;
								$description = serialize($log_desc);
								$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

								// email log

								$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

								// get user details

								$userdata = $this->master_model->getRecords("member_registration", array(
									'regnumber' => $regnumber,
									'isactive' => '1'
								));

								// make recommended fields empty  -

								if (in_array('Name', $update_data)) {
									/*$updatedata=array(
									'namesub'=>'',
									'firstname'=>'',
									'middlename'=>'',
									'lastname'=>''
									);*/
									$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';

									//	$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								if (in_array('DOB', $update_data)) {
									$updatedata['dateofbirth'] = '0000-00-00';

									//	$this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								if (in_array('Employer', $update_data)) {
									$updatedata['associatedinstitute'] = '';

									// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								// -------check for  photo -----------#

								if (in_array('Photo', $update_data)) {
									$updatedata['scannedphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'p');
									$noarray = explode('/p_', $oldfilepath);

									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $noarray[1];
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {

											$this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_p_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								}
								// -------end check for  photo -----------#

								// ------- check for  signature-----------#

								if (in_array('Sign', $update_data)) {

									$updatedata['scannedsignaturephoto'] = '';
									$oldfilepath = get_img_name($regnumber, 's');
									$noarray = explode('/s_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $noarray[1];
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_s_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

											//$this->KYC_Log_model->create_log('fail to delete Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member Signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								}

								// -------End check for  photo -----------#


								// ------- check for  idproof-----------#

								if (in_array('Id-proof', $update_data)) {

									$updatedata['idproofphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'pr');
									$noarray = explode('/pr_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $noarray[1];
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_pr_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//  $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
									// -------End  check for id proof -----------#

								}

								// ------- check for declaration (declaration not required for old member) -----------#

								// if (in_array('Declaration', $update_data)) {

								// 	$updatedata['declaration'] = '';
								// 	$oldfilepath = get_img_name($regnumber, 'declaration');
								// 	$noarray = explode('/declaration_', $oldfilepath);
								// 	$description = 'oldpath:' . $oldfilepath;
								// 	if (isset($noarray[1])) {
								// 		$file_path = implode('/', explode('/', $oldfilepath, -1));
								// 		$photo_file = 'k_declaration_' . $noarray[1];
								// 		$description .= ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								// 		if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
								// 			$this->KYC_Log_model->create_log('Recommended declaration Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								// 		} else {
								// 			$file_path = implode('/', explode('/', $oldfilepath, -1));
								// 			$photo_file = 'k_declaration_' . $regnumber;
								// 			@rename($oldfilepath, $file_path . '/' . $photo_file);
								// 			$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								// 			$this->KYC_Log_model->create_log('fail to delete declaration', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								// 		}
								// 	} else {
								// 		$file_path = implode('/', explode('/', $oldfilepath, -1));
								// 		$photo_file = 'k_declaration_' . $regnumber;
								// 		@rename($oldfilepath, $file_path . '/' . $photo_file);
								// 		$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								// 		$this->KYC_Log_model->create_log('member declaration not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								// 	}
								// 	// -------End  check for declaration -----------#
								// }

								//bank the column with is been recommended  in member registration table
								if (!empty($updatedata)) {
									$this->db->where('isactive', '1');
									$this->master_model->updateRecord('member_registration', $updatedata, array(
										'regnumber' => $regnumber
									));
								}
							}
						}
					}
					$member = $this->master_model->getRecords("admin_kyc_users", array(
						'DATE(date)' => date("Y-m-d"),
						'user_id' => $this->session->userdata('kyc_id'),
						'list_type' => 'Edit'
					));
					$arrayid = explode(',', $member[0]['allotted_member_id']);
					$index = array_search($regnumber, $arrayid, true);

					// get next record

					$currentid = $index;
					$nextid = $currentid + 1;
					if (array_key_exists($nextid, $arrayid)) {
						$next_id = $arrayid[$nextid];
					} else {
						$next_id = $arrayid[0];
					}

					// end of next record
					// unset the  current id index

					unset($arrayid[$index]);
					if (count($arrayid) > 0) {
						foreach ($arrayid as $row) {
							$new_arrayid[] = $row;
						}
					}

					if (count($new_arrayid) > 0) {
						$regstr = implode(',', $new_arrayid);
					} else {
						$regstr = '';
						$next_id = '';
					}

					$update_data = array(
						'allotted_member_id' => $regstr
					);
					$this->db->where('DATE(date)', date('Y-m-d'));
					$this->db->where('list_type', 'Edit');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array(
						'user_id' => $this->session->userdata('kyc_id')
					));
					/* Start Code To Showing Count On Member List*/
					if ($next_id == '') {
						$next_id = 0;
					}

					// $totalRecCount=$this->get_edited_allocation_type_cnt();

					if ($srno > $totalRecCount) {

						// $srno=$totalRecCount;

						$srno = 1;
					} else {
						$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
						$arr = array_slice($original_allotted_Arr, -$totalRecCount);
						$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
						$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
						$memberNo = $next_id;
						$updated_list_index = array_search($memberNo, $reversedArr_list);
						$srno = $updated_list_index;
					}

					redirect(base_url() . 'admin/kyc/Kyc/edited_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
					/* Close Code To Showing Count On Member List*/
				}
			}

			// $data['next_id']= $next_id;
			// $data['next_id'] = '77066';

			if ($regnumber) 
			{
				$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,registrationtype,email,createdon';
				$members = $this->master_model->getRecords("member_registration", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select, "", '0', '1');

				if (count($members) > 0) { $memregnumber = $members[0]['regnumber']; }

				/*	if(count($members))
				{
					$data['result'] = $members;
					$data['reg_no'] = $members[0]['regnumber'];
				}	*/
			}

			$recommnended_members_data = $this->master_model->getRecords("member_kyc", array('regnumber' => $regnumber), '', array('kyc_id' => 'DESC'));

			// $data['recomended_mem_data']=$recommnended_members_data;

			$data = array('result' => $members, 'reg_no' => $memregnumber, 'recomended_mem_data' => $recommnended_members_data, 'next_id' => $next_id, 'error' => $error, 'success' => $success);
			$data['srno'] = $srno;
			$data['totalRecCount'] = $totalRecCount;
			$this->load->view('admin/kyc/edit_recommended_screen', $data);
		} 
		else 
		{
			$this->session->set_flashdata('success', $this->session->flashdata('success'));
			// $this->session->set_flashdata('success','KYC recommend for last record & Email send successfully !!');
			// $this->session->set_flashdata('error','Invalid record!!');
			redirect(base_url() . 'admin/kyc/Kyc/edited_list');
		}
	}
		
	//to show the recommended member list 
	public function recommended_list()
	{
		$kycstatus = array();
		$data['result'] = array();
		$date = date("Y-m-d H:i:s");
		$regstr = $searchText = $searchBy = '';
		$searchBy_regtype = '';
		$searchBy = $this->input->post('regnumber');
		$searchBy_regtype = $this->input->post('registrationtype');
		if ($searchBy != '') {
			$this->db->where('member_kyc.regnumber', $searchBy);
		} elseif ($searchBy_regtype != '') {
			$this->db->where('member_kyc.mem_type', $searchBy_regtype);
		}
		//$this->db->where('member_registration.isactive','1');
		//$this->db->where('member_registration.kyc_status','0');	
		//$this->db->join('member_registration', 'member_registration.regnumber= member_kyc.regnumber', 'LEFT');
		$select = '*';
		$this->db->limit('3000');
		$r_list = $this->master_model->getRecords("member_kyc", array('recommended_by' => $this->session->userdata('kyc_id')), $select, array('kyc_id' => 'DESC'));

		//$r_list = $this->master_model->getRecords("member_kyc",array('regnumber'=>$searchBy));

		if (count($r_list)) {
			$data['result'] =  $r_list;
			$data['status'] =  $kycstatus;
		}

		$this->load->view('admin/kyc/recommender_recommend_list', $data);
	}
	//to get next 100 allocation ..for edited list  same day

	
	//to get next 100 allocation ...for  same day
	
	// Unset session values
	public function kyc_list()
	{
		$this->session->set_userdata('registrationtype', '');
		redirect(base_url() . 'admin/kyc/Kyc/member');
	}
	// Function added by pooja mane to fetch list of pending members for KYC
	public function pending_member($regnumber = NULL, $srno = NULL, $totalRecCount = NULL)
	{
		if ($regnumber) 
		{

			// echo $regnumber;exit;
			$next_id = $sucess = $memregnumber = $description = '';
			$oldfilepath = $file_path = $photo_file = '';
			$data['result'] = $new_arrayid = $noarray = array();
			$registrationtype = '';
			$data['reg_no'] = ' ';
			$employer = array();
			$field_count = 0;
			$data = $update_data = $old_user_data = array();
			$name = array();
			$state = '1';
			$today = date("Y-m-d H:i:s");
			$date = date("Y-m-d H:i:s");
			if (isset($_POST['btnSubmit'])) 
			{
				$select = 'regnumber,registrationtype,email,createdon';
				$data = $this->master_model->getRecords('member_registration', array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);
				if (isset($_POST['cbox'])) {
					$name = $this->input->post('cbox');
				}

				$regnumber = $data[0]['regnumber'];

				// optional
				// echo "You chose the following color(s): <br />";

				$check_arr = array();
				if (count($name) > 0) {
					foreach ($name as $cbox) {

						// echo $cbox."<br />";

						$check_arr[] = $cbox;
					}
				}

				$msg = 'Edit your profile as :-';
				if (count($check_arr) > 0) {
					if (in_array('name_checkbox', $check_arr)) {
						$name_checkbox = '1';
					} else {
						$name_checkbox = '0';
						$field_count++;
						$update_data[] = 'Name';
						$msg .= 'Name,';
					}

					if (in_array('dob_checkbox', $check_arr)) {
						$dob_checkbox = '1';
					} else {
						$dob_checkbox = '0';
						$field_count++;
						$update_data[] = 'DOB';
						$msg .= 'Date of Birth ,';
					}

					if ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM') {
						if (in_array('emp_checkbox', $check_arr)) {
							$emp_checkbox = '1';
						} else {
							$emp_checkbox = '1';
						}
					} elseif ($data[0]['registrationtype'] == 'O' || $data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A') {
						if (in_array('emp_checkbox', $check_arr)) {
							$emp_checkbox = '1';
						} else {
							$emp_checkbox = '0';
							$field_count++;
							$update_data[] = 'Employer';
							$msg .= 'Employer,';
						}
					}

					if (in_array('photo_checkbox', $check_arr)) {
						$photo_checkbox = '1';
					} else {
						$photo_checkbox = '0';
						$field_count++;
						$update_data[] = 'Photo';
						$msg .= 'Photo,';
					}

					if (in_array('sign_checkbox', $check_arr)) {
						$sign_checkbox = '1';
					} else {
						$sign_checkbox = '0';
						$field_count++;
						$update_data[] = 'Sign';
						$msg .= 'Sign,';
					}

					if (in_array('idprf_checkbox', $check_arr)) {
						$idprf_checkbox = '1';
					} else {
						$idprf_checkbox = '0';
						$field_count++;
						$update_data[] = 'Id-proof';
						$msg .= 'Id-proof';
					}

					if ($data[0]['registrationtype'] == 'O') {
						if ($data[0]['createdon'] >= '2022-04-01') {
							if (in_array('declaration_checkbox', $check_arr)) {
								$declaration_checkbox = '1';
							} else {
								$declaration_checkbox = '0';
								$field_count++;
								$update_data[] = 'Declaration';
								$msg .= 'Declaration';
							}
						} else {
							if (in_array('declaration_checkbox', $check_arr)) {
								$declaration_checkbox = '1';
							} else {
								$declaration_checkbox = '0';
							}
						}
					} else {
						$declaration_checkbox = '0';
					}
				} 
				else 
				{
					$name_checkbox = '0';
					$msg .= 'Name,';
					$field_count++;
					$update_data[] = 'Name';
					$dob_checkbox = '0';
					$msg .= 'Date of Birth ,';
					$field_count++;
					$update_data[] = 'DOB';
					if ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM') {
						$emp_checkbox = '1';
					} else {
						$emp_checkbox = '0';
						$msg .= 'Employer,';
						$field_count++;
						$update_data[] = 'Employer';
					}

					$photo_checkbox = '0';
					$msg .= 'Photo,';
					$field_count++;
					$update_data[] = 'Photo';
					$sign_checkbox = '0';
					$msg .= 'Sign,';
					$field_count++;
					$update_data[] = 'Sign';
					$idprf_checkbox = '0';
					$msg .= 'Id-proof,';
					$field_count++;
					$update_data[] = 'Id-proof';
					if ($data[0]['registrationtype'] == 'O') {
						if ($data[0]['createdon'] >= '2022-04-01') {
							$declaration_checkbox = '0';
							$msg .= 'Declaration';
							$field_count++;
							$update_data[] = 'Declaration';
						} else {
							$declaration_checkbox = '0';
						}
					} else {
						$declaration_checkbox = '0';
					}
				}

				$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,kyc_edit';
				$old_user_data = $this->master_model->getRecords('member_registration', array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);

				$email = $data[0]['email'];
				if($data[0]['kyc_edit']=='0')
				{
					$record_source = 'New';
				}
				else
				{ $record_source = 'Edit';}
				
				$insert_data = array(
					'regnumber' => $data[0]['regnumber'],
					'mem_type' => $data[0]['registrationtype'],
					'mem_name' => $name_checkbox,
					// 'email_address' => $data[0]['email'],
					'mem_dob' => $dob_checkbox,
					'mem_associate_inst' => $emp_checkbox,
					'mem_photo' => $photo_checkbox,
					'mem_sign' => $sign_checkbox,
					'mem_proof' => $idprf_checkbox,
					'mem_declaration' => $declaration_checkbox,
					'field_count' => $field_count,
					'old_data' => serialize($old_user_data),
					'kyc_status' => '0',
					'kyc_state' => $state,
					'recommended_by' => $this->session->userdata('kyc_id'),
					'user_type' => $this->session->userdata('role'),
					'recommended_date' => $today,
					'record_source' => $record_source
				);
				$last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);

				// log activity
				// get recommended fields data from member registration -

				$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
				$old_data = $this->master_model->getRecords("member_registration", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);
				$log_desc['old_data'] = $old_data;
				$log_desc['inserted_data'] = $insert_data;
				$description = serialize($log_desc);
				$userdata = $this->master_model->getRecords("member_registration", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				));

				// get user details
				// $userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber));
				// Log activity
				// $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'),$last_insterid,$regnumber, $description);

				if ($data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A') {
					if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1') {

						// $this->session->set_flashdata('success','Recommended Successfully !!');
						// $sucess='Record sent to approver !!';  $data[0]['regnumber']

						$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

						// redirect(base_url().'admin/kyc/Kyc/pending_member/'.$regnumber);

					} else {


						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$userpass = $aes->decrypt($userdata[0]['usrpassword']);
						$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
						$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
						$msg = implode(',', $update_data);
						$emailerstr = $this->master_model->getRecords('emailer', array(
							'emailer_name' => 'recommendation_email_O'
						));
						$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
						$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
						$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
						$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);


						$info_arr = array(

							//'to'=> "kyciibf@gmail.com",
							'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
							'from' => $emailerstr[0]['from'],
							'subject' => $emailerstr[0]['subject'],
							'message' => $final_str
						);
						if ($this->Emailsending->mailsend($info_arr)) {

							// $sucess='KYC recommend for the candidate & Email send successfully !!';

							$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

							// log activity
							// get recommended fields data from member registration -

							$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
							$old_data = $this->master_model->getRecords("member_registration", array(
								'regnumber' => $regnumber,
								'isactive' => '1'
							), $select);
							$log_desc['old_data'] = $old_data;
							$log_desc['inserted_data'] = $insert_data;
							$description = serialize($log_desc);
							$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

							// email log

							$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

							// make recommended fields empty  -

							if (in_array('Name', $update_data)) {

								$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
								$this->db->where('isactive', '1');
								$this->master_model->updateRecord('member_registration', $updatedata, array(
									'regnumber' => $regnumber
								));
							}

							if (in_array('DOB', $update_data)) {
								$updatedata['dateofbirth'] = '0000-00-00';
							}

							if (in_array('Employer', $update_data)) {
								$updatedata['associatedinstitute'] = '';
							}

							// -------check for  photo -----------#

							if (in_array('Photo', $update_data)) {
								$updatedata['scannedphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'p');
								$noarray = explode('/p_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $noarray[1];
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended photo Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {

										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
								}
							}
							// -------end check for  photo -----------#

							// ------- check for  signature-----------#

							if (in_array('Sign', $update_data)) {

								$updatedata['scannedsignaturephoto'] = '';
								$oldfilepath = get_img_name($regnumber, 's');
								$noarray = explode('/s_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {

										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										//$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								} else {

									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member signature not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
								}
							}

							// -------End check for  photo -----------#


							// ------- check for  idproof-----------#

							if (in_array('Id-proof', $update_data)) {

								$updatedata['idproofphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'pr');
								$noarray = explode('/pr_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete idproof ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member idproof  not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

									// $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
								}
								// -------End  check for id proof -----------#
							}
						}
					}

					if (!empty($updatedata)) {
						$this->db->where('isactive', '1');
						$this->master_model->updateRecord('member_registration', $updatedata, array(
							'regnumber' => $regnumber
						));
					}

					$member = $this->master_model->getRecords("admin_kyc_users", array(
						'DATE(date)' => date('Y-m-d'),
						'user_id' => $this->session->userdata('kyc_id'),
						'list_type' => 'Pending'
					));
					$arrayid = explode(',', $member[0]['allotted_member_id']);
					$index = array_search($regnumber, $arrayid, true);
					$currentid = $index;
					$nextid = $currentid + 1;
					if (array_key_exists($nextid, $arrayid)) {
						$next_id = $arrayid[$nextid];
					} else {
						$next_id = $arrayid[0];
					}

					unset($arrayid[$index]);
					if (count($arrayid) > 0) {
						foreach ($arrayid as $row) {
							$new_arrayid[] = $row;
						}
					}

					if (count($new_arrayid) > 0) {
						$regstr = implode(',', $new_arrayid);
					} else {
						$regstr = '';
						$next_id = '';
					}

					$update_data = array(
						'allotted_member_id' => $regstr
					);
					$this->db->where('DATE(date)', date('Y-m-d '));
					$this->db->where('list_type', 'Pending');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array(
						'user_id' => $this->session->userdata('kyc_id')
					));
					/* Start Code To Showing Count On Member List*/
					if ($next_id == '') {
						$next_id = 0;
					}

					// $totalRecCount=$this->get_allocation_type_cnt();

					if ($srno > $totalRecCount) {

						// $srno=$totalRecCount;

						$srno = 1;
					} else {
						$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
						$arr = array_slice($original_allotted_Arr, -$totalRecCount);
						$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
						$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
						$memberNo = $next_id;
						$updated_list_index = array_search($memberNo, $reversedArr_list);
						$srno = $updated_list_index;
					}

					redirect(base_url() . 'admin/kyc/Kyc/pending_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
					/* Close Code To Showing Count On Member List*/
				} elseif ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM') {
					if ($name_checkbox == '1' && $dob_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1') {
						$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

						// $sucess='Record sent to approver !!';
						// redirect(base_url().'admin/kyc/Kyc/pending_member/'.$regnumber);

					} else {

						// email send on recommend...
						// email to user

						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$userpass = $aes->decrypt($userdata[0]['usrpassword']);
						$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
						$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
						$msg = implode(',', $update_data);
						$emailerstr = $this->master_model->getRecords('emailer', array(
							'emailer_name' => 'recommendation_email_NM'
						));
						$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
						$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
						$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
						$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);

						// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

						$info_arr = array(

							//	'to'=> "kyciibf@gmail.com",
							'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
							'from' => $emailerstr[0]['from'],
							'subject' => $emailerstr[0]['subject'],
							'message' => $final_str
						);
						if ($this->Emailsending->mailsend($info_arr)) {

							// $this->session->set_flashdata('success','KYC recommend for  '.$data[0]['regnumber']. ' (previous record) & Email sent successfully !!');

							$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

							// $sucess='KYC recommend for the candidate & Email send successfully !!';
							// log activity
							// get recommended fields data from member registration -

							$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
							$old_data = $this->master_model->getRecords("member_registration", array(
								'regnumber' => $regnumber,
								'isactive' => '1'
							), $select);
							$log_desc['old_data'] = $old_data;
							$log_desc['inserted_data'] = $insert_data;
							$description = serialize($log_desc);
							$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

							// email log

							$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

							// make recommended fields empty  -

							if (in_array('Name', $update_data)) {
								/*$updatedata=array(
								'namesub'=>'',
								'firstname'=>'',
								'middlename'=>'',
								'lastname'=>''
								);*/
								$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
								$this->db->where('isactive', '1');
								$this->master_model->updateRecord('member_registration', $updatedata, array(
									'regnumber' => $regnumber
								));
							}

							if (in_array('DOB', $update_data)) {
								$updatedata['dateofbirth'] = '0000-00-00';

								// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

							}

							if (in_array('Employer', $update_data)) {
								$updatedata['associatedinstitute'] = '';

								// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

							}

							// -------check for  photo -----------#

							if (in_array('Photo', $update_data)) {
								$updatedata['scannedphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'p');
								$noarray = explode('/p_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $noarray[1];

									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to deleted photo ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
								}
							}
							// -------end check for  photo -----------#

							// ------- check for  signature-----------#

							if (in_array('Sign', $update_data)) {

								$updatedata['scannedsignaturephoto'] = '';
								$oldfilepath = get_img_name($regnumber, 's');
								$noarray = explode('/s_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended Signature Rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {

										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';

										$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										//$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								} else {

									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';

									$this->KYC_Log_model->create_log('member signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
								}
							}

							// -------End check for  photo -----------#

							// ------- check for  idproof-----------#

							if (in_array('Id-proof', $update_data)) {

								$updatedata['idproofphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'pr');
								$noarray = explode('/pr_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete idproof ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
								}
								// -------End  check for id proof -----------#

							}
						}
					}

					if (!empty($updatedata)) {
						$this->db->where('isactive', '1');
						$this->master_model->updateRecord('member_registration', $updatedata, array(
							'regnumber' => $regnumber
						));
					}

					$member = $this->master_model->getRecords("admin_kyc_users", array(
						'DATE(date)' => date('Y-m-d'),
						'user_id' => $this->session->userdata('kyc_id'),
						'list_type' => 'Pending'
					));
					$arrayid = explode(',', $member[0]['allotted_member_id']);
					$index = array_search($regnumber, $arrayid, true);

					// get next record

					$currentid = $index;
					$nextid = $currentid + 1;
					if (array_key_exists($nextid, $arrayid)) {
						$next_id = $arrayid[$nextid];
					} else {
						$next_id = $arrayid[0];
					}

					// end of next record
					// unset the  current id index

					unset($arrayid[$index]);
					if (count($arrayid) > 0) {
						foreach ($arrayid as $row) {
							$new_arrayid[] = $row;
						}
					}

					if (count($new_arrayid) > 0) {
						$regstr = implode(',', $new_arrayid);
					} else {
						$regstr = '';
						$next_id = '';
					}

					$update_data = array(
						'allotted_member_id' => $regstr
					);
					$this->db->where('DATE(date)', date('Y-m-d'));
					$this->db->where('list_type', 'Pending');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array(
						'user_id' => $this->session->userdata('kyc_id')
					));
					/* Start Code To Showing Count On Member List*/
					if ($next_id == '') {
						$next_id = 0;
					}

					// $totalRecCount=$this->get_allocation_type_cnt();

					if ($srno > $totalRecCount) {

						// $srno=$totalRecCount;

						$srno = 1;
					} else {
						$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
						$arr = array_slice($original_allotted_Arr, -$totalRecCount);
						$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
						$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
						$memberNo = $next_id;
						$updated_list_index = array_search($memberNo, $reversedArr_list);
						$srno = $updated_list_index;
					}

					redirect(base_url() . 'admin/kyc/Kyc/pending_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
					/* Close Code To Showing Count On Member List*/
				} elseif ($data[0]['registrationtype'] == 'O') {
					if ($data[0]['createdon'] >= '2022-04-01') {
						if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1' && $declaration_checkbox == '1') {

							// $this->session->set_flashdata('success','Recommended Successfully !!');
							// $sucess='Record sent to approver !!';  $data[0]['regnumber']

							$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

							// redirect(base_url().'admin/kyc/Kyc/pending_member/'.$regnumber);

						} else {

							// email send on recommend...
							// email to user

							/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommender_recommend_email'));
							$final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);

							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr=array(
							'to'=> "kyciibf@gmail.com",
							'from'=> $emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);*/
							include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

							$key = $this->config->item('pass_key');
							$aes = new CryptAES();
							$aes->set_key(base64_decode($key));
							$aes->require_pkcs5();
							$userpass = $aes->decrypt($userdata[0]['usrpassword']);
							$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							$msg = implode(',', $update_data);
							$emailerstr = $this->master_model->getRecords('emailer', array(
								'emailer_name' => 'recommendation_email_O'
							));
							$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
							$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
							$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
							$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

							// echo $final_str ;exit;
							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr = array(

								//'to'=> "kyciibf@gmail.com",
								'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'message' => $final_str
							);
							if ($this->Emailsending->mailsend($info_arr)) {

								// $sucess='KYC recommend for the candidate & Email send successfully !!';

								$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

								// log activity
								// get recommended fields data from member registration -

								$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
								$old_data = $this->master_model->getRecords("member_registration", array(
									'regnumber' => $regnumber,
									'isactive' => '1'
								), $select);
								$log_desc['old_data'] = $old_data;
								$log_desc['inserted_data'] = $insert_data;
								$description = serialize($log_desc);
								$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

								// email log

								$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

								// make recommended fields empty  -

								if (in_array('Name', $update_data)) {
									/*$updatedata=array(
									'namesub'=>'',
									'firstname'=>'',
									'middlename'=>'',
									'lastname'=>''
									);*/
									$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
									$this->db->where('isactive', '1');
									$this->master_model->updateRecord('member_registration', $updatedata, array(
										'regnumber' => $regnumber
									));
								}

								if (in_array('DOB', $update_data)) {
									$updatedata['dateofbirth'] = '0000-00-00';

									// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								if (in_array('Employer', $update_data)) {
									$updatedata['associatedinstitute'] = '';

									// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								// -------check for  photo -----------#

								if (in_array('Photo', $update_data)) {
									$updatedata['scannedphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'p');
									$noarray = explode('/p_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $noarray[1];
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended photo Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {

											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_p_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								}
								// -------end check for  photo -----------#

								// ------- check for  signature-----------#

								if (in_array('Sign', $update_data)) {

									$updatedata['scannedsignaturephoto'] = '';
									$oldfilepath = get_img_name($regnumber, 's');
									$noarray = explode('/s_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $noarray[1];
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {

											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_s_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

											//$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
										}
									} else {

										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member signature not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								}

								// -------End check for  photo -----------#


								// ------- check for  idproof-----------#

								if (in_array('Id-proof', $update_data)) {

									$updatedata['idproofphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'pr');
									$noarray = explode('/pr_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $noarray[1];
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_pr_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete idproof ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member idproof  not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										// $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
									// -------End  check for id proof -----------#


								}


								// ------- check for declaration-----------#

								if (in_array('Declaration', $update_data)) {

									$updatedata['declaration'] = '';
									$oldfilepath = get_img_name($regnumber, 'declaration');
									$noarray = explode('/declaration_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath;
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_declaration_' . $noarray[1];
										$description .=  ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended Declaration Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_declaration_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete declaration ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_declaration_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member declaration  not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										// $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
									// -------End  check for id proof -----------#
								}
							}
						}
					} else {
						if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1') {

							// $this->session->set_flashdata('success','Recommended Successfully !!');
							// $sucess='Record sent to approver !!';  $data[0]['regnumber']

							$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

							// redirect(base_url().'admin/kyc/Kyc/pending_member/'.$regnumber);

						} else {

							// email send on recommend...
							// email to user

							/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommender_recommend_email'));
							$final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);

							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr=array(
							'to'=> "kyciibf@gmail.com",
							'from'=> $emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);*/
							include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

							$key = $this->config->item('pass_key');
							$aes = new CryptAES();
							$aes->set_key(base64_decode($key));
							$aes->require_pkcs5();
							$userpass = $aes->decrypt($userdata[0]['usrpassword']);
							$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							$msg = implode(',', $update_data);
							$emailerstr = $this->master_model->getRecords('emailer', array(
								'emailer_name' => 'recommendation_email_O'
							));
							$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
							$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
							$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
							$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

							// echo $final_str ;exit;
							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr = array(

								//'to'=> "kyciibf@gmail.com",
								'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'message' => $final_str
							);
							if ($this->Emailsending->mailsend($info_arr)) {

								// $sucess='KYC recommend for the candidate & Email send successfully !!';

								$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

								// log activity
								// get recommended fields data from member registration -

								$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
								$old_data = $this->master_model->getRecords("member_registration", array(
									'regnumber' => $regnumber,
									'isactive' => '1'
								), $select);
								$log_desc['old_data'] = $old_data;
								$log_desc['inserted_data'] = $insert_data;
								$description = serialize($log_desc);
								$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

								// email log

								$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

								// make recommended fields empty  -

								if (in_array('Name', $update_data)) {
									/*$updatedata=array(
									'namesub'=>'',
									'firstname'=>'',
									'middlename'=>'',
									'lastname'=>''
									);*/
									$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
									$this->db->where('isactive', '1');
									$this->master_model->updateRecord('member_registration', $updatedata, array(
										'regnumber' => $regnumber
									));
								}

								if (in_array('DOB', $update_data)) {
									$updatedata['dateofbirth'] = '0000-00-00';

									// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								if (in_array('Employer', $update_data)) {
									$updatedata['associatedinstitute'] = '';

									// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								// -------check for  photo -----------#

								if (in_array('Photo', $update_data)) {
									$updatedata['scannedphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'p');
									$noarray = explode('/p_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $noarray[1];
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended photo Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {

											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_p_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								}
								// -------end check for  photo -----------#

								// ------- check for  signature-----------#

								if (in_array('Sign', $update_data)) {

									$updatedata['scannedsignaturephoto'] = '';
									$oldfilepath = get_img_name($regnumber, 's');
									$noarray = explode('/s_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $noarray[1];
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {

											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_s_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

											//$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
										}
									} else {

										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member signature not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								}

								// -------End check for  photo -----------#


								// ------- check for  idproof-----------#

								if (in_array('Id-proof', $update_data)) {

									$updatedata['idproofphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'pr');
									$noarray = explode('/pr_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $noarray[1];
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_pr_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete idproof ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member idproof  not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										// $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
									// -------End  check for id proof -----------#


								}


								// ------- check for declaration (not required for old members)-----------#

								// if (in_array('Declaration', $update_data)) {

								// 	$updatedata['declaration'] = '';
								// 	$oldfilepath = get_img_name($regnumber, 'declaration');
								// 	$noarray = explode('/declaration_', $oldfilepath);
								// 	$description = 'oldpath:' . $oldfilepath;
								// 	if (isset($noarray[1])) {
								// 		$file_path = implode('/', explode('/', $oldfilepath, -1));
								// 		$photo_file = 'k_declaration_' . $noarray[1];
								// 		$description .=  ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								// 		if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
								// 			$this->KYC_Log_model->create_log('Recommended Declaration Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								// 		} else {
								// 			$file_path = implode('/', explode('/', $oldfilepath, -1));
								// 			$photo_file = 'k_declaration_' . $regnumber;
								// 			@rename($oldfilepath, $file_path . '/' . $photo_file);
								// 			$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								// 			$this->KYC_Log_model->create_log('fail to delete declaration ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								// 		}
								// 	} else {
								// 		$file_path = implode('/', explode('/', $oldfilepath, -1));
								// 		$photo_file = 'k_declaration_' . $regnumber;
								// 		@rename($oldfilepath, $file_path . '/' . $photo_file);
								// 		$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								// 		$this->KYC_Log_model->create_log('member declaration  not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

								// 		// $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
								// 	}
								// 	// -------End  check for id proof -----------#
								// }
							}
						}
					}

					if (!empty($updatedata)) {
						$this->db->where('isactive', '1');
						$this->master_model->updateRecord('member_registration', $updatedata, array(
							'regnumber' => $regnumber
						));
					}

					$member = $this->master_model->getRecords("admin_kyc_users", array(
						'DATE(date)' => date('Y-m-d'),
						'user_id' => $this->session->userdata('kyc_id'),
						'list_type' => 'Pending'
					));
					$arrayid = explode(',', $member[0]['allotted_member_id']);
					$index = array_search($regnumber, $arrayid, true);
					$currentid = $index;
					$nextid = $currentid + 1;
					if (array_key_exists($nextid, $arrayid)) {
						$next_id = $arrayid[$nextid];
					} else {
						$next_id = $arrayid[0];
					}

					unset($arrayid[$index]);
					if (count($arrayid) > 0) {
						foreach ($arrayid as $row) {
							$new_arrayid[] = $row;
						}
					}

					if (count($new_arrayid) > 0) {
						$regstr = implode(',', $new_arrayid);
					} else {
						$regstr = '';
						$next_id = '';
					}

					$update_data = array(
						'allotted_member_id' => $regstr
					);
					$this->db->where('DATE(date)', date('Y-m-d '));
					$this->db->where('list_type', 'Pending');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array(
						'user_id' => $this->session->userdata('kyc_id')
					));
					/* Start Code To Showing Count On Member List*/
					if ($next_id == '') {
						$next_id = 0;
					}

					// $totalRecCount=$this->get_allocation_type_cnt();

					if ($srno > $totalRecCount) {

						// $srno=$totalRecCount;

						$srno = 1;
					} else {
						$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
						$arr = array_slice($original_allotted_Arr, -$totalRecCount);
						$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
						$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
						$memberNo = $next_id;
						$updated_list_index = array_search($memberNo, $reversedArr_list);
						$srno = $updated_list_index;
					}

					redirect(base_url() . 'admin/kyc/Kyc/pending_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
					/* Close Code To Showing Count On Member List*/
				}
			}

			// $data['next_id']= $next_id;
			// $data['next_id'] = '77066';

			if ($regnumber) 
			{
				$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,registrationtype,email,createdon';
				$members = $this->master_model->getRecords("member_registration a", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select, "", '0', '1');
				if (count($members) > 0) {
					$memregnumber = $members[0]['regnumber'];
				}
				/*if(count($members))
				{
				$data['result'] = $members;
				$data['reg_no'] = $members[0]['regnumber'];
				}*/
			}

			$recommnended_members_data = $this->master_model->getRecords("member_kyc", array(
				'regnumber' => $regnumber
			), '', array(
				'kyc_id' => 'DESC'
			));

			// $data['recomended_mem_data']=$recommnended_members_data;
			// echo count($members);exit;

			$data = array(
				'result' => $members,
				'reg_no' => $memregnumber,
				'recomended_mem_data' => $recommnended_members_data,
				'next_id' => $next_id,
				'success' => $sucess
			);
			$data['srno'] = $srno;
			$data['totalRecCount'] = $totalRecCount;
			$this->load->view('admin/kyc/pending_kyc_list', $data);
		} else {
			$this->session->set_flashdata('success', $this->session->flashdata('success'));

			// $this->session->set_flashdata('success','KYC recommend for last record & Email send successfully !!');
			// $this->session->set_flashdata('error','Invalid record!!');

			redirect(base_url() . 'admin/kyc/Kyc/pending_allocated_list');
		}
	}
	/*
	- SAGAR WALZADE CODE START HERE
	- function use : Function to fetch list of members to initiate KYC
	- Changes : declaration field added, previous function renamed into "member_old"
	*/
	public function member($regnumber = NULL, $srno = NULL, $totalRecCount = NULL)
	{
		if ($regnumber) 
		{

			// echo $regnumber;exit;

			$next_id = $sucess = $memregnumber = $description = '';
			$oldfilepath = $file_path = $photo_file = '';
			$data['result'] = $new_arrayid = $noarray = array();
			$registrationtype = '';
			$data['reg_no'] = ' ';
			$employer = array();
			$field_count = 0;
			$data = $update_data = $old_user_data = array();
			$name = array();
			$state = '1';
			$today = date("Y-m-d H:i:s");
			$date = date("Y-m-d H:i:s");
			if (isset($_POST['btnSubmit'])) 
			{
				$select = 'regnumber,registrationtype,email,createdon';
				$data = $this->master_model->getRecords('member_registration', array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);
				if (isset($_POST['cbox'])) {
					$name = $this->input->post('cbox');
				}

				$regnumber = $data[0]['regnumber'];

				// optional
				// echo "You chose the following color(s): <br />";

				$check_arr = array();
				if (count($name) > 0) {
					foreach ($name as $cbox) {

						// echo $cbox."<br />";

						$check_arr[] = $cbox;
					}
				}

				$msg = 'Edit your profile as :-';
				if (count($check_arr) > 0) {
					if (in_array('name_checkbox', $check_arr)) {
						$name_checkbox = '1';
					} else {
						$name_checkbox = '0';
						$field_count++;
						$update_data[] = 'Name';
						$msg .= 'Name,';
					}

					if (in_array('dob_checkbox', $check_arr)) {
						$dob_checkbox = '1';
					} else {
						$dob_checkbox = '0';
						$field_count++;
						$update_data[] = 'DOB';
						$msg .= 'Date of Birth ,';
					}

					if ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM') {
						if (in_array('emp_checkbox', $check_arr)) {
							$emp_checkbox = '1';
						} else {
							$emp_checkbox = '1';
						}
					} elseif ($data[0]['registrationtype'] == 'O' || $data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A') {
						if (in_array('emp_checkbox', $check_arr)) {
							$emp_checkbox = '1';
						} else {
							$emp_checkbox = '0';
							$field_count++;
							$update_data[] = 'Employer';
							$msg .= 'Employer,';
						}
					}

					if (in_array('photo_checkbox', $check_arr)) {
						$photo_checkbox = '1';
					} else {
						$photo_checkbox = '0';
						$field_count++;
						$update_data[] = 'Photo';
						$msg .= 'Photo,';
					}

					if (in_array('sign_checkbox', $check_arr)) {
						$sign_checkbox = '1';
					} else {
						$sign_checkbox = '0';
						$field_count++;
						$update_data[] = 'Sign';
						$msg .= 'Sign,';
					}

					if (in_array('idprf_checkbox', $check_arr)) {
						$idprf_checkbox = '1';
					} else {
						$idprf_checkbox = '0';
						$field_count++;
						$update_data[] = 'Id-proof';
						$msg .= 'Id-proof';
					}

					if ($data[0]['registrationtype'] == 'O') {
						if ($data[0]['createdon'] >= '2022-04-01') {
							if (in_array('declaration_checkbox', $check_arr)) {
								$declaration_checkbox = '1';
							} else {
								$declaration_checkbox = '0';
								$field_count++;
								$update_data[] = 'Declaration';
								$msg .= 'Declaration';
							}
						} else {
							if (in_array('declaration_checkbox', $check_arr)) {
								$declaration_checkbox = '1';
							} else {
								$declaration_checkbox = '0';
							}
						}
					} else {
						$declaration_checkbox = '0';
					}
				} 
				else 
				{
					$name_checkbox = '0';
					$msg .= 'Name,';
					$field_count++;
					$update_data[] = 'Name';
					$dob_checkbox = '0';
					$msg .= 'Date of Birth ,';
					$field_count++;
					$update_data[] = 'DOB';
					if ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM') {
						$emp_checkbox = '1';
					} else {
						$emp_checkbox = '0';
						$msg .= 'Employer,';
						$field_count++;
						$update_data[] = 'Employer';
					}

					$photo_checkbox = '0';
					$msg .= 'Photo,';
					$field_count++;
					$update_data[] = 'Photo';
					$sign_checkbox = '0';
					$msg .= 'Sign,';
					$field_count++;
					$update_data[] = 'Sign';
					$idprf_checkbox = '0';
					$msg .= 'Id-proof,';
					$field_count++;
					$update_data[] = 'Id-proof';
					if ($data[0]['registrationtype'] == 'O') {
						if ($data[0]['createdon'] >= '2022-04-01') {
							$declaration_checkbox = '0';
							$msg .= 'Declaration';
							$field_count++;
							$update_data[] = 'Declaration';
						} else {
							$declaration_checkbox = '0';
						}
					} else {
						$declaration_checkbox = '0';
					}
				}

				$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
				$old_user_data = $this->master_model->getRecords('member_registration', array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);
				$email = $data[0]['email'];
				$insert_data = array(
					'regnumber' => $data[0]['regnumber'],
					'mem_type' => $data[0]['registrationtype'],
					'mem_name' => $name_checkbox,
					// 'email_address' => $data[0]['email'],
					'mem_dob' => $dob_checkbox,
					'mem_associate_inst' => $emp_checkbox,
					'mem_photo' => $photo_checkbox,
					'mem_sign' => $sign_checkbox,
					'mem_proof' => $idprf_checkbox,
					'mem_declaration' => $declaration_checkbox,
					'field_count' => $field_count,
					'old_data' => serialize($old_user_data),
					'kyc_status' => '0',
					'kyc_state' => $state,
					'recommended_by' => $this->session->userdata('kyc_id'),
					'user_type' => $this->session->userdata('role'),
					'recommended_date' => $today,
					'record_source' => 'New'
				);
				$last_insterid = $this->master_model->insertRecord('member_kyc', $insert_data, true);

				// log activity
				// get recommended fields data from member registration -

				$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
				$old_data = $this->master_model->getRecords("member_registration", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);
				$log_desc['old_data'] = $old_data;
				$log_desc['inserted_data'] = $insert_data;
				$description = serialize($log_desc);
				$userdata = $this->master_model->getRecords("member_registration", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				));

				// get user details
				// $userdata=$this->master_model->getRecords("member_registration",array('regnumber'=>$regnumber));
				// Log activity
				// $this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'),$last_insterid,$regnumber, $description);

				if ($data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A') {
					if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1') {

						// $this->session->set_flashdata('success','Recommended Successfully !!');
						// $sucess='Record sent to approver !!';  $data[0]['regnumber']

						$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

						// redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);

					} else {


						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$userpass = $aes->decrypt($userdata[0]['usrpassword']);
						$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
						$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
						$msg = implode(',', $update_data);
						$emailerstr = $this->master_model->getRecords('emailer', array(
							'emailer_name' => 'recommendation_email_O'
						));
						$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
						$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
						$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
						$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);


						$info_arr = array(

							//'to'=> "kyciibf@gmail.com",
							'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
							'from' => $emailerstr[0]['from'],
							'subject' => $emailerstr[0]['subject'],
							'message' => $final_str
						);
						if ($this->Emailsending->mailsend($info_arr)) {

							// $sucess='KYC recommend for the candidate & Email send successfully !!';

							$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

							// log activity
							// get recommended fields data from member registration -

							$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
							$old_data = $this->master_model->getRecords("member_registration", array(
								'regnumber' => $regnumber,
								'isactive' => '1'
							), $select);
							$log_desc['old_data'] = $old_data;
							$log_desc['inserted_data'] = $insert_data;
							$description = serialize($log_desc);
							$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

							// email log

							$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

							// make recommended fields empty  -

							if (in_array('Name', $update_data)) {

								$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
								$this->db->where('isactive', '1');
								$this->master_model->updateRecord('member_registration', $updatedata, array(
									'regnumber' => $regnumber
								));
							}

							if (in_array('DOB', $update_data)) {
								$updatedata['dateofbirth'] = '0000-00-00';
							}

							if (in_array('Employer', $update_data)) {
								$updatedata['associatedinstitute'] = '';
							}

							// -------check for  photo -----------#

							if (in_array('Photo', $update_data)) {
								$updatedata['scannedphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'p');
								$noarray = explode('/p_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $noarray[1];
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended photo Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {

										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
								}
							}
							// -------end check for  photo -----------#

							// ------- check for  signature-----------#

							if (in_array('Sign', $update_data)) {

								$updatedata['scannedsignaturephoto'] = '';
								$oldfilepath = get_img_name($regnumber, 's');
								$noarray = explode('/s_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {

										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										//$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								} else {

									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member signature not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
								}
							}

							// -------End check for  photo -----------#


							// ------- check for  idproof-----------#

							if (in_array('Id-proof', $update_data)) {

								$updatedata['idproofphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'pr');
								$noarray = explode('/pr_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete idproof ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member idproof  not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

									// $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
								}
								// -------End  check for id proof -----------#
							}
						}
					}

					if (!empty($updatedata)) {
						$this->db->where('isactive', '1');
						$this->master_model->updateRecord('member_registration', $updatedata, array(
							'regnumber' => $regnumber
						));
					}

					$member = $this->master_model->getRecords("admin_kyc_users", array(
						'DATE(date)' => date('Y-m-d'),
						'user_id' => $this->session->userdata('kyc_id'),
						'list_type' => 'New'
					));
					$arrayid = explode(',', $member[0]['allotted_member_id']);
					$index = array_search($regnumber, $arrayid, true);
					$currentid = $index;
					$nextid = $currentid + 1;
					if (array_key_exists($nextid, $arrayid)) {
						$next_id = $arrayid[$nextid];
					} else {
						$next_id = $arrayid[0];
					}

					unset($arrayid[$index]);
					if (count($arrayid) > 0) {
						foreach ($arrayid as $row) {
							$new_arrayid[] = $row;
						}
					}

					if (count($new_arrayid) > 0) {
						$regstr = implode(',', $new_arrayid);
					} else {
						$regstr = '';
						$next_id = '';
					}

					$update_data = array(
						'allotted_member_id' => $regstr
					);
					$this->db->where('DATE(date)', date('Y-m-d '));
					$this->db->where('list_type', 'New');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array(
						'user_id' => $this->session->userdata('kyc_id')
					));
					/* Start Code To Showing Count On Member List*/
					if ($next_id == '') {
						$next_id = 0;
					}

					// $totalRecCount=$this->get_allocation_type_cnt();

					if ($srno > $totalRecCount) {

						// $srno=$totalRecCount;

						$srno = 1;
					} else {
						$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
						$arr = array_slice($original_allotted_Arr, -$totalRecCount);
						$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
						$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
						$memberNo = $next_id;
						$updated_list_index = array_search($memberNo, $reversedArr_list);
						$srno = $updated_list_index;
					}

					redirect(base_url() . 'admin/kyc/Kyc/member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
					/* Close Code To Showing Count On Member List*/
				} elseif ($data[0]['registrationtype'] == 'DB' || $data[0]['registrationtype'] == 'NM') {
					if ($name_checkbox == '1' && $dob_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1') {
						$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

						// $sucess='Record sent to approver !!';
						// redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);

					} else {

						// email send on recommend...
						// email to user

						include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

						$key = $this->config->item('pass_key');
						$aes = new CryptAES();
						$aes->set_key(base64_decode($key));
						$aes->require_pkcs5();
						$userpass = $aes->decrypt($userdata[0]['usrpassword']);
						$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
						$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
						$msg = implode(',', $update_data);
						$emailerstr = $this->master_model->getRecords('emailer', array(
							'emailer_name' => 'recommendation_email_NM'
						));
						$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
						$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
						$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
						$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
						$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);

						// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

						$info_arr = array(

							//	'to'=> "kyciibf@gmail.com",
							'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
							'from' => $emailerstr[0]['from'],
							'subject' => $emailerstr[0]['subject'],
							'message' => $final_str
						);
						if ($this->Emailsending->mailsend($info_arr)) {

							// $this->session->set_flashdata('success','KYC recommend for  '.$data[0]['regnumber']. ' (previous record) & Email sent successfully !!');

							$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

							// $sucess='KYC recommend for the candidate & Email send successfully !!';
							// log activity
							// get recommended fields data from member registration -

							$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
							$old_data = $this->master_model->getRecords("member_registration", array(
								'regnumber' => $regnumber,
								'isactive' => '1'
							), $select);
							$log_desc['old_data'] = $old_data;
							$log_desc['inserted_data'] = $insert_data;
							$description = serialize($log_desc);
							$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

							// email log

							$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

							// make recommended fields empty  -

							if (in_array('Name', $update_data)) {
								/*$updatedata=array(
								'namesub'=>'',
								'firstname'=>'',
								'middlename'=>'',
								'lastname'=>''
								);*/
								$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
								$this->db->where('isactive', '1');
								$this->master_model->updateRecord('member_registration', $updatedata, array(
									'regnumber' => $regnumber
								));
							}

							if (in_array('DOB', $update_data)) {
								$updatedata['dateofbirth'] = '0000-00-00';

								// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

							}

							if (in_array('Employer', $update_data)) {
								$updatedata['associatedinstitute'] = '';

								// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

							}

							// -------check for  photo -----------#

							if (in_array('Photo', $update_data)) {
								$updatedata['scannedphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'p');
								$noarray = explode('/p_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $noarray[1];

									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended photo rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to deleted photo ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_p_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
								}
							}
							// -------end check for  photo -----------#

							// ------- check for  signature-----------#

							if (in_array('Sign', $update_data)) {

								$updatedata['scannedsignaturephoto'] = '';
								$oldfilepath = get_img_name($regnumber, 's');
								$noarray = explode('/s_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended Signature Rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {

										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';

										$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										//$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								} else {

									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_s_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';

									$this->KYC_Log_model->create_log('member signature not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
								}
							}

							// -------End check for  photo -----------#

							// ------- check for  idproof-----------#

							if (in_array('Id-proof', $update_data)) {

								$updatedata['idproofphoto'] = '';
								$oldfilepath = get_img_name($regnumber, 'pr');
								$noarray = explode('/pr_', $oldfilepath);
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (isset($noarray[1])) {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $noarray[1];
									if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
										$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('fail to delete idproof ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
								} else {
									$file_path = implode('/', explode('/', $oldfilepath, -1));
									$photo_file = 'k_pr_' . $regnumber;
									@rename($oldfilepath, $file_path . '/' . $photo_file);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									$this->KYC_Log_model->create_log('member idproof  not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
									//$this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
								}
								// -------End  check for id proof -----------#

							}
						}
					}

					if (!empty($updatedata)) {
						$this->db->where('isactive', '1');
						$this->master_model->updateRecord('member_registration', $updatedata, array(
							'regnumber' => $regnumber
						));
					}

					$member = $this->master_model->getRecords("admin_kyc_users", array(
						'DATE(date)' => date('Y-m-d'),
						'user_id' => $this->session->userdata('kyc_id'),
						'list_type' => 'New'
					));
					$arrayid = explode(',', $member[0]['allotted_member_id']);
					$index = array_search($regnumber, $arrayid, true);

					// get next record

					$currentid = $index;
					$nextid = $currentid + 1;
					if (array_key_exists($nextid, $arrayid)) {
						$next_id = $arrayid[$nextid];
					} else {
						$next_id = $arrayid[0];
					}

					// end of next record
					// unset the  current id index

					unset($arrayid[$index]);
					if (count($arrayid) > 0) {
						foreach ($arrayid as $row) {
							$new_arrayid[] = $row;
						}
					}

					if (count($new_arrayid) > 0) {
						$regstr = implode(',', $new_arrayid);
					} else {
						$regstr = '';
						$next_id = '';
					}

					$update_data = array(
						'allotted_member_id' => $regstr
					);
					$this->db->where('DATE(date)', date('Y-m-d'));
					$this->db->where('list_type', 'New');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array(
						'user_id' => $this->session->userdata('kyc_id')
					));
					/* Start Code To Showing Count On Member List*/
					if ($next_id == '') {
						$next_id = 0;
					}

					// $totalRecCount=$this->get_allocation_type_cnt();

					if ($srno > $totalRecCount) {

						// $srno=$totalRecCount;

						$srno = 1;
					} else {
						$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
						$arr = array_slice($original_allotted_Arr, -$totalRecCount);
						$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
						$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
						$memberNo = $next_id;
						$updated_list_index = array_search($memberNo, $reversedArr_list);
						$srno = $updated_list_index;
					}

					redirect(base_url() . 'admin/kyc/Kyc/member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
					/* Close Code To Showing Count On Member List*/
				} elseif ($data[0]['registrationtype'] == 'O') {
					if ($data[0]['createdon'] >= '2022-04-01') {
						if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1' && $declaration_checkbox == '1') {

							// $this->session->set_flashdata('success','Recommended Successfully !!');
							// $sucess='Record sent to approver !!';  $data[0]['regnumber']

							$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

							// redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);

						} else {

							// email send on recommend...
							// email to user

							/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommender_recommend_email'));
							$final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);

							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr=array(
							'to'=> "kyciibf@gmail.com",
							'from'=> $emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);*/
							include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

							$key = $this->config->item('pass_key');
							$aes = new CryptAES();
							$aes->set_key(base64_decode($key));
							$aes->require_pkcs5();
							$userpass = $aes->decrypt($userdata[0]['usrpassword']);
							$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							$msg = implode(',', $update_data);
							$emailerstr = $this->master_model->getRecords('emailer', array(
								'emailer_name' => 'recommendation_email_O'
							));
							$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
							$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
							$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
							$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

							// echo $final_str ;exit;
							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr = array(

								//'to'=> "kyciibf@gmail.com",
								'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'message' => $final_str
							);
							if ($this->Emailsending->mailsend($info_arr)) {

								// $sucess='KYC recommend for the candidate & Email send successfully !!';

								$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

								// log activity
								// get recommended fields data from member registration -

								$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
								$old_data = $this->master_model->getRecords("member_registration", array(
									'regnumber' => $regnumber,
									'isactive' => '1'
								), $select);
								$log_desc['old_data'] = $old_data;
								$log_desc['inserted_data'] = $insert_data;
								$description = serialize($log_desc);
								$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

								// email log

								$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

								// make recommended fields empty  -

								if (in_array('Name', $update_data)) {
									/*$updatedata=array(
									'namesub'=>'',
									'firstname'=>'',
									'middlename'=>'',
									'lastname'=>''
									);*/
									$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
									$this->db->where('isactive', '1');
									$this->master_model->updateRecord('member_registration', $updatedata, array(
										'regnumber' => $regnumber
									));
								}

								if (in_array('DOB', $update_data)) {
									$updatedata['dateofbirth'] = '0000-00-00';

									// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								if (in_array('Employer', $update_data)) {
									$updatedata['associatedinstitute'] = '';

									// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								// -------check for  photo -----------#

								if (in_array('Photo', $update_data)) {
									$updatedata['scannedphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'p');
									$noarray = explode('/p_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $noarray[1];
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended photo Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {

											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_p_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								}
								// -------end check for  photo -----------#

								// ------- check for  signature-----------#

								if (in_array('Sign', $update_data)) {

									$updatedata['scannedsignaturephoto'] = '';
									$oldfilepath = get_img_name($regnumber, 's');
									$noarray = explode('/s_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $noarray[1];
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {

											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_s_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

											//$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
										}
									} else {

										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member signature not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								}

								// -------End check for  photo -----------#


								// ------- check for  idproof-----------#

								if (in_array('Id-proof', $update_data)) {

									$updatedata['idproofphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'pr');
									$noarray = explode('/pr_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $noarray[1];
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_pr_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete idproof ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member idproof  not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										// $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
									// -------End  check for id proof -----------#


								}


								// ------- check for declaration-----------#

								if (in_array('Declaration', $update_data)) {

									$updatedata['declaration'] = '';
									$oldfilepath = get_img_name($regnumber, 'declaration');
									$noarray = explode('/declaration_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath;
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_declaration_' . $noarray[1];
										$description .=  ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended Declaration Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_declaration_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete declaration ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_declaration_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member declaration  not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										// $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
									// -------End  check for id proof -----------#
								}
							}
						}
					} else {
						if ($name_checkbox == '1' && $dob_checkbox == '1' && $emp_checkbox == '1' && $photo_checkbox == '1' && $sign_checkbox == '1' && $idprf_checkbox == '1') {

							// $this->session->set_flashdata('success','Recommended Successfully !!');
							// $sucess='Record sent to approver !!';  $data[0]['regnumber']

							$this->session->set_flashdata('success', 'KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');

							// redirect(base_url().'admin/kyc/Kyc/member/'.$regnumber);

						} else {

							// email send on recommend...
							// email to user

							/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommender_recommend_email'));
							$final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);

							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr=array(
							'to'=> "kyciibf@gmail.com",
							'from'=> $emailerstr[0]['from'],
							'subject'=>$emailerstr[0]['subject'],
							'message'=>$final_str
							);*/
							include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

							$key = $this->config->item('pass_key');
							$aes = new CryptAES();
							$aes->set_key(base64_decode($key));
							$aes->require_pkcs5();
							$userpass = $aes->decrypt($userdata[0]['usrpassword']);
							$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
							$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
							$msg = implode(',', $update_data);
							$emailerstr = $this->master_model->getRecords('emailer', array(
								'emailer_name' => 'recommendation_email_O'
							));
							$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
							$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
							$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
							$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
							$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);

							// echo $final_str ;exit;
							// $final_str= str_replace("#password#", "".$decpass."",  $newstring);

							$info_arr = array(

								//'to'=> "kyciibf@gmail.com",
								'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'message' => $final_str
							);
							if ($this->Emailsending->mailsend($info_arr)) {

								// $sucess='KYC recommend for the candidate & Email send successfully !!';

								$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');

								// log activity
								// get recommended fields data from member registration -

								$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration';
								$old_data = $this->master_model->getRecords("member_registration", array(
									'regnumber' => $regnumber,
									'isactive' => '1'
								), $select);
								$log_desc['old_data'] = $old_data;
								$log_desc['inserted_data'] = $insert_data;
								$description = serialize($log_desc);
								$this->KYC_Log_model->create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);

								// email log

								$this->KYC_Log_model->email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

								// make recommended fields empty  -

								if (in_array('Name', $update_data)) {
									/*$updatedata=array(
									'namesub'=>'',
									'firstname'=>'',
									'middlename'=>'',
									'lastname'=>''
									);*/
									$updatedata['namesub'] = $updatedata['firstname'] = $updatedata['middlename'] = $updatedata['lastname'] = '';
									$this->db->where('isactive', '1');
									$this->master_model->updateRecord('member_registration', $updatedata, array(
										'regnumber' => $regnumber
									));
								}

								if (in_array('DOB', $update_data)) {
									$updatedata['dateofbirth'] = '0000-00-00';

									// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								if (in_array('Employer', $update_data)) {
									$updatedata['associatedinstitute'] = '';

									// $this->master_model->updateRecord('member_registration',$updatedata, array('regnumber'=>$regnumber));

								}

								// -------check for  photo -----------#

								if (in_array('Photo', $update_data)) {
									$updatedata['scannedphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'p');
									$noarray = explode('/p_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $noarray[1];
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended photo Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {

											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_p_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete photo', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted photo ',$this->session->userdata('id'),0, $reg_no, 'Photo');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_p_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member photo not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member photo not found ',$this->session->userdata('id'),0, $reg_no, 'Photo');
									}
								}
								// -------end check for  photo -----------#

								// ------- check for  signature-----------#

								if (in_array('Sign', $update_data)) {

									$updatedata['scannedsignaturephoto'] = '';
									$oldfilepath = get_img_name($regnumber, 's');
									$noarray = explode('/s_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $noarray[1];
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended signature rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {

											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_s_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete Signature', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

											//$this->KYC_Log_model->create_log('fail to deleted Signature ',$this->session->userdata('id'),0, $reg_no, 'Signature');
										}
									} else {

										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_s_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member signature not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
										//$this->KYC_Log_model->create_log('member Signature not found ',$this->session->userdata('id'),0, $reg_no, 'Signature');
									}
								}

								// -------End check for  photo -----------#


								// ------- check for  idproof-----------#

								if (in_array('Id-proof', $update_data)) {

									$updatedata['idproofphoto'] = '';
									$oldfilepath = get_img_name($regnumber, 'pr');
									$noarray = explode('/pr_', $oldfilepath);
									$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
									if (isset($noarray[1])) {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $noarray[1];
										if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
											$this->KYC_Log_model->create_log('Recommended idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
										} else {
											$file_path = implode('/', explode('/', $oldfilepath, -1));
											$photo_file = 'k_pr_' . $regnumber;
											@rename($oldfilepath, $file_path . '/' . $photo_file);
											$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
											$this->KYC_Log_model->create_log('fail to delete idproof ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
											//$this->KYC_Log_model->create_log('fail to deleted idproof ',$this->session->userdata('id'),0, $reg_no, 'idproof');
										}
									} else {
										$file_path = implode('/', explode('/', $oldfilepath, -1));
										$photo_file = 'k_pr_' . $regnumber;
										@rename($oldfilepath, $file_path . '/' . $photo_file);
										$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
										$this->KYC_Log_model->create_log('member idproof  not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

										// $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
									}
									// -------End  check for id proof -----------#


								}


								// ------- check for declaration (not required for old members)-----------#

								// if (in_array('Declaration', $update_data)) {

								// 	$updatedata['declaration'] = '';
								// 	$oldfilepath = get_img_name($regnumber, 'declaration');
								// 	$noarray = explode('/declaration_', $oldfilepath);
								// 	$description = 'oldpath:' . $oldfilepath;
								// 	if (isset($noarray[1])) {
								// 		$file_path = implode('/', explode('/', $oldfilepath, -1));
								// 		$photo_file = 'k_declaration_' . $noarray[1];
								// 		$description .=  ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								// 		if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
								// 			$this->KYC_Log_model->create_log('Recommended Declaration Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								// 		} else {
								// 			$file_path = implode('/', explode('/', $oldfilepath, -1));
								// 			$photo_file = 'k_declaration_' . $regnumber;
								// 			@rename($oldfilepath, $file_path . '/' . $photo_file);
								// 			$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								// 			$this->KYC_Log_model->create_log('fail to delete declaration ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								// 		}
								// 	} else {
								// 		$file_path = implode('/', explode('/', $oldfilepath, -1));
								// 		$photo_file = 'k_declaration_' . $regnumber;
								// 		@rename($oldfilepath, $file_path . '/' . $photo_file);
								// 		$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								// 		$this->KYC_Log_model->create_log('member declaration  not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);

								// 		// $this->KYC_Log_model->create_log('member idproof  not found',$this->session->userdata('id'),0, $reg_no, 'idproof');
								// 	}
								// 	// -------End  check for id proof -----------#
								// }
							}
						}
					}

					if (!empty($updatedata)) {
						$this->db->where('isactive', '1');
						$this->master_model->updateRecord('member_registration', $updatedata, array(
							'regnumber' => $regnumber
						));
					}

					$member = $this->master_model->getRecords("admin_kyc_users", array(
						'DATE(date)' => date('Y-m-d'),
						'user_id' => $this->session->userdata('kyc_id'),
						'list_type' => 'New'
					));
					$arrayid = explode(',', $member[0]['allotted_member_id']);
					$index = array_search($regnumber, $arrayid, true);
					$currentid = $index;
					$nextid = $currentid + 1;
					if (array_key_exists($nextid, $arrayid)) {
						$next_id = $arrayid[$nextid];
					} else {
						$next_id = $arrayid[0];
					}

					unset($arrayid[$index]);
					if (count($arrayid) > 0) {
						foreach ($arrayid as $row) {
							$new_arrayid[] = $row;
						}
					}

					if (count($new_arrayid) > 0) {
						$regstr = implode(',', $new_arrayid);
					} else {
						$regstr = '';
						$next_id = '';
					}

					$update_data = array(
						'allotted_member_id' => $regstr
					);
					$this->db->where('DATE(date)', date('Y-m-d '));
					$this->db->where('list_type', 'New');
					$this->master_model->updateRecord('admin_kyc_users', $update_data, array(
						'user_id' => $this->session->userdata('kyc_id')
					));
					/* Start Code To Showing Count On Member List*/
					if ($next_id == '') {
						$next_id = 0;
					}

					// $totalRecCount=$this->get_allocation_type_cnt();

					if ($srno > $totalRecCount) {

						// $srno=$totalRecCount;

						$srno = 1;
					} else {
						$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
						$arr = array_slice($original_allotted_Arr, -$totalRecCount);
						$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
						$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
						$memberNo = $next_id;
						$updated_list_index = array_search($memberNo, $reversedArr_list);
						$srno = $updated_list_index;
					}

					redirect(base_url() . 'admin/kyc/Kyc/member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
					/* Close Code To Showing Count On Member List*/
				}
			}

			// $data['next_id']= $next_id;
			// $data['next_id'] = '77066';

			if ($regnumber) 
			{
				$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,registrationtype,email,createdon';
				$members = $this->master_model->getRecords("member_registration a", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select, "", '0', '1');
				if (count($members) > 0) {
					$memregnumber = $members[0]['regnumber'];
				}
				/*if(count($members))
				{
				$data['result'] = $members;
				$data['reg_no'] = $members[0]['regnumber'];
				}*/
			}

			$recommnended_members_data = $this->master_model->getRecords("member_kyc", array(
				'regnumber' => $regnumber
			), '', array(
				'kyc_id' => 'DESC'
			));

			// $data['recomended_mem_data']=$recommnended_members_data;
			// echo count($members);exit;

			$data = array(
				'result' => $members,
				'reg_no' => $memregnumber,
				'recomended_mem_data' => $recommnended_members_data,
				'next_id' => $next_id,
				'success' => $sucess
			);
			$data['srno'] = $srno;
			$data['totalRecCount'] = $totalRecCount;
			$this->load->view('admin/kyc/kyc_list', $data);
		} else {
			$this->session->set_flashdata('success', $this->session->flashdata('success'));

			// $this->session->set_flashdata('success','KYC recommend for last record & Email send successfully !!');
			// $this->session->set_flashdata('error','Invalid record!!');

			redirect(base_url() . 'admin/kyc/Kyc/allocated_list');
		}
	}
	/* SAGAR WALZADE CODE END HERE */
	/// By VSU : Function to fetch list of members to initiate KYC
	//send mail by recommender
	public function send_mail()
	{
		//print_r($_POST);
		$regnumber = $_POST['member_no'];
		$email = $_POST['email'];
		$subject = $_POST['subject'];
		//$mailtext = preg_split('/\r\n|[\r\n]/', "hi hey there."); //$_POST['mailtext'];
		$mailtext = $_POST['mailtext'];
		
		$info_arr = array(
			//'to' => $email,
			'to' => 'iibfdevp@esds.co.in', //'pooja.mane@esds.co.in',//'to' => $userdata[0]['email'],
			'from' => 'noreply@iibf.org.in',
			'subject' => $subject,
			'message' => $mailtext
		);
		if ($this->Emailsending->mailsend_attch($info_arr, '')) {

			$data = array('success' => 'Acknowledgment mail is sent successfully');

			$log_message = serialize($info_arr);
			$titlt = "Mail Send to Member";
			$logs = array(
				'title' => $titlt,
				'description' => $log_message,
				'regnumber' => $regnumber
			);
			$this->master_model->insertRecord('kyc_mail_logs', $logs, true);
		}
		echo json_encode($data);
	}

	//to Show the recommended details	
	public function details($regnumber)
	{
		$data['result'] = array();
		$registrationtype = '';
		$data['reg_no'] = ' ';
		if ($regnumber) {
			$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,scannedsignaturephoto,idproofphoto,registrationtype';
			$members = $this->master_model->getRecords("member_registration a", array('regnumber' => $regnumber, 'isactive' => '1'), $select, "");

			
			if (count($members)) {
				$data['result'] = $members;
				$data['reg_no'] = $members[0]['regnumber'];

				$id = $data['reg_no'];
			}
		}
		$recommnended_members_data = $this->master_model->getRecords("member_kyc", array('regnumber' => $regnumber), '', array('kyc_id' => 'DESC'));
		$data['recomended_mem_data'] = $recommnended_members_data;
		$this->load->view('admin/kyc/recommended_view_details', $data);
	}
	public function get_allocation_type_cnt()
	{
		$new_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));
		if (count($new_allocated_member_list) > 0) {
			if ($new_allocated_member_list[0]['allotted_member_id'] == '') {
				redirect(base_url() . 'admin/kyc/Kyc/next_allocation_type');
			}
		}
		$kyc_start_date = $this->config->item('kyc_start_date');
		$allocated_member_list = $members = array();
		$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));
		//allocated_count
		if (count($allocated_member_list)) {

			if (count($allocated_member_list) > 0) {
				$data['count'] = $allocated_member_list[0]['allocated_count'];
				$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
			}
			foreach ($arraid as $row) {
				$this->db->where('kyc_edit', '0');
				$this->db->where('isactive', '1');
				$this->db->where('kyc_status', '0');
				$this->db->where('DATE(createdon)>=', $kyc_start_date && 'DATE(createdon)!=', '00-00-0000');
				$members = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
				$members_arr[] = $members;
			}
			$emptylistmsg = ' ';
			$data['emptylistmsg']	= $emptylistmsg;
			$data['result'] = call_user_func_array('array_merge', $members_arr);
			return count($data['result']);
			//$this->load->view('admin/kyc/alocated_member',$data);
		} else {
			return 0;
		}
	}
	public function get_edited_allocation_type_cnt()
	{
		$this->db->where('allotted_member_id=', '');
		//$this->db->or_where('original_allotted_member_id=','');

		$edit_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit'));
		//  echo $this->db->last_query();exit;
		//print_r($edit_allocated_member_list);exit;
		if (count($edit_allocated_member_list) > 0) {
			if ($edit_allocated_member_list[0]['allotted_member_id'] == '') {
				redirect(base_url() . 'admin/kyc/Kyc/next_edited_allocation_type');
			}
		}
		$edit_allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id' => '',));
		$emptylistmsg = '';
		$kyc_start_date = $this->config->item('kyc_start_date');
		$allocated_member_list = $members = array();
		$allocated_member_list = $this->master_model->getRecords("admin_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'Edit', 'allotted_member_id !=' => ''));
		//allocated_count
		if (count($allocated_member_list)) {
			if (count($allocated_member_list) > 0) {
				$data['count'] = $allocated_member_list[0]['allocated_count'];
				$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
			}
			foreach ($arraid as $row) {

				$kyc_data = $this->master_model->getRecords("member_kyc", array('regnumber' => $row));
				// check if record exist
				if (!empty($kyc_data)) {
					$this->db->where('member_kyc.kyc_state', '2');
					$this->db->join('member_kyc', 'member_kyc.regnumber=member_registration.regnumber', 'LEFT');
					$this->db->group_by('member_kyc.regnumber');
				}

				$this->db->where('kyc_edit', '1');
				$this->db->where('isactive', '1');
				$this->db->where('member_registration.kyc_status', '0');
				$members = $this->master_model->getRecords("member_registration", array('member_registration.regnumber' => $row, 'isactive' => '1'));
				//echo $this->db->last_query();exit;
				$members_arr[] = $members;
			}
			$data['result'] = call_user_func_array('array_merge', $members_arr);
			return count($data['result']);
			//$this->load->view('admin/kyc/edited_list',$data);
		} else {
			return 0;
		}
	}
	/* Member allocatoin by select date */
	public function benchmark_recommender()
	{
		$new_allocated_member_list = $this->master_model->getRecords("admin_benchmark_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));
		
		if (count($new_allocated_member_list) > 0) {
			if ($new_allocated_member_list[0]['allotted_member_id'] == '') {
				//redirect(base_url().'admin/kyc/Kyc/next_allocation_type');
				$this->load->view('admin/kyc/benchmark_allocation_type');
			}
		}
		//$kyc_start_date=$this->config->item('kyc_start_date');
		$allocated_member_list = $members = array();
		$allocated_member_list = $this->master_model->getRecords("admin_benchmark_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));
		
		//allocated_count
		if (count($allocated_member_list)) {
			if (count($allocated_member_list) > 0) {
				$data['count'] = $allocated_member_list[0]['allocated_count'];
				$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
			}
			foreach ($arraid as $row) {
				$this->db->where('benchmark_kyc_edit', '0');
				$this->db->where('isactive', '1');
				$this->db->where('benchmark_kyc_status', '0');
				$this->db->where('benchmark_disability', 'Y');
				//$this->db->where('DATE(createdon)>=',$kyc_start_date && 'DATE(createdon)!=','00-00-0000' );	
				$members = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
			
				$members_arr[] = $members;
			}
			$emptylistmsg = ' ';
			$data['emptylistmsg']	= $emptylistmsg;
			$data['result'] = call_user_func_array('array_merge', $members_arr);
			/* Start Code To Get Recent Allotted Member Total Count */
			$pagination_total_count = $this->master_model->getRecords("admin_benchmark_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
			if (!empty($pagination_total_count)) {
				foreach ($pagination_total_count[0] as $k => $value) {
					if ($k == "pagination_total_count") {
						$data['totalRecCount'] = $value;
					}
					if ($k == "original_allotted_member_id") {
						$data['original_allotted_member_id'] = $value;
					}
				}
			}
			$this->load->view('admin/kyc/benchmark_recommender', $data);
		} else {
			$this->load->view('admin/kyc/benchmark_allocation_type');
		}
	}
	/* to show the new and edit member list & allocate 200 member */
	/* to show the new and edit member list & allocate 200 member */
	public function benchmark_allocated_list()
	{
		$total_id = $recommendedmemberarr = array();
		//$kyc_start_date=$this->config->item('kyc_start_date');
		$data['count'] = 0;
		$tilte = $allocated_count = '';
		$description = $emptylistmsg = '';
		$allocates_arr = $members_arr = $result = $array = $allocated_member_list = $data_array = array();
		$data['result'] = array();
		$regstr = $searchText = $searchBy = '';
		$searchBy_regtype = '';
		$today = date('Y-m-d H:i:s');
		//$per_page = 100;
		$per_page = 200;
		$last = 199;
		$start = 0;
		$list_type = 'New';
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		$form_start_date = $form_end_date = '';
		if ($this->input->post('form_start_date') != '') {
			$form_start_date = $this->input->post('form_start_date');
		}
		if ($this->input->post('form_end_date') != '') {
			$form_end_date = $this->input->post('form_end_date');
		}


		$data['reg_no'] = ' ';
		if ($page != 0) {
			$start = $page - 1;
		}
		$allocates = array();
		if (isset($form_start_date) && isset($form_end_date)) {
			// Need to travese with all allocated numbers.
			$kyc_data = $this->master_model->getRecords("admin_benchmark_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'New'), 'original_allotted_member_id');

			$allocatedmemberarr = array();
			if (count($kyc_data) > 0) {
				foreach ($kyc_data as $row) {
					$allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
				}
			}
			// get all recommended members to todays date



			$member_kyc = $this->db->query("SELECT benchmark_member_kyc.regnumber,kyc_id
				FROM benchmark_member_kyc
				JOIN member_registration ON member_registration.regnumber = benchmark_member_kyc.regnumber
				WHERE 
				member_registration.benchmark_edit_date < recommended_date
				AND benchmark_kyc_status = '0'
				AND kyc_id IN (
				SELECT MAX(kyc_id)
				FROM benchmark_member_kyc
				GROUP BY regnumber
				) ");
			// _lq();
			// _pa($member_kyc);
			/*recommended_date < '".date('Y-m-d H:i:s')."' 
				AND  */
			//echo "<br>datewali>>".$this->db->last_query();//exit;
			if ($member_kyc->num_rows() > 0) {
				//   _pa($member_kyc->result_array());die;
				foreach ($member_kyc->result_array()  as $row) {
					$recommendedmemberarr[] = $row['regnumber'];
				}
			}

			/* to show list  for  3 days back dated data */
			$this->db->where('mr.regnumber !=', '');
			$this->db->where('mr.benchmark_kyc_edit', '0');
			$this->db->where('mr.benchmark_kyc_status', '0');
			$this->db->where('((mr.benchmark_disability = "Y" AND DATE(mr.createdon)>="' . $form_start_date . '" 
				AND DATE(mr.createdon)<="' . $form_end_date . '") OR 
				(mr.benchmark_edit_flg = "Y" AND mr.benchmark_disability != "N" 
				AND DATE(mr.benchmark_edit_date)>="' . $form_start_date . '" 
				AND DATE(mr.benchmark_edit_date)<="' . $form_end_date . '"))');

			// merge allocated member array with recommended members array
			if (count($allocatedmemberarr) > 0) {
				// get the column data in a single array
				$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
			}

			$data_array = array_merge($data_array, $recommendedmemberarr);

			if (count($data_array) > 0) {
				$this->db->where_not_in('mr.regnumber', array_map('stripslashes', $data_array));
			}

			$this->db->join('benchmark_member_kyc as bmk', 'mr.regnumber = bmk.regnumber', 'left');
			$this->db->where('bmk.regnumber IS NULL');

			$members = $this->master_model->getRecords("member_registration as mr", array('mr.isactive' => '1'), 'mr.*', array('mr.regid' => 'DESC'), $start, $per_page);
			// echo "string";
			// echo "<br>2>>".$this->db->last_query();
			// print_r($members);
			// _pa($members);die;

			$data['start'] = $start;

			//echo "<pre>";
			//print_r($members);
			//insert the allocated array list in table

			$today = date("Y-m-d H:i:s"); //

			$row_count = $this->master_model->getRecordCount("admin_benchmark_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New'));

			//echo "<br>fsdf>>".$this->db->last_query();
			//echo ">>".$row_count;

			if ($row_count == 0) { //echo "<br>1";
				$regstr = '';
				foreach ($members as $row) {
					$allocates_arr[] = $row['regnumber'];
				}
				$allocated_count = count($allocates_arr);
				if (count($allocates_arr) > 0) { //echo "<br>2";
					$regstr = implode(',', $allocates_arr);
				}
				if ($regstr != '') { //echo "<br>3";
					$insert_data = array(
						'user_type'			=> $this->session->userdata('role'),
						'user_id'				=> $this->session->userdata('kyc_id'),
						'allotted_member_id'	=> $regstr,
						'original_allotted_member_id'	=> $regstr,
						'allocated_count'     => $allocated_count,
						'allocated_list_count'     => '1',
						'date'	                => $today,
						'list_type'             => $list_type,
						'pagination_total_count ' => $allocated_count
					);
					// echo '<pre>';
					//print_r($insert_data);//exit;
					$this->master_model->insertRecord('admin_benchmark_kyc_users', $insert_data);
					//echo "<br>3>>".$this->db->last_query();
					//log activity 
					$tilte = 'Recommender New member list allocation';
					$description = 'Recommender has allocated ' . count($allocates_arr) . ' member';
					$user_id = $this->session->userdata('kyc_id');
					$this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);
				}
			}
		}
		$allocated_member_list = $this->master_model->getRecords("admin_benchmark_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));
		//allocated_count
		if (count($allocated_member_list) > 0) {
			redirect('admin/kyc/kyc/benchmark_recommender');
			exit;
			$data['count'] = $allocated_member_list[0]['allocated_count'];
			$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
			$form_start_date = $_POST['form_start_date'];
			$form_end_date = $_POST['form_end_date'];
			if (count($arraid) > 0) {
				if ($form_start_date != '' && $form_end_date != '') {
					if ($searchBy != '' && $form_start_date != '' && $form_end_date != '') { //echo "<br>IF 1";
						$this->db->where('regnumber', $searchBy);
						$this->db->where('benchmark_kyc_status', '0');
						$this->db->where('((benchmark_disability = "Y" AND DATE(createdon)>="' . $form_start_date . '" 
							AND DATE(createdon)<="' . $form_end_date . '") OR 
							(benchmark_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(benchmark_edit_date)>="' . $form_start_date . '" 
							AND DATE(benchmark_edit_date)<="' . $form_end_date . '"))');
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
					} else if ($searchBy != '') { //echo "<br>eles if 2";
						$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
						$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
						$this->db->where('regnumber', $searchBy);
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
					} else if ($form_start_date != '' && $form_end_date != '') { //echo "<br>eles if 3";
						$this->db->where('((benchmark_disability = "Y" AND DATE(createdon)>="' . $form_start_date . '" 
							AND DATE(createdon)<="' . $form_end_date . '") OR 
							(benchmark_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(benchmark_edit_date)>="' . $form_start_date . '" 
							AND DATE(benchmark_edit_date)<="' . $form_end_date . '"))');
						$this->db->where('benchmark_kyc_status', '0');
						$members = $this->master_model->getRecords("member_registration", array('isactive' => '1'));
					}
					if (count($members) > 0) {
						foreach ($members as $row) {
							$members_arr[][] = $row;
						}
					}
				} else {	//default allocation list for 100 member
					foreach ($arraid as $row) {
						/* to show list  for  3 days back dated data */
						//$three_days_back = date('Y-m-d', strtotime("- 3 days"));
						$this->db->where('benchmark_kyc_edit', '0');
						$this->db->where('isactive', '1');
						$this->db->where('benchmark_kyc_status', '0');
						$this->db->where('benchmark_disability', 'Y');
						// 		$this->db->where('((benchmark_disability = "Y" AND DATE(createdon)>="' . $form_start_date . '" 
						// 			AND DATE(createdon)<="' . $form_end_date . '") OR 
						// 			(benchmark_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(benchmark_edit_date)>="' . $form_start_date . '" 
						// 			AND DATE(benchmark_edit_date)<="' . $form_end_date . '"))');
						$members = $this->master_model->getRecords("member_registration", array('regnumber' => $row));
						//echo "<br>4>>".$this->db->last_query();
						$members_arr[] = $members;
					}
				}
			}
			$data['result'] = call_user_func_array('array_merge', $members_arr);
		}
		$total_row = 200;
		$url = base_url() . "admin/kyc/Kyc/benchmark_allocated_list/";
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li><li>Search</li></ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;
		if (($start + $per_page) > $total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start + $per_page;
		if ($total_row)
			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
		else
			$data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';
		$data['index'] = $start + 1;
		/* Start Code To Get Recent Allotted Member Total Count */
		$pagination_total_count = $this->master_model->getRecords("admin_benchmark_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
		if (!empty($pagination_total_count)) {
			foreach ($pagination_total_count[0] as $k => $value) {
				if ($k == "pagination_total_count") {
					$data['totalRecCount'] = $value;
				}
				if ($k == "original_allotted_member_id") {
					$data['original_allotted_member_id'] = $value;
				}
			}
		}
		/* Close Code To Get Recent Allotted Member Total Count */
		$emptylistmsg = ' No records available...!!<br />
			<a href=' . base_url() . 'admin/kyc/Kyc/benchmark_recommender/>Back</a>';
		$data['emptylistmsg']	= $emptylistmsg;
		$data['total_count'] = $allocated_count;
		$this->db->distinct('registrationtype');
		$data['mem_type'] = $this->master_model->getRecords('member_registration', array('isactive' => '1'), 'registrationtype', array('registrationtype' => 'ASC'));
		//$this->load->view('admin/kyc/alocated_member',$data);
		$this->load->view('admin/kyc/benchmark_recommender', $data);
	}
	/* Benchmark Member Recommend */
	public function benchmark_member($regnumber = NULL, $srno = NULL, $totalRecCount = NULL)
	{
		if ($regnumber) {
			$next_id = $sucess = $memregnumber = $description = '';
			$oldfilepath = $file_path = $photo_file = $orthopedically_file = $visually_file = $cerebral_file = '';
			$data['result'] = $new_arrayid = $noarray = array();
			$registrationtype = '';
			$data['reg_no'] = ' ';
			$employer = array();
			$field_count = 0;
			$data = $update_data = $old_user_data = array();
			$name = array();
			$state = '1';
			$today = date("Y-m-d H:i:s");
			$date = date("Y-m-d H:i:s");
			if (isset($_POST['btnSubmit'])) {
				$select = 'regnumber,registrationtype,email,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img';
				$data = $this->master_model->getRecords('member_registration', array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);

				//echo "<pre>";
				//print_r($data);

				if (isset($_POST['cbox'])) {
					$name = $this->input->post('cbox');
				}
				$regnumber = $data[0]['regnumber'];
				$check_arr = array();
				if (count($name) > 0) {
					foreach ($name as $cbox) {
						$check_arr[] = $cbox;
					}
				}
				//print_r($check_arr);
				$msg = 'Edit your benchmark kyc details as :-';
				if (count($check_arr) > 0) {
					$folder_name = date('d-m-Y');
					$new_img_name = date('H:i:s');
					if (in_array('visually_checkbox', $check_arr)) {
						$visually_checkbox = '1';
					} else {
						if ($data[0]['vis_imp_cert_img'] != '') {
							$visually_checkbox = '0';
							$field_count++;
							$update_data[] = 'Visually';
							$msg .= 'Visually,';
							if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name)) {
								mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name, 0777, true);
							}
							if (file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name)) {
								if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability')) {
									mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability', 0777, true);
								}
							}
							$original_file = base_url() . "uploads/disability/v_" . $regnumber . ".jpg";
							$newfile = getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability/v_' . $regnumber . '_' . $new_img_name . '.jpg';
							copy($original_file, $newfile);
						} else {
							$visually_checkbox = '3';
						}
					}
					if (in_array('orthopedically_checkbox', $check_arr)) {
						$orthopedically_checkbox = '1';
					} else {
						if ($data[0]['orth_han_cert_img'] != '') {
							$orthopedically_checkbox = '0';
							$field_count++;
							$update_data[] = 'Orthopedically';
							$msg .= 'Orthopedically,';
							if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name)) {
								mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name, 0777, true);
							}
							if (file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name)) {
								if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability')) {
									mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability', 0777, true);
								}
							}
							$original_file = base_url() . "uploads/disability/o_" . $regnumber . ".jpg";
							$newfile = getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability/o_' . $regnumber . '_' . $new_img_name . '.jpg';
							copy($original_file, $newfile);
						} else {
							$orthopedically_checkbox = '3';
						}
					}
					if (in_array('cerebral_checkbox', $check_arr)) {
						$cerebral_checkbox = '1';
					} else {
						if ($data[0]['cer_palsy_cert_img'] != '') {
							$cerebral_checkbox = '0';
							$field_count++;
							$update_data[] = 'Cerebral';
							$msg .= 'Cerebral';
							if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name)) {
								mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name, 0777, true);
							}
							if (file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name)) {
								if (!file_exists(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability')) {
									mkdir(getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability', 0777, true);
								}
							}
							$original_file = base_url() . "uploads/disability/c_" . $regnumber . ".jpg";
							$newfile = getcwd() . '/uploads/benchmark_kyc_img/' . $folder_name . '/disability/c_' . $regnumber . '_' . $new_img_name . '.jpg';
							copy($original_file, $newfile);
						} else {
							$cerebral_checkbox = '3';
						}
					}
				} else {
					if ($data[0]['vis_imp_cert_img'] != '') {
						$visually_checkbox = '0';
						$msg .= 'Visually';
						$field_count++;
						$update_data[] = 'Visually';
					} else {
						$visually_checkbox = '3';
					}
					if ($data[0]['orth_han_cert_img'] != '') {
						$orthopedically_checkbox = '0';
						$msg .= 'Orthopedically';
						$field_count++;
						$update_data[] = 'Orthopedically';
					} else {
						$orthopedically_checkbox = '3';
					}
					if ($data[0]['cer_palsy_cert_img'] != '') {
						$cerebral_checkbox = '0';
						$msg .= 'Cerebral';
						$field_count++;
						$update_data[] = 'Cerebral';
					} else {
						$cerebral_checkbox = '3';
					}
				}
				//exit;
				$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img';
				$old_user_data = $this->master_model->getRecords('member_registration', array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);
				$email = $data[0]['email'];
				$insert_data = array(
					'regnumber' => $data[0]['regnumber'],
					'mem_visually' => $visually_checkbox,
					'mem_orthopedically' => $orthopedically_checkbox,
					'mem_cerebral' => $cerebral_checkbox,
					'field_count' => $field_count,
					'old_data' => serialize($old_user_data),
					'kyc_status' => '0',
					'kyc_state' => $state,
					'recommended_by' => $this->session->userdata('kyc_id'),
					'user_type' => $this->session->userdata('role'),
					'recommended_date' => $today,
					'record_source' => 'New'
				);
				//echo "<pre>";
				//print_r($insert_data);
				//exit;

				$last_insterid = $this->master_model->insertRecord('benchmark_member_kyc', $insert_data, true);
				// log activity
				// get recommended fields data from member registration -
				$select = 'email,namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img';
				$old_data = $this->master_model->getRecords("member_registration", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select);
				$log_desc['old_data'] = $old_data;
				$log_desc['inserted_data'] = $insert_data;
				$description = serialize($log_desc);
				$userdata = $this->master_model->getRecords("member_registration", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				));

				if (($visually_checkbox == '1' || $visually_checkbox == '3') && ($orthopedically_checkbox == '1' ||  $orthopedically_checkbox == '3') && ($cerebral_checkbox == '1' || $cerebral_checkbox == '3')) {
					$this->session->set_flashdata('success', 'Benchmark KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');
				} else {
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$userpass = $aes->decrypt($userdata[0]['usrpassword']);
					$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
					$msg = implode(',', $update_data);
					$emailerstr = $this->master_model->getRecords('emailer', array(
						'emailer_name' => 'benchmark_recommendation_email'
					));
					$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
					$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
					$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
					if ($data[0]['registrationtype'] == 'O' || $data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A') {
						$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);
					} else {
						$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);
					}
					// echo $final_str ;exit;
					// $final_str= str_replace("#password#", "".$decpass."",  $newstring);
					$info_arr = array(
						//'to'=> "kyciibf@gmail.com",
						'to' => 'iibfdevp@esds.co.in', //'to' => $userdata[0]['email'],
						'from' => $emailerstr[0]['from'],
						'subject' => $emailerstr[0]['subject'],
						'message' => $final_str
					);
					if ($this->Emailsending->mailsend($info_arr)) {
						// $sucess='KYC recommend for the candidate & Email send successfully !!';
						$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');
						// log activity
						// get recommended fields data from member registration 
						$select = 'namesub,firstname,middlename,lastname,dateofbirth,associatedinstitute,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img';
						$old_data = $this->master_model->getRecords("member_registration", array(
							'regnumber' => $regnumber,
							'isactive' => '1'
						), $select);
						$log_desc['old_data'] = $old_data;
						$log_desc['inserted_data'] = $insert_data;
						$description = serialize($log_desc);
						$this->KYC_Log_model->benchmark_create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
						// email log
						$this->KYC_Log_model->benchmark_email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));
						// make recommended fields empty  -
						if (in_array('Visually', $update_data)) {
							$updatedata['vis_imp_cert_img'] = '';
							$updatedata['visually_impaired'] = '';
							$oldfilepath = "uploads/disability/v_" . $regnumber . ".jpg";
							$noarray = explode('/v_', $oldfilepath);
							if (isset($noarray[1])) {
								$file_path = implode('/', explode('/', $oldfilepath, -1));
								$photo_file = 'k_v_' . $noarray[1];
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
									$this->KYC_Log_model->benchmark_create_log('Recommended Benchmark Visually Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								} else {
									$this->KYC_Log_model->benchmark_create_log('fail to delete Benchmark Visually', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								}
							} else {
								$this->KYC_Log_model->benchmark_create_log('member Benchmark Visually not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
							}
						}
						if (in_array('Orthopedically', $update_data)) {
							$updatedata['orth_han_cert_img'] = '';
							$updatedata['orthopedically_handicapped'] = '';
							$oldfilepath = "uploads/disability/o_" . $regnumber . ".jpg";
							$noarray = explode('/o_', $oldfilepath);
							$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
							if (isset($noarray[1])) {
								$file_path = implode('/', explode('/', $oldfilepath, -1));
								$photo_file = 'k_o_' . $noarray[1];
								if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
									$this->KYC_Log_model->benchmark_create_log('Recommended Benchmark Orthopedically rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								} else {
									$this->KYC_Log_model->benchmark_create_log('Fail to delete Benchmark Orthopedically', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								}
							} else {
								$this->KYC_Log_model->benchmark_create_log('Member Benchmark Orthopedically not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
							}
						}
						if (in_array('Cerebral', $update_data)) {
							$updatedata['cer_palsy_cert_img'] = '';
							$updatedata['cerebral_palsy'] = '';
							$oldfilepath = "uploads/disability/c_" . $regnumber . ".jpg";
							$noarray = explode('/c_', $oldfilepath);
							$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
							if (isset($noarray[1])) {
								$file_path = implode('/', explode('/', $oldfilepath, -1));
								$photo_file = 'k_c_' . $noarray[1];
								if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
									$this->KYC_Log_model->benchmark_create_log('Recommended benchmark cerebral Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								} else {
									$this->KYC_Log_model->benchmark_create_log('Fail to delete benchmark cerebral ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								}
							} else {
								$this->KYC_Log_model->benchmark_create_log('Member benchmark cerebral not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
							}
						}
					}
				}
				if (!empty($updatedata)) {
					$this->db->where('isactive', '1');
					$this->master_model->updateRecord('member_registration', $updatedata, array(
						'regnumber' => $regnumber
					));
				}
				$member = $this->master_model->getRecords("admin_benchmark_kyc_users", array(
					'DATE(date)' => date('Y-m-d'),
					'user_id' => $this->session->userdata('kyc_id'),
					'list_type' => 'New'
				));
				$arrayid = explode(',', $member[0]['allotted_member_id']);
				$index = array_search($regnumber, $arrayid, true);
				$currentid = $index;
				$nextid = $currentid + 1;
				if (array_key_exists($nextid, $arrayid)) {
					$next_id = $arrayid[$nextid];
				} else {
					$next_id = $arrayid[0];
				}
				unset($arrayid[$index]);
				if (count($arrayid) > 0) {
					foreach ($arrayid as $row) {
						$new_arrayid[] = $row;
					}
				}
				if (count($new_arrayid) > 0) {
					$regstr = implode(',', $new_arrayid);
				} else {
					$regstr = '';
					$next_id = '';
				}
				$update_data = array(
					'allotted_member_id' => $regstr
				);
				$this->db->where('DATE(date)', date('Y-m-d '));
				$this->db->where('list_type', 'New');
				$this->master_model->updateRecord('admin_benchmark_kyc_users', $update_data, array(
					'user_id' => $this->session->userdata('kyc_id')
				));
				/* Start Code To Showing Count On Member List*/
				if ($next_id == '') {
					$next_id = 0;
				}
				$totalRecCount = $this->get_allocation_type_cnt();
				if ($srno > $totalRecCount) {
					$srno = 1;
				} else {
					$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
					$arr = array_slice($original_allotted_Arr, -$totalRecCount);
					$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
					$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
					$memberNo = $next_id;
					$updated_list_index = array_search($memberNo, $reversedArr_list);
					$srno = $updated_list_index;
				}
				// $totalRecCount=$this->get_allocation_type_cnt();
				if ($srno > $totalRecCount) {
					// $srno=$totalRecCount;
					$srno = 1;
				} else {
					$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
					$arr = array_slice($original_allotted_Arr, -$totalRecCount);
					$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
					$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
					$memberNo = $next_id;
					$updated_list_index = array_search($memberNo, $reversedArr_list);
					$srno = $updated_list_index;
				}
				/* Close Code To Showing Count On Member List*/
				redirect(base_url() . 'admin/kyc/Kyc/benchmark_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);
				/* Close Code To Showing Count On Member List*/
			}
			if ($regnumber) {
				$select = 'regnumber,namesub,firstname,middlename,lastname,DATE_FORMAT(dateofbirth,"%d-%m-%Y") dateofbirth,registrationtype,associatedinstitute,scannedphoto,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,scannedsignaturephoto,idproofphoto,registrationtype,email';
				$members = $this->master_model->getRecords("member_registration a", array(
					'regnumber' => $regnumber,
					'isactive' => '1'
				), $select, "", '0', '1');
				if (count($members) > 0) {
					$memregnumber = $members[0]['regnumber'];
				}
			}
			$recommnended_members_data = $this->master_model->getRecords("benchmark_member_kyc", array(
				'regnumber' => $regnumber
			), '', array(
				'kyc_id' => 'DESC'
			));
			$data = array(
				'result' => $members,
				'reg_no' => $memregnumber,
				'recomended_mem_data' => $recommnended_members_data,
				'next_id' => $next_id,
				'success' => $sucess
			);
			$data['totalRecCount'] = $totalRecCount;
			$this->load->view('admin/kyc/benchmark_kyc_list', $data);
		} else {
			$this->session->set_flashdata('success', $this->session->flashdata('success'));
			//redirect(base_url() . 'admin/kyc/Kyc/benchmark_member');
			$this->load->view('admin/kyc/benchmark_allocation_type');
		}
	}

	/*SCRIBE KYC FUNCTIONS 12/9/2022 */
	/*MEMBER ALLOCATION BY SELECT DATE*/
	public function scribe_recommender()
	{

		$new_allocated_member_list = $this->master_model->getRecords("admin_scribe_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id' => ''));
		//echo $this->db->last_query();die;
		//print_r($new_allocated_member_list);
		if (count($new_allocated_member_list) > 0) {
			// die('IF1');
			if ($new_allocated_member_list[0]['allotted_member_id'] == '') {
				$this->load->view('admin/kyc/scribe_allocation_type');
			}
		}

		$allocated_member_list = $members = array();
		$allocated_member_list = $this->master_model->getRecords("admin_scribe_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));
		/*echo $this->db->last_query();//die;
		echO'</br>';
		print_r($allocated_member_list);DIE;
		*/
		//allocated_count
		if (count($allocated_member_list)) {
			if (count($allocated_member_list) > 0) {
				$data['count'] = $allocated_member_list[0]['allocated_count'];
				$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
			}
			//echo "string";die;
			//get Result
			foreach ($arraid as $row) {
				$this->db->where('scribe_kyc_edit', '0');
				$this->db->where('remark', '1');
				$this->db->where('scribe_kyc_status', '0');
				$this->db->group_by('regnumber');
				//$this->db->where('be_disability', 'Y');
				//$this->db->where('DATE(created_on)>=',$kyc_start_date && 'DATE(createdon)!=','00-00-0000' );	
				$members = $this->master_model->getRecords("scribe_registration", array('regnumber' => $row));
				// echo ">1<br>>".$this->db->last_query();DIE;
				$members_arr[] = $members;
			}
			$emptylistmsg = ' ';
			$data['emptylistmsg']	= $emptylistmsg;
			$data['result'] = call_user_func_array('array_merge', $members_arr);
			/*echo'<pre>';
			print_r($data['result']);DIE;*/

			/* Start Code To Get Recent Allotted Member Total Count */
			$pagination_total_count = $this->master_model->getRecords("admin_scribe_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");

			//echo $this->db->last_query();DIE;


			if (!empty($pagination_total_count)) {
				foreach ($pagination_total_count[0] as $k => $value) {
					if ($k == "pagination_total_count") {
						$data['totalRecCount'] = $value;
					}
					if ($k == "original_allotted_member_id") {
						$data['original_allotted_member_id'] = $value;
					}
				}
			}
			//echo "strinup";die;
			$this->load->view('admin/kyc/scribe_recommender', $data);
		} else {
			//echo "strinbelow";die;
			$this->load->view('admin/kyc/scribe_allocation_type');
		}
	}

	/* to show the new and edit member list & allocate 200 member */
	public function scribe_allocated_list()
	{

		$total_id = $recommendedmemberarr = array();
		//$kyc_start_date=$this->config->item('kyc_start_date');
		$data['count'] = 0;
		$tilte = $allocated_count = '';
		$description = $emptylistmsg = '';
		$allocates_arr = $members_arr = $result = $array = $allocated_member_list = $data_array = array();
		$data['result'] = array();
		$regstr = $searchText = $searchBy = '';
		$searchBy_regtype = '';
		$today = date('Y-m-d H:i:s');
		//$per_page = 100;
		$per_page = 200;
		$last = 199;
		$start = 0;
		$list_type = 'New';
		$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
		$form_start_date = $form_end_date = '';
		if ($this->input->post('form_start_date') != '') {
			$form_start_date = $this->input->post('form_start_date');
		}
		if ($this->input->post('form_end_date') != '') {
			$form_end_date = $this->input->post('form_end_date');
		}

		//echo "<pre>";
		//print_r($_POST);exit;
		$data['reg_no'] = ' ';
		if ($page != 0) {
			$start = $page - 1;
		}
		$allocates = array();
		if (isset($form_start_date) && isset($form_end_date)) {
			// Need to travese with all allocated numbers.
			$kyc_data = $this->master_model->getRecords("admin_scribe_kyc_users", array('DATE(date)' => date('Y-m-d'), 'list_type' => 'New'), 'original_allotted_member_id');
			//echo "<br>1>>".$this->db->last_query();//die;
			$allocatedmemberarr = array();
			if (count($kyc_data) > 0) {
				foreach ($kyc_data as $row) {
					$allocatedmemberarr[] = explode(',', $row['original_allotted_member_id']);
				}
			}
			// get all recommended members to todays date

			//echo "<br>1>>".$this->db->last_query();//die;

			$member_kyc = $this->db->query("SELECT scribe_member_kyc.regnumber,kyc_id
				FROM scribe_member_kyc
				JOIN scribe_registration ON scribe_registration.regnumber = scribe_member_kyc.regnumber
				WHERE 
				scribe_registration.scribe_edit_date < scribe_member_kyc.recommended_date
				AND scribe_registration.scribe_kyc_status = '0'
				AND scribe_member_kyc.kyc_id IN (
				SELECT MAX(kyc_id)
				FROM scribe_member_kyc
				GROUP BY regnumber
				) ");
			//print_r($member_kyc);//die;
			//echo $this->db->last_query();exit;
			// _lq();_pa($member_kyc);die;
			/*recommended_date < '".date('Y-m-d H:i:s')."' 
				AND  */
			//echo "<br>datewali>>".$this->db->last_query();exit;
			if ($member_kyc->num_rows() > 0) {
				foreach ($member_kyc->result_array()  as $row) {
					$recommendedmemberarr[] = $row['regnumber'];
				}
			}

			/* to show list  for  3 days back dated data */
			$this->db->where('scribe_kyc_edit', '0');
			$this->db->where('scribe_kyc_status', '0');
			$this->db->where('((benchmark_disability = "Y" AND DATE(created_on)>="' . $form_start_date . '" 
				AND DATE(created_on)<="' . $form_end_date . '") OR 
				(scribe_edit_flg = "Y" AND benchmark_disability != "N" 
				AND DATE(scribe_edit_date)>="' . $form_start_date . '" 
				AND DATE(scribe_edit_date)<="' . $form_end_date . '"))');

			// merge allocated member array with recommended members array
			if (count($allocatedmemberarr) > 0) {
				// get the column data in a single array
				$data_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($allocatedmemberarr)), 0);
			}

			$data_array = array_merge($data_array, $recommendedmemberarr);
			//print_r($data_array);die;


			if (count($data_array) > 0) {
				$this->db->where_not_in('regnumber', array_map('stripslashes', $data_array));
			}
			$this->db->distinct('regnumber');
			$this->db->group_by('regnumber');
			$members = $this->master_model->getRecords("scribe_registration", array('remark' => '1'), '', array('regnumber' => 'DESC'), $start, $per_page);
			/*print_r(count($members));//die;
			echo $this->db->last_query();exit;*/
			//echo "<br>2>>".$this->db->last_query();exit;

			$data['start'] = $start;

			//echo "<pre>";
			//print_r($members);

			//insert the allocated array list in table

			$today = date("Y-m-d H:i:s"); //

			$row_count = $this->master_model->getRecordCount("admin_scribe_kyc_users", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New'));

			//echo "<br>fsdf>>".$this->db->last_query();
			//echo ">>".$row_count;die;

			if ($row_count == 0) { //echo "<br>1";
				$regstr = '';
				foreach ($members as $row) {
					$allocates_arr[] = $row['regnumber'];
				}
				$allocated_count = count($allocates_arr);

				if (count($allocates_arr) > 0) { //echo "<br>2";
					$regstr = implode(',', $allocates_arr);
				}
				if ($regstr != '') { //echo "<br>3";
					$insert_data = array(
						'user_type'			=> $this->session->userdata('role'),
						'user_id'				=> $this->session->userdata('kyc_id'),
						'allotted_member_id'	=> $regstr,
						'original_allotted_member_id'	=> $regstr,
						'allocated_count'     => $allocated_count,
						'allocated_list_count'     => '1',
						'date'	                => $today,
						'list_type'             => $list_type,
						'pagination_total_count ' => $allocated_count
					);
					/* echo '<pre>';
					print_r($insert_data);//exit;*/
					$this->master_model->insertRecord('admin_scribe_kyc_users', $insert_data);
					//echo "<br>3>>".$this->db->last_query();die;
					//log activity 
					$tilte = 'Recommender New member list allocation';
					$description = 'Recommender has allocated ' . count($allocates_arr) . ' member';
					$user_id = $this->session->userdata('kyc_id');
					$this->KYC_Log_model->create_log($tilte, $user_id, '', '', $description);
				}
			}
		}
		$allocated_member_list = $this->master_model->getRecords("admin_scribe_kyc_users ", array('DATE(date)' => date('Y-m-d'), 'user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''));
		//_lq();_pa($allocated_member_list);die;

		//allocated_count
		if (count($allocated_member_list) > 0) {
			// DIE('IF2');
			$data['count'] = $allocated_member_list[0]['allocated_count'];
			$arraid = explode(',', $allocated_member_list[0]['allotted_member_id']);
			$form_start_date = $_POST['form_start_date'];
			$form_end_date = $_POST['form_end_date'];
			if (count($arraid) > 0) {
				if ($form_start_date != '' && $form_end_date != '') {
					if ($searchBy != '' && $form_start_date != '' && $form_end_date != '') { //echo "<br>IF 1";
						$this->db->where('regnumber', $searchBy);

						$this->db->where('scribe_kyc_status', '0');
						$this->db->where('((benchmark_disability = "Y" AND DATE(created_on)>="' . $form_start_date . '" 
							AND DATE(created_on)<="' . $form_end_date . '") OR 
							(scribe_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(scribe_edit_date)>="' . $form_start_date . '" 
							AND DATE(scribe_edit_date)<="' . $form_end_date . '"))');
						$this->db->group_by('regnumber');
						$members = $this->master_model->getRecords("scribe_registration", array('remark' => '1'));
					} else if ($searchBy != '') { //echo "<br>eles if 2";
						$arrstr = explode(',', $allocated_member_list[0]['allotted_member_id']);
						$this->db->where_in('regnumber', array_map('stripslashes', $arrstr));
						$this->db->where('regnumber', $searchBy);
						$this->db->group_by('regnumber');
						$members = $this->master_model->getRecords("scribe_registration", array('remark' => '1'));
					} else if ($form_start_date != '' && $form_end_date != '') { //echo "<br>eles if 3";
						$this->db->where('((benchmark_disability = "Y" AND DATE(created_on)>="' . $form_start_date . '" 
							AND DATE(created_on)<="' . $form_end_date . '") OR 
							(scribe_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(scribe_edit_date)>="' . $form_start_date . '" 
							AND DATE(scribe_edit_date)<="' . $form_end_date . '"))');
						$this->db->where('scribe_kyc_status', '0');
						$this->db->group_by('regnumber');
						$members = $this->master_model->getRecords("scribe_registration", array('remark' => '1'));
					}
					if (count($members) > 0) {
						foreach ($members as $row) {
							$members_arr[][] = $row;
						}
					}
				} else {	//default allocation list for 100 member
					foreach ($arraid as $row) {
						/* to show list  for  3 days back dated data */
						//$three_days_back = date('Y-m-d', strtotime("- 3 days"));
						$this->db->where('scribe_kyc_edit', '0');
						$this->db->where('remark', '1');
						$this->db->where('scribe_kyc_status', '0');
						$this->db->where('((benchmark_disability = "Y" AND DATE(created_on)>="' . $form_start_date . '" 
							AND DATE(created_on)<="' . $form_end_date . '") OR 
							(scribe_edit_flg = "Y" AND benchmark_disability != "N" AND DATE(scribe_edit_date)>="' . $form_start_date . '" 
							AND DATE(scribe_edit_date)<="' . $form_end_date . '"))');
						$this->db->group_by('regnumber');
						$members = $this->master_model->getRecords("scribe_registration", array('regnumber' => $row));
						//echo "<br>4>>".$this->db->last_query();
						$members_arr[] = $members;
					}
				}
			}
			//echo "<br>4>>".$this->db->last_query();DIE;
			$data['result'] = call_user_func_array('array_merge', $members_arr);
		}
		$total_row = 200;
		$url = base_url() . "admin/kyc/Kyc/scribe_allocated_list/";
		$config = pagination_init($url, $total_row, $per_page, 2);
		$this->pagination->initialize($config);
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li><li>Search</li></ol>';
		$str_links = $this->pagination->create_links();
		//var_dump($str_links);
		$data["links"] = $str_links;
		if (($start + $per_page) > $total_row)
			$end_of_total = $total_row;
		else
			$end_of_total = $start + $per_page;
		if ($total_row)
			$data['info'] = 'Showing ' . ($start + 1) . ' to ' . $end_of_total . ' of ' . $total_row . ' entries';
		else
			$data['info'] = 'Showing 0 to ' . $end_of_total . ' of ' . $total_row . ' entries';
		$data['index'] = $start + 1;
		/* Start Code To Get Recent Allotted Member Total Count */
		$pagination_total_count = $this->master_model->getRecords("admin_scribe_kyc_users", array('user_id' => $this->session->userdata('kyc_id'), 'list_type' => 'New', 'allotted_member_id !=' => ''), "pagination_total_count,original_allotted_member_id");
		if (!empty($pagination_total_count)) {
			foreach ($pagination_total_count[0] as $k => $value) {
				if ($k == "pagination_total_count") {
					$data['totalRecCount'] = $value;
				}
				if ($k == "original_allotted_member_id") {
					$data['original_allotted_member_id'] = $value;
				}
			}
		}
		/* Close Code To Get Recent Allotted Member Total Count */
		$emptylistmsg = ' No records available...!!<br />
			<a href=' . base_url() . 'admin/kyc/Kyc/scribe_recommender/>Back</a>';
		$data['emptylistmsg']	= $emptylistmsg;
		$data['total_count'] = $allocated_count;
		//$this->db->distinct('registrationtype');
		$this->db->group_by('regnumber');
		$data['mem_type'] = $this->master_model->getRecords('scribe_registration', array('remark' => '1'));
		//$this->load->view('admin/kyc/alocated_member',$data);
		$this->load->view('admin/kyc/scribe_recommender', $data);
	}

	/* SCRIBE Member Recommend */
	public function scribe_member($regnumber = NULL, $srno = NULL, $totalRecCount = NULL)
	{

		if ($regnumber) {

			$next_id = $sucess = $memregnumber = $description = '';
			$oldfilepath = $file_path = $photo_file = $orthopedically_file = $visually_file = $cerebral_file = '';
			$data['result'] = $new_arrayid = $noarray = array();
			$registrationtype = '';
			$data['reg_no'] = ' ';
			$employer = array();
			$field_count = 0;
			$data = $update_data = $old_user_data = array();
			$name = array();
			$state = '1';
			$today = date("Y-m-d H:i:s");
			$date = date("Y-m-d H:i:s");

			if (isset($_POST['btnSubmit'])) {
				$select = 'regnumber, email, idproofphoto, declaration_img, vis_imp_cert_img, orth_han_cert_img, cer_palsy_cert_img';
				$this->db->group_by('regnumber');
				$data = $this->master_model->getRecords('scribe_registration', array(
					'regnumber' => $regnumber,
					'remark' => '1',

				), $select);

				/*echo "<pre>";
				print_r($data);die;*/
				//print_r($_POST['cbox']);
				//echo "<br>";
				if (isset($_POST['cbox'])) {
					$name = $this->input->post('cbox');
				}
				//print_r($_POST);//die;
				$regnumber = $data[0]['regnumber'];
				$scribe_uid = $_POST['scribe_uid'];
				$idproofphoto = $data[0]['idproofphoto'];
				$declaration_img = $data[0]['declaration_img'];
				$vis_imp_cert_img = $data[0]['vis_imp_cert_img'];
				$orth_han_cert_img = $data[0]['orth_han_cert_img'];
				$cer_palsy_cert_img = $data[0]['cer_palsy_cert_img'];

				//print_r($scribe_uid);die;
				$check_arr = array();
				if (count($name) > 0) {
					foreach ($name as $cbox) {
						$check_arr[] = $cbox;
					}
				}

				$msg = 'Edit your benchmark kyc details as :-';
				if (count($check_arr) > 0) {
					$folder_name = date('d-m-Y');
					$new_img_name = date('H:i:s');

					if (in_array('idproof_checkbox', $check_arr)) {
						$idproof_checkbox = '1';
					} else {
						//print_r($data[0]['idproofphoto']);DIE;
						if ($data[0]['idproofphoto'] != '') {
							$idproof_checkbox = '0';
							$field_count++;
							$update_data[] = 'Idproof';
							$msg .= 'Idproof,';
							if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name)) {
								mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name, 0777, true);
							}

							if (file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name)) {
								if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/idproof')) {
									mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/idproof', 0777, true);
								}
							}


							$original_file = base_url() . "uploads/scribe/idproof/" . $idproofphoto;
							/*print_r($original_file);die;*/
							$newfile = getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/idproof/idproof_' . $regnumber . '_' . $new_img_name . '.jpg';

							// $newfile = getcwd() . '/uploads/kyc_img/' . $folder_name . '/photo/p_' . $regnumber . '_' . $new_img_name . '.jpg';
							$arrContextOptions = array(
								"ssl" => array(
									"verify_peer" => false,
									"verify_peer_name" => false,
								),
							);
							copy($original_file, $newfile, stream_context_create($arrContextOptions));
							// die;
						} else {
							$idproof_checkbox = '3';
						}
					}
					if (in_array('declaration_checkbox', $check_arr)) {
						$declaration_checkbox = '1';
					} else {
						if ($data[0]['declaration_img'] != '') {
							$declaration_checkbox = '0';
							$field_count++;
							$update_data[] = 'Declaration';
							$msg .= 'Declaration,';
							if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name)) {
								mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name, 0777, true);
							}

							if (file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name)) {
								if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/declaration')) {
									mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/declaration', 0777, true);
								}
							}
							$original_file = base_url() . "uploads/scribe/declaration/" . $declaration_img;
							/*print_r($original_file);die;*/
							$newfile = getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/declaration/declaration_' . $regnumber . '_' . $new_img_name . '.jpg';

							$arrContextOptions = array(
								"ssl" => array(
									"verify_peer" => false,
									"verify_peer_name" => false,
								),
							);
							copy($original_file, $newfile, stream_context_create($arrContextOptions));
						} else {
							$declaration_checkbox = '3';
						}
					}
					if (in_array('visually_checkbox', $check_arr)) {
						$visually_checkbox = '1';
					} else {
						if ($data[0]['vis_imp_cert_img'] != '') {
							$visually_checkbox = '0';
							$field_count++;
							$update_data[] = 'Visually';
							$msg .= 'Visually,';
							if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name)) {
								mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name, 0777, true);
							}

							if (file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name)) {
								if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability')) {
									mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability', 0777, true);
								}
							}
							$original_file = base_url() . "uploads/scribe/disability/" . $vis_imp_cert_img;
							/*print_r($original_file);die;*/
							$newfile = getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability/vis_imp_cert_' . $regnumber . '_' . $new_img_name . '.jpg';
							$arrContextOptions = array(
								"ssl" => array(
									"verify_peer" => false,
									"verify_peer_name" => false,
								),
							);
							copy($original_file, $newfile, stream_context_create($arrContextOptions));
						} else {
							$visually_checkbox = '3';
						}
					}
					if (in_array('orthopedically_checkbox', $check_arr)) {
						$orthopedically_checkbox = '1';
					} else {
						if ($data[0]['orth_han_cert_img'] != '') {
							$orthopedically_checkbox = '0';
							$field_count++;
							$update_data[] = 'Orthopedically';
							$msg .= 'Orthopedically,';
							if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name)) {
								mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name, 0777, true);
							}
							if (file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name)) {
								if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability')) {
									mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability', 0777, true);
								}
							}
							$original_file = base_url() . "uploads/scribe/disability/" . $orth_han_cert_img;
							$newfile = getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability/orth_han_cert_' . $regnumber . '_' . $new_img_name . '.jpg';

							$arrContextOptions = array(
								"ssl" => array(
									"verify_peer" => false,
									"verify_peer_name" => false,
								),
							);
							copy($original_file, $newfile, stream_context_create($arrContextOptions));
							//copy($original_file, $newfile);
						} else {
							$orthopedically_checkbox = '3';
						}
					}
					if (in_array('cerebral_checkbox', $check_arr)) {
						$cerebral_checkbox = '1';
					} else {

						if ($data[0]['cer_palsy_cert_img'] != '') {
							$cerebral_checkbox = '0';
							$field_count++;
							$update_data[] = 'Cerebral';
							$msg .= 'Cerebral';
							if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name)) {
								mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name, 0777, true);
							}
							if (file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name)) {
								if (!file_exists(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability')) {
									mkdir(getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability', 0777, true);
								}
							}
							$original_file = base_url() . "uploads/scribe/disability/" . $cer_palsy_cert_img;
							$newfile = getcwd() . '/uploads/scribe_kyc_img/' . $folder_name . '/disability/cer_palsy_cert_' . $regnumber . '_' . $new_img_name . '.jpg';

							//echo $original_file;echo "<br>";
							//echo $newfile;die;
							$arrContextOptions = array(
								"ssl" => array(
									"verify_peer" => false,
									"verify_peer_name" => false,
								),
							);
							copy($original_file, $newfile, stream_context_create($arrContextOptions));
						} else {
							$cerebral_checkbox = '3';
						}
					}
				} else {
					if ($data[0]['idproofphoto'] != '') {
						$idproof_checkbox = '0';
						$msg .= 'Idproof';
						$field_count++;
						$update_data[] = 'Idproof';
					} else {
						$idproof_checkbox = '3';
					}
					if ($data[0]['declaration_img'] != '') {
						$declaration_checkbox = '0';
						$msg .= 'Declaration';
						$field_count++;
						$update_data[] = 'Declaration';
					} else {
						$declaration_checkbox = '3';
					}
					if ($data[0]['vis_imp_cert_img'] != '') {
						$visually_checkbox = '0';
						$msg .= 'Visually';
						$field_count++;
						$update_data[] = 'Visually';
					} else {
						$visually_checkbox = '3';
					}
					if ($data[0]['orth_han_cert_img'] != '') {
						$orthopedically_checkbox = '0';
						$msg .= 'Orthopedically';
						$field_count++;
						$update_data[] = 'Orthopedically';
					} else {
						$orthopedically_checkbox = '3';
					}
					if ($data[0]['cer_palsy_cert_img'] != '') {
						$cerebral_checkbox = '0';
						$msg .= 'Cerebral';
						$field_count++;
						$update_data[] = 'Cerebral';
					} else {
						$cerebral_checkbox = '3';
					}
				}
				//exit;
				$this->db->distinct('regnumber');
				$this->db->group_by('regnumber');
				$select = 'namesub,firstname,middlename,lastname,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,email,idproofphoto,declaration_img';
				$this->db->group_by('regnumber');
				$old_user_data = $this->master_model->getRecords('scribe_registration', array(
					//'scribe_uid' => $scribe_uid,
					'regnumber' => $regnumber,
					'remark' => '1'
				), $select);

				//echo $this->db->last_query();die;
				$email = $data[0]['email'];

				//echo "string"; print_r($scribe_uid);die;
				$insert_data = array(
					'regnumber' => $data[0]['regnumber'],
					//'scribe_uid' => $scribe_uid,
					'mem_idproof' => $idproof_checkbox,
					'mem_declaration' => $declaration_checkbox,
					'mem_visually' => $visually_checkbox,
					'mem_orthopedically' => $orthopedically_checkbox,
					'mem_cerebral' => $cerebral_checkbox,
					'field_count' => $field_count,
					'old_data' => serialize($old_user_data),
					'kyc_status' => '0',
					'kyc_state' => $state,
					'recommended_by' => $this->session->userdata('kyc_id'),
					'user_type' => $this->session->userdata('role'),
					'recommended_date' => $today,
					'record_source' => 'New'
				);
				/*echo "<pre>";
				print_r($insert_data);
				exit;*/

				$last_insterid = $this->master_model->insertRecord('scribe_member_kyc', $insert_data, true);

				// log activity
				// get recommended fields data from member registration -
				$select = 'email,namesub,firstname,middlename,lastname,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,idproofphoto,declaration_img,email,';
				$this->db->group_by('regnumber');
				$old_data = $this->master_model->getRecords("scribe_registration", array(
					'regnumber' => $regnumber,
					//'scribe_uid' => $scribe_uid,
					'remark' => '1'
				), $select);

				$log_desc['old_data'] = $old_data;
				$log_desc['inserted_data'] = $insert_data;
				$description = serialize($log_desc);
				$this->db->group_by('regnumber');
				$userdata = $this->master_model->getRecords("scribe_registration", array(
					'regnumber' => $regnumber,
					//'scribe_uid' => $scribe_uid,
					'remark' => '1'
				));

				if (($declaration_checkbox == '1' || $declaration_checkbox == '3')
					&& ($idproof_checkbox == '1' || $idproof_checkbox == '3')
					&& ($visually_checkbox == '1' || $visually_checkbox == '3')
					&& ($orthopedically_checkbox == '1' ||  $orthopedically_checkbox == '3')
					&& ($cerebral_checkbox == '1' || $cerebral_checkbox == '3')
				) {
					//echo "INNN";
					$this->session->set_flashdata('success', 'Scribe KYC recommended for ' . $data[0]['regnumber'] . '(Previous record) sent to approver !!');
				} else {
					//echo "ELSEOUT";
					$memberdata = $this->master_model->getRecords("member_registration", array(
						'regnumber' => $regnumber,
						'isactive' => '1'
					));
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$userpass = '';
					if (count($memberdata) > 0) {
						$userpass = $aes->decrypt($memberdata[0]['usrpassword']);
					}

					//echo $userpass;die;
					$username = $userdata[0]['namesub'] . ' ' . $userdata[0]['firstname'] . ' ' . $userdata[0]['middlename'] . ' ' . $userdata[0]['lastname'];
					$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
					$msg = implode(',', $update_data);
					$emailerstr = $this->master_model->getRecords('emailer', array(
						'emailer_name' => 'scribe_recommendation_email'
					));
					$newstring1 = str_replace("#REGNUMBER#", "" . $regnumber . "", $emailerstr[0]['emailer_text']);
					$newstring2 = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
					$newstring3 = str_replace("#PASSWORD#", "" . $userpass . "", $newstring2);
					$newstring4 = str_replace("#MSG#", "" . $msg . "", $newstring3);
					/*if ($data[0]['registrationtype'] == 'O' || $data[0]['registrationtype'] == 'F' || $data[0]['registrationtype'] == 'A') {*/
					//$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . '" style="color:#F00">Click here to Login </a>', $newstring4);
					/*} else {*/
					$final_str = str_replace("#CLICKHERE#", '<a href="' . base_url() . 'nonmem/" style="color:#F00">Click here to Login </a>', $newstring4);
					//}
					//
					// $final_str= str_replace("#password#", "".$decpass."",  $newstring);
					//echo $final_str ;exit;
					$info_arr = array(
						//'to'=> "kyciibf@gmail.com",
						//'to' => 'iibfdevp@esds.co.in',
						'to' => 'iibfdevp@esds.co.in',
						'from' => $emailerstr[0]['from'],
						'subject' => $emailerstr[0]['subject'],
						'message' => $final_str
					);
					if ($this->Emailsending->mailsend($info_arr)) {
						// $sucess='KYC recommend for the candidate & Email send successfully !!';
						$this->session->set_flashdata('success', '' . $data[0]['regnumber'] . '  (previous record), Email for Rectification sent successfully !!');
						// log activity
						// get recommended fields data from member registration 
						$select = 'namesub,firstname,middlename,lastname,vis_imp_cert_img,orth_han_cert_img,cer_palsy_cert_img,idproofphoto,declaration_img,email';
						$this->db->group_by('regnumber');
						$old_data = $this->master_model->getRecords("scribe_registration", array(
							'regnumber' => $regnumber,
							//'scribe_uid' => $scribe_uid,
							'remark' => '1'
						), $select);
						$log_desc['old_data'] = $old_data;
						$log_desc['inserted_data'] = $insert_data;
						$description = serialize($log_desc);
						$this->KYC_Log_model->scribe_create_log('Member recommend', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
						// email log
						$this->KYC_Log_model->scribe_email_log($last_insterid, $this->session->userdata('kyc_id'), '0', '', $regnumber, serialize($info_arr), $today, $this->session->userdata('role'));

						// make recommended fields empty  -
						if (in_array('Idproof', $update_data)) {
							$updatedata['idproofphoto'] = '';
							//$updatedata['visually_impaired'] = '';
							$oldfilepath = "uploads/declaration/" . $updatedata['idproofphoto'];
							$noarray = explode('/', $oldfilepath);

							if (isset($noarray[1])) {
								$file_path = implode('/', explode('/', $oldfilepath, -1));
								$photo_file = 'k_' . $noarray[1];
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
									$this->KYC_Log_model->scribe_create_log('Recommended Scribe Idproof Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								} else {
									$this->KYC_Log_model->scribe_create_log('fail to delete Scribe Idproof', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								}
							} else {
								$this->KYC_Log_model->scribe_create_log('member Scribe Idproof not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
							}
						}
						if (in_array('Declaration', $update_data)) {
							$updatedata['declaration_img'] = '';
							//$updatedata['visually_impaired'] = '';
							$oldfilepath = "uploads/declaration/" . $updatedata['declaration_img'];
							$noarray = explode('/', $oldfilepath);

							if (isset($noarray[1])) {
								$file_path = implode('/', explode('/', $oldfilepath, -1));
								$photo_file = 'k_' . $noarray[1];
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
									$this->KYC_Log_model->scribe_create_log('Recommended Scribe Declaration Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								} else {
									$this->KYC_Log_model->scribe_create_log('fail to delete Scribe Declaration', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								}
							} else {
								$this->KYC_Log_model->scribe_create_log('member Scribe Declaration not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
							}
						}
						if (in_array('Visually', $update_data)) {
							$updatedata['vis_imp_cert_img'] = '';
							$updatedata['visually_impaired'] = '';
							$oldfilepath = "uploads/scribe/disability/vis_imp_cert" . $regnumber . ".jpg";
							$noarray = explode('/vis_imp_cert', $oldfilepath);

							if (isset($noarray[1])) {
								$file_path = implode('/', explode('/', $oldfilepath, -1));
								$photo_file = 'k_vis_imp_cert' . $noarray[1];
								$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
								if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
									$this->KYC_Log_model->benchmark_create_log('Recommended Benchmark Visually Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								} else {
									$this->KYC_Log_model->benchmark_create_log('fail to delete Benchmark Visually', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								}
							} else {
								$this->KYC_Log_model->benchmark_create_log('member Benchmark Visually not found', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
							}
						}
						if (in_array('Orthopedically', $update_data)) {
							$updatedata['orth_han_cert_img'] = '';
							$updatedata['orthopedically_handicapped'] = '';
							$oldfilepath = "uploads/disability/orth_han_cert_img" . $regnumber . ".jpg";
							$noarray = explode('/orth_han_cert_img', $oldfilepath);
							$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
							if (isset($noarray[1])) {
								$file_path = implode('/', explode('/', $oldfilepath, -1));
								$photo_file = 'k_orth_han_cert_img' . $noarray[1];
								if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
									$this->KYC_Log_model->benchmark_create_log('Recommended Benchmark Orthopedically rename', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								} else {
									$this->KYC_Log_model->benchmark_create_log('Fail to delete Benchmark Orthopedically', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								}
							} else {
								$this->KYC_Log_model->benchmark_create_log('Member Benchmark Orthopedically not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
							}
						}
						if (in_array('Cerebral', $update_data)) {
							$updatedata['cer_palsy_cert_img'] = '';
							$updatedata['cerebral_palsy'] = '';
							$oldfilepath = "uploads/disability/cer_palsy_cert_img" . $regnumber . ".jpg";
							$noarray = explode('/cer_palsy_cert_img', $oldfilepath);
							$description = 'oldpath:' . $oldfilepath . ' || ' . 'file path: ' . $file_path . '/' . $photo_file . '';
							if (isset($noarray[1])) {
								$file_path = implode('/', explode('/', $oldfilepath, -1));
								$photo_file = 'k_cer_palsy_cert_img' . $noarray[1];
								if (@rename($oldfilepath, $file_path . '/' . $photo_file)) {
									$this->KYC_Log_model->benchmark_create_log('Recommended benchmark cerebral Rename ', $this->session->userdata('kyc_id'), $last_insterid, $regnumber, $description);
								} else {
									$this->KYC_Log_model->benchmark_create_log('Fail to delete benchmark cerebral ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
								}
							} else {
								$this->KYC_Log_model->benchmark_create_log('Member benchmark cerebral not found ', $this->session->userdata('kyc_id'), 0, $regnumber, $description);
							}
						}
					}
				}
				if (!empty($updatedata)) {
					$this->db->where('remark', '1');
					$this->master_model->updateRecord('scribe_registration', $updatedata, array(
						//'scribe_uid' => $scribe_uid,
						'regnumber' => $regnumber
					));
				}

				$member = $this->master_model->getRecords("admin_scribe_kyc_users", array(
					'DATE(date)' => date('Y-m-d'),
					'user_id' => $this->session->userdata('kyc_id'),
					'list_type' => 'New'
				));
				//echo $this->db->last_query();DIE;
				$arrayid = explode(',', $member[0]['allotted_member_id']);
				//print_r($arrayid);echo" ARRAY<br>";//die;
				$index = array_search($regnumber, $arrayid, true);
				//echo $index; echo" index<br>";
				$currentid = $index;
				//echo $currentid;echo" indecurrentx<br>";
				$nextid = $currentid + 1;
				//echo $nextid; echo" nextid<br>";
				if (array_key_exists($nextid, $arrayid)) {
					$next_id = $arrayid[$nextid];
				} else {
					$next_id = $arrayid[0];
				}
				//echo $nextid; echo" ifelsenextid<br>";
				//print_r($arrayid);echo "up<br>";
				unset($arrayid[$index]);
				if (count($arrayid) > 0) {
					foreach ($arrayid as $row) {
						$new_arrayid[] = $row;
					}
				}
				//print_r($arrayid);echo "sec<br>";
				if (count($new_arrayid) > 0) {
					$regstr = implode(',', $new_arrayid);
				} else {
					$regstr = '';
					$next_id = '';
				}
				//print_r($arrayid);die;
				$update_data = array(
					'allotted_member_id' => $regstr
				);
				//print_r($update_data);die;
				$this->db->where('DATE(date)', date('Y-m-d '));
				$this->db->where('list_type', 'New');
				$this->master_model->updateRecord('admin_scribe_kyc_users', $update_data, array(
					'user_id' => $this->session->userdata('kyc_id')
				));

				/* Start Code To Showing Count On Member List*/
				if ($next_id == '') {
					$next_id = 0;
				}
				$totalRecCount = $this->get_allocation_type_cnt();
				if ($srno > $totalRecCount) {
					$srno = 1;
				} else {
					$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
					$arr = array_slice($original_allotted_Arr, -$totalRecCount);
					$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
					$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
					$memberNo = $next_id;
					$updated_list_index = array_search($memberNo, $reversedArr_list);
					$srno = $updated_list_index;
				}
				// $totalRecCount=$this->get_allocation_type_cnt();
				if ($srno > $totalRecCount) {
					// $srno=$totalRecCount;
					$srno = 1;
				} else {
					$original_allotted_Arr = explode(',', $member[0]['original_allotted_member_id']);
					$arr = array_slice($original_allotted_Arr, -$totalRecCount);
					$Updated_original_allotted_Arr = array_combine(range(1, count($arr)), array_values($arr));
					$reversedArr_list = array_reverse($Updated_original_allotted_Arr, true);
					$memberNo = $next_id;
					$updated_list_index = array_search($memberNo, $reversedArr_list);
					$srno = $updated_list_index;
				}
				/* Close Code To Showing Count On Member List*/
				redirect(base_url() . 'admin/kyc/Kyc/scribe_member/' . $next_id . "/" . $srno . "/" . $totalRecCount);

				/* Close Code To Showing Count On Member List*/
			}
			if ($regnumber) {
				$select = 'regnumber,scribe_uid, firstname, lastname, name_of_scribe, mobile_scribe, benchmark_disability, vis_imp_cert_img, orth_han_cert_img, cer_palsy_cert_img, photoid_no, idproofphoto, declaration_img,email';

				$members = $this->master_model->getRecords("scribe_registration a", array(
					'regnumber' => $regnumber,
					'remark' => '1'
				), $select, "", '0', '1');
				if (count($members) > 0) {
					$memregnumber = $members[0]['regnumber'];
				}
				//
			}
			//echo $this->db->last_query();die;
			$recommnended_members_data = $this->master_model->getRecords(" scribe_member_kyc", array(
				'regnumber' => $regnumber
			), '', array(
				'kyc_id' => 'DESC'
			));

			$data = array(
				'result' => $members,
				'reg_no' => $memregnumber,
				'recomended_mem_data' => $recommnended_members_data,
				'next_id' => $next_id,
				'success' => $sucess
			);
			$data['totalRecCount'] = $totalRecCount;
			//echo $this->db->last_query();die;
			$this->load->view('admin/kyc/scribe_kyc_list', $data);
		} else {
			$this->session->set_flashdata('success', $this->session->flashdata('success'));
			redirect(base_url() . 'admin/kyc/kyc/scribe_recommender');
			//$this->load->view('admin/kyc/scribe_allocation_type');
		}
	}
	/*SCRIBE KYC FUNCTIONS end 12/9/2022 */
	public function send_RPE_mail_Pooja()
	{

		$member_candidate_qry = $this->db->query("SELECT exm_cd, exm_prd, mem_type, g_1, mem_mem_no, mam_nam_1, email, mobile, center_code, center_name, sub_cd, sub_dsc, venueid, venue_name, CONCAT(`venueadd1`,', ',`venueadd2`,', ',`venueadd3`,', ',`venueadd4`,', ',`venueadd5`) AS VENUE_ADDRESS, venpin, seat_identification, pwd, exam_date, time, mode, m_1, scribe_flag, vendor_code, admitcard_image FROM `admit_card_details`, `member_registration` 
                                                WHERE `exm_cd` IN (600,210) 
                                                AND `remark` = 1 
                                                AND member_registration.regnumber = admit_card_details.mem_mem_no
                                                AND isactive = '1' 
                                                AND admitcard_image != ''
                                                LIMIT 1");
		$member_candidate_data = $member_candidate_qry->result_array();


		foreach ($member_candidate_data as $key => $member) {
			//$email = $member['email'];
			$email = array('iibfdevp@esds.co.in');
			$exam_name = $member['sub_dsc'];

			$admitcard_image = 'https://iibf.esdsconnect.com//uploads/WELCOME_KIT.pdf';

			$final_str = 'Hello Sir/Madam <br/><br/>';

			$final_str .= '<p align="center">INDIAN INSTITUTE  OF BANKING &amp; FINANCE<br>
			  (AN ISO 9001:2015  Certified )</p>
			<p align="center">Your  application has been saved successfully. <br>
			  Your Membership No  &amp; Login ID  is:  #application_num# and Your Password is :  #password#<br>
			  <br>
			  Please Print/Note down your Membership No and Password as the same will be  required for Applying for Examinations, Accessing Edit Profile, Downloading  Admit Letter, Results etc. <br>
			  <br>
			  You may also print or save membership registration page for further reference.  Pl check your details mentioned there for its correctness. <br>
			  <br>
			<p>Members of the Institute / Candidates  enrolled for the examinations of the Institute can register their Queries in  the Application available on the web site, link   given on the top of the home page  as under :</p>
			<p align="center"><a href="https://iibf.esdsconnect.com/CmsComplaint" target="_blank">Members/Candidates Support Services (Help)</a> <br>
			  <a href="http://www.iibf.org.in/membersupportservice.asp" target="_blank" style="color:#F00">Register Your Queries Here</a></p>';


			$info_arr = array(
				'to' => $email,
				'from' => 'noreply@iibf.org.in',
				'subject' => 'Download the admit letter',
				'message' => $final_str
			);
			echo '<pre>' . $key;
			if ($this->Emailsending->mailsend($info_arr)) {
				echo 'mail send';
			} else {
				echo 'mail not send';
			}
		}
	}
}
