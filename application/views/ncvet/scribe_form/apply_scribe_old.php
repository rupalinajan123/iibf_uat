<!DOCTYPE html>
<html>
	<head>
		<?php $this->load->view('scribe_form/inc_header'); ?>
		<style type="text/css">
		#scribe
			{
				text-align: left;
			    background-color: #1287c0;
			    margin: 10px 0;
			    border-radius: 2px;
			    color: #fff;
			    font-size: 15px;
			    line-height: 24px;
			}
		.main-footer
			{
				border-top: none;
			}
		#row1,#row2{
			
			    display: flex;
			    flex-wrap: nowrap;
			}
		#refresh1,#refresh2
			{
				font-size: 12px;
				line-height: normal;
				margin-left: 5px;
			}
			.container1
			{
				margin: auto;
			}
			.form-horizontal{
				padding: 12px;
			}
		@media screen and (max-width: 480px) 
		{
			.login-logo a 
			{
				text-align: center;
				font-size: 18px;
				display: inline-block;
			}
			label
			{  padding: 2%; }

			.container 
			{   width: 90%; }
			.main-header 
			{  width: 90%; }
		}						
		</style>

		
	</head>
	
	<body class="hold-transition skin-blue layout-top-nav">
		<div class="wrapper">
			<div></div>
			
			<div class="container1">				
				<section class="content">
										<div class="col-md-12" >  						
						<div  class ="row" id="form1" style="display:block;">						
							
							<div class="box box-info">
								<form class="form-horizontal" name="usersAddForm" id="usersAddForm1"  method="post"  action="" autocomplete="off">
									<h3 class="alert text-center text-bold text-dark">Apply for Scribe</h3>
									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Membership/Registration No.<span style="color:#F00">*</span></label>
										<div class="col-sm-6">
											<input type="text" class="form-control" id="member_no" name="member_no" placeholder="Membership/Registration No" required value="" >
																					</div>
									</div>	
																					<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Exam Name.<span style="color:#F00">*</span></label>
										<div class="col-sm-6">
											<select class="form-control chosen-select" id="exam_code1" name="exam_code" required autofocus data-placeholder="Select Exam">
																								<option value="8" >DIPLOMA IN BANKING TECHNOLOGY- January-2025 - 8</option>
																										
																							</select>
																				</div>
									</div>	

									<div class="form-group">
										<label for="roleid" class="col-sm-4 control-label">Subject Name<span style="color:#F00">*</span></label>
										<div class="col-sm-6">
											<select id='sel_subject1' class="form-control chosen-select" name="subject_code" >
									          <option>-- Select subject --</option>
									        </select>
										</div>
									</div>	
									
									<div class="form-group">
										
										<label for="roleid" class="col-sm-4 control-label" style="line-height:20px;">Security Code <span style="color:#F00; ">*</span></label>										
										
										<div class="col-sm-3">
											<input type="text" name="captcha_code" id="captcha_code" required class="form-control" placeholder="Security Code" maxlength="5" value="">
										</div>										
										
										<div class="col-sm-5" id="row1">
											<div id="captcha_img">	<style>
									.CaptchaBgText { position: relative; width: 150px; height: 30px; background-image: url(https://iibf.esdsconnect.com/assets/images/captcha_bg.png); background-size: 60% 70%; border: 1px solid #A2A2A2; background-color: #b7d2ed; }
									
									.CaptchaBgText::after { content: "LALGB"; position: absolute; color: #10275b; top: 0; left: 0; width: 150px; height: 30px; text-align: center; font-size: 15px; font-weight: 600; letter-spacing: 12px; overflow: hidden; line-height: 28px; }
								</style>
								<div class="CaptchaBgText"></div></div>
											<a id="refresh1" href="javascript:void(0);" onclick="refresh_captcha_img();" class="text-danger btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i></a>
										</div>
									</div>
																		
									<div class="col-sm-12 text-center">
										<input type="button" class="btn btn-info" name="btn_Submit" id="btn_Submit" onclick="change_scribe();" value="Get Details">&nbsp;&nbsp;
                   
                    			     <input type="button" class="btn btn-info" value="Cancel" onclick="window.location='https://iibf.esdsconnect.com/Scribe_form/index'">
									</div>
									
								</form>
								
							</div>

							
						</div>

											</div>
				</section>
			</div>
		</div>	