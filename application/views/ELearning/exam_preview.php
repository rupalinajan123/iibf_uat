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
    <?php 
	 	/*if($this->session->userdata['examinfo']['fee']=='')
		{$function = "saveexam";	}
        else
        {	$function = "Msuccess";	}*/
		$function = "Msuccess";
    ?>
	<form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>ELearning/<?php echo $function;?>/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('eregid');?>"> 
    <input type="hidden" name="processPayment" id="processPayment" value="1"> 
     
   
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
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                    <?php if($user_info[0]['middlename']!=''){echo $user_info[0]['middlename'];}else{echo '-';}?>
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                    <?php if($user_info[0]['lastname']!=''){echo $user_info[0]['lastname'];}else{echo '-';}?>
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
         
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
                     <?php //echo $this->session->userdata['examinfo']['exname'];?>
                     
                     <?php echo str_replace("\\'","",html_entity_decode($this->session->userdata['examinfo']['exname']));?>
                        
                         <?php echo $this->session->userdata['examinfo']['selected_elect_subname'];?>
                     <div id="error_dob"></div>
                    </div>
                </div>
                
                   
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5 ">
                     <?php echo $this->session->userdata['examinfo']['fee'];?>
                     <div id="error_dob"></div>
                    </div>
                </div>
         
			     <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium *</label>
                	<div class="col-sm-2">
                  <?php 
				  if(count($medium) > 0)
                    {
                        foreach($medium as $mrow)
                        {
                            if($this->session->userdata['examinfo']['medium']==$mrow['medium_code'])
                            {
                                echo $mrow['medium_description'];
                            }	
                         }
                    }?>
                  
                    </div>
                </div>
                <?php /*  code commented on 7 jul 2021 as per client mail 	   
        		<!-- <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">GSTIN No.</label>
                	<div class="col-sm-2">
                  
                  <?php echo $this->session->userdata['examinfo']['gstin_no'];?>
                    </div>
                </div> -->
               */ ?>
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                <!--     <a href="javascript:void(0);" class="btn btn-info" id="payonline" onclick="javascript:return validate();">Pay Online</a>-->
                   <?php if($function=='saveexam')
				   {?>
						  <input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Save">
				<?php }
				 	else if($function=='Msuccess')
				   {
					   			if($this->config->item('exam_apply_gateway')=='sbi')
							{?>
									<input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Pay Online"> 
							<?php 
							}
							else
							{?>
						<input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Pay Online" onclick="javascript:return validate();"> 
							<?php  
							}
					}?>
                   <a href="javascript:window.history.go(-1);" class="btn btn-info" id="preview">Back</a>
                   
                    </div>
              </div>
             </div>
     </div>
  </div>
     
      
      </div>
    </section>
 
  
     </form>
     </div>
<!-- Data Tables -->




 

