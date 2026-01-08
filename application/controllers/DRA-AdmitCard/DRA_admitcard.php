<?php
try{
	error_reporting(E_ERROR);
	
	$connect=mysqli_connect("localhost","root","","simple_laravel_api");//admitcard DB Name

	if (!$connect) {
	 die('Could not connect to mysqli: ' . mysqli_error());
	}
	
	//$cid =mysqli_select_db('iibf',$connect);
	$csv_file    = "admitCardReport - Malda.csv"; // change csv file name
	$exam_period = '17';// change period given in mail

	if (($handle = fopen($csv_file, "r")) !== FALSE) {
	    fgetcsv($handle);   
	    while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
		   
		   	// echo '<pre>';
		   	// print_r($data); die;
		   
			$num = count($data);
			for ($c=0; $c < $num; $c++) {
			  $col[$c] = $data[$c];
			}
			
			$exm_cd 	=   ($col[15]); 
			$mem_mem_no =   ($col[3]);

			/*if ($exm_cd == 1036) {
				$exam_period = 8;	
			} elseif ($exm_cd == 45) {
				$exam_period = 748;
			} */

			 $center_code =   ($col[0]);  
			 $center_name =   ($col[1]);  
			 $mem_type = ($col[2]);    
			 $g_1 =   ($col[4]); 
			 $mam_nam_1 =   ($col[5]);  
			 $mem_adr_1 =   ($col[6]); 
			 $mem_adr_2 =   ($col[7]); 
			 $mem_adr_3 =   ($col[8]); 
			 $mem_adr_4 =   ($col[9]);  
			 $mem_adr_5 =   ($col[10]);  
			 $mem_adr_6 =   ($col[10]); 
			 $mem_pin_cd =   ($col[12]); 
			 $zo =   ($col[13]); 
			 $state =   ($col[14]); 
			 
			 $sub_cd =   ($col[16]);  
			 $m_1 =   ($col[17]);  
			 $inscd =   ($col[18]); 
			 $insname =   ($col[19]);  
			 $venueid =   ($col[20]);  
			 $venueadd1 =  str_replace("'", '', $col[21]);  
			 $venueadd2 =   ($col[22]);  
			 $venueadd3 =   ($col[23]);  
			 $venueadd4 =   ($col[24]); 
			 $venueadd5 =   ($col[25]); 
			 $venpin =   ($col[26]);  
			 $stat =   ($col[27]);  
			 $pwd =   ($col[28]);  
			 
			 $originalDate =   ($col[29]);
			 $date = date("Y-m-d", strtotime($originalDate));
			  
			 $time =   ($col[30]);  
			 $mode =   ($col[31]); 
			 $seat_identification =   ($col[32]);  
			 
			
			
			
			$query = "INSERT INTO dra_admitcard_info(center_code, mem_type, mem_mem_no, g_1, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_adr_6, mem_pin_cd, zo, state, exm_cd, sub_cd, m_1, inscd, insname, venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin, stat, pwd, date, time, mode, seat_identification, exam_period,center_name) VALUES ('".$center_code."','".$mem_type."','".$mem_mem_no."','".$g_1."','".$mam_nam_1."','".$mem_adr_1."','".$mem_adr_2."','".$mem_adr_3."','".$mem_adr_4."','".$mem_adr_5."','".$mem_adr_6."','".$mem_pin_cd."','".$zo."','".$state."','".$exm_cd."','".$sub_cd."','".$m_1."','".$inscd."','".$insname."','".$venueid."','".$venueadd1."','".$venueadd2."','".$venueadd3."','".$venueadd4."','".$venueadd5."','".$venpin."','".$stat."','".$pwd."','".$date."','".$time."','".$mode."','".$seat_identification."','".$exam_period."','".$center_name."')";
			 			
			/* echo "<br/><br/>";
			 echo $query; 
			 exit;*/

		  $s     = mysqli_query($connect,$query);
		}
		fclose($handle);
	}
	echo "File data successfully imported to database!!";
	
}catch(Exception $e){
	// echo $e;
	print_r($e);
}
//$this->load->view('welcome_message');
	

?>