<?php 
	$this->load->view('memapplyexam_dbf/front-header');
	header('Cache-Control: must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

<div class="content-wrapper">  
  <form class="form-horizontal" name="member_conApplication" id="member_conApplication"  method="post"  enctype="multipart/form-data" action="<?php echo site_url('ApplyDbfElearning/comApplication');?>" onsubmit="return check_validation()">
    <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('mregid_applyexam');?>">
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
						</div>
            
						<div class="box-body">
              <?php if($this->session->flashdata('error')!='')
								{	?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $this->session->flashdata('error'); ?> 
								</div>
								<?php } 
								
								if($this->session->flashdata('success')!='')
								{ ?>
								<div class="alert alert-success alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo $this->session->flashdata('success'); ?> 
								</div>
								<?php }
								
								if(validation_errors()!='')
								{	?>
								<div class="alert alert-danger alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<?php echo validation_errors(); ?> 
								</div>
							<?php } ?>
							
              <?php $fee_amount=$grp_code='';?>
              <input type="hidden" name="reg_no" id="reg_no" value="<?php echo $user_info[0]['regnumber'];?>">
              <input type="hidden" name="extype" id="extype" value="<?php echo $examinfo[0]['exam_type'];?>">
              <input type="hidden" id="exname" name="exname"  value=" <?php echo $examinfo[0]['description'];?>">
              <input type="hidden" id="excd" name="excd"  value="<?php echo base64_encode($this->session->userdata('memexcode'));?>">
              <input id="examcode" name="examcode" type="hidden" value="<?php echo $this->session->userdata('memexcode');?>">
              <input id="eprid" name="eprid" type="hidden" value="<?php echo $this->session->userdata('memexprd');?>">
              <input id="fee" name="fee" type="hidden" value="">
							<input id="feeEL" name="feeEL" type="hidden" value="">
              <input type='hidden' name='mtype' id='mtype' value="<?php echo $this->session->userdata('memtype')?>">
              <?php 
								if(isset($examinfo[0]['app_category']))
								{
									$grp_code=$examinfo[0]['app_category'];
								}
								else
								{
									$grp_code='B1_1';
								}; ?>
              <input id="grp_code" name="grp_code" type="hidden" value="<?php echo trim($grp_code);?>">
              
							<div class="form-group">
                <label class="col-sm-3 control-label">First Name </label>
                <div class="col-sm-3"> <?php echo $user_info[0]['firstname'];?> </div>
							</div>
							
              <div class="form-group">
                <label class="col-sm-3 control-label">Middle Name</label>
                <div class="col-sm-5"> <?php echo $user_info[0]['middlename'];?></div>
							</div>
							
              <div class="form-group">
                <label class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-5"> <?php echo $user_info[0]['lastname'];?></div>
							</div>
              
							<input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-editemailcheckexamapply  type="hidden" data-parsley-trigger-after-failure="focusout" >
              <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
						</div>
					</div>
          
					<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
						</div>
            <div class="box-body">
              <div class="form-group">
                <label class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-5 "> <?php echo $examinfo['0']['description'];?></div>
							</div>
							
							<div class="form-group">
                <label class="col-sm-3 control-label">Exam Period</label>
                <div class="col-sm-5 "> <?php echo 'Dec 2020'; ?></div>
							</div>
							
							<div class="form-group">
                <label class="col-sm-3 control-label">Centre Name</label>
                <div class="col-sm-5 "> <?php echo $center['0']['center_name'];?></div>
							</div>
							              
							<?php 
									$subject_cnt = count($compulsory_subjects);
									$subject_cnt_arr = array('subject_cnt'=>$subject_cnt);
									$this->session->set_userdata($subject_cnt_arr);
								?>
								
								<?php /*<div class="form-group">
									<label class="col-sm-12 control-label" style="text-align:center">Please select at least one eLearning subject</label>									
								</div> */ ?>
								
								<?php foreach($compulsory_subjects as $el_subject)
									{ ?>									
									<div class="form-group show_el_subject" >
                    <label class="col-sm-3 control-label"><?php echo $el_subject['sub_dsc']?><span style="color:#F00">*</span></label>
										<div class="col-sm-3">
											<input type="checkbox" name="el_subject[<?php echo $el_subject['sub_cd']?>]" value="Y" checked="checked" class="el_sub_prop" data-parsley-mincheck="1" />
										</div>
									</div>
								<?php } ?>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5 " id="html_fee_id_EL">
										<div style="color:#F00"></div>
										
										<div id="error_dob"></div>
										<br>
										<div id="error_dob_size"></div>
										<span class="dob_proof_text" style="display:none;"></span>
										<span class="error"><?php //echo form_error('idproofphoto');?></span>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Centre Code <span style="color:#F00">*</span></label>
									<div class="col-sm-2">
										<input type="text" name="txtCenterCode" id="txtCenterCode"  class="form-control pull-right" readonly="readonly" value="<?php echo $center['0']['center_code'];?>">
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

<script>
	$(document).ready(function()
	{		
		if($('#hiddenphoto').val()!='')
		{
			$('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());
		}
		
		if($('#hiddenscansignature').val()!='')
		{
			$('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());
		}		
		
		calculate_fees();
		$(".el_sub_prop").click(function() { calculate_fees(); })
		
		function calculate_fees()
		{
			$(".loading").show();
			var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
			var datastring_1='subject_cnt='+el_subject_cnt;
			
			$.ajax(
			{
				url:site_url+'ApplyDbfElearning/set_jaiib_elsub_cnt/',
				data: datastring_1,
				type:'POST',
				async: false,
				success: function(data) { }
			});
			
			var cCode =  document.getElementById('txtCenterCode').value;
			var examType = document.getElementById('extype').value;
			var examCode = document.getElementById('examcode').value;
			var eprid = document.getElementById('eprid').value;
			var excd = document.getElementById('excd').value;
			var extype= document.getElementById('extype').value;
			var mtype= document.getElementById('mtype').value;
			var Eval = 'N'; 
			
			if(document.getElementById('elearning_flag_Y').checked)
			{
				var Eval = document.getElementById('elearning_flag_Y').value;
			}
			
			if(document.getElementById('elearning_flag_N').checked)
			{
				var Eval = document.getElementById('elearning_flag_N').value;
			}
			
			var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&mtype='+mtype+'&elearning_flag='+Eval;
			
			$.ajax({
				url:site_url+'Fee/getFeeEL/',
				data: datastring,
				type:'POST',
				async: false,
				success: function(data) 
				{
					if(data)
					{
						document.getElementById('feeEL').value = data ;
						document.getElementById('html_fee_id_EL').innerHTML =data;
					}
				}
			});
			$(".loading").hide();
		}		
	});
	
	function check_validation()
	{
		var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
		if(el_subject_cnt > 0)
		{
			var total_fee = $("#feeEL").val();
			if(total_fee > 0){ return true; }
			else
			{
				alert("Fee can not be Zero(0)");
				return false;
			}
		}
		else
		{
			alert("Please select at least one eLearning subject");
			return false;
		}
	}
</script> 

<script>	
	function showSelect_scribe_flagY() { $('#myModal').modal('show'); }
	function showSelect_scribe_flagN() { $('#myModal').modal('hide'); }
</script>

<?php $this->load->view('memapplyexam_dbf/front-footer'); ?>