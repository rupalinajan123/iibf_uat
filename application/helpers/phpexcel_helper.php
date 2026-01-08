<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function download_data_excel($registration, $filename, $title)
{
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');
	
	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');
	
	/** Include PHPExcel */
	//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
	require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel.php';
	
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("IIBF")
								 ->setLastModifiedBy("IIBF")
								 ->setTitle("IIBF")
								 ->setSubject("IIBF")
								 ->setDescription("IIBF")
								 ->setKeywords("IIBF")
								 ->setCategory("IIBF");
	
	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'MEM_MEM_NO')
				->setCellValue('B1', 'MEM_MEM_TYP')
				->setCellValue('C1', 'MEM_TLE')
				->setCellValue('D1', 'MEM_NAM_1')
				->setCellValue('E1', 'MEM_NAM_2')
				->setCellValue('F1', 'MEM_NAM_3')
				->setCellValue('G1', 'ID_CARD_NAME')
				->setCellValue('H1', 'MEM_ADR_1')
				->setCellValue('I1', 'MEM_ADR_2')
				->setCellValue('J1', 'MEM_ADR_3')
				->setCellValue('K1', 'MEM_ADR_4')
				->setCellValue('L1', 'MEM_ADR_5')
				->setCellValue('M1', 'MEM_ADR_6')
				->setCellValue('N1', 'MEM_PIN_CD')
				->setCellValue('O1', 'MEM_STE_CD')
				->setCellValue('P1', 'MEM_DOB')
				->setCellValue('Q1', 'MEM_SEX_CD')
				->setCellValue('R1', 'MEM_QLF_GRD')
				->setCellValue('S1', 'MEM_QLF_CD')
				->setCellValue('T1', 'MEM_INS_CD')
				->setCellValue('U1', 'BRANCH')
				->setCellValue('V1', 'MEM_DSG_CD')
				->setCellValue('W1', 'MEM_BNK_JON_DT')
				->setCellValue('X1', 'EMAIL')
				->setCellValue('Y1', 'STD_R')
				->setCellValue('Z1', 'PHONE_R')
				->setCellValue('AA1', 'MOBILE')
				->setCellValue('AB1', 'ID_TYPE')
				->setCellValue('AC1', 'ID_NO')
				->setCellValue('AD1', 'BDRNO')
				->setCellValue('AE1', 'TRN_AMT')
				->setCellValue('AF1', 'TRN_DATE')
				->setCellValue('AG1', 'INSTRUMENT_NO')
				->setCellValue('AH1', 'INSTRUMENT_TYPE')
				->setCellValue('AI1', 'AR_FLG')
				->setCellValue('AJ1', 'PROC_FLG')
				->setCellValue('AK1', 'FI_YEAR_ID');
				
	$objPHPExcel->getActiveSheet()->getStyle('A1:AK1')->getFont()->setBold(true);
	
	$CI =& get_instance();
	
	if(count($registration))
	{
		$i = 2;
		foreach($registration as $reg_data)
		{
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.($i).'', $reg_data['regnumber'])
				->setCellValue('B'.($i).'', $reg_data['registrationtype'])
				->setCellValue('C'.($i).'', $reg_data['namesub'])
				->setCellValue('D'.($i).'', $reg_data['firstname'])
				->setCellValue('E'.($i).'', $reg_data['middlename'])
				->setCellValue('F'.($i).'', $reg_data['lastname'])
				->setCellValue('G'.($i).'', $reg_data['displayname'])
				->setCellValue('H'.($i).'', $reg_data['address1'])
				->setCellValue('I'.($i).'', $reg_data['address2'])
				->setCellValue('J'.($i).'', $reg_data['address3'])
				->setCellValue('K'.($i).'', $reg_data['address4'])
				->setCellValue('L'.($i).'', $reg_data['district'])
				->setCellValue('M'.($i).'', $reg_data['city'])
				->setCellValue('N'.($i).'', $reg_data['pincode'])
				->setCellValue('O'.($i).'', $reg_data['state'])
				->setCellValue('P'.($i).'', date('d-M-y',strtotime($reg_data['dateofbirth'])))
				->setCellValue('Q'.($i).'', $reg_data['gender'])
				->setCellValue('R'.($i).'', $reg_data['qualification'])
				->setCellValue('S'.($i).'', $reg_data['specify_qualification'])
				->setCellValue('T'.($i).'', $reg_data['associatedinstitute'])
				->setCellValue('U'.($i).'', $reg_data['branch'])
				->setCellValue('V'.($i).'', $reg_data['designation'])
				->setCellValue('W'.($i).'', date('d-M-y',strtotime($reg_data['dateofjoin'])))
				->setCellValue('X'.($i).'', $reg_data['email'])
				->setCellValue('Y'.($i).'', $reg_data['stdcode'])
				->setCellValue('Z'.($i).'', $reg_data['office_phone'])
				->setCellValue('AA'.($i).'', $reg_data['mobile'])
				->setCellValue('AB'.($i).'', $reg_data['idproof'])
				->setCellValue('AC'.($i).'', $reg_data['idNo'])
				->setCellValue('AD'.($i).'', 'BDRNO')
				->setCellValue('AE'.($i).'', 'TRN_AMT')
				->setCellValue('AF'.($i).'', 'TRN_DATE')
				->setCellValue('AG'.($i).'', 'INSTRUMENT_NO')
				->setCellValue('AH'.($i).'', 'INSTRUMENT_TYPE')
				->setCellValue('AI'.($i).'', 'AR_FLG')
				->setCellValue('AJ'.($i).'', 'PROC_FLG')
				->setCellValue('AK'.($i).'', 'FI_YEAR_ID');
				
				$i++;
		}
	}
	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle($title);
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client's web browser (Excel2007)
	
	
	//header('Content-type: text/csv');
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsv"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');	
}

function download_edited_excel($registration, $filename, $title)
{
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');
	
	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');
	
	/** Include PHPExcel */
	//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
	require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel.php';
	
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("IIBF")
								 ->setLastModifiedBy("IIBF")
								 ->setTitle("IIBF")
								 ->setSubject("IIBF")
								 ->setDescription("IIBF")
								 ->setKeywords("IIBF")
								 ->setCategory("IIBF");
	
	//MEM_MEM_NO	MEM_MEM_TYP	MEM_TLE	MEM_NAM_1	MEM_NAM_2	MEM_NAM_3	ID_CARD_NAME	MEM_ADR_1	MEM_ADR_2	MEM_ADR_3	MEM_ADR_4	MEM_ADR_5	MEM_ADR_6	MEM_PIN_CD	MEM_STE_CD	MEM_DOB	MEM_SEX_CD	MEM_QLF_GRD	MEM_QLF_CD	MEM_INS_CD	BRANCH	MEM_DSG_CD	MEM_BNK_JON_DT	EMAIL	STD_R	PHONE_R	MOBILE	ID_TYPE	ID_NO	BDRNO	TRN_AMT	TRN_DATE	INSTRUMENT_NO	INSTRUMENT_TYPE	AR_FLG	PROC_FLG	FI_YEAR_ID	LOT_NO	LOT_TY	UPD_DT	PHOTO_FLG	SIGNATURE_FLG	ID_FLG	REG_DATE	EDITED_DATAS	MODIFIED_BY	UPDATED_ON

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'MEM_MEM_NO')
				->setCellValue('B1', 'MEM_MEM_TYP')
				->setCellValue('C1', 'MEM_TLE')
				->setCellValue('D1', 'MEM_NAM_1')
				->setCellValue('E1', 'MEM_NAM_2')
				->setCellValue('F1', 'MEM_NAM_3')
				->setCellValue('G1', 'ID_CARD_NAME')
				->setCellValue('H1', 'MEM_ADR_1')
				->setCellValue('I1', 'MEM_ADR_2')
				->setCellValue('J1', 'MEM_ADR_3')
				->setCellValue('K1', 'MEM_ADR_4')
				->setCellValue('L1', 'MEM_ADR_5')
				->setCellValue('M1', 'MEM_ADR_6')
				->setCellValue('N1', 'MEM_PIN_CD')
				->setCellValue('O1', 'MEM_STE_CD')
				->setCellValue('P1', 'MEM_DOB')
				->setCellValue('Q1', 'MEM_SEX_CD')
				->setCellValue('R1', 'MEM_QLF_GRD')
				->setCellValue('S1', 'MEM_QLF_CD')
				->setCellValue('T1', 'MEM_INS_CD')
				->setCellValue('U1', 'BRANCH')
				->setCellValue('V1', 'MEM_DSG_CD')
				->setCellValue('W1', 'MEM_BNK_JON_DT')
				->setCellValue('X1', 'EMAIL')
				->setCellValue('Y1', 'STD_R')
				->setCellValue('Z1', 'PHONE_R')
				->setCellValue('AA1', 'MOBILE')
				->setCellValue('AB1', 'ID_TYPE')
				->setCellValue('AC1', 'ID_NO')
				->setCellValue('AD1', 'BDRNO')
				->setCellValue('AE1', 'TRN_AMT')
				->setCellValue('AF1', 'TRN_DATE')
				->setCellValue('AG1', 'INSTRUMENT_NO')
				->setCellValue('AH1', 'INSTRUMENT_TYPE')
				->setCellValue('AI1', 'AR_FLG')
				->setCellValue('AJ1', 'PROC_FLG')
				->setCellValue('AK1', 'FI_YEAR_ID')
				->setCellValue('AL1', 'LOT_NO')
				->setCellValue('AM1', 'LOT_TY')
				->setCellValue('AN1', 'UPD_DT')
				->setCellValue('AO1', 'PHOTO_FLG')
				->setCellValue('AP1', 'SIGNATURE_FLG')
				->setCellValue('AQ1', 'ID_FLG')
				->setCellValue('AR1', 'REG_DATE')
				->setCellValue('AS1', 'EDITED_DATAS')
				->setCellValue('AT1', 'MODIFIED_BY')
				->setCellValue('AU1', 'UPDATED_ON');
				
				
	$objPHPExcel->getActiveSheet()->getStyle('A1:AU1')->getFont()->setBold(true);
	
	$CI =& get_instance();
	
	if(count($registration))
	{
		$CI->load->model('Master_model'); 
		$i = 2;
		foreach($registration as $reg_data)
		{
			if($reg_data['editedby'] == 'Admin')
			{
				$CI->db->like('title','id:'.$reg_data['regnumber'].'');
				$edited_data = $CI->Master_model->getRecords('adminlogs',array('userid'=>$reg_data['editedbyadmin']),'',array('id'=>'DESC'),0,1);
			}
			else
			{
				$CI->db->like('title','update');
				$edited_data = $CI->Master_model->getRecords('userlogs',array('regnumber'=>$reg_data['regnumber']),'',array('id'=>'DESC'),0,1);
			}
			
			
			if(count($edited_data))
			{
				//$EDITED_DATAS = unserialize($edited_data[0]['description']);
				$unserialize_data = unserialize($edited_data[0]['description']);
				$EDITED_DATAS = '';
				if(count($unserialize_data))
				{
					foreach($unserialize_data['updated_data'] as $key => $row_data)
					{
						$EDITED_DATAS .= $key.' = '.$row_data.' && ';
					}
				}
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.($i).'', $reg_data['regnumber'])
				->setCellValue('B'.($i).'', $reg_data['registrationtype'])
				->setCellValue('C'.($i).'', $reg_data['namesub'])
				->setCellValue('D'.($i).'', $reg_data['firstname'])
				->setCellValue('E'.($i).'', $reg_data['middlename'])
				->setCellValue('F'.($i).'', $reg_data['lastname'])
				->setCellValue('G'.($i).'', $reg_data['displayname'])
				->setCellValue('H'.($i).'', $reg_data['address1'])
				->setCellValue('I'.($i).'', $reg_data['address2'])
				->setCellValue('J'.($i).'', $reg_data['address3'])
				->setCellValue('K'.($i).'', $reg_data['address4'])
				->setCellValue('L'.($i).'', $reg_data['district'])
				->setCellValue('M'.($i).'', $reg_data['city'])
				->setCellValue('N'.($i).'', $reg_data['pincode'])
				->setCellValue('O'.($i).'', $reg_data['state'])
				->setCellValue('P'.($i).'', date('d-M-y',strtotime($reg_data['dateofbirth'])))
				->setCellValue('Q'.($i).'', $reg_data['gender'])
				->setCellValue('R'.($i).'', $reg_data['qualification'])
				->setCellValue('S'.($i).'', $reg_data['specify_qualification'])
				->setCellValue('T'.($i).'', $reg_data['associatedinstitute'])
				->setCellValue('U'.($i).'', $reg_data['branch'])
				->setCellValue('V'.($i).'', $reg_data['designation'])
				->setCellValue('W'.($i).'', date('d-M-y',strtotime($reg_data['dateofjoin'])))
				->setCellValue('X'.($i).'', $reg_data['email'])
				->setCellValue('Y'.($i).'', $reg_data['stdcode'])
				->setCellValue('Z'.($i).'', $reg_data['office_phone'])
				->setCellValue('AA'.($i).'', $reg_data['mobile'])
				->setCellValue('AB'.($i).'', $reg_data['idproof'])
				->setCellValue('AC'.($i).'', $reg_data['idNo'])
				->setCellValue('AD'.($i).'', 'BDRNO')
				->setCellValue('AE'.($i).'', 'TRN_AMT')
				->setCellValue('AF'.($i).'', 'TRN_DATE')
				->setCellValue('AG'.($i).'', 'INSTRUMENT_NO')
				->setCellValue('AH'.($i).'', 'INSTRUMENT_TYPE')
				->setCellValue('AI'.($i).'', 'AR_FLG')
				->setCellValue('AJ'.($i).'', 'PROC_FLG')
				->setCellValue('AK'.($i).'', 'FI_YEAR_ID')
				->setCellValue('AL'.($i).'', 'LOT_NO')
				->setCellValue('AM'.($i).'', 'LOT_TY')
				->setCellValue('AN'.($i).'', 'UPD_DT')
				->setCellValue('AO'.($i).'', 'PHOTO_FLG')
				->setCellValue('AP'.($i).'', 'SIGNATURE_FLG')
				->setCellValue('AQ'.($i).'', 'ID_FLG')
				->setCellValue('AR'.($i).'', date('d-m-Y h:i:s A',strtotime($reg_data['createdon'])))
				/*->setCellValue('AS'.($i).'', 'EDITED_DATAS')*/
				->setCellValue('AS'.($i).'', $EDITED_DATAS)
				->setCellValue('AT'.($i).'', $reg_data['editedby'])
				->setCellValue('AU'.($i).'', $reg_data['editedon']);
				
				$i++;
			}
		}
	}
	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle($title);
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client's web browser (Excel2007)
	
	
	//header('Content-type: text/csv');
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');	
	
}


?>