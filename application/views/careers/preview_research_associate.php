<style>
	.modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 920px;
	min-width: 300px;
	}
	#confirm .modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 420px;
	min-width: 400px;
	}
	.skin-blue .main-header .navbar {
	background-color:#fff;
	}
	body.layout-top-nav .main-header h1 {
	color:#0699dd;
	margin-bottom:0;
	margin-top:30px;
	}
	.container {
	position:relative;
	}
	.box-header.with-border {
	background-color:#7fd1ea;
	border-top-left-radius:0;
	border-top-right-radius:0;
	margin-bottom:10px;
	}
	.header_blue {
	background-color:#2ea0e2 !important;
	color:#fff !important;
	margin-bottom:0 !important;
	}
	.box {
	border:none;
	box-shadow:none;
	border-radius:0;
	margin-bottom:0;
	}
	.nobg {
	background:none !important;
	border:none !important;
	}
	.box-title-hd {
	color:#3c8dbc;
	font-size:16px;
	margin:0;
	}
	.blue_bg {
	background-color:#e7f3ff;
	}
	.m_t_15 {
	margin-top:15px;
	}
	.main-footer {
	padding-left:160px;
	padding-right:160px;
	}
	.content-header > h1 {
	font-size:22px;
	font-weight:600;
	}
	h4 {
	margin-top:5px;
	margin-bottom:10px !important;
	font-size:14px;
	line-height:18px;
	padding:0 5px;
	font-weight:600;
	text-align:justify;
	}
	.form-horizontal .control-label {
	padding-top:4px;
	}
	.pad_top_2 {
	padding-top:2px !important;
	}
	.pad_top_0 {
	padding-top:0px !important;
	}
	div.form-group:nth-child(odd) {
	background-color:#dcf1fc;
	padding:5px 0;
	}
	#confirmBox {
	display: none;
	background-color: #eee;
	border-radius: 5px;
	border: 1px solid #aaa;
	position: fixed;
	width: 300px;
	left: 50%;
	margin-left: -150px;
	padding: 6px 8px 8px;
	box-sizing: border-box;
	text-align: center;
	z-index:1;
	box-shadow:0 1px 3px #000;
	}
	#confirmBox .button {
	background-color: #ccc;
	display: inline-block;
	border-radius: 3px;
	border: 1px solid #aaa;
	padding: 2px;
	text-align: center;
	width: 80px;
	cursor: pointer;
	}
	#confirmBox .button:hover {
	background-color: #ddd;
	}
	#confirmBox .message {
	text-align: left;
	margin-bottom: 8px;
	}
	.form-group {
	margin-bottom:10px;
	}
	.form-horizontal .form-group {
	margin-left:0;
	margin-right:0;
	}
	.form-control {
	border-color:#888;
	}
	.form-horizontal .control-label {
	font-weight:normal;
	}
	a.forget {
	color:#9d0000;
	}
	a.forget:hover {
	color:#9d0000;
	text-decoration:underline;
	}
	ol li {
	line-height:18px;
	}
	.example {
	text-align:left !important;
	padding:0 10px;
	}
	label{
	font-weight:bold !important;
	}
</style>
<?php 
	header('Cache-Control: must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

<div class="container">
  <section class="content-header">
    <h1> 
			Please go through the given detail, correction may be made if necessary. 
			<a href="javascript:window.history.go(-1);">Modify</a> 
		</h1>
    <br>
	</section>
	
  <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" 
	action="<?php echo base_url()?>Careers/addmember/" autocomplete="off">
		<input type="hidden" id="position_id" name="position_id" value="1">
    <section class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">BASIC DETAILS</h3>
							<div style="float:right;"> </div>
						</div>
						
						<!-- form start -->
						<div class="box-body">
							<?php //echo validation_errors(); ?>
							
							<?php if($this->session->flashdata('error')!='')
								{	?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $this->session->flashdata('error'); ?> 
								</div>
								<?php } 
								
								if($this->session->flashdata('success')!='')
								{ ?>
								<div class="alert alert-success alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $this->session->flashdata('success'); ?> 
								</div>
								<?php } 
								
								if(validation_errors()!='')
								{	?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo validation_errors(); ?> 
								</div>
							<?php }  ?>
							
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">First Name</label>
								<div class="col-sm-1"> <?php echo $this->session->userdata['enduserinfo']['sel_namesub'];?> </div>
								<div class="col-sm-0"> <?php echo $this->session->userdata['enduserinfo']['firstname'];?> </div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Middle Name</label>
								<div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['middlename'];?></div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Last Name</label>
								<div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['lastname'];?> </div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Father's/Husband's Name</label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['father_husband_name'];?></div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Date of Birth </label>
								<div class="col-sm-2 example"> <?php echo $this->session->userdata['enduserinfo']['dateofbirth'];?> </div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Gender </label>
								<div class="col-sm-6">
									<?php if($this->session->userdata['enduserinfo']['gender']=='female'){echo 'Female';}?>
									<?php if($this->session->userdata['enduserinfo']['gender']=='male'){echo 'Male';}?>
								</div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Email Id</label>
								<div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['email'];?> </div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Marital Status</label>
								<div class="col-sm-6"> <?php echo $this->session->userdata['enduserinfo']['marital_status'];?> </div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Mobile </label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['mobile'];?></div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Alternate Mobile</label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['alternate_mobile'];?> </div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">PAN No</label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['pan_no'];?> </div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Aadhar Card Number</label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['aadhar_card_no'];?></div>
							</div>
						</div>
					</div>
					<!-- Basic Details box closed-->
					
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">COMMUNICATION ADDRESS</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Address line1 </label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline1'];?></div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Address line2</label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline2'];?></div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Address line3</label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline3'];?></div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Address line4</label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline4'];?></div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">District </label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['district'];?></div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">City </label>
								<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['city'];?></div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">State </label>
								<div class="col-sm-3">
									<?php if(count($states) > 0){
										foreach($states as $row1){ 	?>
										<?php if($this->session->userdata['enduserinfo']['state']==$row1['state_code']){echo  $row1['state_name'];}?>
									<?php } } ?>
								</div>
								<label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode </label>
								<div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['pincode'];?> </div>
							</div>
							<div class="form-group">
								<label for="roleid" class="col-sm-4 control-label">Contact Number</label>
								<div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['contact_number'];?> </div>
							</div>
							
							<div class="box-header with-border">
								<h3 class="box-title">PERMANENT ADDRESS</h3>
							</div>
							<div class="box-body">
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Address line1 </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline1_pr'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Address line2</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline2_pr'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Address line3</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline3_pr'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Address line4</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline4_pr'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">District </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['district_pr'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">City </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['city_pr'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">State </label>
									<div class="col-sm-3">
										<?php if(count($states) > 0){
											foreach($states as $row1){ 	?>
											<?php if($this->session->userdata['enduserinfo']['state_pr']==$row1['state_code']){echo  $row1['state_name'];}?>
										<?php } } ?>
									</div>
									<label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode </label>
									<div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['pincode_pr'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Contact Number</label>
									<div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['contact_number_pr'];?> </div>
								</div>
								<?php /* <div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Exam Center </label>
									<div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['exam_center'];?> </div>
								</div> */ ?>
								
								<!------------------------------| EDUCATIONAL QUALIFICATION |--------------------------->
								<div class="box box-info">
									<div class="box-header with-border">
										<h3 class="box-title">EDUCATIONAL QUALIFICATION</h3>
									</div>
								</div>
								<div class="box-body" style="margin-bottom:10px;text-align:justify;">
									<strong>The date of passing eligibility examination will be the date appearing on the marksheet issued by the University/Institute. The percentage marks shall be arrived at by dividing the total marks obtained by the candidate in all the subjects in all semesters / years by aggregate maximum marks in all the subjects irrespective of optional/additional optional subject, if any. The fraction of percentage so arrived will be ignored i.e. 59.99% will be treated as less than 60%.</strong>
								</div>
								
								<div class="box-title box-header"><strong>ESSENTIAL</strong> </div><br />
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Degree </label>
									<div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['ess_course_name']; ?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Subject</label>
									<div class="col-sm-5" style="word-wrap: break-word;"> <?php echo $this->session->userdata['enduserinfo']['ess_subject']; ?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">College Name and Address </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_college_name'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">University </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_university'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Period </label>
									<div class="col-sm-5">From: <?php echo $this->session->userdata['enduserinfo']['ess_from_date'];?> - To: <?php echo $this->session->userdata['enduserinfo']['ess_to_date'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Date of completion of the Degree </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_degree_completion_date'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Aggregate Marks Obtained </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_aggregate_marks_obtained'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Aggregate Maximum Marks </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_aggregate_max_marks'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Percentage </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['ess_percentage'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Class/Grade </label>
									<div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['ess_class'];?> </div>
								</div>
								
								<div class="box-title box-header"><strong>DESIRABLE</strong> </div><br />
								<div class="form-group">
									<div class="col-sm-12">
										<?php 
											$course_code_arr = explode(",",$this->session->userdata['enduserinfo']['course_code']);
											if(count($course_code_arr) > 0)
											{
												foreach($course_code_arr as $course_code)
												{
													echo '> '.$course_code.'<br>';
												}
											}
										?>
									</div>
									<?php /* <label for="roleid" class="col-sm-4 control-label">Name Of Course </label>
									<div class="col-sm-6" style="word-wrap: break-word;">
										<?php 
											$course_code = $this->session->userdata['enduserinfo']['course_code'];
											if(count($careers_course_mst) > 0)
											{
												foreach($careers_course_mst as $row1)
												{ 	
													if($course_code == $row1['course_code'])
													{
														echo  $row1['course_name'];
													}
												}
											} 
											
										?>
									</div> */ ?>
								</div>
								<?php /* <div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">College Name and Address </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['college_name'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">University </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['university'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Period </label>
									<div class="col-sm-5">From: <?php echo $this->session->userdata['enduserinfo']['from_date'];?> - To: <?php echo $this->session->userdata['enduserinfo']['to_date'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Date of completion of the Degree </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['degree_completion_date'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Aggregate Marks Obtained </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['aggregate_marks_obtained'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Aggregate Maximum Marks </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['aggregate_max_marks'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Percentage </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['percentage'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Class/Grade </label>
									<div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['class'];?> </div>
								</div> */ ?>
								
								<div class="box box-info">
									<div class="box-header with-border">
										<h3 class="box-title">EMPLOYMENT HISTORY</h3>
									</div>
								</div>
								<?php 
									$organization = $this->session->userdata['enduserinfo']['organization'];
									$designation = $this->session->userdata['enduserinfo']['designation'];
									$responsibilities = $this->session->userdata['enduserinfo']['responsibilities'];
									$job_from_date = $this->session->userdata['enduserinfo']['job_from_date'];
									$job_to_date = $this->session->userdata['enduserinfo']['job_to_date'];
									
									foreach($organization as $job_key => $job_val)
									{
										$organization_val = $job_val;
										$designation_val = $designation[$job_key];
										$responsibilities_val = $responsibilities[$job_key];
										$job_from_date_val = $job_from_date[$job_key];
										$job_to_date_val = $job_to_date[$job_key];
									?>
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Name of the Organization </label>
										<div class="col-sm-5"><?php echo $organization_val; ?></div>
									</div>
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Designation </label>
										<div class="col-sm-5"><?php echo $designation_val;?></div>
									</div>
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Responsibilities </label>
										<div class="col-sm-5"><?php echo $responsibilities_val; ?></div>
									</div>
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Period </label>
										<div class="col-sm-5">From: <?php echo $job_from_date_val;?> - To: <?php echo $job_to_date_val;?></div>
									</div>
									<?php
										
										
									}
									
								?>
								<div class="box box-info">
									<div class="box-header with-border">
										<h3 class="box-title">Languages, Extracurricular, Achievements</h3>
									</div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Languages Known 1</label>
									<div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['languages_known'];?> : <?php  
										$languages_option_arr = $this->session->userdata['enduserinfo']['languages_option'];
										echo $languages_option_arr;
									?></div>
									
								</div>
																
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Languages Known 2</label>
									<div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['languages_known1'];?> : <?php  
										$languages_option_arr = $this->session->userdata['enduserinfo']['languages_option1'];
										echo $languages_option_arr;
									?></div>
									
								</div>
																
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Languages Known 3</label>
									<div class="col-sm-3"><?php echo $this->session->userdata['enduserinfo']['languages_known2'];?> : <?php  
										$languages_option_arr = $this->session->userdata['enduserinfo']['languages_option2'];
										echo $languages_option_arr;
									?></div>
									
								</div>
								
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Extracurricular (Games / Membership / Association)</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['extracurricular'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Hobbies</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['hobbies'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Achievements</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['achievements'];?> </div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-10 control-label" style="text-align:left;">Have your ever been arrested, or kept under detention or bound down/ fined/ convicted by a court of law for any offence or a case against you is pending in respect of any criminal offence/ charge is under investigation, inquiry or trial or otherwise. YES or NO. If YES full particulars of the case should be given. Canvassing in any form will be a disqualification.</label>
									<div class="col-sm-2"> <?php echo $this->session->userdata['enduserinfo']['declaration1'];?> </div>
								</div>
								<?php if($this->session->userdata['enduserinfo']['declaration1'] == 'Yes') { ?>
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Declaration Note</label>
										<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['declaration_note'];?> </div>
									</div>
								<?php } ?>
								
								<div class="box-header with-border"><h3 class="box-title">REFERENCE</h3></div>
								<div class="box-body" style="margin-bottom:10px;">
									<strong>Candidates are instructed to provide two professional references. (References of family members, relatives & friends will not be considered)</strong>
								</div>
								<div class="box box-info">
									<div class="box-header with-border">
										<h3 class="box-title">PROFESSIONAL REFERENCE 1</h3>
									</div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Name </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refname_one'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Designation</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refdesignation_one'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Organisation</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['reforganisation_one'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Complete Address</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refaddressline_one'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Email Id</label>
									<div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['refemail_one'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Mobile </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refmobile_one'];?></div>
								</div>
								<div class="box box-info">
									<div class="box-header with-border">
										<h3 class="box-title">PROFESSIONAL REFERENCE 2</h3>
									</div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Name </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refname_two'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Designation</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refdesignation_two'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Organisation</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['reforganisation_two'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Complete Address</label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refaddressline_two'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Email Id</label>
									<div class="col-sm-6"><?php echo $this->session->userdata['enduserinfo']['refemail_two'];?></div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-4 control-label">Mobile </label>
									<div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['refmobile_two'];?></div>
								</div>
								
								<div class="box box-info">
									<div class="box-header with-border">
										<h3 class="box-title">Other Information</h3>
									</div>
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Any other information that the candidate would like to add</label>
										<div class="col-sm-6" style="word-wrap: break-word;">
											<?php 
												$comment = $this->session->userdata['enduserinfo']['comment'];
											echo wordwrap($comment,75,"<br>\n"); ?>
										</div>
									</div>
									<div class="box box-info">
										<div class="box-header with-border">
											<h3 class="box-title">Declaration</h3>
										</div>
									</div>
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Declaration&nbsp;</label>
										<div class="col-sm-8" align="justify"> <strong><?php echo $this->session->userdata['enduserinfo']['declaration2'];?></strong> &nbsp;
										&nbsp;I declare that all statements made in this application are true, complete and correct to the best  of  my  knowledge  and  belief . I also declare that I  have  not  suppressed  any  material  fact(s)/information.  I understand that in the event of any information being found untrue or incomplete at any stage or my not satisfying  any  of  the  eligibility  criteria  according  to  the  requirements  of  the  related  advertisement of Indian Institute Banking & Finance, my candidature / appointment for the said post is liable to be cancelled at any stage and even after appointment, my services are liable to be terminated without any notice.</div>
									</div>
									<div class="box box-info">
										<div class="box-header with-border">
											<h3 class="box-title">UPLOAD</h3>
										</div>
									</div>
									<div class="form-group">
										<label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedphoto'];?>" height="100" width="100" ></label>
										<label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedsignaturephoto'];?>" height="100" width="100"></label>
										<!--<label for="roleid" class="col-sm-3 control-label"><a href="<?php //echo $this->
										//session->userdata['enduserinfo']['uploadcv_path'];?>" target="_blank"><img src="<?php //echo base_url() ?>/uploads/uploadcv/resume.png" height="100" width="100">Download</a>
										</label>
										--> </div>
										<div class="form-group">
											<label for="roleid" class="col-sm-3 control-label">Uploaded Photo</label>
											<label for="roleid" class="col-sm-3 control-label">Uploaded Signature</label>
											<!--<label for="roleid" class="col-sm-3 control-label">uploaded Resume/CV</label>-->
										</div>
										<div class="box box-info">
											<div class="box-header with-border">
												<h3 class="box-title">Place &amp; Date</h3>
												
											</div>
											<div class="form-group">
												<label for="roleid" class="col-sm-4 control-label">Place</label>
												<div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['place'];?></div>
											</div>
											<div class="form-group">
												<label for="roleid" class="col-sm-4 control-label">Date</label>
												<div class="col-sm-6">
													<div class="col-sm-5 date"> <?php echo $this->session->userdata['enduserinfo']['submit_date'];?> </div>
												</div>
											</div>
										</div>
								</div>
								<div class="box box-info">
									<div class="box-footer">
										<div class="col-sm-4 col-xs-offset-3">
											<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit Application">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</form>
</div>