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
  <form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" >
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
              <ul>
                <li>On successful completion of transaction confirmation SMS/email will be sent to the candidate intimating the receipt of examination forms. </li>
                <li>In case the candidate does not receive confirmation SMS/email from the institute, the candidate should apply again till he receives the confirmation SMS/email. </li>
                <li>Please note that Institute will not accept any responsibility in case of failed transactions. However fees debited if any to candidate's account will be refunded within seven working days of the transaction. Candidates need to reapply in such case. </li>
              </ul>
            </div>
          </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Your admitcard pdf forwarded to your registered e-mail id.</h3>
            </div>
            <!-- /.box-header --> 
            <!-- form start -->
            <div class="box-body">
              <div class="form-group" >
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                <div class="col-sm-1"> <?php echo $admitcard_info[0]['mem_mem_no'];?> </div>
                <span></span>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                <div class="col-sm-6">  
					<?php echo str_replace("\\'","",html_entity_decode($exam_name[0]['description']));?> 
                     <span class="error"><?php //echo form_error('firstname');?></span>
                  </div>
              </div>
              <?php /*?><div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5">  <?php echo $fee_amt[0]['fee_amt']. ' + GST AS APPLICABLE';?>  </div>
              </div><?php */?>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                <div class="col-sm-5">
                  <?php echo $exam_period;?>
                  </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode</label>
                <div class="col-sm-5">
                  <?php echo 'Online'; ?>
				</div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium</label>
                <div class="col-sm-5">
                  <?php echo 'English';?>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Center</label>
                <div class="col-sm-5">
                  <?php echo 'Remote Proctored Exam';?>
                  <span class="error">
                  <?php //echo form_error('email');?>
                  </span> </div>
              </div>
            </div>
            <div style="text-align:left"> 
            <a href="<?php echo base_url()?>/uploads/admitcardpdf/<?php echo $admitcard_info[0]['admitcard_image']?>" target="_blank">Download admitcard</a> 
            <?php
            	if($this->session->userdata['examinfo']['update_exam_code'] == 1002){
					$link = 'https://www.amazon.in/dp/B088FXM796';
				}elseif($this->session->userdata['examinfo']['update_exam_code'] == 1003){
					$link = 'https://www.amazon.in/Micro-Small-Medium-Enterprises-India-ebook/dp/B07Z9CJTDJ/ref=sr_1_1?dchild=1&keywords=9789386394071&qid=1589462229&sr=8-1';
				}elseif($this->session->userdata['examinfo']['update_exam_code'] == 1004){
					$link = 'https://www.amazon.in/dp/B088FXRVKY';
				}
				
			?>
            <a href="<?php echo $link;?>" target="_blank">Ebook</a>
            
            </div>
            <div class="box-footer">
              <div class="col-sm-2 col-xs-offset-5"> <a href="<?php echo  base_url()?>ELApplyexam/logout/">Logout</a> </div>
            </div>
          </div>
          
          <!-- Basic Details box closed--> 
          
        </div>
      </div>
    </section>
</form>
</div>
<!-- Data Tables --> 

