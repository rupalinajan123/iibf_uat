<style>
  /*Cropper Image Editor*/
    #optionsModal > .modal-dialog, #cropModal > .modal-dialog { max-width: 600px; }
    #optionsModal > .modal-dialog h4.modal-title, #GuidelinesModal > .modal-dialog h4.modal-title, #cropModal > .modal-dialog h4.modal-title { text-align: center; }

    #GuidelinesModal > .modal-dialog { max-width: 800px; }
  /*Cropper Image Editor*/

  /*Corporate BC Changes */
#parsley-id-multiple-corporate_bc_option{
  margin-top: 30px;
  margin-left: 428px;
}  
/*Corporate BC Changes */  

</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
    
	<!-- <form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>NonMember/preview/"> -->
    
    	<form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>CSCSpecialMember/comApplication/">
    
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('cscnmregid');?>"> 
    <section class="content">
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
            <?php //echo validation_errors(); ?>
              <?php if($this->session->flashdata('error')!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?>
              </div>
             <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo validation_errors(); ?>
                </div>
              <?php } 
			 ?> 
             
             <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                	<div class="col-sm-1">
                		<?php echo $user_info[0]['regnumber'];
						  $fee_amount=$grp_code='';?>
                          
                     <input type="hidden" name="reg_no" id="reg_no" value="<?php echo $user_info[0]['regnumber'];?>">
                      <input type="hidden" name="extype" id="extype" value="<?php echo $examinfo[0]['exam_type'];?>">
                      <input type="hidden" id="exname" name="exname"  value=" <?php echo $examinfo[0]['description'];?>">
                       <input type="hidden" id="excd" name="excd"  value="<?php echo base64_encode($this->session->userdata('examcode'));?>">
                          <input id="examcode" name="examcode" type="hidden" value="<?php echo $this->session->userdata('examcode');?>">
                         <input id="eprid" name="eprid" type="hidden" value="<?php echo $examinfo[0]['exam_period'];?>">
                         <input id="fee" name="fee" type="hidden" value="">         
                         <input type='hidden' name='mtype' id='mtype' value="<?php echo $this->session->userdata('memtype')?>">     
                         <?php 
							if(isset($examinfo[0]['app_category']))
							{
								$grp_code=$examinfo[0]['app_category'];
							}
							else
							{
								$grp_code='B1_1';
							};
                        ?>
                        	 <input id="grp_code" name="grp_code" type="hidden" value="<?php echo trim($grp_code);?>">                 
                    </div>
                </div>
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name </label>
                     <div class="col-sm-3">
					<?php echo $user_info[0]['firstname'];?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['middlename'];?>
                  <!--    <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $user_info[0]['middlename'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['lastname'];?>
                      <!--<input type="text" class="form-control" id="middlename" name="lastname" placeholder="Last Name"  value="<?php echo $user_info[0]['lastname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >-->
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                 
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <?php echo $user_info[0]['mobile'];?>
                      <!-- <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['mobile'];?>"  data-parsley-nmeditmobilecheck required data-parsley-trigger-after-failure="focusout" > -->
                      <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>
                
                <?php 

                $email_verify_status  = set_value('email_verify_status') != '' ? set_value('email_verify_status') : 'no'; 
                 
                $emailStatus  = false;  
                 
                if ($email_verify_status == 'yes') {
                  $emailStatus = true;
                } 

                /*if($_POST['email_verified'] == 'yes'){
                  $emailStatus = 'yes';
                  $email_verify_status = 'yes';
                }*/

                if (isset($this->session->userdata['enduserinfo']['email_verified']) && isset($this->session->userdata['enduserinfo']['verified_email_val']) )
                {
                  if($this->session->userdata['enduserinfo']['email_verified'] == "yes" && ($this->session->userdata['enduserinfo']['verified_email_val'] == $user_info[0]['email'])){
                    $emailStatus = 'yes';
                    $email_verify_status = 'yes';
                  } 
                }
                 
              ?>

                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    <input class="form-control email-id" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-nmeditemailcheck  type="text" data-parsley-trigger-after-failure="focusout" readonly>
                    
                      
                       <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                        <span style="color:#F00;font-size:small;">(Please check correctness of your Email id and Mobile number. Correct/Active E-mail address is mandatory for receipt of Admit Letter and other communication/s through e-mail.)</span>
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>

                    <div class="col-sm-2">
                  <?php ?>
                  <button type="button" class="btn btn-info send-otp" id="send_otp_btn" data-type='send_otp' <?php if($email_verify_status == 'yes') { ?> style="display:none;" <?php } ?>>Get OTP</button> 

                  <!-- <a class="btn btn-info" id="reset_btn" href="javascript:void(0)" <?php if($emailStatus == 'yes') { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>Change Email</a> -->

                  </div>

                  <input type="hidden" id="email_verify_status" name="email_verify_status" value="<?php echo $email_verify_status; ?>">

                </div> 

                <div class="form-group verify-otp-section" style="display:none;">
                <label for="roleid" class="col-sm-3 control-label">OTP <span style="color:#F00">*</span></label>
                <div class="row">
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="otp" name="otp" placeholder="OTP" onKeyPress="if(this.value.length==6) return false;" value="<?php echo set_value('otp'); ?>">
                  </div>
                  <div class="col-sm-4">
                    <button type="button" class="btn btn-info verify-otp" data-verify-type='email'>Verify OTP </button>
                    <button type="button" class="btn btn-info send-otp" data-type='resend_otp'>Resend OTP</button>
                  </div>  
                </div>  
              </div>


              <div class="form-group"><?php // Upload your scanned Photograph  ?>
                <label for="scannedphoto" class="col-sm-3 control-label">Scanned Photograph <span style="color:#F00">*</span></label>
                <div class="col-sm-5"> 

              <!-- START: FOR IMAGE EDITOR -->
              <?php $data_lightbox_title_common = $user_info[0]['firstname']." ".$user_info[0]['middlename']." ".$user_info[0]['lastname']; ?>
              <input type="hidden" name="form_value" id="form_value" value="form_value">
              <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $data_lightbox_title_common; ?>">
              <?php $inc_fileChooser_accepted_files = '.jpg, .jpeg'; ?>
              <input type="hidden" name="inc_fileChooser_accepted_files" id="inc_fileChooser_accepted_files" value="<?php echo $inc_fileChooser_accepted_files; ?>">
              <!-- END: FOR IMAGE EDITOR --> 

                  <div id="scannedphoto_preview" class="upload_img_preview">
                    <?php
                    $preview_scannedphoto = $csc_scannedphoto = '';
                    if(is_file(get_img_name($this->session->userdata('cscnmregnumber'),'p'))){
                      $preview_scannedphoto = base_url()."".get_img_name($this->session->userdata('cscnmregnumber'),'p');
                      $csc_scannedphoto = get_img_name($this->session->userdata('cscnmregnumber'),'p');
                    }

                    if ($preview_scannedphoto != "")
                    { ?>
                      <a href="<?php echo $preview_scannedphoto . "?" . time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Scanned Photograph - '; echo $data_lightbox_title_common;?>">
                        <img src="<?php echo $preview_scannedphoto . "?" . time(); ?>">
                      </a> 
                    <?php }
                    else
                    {
                      echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                    } ?>

                    <input type="hidden" name="csc_scannedphoto" id="csc_scannedphoto" value="<?php echo $csc_scannedphoto; ?>" /><?php /* FOR CROPPED IMAGE */ ?>

                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>

                </div>

               

                
               </div> <!-- Basic Details box closed-->
                 <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
            </div>
            
         

            <div class="box-body">
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                	<div class="col-sm-5 ">
                        <?php echo $examinfo['0']['description'];?>
                     <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                
                
              
                
          
			     <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <?php //echo  ?>
                    <select name="medium" id="medium" class="form-control" required style="width:250px">
                  	<option value="">Select</option>
                    <?php 
                    if(count($medium) > 0)
					{
						foreach($medium as $mrow)
						{?>
								<option value="<?php echo $mrow['medium_code']?>"><?php echo $mrow['medium_description']?></option>
						<?php }
					}?>
                    </select>
                    </div>
                </div>
   
  				 <div class="form-group">
                
                <label for="roleid" class="col-sm-3 control-label">Centre Name <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <select name="selCenterName" id="selCenterName" class="form-control" required onchange="valCentre(this.value);" style="width:250px">
                  	<option value="">Select</option>
                    <?php if(count($center) > 0)
					{
						
						foreach($center as $crow)
						{?>
								<option value="<?php echo $crow['center_code']?>" class=<?php echo $crow['exammode'];?>><?php echo $crow['center_name']?></option>
						<?php }
					}?>
                    </select>
                    </div>
                   </div>
                
                
                <?php 
				   if(count($compulsory_subjects) > 0)
				   {
					   $i=1;
					   foreach($compulsory_subjects as $subject)
					   {?>
                            <div class="form-group">
                          	  <label for="roleid" class="col-sm-3 control-label"><?php echo $subject['subject_description']?><span style="color:#F00">*</span></label>
                                <div class="col-sm-2">
                                <label for="roleid" class="col-sm-3 control-label">Venue<span style="color:#F00">*</span></label>
                                <select name="venue[<?php echo $subject['subject_code']?>]" id="venue_<?php echo $i;?>" class="form-control venue_cls" required  onchange="venue(this.value,'date_<?php echo $i;?>','time_<?php echo $i;?>','<?php echo $subject['subject_code']?>','seat_capacity_<?php echo $i;?>');" attr-data='<?php echo $subject['subject_code']?>'>
                                <option value="">Select</option>
                                </select>
                                </div>
                                
                                <div class="col-sm-2">
                                <label for="roleid" class="col-sm-3 control-label">Date<span style="color:#F00">*</span></label>
                                <select name="date[<?php echo $subject['subject_code']?>]" id="date_<?php echo $i;?>" class="form-control date_cls" required  onchange="date(this.value,'venue_<?php echo $i;?>','time_<?php echo $i;?>');">
                                <option value="">Select</option>
                                </select>
                                </div>
                                
                                <div class="col-sm-2">
                                <label for="roleid" class="col-sm-3 control-label">Time<span style="color:#F00">*</span></label>
                                <select name="time[<?php echo $subject['subject_code']?>]" id="time_<?php echo $i;?>" class="form-control time_cls" required onchange="time(this.value,'venue_<?php echo $i;?>','date_<?php echo $i;?>','seat_capacity_<?php echo $i;?>');">
                                <option value="">Select</option>
                                </select>
                                </div>
                                
                               
                                <label for="roleid" class="col-sm-0 control-label">Seat(s) Available<span style="color:#F00">*</span></label>
                                <div id="seat_capacity_<?php echo $i;?>">
                              	-
                                </div>
                               </div>
                               
                               
                <?php 
				$i++;}
				 }?>
             
                <div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Centre Code <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <input type="text" name="txtCenterCode" id="txtCenterCode"  class="form-control pull-right" readonly="readonly"
                     value=""> 
                    <input id="optmode" name="optmode" value="" type="hidden"> 
                    <input type="hidden" name="elected_exam_mode" id="elected_exam_mode" value="C">
                    <input type="hidden" name="placeofwork" id="placeofwork" value="">
                    <input type="hidden" name="state_place_of_work" id="state" value="">
                    <input type="hidden" name="pincode_place_of_work" id="pincode_place_of_work" value="">
                  </div>
                </div>  

               
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                     
                     <!--<a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return login_nm_checkform();" id="preview">Preview</a>-->
                     
                     <input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Submit" onclick="javascript:return login_nm_checkform();">
                     
                   <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                   <a href="<?php echo base_url();?>CSCSpecialMember/comApplication/" class="btn btn-info" id="Reset">Reset</a>
                     <!--<button type="reset" class="btn btn-info" name="btnReset" id="btnReset">Reset</button>-->
                     <!-- <a href="<?php echo base_url();?>CSCSpecialMember/" class="btn btn-info" id="preview">Back</a> -->
                    </div>
              </div>
             </div>
     </div>
  </div>
     
      
      </div>
    </section>
 
  
     </form>
     </div>
     <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <center><strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4></strong></center>
      </div>
      <div class="modal-body" style="color:#F00">
           The facility of scribe ,on request, is provided to the person with Disability only.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>
<!--<script type="text/javascript">
<!-- Data Tables -->

<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>

<!-- Image Validation -->
<script src="https://iibf.esdsconnect.com/staging/js/validateFile.js"></script>
<script src="<?php echo base_url(); ?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>

<script type="text/javascript">
  function loginusercheckform()
  {$('#member_conApplication').parsley().validate();}
  $('#scribe_flag').on('change', function(e){
   if(e.target.checked){
     $('#myModal').modal();
   }
});
</script>

<script>
$(document).ready(function(){
		var cCode=$('#selCenterName').val();
		if(cCode!='')
		{
			document.getElementById('txtCenterCode').value = cCode ;
			var examType = document.getElementById('extype').value;
			var examCode = document.getElementById('examcode').value;
			var temp = document.getElementById("selCenterName").selectedIndex;
			selected_month = document.getElementById("selCenterName").options[temp].className;
			if(selected_month == 'ON')
			{
				if(document.getElementById("optmode1")){
					document.getElementById("optmode1").style.display = "block";
					document.getElementById('optmode').value= 'ON';
				}
					
				if(document.getElementById("optmode2"))
				{
					document.getElementById("optmode2").style.display = "none";	
				}
				
			}	
			else if(selected_month == 'OF')
			{
				if(document.getElementById("optmode2")){
					document.getElementById("optmode2").style.display = "block";
					document.getElementById('optmode').value= 'OF';
				}
				if(document.getElementById("optmode1")){
					document.getElementById("optmode1").style.display = "none";
				}	
			}
			else{
					if(document.getElementById("optmode1")){
						document.getElementById("optmode1").style.display = "none";
					}
					if(document.getElementById("optmode2")){
						document.getElementById("optmode2").style.display = "none";
					}
			}
		
		}
		
	if($('#hiddenphoto').val()!='')
	{
		   $('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());
	}
	if($('#hiddenscansignature').val()!='')
	{
		   $('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());
	}
	
});


$("#dob1").change(function() {
      var sel_dob = $("#dob1").val();
      if (sel_dob != '') {
        var dob_arr = sel_dob.split('-');
        if (dob_arr.length == 3) {
          chkage(dob_arr[2], dob_arr[1], dob_arr[0]);
        } else {
          alert('Select valid date');
        }
      }
    });
  $(function() {
      $("#doj1").dateDropdowns({
        submitFieldName: 'doj1',
        minAge: 0,
        maxAge: 59
      });
    });

    $(document).ready(function() {
      $("#doj1").change(function() {
        var sel_doj = $("#doj1").val();
        if (sel_doj != '') {
          var doj_arr = sel_doj.split('-');
          if (doj_arr.length == 3) {
            CompareMaxDate(doj_arr[2], doj_arr[1], doj_arr[0]);
          } else {
            alert('Select valid date');
          }
        }
      });
    })

  function CompareMaxDate(day,month,year)
  {
    var exam_date_exist = $("#exam_date_exist").val();
    //var check_start_date = "2023-07-01"; 
    var check_start_date = "1964-01-01";
    var check_start_date = new Date(check_start_date);
    var check_end_date = "2024-03-31"; 
    var check_end_date = new Date(check_end_date);
    check_end_date.setDate(check_end_date.getDate() + 1);
    //alert(exam_date_exist);
    var flag = 0;
    if(day!='' && month!='' && year!='')
    {
      /*var today = new Date();
      var dd = today.getDate(); 
      var mm = today.getMonth(); 
      var yyyy = today.getFullYear();*/

      var dd = "31"; 
      var mm = "02"; 
      var yyyy = "2024";
       
      if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} 
        var today = new Date(yyyy, mm, dd);
    
      var jday  = day;
      var jmnth = month;
      var jyear = year;
      var jdate = new Date(jyear, jmnth-1, jday);
      
      var sel_dob = $("#dob1").val();
      var dobYear = 0;
      if(sel_dob!='')
      {
        var dob_arr = sel_dob.split('-');
        if(dob_arr.length == 3)
        {
          dobYear = dob_arr[0];
        }
      }
      var minjoinyear = parseInt(dobYear) + parseInt(18);
      //console.log(jdate +'>'+ today);

      var examDate = new Date(exam_date_exist);
      var formattedExamDate = formatDateJs(examDate);
      // Add 9 months
      var ninemonthDate = new Date(jdate);
      ninemonthDate.setMonth(ninemonthDate.getMonth() + 9);
      //alert(ninemonthDate);
      var beforeninemonthDate = new Date(exam_date_exist);
      beforeninemonthDate.setMonth(beforeninemonthDate.getMonth() - 9);
      jdate.setDate(jdate.getDate() + 1); 
      
      /*if( jdate > today )
      {
        $("#doj_error").html('Date of joining should not be greater than 31-March-2024');
        flag = 0;
        return false;
      }
      else if( jdate < beforeninemonthDate ) // && jdate > examDate 
      {
        //console.log(jdate +'<'+ beforeninemonthDate);
        var formattedbeforeNineMonthDate = formatDateJs(beforeninemonthDate);
        $("#doj_error").html('Commencement of operations / joining as BC to be within 9 months from the date of examination.');
        //$("#doj_error").html('Please select your Date of Joining within 9 months (270 days) from the date of examination.<br> Your Examination Date is '+formattedExamDate+', your Date of Joining should be on or after '+formattedbeforeNineMonthDate+'.');
        flag = 0;
        return false;
      }*/
      if( jdate < check_start_date ) // && jdate > examDate 
      {
        $("#doj_error").html('Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.'); 
        flag = 0;
        return false;
      }
      else if( jdate > check_end_date ) // && jdate > examDate 
      {
        $("#doj_error").html('Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.'); 
        flag = 0;
        return false;
      }
      else if( jdate > examDate) // && jdate > examDate 
      { 
        //console.log(jdate +'>'+ examDate);
        var formattedbeforeNineMonthDate = formatDateJs(beforeninemonthDate);
        $("#doj_error").html('Only Agents who joined the Bank as BC on or before March 31, 2024, are eligible.');
        //$("#doj_error").html('Commencement of operations / joining as BC to be within 9 months from the date of examination.');
        flag = 0;
        return false;
      }
      else
      {
        $("#doj_error").html('');
        flag = 1;
      }
      
      if(jyear!='' && jyear < minjoinyear )
      {
        //alert("Please select Proper Year of Joining");
        $("#doj_error").html("Please select Proper Year of Joining");
        $("#doj_error").focus();
        flag = 0;
        return false;
      }
      else
      {
        $("#doj_error").html('');
        flag = 1;
      }
    }
    else
    {
      $("#doj_error").html('Please select valid date');
      $("#doj_error").focus();
      flag = 0;
    }
    if(flag==1)
      return true;
    else
      return false;
  }

  function formatDateJs(date) {
      var day = date.getDate();
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                      "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var month = monthNames[date.getMonth()];
    var year = date.getFullYear();

    // Add leading zero to the day if it's less than 10
    day = day < 10 ? '0' + day : day;

    return day + '-' + month + '-' + year;
  }

  function check_bank_bc_id_no(){
    var name_of_bank_bc = $("#name_of_bank_bc").val();
    var ippb_emp_id = $("#ippb_emp_id").val();
    var regnumber = '<?php echo (isset($user_info[0]['regnumber']) && $user_info[0]['regnumber'] != "" ? $user_info[0]['regnumber'] : ''); ?>';
    var datastring='name_of_bank_bc='+name_of_bank_bc+'&ippb_emp_id='+ippb_emp_id+'&mem_type=NM'+'&regnumber='+regnumber;
    $.ajax({
        url:site_url+'Bcbfexam/check_bank_bc_id_no/',
        data: datastring,
        type:'POST',
        async: false,
        success: function(data) {
        if(data != ""){
           $("#ippb_emp_id_error").html(data);
           $("#ippb_emp_id_error").focus();
           return false;
        }else{
          $("#ippb_emp_id_error").html(data);
        }
      }
    });  
  }
</script>

<!-- START: JS CODE FOR IMAGE EDITOR -->
<?php $this->load->view('iibfbcbf/common/inc_lightbox_files'); ?>
<?php $this->load->view('iibfbcbf/common/inc_sweet_alert_files'); ?>
<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
<?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('inc_fileChooser_accepted_files' => $inc_fileChooser_accepted_files, 'page_name'=>'csc_non_mem_reg')); ?>

<script>
  function validate_form_images(input_id) 
  {
    $("#page_loader").show();
     
    if(input_id == 'scannedphoto') { $('#scannedphoto').parsley().reset(); }
    else if(input_id == 'scannedsignaturephoto') { $('#scannedsignaturephoto').parsley().reset(); }
    //else if(input_id == 'idproofphoto') { $('#idproofphoto').parsley().reset(); }
    else if(input_id == 'empidproofphoto') { $('#empidproofphoto').parsley().reset(); }
    else if(input_id == 'declarationform') { $('#declarationform').parsley().reset(); }

    $("#page_loader").hide();
  }

  /*Start: Corporate BC Changes*/
  function check_are_you_corporate_bc(val) {
     if(val == 'Yes'){
      $("#corporate_bc_option_div").show();
      
      $("input[name='corporate_bc_option']").prop('required', true);

     }else{
      $("#corporate_bc_option_div").hide();
      $("#corporate_bc_associated_div").hide();
      $("input[name='corporate_bc_option']").prop('checked', false);
      $("#corporate_bc_associated").val('');
      $("#corporate_bc_validation_message_div").hide();
        
      $("input[name='corporate_bc_option']").prop('required', false); 
     } 
  }

  function check_corporate_bc_option(val) {
     if(val == 'CSC'){
      $("#corporate_bc_option_div").show();
      $("#corporate_bc_associated_div").hide();
      $("#corporate_bc_associated").val('');
      $("#corporate_bc_validation_message_div").show();
      $("input[name='corporate_bc_associated']").prop('required', false);
     }else if(val == 'Other'){
      $("#corporate_bc_associated_div").show();
      $("#corporate_bc_validation_message_div").hide();
      $("input[name='corporate_bc_associated']").prop('required', true);
     }else{
      $("#corporate_bc_associated_div").hide();
      $("#corporate_bc_validation_message_div").hide();
      $("input[name='corporate_bc_associated']").prop('required', false);
     }
  } 
  //$("input[name='are_you_corporate_bc']").prop('checked', false);
  /*End: Corporate BC Changes*/


  /*START: OTP Verificaion*/

  $('#btnPreviewSubmit').click(function() 
  {
    var email_verify_status = $('#email_verify_status').val();
    if(email_verify_status != "yes"){
      $('#email').focus();
      alert('Please verify the email using OTP.');
    }
  });
  
  $('.verify-otp').click(function() 
  {
    var otp         = $('#otp').val();
    var verify_type = $(this).attr('data-verify-type');
    var email     = $('#email').val();
      var type      = 'verify_otp';
      
      var data = {};
      data.email     = email;
      data.otp       = otp;
      data.verify_type = verify_type;
    if (otp != '' && otp != undefined) 
    {
      send_verify_otp(type,data,this)       
    } else {
      alert('Please enter the OTP.');
    } 
  }); 

  /*$('#reset_btn').click(function() {
    $('#email').attr('readonly',false);
    $('#email').val('');
    $('#send_otp_btn').show();
    $('#reset_btn').hide();
    $('.verify-otp-section').hide();
    emailVerify = false;
    $('#email_verify_status').val('no');
    $('#otp').val('');
  });*/   

  function send_verify_otp(type,data,selector) {
    $.ajax({
      type: 'POST',
      url: site_url + 'CSCSpecialMember/send_otp/',
      data : {'email':data.email,'type':type,'otp':data.otp,'verify_type':data.verify_type},
      beforeSend: function(xhr) {
          $(selector).attr('disabled',true).text('Processing..')  
        },
      async: true,
      success: function(otp_response) {
        var json_otp_response = JSON.parse(otp_response);
        if (json_otp_response.status) {
          if (type == 'send_otp') {
            $('#send_otp_btn').hide();
            $('#send_otp_btn').attr('disabled',false).text('Get OTP')
            $('.verify-otp-section').show();
            //$('#reset_btn').show(); 
          } else if (type == 'resend_otp') {
            $(selector).attr('disabled',false).text('Resend OTP')
            $('.verify-otp-section').show();
          } else if (type == 'verify_otp') {
            $(selector).attr('disabled',false).text('Verify OTP')
            $('.verify-otp-section').hide();
            emailVerify = true;
            $('#email_verify_status').val('yes');
            //alert(json_otp_response.msg); 
          }

          $('.email-id').removeClass('parsley-error');
          $('.email-id').addClass('parsley-success');
          $('#email').attr('readonly',true);
          
          alert(json_otp_response.msg);
        } else {
          if (type == 'send_otp') {
            $(selector).attr('disabled',false).text('Get OTP')
          } else if (type == 'resend_otp') { 
            $(selector).attr('disabled',false).text('Resend OTP')
          } else if (type == 'verify_otp') {
            $(selector).attr('disabled',false).text('Verify OTP')
          } 
          alert(json_otp_response.msg); 
        }
        $('#otp').val('');
      }
    });
  } 
   
  var sessionEmail = '<?php echo $_POST['verified_email_val']; ?>';
  $('.send-otp').click(function() {
      var email = $('#email').val();
      var type  = $(this).attr('data-type');
      var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Regular expression for email format
      var stopExecution = false;
      var otpButton = document.getElementById('send_otp_btn');
      //var reset_btn = document.getElementById('reset_btn');

      if(sessionEmail !='' && email !='' && email === sessionEmail) 
      {
          alert("This email has already been verified by you previously. You do not need to verify it again.");
          otpButton.style.display = 'none';
          //reset_btn.style.display = 'block';
          stopExecution = true;
      }

      if (stopExecution) {
        return; // Exit the function if the flag is true
      }
      
      if (type == 'resend_otp') {
        $('#otp').val('');
      }
      var data = {};
      data.email       = email;
      data.otp       = '';
      data.verify_type = '';
        
      if (email.trim() != '') {
          if (emailRegex.test(email)) {
              send_verify_otp(type,data,this)
          } else {
            $('.email-id').addClass('parsley-error');
              $('#email').focus();
              alert('Please enter a valid email address.');
          }
      } else {
        $('.email-id').addClass('parsley-error');
          $('#email').focus();
          alert('Please enter email id first.');
      }
  });
 
  /*END: OTP Verificaion*/
</script>
<!-- END: JS CODE FOR IMAGE EDITOR -->