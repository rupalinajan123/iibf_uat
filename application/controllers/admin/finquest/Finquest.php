<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Finquest extends CI_Controller
{
    public $UserID;
    
    public function __construct()
    {
        parent::__construct();
        /*	if($this->session->userdata('kyc_id') == ""){
        redirect('admin/kyc/Login');
        }		*/
        
        $this->load->model('UserModel');
        $this->load->model('Master_model');
       
        $this->load->helper('pagination_helper');
        $this->load->library('pagination');
        $this->load->helper('upload_helper');
        $this->load->library('email');
        $this->load->model('KYC_Log_model');
        $this->load->model('Emailsending');
        $this->load->model('Chk_KYC_session');
        
    }
    public function index()
    {
          $this->load->view('admin/finquest_dashboard/dashboard');
    }
	//remove from mail list
	public function mail_send()
    {
	
	print_r($_POST);exit;
    #------------send mail to chetan-----------#
					$message="Hello Chetan,<br><br>
					
					Please add following member to fin@quest mailing list.<br>
					
					Member details :<br>
					Email id:  
					".$_POST[''].'<br><br>
					
					-- <br>
			 REGARDS,<br>
			
			POOJA GODSE | PHP DEVELOPER<br>
			
			ESDS Software Solution Pvt. Ltd .<br>
			Website : WWW.ESDS.CO.IN | Email: POOJA.GODSE@ESDS.CO.IN<br>
			Address : Plot No. B- 24 & 25, NICE Industrial Area, Satpur MIDC, <br>   
			Nashik 422 007<br>
			Toll Free: 1800 209 3006 | Landline: +91 (0253) 663 6500<br>
			"_We are committed to creating Lifetime Customer Relationships by<br>
			delivering World Class Managed Data Center Services and Cloud enabled
			Solutions._<br>"

					';
				
					$info_arr_c  = array(
					'to'=>'kyciibf@gmail.com,',
					'from' => 'POOJA.GODSE@ESDS.CO.IN',
					'subject' => 'Add member to finquest subscription list.',
					'message' => $message
					);
					
					$attachpath_c='';	
						       
                 if ($this->Emailsending->mailsend_attch_finquest($info_arr_c, $attachpath_c)) {
						
				 /*log activity for genarate subscription number*/
					$log_title   = "Email send sucessfully to chetan & member added in the finquest mail list :" . $subscription_number . " for id  :" . $this->session->userdata['FinQuest_memberdata']['id'] . "";
					$log_message = serialize($info_arr_c);
					storedUserActivity($log_title, $log_message, '', '');
				} 
					#------------end send mail to chetan-----------#	
					
    }
	
	
		  public function prize_winner_list()
    {
	
$cc_mem_info=array();
	#--------------------contcat classes --------------------------#
		//SELECT *  FROM `contact_classes_registration` WHERE `program_code` LIKE '20' AND `program_prd` = 417 AND `pay_status` = 1

          $cc_mem_info = $this->master_model->getRecords('prizewinners_registration');
		  
	
	#-------------------end -contact classes -------------------------#
	
		  
		    $data['cc_mem_info'] = $cc_mem_info;
		//	$data['cc_sub_info'] = $cc_sub_info;
			
			$this->load->view('admin/finquest_dashboard/prize_winner_list',$data);
		

        //  $this->load->view('admin/finquest_dashboard/dashboard');
    }
	  public function cc_subject_list()
    {
	
	
	#--------------------contcat classes --------------------------#
		
		$cc_subject=$this->db->query(	"SELECT contact_classes_Subject_registration.`sub_code`,contact_classes_Subject_registration.`sub_name`,contact_classes_Subject_registration.`center_code`,count(contact_classes_Subject_registration.`id`)as total_reg,capacity FROM `contact_classes_Subject_registration` INNER JOIN contact_classes_subject_master ON contact_classes_subject_master.exam_prd=contact_classes_Subject_registration.`program_prd` AND contact_classes_subject_master.sub_code=contact_classes_Subject_registration.`sub_code` AND contact_classes_subject_master.center_code=contact_classes_Subject_registration.`center_code` WHERE contact_classes_Subject_registration.`program_prd`=118 group by `program_code`,`program_prd`,`sub_code`,`center_code`");
						$cc_subject_list=  $cc_subject->result_array();
		  
	
	#-------------------end -contact classes -------------------------#
	
		  
		    $data['cc_subject_list'] = $cc_subject_list;
		//	$data['cc_sub_info'] = $cc_sub_info;
			
			$this->load->view('admin/finquest_dashboard/cc_subject_count',$data);
		

        //  $this->load->view('admin/finquest_dashboard/dashboard');
    }	
	
	
   public function member_list()
    {
		
		//query to get the members (SELECT * FROM `fin_quest` WHERE `pay_status` = 1 )
		 $this->db->where('pay_status',1);
          $mem_info = $this->master_model->getRecords('fin_quest');
		  
	
			    $data['mem_info'] = $mem_info;
	   $this->load->view('admin/finquest_dashboard/member_list',$data);
		
	
        //  $this->load->view('admin/finquest_dashboard/dashboard');
    }
	  public function cc_member_list()
    {
	
	
	#--------------------contcat classes --------------------------#
		//SELECT *  FROM `contact_classes_registration` WHERE `program_code` LIKE '20' AND `program_prd` = 417 AND `pay_status` = 1
		$this->db->where('program_code',20);
		$this->db->where('program_prd',417);
		$this->db->where('pay_status',1);
          $cc_mem_info = $this->master_model->getRecords('contact_classes_registration');
		  
	
	#-------------------end -contact classes -------------------------#
	
		  
		    $data['cc_mem_info'] = $cc_mem_info;
		//	$data['cc_sub_info'] = $cc_sub_info;
			
			$this->load->view('admin/finquest_dashboard/cc_member_list',$data);
		

        //  $this->load->view('admin/finquest_dashboard/dashboard');
    }
		  public function dupid_member_list()
    {
	
	
	#--------------------dupid card  --------------------------#
		//SELECT *  FROM `duplicate_icard` WHERE `added_date` > '2017-05-31 23:59:59' AND `pay_status` = '1'
		$this->db->where('added_date >','2017-05-31 23:59:59');
		$this->db->where('pay_status','1');
          $duplicate_icard_info = $this->master_model->getRecords('duplicate_icard');

	#-------------------end -dupid card-------------------------#
	
		  
		    $data['duplicate_icard_info'] = $duplicate_icard_info;
			
			$this->load->view('admin/finquest_dashboard/dupid_member_list',$data);
		

        //  $this->load->view('admin/finquest_dashboard/dashboard');
    }
	 public function asondate()
	{
			 		$kyc_start_date=$this->config->item('kyc_start_date');
					 $new_registration_count=$total_edit_count =$pending_new_list_member=$pending_edit_member=$non_member_pending=$approve_edit_member=$approve_new_member=0;
					/*New registration count */
					
					/*$query = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE `isactive` = '1' AND DATE(`createdon`) >= '".$kyc_start_date."'");
				 	 $new_registration_count= $query->num_rows();*/
					/*echo $this->db->last_query();
					exit;*/
#-------------------------member o new registration count -----------------------------------------#
					$query_M = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  registrationtype   IN ('O') AND  `isactive` = '1' AND DATE(`createdon`) >= '".$kyc_start_date."'");
				 	 $new_registration_count_M= $query_M->num_rows();
			
					$query_NM = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('NM','DB') AND  `isactive` = '1' AND DATE(`createdon`) >= '".$kyc_start_date."'");
				 	 $new_registration_count_NM= $query_NM->num_rows();
#-------------------------End member o new registration count -----------------------------------------#	
	
#-------------------------member o edit registration count -----------------------------------------#				
					$query_M = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  kyc_edit =1 AND registrationtype  IN ('O') AND  `isactive` = '1'");
				 	 $edit_registration_count_M= $query_M->num_rows();
			
					$query_NM = $this->db->query("SELECT `regnumber`  FROM `member_registration` WHERE  kyc_edit  = 1 AND registrationtype  IN ('NM','DB') AND  `isactive` = '1'");
				 	 $edit_registration_count_NM= $query_NM->num_rows();
					
#-------------------------member o edit registration count -----------------------------------------#					
					
					
#--------------------------------------pending for new list-------------------------------------------#
					//member 
						$new_members =$new=array();		
						$new_members=$this->db->query(	"SELECT  `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('O')  AND `isactive` = '1' AND `isdeleted` = 0 AND DATE(`createdon`) BETWEEN '".$kyc_start_date."' AND '". date('Y-m-d')."' AND `kyc_status` = '0' AND `kyc_edit` = 0");
						$new_members1=  $new_members->result_array();
						
						$count_new_member_status='0';
						foreach($new_members1 as $k=>$v) 
						{
							$new[$k] = $v['regnumber'];
							$count_new_member_status++;
						}
						$newarray = implode("','", $new);
					
						$query_new= $this->db->query("SELECT DISTINCT(regnumber) FROM `member_kyc` WHERE mem_type  IN ('O')  AND `regnumber` IN ('".$newarray."')");
					   $pending_kyc = $query_new->num_rows();
					   $pending_new_list_member=$count_new_member_status-$pending_kyc;
						
				//Non memeber 
				
					$new_nonmembers =$new=array();		
						$new_nonmembers=$this->db->query(	"SELECT  `regnumber`  FROM `member_registration` WHERE  registrationtype  IN ('NM','DB')  AND `isactive` = '1' AND `isdeleted` = 0 AND DATE(`createdon`) BETWEEN '".$kyc_start_date."' AND '". date('Y-m-d')."' AND `kyc_status` = '0' AND `kyc_edit` = 0");
						$new_nonmembers1=  $new_nonmembers->result_array();
						
						$count_new_nonmembers_status='0';
						foreach($new_nonmembers1 as $k=>$v) 
						{
							$new[$k] = $v['regnumber'];
							$count_new_nonmembers_status++;
						}
						$newarray = implode("','", $new);
					
						$query_new_nonmembers= $this->db->query("SELECT DISTINCT(regnumber) FROM `member_kyc` WHERE mem_type  IN ('NM','DB')  AND `regnumber` IN ('".$newarray."')");
					   $pending_kyc = $query_new_nonmembers->num_rows();
					   $pending_new_list_nonmembers=$count_new_nonmembers_status-$pending_kyc;
					   
					   
	#--------------------------------------end pending for new list-------------------------------------------#				   
				
#-----------------------------------------pending for Edit list-------------------------------------------------#
						
						//member 
						$edit_members =$new=array();
						$type=array('O');
						$this->db->where_in('registrationtype', $type);
						$this->db->where('kyc_edit','1');
						$this->db->where('kyc_status ','0');
						$edit_members = $this->master_model->getRecords("member_registration",array('isactive'=>'1'),'regnumber');
					
						$count_edited_member_status=0;
						foreach($edit_members as $k=>$v) 
						{
							$edit[$k] = $v['regnumber'];
							$count_edited_member_status++;
						}
						$editarray = implode("','", $edit);
						$query1 = $this->db->query("SELECT DISTINCT(regnumber)  FROM `member_kyc` WHERE mem_type  IN ('O')  AND `regnumber` IN ('".$editarray."')  ");
						$present_member = $query1->num_rows();
						
						$not_prent_member=$count_edited_member_status-$present_member;
						
						$query2 = $this->db->query("SELECT regnumber,kyc_id
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				  WHERE regnumber IN ('".$editarray."')
																				 GROUP BY regnumber
																	  )
																 AND kyc_status = '0' AND  kyc_state = 2  AND mem_type  IN ('O') ");
							$state2_member = $query2->num_rows();
				
							$pending_edit_member=$not_prent_member+$state2_member;
	
			
					/*non member pendinmg count*/

					  
					   //pending for edit list non member 
					   $registrationtype=array('NM','DB');
					   $edit_nonmembers =$e_nonnew=array();
						$this->db->where('kyc_edit','1');
						$this->db->where('kyc_status ','0');
						$this->db->where_in('registrationtype', $registrationtype);
						$edit_nonmembers = $this->master_model->getRecords("member_registration",array('isactive'=>'1'),'regnumber');
					
						$count_edited_nonmember_status=0;
						foreach($edit_nonmembers as $k=>$v) 
						{
							$e_nonnew[$k] = $v['regnumber'];
							$count_edited_nonmember_status++;
						}
					 
						 $non_editarray = implode("','",$e_nonnew);
						$non_query1 = $this->db->query("SELECT DISTINCT(regnumber)  FROM `member_kyc` WHERE mem_type  IN ('NM','DB')  AND  `regnumber` IN ('".$non_editarray."')");
						$present_nonmember = $non_query1->num_rows();
						$not_prent_nonmember=$count_edited_nonmember_status-$present_nonmember;
						
						$non_query2 = $this->db->query("SELECT regnumber,kyc_id
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				  WHERE regnumber IN ('".$non_editarray."')
																				 GROUP BY regnumber
																	  )
																 AND kyc_status = '0' AND  kyc_state = 2  AND mem_type  IN ('NM','DB')  ");
							$state2_nonmember = $non_query2->num_rows();
							$pending_edit_nonmember=$not_prent_nonmember+$state2_nonmember;
					    	$non_member_pending=$pending_edit_nonmember;
				
#-----------------------new list approve---------------------------------#
						/*	$approve_new = $this->db->query("SELECT  `regnumber`   FROM `member_registration` WHERE `isactive` = '1' AND `isdeleted` = 0 AND `kyc_status` = '1' AND `kyc_edit` = 0");
							$approve_new_member  = $approve_new->num_rows();*/
							$type=array('O');
							$this->db->where('isactive','1');
							$this->db->where('kyc_status','1');
							$this->db->where('kyc_edit',0);
							$this->db->where_in('registrationtype', $type);
							$approve_new_member= $this->UserModel->getRecordCount("member_registration");
							
				
							$type=array('NM','DB');
							$this->db->where('isactive','1');
							$this->db->where('kyc_status','1');
							$this->db->where('kyc_edit',0);
							$this->db->where_in('registrationtype', $type);
							$approve_new_nonmember= $this->UserModel->getRecordCount("member_registration");
				
#-----------------------end new list approve---------------------------------#				
							
#----------------------------edit list approve--------------------------------#
							/*$approve_edit= $this->db->query("SELECT  `regnumber`   FROM `member_registration` WHERE `isactive` = '1' AND `isdeleted` = 0 AND `kyc_status` = '1' AND `kyc_edit` = 1");
							$approve_edit_member  = $approve_edit->num_rows();
							*/
								$type=array('O');
							$this->db->where('isactive','1');
							$this->db->where('kyc_status','1');
							$this->db->where('kyc_edit',1);
							$this->db->where_in('registrationtype', $type);
							$approve_edit_member= $this->UserModel->getRecordCount("member_registration");
						
						$type=array('NM','DB');
							$this->db->where('isactive','1');
							$this->db->where('kyc_status','1');
							$this->db->where_in('registrationtype', $type);
							$approve_edit_nonmember= $this->UserModel->getRecordCount("member_registration");
#----------------------------edit list approve--------------------------------#						
							/*Dupilcate card*/
						/*$dup_card= $this->db->query("SELECT  `regnumber`   FROM `duplicate_icard` WHERE DATE(`added_date`) >= '".$kyc_start_date."' AND `pay_status` = '1'");
						$dup_card_count  = $dup_card->num_rows();	*/
						
							
							$this->db->where('pay_status','1');
							$this->db->where('DATE(`added_date`)>=', $kyc_start_date);
							$dup_card_count = $this->UserModel->getRecordCount("duplicate_icard");
						
						
						/*membership Id-card*/
						$dwn_mem_icard= $this->db->query("SELECT DISTINCT(member_number)  FROM `member_idcard_cnt` WHERE `dwn_date` BETWEEN '".$kyc_start_date."' AND '".date('Y-m-d')."'");
						$dwn_mem_icard_count = $dwn_mem_icard->num_rows();	
								
								
						//pending for approver 
#-------------------------new-----------------------------------#
						//member 
			
						$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				
																				 GROUP BY regnumber
																	  )
																AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('O') AND `record_source` = 'New' ");
							$ap_new_pending_count = $query2->num_rows();
						
					//non member	
							 
							
						$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																					
																				 GROUP BY regnumber
																	  )
																AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('NM','DB') AND `record_source` = 'New' ");
							$ap_new_pending_count_non= $query2->num_rows();
						
#----------------------------end-------------------------------------#

#-------------------------edit ----------------------------------------#
							//member 
			
						$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																				
																				 GROUP BY regnumber
																	  )
																AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('O') AND `record_source` = 'Edit' ");
							$ap_edit_pending_count = $query2->num_rows();
						
					//non member	
							 
							
						$query2 = $this->db->query("SELECT regnumber
																 FROM member_kyc
																 WHERE kyc_id IN (
																				 SELECT MAX(kyc_id)
																				FROM member_kyc
																					
																				 GROUP BY regnumber
																	  )
																AND `kyc_status` = '0' AND `kyc_state` = 1 AND `approved_by` = 0 AND  mem_type  IN ('NM','DB') AND `record_source` = 'Edit' ");
							$ap_edit_pending_count_non= $query2->num_rows();
						
#------------------------end------------------------#
											
						$data = array("new_registration_count_M"=>$new_registration_count_M,
						"new_registration_count_NM"=>$new_registration_count_NM,
						"edit_registration_count_M"=>$edit_registration_count_M,
						'edit_registration_count_NM'=>$edit_registration_count_NM,
						
						'non_member_pending'=>$non_member_pending,
						
						"approve_new_member"=>$approve_new_member,
						"approve_new_nonmember"=>$approve_new_nonmember,
						
						"approve_edit_member"=>$approve_edit_member,
						"approve_edit_nonmember"=>$approve_edit_nonmember,
						
						
						  "pending_new_list_member"=> $pending_new_list_member,
						  "pending_new_list_nonmembers"=> $pending_new_list_nonmembers,
						  
						  "pending_edit_member"=>$pending_edit_member,
						  	  "pending_edit_nonmember"=>$non_member_pending,
							  
//						"approve_non_member"=>$approve_non_member,
						'dup_card_count' =>$dup_card_count,
						'dwn_mem_icard_count'=>$dwn_mem_icard_count,
						'approver_new_pending'=>$ap_new_pending_count,
							'approver_new_pending_non'=>$ap_new_pending_count_non,
							
						
						'approver_edit_pending'=>$ap_edit_pending_count,
						'approver_edit_pending_non'=>$ap_edit_pending_count_non);
						//'approver_non_pending'=>$ap_non_pending_count);
			
			$this->load->view('admin/finquest_dashboard/kyc_asondate_report',$data);
				
	}
	
}
