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
	<form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" action="">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('eregid');?>"> 
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
           <li>On successful completion of transaction confirmation SMS/email will be sent to the candidate intimating the receipt of examination forms.
					</li><li>In case the candidate does not receive confirmation SMS/email from the institute, the candidate should apply again till he receives the confirmation SMS/email.
					</li><li>Please note that Institute will not accept any responsibility in case of failed transactions. However fees debited if any to candidate's account will be refunded within seven working days of the transaction. Candidates need to reapply in such case. 
				</li>
                </div>
                
               </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">  Exam enrollment acknowledgment forwarded to your registered e-mail id.</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            
             <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                	<div class="col-sm-1">
                	<?php echo $applied_exam_info[0]['regnumber'];?>
                    </div>
                </div>
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                     <div class="col-sm-3">
					<?php echo $applied_exam_info[0]['description'];?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5">
                    <?php echo $applied_exam_info[0]['exam_fee'];?>
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                	<div class="col-sm-5">
                      <?php 
                    //$month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4)."-".date('d');
					$month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4);
                    echo date('F',strtotime($month))."-".substr($applied_exam_info['0']['exam_month'],0,-2);
             ?>
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode</label>
                	<div class="col-sm-5">
                      <?php 
					if($applied_exam_info[0]['exam_mode']=='ON')
					{
						echo 'Online';
					}
					else if($applied_exam_info[0]['exam_mode']=='OF')
					{
						echo 'Offline';
					}?>
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium</label>
                	<div class="col-sm-5">
                   <?
                                echo $medium[0]['medium_description'];
                       ?>
                 
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Center</label>
                	<div class="col-sm-5">
                    <?php 
					if(isset($center[0]['center_name']))
					{
								echo $center[0]['center_name'];
					}
					?>
                 
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>
         
         
         <div style="text-align:left">
                        <a href="<?php echo base_url()?>Home/exampdf/">Save as pdf</a> &nbsp; &nbsp;
                      <a href="javascript:void(0);" onclick="print_exam_preview()">Continue</a>
                        </div>
                </div>
                
          <div class="box-footer">
          <div class="col-sm-2 col-xs-offset-5">
          <a href="<?php echo  base_url()?>Login/logout/">Logout</a>
          </div>
           <div class="col-sm-2 col-xs-offset-5">
       	   <a href="<?php echo  base_url()?>Home/Dashboard/">Home</a>
          </div>
           </div>
           
           
           
               </div> 
               
               
               
               <!-- Basic Details box closed-->
                 
  </div>
     
      
      </div>
    </section>
 
  
     </form>
     </div>
<!-- Data Tables -->