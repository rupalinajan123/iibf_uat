<?php $this->load->view('memapplyexam_dbf/front-header'); ?>
<div class="content-wrapper">  
  <?php $function = "add_record";	?>
  <form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>ApplyDbfElearning/<?php echo $function;?>/">
    <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('regid');?>">
    <input type="hidden" name="processPayment" id="processPayment" value="1">
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
						</div>
            
						<div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                <div class="col-sm-1"> <?php echo $user_info[0]['regnumber'];?> </div>
							</div>
							
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name </label>
                <div class="col-sm-3"> <?php echo $user_info[0]['firstname'];?></div>
							</div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                <div class="col-sm-5"><?php if($user_info[0]['middlename']!=''){echo $user_info[0]['middlename'];}else{echo '-';}?></div>
							</div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-5"><?php if($user_info[0]['lastname']!=''){echo $user_info[0]['lastname'];}else{echo '-';}?></div>
							</div>
						</div>
					</div>
          
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
						</div>
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-5 "> <?php echo $examinfo['0']['description'];?>
                  <div id="error_dob"></div>
								</div>
							</div>
							
							<div class="form-group">
                <label class="col-sm-3 control-label">Exam Period</label>
                <div class="col-sm-5 "> <?php echo 'Dec 2020'; ?></div>
							</div>
							
							<?php 
								$el_subject_arr = array();
								$el_subject_arr = $this->session->userdata['examinfo']['el_subject'];
								
								if(!empty($el_subject_arr))
								{
									foreach($el_subject_arr as $el_key => $el_subject)
									{
										$get_subject_details = $this->master_model->getRecords('subject_master',array('subject_code'=>$el_key),'subject_code,subject_description');
									?>
									<div class="form-group show_el_subject" >
										<label for="roleid" class="col-sm-3 control-label"><?php echo $get_subject_details[0]['subject_description'];?></label>
										<div class="col-sm-3">
											<?php echo $el_subject;?>
										</div>
									</div>
									<?php }
								} ?>
								
								<div class="form-group">
									<label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5 " id="html_fee_id_EL">
										<div style="color:#F00"></div>
										
										<div id="error_dob"></div>
										
										<div id="col-sm-3"><?php echo $this->session->userdata['examinfo']['feeEL'];?> </div>
									</div>
								</div>
								
								<div class="form-group">
									<label for="roleid" class="col-sm-3 control-label">Centre Name *</label>
									<div class="col-sm-2">
										<?php 
											if(isset($center[0]['center_name']))
											{
												echo $center[0]['center_name'];
											}
										?>
									</div>
								</div>
								<div class="form-group">
									<label for="roleid" class="col-sm-3 control-label">Centre Code *</label>
									<div class="col-sm-2"> <?php echo  $center[0]['center_code']; ?> </div>
								</div>
								<div class="box-footer">
									<div class="col-sm-4 col-xs-offset-3">
										<?php 
											if($function=='saveexam')
											{?>
											<input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Save">
											<?php }
											else if($function=='add_record')
											{
												if($this->config->item('exam_apply_gateway')=='sbi')
												{?>
												<input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Submit">
												<?php 
												}
												else
												{?>
												<input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Submit" onclick="javascript:return validate();">
												<?php 
												}?>
										<?php  }?>
									<a href="javascript:window.history.go(-1);" class="btn btn-info" id="preview">Back</a> </div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</form>
</div>
<?php $this->load->view('memapplyexam_dbf/front-footer'); ?>
