<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blended_result extends CI_Controller {

	/** 
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct()
	{
		 parent::__construct(); 
	} 
	

//Result for Blended login 
	public function index(){
		$flag=0;
		$data=array();
		$data['error']='';
		
		 if(isset($_POST['submit'])){ 
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
									),
							);
			
				$this->form_validation->set_rules($config);
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encpass = $aes->encrypt($this->input->post('Password'));
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'usrpassword'=>$encpass,
				);
				if ($this->form_validation->run() == TRUE){
					$meminfo=$this->master_model->getRecords('member_registration',$dataarr);
					$table_blended_memdtl='blended_memdtl_'.$this->input->post('exam');
					$table_blended_marks='blended_marks_'.$this->input->post('exam');
				    $table_consolidated='blended_cons_mrk';
					
					 if($this->db->table_exists('blended_marks_'.$this->input->post('exam').'') ){
						 
					
						 if(count($meminfo) > 0){    
						 	
						
						 //marks table
						 $user_info= $this->master_model->getRecords($table_blended_marks, array(
									'exam_id' =>$this->input->post('exam'),
									'regnumber' => $this->input->post('Username')) );
						  
						
						
				
							if(count($user_info) > 0){ 
							    
							    //echo "iibf"; die;
        						// member detils 
        						 			$mem_info= $this->master_model->getRecords($table_blended_memdtl, array(
        									'exam_code' =>$this->input->post('exam'),
        									'member_number' => $this->input->post('Username')) );
        								
        						if(empty($mem_info))
        						{
        							redirect(base_url().'Blended_result');
        						}
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$mem_info[0]['member_number'],
													 'result_name'=>$mem_info[0]['firstname'],
													 'result_period'=>$user_info[0]['exam_period'],
													 'result_examcode'=>$this->input->post('exam'));
								$this->session->set_userdata($result__data);
								redirect(base_url().'Blended_result/dashboard');	
						 } else{
						      
						    //consolidatedmarks table
						 $user_info= $this->master_model->getRecords($table_consolidated, array(
        									'exam_code' =>$this->input->post('exam'),
        									'mem_no' => $this->input->post('Username')) );
				//	echo	  $this->db->last_query();die;
						
						
				
							if(count($user_info) > 0){ 
							    
							     //echo "iibf2"; die;
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info[0]['mem_no'],
													 'result_name'=>$user_info[0]['mem_name'],
													 'result_period'=>$user_info[0]['exam_period'],
													 'result_examcode'=>$this->input->post('exam'));
								$this->session->set_userdata($result__data);
								redirect(base_url().'Blended_result/dashboard');	
						 } 
						     
						 }
						 }else{
							$data['error']='<span style="">Invalid credential.</span>'; 
						 }
					}
					
				
					 
					 //exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array($this->config->item('examCodeCaiib'),'62',$this->config->item('examCodeCaiibElective63'),'64','65','66','67',$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),'72');
		$arr = array('217');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('blended_result/blended_consolidatedlogin',$data);
	}
	

	public function dashboard(){
	    //echo "swati"; die;
	    $member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_p = $this->session->userdata('result_period');
			
			$this->db->select('mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('blended_cons_mrk');
			
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			//$this->db->where(array("exam_period"=>$exam_p));
			$this->db->where(array("exam_code"=>$exam_code));
			$record = $this->db->get();
			$user_info = $record->row();
			//echo $this->db->last_query();
			if(count($user_info) > 0){
				$exam_period=$user_info->exam_period;
			}
			
			if(isset($user_info)){
				$exam_hold_on = $user_info->exam_hold_on;
				$is_consolidated = $user_info->mem_no;
			}else{
				$exam_hold_on = '';
				$is_consolidated = '';
			}
		
		$data = array('is_consolidated'=>$is_consolidated);
		
		$this->load->view('blended_result/blended_dashboard',$data);	
	}
	   
	   	public function blendedconsolidated(){
		try{
			$datearr=array();
			$exam_period='';
			if($this->session->userdata('result_mem_no')==''){ 
				redirect(base_url().'blended_result/');
			}
			
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_p = $this->session->userdata('result_period');
			
			$this->db->select('mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('blended_cons_mrk');
			
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			//$this->db->where(array("exam_period"=>$exam_p));
			$this->db->where(array("exam_code"=>$exam_code));
			//$this->db->group_by('exam_code,exam_period,subject_code');
			$record = $this->db->get();
			$user_info = $record->row();
			if(count($user_info) > 0){
				$exam_period=$user_info->exam_period;
			}
			
			if(isset($user_info)){
				$exam_hold_on = $user_info->exam_hold_on;
				$is_conso = $user_info->mem_no;
			}else{
				$exam_hold_on = '';
				$is_conso = '';
			}
			
			/*$this->db->select('description');
			$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			$exam_name = $exam_result->description;*/
			
			$this->db->select('exam_name_full');
			$exam = $this->db->get_where('blended_result_exam', array('exam_code' => $exam_code,'exam_period'=>$exam_p));
			$exam_result = $exam->row();
			$exam_name = $exam_result->exam_name_full;
			
			$this->db->select('subject_name, marks, result_flag, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('blended_cons_mrk');
			
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			//$this->db->where(array("exam_period"=>$exam_p));
			$this->db->where(array("exam_code"=>$exam_code));
			$this->db->group_by('exam_code,exam_period,subject_code');
			$sql = $this->db->get();
			$result = $sql->result();
			
			

			if(sizeof($result)!=0){
				$is_conso = 'yes';
			}else{
				$is_conso = '';
			}
			
			foreach($result as $dres){
				$datearr[] = $dres->exam_result_date;
			}
			
			
			$this->db->select('result_date,exam_conduct');
			$this->db->from('blended_result_exam');
			$this->db->where(array("exam_code"=>$exam_code,"exam_period"=>$exam_p));
			$result_date = $this->db->get();
			$result_date_info = $result_date->row();
			$exam_result_date =  $result_date_info->result_date;
			$exam_conduct = $result_date_info->exam_conduct;
	
			$data = array("user_info"=>$user_info,"exam_name" => $exam_name,"result"=>$result,"exam_hold_on"=>$exam_hold_on,"printresult"=>$result,"exam_result_date"=>$exam_result_date,"is_conso"=>$is_conso,"exam_code"=>$exam_code,"datearr"=>$datearr,"exam_period"=>$exam_period,"exam_p"=>$exam_p,'exam_conduct'=>$exam_conduct);
			
			$this->load->view('blended_result/blendedconsolidated',$data);
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
		public function blended_result_consolidated(){
			
	
			
			$datearr=array();$exam_period='';
			if($this->session->userdata('result_mem_no')=='')
			{ 
			
				redirect(base_url().'Blended_result');
			}
			
			
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_p = $this->session->userdata('result_period');
		
			$table_blended_memdtl='blended_memdtl_'.$this->session->userdata('result_examcode');
			$table_blended_marks='blended_marks_'.$this->session->userdata('result_examcode');
			
				 if($this->db->table_exists($table_blended_memdtl)){
			
			
						 //member detils 
						 			$mem_info= $this->master_model->getRecords($table_blended_memdtl, array(
									'exam_code' =>$exam_code,
									'member_number' => $member_id) );
						
						 //marks table
						 $this->db->group_by('exam_id,exam_period,subject_id');
						 $marks_info= $this->master_model->getRecords($table_blended_marks, array(
									'exam_id' =>$exam_code,
									'regnumber' => $member_id) );
									
						
					
						
		            //result table
		            
		            	$this->db->order_by('exam_id','DESC');
						 $exam_result_date= $this->master_model->getRecords('blended_result_exam', array(
									'exam_code' =>$exam_code,'exam_period'=>$exam_p),'result_date');
							
						
							//exam name 		
							$this->db->select('description');
						$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
						$exam_result = $exam->row();
						$exam_name = $exam_result->description;
					
			
		$datearr=$exam_conduct=$result=array();
		$exam_hold_on='';
		
	if($marks_info)
	{
		$is_conso=$member_id;
	}else
	{
		$is_conso='';
	}
	
			$data = array("is_conso"=>$is_conso,"marks_info"=>$marks_info,"mem_info"=>$mem_info,"exam_name" => $exam_name,"result"=>$result,"exam_hold_on"=>$exam_hold_on,"printresult"=>$result,"exam_result_date"=>$exam_result_date[0]['result_date'],"exam_code"=>$exam_code,"datearr"=>$datearr,"exam_period"=>$exam_period,"exam_p"=>$exam_p,'exam_conduct'=>$exam_conduct);
			
			 }else{ 
				 $is_conso = '';
				 $data = array("is_conso"=>$is_conso);
			 }
			
			$this->load->view('blended_result/blended_consolidatedresult',$data);
			
		
	}
}