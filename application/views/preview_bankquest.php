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
    <h1 class="register">Bankquest Subscription Preview</h1>
  </section>
  <div> 
</div>
<!-- Close Get Details -->
<form class="form-horizontal" name="bankquestForm" id="bankquestForm"  method="post"   action="<?php echo base_url()?>bankquest/addrecord">
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Entered Details</h3>
            </div>
            <div class="box-body">
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
              
              <?php /*?><div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Category</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="category" id="category" value="<?php echo $this->session->userdata['bankquest_info']['category'];?>" readonly="readonly" disabled="disabled"/>
                </div>
              </div><?php */?>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $this->session->userdata['bankquest_info']['namesub'] ." ".$this->session->userdata['bankquest_info']['fname'] ;?>" readonly="readonly" disabled="disabled">
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="mname" id="mname" value="<?php echo $this->session->userdata['bankquest_info']['mname'] ;?>" readonly="readonly" disabled="disabled">
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="lname" id="lname" value="<?php echo $this->session->userdata['bankquest_info']['lname'] ;?>" readonly="readonly" disabled="disabled">
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Gender<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                 <?php 
				 	if($this->session->userdata['bankquest_info']['gender'] == 'M'){
						echo "Male";
					}else{
						echo "Female";
					}
				 ?>
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email ID<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="email" class="form-control" name="email_id" id="email_id" value="<?php echo $this->session->userdata['bankquest_info']['email_id'];?>" readonly="readonly" disabled="disabled"/>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile Number<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="tel" class="form-control" id="contact_no" name="contact_no" value="<?php echo $this->session->userdata['bankquest_info']['contact_no'];?>" readonly="readonly" disabled="disabled" >
                  <span class="error">
                  </span> </div>
                </div>
                
              <?php /*?><div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Subscription No</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="subscription_no" name="subscription_no"  value="<?php echo $this->session->userdata['bankquest_info']['contact_no'];?>"  readonly="readonly" disabled="disabled">
                  <span class="error">
                  </span> </div>
               </div><?php */?>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address 1<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="address_1" name="address_1"  value="<?php echo $this->session->userdata['bankquest_info']['address_1'];?>" readonly="readonly" disabled="disabled" >
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address 2</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="address_2" name="address_2"  value="<?php echo $this->session->userdata['bankquest_info']['address_2'];?>"  readonly="readonly" disabled="disabled">
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address 3</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="address_3" name="address_3"  value="<?php echo $this->session->userdata['bankquest_info']['address_3'];?>"  readonly="readonly" disabled="disabled">
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address 4</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="address_4" name="address_4"  value="<?php echo $this->session->userdata['bankquest_info']['address_4'];?>" readonly="readonly" disabled="disabled" >
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="district" name="district"  value="<?php echo $this->session->userdata['bankquest_info']['district'];?>"  readonly="readonly" disabled="disabled">
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="city" name="city"  value="<?php echo $this->session->userdata['bankquest_info']['city'];?>"  readonly="readonly" disabled="disabled">
                  <span class="error">
                  </span> </div>
               </div>
               
               <?php
               $state = $this->master_model->getRecords('state_master',array('state_code'=>$this->session->userdata['bankquest_info']['state']),'state_name');
			   
			   ?>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="state" name="state"  value="<?php echo $state[0]['state_name'];?>"  readonly="readonly" disabled="disabled">
                </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Pincode/Zipcode<span style="color:#F00">*</span></label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="pincode" name="pincode"  value="<?php echo $this->session->userdata['bankquest_info']['pincode'];?>"  readonly="readonly" disabled="disabled">
                  <span class="error">
                  </span> </div>
               </div>
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Subscription Fees</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="subscription_fees" name="subscription_fees"  value="<?php echo $this->session->userdata['bankquest_info']['subscription_fees'];?>" readonly="readonly" disabled="disabled">
                  <span class="error">
                  </span> </div>
               </div>
               
               <?php
               	$range1 = date('d-M-Y',strtotime('first day of +1 month'));
				$range2 = date('d-M-Y', strtotime('+1 years', strtotime($range1)));
			   ?>
               
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Validity period Upto</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="from_to_date" name="from_to_date"  value="<?php echo $range1." To ".$range2 ;?>" readonly="readonly" disabled="disabled" >
                  <span class="error">
                  </span> </div>
               </div>
                
            </div>
            <!--</div>--> 
            <!-- Basic Details box closed-->
          </div>
          <div class="box box-info">
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-3"> 
              <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Proceed for Payment">
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
 
 
