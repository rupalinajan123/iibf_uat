<?php
  defined('BASEPATH') OR exit('No direct script access allowed');
  header("Access-Control-Allow-Origin: *");
  
  class Rpe_exam_dashboard extends CI_Controller
  {    
    public function __construct()
    { //exit;
      parent::__construct();
      $this->load->library('upload');
      $this->load->helper('upload_helper');
      /* $this->load->helper('master_helper'); */
      $this->load->helper('general_helper');
      $this->load->helper('blended_invoice_helper');
      $this->load->model('Master_model');
      $this->load->library('email');
      $this->load->helper('date');
      $this->load->library('email');
      $this->load->model('Emailsending');
      $this->load->model('log_model');
    }
    
    public function index()
    {
			$data['current_date'] = $current_date = date("Y-m-d");
			$error_msg = '';
			
			$exam_code = $start_date = $end_date = $exam_disp_name = $exam_date = '';
			$result_data = array();			
      
			if(isset($_POST) && count($_POST) > 0)
			{ 
				$exam_code = $this->input->post('exam_code');
				$start_date = $this->input->post('start_date');
				$end_date = $this->input->post('end_date');
				$exam_date = $this->input->post('exam_date');
        				
				$this->form_validation->set_rules('exam_code[]', 'Exam Code', 'trim|required|xss_clean',array('required' => 'Please select the %s'));				
				$this->form_validation->set_rules('start_date', 'Start Date', 'trim|xss_clean');		
				$this->form_validation->set_rules('end_date', 'End Date', 'trim|xss_clean');		
				$this->form_validation->set_rules('exam_date', 'Exam Date', 'trim|xss_clean');
				if($start_date == '' && $end_date == '' && $exam_date == '')
				{					
					$error_msg = 'Please select (Start date & End Date) or (Only Exam Date) or (Start date, End Date & Exam Date) combinations for filter';
				}
				else if($start_date != '' && $end_date == '')
				{
					$this->form_validation->set_rules('end_date', 'End Date', 'trim|required|callback_check_end_date|xss_clean',array('required' => 'Please select the %s'));		
				}
				else if($start_date == '' && $end_date != '')
				{
					$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|xss_clean',array('required' => 'Please select the %s'));		
				}
				else if($start_date != '' && $end_date != '')
				{
					$this->form_validation->set_rules('end_date', 'End Date', 'trim|callback_check_end_date|xss_clean');		
				}
				
				if($exam_code != "")
				{
					$dateRange = $this->get_exam_date_range($exam_code);		
					$data['StartDateLimit'] = $dateRange['StartDate'];
					$data['EndDateLimit'] = $dateRange['EndDate'];
				}
				
				if($this->form_validation->run() && $error_msg == '')
				{ 
          			$this->db->where_in('exam_code', $exam_code, FALSE);
					$exam_details = $this->master_model->getRecords('exam_master',array(),'description',array(),'',''); 
		          //echo $this->db->last_query(); exit;
		          if(count($exam_details) > 0)
		          {
		            foreach($exam_details as $exam_name)
		            {
		              $exam_disp_name .= $exam_name['description'].', ';
		            }
		          }
					
					//$select_res = "e.id, e.regnumber, e.exam_code, DATE(e.created_on) AS CreatedDate, a.admitcard_id, a.mem_exam_id, a.exam_date, a.exm_cd, a.remark";
					$select_res = "count(e.id) AS TotalCnt, DATE(e.created_on) AS CreatedDate";
					//$whr_result['e.exam_code'] = $exam_code;
					$this->db->where_in('e.exam_code', $exam_code, FALSE);
					if($start_date != "" && $end_date != "")
					{
						$whr_result['DATE(e.created_on) >= '] = $start_date;
						$whr_result['DATE(e.created_on) <= '] = $end_date;
						$this->db->group_by('DATE(e.created_on)');
					}
					$whr_result['e.pay_status'] = '1';
				
					if($exam_date != "")
					{
						$this->db->join("admit_card_details a", "a.mem_exam_id = e.id", "INNER");
						$whr_result['a.exam_date '] = $exam_date;
						$whr_result['a.remark '] = '1';
						$whr_result['a.record_source '] = 'Online';
					}
					$total_data = $this->master_model->getRecords('member_exam e',$whr_result,$select_res,array(),'','');
					//echo "<br> Total data : ".$this->db->last_query();
						
					/********** START : FREE DATA QRY CONDITIONS *****************/
          			$this->db->where_in('e.exam_code', $exam_code, FALSE);
					$this->db->where("e.exam_fee <=","0");
					$this->db->where("e.elearning_flag","N");
					$this->db->where("e.free_paid_flg","F");
					$this->db->where("a.record_source","Online");
					if($start_date != "" && $end_date != "") { $this->db->group_by('DATE(e.created_on)'); }
					if($exam_date != "") { $this->db->join("admit_card_details a", "a.mem_exam_id = e.id", "INNER"); }
					$free_result_data = $this->master_model->getRecords('member_exam e',$whr_result,$select_res,array(),'','');	
					//echo "<br><br> Free data : ".$this->db->last_query(); exit;
				
					$free_result_data_final = array();
					if(count($free_result_data) > 0)
					{
						foreach($free_result_data as $free_result)
						{
							$free_result_data_final[$free_result['CreatedDate']] = $free_result['TotalCnt'];
						}
					}
					/********** END : FREE DATA QRY CONDITIONS *****************/
					
					/********** START : FREE + E-LEARNING DATA QRY CONDITIONS *****************/
         			$this->db->where_in('e.exam_code', $exam_code, FALSE);
					$this->db->where("e.exam_fee <","200");
					$this->db->where("e.elearning_flag","Y");
					$this->db->where("e.free_paid_flg","P");
					$this->db->where("a.record_source","Online");
					if($start_date != "" && $end_date != "") { $this->db->group_by('DATE(e.created_on)'); }
					if($exam_date != "") { $this->db->join("admit_card_details a", "a.mem_exam_id = e.id", "INNER"); }
					$free_elearning_result_data = $this->master_model->getRecords('member_exam e',$whr_result,$select_res,array(),'','');
					//echo "<br><br> Free e-learning data : ".$this->db->last_query();
				
					$free_elearning_result_data_final = array();
					if(count($free_elearning_result_data) > 0)
					{
						foreach($free_elearning_result_data as $free_elearning_result)
						{
							$free_elearning_result_data_final[$free_elearning_result['CreatedDate']] = $free_elearning_result['TotalCnt'];
						}
					}
					/********** END : FREE + E-LEARNING DATA QRY CONDITIONS *****************/
					
					/********** START : PAID DATA QRY CONDITIONS *****************/
          			$this->db->where_in('e.exam_code', $exam_code, FALSE);
					$this->db->where("e.exam_fee >=","200");
					$this->db->where("e.elearning_flag","N");
					$this->db->where("e.free_paid_flg","P");
					$this->db->where("a.record_source","Online");
					if($start_date != "" && $end_date != "") { $this->db->group_by('DATE(e.created_on)'); }
					if($exam_date != "") { $this->db->join("admit_card_details a", "a.mem_exam_id = e.id", "INNER"); }
					$paid_result_data = $this->master_model->getRecords('member_exam e',$whr_result,$select_res,array(),'','');
					//echo "<br><br> Paid data : ".$this->db->last_query();
				
					$paid_result_data_final = array();
					if(count($paid_result_data) > 0)
					{
						foreach($paid_result_data as $paid_result)
						{
							$paid_result_data_final[$paid_result['CreatedDate']] = $paid_result['TotalCnt'];
						}
					}
					/********** END : PAID DATA QRY CONDITIONS *****************/
					
					/********** START : PAID + E-LEARNING DATA QRY CONDITIONS *****************/
         			$this->db->where_in('e.exam_code', $exam_code, FALSE);
					$this->db->where("e.exam_fee >=","200");
					$this->db->where("e.elearning_flag","Y");
					$this->db->where("e.free_paid_flg","P");
					$this->db->where("a.record_source","Online");
					if($start_date != "" && $end_date != ""){ $this->db->group_by('DATE(e.created_on)'); }
					if($exam_date != "") { $this->db->join("admit_card_details a", "a.mem_exam_id = e.id", "INNER"); }
					$paid_elearning_result_data = $this->master_model->getRecords('member_exam e',$whr_result,$select_res,array(),'','');
					//echo "<br><br> Paid e-learning data : ".$this->db->last_query(); 
				
					$paid_elearning_result_data_final = array();
					if(count($paid_elearning_result_data) > 0)
					{
						foreach($paid_elearning_result_data as $paid_elearning_result)
						{
							$paid_elearning_result_data_final[$paid_elearning_result['CreatedDate']] = $paid_elearning_result['TotalCnt'];
						}
					}
					/********** END : PAID + E-LEARNING DATA QRY CONDITIONS *****************/
					
					if($start_date != "" && $end_date != "")
					{
						/********** DATE ARRAY FROM SELECTED DATE RANGE *****************/
						$result_date_arr = array();
						$check_date_arr = new DatePeriod(new DateTime($start_date), new DateInterval('P1D'), new DateTime(date('Y-m-d', strtotime("+1 days", strtotime($end_date)))));
						foreach ($check_date_arr as $key => $value) 
						{
							$chk_date = $value->format('Y-m-d');
							$result_date_arr[] = $chk_date;
						} 
					
						/********** FINAL RESULT ARRAY PROCESSING *****************/
						if(count($result_date_arr)>0)
						{
							foreach($result_date_arr as $res_date)
							{
								$result_data[$res_date]['res_date'] = $res_date;
								if(array_key_exists($res_date,$free_result_data_final))
								{
									$result_data[$res_date]['free_cnt'] = $free_result_data_final[$res_date];
								}
								else { $result_data[$res_date]['free_cnt'] = 0; }
								
								if(array_key_exists($res_date,$free_elearning_result_data_final))
								{
									$result_data[$res_date]['free_elearning_cnt'] = $free_elearning_result_data_final[$res_date];
								}
								else { $result_data[$res_date]['free_elearning_cnt'] = 0; }
								
								if(array_key_exists($res_date,$paid_result_data_final))
								{
									$result_data[$res_date]['paid_cnt'] = $paid_result_data_final[$res_date];
								}
								else { $result_data[$res_date]['paid_cnt'] = 0; }
								
								if(array_key_exists($res_date,$paid_elearning_result_data_final))
								{
									$result_data[$res_date]['paid_elearning_cnt'] = $paid_elearning_result_data_final[$res_date];
								}
								else { $result_data[$res_date]['paid_elearning_cnt'] = 0; }
							}
						}					
					}
					else
					{
						$result_data[$exam_date]['res_date'] = $exam_date;
						$result_data[$exam_date]['free_cnt'] = $free_result_data[0]['TotalCnt'];
						$result_data[$exam_date]['free_elearning_cnt'] = $free_elearning_result_data[0]['TotalCnt'];
						$result_data[$exam_date]['paid_cnt'] = $paid_result_data[0]['TotalCnt'];
						$result_data[$exam_date]['paid_elearning_cnt'] = $paid_elearning_result_data[0]['TotalCnt'];
					}
				}
      		}
      
			/********** START : ACTIVE EXAM DATA TO DISPLAY IN DROPDOWN *****************/
			$select_exam = "a.id, a.exam_code, a.exam_period, a.exam_from_date, a.exam_from_time, a.exam_to_date, a.exam_to_time, a.exam_activation_delete, em.description";
		      /* $whr_exam['exam_from_date <='] = $current_date;
		      $whr_exam['exam_to_date >='] = $current_date; */
		      $whr_exam['a.exam_activation_delete'] = '0';
			$this->db->where_in('a.exam_code','1002,1003,1004,1005,1006,1007,1008,1009,1010,1011,1012,1013,1014,2027,1019,1020',FALSE);
			$this->db->order_by('a.exam_code', 'ASC');
			$this->db->join("exam_master em","em.exam_code = a.exam_code","LEFT");
			$data['active_exam_data'] = $active_exam_data = $this->master_model->getRecords('exam_activation_master a',$whr_exam,$select_exam,array(),'','');
			/********** END : ACTIVE EXAM DATA TO DISPLAY IN DROPDOWN *****************/
      		//echo $this->db->last_query();die;
		      /***** START : TOTAL COUNT *****/
		      $active_exam_code_str = '';
		      if(count($active_exam_data) > 0)
		      {
		        foreach($active_exam_data as $active_exam_res)
		        {
		          $active_exam_code_str .= $active_exam_res['exam_code'].",";
		        }
		      }
		      
		      if($active_exam_code_str != '')
		      {
		        $select_tot_cnt = "count(e.id) AS TotalCnt";
		        $this->db->where_in('e.exam_code',rtrim($active_exam_code_str,","),FALSE);
		        $whr_tot_cnt['DATE(e.created_on) >= '] = '2020-07-15';
		        $whr_tot_cnt['e.pay_status'] = '1';
		        $total_cnt_data = $this->master_model->getRecords('member_exam e',$whr_tot_cnt,$select_tot_cnt,array(),'','');
		        $data['total_cnt'] = $total_cnt_data[0]['TotalCnt'];
		      }
		      else
		      {
		        $data['total_cnt'] = 0;
		      }
		      /***** END : TOTAL COUNT *****/
      
			$data['exam_date_data'] = $this->master_model->getRecords('valid_examination_date',array('examination_date >= '=>date('Y-m-d')),'DISTINCT(examination_date) AS ExamDate',array('examination_date'=>'ASC'),'','');
			//echo $this->db->last_query();die;
			
			
		      $data['exam_code'] = $exam_code;
					$data['exam_disp_name'] = $exam_disp_name;
					$data['start_date'] = $start_date;
					$data['end_date'] = $end_date;
					$data['exam_date'] = $exam_date;
					$data['result_data'] = $result_data;
					$data['error_msg'] = $error_msg;
					$data['middle_content'] = 'rpe_exam_dashboard/index';

		      $this->load->view('rpe_exam_dashboard/rpe_exam_common_view', $data);

	}
		
	public function get_date_range_ajax()
	{
      $selected_start_date = $selected_end_date = '';
      $blank_flag = 0;
			if(isset($_POST) && $_POST['exam_code'] != "")
			{
        $dateRange = $this->get_exam_date_range($_POST['exam_code']);
				if(count($dateRange['exam_details']) > 0)
				{
					if(isset($_POST) && $_POST['selected_start_date'] != "" && $_POST['selected_start_date'] >= $dateRange['StartDate'] && $_POST['selected_start_date'] <= $dateRange['EndDate'])
					{
						$selected_start_date = $_POST['selected_start_date'];
					}
					
					if(isset($_POST) && $_POST['selected_end_date'] != "" && $_POST['selected_end_date'] >= $dateRange['StartDate'] && $_POST['selected_end_date'] <= $dateRange['EndDate'])
					{
						$selected_end_date = $_POST['selected_end_date'];
					}					
				}
				else
				{
					$blank_flag = 1;
				}
      }
			else
			{
        $blank_flag = 1;
			}
      
      if($blank_flag == 1)
      {
        $dateRange['StartDate'] = '';
        $dateRange['EndDate'] = date("Y-m-d");
        
        $selected_start_date = $_POST['selected_start_date'];        
        if(isset($_POST) && $_POST['selected_end_date'] != "" && $_POST['selected_end_date'] <= $dateRange['EndDate'])
        {
          $selected_end_date = $_POST['selected_end_date'];
        }
      }
      
      $result['flag'] = "success";
      $result['start_date_html'] = '<input type="text" class="form-control" id="start_date" name="start_date" value="'.$selected_start_date.'" placeholder="Start Date">';
      $result['end_date_html'] = '<input type="text" class="form-control" id="end_date" name="end_date" value="'.$selected_end_date.'" placeholder="End Date">';
                
      $result['date_response'] = '
        <script type="text/javascript">
          $(document).ready(function() 
          {
            $("#start_date").datepicker(
            { 
              keyboardNavigation: true, 
              forceParse: true, 
              autoclose: true, 
              format: "yyyy-mm-dd",
              clearBtn: true,
              startDate:"'.$dateRange['StartDate'].'",
              endDate:"'.$dateRange['EndDate'].'"
            });
            
            $("#end_date").datepicker(
            { 
              keyboardNavigation: true, 
              forceParse: true, 
              autoclose: true, 
              format: "yyyy-mm-dd",
              clearBtn: true,
              startDate:"'.$dateRange['StartDate'].'",
              endDate:"'.$dateRange['EndDate'].'"
            });
          });
        </script>';
        
			echo json_encode($result);
	}
		
	public function get_exam_date_range($exam_code='')
	{
      $current_date = date("Y-m-d");
      $data['StartDate'] = $StartDate = $current_date;
      $data['EndDate'] = date('Y-m-d', strtotime("-1days", strtotime($StartDate)));
      $data['exam_details'] = array();
      
			if($exam_code != '')
			{
		        $this->db->where_in('exam_code',$exam_code, FALSE);
						$exam_details = $this->master_model->getRecords('exam_activation_master',array(),'min(exam_from_date) AS exam_from_date, max(exam_to_date) AS exam_to_date',array(),'','');
		        /* echo $this->db->last_query(); exit; */
		        
		        $data['exam_details'] = $exam_details;
		        if(count($exam_details) > 0)
						{
							if($exam_details[0]['exam_to_date'] <= $current_date) { $EndDate = $exam_details[0]['exam_to_date']; }
							else { $EndDate = $current_date; }
							
							if($exam_details[0]['exam_from_date'] <= $current_date) { $StartDate = $exam_details[0]['exam_from_date']; }
							else { $StartDate = $current_date; $EndDate = date('Y-m-d', strtotime("-1days", strtotime($StartDate))); }
							
							$data['StartDate'] = $StartDate;
							$data['EndDate'] = $EndDate;
						}
			}
					return $data;
	}
		
	public function check_end_date($str)
	{
			$start_date = $this->input->post('start_date');
			$end_date = $str;
			if($start_date > $end_date)
			{
				$this->form_validation->set_message('check_end_date', 'Please select valid end date');
				return FALSE;
			}
			else
			{
				return TRUE;
			}
	}
  
	public function download_admit_card($exam_code=0,$start_date=0,$end_date=0,$exam_date=0)
	{
			$select_res = "ac.exm_cd, ac.exm_prd, ac.mem_type, ac.g_1, ac.mem_mem_no, ac.mam_nam_1, r.email, r.mobile, ac.center_code, ac.center_name, ac.sub_cd, ac.sub_dsc, ac.venueid, ac.venue_name, CONCAT(ac.venueadd1,', ',ac.venueadd2,', ',ac.venueadd3,', ',ac.venueadd4,', ',ac.venueadd5) AS VENUE_ADDRESS, ac.venpin, ac.seat_identification, ac.pwd, ac.exam_date, ac.time, ac.mode, ac.m_1, scribe_flag, ac.vendor_code,";
			
			if($start_date != 0) { $whr_qry['DATE(ac.created_on) >= '] = $start_date; }
			if($end_date != 0) { $whr_qry['DATE(ac.created_on) <= '] = $end_date; }
			if($exam_date != 0) { $whr_qry['ac.exam_date'] = $exam_date; }
			
			$this->db->where_in('ac.exm_cd',$exam_code,FALSE);
			$whr_qry['ac.remark'] = '1';
			$whr_qry['ac.record_source'] = 'Online';
			//$this->db->where_in('ac.exm_prd','',FALSE);
			$whr_qry['r.isactive'] = '1';
			$this->db->join('member_registration r','r.regnumber=ac.mem_mem_no','INNER');
			$admin_card_data = $this->master_model->getRecords('admit_card_details ac',$whr_qry,$select_res,array(),'','');
			//echo $this->db->last_query();
			$exam_code_for_file_name = str_replace(',', '_', $exam_code);
			$filename = "admin_card_".$exam_code_for_file_name."_".date('YmdHis').".csv";
			$fp = fopen('php://output', 'w');
			$header = array("exm_cd",	"exm_prd",	"mem_type",	"g_1", "mem_mem_no",	"mam_nam_1",	"email",	"mobile",	"center_code",	"center_name",	"sub_cd",	"sub_dsc",	"venueid",	"venue_name",	"VENUE_ADDRESS",	"venpin",	"seat_identification",	"pwd",	"exam_date",	"time",	"mode",	"m_1",	"scribe_flag",	"vendor_code");
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			fputcsv($fp, $header);
			
			if(count($admin_card_data) > 0)
			{
				foreach ($admin_card_data as $line) 
				{
					fputcsv($fp, $line);
				}
			}			
			exit;
	}
}
