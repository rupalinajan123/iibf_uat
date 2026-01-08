<!DOCTYPE html>
<html>
	<head>
    <?php $this->load->view('google_analytics_script_common'); ?>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>IIBF - FEDAI Non Member Data Collection</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="<?php echo  base_url()?>assets/admin/plugins/iCheck/square/blue.css">
		<script src="<?php echo base_url()?>assets/admin/plugins/jQuery/jQuery-2.2.0.min.js"></script>

		<style>
			ul {
			margin-top: -1px;
			padding: 5px 10px 5px 30px;
			border: 1px solid #1287c0;
			background-color: #dcf1fc;
			}
			ul li {
			padding: 2px 0;
			}
			.login-box-body a {
			line-height: 20px;
			}
			.short_logo {
			display: inline-block;
			float: left;
			margin: 0 0 0 20px;
			}
			.login-logo a {
			color: #619fda;
			font-weight: 600;
			text-align: center;
			font-size: 28px;
			line-height: 24px;
			display: inline-block;
			}
			.login-logo a small {
			font-size: 14px;
			color: #1d1d1d;
			}
			
			label {
			line-height: 18px;
			font-weight: normal;
			}
			form {
			padding: 20px 10px 30px;
			border: 1px solid #1287c0;
			background-color: #dcf1fc;
			}
			.form-group {
			margin-bottom: 10px;
			}
			a.forget {
			color: #9d0000;
			line-height: 24px;
			}
			a.forget:hover {
			color: #9d0000;
			text-decoration: underline;
			}
			.btn.btn-flat {
			min-height: 34px;
			background-color: #015171;
			}
			.red {
			color: #f00;
			}
			
			.login-box-body, .register-box-body 
			{
      background: rgba(255,255,255,1);
      padding: 0 0 20px;
      border-top: 0;
      color: #000;
      width: 90%;
      position: unset;
      left: 0;
      margin: 20px auto 10px;
      max-width: 600px;
			}
			
			label.error 
			{
      margin: 2px 0 0 0;
      display: block !important;
      line-height: 18px;
      font-size: 13px;
			}
			
			h4.login_heading 
			{
      text-align: center;
      margin: 0 0 20px 0;
      font-weight: 600;
      border-bottom: 1px solid #1287c0;
      padding-bottom: 10px;
      color: #1287c0;
      font-size: 18px;
			}
			
			.login-box 
			{
      width: 100%;
      max-width: 700px;
			}
			
			.login-logo, .register-logo
			{
      border-bottom: 1px solid #1287c0;
      padding-bottom: 7px;
			}
			.field-name {
      font-weight: 700;
			}

      .form_note { color: #4e9099; font-weight: 600; font-size: 12px; line-height: 15px !important; display: block; margin: 4px 0 0 0; }
    </style>
  </head>
	
	<body class="hold-transition login-page">
		<div class="login-box">
			<div class="login-logo">
				<div class="short_logo"><img src="<?php echo base_url();?>assets/images/iibf_logo_short.png"></div>
				<div><a>INDIAN INSTITUTE OF BANKING &amp; FINANCE<br><small>(An ISO 21001:2018 Certified)</small></a></div>
      </div>
			
			<div class="login-box-body">
				<?php 
					if($this->session->flashdata('error')!=''){?>								
					<div class="alert alert-danger alert-dismissible" id="error_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<?php echo $this->session->flashdata('error'); ?>
          </div>								
					<?php } 
					
					if($this->session->flashdata('success')!=''){ ?>
					<div class="alert alert-success alert-dismissible" id="success_id">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php } ?>   
				
				<form action="<?php echo site_url('NonmemberData/member_details'); ?>" method="post" name="nonmember_form" id="nonmember_form" enctype="multipart/form-data" autocomplete="off">
					<h4 class="login_heading"> Member Details</h4>
					
					<input type="hidden" id="mode" name="mode" value="<?php echo $mode; ?>">
					<div class="form-group has-feedback clearfix">
						<label class="col-md-4 field-name">Membership No. :</label>
						<div class="col-md-8">
							<label> <?php echo $form_data[0]['regnumber']; ?></label>							
            </div>
          </div>					
					
					<div class="form-group has-feedback clearfix">
						<label class="col-md-4 field-name">Name :</label>
						<div class="col-md-8">
							<label> 
                <?php 
                  if($form_data[0]['namesub'] != "") { echo $form_data[0]['namesub'].' '; }
                  if($form_data[0]['firstname'] != "") { echo $form_data[0]['firstname'].' '; }
                  if($form_data[0]['middlename'] != "") { echo $form_data[0]['middlename'].' '; }
                  if($form_data[0]['lastname'] != "") { echo $form_data[0]['lastname'].' '; } 
                ?>
              </label>	
            </div>
          </div>
          
					<div class="form-group has-feedback clearfix">
						<label class="col-md-4 field-name">Email	:</label>
						<div class="col-md-8">
							<label> <?php echo $form_data[0]['email']; ?></label>	
            </div>
          </div>
          
					<div class="form-group has-feedback clearfix">
						<label class="col-md-4 field-name">Mobile 	:</label>
						<div class="col-md-8">
							<label> <?php echo $form_data[0]['mobile']; ?></label>	
            </div>
          </div>
          
					<div class="form-group has-feedback clearfix">
						<label class="col-md-4 field-name">Employee Bank Name <span style="color:#f00">*</span></label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="emp_bank_name" id="emp_bank_name" value="<?php echo $non_member_data[0]['bank_name']; ?>" required placeholder="Employee Bank Name" maxlength="100">	
              <note class="form_note" id="emp_bank_name_err">Note: Please enter only 100 characters</note>
            </div>
          </div>
          
					<div class="form-group has-feedback clearfix">
            <label class="col-sm-4 field-name">Upload your Employee Id proof <span style="color:#f00">*</span></label>
            <div class="col-sm-8">
              <input type="file" name="empidproofphoto" id="empidproofphoto" class="form-control" accept=".pdf,.jpeg,.jpg" data-accept=".pdf,.jpeg,.jpg" onchange="validate_input('empidproofphoto');" <?php if($mode == 'Add' || ($mode == 'Update' && $non_member_data[0]['empidproofphoto'] == "")) { echo 'required'; } ?> />
                            
              <note class="form_note" id="empidproofphoto_err">Note: Please upload only .jpg, .jpeg, or .pdf files between 10KB and 25KB in size.</note>
              <?php if(isset($file_upload_error) && $file_upload_error != "") { echo $file_upload_error; } ?>
            </div>
            <br>
            <?php if($non_member_data[0]['empidproofphoto'] != '') { ?>
              <label class="col-sm-4 field-name"></label>
              <label class="col-sm-8 field-name"><a class="btn btn-primary btn-sm mt-1" href="<?php echo base_url('uploads/empidproof/'.$non_member_data[0]['empidproofphoto']); ?>" target="blank" style="padding: 2px 10px 3px 10px; margin: 10px 0 0 0;">View Employee ID Card</a></label>              
            <?php } ?>  
          </div>
          
          <div style="border-top:1px dashed #1287c0; margin:0 0 10px 0;"></div>
					<div class="row">
            <label class="col-sm-4 field-name"></label>
            <div class="col-sm-8">
							<input id="Submit" class="btn btn-primary btn-flat" name="btnLogin" value="Submit" type="submit">
							<a class="btn btn-primary btn-flat" href="<?php echo site_url('NonmemberData/logout'); ?>">Back</a>
            </div>
						<span style="color:#F00;"></span> 
          </div>
          
        </form>
      </div>
    </div>
    
    <script src="<?php echo  base_url()?>assets/admin/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url('assets/iibfbcbf/jquery_validation/jquery.validate.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/iibfbcbf/jquery_validation/jquery.validate_additional.js'); ?>"></script>
    
    <script> 
      function validate_input(input_id) { $("#"+input_id).valid(); } 
      $(document).ready(function() 
      {
        $.validator.addMethod("required", function(value, element) { if ($.trim(value).length == 0) { return false; } else { return true; } });

        $.validator.addMethod("allow_only_alphabets_and_floats_and_space", function(value, element) { return this.optional(element) || /^[a-zA-Z0-9.\s]+$/i.test(value); },'Only alphabets, numbers and spaces are allowed') //Allow only alphabet + numbers + floats + space

        jQuery.validator.addMethod("check_valid_file", function(value, element, param)//for size 0 files
        {
          var isOptional = this.optional(element), file;
          if(isOptional) { return isOptional; }
          
          if ($(element).attr("type") === "file") 
          {
            if (element.files && element.files.length) 
            {
              file = element.files[0];             
              if(file.size > 0)
              {
                return true;              
              }
            }
          }
          return false;
        }, "Please select valid file");

        //valid_file_format: '.png',
        //valid_file_format: '.png,.pdf',
        $.validator.addMethod("valid_file_format", function(value, element, param) 
        { 
          if(value != "" && param != "")
          {
            var validExts = param.split(',');
                                
            var fileExt = value.toLowerCase();
            fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
            if (validExts.indexOf(fileExt) < 0)  { return false; } else return true;
          }else return true;
        }, "Invalid file selection");

        jQuery.validator.addMethod("filesize_min", function(value, element, param) //use size in bytes //filesize_min: 1MB : 1000000
        {
          var isOptional = this.optional(element), file;
          if(isOptional) { return isOptional; }
          
          if ($(element).attr("type") === "file") 
          {
            if (element.files && element.files.length) 
            {
              file = element.files[0]; 
              //console.log(file.size +'>='+ param);
              return ( file.size && file.size >= param ); 
            }
          }
          return false;
        }, "File size must be greater than {0} bytes.");

        jQuery.validator.addMethod("filesize_max", function(value, element, param) //use size in bytes //filesize_max: 1MB : 1000000
        {
          var isOptional = this.optional(element), file;
          if(isOptional) { return isOptional; }
          
          if ($(element).attr("type") === "file") 
          {
            if (element.files && element.files.length) 
            {
              file = element.files[0]; 
              //console.log(file.size+" < "+param);
              return ( file.size && file.size <= param ); 
            }
          }
          return false;
        }, "File size must be less than {0} bytes.");
        
        $("#nonmember_form").validate(
        {
          onblur: function(element) { $(element).valid(); },
          rules: 
          { 
            emp_bank_name: { required: true, allow_only_alphabets_and_floats_and_space:true, maxlength:100 },
            empidproofphoto: {  <?php if($mode == 'Add' || ($mode == 'Update' && $non_member_data[0]['empidproofphoto'] == "")) { ?>required: true,<?php } ?> check_valid_file:true, valid_file_format:'.jpg,.jpeg,.pdf', filesize_min:'10000', filesize_max:'25000'  },
          },
          messages: 
          {
            emp_bank_name: { required: "Please enter the Employee Bank Name" },
            empidproofphoto: { required: "Please select the Employee Id proof", valid_file_format:"Please upload only .jpg, .jpeg, .pdf files", filesize_min:"Please upload file greater than 10KB", filesize_max:"Please upload file less than 25KB" },
          },
          submitHandler: function(form) 
          {
            $("#page_loader").show();
            form.submit();
          }
        });
      });
    </script>  
  </body>
</html>