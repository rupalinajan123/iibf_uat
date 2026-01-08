<?php $this->load->view('common_header'); ?>
<style>.main-footer {padding-left: 0; padding-right: 0;}</style>
<div class="container">
<section class="content">
<section class="content-header">
   <h1 class="register"> 
      DRA Count
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
   <?php echo $this->session->flashdata('error'); ?>
</div>
<?php } if($this->session->flashdata('success')!=''){ ?>
<div class="alert alert-success alert-dismissible" id="success_id">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <?php echo $this->session->flashdata('success'); ?>
</div>
<?php } 
   if(validation_errors()!=''){?>
<div class="alert alert-danger alert-dismissible" id="error_id">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <?php echo validation_errors(); ?>
</div>
<?php }
   if(@$var_errors!='')
   
   {?>
<div class="alert alert-danger alert-dismissible">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
   <?php echo $var_errors; ?>
</div>
<?php 
   } 
   
   ?> 
<div class="box box-info">
   <form class="form-horizontal" name="userAddForm" id="userAddForm"  method="post"  action="">
      <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Exam Code <span style="color:#F00">*</span></label>
         <div class="col-sm-7">
            <input type="number" class="form-control " id="exam_code" name="exam_code" value="" required  >
            <?php if(form_error('exam_code')!=""){ ?><label class="error"><?php echo form_error('exam_code'); ?></label> <?php } ?>
         </div>
      </div>
      
      <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Exam Period <span style="color:#F00">*</span></label>
         <div class="col-sm-7">
            <input type="number" class="form-control " id="exam_period " name="exam_period"  value=""  required >
            <?php if(form_error('exam_period')!=""){ ?><label class="error"><?php echo form_error('exam_period'); ?></label> <?php } ?>
         </div>
      </div>

      <!-- <div class="form-group">
         <label for="roleid" class="col-sm-3 control-label">Exam Type <span style="color:#F00">*</span></label>
         <div class="col-sm-7">
            <select name="exam_type" id="exam_type" class="form-control" required>
              <option value="">Select Exam</option>
              <option value="0">Normal Exam</option>
              <option value="1">Bulk Exam</option>
            </select>
            <?php if(form_error('exam_type')!=""){ ?><label class="error"><?php echo form_error('exam_type'); ?></label> <?php } ?>
         </div>
      </div> -->

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

<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url()?>js/jquery.validate.min.js"></script>
<script>
$(document).ready(function(){

     $("#userAddForm").validate({
       onkeyup: false,
       onclick: false,
       onblur: false,
       onfocusout: false,
       rules:
            {
               exam_code: { 
                  required : true, 
               },              
               captcha_code: { required : true, remote: { url: "<?php echo site_url('Dwnletter/check_captcha_code_ajax') ?>", type: "post", data: { "session_name": "DRA_APP_COUNT" } } },       
            },
            messages:
            {
               /* val3: { required : "Please enter code" } */
               captcha_code: { required : "Please enter code", remote:"Please enter valid captcha" }
            }
      });



   	$('#new_captcha').click(function(event){
			event.preventDefault();
   		$.ajax({
   			type: 'POST',
   			url: site_url+'Dwnletter/generatecaptchaajax/',
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

