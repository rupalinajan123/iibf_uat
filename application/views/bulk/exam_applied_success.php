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
	<form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Applyexam/Msuccess/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('mregid_applyexam');?>"> 
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
           <li>On successful completion of transaction confirmation SMS/email will be sent to the candidate intimating the receipt of examination forms.
					</li><li>In case the candidate does not receive confirmation SMS/email from the institute, the candidate should apply again till he receives the confirmation SMS/email.
					</li><li>Please note that Institute will not accept any responsibility in case of failed transactions. However fees debited if any to candidate's account will be refunded within seven working days of the transaction. Candidates need to reapply in such case. 
				</li>
                </ul>
                </div>
                
            </div>
          <div class="box box-info">
            <div class="box-header with-border">
              <!--<h3 class="box-title">Your transaction details are forwarded to your registered e-mail id.</h3>-->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
					<div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $message; ?>
                            </div>
             <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                	<div class="col-sm-1">
                	<?php //echo $applied_exam_info[0]['regnumber'];?>
                	<?php echo $this->session->userdata('regnumber');?>
                    </div>
                </div>
                
                
               
                
                 <?php if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
			  {?>
             	 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Place of Work</label>
                	<div class="col-sm-5 ">
                         <?php echo $this->session->userdata['examinfo']['placeofwork'];?>
                     <div id="error_dob"></div>
                  </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State (Place of Work)</label>
                	<div class="col-sm-5 ">
                     <?php 
				  if(count($states) > 0)
                    {
                        foreach($states as $srow)
                        {
                            if($this->session->userdata['examinfo']['state_place_of_work']==$srow['state_code'])
                            {
                                echo $srow['state_name'];
                            }	
                         }
                    }?>
                </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Pin Code (Place of Work)</label>
                	<div class="col-sm-5 ">
                         <?php echo $this->session->userdata['examinfo']['pincode_place_of_work'];?>
                     <div id="error_dob"></div>
                    </div>
                </div>
              <?php 
			  }?>
                
            
                
                
                
                
                </div>
               <?php 
			   		if($payment_info[0]['status']=='1'){
					if($applied_exam_info[0]['exam_code']!=101
					&& $applied_exam_info[0]['exam_code']!=1010
					&& $applied_exam_info[0]['exam_code']!=10100
					&& $applied_exam_info[0]['exam_code']!=101000
					&& $applied_exam_info[0]['exam_code']!=1010000
					&& $applied_exam_info[0]['exam_code']!=10100000
					&& $applied_exam_info[0]['exam_code']!=996)
					{						
					$this->db->join('admit_exam_master', 'admit_card_details.exm_cd = admit_exam_master.exam_code');
					$this->db->group_by('exm_cd');
					$admit_card_details = $this->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$applied_exam_info[0]['regnumber'],'remark'=>1),'admitcard_image,description',array('admitcard_id'=>'desc'));
					}
						
				?> 
              	  		
			<?php }?>			
                        
                <div class="box-footer">
          <div class="col-sm-2 col-xs-offset-5">
          <a href="<?php echo  base_url()?>bulk/Banklogin/logout/">Logout</a>
          </div>
           <div class="col-sm-2 col-xs-offset-5">
       	   <a href="<?php echo  base_url()?>bulk/BulkApply/Dashboard/">Home</a>
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