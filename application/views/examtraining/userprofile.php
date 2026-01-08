  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Non-Membership Registration Edit Page 
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="NMProfile" id="NMProfile"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>NonMember/profile/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('nmregid');?>"> 
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
                <label for="roleid" class="col-sm-3 control-label">First Name <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                <?php 
				if($user_info[0]['namesub']=='')
				{?>
                	<select name="sel_namesub" id="sel_namesub" class="form-control" required>
                    <option value="" >Select</option>
                    <option value="Mr."  <?php if($user_info[0]['namesub']=='Mr.'){echo  'selected="selected"';}?>>Mr.</option>
                    <option value="Mrs." <?php if($user_info[0]['namesub']=='Mrs.'){echo  'selected="selected"';}?>>Mrs.</option>
                    <option value="Ms." <?php if($user_info[0]['namesub']=='Ms.'){echo  'selected="selected"';}?>>Ms.</option>
                    <option value="Dr."  <?php if($user_info[0]['namesub']=='Dr.'){echo  'selected="selected"';}?>>Dr.</option>
                    <option value="Prof." <?php if($user_info[0]['namesub']=='Prof.'){echo  'selected="selected"';}?>>Prof.</option>
                    </select>
                    <input type="hidden" name="hidd_sel_namesub" id="hidd_sel_namesub" value="<?php echo $user_info[0]['namesub'];?>">	
               <?php 
				}
				else
				{
					echo $user_info[0]['namesub'];?>
					<input type="hidden" name="sel_namesub" id="sel_namesub" value="<?php echo $user_info[0]['namesub'];?>">	
                    <input type="hidden" name="hidd_sel_namesub" id="hidd_sel_namesub" value="<?php echo $user_info[0]['namesub'];?>">	
				<?php 
				}?>
                </div>
                    
                   <div class="col-sm-2">
				    <?php 
					if($user_info[0]['firstname']=='')
					{?>
                    	<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php echo $user_info[0]['firstname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                        <input type="hidden" name="hidd_firstname" id="hidd_firstname" value="<?php echo $user_info[0]['firstname'];?>">
                     <?php 
					}
					else
					{
							echo $user_info[0]['firstname'];	?>
							<input type="hidden" name="firstname" id="firstname" value="<?php echo $user_info[0]['firstname'];?>">
                        	  <input type="hidden" name="hidd_firstname" id="hidd_firstname" value="<?php echo $user_info[0]['firstname'];?>">
                        
					<?php }?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>
                
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                   
                     <?php 
					if($user_info[0]['firstname']=='')
				{
						if($user_info[0]['middlename']=='')
						{?>
						  <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $user_info[0]['middlename'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
						  <input type="hidden" name="hidd_middlename" id="hidd_middlename" value="<?php echo $user_info[0]['middlename'];?>">
						 
						 <?php 
						}else
						{
						 echo $user_info[0]['middlename'];?>
						 <input type="hidden" name="middlename" id="middlename" value="<?php echo $user_info[0]['middlename'];?>">
						<input type="hidden" name="hidd_middlename" id="hidd_middlename" value="<?php echo $user_info[0]['middlename'];?>">
						<?php 
						}
				}else
				{if($user_info[0]['middlename']=='')
						{?>
						  <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $user_info[0]['middlename'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" readonly="readonly">
						  <input type="hidden" name="hidd_middlename" id="hidd_middlename" value="<?php echo $user_info[0]['middlename'];?>" readonly="readonly">
						 
						 <?php 
						}else
						{
						 echo $user_info[0]['middlename'];?>
						 <input type="hidden" name="middlename" id="middlename" value="<?php echo $user_info[0]['middlename'];?>">
						<input type="hidden" name="hidd_middlename" id="hidd_middlename" value="<?php echo $user_info[0]['middlename'];?>">
						<?php 
						}
					
					}
				?>
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
                    <?php 
					if($user_info[0]['middlename']=='')
					{?>
                    (Max 30 Characters) 
                    <?php 
					}?>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                    <?php //echo $user_info[0]['lastname'];?>
                     <?php 
					 if($user_info[0]['firstname']=='')
				{
						if($user_info[0]['lastname']=='')
						{?>
						  <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name"  value="<?php echo $user_info[0]['lastname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
						  <input type="hidden" name="hidd_lastname" id="hidd_lastname" value="<?php echo $user_info[0]['lastname'];?>">
						<?php 
						 }else
						{
						 echo $user_info[0]['lastname'];?>
						 <input type="hidden" name="lastname" id="lastname" value="<?php echo $user_info[0]['lastname'];?>">
						<input type="hidden" name="hidd_lastname" id="hidd_lastname" value="<?php echo $user_info[0]['lastname'];?>">
						<?php 
						}
				}else
				{
						if($user_info[0]['lastname']=='')
						{?>
						  <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name"  value="<?php echo $user_info[0]['lastname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" readonly="readonly">
						  <input type="hidden" name="hidd_lastname" id="hidd_lastname" value="<?php echo $user_info[0]['lastname'];?>" readonly="readonly">
						<?php 
						 }else
						{
						 echo $user_info[0]['lastname'];?>
						 <input type="hidden" name="lastname" id="lastname" value="<?php echo $user_info[0]['lastname'];?>">
						<input type="hidden" name="hidd_lastname" id="hidd_lastname" value="<?php echo $user_info[0]['lastname'];?>">
						<?php 
						}
				}?>
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div>
                    <?php 
					if($user_info[0]['lastname']=='')
					{?>
                    		(Max 30 Characters)
                    <?php 
					}?>
                </div>
                
              </div>
                
               </div> <!-- Basic Details box closed-->
                 <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Contact Details</h3>
            </div>

<div class="sub_title">
              <h6 class="box-title">Office/Residential Address for communication (Pl do not repeat the name of the Applicant, Only Address to be typed)</h6>
            </div>
                        
            
            <div class="box-body">
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1 <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo $user_info[0]['address1'];?>"  data-parsley-maxlength="30" >
                      <input type="hidden" name="" id="addressline1_hidd" value="<?php echo $user_info[0]['address1'];?>">
                      <span class="error"><?php //echo form_error('addressline1');?></span>
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo $user_info[0]['address2'];?>"  data-parsley-maxlength="30" >
                      <input type="hidden" name="" id="addressline2_hidd" value="<?php echo $user_info[0]['address2'];?>">
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo $user_info[0]['address3'];?>"  data-parsley-maxlength="30" >
                      <input type="hidden" name="" id="addressline3_hidd" value="<?php echo $user_info[0]['address3'];?>">
                      <span class="error"><?php //echo form_error('addressline3');?></span>
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo $user_info[0]['address4'];?>" data-parsley-maxlength="30" >
                        <input type="hidden" name="" id="addressline4_hidd" value="<?php echo $user_info[0]['address4'];?>">
                      <span class="error"><?php //echo form_error('addressline4');?></span>
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="district" name="district" placeholder="District" required value="<?php echo $user_info[0]['district'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                        <input type="hidden" name="" id="district_hidd" value="<?php echo $user_info[0]['district'];?>">
                      <span class="error"><?php //echo form_error('district');?></span>
                    </div>
                    (Max 30 Characters) 
                </div>
                
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $user_info[0]['city'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                        <input type="hidden" name="" id="city_hidd" value="<?php echo $user_info[0]['city'];?>">
                      <span class="error"><?php //echo form_error('city');?></span>
                    </div>
                    (Max 30 Characters) 
                </div>
                
                
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <select class="form-control" id="state" name="state" required >
                        <option value="">Select</option>
                        <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                        <option value="<?php echo $row1['state_code'];?>" 
						<?php if($user_info[0]['state']==$row1['state_code']){echo  'selected="selected"';}?>><?php echo $row1['state_name'];?></option>
                        <?php } } ?>
                      </select>
                     <input type="hidden" name="" id="state_hidd" value="<?php echo $user_info[0]['state'];?>">
                    
      
                    </div>(Max 6 digits) 
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                   
                     <div class="col-sm-2">
                     <input class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required="" value="<?php echo $user_info[0]['pincode'];?>" data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-nonmemcheckpin data-parsley-type="number" autocomplete="off" type="text" data-parsley-trigger-after-failure="focusout">
                     
                        
                         <input type="hidden" name="" id="pincode_hidd" value="<?php echo $user_info[0]['pincode'];?>">
                         <span class="error"><?php //echo form_error('pincode');?></span>
                    </div>
                    
                </div>
              
                 <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                     <?php echo $user_info[0]['dateofbirth'];?>
                     <input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php //echo $user_info[0]['dateofbirth'];?>">
                      <input type="hidden" name="" id="dob_hidd" value="<?php //echo $user_info[0]['dateofbirth'];?>">
                      <span class="error"><?php //echo form_error('dob');?></span>
                    </div>
                </div> -->
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                    <?php if($user_info[0]['dateofbirth']=='' || $user_info[0]['dateofbirth'] == "0000-00-00"){?>
                    	  
                          <input type="hidden" id="dob1" name="dob" required  value="">
                            <input type="hidden" name="dob_hidd" id="dob_hidd" value="<?php echo $user_info[0]['dateofbirth'];?>">
                         <input type="hidden" name="datepicker_hidd" id="datepicker_hidd" value="" />
                    <?php } else {  
							echo date('d-M-Y',strtotime($user_info[0]['dateofbirth']));?>
                      		<!--<input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo $user_info[0]['dateofbirth'];?>">-->
                       <input type="hidden" name="dob_hidd" id="dob_hidd" value="<?php echo $user_info[0]['dateofbirth'];?>">
                       <input type="hidden" name="dob1" id="dob1" value="<?php echo $user_info[0]['dateofbirth'];?>" />
                    <?php } 
                        $min_year = date('Y', strtotime("- 18 year"));
                        $max_year = date('Y', strtotime("- 60 year"));
                    ?>
                    <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                    <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">
                    <span id="dob_error" class="error"></span>
                    </div>
                </div>
                
                
                     <?php 
				if($user_info[0]['gender']!='')
				{?>
                    <div class="form-group">
            	    <label for="roleid" class="col-sm-3 control-label">Gender <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                     <?php if($user_info[0]['gender']=='female'){echo  'Female';}?>
                     <?php if($user_info[0]['gender']=='male'){echo  'Male';}?>
                    </div>
                </div>
                <input type="hidden" name="gender" id="gender" value="<?php echo $user_info[0]['gender'];?>">
               <?php 
				}
				else
				{?>	
                	 <div class="form-group">
              		 <label for="roleid" class="col-sm-3 control-label">Gender <span style="color:#F00">*</span></label>
                	<div class="col-sm-2">
                      <input type="radio" class="minimal cls_gender" id="female"   name="gender"  required value="female" <?php 
					  if($user_info[0]['gender']=='female'){echo  'checked="checked"';}?>>
                     Female
                   <input type="radio" class="minimal cls_gender" id="male"  name="gender"  required value="male" <?php 
					  if($user_info[0]['gender']=='male'){echo  'checked="checked"';}?>>
                     Male
                      <span class="error"><?php //echo form_error('gender');?></span>
                    </div>
                </div>
                
                <input type="hidden" name="" id="gender" value="<?php echo $user_info[0]['gender'];?>">
                <?php 
				}?>
                 <input type="hidden" name="" id="hidd_gender" value="<?php echo $user_info[0]['gender'];?>">
                
                  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification <span style="color:#F00">*</span></label>
                	<div class="col-sm-4">
                      <input type="radio" class="minimal" id="U"   name="optedu"  value="U" <?php if($user_info[0]['qualification']=='U'){echo  'checked="checked"';}?>                      onclick="changedu(this.value)" <?php echo set_radio('optedu', 'U'); ?> required="required">
                        Under Graduate
                        <input type="radio" class="minimal" id="G"   name="optedu"  value="G" <?php if($user_info[0]['qualification']=='G'){echo  'checked="checked"';}?>
                         onclick="changedu(this.value)" <?php echo set_radio('optedu', 'G'); ?>>
                        Graduate
                        <input type="radio" class="minimal" id="P"   name="optedu"  value="P"   <?php if($user_info[0]['qualification']=='P'){echo  'checked="checked"';}?>
                        onclick="changedu(this.value)" <?php echo set_radio('optedu', 'P'); ?>>
                        Post Graduate
                      <span class="error"><?php //echo form_error('optedu');?></span>
                    </div>
                </div>
                  <input type="hidden" name="" id="optedu_hidd" value="<?php echo $user_info[0]['qualification'];?>">
                 
                
               				 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Please specify <span style="color:#F00">*</span></label>
                
              <div class="col-sm-5" <?php if($user_info[0]['specify_qualification']!=''|| $user_info[0]['qualification']!=''){echo 'style="display:none"';}else
			  {echo 'style="display:block"';}?>  id="edu">
       			    	  <select id="eduqual" name="eduqual" class="form-control" <?php if($user_info[0]['specify_qualification']=='' && $user_info[0]['qualification']==''){echo 'required';}?>>
				<option value="" selected="selected">--Select--</option>
				</select>
            </div>
                    
                  
                		<div class="col-sm-5"  <?php if($user_info[0]['qualification']=='U'){echo 'style="display:block"';}else{echo 'style="display:none"';}?> id="UG">
                      <select class="form-control" id="eduqual1" name="eduqual1" <?php if($user_info[0]['qualification']=='U'){echo 'required';}?> >
                        <option value="">--Select--</option>
                        <?php if(count($undergraduate)){
                                foreach($undergraduate as $row1){ 	?>
                        <option value="<?php echo $row1['qid'];?>" <?php if($user_info[0]['specify_qualification']==$row1['qid']){echo  'selected="selected"';}?>><?php echo $row1['name'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php //echo form_error('eduqual1');?></span>
                    <input type="hidden" name="hdd_eduqual1" id="hdd_eduqual1" value="<?php echo $user_info[0]['specify_qualification'];?>">
                    </div>
                    
         			 	<div class="col-sm-5"  <?php if($user_info[0]['qualification']=='G'){echo 'style="display:block"';}else{echo 'style="display:none"';}?> id="GR">
                      <select class="form-control" id="eduqual2" name="eduqual2" <?php if($user_info[0]['qualification']=='G'){echo 'required';}?> >
                        <option value="">--Select--</option>
                        <?php if(count($graduate)){
                                foreach($graduate as $row2){ 	?>
                        <option value="<?php echo $row2['qid'];?>" <?php if($user_info[0]['specify_qualification']==$row2['qid']){echo  'selected="selected"';}?>><?php echo $row2['name'];?></option>
                        <?php } } ?>
                      </select>
                      <span class="error"><?php //echo form_error('eduqual2');?></span>
                       <input type="hidden" name="hdd_eduqual2" id="hdd_eduqual2" value="<?php echo $user_info[0]['specify_qualification'];?>">
                    </div>
                    
       			    	<div class="col-sm-5"  <?php if($user_info[0]['qualification']=='P'){echo 'style="display:block"';}else{echo 'style="display:none"';}?>id="PG">
                      <select class="form-control" id="eduqual3" name="eduqual3" <?php if($user_info[0]['qualification']=='P'){echo 'required';}?>>
                        <option value="">--Select--</option>
                        <?php if(count($postgraduate)){
                                foreach($postgraduate as $row3){ 	?>
                        <option value="<?php echo $row3['qid'];?>" <?php if($user_info[0]['specify_qualification']==$row3['qid']){echo  'selected="selected"';}?>><?php echo $row3['name'];?></option>
                        <?php } } ?>
                      </select>
                      
                      <span class="error"><?php //echo form_error('eduqual3');?></span>
                    </div>
                     <input type="hidden" name="hdd_eduqual3" id="hdd_eduqual3" value="<?php echo $user_info[0]['specify_qualification'];?>">
                </div>
                 <input type="hidden" name="specify_q" id="specify_q" value="<?php echo ceil($user_info[0]['specify_qualification']);?>">
                
            <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    <input class="form-control" id="email" name="email" placeholder="Email" data-parsley-type="email" value="<?php echo $user_info[0]['email'];?>" data-parsley-maxlength="45" required="" data-parsley-nmeditemailcheck  autocomplete="off" type="text" data-parsley-trigger-after-failure="focusout">
                    
                      
                       <input type="hidden" name="" id="email_hidd" value="<?php echo $user_info[0]['email'];?>">
                         <span style="color:#F00;font-size:small;">(Please check correctness of your Email id and Mobile number. Please change the same here if required. Correct/Active E-mail address is mandatory for receipt of Admit Letter and other communication/s through e-mail.)</span>
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>  
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone </label>
                	<div class="col-sm-2">
                      STD Code
                      <input type="text" class="form-control pull-right" id="stdcode"  name="stdcode" placeholder="STD Code"  value="<?php echo $user_info[0]['stdcode'];?>">
                      <input type="hidden" name="" id="stdcode_hidd" value="<?php echo $user_info[0]['stdcode'];?>">
                      <span class="error"><?php //echo form_error('stdcode');?></span>
                    </div>
                    <div class="col-sm-2">
                    Phone No
                      <input type="text" class="form-control pull-right" id="phone"  name="phone" placeholder="Phone No"  data-parsley-minlength="7"
                      data-parsley-type="number" data-parsley-maxlength="12"  value="<?php echo $user_info[0]['office_phone'];?>" data-parsley-trigger-after-failure="focusout">
                        <input type="hidden" name="" id="phone_hidd" value="<?php echo $user_info[0]['office_phone'];?>">
                      <span class="error"><?php //echo form_error('phone');?></span>
                    </div>
                </div>
                 
                 
                 
                  <div class="form-group">
                <label for="roleid" class="col-sm-3  control-label">Mobile <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                      <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  
                      data-parsley-minlength="10" data-parsley-maxlength="10"  value="<?php echo $user_info[0]['mobile'];?>"  data-parsley-nmeditmobilecheck data-parsley-trigger-after-failure="focusout" required>
                      <input type="hidden" name="" id="mobile_hidd" value="<?php echo $user_info[0]['mobile'];?>">
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>  
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Aadhar Card Number<!--<span style="color:#F00">*</span>--></label>
                	<div class="col-sm-5">
                     <?php
					if($user_info[0]['aadhar_card']=='')
					{?>
                        <?php /*?> <input type="text" class="form-control " id="aadhar_card"  name="aadhar_card" placeholder="Aadhar Card Number" 
                         data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12" required 
                         value="<?php echo $user_info[0]['aadhar_card'];?>" data-parsley-trigger-after-failure="focusout">
                         <input type="hidden" id="aadhar_hidd"  name="aadhar_hidd" value="<?php  echo $user_info[0]['aadhar_card']?>"><?php */?>

					<input type="text" class="form-control " id="aadhar_card"  name="aadhar_card" placeholder="Aadhar Card Number" 
                         data-parsley-minlength="12" data-parsley-maxlength="12" data-parsley-type="number" size="12" 
                         value="<?php echo $user_info[0]['aadhar_card'];?>" data-parsley-trigger-after-failure="focusout">
                         <input type="hidden" id="aadhar_hidd"  name="aadhar_hidd" value="<?php  echo $user_info[0]['aadhar_card']?>">
					<?php 
					}
					else
					{
					 echo $user_info[0]['aadhar_card'];?>
					<input type="hidden" id="aadhar_card"  name="aadhar_card" value="<?php  echo $user_info[0]['aadhar_card']?>">
                    <input type="hidden" id="aadhar_card_hidd"  name="aadhar_card_hidd" value="<?php  echo $user_info[0]['aadhar_card']?>">
					<?php 
					}?>
                      <span class="error"><?php //echo form_error('idNo');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 text-center">
                <?php 
				
				$this->db->select('mem_photo,mem_sign,mem_proof');
				$kyc_info = $this->master_model->getRecords('member_kyc', array('regnumber' => $this->session->userdata('nmregnumber')));
				
			   	if(is_file(get_img_name($this->session->userdata('nmregnumber'),'p')))
				{?>
               	 <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('nmregnumber'),'p');?><?php echo '?'.time(); ?>" height="100" width="100" >
                 <?php 
				}else if(count($kyc_info) > 0)
				{
					if($kyc_info[0]['mem_photo']==1)
					{?>
						<img src="<?php echo base_url();?>assets/images/kyc-pending.png<?php echo '?'.time(); ?>" height="100" width="100" >					
					<?php }
					else
					{?>
						<img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
					<?php }
				}
				else
				{?>
                	 <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                <?php 
				}?>
                </label>
               <label for="roleid" class="col-sm-3 text-center">
               <?php 
			   	if(is_file(get_img_name($this->session->userdata('nmregnumber'),'s')))
				{?>
               <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('nmregnumber'),'s');?><?php echo '?'.time(); ?>" height="100" width="100">
               <?php 
				}else if(count($kyc_info) > 0)
				{
					if($kyc_info[0]['mem_sign']==1)
					{?>
						<img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >					
					<?php 
					}
					else
					{?>
						<img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
					<?php 
					}
				}
				else
				{?>
                	 <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                <?php 
				}?>
               </label>
              
                <label for="roleid" class="col-sm-3 text-center">
                <?php
                if(is_file(get_img_name($this->session->userdata('nmregnumber'),'pr')))
				{?>
                <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('nmregnumber'),'pr');?><?php echo '?'.time(); ?>"  height="100" width="100">
                 <?php 
				}else if(count($kyc_info) > 0)
				{
					if($kyc_info[0]['mem_proof']==1)
					{?>
						<img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >					
					<?php 
					}
					else
					{?>
						<img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
					<?php
					 }
				}
				else
				{?>
               		  <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                  <?php 
				}?>
                </label>
                </div>
                
            <div class="form-group">
            <label for="roleid" class="col-sm-3  text-center">Uploaded Photo</label>
            <label for="roleid" class="col-sm-3  text-center">uploaded Signature</label>
            <label for="roleid" class="col-sm-3 text-center">Uploaded ID Proof</label>
         <?php 
		 /*if(!file_exists('./uploads/photograph/'.$user_info[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_info[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_info[0]['idproofphoto']) ||$user_info[0]['scannedphoto']=='' || $user_info[0]['scannedsignaturephoto']=='' ||  $user_info[0]['idproofphoto']=='')
			{*/
			if(!is_file(get_img_name($this->session->userdata('nmregnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('nmregnumber'),'s')) || !is_file(get_img_name($this->session->userdata('nmregnumber'),'p')))
			{?>
            		<label for="roleid" class="col-sm-3  text-center"><a  class="btn btn-warning " href="<?php echo base_url();?>NonMember/editimages/">Edit Images</a></label>
        	<?php 
			}?>
            </div>
            
            <input type="hidden" name="scannedphoto1_hidd" id="scannedphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('nmregnumber'),'p');?>">
            <input type="hidden" name="scannedsignaturephoto1_hidd" id="scannedsignaturephoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('nmregnumber'),'s');?>">
            <input type="hidden" name="idproofphoto1_hidd" id="idproofphoto1_hidd" value="<?php echo get_actual_img_name($this->session->userdata('nmregnumber'),'pr');?>">
            
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your scanned Photograph <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    	<img src="<?php echo base_url();?>uploads/photograph/<?php echo $user_info[0]['scannedphoto'];?>">
                        <input  type="file" class="form-control" name="scannedphoto" id="scannedphoto" required>
                          <div id="error_photo"></div>
                     <br>
                     <div id="error_photo_size"></div>
                     <span class="photo_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedphoto');?></span>
                    </div>
                </div>-->
                
                
                 <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"> Upload your scanned Signature Specimen <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                  		<img src="<?php echo base_url();?>uploads/scansignature/<?php echo $user_info[0]['scannedsignaturephoto'];?>">
                        <input  type="file" class="form-control" name="scannedsignaturephoto" id="scannedsignaturephoto" required>
                    <div id="error_signature"></div>
                     <br>
                     <div id="error_signature_size"></div>
                     
                     <span class="signature_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('scannedsignaturephoto');?></span>
                    </div>
                </div>-->
                
                <?php if($user_info[0]['idproof']!='')
				{?>
           		    <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Select Id Proof <span style="color:#F00">*</span></label>
                    <div class="col-sm-5">
                     <?php if(count($idtype_master) > 0)
                            {
                                foreach($idtype_master as $idrow)
                                {?>
                                        <?php if($user_info[0]['idproof']==$idrow['id']){echo  $idrow['name'];}?>
                              <?php 
                              }
                           }?>	
                           
                        <input type="hidden" name="idproof" id="idproof" value="<?php echo $user_info[0]['idproof'];?>">	
                        <input type="hidden" name="idproof_hidd" id="idproof_hidd" value="<?php echo $user_info[0]['idproof'];?>">	
                      <span class="error"><?php //echo form_error('idproof');?></span>
                    </div>
                </div>
        		 <?php 
				}
				else
				{?>
              	    
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Select Id Proof <span style="color:#F00">*</span></label>
                        <div class="col-sm-5">
                        <?php if(count($idtype_master) > 0)
                        {
                            $i=1;
                            foreach($idtype_master as $idrow)
                            {?>
                               <input name="idproof" value="<?php echo $idrow['id'];?>" type="radio" class="minimal" 
                               <?php if(set_value('idproof')){echo set_radio('idproof', $idrow['id'], TRUE);}?> 
                               <?php if($i==count($idtype_master)){echo 'required';}?>>
                               <?php echo $idrow['name'];?><br>
                       <?php 
                       $i++;}
                       }?>
                          <span class="error"><?php //echo form_error('idproof');?></span>
                    </div>
                </div>
                 <input type="hidden" name="idproof" id="idproof" value="<?php echo $user_info[0]['idproof'];?>">	
                <input type="hidden" name="idproof_hidd" id="idproof_hidd" value="<?php echo $user_info[0]['idproof'];?>">	
                <?php 
				}?>
                
                 
                  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">ID No.<span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                     <?php
					if($user_info[0]['idNo']=='')
					{?>
                        <input type="text" class="form-control " id="idNo"  name="idNo" placeholder="ID No." required value="<?php echo $user_info[0]['idNo'];?>" data-parsley-maxlength="25" data-parsley-pattern="/^[a-zA-Z0-9][a-zA-Z0-9 ]+$/">
                        <input type="hidden" id="idNo_hidd"  name="idNo_hidd" value="<?php  echo $user_info[0]['idNo']?>">
					<?php 
					}
					else
					{
						 echo $user_info[0]['idNo'];?>
						<input type="hidden" id="idNo"  name="idNo" value="<?php  echo $user_info[0]['idNo']?>">
                        <input type="hidden" id="idNo_hidd"  name="idNo_hidd" value="<?php  echo $user_info[0]['idNo']?>">
					<?php 
					}?>
                      <span class="error"><?php //echo form_error('idNo');?></span>
                    </div>
                </div>
                
                
                
                
                
                 
                
                   <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload your id proof <span style="color:#F00">*</span></label>
                	<div class="col-sm-5">
                    <img src="<?php echo base_url();?>uploads/idproof/<?php echo $user_info[0]['idproofphoto'];?>" height="100" width="100">
                        <input  type="file" class="form-control" name="idproofphoto" id="idproofphoto" required>
                     <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                       <span class="dob_proof_text" style="display:none;"></span>
                      <span class="error"><?php //echo form_error('idproofphoto');?></span>
                    </div>
                </div>-->
                
                
               <?php //if($user_info[0]['optnletter']=='Y')
			//   {?>
                     <!--<div class="form-group">
                <label for="roleid" class="col-sm-10 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
            <div class="col-sm-2">
             Yes
               <input type="hidden" name="optnletter" id="optnletter" value="<?php //echo $user_info[0]['optnletter'];?>">
              <span class="error"><?php //echo form_error('optnletter');?></span>
    		        </div>
		   </div>-->
              <?php //}
		//	else
			//{?>
				<!--<div class="form-group">
                <label for="roleid" class="col-sm-10 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
            <div class="col-sm-2">
               <input value="Y" name="optnletter" id="optnletter" checked="" type="radio" <?php //if($user_info[0]['optnletter']=='Y'){echo  'checked="checked"';}?>  >Yes
                <input value="N" name="optnletter" id="optnletter" type="radio"  <?php //if($user_info[0]['optnletter']=='N'){echo  'checked="checked"';}?>>No
           	   <input type="hidden" name="" id="optnletter_hidd" value="<?php //echo $user_info[0]['optnletter'];?>">
              <span class="error"><?php //echo form_error('optnletter');?></span>
    		        </div>
		   </div>-->
			<?php //}
		   ?>
	</div>
             
           
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                   <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Save" onclick="javascript:return checkEditNM();">
	              </div>
           </div>
    	</div>
     </div>
  </div>
     
      <?php if($user_info[0]['idNo']=='')
	  {?>
			<input type="hidden" name="checkblank" id="checkblank" value="0"> 
		<?php 
		}
		else
		{?>
			<input type="hidden" name="checkblank" id="checkblank" value="1"> 
		<?php 
		}?>
      
    </section>
    </form>
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

<script src="<?php echo base_url();?>js/validation.js"></script>
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>


<script>
$(document).ready(function() {
  $('input[type=radio][name=idproof]').change(function() {
       $('#idproof').val(this.value);
	});
	
	 $('input[type=radio][name=gender]').change(function() {
       $('#gender').val(this.value);
	});
	
	
});

	$(document).ready(function() 
	{
		var dtable = $('.dataTables-example').DataTable();
	   
	   //$(".DTTT_button_print")).hide();
	/*$('#datepicker,#doj').datepicker({
       autoclose: true
     });*/
	 
	 <?php if($user_info[0]['dateofbirth']=='' || $user_info[0]['dateofbirth'] == "0000-00-00"){?>
		$(function() {
			$("#dob1").dateDropdowns({
				submitFieldName: 'dob1',
				minAge: 0,
				maxAge:59
			});
			// Set all hidden fields to type text for the demo
			//$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
		});
	<?php } ?>	
		
		$(function() {
			$("#doj1").dateDropdowns({
				submitFieldName: 'doj1',
				minAge: 0,
				maxAge:59
			});
		});	  
		
	 
	});
	
	function editUser(id,roleid,Name,Username,Email){
		$('#id').val(id);
		$('#roleid').val(roleid);
		$('#name').val(Name);
		$('#username').val(Username);
		$('#emailid').val(Email);
		$('#btnSubmit').val('Update');
		$('#roleid').focus();
		$('#password').removeAttr('required');
		$('#confirmPassword').removeAttr('required');
		
	}
	
	function changedu(dval)
	{
	var UGid = document.getElementById('UG');
	var GRid = document.getElementById('GR');
	var PGid = document.getElementById('PG');
	var EDUid = document.getElementById('edu');

	if(dval == 'U')
	{
		$('#eduqual1').attr('required','required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
		
		if(UGid != null) {
		//	alert('UG');
			document.getElementById('UG').style.display = "block";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
	else if(dval == 'G')
	{
		$('#eduqual1').removeAttr('required');;
		$('#eduqual2').attr('required','required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "block";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "none";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	
	}
	else if(dval == 'P')
	{
		$('#eduqual1').removeAttr('required');;
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').attr('required','required');
		$('#eduqual').removeAttr('required');
			
		if(UGid != null) {
			document.getElementById('UG').style.display = "none";
		}
		if(GRid != null) {
			document.getElementById('GR').style.display = "none";
		}
		if(PGid != null) {
			document.getElementById('PG').style.display = "block";	
		}
		if(EDUid != null) {
			document.getElementById('edu').style.display = "none";	
		}
	}
}
	
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
	
//set datepicker on id's
 $("#datepicker,#doj").keypress(function(event) {event.preventDefault();});
});
</script>

<style>
  .pad_top_7_w {
	  width:3%
	 }
	 .sub_title .box-title {
		 font-size:16px;
		 padding-left:15px;
		 color:#078ed1;
		}
  </style>
 
 <?php if(!is_file('./uploads/photograph/'.$user_info[0]['scannedphoto']) || !is_file('./uploads/scansignature/'.$user_info[0]['scannedsignaturephoto']) || !is_file('./uploads/idproof/'.$user_info[0]['idproofphoto']) || $user_info[0]['mobile']=='' || $user_info[0]['email']=='')
			{
				?>
            <script>
		$(document).ready(function(){
				$('#update_profile').modal('show');
				});
			</script>
<?php }?>