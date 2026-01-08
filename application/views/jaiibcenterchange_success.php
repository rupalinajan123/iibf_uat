<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> </h1>
  </section>
  <form class="form-horizontal" >
    <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('regid');?>">
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header --> 
            <!-- form start -->
            <div class="box-body">
              
            </div>
          </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Your transaction details are forwarded to your registered e-mail id.</h3>
            </div>
            <!-- /.box-header --> 
            <!-- form start -->
            <div class="box-body">
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                <div class="col-sm-1"> <?php echo $this->session->userdata('mregnumber_applyexam');?> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-3"> <?php echo $examinfo[0]['description'];?> <span class="error">
                  <?php //echo form_error('firstname');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode</label>
                <div class="col-sm-5">
                  <?php echo 'Online';?>
                  <span class="error">
                  <?php //echo form_error('mobile');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Center</label>
                <div class="col-sm-5">
                  <?php 
					if(isset($center[0]['center_name'])){
						echo $center[0]['center_name'];
					}
					?>
                  <span class="error">
                  <?php //echo form_error('email');?>
                  </span> </div>
              </div>
              
              <div style="text-align:left"> 
                <?php 
				$admitcard_image_name = $this->session->userdata('memexcode')."_".'221'."_".$this->session->userdata('mregnumber_applyexam').".pdf";
				?>
                <a href="<?php echo base_url()?>/uploads/admitcardpdf/<?php echo $admitcard_image_name?>" target="_blank">Download admitcard</a>
              </div>
              
             
              
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>
<!-- Data Tables -->