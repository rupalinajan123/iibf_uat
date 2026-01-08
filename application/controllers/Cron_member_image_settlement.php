<?php
	/********************************************************************
		* Created BY: Sagar Matale & Pratibha Purkar on 07-01-2022
		* Updated BY: Sagar Matale & Pratibha Purkar on 11-01-2022
		* Description: This is cron for member image settlement using base64 encoded images 		
	********************************************************************/
	
	defined('BASEPATH') OR exit('No direct script access allowed');		
	class Cron_member_image_settlement extends CI_Controller
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
		{	exit;
			$member_data = $this->get_member_data();
			$this->cron_function_body($member_data);
		}
		
		public function hourly()
		{
			$member_data = $this->get_member_data('hourly');
			$this->cron_function_body($member_data);
		}
		
		public function get_member_data($cron_type='')
		{
			/*$this->db->where('mr.regnumber', '801844671');
			$this->db->limit(1);  */
			//$this->db->where("DATE(mr.createdon)", date('Y-m-d'));
			
			if($cron_type == 'hourly')
			{
				$this->db->where("mr.createdon <= NOW() - INTERVAL 20 MINUTE AND mr.createdon > NOW() - INTERVAL 120 MINUTE");
			}
			else
			{
				$this->db->where("DATE(mr.createdon)", date('Y-m-d'));
			}
			
			//$this->db->where("DATE(mr.createdon) <= '2022-01-10' AND DATE(mr.createdon) >= '2022-01-10'");
			$this->db->join('tbl_member_image_base64 mi', 'mi.reg_id = mr.regid', 'INNER');
			$select = 'mr.regid, mr.regnumber, mr.scannedphoto, mr.idproofphoto, mr.scannedsignaturephoto, mi.id, mi.photo, mi.sign, mi.idproof';
			$member_data = $this->master_model->getRecords('member_registration mr', array('mr.regnumber !='=>'', 'mr.isactive' => '1', 'mr.isdeleted' => 0), $select);
			
			echo $this->db->last_query(); exit;
			//mail('pratibha.purkar@esds.co.in','Test',$this->db->last_query());
			
			return $member_data;
		}
		
		public function cron_function_body($member_data=array())
		{
			if(count($member_data)) 
			{
				foreach ($member_data as $res) 
				{
					$actual_photo = $res['scannedphoto'];
					$actual_sign = $res['scannedsignaturephoto'];
					$actual_id_proof = $res['idproofphoto'];
					
					$photo_error_flag = $sign_error_flag = $id_prrof_error_flag = 0;
					
					if($actual_photo != "") { if(!file_exists('./uploads/photograph/'.$actual_photo)) { $photo_error_flag = 1; } }
					else { $photo_error_flag = 1; }
					
					if($actual_sign != "") { if(!file_exists('./uploads/scansignature/'.$actual_sign)) { $sign_error_flag = 1; } }
					else { $sign_error_flag = 1; }
					
					if($actual_id_proof != "") { if(!file_exists('./uploads/idproof/'.$actual_id_proof)) { $id_prrof_error_flag = 1; } }
					else { $id_prrof_error_flag = 1; }
					
					if($photo_error_flag == 1 || $sign_error_flag == 1 || $id_prrof_error_flag == 1)
					{
						//echo '<br>Found : '.$res['id'];
						
						$up_arr = array();
						if($photo_error_flag == 1)
						{
							$back_up_base64_photo = $res['photo'];
							$restore_photo = 'uploads/photograph/p_'.$res['regnumber'].'.jpg';
							//$restore_photo = 'uploads/sm_tmp/p_'.$res['regnumber'].'.jpg';
							
							$photo_res = $this->base64_to_jpg($back_up_base64_photo, $restore_photo);
							if($photo_res == 'success')
							{
								$up_arr['scannedphoto'] = 'p_'.$res['regnumber'].'.jpg';
							}
						}
						
						if($sign_error_flag == 1)
						{
							$back_up_base64_sign = $res['sign'];
							$restore_sign = 'uploads/scansignature/s_'.$res['regnumber'].'.jpg';
							//$restore_sign = 'uploads/sm_tmp/s_'.$res['regnumber'].'.jpg';
							
							$sign_res = $this->base64_to_jpg($back_up_base64_sign, $restore_sign);
							if($sign_res == 'success')
							{
								$up_arr['scannedsignaturephoto'] = 's_'.$res['regnumber'].'.jpg';
							}								
						}
						
						if($id_prrof_error_flag == 1)
						{
							$back_up_base64_id_prrof = $res['idproof'];
							$restore_id_proof = 'uploads/idproof/pr_'.$res['regnumber'].'.jpg';
							//$restore_id_proof = 'uploads/sm_tmp/pr_'.$res['regnumber'].'.jpg';
							
							$id_proof_res = $this->base64_to_jpg($back_up_base64_id_prrof, $restore_id_proof);
							if($id_proof_res == 'success')
							{
								$up_arr['idproofphoto'] = 'pr_'.$res['regnumber'].'.jpg';
							}
						}
						
						if(count($up_arr) > 0)
						{
							$this->master_model->updateRecord('member_registration', $up_arr, array('regnumber' => $res['regnumber']));
							echo '<br>'.$this->db->last_query();
							
							$this->master_model->insertRecord('tbl_settled_img_log',array('regnumber'=>$res['regnumber'], 'qry'=>json_encode($this->db->last_query())));
							echo '<br>'.$this->db->last_query();
							
						}
					}
				}
			}
		}		
		
		function base64_to_jpg($base64_string, $output_file) 
		{
			// Retrieved values from database
			$encodedImg = explode("base64,",$base64_string)[1];
			
			// Concatenate new file name with existing extention
			// NOTE : This parameter is a full path.  Make sure that the folder 
			// you are writing the file to has the correct permissions allowing the
			// script write access.
			
			// Saving the decoded file with the new file name.
			file_put_contents($output_file, base64_decode($encodedImg));
			
			if(file_exists($output_file))
			{
				return 'success';
			}
			else
			{
				return 'error';
			}
		}		
	}
