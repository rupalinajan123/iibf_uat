<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Dupcert_stats extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Master_model');
		$this->load->model('UserModel');
	}
	public function index()
	{
	    if(isset($_POST['btnSearch']))
		{
			$from_date = $this->input->post('from_date');
			$to_date = $this->input->post('to_date');
			
			if(!empty($from_date))
			{
				$select = 'DISTINCT(a.id)';
				//$this->db->join('member_registration c','c.regnumber=a.regnumber','LEFT');
				$this->db->join('payment_transaction b','b.ref_id=a.id AND b.member_regnumber=a.regnumber','LEFT');
				if($from_date!='' && $to_date!='')
				{
					//$this->db->where('DATE(date) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1 AND pay_type = 4 AND pay_status = "1" ');
				}
				else if($from_date!='' & $to_date=='')
				{
					//$this->db->where('DATE(date) = "'.$from_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND  b.status=1 AND pay_type = 4 AND pay_status = "1" ');
				}
				$data = $this->UserModel->getRecordCount("duplicate_certificate a", '', '',$select);
				if(!empty($data))
				{
					$data = array('data'=>$data);
				}
				else
				{  
					$data = 'No data';
					$data = array('data'=>$data);
				}
				$this->load->view('admin/dup_cert_stats',$data);
			}
			
			
		}
		else
		{
			$this->load->view('admin/dup_cert_stats');
		}
	}
	public function exam_wise_count()
	{ 
	        //$from_date = $this->input->post('from_date');
	        $from_date ='2017-09-01';
			//$to_date = $this->input->post('to_date');
			$to_date = '2017-09-30';
			
			if(!empty($from_date))
			{
				$select = 'DISTINCT(a.id)';
				//$this->db->join('member_registration c','c.regnumber=a.regnumber','LEFT');
				//$this->db->join('payment_transaction b','b.ref_id=a.id AND b.member_regnumber=a.regnumber','LEFT');
				if($from_date!='' && $to_date!='')
				{
					$this->db->where('DATE(a.created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND a.pay_status = "1" ');
				}
				else if($from_date!='' & $to_date=='')
				{
					$this->db->where('DATE(date) = "'.$from_date.'" AND isactive = "1" AND isdeleted=0 AND b.status=1 AND pay_type = 4 AND pay_status = "1" ');
				}
				$data = $this->UserModel->getRecordCount("duplicate_certificate a", '', '',$select);
				if(!empty($data))
				{
					$data = array('data'=>$data);
				}
				
			}
	   
		Print_r($data);//exit;
	    if(!empty($data))
		{ 
			//$this->db->where('exam_master.exam_delete','0'); 
			$exams=$this->master_model->getRecords('exam_master');
			//print_r($exams);
			$count = 0;
			//$count2 = 0;
			if(!empty($exams))
			{
				 foreach($exams as $key)
				 {
				    $from_date ='2017-09-01';
					$to_date = '2017-09-30';
				    $this->db->where('DATE(created_on) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND pay_status = "1" ');
				    $where = array('exam_code'=>$key['exam_code']);
				    $exams=$this->master_model->getRecords('duplicate_certificate',$where);
				    $res_count = count($exams);
					if(!empty($res_count))
					{
					//echo $this->db->last_query();
					echo '<pre>','Count ='.$res_count.' Exam Code ='.$key['exam_code'].' Exam name is ='.$key['description'],'</pre>'; 
					
					}
					//echo $count2 ;
				   /* .'Exam name is ='.$key['description'] 
				   echo $key['exam_code'];
					foreach($data as $key2)
					{
					print_r($key2);
						if($key['exam_code'] == $key2->exam_code )
						{
						echo 'in';
						   $count++;
						}
					   $count2++;
					}
				   echo '<pre>','Count ='.$count.'Exam Code'.$key['exam_code'].'Exam name is ='.$key['description'],'</pre>';
				   echo $count2 ;*/
				} 
			} 
		}
	    
	}
	
	
 
}
?>