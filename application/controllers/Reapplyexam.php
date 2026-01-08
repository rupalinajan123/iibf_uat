<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reapplyexam extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('chk_session');
		$this->chk_session->Check_mult_session();
		if($this->session->userdata('memberdata'))
		{
			$this->session->unset_userdata('memberdata');
		}

	}
	public function gpass()
	{
		echo $password=$this->generate_random_password();	echo '<br>';		
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('pass_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		echo  $encPass = $aes->encrypt($password);	 
		exit;	
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('pass_key');
		$aes = new CryptAES();$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		echo $encpass = $aes->decrypt('ax+Bf574w6ovk5dA+MQ1RQ==');
		exit;
	}
	public function sbi_merchent_key()
	{
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$key = $this->config->item('pass_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();echo $encpass = $aes->decrypt('roDnMt2vbUt4bG4xKhlKgHOMeQQI/DtF+RTlAl9hqTc=');
		exit;
	}
	public function index()
	{	
		$data=array();
		$data['error']='';
		if(isset($_POST['submit']))
		{
			$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
								),*/
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
									),
							);
					$this->form_validation->set_rules($config);include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$encpass = $aes->encrypt($this->input->post('Password'));
				$dataarr = array('regnumber'=> $this->input->post('Username'),'usrpassword'=>$encpass,'isactive'=>'1','isdeleted'=>'0');
			if ($this->form_validation->run() == TRUE)
			{
				### Eligible master check
				//$exams = array('21','42','992','60','63','65','68','69','70','71');
				//$exams = array('60','63','65','68','69','70','71');
				
				/*$exams = array('60');
				$this->db->select('id,exam_code');			
				$this->db->where_in('exam_code',$exams);
				$this->db->where('member_no',$this->input->post('Username'));
				$this->db->where('eligible_period','121');
				$this->db->where('exam_status','F');
				$this->db->limit(1);
				$eligible = $this->master_model->getRecords('eligible_master');
				//echo "Exam code=>".$eligible[0]['exam_code'];*/
				/*$eligible = array(100000120,510047543,510049072,100000101,7433954,510077605,500070854,510343154,510025689,500159842,510037305,510067376,500083798,510155344,510331539,510288049,510058281,500213047,400033817,510199408,510296543,510412547,510078530,510383199,500179013,510141068,500201544,510165392,510450012,510021648,510360134,510215987,510345083,510368567,510399988,510187526,510210835,500082868,510154474,500064245,510166314,510093305,510301492,510266641,510411390,510085644,510238831,510450504,500170912,510373511,510354005,510154778,510280118,500013953,510027824,510328255,510140531,500100917,510272886,510345398,500050718,510397627,500057357,4834341,510154426,500054763,510222465,510266712,510326623,500118182,500062094,500169104,510364879,510107068,510246416,500138552,510126682,510207681,510478351,510317496,510040716,510012988,510285355,500061147,500192627,510098466,510057230,7631100,510194562,510352404,510226121,510052471,510193084,510394939,510331040,500146485,510362484,510056217,510279897,510165915,500098641,500122665,510383106,500209642,500111122,510063988,500200212,510163501,500199803,500131169,510241475,510177964,510308903,6575801,510291375,510168683,510021414,510475261,500144818,510337827,500157909,510095080,510175791,510153580,510275113,510122118,510305184,510388406,510430564,500079784,500216309,510356878,510404453,510217293,510058894,7437434,500140603,510071785,500159930,6932431,510185279,500064075,510216237,500065104,510254231,510388689,500072930,510283746,510473005,510304641,500083335,510061706,510068013,510050869,510337793,510176651,510310223,510312680,500216527,510220228,510423210,510309383,510033725,510209134,510150683,500168339,510173629,4980662,510145566,510128655,510138948,510014114,510369436,500155890,510078649,510334623,510148645,510083693,500008317,510393043,500211137,500129882,510135895,500181687,500143241,510005896,6024879,510265080,500104954,510431118,400109675,510258532,510336409,510284255,510179611,510318798,6908776,400057917,510104366,510424910,510387782,510276343,510086971,510297119,500044017,500140754,510371453,510343271,510398325,510103695,510378263,500110862,500097109,510124594,510330528,510072786,510276853,510351021,510084261,500036810,500054529,510140793,510056829,510032256,500094878,510017887,510381555,500151523,500070130,500096980,500208435,510164958,500138549,510175723,510410895,510191800,510460365,510380116,510338083,510317695,500017277,510244020,510160566,510244183,510387119,510203622,510072247,510451587,510151050,510349704,510224924,510266415,500195732,510193978,500168814,510379275,510362208,510449856,510294810,510447355,510233711,510036899,510412248,500139932,510346647,500058736,510391001,510224073,510205177,510389575,510186196,510388060,510328625,510156538,510194527,510228824,510289779,200069181,510186447,510361624,510416167,510469839,200065280,510124437,510036740,500166471,510403098,510467656,500163494,510388507,510135464,510317868,510335012,510314464,510426929,510403478,500052572,400057354,510151903,510089556,510147372,510108362,510357379,510213621,510241920,510351233,510439320,510453390,510349030,510293600,7009859,510201492,510270440,510101474,510175306,7441858,510301726,500160871,510095438,510240803,510349852,510120615,500001959,500102920,500154010,510056614,510036731,510313220,500114923,510374539,500180957,510058476,510053478,500033353,510360753,510190410,510068137,500136145,510111580,510090014,510332060,500137281,510283438,510322396,510054224,510382669,510301478,510115953,7554314,510374987,510056910,500096170,510416065,510376671,6475412,510405500,500190774,510284135,500013719,510198305,510253872,7122874,510062846,200023600,510204141,500210313,500111352,510274135,500115018,500113707,510058975,500138381,500186013,510130568,510033668,510418721,510217238,510211983,500154626,500069066,7388859,510058090,510316637,500059477,510375594,510389580,510215480,510288184,510178480,510295221,510065142,510247441,500129140,510170058,7343928,510185004,510075648,510115249,500140659,500125213,500132362,510158222,510156666,510224064,500147205,510065799,510059838,510118069,510376611,510147923,510308168,510019349,510378451,510245962,510078945,510376226,510243750,510117747,500051970,510013972,300041119,510301613,510335319,510020513,510323328,510191001,510305760,510319459,510312512,510165677,500051383,510387156,7564581,510418266,510351814,500060855,510160453,510367045,500190696,510278277,510100327,510482298,510442698,510060358,500101282,500110883,510065409,510185368,510458910,510173529,510449764,510172588,500095590,510090258,510447936,510368131,510305840,510068848,510101793,7522858,510376918,500138918,6074119,510009516,510318716,510129932,510112695,510064264,510123877,510339032,500142301,500106007,510177533,510450683,510443942,7284845,510264301,7314096,510467684,510415526,510425821,500096311,500125507,510121789,500179426,500128754,510115322,510299071,500070215,510205746,510259061,510304814,510242331,510120789,510144268,510275586,510117753,510069886,500119220,510329900,500102035,500196601,510371298,510283454,510074624,510094012,510251702,510020699,510033451,510251649,500023715,500061616,510479802,510048458,510263388,6214659,510111808,510014965,500120681,510315946,510211583,510466757,510027604,510018084,510421790,510405499,510421149,500129499,5622228,510307698,510145737,500043259,510042699,510326418,510326462,510300148,510263337,510449568,510012076,510302706,510047172,500069299,7650043,500202735,500049943,510406044,510163934,510367005,400026081,510102423,500063229,510263536,510164158,510102758,510103985,510094958,510253221,510445387,510081815,510469167,200077262,510151788,510194959,500200258,510138529,510445007,510153111,510261744,510350712,510408624,510368423,500213507,510086882,510240517,510401761,510303102,510417524,510190448,510412835,510075910,510035817,510196601,510288848,510246029,510313845,510232452,510332163,500186285,500033506,500053419,510223615,510254327,500070498,200075974,500042548,510196789,500101710,100050490,500160935,510303050,510214365,510322756,510294908,500059831,510188677,510106535,510182173,510195560,500148878,510341273,500158882,510412590,6126019,510300024,510171051,510130600,510197863,510067701,510026142,510479666,500069457,510053403,510173294,510249985,500154398,510155441,500094063,510399952,510266618,500128872,510201730,500009102,500167734,510209868,510500222,510037028,510247441,500169586,510458089,510293863,510394913,7024603,500163392,510149578,510179715,7357253,510026957,510131162,500168705,510417558,510305096,500143731,500099483,7320960,510344293,400034171,500134229,510353151,510035390,510366787);*/
				//$eligible = array(510188677,801426905,510131162);
				## 500067269 CAIIB Special Link
				$eligible = array(500067269);
				$exam_code = $this->config->item('examCodeCaiib');
				//if(count($eligible) > 0)
				if(in_array($this->input->post('Username'),$eligible))
				{ 	 
						$this->db->select('registrationtype,regid,regnumber,firstname,middlename,lastname,createdon,registrationtype,isactive,usrpassword');	$where="(registrationtype='O' OR registrationtype='A' OR registrationtype='F' OR registrationtype='DB')";
						$this->db->where($where);
						$user_info=$this->master_model->getRecords('member_registration',$dataarr);
						//echo $this->db->last_query();
						if(count($user_info) > 0)
						{ 	 
							if($user_info[0]['isactive']==1)	 
							{		  				
								$chklink=$this->master_model->showcarddownloadlink($user_info[0]['regnumber']);	
								if($chklink['is_show'] == "yes"){	
								$exam_name=$chklink['exam_name'];		
								$showlink = "yes";				
								}else{	
									$showlink = "no";		
									$exam_name=$chklink['exam_name'];	
								}			
								
								$mysqltime=date("H:i:s");	

									if($exam_code == $this->config->item('examCodeDBF') && $user_info[0]['registrationtype'] == 'DB')
									{
												$user_data=array('dbregid'=>$user_info[0]['regid'],
																'dbregnumber'=>$user_info[0]['regnumber'],
																'dbfirstname'=>$user_info[0]['firstname'],
																'dbmiddlename'=>$user_info[0]['middlename'],
																'dblastname'=>$user_info[0]['lastname'],
																'dbtimer'=>base64_encode($mysqltime),
																'showlink'=>$showlink,
																'exam_name'=>$exam_name,
																'memtype'=>$user_info[0]['registrationtype'],
																'dbpassword'=>base64_encode($this->input->post('Password')));
									}else {	
													$user_data=array('mregid_applyexam'=>$user_info[0]['regid'],
														'mregnumber_applyexam'=>$user_info[0]['regnumber'],
														'mfirstname_applyexam'=>$user_info[0]['firstname'],
														'mmiddlename_applyexam'=>$user_info[0]['middlename'],
														'mlastname_applyexam'=>$user_info[0]['lastname'],
														'mtimer_applyexam'=>base64_encode($mysqltime),
														'memtype'=>$user_info[0]['registrationtype'],
														'mpassword_applyexam'=>base64_encode($this->input->post('Password')));
									}
									$this->session->set_userdata($user_data);
									
									if($exam_code == $this->config->item('examCodeDBF') && $user_info[0]['registrationtype'] == 'DB')
									{
										## DBF exam url
										//http://iibf.teamgrowth.net/Dbf/examdetails/?excode2=NDI=
										redirect(base_url().'Dbf/examdetails/?excode2=NDI=');	
									}else{
										## Jaiib / Caiib exam
										redirect(base_url().'Applyexam/examdetails/?ExId='.base64_encode($exam_code).'&Extype=NA==');	
										
									}
							  }
							  else if($user_info[0]['isactive']==0)
							  {
									$data['error']='<span style="">Invalid Credentials.</span>'; 
							  }
							  else
							  {
									$data['error']='<span style="">This account is suspended</span>'; 
							  }
					}
					else
					{
						$data['error']='<span style="">Invalid Credentials..</span>';
					}
				}else{
					$data['error']='<span style="">Access Denied!.</span>';
				}
		}
		else
		{
			$data['validation_errors'] = validation_errors();
		}
	}

		$this->load->helper('captcha');
		$vals = array(
				'img_path' => './uploads/applications/',
				'img_url' => base_url().'uploads/applications/',
			);
		$cap = create_captcha($vals);

		$data['image'] = $cap['image'];$data['code']=$cap['word'];
		$this->session->set_userdata('userlogincaptcha', $cap['word']);
		$this->load->view('reapply_exam_login',$data);
	}

	public function check_captcha_userlogin($code) 
	{
		if(!isset($this->session->userlogincaptcha) && empty($this->session->userlogincaptcha))
		{
			redirect(base_url().'login/');
		}

		if($code == '' || $this->session->userlogincaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 
			$this->session->set_userdata("userlogincaptcha", rand(1,100000));
			return false;
		}
		if($this->session->userlogincaptcha == $code)
		{
			$this->session->set_userdata('userlogincaptcha','');
			$this->session->unset_userdata("userlogincaptcha");
			return true;
		}

	}

	public function generatecaptchaajax()
	{
		$this->load->helper('captcha');
		$this->session->unset_userdata("userlogincaptcha");
		$this->session->set_userdata("userlogincaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["userlogincaptcha"] = $cap['word'];
		echo $data;

	}

	
	function obfuscate_email($email)
	{
		$extension = explode("@",$email);
		$name = implode('@', array_slice($extension, 0, count($extension)-1));
		$len = strlen($name); $start = $len - 2;
		return str_repeat('*', $start).substr($name,$start,$len)."@".end($extension);   
	}
	
	public function Logout(){
	$sessionData = $this->session->all_userdata();
	foreach($sessionData as $key =>$val){
		$this->session->unset_userdata($key);    
	}
	$cookie_name = "instruction";$cookie_value = "0";setcookie($cookie_name, $cookie_value, time() + (60 * 10), "/"); 
	// 60 seconds ( 1 minute) * 10 = 10 minutesredirect('http://iibf.org.in/');

	}

	

	public function thumb()
	{
		$this->load->helper('gpdf_thumb');
		$pdf_file_url = "http://github-media-downloads.s3.amazonaws.com/GitHub.Quick.Facts.pdf";
		$file_path = base_url()."uploads/index.jpg";
		echo saveGPDFThumb($pdf_file_url, $file_path);
		exit;

	}

	public function thumbceate()
	{
		$pdf_file_url = "http://github-media-downloads.s3.amazonaws.com/GitHub.Quick.Facts.pdf";
		$file_path = base_url()."uploads/thumb";
		$this->master_model->createThumb('GitHub.Quick.Facts.pdf',$file_path,'300',300);

	}

	
function genPdfThumbnail($source, $target)
	{
		$target = dirname($source).DIRECTORY_SEPARATOR.$target;
		$im     = new Imagick($source."[0]"); // 0-first page, 1-second page
		$im->setImageColorspace(255); // prevent image colors from inverting
		$im->setimageformat("jpeg");
		$im->thumbnailimage(160, 120); // width and height
		$im->writeimage($target);
		$im->clear();
		$im->destroy();

	}
}

