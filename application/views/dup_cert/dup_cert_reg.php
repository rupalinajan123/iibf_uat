<style>
.modal-dialog{
    position: relative;
    display: table; 
    overflow-y: auto;    
    overflow-x: auto;        
    width: 920px;
    min-width: 300px;   
}

#confirm .modal-dialog{
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

#confirmBox
{
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
#confirmBox .button:hover
{
    background-color: #ddd;
}
#confirmBox .message
{
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
a.forget  {color:#9d0000;}
a.forget:hover {color:#9d0000; text-decoration:underline;}
ol li {
	line-height:18px;
}
.example {
	text-align:left !important;
	padding:0 10px;
}
.uppercase {
    text-transform: uppercase;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
//if(@$result){print_r(@$result[0]['lastname']);}
?>

	<div class="container">
	
  	<section class="content">
		<section class="content-header">
			   <h1 class="register"> 
		   Application for Duplicate Certificate in e-format (Not applicable for Examinations held after 1st Oct 2019)
		   </h1><br/>
		</section>
		
     <!-- <div class="row">
        <!--<div class="col-md-1"></div>     --     
            <div class="col-md-12">
            	<h4> I, as an employee of the bank/financial institution mentioned below, apply myself for being admitted as an Ordinary Member of Indian Institute of Banking &amp; Finance (I have never been a Member of the Institute in the past.)
            Please enter your details carefully, correction may not be possible later
            </h4>
        </div>
        <!--<div class="col-md-1"></div>--          
      </div>-->
     
        <div class="col-md-12">  
          <!-- Horizontal Form -->
		  <div  class ="row">
		   <?php //echo validation_errors(); ?>
              <?php if($this->session->flashdata('error')!=''){?>
                
                <div class="alert alert-danger alert-dismissible" id="error_id">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
                
              <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?>
              </div>
             <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible" id="error_id">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo validation_errors(); ?>
                </div>
              <?php }
			  if(@$var_errors!='')
			  {?>
                <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                <?php echo $var_errors; ?>
        	    </div>
				 
				 <?php 
			  } 
			 ?> 
			 <div class="box box-info">
                <div class="box-header with-border " style="background-color: #1287c0;"><!--header_blue-->
					<h3 class="box-title">Note:</h3>
				</div>
            
            <div class="box-body blue_bg">
                <div class="form-group">

				    <div class="col-sm-12">
						<ol style="list-style-type: none;">
						<?php //if(@$result[0]['is_dra_mem'] != '2'){?>
						<li> •  Application for Duplicate Certificate in e-format (Not applicable for Examinations held after 1st Oct 2019).
						</li>
						<li> •  The candidate requesting for a Duplicate e-certificate needs to pay <span style="color:#F00; font-size:14px; font-weight:bold;">one-time conversion </span> fee Rs.200/- + GST.
						</li>
						<li> •  The <span style="color:#F00; font-size:14px; font-weight:bold;"> candidates who have </span> received certificate in e-certificate format <span style="color:#F00; font-size:14px; font-weight:bold;"> earlier </span> need not apply for the Duplicate e-certificate <span style="color:#F00; font-size:14px; font-weight:bold;"> again.</span>
						</li>
						<li> •  Such candidates send an e-mail to <a href="mailto:care@iibf.org.in" target="_blank"><span style="color: #1287C0;">care@iibf.org.in</span> </a> </span> requesting for copy of e-certificate with subject line “Copy of e-Certificate”  giving member/registration no, exam name etc.
						</li>
						<li> •   <span style="color:#F00; font-size:14px; font-weight:bold;"> Candidate will receive duplicate certificate in e-format from the e-mail </span> <a href="mailto:iibfnoreplymail@iibf.org.in" target="_blank"><span style="color: #1287C0;">iibfnoreplymail@iibf.org.in</span> </a>  </span>
						</li>
						<li> •   <span style="color:#F00; font-size:14px; font-weight:bold;"> If the candidate wishes to change e-mail id they can do so by editing their profile or e-mail to  </span> <a href="mailto:care@iibf.org.in" target="_blank"><span style="color: #1287C0;">care@iibf.org.in</span> </a>  </span>
						</li>
						
						<?php // } ?>
						<li> •  e-certificate will be emailed within one month after submission of application.
						</li>
						<li> •  The Paper Certificate (Duplicate) presently issued by the Institute has been discontinued.
						</li>
						
						</ol>
                    </div>
                </div>
   
            </div>
             
            </div><br>
		   <div class="box box-info">
           
				<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  action="<?php echo base_url();?>DupCert/getdetails"  autocomplete="off">
				<?php /*if(empty($result[0]['sel_exam'])){?>
				    <div class="form-group">
					<label for="" class="col-sm-4 control-label">Examination (select the correct name)<span style="color:#F00">*</span></label>
					
					    <div class="col-sm-4"  style="display:block" >
							<select id="sel_exam" name="sel_exam" class="form-control" required>
							 <option value="">--Select--</option>
							<?php if(count($exams)){
									foreach($exams as $exams_row){ 	?>
								<option value="<?php echo $exams_row['exam_code'];?>" <?php echo  set_select('sel_exam', $exams_row['exam_code']); ?>><?php echo $exams_row['description'];?></option>
								<?php } } ?>
							</select>
						 <span class="error"><?php //echo form_error('designation');?></span>
					    </div>
					</div>
					<?php } */?>
					<div class="form-group">
					<label for="roleid" class="col-sm-4 control-label">Membership/Registration no<span style="color:#F00">*</span></label>
						<div class="col-sm-4">
							<input type="text" class="form-control " id="member_no" name="member_no" data-parsley-type="number" placeholder="Membership/Registration no"  value="<?php echo @$result[0]['regnumber'] ;?>" <?php if(!empty($result[0]['regnumber'])){echo "disabled='disabled'";}?> required data-parsley-trigger-after-failure="focusout" >
						  <span class="error"><?php //echo form_error('member_no');?></span>
						</div>
					
					<!--<div class="form-group"></div>-->
					 <?php if(!empty($result[0]['regnumber'])){?><input type="hidden" class="form-control" name="member_no" value="<?php echo @$result[0]['regnumber'] ;?>" autocomplete="false"> <?php } ?>
						<div class="col-sm-4">
						    <input type="submit" class="btn btn-info" name="btn_Submit" id="btn_Submit" value="get details">
					    </div>
					 </div>
					   <?php if(empty($result[0]['regnumber'])){?>
					<div class="form-group m_t_15">
                <label for="roleid" class="col-sm-3 control-label">Security Code<span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                      <input type="text" name="code" id="code"  class="form-control" required>
                         <span class="error" id="captchaid" style="color:#B94A48;"></span>
                         
                    </div>
                     <div class="col-sm-3">
                         <div id="captcha_img"><?php echo @$image;?></div>
                         <span class="error"><?php //echo form_error('code');?></span>
                    </div>
                    <div class="col-sm-3">
                          <a href="javascript:void(0);" id="new_captcha"  class="forget">Change Image</a>
                         <span class="error"><?php //echo form_error('code');?></span>
                    </div>
                      
            </div> 
			<?php  }?>
					 <div class="form-group"><div class="col-sm-12"><span style="color:#F00; font-size:13px; font-weight:bold">Please insert your membership/regsitration no and click on get details. All below details will get filled automatically.</span></div></div>
					</form>
			</div>
				 <!-- form start -->
		  <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data"
		   action="<?php echo base_url();?>DupCert/getdetails"  onsubmit="return validateForm(this)"data-parsley-validate="parsley" >
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
            <!-- /.box-header -->
           <?php if(empty($result)){?>
		   <div class="box-body">
                <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none">
                    <span>display ajax response errors here</span>
                </div>
				   
				    <!--<div class="form-group">
					  <label for="roleid" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>
					  <div class="col-sm-6">
						<input type="text" class="form-control" id="name" name="name" placeholder="Name Of Candidate"  value="<?php echo set_value('name');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
						<span class="error">
						<?php //echo form_error('nameOfBank');?>
						</span> </div>
					</div>-->
					<div class="form-group">
					  <label for="roleid" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>
					
						<input type="hidden" class="form-control" id="namesub" name="namesub" value="<?php  echo set_value('namesub');?>" placeholder="select" autocomplete="false">
						
						<div class="col-sm-3">
						
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo set_value('firstname');?>" placeholder="Firstname" readonly="readonly">
						</div>
						<div class="col-sm-3">
						<input type="text" class="form-control" id="middlename" name="middlename" value="<?php  echo set_value('middlename');?>" placeholder="Middlename"  readonly="readonly">
						</div>
						<div class="col-sm-3">
						<input type="text" class="form-control" id="lastname" name="lastname" value="<?php  echo set_value('lastname');?>" placeholder="Lastname"  readonly="readonly">
						</div>
						
						
					</div>
					
					<div class="form-group">
					<label for="" class="col-sm-3 control-label">Examination (select the correct name)<span style="color:#F00">*</span></label>
					    <div class="col-sm-6"  style="display:block" >
                        <?php if(!empty($result[0]['regnumber']))
						{?>
							
							<select id="sel_exam" name="sel_exam" class="form-control" required>
							 <option value="">--Select--</option>
																
							<!--<option value="">--extra added exam 1--</option>
								<option value="">--extra added exam 2--</option>-->
							
								<?php/* if(count($exams))
								{
									foreach($exams as $exams_row)
									{ 	?>
										<option value="<?php echo $exams_row['exam_code'];?>" <?php echo  set_select('sel_exam', $exams_row['exam_code']); ?>><?php echo $exams_row['description'];?></option>
									<?php 
									}
									
								 } */ ?>
							</select>
						<?php }else
						{?>
							<select id="sel_exam" name="sel_exam" class="form-control" required disabled>
							 <option value="">--Select--</option>
                            </select>
					<?php 	}
						?>
							
						 <span class="error"><?php //echo form_error('designation');?></span>
					    </div>
					</div>
					
					<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo set_value('email');?>"  data-parsley-maxlength="45" required  data-parsley-cpdemailcheck data-parsley-trigger-after-failure="focusout"   readonly="readonly">
						 <!-- (Enter valid and correct email ID to receive communication)-->
						  <span class="error"><?php //echo form_error('email');?></span>
						</div>
					</div> 
						
					<div class="form-group"> 
					<label for="roleid" class="col-sm-3 control-label">Mobile<span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('mobile');?>"  required  data-parsley-cpdmobilecheck  data-parsley-trigger-after-failure="focusout"   readonly="readonly">
						  <span class="error"><?php //echo form_error('mobile');?></span>
						</div>
					</div> 	
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line1<span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo set_value('addressline1');?>"  data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"  readonly="readonly">(Max 30 characters accepted)
						  <span class="error"><?php //echo form_error('addressline1');?></span>
						</div>
						  
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line2</label>
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo set_value('addressline2');?>"  data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"  readonly="readonly">
						  <span class="error"><?php //echo form_error('addressline2');?></span>
						</div>
						    
					</div>--> 
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line3</label>
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo set_value('addressline3');?>"  data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"  readonly="readonly">
						  <span class="error"><?php //echo form_error('addressline3');?></span>
						</div>
						   
					</div> -->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line4</label>
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo set_value('addressline4');?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"  readonly="readonly">
						  <span class="error"><?php //echo form_error('addressline4');?></span>
						</div>
						  
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">District <span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo set_value('district');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30"  readonly="readonly">
						  <span class="error"><?php //echo form_error('district');?></span>
						</div>
						 
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">City <span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo set_value('city');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30"  readonly="readonly">
						  <span class="error"><?php //echo form_error('city');?></span>
						</div>
						 
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">State <span style="color:#f00">*</span></label>
						<div class="col-sm-3">
					    <?php if(!empty($result[0]['regnumber']))
						{?>
							
                                <select class="form-control" id="state" name="state" required disabled="disabled" >
                                    <option value="">Select</option>
                                    <?php if(count($states) > 0)
									{
                                            foreach($states as $row1)
											{ 	?>
                                                <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
                                                <?php 
                                            }
									 } ?>
                                 </select>
              <?php }else{
				  ?>
                	  <select class="form-control" id="state" name="state" required  disabled>
							 <option value="">--Select--</option>
                            </select>
				  <?php
			  }
			   ?>
						<input hidden="statepincode" id="statepincode" value="">
						</div> 
						 <label for="roleid" class="col-sm-3 control-label">Pincode/Zipcode <span style="color:#f00">*</span></label>
					   
						<div class="col-sm-3">
							<input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode');?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-dbfcheckpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout"  readonly="readonly"> (Max 6 digits)
							 <span class="error"><?php //echo form_error('pincode');?></span>
						</div>
					</div>-->
				
                </div>
                <?php }elseif($result[0]['is_dra_mem'] == '2'){ 
				?>
				
				 <div class="box-body">
                <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none">
                    <span>display ajax response errors here</span>
                </div>
				    <input type="hidden" class="form-control" name="member_no" value="<?php echo @$result[0]['regnumber'] ;?>" autocomplete="false">
				    <input type="hidden" class="form-control" name="is_dra_mem" value="<?php echo @$result[0]['is_dra_mem'] ;?>" autocomplete="false">
				  <input type="hidden" class="form-control" name="registrationtype" value="<?php echo @$result[0]['registrationtype'] ;?>" autocomplete="false">
				  
				    <!--<div class="form-group">
					  <label for="roleid" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>
					  <div class="col-sm-6">
						<input type="text" class="form-control" id="candidate_name" name="candidate_name" placeholder="Name Of Candidate"  value="<?php echo $result[0]['namesub'].' '.$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname']?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="60" maxlength="30" readonly="readonly">
						<span class="error">
						<?php //echo form_error('nameOfBank');?>
						</span> </div>
					</div>-->
					<div class="form-group">
					  <label for="roleid" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>
					
						<input type="hidden" class="form-control" id="namesub" name="namesub" value="<?php echo $result[0]['namesub'];?>"autocomplete="false" >
						
						<div class="col-sm-3">
						<input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $result[0]['firstname'];?>" required readonly="readonly">
						</div>
						<div class="col-sm-3">
						<input type="text" class="form-control" id="middlename" name="middlename" value="<?php echo $result[0]['middlename'];?>" readonly="readonly" >
						</div>
						<div class="col-sm-3">
						<input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $result[0]['lastname'];?>" readonly="readonly">
						</div>
					</div>
					
					<!--<div class="form-group">
					<label for="" class="col-sm-3 control-label">Examination (select the correct name)<span style="color:#F00">*</span></label>
					    <div class="col-sm-6"  style="display:block" >
							<select id="sel_exam" name="sel_exam" class="form-control" readonly="readonly" required>
							 <option value="">--Select--</option>
							 <?php if(!empty($exams)){
											foreach($exams as $exams_row){ ?>
									 <option value="<?php echo $exams_row['exam_code'];?>" <?php if($result[0]['sel_exam']==$exams_row['exam_code']){echo "selected='selected'";} ?>><?php echo $exams_row['description'];?></option>
									<?php } } ?>
							</select>
						 <span class="error"><?php //echo form_error('designation');?></span>
					    </div>
					</div>-->
						
					<div class="form-group">
					<label for="" class="col-sm-3 control-label">Examination (select the correct name)<span style="color:#F00">*</span></label>
					    <div class="col-sm-6"  style="display:block" >
						<?php if(!empty($result[0]['regnumber']))
						{?>
							<select id="sel_exam" name="sel_exam" class="form-control"  required >
							  <?php if(count($exam_name) > 0)
									{
                                            foreach($exam_name as $row1)
											{ 	?>
                                                <option value="<?php echo $row1[0]['description'].'##'.$row1[0]['exam_code'];?>" <?php echo  set_select('sel_exam', $row1[0]['description']); ?>><?php echo $row1[0]['description'];?></option>
													
                                                <?php 
                                            }
											 } ?>
									
                                 </select>
							
							 
									
						 <?php }else{
				  ?>
                	  <select class="form-control" id="state" name="state" required  disabled>
							 <option value="">--Select--</option>
                            </select>
				  <?php
			  }
			   ?>	
						 <span class="error"><?php //echo form_error('designation');?></span>
					    </div>
					</div>
					
					<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo $result[0]['email'] ;?>"  data-parsley-maxlength="45" required   data-parsley-trigger-after-failure="focusout" >
						 <!-- data-parsley-cpdemailcheck(Enter valid and correct email ID to receive communication)-->
						  <span class="error"><?php //echo form_error('email');?></span>
						</div>
					</div> 
						
					<div class="form-group"> 
					<label for="roleid" class="col-sm-3 control-label">Mobile<span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="tel" class="form-control setAlg" id="mobile" name="mobile" placeholder="mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo $result[0]['mobile'] ;?>"  required   data-parsley-trigger-after-failure="focusout"  readonly="readonly">
						  <span class="error"><?php //data-parsley-cpdmobilecheck  echo form_error('mobile');?></span>
						</div>
					</div> 	
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line1<span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo $result[0]['address1']; ?>"  data-parsley-maxlength="30" maxlength="30"> (Max 30 characters accepted)
						  <span class="error"><?php // data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"   echo form_error('addressline1');?></span>
						</div>
						  
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line2</label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo $result[0]['address2'];?>"  data-parsley-maxlength="30" maxlength="30">
						  <span class="error"><?php //echo form_error('addressline2');?></span>
						</div>
						    
					</div> 
					-->
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line3</label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo $result[0]['address3']; ?>"  data-parsley-maxlength="30" maxlength="30">
						  <span class="error"><?php //echo form_error('addressline3');?></span>
						</div>
						   
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line4</label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo $result[0]['address4'];?>" data-parsley-maxlength="30" maxlength="30">
						  <span class="error"><?php //echo form_error('addressline4');?></span>
						</div>
						  
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">District <span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="district" name="district" placeholder="District" required value="<?php echo $result[0]['district'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30"  >
						  <span class="error"><?php //echo form_error('district');?></span>
						</div>
						 
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">City <span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="city" name="city" placeholder="City" required value="<?php echo $result[0]['city'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" >
						  <span class="error"><?php //echo form_error('city');?></span>
						</div>
						 
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">State <span style="color:#F00">*</span></label>
						<div class="col-sm-4">
						<select class="form-control change" id="state" name="state" required  onchange="javascript:checksate(this.value)" > 
							<option value="">Select</option>
							<?php if(count($states) > 0){
									foreach($states as $row1){ 	?>
							<option value="<?php echo $row1['state_code'];?>" <?php if(!empty($result[0]['state'])) {if($result[0]['state']==$row1['state_code']){echo "selected='selected'";}} ?>><?php echo $row1['state_name'];?></option>
							<?php } } ?>
						  </select> -->
						
					
						<input hidden="statepincode" id="statepincode" value="" autocomplete="false">
		               <input type="hidden" class="form-control" name="state_ds" id="state_ds" value="<?php echo @$result[0]['state'] ;?>" autocomplete="false">
						<!--</div> 
						 <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
					   
						 <div class="col-sm-3">
							<input type="text" class="form-control setAlg" id="pincode" name="pincode" placeholder="Pincode/Zipcode" value="<?php echo $result[0]['pincode'];?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout" required > (Max 6 digits)
							 <span class="error"><?php //echo form_error('pincode');?></span>
						</div>
					</div>-->
					
					<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Fees<span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="fees" name="fees" placeholder="fees" required value="<?php if(!empty($result[0]['state'])){if($result[0]['state'] =='MAH'){echo $this->config->item('Dup_cert_cs_total');}else{echo $this->config->item('Dup_cert_igst_tot');}}?>" readonly="readonly">
						  <span class="error"><?php //echo form_error('city');?></span>
						</div>
						 
					</div>
					
				
                </div>
				 <?php } else{ ?>
				 <div class="box-body">
                <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none">
                    <span>display ajax response errors here</span>
                </div>
				    <input type="hidden" class="form-control" name="member_no" value="<?php echo @$result[0]['regnumber'] ;?>" autocomplete="false">
					<input type="hidden" class="form-control" name="is_dra_mem" value="<?php echo @$result[0]['is_dra_mem'] ;?>" autocomplete="false">
				  <input type="hidden" class="form-control" name="registrationtype" value="<?php echo @$result[0]['registrationtype'] ;?>" autocomplete="false">
				  
				    <!--<div class="form-group">
					  <label for="roleid" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>
					  <div class="col-sm-6">
						<input type="text" class="form-control" id="candidate_name" name="candidate_name" placeholder="Name Of Candidate"  value="<?php echo $result[0]['namesub'].' '.$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname']?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="60" maxlength="30" readonly="readonly">
						<span class="error">
						<?php //echo form_error('nameOfBank');?>
						</span> </div>
					</div>-->
					<div class="form-group">
					  <label for="roleid" class="col-sm-3 control-label">Candidate Name<span style="color:#F00">*</span> </label>
					
						<input type="hidden" class="form-control" id="namesub" name="namesub" value="<?php echo $result[0]['namesub'];?>" readonly="readonly" autocomplete="false">
						
						<div class="col-sm-3">
						<input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $result[0]['firstname'];?>" readonly="readonly" required>
						</div>
						<div class="col-sm-3">
						<input type="text" class="form-control" id="middlename" name="middlename" value="<?php echo $result[0]['middlename'];?>" readonly="readonly">
						</div>
						<div class="col-sm-3">
						<input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $result[0]['lastname'];?>" readonly="readonly">
						</div>
					</div>
					
					<!--<div class="form-group">
					<label for="" class="col-sm-3 control-label">Examination (select the correct name)<span style="color:#F00">*</span></label>
					    <div class="col-sm-6"  style="display:block" >
							<select id="sel_exam" name="sel_exam" class="form-control" readonly="readonly" required>
							 <option value="">--Select--</option>
							 <?php if(!empty($exams)){
											foreach($exams as $exams_row){ ?>
									 <option value="<?php echo $exams_row['exam_code'];?>" <?php if($result[0]['sel_exam']==$exams_row['exam_code']){echo "selected='selected'";} ?>><?php echo $exams_row['description'];?></option>
									<?php } } ?>
							</select>
						 <span class="error"><?php //echo form_error('designation');?></span>
					    </div>
					</div>-->
					<div class="form-group">
					<label for="" class="col-sm-3 control-label">Examination (select the correct name)<span style="color:#F00">*</span></label>
					    <div class="col-sm-6"  style="display:block" >
						<?php if(!empty($result[0]['regnumber']))
						{?>
							<select id="sel_exam" name="sel_exam" class="form-control"  required >
							  <?php if(count($exam_name) > 0)
									{
                                            foreach($exam_name as $row1)
											{ 	?>
                                                <option value="<?php echo $row1[0]['description'].'##'.$row1[0]['exam_code'];?>" <?php echo  set_select('sel_exam', $row1[0]['description']); ?>><?php echo $row1[0]['description'];?></option>
													
                                                <?php 
                                            }
											 } ?>
									
                                 </select>
							
							 
									
						 <?php }else{
				  ?>
                	  <select class="form-control" id="state" name="state" required  disabled>
							 <option value="">--Select--</option>
                            </select>
				  <?php
			  }
			   ?>	
						 <span class="error"><?php //echo form_error('designation');?></span>
					    </div>
					</div>
					
					<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo $result[0]['email'] ;?>"  data-parsley-maxlength="45" required   data-parsley-trigger-after-failure="focusout">
						 <!-- data-parsley-cpdemailcheck(Enter valid and correct email ID to receive communication)-->
						  <span class="error"><?php //echo form_error('email');?></span>
						</div>
					</div> 
						
					<div class="form-group"> 
					<label for="roleid" class="col-sm-3 control-label">Mobile<span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="tel" class="form-control setAlg" id="mobile" name="mobile" placeholder="mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo $result[0]['mobile'] ;?>"  required    data-parsley-trigger-after-failure="focusout" readonly="readonly">
						  <span class="error"><?php //data-parsley-cpdmobilecheck  echo form_error('mobile');?></span>
						</div>
					</div> 	
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line1<span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo $result[0]['address1']; ?>"  data-parsley-maxlength="30" maxlength="30"> (Max 30 characters accepted)
						  <span class="error"><?php // data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"   echo form_error('addressline1');?></span>
						</div>
						  
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line2</label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo $result[0]['address2'];?>"  data-parsley-maxlength="30" maxlength="30">
						  <span class="error"><?php //echo form_error('addressline2');?></span>
						</div>
						    
					</div> -->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line3</label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo $result[0]['address3']; ?>"  data-parsley-maxlength="30" maxlength="30">
						  <span class="error"><?php //echo form_error('addressline3');?></span>
						</div>
						   
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Address line4</label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo $result[0]['address4'];?>" data-parsley-maxlength="30" maxlength="30">
						  <span class="error"><?php //echo form_error('addressline4');?></span>
						</div>
						  
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">District <span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="district" name="district" placeholder="District" required value="<?php echo $result[0]['district'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" >
						  <span class="error"><?php //echo form_error('district');?></span>
						</div>
						 
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">City <span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control setAlg" id="city" name="city" placeholder="City" required value="<?php echo $result[0]['city'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" >
						  <span class="error"><?php //echo form_error('city');?></span>
						</div>
						 
					</div>-->
					
					<!--<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">State <span style="color:#F00">*</span></label>
						<div class="col-sm-4">
						<select class="form-control change" id="state" name="state" required onchange="javascript:checksate(this.value)" >
							<option value="">Select</option>
							<?php if(count($states) > 0){
									foreach($states as $row1){ 	?>
							<option value="<?php echo $row1['state_code'];?>" <?php if(!empty($result[0]['state'])) {if($result[0]['state']==$row1['state_code']){echo "selected='selected'";}} ?>><?php echo $row1['state_name'];?></option>
							<?php } } ?>
						  </select>-->
						
						<input hidden="statepincode" id="statepincode" value="" autocomplete="false">
		               <input type="hidden" class="form-control" name="state_ds" id="state_ds" value="<?php echo @$result[0]['state'] ;?>" autocomplete="false">
						<!--</div> 
						 <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
					   
						 <div class="col-sm-3">
							<input type="text" class="form-control setAlg" id="pincode" name="pincode" placeholder="Pincode/Zipcode" value="<?php echo $result[0]['pincode'];?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout" required > (Max 6 digits)
							 <span class="error"><?php //echo form_error('pincode');?></span>
						</div>
					</div>-->
					
					<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Fees<span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="fees" name="fees" placeholder="fees" required value="<?php if(!empty($result[0]['state'])){if($result[0]['state'] =='MAH'){echo $this->config->item('Dup_cert_cs_total');}else{echo $this->config->item('Dup_cert_igst_tot');}}?>" readonly="readonly">
						  <span class="error"><?php //echo form_error('city');?></span>
						</div>
						 
					</div>
					
				
                </div>
				<?php } ?>
               </div> 
			
			  
                <?php 
				$star='';
				$requiredflag=0;
				if(set_value('state'))
				{
					if(set_value('state')!='ASS' && set_value('state')!='JAM' && set_value('state')!='MEG')
					{
						$star='*';
						$requiredflag=1;
					}
				}
				else
				{
					$star='*';
					$requiredflag=1;
				}?>
                 
               
             
            <div class="box box-info">
            <!--<div class="box-header with-border">
              <h3 class="box-title">  <input name="declaration1" value="1" type="checkbox" required="required" 
			  <?php if(set_value('declaration1'))
			  {
				  echo set_radio('declaration1', '1');
				 }?>>&nbsp; I Accept</h3>
            </div>-->
            <?php if($result[0]['regnumber']){?>
            <div class="form-group m_t_15">
                <label for="roleid" class="col-sm-3 control-label">Security Code<span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                      <input type="text" name="code" id="code" required class="form-control " >
                         <span class="error" id="captchaid" style="color:#B94A48;"></span>
                         
                    </div>
                     <div class="col-sm-3">
                         <div id="captcha_img"><?php echo @$image;?></div>
                         <span class="error"><?php //echo form_error('code');?></span>
                    </div>
                    <div class="col-sm-3">
                          <a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a>
                         <span class="error"><?php //echo form_error('code');?></span>
                    </div>
                      
            </div> 
			<?php }?>
			<div class="box-footer">
					<div class="col-sm-6 col-sm-offset-3">
					<!-- <a href="javascript:void(0);" class="btn btn-info"onclick="javascript:return checkform();" id="preview">Preview and Proceed for Payment</a>-->
					<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="submit">
					<!--<a href="<?php echo base_url();?>DupCert" class="btn btn-default"> <button type="reset" class="btn btn-default" value="Reset" name="btnReset" id="btnReset">Reset</button></a>-->
					<a href="<?php echo base_url();?>DupCert" class="btn btn-default"> Reset</a>
					</div>
			</div>
           </div>
        </div>
      </div>
     
     
    </section>
  
  </div>
  <div class="modal fade" id="confirm"  role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" style="color:#F00"> <strong>VERY IMPORTANT</strong><br></h4>
      </div>
      <div class="modal-body">
        <p>
		Mandatory fields of form are blank. Kindly go to <a href=<?php if(!empty($result[0]['registrationtype'])){if($result[0]['registrationtype']=='NM'){echo base_url('nonmem');}else{echo base_url();}}else{echo base_url();} ?> target="_blank"><span style="color:#0000FF">edit profile</span></a> and update your profile in order to submit the form.</p>
		 
         <!--Required fields are blank !please <a href=<?php if(!empty($result[0]['registrationtype'])){if($result[0]['registrationtype']=='NM'){echo base_url('nonmem');}else{echo base_url();}}else{echo base_url();} ?> target="_blank"><span style="color:#F00">edit your profile</span></a>.</p>-->
      </div>
      <!--<div class="modal-footer">
       <!--  <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="preview();">Confirm</button>--
      <input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm">
      </div>-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
  </form>
  <link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
  <script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
  <script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
  <script src="<?php echo base_url();?>js/validation.js?<?php echo time(); ?>"></script> 

<script>
$(document).ready(function() 
{
     //var form_flag=$('#usersAddForm').parsley().validate();
	$('#new_captcha').click(function(event){
			event.preventDefault();
		$.ajax({
			type: 'POST',
			url: site_url+'DupCert/generatecaptchaajax/',
			success: function(res)
			{	
				if(res!='')
				{$('#captcha_img').html(res);
				}
			}
		});
		});
		/*$('#checkmemtxt').click(function(event){
			var mem_no = $('#member_no').val();
			//alert(mem_no);
			if(mem_no == '')
			{
			 alert('Please put member number first');
			 return false;
			}
			
		});*/
			
});

function checkExam()
{
	var is_dra_mem = '<?php echo $result[0]['is_dra_mem']; ?>';
	//var exam = $('#sel_exam').val();  
	if(is_dra_mem == '2')
	{alert('***');
		$( '.setAlg' ).each(function() {
		  $( this ).removeAttr('disabled');
		   $( this ).removeAttr('readonly');
		    $('#state').removeAttr('disabled')
		});
		// $('.setAlg').removeAttr('disabled');
	}
	else
	{
		
		$('.setAlg').attr('readonly', 'readonly');
		$('.change').attr('disabled', 'disabled');
	}
}
checkExam();
    function validateForm(form)
    {
	    //var 
		var member_no = document.getElementById('member_no').value;
		//alert(member_no);
		if(member_no=="")
		{
			alert('First get details of member and then submit');
			document.getElementById("member_no").focus();
			return false;
		}
		else
		{
		
			var is_dra_mem = '<?php echo @$result[0]['is_dra_mem']; ?>';
			
		    if(is_dra_mem != '2')
			{
			
				var firstname=form.firstname.value;
				var email=form.email.value;
				var mobile=form.mobile.value;
				//var addressline1=form.addressline1.value;
				//var district=form.district.value;
				//var city=form.city.value;
				//var state=form.state.value;
				//var pincode=form.pincode.value;
				//alert();
				if( firstname=="" ||email=="" || mobile=="" )
				{
					$('#confirm').modal('show');
					/*var answer = confirm ("Required fields are blank !fill these fields , Please click on OK to continue.")
					if (answer)
					//window.location='<?php echo base_url();?>';
					window.location='<?php if(!empty($result[0]['registrationtype'])){if($result[0]['registrationtype']=='NM'){echo base_url('nonmem');}else{echo base_url();}}else{echo base_url();} ?>';*/
				}
			}
		}
    }
	
//$('#state').attr('disabled', 'disabled');
//$('#state').removeAttr('disabled');
</script>