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
<div id="confirmBox">
   <div class="message" style="color:#F00"> <strong>VERY IMPORTANT</strong> I confirm that the  Photo, Signature & Id proof images  uploaded belongs to me and they are clear and readable.</div>
   <span class="button yes">Confirm</span> <span class="button no">Cancel</span> 
</div>
<div class="container">
   <!-- Trigger the modal with a button --> 
   <!-- Modal -->
   <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
   <!--  <img src="<?php echo base_url();?>assets/images/iibf_logo_black.png" class="ifci_logo_black" />--> 
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1 class="register">Examination Prize winner's for the year 2024-25</h1>
      <br />
      <!--<ol class="breadcrumb">
         <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
         <li class="active">Manage Users</li>
         </ol>--> 
   </section>
   <section class="content">
      <!-- form start -->
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
      <div class="row">
         <form name="getDetailsForm" id="getDetailsForm" method="post" action="<?php echo base_url(); ?>PrizeWinner/member">
            <!--<div class="col-md-1"></div>     -->
            <div class="col-md-12">
               <div for="roleid" class="col-sm-5 control-label" style="text-align: right; width:35%;">Membership No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</div>
               <div class="col-sm-4" style="width: 32%;text-align: left;">
                  <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Membership No." required value="<?php if (isset($row['regnumber'])) { echo $row['regnumber'];} else { echo set_value('regnumber'); }
                     ?>" <?php if (isset($row['regnumber'])) { echo "readonly='readonly'";} elseif (set_value('regnumber')) { echo "readonly='readonly'"; } ?> style="border-color:#000;" title="Membership No.">
               </div>
               <div class="col-sm-3" style="padding-bottom: 10px">
                  <?php 
                     if (isset($row['regnumber']) || set_value('regnumber')) {
                     ?>
                  <a href="<?php echo base_url();?>PrizeWinner/member" class="btn btn-info" id="modify" style="height: 32px; width: 150px">Modify</a>
                  <input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGet" value="Get Details" style="height: 32px; width: 150px; font-size:15px; display:none;">
                  <?php
                     } 
                     else
                     {
                     ?>
                  <input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGetDetails" value="Get Details" style="height: 32px; width: 150px; font-size:15px;">
                  <?php 
                     } 
                      ?>
               </div>
               <div class="col-sm-12" align="center"> <span style="color:#F00; font-size:14px;">Please insert your 'Membership No.' and click on 'Get Details' button. Basic detai will get filled automatically.</span> </div>
            </div>
            <!--<div class="col-md-1"></div>--> 
         </form>
      </div>
      <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data">
         <div class="row">
            <div class="col-md-12">
               <!-- Horizontal Form -->
               <div class="box box-info">
                  <!-- /.box-header --> 
                  <div class="box box-info">
                     <div class="box-header with-border">
                        <h3 class="box-title">Basic Details</h3>
                     </div>
                     <div class="box-body">
                        <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
                        <input type="hidden" name="regnumber" id="regnumber" value="<?php
                           if (isset($row[0]['regnumber'])) 
                           {echo $row[0]['regnumber'];}
                           ?>"/>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">First Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                           <div class="col-sm-2">
                              <input type="text" class="form-control" id="sel_namesub" name="sel_namesub" value="<?php if (isset($row[0]['namesub'])) { echo $row[0]['namesub'];} elseif(isset($this->session->userdata['enduserinfo']['sel_namesub'])){echo $this->session->userdata['enduserinfo']['sel_namesub'];}else { echo set_value('sel_namesub'); }?>" placeholder="Prefix" readonly="readonly">
                           </div>
                           <div class="col-sm-3">
                              <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php if (isset($row[0]['fname'])) {echo $row[0]['fname'];
                                 }elseif(isset($this->session->userdata['enduserinfo']['firstname'])){echo $this->session->userdata['enduserinfo']['firstname'];} else { echo set_value('firstname'); }?>" readonly="readonly">
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Last Name&nbsp;:</label>
                           <div class="col-sm-5">
                              <input type="text" class="form-control" name="lastname" id="lastname" value="<?php if (isset($row[0]['laname'])) {echo $row[0]['laname'];}elseif(isset($this->session->userdata['enduserinfo']['lastname'])){echo $this->session->userdata['enduserinfo']['lastname'];} else { echo set_value('lastname'); }?>" placeholder="Last Name" readonly="readonly"/>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Email&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                           <div class="col-sm-5">
                              <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php if (isset($row[0]['email'])) { echo $row[0]['email'];
                                 }elseif(isset($this->session->userdata['enduserinfo']['email'])){echo $this->session->userdata['enduserinfo']['email'];} else { echo set_value('email'); }?>"  data-parsley-maxlength="45" required   data-parsley-trigger-after-failure="focusout" readonly="readonly">
                              <span class="error"> </span> 
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="roleid" class="col-sm-4 control-label">Mobile&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                           <div class="col-sm-5">
                              <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  value="<?php if (isset($row[0]['moblie'])) {
                                 echo $row[0]['moblie'];}elseif(isset($this->session->userdata['enduserinfo']['mobile'])){echo $this->session->userdata['enduserinfo']['mobile'];} else { echo set_value('mobile'); }?>"  required  data-parsley-trigger-after-failure="focusout" readonly="readonly">
                              <span class="error"></span> 
                           </div>
                        </div>
                     </div>
                     <!-- Basic Details box closed-->
                     <div class="box box-info">
                        <div class="box-header with-border">
                           <h3 class="box-title">Bank Details</h3>
                        </div>
                        <div class="box-body">
                           <?php
                              $address1 = $address2 = $address3 = $address4 = '';
                              if (isset($row['address1'])){ $address1 = $row['address1']; }
                              if (isset($row['address2'])){ $address2 = $row['address2']; }
                              if (isset($row['address3'])){ $address3 = $row['address3']; }
                              if (isset($row['address4'])){ $address4 = $row['address4']; }
                              
                              $address1 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address1);
                              $address2 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address2);
                              $address3 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address3);
                              $address4 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address4);
                              
                              ?>
                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label">Bank Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                              <div class="col-sm-5">
                                 <input type="text" class="form-control" id="bankname" name="bankname" placeholder="Bank Name" required value="<?php  echo set_value('bankname'); ?>">
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label">Branch Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                              <div class="col-sm-5">
                                 <input type="text" class="form-control" id="branchname" name="branchname" placeholder="Branch Name" required value="<?php echo set_value('branchname');  ?>" >
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label">IFS CODE &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                              <div class="col-sm-5">
                                 <input type="text" class="form-control" id="ifs_code" name="ifs_code" placeholder="IFS CODE" required value="<?php echo set_value('ifs_code');  ?>">(should be 11digit)
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label">Bank Address line1&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                              <div class="col-sm-5">
                                 <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Bank Address line1" required value="<?php  echo set_value('addressline1');   ?>" data-parsley-maxlength="30" maxlength="30" />
                                 <span class="error"></span> 
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label">Bank Address line2&nbsp;:</label>
                              <div class="col-sm-5">
                                 <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Bank Address line2"  value="<?php  echo set_value('addressline2');  ?>"  data-parsley-maxlength="30" maxlength="30"  />
                                 <span class="error"></span> 
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label">Bank Address line3&nbsp;:</label>
                              <div class="col-sm-5">
                                 <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Bank Address line3"  value="<?php   echo set_value('addressline3'); ?>"  data-parsley-maxlength="30" maxlength="30">
                                 <span class="error"></span> 
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label">Bank Address line4&nbsp;:</label>
                              <div class="col-sm-5">
                                 <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Bank Address line4"  value="<?php  echo set_value('addressline4'); ?>" data-parsley-maxlength="30" maxlength="30" >
                                 <span class="error"> </span> 
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label">Type of Account <span style="color:#F00">*</span>&nbsp;:</label>
                              <div class="col-sm-5">
                                 <select class="form-control" id="account_type" name="account_type"  value="<?php echo set_value('account_type');?>">
                                    <option value="">- Select Course -</option>
                                    <option value="SA" <?php echo  set_select('account_type', 'SA');?>>Saving Account</option>
                                    <option value="CA" <?php echo  set_select('account_type', 'CA'); ?>>Current Account</option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label">Account no. &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                              <div class="col-sm-5">
                                 <input type="text" class="form-control" id="account_no" name="account_no" placeholder="Account no. " required value="<?php  echo set_value('account_no');  ?>" >
                              </div>
                           </div>
                          <!-- <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label"> Upload Your Cancel Cheque Image<span style="color:#f00">**</span></label>
                              <div class="col-sm-5">
                                 <input type="file" class="" name="scannedsignaturephoto1" id="scannedsignaturephoto1">
                                 <input type="hidden" id="hiddenscansignature1" name="hiddenscansignature1" >
                                 <div id="error_signature1"></div>
                                 <br>
                                 <div id="error_signature_size1"></div>
                                 <span class="signature_text1" style="display:none;"></span> <span class="error">
                                 <?php //echo form_error('scannedsignaturephoto');?>
                                 </span> 
                              </div>
                              <img id="image_upload_sign_preview1" height="100" width="100" /> 
                           </div> -->

                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label"> Upload Your Pan Card File<span style="color:#f00">*</span></label>
                              <div class="col-sm-5">
                                 <input type="file" class="" required name="pan_card_file" id="pan_card_file" onchange="validateFile(event, 'error_pan_card_file_size', 'image_upload_pan_card_file_preview', '2048kb')"> <!-- 2048KB => 2MB -->
                                 <input type="hidden" id="hidden_pan_card_file" name="hidden_pan_card_file" >
                                 <div id="error_pan_card_file"></div>
                                 <br> 
                                 <div id="error_pan_card_file_size" class="error"></div>
                                 <span class="pan_card_file_text1" style="display:none;"></span> <span class="error">
                                 <?php //echo form_error('pan_card_file');?>
                                 </span> 
                              </div>
                              <img class="mem_reg_img" id="image_upload_pan_card_file_preview" height="100" width="100" src="/assets/images/default1.png" />
                           </div>

                           <div class="form-group">
                              <label for="roleid" class="col-sm-4 control-label"> Upload Your Cancel Cheque File<span style="color:#f00">*</span></label>
                              <div class="col-sm-5">
                                 <input type="file" class="" required name="cancel_cheque_file" id="cancel_cheque_file" onchange="validateFile(event, 'error_cancel_cheque_file_size', 'image_upload_cancel_cheque_file_preview', '2048kb')"> <!-- 2048KB => 2MB -->
                                 <input type="hidden" id="hidden_cancel_cheque_file" name="hidden_cancel_cheque_file" >
                                 <div id="error_cancel_cheque_file"></div>
                                 <br> 
                                 <div id="error_cancel_cheque_file_size" class="error"></div>
                                 <span class="cancel_cheque_file_text1" style="display:none;"></span> <span class="error">
                                 <?php //echo form_error('cancel_cheque_file');?>
                                 </span> 
                              </div>
                              <img class="mem_reg_img" id="image_upload_cancel_cheque_file_preview" height="100" width="100" src="/assets/images/default1.png" />
                           </div>

                           <div class="form-group">
                              <label for="roleid" class="col-sm-1 control-label"><span style="color:#F00; font-size:14px;"> Note <span style="color:#F00"></span>&nbsp;:</label>
                              <div class="col-sm-9"><span style="color:#F00; font-size:14px;"> Please ensure the bank details should be correct.</br>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- Basic Details box closed-->
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
                  <div class="form-group m_t_15">
                     <label for="roleid" class="col-sm-3 control-label">Security Code <span style="color:#F00">*</span></label>
                     <div class="col-sm-2">
                        <input type="text" name="code" id="code" required class="form-control " >
                        <span class="error" id="captchaid" style="color:#B94A48;"></span> 
                     </div>
                     <div class="col-sm-3">
                        <div id="captcha_img"><?php echo $image;?></div>
                        <span class="error">
                        <?php //echo form_error('code');?>
                        </span> 
                     </div>
                     <div class="col-sm-2"> <a href="javascript:void(0);" id="new_captcha" class="forget">Change Image</a> <span class="error">
                        <?php //echo form_error('code');?>
                        </span> 
                     </div>
                  </div>
                  <div class="box-footer">
                     <center>
                        <input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Preview">
                        <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset">Reset</button>  
                     </center>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </section>
</div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<script src="<?php echo base_url();?>js/validation.js?<?php echo time(); ?>"></script> 
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
   
   
      $('#new_captcha').click(function(event){
          event.preventDefault();
      $.ajax({
   		type: 'POST',
   		url: site_url+'Register/generatecaptchaajax/',
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


function validateFile(event, error_id, show_img_id, size, img_width, img_height) {
  var srcid = event.srcElement.id;
  if (document.getElementById(srcid).files.length != 0) {
    var file = document.getElementById(srcid).files[0];

    if (file.size == 0) {
      $('#' + error_id).text('Please select valid file');
      $('#' + document.getElementById(srcid).id).val('')
      $('#' + show_img_id).attr('src', "/assets/images/default1.png");
    }
    else {
      var file_size = document.getElementById(srcid).files[0].size / 1024;
      var mimeType = document.getElementById(srcid).files[0].type;

      //var allowedFiles = [".jpg", ".jpeg", ".pdf"];
      var allowedFiles = [".jpg", ".jpeg"];
      if ($('#' + document.getElementById(srcid).id + '_allowedFilesTypes').text() != "") {
        var allowedFiles = $('#' + document.getElementById(srcid).id + '_allowedFilesTypes').text().split(",");
      }
      var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");

      var reader = new FileReader();

      var check_size = '';

      if (size.indexOf('kb') !== -1) {
        var check_size = size.split('k');
      }
      if (size.indexOf('mb') !== -1) {
        var check_size = size.split('m');
      }

      reader.onload = function (e) {
        var img = new Image();
        img.src = e.target.result;

        if (reader.result == 'data:') {
          $('#' + error_id).text('This file is corrupted');
          //$('.btn_submit').attr('disabled',true);
          //$('#'+show_img_id).removeAttr('src');
          $('#' + document.getElementById(srcid).id).val('')
          $('#' + show_img_id).attr('src', "/assets/images/default1.png");
        }
        else {
          //$('#'+error_id).text('This file can be uploaded');
          if (!regex.test(file.name.toLowerCase())) {
            $('#' + error_id).text("Please upload " + allowedFiles.join(', ') + " only.");
            //$('.btn_submit').attr('disabled',true);
            //$('#'+show_img_id).removeAttr('src');
            $('#' + document.getElementById(srcid).id).val('')
            $('#' + show_img_id).attr('src', "/assets/images/default1.png");
          }
          else {
            if (file_size > check_size[0]) {
              //console.log('if');
              $('#' + error_id).text("Please upload file less than " + size);
              //$('.btn_submit').attr('disabled',true);
              //$('#'+show_img_id).removeAttr('src');
              $('#' + document.getElementById(srcid).id).val('')
              $('#' + show_img_id).attr('src', "/assets/images/default1.png");
            }
            else if (file_size < 8) //IF FILE SIZE IS LESS THAN 8KB
            {
              $('#' + error_id).text("Please upload file having size more than 8KB");
              $('#' + document.getElementById(srcid).id).val('')
              $('#' + show_img_id).attr('src', "/assets/images/default1.png");
            }
            else {
              img.onload = function () {
                var width = this.width;
                var height = this.height;

                //console.log(width+'----'+height);

                if (width > img_width && height > img_height) {
                  $('#' + error_id).text(' Uploaded File dimensions are ' + width + '*' + height + ' pixel. Please Upload file dimensions between ' + img_width + '*' + img_height + ' pixel');
                  //$('.btn_submit').attr('disabled',true);
                  //$('#'+show_img_id).removeAttr('src');
                  $('#' + document.getElementById(srcid).id).val('')
                  $('#' + show_img_id).attr('src', "/assets/images/default1.png");
                }
                else {
                  //console.log('else');
                  $('#' + error_id).text("");
                  $('.btn_submit').attr('disabled', false);
                  $('#' + show_img_id).attr('src', '');
                  $('#' + show_img_id).removeAttr('src');
                  $('#' + show_img_id).attr('src', reader.result);

                  var img = new Image();
                  img.src = reader.result;

                  //$('.'+show_img_id+'_zoom').zoom();
                }
              }

            }
          }
        }
      }

      reader.readAsDataURL(event.target.files[0]);
    }
  }
  else {
    $('#' + error_id).text('Please select file');
    //$('.btn_submit').attr('disabled',true);
    //$('#'+show_img_id).removeAttr('src');
    $('#' + document.getElementById(srcid).id).val('')
    $('#' + show_img_id).attr('src', "/assets/images/default1.png");
  }
}


</script>