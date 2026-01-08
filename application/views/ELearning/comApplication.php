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
	<form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>ELearning/comApplication/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('eregid');?>"> 
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
             
             
             <?php $fee_amount=$grp_code='';?>
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
                                         
             <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                	<div class="col-sm-1">
                	 <?php echo $user_info[0]['regnumber'];?>
                    </div>
                </div>-->
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name </label>
                     <div class="col-sm-3">
					<?php echo $user_info[0]['firstname'];?>
                         <span class="error"></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['middlename'];?>
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['lastname'];?>
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Note :</label>
                     <div class="col-sm-8">
													In order to check your profile details, you need to <a href="<?php echo base_url();?>" target="new"><strong>login</strong></a> to with your membership number and password
                         <span class="error"></span>
                    </div>
                    
                </div>
                
           
                <input type="hidden" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['mobile'];?>"  data-parsley-editmobilecheckexamapply required data-parsley-trigger-after-failure="focusout" > <span class="error"><?php //echo form_error('mobile');?></span>
                <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                
          
                <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-editemailcheckexamapply  type="hidden" data-parsley-trigger-after-failure="focusout" >
                <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
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
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5" id="html_fee_id">
                    <div style="color:#F00">select center first</div>
                        <?php //echo $examinfo['0']['fees'];?>
                        <?php //if($examinfo['0']['fees']==''){echo '-';}else{echo $examinfo['0']['fees'];}?>
                     <div id="error_dob"></div>
                   
                   <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
                
                <?php $elective_sub=0;?>
				 <input type="hidden" name="selSubcode" id="selSubcode" value="">
                 <input type="hidden" name="selSubName1" id="selSubName1" value="">
			    <input type="hidden" name="medium" id="medium" value="<?php echo $medium[0]['medium_code']?>">
                 <input type="hidden" name="selCenterName" id="selCenterName" value="<?php echo $center[0]['center_code']?>">
                    <input type="hidden" name="txtCenterCode" id="txtCenterCode"  class="form-control pull-right"value="<?php echo $center[0]['center_code']?>">
               
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium</label>
                	<div class="col-sm-5 ">
                        <?php echo $medium['0']['medium_description'];?>
                     <div id="error_dob"></div>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>
				<?php /*  code commented on 7 jul 2021 as per client mail 
				<!-- <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">GSTIN No.&nbsp;</label>
                	<div class="col-sm-5 ">
                         <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="GSTIN No." value="<?php echo set_value('gstin_no');?>"  data-parsley-minlength="15" data-parsley-maxlength="15" data-parsley-trigger-after-failure="focusout">
                     <div id="error_dob"></div>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div> -->
				*/ ?>
				
                  <?php 
				/*if(!file_exists('./uploads/photograph/'.$user_info[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_info[0]['scannedsignaturephoto']) ||$user_info[0]['scannedphoto']=='' || $user_info[0]['scannedsignaturephoto']=='')
			{*/
			if(!is_file(get_img_name($this->session->userdata('eregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('eregnumber'),'p')))
			{?>
            	  
                
                   <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Photo</label>
                	<div class="col-sm-5">
                        <input  type="file" class="" name="scannedphoto" id="scannedphoto" required="required">
                    	 <input type="hidden" id="hiddenphoto" name="hiddenphoto">
                    	<div id="error_photo"></div>
                     <br>
                     <div id="error_photo_size"></div>
                     <span class="photo_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedphoto');?></span>
                    </div>
                </div>
                
                   <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Signature</label>
                	<div class="col-sm-5">
                        <input  type="file" class="" name="scannedsignaturephoto" id="scannedsignaturephoto"  required="required">
                         <input type="hidden" id="hiddenscansignature" name="hiddenscansignature">
                    <div id="error_signature"></div>
                     <br>
                     <div id="error_signature_size"></div>
                     
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                    </div>
                </div>
        <?php 
		}?>        
					 <input type="hidden" name="elected_exam_mode" id="elected_exam_mode" value="C">
                        <input type="hidden" name="placeofwork" id="placeofwork" value="">
                       	   <input type="hidden" name="state_place_of_work" id="state" value="">
                        	 <input type="hidden" name="pincode_place_of_work" id="pincode_place_of_work" value="">
                <div class="form-group">
              <div class="col-sm-12">
			<br />
            <span class="error"><?php //echo form_error('gender');?></span>
            </div>
           </div>
               
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                     
                     <!--<a href="javascript:void(0);" class="btn btn-info" onclick="javascript:return member_apply_exam();" id="preview">Preview</a>-->
                     
                       <input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Preview" onclick="javascript : return  member_apply_exam();">
                     
                   <!-- <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save">-->
                     <!-- <button type="reset" class="btn btn-info" name="btnReset" id="btnReset">Reset</button> -->
                     <a href="<?php echo base_url();?>ELearning/examdetails/?ExId=<?php echo base64_encode($this->session->userdata('examcode'));?>" class="btn btn-info" id="preview">Back</a>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <center><strong> <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4></strong></center>
      </div>
      <div class="modal-body">
    <img src="<?php echo base_url()?>assets/images/bullet2.gif"> The candidate should send a scan copy of the DECLARATION as given in the Annexure-I duly completed and to email iibfwzmem@iibf.org.in. Application Form (available in our website) completed to the MSS Department about such requirement for obtaining permission much before the commencement of the examination (This application is required to make suitable arrangements at the examination venue).Candidate is required to follow this procedure for each attempt of examination in case the help of scribe is required. For more details please refer to the guidelines for use of scribe, given in the website.<br /><br />
			<p style="color:#F00">Click here to download the declaration form <a href="http://www.iibf.org.in/documents/Scribe_Guideliness_Rev.pdf" download target="_blank">Scribe_Guideliness_Rev.pdf</a></p>	 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>
<!-- Data Tables -->

<link href="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css" rel="stylesheet">

<!-- Data Tables -->
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>





<script>
	/////get default fee ////
	function getFee(cCode)
	{
		 var subject_array=new Array();  
		document.getElementById('txtCenterCode').value = cCode ;
		var examType = document.getElementById('extype').value;
		var examCode = document.getElementById('examcode').value;
		var temp = document.getElementById("selCenterName").selectedIndex;
		var eprid = document.getElementById('eprid').value;
		var excd = document.getElementById('excd').value;
		var grp_code = document.getElementById('grp_code').value;
		var extype= document.getElementById('extype').value;
		var mtype= document.getElementById('mtype').value;
		var venue_elements=document.getElementsByClassName('venue_cls');
		for (var i=0; i<venue_elements.length; i++) {
			if(venue_elements[i].getAttribute("attr-data")!='' && venue_elements[i].getAttribute("attr-data")!=null)
			{
				subject_array[i]=venue_elements[i].getAttribute("attr-data");
			}
		}
		
		$(".loading").show();
		if(cCode!='')
		{
				var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype;
				$.ajax({
								url:site_url+'Fee/getFee/',
								data: datastring,
								type:'POST',
								async: false,
								success: function(data) {
								 if(data)
								{
									document.getElementById('fee').value = data ;
									document.getElementById('html_fee_id').innerHTML =data;
									//response = true;
								}
							}
						});
				}
		$(".loading").hide();
	}
	
$(document).ready(function(){
		var cCode=$('#selCenterName').val();
		if(cCode!='')
		{
			getFee(cCode);
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

if($('#selSubcode').val()!=0 && $('#selSubcode').val()!='')
{
	$('#selSubName').attr("disabled", true);
}

});


</script>
 
<script>

function showSelect_scribe_flagY() {

$('#myModal').modal('show');
}

function showSelect_scribe_flagN() {

$('#myModal').modal('hide');
}
function showSelect() {

$("#disability").show();

//$('#disability').attr("required","true");
$("#showdept_dropdown_default").show();
$("#Sub_menue").attr("required","true");
$("#disability_value").attr("required","true");
$("#Sub_menue").show();
$("#scribe_flag").removeAttr("disabled");
 
}
function hideSelect() {
$("#showdept_dropdown_default").hide();
$("#disability").hide();
$("#Sub_menue").hide();
$("#showdept_dropdown").hide();
$("#disability_value").removeAttr("required"); 
$("#Sub_menue").removeAttr("required");
$("#disability_value").css('display','block');
$("#Sub_menue").val("");
$("#disability_value").val("");
$("#scribe_flag").attr("disabled","true");
$("#scribe_flag").attr('checked',false);
$("#scribe_flag").attr("required","true");
//$('#disability').removeAttr("required");
//$('#Sub_menue').removeAttr("required")


}


 var base_url = '<?php echo base_url();?>'
   function getsub_menue(deptid)
   {
   			$.ajax({
   		type:"POST",
   		url: base_url+"ELearning/getsub_menue",
   		data:{deptid:deptid},
   		success:function(data){
   			if(data != "")
   			{   
   					$("#showdept_dropdown").show();
   					$("#textTraining_type").text('');
   					$("#textTraining_type").append(data);
				    $("#Sub_menue").attr("required","true");
   				    $("#showdept").hide();
					$("#showdept_dropdown_default").hide();
					
				
   			}
   			else{
				$("#Sub_menue").removeAttribute("required"); 
   				$("#showdept_dropdown").hide();
   				$("#showdept").show();
				
			
   			}
   		}	
   	},"json");
   }
</script>