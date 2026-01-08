<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Examdashboard extends CI_Controller 
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
			
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
		}
		
		public function index()
		{
			if(!$this->session->userdata('username')) { redirect(site_url('webmanager/login/logout')); }			
			$count = $paid_count = $free_count = $refund_count = $exam_code = $exam_period = $from_date = $to_date = $elearning_flag = '';
			
			$this->db->select('em.id,em.description,act.created_on,em.exam_code,act.exam_code,act.exam_from_date,act.exam_to_date,act.exam_period');
			$this->db->join('exam_master em','em.exam_code= act.exam_code','INNER');
	    $data['exam_data'] = $exam_data = $this->master_model->getRecords('exam_activation_master act', '', '', array('em.description'=>'ASC'));
			
			if(isset($_POST) && count($_POST) > 0)
			{
				$this->form_validation->set_rules('exam_code[]', 'Exam Name', 'trim|required', array('required'=>"Please select the %s"));
				$this->form_validation->set_rules('exam_period[]', 'Exam Period', 'trim|required', array('required'=>"Please select the %s"));
				if ($this->form_validation->run() == TRUE)
				{
					$exam_code = $_POST['exam_code'];
					$exam_period = $_POST['exam_period'];
					if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
					if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
					$count = 0;
					
					//START : FOR PAID MEMBERS
					$this->db->where('me.pay_status','1');
					$this->db->where_in('me.exam_code',$exam_code); 
					$this->db->where_in('me.exam_period',$exam_period); 
					$this->db->where('me.app_update','0'); 
					$this->db->where('me.institute_id','0'); 
					$this->db->where('me.free_paid_flg','P'); 
					
					if($from_date != "") { $this->db->where('DATE(me.created_on) >=', $from_date); }
					if($to_date != "") { $this->db->where('DATE(me.created_on) <=', $to_date); }
					
					if(isset($_POST['elearning_check']) && $_POST['elearning_check'] == 1)
					{
						$this->db->where('me.elearning_flag','Y');
						$elearning_flag = $_POST['elearning_check'];
					}
					
					$this->db->group_by('me.id');
					$this->db->join('member_registration mr', 'mr.regnumber = me.regnumber', 'LEFT', FALSE);
					$total_paid_data = $this->master_model->getRecords('member_exam me', array(), 'mr.namesub, mr.firstname, mr.middlename, mr.lastname, mr.email, mr.mobile, mr.state, me.id, me.regnumber, me.exam_code, me.exam_mode, me.exam_medium, me.exam_period, me.exam_center_code, me.exam_fee, me.created_on, me.modified_on, me.elearning_flag, me.sub_el_count'); //echo $this->db->last_query(); exit;					
					$paid_count = count($total_paid_data);
					//$paid_count = $this->master_model->getRecordCount('member_exam'); //echo $this->db->last_query();
					//END : FOR PAID MEMBERS
					
					//START : FOR FREE MEMBERS
					$this->db->where('me.pay_status','1');
					$this->db->where_in('me.exam_code',$exam_code); 
					$this->db->where_in('me.exam_period',$exam_period); 
					$this->db->where('me.app_update','0'); 
					$this->db->where('me.institute_id','0'); 
					$this->db->where('me.free_paid_flg','F'); 
					
					if($from_date != "") { $this->db->where('DATE(me.created_on) >=', $from_date); }
					if($to_date != "") { $this->db->where('DATE(me.created_on) <=', $to_date); }
					
					if(isset($_POST['elearning_check']) && $_POST['elearning_check'] == 1)
					{
						$this->db->where('me.elearning_flag','Y');
						$elearning_flag = $_POST['elearning_check'];
					}
					
					$this->db->group_by('me.id');
					$this->db->join('member_registration mr', 'mr.regnumber = me.regnumber', 'LEFT', FALSE);
					$total_free_data = $this->master_model->getRecords('member_exam me', array(), 'mr.namesub, mr.firstname, mr.middlename, mr.lastname, mr.email, mr.mobile, mr.state, me.id, me.regnumber, me.exam_code, me.exam_mode, me.exam_medium, me.exam_period, me.exam_center_code, me.exam_fee, me.created_on, me.modified_on, me.elearning_flag, me.sub_el_count'); //echo $this->db->last_query(); exit;
					$free_count = count($total_free_data);
					//$free_count = $this->master_model->getRecordCount('member_exam');
					//START : FOR FREE MEMBERS
					
					
					//START : FOR REFUND MEMBER
					$this->db->join('payment_transaction pt', 'pt.ref_id = me.id', 'INNER');
					$this->db->where('me.pay_status','0');
					$this->db->where_in('me.exam_code',$exam_code); 
					$this->db->where_in('me.exam_period',$exam_period); 
					$this->db->where('me.app_update','0'); 
					$this->db->where('me.institute_id','0');
					$this->db->where('pt.status','3');
					$this->db->where('pt.pay_type','2');
					$this->db->having('MKID > ', 0);
					
					if($from_date != "") { $this->db->where('me.created_on >=', $from_date." 00:00:00"); }
					if($to_date != "") { $this->db->where('me.created_on <=', $to_date." 23:59:59"); }
					
					if(isset($_POST['elearning_check']) && $_POST['elearning_check'] == 1)
					{
						$this->db->where('me.elearning_flag','Y');
						$elearning_flag = $_POST['elearning_check'];
					}					
					
					//$refund_count = $this->master_model->getRecordCount('member_exam me'); 
					$this->db->group_by('me.id');
					$this->db->join('member_registration mr', 'mr.regnumber = me.regnumber', 'LEFT', FALSE);
					$total_refund_data = $this->master_model->getRecords('member_exam me', '', 'mr.namesub, mr.firstname, mr.middlename, mr.lastname, mr.email, mr.mobile, mr.state, me.id, me.regnumber, me.exam_code, me.exam_mode, me.exam_medium, me.exam_period, me.exam_center_code, me.exam_fee, me.created_on, me.modified_on, me.elearning_flag, me.sub_el_count, (SELECT id FROM maker_checker WHERE transaction_no = pt.transaction_no AND req_status = 5 LIMIT 1) AS MKID', array('exam_period'=>'DESC'));
					$refund_count = count($total_refund_data);
					$data['refund_count_qry'] = $this->db->last_query();				
					//END : FOR REFUND MEMBER
					
					if(isset($_POST['download_csv']) && $_POST['download_csv'] != "")
					{
						$final_arr = array();						
						
						if(count($total_paid_data) > 0)
						{
							foreach($total_paid_data as $total_paid_res)
							{
								$temp_arr = array();
								$temp_arr['namesub'] = $total_paid_res['namesub'];
								$temp_arr['firstname'] = $total_paid_res['firstname'];
								$temp_arr['middlename'] = $total_paid_res['middlename'];
								$temp_arr['lastname'] = $total_paid_res['lastname'];
								$temp_arr['email'] = $total_paid_res['email'];
								$temp_arr['mobile'] = $total_paid_res['mobile'];
								$temp_arr['state'] = $total_paid_res['state'];
								$temp_arr['regnumber'] = $total_paid_res['regnumber'];
								$temp_arr['exam_code'] = $total_paid_res['exam_code'];
								$temp_arr['exam_period'] = $total_paid_res['exam_period'];
								$temp_arr['exam_center_code'] = $total_paid_res['exam_center_code'];
								$temp_arr['exam_fee'] = $total_paid_res['exam_fee'];
								$temp_arr['elearning_flag'] = $total_paid_res['elearning_flag'];
								$temp_arr['sub_el_count'] = $total_paid_res['sub_el_count'];
								
								if($total_paid_res['created_on'] != "0000-00-00 00:00:00") { $temp_arr['created_on'] = $total_paid_res['created_on']; }
								else { $temp_arr['created_on'] = $total_paid_res['modified_on']; }
								
								$temp_arr['type'] = 'Paid';																
								$final_arr[] = $temp_arr;
							}
						}
						
						if(count($total_free_data) > 0)
						{
							foreach($total_free_data as $total_free_res)
							{
								$temp_arr = array();
								$temp_arr['namesub'] = $total_free_res['namesub'];
								$temp_arr['firstname'] = $total_free_res['firstname'];
								$temp_arr['middlename'] = $total_free_res['middlename'];
								$temp_arr['lastname'] = $total_free_res['lastname'];
								$temp_arr['email'] = $total_free_res['email'];
								$temp_arr['mobile'] = $total_free_res['mobile'];
								$temp_arr['state'] = $total_free_res['state'];
								$temp_arr['regnumber'] = $total_free_res['regnumber'];
								$temp_arr['exam_code'] = $total_free_res['exam_code'];
								$temp_arr['exam_period'] = $total_free_res['exam_period'];
								$temp_arr['exam_center_code'] = $total_free_res['exam_center_code'];
								$temp_arr['exam_fee'] = $total_free_res['exam_fee'];
								$temp_arr['elearning_flag'] = $total_free_res['elearning_flag'];
								$temp_arr['sub_el_count'] = $total_free_res['sub_el_count'];
								
								if($total_free_res['created_on'] != "0000-00-00 00:00:00") { $temp_arr['created_on'] = $total_free_res['created_on']; }
								else { $temp_arr['created_on'] = $total_free_res['modified_on']; }
								
								$temp_arr['type'] = 'Free';
								$final_arr[] = $temp_arr;
							}
						}
						
						if(count($total_refund_data) > 0)
						{
							foreach($total_refund_data as $total_refund_res)
							{
								$temp_arr = array();
								$temp_arr['namesub'] = $total_refund_res['namesub'];
								$temp_arr['firstname'] = $total_refund_res['firstname'];
								$temp_arr['middlename'] = $total_refund_res['middlename'];
								$temp_arr['lastname'] = $total_refund_res['lastname'];
								$temp_arr['email'] = $total_refund_res['email'];
								$temp_arr['mobile'] = $total_refund_res['mobile'];
								$temp_arr['state'] = $total_refund_res['state'];
								$temp_arr['regnumber'] = $total_refund_res['regnumber'];
								$temp_arr['exam_code'] = $total_refund_res['exam_code'];
								$temp_arr['exam_period'] = $total_refund_res['exam_period'];
								$temp_arr['exam_center_code'] = $total_refund_res['exam_center_code'];
								$temp_arr['exam_fee'] = $total_refund_res['exam_fee'];
								$temp_arr['elearning_flag'] = $total_refund_res['elearning_flag'];
								$temp_arr['sub_el_count'] = $total_refund_res['sub_el_count'];
								
								if($total_refund_res['created_on'] != "0000-00-00 00:00:00") { $temp_arr['created_on'] = $total_refund_res['created_on']; }
								else { $temp_arr['created_on'] = $total_refund_res['modified_on']; }
								
								$temp_arr['type'] = 'Refund';
								$final_arr[] = $temp_arr;
							}
						}
						
						array_multisort(array_column($final_arr, 'created_on'), SORT_ASC, $final_arr);

						//echo '<pre>'; print_r($final_arr); echo '</pre>'; exit;
						
						$csv = "Sr. No, Name Sub, First Name, Middle Name, Last Name, Email, Mobile, State, Regnumber, Exam Code, Exam Period, Exam Center Code, Exam Fee, Elearning Flag, Date \n";
						
						if(count($final_arr) > 0)
						{	
							$sr_no = 1;
							foreach($final_arr as $row)
							{	
								 $csv.= $sr_no.",".$row['namesub'].",".$row['firstname'].",".$row['middlename'].",".$row['lastname'].",".$row['email'].",".$row['mobile'].",".$row['state'].",".$row['regnumber'].",".$row['exam_code'].",".$row['exam_period'].",".$row['exam_center_code'].",".$row['exam_fee'].",".$row['elearning_flag'].",".date("Y-m-d h:ia", strtotime($row['created_on']))."\n"; 
								 $sr_no++;
							}
						}
						
						$filename = "member_exam_data_".date("YmdHis").".csv";
						header('Content-type: application/csv');
						header('Content-Disposition: attachment; filename='.$filename);
						$csv_handler = fopen('php://output', 'w');
						fwrite ($csv_handler,$csv);
						fclose ($csv_handler);
						exit;
					}
				}
			}
			
			$data['exam_period_data'] = $this->master_model->getRecords('dashboard_exam_period', '', 'exam_period', array('exam_period'=>'DESC'));
			$data['count'] = $count;
			$data['paid_count'] = $paid_count;
			$data['free_count'] = $free_count;
			$data['refund_count'] = $refund_count;
			$data['exam_code'] = $exam_code;
			$data['exam_period'] = $exam_period;
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['elearning_flag'] = $elearning_flag;
			$this->load->view('webmanager/examdashboard',$data);
		}
		
		function add_new_exam_period_ajax()
		{
			if(isset($_POST) && count($_POST) > 0)
			{
				$new_exam_period = trim($this->input->post('new_exam_period'));
				$this->db->where('exam_period',$new_exam_period); 
				$chk_exist = $this->master_model->getRecordCount('dashboard_exam_period');
				
				if($chk_exist == 0)
				{				
					$result['flag'] = "success";
					
					$add_data['exam_period'] = $new_exam_period;
					$this->master_model->insertRecord('dashboard_exam_period', $add_data, true); 
					
					$result['message'] = '<div class="alert alert-success alert-dismissable"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>Exam period successfully added</div>';
					$exam_period_data = $this->master_model->getRecords('dashboard_exam_period', '', 'exam_period', array('exam_period'=>'DESC'));
					
					$sel_exam_prd = trim($this->input->post('sel_exam_prd'));
					
					$exam_period_sel = '<select name="exam_period" id="exam_period" class="form-control chosen-select">
					<option value="">Select Exam Period</option>';
					if(count($exam_period_data) > 0)
					{
						foreach($exam_period_data as $row)
						{
							$selected_val = '';
							if($sel_exam_prd != "" && $sel_exam_prd == $row['exam_period']) { $selected_val = 'selected'; }$exam_period_sel .= '<option value="'.$row['exam_period'].'" '.$selected_val.'>'.$row['exam_period'].'</option>';	
						}
					}
					$exam_period_sel .= '</select>';
					
					$result['exam_period_sel'] = $exam_period_sel;
				}
				else
				{
					$result['flag'] = "error";
					$result['message'] = "Exam period already exist in system";
				}
			}
			else
			{
				$result['flag'] = "error";
				$result['message'] = "";
			}
			echo json_encode($result);
		}
		
		function get_exam_period_dropdown()
		{
			// ini_set('display_errors', 1);
			// ini_set('display_startup_errors', 1);
			// error_reporting(E_ALL);
			$result = array();
			if(isset($_POST) && count($_POST) > 0)
			{
				$exam_code = trim($this->input->post('exam_code'));
				$selected_period = trim($this->input->post('selected_period'));
				$selected_period_arr = explode(",",$selected_period);
				
				$this->db->group_by('exam_period');
				$this->db->where_in('exam_code',explode(",",$exam_code)); 
				$exam_data = $this->master_model->getRecords('exam_activation_master','','exam_period');
				//echo $this->db->last_query();
				
				if(count($exam_data) > 0)
				{				
					$drop_down='';
					$drop_down.='<select name="exam_period[]" id="exam_period" class="form-control chosen-select" multiple data-placeholder="Select Exam Period">';
					foreach ($exam_data as $key => $value) 
					{
						$selected = '';
						//if($selected_period!='' && $value['exam_period']==$selected_period) { $selected = 'selected'; }
						if(in_array($value['exam_period'],$selected_period_arr)) { $selected = 'selected'; }
						$drop_down.= '<option '.$selected.' value="'.$value['exam_period'].'">'.$value['exam_period'].'</option>';
					}
					$drop_down.='</select>';
					$result['flag'] = "success";
					$result['message']='';
					$result['drop_down']=$drop_down;
				}
				else
				{
					$result['flag'] = "error";
					$result['message'] = "No data found.Please try again";
				}
			}
			else
			{
				$result['flag'] = "error";
				$result['message'] = "";
			}
			echo json_encode($result);
			
		}
	}			