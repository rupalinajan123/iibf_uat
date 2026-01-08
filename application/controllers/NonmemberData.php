<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*------ This Controller is used for collection data for nonmemer -------*/
// Created By Gaurav Shewale (28-06-2024) 

class NonmemberData extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->helper('general_helper');
		$this->load->model('master_model');

    $this->valid_member_no_arr = array(801010927,801194073,801197272,801197364,801218729,801250884,801251602,801260163,801260345,801260776,801261358,801316374,801424007,801426100,801553196,801553350,801568344,801585607,801677407,801680656,801746610,801764841,801802622,801813535,801885004,801885628,801895593,801902522,801977996,802020862,802021064,802021077,802021089,802021514,802060157,802060303,802060355,802060400,802060426,802060600,802060602,802060620,802068818,802107239,802107276,802133546,802136361,802136466,802136556,802136573,802137952,802139853,802139994,802168107,802178539,802183725,802183934,802184710,802205172,802205314,802205474,802205768,802207835,802209764,802209935,802232267,802249884,802249890,802249893,802249962,802249966,802249985,802249994,802250002,802250015,802250044,802250164,802250441,802250503,802250516,802250677,802250684,802250746,802250772,802250797,802250809,802250817,802250825,802250832,802250868,802250921,802250930,802250981,802250983,802251194,802251195,802251246,802251270,802251339,802251380,802253079,802253202,802253221,802253301,802253846,802253854,802253943,802253966,802255028,802255606,802255660,802255923,801192808,801193047,801220076,801513659,801673399,801764876,801792199,801802615,801959521,801978360,802020859,802023719,802060529,802107235,802133535,802133601,802133604,802133620,802136715,802183644,802186278,802205601,802206626,802208969,802261990,802262009,802262036,802262127,802262204,802262213,802262235,802262238,802262340,802262384,802262437,802262466,802262484,802262497,802262721,802267571,802268257,802268311,802268339,802268343,802268518,802268537,802268539,802268660,802269797,802269964,802270348,802270862,802271119,802271151,802271639,831104800,500191471,801005473,801177037,801196508,801218221,801346949,801531117,801584522,801584526,801673965,801765053,801814855,801821145,801963008,801963676,801991370,802021437,802060659,802133576,802184948,802205500,802205729,802207964,802261968,802276103,802276199,802276214,802276300,802276352,802277325,802277408,802277478,802277480,802277597,802277652,802277730,802277811,802278028,802278208,802279679,802279829,802280433,802280527,801121286,801163084,801220182,801350563,801472188,801480761,801524795,801674332,801800544,801814686,801886248,801886367,801963286,801991279,802107165,802107344,802107604,802136530,802182337,802184311,802190315,802205533,802205577,802209759,802282768,802284265,802284269,802284327,802284339,802284358,802284397,802284442,802284494,802284591,802284596,802284663,802284664,802284669,802284670,802284677,802284680,802284809,802284823,802284890,802284913,802285061,802286177,802286232,802286241,802286346,802286741,802286872,802286904,802287087,802287155,802287429,802287665,802287676,802287692,802287732,802287743,802287931,802288074,802288105,802288140,802288143,802288196,802288361,802288399,802288459,802288984,802288988,802289090,802289121,802289165,801672864,801672972,801991307,801991355,802133586,802136350,802140064,802140170,802184492,802205263,802256246,802332109,802332131,802332147,802332231,802332235,802332249,802332718,802332743,802332759,802333430,802333483,802333519,802333530,802333580,802333752,500038905,500165700,801001149,801010969,801120436,801192537,801197315,801543719,801742185,801839305,801991178,802068782,802107245,802107680,802190664,802190675,802205335,802333454,802333585,802333636,802333840,802334006,802349283,802349301,802349308,802349322,802349323,802349368,802349649,802349684,802349760,802349764,802349802,802349852,802349913,801196806,801197374,801249331,801352084,801415527,801425882,801471595,801587392,801801036,801817556,801978088,802060621,802107441,802209738,802227776,802254748,802336193,802360751,802360755,802360774,802360783,802360788,802360808,802360811,802360827,802360840,802360850,802360867,802360905,802360913,802360956,802360965,802360973,802360994,802361056,802361062,802361072,802361089,802361110,802361175,802361180,802361184,802361186,802361193,802361473,802361485,802361486,802361495,802361555,802361581,802361618,802361634,802361708,802361757,802361780,802361794,802361809,802361813,802361815,802361820,802361827,802361858,802361863,802362035,802362061,802362299,802362742,802363806,802363852,802363868,802363870,802364413,802364473,802364504,802364863,801176700,801234351,801251404,801276806,801415360,801419563,801471986,801553540,801674334,801674571,801674629,801674683,801677474,801677477,801677618,801739412,801840709,801901521,801991266,801991450,801991525,801991762,802112289,802113768,802115520,802208083,802360929,802363879,802377511,802377518,802377519,802377528,802377559,802377563,802377572,802377576,802377612,802377614,802377619,802377640,802377647,802377679,802377690,802377696,802377701,802377735,802377753,802377828,802377853,802377908,802377979,802377990,802377991,802377995,802378017,802378033,802378034,802378035,802378036,802378053,802378058,802378067,802378068,802378072,802378087,802378091,802378092,802378093,802378098,802378100,802378116,802378271,802379074,802379133,802379142,802379156,802379160,802395655,802395671,802395888,802395910,802395927,802395929,802396091,801120939,801187447,801197147,801197407,801219466,801219970,801220834,801260423,801522085,801522758,801764486,801802611,801977907,802107591,802112203,802112356,802113120,802113349,802133559,802137091,802184652,802189046,802190164,802209843,802446190,802446222,802446231,802446240,802446252,802446256,802446267,802446275,802446276,802446277,802446281,802446295,802446298,802446313,802446315,802446316,802446318,802446320,802446321,802446323,802446328,802446330,802446343,802446346,802446354,802446374,802446376,802446420,802446424,802446447,802446449,802446454,802446475,802446503,802446504,802446534,802446555,802446558,802446559,802446567,802446569,802446582,802446621,802446649,802446655,802446656,802448653,802448696,802448781,802448855,802448977,802448987,802449047,802449069,801133398,801172821,801176085,801176965,801195853,801202831,801218270,801220168,801220209,801260107,801260715,801321845,801377600,801427213,801501870,801515256,801517899,801521338,801521776,801522683,801523990,801529304,801534152,801655266,801885036,801885369,801977885,801991254,802107360,802113049,802116529,802167945,802189075,802189099,802189162,802189343,802190730,802190783,802206801,802206883,802249897,802254533,802261604,802363634,802448523,802476437,802476441,802476461,802476479,802476481,802476487,802476489,802476492,802476493,802476503,802476521,802476523,802476529,802476531,802476533,802476541,802476547,802476549,802476557,802476611,802476613,802476618,802476624,802476625,802476634,802476638,802476641,802476657,802476669,802476673,802476680,802476691,802476696,802476703,802476705,802476711,802476719,802476725,802476730,802476733,802476734,802476746,802476752,802476769,802476781,802476794,802476808,802476828,802476863,802476910,802476926,802476936,802476939,802476969,802476972,802476985,802477010,802477015,802477020,802477023,802477026,802477038,802477046,802477073,802477080,802477082,802477092,802477111,802477120,802477122,802477137,802477221,802477264,802477298,802477360,802478335,802478384,802478448,802478470,802478537,802478538,802478547,802478554,802478563,802478580,802478615,802478617,802479456,802479646,802479711,802479720,802479746,802479753,802479829,802479858,802479943,802479984,802479989,802479992,802479997,802480011,802480062,802480108,802480109,802480190,802480197,802480212,802480214,802480230,802480250,802480252,802480369,802480475,802480476,802480545,802480606,802480610,802480622,831012521,500120569,801188333,801218320,801422139,801458907,801674578,801679795,801885042,801991321,801991961,802068737,802112351,802113519,802113547,802113978,802189180,802209614,802276051,802332072,802332162,802448361,802448613,802457946,802463107,802486864,802487886,802487911,802487912,802487914,802487915,802487917,802487918,802487931,802487980,802487994,802488048,802488053,802488054,802488095,802488099,802488131,802488156,802488162,802488179,802488398,802488477,802488479,802488511,802496236,802496271,802496288,802496295,802496309,802496315,802496320,802496363,802496431,802496439,802496447,802496456,500011865,801259647,801260452,801593685,801982512,802133556,802189157,802482506,802508247,802508342,802508359,802508465,802508476,802508550,802508551,802508561,802508570,802508593,802508641,802508822,802509038,802509077,802509216,802509337,802509468,802509477,802509568,802509768,802510018,802510239,802510452,802510454,802510747,802510781,802510828,802511299,802511858,801219971,801348922,801568340,801881535,801977920,802068795,802136484,802182318,802189173,802189186,802205687,802363854,802418016,802525100,802525118,802525124,802525179,802525435,802525465,802525474,802525602,802525663,802525701,802525725,802525970,802526155,802526426,802526792,802526816,802527984,802528051,802528292,802528305,802528313,802528433,802528556,802528863,802528876,802528879,802529336,802529344,802529657,802529796,802529877,802529897,801219869,801219991,801522762,801529300,801982603,801997383,802059940,802113562,802179734,802189138,802190485,802210150,802249882,802250531,802277470,802332247,802377565,802448742,802488050,802535581,802536035,802536044,802536061,802536068,802536084,802536151,802536167,802536193,802536203,802536365,802536367,802536380,802536411,802536419,802536433,802536439,802536556,802536571,802536680,802536695,802536698,802536711,802536720,802536760,802536773,802536781,802536797,802536798,802536810,802536813,802538612,802538618,802538681,802538870,802538883,802538895,802538926,802541255,802541262,802541289,802541299,802542130,802542257,802542317,802542332,802542369,802542432,802542460,802542504,802542541,802542646,100019954,300000715,801153099,801153635,801172772,801176329,801177045,801196672,801218293,801218401,801221221,801567679,801677764,801739205,801991249,801991306,802113968,802139883,802139892,802190516,802262002,802284441,802360779,802446237,802448380,802476477,802526178,802536340,802536372,802536521,802538989,802559652,802559898,802559900,802559909,802559951,802559984,802559987,802559989,802560014,802560030,802560037,802560082,802560092,802560138,802560141,802560142,802560162,802560163,802560186,802560194,802560199,802560202,802560207,802560226,802560277,802560298,802560306,802560313,802560338,802560359,802560363,802560374,802560409,802560466,802560471,802560478,802560545,802560562,802560595,802560630,802560633,802560635,802560641,802560654,802560668,802560677,802560694,802560733,802560759,802560869,802560915,802560916,802563345,802563401,802563435,802563442,802563481,802563546,802563725,802563747,802563901,802564072,802564324,802564610,802565126,802565132,802565136,831087506,200073209,200084463,400012915,801177050,801188925,801201061,801414718,801764420,801881543,802128943,802183628,802259680,802446289,802512005,802560614,802575069,802575083,802575114,802575246,802575283,802575314,802575351,802575362,802575418,802575439,802575494,802575515,802575522,802575530,802575593,802575602,802575603,802575644,802575649,802575714,802575744,802575754,802575763,802575930,802575987,802576169,802576189,802576402,802576404,802576431,802576466,802576488,802576553,802576747,802576865,802576965,802577022,802577046,802577055,802577069,802577070,802577085,802577094,802577115,802577134,802577158,802578452,802578517,802578702,802578745,802578839,802578890,802579505,802579624,802579695,802584693,802584694,802584695,802584696,802584697,801196304,802184837,802249895,802455949,802590699,802590713,802590723,802590742,802590884,802590885,802590890,802590972,802590999,802591009,802591017,802591094,802591131,802591188,802591225,802591269,802591343,802591351,802591359,802591465,802591528,802591530,802591723,802591731,802591733,802591738,802591752,802591758,802591778,802591796,802591797,802591831,802591885,802591896,802591978,802592209,802592247,802607038,802607253,802607799,802608425,802608529,802608662,802608739);
	}

	public function index()
	{
		redirect(site_url('NonmemberData/login'));
	}

	/*----- Created by login function by gaurav at 28-06-2024 -----*/
	public function login() //START : LOGIN
	{
		$data = array();
		$data['error'] = '';
    $this->load->model('Captcha_model');

    if(isset($_SESSION['FEDAI_NON_MEMBER_DATA_COLLECTION_LOGIN_ID']) && $_SESSION['FEDAI_NON_MEMBER_DATA_COLLECTION_LOGIN_ID'] != "")
    {
      redirect(site_url('NonmemberData/member_details'),'refresh');
    }

    if(isset($_POST) && count($_POST) > 0)
    {
      $this->form_validation->set_rules('member_no','Membership No','trim|required|xss_clean|callback_validation_check_member_no',array('required' => 'Please enter the %s'));
      $this->form_validation->set_rules('code','code','trim|required|xss_clean|callback_validation_check_captcha',array('required' => 'Please enter the %s'));	
              
      if($this->form_validation->run())
      {
        $this->Captcha_model->generate_captcha_img('FEDAI_NON_MEMBER_DATA_COLLECTION_CAPTCHA');
        
        $member_no = $this->input->post('member_no');        
        $result_data = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1', 'isdeleted' => '0'), 'regnumber');
        
        if(count($result_data) > 0)
        {
          $session_data = array('FEDAI_NON_MEMBER_DATA_COLLECTION_LOGIN_ID' => $result_data[0]['regnumber']);
          $this->session->set_userdata($session_data);						
          redirect(site_url('NonmemberData/member_details'),'refresh');	
        }         						
      }			
    }
    
    $captcha_img = $this->Captcha_model->generate_captcha_img('FEDAI_NON_MEMBER_DATA_COLLECTION_CAPTCHA');
		$data['image'] = $captcha_img;

		$this->load->view('nonmember-data/login', $data);
	} //END : LOGIN
	/*---------- END ----------------------------------------------*/

  public function generatecaptchaajax() //START : GENERATE CAPTCHA CODE AJAX
	{
		$this->load->model('Captcha_model');
		echo $this->Captcha_model->generate_captcha_img('FEDAI_NON_MEMBER_DATA_COLLECTION_CAPTCHA');
	} //END : GENERATE CAPTCHA CODE AJAX
  
  /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
  public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    if(isset($_POST) && $_POST['code'] != "")
    {
      if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('code')); }
      else if($type == '0') { $captcha = $str; }
      
      $session_captcha = $this->session->userdata('FEDAI_NON_MEMBER_DATA_COLLECTION_CAPTCHA');
      
      if($captcha == $session_captcha)
      {
        $return_val_ajax = 'true';
      }
    }   
    
    if($type == '1') { echo $return_val_ajax; }
    else if($type == '0') 
    { 
      if($return_val_ajax == 'true') { return TRUE; } 
      else if($_POST['code'] != "")
      {
        $this->form_validation->set_message('validation_check_captcha','Please enter the valid code');
        return false;
      }
    }
  }/******** END : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/

  /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT MEMBER NO ********/
  public function validation_check_member_no($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
  {
    $return_val_ajax = 'false';
    $valid_member_no_arr = $this->valid_member_no_arr;

    if(isset($_POST) && $_POST['member_no'] != "")
    {
      if($type == '1') { $member_no = $this->security->xss_clean($this->input->post('member_no')); }
      else if($type == '0') { $member_no = $str; }
      
      if(in_array($member_no, $valid_member_no_arr))      
      {
        $result_data = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1', 'isdeleted' => '0'), 'regnumber');  
        
        if(count($result_data) > 0) 
        {
          $return_val_ajax = 'true';
        }  
      }  

      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['member_no'] != "")
        {
          $this->form_validation->set_message('validation_check_member_no','Please enter the valid Membership No');
          return false;
        }
      }
    }
  }/******** END : VALIDATION FUNCTION TO CHECK THE CORRECT MEMBER NO ********/

  public function logout()
  {
    $session_data = array('FEDAI_NON_MEMBER_DATA_COLLECTION_LOGIN_ID' => "");
    $this->session->set_userdata($session_data);
    redirect(site_url('NonmemberData/login'),'refresh');
  }

  function check_after_login_pages()
  {
    if(!isset($_SESSION['FEDAI_NON_MEMBER_DATA_COLLECTION_LOGIN_ID']) || $_SESSION['FEDAI_NON_MEMBER_DATA_COLLECTION_LOGIN_ID'] == "")
    {
      redirect(site_url('NonmemberData/logout'),'refresh');
    }
  }
  
  public function member_details() 
	{
    $this->check_after_login_pages();

		$data = array();   
		
    $mode = 'Add';
    $valid_member_no_arr = $this->valid_member_no_arr;
    $member_no = $_SESSION['FEDAI_NON_MEMBER_DATA_COLLECTION_LOGIN_ID'];
    if(in_array($member_no, $valid_member_no_arr))      
    {
      $form_data = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1', 'isdeleted' => '0'), 'regid, regnumber, namesub, firstname, middlename, lastname, email, mobile');  

      if(count($form_data) > 0)
      {
        $data['form_data'] = $form_data;

        $non_member_data = $this->master_model->getRecords('nonmember_bank_details', array('member_no' => $member_no));
        $data['non_member_data'] = $non_member_data;
        
        if(count($non_member_data) > 0) { $mode = 'Update'; }
      }
      else
      {
        $this->session->set_flashdata('error', 'Record not found for the member');
        redirect(site_url('NonmemberData/member_details'),'refresh');
      }
    }

    $file_upload_error = '';
    $file_upload_error_flag = 1;
    if(isset($_POST) && count($_POST) > 0)
    {
      $empidproofphoto_req_flg = 'n';
      if($mode == 'Add') { $empidproofphoto_req_flg = 'y'; }
      else if($mode == 'Update' && $non_member_data[0]['empidproofphoto'] == "") { $empidproofphoto_req_flg = 'y'; }

      $this->form_validation->set_rules('emp_bank_name', 'Employee Bank Name', 'trim|required|max_length[100]|xss_clean', array('required' => "Please enter the %s"));
      $this->form_validation->set_rules('empidproofphoto', 'Employee Id proof', 'callback_fun_validate_file_upload[empidproofphoto|' . $empidproofphoto_req_flg . '|jpg,jpeg,pdf|25|Employee Id proof|10]'); //callback parameter separated by pipe 'input name|required|allowed extension|max size in kb|display name|min size in kb'

      if ($this->form_validation->run())
      {
        $add_data = array();
        $add_data['member_no'] = $member_no;
        $add_data['bank_name'] = $this->input->post('emp_bank_name');

        if (isset($_FILES['empidproofphoto']['name']) && ($_FILES['empidproofphoto']['name'] != '')) 
        {
          $img = "empidproofphoto";
          $tmp_inputempidproof = $member_no.'_'.date('YmdHis') . rand(0, 100);
          $new_filename = 'nonmem_empidproof_' . $tmp_inputempidproof;
          $config = array(
            'upload_path' => './uploads/empidproof',
            'allowed_types' => 'jpg|jpeg|JPG|JPEG|pdf',
            'file_name' => $new_filename,
          );
          $this->upload->initialize($config);
          $size = @getimagesize($_FILES['empidproofphoto']['tmp_name']);
          if ($size) 
          {
            if ($this->upload->do_upload($img)) 
            {
              $dt = $this->upload->data();
              $add_data['empidproofphoto'] = $dt['file_name'];
            } 
            else 
            {
              $file_upload_error_flag = 0;
              $file_upload_error = $this->upload->display_errors();
            }
          } 
          else 
          {
            $file_upload_error_flag = 0;
            $file_upload_error = 'The filetype you are attempting to upload is not allowed';
          }
        }

        if($file_upload_error_flag == 1)
        {       
          if($mode == "Add") 
          {
            $add_data['created_on'] = date("Y-m-d H:i:s");
            if($this->master_model->insertRecord('nonmember_bank_details ',$add_data))
            {
              $this->session->set_flashdata('success', 'The non member data added succesfully.');						
            }
            else
            {
              $this->session->set_flashdata('error', 'Error occurred. Please try again');
            }

            redirect(site_url('NonmemberData/member_details'));
          }
          else if ($mode == "Update")
          {
            $add_data['updated_on'] = date("Y-m-d H:i:s");
            if($this->master_model->updateRecord('nonmember_bank_details', $add_data, array('member_no' => $member_no)))
            {
              $this->session->set_flashdata('success', 'The non member data updated succesfully.');	
            }
            else
            {
              $this->session->set_flashdata('error', 'Error occurred. Please try again');
            }

            redirect(site_url('NonmemberData/member_details'));
          }
        }
      }
    }

    $data['mode'] = $mode;
    $data['file_upload_error'] = $file_upload_error;
    $this->load->view('nonmember-data/member_details', $data);
  }

  /******** START : VALIDATION FUNCTION TO CHECK VALID FILE ********/
  function fun_validate_file_upload($str,$parameter) // Custom callback function for check valid file
  {
    $result = $this->inc_fun_validate_file_upload($parameter);
    if($result['flag'] == 'success') { return true; }
    else
    {
      $this->form_validation->set_message('fun_validate_file_upload', $result['response']);
      return false;
    }
  }/******** END : VALIDATION FUNCTION TO CHECK VALID FILE ********/

  /******** START : CALLBACK CI VALIDATION FUNCTIONS FOR CORRECT FILE ********/
  //parameter : separated by pipe 'input name|required|allowed extension|max size in kb|input display name|min size in kb'
  //eg. 'pan_photo|y|jpg,jpeg,png|20|pan photo|50'
  //callback_fun_validate_file_upload[pan_photo|y|jpg,jpeg,png|20|pan photo|50]
  public function inc_fun_validate_file_upload($parameter='') 
  {
    $result['flag'] = 'success';
    $result['response'] = '';

    $parameter_str = $parameter; 
    $parameter_err = explode('|',$parameter_str);

    $input_name = $is_required = $allow_ext = $max_size_in_kb = $input_disp_name = $min_size_in_kb = '';
    if(count($parameter_err) > 0 )
    {
      if(isset($parameter_err[0])) { $input_name = $parameter_err[0]; }
      if(isset($parameter_err[1])) { $is_required = $parameter_err[1]; }
      if(isset($parameter_err[2])) { $allow_ext = $parameter_err[2]; }
      if(isset($parameter_err[3])) { $max_size_in_kb = $parameter_err[3]; }
      if(isset($parameter_err[4])) { $input_disp_name = $parameter_err[4]; }
      if(isset($parameter_err[5])) { $min_size_in_kb = $parameter_err[5]; }
    }

    /* echo '<br>input_name : '.$input_name;
    echo '<br>is_required : '.$is_required;
    echo '<br>allow_ext : '.$allow_ext; 
    echo '<br>max_size_in_kb : '.$max_size_in_kb;
    echo '<br>input_disp_name : '.$input_disp_name; exit; */
    
    if($is_required == 'y')
    {
      if(empty($_FILES[$input_name]['name']))
      {
        $result['response'] = 'Please select the '.$input_disp_name;
        $result['flag'] = 'error';
        return $result;
      }

      // Check if the file was uploaded without errors
      if ($_FILES[$input_name]['error'] !== UPLOAD_ERR_OK) 
      {
        $result['response'] = 'Error uploading the '.$input_disp_name;
        $result['flag'] = 'error';
        return $result;
      }
    }

    if(!empty($_FILES[$input_name]['name']))
    {
      // Check if the uploaded file is an image
      //$allowed_types = array('jpg', 'jpeg', 'png', 'gif');
      $allowed_types = explode(",",$allow_ext);

      $file_ext = pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION);

      if (!in_array(strtolower($file_ext), $allowed_types)) 
      {
        $result['response'] = 'Only '.strtoupper(str_replace(",",", ",$allow_ext)).' files are allowed';
        $result['flag'] = 'error';
        return $result;
      }

      if ($_FILES[$input_name]['size'] == 0) 
      {
        $result['response'] = 'The file size should be more than 0KB';
        $result['flag'] = 'error';
        return $result;
      }

      // Check maximum file size
      $max_size = $max_size_in_kb; // 20kb
      if ($_FILES[$input_name]['size'] > $max_size * 1024) 
      {
        $result['response'] = 'The file size should not be more than '.$max_size.'KB';  
        $result['flag'] = 'error'; 
        return $result;       
      }

      // Check minimum file size
      $min_size = $min_size_in_kb; // 20kb
      if ($_FILES[$input_name]['size'] < $min_size * 1024) 
      {
        $result['response'] = 'The file size should not be less than '.$min_size.'KB';  
        $result['flag'] = 'error'; 
        return $result;       
      }
    }
    
    return $result;
  }/******** END : CALLBACK CI VALIDATION FUNCTIONS FOR CORRECT FILE ********/

	/*----- Created by nonmember listing function by gaurav at 02-07-2024 -----*/
	function listing()
	{
		$nonmember_data = $this->master_model->getRecords('nonmember_bank_details');
			
		$data['nonmember_data'] = $nonmember_data;

		$this->load->view('nonmember-data/index',$data);
	}  
}
