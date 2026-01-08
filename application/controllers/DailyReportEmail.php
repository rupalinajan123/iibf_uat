<?php 

   /*Controller class DailyReportEmail.
  * @copyright    Copyright (c) 2019 ESDS Software Solution Private.
  * @author       Manoj Mali
  * @package      Controller
  * @last Update  2019-06-24 : Email Update : Manoj
  */
class DailyReportEmail extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->library('email');
        $this->load->model('Master_model'); 
        $this->load->model('Emailsending');
        $this->load->library('excel');
				
				ini_set("memory_limit", "-1");
    } 
    /* daily report Mail */
    public function daily_report()
    { 
       //error_reporting(E_ALL);
	  /* if($_GET['url_date'] != ''){
		   $yesterday = date($_GET['url_date'], strtotime("- 1 day"));	
		}else{
		 $yesterday = date('Y-m-d', strtotime("- 1 day"));	   
		}*/		
		
		//$yesterday ='2022-04-24';
	    $yesterday = date('Y-m-d', strtotime("- 1 day"));		
        $daily_report   = "";
        $parent_dir_flg = 0;
        $success        = array();
        $error          = array();
        $cron_file_dir  = "./uploads/Cron_iibfdra_Dailyreport/";
        $daily_report   = "";
				
				$file1 = "dra_daily_report_cron_logs_" . $yesterday . ".txt";
				$fp1 = fopen($cron_file_dir . '/' . $file1, 'a');
				fwrite($fp1, "\n***** DRA DAILY REPORT Cron Execution Started - " . date("Y-m-d H:i:s") . " ***** \n");
        
				fwrite($fp1, "\n 1 start - " . date("Y-m-d H:i:s") . "  \n");
        /*-NEW SHEET-1*/   
        /*New Agency Registration Details*/
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->setActiveSheetIndex(0);
             
          $objPHPExcel->getActiveSheet()->setTitle("New Agency Registration");
          $table_columns = array("Institute Name", "Institute Head Name", "Institute City","Institute State","Center Name","Center State","Contact Person Name");

          $table_fields = array("inst_name", "inst_head_name", "inst_city","inst_state","center_city","center_state","contact_person_name");
          $column = 0;
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setBold(true);
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setSize(14);
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1, "New Agency Registration Details ");
              foreach($table_columns as $field)
          {
            
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setSize(13);
              
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
            $column++;
          }
          $this->db->select('dra_inst_registration.inst_name,dra_inst_registration.inst_head_name,c2.city_name as inst_city,s2.state_name as inst_state,agency_center.contact_person_name,c1.city_name as center_city,s1.state_name as center_state');
          $this->db->where('agency_center.center_add_status', 'F');
          $this->db->where('agency_center.pay_status ', '1');
          $this->db->join('agency_center', 'agency_center.agency_id = dra_inst_registration.id','LEFT');
          $this->db->join('city_master c1','agency_center.location_name=c1.id','LEFT'); 
          $this->db->join('state_master s1','agency_center.state=s1.state_code','LEFT');  
          $this->db->join('city_master c2','dra_inst_registration.main_city=c2.id','LEFT'); 
          $this->db->join('state_master s2','dra_inst_registration.main_state=s2.state_code','LEFT');
          $this->db->where('DATE(dra_inst_registration.created_on)',$yesterday);
          $agency_info = $this->master_model->getRecords('dra_inst_registration');
         //print_r($agency_info);die;
          // Fetching the table data
          $row = 4;
          foreach($agency_info as $data)
          {
            $col = 0;
              foreach ($table_fields as $field)
              {
                  
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data[$field]);
                  $col++;
              }
              $row++;
          }
					
					fwrite($fp1, "\n 1 end - " . date("Y-m-d H:i:s") . "  \n");
					
					fwrite($fp1, "\n 2 start - " . date("Y-m-d H:i:s") . "  \n");
					
          /*-NEW SHEET-2*/
          /*New Centre Details added by Existing Agency(Variable prefix = EX_ / ex_ )*/
            $objPHPExcel->createSheet();
            
            $objPHPExcel->setActiveSheetIndex(1);

            $objPHPExcel->getActiveSheet()->setTitle("New Centre Added");
            $table_columns1 = array("Institute Name","Contact Person Name","Center Name","Center State","Center Type",);
            $table_fields1 = array("inst_name","contact_person_name","center_city","center_state","center_type");
            $column = 0;

            $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setSize(14);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1, "New Centre Details added by Existing Agency ");
            foreach($table_columns1 as $field)
            {
              $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);
              $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setSize(13);
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
              $column++;
            }
            $this->db->select('c1.city_name as center_city, agency_center.center_type, agency_center.contact_person_name,s1.state_name as center_state, dra_inst_registration.inst_name,c2.city_name as inst_city,s2.state_name as inst_state','LEFT');
            $this->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_center.agency_id','LEFT');
            $this->db->join('city_master c2','dra_inst_registration.main_city=c2.id','LEFT'); 
            $this->db->join('state_master s2','dra_inst_registration.main_state=s2.state_code','LEFT'); 
            $this->db->join('city_master c1','agency_center.location_name=c1.id','LEFT'); 
            $this->db->join('state_master s1','agency_center.state=s1.state_code','LEFT');
            $this->db->where('agency_center.center_add_status', 'E');
            $this->db->where('DATE(agency_center.created_on)',$yesterday);
            $center_info = $this->master_model->getRecords('agency_center');
      
      //echo $this->db->last_query();
      //exit;
            // Fetching the table data
            $row = 4;
            foreach($center_info  as $data)
            {
                $col = 0;
                foreach ($table_fields1 as $field)
                {
                  if ($field == 'center_type' ) {
                    $text = ($data[$field] == 'T' ? 'Temporary' :'Regular');
                  }else{
                     $text = $data[$field];
                  }
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $text);
                    $col++;
                }
                $row++;
            }
          
					fwrite($fp1, "\n 2 end - " . date("Y-m-d H:i:s") . "  \n");
					
					fwrite($fp1, "\n 3 start - " . date("Y-m-d H:i:s") . "  \n");
					
         /*-NEW SHEET-3*/
         /*Centre (regular/temp) Details Updated by the Agency  (Variable prefix = UP_ / up_ )*/
          $objPHPExcel->createSheet();
          $objPHPExcel->setActiveSheetIndex(2);

          $objPHPExcel->getActiveSheet()->setTitle("Centre Updated");
          $table_columns2 = array("Institute Name","Institute City","Institute State","Center Name","Center State","Center Type");
          $table_fields2 = array("inst_name","inst_city","inst_state","center_city","center_state","center_type");
          $column = 0;
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setBold(true);
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setSize(14);
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1, "Centre (regular/temp) Details Updated by the Agency");
          foreach($table_columns2 as $field)
          {
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
            $column++;
          }
          $this->db->select('c1.city_name as center_city, agency_center.center_type, agency_center.contact_person_name,s1.state_name as center_state, dra_inst_registration.inst_name,c2.city_name as inst_city,s2.state_name as inst_state','LEFT');
          $this->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_center.agency_id','LEFT');
          $this->db->join('city_master c2','dra_inst_registration.main_city=c2.id','LEFT'); 
          $this->db->join('state_master s2','dra_inst_registration.main_state=s2.state_code','LEFT'); 
          $this->db->join('city_master c1','agency_center.location_name=c1.id','LEFT'); 
          $this->db->join('state_master s1','agency_center.state=s1.state_code','LEFT');
          $this->db->where('DATE(agency_center.updated_on)',$yesterday);
          $updated_center_info = $this->master_model->getRecords('agency_center');
          // Fetching the table data
          $row = 4;
          foreach($updated_center_info  as $data)
          {
              $col = 0;
              foreach ($table_fields2 as $field)
              {
                if ($field == 'center_type' ) {
                    $text = ($data[$field] == 'T' ? 'Temporary' :'Regular');
                  }else{
                     $text = $data[$field];
                  }
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $text);
                  $col++;
              }
              $row++;
          }
					
					fwrite($fp1, "\n 3 end - " . date("Y-m-d H:i:s") . "  \n");
					
					fwrite($fp1, "\n 4 start - " . date("Y-m-d H:i:s") . "  \n");
					
          /*-NEW SHEET-4*/
          /* Payment Done for Accreditation Centre.*/
          $objPHPExcel->createSheet();
          $objPHPExcel->setActiveSheetIndex(3);

          $objPHPExcel->getActiveSheet()->setTitle("Payment For Centre");
          $table_columns3 = array("Institute Name","Institute City","Institute State","Center Name","Center State","Center Type","Paid Amount");
          $table_fields3 = array("inst_name","inst_city","inst_state","center_city","center_state","center_type","amount");
          $column = 0;
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setBold(true);
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setSize(14);
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1, "Payment Done for Accreditation Centre");
          foreach($table_columns3 as $field)
          {
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
            $column++;
          }
         
          $this->db->select('c1.city_name as center_city, agency_center.center_type,s1.state_name as center_state, dra_inst_registration.inst_name,c2.city_name as inst_city,s2.state_name as inst_state, payment_transaction.amount');
          $this->db->where('payment_transaction.status', '1');
          $this->db->where('payment_transaction.pay_type' , '16');  
          $this->db->where('DATE(payment_transaction.date)',$yesterday);
          $this->db->join('payment_transaction ','agency_center.center_id=payment_transaction.ref_id ','LEFT');
          $this->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_center.agency_id','LEFT');
          $this->db->join('city_master c2','dra_inst_registration.main_city=c2.id','LEFT'); 
          $this->db->join('state_master s2','dra_inst_registration.main_state=s2.state_code','LEFT'); 
          $this->db->join('city_master c1','agency_center.location_name=c1.id','LEFT'); 
          $this->db->join('state_master s1','agency_center.state=s1.state_code','LEFT');
          $this->db->where('agency_center.pay_status', '1'); 
          $this->db->where('agency_center.center_add_status ', 'E');
          $accreditation_center = $this->master_model->getRecords('agency_center');

          // Fetching the table data
          $row = 4;
          foreach($accreditation_center  as $data)
          {
              $col = 0;
              foreach ($table_fields3 as $field)
              {
                if ($field == 'center_type' ) {
                    $text = ($data[$field] == 'T' ? 'Temporary' :'Regular');
                  }else{
                     $text = $data[$field];
                  }
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $text);
                  $col++;
              }
              $row++;
          }
					
					fwrite($fp1, "\n 4 end - " . date("Y-m-d H:i:s") . "  \n");
					fwrite($fp1, "\n 5 start - " . date("Y-m-d H:i:s") . "  \n");
					
          /*-NEW SHEET-5*/
          /* New Batch Created by Agency  (Variable prefix = BATCH_ / batch_ )*/
          $objPHPExcel->createSheet();
          $objPHPExcel->setActiveSheetIndex(4);

          $objPHPExcel->getActiveSheet()->setTitle("New Batch Created");
          $table_columns4 = array("Institute Name","Institute City","Institute State","Center Name","Center State","Center Type","Batch Name","Batch Code","From Date","To Date");
          $table_fields4 = array("inst_name","inst_city","inst_state","center_city","center_state","center_type","batch_name","batch_code","batch_from_date","batch_to_date");
          $column = 0;
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setBold(true);
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setSize(14);
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1, "New Batch Created by Agency");
          foreach($table_columns4 as $field)
          {
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
            $column++;
          }

          $this->db->select('dra_inst_registration.inst_name,c2.city_name as inst_city,s2.state_name as inst_state,c1.city_name as center_city,s1.state_name as center_state, agency_batch.batch_name, agency_batch.batch_code, agency_batch.batch_from_date, agency_batch.batch_to_date,agency_center.center_type');
          $this->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_batch.agency_id','LEFT');
          $this->db->join('city_master c2','dra_inst_registration.main_city=c2.id','LEFT'); 
          $this->db->join('state_master s2','dra_inst_registration.main_state=s2.state_code','LEFT'); 
          $this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id','LEFT');
          $this->db->join('city_master c1','agency_center.location_name=c1.id','LEFT'); 
          $this->db->join('state_master s1','agency_center.state=s1.state_code','LEFT');
		  //$this->db->where('(agency_batch.batch_code)','BTCH1221996');
          $this->db->where('DATE(agency_batch.created_on)',$yesterday);

          $batch_info = $this->master_model->getRecords('agency_batch');
		  
		  //echo $this->db->last_query(); die;
          // Fetching the table data
          $row = 4;
          foreach($batch_info  as $data)
          {
              $col = 0;
              foreach ($table_fields4 as $field)
              {
                if ($field == 'center_type' ) {
                    $text = ($data[$field] == 'T' ? 'Temporary' :'Regular');
                  }else{
                     $text = $data[$field];
                  }
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $text);
                  $col++;
              }
              $row++;
          }
					
					fwrite($fp1, "\n 5 end - " . date("Y-m-d H:i:s") . "  \n");
					fwrite($fp1, "\n 6 start - " . date("Y-m-d H:i:s") . "  \n");
					
          /*-NEW SHEET-6*/
          /*Batch Details Updated by the Agency (Variable prefix = UP_BATCH_ / up_batch_ )*/
          $objPHPExcel->createSheet();
          $objPHPExcel->setActiveSheetIndex(5);

          $objPHPExcel->getActiveSheet()->setTitle("Batch Updated");
          $table_columns5 = array("Institute Name","Institute City","Institute State","Center Name","Center State","Batch Name","Batch Code","From Date","To Date","Comments");
          $table_fields5 = array("inst_name","inst_city","inst_state","center_city","center_state","batch_name","batch_code","batch_from_date","batch_to_date","remarks");
          $column = 0;
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setBold(true);
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setSize(14);
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1, "Batch Details Updated by the Agency");
          foreach($table_columns5 as $field)
          {
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
            $column++;
          }  
		  
		$res_data = array();
		$b_code = array();
		$bath_codes = array();
		$batch_code_arr = array();
		$res_data = array();
		
		$this->db->select("dra_userlogs.description");
		$this->db->like('dra_userlogs.title',"Edit Agency Batch Successfully");
		$this->db->where('DATE(dra_userlogs.date)',$yesterday);	
		$result = $this->master_model->getRecords("dra_userlogs");	
					
		if(count($result))
		{			
			foreach($result as $row)
			{			   
				$res_data[] = unserialize($row['description']);				
			}
		
			foreach($res_data as $k=>$v)
			{
			    $b_code[] = $v['old_data']['batch_code'];			    
			}
		
			$bath_codes = array_unique($b_code);			
			$batch_code_arr = array_values($bath_codes);
			
          /*$this->db->select('dra_inst_registration.inst_name,c2.city_name as inst_city,s2.state_name as inst_state,c1.city_name as center_city,s1.state_name as center_state, agency_batch.batch_name, agency_batch.batch_code, agency_batch.batch_from_date, agency_batch.batch_to_date,agency_center.center_type');
          $this->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_batch.agency_id','LEFT');
          $this->db->join('city_master c2','dra_inst_registration.main_city=c2.id','LEFT'); 
          $this->db->join('state_master s2','dra_inst_registration.main_state=s2.state_code','LEFT'); 
          $this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id','LEFT');
          $this->db->join('city_master c1','agency_center.location_name=c1.id','LEFT'); 
          $this->db->join('state_master s1','agency_center.state=s1.state_code','LEFT');
          $this->db->where('DATE(agency_batch.updated_on)',$yesterday);*/
		  
		  
		    $this->db->select('dra_inst_registration.inst_name,c2.city_name as inst_city,s2.state_name as inst_state,c1.city_name as center_city,s1.state_name as center_state, agency_batch.batch_name, agency_batch.batch_code, agency_batch.batch_from_date, agency_batch.batch_to_date,agency_center.center_type,agency_batch.remarks');
			$this->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_batch.agency_id','INNER');
			$this->db->join('city_master c2','dra_inst_registration.main_city=c2.id','LEFT'); 
			$this->db->join('state_master s2','dra_inst_registration.main_state=s2.state_code','LEFT'); 
			$this->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id','LEFT');
			$this->db->join('city_master c1','agency_center.location_name=c1.id','LEFT'); 
			$this->db->join('state_master s1','agency_center.state=s1.state_code','LEFT');
			$this->db->where_in('agency_batch.batch_code',$batch_code_arr);
		  
           $updated_batch_info = $this->master_model->getRecords('agency_batch');
           // Fetching the table data
           $row = 4;
			  foreach($updated_batch_info  as $data)
			  {
				  $col = 0;
				  foreach ($table_fields5 as $field)
				  {
					if ($field == 'center_type' ) {
						$text = ($data[$field] == 'T' ? 'Temporary' :'Regular');
					  }else{
						 $text = $data[$field];
					  }
					  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $text);
					  $col++;
				  }
				  $row++;
			  }		  
		   }
		   
			 fwrite($fp1, "\n 6 end - " . date("Y-m-d H:i:s") . "  \n");
			 fwrite($fp1, "\n 7 start - " . date("Y-m-d H:i:s") . "  \n");
		   
		   //---------------------NEW CODE ADDED FRO RENEW-----on 15-july-2019------------------
		   
		     /*-NEW SHEET-7*/
          /* Payment Done for Accreditation Centre.*/
          $objPHPExcel->createSheet();
          $objPHPExcel->setActiveSheetIndex(6);

          $objPHPExcel->getActiveSheet()->setTitle("Payment For Renew Centre");
          $table_columns4 = array("Institute Name","Institute Code","Institute City","Institute State","Center Name","Center State","Center Type","Paid Amount");
          $table_fields4 = array("institute_name","institute_code","main_city","state_name","location_name","statename","center_type","amount");
          $column = 0;
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setBold(true);
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setSize(14);
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1, "Payment Done for Renew Centre");
          foreach($table_columns4 as $field)
          {
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
            $column++;
          }
          
		  $sql =  "SELECT `payment_transaction`.`id`,`exam_invoice`.`invoice_image`,state_master.state_name,dra_accerdited_master.institute_code,dra_accerdited_master.institute_name,dra_accerdited_master.address6,dra_inst_registration.main_city,`payment_transaction`.* FROM `payment_transaction` 
INNER JOIN `exam_invoice` ON `exam_invoice`.`pay_txn_id`=`payment_transaction`.`id` 
INNER JOIN `agency_center_renew` ON `agency_center_renew`.`agency_renew_id`=`payment_transaction`.`ref_id` 
LEFT JOIN `dra_accerdited_master` ON `agency_center_renew`.`agency_id`=`dra_accerdited_master`.`dra_inst_registration_id` 
LEFT JOIN `dra_inst_registration` ON `dra_inst_registration`.`id`= `agency_center_renew`.`agency_id`
LEFT JOIN `state_master` ON `dra_accerdited_master`.`ste_code`= `state_master`.`state_code` 
WHERE `exam_invoice`.`invoice_image` != '' AND `exam_invoice`.`app_type` = 'W' AND `payment_transaction`.`pay_type` = 17 AND DATE(exam_invoice.date_of_invoice) = '".$yesterday."' AND agency_center_renew.pay_status='1'";

			$query = $this->db->query($sql);						
			$renew_transaction_res = $query->result_array();		
			
 			//print_r($renew_transaction_res);	
          // Fetching the table data
          $row = 4;
		   if(count($renew_transaction_res)>0){
          foreach($renew_transaction_res  as $data)
          {
             
			$sql_renew_detail = " SELECT a.agency_renew_id , a.centers_id,a.center_type, GROUP_CONCAT(c.location_name ORDER BY c.center_id) mylocation, GROUP_CONCAT(cm.city_name ORDER BY cm.id) cityname , GROUP_CONCAT(cm.state_name ORDER BY cm.id) statename FROM agency_center_renew a LEFT JOIN agency_center c ON FIND_IN_SET(c.center_id, a.centers_id) > 0 LEFT JOIN city_master as cm ON FIND_IN_SET(cm.id, c.location_name) > 0 WHERE a.agency_renew_id= '".$data['ref_id']."' GROUP BY a.agency_renew_id ";
			$query_res = $this->db->query($sql_renew_detail);						
			$agency_center_renew_res = $query_res->result_array();
			  
			  //print_r($agency_center_renew_res);
			  if(count($agency_center_renew_res)){
				  
			  $data['location_name'] 	= $agency_center_renew_res[0]['cityname'];
			  $data['statename'] 		= $agency_center_renew_res[0]['statename'];
			  $data['center_type'] 		= $agency_center_renew_res[0]['center_type'];	
			   
			  }else{
				  
			  $data['location_name'] 	= '';
			  $data['statename'] 		= '';
			  $data['center_type'] 		= '';
				  
			  }
			  
			  $col = 0;
              foreach ($table_fields4 as $field)
              {
                //center_type
				
				
				if ($field == 'center_type' ) {
                    $text = ($data[$field] == 'T' ? 'Temporary' :'Regular');
                  }else{
                     $text = $data[$field];
                  }
				  
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $text);
                  $col++;
              }
              $row++;
          }
		  
		 }
		 
			
			fwrite($fp1, "\n 7 end - " . date("Y-m-d H:i:s") . "  \n");
			fwrite($fp1, "\n 8 start - " . date("Y-m-d H:i:s") . "  \n");
			
		  /*-NEW SHEET-8*/
          /* Payment Done for Accreditation Centre.*/
          $objPHPExcel->createSheet();
          $objPHPExcel->setActiveSheetIndex(7);

          $objPHPExcel->getActiveSheet()->setTitle("Renewal Request");
          $table_columns4 = array("Institute Name","Institute Code","Institute City","Institute State","Center Name","Center State","Center Type","Pay Status","Renew Type");
          $table_fields4 = array("institute_name","institute_code","main_city","state_name","location_name","statename","center_type","pay_status","renew_type");
          $column = 0;
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setBold(true);
          $objPHPExcel->getActiveSheet()->getStyle(0)->getFont()->setSize(14);
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1, "Renewal Request");
          foreach($table_columns4 as $field)
          {
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle(3)->getFont()->setSize(13);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
            $column++;
          }
       
		  $sql =  "SELECT state_master.state_name,dra_accerdited_master.institute_code,dra_accerdited_master.institute_name,dra_accerdited_master.address6,dra_inst_registration.main_city,agency_center_renew.* FROM `agency_center_renew`
LEFT JOIN `dra_accerdited_master` ON `agency_center_renew`.`agency_id`=`dra_accerdited_master`.`dra_inst_registration_id` 
LEFT JOIN `dra_inst_registration` ON `dra_inst_registration`.`id`= `agency_center_renew`.`agency_id`
LEFT JOIN `state_master` ON `dra_accerdited_master`.`ste_code`= `state_master`.`state_code` WHERE DATE(agency_center_renew.created_on) = '".$yesterday."'";

			$query = $this->db->query($sql);						
			$renew_transaction_res = $query->result_array();		
			
 			//print_r($renew_transaction_res);	
          // Fetching the table data
          $row = 4;
		   if(count($renew_transaction_res)>0){
          foreach($renew_transaction_res  as $data)
          {
			
			$sql_renew_detail = "SELECT a.agency_renew_id , a.centers_id,a.center_type, GROUP_CONCAT(c.location_name ORDER BY c.center_id) mylocation, GROUP_CONCAT(cm.city_name ORDER BY cm.id) cityname , GROUP_CONCAT(cm.state_name ORDER BY cm.id) statename FROM agency_center_renew a LEFT JOIN agency_center c ON FIND_IN_SET(c.center_id, a.centers_id) > 0 LEFT JOIN city_master as cm ON FIND_IN_SET(cm.id, c.location_name) > 0 WHERE a.agency_renew_id= '".$data['agency_renew_id']."' GROUP BY a.agency_renew_id ";
			$query_res = $this->db->query($sql_renew_detail);						
			$agency_center_renew_res = $query_res->result_array();
			  
			  //print_r($agency_center_renew_res);
			  if(count($agency_center_renew_res)){
				  
			  $data['location_name'] 	= $agency_center_renew_res[0]['cityname'];
			  $data['statename'] 		= $agency_center_renew_res[0]['statename'];
			  $data['center_type'] 		= $agency_center_renew_res[0]['center_type'];	
			   
			  }else{
				  
			  $data['location_name'] 	= '';
			  $data['statename'] 		= '';
			  $data['center_type'] 		= '';
				  
			  }
			  
			  $col = 0;
              foreach ($table_fields4 as $field)
              {
                //center_type
				
				
				if ($field == 'center_type' ) {
                    $text = ($data[$field] == 'T' ? 'Temporary' :'Regular');
                  }else if ($field == 'pay_status') {
                    $text = ($data[$field] == '1' ? 'Success' :'Pending');
                  }else{
                    $text = $data[$field];
                  }  
				  
                  $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $text);
                  $col++;
              }
              $row++;
          }
		 }
		   //-------------------------------------------------------------------------------
		  fwrite($fp1, "\n 8 end - " . date("Y-m-d H:i:s") . "  \n");
          
          $object_writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

          if (!file_exists($cron_file_dir . $yesterday)) 
          {
              $parent_dir_flg = mkdir($cron_file_dir . $yesterday, 0700);
          }
          if (file_exists($cron_file_dir . $yesterday)) 
          {
              $cron_file_path = $cron_file_dir . $yesterday; // Path with YESTERDAY DATE DIRECTORY
              $file           = "DailyReport_" . $yesterday . ".xlsx";
            
          }

          $attachpath = $cron_file_path.'/'.$file; 
          $objWriter  = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
          $objWriter->save($attachpath);
          //$objWriter->save('excel-files/' . $file);
          $date_format = date("d-m-Y", strtotime($yesterday));
        
          $message = "Dear Admin, <br>  Please go through the following details Daily Activities of ".$date_format." conducted by Agencies.<br>
          Please find the attachment <br><br> Yours truly,<br>IIBF Team";

         /* $info_arr   = array('to'=>'esdstesting12@gmail.com','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF DRA Daily Report of '. $date_format,'message'=>$message);
          $this->Emailsending->mailsend_attch($info_arr,$attachpath);
		  
					$info_arr2   = array('to'=>'esdstesting14@gmail.com','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF DRA Daily Report of '. $date_format,'message'=>$message);
          $this->Emailsending->mailsend_attch($info_arr2,$attachpath);*/
         
					//COMMENTED BY SAGAR ON 29-09-2020
          /* $info_arr   = array('to'=>'iibfdevp@esds.co.in','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF DRA Daily Report of '. $date_format,'message'=>$message);
          $this->Emailsending->mailsend_attch($info_arr,$attachpath); */
         
          // $info_arr1   = array('to'=>'soumya@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF DRA Daily Report of '. $yesterday,'message'=>$message); 
          // $this->Emailsending->mailsend_attch($info_arr1,$attachpath);
             
					 //COMMENTED BY SAGAR ON 29-09-2020
          /* $info_arr2   = array('to'=>'lathasekhar@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF DRA Daily Report of '. $yesterday,'message'=>$message);
          $this->Emailsending->mailsend_attch($info_arr2,$attachpath);
          
          $info_arr3   = array('to'=>'kalpanashetty@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF DRA Daily Report of '. $yesterday,'message'=>$message);
          $this->Emailsending->mailsend_attch($info_arr3,$attachpath);
		  
					$info_arr4   = array('to'=>'dharmvirm@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF DRA Daily Report of '. $yesterday,'message'=>$message);
          $this->Emailsending->mailsend_attch($info_arr4,$attachpath);

          $info_arr5   = array('to'=>'kavan@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF DRA Daily Report of '. $yesterday,'message'=>$message);
          $this->Emailsending->mailsend_attch($info_arr5,$attachpath);

          $info_arr6   = array('to'=>'balasalian@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','subject'=>'IIBF DRA Daily Report of '. $yesterday,'message'=>$message);
          $this->Emailsending->mailsend_attch($info_arr6,$attachpath); */

					//added BY SAGAR ON 29-09-2020
					$info_arr = array('to'=>'dracell@iibf.org.in', 'from'=>'logs@iibf.esdsconnect.com', 'cc'=>'add.dir@iibf.org.in,jd.exm2@iibf.org.in,iibfdevp@esds.co.in', 'subject'=>'IIBF DRA Daily Report of '. $yesterday,'message'=>$message);  

            
          $this->Emailsending->mailsend_attch_cc($info_arr,$attachpath);          
					
					fwrite($fp1, "\n***** DRA DAILY REPORT Cron Execution Ended - " . date("Y-m-d H:i:s") . " ***** \n");
    }
		
		function test_mail()
		{
			$sub = 'IIBF Email Check '.date('d-m-Y H:i:s');
			$info_arr = array('to'=>'pra.kot15@gmail.com,sagar.matale@esds.co.in', 'from'=>'logs@iibf.esdsconnect.com', 'subject'=>$sub,'message'=>'IIBF Email Check : '.$this->get_ip_address());   
			$this->Emailsending->mailsend_attch_cc($info_arr);
			
			echo '<pre>'; print_r($info_arr); echo '</pre>';
		}
		
		function get_ip_address() // GET IP ADDRESS
		{
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