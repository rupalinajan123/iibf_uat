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
</style>
<?php 
   header('Cache-Control: must-revalidate');
   header('Cache-Control: post-check=0, pre-check=0', FALSE);
   ?>
<div class="container">
   <section class="content-header box-header with-border" style="height: 48px; background-color: #1287C0; ">
      <h1 class="register">Training Program Application Form</h1>
   </section>

   <div class="row">
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
         if($var_errors!='')
         {?>
      <div class="alert alert-danger alert-dismissible">
         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
         <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
         <?php echo $var_errors; ?> 
      </div>
      <?php 
         } 
         ?>
      <!--<div class="col-md-1"></div>     -->
      <div class="col-md-12"> </div>
      <!--<div class="col-md-1"></div>-->
   </div>
   <marquee><p style="color: red; font-size:15px; padding: 0px; font-weight: bold; ">It is recommended to use the Mozilla Firefox browser while filling out this application form.</p></marquee>
   <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" action="<?php echo base_url()?>ContactClasses/check_vtmember" autocomplete="off">

      <div class="row">
         <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-info">
               <div class="box-header with-border">
                  <h3 class="box-title">Basic Details</h3>
               </div>
               <!-- /.box-header --> 
               <!-- form start -->
               <div class="box-body">
                  <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">First Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                     <div class="col-sm-2">
                        <select name="sel_namesub" id="sel_namesub" class="form-control"   required>
														<option value="">Select</option>
														<option value="Mr.">Mr.</option>
														<option value="Mrs.">Mrs.</option>
														<option value="Ms.">Ms.</option>
														<option value="Dr.">Dr.</option>
														<option value="Prof.">Prof.</option>
												</select>
                        <span class="error" id="tiitle_error">
                        <?php //echo form_error('firstname');?>
                        </span> 
                     </div>
                     <div class="col-sm-4">
                        <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" required value="" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                        <span class="error">
                        <?php //echo form_error('middlename');?>
                        </span> 
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Middle Name&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="mname" name="mname" placeholder="Middle Name"  value="" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                        <span class="error">
                        <?php //echo form_error('middlename');?>
                        </span> 
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Last Name&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name"  value="" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                        <span class="error">
                        <?php //echo form_error('lastname');?>
                        </span> 
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Email &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email" value=""  data-parsley-maxlength="45" required data-parsley-trigger-after-failure="focusout">
                        (Enter valid and correct email ID to receive communication) <span class="error">
                        <?php //echo form_error('email');?>
                        </span> 
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Mobile Number &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" pattern="[1-9]{1}[0-9]{9}" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  maxlength="10" value=""  required  data-parsley-trigger-after-failure="focusout">
                        <span class="error">
                        <?php //echo form_error('mobile');?>
                        </span> 
                     </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Bank/Institution working  &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="institution" name="institution" placeholder="Institution Name"  value=""  maxlength="50">
                        <span class="error">
                        <?php //echo form_error('lastname');?>
                        </span> 
                     </div>
                    </div>
                    <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Designation &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                     <div class="col-sm-6">
                        <select name="designation" id="designation" class="form-control" required>
                           <option value="" >Select Designation</option>
			                           <?php if(!empty($designation))
			                              {
			                               foreach($designation as $value)
			                               { 
			                               
			                               ?>
			                           <option value="<?php  echo $value['dcode'] ?>" >
			                              <?php  echo $value['dname'] ?>
			                           </option>
			                           <?php }
			                              }?>
                        </select>
                     </div>
                    </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Address line1&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value=""  data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                        <span class="error">
                        <?php //echo form_error('addressline1');?>
                        </span> 
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Address line2&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value=""  data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                        <span class="error">
                        <?php //echo form_error('addressline2');?>
                        </span> 
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Address line3&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value=""  data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                        <span class="error">
                        <?php //echo form_error('addressline3');?>
                        </span> 
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">Address line4&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="" data-parsley-maxlength="30" maxlength="30" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                        <span class="error">
                        <?php //echo form_error('addressline4');?>
                        </span> 
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">District &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                        <span class="error">
                        <?php //echo form_error('district');?>
                        </span> 
                     </div>
                  </div>
                  <div class="form-group">
                     <label for="roleid" class="col-sm-4 control-label">City &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                     <div class="col-sm-6">
                        <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30">
                        <span class="error">
                        <?php //echo form_error('city');?>
                        </span> 
                     </div>
                  </div>
                  <?php //print_r($states);?>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">State &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                     <div class="col-sm-3">
                        <select name="state" id="state" class="form-control" required>
                           <option value="" >Select Course</option>
			                           <?php if(!empty($states))
			                              {
			                               foreach($states as $value)
			                               { 
			                               
			                               ?>
			                           <option value="<?php  echo $value['state_code'] ?>" >
			                              <?php  echo $value['state_name'] ?>
			                           </option>
			                           <?php }
			                              }?>
                        </select>
                        <input hidden="statepincode" id="statepincode" value="" autocomplete="false">
                     </div>
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value=""  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-type="number" data-parsley-trigger-after-failure="focusout">
                        <span class="error">
                        <?php //echo form_error('pincode');?>
                        </span> 
                     </div>
                  </div>
               </div>
            </div>
            <!-- Basic Details box closed-->
             <!-- Course  Details box -->
              <div class="row">
			         <div class="col-md-12">
			            <div class="box box-info">
			               <div class="box-header with-border">
			                  <h3 class="box-title">CourseDetails</h3>
			               </div>
			               <!-- /.box-header --> 
			               <!-- form start -->
			               <div class="alert alert-danger alert-dismissible" id="VCDBF" style="display:none">
			                  <?php   echo 'Please select JAIIB Virtual Contact Class';?>
			               </div>
			               <div class="alert alert-danger alert-dismissible" id="VCJAIIB" style="display:none">
			                  <?php   echo 'Please select DB&F Virtual Contact Class';?>
			               </div>
			               <div class="box-body">
			                  <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
			                  <div class="form-group">
			                     <label for="roleid" class="col-sm-4 control-label">Course &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
			                     <div class="col-sm-4"> <?php //echo '<pre>';print_r($course);die;?>
			                        <select name="course" id="course" class="form-control" onchange="getcenter(this.value)">
			                        <?php //onchange="getcenter(this.value,<?php echo $member_data[0]['regnumber']  ?>)" <?php //if (!isset( $member_data[0]['regnumber'])) { echo "disabled='disabled'";}  
			                           // $_SESSION['mem_no'] = $member_data[0]['regnumber'];?> >

			                           <option value="" >Select Course</option>
			                           <?php if(!empty($course))
			                              {
			                               foreach($course as $value)
			                               { 
			                               
			                               ?>
			                           <option value="<?php  echo $value['course_code'] ?>" >
			                              <?php  echo $value['course_name'] ?>
			                           </option>
			                           <?php }
			                              }?>
			                        </select>
			                        <span class="error" id="tiitle_error">
			                        <?php //echo form_error('firstname');?>
			                        </span> 
			                     </div>
			                  </div>
			                  <div class="form-group">
			                     <label for="roleid" class="col-sm-4 control-label">Center&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
			                     <div class="col-sm-4">
			                        <select name="center" id="center" class="form-control" onchange="getsub(this.value);">
			                           <option value="">Select Center </option>
			                        </select>
			                     </div>
			                  </div>
			                  <div class="form-group">
			                     <div  id="submsg">
			                        <label for="roleid" class="col-sm-4 control-label"  id="submsg" >Subjects&nbsp;<span style="color:#F00">*</span>&nbsp;: </label>
			                        <div class="col-sm-6" align="center"> <span style="color:#F00; font-size:14px;">Please select the course and center to get the subjects .</span> </div>
			                     </div>
			                  </div>
			                  <div class="form-group">
			                     <div  id="subject"> </div>
			                     <div class="col-sm-2"> <span class="error" id="title_error">
			                        <?php //echo form_error('firstname');?>
			                        </span> 
			                     </div>
			                  </div>
			               </div>
			            </div>
			         </div>
			      </div>
			       <!-- Course Details box closed--> 
            <div class="box box-info">
               <div class="box-header with-border">
                  <h3 class="box-title">
                     <input name="declaration1" value="1" type="checkbox" required="required" 
                        <?php if(set_value('declaration1'))
                           {
                             echo set_radio('declaration1', '1');
                            }?>>
                     &nbsp; I Accept
                  </h3>
               </div>

               <div class="box-footer">
                  <center>
                     <!-- <a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return checkform();" id="preview">Preview and Proceed for Payment</a>  -->
                     <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Preview and Proceed for Payment">
                     <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset" onclick="myFunction()">Reset</button>    
                  </center>
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
                     I confirm that all the  information is correct
                  </p>
               </div>
               <div class="modal-footer">
                  <!--  <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="preview();">Confirm</button>-->
                  <input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm"> 
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
<script src="<?php echo base_url();?>js/contact_classes.js"></script> 
<script>
   function myFunction() {
   location.reload();
   }
</script>
<script>
   $(function() {
   $("#dob1").dateDropdowns({
   submitFieldName: 'dob1',
   minAge: 0,
   maxAge:59
   });
   // Set all hidden fields to type text for the demo
   //$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
   });
   $(function() {
   $("#doj1").dateDropdowns({
   submitFieldName: 'doj1',
   minAge: 0,
   maxAge:59
   });
   });
   
   $(document).ready(function() 
   {
   var dtable = $('.dataTables-example').DataTable();
   $("#dob1").change(function(){
   var sel_dob = $("#dob1").val();
   if(sel_dob!='')
   {
   var dob_arr = sel_dob.split('-');
   if(dob_arr.length == 3)
   {
   	chkage(dob_arr[2],dob_arr[1],dob_arr[0]);	
   }
   else
   {	alert('Select valid date');	}
   }
   });
   
   $("#doj1").change(function(){
   var sel_doj = $("#doj1").val();
   if(sel_doj!='')
   {
   var doj_arr = sel_doj.split('-');
   if(doj_arr.length == 3)
   {
   	CompareToday(doj_arr[2],doj_arr[1],doj_arr[0]);	
   }
   else
   {	alert('Select valid date');	}
   }
   });
   
   var dt = new Date();
   dt.setFullYear(new Date().getFullYear()-18);
   
   if($('#hiddenphoto').val()!='')
   {
   $('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());
   }
   if($('#hiddenscansignature').val()!='')
   {
   $('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());
   }
   if($('#hiddenidproofphoto').val()!='')
   {
   $('#image_upload_idproof_preview').attr('src', $('#hiddenidproofphoto').val());
   }
   
   
   statecode=$("#state option:selected").val();
   
   if(statecode!='')
   {
   if(statecode=='ASS' || statecode=='JAM' || statecode=='MEG')
   {
   document.getElementById('mendatory_state').style.display = "none";
   //document.getElementById('non_mendatory_state').style.display = "block";
   $("#aadhar_card").removeAttr("required");
   }
   else
   {
   document.getElementById('mendatory_state').style.display = "block";
   document.getElementById('mendatory_state').innerHTML = "*";
   //document.getElementById('non_mendatory_state').style.display = "none";
   $("#aadhar_card").attr("required","true");
   }
   }
   
   });
   
   function editUser(id,roleid,Name,Username,Email){
   $('#id').val(id);
   $('#roleid').val(roleid);
   $('#name').val(Name);
   $('#username').val(Username);
   $('#emailid').val(Email);
   $('#btnSubmit').val('Update');
   $('#roleid').focus();
   $('#password').removeAttr('required');
   $('#confirmPassword').removeAttr('required');
   
   }
   
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
   
   var dval = $('#education_type').val();
   if(dval!='')
   {
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
   
   }
   
   });
   
   function sameAsAbove(fill) 
   {
   
   /*var addressline1 = fill.addressline1.value;
   var district = fill.district.value;
   var city = fill.city.value;
   var state = fill.state.value;
   var pincode = fill.pincode.value;
   
   var r = confirm("Please fill contact details first!");
   if (addressline1 == '' && district == '' && city == '' && state == '' && pincode == '' ) {
   alert('please fill contact details first..');
   } 
   else
   { }*/
   if(fill.same_as_above.checked == true) 
   {
   fill.addressline1_pr.value = fill.addressline1.value;
   fill.addressline2_pr.value = fill.addressline2.value;
   fill.addressline3_pr.value = fill.addressline3.value;
   fill.addressline4_pr.value = fill.addressline4.value;
   fill.district_pr.value = fill.district.value;
   fill.city_pr.value = fill.city.value;
   fill.state_pr.value = fill.state.value;
   fill.pincode_pr.value = fill.pincode.value;
   }
   else
   {
   fill.addressline1_pr.value = '';
   fill.addressline2_pr.value = '';
   fill.addressline3_pr.value = '';
   fill.addressline4_pr.value = '';
   fill.district_pr.value = '';
   fill.city_pr.value = '';
   fill.state_pr.value = '';
   fill.pincode_pr.value = '';
   }
   
   /*else {
   txt = "You pressed Cancel!";
   } */
   
   }
</script>
<script type="text/javascript">
	// $(document).ready(function(){
	//  $('#course').change(function(){
	//   var course = $('#course').val();
	//   // alert(center);
	//   if(course != '')
	//   {
	//    $.ajax({
	//     url:"<?php echo base_url(); ?>ContactClasses/getvtcenter",
	//     method:"POST",
	//     data:{course:course},
	//     success:function(data)
	//     {
	//      alert(data);
	//      console.log(data);
	//      $('#center').html(data);
	//     }
	//    });
	//   }
	//   else
	//   {
	//    $('#center').html('<option value="">Select State</option>');
	//    // $('#city').html('<option value="">Select City</option>');
	//   }
	//  });

	//  $('#center').change(function(){
	//   var center = $('#center').val();
	//   // alert(center);
	//   if(center != '')
	//   {
	//    $.ajax({
	//     url:"<?php echo base_url(); ?>dynamic_dependent/fetch_city",
	//     method:"POST",
	//     data:{center:center},
	//     success:function(data)
	//     {
	//      // $('#sub').html(data);
	//     }
	//    });
	//   }
	//   else
	//   {
	//    $('#city').html('<option value="">Select City</option>');
	//   }
	//  });
	// });
</script>