<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      Please go through the given detail, correction may be made if necessary.
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data"      action="">
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
           <!-- Basic Details box closed-->
 		<div class="box box-info">
       	 <div class="box-header with-border">
            <div style="float:right;">
            <a href="<?php echo base_url();?>admin/MainController/">Back</a>
            </div>
            </div>
            
            <?php if(count($member_info)){?>
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
                <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($member_info[0]['regnumber'],'p');?><?php echo '?'.time(); ?>" height="100" width="100" ></label>
                <input type="hidden" name="scannedphoto1_hidd" id="scannedphoto1_hidd" value="<?php echo get_actual_img_name($member_info[0]['regnumber'],'p');?>">
                   
               <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($member_info[0]['regnumber'],'s');?><?php echo '?'.time(); ?>" height="100" width="100"></label>
                <input type="hidden" name="scannedsignaturephoto1_hidd" id="scannedsignaturephoto1_hidd" value="<?php echo get_actual_img_name($member_info[0]['regnumber'],'s');?>">
                    
                    <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($member_info[0]['regnumber'],'pr');?><?php echo '?'.time(); ?>"  height="100" width="100"></label>
                    <input type="hidden" name="idproofphoto1_hidd" id="idproofphoto1_hidd" value="<?php echo get_actual_img_name($member_info[0]['regnumber'],'pr');?>">

                    <?php 
                if($member_info[0]['registrationtype']== 'O' && !in_array($member_info[0]['excode'], array(1009))){?>   
                  <!-- Declaration -->
                  <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo base_url();?><?php echo get_img_name($member_info[0]['regnumber'],'declaration');?><?php echo '?'.time(); ?>"  height="100" width="100"></label>
                <?php } ?>
                <input type="hidden" name="declaration_hidd" id="declaration_hidd" value="<?php echo get_actual_img_name($member_info[0]['regnumber'],'declaration');?>">

                </div>  
                   <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Uploaded Photo</label>
            <label for="roleid" class="col-sm-3 control-label">uploaded Signature</label>
            <label for="roleid" class="col-sm-3 control-label">Uploaded ID Proof</label>
            <?php if($member_info[0]['registrationtype']== 'O' && !in_array($member_info[0]['excode'], array(1009))){?>   
              <label for="roleid" class="col-sm-3 control-label">Uploaded Declaration</label>
            <?php } ?>
         	</div>
                
                
                   <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your scanned Photograph *</label>
                	<div class="col-sm-5">
                        <input  type="file" class="form-control" name="scannedphoto" id="scannedphoto"  autocomplete="off">
                    	 <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                    	<div id="error_photo"></div>
                     <br>
                     <div id="error_photo_size"></div>
                     <span class="photo_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedphoto');?></span>
                    </div>
                </div>
                
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> Upload your scanned Signature Specimen *</label>
                	<div class="col-sm-5">
                        <input  type="file" class="form-control" name="scannedsignaturephoto" id="scannedsignaturephoto"  autocomplete="off">
                         <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                    <div id="error_signature"></div>
                     <br>
                     <div id="error_signature_size"></div>
                     
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your id proof *</label>
                	<div class="col-sm-5">
                        <input  type="file" class="form-control" name="idproofphoto" id="idproofphoto">
                         <input type="hidden" id="hiddenidproofphoto" name="hiddenidproofphoto">
                     <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>

                <?php if(in_array($member_info[0]['excode'], array(1009))){ ?>
                  <!-- START: Added for fedai Employment Proof & Declaration Form by Anil S 2024-10-09-->
                  <div class="form-group">
                      <label for="roleid" class="col-sm-3 control-label">Upload your employment proof *</label>
                      <div class="col-sm-5">
                          <input  type="file" class="form-control" name="empidproofphoto" id="empidproofphoto">
                           <input type="hidden" id="hiddenempidproofphoto" name="hiddenempidproofphoto">
                       <div id="error_empidproofphoto"></div>
                       <br>
                       <div id="error_empidproofphoto_size"></div>
                         <span class="empidproofphoto_text" style="display:none;"></span>
                        <span class="error"><?php //echo form_error('empidproofphoto');?></span>
                      </div>
                      <?php if(is_file(get_img_name($member_info[0]['regnumber'], 'empr'))){ ?>
                      <img src="<?php echo base_url('uploads/empidproof/'.$member_info[0]['empidproofphoto'].'?'.time());?>" height="100" width="100" >
                    <?php } ?>
                  </div> 
                  <div class="form-group">
                      <label for="roleid" class="col-sm-3 control-label">Upload your Declaration Form *</label>
                      <div class="col-sm-5">
                          <input  type="file" class="form-control" name="declaration" id="declaration">
                           <input type="hidden" id="hiddendeclaration" name="hiddendeclaration">
                       <div id="error_declaration"></div>
                       <br>
                       <div id="error_declaration_size"></div>
                         <span class="declaration_text" style="display:none;"></span>
                        <span class="error"><?php //echo form_error('declaration');?></span>
                      </div>
                      <?php if(is_file(get_img_name($member_info[0]['regnumber'], 'declaration'))){ ?>
                      <img src="<?php echo base_url('uploads/declaration/'.$member_info[0]['declaration'].'?'.time());?>" height="100" width="100" >
                    <?php } ?>
                  </div> 
                  <!-- END: Added for fedai Employment Proof & Declaration Form by Anil S 2024-10-09-->

                <?php }else if($member_info[0]['registrationtype']== 'O' && !in_array($member_info[0]['excode'], array(1009))){?>   
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your declaration *</label>
                  <div class="col-sm-5">
                        <input  type="file" class="form-control" name="declaration" id="declaration">
                         <input type="hidden" id="hiddendeclaration" name="hiddendeclaration">
                     <div id="error_declaration"></div>
                     <br>
                     <div id="error_declaration_size"></div>
                       <span class="declaration_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('declaration');?></span>
                    </div>
                </div>
                <?php } ?> 

                <?php //if(in_array($member_info[0]['excode'], array(1046))){ ?>
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Upload Bank BC ID Card *</label>
                    <div class="col-sm-5">
                        <input  type="file" class="form-control" name="bank_bc_id_card" id="bank_bc_id_card">
                         <input type="hidden" id="bank_bc_id_card1_hidd" name="bank_bc_id_card1_hidd" value="<?php echo $member_info[0]['bank_bc_id_card']; ?>">
                     <div id="error_bank_bc_id_card"></div>
                     <br>
                     <div id="error_bank_bc_id_card_size"></div>
                        <span class="bank_bc_id_card_text" style="display:none;"></span>
                        <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                    <?php if(is_file(('uploads/empidproof/'.$member_info[0]['bank_bc_id_card']))){ ?>
                    <img src="<?php echo base_url('uploads/empidproof/'.$member_info[0]['bank_bc_id_card'].'?'.time());?>" height="100" width="100" >
                    <?php } ?> 
                </div> 
                <?php //} ?> 

                <div class="form-group">
                <label for="roleid" class="col-sm-2 control-label"> Note</label>
                	<div class="col-sm-9">
                    1. Images format should be in JPG 8bit and size should be minimum 8KB and maximum 50KB.</br>
                    2. Image Dimension of Photograph should be 100(Width) * 120(Height) Pixel only</br>
                    3. Image Dimension of Signature should be 140(Width) * 60(Height) Pixel only</br>
                    4. Image Dimension of ID Proof should be 400(Width) * 420(Height) Pixel only. Size should be minimum 8KB and maximum 300KB.</br>
                    <?php if($member_info[0]['registrationtype']== 'O'){?>   
                    5. Mandatorily upload the Declaration form signed(with stamped) by Branch Manager/HOD. <a style='color:#FF0000;' href=" <?php echo base_url()?>uploads/declaration/DECLARATION.pdf" target="_blank"><strong style="color:#F00; text-decoration:underline">Please click here to PRINT.</strong></a></br> <?php } ?>
                    </br>
                    </div>
                </div>
                
            </div>
             
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">
                    </div>
              </div>
              
            <?php } ?> 
              
             </div>
                
             
             
        </div>
      </div>
     
      
      
    </section>
    </form>
  </div>

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url()?>js/validation.js"></script>
<script type="text/javascript">
  <!--var flag=$('#usersAddForm').parsley('validate');-->

</script>
<script>
$(document).ready(function() 
{
	//$('#usersAddForm').parsley('validate');

});
	
/*function checkEdit()
{
	//if($('#sel_namesub').val() == $('#sel_namesub_hidd').val())
	
	var gender = $('input[name=gender]:checked').val();
	var optedu = $('input[name=optedu]:checked').val();
	var idproof = $('input[name=idproof]:checked').val();
	
	var edu = '<?php echo $regData['qualification']; ?>';
	var qualification = '<?php echo $regData['specify_qualification']; ?>';
	qual_query = '';
	if(edu == 'U')
	{
		if($('#eduqual1').val() == $('#eduqual1_hidd').val())
			var qual_query = true;
		else
			var qual_query =  false;
	}
	else if(edu == 'G')
	{
		if($('#eduqual2').val() == $('#eduqual2_hidd').val())
			var qual_query = true;
		else
			var qual_query =  false;
	}
	else if(edu == 'P')
	{
		if($('#eduqual3').val() == $('#eduqual3_hidd').val())
			var qual_query = true;
		else
			var qual_query =  false;
	}

	if($('#sel_namesub').val() == $('#sel_namesub_hidd').val() && $('#firstname').val() == $('#firstname_hidd').val() && $('#middlename').val() == $('#middlename_hidd').val() && $('#lastname').val() == $('#lastname_hidd').val() && $('#nameoncard').val() == $('#nameoncard_hidd').val() && $('#addressline1').val() == $('#addressline1_hidd').val() && $('#addressline2').val() == $('#addressline2_hidd').val() && $('#addressline3').val() == $('#addressline3_hidd').val() && $('#addressline4').val() == $('#addressline4_hidd').val() && $('#district').val() == $('#district_hidd').val() && $('#city').val() == $('#city_hidd').val()  && $('#state').val() == $('#state_hidd').val() && $('#datepicker').val() == $('#datepicker_hidd').val()  && $('#pincode').val() == $('#pincode_hidd').val()  && gender == $('#gender_hidd').val()  && optedu == $('#optedu_hidd').val() && $('#institutionworking').val() == $('#institutionworking_hidd').val() && 
$('#email').val() == $('#email_hidd').val() && $('#stdcode').val() == $('#stdcode_hidd').val() && $('#phone').val() == $('#phone_hidd').val() && $('#office').val() == $('#office_hidd').val() && $('#mobile').val() == $('#mobile_hidd').val() && idproof == $('#idproof_hidd').val() && $('#idNo').val() == $('#idNo_hidd').val() && $('#optnletter').val() == $('#optnletter_hidd').val() && $('#designation').val().trim() == $('#designation_hidd').val().trim() && qual_query) 
	{
		alert("Please Change atleast One Value");
		return false;
	}
	else
	{
		var flag=$('#usersAddForm').parsley().validate();
		if(flag){
			return true;	//$('#usersAddForm').submit();
		}
		else{
			return false;
		}
	}	
}*/

	
</script> 

<script>
$(function(){
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

 $("#datepicker,#doj").keypress(function(event) {event.preventDefault();});
});
</script>
<?php $this->load->view('admin/includes/footer');?>