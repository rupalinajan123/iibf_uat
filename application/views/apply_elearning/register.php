<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('apply_elearning/inc_header'); ?>
		<style>input, select { padding: 4px 5px 4px 5px !important; } .form-horizontal .control-label { line-height:18px; } </style>
	</head>
	<?php 
		$loginType = $_GET['login_type'];
	?>
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<?php $this->load->view('apply_elearning/inc_navbar'); ?>	
			<div class="container">				
				<section class="content">
					<section class="content-header">
						<h1 class="register">Apply For E-learning<?php if ($loginType=='sbi') { echo ' : SBI Only '; } ?> </h1><br/>
					</section>
					
					<div class="box box-info" style="padding: 0 10px 0 10px;">
						<?php if($this->session->flashdata('error')!=''){?>
							<div class="alert alert-danger alert-dismissible" id="error_id">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<?php echo $this->session->flashdata('error'); ?>
							</div>
							
							<?php } if($this->session->flashdata('success')!=''){ ?>
							<div class="alert alert-success alert-dismissible" id="success_id">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								<?php echo $this->session->flashdata('success'); ?>
							</div>
						<?php } ?> 
						
						<?php
							if($this->router->fetch_method() == 'preview')
							$action = site_url('ApplyElearning/add_record?login_type='.$loginType);
							else
							$action = site_url('ApplyElearning/register/'.$member_no.'?login_type='.$loginType);
						?>
						<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  action="<?php echo $action;?>" autocomplete="off">    
              <input type="hidden" class="csrf_iibf_name"  name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" autocomplete='false'>
							<?php 
								if(count($member_info) > 0 && isset($member_info[0]['regnumber'])) 
								{ 
									$regnumber = $member_info[0]['regnumber'];  
									$readonly = "readonly='readonly'"; 
								}
								else if($this->session->has_userdata('session_array_'.$loginType))
								{									
									$regnumber = (isset($session_data['member_no']))?$session_data['member_no']:'';
									$readonly = "readonly='readonly'";
								}
								else { $regnumber = ''; }
								
								if($regnumber != "")
								{	 ?>								
									<div class="form-group text-center">
										<label for="member_no" class="col-sm-3 control-label">Membership / Registration No.<span style="color:#F00">*</span></label>
										<div class="col-sm-8">
											<input type="text" class="form-control " id="member_no" name="member_no" placeholder="Membership/Registration No." value="<?php echo $regnumber; ?>" <?php echo $readonly; ?> required>
										</div>
									</div>
					<?php } ?>
							
							<div class="form-group">
								<label for="namesub" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>								
								<div class="col-sm-4">
									<?php 										
										if(count($member_info) > 0 && isset($member_info[0]['namesub']))
										{ 
											$namesub = $member_info[0]['namesub']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{											
											$namesub = (isset($session_data['namesub']))?$session_data['namesub']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$namesub = set_value('namesub'); 
											$readonly = '';
										} 
                    $namesub = str_replace(".","",strtolower($namesub));
                    
                    $namesub_readonly = '';
                    if($this->router->fetch_method() == 'preview') { $namesub_readonly = "disabled='disabled'"; } ?>			
										
										<?php /* <input type="text" class="form-control" id="namesub" name="namesub" value="<?php echo $namesub;?>" <?php echo $readonly; ?> required Placeholder="Title" maxlength="5"> */ ?>
										
										<select id="namesub" name="namesub" class="form-control" <?php echo $readonly; echo $namesub_readonly; ?> required>
											<option value="" >Select</option>
											<option value="Mr." <?php if($namesub == 'mr') { echo 'selected'; } ?>>Mr.</option>
											<option value="Mrs." <?php if($namesub == 'mrs') { echo 'selected'; } ?>>Mrs.</option>
											<option value="Ms." <?php if($namesub == 'ms') { echo 'selected'; } ?>>Ms.</option>
											<option value="Dr." <?php if($namesub == 'dr') { echo 'selected'; } ?>>Dr.</option>
											<option value="Prof." <?php if($namesub == 'prof') { echo 'selected'; } ?>>Prof.</option>
										</select>
										
										<?php if(form_error('namesub')!=""){ ?><label class="error"><?php echo form_error('namesub'); ?></label> <?php } ?>
								</div> 
              
								<div class="col-sm-4">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['firstname']))
										{ 
											$firstname = $member_info[0]['firstname']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$firstname = (isset($session_data['firstname']))?$session_data['firstname']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$firstname = set_value('firstname'); 
											$readonly = '';
										} ?>											
										<input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $firstname;?>" <?php echo $readonly; ?> required Placeholder="First Name" maxlength="30">
										<?php if(form_error('firstname')!=""){ ?><label class="error"><?php echo form_error('firstname'); ?></label> <?php } ?>
								</div>
              </div> 
							
              <div class="form-group">
								<label class="col-sm-3"></label>
								<div class="col-sm-4">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['middlename']))
										{ 
											$middlename = $member_info[0]['middlename']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$middlename = (isset($session_data['middlename']))?$session_data['middlename']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$middlename = set_value('middlename'); 
											$readonly = '';
										} ?>											
										<input type="text" class="form-control" id="middlename" name="middlename" value="<?php echo $middlename;?>" <?php echo $readonly; ?> Placeholder="Middle Name" maxlength="30">
								</div>
								
								<div class="col-sm-4">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['lastname']))
										{ 
											$lastname = $member_info[0]['lastname']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$lastname = (isset($session_data['lastname']))?$session_data['lastname']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$lastname = set_value('lastname'); 
											$readonly = '';
										} ?>
										<input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $lastname;?>" <?php echo $readonly; ?> Placeholder="Last Name" maxlength="30">
								</div>
							</div>
							
							<div class="form-group">
								<label for="email" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
								<div class="col-sm-8">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['email']))
										{ 
											$email = $member_info[0]['email']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$email = (isset($session_data['email']))?$session_data['email']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$email = set_value('email'); 
											$readonly = '';
										} ?>
										<input type="text" class="form-control setAlg" id="email" name="email" placeholder="Email" value="<?php echo $email;?>" required <?php echo $readonly; ?> maxlength="45">
										<?php if(form_error('email')!=""){ ?><label class="error"><?php echo form_error('email'); ?></label> <?php } ?>
								</div>
							</div>
							
							<div class="form-group"> 
								<label for="mobile" class="col-sm-3 control-label">Mobile<span style="color:#F00">*</span></label>
								<div class="col-sm-8">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['mobile']))
										{ 
											$mobile = $member_info[0]['mobile']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$mobile = (isset($session_data['mobile']))?$session_data['mobile']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$mobile = set_value('mobile'); 
											$readonly = '';
										} ?>
										<input type="tel" class="form-control setAlg" id="mobile" name="mobile" placeholder="mobile" minlength="10" maxlength="10" value="<?php echo $mobile;?>" required <?php echo $readonly; ?>>
										<?php if(form_error('mobile')!=""){ ?><label class="error"><?php echo form_error('mobile'); ?></label> <?php } ?>
								</div>
							</div> 

							<?php /*
              <div class="form-group">
								<label for="address1" class="col-sm-3 control-label">Address line1<span style="color:#F00">*</span></label>
								<div class="col-sm-8">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['address1']))
										{ 
											$address1 = $member_info[0]['address1']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$address1 = (isset($session_data['address1']))?$session_data['address1']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$address1 = set_value('address1'); 
											$readonly = '';
										} ?>
										<input type="text" class="form-control" id="address1" name="address1" placeholder="Address line1" required value="<?php echo $address1;?>" maxlength="30" <?php echo $readonly; ?>>(Max 30 characters accepted)
										<?php if(form_error('address1')!=""){ ?><label class="error"><?php echo form_error('address1'); ?></label> <?php } ?>
								</div>
							</div>
							
							<div class="form-group">
								<label for="address2" class="col-sm-3 control-label">Address line2</label>
								<div class="col-sm-8">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['address2']))
										{ 
											$address2 = $member_info[0]['address2']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$address2 = (isset($session_data['address2']))?$session_data['address2']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$address2 = set_value('address2'); 
											$readonly = '';
										} ?>
										<input type="text" class="form-control" id="address2" name="address2" placeholder="Address line2"  value="<?php echo $address2;?>" maxlength="30" <?php echo $readonly; ?>>
								</div>
							</div>
							
							<div class="form-group">
								<label for="address3" class="col-sm-3 control-label">Address line3</label>
								<div class="col-sm-8">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['address3']))
										{ 
											$address3 = $member_info[0]['address3']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$address3 = (isset($session_data['address3']))?$session_data['address3']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$address3 = set_value('address3'); 
											$readonly = '';
										} ?>
										<input type="text" class="form-control" id="address3" name="address3" placeholder="Address line3"  value="<?php echo $address3;?>" maxlength="30" <?php echo $readonly; ?>>
								</div>
							</div>
							
							<div class="form-group">
								<label for="address4" class="col-sm-3 control-label">Address line4</label>
								<div class="col-sm-8">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['address4']))
										{ 
											$address4 = $member_info[0]['address4']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$address4 = (isset($session_data['address4']))?$session_data['address4']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$address4 = set_value('address4'); 
											$readonly = '';
										} ?>
										<input type="text" class="form-control" id="address4" name="address4" placeholder="Address line4"  value="<?php echo $address4;?>" maxlength="30" <?php echo $readonly; ?>>
								</div>
							</div>
							
							<div class="form-group">
								<label for="district" class="col-sm-3 control-label">District <span style="color:#F00">*</span></label>
								<div class="col-sm-8">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['district']))
										{ 
											$district = $member_info[0]['district']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$district = (isset($session_data['district']))?$session_data['district']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$district = set_value('district'); 
											$readonly = '';
										} ?>
										<input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo $district;?>" maxlength="30" <?php echo $readonly; ?>>
										<?php if(form_error('district')!=""){ ?><label class="error"><?php echo form_error('district'); ?></label> <?php } ?>
								</div>
							</div>
							
							<div class="form-group">
								<label for="city" class="col-sm-3 control-label">City <span style="color:#F00">*</span></label>
								<div class="col-sm-8">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['city']))
										{ 
											$city = $member_info[0]['city']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
											
											$city = (isset($session_data['city']))?$session_data['city']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$city = set_value('city'); 
											$readonly = '';
										} ?>
										<input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $city; ?>" maxlength="30" <?php echo $readonly; ?>>
										<?php if(form_error('city')!=""){ ?><label class="error"><?php echo form_error('city'); ?></label> <?php } ?>
								</div>
							</div>
							*/ ?>
              
							<div class="form-group">
								<label for="state" class="col-sm-3 control-label">State <span style="color:#f00">*</span></label>
								<div class="col-sm-8">
									<?php 
										if(count($member_info) > 0 && isset($member_info[0]['state']))
										{ 
											$state = $member_info[0]['state']; 
											$readonly = "readonly='readonly'";
										} 
										else if($this->session->has_userdata('session_array_'.$loginType))
										{
                      $state = (isset($session_data['state']))?$session_data['state']:'';
											$readonly = "readonly='readonly'";
										}
										else 
										{ 
											$state = set_value('state'); 
											$readonly = '';
										} 
                    
                    if($this->router->fetch_method() == 'preview') { $readonly = "disabled='disabled'"; } ?>
										
										<select class="form-control" id="state" name="state" required  <?php echo $readonly;?>>
											<option value="">Select</option>
											<?php if(count($states) > 0)
												{
													foreach($states as $row1)
													{ 	
													if($state == $row1['state_code']) $sel = 'selected'; else $sel = ''; ?>
													<option value="<?php echo $row1['state_code'];?>" <?php echo $sel;?>><?php echo $row1['state_name'];?></option>
													<?php }
												} ?>
										</select><?php if(form_error('state')!=""){ ?><label class="error"><?php echo form_error('state'); ?></label> <?php } ?>											
								</div>										
							</div>
              				
							<div class="form-group">
								<label for="exam_name" class="col-sm-3 control-label">Exam <span style="color:#f00">*</span></label>
								<div class="col-sm-8">
				                  <?php 
				                  /*if(count($exam_data) > 0 )
				                  { 
				                    $exam = $exam_data[0]['exam_code']; 
				                    $e_readonly = "readonly='readonly'";
				                  } 
				                  else */
				                  $e_readonly = '';
				                  if($this->session->has_userdata('session_array_'.$loginType))
				                  {
				                    $exam = (isset($session_data['exam_name']))?$session_data['exam_name']:'';
				                    //$exam = explode("##",$exam)[0];
				                    //$e_readonly = "readonly='readonly'";
				                  }
				                  else 
				                  { 
				                    $exam = set_value('exam_name'); 
				                    //$e_readonly = '';
				                  } 
                  				  
				                  if ($loginType == 'sbi') {
				                  	$exam = 1032;
				                  }

              					if($this->router->fetch_method() == 'preview') { $e_readonly = "disabled='disabled'"; } ?>
									<select class="form-control" id="exam_name" name="exam_name" required onchange="get_elearning_subjects(this.value)" <?php echo $e_readonly;?>>
										<option value="">Select</option>									
										<?php if(count($exam_data) > 0)
                  						{
											foreach($exam_data as $exam_res)
											{
                      							if($exam == $exam_res['exam_code']) $sel = 'selected'; else $sel = ''; ?>
										  		<option value="<?php echo $exam_res['exam_code'];?>" <?php echo $sel;?>><?php echo $exam_res['exam_name'];?> </option>
              								<?php }
                  						} ?>
								  </select>
								  <?php if(form_error('exam_name')!="") { ?>
								  	<label class="error"><?php echo form_error('exam_name'); ?></label> 
								  <?php } ?>
								</div>										
							</div>
							
							<div class="form-group <?php if($exam != "") { } else { echo 'hide'; } ?>" id="elearning_main_outer">
								<label class="col-sm-3 control-label">E-learning Subjects <span style="color:#f00">*</span></label>
								<div class="col-sm-8">
                  <div id="elearning_sub_outer">
                    <?php
                      $availeble_subjects_cnt = 0;
                      if($exam != "")
                      {
                        /* $exam_name_arr = explode("##", $exam);
                        $exam_code = $exam_name_arr[0]; 
                        $exam_period = $exam_name_arr[1]; */ 
                        $exam_code = $exam;
                        
                        $subject_data = $this->master_model->getRecords('spm_elearning_subject_master', array('exam_code' => $exam_code, /* 'exam_period' => $exam_period, */ 'subject_delete' => '0'));
                        //echo $this->db->last_query();
                        
                        if(count($subject_data) > 0)  
                        {
                          $selected_sub_arr = array();
                          $e_readonly = "";
                          if($this->session->has_userdata('session_array_'.$loginType))
                          {
                            $selected_sub_arr = explode(",",$session_data['el_subject']);
                            $e_readonly = "";
                          }
                          else
                          {
                            $selected_sub_arr = set_value('el_subject');
                          }
                          
                          if($this->router->fetch_method() == 'preview') { $e_readonly = "disabled='disabled'"; }
                          
                          foreach($subject_data as $sub) 
                          {
                            if(!array_key_exists($sub['subject_code'], $already_purchase_subjects))
                            {
                              $show_record_flag = 0;
                              
                              if($this->router->fetch_method() == 'register') 
                              { 
                                $show_record_flag = 1; 
                              }
                              else if($this->router->fetch_method() == 'preview') 
                              {  
                                if(in_array($sub['subject_code'], $selected_sub_arr))
                                {
                                  $show_record_flag = 1;
                                }
                              }
                              
                              if($show_record_flag == 1)
                              { ?>
                                <div>
                                  <label>
                                    <input type="checkbox" name="el_subject[]" value="<?php echo $sub['subject_code']; ?>" class="el_sub_prop" required id="el_subject_<?php echo $sub['subject_code']; ?>" <?php if(in_array($sub['subject_code'], $selected_sub_arr)) { echo 'checked'; } ?> <?php echo $e_readonly; ?>><?php echo $sub['subject_description']; ?>
                                  </label>
                                </div>
                        <?php   $availeble_subjects_cnt++;
                              }
                            }
                          }  
                          if($availeble_subjects_cnt == 0) { ?><label>You have already purchased all the elearning subjects</label> <?php }
                        }
                      }
                    ?>
                  </div>
									
                  <?php 
									/* $availeble_subjects_cnt = 0;
									if(!empty($subjects))  
									{
										$el_subject_arr = array();
										$readonly = '';
										if(isset($this->session->userdata['session_array_'.$loginType]['el_subject']))
										{
											$el_subject_arr = explode(",",$this->session->userdata['session_array_'.$loginType]['el_subject']);
											
											if($this->router->fetch_method() == 'preview') { $readonly = "disabled='disabled'"; }
										}
										else if(set_value('el_subject'))
										{ 
											$el_subject_arr = set_value('el_subject'); 											
										}
																			
										foreach($subjects as $sub) 
										{
											$check_val = '';
											if(count($el_subject_arr) > 0 && in_array($sub['subject_code'], $el_subject_arr))
											{
												$check_val = 'checked';
											}
											
											if(!array_key_exists($sub['subject_code'], $already_purchase_subjects))
											{ ?>
												<div>
													<label>
														<input type="checkbox" name="el_subject[]" value="<?php echo $sub['subject_code']; ?>" class="el_sub_prop" required id="el_subject_<?php echo $sub['subject_code']?>" <?php echo $check_val; ?> <?php echo $readonly; ?>> <?php echo $sub['subject_description']?>
													</label>
												</div>
									<?php	$availeble_subjects_cnt++;
											}
										}  
									}
									if($availeble_subjects_cnt == 0) { echo '<label>You have already purchased all the elearning subjects</label>'; } */	?>
									
									<div class="clearfix"></div>
									<div id="el_subject_err"></div>	
									<?php if(form_error('el_subject[]')!=""){ ?><label class="error"><?php echo form_error('el_subject[]'); ?></label> <?php } ?>	
								</div>	
							</div>
							
							<?php if(count($already_purchase_subjects) > 0)
							{	?>
								<div class="form-group">
									<label class="col-sm-3 control-label">Already Purchased Subjects <span style="color:#f00"></span></label>
									<div class="col-sm-8">
										<?php 
											$sr_no = 1;
											foreach($already_purchase_subjects as $purchase_sub) 
											{ ?>
												<div>
													<label style="margin-bottom:0;"><?php echo $sr_no.". ".$purchase_sub['subject_description']; ?></label>
												</div>
								<?php	$sr_no++;
											} ?>	
									</div>	
								</div>
							<?php } ?>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">Fee Amount</label>
								<input id="total_fee" name="total_fee" type="hidden" value="" autocomplete='false'>
								<div class="col-sm-5 " id="html_fee_id"></div>
							</div>
              
              <input type="hidden" name="availeble_subjects_cnt" id="availeble_subjects_cnt" value="<?php echo $availeble_subjects_cnt; ?>" autocomplete='false'>
							
							<div class="form-group"> 
								<div class="col-sm-3"></div>
								<div class="col-sm-9">
									<div class="disabledmsg" style="color:red;"></div> <!-- Priyanka d - 01-feb-23 -->
									<?php if($this->session->has_userdata('session_array_'.$loginType) && isset($session_data['member_no']) && $session_data['member_no'] != 0)
										$back_url  = site_url('ApplyElearning/register/'.base64_encode($session_data['member_no']).'?login_type='.$loginType);
										else
										$back_url  = site_url('ApplyElearning/register/');		
									?>
									
									<?php if($this->router->fetch_method() == 'preview')
									{	?>	
										<?php /* if($availeble_subjects_cnt > 0) */ { ?><input type="submit" class="btn btn-info submit_preview_btn" name="btn_pay" id="btn_pay" value="Pay Now">&nbsp;&nbsp; <?php } ?>
										<input type="button" class="btn btn-info" name="btn_back" id="btn_back" value="Back" onclick="window.location='<?php echo $back_url;?>'">
						<?php }
									else	
									{ 
										if ($loginType == 'sbi') {
											$regBackUrl = site_url('ApplyElearning/applyExam');
										} else {
											$regBackUrl = site_url('ApplyElearning');
										}
									?>
										<?php /* if($availeble_subjects_cnt > 0) */ { ?>
										<input type="submit" class="btn btn-info submit_preview_btn" name="btn_preview" id="btn_preview" value="Preview and Proceed for Payment">&nbsp;&nbsp; <?php } ?>
										<input type="button" class="btn btn-info" name="btn_back" id="btn_back" value="Back" onclick="window.location='<?php echo $regBackUrl;?>'">
						<?php } ?>									
								</div>
							</div>
						</form>
					</div>					
					<?php $this->load->view('apply_elearning/inc_footerbar'); ?>
				</section>
			</div>
		</div>		
		<?php $this->load->view('apply_elearning/inc_footer'); ?>
		
		<script>
      function get_elearning_subjects(exam_name)
      {
        $(".loading").show();
				
        var datastring = { 'exam_name':exam_name, 'member_no':'<?php echo $member_no; ?>' };
				$.ajax({
					url:"<?php echo site_url('ApplyElearning/get_elearning_subjects'); ?>",
					data: datastring,
					type:'POST',
					async: false,
					dataType: 'JSON',
					success: function(data) 
					{
						if(data.flag == 'success')
						{
							$('#elearning_sub_outer').html(data.response);
							  $("#availeble_subjects_cnt").val(data.availeble_subjects_cnt);
							  
							  if(data.response != "") { $("#elearning_main_outer").removeClass('hide'); 
							  $("#total_fee").val('0.00');
								$("#html_fee_id").html('0.00');
							  }
							  else 
							  { 
								$("#elearning_main_outer").addClass('hide'); 
								$("#total_fee").val('0.00');
								$("#html_fee_id").html('0.00');
							  }
							  
							  $(".el_sub_prop").click(function() { calculate_fees(); });
						}
					}
				});
				$(".loading").hide();
      }
      
			$(document ).ready( function() 
			{
				/*
				$("#exam_name").on("change", function (e) {//Priyanka d - 01-feb-23 - diabled jaiib & caiib
				
					$('.disabledmsg').html('');
					if(  $(this).val()==$this->config->item('examCodeCaiib')) {
						$('.disabledmsg').html('Registration for JAIIB/ CAIIB e-learning is temporarily suspended due to technical reasons. The same will be made available soon.');
					}
				});
				$(".submit_preview_btn").on("click", function (e) { //Priyanka d - 01-feb-23 - diabled jaiib & caiib
					e.preventDefault();
					$('.disabledmsg').html('');
					if( $('#exam_name').val()==$this->config->item('examCodeCaiib') || $('#exam_name').val()==60) {
						$('.disabledmsg').html('Registration for  CAIIB e-learning is temporarily suspended due to technical reasons. The same will be made available soon.');
						return false;
					}
					else
					$("form#usersAddForm").submit();
				});*/

				$("#usersAddForm").validate( 
				{
					rules:
					{
						<?php if(count($member_info) > 0 && isset($member_info[0]['regnumber'])) { ?> 
						member_no: { required : true },  /* , remote: { url: "<?php echo site_url('ApplyElearning/check_member_no_ajax') ?>", type: "post" } */				
						<?php } ?>
						namesub: { required : true, maxlength:5 },					
						firstname: { required : true, maxlength:30 },				
						middlename: { maxlength:30 },					
						lastname: { maxlength:30 },	
            email: 
            { 
              required : true, valid_email: true, maxlength:45, 
              <?php if($this->session->has_userdata('session_array_'.$loginType)) { } else { ?>
				//comment by Pooja to remove mail validate : 30-12-2022
                //remote: { url: "<?php echo site_url('ApplyElearning/check_email_exist_ajax') ?>", type: "post", data: { "member_no": function() { return "<?php //echo $member_no; ?>" } } } 
              <?php } ?> 
            }, 				
						mobile: 
            { 
              required : true, digits:true, minlength: 10, maxlength:10,
              <?php if($this->session->has_userdata('session_array_'.$loginType)) { } else { ?>
				//comment by Pooja to remove mobile validate : 30-12-2022
                //remote: { url: "<?php echo site_url('ApplyElearning/check_mobile_exist_ajax') ?>", type: "post", data: { "member_no": function() { return "<?php //echo $member_no; ?>" } } } 
              <?php } ?> 
            },				
						state: { required : true },  		
						exam_name: { required : true },  		
						"el_subject[]": { required : true },  		
					},
					messages:
					{
						member_no: { required : "Please enter Membership/Registration No.", remote : "Please enter valid Membership/Registration No." },
						namesub: { required : "Please select Title" },
						firstname: { required : "Please enter First Name" },
						middlename: { },
						email: { required : "Please enter Email", maxlength : "Please enter maximum 45 characters in Email", valid_email : "Please enter Valid Email", remote : "Email already exist" },
						mobile: { required : "Please enter Mobile", minlength : "Please enter 10 numbers in Mobile", maxlength : "Please enter 10 numbers in Mobile", remote : "Mobile already exist" },
						state: { required : "Please select State" },
						exam_name: { required : "Please select Exam" },
						"el_subject[]": { required : "Please select at least one subject" },
					},
					errorPlacement: function(error, element) // For replace error 
					{
						if (element.attr("name") == "el_subject[]") 
						{
							error.insertBefore("#el_subject_err");
						}
						else 
						{
							error.insertAfter(element);
						}
					},
          submitHandler: function(form) 
          {
            var availeble_subjects_cnt = $("#availeble_subjects_cnt").val();
            var total_fee = $("#total_fee").val();
            
            if(total_fee > 0)
            {
              if(availeble_subjects_cnt > 0) { return true; }
              else { alert('You can not proceed as no elearning subject available for selected exam'); return false; }
            }
            else
            {
              alert('You can not proceed as the fee amount is zero for selected subjects'); return false;
            }
            
          }
				});
			});
			calculate_fees();
			$(".el_sub_prop").click(function() { calculate_fees(); });
			
			function calculate_fees()
			{
				$(".loading").show();
				/*var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
					var cCode =  319;
					var eprid = 220;
					var excd = 72;
					var extype= 1;
					var mtype= 'O';
					var Eval = 'Y'; 
					var grpcode = 'S1'; 
					var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&mtype='+mtype+'&elearning_flag='+Eval+'&grp_code='+grpcode;
				*/
				
				var selected_subject_code = [];
				$.each($("input.el_sub_prop:checked"), function() { selected_subject_code.push($(this).val()); });
		
				var datastring = { 'selected_subject_code':selected_subject_code };
				$.ajax({
					url:'<?php echo site_url();?>'+'Fee/getElearningFees/',
					data: datastring,
					type:'POST',
					async: false,
					dataType: 'JSON',
					success: function(response) 
					{
						if(response)
						{
							$('#total_fee').val(response.total_fees)
							$('#html_fee_id').html(response.total_fees)
							//document.getElementById('total_fee').value = data ;
							//document.getElementById('html_fee_id').innerHTML =data;
						}
					}
				});
				$(".loading").hide();
			}	
		</script>		
		
		<script>	
			$( document ).ready( function () { $('.loading').delay(0).fadeOut('slow'); });
			/* $(document).ready(function() { setTimeout(function() { $('#alert_fadeout').fadeOut(3000); }, 8000 ); }); */
		</script>
	</body>
</html>	