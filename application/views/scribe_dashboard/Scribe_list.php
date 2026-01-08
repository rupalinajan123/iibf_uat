<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Scribe_list extends CI_Controller 
	{
		public function __construct()
  		{ 		
   			parent::__construct();
			//$this->load->helper(array('form', 'url'));
			//this->load->helper('page');
			/* Load form validation library */ 
			//$this->load->library('upload');
			//$this->load->library('email');
			//$this->load->library('pagination');
			//$this->load->library('table');	
			
			$this->load->library('form_validation');
			$this->load->model('Master_model'); 
			$this->load->library('session');
			$this->load->model('Emailsending');    
			
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
		}

		public function index()
    	{
			/*TOTAL SCRIBE APPLICATION*/
			$this->db->select('count(*) as rows');
			$this->db->where('mobile_scribe != 0');
			$data['total_scribe_reg']= $total_scribe_reg= $this->master_model->getRecords('scribe_registration');
			$data['count1']= $count1 = $total_scribe_reg[0]['rows'];//total scribe

			/*TOTAL SPECIAL APPLICATION*/
			$this->db->select('count(*) as rows');
			$this->db->where('mobile_scribe = 0');
			$data['total_special']= $total_special= $this->master_model->getRecords('scribe_registration');
			$data['count2']= $count2 = $total_special[0]['rows']; // total special
			

			/*TOTAL APPROVED SCRIBE APPLICATION*/
			$this->db->select('count(*) as rows');
			$this->db->where('mobile_scribe != 0');
			$data['total_scribe_reg']= $total_scribe_reg= $this->master_model->getRecords('scribe_registration',array('scribe_approve' => '1'));
			$data['count3']= $count3 = $total_scribe_reg[0]['rows']; //approved scribe
			

			/*TOTAL SPECIAL APPROVED APPLICATION*/
			$this->db->select('count(*) as rows');
			$this->db->where('mobile_scribe = 0');
			$data['total_special']= $total_special= $this->master_model->getRecords('scribe_registration',array('scribe_approve' => '1'));
			$data['count4']= $count4 = $total_special[0]['rows']; //approved special
			

			/*TOTAL REJECTED SCRIBE APPLICATION*/
			$this->db->select('count(*) as rows');
			$this->db->where('mobile_scribe != 0');
			$data['total_scribe_reg']= $total_scribe_reg= $this->master_model->getRecords('scribe_registration',array('scribe_approve' => '3'));
			$data['count5']= $count5 = $total_scribe_reg[0]['rows']; //rejected scribe
			

			/*TOTAL SPECIAL REJECTED APPLICATION*/
			$this->db->select('count(*) as rows');
			$this->db->where('mobile_scribe = 0');
			$data['total_special']= $total_special= $this->master_model->getRecords('scribe_registration',array('scribe_approve' => '3'));
			$data['count6']= $count6 = $total_special[0]['rows']; //rejected special

			/*DAILY OR TODAY'S APPLICATION DETAILS*/
			$today = date('Y-m-d');
			
			/*TOTAL SCRIBE APPLICATION TODAY*/
			$this->db->select('count(*) as rows');
			$array = array('created_on' => $today, 'mobile_scribe !='=>'0');
			$this->db->where($array);
			$data['total_scribe_reg']= $total_scribe_reg= $this->master_model->getRecords('scribe_registration');
			$data['count7']= $count7 = $total_scribe_reg[0]['rows'];//total scribe
			//echo $this->db->last_query();die;

			/*TOTAL SPECIAL APPLICATION TODAY*/
			$this->db->select('count(*) as rows');
			$array = array('created_on' => $today, 'mobile_scribe'=>'0');
			$this->db->where($array);
			$data['total_special']= $total_special= $this->master_model->getRecords('scribe_registration');
			$data['count8']= $count8 = $total_special[0]['rows']; // total special
			

			/*TOTAL APPROVED SCRIBE APPLICATION TODAY*/
			$this->db->select('count(*) as rows');
			$array = array('mobile_scribe !='=>'0');
			$this->db->like('modified_on',$today);
			$this->db->where($array);
			$data['total_scribe_reg']= $total_scribe_reg= $this->master_model->getRecords('scribe_registration',array('scribe_approve' => '1'));
			$data['count9']= $count9 = $total_scribe_reg[0]['rows']; //approved scribe
			//echo $this->db->last_query();die;

			/*TOTAL SPECIAL APPROVED APPLICATION TODAY*/
			$this->db->select('count(*) as rows');
			$array = array('mobile_scribe '=>'0');
			$this->db->where($array);
			$this->db->like('modified_on',$today);
			$data['total_special']= $total_special= $this->master_model->getRecords('scribe_registration',array('scribe_approve' => '1'));
			$data['count10']= $count10 = $total_special[0]['rows']; //approved special
			
			//echo $this->db->last_query();die;

			/*TOTAL REJECTED SCRIBE APPLICATION TODAY*/
			$this->db->select('count(*) as rows');
			$array = array('mobile_scribe !='=>'0');
			$this->db->where($array);
			$this->db->like('modified_on',$today);
			$data['total_scribe_reg']= $total_scribe_reg= $this->master_model->getRecords('scribe_registration',array('scribe_approve' => '3'));
			$data['count11']= $count11 = $total_scribe_reg[0]['rows']; //rejected scribe
			//echo $this->db->last_query();die;

			/*TOTAL SPECIAL REJECTED APPLICATION TODAY*/
			$this->db->select('count(*) as rows');
			$array = array('mobile_scribe !='=>'0');
			$this->db->where($array);
			$this->db->like('modified_on',$today);
			$data['total_special']= $total_special= $this->master_model->getRecords('scribe_registration',array('scribe_approve' => '3'));
			$data['count12']= $count12 = $total_special[0]['rows']; //rejected special
			//echo $this->db->last_query();die;
       		$this->load->view('scribe_dashboard/dashboard',$data);
    	}
		
		public function scribe()
		{
			
			$from_date = $to_date = '';
			//$this->db->where('scribe_approve','1');

			/*SEARCH BASED ON TWO DATES 16/08/2022*/
			$where= '';
			$where .= 'mobile_scribe != 0';
			if(isset($_POST['btnSearch']))
            {
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}


				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					$where .= " AND s.created_on BETWEEN '".$from_date."' AND '".$to_date."' AND mobile_scribe != 0 ";
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
					//echo $this->db->last_query();die;

				}
				else
				{
					$where .=' AND mobile_scribe != 0';
					$scribe_show = $this->master_model->getRecords('scribe_registration s');
				}	
				/*echo $this->db->last_query();//die;
				print_r($scribe_show);die;*/
			}
			/*END SEARCH BASED ON TWO DATES 16/08/2022*/
			
			/*WHOLE LISITING */
			$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
			//print_r($scribe_show);die;
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['scribe_show']= $scribe_show;
			$this->load->view('scribe_dashboard/scribelist',$data,$where);

			/*DOWNLOAD BASED ON TWO DATES 16/08/2022*/
			if (isset($_POST['download'])) 
			{
				
				$csv = "Scribe registration details \n\n";
				$csv.= "Sr.No, Member no,  Member Name,Email,Mobile,Exam Name,  Center Name, Scribe Name, Scribe Mobile no,Educational qualification,Employment details,Photo ID Number,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date , Created date\n";//Column headers
			
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}
				/*print_r($from_date);
				print_r($to_date);
				die;*/
				
				$this->db->select('*');
				$this->db->join('qualification','qualification.qid = s.specify_qualification');
				
				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					//$where = "s.created_on BETWEEN '".$from_date."' AND '".$to_date."' ";
					$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date'");
					$scribe_show = $this->master_model->getRecords('scribe_registration s');	
					//echo $this->db->last_query();die;
				}
				else
				{
					//$where='';
					$scribe_show = $this->master_model->getRecords('scribe_registration s');
					//$query=$this->db->get();
					//$scribe_show= $query->result_array();
				}		
				/*echo $this->db->last_query();//die;
				echo'<pre>';
			    print_r($scribe_show);die;*/

				if(!empty($scribe_show))
				{
					$i=1;
					foreach($scribe_show as $record)
					{					
						// print_r($record);exit;
						$csv.= $i.','.$record['regnumber'].','.$record['firstname'].','.$record['email'].','.$record['mobile'].','.$record['exam_name'].',"'.$record['center_name'].'",'.$record['name_of_scribe'].','.$record['mobile_scribe'].','.$record['name'].','.$record['emp_details_scribe'].','.$record['photoid_no'].','.$record['visually_impaired'].','.$record['orthopedically_handicapped'].','.$record['cerebral_palsy'].','.$record['exam_date'].','.$record['created_on']."\n";
						$i++;
						//echo'<pre>';print_r($csv);die;
					}
				}
				$filename = "Scribe registration details.csv";
				header('Content-type: application/csv');
				header('Content-Disposition: attachment; filename='.$filename);
				$csv_handler = fopen('php://output', 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);die;

			}
			/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/

		}

		/*VIEW SCRIBE APPLIATION DETAILS POOJA MANE:11/08/2022*/
		public function approved_list()
		{
			$from_date = $to_date = '';
			$where= 'mobile_scribe != 0';
			//echo "approved";die;
			/*SEARCH BASED ON TWO DATES 16/08/2022*/
			
			if(isset($_POST['btnSearch1']))
            {
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}


				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date' AND mobile_scribe != 0");
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '1'));
					//echo $this->db->last_query();//die;
				}
				else
				{
					$this->db->where("mobile_scribe != 0");
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '1'));
					//echo $this->db->last_query();die;
				}	

			}
			else{
					/*WHOLE LISITING */
					$this->db->where("mobile_scribe != 0");
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '1'));
					$data['from_date'] = $from_date;
					$data['to_date'] = $to_date;
					$data['scribe_show']= $scribe_show;
					//$this->load->view('scribe_dashboard/approved_rejected_list',$data);
				}
			/*END SEARCH BASED ON TWO DATES 16/08/2022*/
				$data['from_date'] = $from_date;
				$data['to_date'] = $to_date;
				$data['scribe_show']= $scribe_show;
				$this->load->view('scribe_dashboard/approved_rejected_list',$data);
			
			//$where .= "scribe_approve = 1";
			//echo $this->db->last_query();die;
			//print_r($scribe_show);die;
			
			
			/*DOWNLOAD BASED ON TWO DATES 16/08/2022*/
			if (isset($_POST['download1'])) 
			{
				
				$csv = "Scribe Approved details \n\n";
				$csv.= "Sr.No, Member no,  Member Name,Email,Mobile,Exam Name,  Center Name, Scribe Name, Scribe Mobile no,Educational qualification,Employment details,Photo ID Number,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date , Created date\n";//Column headers

			
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}
				/*print_r($from_date);
				print_r($to_date);
				die;*/	
				$this->db->select('*');
				$this->db->join('qualification','qualification.qid = s.specify_qualification');
	
				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					/*$where = "s.created_on BETWEEN '".$from_date."' AND '".$to_date."' AND scribe_approve = 1 ";*/
					$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date'");
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '1'));
					
				}
				else
				{
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '1'));
				}
				//echo $this->db->last_query();die;
			    //print_r($scribe_show);die;
				if(!empty($scribe_show))
				{
					$i=1;
					foreach($scribe_show as $record)
					{					
						// print_r($record);exit;
						$csv.= $i.','.$record['regnumber'].','.$record['firstname'].','.$record['email'].','.$record['mobile'].','.$record['exam_name'].',"'.$record['center_name'].'",'.$record['name_of_scribe'].','.$record['mobile_scribe'].','.$record['name'].','.$record['emp_details_scribe'].','.$record['photoid_no'].','.$record['visually_impaired'].','.$record['orthopedically_handicapped'].','.$record['cerebral_palsy'].','.$record['exam_date'].','.$record['created_on']."\n";
						$i++;
						//print_r($csv);die;
					}
				}
				$filename = "Scribe Approved details.csv";
				header('Content-type: application/csv');
				header('Content-Disposition: attachment; filename='.$filename);
				$csv_handler = fopen('php://output', 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);die;

			}
			/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/

		}

		/*VIEW SCRIBE APPLIATION DETAILS POOJA MANE:11/08/2022*/
		public function rejected_list()
		{
			$from_date = $to_date = '';
			//echo "reject";die;
			/*SEARCH BASED ON TWO DATES 16/08/2022*/
			$where= '';
			if(isset($_POST['btnSearch2']))
            {
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}


				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date' AND mobile_scribe != 0");
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '3'));
				}
				else
				{
					$this->db->where("mobile_scribe != 0");
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '3'));
				}	

			}
			/*END SEARCH BASED ON TWO DATES 16/08/2022*/
			else
				{
					/*WHOLE LISITING */
					$this->db->where("mobile_scribe != 0");
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '3'));
					$data['from_date'] = $from_date;
					$data['to_date'] = $to_date;
					$data['scribe_show']= $scribe_show;
				}
			//echo $this->db->last_query();die;	
			
			//$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '3'));
			//print_r($scribe_show);die;
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['scribe_show']= $scribe_show;
			$this->load->view('scribe_dashboard/approved_rejected_list',$data);
			
			/*DOWNLOAD BASED ON TWO DATES 16/08/2022*/
			if (isset($_POST['download2'])) 
			{
				
				$csv = "Scribe Rejected details \n\n";
				$csv.= "Sr.No, Member no,  Member Name,Email,Mobile,Exam Name,  Center Name, Scribe Name, Scribe Mobile no,Educational qualification,Employment details,Photo ID Number,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date , Created date\n";//Column headers
			
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}
				/*print_r($from_date);
				print_r($to_date);
				die;*/
				$this->db->select('*');
				$this->db->join('qualification','qualification.qid = s.specify_qualification');

				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					/*$where = "s.created_on BETWEEN '".$from_date."' AND '".$to_date."' AND scribe_approve = 1 ";*/
					$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date' AND mobile_scribe != 0");
					$this->db->group_by('s.id');
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '3'));
					
				}
				else
				{
					$this->db->where("mobile_scribe != 0");
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '3'));
				}		
				/*echo $this->db->last_query();//die;	
			    print_r($scribe_show);die;*/
				if(!empty($scribe_show))
				{
					$i=1;
					foreach($scribe_show as $record)
					{					
						// print_r($record);exit;
						$csv.= $i.','.$record['regnumber'].','.$record['firstname'].','.$record['email'].','.$record['mobile'].','.$record['exam_name'].',"'.$record['center_name'].'",'.$record['name_of_scribe'].','.$record['mobile_scribe'].','.$record['name'].','.$record['emp_details_scribe'].','.$record['photoid_no'].','.$record['visually_impaired'].','.$record['orthopedically_handicapped'].','.$record['cerebral_palsy'].','.$record['exam_date'].','.$record['created_on']."\n";
						$i++;
						//print_r($csv);die;
					}
				}
				$filename = "Scribe Rejected details.csv";
				header('Content-type: application/csv');
				header('Content-Disposition: attachment; filename='.$filename);
				$csv_handler = fopen('php://output', 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);die;

			}
			/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/

		}


		/*VIEW SCRIBE APPLIATION DETAILS POOJA MANE:04/08/2022*/
		public function view($id)
		{
			//echo "string";die;
			$id=$this->uri->segment(4);
			$name = $this->db->query("SELECT CONCAT(firstname,' ', lastname) AS name FROM scribe_registration where 'id' = $id");
			$data['fullname'] = $name->result_array();

			$res_arr = $this->master_model->getRecords("scribe_registration",array('id'=> $id));
			$specify_qualification = $res_arr[0]['specify_qualification'];
			
			$this->db->select('name as education');
			$this->db->join('scribe_registration s','s.specify_qualification = q.qid');
			$this->db->group_by('name');
			$data['specify_qualification'] = $specify_qualification = $this->master_model->getRecords('qualification q',array('s.id'=> $id));
			//print_r($specify_qualification);die;
			
			$data['reuest_list'] = $res_arr;
			//print_r($data['fullname'])	;die;	
			$this->load->view('scribe_dashboard/view_scribe',$data);
		}

		/*APPROVE SCRIBE APPLICATION POOJA MANE: 08/08/2022 */
		public function approve()
	    {
	        
			//$reason = $this->input->post('reject_reason');
	    	//$id = $this->input->post('id');
	    	$id = $this->uri->segment('4');
	    	//print_r($id);die;
	        if(is_numeric($id))
	        {
	            $this->db->where('id',$id);
	            $arr_data = $this->master_model->getRecords('scribe_registration');
	            /*print_r($arr_data);
	            echo "<br>";
	            print_r($arr_data[0]['mobile_scribe']);*/
	            //die;
		            if(empty($arr_data))
		            {
		                $this->session->set_flashdata('error','Invalid Selection Of Record');
		                redirect(base_url().'scribe_dashboard/Scribe_list');
		            }

		            $arr_update = array('scribe_approve' => '1');
		
		            $arr_where = array("id" => $id);
		            $result = $this->master_model->updateRecord('scribe_registration',$arr_update,$arr_where);
		        	
		            if($result > 0)
		            { 
		                $this->session->set_flashdata('success','Application Approved Successfully.');

		                /*APPROVE SCRIBE APPLICATION MAIL POOJA MANE: 10/08/2022 */
		                $regnumber = $arr_data[0]['regnumber'];
						$exam_code = $arr_data[0]['exam_code'];
						$scribe_info=$this->master_model->getRecords('scribe_registration',array('regnumber'=>$regnumber,'exam_code'=>$exam_code,'scribe_approve'=>'1',"id" => $id));
						//
						//echo '<pre>';print_r($scribe_info);die;
						$name = $scribe_info[0]['firstname'];
						$exam_name = $scribe_info[0]['exam_name'];
						$center_name = $scribe_info[0]['center_name'];
						$email = $scribe_info[0]['email'];
						$name_of_scribe = $scribe_info[0]['name_of_scribe'];
						$mobile_scribe = $scribe_info[0]['mobile_scribe'];
						$applied_date = $scribe_info[0]['created_on'];
						$photoid_no = $scribe_info[0]['photoid_no'];
						$date = date("d-m-Y", strtotime($applied_date));
						$final_str = '';
						$today = date('Y-m-d H:i:s');

					
						//print_r($today);die;

						if(!empty($scribe_info))
						{
							
							$final_str.= 'Dear '.$name.',';
							$final_str.= '<br/><br/>';
							$final_str.= 'You have been granted permission for scribe as per scan copy of declaration form/disability certificate submitted by you for following date/s and subject/s';
							$final_str.= '<br/>';
							$final_str.=  'a)';
							$final_str.= '<br/>';
							$final_str.=  'b)';
							$final_str.= '<br/><br/>';
							$final_str.=  'Your scribe details given below:';
							$final_str.= '<br/><br/>';
							$final_str.=  'Name    :'.$name_of_scribe;
							$final_str.= '<br/>';
							$final_str.=  'ID proof:'.$photoid_no;
							$final_str.= '<br/><br/>';
							$final_str.=  'You are requested to carry and produce the following at the Examination Venue without fail:';
							$final_str.= '<br/><br/>';
							$final_str.=  '1)Printout of this e-mail permitting you to use the Scribe';
							$final_str.= '<br/>';
							$final_str.=  '2)Admit Letter issued by the Institute for the Examination';
							$final_str.= '<br/>';
							$final_str.=  '3)Original declaration form along with attested certificate issued in respect of disability   of candidate.';
							$final_str.= '<br/>';
							$final_str.=  '4)	Original ID Proof of the Scribe';
							$final_str.= '<br/><br/>';
							$final_str.= 'You are required to be present along with the scribe at least 30 minutes prior to the start of the examination and submit these documents to the Centre Authorities.';
							$final_str.= '<br/><br/>';  
							$final_str.= 'Thanks and Regards,';
							$final_str.= '<br/>';
							$final_str.= 'Indian Institute of Banking & Finance';

							//print_r($final_str);die;

							$info_arr=array(//'to'=>$email,
										'to'=>'pooja.mane@esds.co.in',
								//'to'=>$email, 
								'from'=>'noreply@iibf.org.in',
								'subject'=>'Your application for scribe permission dated on '.$date,
								'message'=>$final_str
							); 
							
							
							
							if($this->Emailsending->mailsend_attch($info_arr,''))
							{
								$update_data  = array(
									'email_flag' => '2' //Email flag= 2 on approval of application
									
								);
								
								$this->master_model->updateRecord('scribe_registration',
								$update_data,array('regnumber'=>$regnumber,'exam_code'=>$exam_code,
								'remark'=>'1','scribe_approve'=>'1','id'=>$id, 'modified_on' => $today));

								$arr_update = array('modified_on' => $today);
		
		            			$arr_where = array("id" => $id);
		            			$this->master_model->updateRecord('scribe_registration',$arr_update,$arr_where);
								//echo $this->db->last_query();die;
							}
							
						}
						
	            }
	            else
	            {
	                $this->session->set_flashdata('error_message','Oops,Something Went Wrong While Approving Application.');
	            }
	            //print_r($final_str);print_r($mobile_scribe);die;die;
	        }
	        else
	        {
	            $this->session->set_flashdata('error_message','Invalid Selection Of Record');
	        }
	        //echo "string";
	        
	        if ($arr_data[0]['mobile_scribe'] == '0') 
	        {
	        	 //print_r($arr_data[0]['mobile_scribe']);die;
	        	 redirect(base_url().'scribe_dashboard/Scribe_list/special');
	        }
	        else
	        {
	        	//print_r($arr_data[0]['mobile_scribe']);die;
	        	redirect(base_url().'scribe_dashboard/Scribe_list/Scribe');
	        }
	          
	    }

	    /*REJECT SCRIBE APPLICATION POOJA MANE: 08/08/2022 */
	    public function reject()
	    {
	        //echo "reject";//die;
	    	//Get Rejection Details
	    	$reject_reason = $this->input->post('reject_reason');
	    	$id = $this->input->post('id');
	    	//print_r($_POST);die;
        	//print_r($reject_reason);echo "string2";die;
	        if(is_numeric($id))
	        {
	            $this->db->where('id',$id);
	            $arr_data = $this->master_model->getRecords('scribe_registration');
	            
	            if(empty($arr_data))
	            {
	                $this->session->set_flashdata('error','Invalid Selection Of Record');
	                 redirect(base_url().'scribe_dashboard/Scribe_list');
	            }
	           $arr_update = array('scribe_approve' => '3');
	 
	            $arr_where = array("id" => $id);
	            $result = $this->master_model->updateRecord('scribe_registration',$arr_update,$arr_where);
	        	

	        	/*REJECT SCRIBE APPLICATION MAIL POOJA MANE: 10/08/2022 */
	            if($result > 0)
	            { 
	                $this->session->set_flashdata('success','Application Rejected Successfully.');

	                $regnumber = $arr_data[0]['regnumber'];
					$exam_code = $arr_data[0]['exam_code'];
					$scribe_info=$this->master_model->getRecords('scribe_registration',array('regnumber'=>$regnumber,'exam_code'=>$exam_code,'scribe_approve'=>'3',"id" => $id));
					
					$name = $scribe_info[0]['firstname'];
					$exam_name = $scribe_info[0]['exam_name'];
					$center_name = $scribe_info[0]['center_name'];
					$email = $scribe_info[0]['email'];
					$name_of_scribe = $scribe_info[0]['name_of_scribe'];
					$mobile_scribe = $scribe_info[0]['mobile_scribe'];
					$applied_date = $scribe_info [0]['created_on'];
					$date = date($applied_date);
					$final_str = '';
					$today = date('Y-m-d H:i:s');
					$reason = $reject_reason;

					//print_r($today);die;

					if(!empty($scribe_info))
					{
						
						$final_str.= 'Dear '.$name.',';
						$final_str.= '<br/><br/>';
						$final_str.= 'You have not been granted permission for scribe because of the following reasons:';
						$final_str.= '<br/><br/>';
						$final_str.=  '1)Scribe form is not properly filled-in/visible';
						$final_str.= '<br/>';
						$final_str.=  '2)	Handicap Certificate is not correct/properly visible';
						$final_str.= '<br/>';
						$final_str.=  '3)	Online Information not filled/filled  improperly';
						$final_str.= '<br/>';
						$final_str.=  '4)Other reason :'.$reason;
						$final_str.= '<br/><br/>';
						$final_str.=  'Please apply again or contact MSS department.';
						$final_str.= '<br/><br/>';  
						$final_str.= 'Thanks and Regards,';
						$final_str.= '<br/>';
						$final_str.= 'Indian Institute of Banking & Finance';
						
						//print_r($final_str) ;die;
						
						$info_arr=array(//'to'=>$email,
									'to'=>'pooja.mane@esds.co.in', 
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Your application for scribe permission dated on '.$date,
							'message'=>$final_str
						); 
						
						
						if($this->Emailsending->mailsend_attch($info_arr,''))
						{
							$update_data  = array(
								'email_flag' => '2' //Email flag= 2 on rejection of application.
								
							);
							
							$this->master_model->updateRecord('scribe_registration',
							$update_data,
							array('regnumber'=>$regnumber,
							'exam_code'=>$exam_code,
							'remark'=>'1','scribe_approve'=>'3'));

							$arr_update = array('modified_on' => $today);
		
		            		$arr_where = array("id" => $id);
		            		$this->master_model->updateRecord('scribe_registration',$arr_update,$arr_where);
						}
						
					}
	            }
	            else
	            {
	                $this->session->set_flashdata('error_message','Oops,Something Went Wrong While rejecting Application.');
	            }
	        }
	        else
	        {
	            $this->session->set_flashdata('error_message','Invalid Selection Of Record');
	        }
	        redirect(base_url().'scribe_dashboard/Scribe_list');   
	    }

	    /*SEARCH SCRIBE LIST*/
	    public function Search()
	    {
	    	$from_date = $to_date = '';
	    	if(isset($_POST['from_date']) && $_POST['from_date'] != "") 
	    	{ 
	    		$from_date = date("Y-m-d",strtotime($_POST['from_date'])); 
	    	} 
	    	else { $from_date = ''; }

			if(isset($_POST['to_date']) && $_POST['to_date'] != "") 
			{ 
				$to_date = date("Y-m-d",strtotime($_POST['to_date'])); 
			} 
			else { $to_date = ''; }

	    	
	    }

		 /*SPECIAL ASSISTANCE DASHBOARD FUNCTIONS*/
		/*VIEW  SPECIAL SCRIBE APPROVED LIST POOJA MANE:28/08/2022*/
	    public function special()
		{
			
			$from_date = $to_date = '';
			//$this->db->where('scribe_approve','1');

			/*SEARCH BASED ON TWO DATES 16/08/2022*/
			$where ='';
			$where .= "`mobile_scribe` = '0'";
			if(isset($_POST['btnSearchsp']))
            {
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}


				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					$where .= "AND s.created_on BETWEEN '".$from_date."' AND '".$to_date."' ";
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
					//echo $this->db->last_query();die;

				}
				else
				{
					$where.=" AND `mobile_scribe` = '0'";
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
					//echo $this->db->last_query();die;
				}	

			}
			/*END SEARCH BASED ON TWO DATES 16/08/2022*/
			//echo $this->db->last_query();die;
			/*WHOLE LISITING */
			$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
			//echo $this->db->last_query();die;
			//print_r($scribe_show);die;
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['scribe_show']= $scribe_show;
			$this->load->view('scribe_dashboard/specialList',$data,$where);

			/*DOWNLOAD BASED ON TWO DATES 16/08/2022*/
			if (isset($_POST['downloadsp'])) 
			{
				
				$csv = "Scribe registration details \n\n";
				$csv.= "Sr.No, Member no,  Member Name,Email,Mobile,Exam Name,  Center Name, Description,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date , Created date\n";//Column headers
			
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}
				
				$this->db->select('*');
				//$this->db->join('qualification','qualification.qid = s.specify_qualification');

				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					$where .= " AND s.created_on BETWEEN '".$from_date."' AND '".$to_date."' ";
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
					//echo $this->db->last_query();die;

				}
				else
				{
					$where.=" AND `mobile_scribe` = '0'";
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
				}		
				// echo $this->db->last_query();
			 //    print_r($scribe_show);die;
				if(!empty($scribe_show))
				{
					$i=1;
					foreach($scribe_show as $record)
					{					
						// print_r($record);exit;
						$csv.= $i.','.$record['regnumber'].','.$record['firstname'].','.$record['email'].','.$record['mobile'].','.$record['exam_name'].',"'.$record['center_name'].'",'.$record['description'].','.$record['visually_impaired'].','.$record['orthopedically_handicapped'].','.$record['cerebral_palsy'].','.$record['exam_date'].','.$record['created_on']."\n";
						$i++;
						//print_r($csv);die;
					}
				}
				$filename = "Scribe registration details.csv";
				header('Content-type: application/csv');
				header('Content-Disposition: attachment; filename='.$filename);
				$csv_handler = fopen('php://output', 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);die;

			}
			/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/

		}
		
		/*VIEW  SPECIAL SCRIBE APPROVED LIST POOJA MANE:29/08/2022*/
		public function special_approved_list()
		{
			$from_date = $to_date = '';
			$where ='';
			$where .= "`mobile_scribe` = '0'";
			$where .= " AND `scribe_approve` = '1'";
			//echo "approved";die;
			/*SEARCH BASED ON TWO DATES 29/08/2022*/
			
			if(isset($_POST['btnSearch1']))
            {
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}


				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					$where .= "AND s.created_on BETWEEN '".$from_date."' AND '".$to_date."' ";
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
					//echo $this->db->last_query();//die;
				}
				else
				{
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
					//echo $this->db->last_query();die;
				}	
				//echo $this->db->last_query();die;
			}
			else{
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
				}
			/*END SEARCH BASED ON TWO DATES 29/08/2022*/

			/*WHOLE LISITING */
			//$where .= "scribe_approve = 1";
			//echo $this->db->last_query();die;
			//print_r($scribe_show);die;
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['scribe_show']= $scribe_show;
			$this->load->view('scribe_dashboard/special_approved_rejected',$data);
			
			/*DOWNLOAD BASED ON TWO DATES 29/08/2022*/
			if (isset($_POST['download1'])) 
			{
				
				$csv = "Scribe Approved details \n\n";
				$csv.= "Sr.No, Member no,  Member Name,Email,Mobile,Exam Name,  Center Name, Scribe Name, Scribe Mobile no,Educational qualification,Employment details,Photo ID Number,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date , Created date\n";//Column headers
			
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}
				/*print_r($from_date);
				print_r($to_date);
				die;*/	
				$this->db->select('*');
				//$this->db->join('qualification','qualification.qid = s.specify_qualification');

				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					/*$where = "s.created_on BETWEEN '".$from_date."' AND '".$to_date."' AND scribe_approve = 1 ";*/
					$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date' AND mobile_scribe = 0");
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '1'));
					
				}
				else
				{
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '1','mobile_scribe'=>'0'));
				}
				//echo $this->db->last_query();
			    //print_r($scribe_show);die;
				if(!empty($scribe_show))
				{
					$i=1;
					foreach($scribe_show as $record)
					{					
						// print_r($record);exit;
						$csv.= $i.','.$record['regnumber'].','.$record['firstname'].','.$record['email'].','.$record['mobile'].','.$record['exam_name'].',"'.$record['center_name'].'",'.$record['description'].','.$record['visually_impaired'].','.$record['orthopedically_handicapped'].','.$record['cerebral_palsy'].','.$record['exam_date'].','.$record['created_on']."\n";
						$i++;
						//print_r($csv);die;
					}
				}
				$filename = "Scribe Approved details.csv";
				header('Content-type: application/csv');
				header('Content-Disposition: attachment; filename='.$filename);
				$csv_handler = fopen('php://output', 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);die;

			}
			/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/

		}
		/*VIEW  SPECIAL SCRIBE REJECTED LIST POOJA MANE:30/08/2022*/
		public function special_rejected_list()
		{
			$from_date = $to_date = '';
			$where ='';
			$where .= "`mobile_scribe` = '0'";
			$where .= " AND `scribe_approve` = '3'";
			//echo "approved";die;
			/*SEARCH BASED ON TWO DATES 29/08/2022*/
			
			if(isset($_POST['btnSearch2']))
            {
				//echo "string";die;
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}


				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					$where .= "AND s.created_on BETWEEN '".$from_date."' AND '".$to_date."' ";
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
					//echo'date';echo $this->db->last_query();//die;
				}
				else
				{
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
					//echo'nodate';echo $this->db->last_query();die;
				}	
				//echo $this->db->last_query();die;
			}
			else{
					//echo'all';
					$scribe_show = $this->master_model->getRecords('scribe_registration s',$where);
				}
			/*END SEARCH BASED ON TWO DATES 29/08/2022*/

			/*WHOLE LISITING */
			//$where .= "scribe_approve = 1";
			//echo $this->db->last_query();die;
			//print_r($scribe_show);die;
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['scribe_show']= $scribe_show;
			$this->load->view('scribe_dashboard/special_approved_rejected',$data);
			
			/*DOWNLOAD BASED ON TWO DATES 29/08/2022*/
			if (isset($_POST['download2'])) 
			{
				
				$csv = "Scribe Rejected details \n\n";
				$csv.= "Sr.No, Member no,  Member Name,Email,Mobile,Exam Name,  Center Name, Scribe Name, Scribe Mobile no,Educational qualification,Employment details,Photo ID Number,Visually impaired,orthopedical Handicapped,Cerebral Palsy, Exam date , Created date\n";//Column headers
			
				if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
				if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = '';}
				/*print_r($from_date);
				print_r($to_date);
				die;*/	
				$this->db->select('*');
				//$this->db->join('qualification','qualification.qid = s.specify_qualification');

				if (!empty($from_date) && !empty($to_date)) //Check dates are not empty
				{
					/*$where = "s.created_on BETWEEN '".$from_date."' AND '".$to_date."' AND scribe_approve = 1 ";*/
					$this->db->where("s.created_on BETWEEN '$from_date' AND '$to_date'");
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '3','mobile_scribe'=>'0'));
					
				}
				else
				{
					$scribe_show = $this->master_model->getRecords('scribe_registration s',array('scribe_approve' => '3','mobile_scribe'=>'0'));
				}
				echo $this->db->last_query();die;
			    //print_r($scribe_show);die;
				if(!empty($scribe_show))
				{
					$i=1;
					foreach($scribe_show as $record)
					{					
						// print_r($record);exit;
						$csv.= $i.','.$record['regnumber'].','.$record['firstname'].','.$record['email'].','.$record['mobile'].','.$record['exam_name'].',"'.$record['center_name'].'",'.$record['description'].','.$record['visually_impaired'].','.$record['orthopedically_handicapped'].','.$record['cerebral_palsy'].','.$record['exam_date'].','.$record['created_on']."\n";
						$i++;
						//print_r($csv);die;
					}
				}
				$filename = "Scribe Approved details.csv";
				header('Content-type: application/csv');
				header('Content-Disposition: attachment; filename='.$filename);
				$csv_handler = fopen('php://output', 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);die;

			}
			/*END DOWNLOAD BASED ON TWO DATES 16/08/2022*/

		}
		
		
	}			