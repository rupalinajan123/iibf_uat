<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Downloads extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		if($this->session->id==""){
			redirect('admin/Login');
		}
		
		if($this->session->userdata('roleid')!=1){
			redirect(base_url().'admin/MainController');
		}		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->UserID=$this->session->id;
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
	}
	
	// By VSU : Fuction to fetch appropriate data for given inputs which will output links to download data in files
	public function data($flag)
	{
		$data = array();
		
		$links = '';
		$record_no = 0;
		$links_no = '';
		$data['result_text'] = '';
		
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('from_date','From Date','trim|required');
			$this->form_validation->set_rules('to_date','To Date','trim|required');
			$this->form_validation->set_rules('report_type','Report Type','trim|required');
			$this->form_validation->set_rules('record_no','No. of Records','trim|required|numeric');
			if($this->form_validation->run()==TRUE)
			{
				$from_date = '';
				$to_date = '';
				if(isset($_POST['from_date']) && $_POST['from_date']!='')
				{
					$from_date1 = str_replace('/','-',$_POST['from_date']);
					$from_date = date('Y-m-d',strtotime($from_date1));
				}
				if(isset($_POST['to_date']) && $_POST['to_date']!='')
				{
					$to_date1 = str_replace('/','-',$_POST['to_date']);
					$to_date = date('Y-m-d',strtotime($to_date1));
				}
				
				if($from_date!='' && $to_date!='')
				{
					if($flag == 1)	// DATA
					{
						$this->db->where(' DATE(createdon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND regnumber !="" ');
					}
					if($flag == 2)	// EDITED DATA
					{	
						$this->db->where(' DATE(editedon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND regnumber !="" ');
						//$this->db->where('editedby !=','');
					}
					$reg_cnt = $this->Master_model->getRecordCount('member_registration');
					//echo $this->db->last_query();
					if($reg_cnt>0)
					{
						if($_POST['record_no']!=0)
						{
							$record_no = $_POST['record_no'];
							$links_no = ceil($reg_cnt/$record_no);
							//$links_no = $reg_cnt/$record_no;
							
							$start = 0;
							$iteration = $links_no;
							$i = 1;
							$num_str  = '';
							$num_str_label  = '';
							$id_str  = '';
							while($iteration>0)
							{
								$start_reg_no = ''; 
								$end_reg_no = '';
								if($flag == 1)
								{$this->db->where(' DATE(createdon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND regnumber !="" ');}
								if($flag == 2)
								{	
									$this->db->where(' DATE(editedon) BETWEEN "'.$from_date.'" AND "'.$to_date.'" AND regnumber !="" ');
									//$this->db->where('editedby !=','');	
								}
								$reg_no_res = $this->Master_model->getRecords('member_registration b','','regid,regnumber',array('regnumber'=>'DESC'),$start,$record_no);
								//echo $this->db->last_query()."<br>";
								$reg_no_cnt = count($reg_no_res);
								if($reg_no_cnt)
								{
									//echo "<pre>";print_r($reg_no_res);
									$start_reg_no = $reg_no_res[0]['regnumber'];
									$start_reg_id = $reg_no_res[0]['regid'];
									$arr_cnt = $reg_no_cnt;
									
									//$j=0;
									for($j=0;$j<$reg_no_cnt;$j++)
									{
										//$arr_cnt-=1;
										//echo $j;
										if($reg_no_res[$j]['regnumber']!='')
										{
											$start_reg_no = $reg_no_res[$j]['regnumber'];
											$start_reg_id = $reg_no_res[$j]['regid'];
											//$j++;
											break; 
										}
									}
									
									while($arr_cnt)
									{
										$arr_cnt-=1;
										if($reg_no_res[$arr_cnt]['regnumber']!='')
										{
											$end_reg_no = $reg_no_res[$arr_cnt]['regnumber'];
											$end_reg_id = $reg_no_res[$arr_cnt]['regid'];
											break;
										}
									}
									
									if($start_reg_no != '' &&  $end_reg_no != '')
									{
										if($start_reg_no != $end_reg_no)
										{
											/*$num_str = "'".$start_reg_no."-".$end_reg_no."'";
											$num_str_label = $start_reg_no."-".$end_reg_no;*/
											$num_str = "'".$start_reg_no."-".$end_reg_no."'";
											$num_str_label = $end_reg_no."-".$start_reg_no;
										}
										else
										{
											$num_str = $start_reg_no;
											$num_str_label = $start_reg_no;	
										}
									}
									else
									{
										/*$num_str = $start_reg_no;
										$num_str_label = $start_reg_no;*/
										$num_str = "";
										$num_str_label = "";
									}
										
									
									if($start_reg_id != '' &&  $end_reg_id != '')
									{
										if($start_reg_id != $end_reg_id)
											$id_str = $start_reg_id."-".$end_reg_id;
										else
											$id_str = $start_reg_id;
									}
									else if($start_reg_id!='')
										$id_str = $start_reg_id;
									
									$format = "'".$_POST['report_type']."'";
									$dates = "'".$from_date."|".$to_date."'";
									//echo 'onclick="download_data('.$i.','.$format.','.$dates.','.$num_str.','.$id_str.','.$record_no.')';exit;
									
									if($flag == 1)
									{
										//echo $num_str_label."<br>";
										if($num_str_label!='')
										{
											$links .= ' <div class="col-md-4"><h5>'.$i.'. <a href="'.base_url().'admin/downloads/download_data/'.$_POST['report_type'].'/'.$from_date."/".$to_date.'/'.$id_str.'/'.$num_str_label.'" target="_blank">'.$num_str_label.'</a></h5></div>'; 
											
										}
										//echo $i." -- ".$links;
									}
									else if($flag == 2)
									{
										if($num_str_label!='')
										{
											$links .= ' <div class="col-md-4"><h5>'.$i.'. <a href="'.base_url().'admin/downloads/download_edited_data/'.$_POST['report_type'].'/'.$from_date."/".$to_date.'/'.$id_str.'/'.$num_str_label.'" target="_blank">'.$num_str_label.'</a></h5></div>'; 
										}
									}
								}
								
								//onclick="download_data('.$i.','.$format.','.$dates.','.$num_str.','.$id_str.','.$record_no.')"
								//'admin/downloads/download_data'.$i.'/'.$format.'/'.$dates.'/'.$num_str.'/'.$id_str.'/'.$record_no.''
								
								$start += $record_no;
								$iteration--;
								$i++;
								
							}
							//exit;						
							$data['result_text'] = 	'<div class="col-md-12">
													  <div class="box box-success">
														<div class="box-header with-border">
														  <h5 class="">Total No.Of Records <strong>: '.$reg_cnt.' </strong></h5> 
														  <h5 class="">No.Of Records in each download file <strong>: '.$record_no.' </strong></h5>
														  <h5 class="">Total No.Of Links <strong>: '.$links_no.' </strong> </h5>
														  '.$links.'
														</div>
													  </div>
													</div>';
							
						}
						else
						{
							$this->session->set_flashdata('error','Invalid No. of Records.');
							redirect(base_url().'admin/Downloads/data');
						}
					}
					else
					{
						$data['result_text'] = '<div class="col-md-12"><div class="box box-danger"><div class="box-header with-border"><h5 class="">No data found...</h5></div></div></div>';
					}
				}
				else
				{
					$this->session->set_flashdata('error','Dates should not be blank');
					redirect(base_url().'admin/Downloads/data');
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		
		$data['links'] = $links;
		$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'admin/MainController"><i class="fa fa-dashboard"></i> Home</a></li>
									<li class="active">Download Data</li>
							   </ol>';
				
		if($flag==1)
			$data['title'] = 'Download Data';
		else
			$data['title'] = 'Download Edited Data';
		$this->load->view('admin/download_data',$data);
	}
	
	public function download_data($format='', $from_date='', $to_date='',$reg_id='', $reg_num='')
	{
		$this->load->helper('phpexcel_helper');
		
		$from_id = '';
		$to_id = '';
		$from_regnum = '';
		$to_regnum = '';
		
		if($reg_id!='')
		{
			if(strpos($reg_id,'-'))
			{
				$reg_ids = explode('-',$reg_id);
				$from_id = $reg_ids[1];	
				$to_id = $reg_ids[0];	
			}
			else
				$from_id = $reg_id;
		}
		
		if($reg_num!='')
		{
			if(strpos($reg_num,'-'))
			{
				$reg_nums = explode('-',$reg_num);
				$from_regnum = $reg_nums[0];	
				$to_regnum = $reg_nums[1];	
			}
			else
				$from_regnum = $reg_num;
		}
		
		if($from_date != '' && $to_date!='')
			$this->db->where(' DATE(createdon) BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
			
		/*if($from_id != '' && $to_id!='')
			$this->db->where(' regid BETWEEN '.$from_id.' AND '.$to_id.'');
		else if($from_id != '')
			$this->db->where('regid',$from_id);*/
			
		if($from_regnum != '' && $to_regnum!='')
			$this->db->where(' regnumber BETWEEN "'.$from_regnum.'" AND "'.$to_regnum.'"');
		else if($from_regnum != '')
			$this->db->where('regnumber',$from_regnum);
		
		$this->db->where(' regnumber !="" ');
		$this->db->order_by('regnumber','DESC');
		$register_details = $this->Master_model->getRecords('member_registration');
		//echo $this->db->last_query();exit;
		if(count($register_details))
		{
			$filename = "IIBFTRAINEE_DATA_(".$from_date." - ".$to_date.")";
			$title = "IIBFTRAINEE_DATA";
			
			$data = "MEM_MEM_NO|MEM_MEM_TYP|MEM_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|ID_CARD_NAME|MEM_ADR_1|MEM_ADR_2|MEM_ADR_3|MEM_ADR_4|MEM_ADR_5|MEM_ADR_6|MEM_PIN_CD|MEM_STE_CD|MEM_DOB|MEM_SEX_CD|MEM_QLF_GRD|MEM_QLF_CD|MEM_INS_CD|BRANCH|MEM_DSG_CD|MEM_BNK_JON_DT|EMAIL|STD_R|PHONE_R|MOBILE|ID_TYPE|ID_NO|BDRNO|TRN_AMT|TRN_DATE|INSTRUMENT_NO|INSTRUMENT_TYPE|AR_FLG|PROC_FLG|FI_YEAR_ID\n";
		
			foreach($register_details as $id => $reg_data) 
			{
				//$data .= $reg_data['regnumber']."|".$reg_data['registrationtype']."|".$reg_data['namesub']."|".$reg_data['firstname']."|".$reg_data['middlename']."|".$reg_data['lastname']."|".$reg_data['displayname']."|".$reg_data['address1']."|".$reg_data['address2']."|".$reg_data['address3']."|".$reg_data['address4']."|".$reg_data['district']."|".$reg_data['city']."|".$reg_data['state']."|".date('d-M-y',strtotime($reg_data['dateofbirth']))."|".$reg_data['gender']."|".$reg_data['qualification']."|".$reg_data['specify_qualification']."|".$reg_data['associatedinstitute']."|".$reg_data['office']."|".$reg_data['designation'].date('d-M-y',strtotime($reg_data['dateofjoin']))."|".$reg_data['email']."|".$reg_data['stdcode']."|".$reg_data['office_phone']."|".$reg_data['mobile']."|".$reg_data['idproof']."|".$reg_data['idNo']."|'BDRNO'|TRN_AMT|TRN_DATE|INSTRUMENT_NO|INSTRUMENT_TYPE|AR_FLG|PROC_FLG|FI_YEAR_ID\n";
				$gender = '';
				if($reg_data['gender']!='')
				{
					if($reg_data['gender'] == 'male')	{ $gender = 'M';}
					else if($reg_data['gender'] == 'female')	{ $gender = 'F';}
				}
				
				$transaction_no = '';
				$amount = '';
				$trans_date = '';
				$payment = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$reg_data['regnumber'],'ref_id'=>$reg_data['regid']),'transaction_no,amount,date');
				if(count($payment))
				{
					$transaction_no = $payment[0]['transaction_no'];
					$amount = $payment[0]['amount'];
					$trans_date = date('d-M-y',strtotime($payment[0]['date']));
				}
				
				$data .= '"'.$reg_data['regnumber'].'"|"'.$reg_data['registrationtype'].'"|"'.$reg_data['namesub'].'"|"'.$reg_data['firstname'].'"|"'.$reg_data['middlename'].'"|"'.$reg_data['lastname'].'"|"'.$reg_data['displayname'].'"|"'.$reg_data['address1'].'"|"'.$reg_data['address2'].'"|"'.$reg_data['address3'].'"|"'.$reg_data['address4'].'"|"'.$reg_data['district'].'"|"'.$reg_data['city'].'"|"'.$reg_data['pincode'].'"|"'.$reg_data['state'].'"|"'.date('j-M-y',strtotime($reg_data['dateofbirth'])).'"|"'.$gender.'"|"'.$reg_data['qualification'].'"|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|"'.$reg_data['office'].'"|"'.$reg_data['designation'].'"|"'.date('j-M-y',strtotime($reg_data['dateofjoin'])).'"|"'.$reg_data['email'].'"|'.$reg_data['stdcode'].'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$amount.'|'.$trans_date.'|||'.$reg_data['optnletter'].'||'."\n";
				
			}
			
			if($format == 'CSV')
			{
				//download_data_excel($register_details, $filename, $title);
				
				$file_data = str_replace("|",",",$data);
				//echo $file_data;exit;
				header("Content-type: application/application/x-gzip");
				header('Content-Disposition: attachement; filename="'.$filename.'.csv.gz"');
				
				logadminactivity($log_title = "Member Registarion data downloaded", $log_message = "Member Registarion data downloaded from $from_date to $to_date in CSV format (Reg. No. $reg_num)");
				
				echo gzencode($file_data,9);exit;
			}
			else if($format == 'TEXT')
			{
				$file_data = str_replace('"','',$data);
				$file_data = str_replace('\n','"\n"',$file_data);
				
				logadminactivity($log_title = "Member Registarion data downloaded", $log_message = "Member Registarion data downloaded from $from_date to $to_date in TEXT format (Reg. No. $reg_num)");
				
				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="'.$filename.'.txt.gz"');
				echo gzencode($file_data, 9); exit();
	
			}
		}
	}
	
	
	public function download_edited_data($format='', $from_date='', $to_date='',$reg_id='', $reg_num='')
	{
		$this->load->helper('phpexcel_helper');
		
		$from_id = '';
		$to_id = '';
		$from_regnum = '';
		$to_regnum = '';
		$edited_data = array();
		
		if($reg_id!='')
		{
			if(strpos($reg_id,'-'))
			{
				$reg_ids = explode('-',$reg_id);
				$from_id = $reg_ids[1];	
				$to_id = $reg_ids[0];	
			}
			else
				$from_id = $reg_id;
		}
		
		if($reg_num!='')
		{
			if(strpos($reg_num,'-'))
			{
				$reg_nums = explode('-',$reg_num);
				$from_regnum = $reg_nums[0];	
				$to_regnum = $reg_nums[1];	
			}
			else
				$from_regnum = $reg_num;
		}
		
		if($from_date != '' && $to_date!='')
			$this->db->where(' DATE(editedon) BETWEEN "'.$from_date.'" AND "'.$to_date.'"');
			
		/*if($from_id != '' && $to_id!='')
			$this->db->where(' regid BETWEEN '.$from_id.' AND '.$to_id.'');
		else if($from_id != '')
			$this->db->where('regid',$from_id);*/
			
		if($from_regnum != '' && $to_regnum!='')
			$this->db->where(' regnumber BETWEEN "'.$from_regnum.'" AND "'.$to_regnum.'" ');
		else if($from_regnum != '')
			$this->db->where('regnumber',$from_regnum);
		
		$this->db->where(' regnumber !="" ');
		$this->db->join('administrators a','a.id=b.editedbyadmin','LEFT');
		$this->db->order_by('regnumber','DESC');
		$register_details = $this->Master_model->getRecords('member_registration b');
		//echo $this->db->last_query();exit;
		if(count($register_details))
		{
			$filename = "IIBFTRAINEE_EDITED_DATA_(".$from_date." - ".$to_date.")";
			$title = "IIBFTRAINEE_EDITED_DATA";
			
			$data = "MEM_MEM_NO|MEM_MEM_TYP|MEM_TLE|MEM_NAM_1|MEM_NAM_2|MEM_NAM_3|ID_CARD_NAME|MEM_ADR_1|MEM_ADR_2|MEM_ADR_3|MEM_ADR_4|MEM_ADR_5|MEM_ADR_6|MEM_PIN_CD|MEM_STE_CD|MEM_DOB|MEM_SEX_CD|MEM_QLF_GRD|MEM_QLF_CD|MEM_INS_CD|BRANCH|MEM_DSG_CD|MEM_BNK_JON_DT|EMAIL|STD_R|PHONE_R|MOBILE|ID_TYPE|ID_NO|BDRNO|TRN_AMT|TRN_DATE|INSTRUMENT_NO|INSTRUMENT_TYPE|AR_FLG|PROC_FLG|FI_YEAR_ID|LOT_NO|LOT_TY|UPD_DT|PHOTO_FLG|SIGNATURE_FLG|ID_FLG|REG_DATE|EDITED_DATAS|MODIFIED_BY|UPDATED_ON\n";
		
			foreach($register_details as $id => $reg_data) 
			{
				$EDITED_DATAS = '';
				$whr = array('regid'=>$reg_data['regid'],'regnumber'=>"'".$reg_data['regnumber']."'",'type'=>'data');
				$edited_data = $this->Master_model->getRecords('profilelogs',$whr,'',array('date'=>'DESC'),0,1);
				if(count($edited_data))
				{
					$EDITED_DATAS .= $edited_data[0]['description'];
				}
				
				if($reg_data['editedby'] == 'admin')
				{	$modified_by = $reg_data['name'];	}
				else
				{	$modified_by = 'Candidate';	}
				
				$transaction_no = '';
				$amount = '';
				$trans_date = '';
				$payment = $this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$reg_data['regnumber'],'ref_id'=>$reg_data['regid']),'transaction_no,amount,date');
				if(count($payment))
				{
					$transaction_no = $payment[0]['transaction_no'];
					$amount = $payment[0]['amount'];
					$trans_date = date('d-M-y',strtotime($payment[0]['date']));
				}
				
				$data .= '"'.$reg_data['regnumber'].'"|"'.$reg_data['registrationtype'].'"|"'.$reg_data['namesub'].'"|"'.$reg_data['firstname'].'"|"'.$reg_data['middlename'].'"|"'.$reg_data['lastname'].'"|"'.$reg_data['displayname'].'"|"'.$reg_data['address1'].'"|"'.$reg_data['address2'].'"|"'.$reg_data['address3'].'"|"'.$reg_data['address4'].'"|"'.$reg_data['district'].'"|"'.$reg_data['city'].'"|"'.$reg_data['pincode'].'"|"'.$reg_data['state'].'"|"'.date('j-M-y',strtotime($reg_data['dateofbirth'])).'"|"'.$reg_data['gender'].'"|"'.$reg_data['qualification'].'"|'.$reg_data['specify_qualification'].'|'.$reg_data['associatedinstitute'].'|"'.$reg_data['office'].'"|"'.$reg_data['designation'].'"|"'.date('j-M-y',strtotime($reg_data['dateofjoin'])).'"|"'.$reg_data['email'].'"|'.$reg_data['stdcode'].'|'.$reg_data['office_phone'].'|'.$reg_data['mobile'].'|'.$reg_data['idproof'].'|'.$reg_data['idNo'].'|'.$transaction_no.'|'.$amount.'|'.$trans_date.'|||'.$reg_data['optnletter'].'||||||'.$reg_data['photo_flg'].'|'.$reg_data['signature_flg'].'|'.$reg_data['id_flg'].'|'.date('j-M-y',strtotime($reg_data['dateofjoin'])).'|"'.$EDITED_DATAS.'"|'.$modified_by.'|'.date('d-m-Y h:i:s A',strtotime($reg_data['editedon']))."\n";
			}
			
			if($format == 'CSV')
			{
				//download_edited_excel($register_details, $filename, $title);
				
				$file_data = str_replace("|",",",$data);
				header("Content-type: application/application/x-gzip");
				header("Content-Disposition: attachment; filename=\"$filename.csv.gz\"");
				
				logadminactivity($log_title = "Member Registarion edited data downloaded", $log_message = "Member Registarion edited data downloaded from $from_date to $to_date in CSV format (Reg. No. $reg_num)");
				
				echo gzencode($file_data,9);exit;
			}
			else if($format == 'TEXT')
			{
				$file_data = str_replace('"','',$data);
				$file_data = str_replace('\n','"\n"',$file_data);
				
				logadminactivity($log_title = "Member Registarion edited data downloaded", $log_message = "Member Registarion edited data downloaded from $from_date to $to_date in TEXT format (Reg. No. $reg_num)");
				
				header("Content-type: application/x-gzip");
				header('Content-Disposition: attachement; filename="'.$filename.'.txt.gz"');
				echo gzencode($file_data, 9); exit();
	
			}
		}
	}
	
}