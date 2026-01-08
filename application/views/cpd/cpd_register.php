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

	<div class="container">
	
  	<section class="content">
        <section class="content-header">
			   <h1 class="register"> 
		   CPD - REGISTRATION 
		   </h1><br/>
		</section>
		
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
            
				 <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  action="<?php echo base_url();?>Cpd/getdetails" autocomplete="off">
					<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Membership No<span style="color:#F00">*</span></label>
						<div class="col-sm-3">
						  <input type="text" class="form-control " id="member_no" name="member_no" placeholder="Membership No" value="<?php echo @$result[0]['regnumber'] ;?>" <?php if(!empty($result[0]['regnumber'])){echo "disabled='disabled'";}?> required>
						  <span class="error"><?php //data-parsley-type="number" //echo form_error('member_no');?></span>
						</div>
					
					<!--<div class="form-group"></div>
					 <?php if(empty($result[0]['regnumber'])){?><div class="col-sm-4"><input type="submit" class="btn btn-info" name="btn_Submit" id="btn_Submit" value="get details"></div><?php } ?>-->
					 
					<?php if(!empty($result[0]['regnumber'])){?><input type="hidden" class="form-control" name="member_no" value="<?php echo @$result[0]['regnumber'] ;?>" autocomplete="false" > <?php } ?>
					 
					 <div class="col-sm-4"><input type="submit" class="btn btn-info" name="btn_Submit" id="btn_Submit" value="get details"></div>
					 
					 </div>
					 
					 <!-- Added by chaitali on 2021-10-15 -->
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
								  <a href="javascript:void(0);" id="new_captcha" class="forget">Change Image</a>
								 <span class="error"><?php //echo form_error('code');?></span>
							</div>
							  
					</div> 
			<?php  }?>
					 <div class="form-group"><div class="col-sm-12"><span style="color:#F00; font-size:13px; font-weight:bold">Please insert your membership/registration no and click on get details. Below details will get filled automatically.</span></div></div>
					</form>
			</div>
				
		  <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Cpd/getdetails" onsubmit="return validateForm(this)" data-parsley-validate="parsley" autocomplete="off">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
          
           <?php  if(empty($result)){   ?>
            <div class="box-body">
                <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none">
                    <span>display ajax response errors here</span>
                </div>
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <!--<select name="sel_namesub" id="sel_namesub" class="form-control" readonly="readonly" required>
                    <option value="">Select</option>
                    <option value="Mr." <?php echo  set_select('sel_namesub', 'Mr.'); ?>>Mr.</option>
                    <option value="Mrs." <?php echo  set_select('sel_namesub', 'Mrs.'); ?>>Mrs.</option>
                    <option value="Ms." <?php echo  set_select('sel_namesub', 'Ms.'); ?>>Ms.</option>
                    <option value="Dr." <?php echo  set_select('sel_namesub', 'Dr.'); ?>>Dr.</option>
                    <option value="Prof." <?php echo  set_select('sel_namesub', 'Prof.'); ?>>Prof.</option>
                    </select>-->
                     
	                <input type="text" class="form-control" id="sel_namesub_1" name="sel_namesub_1" value="<?php echo set_value('sel_namesub');?>" readonly>			
                    <span class="error" id="tiitle_error"><?php //echo form_error('firstname');?></span>					
                    </div>
                    
                     <div class="col-sm-4">
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required 
                        value="<?php echo set_value('firstname');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" readonly="readonly">
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo set_value('middlename');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" readonly="readonly">
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
                </div>
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name"  value="<?php echo set_value('lastname');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" readonly="readonly" >
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div>
                </div>
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
					   <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo set_value('email');?>"  data-parsley-maxlength="45" required  data-parsley-emailcheck data-parsley-trigger-after-failure="focusout" readonly="readonly" >
                      <span class="error"><?php //echo form_error('email');?></span> 
                    </div>
                </div>  
				
				<div class="form-group"> 
                <label for="roleid" class="col-sm-3 control-label">Mobile<span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                      <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Contact No" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('mobile');?>"  required  data-parsley-cpdmobilecheck  data-parsley-trigger-after-failure="focusout" readonly="readonly" >
                      <span class="error"><?php //echo form_error('contact_no');?></span>
                    </div>
                </div> 
			
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1<span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo set_value('addressline1');?>"  data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" readonly="readonly" >
                    </div> 
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo set_value('addressline2');?>"  data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" readonly="readonly">
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div> 
                </div> 
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo set_value('addressline3');?>"  data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" readonly="readonly">
                      <span class="error"><?php //echo form_error('addressline3');?></span>
                    </div> 
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo set_value('addressline4');?>" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" readonly="readonly">
                      <span class="error"><?php //echo form_error('addressline4');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District <span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                        <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo set_value('district');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" readonly="readonly">
                      <span class="error"><?php //echo form_error('district');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City <span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                        <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo set_value('city');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" readonly>
                      <span class="error"><?php //echo form_error('city');?></span>
                    </div>
                   
                </div>
				
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State <span style="color:#F00">*</span></label>
                	<div class="col-sm-3">
                    <select class="form-control" id="state" name="state" required onchange="javascript:checksate(this.value)" readonly>
                        <option value="">Select</option>
                    </select>
                    
                    <input hidden="statepincode" id="statepincode" value="">
      
                    </div> 
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                   
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pincode');?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout" readonly > (Max 6 digits)
                         <span class="error"><?php //echo form_error('pincode');?></span>
                    </div>
                    
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification <span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                      <input type="radio" class="minimal" id="U"  attr="optedu"  name="optedu" value="U" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'U'); ?>required readonly>
                        Under Graduate
                        <input type="radio" class="minimal" id="G" attr="optedu"  name="optedu"  value="G" onclick="changedu(this.value)" <?php echo set_radio('optedu', 'G'); ?> readonly>
                        Graduate
                        <input type="radio" class="minimal" id="P"  attr="optedu" name="optedu"  value="P"   onclick="changedu(this.value)" <?php echo set_radio('optedu', 'P'); ?> readonly>
                        Post Graduate
                      <span class="error"><?php //echo form_error('optedu');?></span>
                    </div>
                </div>
                 
			<div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Please specify <span style="color:#F00">*</span></label>
                
            <div class="col-sm-5" 
			  <?php if(set_value('eduqual1') || set_value('eduqual2') || set_value('eduqual3')){echo 'style="display:none"';}else
			  {echo 'style="display:block"';}?>  id="edu">
           	  <select id="eduqual" name="eduqual" class="form-control" <?php if(!set_value('eduqual1') && !set_value('eduqual2') && !set_value('eduqual3')){echo 'required';}?> readonly>
				<option value="" selected="selected">--Select--</option>
				</select>
            </div>
                	<div class="col-sm-5"  <?php if(set_value('optedu')=='U'){echo 'style="display:block;"';}else if(!set_value('optedu')){echo 'style="display:none;"';}else{echo 'style="display:none;"';}?> id="UG">
                      <select class="form-control" id="eduqual1" name="eduqual1" <?php if(set_value('optedu')=='U'){echo 'required';}?>>
                        <option value="">--Select--</option>
                       
                      </select>
                      <span class="error"><?php //echo form_error('eduqual1');?></span>
                    </div>
                    
                    <div class="col-sm-5"  <?php if(set_value('optedu')=='G'){echo 'style="display:block"';}else{echo 'style="display:none"';}?> id="GR">
                      <select class="form-control" id="eduqual2" name="eduqual2" <?php if(set_value('optedu')=='G'){echo 'required';}?> >
                        <option value="">--Select--</option>

                      </select>
                      <span class="error"><?php //echo form_error('eduqual2');?></span>
                    </div>
                    
                    
                    <div class="col-sm-5"  <?php if(set_value('optedu')=='P'){echo 'style="display:block"';}else{echo 'style="display:none"';}?>id="PG">
                      <select class="form-control" id="eduqual3" name="eduqual3" <?php if(set_value('optedu')=='P'){echo 'required';}?>>
                        <option value="">--Select--</option>
                       
                      </select>
                      <span class="error"><?php //echo form_error('eduqual3');?></span>
                    </div>
                </div>
                <input type="hidden" id="education_type" value="" autocomplete="false">
				
				<div class="form-group">
					 <label for="" class="col-sm-3 control-label">Designation <span style="color:#F00">*</span></label>
					  <div class="col-sm-6"  style="display:block" >
					   <select id="designation" name="designation" class="form-control" required readonly>
						 <option value="">--Select--</option>
						 
						</select>
						<span class="error"><?php //echo form_error('designation');?></span>
					</div>
				</div>
				<div class="form-group">
					 <label for="roleid" class="col-sm-3 control-label">Bank Name<span style="color:#F00">*</span></label>
					  <div class="col-sm-6"  style="display:block" >
					  <select id="bank_name" name="bank_name" class="form-control" required readonly>
						 <option value="">--Select--</option>
						 
						</select>
						<span class="error"><?php //echo form_error('institutionworking');?></span>
					</div>
				</div>
                <div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Branch/Office address<span style="color:#F00">*</span></label>
					<div class="col-sm-6">
					  <input type="text" class="form-control" id="office" name="office" placeholder="Branch/Office" required value="<?php echo set_value('office');?>"  data-parsley-maxlength="20" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" readonly>
					</div>
				</div>
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Number of years experience<span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control " id="experience" name="experience" placeholder="Number of years experience" data-parsley-type="decimal"  value="<?php echo set_value('experience');?>" readonly>
                      <span class="error"><?php //echo form_error('experience');?></span>
                    </div>
					(Only Numeric values)
                </div>
            </div>
			<?php } else { ?>
			<div class="box-body">
                <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none">
                    <span>display ajax response errors here</span>
                </div>
				  <input type="hidden" class="form-control" id="member_no" name="member_no" value="<?php echo $result[0]['regnumber'] ;?>" autocomplete="false">
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <!--<select name="sel_namesub_1" id="sel_namesub_1" class="form-control" disabled>
                    <option value="" >Select</option>
					<option value="MR." <?php if($result[0]['namesub']=='MR.'){echo "selected='selected'";} ?>>MR.</option>
					<option value="MRS." <?php if($result[0]['namesub']=='MRS.'){echo "selected='selected'";} ?>>MRS.</option>
                    <option value="MS." <?php if($result[0]['namesub']=='MS.'){echo "selected='selected'";} ?>>MS.</option>
                    <option value="DR." <?php if($result[0]['namesub']=='DR.'){echo "selected='selected'";} ?>>DR.</option>
                    <option value="PROF." <?php if($result[0]['namesub']=='PROF.'){echo "selected='selected'";} ?>>PROF.</option>
                    
                    </select>-->
					 <input type="text" class="form-control" id="sel_namesub_1" name="sel_namesub_1" value="<?php echo $result[0]['namesub'] ;?>" readonly required>
					<input type="hidden" name="sel_namesub" id="sel_namesub" value="<?php echo $result[0]['namesub'];?>" autocomplete="false"/> 
                     <span class="error" id="tiitle_error"><?php //echo form_error('firstname');?></span> 
                    </div>
                    
                    <div class="col-sm-4">
                        <input type="text" class="form-control uppercase" id="firstname" name="firstname" placeholder="First Name"  value="<?php echo $result[0]['firstname'] ;?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" readonly required>
                        <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control uppercase" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $result[0]['middlename'] ;?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" readonly  >
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control uppercase" id="lastname" name="lastname" placeholder="Last Name"  value="<?php echo $result[0]['lastname'] ;?>"  data-parsley-maxlength="30" maxlength="30" readonly>
                      <span class="error"><?php//data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" //echo form_error('lastname');?></span>
                    </div> 
                </div>
                
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo $result[0]['email'] ;?>"  data-parsley-maxlength="45" required   data-parsley-trigger-after-failure="focusout" readonly>
                     <!-- data-parsley-cpdemailcheck (Enter valid and correct email ID to receive communication)-->
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>  
				
				<div class="form-group"> 
                <label for="roleid" class="col-sm-3 control-label">Mobile<span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                      <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile Number" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo @$result[0]['mobile'] ;?>"  required  data-parsley-trigger-after-failure="focusout" readonly>
                      <span class="error"><?php // data-parsley-cpdmobilecheck   echo form_error('contact_no');?></span>
                    </div>
                </div> 
			
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1<span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo $result[0]['address1'];?>"  data-parsley-maxlength="80" maxlength="80"  readonly>
                      <span class="error"><?php //echo form_error('addressline1');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php if(!empty($result[0]['address2'])){echo $result[0]['address2'];} ?>"  data-parsley-maxlength="40" maxlength="40" readonly >
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div>    
                </div> 
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php if(!empty($result[0]['address3'])){echo $result[0]['address3'];} ?>"  data-parsley-maxlength="40" maxlength="40"  readonly>
                      <span class="error"><?php //echo form_error('addressline3');?></span>
                    </div> 
                      
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php if(!empty($result[0]['address4'])){echo $result[0]['address4'];} ?>" data-parsley-maxlength="40" maxlength="40"  readonly>
                      <span class="error"><?php //echo form_error('addressline4');?></span>
                    </div>  
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District <span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php if(!empty($result[0]['district'])){echo $result[0]['district'];} ?>"  data-parsley-maxlength="40" maxlength="40" readonly>
                      <span class="error"><?php //echo form_error('district');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City <span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $result[0]['city'];?>"  data-parsley-maxlength="40" maxlength="40" readonly>
                      <span class="error"><?php //echo form_error('city');?></span>
                    </div>
                </div>
                
                
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State <span style="color:#F00">*</span></label>
                	<div class="col-sm-4">
                    <select class="form-control" id="state_1" name="state_1" required onchange="javascript:checksate(this.value)" disabled>
                        <option value="">Select</option>
                        <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
						<option value="<?php echo $row1['state_code'];?>" <?php if(!empty($result[0]['state'])) {if($result[0]['state']==$row1['state_code']){echo "selected='selected'";}} ?>><?php echo $row1['state_name'];?></option>
                        <?php } } ?>
                    </select>
                    <input type="hidden" name="state" id="state" value="<?php echo $result[0]['state'];?>" autocomplete="false"/>
                    <input hidden="statepincode" id="statepincode" value="" autocomplete="false">
      
                    </div> 
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                   
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" value="<?php echo @$result[0]['pincode'];?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-cpdcheckpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout" required readonly> (Max 6 digits)
                         <span class="error"><?php //echo form_error('pincode');?></span>
                    </div>
                    
                  </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification <span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
						<input type="radio" class="minimal optedu_chk" id="U"   name="optedu"   value="U" onclick="changedu(this.value)" <?php if(@$result[0]['qualification']=='U'){echo "checked='checked'";}?>  disabled required> Under Graduate
						<input type="radio" class="minimal optedu_chk" id="G"   name="optedu"  value="G" onclick="changedu(this.value)" <?php if(@$result[0]['qualification']=='G'){echo "checked='checked'";}?> disabled>
                        Graduate
                        <input type="radio" class="minimal optedu_chk" id="P"   name="optedu"  value="P"   onclick="changedu(this.value)" <?php if(@$result[0]['qualification']=='P'){echo "checked='checked'";}?> disabled>
                        Post Graduate
						<input type="hidden" name="optedu" id="optedu" value="<?php echo $result[0]['qualification'];?>" autocomplete="false" />
                      <span class="error"><?php //echo form_error('optedu');?></span>
                    </div>
                </div>
                 
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Please specify<span style="color:#F00">*</span></label>
                
                <div class="col-sm-6" 
				  <?php if(set_value('eduqual1') || set_value('eduqual2') || set_value('eduqual3')){echo 'style="display:none"';}else
				  {echo 'style="display:block"';}?>  id="edu">
				  <select id="eduqual" name="eduqual" class="form-control" <?php if(!set_value('eduqual1') && !set_value('eduqual2') && !set_value('eduqual3')){echo 'required';}?> disabled>
					<option value="" selected="selected">--Select--</option>
				  </select>
				</div>
                    
                	<div class="col-sm-6"  <?php if(set_value('optedu')=='U'){echo 'style="display:block;"';}else if(!set_value('optedu')){echo 'style="display:none;"';}else{echo 'style="display:none;"';}?> id="UG">
                      <select class="form-control" id="eduqual1_1" name="eduqual1" <?php if(set_value('optedu')=='U'){echo 'required';}?> disabled>
                        <option value="">--Select--</option>
                        <?php if(!empty($undergraduate)){
                                foreach($undergraduate as $row1){ 	?>
                        <!--<option value="<?php echo $row1['qid'];?>" <?php echo set_select('eduqual1', $row1['qid']); ?>><?php echo $row1['name'];?></option>-->
						<option value="<?php echo $row1['qid'];?>" <?php if(@$result[0]['specify_qualification']==$row1['qid']){echo "selected='selected'";} ?>><?php echo $row1['name'];?></option>
                        <?php } } ?>
                      </select>
					   <input type="hidden" name="eduqual1" id="eduqual1" value="<?php echo $result[0]['specify_qualification'];?>" autocomplete="false" />
                      <span class="error"><?php //echo form_error('eduqual1');?></span>
                    </div>
                    
                    <div class="col-sm-6"  <?php if(set_value('optedu')=='G'){echo 'style="display:block"';}else{echo 'style="display:none"';}?> id="GR">
                      <select class="form-control" id="eduqual2_1" name="eduqual2" <?php if(set_value('optedu')=='G'){echo 'required';}?>disabled >
                        <option value="">--Select--</option>
                        <?php if(!empty($graduate)){
                                foreach($graduate as $row2){ 	?>
                        <!--<option value="<?php echo $row2['qid'];?>" <?php echo  set_select('eduqual2', $row2['qid']); ?>><?php echo $row2['name'];?></option>-->
						<option value="<?php echo $row2['qid'];?>" <?php if(@$result[0]['specify_qualification']==$row2['qid']){echo "selected='selected'";} ?>><?php echo $row2['name'];?></option>
                        <?php } } ?>
                      </select>
					  <input type="hidden" name="eduqual2" id="eduqual2" value="<?php echo $result[0]['specify_qualification'];?>" autocomplete="false" />
                      <span class="error"><?php //echo form_error('eduqual2');?></span>
                    </div>
                    
                    
                    <div class="col-sm-6"  <?php if(set_value('optedu')=='P'){echo 'style="display:block"';}else{echo 'style="display:none"';}?>id="PG">
                      <select class="form-control" id="eduqual3_1" name="eduqual3" <?php if(set_value('optedu')=='P'){echo 'required';}?> disabled>
                        <option value="">--Select--</option>
                        <?php if(!empty($postgraduate)){
                                foreach($postgraduate as $row3){ 	?>
                        <!--<option value="<?php echo $row3['qid'];?>" <?php echo  set_select('eduqual3', $row3['qid']); ?>><?php echo $row3['name'];?></option>-->
						<option value="<?php echo $row3['qid'];?>" <?php if(@$result[0]['specify_qualification']==$row3['qid']){echo "selected='selected'";} ?>><?php echo $row3['name'];?></option>
                        <?php } } ?>
                      </select>
					   <input type="hidden" name="eduqual3" id="eduqual3" value="<?php echo $result[0]['specify_qualification'];?>" autocomplete="false" />
                      <span class="error"><?php //echo form_error('eduqual3');?></span>
                    </div>
                </div>
                <input type="hidden" id="education_type" value="" autocomplete="false">
				
				<div class="form-group">
					 <label for="" class="col-sm-3 control-label">Designation <span style="color:#F00">*</span></label>
					  <div class="col-sm-6"  style="display:block" >
					  <select id="designation_1" name="designation_1" class="form-control"  required disabled>
						 <option value="">--Select--</option>
						 <?php if(!empty($designations)){
										foreach($designations as $designation_row){ ?>
								 <option value="<?php echo $designation_row['dcode'];?>" <?php if($result[0]['designation']==$designation_row['dcode']){echo "selected='selected'";} ?>><?php echo $designation_row['dname'];?></option>
								<?php } } ?>
						</select>
						 <input type="hidden" name="designation" id="designation" value="<?php echo $result[0]['designation'];?>" autocomplete="false" />
						<span class="error"><?php //echo form_error('designation');?></span>
					</div>
				</div>
				<div class="form-group">
					 <label for="roleid" class="col-sm-3 control-label">Bank Name<span style="color:#F00">*</span></label>
					  <div class="col-sm-6"  style="display:block" >
					  <select id="associatedinstitute_1" name="associatedinstitute_1" class="form-control" required disabled>
						 <option value="">--Select--</option>
						 <?php if(count($institution_master)){
                                foreach($institution_master as $institution_row){ 	?>
                        <option value="<?php echo $institution_row['institude_id'];?>" <?php if($result[0]['associatedinstitute']==$institution_row['institude_id']){echo "selected='selected'";} ?>><?php echo $institution_row['name'];?></option>
                        <?php } } ?>
						
						</select>
						<input type="hidden" name="associatedinstitute" id="associatedinstitute" value="<?php echo $result[0]['associatedinstitute'];?>" autocomplete="false" />
						<span class="error"><?php //echo form_error('institutionworking');?></span>
						
					    </div>
				</div>
				<?php

				   //get branch if office is blank
	        $office = '';
			 if($result[0]['office'] !='')
			 {
			    $office = $result[0]['office'];
			 }
			 elseif($result[0]['branch'] !='')
			 {
			    $office = $result[0]['branch'];
			 }
			 else
			 {
			    $office = $result[0]['office'];
			 }
	        
			
			//$editedon = date('Y-m-d', strtotime($result[0]['createdon']));
		   /*	$editedon = date('Y-m-d', strtotime($result[0]['editedon']));
			
			if($editedon < "2016-12-29")
			{
				$office = $result[0]['branch'];
			}
			else if($editedon >= "2016-12-29")
			{
				if(is_numeric($result[0]['office']))
				{
					if($result[0]['branch']!='')
						$office = $result[0]['branch'];
					else
						$office = $result[0]['office'];
				}
				else
				{
					if($result[0]['branch']!='')
						$office = $result[0]['branch'];
					else
						$office = $result[0]['office'];
				}
			}
			*/
				?>
                <div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Branch/Office address<span style="color:#F00">*</span></label>
					<div class="col-sm-6">
					  <input type="text" class="form-control" id="office" name="office" placeholder="Branch/Office"  value="<?php echo $office;?>"  readonly required>
					</div>
				</div>
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Number of years experience<span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                      <input type="text" class="form-control " id="experience" name="experience" placeholder="Number of years experience"  value="<?php echo set_value('experience');?>" data-parsley-pattern="([0-9]{1,2})(\.[0-9]{1,2})?"  required>
                      <span class="error"><?php //data-parsley-type="number" //data-parsley-pattern="[0-9]+?\.[0-9]{0,1}" //data-parsley-pattern="[0-9]+\.[0,1}$" //data-parsley-pattern="([0-9]{2})(\.[0-9]{1})?" form_error('experience');?></span>
                    </div>
					(Only Numeric values)
                </div>
				<div class="form-group">
					<label for="roleid" class="col-sm-3 control-label">Fees<span style="color:#F00">*</span></label>
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="fees" name="fees" placeholder="fees" required value="<?php if(!empty($result[0]['state'])){if($result[0]['state'] =='MAH'){echo $this->config->item('CPD_cs_total');}else{echo $this->config->item('CPD_igst_tot');}}?>" readonly="readonly">
						  <span class="error"><?php //echo form_error('city');?></span>
						</div>
						 
			    </div>
            </div>
			<?php } ?>
			
	    </div>
                
              
            <div class="box box-info">
            <!--<div class="box-header with-border">
              <h3 class="box-title">  <input name="declaration1" value="1" type="checkbox" required="required" 
			  <?php if(set_value('declaration1'))
			  {
				  echo set_radio('declaration1', '1');
				 }?>>&nbsp; I Accept</h3>
            </div>-->
           <?php if($result[0]['regnumber']){ ?>
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
			<?php } ?>
			<div class="box-footer">
					<div class="col-sm-6 col-sm-offset-3">
					<!-- <a href="javascript:void(0);" class="btn btn-info"onclick="javascript:return checkform();" id="preview">Preview and Proceed for Payment</a>-->
					<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="submit">
					 <a href="<?php echo base_url();?>Cpd" class="btn btn-default">Reset</a>
					</div>
			</div>
           </div>
        </div>
      </div>
     
     
    </section>
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
	$('#new_captcha').click(function(event){
			event.preventDefault();
		$.ajax({
			type: 'POST',
			url: site_url+'Cpd/generatecaptchaajax/',
			success: function(res)
			{	
				if(res!='')
				{$('#captcha_img').html(res);
				}
			}
		});
		}); 
		
	var edu = '<?php echo @$result[0]['qualification']; ?>';
	var qualification = '<?php echo @$result[0]['specify_qualification']; ?>';
	if(edu == 'U')
	{
		$('#eduqual1').val(qualification);
	}
	else if(edu == 'G')
	{
		$('#eduqual2').val(qualification);
	}
	else if(edu == 'P')
	{
		$('#eduqual3').val(qualification);
	}
	
	changedu(edu);	
});

	//validate required field and redirect them to their edit profiile 
	function validateForm(form)
    {
	    //var 
		var member_no = document.getElementById('member_no').value;
		//alert(member_no);
		if(member_no=="")
		{
			alert('First get details of member and then submit');
			document.getElementById("member_no").focus();
			refresh_cpd_captcha();
			return false;
		}
	    else
		{
			    var sel_namesub=form.sel_namesub.value;
			    var firstname=form.firstname.value;
				var email=form.email.value;
				var mobile=form.mobile.value;
				var addressline1=form.addressline1.value;
				var district=form.district.value;
				var city=form.city.value;
				var state=form.state.value;
				var pincode=form.pincode.value;
				var office=form.office.value;
				var associatedinstitute=form.associatedinstitute.value;
				var designation=form.designation.value;
				//var arr = [ 'sel_namesub',sel_namesub,'firstname',firstname,'email',email,'mobile',mobile,'addressline1',addressline1,'district',district,'city',city,'state',state,'pincode',pincode,'office',office,'associatedinstitute',associatedinstitute,'designation',designation] ;
				//alert(arr);
				//alert();
				if( sel_namesub=="" || firstname=="" ||email=="" || mobile=="" || addressline1=="" || district=="" || city=="" || state == "" || pincode ==""|| office ==""|| associatedinstitute ==""|| designation =="")
				{
					$('#confirm').modal('show');
				}
		}
    }
	//Qualification Selection as per u,g and p
	function changedu(dval)
	{
	
	$('#education_type').val(dval)
	var UGid = document.getElementById('UG');
	var GRid = document.getElementById('GR');
	var PGid = document.getElementById('PG');
	var EDUid = document.getElementById('edu');

	if(dval == 'U')
	{
		$('#eduqual1').attr('required','required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
	//	$('#noOptEdu').hide();
		
		if(UGid != null) {
		//	alert('UG');
			document.getElementById('UG').style.display = "block";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
	else if(dval == 'G')
	{
		$('#eduqual1').removeAttr('required');;
		$('#eduqual2').attr('required','required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
		//$('#noOptEdu').hide();
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "block";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	
	}
	else if(dval == 'P')
	{
		$('#eduqual1').removeAttr('required');;
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').attr('required','required');
		$('#eduqual').removeAttr('required');
		//$('#noOptEdu').hide();
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "block";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
	else
	{
		//$('#noOptEdu').show();	
	}
}
	
</script>