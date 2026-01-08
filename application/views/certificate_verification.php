<?php 
   $this->load->view('common_header');
   
   ?>
<style>
  .main-footer {padding-left: 0; padding-right: 0;} 
  .datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-bottom { z-index: 999999999; }
</style>
<div class="container">
<section class="content">
   <?php 
   //print_r($api_res); 
   if(count($api_res)==0) {?>
<section class="content-header">
   <h1 class="register"> 
      Certificate Verification
   </h1>
   <br/>
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
<?php if( count($api_res)==0  && $post_count>0) { ?>
  <div class="alert alert-danger alert-dismissible" id="error_id">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   Certificate details not found
  </div>
<?php } ?>

<div class="box box-info">
   <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  action="">
      <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Membership/Registration no <span style="color:#F00">*</span></label>
         <div class="col-sm-7">
            <input type="text" class="form-control " id="member_no" name="member_no"  placeholder="Membership/Registration no"  value="" required  autocomplete="off">
            <?php if(form_error('member_no')!=""){ ?><label class="error"><?php echo form_error('member_no'); ?></label> <?php } ?>
         </div>
      </div>
       <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Certificate number <span style="color:#F00">*</span></label>
         <div class="col-sm-7">
            <input type="text" class="form-control " id="certificate_number " name="certificate_number" placeholder="Certificate number"  value=""  required autocomplete="off">
            <?php if(form_error('certificate_number')!=""){ ?><label class="error"><?php echo form_error('certificate_number'); ?></label> <?php } ?>
         </div>
      </div>

       <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Certificate date  <span style="color:#F00">*</span></label>
         <div class="col-sm-7">
            <input type="text" class="form-control " id="certificate_date" name="certificate_date" placeholder="Certificate date"  value="" required autocomplete="off" readonly>
            <?php if(form_error('certificate_date')!=""){ ?><label class="error"><?php echo form_error('certificate_date'); ?></label> <?php } ?>
         </div>
      </div>

      <div class="form-group m_t_15">
         <label for="roleid" class="col-sm-3 control-label">Security Code<span style="color:#F00">*</span></label>
         <div class="col-sm-2">
            <input type="text" name="captcha_code" id="captcha_code" required class="form-control " >
            <span class="error" id="captchaid" style="color:#B94A48;"></span>
         </div>
         <div class="col-sm-3">
            <div id="captcha_img"><?php echo $image;?></div>
            <?php if(form_error('captcha_code')!=""){ ?><label class="error"><?php echo form_error('captcha_code'); ?></label> <?php } ?>
         </div>
         <div class="col-sm-2">
            <a href="javascript:void(0);" id="new_captcha" class="forget" >Change Image</a>
            <span class="error"><?php //echo form_error('code');?></span>
         </div>
      </div>
      <div class="form-group">
         <div class="col-sm-3"></div>
         <div class="col-sm-7">
            <input type="submit" class="btn btn-info" name="btn_Submit" id="btn_Submit" value="Get Details">
         </div>
      </div>
   </form>
</div>
<?php } ?>

<?php 
   // print_r($api_res); 
   if(count($api_res) > 0) {?>
<section class="content-header">
   <h1 class="register"> 
      Certificate Verification
   </h1>
   <br/>
</section>
<div class="col-md-12">
<!-- Horizontal Form -->
<div  class ="row">

<div class="alert alert-success alert-dismissible" id="success_id">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  
   Certification verification successful
</div>

<div class="box box-info">
   <form class="form-horizontal" name="usersAddForm"  method="post"  action="">
      <div class="form-group ">
         <label for="roleid" class="col-sm-3 control-label">Membership/Registration no <span style="color:#F00">*</span></label>
         <div class="col-sm-6">
            <input type="text" class="form-control "   value="<?php echo $api_res[0][0]; ?>" readonly  disabled>
           
         </div>
      </div>
       <!-- <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Certificate number <span style="color:#F00">*</span></label>
         <div class="col-sm-6">
            <input type="text" class="form-control "   value="<?php echo $api_res[0][4]; ?>"   readonly disabled>
          
         </div>
      </div> -->

       <!-- <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Certificate date  <span style="color:#F00">*</span></label>
         <div class="col-sm-6">
            <input type="text" class="form-control "  value="<?php echo date( 'd-m-Y',strtotime($api_res[0][3])); ?>"  readonly disabled>
          
         </div>
      </div> -->
      <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Candidate Name </label>
         <div class="col-sm-6">
            <input type="text" class="form-control "  value="<?php echo $api_res[0][1]; ?>"  readonly disabled>
          
         </div>
      </div>
      <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Exam Name  <span style="color:#F00">*</span></label>
         <div class="col-sm-6">
            <input type="text" class="form-control "  value="<?php echo $api_res[0][2]; ?>"  readonly disabled>
          
         </div>
      </div>

      <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Certificate date </label>
         <div class="col-sm-8">
            <input type="text" class="form-control "  value="<?php echo date( 'd-M-Y',strtotime($api_res[0][3])); ?>"  readonly disabled>
          
         </div>
      </div>

       <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Certificate Sr. number</label>
         <div class="col-sm-8">
            <input type="text" class="form-control "   value="<?php echo $api_res[0][4]; ?>"   readonly disabled>
          
         </div>
      </div>


       <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Date of Birth </label>
         <div class="col-sm-6">
            <input type="text" class="form-control "  value="<?php echo date( 'd-m-Y',strtotime($api_res[0][5])); ?>"  readonly disabled>
          
         </div>
      </div>

    

      <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label"> Employee ID </label>
         <div class="col-sm-6">
            <input type="text" class="form-control "  value="<?php echo $api_res[0][6]; ?>"  readonly disabled>
          
         </div>
      </div>

      <?php /*<div class="form-group">
         <label for="roleid" class="col-sm-3 control-label"> ID proof no</label>
         <div class="col-sm-6">
            <input type="text" class="form-control "  value="<?php echo $api_res[0][7]; ?>"  readonly disabled>
          
         </div>
      </div> */ ?>

       <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label"> Email ID</label>
         <div class="col-sm-6">
            <input type="text" class="form-control "  value="<?php echo $api_res[0][8]; ?>"  readonly disabled>
          
         </div>
      </div>

      

      <div class="form-group">
         <div class="col-sm-3"></div>
         <div class="col-sm-7">
            <a href="<?php echo base_url('Bcbf_application_validation') ?>"  class="btn btn-info">Back</a>
         </div>
      </div>
   </form>
</div>
<?php } ?>



<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url();?>js/validation.js?<?php echo time(); ?>"></script> 
<script src="<?php echo base_url()?>js/jquery.validate.min.js"></script>
<script>
$(document).ready(function(){
     $.validator.addMethod("custom_check_member_no_ajax", function(value, element)
        {
          if($.trim(value).length == 0) { return true; }
          else
          {
            var isSuccess = false;
            var parameter = { "member_no":$.trim(value) }
            $.ajax(
            {
              type: "POST",
              url: "<?php echo site_url('certificate_verification/check_member_no_ajax') ?>",
              data: parameter,
              async: false,
              dataType: 'JSON',
              success: function(data)
              {
                if($.trim(data.flag) == 'success')
                {
                  isSuccess = true;
                }
                else
                {
                           refresh_captcha_img();
                  /* $("#val1").val(data.val1_hidden);
                  $("#val2").val(data.val2_hidden);
                  $("#val3").val('');
                  $("#captcha_input_val1").html(data.val1_hidden);
                  $("#captcha_input_val2").html(data.val2_hidden); */
                }
                
                $.validator.messages.custom_check_member_no_ajax = data.response;
              }
            });
            
            return isSuccess;
          }
        }, '');

     $("#usersAddForm").validate({
       onkeyup: false,
       onclick: false,
       onblur: false,
       onfocusout: false,
       rules:
            {
               member_no: { 
                  required : true, 
                  custom_check_member_no_ajax:true 
               },              
               captcha_code: { required : true, remote: { url: "<?php echo site_url('certificate_verification/check_captcha_code_ajax') ?>", type: "post", data: { "session_name": "bcbf_app_cap" } } },       
            },
            messages:
            {
               member_no: { required : "Please enter Membership/Registration No", custom_check_member_no_ajax : "Please enter valid Membership/Registration No" },
               /* val3: { required : "Please enter code" } */
               captcha_code: { required : "Please enter code", remote:"Please enter valid captcha" }
            }
      });


   $('#certificate_date').datepicker(
      { 
         /* todayBtn: "linked", */ 
         keyboardNavigation: true, 
         endDate: '+0d',
         forceParse: true, 
         /* calendarWeeks: true, */ 
         autoclose: true, 
         // format: "yyyy-mm-dd",
         format: "dd-mm-yyyy", 
         /* todayHighlight:true,  */ 
         clearBtn: true,
        // startDate:"2021-09-07",
        // endDate:"2021-09-29"
      });

   	$('#new_captcha').click(function(event){
			event.preventDefault();
   		$.ajax({
   			type: 'POST',
   			url: site_url+'certificate_verification/generatecaptchaajax/',
   			success: function(res)
   			{	
   				if(res!='')
   				{
   					$('#captcha_img').html(res);
   				}
   			}
   		});
      });
   });
   
   
</script>
<?php
   $this->load->view('common_footer');
?>
