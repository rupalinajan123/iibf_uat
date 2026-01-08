<?php
/********************************************************************
* Created BY: Sagar Matale
* Update By : Sagar Matale On 14-09-2021
* Update By : Sagar Matale On 12-11-2021
* Description: This is automation cron for sending data to NSEIT VENDOR. 
* Previous cron file :  controllers/admin/Cron_csv.php : exam_csv_NSEIT
* Exam codes : 1002, 1010, 1011, 1012, 1013, 1014, 1019, 1020, 2027
********************************************************************/

	defined('BASEPATH') OR exit('No direct script access allowed');
	/* header("Access-Control-Allow-Origin: *"); */
	
	class Cron_nseit_vendor_automation_custom extends CI_Controller
	{    
		public function __construct()
		{ 
			parent::__construct();
			$this->load->library('upload');
			$this->load->model('Master_model');
			$this->load->model('log_model'); 
			
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
			ini_set('display_errors', 1);
			ini_set("memory_limit", "-1");
		}
		
		public function index()
		{
			$this->load->model('Image_search_model');
			ini_set("memory_limit", "-1");
			$dir_flg = $parent_dir_flg = $exam_file_flg  = 0;
			$success = $error = array();
			$start_time = date("Y-m-d H:i:s");
			$current_date = date("Ymd"); //'20211111'; //
			$cron_file_dir = "./uploads/rahultest/"; 
			$result = array("success" => "", "error" => "", "Start Time" => $start_time, "End Time" => "");
			$desc = json_encode($result);
			//$this->log_model->cronlog("NSEIT CSV Cron Execution Start", $desc);
			if (!file_exists($cron_file_dir . $current_date)) { $parent_dir_flg = mkdir($cron_file_dir . $current_date, 0700); }
			if (file_exists($cron_file_dir . $current_date)) 
			{
				$cron_file_path = $cron_file_dir . $current_date; // Path with CURRENT DATE DIRECTORY
				
				$file1 = "Automation_nseit_vendor_cron_logs_" . $current_date . ".txt";				
				$fp1 = fopen($cron_file_path . '/' . $file1, 'a');
				fwrite($fp1, "\n***** NSEIT CSV Cron Execution Started - " . $start_time . " ***** \n");
				
				$file2 = "Automation_nseit_vendor_member_img_logs_" . $current_date . ".txt";
				$member_img_log = fopen($cron_file_path.'/'.$file2,'a');
				
				//$yesterday = '2022-04-25';//date('Y-m-d', strtotime("- 1 day")); //
				
				$exam_code = array('1002','1010','1011','1012','1013','1014','1019','1020','2027'); // Send Free and Paid Both Applications		 		
				$regnumberArr = array(510576463,510255666,801654598,510461276,510389554,510552356,510324320,510345745,510359816,500189857,500086373,510336618,510575758,510114293,500211524,510200352,510499016,510140672,510249186,510509455,510228577,510575027,500008095,510501675,510559202,510455550,801679373,510482639,510550577,510120379,510484648,510415336,510411989,510198993,500003702,510170429,510104512,510012208,500196644,500058085,510535631,510358866,510205740,510323820,500092447,510251904,510326484,510121753,510085971,510306046,510285850,510568618,510436474,510533345,510159997,510316562,510302086,802182320,510228227,802133494,802182294,500013104,510279572,500181845,510562506,500155346,500050872,510452305,500021294,510491904,500168645,510094186,510531994,510101875,802182325,510114339,802107223,510397948,510136082,100031350,510407982,510571240,510571967,802182328,510126077,500202088,510280073,510279552,510435045,802182344,510422093,510290232,510575717,500052992,802182349,500121247,500131571,510556216,802182351,802182352,510575397,802182355,510157410,801746534,510128281,500011921,510576547,801470931,510576557,510576561,510456144,801814846,510093663,510095148,510272654,510413033,510321159,510120122,500170544,510165039,510554263,500073726,510552838,510474922,510576182,510114956,500158492,510412261,510168276,802128746,510097558,510494077,510551095,802182392,510305981,500017587,500107795,802182394,510400898,200051373,510571753,510553119,510348081,801963496,802182401,510440125,510156513,802182403,845051215,510253698,510232668,510228479,510517672,510472665,510052574,510410568,510411283,510412801,510575872,510560132,802182406,510185628,510224857,510566873,510275363,801324182,510342344,500054042,510334696,510313470,510417129,510576450,500142736,510166538,802060531,510329461,510256121,510451591,510551291,510392088,510099114,510332880,510361519,510455349,510575644,510143735,510146531,510362410,510061828,510496524,500149077,510373507,510211131,510575547,510198128,510575629,802182426,510241662,500093487,510110647,400018571,500176930,510246109,510411646,510558749,510456393,510408579,510042230,7000998,801426292,510563217,510345978,510576613,510438339,510066487,510389889,510417715,510406397,510312420,510554157,510296473,500098764,510374265,510181100,500167821,510434608,510375039,510270325,510134605,510163484,510533303,510125365,510184883,510220322,802182443,801176668,510553277,510552084,500212930,802060254,510468836,510528025,510474705,801455294,510041603,510300961,510359778,802182447,500015426,510278089,500106469,510172565,510172806,510278751,802182457,802182459,802182462,802182463,510306478,802140203,510554138,510354246,510281449,510575605,802068847,500048907,510551038,510491255,802182468,801213440,510576148,510234943,510257431,510569149,510287609,510488001,510551227,510554411,510116023,510542373,802182474,510245233,510254373,510550605,510101901,510236260,510467568,510482280,510287476,500092822,510336714,510496873,510511496,510357954,510089875,510196804,500150845,510505597,510278151,510098225,510561715,510559230,510482112,500126010,510342995,510130427,510312525,500193588,510557140,510559904,510445099,510546128,400006954,510310256,510156061,510523806,802182496,510217970,510558630,510047181,510386397,510330289,510165091,510371416,510394213,510410313,510575235,510576312,510479895,510539418,510107158,510476335,7565354,510283642,510177020,510237875,802133581,510384163,510575305,510315317,510098330,801982664,801813479,801880789,500120581,510341348,510495006,510189769,510476661,510509355,510352647,500114760,510533114,510438315,801423875,510081732,802060517,510372652,802182576,500132548,500212248,510507520,510167810,510196775,510111504,500062943,500008810,510356116,510441921,510120540,510045362,802182606,510238511,500143476,510575428,510575584,510323164,802182622,510548268,500175787,801257031,510282907,500181559,510144884,801792114,510456546,500134989,510337336,510194680,510211042,6934604,510458420,500180574,510038570,510038191,500021015,802182713,510072381,510576630,500036298,801962586,510190639,510301748,802182738,510576381,510100368,510064446,510172667,510466083,7436188,802182770,510361435,510576631,510138533,510468397,100090234,500067545,510126725,802182799,500193370,801256087,510299225,510417033,510138095,510356673,802177110,510340744,510424219,510421159,510439362,510045368,510195663,500086724,510562168,510366738,510037017,510021377,510009135,510121765,510082400,510440156,802068870,500170963,510438357,500185737,510051922,510442975,510450219,510285230,510543196,510171763,500110862,400046889,510318859,510569547,510409753,802162478,510225212,510372374,510285787,510477749,510387832,510319544,500089000,510459069,510033510,510551893,510460759,510339868,510460617,500167432,510551122,802183450,510484423,510171456,510395383,500110989,510116401,510566337,802183471,510226439,500012817,510552035,510384423,510530609,510536963,510205385,510575793,510509402,510014920,510059908,510391729,510522838,510510164,500176902,500197076,510172542,510550634,510415192,510231164,510430786,510266562,510118806,510530954,510169924,510372909,510391866,510291430,510395262,510274229,802183556,510455944,510416502,510501263,801458562,510571112,510371419,510575913,510458585,510173460,500196312,510263304,510576628,510484560,510567736,510258205,500142321,510494926,510454327,500179872,510470990,500043753,500147444,510379691,510289607,510324695,510339169,510459061,500182014,510185083,510531274,510365317,510531235,510033933,510085969,510046623,510357278,510545505,510284990,510250715,500207930,510325200,510284251,510500241,802183610,510172896,510118508,510498233,510242286,510551308,510576349,510148552,510153850,802183618,510572908,510353028,510388102,510576644,510576305,802139670,510197363,510081842,802183627,510208814,510575640,500158391,510451177,510527304,510468404,510501510,510478959,500118522,510139526,500013068,510024711,510373061,500186580,510079007,510576614,510037814,801429708,510550906,510240526,510312704,510565080,510576658,510299789,510512152,510462938,510552191,510505778,500014924,510124036,510391016,510489690,510409116,510474503,510062137,510359316,510409050,510497868,510298825,510106078,510388912,510476041,510427563,510367277,510264525,510515164,510371930,510573501,300042976,510307509,510234475,500214284,802183681,500145421,510550750,802139745,510552694,510497906,510576549,510466046,510548777,510283546,500090816,510156620,510322343,500135417,510317067,510576663,200025159,510262453,500008125,510576114,510076195,802183731,500096808,510328755,510576643,100052369,510266471,510289741,510498393,510312022,510457749,510291863,510576674,510437732,510576669,510184602,510411193,510477230,510107477,802183822,510119282,510406700,510458787,510557592,510534147,510571758,510381224,500121825,510311265,500123222,801963049,510576577,510118577,510313690,801370735,510387682,510289845,510222696,510404067,802183883,510378419,510474889,510112773,510310422,510290426,510030874,510112678,500000907,500044590,510418696,510576683,510551177,300042018,400016511,510467295,7510024,510266871,802183978,510558446,510161950,510392781,510363530,500213677,510555539,510484765,510100764,510056318,510310555,510455821,510557232,802184077,510250509,510407257,510364053,802184081,510349822,510013531,510346958,510322339,500093726,510229486,802184104,510446593,510397988,500184693,510148197,510496257,510550845,510571062,510120595,510568877,510174762,510553688,510420880,510164815,510136997,510135608,500047529,510576696,510575923,510499839,510575009,510506072,510074823,510394113,510242017,802184156,510439073,510553444,510403461,510524024,510211730,510151173,510536014,510510635,510113893,510170150,510576616,510078010,500128410,510225060,510347614,500033937,510356999,510454655,510241015,500056971,510253402,510195694,510014470,510350841,510322188,510507519,510479248,510386771,510286678,510325843,510410307,510154076,500195833,510397277,510317903,510576709,802184258,510166155,500210900,510482507,500207965,802184308,510162299,510562174,510137344,510296573,510095784,510188101,510573550,802184351,510576715,510530445,510501561,100031636,802184360,510345708,510127155,510121570,500142367,500114481,500188940,510565672,510131063,510389418,510120676,500063614,500196741,801456156,510002700,510136273,510515387,510169625,510434309,510355653,802184533,510538473,510380294,510319090,510530014,510376523,510270007,510518807,802079278,510061822,510122972,510412230,510500566,500202811,802107629,510292982,510429940,510384216,510439101,510504877,510557251,510333938,510576409,802184628,510152837,510451497,510197226,510413900,510492577,510551705,802184640,510401771,510341985,510560697,510460675,510430170,510227671,510536608,400142651,510226809,510271812,7173926,802184658,510576745,510038095,510349086,802184662,510330870,500152379,510360818,510413780,510479818,510576732,500192733,510563537,510168647,510483101,500016431,510491562,802184705,510425211,500145635,510163339,801177080,500045076,510030411,510179014,510097375,510460391,510002405,510397411,510105832,510345549,510348892,510282423,510414636,510245845,802184838,510358640,510479783,510576113,510486487,510550687,510296706,510174667,802184911,510141082,802184942,802130991,510068278,510182589,510180843,510548598,510024085,510269099,510172288,100097057,510287409,510524527,510170392,510245450,510355141,510458507,510220381,802185073,510386230,510463934,510463940,510554092,510194721,500181370,510509394,510196100,510545049,510394029,500142187,802185107,510323288,510551256,802185109,510551338,510468000,510560022,510363286,510346417,510187842,510392308,500183821,510118108,510433868,802185125,510451286,510455856,510065606,500052361,510383768,500147278,510156797,510576629,802185151,510113223,510005055,510155799,510140746,510317941,510266913,510506796,500169419,6729194,802185250,802083744,500166855,802185287,510338326,510359853,802185692,801765202,510453789,510319532,510114674,510425938,500171295,801430674,500186885,510349172,510293073,802186363,510576802,500055656,510556256,510554934,500056371,510124904,510223907,500098436,802186455,510513726,510576784,510048750,801881014,510152283,510334809,802186477,500125630,510559686,510195745,510193858,500152861,500034836,802186497,802186501,510337512,510478259,802009301,510341173,510379489,510086248,510486778,510012799,510379769,510391053,500198983,510481462,500000995,510556730,510468895,500172502,510274168,510465614,510432014,510277136,802186550,510416757,510546778,510001858,510545144,802186588,510515716,510419164,500155632,510421590,510402054,510444327,500106629,510405155,510302554,510271271,510065488,510228136,510397104,200069289);
				$this->db->where_in('a.regnumber', $regnumberArr); 
				$select = 'a.exam_code,c.regid, c.firstname,c.middlename,c.lastname, c.image_path, c.reg_no, c.regnumber, c.scannedphoto, c.idproofphoto, c.scannedsignaturephoto, a.regnumber,c.dateofbirth,c.gender,c.email,c.stdcode,c.office_phone,c.mobile,c.address1,c.address2,c.address3,c.address4,c.district,c.city,c.state,c.pincode,c.associatedinstitute,c.designation,a.exam_period,a.elected_sub_code,c.editedon,a.examination_date,a.id AS mem_exam_id,a.created_on AS registration_date,a.exam_medium,a.exam_center_code,d.venueid,d.pwd,d.exam_date,d.time';
				$this->db->join('member_registration c', 'a.regnumber=c.regnumber', 'LEFT');
				$this->db->join('admit_card_details d', 'a.id=d.mem_exam_id', 'LEFT');
				$this->db->where_in('a.exam_code', $exam_code);
				$can_exam_data = $this->Master_model->getRecords('member_exam a', array(
				'remark' => 1,
				'isactive' => '1',
				'isdeleted' => 0,
				'pay_status' => 1,
				//'DATE(a.created_on)' => $yesterday,					
				'd.exam_date >=' => '2023-02-11', 
				), $select,'','' 
				);				/* ,'1'  */
				echo $this->db->last_query(); //exit;
								
				//echo "<pre>"; print_r($can_exam_data); echo "</pre>";//exit;
				$exam_cnt = 0;
				$api_data_arr = array();
				if (count($can_exam_data)) 
				{
					$i = 1;					
					foreach ($can_exam_data as $exam) 
					{
						$firstname = $middlename = $lastname = $city = $state = $pincode = $dateofbirth = $address = $gender = $exam_name = $registration_date = $data = $syllabus_code = $subject_description = $subject_code = $subject_description = $institution_name = $designation_name = $exam_code = $exam_name = $medium_name = $server_url = $scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
						
						//ADDED BY SAGAR ON 27-06-2020 FOR MEMBER IMAGES          
						$member_images = $this->get_member_images($exam['image_path'], $exam['reg_no'], $exam['regnumber'], $exam['scannedphoto'], $exam['idproofphoto'], $exam['scannedsignaturephoto'], $exam['regid'], $yesterday);
						$scannedphoto = $member_images['scannedphoto'];
						$scannedsignaturephoto = $member_images['scannedsignaturephoto'];
						$idproofphoto = $member_images['idproofphoto'];
						
						if($scannedphoto == "") { fwrite($member_img_log, "Photo missing - " . $exam['regnumber'] . " \n"); }
						if($scannedsignaturephoto == "") { fwrite($member_img_log, "Signature missing - " . $exam['regnumber'] . " \n"); }
						if($idproofphoto == "") { fwrite($member_img_log, "ID Proof missing - " . $exam['regnumber'] . " \n"); }            
						if($scannedphoto == "" || $scannedsignaturephoto == "" || $idproofphoto == "") { fwrite($member_img_log, "\n"); }
						
						if ($exam['exam_code'] != '' && $exam['exam_code'] != 0) 
						{
							$ex_code = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exam['exam_code']));
							if(count($ex_code)) 
							{
								if($ex_code[0]['original_val'] != '' && $ex_code[0]['original_val'] != 0) 
								{
									$exam_code = $ex_code[0]['original_val'];
								} 
								else 
								{
									$exam_code = $exam['exam_code'];
								}
							}
							else 
							{
								$exam_code = $exam['exam_code'];
							}
						} 
						else 
						{
							$exam_code = $exam['exam_code'];
						}
						
						if($exam_code == '2027') { $exam_code = '1017'; }
						
						$dateofbirth = date('d-m-Y', strtotime($exam['dateofbirth']));
						$registration_date = date('d-m-Y', strtotime($exam['registration_date']));
						
						$address = $exam['address1'] . ' ' . $exam['address2'] . ' ' . $exam['address3'] . ' ' . $exam['address4'] . ' ' . $exam['district'] . ' ' . $exam['city'];
						$address = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $address);
						$gender = $exam['gender'];
						if ($gender == 'male' || $gender == 'Male') { $gender = 'M'; } elseif($gender == 'female' || $gender == 'Female') { $gender = 'F'; }
						$designation = $this->master_model->getRecords('designation_master');
						if (count($designation)) 
						{
							foreach ($designation as $designation_row) 
							{
								if ($exam['designation'] == $designation_row['dcode']) 
								{
									$designation_name = $designation_row['dname'];
								}
							}
						}
						$designation_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $designation_name);
						
						$medium = $this->master_model->getRecords('medium_master');
						if(count($medium)) 
						{
							foreach ($medium as $medium_row) 
							{
								if ($exam['exam_medium'] == $medium_row['medium_code']) 
								{
									$medium_name = $medium_row['medium_description'];
								}
							}
						}
						$medium_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $medium_name);
						
						$institution_master = $this->master_model->getRecords('institution_master');
						if(count($institution_master)) 
						{
							foreach ($institution_master as $institution_row) 
							{
								if ($exam['associatedinstitute'] == $institution_row['institude_id']) 
								{
									$institution_name = $institution_row['name'];
								}
							}
						}
						$institution_name = preg_replace('/[^A-Za-z0-9\-\/ ]/', '', $institution_name);
						$firstname = preg_replace('/[^A-Za-z\/ ]/', '', $exam['firstname']);
						$middlename = preg_replace('/[^A-Za-z]/', '', $exam['middlename']);
						$lastname = preg_replace('/[^A-Za-z]/', '', $exam['lastname']);
						$mobile = preg_replace('/[^0-9]/', '', $exam['mobile']);
						$pincode = preg_replace('/[^0-9]/', '', $exam['pincode']);
						
						$exam_arr = array('1002' => 'ANTI MONEY LAUNDERING AND KNOW YOUR CUSTOME',
						'1010' => 'CUSTOMER SERVICE AND BANKING CODES AND STANDARDS',
						'1011' => 'CERTIFICATE EXAMINATION IN IT SECURITY',
						'1012' => 'CERTIFIED INFORMATION SYSTEM BANKER REVISED SYLLABUS',
						'1013' => 'CERTIFICATE COURSE IN DIGITAL BANKING',
						'1014' => 'CERTIFICATE IN INTERNATIONAL TRADE FINANCE',
						'1019' => 'CERTIFICATE COURSE IN STRATEGIC MANAGEMENT & INNOVATIONS IN BANKING',
						'1020' => 'Certificate Course in Emerging Technologies',
						'1017' => 'CERTIFICATE COURSE ON RESOLUTION OF STRESSED ASSETS WITH SPECIAL EMPHASIS ON INSOLVENCY AND BANKRUPTCY CODE 2016 FOR BANKERS');         
						
						foreach ($exam_arr as $k => $val) 
						{
							if ($exam_code == $k) 
							{
								$exam_name = $val;
							}
						}
						
						$select    = 'regnumber';
						$this->db->where_in('exam_code', $exam['exam_code']);
						$this->db->where_in('regnumber', $exam['regnumber']);
						$attempt_count = $this->Master_model->getRecords('member_exam', array('pay_status' => 1), $select);
						$attempt_count = count($attempt_count);            
						$attempt_count = $attempt_count - 1;
						
						if($scannedphoto != "" && $scannedsignaturephoto != "" && $idproofphoto != "")
						{
							$post_field_arr['first_name'] = $firstname;
							$post_field_arr['middle_name'] = $middlename;
							$post_field_arr['last_name'] = $lastname;
							$post_field_arr['mem_no'] = $exam['regnumber'];
							$post_field_arr['password'] = $exam['pwd'];
							$post_field_arr['dob'] = date("Y-m-d", strtotime($dateofbirth));
							$post_field_arr['gender'] = $gender;
							$post_field_arr['email'] = $exam['email'];
							$post_field_arr['mobile'] = $mobile;
							$post_field_arr['address'] = $address;
							$post_field_arr['state'] = $exam['state'];
							$post_field_arr['pin_code'] = $pincode;
							$post_field_arr['country'] = 'INDIA';
							$post_field_arr['profession'] = '';
							$post_field_arr['organization'] = $institution_name;
							$post_field_arr['designation'] = $designation_name;
							$post_field_arr['exam_code'] = $exam_code;
							$post_field_arr['course'] = $exam_name;
							$post_field_arr['elective_sub_code'] = $subject_code;
							$post_field_arr['elective_sub_desc'] = $subject_description;
							$post_field_arr['attempt'] = $attempt_count;
							$post_field_arr['registration_date'] = date("Y-m-d", strtotime($registration_date));
							$post_field_arr['exam_date'] = date("Y-m-d", strtotime($exam['exam_date']));
							$post_field_arr['time'] = $exam['time'];
							$post_field_arr['exam_medium'] = $medium_name;
							$post_field_arr['exam_center_code'] = $exam['exam_center_code'];
							$post_field_arr['venue_code'] = $exam['venueid'];
							//$post_field_arr['server_url'] = $server_url;
							$post_field_arr['photo_url'] = $scannedphoto;
							$post_field_arr['sign_url'] = $scannedsignaturephoto;
							$post_field_arr['idproof_url'] = $idproofphoto;
							
							$api_data_arr[] = $post_field_arr;
							
							$i++;
							$exam_cnt++;
							
							$append_log_data = $firstname.' | '.$middlename.' | '.$lastname.' | '.$exam['regnumber'].' | '.$exam['pwd'].' | '.date("Y-m-d", strtotime($dateofbirth)).' | '.$gender.' | '.$exam['email'].' | '.$mobile.' | '.$address.' | '.$exam['state'].' | '.$pincode.' | '."INDIA".' | '."".' | '.$institution_name.' | '.$designation_name.' | '.$exam_code.' | '.$exam_name.' | '.$subject_code.' | '.$subject_description.' | '.$attempt_count.' | '.date("Y-m-d", strtotime($registration_date)).' | '.date("Y-m-d", strtotime($exam['exam_date'])).' | '.$exam['time'].' | '.$medium_name.' | '.$exam['exam_center_code'].' | '.$exam['venueid'].' | '.$scannedphoto.' | '.$scannedsignaturephoto.' | '.$idproofphoto;
							fwrite($fp1, "\n".$exam_cnt.' - '.$append_log_data."\n");
						}
					}
					fwrite($fp1, "Total Applications - " . $exam_cnt . "\n");
					
					$insert_info = array('CurrentDate' => $current_date,'old_file_name' => '','new_file_name' => '','record_count' => $exam_cnt,'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_nseit_sm', $insert_info, true); 
					$last_inserted_id = $this->db->insert_id();
				} 
				else 
				{
					fwrite($fp1, "No data found for the date: " . $yesterday . " \n");
					$insert_info = array('CurrentDate' => $current_date,'old_file_name' => '','new_file_name' => '','record_count' => 0, 'createdon' => date('Y-m-d H:i:s'));
					$this->master_model->insertRecord('cron_csv_nseit_sm', $insert_info, true);
					$last_inserted_id = $this->db->insert_id();
					$success[] = "No data found for the date";
				}
				fclose($member_img_log);
				echo "<pre>"; print_r($api_data_arr); echo "</pre>"; //exit;  
				//echo json_encode($api_data_arr); exit;  
				
				/*************** START : SEND DATA TO API IN JSON FORMAT ********************************/
				//$api_data_new_arr['request_data'] = $api_data_arr; 
				$mail_body = '';
				if(count($api_data_arr) > 0) 
				{ 
					//$api_json = json_encode($api_data_arr);
					
					$data_send_url = 'https://iibfapi.onlineregistrationform.org/EXODProctoring/api/upload-candidate-details';
					
					//Send a POST request without cURL.
					//$result = $this->post_data($data_send_url, $api_data_arr); 
					$result = $this->post_data_new($data_send_url, $api_data_arr, $cron_file_path.'/'.$file2);
					//print_r($result); 
					
					/* $result = str_replace("</pre>","",$result);
					$result = str_replace("<pre>","",$result); 
					
					$mail_body = '************************* NSEIT CSV Cron Execution Start - '.$start_time.' *************************<br><br>
					Total Applications : '.$exam_cnt.'<br> 
					Message : <br>'.$result.'<br><br>
					************************* NSEIT CSV Cron Execution End - '.date("Y-m-d H:i:s").' *************************';
					
					$attachment = $cron_file_path.'/'.$file2;
					//$this->send_mail('sagar.matale@esds.co.in', 'sagar.matale@esds.co.in', 'NSEIT Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);
					$this->send_mail('logs@iibf.esdsconnect.com', 'iibfdevp@esds.co.in', 'NSEIT Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment); */
				}
				else
				{
					$mail_body = 
					'************************* NSEIT CSV Cron Execution Start - '.$start_time.' *************************<br><br>
					Total Applications : 0<br><br>					
					************************* NSEIT CSV Cron Execution End - '.date("Y-m-d H:i:s").' *************************';
					
					$attachment = $cron_file_path.'/'.$file2;
					//$this->send_mail('sagar.matale@esds.co.in', 'sagar.matale@esds.co.in', 'NSEIT Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);
					$this->send_mail('logs@iibf.esdsconnect.com', 'iibfdevp@esds.co.in', 'NSEIT Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);
				}
				/*************** END : SEND ENCRYPTED DATA TO API IN JSON FORMAT ********************************/
				
				$this->master_model->updateRecord('cron_csv_nseit_sm',array('mail_content'=>json_encode($mail_body)),array('id'=>$last_inserted_id));
				
				$end_time = date("Y-m-d H:i:s");
				$result = array("success" => $success, "error" => $error, "Start Time" => $start_time, "End Time" => $end_time);
				$desc = json_encode($result);
				//$this->log_model->cronlog("NSEIT CSV Cron Execution End", $desc);
				fwrite($fp1, "\n" . "***** NSEIT CSV Cron Execution End " . $end_time . " ******" . "\n");
				fclose($fp1); 
			}
		}
		
		function post_data_new($url, $postVars = array(), $attachment='')
		{	
			if(count($postVars) > 0)
			{
				'<br> total_record_cnt : '.$total_record_cnt = count($postVars); 
				'<br> max_record_send : '.$max_record_send = 60;
				
				'<br> total_slots : '.$total_slots = floor($total_record_cnt / $max_record_send);
				'<br> total_slots_mod : '.$total_slots_mod = $total_record_cnt % $max_record_send; 
				
				$key_arr = array();
				$start_val = $end_val = 0; 
				if($total_slots > 0)
				{
					for($i=0; $i< $total_slots; $i++)
					{
						$temp_arr = array();
						
						if($i == 0) { $start_val = 0; $end_val = $max_record_send-1; }
						else{ $start_val = $end_val + 1; $end_val = $end_val + $max_record_send; }
						
						$temp_arr[0] = $start_val;
						$temp_arr[1] = $end_val;
						$key_arr[] = $temp_arr;
					}					
				}
				
				if($total_slots_mod > 0)
				{
					$temp_arr = array();
					
					if($total_slots == 0)
					{
						$temp_arr[0] = 0;
						$temp_arr[1] = $total_record_cnt -1;
					}
					else
					{
						$temp_arr[0] = $end_val+1;
						$temp_arr[1] = $end_val+$total_slots_mod;
					}
					$key_arr[] = $temp_arr;
				}
				
				
				/* echo '<pre>'; 
				print_r($postVars);//exit;				
				print_r($key_arr);
				echo '</pre>'; exit; */
				
				$mail_text = '';
				if(count($key_arr) > 0)
				{		
					foreach($key_arr as $key_res) 
					{
						$options = array(
							'http' => array(
							'method'  => 'POST',
							'header'=> "Content-Type: application/json\r\n" .
							"Accept: application/json\r\n".
							"C-TOKEN: a84cbd09-0211-415f-a939-6c8ba2a5cf87",
							'content' => json_encode(array_slice($postVars, $key_res[0], $max_record_send)),
							)
							);
						
						//echo '<pre>'; print_r($options); echo '</pre>'; //exit; 
						
						//Pass our $options array into stream_context_create.
						//This will return a stream context resource.
						$streamContext  = stream_context_create($options);
						
						//Use PHP's file_get_contents function to carry out the request.
						//We pass the $streamContext variable in as a third parameter.
						$result = file_get_contents($url, false, $streamContext);
						
						//If $result is FALSE, then the request has failed.
						if($result === false)
						{
							//If the request failed, throw an Exception containing
							//the error.
							$error = error_get_last();
							throw new Exception('POST request failed: ' . $error['message']);
						}
						//If everything went OK, return the response.
						
						$result = str_replace("</pre>","",$result);
						$result = str_replace("<pre>","",$result);
						
						echo "<br><br>".$result; //exit;
						
						$mail_text .= '<br>Range : '.$key_res[0].' - '.$key_res[1].'<br>';
						$mail_text .= 'Message : '.$result.'<br><br>';
					}
					
					$mail_body = '************************* NSEIT CSV Cron Execution Start - '.date("Y-m-d H:i:s").' *************************<br><br>
					Total Applications : '.$total_record_cnt.'<br> 
					Message : <br>'.$mail_text.'<br><br>
					************************* NSEIT CSV Cron Execution End - '.date("Y-m-d H:i:s").' *************************';
					
					//$this->send_mail('sagar.matale@esds.co.in', 'sagar.matale@esds.co.in', 'NSEIT Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);
					$this->send_mail('logs@iibf.esdsconnect.com', 'iibfdevp@esds.co.in', 'NSEIT Data Automation Cron Execution : '.date("Y-m-d"), $mail_body, $view_flag='', $attachment);
				}
			}
		}
		
		
		function post_data($url, $postVars = array())
		{
			//Transform our POST array into a URL-encoded query string.
			//$postStr = http_build_query($postVars);
			//$postStr = json_encode(http_build_query($postVars));
			//echo $postStr; exit;
			
			//Create an $options array that can be passed into stream_context_create.
			/* $options = array(
				'http' =>
				array(
				'method'  => 'POST', //We are using the POST HTTP method.
				'header'  => 'Content-type: application/x-www-form-urlencoded'.
				'Authorization: Bearer VGVjQ1NDU1BWOlRlY0NTQ1NQXv',
				'content' => json_encode( $postVars ) //Our URL-encoded query string.
				)
			); */
			
			$options = array(
			'http' => array(
			'method'  => 'POST',
			'header'=>  "Content-Type: application/json\r\n" .
			"Accept: application/json\r\n".
			"C-TOKEN: a84cbd09-0211-415f-a939-6c8ba2a5cf87",
			/* "Authorization: C-TOKEN a84cbd09-0211-415f-a939-6c8ba2a5cf87", */
			'content' => json_encode( $postVars ),					
			)
			);
			
			//return json_encode( $postVars ); exit;
			
			//Pass our $options array into stream_context_create.
			//This will return a stream context resource.
			$streamContext  = stream_context_create($options);
			
			//Use PHP's file_get_contents function to carry out the request.
			//We pass the $streamContext variable in as a third parameter.
			$result = file_get_contents($url, false, $streamContext);
			
			//If $result is FALSE, then the request has failed.
			if($result === false)
			{
				//If the request failed, throw an Exception containing
				//the error.
				$error = error_get_last();
				throw new Exception('POST request failed: ' . $error['message']);
			}
			//If everything went OK, return the response.
			return $result;
		}
		
		public function get_member_images($image_path='', $reg_no='', $regnumber='', $scannedphoto='', $idproofphoto='', $scannedsignaturephoto='', $regid='', $yesterday='')
		{	
			$recover_images = $this->recover_images($image_path, $reg_no, $regnumber, $scannedphoto, $idproofphoto, $scannedsignaturephoto, $regid, $yesterday);
			$scannedphoto_res = $recover_images['scannedphoto'];
			$idproofphoto_res = $recover_images['idproofphoto'];
			$scannedsignaturephoto_res = $recover_images['scannedsignaturephoto'];
			
			if($scannedphoto_res == "" || $idproofphoto_res == "" || $scannedsignaturephoto_res == "")
			{			
				$this->db->where("REPLACE(title,' ','') LIKE '%CSCINSERTArray%'");
				$user_log = $this->Master_model->getRecords('userlogs a',array('regid'=>$regid,' DATE(date)'=>$yesterday));
				
				if(COUNT($user_log) > 0)
				{
					$description = unserialize($user_log[0]['description']);
					$scannedphoto =  $description['scannedphoto'];
					$scannedsignaturephoto =  $description['scannedsignaturephoto'];
					$idproofphoto =  $description['idproofphoto'];
					
					$recover_images2 = $this->recover_images($image_path, $reg_no, $regnumber, $scannedphoto, $idproofphoto, $scannedsignaturephoto, $regid, $yesterday);
					$scannedphoto_res = $recover_images2['scannedphoto'];
					$idproofphoto_res = $recover_images2['idproofphoto'];
					$scannedsignaturephoto_res = $recover_images2['scannedsignaturephoto'];
				}
			}
			
			$data['scannedphoto'] = $scannedphoto_res;
			$data['idproofphoto'] = $idproofphoto_res;
			$data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
			return $data;
		}
		
		public function recover_images($image_path='', $reg_no='', $regnumber='', $scannedphoto='', $idproofphoto='', $scannedsignaturephoto='', $regid='', $yesterday='')
		{	
			//// FOR PHOTO
			if($scannedphoto != '' && $scannedphoto != 'p_'.$regnumber.'.jpg')
			{							
				$attachpath = "uploads/photograph/".$scannedphoto;
				if(file_exists($attachpath))
				{
					if(@ rename("./uploads/photograph/".$scannedphoto,"./uploads/photograph/p_".$regnumber.".jpg"))
					{
						$insert_data  = array(
						'member_no' => $regnumber,
						'update_value' => "uploads folder Photo rename",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}					
				}				
			}
			
			//// FOR SIGNATURE
			if($scannedsignaturephoto != '' && $scannedsignaturephoto != 's_'.$regnumber.'.jpg')
			{							
				$attachpath = "uploads/scansignature/".$scannedsignaturephoto;
				if(file_exists($attachpath))
				{
					if(@ rename("./uploads/scansignature/".$scannedsignaturephoto,"./uploads/scansignature/s_".$regnumber.".jpg"))
					{
						$insert_data  = array(
						'member_no' => $regnumber,
						'update_value' => "uploads folder Signature rename",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}					
				}				
			}
			
			//// FOR IDPROOF
			if($idproofphoto != '' && $idproofphoto != 'pr_'.$regnumber.'.jpg')
			{							
				$attachpath = "uploads/idproof/".$idproofphoto;
				if(file_exists($attachpath))
				{
					if(@ rename("./uploads/idproof/".$idproofphoto,"./uploads/idproof/pr_".$regnumber.".jpg"))
					{
						$insert_data  = array(
						'member_no' => $regnumber,
						'update_value' => "uploads folder id proof rename",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}					
				}				
			}
			
			$extn = '.jpg';
			$member_no = $regnumber;
			
			//// Code for Photo
			$photo_name = $scannedphoto;
			$photo = strpos($photo_name,'photo');
			if($photo == 8)
			{
				$photo_replace = str_replace($photo_name,'p_',$photo_name);
				$updated_photo = $photo_replace.$member_no.$extn;
				
				$update_data = array('scannedphoto' => $updated_photo);
				$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				
				$insert_data  = array(
				'member_no' => $member_no,
				'update_value' => "Photo",
				'update_date' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('member_images_update', $insert_data);
				
				$scannedphoto = $updated_photo;
			} 
			
			//// Code for Signature
			$sign_name = $scannedsignaturephoto;
			$sign = strpos($sign_name,'sign');
			if($sign == 8)
			{
				$sign_replace = str_replace($sign_name,'s_',$sign_name);
				$updated_sign = $sign_replace.$member_no.$extn;
				
				$update_data = array('scannedsignaturephoto' => $updated_sign);
				$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				
				$insert_data  = array(
				'member_no' => $member_no,
				'update_value' => "Signature",
				'update_date' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('member_images_update', $insert_data);
				
				$scannedsignaturephoto = $updated_sign;
			}
			
			//// Code for IDPROOF
			$idproof_name = $idproofphoto;
			$idproof = strpos($idproof_name,'idproof');
			if($idproof == 8)
			{
				$idproof_replace = str_replace($idproof_name,'pr_',$idproof_name);
				$updated_idproof = $idproof_replace.$member_no.$extn;
				
				$update_data = array('idproofphoto' => $updated_idproof);
				$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
				
				$insert_data  = array(
				'member_no' => $member_no,
				'update_value' => "ID Proof",
				'update_date' => date('Y-m-d H:i:s')
				);
				$this->master_model->insertRecord('member_images_update', $insert_data);
				
				$idproofphoto = $updated_idproof;
			}
			
			$db_img_path = $image_path; //Get old image path from database
			$scannedphoto_res = $idproofphoto_res = $scannedsignaturephoto_res = '';
			
			$final_photo_img = '';
			if($scannedphoto != "")
			{
				$photo_img_arr = explode('.', $scannedphoto);
				if(count($photo_img_arr) > 0)
				{
					$chk_photo_img = $photo_img_arr[0];
					
					if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpg'))
					{
						$final_photo_img = $chk_photo_img.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/photograph/".$chk_photo_img.'.jpeg'))
					{
						$final_photo_img = $chk_photo_img.'.jpeg';
					}
				}
			}
			
			if($final_photo_img == "")
			{
				if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpg'))
				{
					$final_photo_img = "p_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/photograph/p_".$member_no.'.jpeg'))
				{
					$final_photo_img = "p_".$member_no.'.jpeg';
				}
			}
				
			
			if($final_photo_img != "") //Check photo in regular folder
			{ 
				$scannedphoto_res = base_url()."uploads/photograph/".$final_photo_img; 
			}
			else if($db_img_path != "") //Check photo in old image path
			{ 
				if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$reg_no.".jpg"))
				{
					$scannedphoto_res = base_url()."uploads".$db_img_path."photo/p_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."photo/p_".$regnumber.".jpg"))
				{
					$scannedphoto_res = base_url()."uploads".$db_img_path."photo/p_".$regnumber.".jpg"; 
				}
			}
			else  //Check photo in kyc folder          
			{
				if($reg_no != "" && file_exists(FCPATH."uploads/photograph/k_p_".$reg_no.".jpg"))
				{
					$scannedphoto_res = base_url()."uploads/photograph/k_p_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads/photograph/k_p_".$regnumber.".jpg"))
				{
					$scannedphoto_res = base_url()."uploads/photograph/k_p_".$regnumber.".jpg"; 
				}
			}
			
			
			$final_idproofphoto_img = '';
			if($idproofphoto != "")
			{
				$idproofphoto_img_arr = explode('.', $idproofphoto);
				if(count($idproofphoto_img_arr) > 0)
				{
					$chk_idproofphoto_img = $idproofphoto_img_arr[0];
					
					if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpg'))
					{
						$final_idproofphoto_img = $chk_idproofphoto_img.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/idproof/".$chk_idproofphoto_img.'.jpeg'))
					{
						$final_idproofphoto_img = $chk_idproofphoto_img.'.jpeg';
					}
				}
			}
			
			if($final_idproofphoto_img == "")
			{
				if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpg'))
				{
					$final_idproofphoto_img = "pr_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/idproof/pr_".$member_no.'.jpeg'))
				{
					$final_idproofphoto_img = "pr_".$member_no.'.jpeg';
				}
			}
			
			
			if ($final_idproofphoto_img != "") //Check id proof in regular folder
			{ 
				$idproofphoto_res = base_url()."uploads/idproof/".$final_idproofphoto_img; 
			}
			else if($db_img_path != "") //Check id proof in old image path
			{ 
				if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$reg_no.".jpg"))
				{
					$idproofphoto_res = base_url()."uploads".$db_img_path."idproof/pr_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."idproof/pr_".$regnumber.".jpg"))
				{
					$idproofphoto_res = base_url()."uploads".$db_img_path."idproof/pr_".$regnumber.".jpg"; 
				}
			}
			else //Check photo in kyc folder
			{
				if($reg_no != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$reg_no.".jpg"))
				{
					$idproofphoto_res = base_url()."uploads/idproof/k_pr_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads/idproof/k_pr_".$regnumber.".jpg"))
				{
					$idproofphoto_res = base_url()."uploads/idproof/k_pr_".$regnumber.".jpg"; 
				}
			}
			
			
			$final_scanphoto_img = '';
			if($scannedsignaturephoto != "")
			{
				$scanphoto_img_arr = explode('.', $scannedsignaturephoto);
				if(count($scanphoto_img_arr) > 0)
				{
					$chk_scanphoto_img = $scanphoto_img_arr[0];
					
					if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpg'))
					{
						$final_scanphoto_img = $chk_scanphoto_img.'.jpg';
					}
					else if(file_exists(FCPATH."uploads/scansignature/".$chk_scanphoto_img.'.jpeg'))
					{
						$final_scanphoto_img = $chk_scanphoto_img.'.jpeg';
					}
				}
			}
			
			if($final_scanphoto_img == "")
			{
				if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpg'))
				{
					$final_scanphoto_img = "s_".$member_no.'.jpg';
				}
				else if(file_exists(FCPATH."uploads/scansignature/s_".$member_no.'.jpeg'))
				{
					$final_scanphoto_img = "s_".$member_no.'.jpeg';
				}
			}				
				
			if ($final_scanphoto_img != "") //Check signature in regular folder
			{ 
				$scannedsignaturephoto_res = base_url()."uploads/scansignature/".$final_scanphoto_img; 
			}
			else if($db_img_path != "") //Check signature in old image path
			{ 
				if($reg_no != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$reg_no.".jpg"))
				{
					$scannedsignaturephoto_res = base_url()."uploads".$db_img_path."signature/s_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads".$db_img_path."signature/s_".$regnumber.".jpg"))
				{
					$scannedsignaturephoto_res = base_url()."uploads".$db_img_path."signature/s_".$regnumber.".jpg"; 
				}
			}
			else //Check signature in kyc folder
			{
				if($reg_no != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$reg_no.".jpg"))
				{
					$scannedsignaturephoto_res = base_url()."uploads/scansignature/k_s_".$reg_no.".jpg"; 
				}
				else if($regnumber != "" && file_exists(FCPATH."uploads/scansignature/k_s_".$regnumber.".jpg"))
				{
					$scannedsignaturephoto_res = base_url()."uploads/scansignature/k_s_".$regnumber.".jpg"; 
				}
			}
			
			$data['scannedphoto'] = $scannedphoto_res;
			$data['idproofphoto'] = $idproofphoto_res;
			$data['scannedsignaturephoto'] = $scannedsignaturephoto_res;
			return $data;
		}
		
		public function test_post_data($exam_cnt=0)
		{
			echo json_encode(array('status' => 100, 'count'=>$exam_cnt));
		}		
		
		function send_mail($from_mail='', $to_email='', $subject='', $mail_data='', $view_flag='', $attachment='')
		{
			if($from_mail != '' && $to_email != '' && $subject != '' && $mail_data != '')
			{
				if($view_flag=='1')
				{
					echo "<br>From = ".$from_mail;
					echo "<br>To = ".$to_email;				
					echo "<br>subject = ".$subject;
					echo "<br>message = ".$mail_data; exit;
				}
				
				$this->load->library('email');
				//$config['protocol'] = 'sendmail';
				//$config['mailpath'] = '/usr/sbin/sendmail';
				$config['charset'] = 'iso-8859-1';
				$config['charset'] = 'UTF-8';
				$config['wordwrap'] = TRUE;
				$config['mailtype'] = 'html';
				$this->email->initialize($config);
				//$this->email->subject($subject." php mail");
				
				$this->email->from($from_mail);
				$this->email->to($to_email);
				$this->email->subject($subject);
				$this->email->message($mail_data);
				
				if($attachment != '')
				{
					$this->email->attach($attachment);
				}
				
				if(@$this->email->send())
				{
					$final_msg = 'success';
				}
				else
				{
					$final_msg = 'error. Email not send<br>';
					$final_msg .= $this->email->print_debugger();
				}
				
				return $final_msg;
				$this->email->clear();				
			}
			else
			{
				return 'error - invalid form fields';
			}
		}
	}
