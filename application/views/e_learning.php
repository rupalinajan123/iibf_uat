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
	background-color: #fff;
}
body.layout-top-nav .main-header h1 {
	color: #0699dd;
	margin-bottom: 0;
	margin-top: 30px;
}
.container {
	position: relative;
}
.box-header.with-border {
	background-color: #7fd1ea;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
	margin-bottom: 10px;
}
.header_blue {
	background-color: #2ea0e2 !important;
	color: #fff !important;
	margin-bottom: 0 !important;
}
.box {
	border: none;
	box-shadow: none;
	border-radius: 0;
	margin-bottom: 0;
}
.nobg {
	background: none !important;
	border: none !important;
}
.box-title-hd {
	color: #3c8dbc;
	font-size: 16px;
	margin: 0;
}
.blue_bg {
	background-color: #e7f3ff;
}
.m_t_15 {
	margin-top: 15px;
}
.main-footer {
	padding-left: 160px;
	padding-right: 160px;
}
.content-header > h1 {
	font-size: 22px;
	font-weight: 600;
}
h4 {
	margin-top: 5px;
	margin-bottom: 10px !important;
	font-size: 14px;
	line-height: 18px;
	padding: 0 5px;
	font-weight: 600;
	text-align: justify;
}
.form-horizontal .control-label {
	padding-top: 4px;
}
.pad_top_2 {
	padding-top: 2px !important;
}
.pad_top_0 {
	padding-top: 0px !important;
}
div.form-group:nth-child(odd) {
	background-color: #dcf1fc;
	padding: 5px 0;
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
	z-index: 1;
	box-shadow: 0 1px 3px #000;
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
	margin-bottom: 10px;
}
.form-horizontal .form-group {
	margin-left: 0;
	margin-right: 0;
}
.form-control {
	border-color: #888;
}
.form-horizontal .control-label {
	font-weight: normal;
}
a.forget {
	color: #9d0000;
}
a.forget:hover {
	color: #9d0000;
	text-decoration: underline;
}
ol li {
	line-height: 18px;
}
.example {
	text-align: left !important;
	padding: 0 10px;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>


<div class="container">
  <section class="content-header box-header with-border" style="height: 48px; background-color: #1287C0; ">
    <h1 class="register">E-learning Registration</h1>
  </section>
  <div> 
    <!-- Start Get Details -->
    <?php
  if(!empty($selectedRecord)) { 
	if(isset($selectedRecord['msg']) && $selectedRecord['msg'] != ''){ 
		echo '<div class="alert alert-danger alert-dismissible">'.$selectedRecord['msg'].'</div>'; 
	}
  }
?>
  </div>
  <section class="">
    <div class="row">
      <div class="col-md-12" style=""> 
        <!-- /.box-header --> 
        <!-- form start -->
        <?php if($this->session->flashdata('error')!=''){?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('error'); ?> </div>
        <?php } if($this->session->flashdata('success')!=''){ ?>
        <div class="alert alert-success alert-dismissible" id="success_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('success'); ?> </div>
        <?php } 
			 if(validation_errors()!=''){?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo validation_errors(); ?> </div>
        <?php }
			  if($var_errors!='')
			  {?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $var_errors; ?> </div>
        <?php }  ?>
      </div>
    </div>
  </section>
  
  <!-- Close Get Details -->
  
  <form class="form-horizontal" name="elrForm" id="elrForm"  method="post"   action="<?php echo base_url()?>ELearning/add_elearning_app">
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Enter Details</h3>
            </div>
            <div class="box-body">
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
              
              
              
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Select Exam<span style="color:#F00">*</span></label>
                
                 <?php //echo '<pre>'; print_r($exam);?>
                <div class="col-sm-3">
                <select name="exam_code" id="exam_code" class="form-control" required>
                    <option value="" >Select</option>
                   <?php foreach($exam as $rec){?>
                    <option value="<?php echo $rec['exam_code']?>" ><?php echo $rec['description']?></option>
                    <?php }?>
                 </select>
                </div>
                
                
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Select Subject<span style="color:#F00">*</span></label>
                <div class="col-sm-3">
                <select name="subject_code" id="subject_code" class="form-control" required>
                    <option value="" >Select</option>
                    <option value="1" >test</option>
                 </select>
                </div>
              </div>
              
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Enter name<span style="color:#F00">*</span></label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="user_name" id="user_name" value="" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" placeholder="Enter your full name" required>
                  <span class="error"></span>
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email ID<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="email" class="form-control" name="email_id" id="email_id" value="" placeholder="Enter email id" required data-parsley-maxlength="45" data-parsley-trigger-after-failure="" data-parsley-elremailcheck/>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile Number<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="tel" class="form-control" id="contact_no" name="contact_no" placeholder="Enter mobile number" value="" required data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  maxlength="10" size="10" data-parsley-elrmobilecheck>
                  <span class="error">
                  </span> </div>
                </div>
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Member Number</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="member_number" name="member_number" placeholder="Enter member number" value="" data-parsley-maxlength="10"  maxlength="10" size="10" >
                  <span class="error">
                  </span> </div>
                </div> 
            </div>
            <!--</div>--> 
            <!-- Basic Details box closed-->
          </div>
          <div class="box box-info">
            
            <div class="form-group m_t_15">
              <label for="roleid" class="col-sm-3 control-label">Security Code <span style="color:#F00">*</span></label>
              <div class="col-sm-2">
                <input type="text" name="code" id="code" required class="form-control " >
                <span class="error" id="captchaid" style="color:#B94A48;"></span> </div>
              <div class="col-sm-3">
                <div id="captcha_img"><?php echo $image;?></div>
                <span class="error">
                <?php //echo form_error('code');?>
                </span> </div>
              <div class="col-sm-2"> <a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a> <span class="error">
                <?php //echo form_error('code');?>
                </span> </div>
            </div>
            <div class="box-footer">
                <!--<input type="submit" class="btn btn-default"  name="submit" id="submit" value="Submit" />-->
                <div class="col-sm-6 col-sm-offset-3"> <a href="javascript:void(0);" class="btn btn-info"onclick="javascript:return elrcheckform();" id="preview">Submit</a> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
  </form>
</div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<script src="<?php echo base_url();?>js/validation_bankquest.js?<?php echo time(); ?>"></script> 
 
<script>
 function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}

$(function(){
	
	function readCookie(name) {
		var nameEQ = encodeURIComponent(name) + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) === ' ') c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
		}
		return null;
	}

	if(readCookie('member_register_form'))
	{
		$('#error_id').html(''); 
		$('#error_id').removeClass("alert alert-danger alert-dismissible");
		createCookie('member_register_form', "", -1);	
	}
	

    $('#new_captcha').click(function(event){
        event.preventDefault();
		$.ajax({
			type: 'POST',
			url: site_url+'bankquest/generatecaptchaajax/',
			success: function(res)
			{	
				if(res!='')
				{$('#captcha_img').html(res);
				}
			}
		});
	});

	 $(document).keydown(function(event) {
        if (event.ctrlKey==true && (event.which == '67' || event.which == '86')) {
            if(event.which == '67')
			{
				alert('Key combination CTRL + C has been disabled.');
			}
			if(event.which == '86')
			{
				alert('Key combination CTRL + V has been disabled.');
			}
			event.preventDefault();
         }
    });
	
	$("body").on("contextmenu",function(e){
        return false;
    });
	
    $(this).scrollTop(0);

});
</script> 
