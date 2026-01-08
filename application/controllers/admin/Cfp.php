<?php
 //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
defined('BASEPATH') or exit('No direct script access allowed');
class Cfp extends CI_Controller
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
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('KYC_Log_model');
		$this->load->model('billdesk_pg_model');
	}


	public function examReg()
	{
		
		if ($this->session->userdata('roleid') != 1 && $this->session->userdata('roleid') != 20 ) {
			redirect(base_url() . 'admin/MainController'); 
		}

		$from_date = date('Y-m-d', strtotime(date('Y-m-d'). ' - 15 days'));
		$end_date  = date('Y-m-d');
 
		if(isset($_GET['from_date']) && $_GET['end_date']) {
			$from_date = date('Y-m-d', strtotime($_GET['from_date']));
			$end_date  =  date('Y-m-d', strtotime($_GET['end_date']));
		} 
		$data['from_date']=$from_date;
		$data['end_date']=$end_date;
		$data["breadcrumb"] = '<ol class="breadcrumb"><li><a href="' . base_url() . 'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li>Member List</li>
							   </ol>';

		

		$data['result'] = $this->getRecords($from_date,$end_date);
		//echo'<pre>';print_r($data['result'] );exit;

		$this->load->view('admin/cfp_exam_details', $data);
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
		$filename = FCPATH.'/uploads/cfpcsv/cfp_data.xlsx';
		$newfilename = FCPATH.'/uploads/cfpcsv/cfp-records-'.date('ymdhis').'.xlsx';
		//$filename = 'cfp_data.xlsx';
		$objPHPExcel =PHPExcel_IOFactory::load($filename);
	//	

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
					//'size'  => 15,
					//'name'  => 'Verdana'
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
								->setCellValue('G'.$cell, $c_row['mobile'])
								/*->setCellValue('H'.$cell, $c_row['registrationtype'])
								->setCellValue('I'.$cell, $c_row['amount'])
								->setCellValue('J'.$cell, $c_row['amount'])
								->setCellValue('K'.$cell, 'Yes')*/;
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
			/*$objPHPExcel->getActiveSheet()->getStyle('H'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('J'.$cell)->applyFromArray($styleArray1);
			$objPHPExcel->getActiveSheet()->getStyle('K'.$cell)->applyFromArray($styleArray1);*/
			// 
								// Save Excel xls File
								//$filename = 'cfp_data.xls';
								$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
								$objWriter->save($newfilename);
		   }
		   $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false);
		   $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(100);

		   $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(false);
		   $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(100);
		   
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

		$this->db->where('isactive', '1');
		$this->db->where('isdeleted', 0);
		$this->db->where('pay_type', 2);
		$this->db->where('b.status', 1);
		$this->db->where('g.pay_status', 1);
		$select = 'd.description,b.transaction_no,a.regid,a.regnumber,g.namesub,g.firstname,g.lastname,g.dateofbirth,g.createdon,b.status,b.date,c.exam_medium,c.exam_fee,gender,d.description,c.exam_center_code,b.transaction_details,g.email,a.registrationtype,b.amount,a.mobile';

		$this->db->where('date(c.created_on) BETWEEN "'.$from_date. '" and "'.$end_date.'"');

		$this->db->join('member_registration a', 'a.regnumber=c.regnumber', 'RIGHT');
		$this->db->join('exam_master d', 'd.exam_code=c.exam_code', 'LEFT');
		$this->db->join('payment_transaction b', 'b.ref_id=c.id', 'LEFT');
		$this->db->join('cfp_exam_registration g', 'g.mem_exam_id=c.id', 'LEFT');
		//$this->db->join('member_registration a','a.regnumber=b.member_regnumber','RIGHT');
		$this->db->group_by('b.transaction_no');
		$this->db->order_by('c.id', 'DESC');
		$res = $this->UserModel->getRecords("member_exam c", $select,'c.exam_code', $this->config->item('examCodeCFP'));
		
	//	echo $this->db->last_query();exit;
		if ($res) {
			$result = $res->result_array();
			$data['result'] = $result;
			$i = 0;
			foreach ($result as $row) {
				if ($row['status'] == 1)
					$result[$i]['status'] = 'Completed';
				else if ($row['status'] == 2)
					$result[$i]['status'] = 'Pending';
				else
					$result[$i]['status'] = 'Incomplete';

				
				$center = $this->master_model->getRecords("center_master", array('center_code' => $row['exam_center_code']), 'center_name,center_code');

				if (count($center)) {
					$result[$i]['center_name'] = $center[0]['center_name'] . "<br>(" . $center[0]['center_code'] . ")";
				} else {
					$result[$i]['center_name'] = '';
				}

				$medium = $this->master_model->getRecords("medium_master", array('medium_code' => $row['exam_medium']), 'medium_description');

				if (count($medium)) {
					$result[$i]['medium_description'] = $medium[0]['medium_description'];
				} else {
					$result[$i]['medium_description'] = '';
				}

				$result[$i]['createdon'] = date('d-m-Y', strtotime($row['createdon']));

				$i++;
			}

			//$data['result'] = $result;

			

		}
		return $result;
	}
	
}
