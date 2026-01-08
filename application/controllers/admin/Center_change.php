<?php
 //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
defined('BASEPATH') or exit('No direct script access allowed');
class Center_change extends CI_Controller
{
	public $UserID;

	public function __construct()
	{
		parent::__construct();
		if ($this->session->id == "") {
			redirect('admin/Login');
		}

		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->UserID = $this->session->id;

		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->helper('general_helper');
		$this->load->helper('admitcard_helper');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('KYC_Log_model');
		$this->load->model('billdesk_pg_model');
	}


	public function examReg()
	{
		
		if ($this->session->userdata('roleid') != 1 && $this->session->userdata('roleid') != 21) {
			redirect(base_url() . 'admin/MainController'); 
		}

		$from_date = date('Y-m-d', strtotime(date('Y-m-d'). ' - 30 days'));
		$end_date  = date('Y-m-d');
 
		if(isset($_GET['from_date']) && $_GET['end_date']) {
			$from_date = date('Y-m-d', strtotime($_GET['from_date']));
			$end_date  =  date('Y-m-d', strtotime($_GET['end_date']));
		} 
		$data['from_date']=$from_date;
		$data['end_date']=$end_date;
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Center Change Request</li>
							   </ol>';

		

		$data['result'] = $this->getRecords($from_date,$end_date);
		

		$this->load->view('admin/center_change_list', $data);
	}
	public function exportToCSV()
	{
		require_once  FCPATH. 'application/helpers/PHPExcel/Classes/PHPExcel.php';
		require_once  FCPATH. 'application/helpers/PHPExcel/Classes/PHPExcel/IOFactory.php';

		$from_date = date('Y-m-d', strtotime(date('Y-m-d'). ' - 15 days'));
		$end_date  = date('Y-m-d');
 
		if(isset($_GET['from_date']) && $_GET['end_date']) {
			$from_date = date('Y-m-d', strtotime($_GET['from_date']));
			$end_date  =  date('Y-m-d', strtotime($_GET['end_date']));
		}
		$result = $this->getRecords($from_date,$end_date);
		$filename = FCPATH.'/uploads/centerchangecsv/center_change_data.xlsx';
		$newfilename = FCPATH.'/uploads/centerchangecsv/center-change-records-'.date('ymdhis').'.xlsx';
		
		$objPHPExcel =PHPExcel_IOFactory::load($filename);
	

		$objPHPExcel->setActiveSheetIndex(0);
		$cell =3;// $objPHPExcel->getActiveSheet()->getHighestRow()+1;

		$styleArray1 = array(
			
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				//	'color' => array('rgb' => 'AAAAAA')
				)
			));
			$styleArray = array(
				'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => 'ffffff'),
				
				),
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					//	'color' => array('rgb' => 'AAAAAA')
					)
				));
			$i=1;
		foreach ($result as $key=>$c_row){
			$cell++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$cell,$i++)
								->setCellValue('B'.$cell, $c_row['description'])
								->setCellValue('C'.$cell, $c_row['regnumber'])
								->setCellValue('D'.$cell, $c_row['firstname'])
								->setCellValue('E'.$cell, $c_row['lastname'])
								->setCellValue('F'.$cell, $c_row['email'])
								->setCellValue('G'.$cell, $c_row['old_center_name'])
								->setCellValue('H'.$cell, $c_row['center_name'])
								->setCellValue('I'.$cell, $c_row['status'])
								->setCellValue('J'.$cell, $c_row['createdon']);
			//$objPHPExcel->getColumnDimension(substr('A'.$cell, 0, 1))->setWidth(50);
			/*$objPHPExcel->getActiveSheet()->getStyle('H'.$cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$cell)->getFill()->getStartColor()->setARGB('4d4d4d'); 
			$objPHPExcel->getActiveSheet()->getStyle('H'.$cell)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$cell)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$cell)->getFill()->getStartColor()->setARGB('4d4d4d'); 
			$objPHPExcel->getActiveSheet()->getStyle('J'.$cell)->applyFromArray($styleArray);*/

			$objPHPExcel->getActiveSheet()->getStyle('A'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$cell)->applyFromArray($styleArray1);
			// 
								// Save Excel xls File
								//$filename = 'garp_data.xls';
								$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
								$objWriter->save($newfilename);
		   }
		   $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
		   $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(100);

		   ob_clean();
		   $file = basename($newfilename);

		if(file_exists($newfilename)){

				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private",false);
				header("Content-Description: File Transfer");
				header("Content-Disposition: attachment; filename=\"$file\"");
				header("Content-Type:  application/vnd.ms-excel");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ".filesize($newfilename));
				readfile($newfilename);
				unlink($newfilename);
			exit;
		}
		
	}
	function getRecords($from_date,$end_date) {

		$result = array();
		$this->db->select('c.*,member_registration.firstname,member_registration.lastname,exam_master.description');
		$this->db->join('exam_master', 'c.exam_code=exam_master.exam_code');
		$this->db->join('member_registration', 'member_registration.regnumber=c.regnumber');
		$this->db->where('date(c.created_on) BETWEEN "'.$from_date. '" and "'.$end_date.'"');		
		$this->db->order_by('c.id', 'DESC');
		$res = $this->master_model->getRecords("exam_center_changes c");
		//echo $this->db->last_query();exit;
		if ($res) {
			$result = $res;
			$data['result'] = $result;
			//echo'<pre>';print_r($data['result'] );exit;
			$i = 0;
			foreach ($result as $row) {
				if ($row['status'] == 1)
					$result[$i]['status'] = 'Approved';
				else if ($row['status'] == 2)
					$result[$i]['status'] = 'Pending';
				else
					$result[$i]['status'] = 'Rejected';

				$result[$i]['createdon'] = date('d-m-Y', strtotime($row['created_on']));

				$admitcard_details = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $row['regnumber'], 'exm_cd' => $row['exam_code'], 'exm_prd ' => $row['exam_period'], 'remark' => 1));
				$result[$i]['old_center_name'] = $admitcard_details[0]['center_name'];

				$i++;
			}


		}
		return $result;
	}
	function details($id) {

		$id = base64_decode($id);
		$this->db->select('c.*,member_registration.regid,member_registration.firstname,member_registration.lastname,exam_master.description,exam_master.exam_type,center_master.exammode,center_master.center_name');
		$this->db->join('exam_master', 'c.exam_code=exam_master.exam_code');
		$this->db->join('center_master', 'c.center_code=center_master.center_code');
		$this->db->join('member_registration', 'member_registration.regnumber=c.regnumber');
		$this->db->where('c.id= "'.$id.'"');		
		$this->db->order_by('c.id', 'DESC');
		$result = $this->master_model->getRecords("exam_center_changes c");

		//
		$admitcard_details = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $result[0]['regnumber'], 'exm_cd' => $result[0]['exam_code'], 'remark' => 1));
		$data['row'] = $result[0];
		$data['admitcard_details'] = $admitcard_details[0];
		//echo '<pre>';print_r($admitcard_details);exit;
		$compulsory_subjects = array();
		$this->db->group_by('subject_code');
			$compulsory_subjects[] = $this->master_model->getRecords('subject_master', array(
					'exam_code'      => $result[0]['exam_code'],
					'subject_delete' => '0',
					'group_code'    => 'C', // priyanka d- 20-3023 >> change caiib exam selection 
					'exam_period'    => $result[0]['exam_period'],
					//'subject_code'   => $rowdata['subject_code'],
			));
			$compulsory_subjects=array_filter($compulsory_subjects);// priyanka d- 20-3023
			
      //$compulsory_subjects = array_map('current', $compulsory_subjects);
			//echo'<pre>';print_r($compulsory_subjects);exit;
			$data['compulsory_subjects'] = $compulsory_subjects;

			if(isset($_POST) && !empty($_POST)) {
				$this->form_validation->set_rules('selCenterName', 'Centre Name', 'required|xss_clean');
        $this->form_validation->set_rules('txtCenterCode', 'Centre Code', 'required|xss_clean');
				$this->form_validation->set_rules('application_status', 'Application Status', 'required|xss_clean');
				/*if($_POST['application_status']==1) {
					$this->form_validation->set_rules('venue[]', 'Venue', 'trim|required|xss_clean');
					$this->form_validation->set_rules('date[]', 'Date', 'trim|required|xss_clean');
					$this->form_validation->set_rules('time[]', 'Time', 'trim|required|xss_clean');
				}
				if($_POST['application_status']==3) {
					$this->form_validation->set_rules('reject_reason', 'Reason of Reject', 'required|xss_clean');
        
				}*/
				$regnumber = $_POST['regnumber'];
					$checkpayment = $this->master_model->getRecords('payment_transaction', array(
						'member_regnumber' => $regnumber,
						'exam_code'      => $result[0]['exam_code'],
						'status'           => '1',
						'pay_type'         => '2',
				), '', array(
						'id' => 'DESC',
				));
				//echo $this->db->last_query();exit;
				if (count($checkpayment) > 0) {
					//echo'<pre>';print_r($_POST);exit;
					if ($this->form_validation->run() == true) {
						//echo'<pre>';print_r($_POST);exit;
						if($_POST['application_status']==1) {
								$up_application_data = array('status'=>1,'updated_on'=>date('Y-m-d H:i:s'));
								/*$subject_arr        = array();
								$venue              = $this->input->post('venue');
								$date               = $this->input->post('date');
								$time               = $this->input->post('time');

								if (count($venue) > 0 && count($date) > 0 && count($time) > 0) {
									
										foreach ($venue as $k => $v) {
											//echo'here';exit;
												$this->db->group_by('subject_code');
												$compulsory_subjects_name = $this->master_model->getRecords('subject_master', array(
														'exam_code'      => base64_decode($_POST['excd']),
														'subject_delete' => '0',
														'exam_period'    => $_POST['eprid'],
														'subject_code'   => $k,
												));
												
												
												$subject_arr[$k] = array(
														'venue'        => $v,
														'date'         => $date[$k],
														'session_time' => $time[$k],
														'subject_name' => $compulsory_subjects_name[0]['subject_description'],
												);

												$venue_details = $this->master_model->getRecords('venue_master', array(
													'venue_code'   => $v,
													'exam_date'    =>  $date[$k],
													'session_time' => $time[$k],
													'center_code'  => $_POST['txtCenterCode'],
											));

												$curr_admitcard_details = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $regnumber, 'exm_cd' => $result[0]['exam_code'],  'sub_cd' => $compulsory_subjects_name[0]['subject_code'],'remark' => 1));
												//echo $this->db->last_query();
												//echo'<pre>';print_r($curr_admitcard_details);
												if(count($curr_admitcard_details)>0) {
													
													
													
													$update_admitcard_data = array('center_code' => $_POST['txtCenterCode'],'center_name' => $_POST['center_name'],'venueid'=>$v,'venue_name'=>$venue_details[0]['venue_name'],'venueadd1'=>$venue_details[0]['venue_addr1'],'venueadd2'=>$venue_details[0]['venue_addr2'],'venueadd3'=>$venue_details[0]['venue_addr3'],'venueadd4'=>$venue_details[0]['venue_addr4'],'venueadd5'=>$venue_details[0]['venue_addr5'],'venpin'=>$venue_details[0]['venue_pincode'],'admitcard_image'=>'','exam_date'=>$date[$k],  'time' => $time[$k]);
													
													$this->master_model->updateRecord('admit_card_details', $update_admitcard_data, array('admitcard_id' => $curr_admitcard_details[0]['admitcard_id']));
														//echo $this->db->last_query().'<br>';
												}
										}
										$admitcard_pdf = genarate_admitcard( $result[0]['regnumber'],$result[0]['exam_code'], $result[0]['exam_period']);
										//echo $admitcard_pdf;exit;
										
								}*/
							}
							if($_POST['application_status']==0) {
								$up_application_data = array('status'=>0,'updated_on'=>date('Y-m-d H:i:s'));
							}
							$this->master_model->updateRecord('exam_center_changes', $up_application_data, array('id' => $id));
							$this->session->set_flashdata('success','Application status changed successfully !!');
							//exit;
							redirect(base_url('admin/Center_change/examReg'));
					}
				}
			}
		$this->load->view('admin/center_change_details', $data);
	}

	
}
