<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
defined('BASEPATH') OR exit('No direct script access allowed');
class Examination extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
	}
	public function index() {
		
		// echo $this->get_clint_ip1(); exit;

		// if($this->get_client_ip1()!='115.124.115.75') {
		// 	echo $this->get_client_ip1(). 'Under maintenance';exit;
		// }
		$memtype = '';
		if(isset( $_GET['type'] )) {
			$memtype = trim($_GET['type']);
			$memtype = base64_decode( $memtype );	
		} else {
			redirect('Examination/?type=Tw==');
		}
		$exam_types = $this->master_model->getRecords('exam_type');
		$examtypearr = array();
		$today_date=date('Y-m-d');
		if( count( $exam_types ) > 0 ) {
			foreach( $exam_types as $exam_type ) 
			{
				/*XXX Added By Sagar On 30-11-2021 For allowing member to register for JAIIB after registration closed */

				//$examcodes  = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),'65',$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),'8','11','19'); // priyanka d- 23-march-23 >> commented this  to list caiib exam

				//$examcodes  = array('65','8','11','19');
				$examcodes  = array('65','8','19');
				$this->db->where_not_in('exam_master.exam_code', $examcodes);
				//$this->db->where_not_in('exam_master.exam_code', $this->config->item('examCodeJaiib'));
									
				$this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
				$this->db->join('medium_master','medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.	exam_period');
				$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
				$this->db->where('medium_delete','0');
				$this->db->where('medium_master.exam_code !=' , 1016);
				$this->db->where('exam_type',$exam_type['id']);	
				$this->db->where('exam_activation_master.exam_activation_delete','0');	

				if($memtype=='O') 
				{
					$this->db->where('elg_mem_o','Y');	
				} 
				else if( $memtype=='NM' ) 
				{
					
					$where=" (`elg_mem_nm` = 'Y' OR `elg_mem_db` = 'Y')";
					$this->db->where($where);	
					//$this->db->where('elg_mem_nm','Y');	
					//added for DB&f exam 
					//$this->db->or_where('elg_mem_db','Y');
				} 
				$this->db->group_by('medium_master.exam_code');
				$exam_list = $this->master_model->getRecords('exam_master');
				
				//echo $this->db->last_query().'<br>';// exit;
				//echo'<pre>';print_r($exam_list);exit;
				if( count( $exam_list ) > 0 ) {
					$examtypearr[] = $exam_type['id'].'*'.$exam_type['type'];
				}
			}
		}
		
		//echo $this->get_client_ip1();exit;
			//echo $this->db->last_query(); die;
		$data = array('middle_content' => 'examinations', 'memtype' => $memtype, 'examtypes' => $examtypearr, 'examlist' => $exam_list );
		$this->load->view('common_view_fullwidth',$data);
	}
	function get_client_ip1() {
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
}
?>