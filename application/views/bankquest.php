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
    <h1 class="register">Bank Quest Subscription</h1>
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
  
  <form class="form-horizontal" name="bankquestForm" id="bankquestForm"  method="post"   action="<?php echo base_url()?>bankquest">
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
              
              <?php /*?><div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Category</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="category" id="category" value="IIBF bank quest subscription" readonly="readonly"/>
                </div>
              </div><?php */?>
              <?php /*?><div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Name<span style="color:#F00">*</span></label>
                <div class="col-sm-2">
                <select name="namesub" id="namesub" class="form-control" required>
                    <option value="" >Select</option>
                    <option value="Mr.">Mr.</option>
                    <option value="Mrs.">Mrs.</option>
                    <option value="Ms.">Ms.</option>
                    <option value="Dr.">Dr.</option>
                    <option value="Prof.">Prof.</option>
                 </select>
                </div>
                
              </div><?php */?>
              
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First name<span style="color:#F00">*</span></label>
                
                <div class="col-sm-2">
                <select name="namesub" id="namesub" class="form-control" required>
                    <option value="" >Select</option>
                    <option value="Mr.">Mr.</option>
                    <option value="Mrs.">Mrs.</option>
                    <option value="Ms.">Ms.</option>
                    <option value="Dr.">Dr.</option>
                    <option value="Prof.">Prof.</option>
                 </select>
                </div>
                <div class="col-sm-4">
                  <input type="text" class="form-control" name="fname" id="fname" value="" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" placeholder="Enter First name" required>
                  <span class="error"></span>
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle name</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="mname" id="mname" value="" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" placeholder="Enter Middle name" >
                  <span class="error"></span>
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last name</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="lname" id="lname" value="" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" placeholder="Enter Last name" >
                  <span class="error"></span>
                </div>
              </div>
              
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Select Gender<span style="color:#F00">*</span></label>
                <div class="col-sm-3">
                  <input type="radio" name="gender" id="mgender" value="M" class="cls_gender"  required/> Male &nbsp;&nbsp;
                  <input type="radio" name="gender" id="fgender" value="F" class="cls_gender"  required/> Female 
                  <span class="error"></span>
                </div>
              </div>
              
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email ID<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="email" class="form-control" name="email_id" id="email_id" value="" placeholder="Enter email id" required data-parsley-maxlength="45" data-parsley-trigger-after-failure="" data-parsley-bnqemailcheck/>(Enter valid and correct email ID to receive communication)
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile Number<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="tel" class="form-control" id="contact_no" name="contact_no" placeholder="Enter mobile number" value="" required data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  maxlength="10" size="10" data-parsley-bnqmobilecheck>
                  <span class="error">
                  </span> </div>
                </div>
                
              <?php /*?><div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Subscription No</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="subscription_no" name="subscription_no"  value="123"  readonly="readonly" >
                  <span class="error">
                  </span> </div>
               </div><?php */?>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address 1<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="address_1" name="address_1"  value="" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" placeholder="Enter address 1" required >
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address 2</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="address_2" name="address_2"  value="" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" placeholder="Enter address 2"  >
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address 3</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="address_3" name="address_3"  value="" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" placeholder="Enter address 3" >
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address 4</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="address_4" name="address_4"  value="" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" placeholder="Enter address 4" >
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="district" name="district"  value="" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" placeholder="Enter district" required>
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="city" name="city"  value="" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" placeholder="Enter city" required>
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <select class="form-control" id="state" name="state" required>
                    <option value="" >Select</option>
                      <?php 
					  		if(count($states) > 0){
								foreach($states as $row1){ 	
					  ?>
                      <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('state', $row1['state_code']); ?><?php if(isset($selectedRecord['state']) && $row1['state_code'] == $selectedRecord['state']){ ?>selected="selected"<?php } ?>><?php echo $row1['state_name'];?></option>
                      <?php } } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Pincode/Zipcode<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="pincode" name="pincode"  value=""  required data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-bnqcheckpin data-parsley-type="number" data-parsley-trigger-after-failure="focusout" placeholder="Enter pincode/zipcode">
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Subscription Fees</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="subscription_fees" name="subscription_fees"  value="160" readonly="readonly" >
                  <span class="error">
                  </span> </div>
               </div>
               
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Validity period</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="from_to_date" name="from_to_date"  value="<?php echo $subscription_range;?>" readonly="readonly" >
                  <span class="error">
                  </span> </div>
               </div>
              >> Your subscription is valid for 1 year and will start from the 1st day of next month.           
            </div>
            <!--</div>--> 
            <!-- Basic Details box closed-->
          </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">
                <input name="declaration1" value="1" type="checkbox" required="required" >
                &nbsp; I Accept</h3>
            </div>
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
              <div class="col-sm-6 col-sm-offset-3"> <a href="javascript:void(0);" class="btn btn-info"onclick="javascript:return bnqcheckform();" id="preview">Preview and Proceed for Payment</a> 
                <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset">Reset</button>
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
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p style="color:#F00"> <strong>VERY IMPORTANT</strong><br>
              I confirm that all the detail entered are correct as per my knowledge.</p>
          </div>
          <div class="modal-footer"> 
            <!--  <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="preview();">Confirm</button>-->
            <input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm" >
          </div>
        </div>
        <!-- /.modal-content --> 
      </div>
      <!-- /.modal-dialog --> 
    </div>
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
