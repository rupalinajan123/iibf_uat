<?php $this->load->view('memapplyexam_dbf/front-header'); ?>

<div class="content-wrapper"> 
  <form class="form-horizontal" >
    <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('regid');?>">
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Your transaction details are forwarded to your registered e-mail id.</h3>
						</div>
            
						<div class="box-body">							
							<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Transaction Details</label>
                <div class="col-sm-1"> <strong style="color:#FF0000;">Transaction Failed</strong></div>
							</div>
							
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                <div class="col-sm-1"> <?php echo $this->session->userdata('mregnumber_applyexam');?> </div>
							</div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-3"> <?php echo $examinfo[0]['description'];?></div>
							</div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode</label>
                <div class="col-sm-5"><?php echo 'Online';?></div>
							</div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Center</label>
                <div class="col-sm-5">
                  <?php 
										if(isset($center[0]['center_name'])){
											echo $center[0]['center_name'];
										}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</form>
</div>


<?php $this->load->view('memapplyexam_dbf/front-footer'); ?>