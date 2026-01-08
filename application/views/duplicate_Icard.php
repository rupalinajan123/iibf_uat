  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Duplicate ID Card Request Form</h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="member_dupliatecard" id="member_dupliatecard"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Duplicate/card/">
  
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
             <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                	<div class="col-sm-1">
                	 <?php echo $user_info[0]['regnumber'];?>
                    </div>
                </div>
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Name </label>
                     <div class="col-sm-3">
					<?php
							 $username=$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
							echo $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
							?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">BANK /INSTITUTION NAME</label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['name'];?>
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth</label>
                	<div class="col-sm-5">
                    <?php echo date('d-m-Y',strtotime($user_info[0]['dateofbirth']));?>
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile </label>
                	<div class="col-sm-5">
                      <?php echo $user_info[0]['mobile'];?>
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email </label>
                	<div class="col-sm-5">
                    <?php echo $user_info[0]['email'];?>
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee </label>
                	<div class="col-sm-5">
                    
                    <?php 
						/*change fee as per state */
		$this->db->join('state_master','member_registration.state = state_master.state_code', 'LEFT');
			$member_deatails=$this->master_model->getRecords('member_registration',array('regnumber'=>$this->session->userdata('regnumber'),'isactive'=>'1'),'state_master.state_name,member_registration.state');
			if($member_deatails[0]['state']=='JAM')
			{
				echo $fee=$this->config->item('Dup_Id_apply_fee');
			}else
			{
				echo $fee=$this->config->item('Dup_Id_cs_total');
			}
			
					 ?>  
                  
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>
                
                
                </div>
                
               </div> <!-- Basic Details box closed-->
                 <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Photograph and Signature:</h3>
            </div>
            
         

            <div class="box-body">
                <div class="form-group">
            <label for="roleid" class="col-sm-3 text-center">Photograph of the Candidate :</label>
            <label for="roleid" class="col-sm-7 text-center">Signature of the Candidate :</label>
           </div>
           
                <div class="form-group">
                <label for="roleid" class="col-sm-3 text-center"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('regnumber'),'p');?>" height="100" width="100" ></label>
               <label for="roleid" class="col-sm-7 text-center"><img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('regnumber'),'s');?>" height="100" width="100"></label>
                
                </div>
                
                 
                </div>
               
               </div>
               
               <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Declaration</h3>
            </div>
            
         

            <div class="box-body">
                <div class="form-group">
            <label for="roleid" class="col-sm-12">
            1) I have verified the basic details displayed above and it is correct</br>
            2) I request the Institute to kindly provide me duplicate ID/card due to following reason <span style="color:#F00">*</span>: (Please tick whichever is applicable) :</label>
           </div>
           <div class="form-group">
                <label for="roleid" class="col-sm-0 control-label"> </label>
                	<div class="col-sm-10 col-sm-offset-1" style="z-index:999;">											
                      <input name="optreason" id="optreason" value="mis" type="radio">My Original I-card is lost/misplaced<br>
                     <input name="optreason" id="optreason" value="dam" type="radio"> My Original I-card is torn/damaged<br>
                     <input name="optreason" id="optreason" value="cha" type="radio" required> After Marriage my name has been changed 
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>
               
   </div>
   </div>
	
          <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Declaration</h3>
            </div>

            <div class="box-body">
   
  		    <div class="form-group">
            <label for="roleid" class="col-sm-12">
         		 I hereby declare that, above information is true & correct and I undertake to be responsible for all the cost & consequences if any, arising there.<br>
			  </label>
           </div>
           
           
           <div class="form-group">
            <label for="roleid" class="col-sm-1 control-label"></label>
              <div class="col-sm-10"  style="z-index:999;">
               <input name="optcheck" id="optcheck" value="Y" type="checkbox" required>  I agree 
             </div>
           </div>
                
              </div>
              </div>
                
      <div class="box box-info">
          <div class="box-header with-border">
              <h3 class="box-title">Instructions :</h3>
         	   </div>
            <div class="box-body">
  		    <div>
            <label for="roleid" class="col-sm-12">
           Please contact the MSS Dept. at Mumbai with regard to issuance of Duplicate ID card, if not received within 45 days of successful submission of the application.
			</label>
           </div>
           
           <div>
            <label for="roleid" class="col-sm-12">
           <span style="color:#F00">*</span> Please apply for duplicate I-CARD only if your above details are correct / after your new name / employer name appears in above details.
			</label>
           </div>
           
           <div>
            <label for="roleid" class="col-sm-12">
           <span style="color:#F00">**</span>  For Change in Name / Employer, please contact MSS Dept. of the Institute.
			</label>
           </div>
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-4 text-center">
                <!--     <a href="javascript:void(0);" class="btn btn-info" id="payonline" onclick="javascript:return validate();">Pay Online</a>-->
                    <input type="submit" class="btn btn-info" name="btnDupicate" id="btnDupicate" value="Submit" onclick="javascript:return checkDuplicateIcard()" >
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




 

