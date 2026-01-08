<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> </h1>
    <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>--> 
  </section>
  <form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Applyjaiib/comApplication/">
    <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('mregid_applyexam');?>">
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
                <?php echo $this->session->flashdata('error'); ?> </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
                <?php echo $this->session->flashdata('success'); ?> </div>
              <?php } 
			 if(validation_errors()!=''){?>
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
                <?php echo validation_errors(); ?> </div>
              <?php } 
			 ?>
              <?php $fee_amount=$grp_code='';?>
              <input type="hidden" name="reg_no" id="reg_no" value="<?php echo $user_info[0]['regnumber'];?>">
              <input type="hidden" name="extype" id="extype" value="<?php echo $examinfo[0]['exam_type'];?>">
              <input type="hidden" id="exname" name="exname"  value=" <?php echo $examinfo[0]['description'];?>">
              <input type="hidden" id="excd" name="excd"  value="<?php echo base64_encode($this->session->userdata('memexcode'));?>">
              <input id="examcode" name="examcode" type="hidden" value="<?php echo $this->session->userdata('memexcode');?>">
              <input id="eprid" name="eprid" type="hidden" value="<?php echo $this->session->userdata('memexprd');?>">
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
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name </label>
                <div class="col-sm-3"> <?php echo $user_info[0]['firstname'];?> <span class="error"></span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                <div class="col-sm-5"> <?php echo $user_info[0]['middlename'];?> <span class="error">
                  <?php //echo form_error('middlename');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-5"> <?php echo $user_info[0]['lastname'];?> <span class="error">
                  <?php //echo form_error('lastname');?>
                  </span> </div>
                <!--(Max 30 Characters) --> 
              </div>
              <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-editemailcheckexamapply  type="hidden" data-parsley-trigger-after-failure="focusout" >
              <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
            </div>
          </div>
          <!-- Basic Details box closed-->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-5 "> <?php echo $examinfo['0']['description'];?>
                  <div id="error_dob"></div>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('idproofphoto');?>
                  </span> </div>
              </div>
              <div class="form-group" style="display:none">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                <div class="col-sm-5" id="html_fee_id">
                  <div style="color:#F00">select center first</div>
                  <?php //echo $examinfo['0']['fees'];?>
                  <?php //if($examinfo['0']['fees']==''){echo '-';}else{echo $examinfo['0']['fees'];}?>
                  <div id="error_dob"></div>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('idproofphoto');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                <div class="col-sm-5 "> <?php echo 'DEC 2020'; ?>
                  <?php //echo $this->db->userdata['enduserinfo']['eprid'];?>
                  <div id="error_dob"></div>
                  <div id="error_dob_size"></div>
                  <span class="dob_proof_text" style="display:none;"></span> <span class="error">
                  <?php //echo form_error('idproofphoto');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Centre Name <span style="color:#F00">*</span></label>
                <div class="col-sm-4">
                  <select name="selCenterName" id="selCenterName" class="form-control" required onchange="valCentre(this.value);">
                    <option value="">Select</option>
                    <?php 
						if(count($center) > 0){
							foreach($center as $crow){
					?>
                    <option value="<?php echo $crow['center_code']?>" class=<?php echo $crow['exammode'];?> <?php if($crow['center_code'] == $old_center){?>selected="selected"<?php }?> ><?php echo $crow['center_name']?></option>
                    <?php }
					}?>
                  </select>
                </div>
              </div>
              <?php  
			  		echo '<pre>';
					print_r($compulsory_subjects);
				   if(count($compulsory_subjects) > 0 && $this->session->userdata('examcode')!=101)
				   {
					   $i=1;
					   foreach($compulsory_subjects as $subject)
					   {
						    $this->db->where('exam_date',$subject['exam_date']);
							$this->db->where('center_code',$old_center);
							$this->db->where('venue_code',$subject['venueid']);
							$venue = $this->master_model->getRecords('venue_master','','venue_name');
							
							echo $this->db->last_query();
							
							
			 ?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"><?php echo $subject['sub_dsc']?><span style="color:#F00">*</span></label>
                <div class="col-sm-2">
                  <label for="roleid" class="col-sm-3 control-label">Venue<span style="color:#F00">*</span></label>
                  <select name="venue[<?php echo $subject['sub_cd']?>]" id="venue_<?php echo $i;?>" class="form-control venue_cls" required  onchange="venue(this.value,'date_<?php echo $i;?>','time_<?php echo $i;?>','<?php echo $subject['sub_cd']?>','seat_capacity_<?php echo $i;?>');" attr-data='<?php echo $subject['sub_cd']?>'>
                    <option value="<?php echo $subject['venueid']?>"><?php echo $venue[0]['venue_name']?></option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label for="roleid" class="col-sm-3 control-label">Date<span style="color:#F00">*</span></label>
                  <select name="date[<?php echo $subject['sub_cd']?>]" id="date_<?php echo $i;?>" class="form-control date_cls" required  onchange="date(this.value,'venue_<?php echo $i;?>','time_<?php echo $i;?>');">
                    <option value="<?php echo $subject['exam_date']?>"><?php echo $subject['exam_date']?></option>
                  </select>
                </div>
                <div class="col-sm-2">
                  <label for="roleid" class="col-sm-3 control-label">Time<span style="color:#F00">*</span></label>
                  <select name="time[<?php echo $subject['sub_cd']?>]" id="time_<?php echo $i;?>" class="form-control time_cls" required onchange="time(this.value,'venue_<?php echo $i;?>','date_<?php echo $i;?>','seat_capacity_<?php echo $i;?>');">
                    <option value="<?php echo $subject['time']?>"><?php echo $subject['time']?></option>
                  </select>
                </div>
                <label for="roleid" class="col-sm-0 control-label">Seat(s) Available<span style="color:#F00">*</span></label>
                <div id="seat_capacity_<?php echo $i;?>"> - </div>
              </div>
              <?php $i++;} }  ?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Centre Code <span style="color:#F00">*</span></label>
                <div class="col-sm-2">
                  <input type="text" name="txtCenterCode" id="txtCenterCode"  class="form-control pull-right" readonly="readonly" value="">
                  <input type="hidden" id="elearning_flag_Y" value="N" />
                  <input type="hidden" id="elearning_flag_N" value="N" />
                </div>
              </div>
              <div class="box-footer">
                <div class="col-sm-4 col-xs-offset-3">
                  <input type="submit" class="btn btn-info" name="btnPreviewSubmit" id="btnPreviewSubmit" value="Preview" onclick="javascript : return  member_apply_exam();">
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
<div class="modal fade" id="myModal_EL" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center>
          <strong>
          <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4>
          </strong>
        </center>
      </div>
      <div class="modal-body"> <img src="<?php echo base_url()?>assets/images/bullet2.gif"> You have opted for e-learning. Login credentials will be provided to you. In case, you do not receive the credentials within three days, please also check your spam folder. If you have still not received the said credentials within three days after registering for the e-learning, please send a mail to care@iibf.org.in.<br />
        <br />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center>
          <strong>
          <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4>
          </strong>
        </center>
      </div>
      <div class="modal-body"> 
        Dear Candidate,<br>
        <br>
        <p> You have opted for the services of a scribe for the above mentioned examination under <strong>Remote Proctored mode</strong>.<br>
          <br>
          For the purpose of approving the scribe and to give you extra time as per rules, you are requested to email Admit letter, Details of the scribe, Declaration and Relevant Doctor's Certificates to <strong>suhas@iibf.org.in / amit@iibf.org.in</strong> at least one week before the exam date<br>
          <br>
          Your application for scribe will be scrutinized and an email will be sent 1-2 days before the exam date, mentioning the status of acceptance of scribe.<br>
          <br>
          You will be required to produce the print out of permission granted, required documents along with the Admit Letter to the test conducting authority (procter).<br>
          <br>
        </p>
        <p style="color:#F00">Click Here - <a href="http://www.iibf.org.in/documents/Scribe_Guideliness_R-150219.pdf" target="_blank">GENERAL GUIDELINES/RULES FOR USING SCRIBE BY VISUALLY IMPAIRED & ORTHOPEADICALLY CHALLENGED CANDIDATES</a><br>
        </p>
        Regards,<br>
        IIBF Team.<br>
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
	
/*function showSelect() {

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


}*/


 var base_url = '<?php echo base_url();?>'
   function getsub_menue(deptid)
   {
   			$.ajax({
   		type:"POST",
   		url: base_url+"Applyexam/getsub_menue",
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