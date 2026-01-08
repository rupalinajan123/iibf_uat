<?php $this->load->view('admin/includes/header');?>
<?php $this->load->view('admin/kyc/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Ordinary Membership Registration Admin Edit Page
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data">
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
             
             	<input type="hidden" name="regid" id="regid" value="<?php echo $regData['regid']; ?>" />
                <input type="hidden" name="regtype" id="regtype" value="<?php echo $regData['registrationtype']; ?>" />
             
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name *</label>
                	<div class="col-sm-2">
                    <select name="sel_namesub" id="sel_namesub" class="form-control" required>
                    <option value="MR." <?php if($regData['namesub']=='MR.'){echo "selected='selected'";} ?>>MR.</option>
                    <option value="MRS." <?php if($regData['namesub']=='MRS.'){echo "selected='selected'";} ?>>MRS.</option>
                    <option value="MS." <?php if($regData['namesub']=='MS.'){echo "selected='selected'";} ?>>MS.</option>
                    <option value="DR." <?php if($regData['namesub']=='DR.'){echo "selected='selected'";} ?>>DR.</option>
                    <option value="PROF." <?php if($regData['namesub']=='PROF.'){echo "selected='selected'";} ?>>PROF.</option>
                    </select>
                    <input type="hidden" name="sel_namesub_hidd" id="sel_namesub_hidd" value="<?php echo $regData['namesub'];?>" /> 
                    </div>(Max 30 Characters) 
                    
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" value="<?php echo $regData['firstname'];?>" required data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                          <input type="hidden" name="firstname_hidd" id="firstname_hidd" value="<?php echo $regData['firstname'];?>" /> 
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Middle Name"  value="<?php echo $regData['middlename'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                      <input type="hidden" name="middlename_hidd" id="middlename_hidd" value="<?php echo $regData['middlename'];?>" /> 
                    </div>(Max 30 Characters) 
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name"  value="<?php echo $regData['lastname'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                       <input type="hidden" name="lastname_hidd" id="lastname_hidd" value="<?php echo $regData['lastname'];?>" /> 
                    </div>(Max 30 Characters) 
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Name as to appear on Card *</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="nameoncard" name="nameoncard" placeholder="Name as to appear on Card" value="<?php echo $regData['displayname'];?>"  data-parsley-maxlength="35" >
                       <input type="hidden" name="nameoncard_hidd" id="nameoncard_hidd" value="<?php echo $regData['displayname'];?>" /> 
                    </div>(Max 35 Characters) 
                </div>
                
                </div>
                
               </div> <!-- Basic Details box closed-->
                 <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Contact Details</h3>
            </div>

<div class="box-header with-border">
              <h6 class="box-title">Office/Residential Address for communication (Pl do not repeat the name of the Applicant, Only Address to be typed)</h6>
            </div>
                        
            
            <div class="box-body">
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line1 *</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" value="<?php echo $regData['address1'];?>"  data-parsley-maxlength="30" >
                      <input type="hidden" name="addressline1_hidd" id="addressline1_hidd" value="<?php echo $regData['address1'];?>" /> 
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line2</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo $regData['address2'];?>"  data-parsley-maxlength="30" >
                       <input type="hidden" name="addressline2_hidd" id="addressline2_hidd" value="<?php echo $regData['address2'];?>" /> 
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line3</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo $regData['address3'];?>"  data-parsley-maxlength="30" >
                       <input type="hidden" name="addressline3_hidd" id="addressline3_hidd" value="<?php echo $regData['address3'];?>" /> 
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Address line4</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo $regData['address4'];?>" data-parsley-maxlength="30" >
                       <input type="hidden" name="addressline4_hidd" id="addressline4_hidd" value="<?php echo $regData['address4'];?>" /> 
                    </div>
                    (Max 30 Characters) 
                    
                    
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District *</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="district" name="district" placeholder="District" required  value="<?php echo $regData['district'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >
                      <input type="hidden" name="district_hidd" id="district_hidd" value="<?php echo $regData['district'];?>" /> 
                    </div>
                    (Max 30 Characters) 
                </div>
                
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City *</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="city" name="city" placeholder="City" required  value="<?php echo $regData['city'];?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z- ]+$/" data-parsley-maxlength="30" >
                       <input type="hidden" name="city_hidd" id="city_hidd" value="<?php echo $regData['city'];?>" />
                    </div>
                    (Max 30 Characters) 
                </div>
                
                
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State *</label>
                	<div class="col-sm-2">
                    <select class="form-control" id="state" name="state"   >
                        <option value="">Select</option>
                        <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                        <option value="<?php echo $row1['state_code'];?>" <?php if($regData['state']==$row1['state_code']){echo "selected='selected'";} ?>><?php echo $row1['state_name'];?></option>
                        <?php } } ?>
                      </select>
                     <input type="hidden" name="state_hidd" id="state_hidd" value="<?php echo $regData['state'];?>" /> 
                    <input hidden="statepincode" id="statepincode" value="<?php echo $regData['state'];?>">
      
                    </div>(Max 6 digits) 
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode *</label>
                   
                     <div class="col-sm-2">
                        <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode"   value="<?php echo $regData['pincode'];?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpin data-parsley-type="number"> 
                          <input type="hidden" name="pincode_hidd" id="pincode_hidd" value="<?php echo $regData['pincode'];?>" /> 
                    </div>
                    
                </div>
              
                 <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of Birth *</label>
                	<div class="col-sm-2">
                      <input type="text" class="form-control pull-right" id="datepicker"  name="dob" placeholder="Date of Birth" required value="<?php echo date('Y-m-d',strtotime($regData['dateofbirth']));?>">
                       <input type="hidden" name="datepicker_hidd" id="datepicker_hidd" value="<?php echo date('Y-m-d',strtotime($regData['dateofbirth']));?>" /> 
                    </div>
                </div>-->
                
                <div class="form-group">
                	<label for="roleid" class="col-sm-3 control-label">Date of Birth *</label>
                	<div class="col-sm-5 example">
                        <?php if($regData['dateofbirth'] != "0000-00-00" && $regData['dateofbirth']!= ""){ ?>
                        <input type="hidden" id="dob1" name="dob" required  value="<?php echo date('Y-m-d',strtotime($regData['dateofbirth']));?>">
                        <input type="hidden" name="datepicker_hidd" id="datepicker_hidd" value="<?php echo date('Y-m-d',strtotime($regData['dateofbirth']));?>"
                        <?php } else{?>
                        <input type="hidden" id="dob1" name="dob" required  value="">
                        <input type="hidden" name="datepicker_hidd" id="datepicker_hidd" value=""  />
                        <?php } ?>
            
                        <?php 
                            $min_year = date('Y', strtotime("- 18 year"));
                            $max_year = date('Y', strtotime("- 60 year"));
                        ?>
                        <input type='hidden' name='minyear' id='minyear' value="<?php echo $min_year; ?>">
                        <input type='hidden' name='maxyear' id='maxyear' value="<?php echo $max_year; ?>">
                        <span id="dob_error" class="error"></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Gender *</label>
                	<div class="col-sm-2">
                      <input type="radio" class="minimal gender_chk cls_gender" id="female"  name="gender"  required  value="female" <?php if($regData['gender']=='female'){echo "checked='checked'";}?>>
                     Female
                   <input type="radio" class="minimal gender_chk cls_gender" id="male"   name="gender" required   value="male" <?php if($regData['gender']=='male'){echo "checked='checked'";}?>>
                     Male
                       <input type="hidden" name="gender_hidd" id="gender_hidd" value="<?php echo $regData['gender']; ?>" /> 
                    </div>
                </div>
                
                
                  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Qualification *</label>
                	<div class="col-sm-5">
                      <input type="radio" class="minimal optedu_chk" id="U"   name="optedu"   value="U" onclick="changedu(this.value)" <?php if($regData['qualification']=='U'){echo "checked='checked'";}?>>
                        Under Graduate
                        <input type="radio" class="minimal optedu_chk" id="G"   name="optedu"  value="G" onclick="changedu(this.value)" <?php if($regData['qualification']=='G'){echo "checked='checked'";}?>>
                        Graduate
                        <input type="radio" class="minimal optedu_chk" id="P"   name="optedu"  value="P"   onclick="changedu(this.value)" <?php if($regData['qualification']=='P'){echo "checked='checked'";}?>>
                        Post Graduate
                      <input type="hidden" name="optedu_hidd" id="optedu_hidd" value="<?php echo $regData['qualification']; ?>" /> 
                    </div>
                </div>
                 
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Please specify *</label>
                
              <div class="col-sm-5"  id="edu">
           	  <select id="eduqual" name="eduqual" class="form-control" <?php /*  if(!set_value('eduqual1') && !set_value('eduqual2') && !set_value('eduqual3')){echo 'required';} */?>>
				<option value="" selected="selected">--Select--</option>
				</select>
            </div>
                    
                    
                	<div class="col-sm-5" id="UG">
                      <select class="form-control" id="eduqual1" name="eduqual1"  >
                        <option value="">--Select--</option>
                        <?php if(count($undergraduate)){
                                foreach($undergraduate as $row1){ 	?>
                        <option value="<?php echo $row1['qid'];?>" ><?php echo $row1['name'];?></option>
                        <?php } } ?>
                      </select>
                       <input type="hidden" name="eduqual1_hidd" id="eduqual1_hidd" value="" /> 
                    </div>
                    
                    <div class="col-sm-5" id="GR">
                      <select class="form-control" id="eduqual2" name="eduqual2"  >
                        <option value="">--Select--</option>
                        <?php if(count($graduate)){
                                foreach($graduate as $row2){ 	?>
                        <option value="<?php echo $row2['qid'];?>" ><?php echo $row2['name'];?></option>
                        <?php } } ?>
                      </select>
                       <input type="hidden" name="eduqual2_hidd" id="eduqual2_hidd" value="" /> 
                    </div>
                    
                    
                    <div class="col-sm-5" id="PG">
                      <select class="form-control" id="eduqual3" name="eduqual3" >
                        <option value="">--Select--</option>
                        <?php if(count($postgraduate)){
                                foreach($postgraduate as $row3){ 	?>
                        <option value="<?php echo $row3['qid'];?>" ><?php echo $row3['name'];?></option>
                        <?php } } ?>
                      </select>
                       <input type="hidden" name="eduqual3_hidd" id="eduqual3_hidd" value="" /> 
                    </div>
                </div>
                
                
                
              
              <div class="form-group">
             <label for="roleid" class="col-sm-3 control-label">Bank/Institution working *</label>
              <div class="col-sm-5"  style="display:block" id="edu">
           	  <select id="institutionworking" name="institutionworking" class="form-control"  >
				 <option value="">--Select--</option>
                 <?php if(count($institution_master)){
                                foreach($institution_master as $institution_row){ 	?>
                        <option value="<?php echo $institution_row['institude_id'];?>" <?php if($regData['associatedinstitute']==$institution_row['institude_id']){echo "selected='selected'";} ?>><?php echo $institution_row['name'];?></option>
                        <?php } } ?>
				</select>
                <input type="hidden" name="institutionworking_hidd" id="institutionworking_hidd" value="<?php echo $regData['associatedinstitute'];?>" /> 
            </div>
         </div>
                
         <?php 
         	$branch = '';
			$branch_name = '';
			if($regData['editedon'] < "2016-12-29 00:00:00")
			{
				$branch = $regData['branch'];
			}
			else if($regData['editedon'] >= "2016-12-29")
			{
				if(is_numeric($regData['office']))
				{
					if($regData['branch']!='')
						$branch = $regData['branch'];
					else
						$branch = $regData['office'];
				}
				else
				{
					if($regData['branch']!='')
						$branch = $regData['branch'];
					else
						$branch = $regData['office'];
				}
			}
			
         ?>
         
         <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Branch/Office *</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="office" name="office" placeholder="Branch/Office"   value="<?php echo $branch;?>"  data-parsley-maxlength="20" >
                  <input type="hidden" name="office_hidd" id="office_hidd" value="<?php echo $branch;?>" /> 
                </div>
                (Max 20 Characters) 
            </div>
                
                
                
                <div class="form-group">
             <label for="roleid" class="col-sm-3 control-label">Designation *</label>
              <div class="col-sm-5"  style="display:block" id="edu">
           	  <select id="designation" name="designation" class="form-control"  >
				 <option value="">--Select--</option>
                 <?php if(count($designation)){
                                foreach($designation as $designation_row){ 	?>
                        <option value="<?php echo $designation_row['dcode'];?>" <?php if($regData['designation']==$designation_row['dcode']){echo "selected='selected'";} ?>><?php echo $designation_row['dname'];?></option>
                        <?php } } ?>
				</select>
                 <input type="hidden" name="designation_hidd" id="designation_hidd" value="<?php echo $regData['designation']; ?> " /> 
            </div>
         </div>
                
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of joining Bank/Institution  *</label>
                	<div class="col-sm-3">
                      <input type="text" class="form-control pull-right" id="doj"  name="doj" placeholder="Date of joining Bank/Institution" required value="<?php echo date('Y-m-d',strtotime($regData['dateofjoin']));?>">
                       <input type="hidden" name="doj_hidd" id="doj_hidd" value="<?php echo date('Y-m-d',strtotime($regData['dateofjoin']));?>" /> 
                    </div>
                </div>-->
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Date of joining Bank/Institution  *</label>
                	<div class="">
                      <!--<input type="text" class="form-control pull-right" id="doj"  name="doj" placeholder="Date of joining Bank/Institution" required value="<?php echo set_value('doj');?>" >-->
                      
                    <div class="col-sm-5 example">
                     <?php if($regData['dateofjoin'] != "0000-00-00" && $regData['dateofjoin'] != ""){ ?>
                        <input type="hidden" id="doj1" name="doj" value="<?php echo date('Y-m-d',strtotime($regData['dateofjoin']));?>">
                        <input type="hidden" name="doj_hidd" id="doj_hidd" value="<?php echo date('Y-m-d',strtotime($regData['dateofjoin']));?>" />
                     <?php }else{ ?>
                        <input type="hidden" id="doj1" name="doj" value="">
                        <input type="hidden" name="doj_hidd" id="doj_hidd" value="" />
                     <?php } ?>
                    </div>
                     <span id="doj_error" class="error"></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email *</label>
                	<div class="col-sm-5">
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo $regData['email'];?>"  data-parsley-maxlength="45" required   data-parsley-editemailcheckadmin />
                      (Enter valid and correct email ID to receive communication)
                       <input type="hidden" name="email_hidd" id="email_hidd" value="<?php echo $regData['email'];?>" /> 
                    </div>
                </div>  
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone </label>
                	<div class="col-sm-2">
                      <label for="roleid" class="col-sm-6 control-label labelleft">STD Code</label>
                      <input type="text" class="form-control w50" id="stdcode"  name="stdcode" placeholder="STD Code"  value="<?php echo $regData['stdcode'];?>">
                      <input type="hidden" name="stdcode_hidd" id="stdcode_hidd" value="<?php echo $regData['stdcode'];?>" /> 
                    </div>
                    <div class="col-sm-3">
                    <label for="roleid" class="col-sm-3 control-label labelleft">Phone No</label>
                      <input type="text" class="form-control w50" id="phone"  name="phone" placeholder="Phone No"  data-parsley-minlength="7"
                      data-parsley-type="number" data-parsley-maxlength="12"    value="<?php echo $regData['office_phone'];?>">
                      <input type="hidden" name="phone_hidd" id="phone_hidd" value="<?php echo $regData['office_phone'];?>" /> 
                    </div>
                </div>
                 
                 
                 
                  <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile *</label>
                	<div class="col-sm-5">
                      <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number" required data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $regData['mobile'];?>"   >
                       <input type="hidden" name="mobile_hidd" id="mobile_hidd" value="<?php echo $regData['mobile'];?>" /> 
                    </div>
                </div>  
                
                
                <div class="form-group">
                	 <label for="roleid" class="col-sm-3 control-label"></label>
                	<div class="col-sm-2">
                    <?php $actual_photo = get_img_name($regData['regnumber'],'p');?>
                        <img src="<?php echo base_url();?><?php echo $actual_photo;?><?php echo '?'.time(); ?>" name="scannedphoto" id="scannedphoto" width="100" height="100"/>
                    	 <input type="hidden" id="hiddenphoto" name="hiddenphoto" value="<?php echo get_actual_img_name($regData['regnumber'],'p');?>">
                     <br>
                     
                     <label for="" class="control-label">Uploaded Photograph </label>
                    </div>
                    
                    <div class="col-sm-2">
                    <?php $actual_sign = get_img_name($regData['regnumber'],'s');?>
                         <img src="<?php echo base_url();?><?php echo $actual_sign;?><?php echo '?'.time(); ?>" name="scannedsignaturephoto" id="scannedsignaturephoto" width="100" height="100" />
                    	 <input type="hidden" id="hiddenphoto" name="hiddenphoto" value="<?php echo get_actual_img_name($regData['regnumber'],'s');?>">
                     <br>
                     	<label for="" class="control-label">Uploaded Signature </label>
                    </div>
                    
                    <div class="col-sm-2">
                    <?php $actual_idproof = get_img_name($regData['regnumber'],'pr');?>
                       <img src="<?php echo base_url();?><?php echo $actual_idproof;?><?php echo '?'.time(); ?>" name="idproofphoto" id="idproofphoto" width="100" height="100" />
                    	 <input type="hidden" id="hiddenphoto" name="hiddenphoto" value="<?php echo get_actual_img_name($regData['regnumber'],'pr');?>">
                     <br>
                    	<label for="" class="control-label">Uploaded Id Proof </label>
                    </div>
                    
                    <div class="col-sm-2">
						<div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">&nbsp;</label>
                            
                        </div>
                        <h4>
                        	<a href="<?php echo base_url();?>admin/Report/editimages/<?php echo base64_encode($regData['regid']);?>/<?php echo base64_encode($regData['regnumber']);?>">Edit Images</a>
                       </h4>
                            <!--<a href="<?php //echo base_url();?>admin/Report/editimages/?reg_id=<?php //echo base64_encode($regData['regid']);?>">Edit Images</a>-->
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Select Id Proof *</label>
                	<div class="col-sm-5">
                       <input name="idproof" value="1" type="radio" class="minimal"  <?php if($regData['idproof']=='1'){echo "checked='checked'";}?>>Aadhaar id<br>
                        <input name="idproof" value="2" type="radio" class="minimal" <?php if($regData['idproof']=='2'){echo "checked='checked'";}?>>Driving License<br>
                        <input name="idproof" value="3" type="radio" class="minimal" <?php if($regData['idproof']=='3'){echo "checked='checked'";}?>>Election Voters card<br>
                        <input name="idproof" value="4" type="radio" class="minimal" <?php if($regData['idproof']=='4'){echo "checked='checked'";}?>>Employer's card<br>
                        <input name="idproof" value="5" type="radio" class="minimal" <?php if($regData['idproof']=='5'){echo "checked='checked'";}?>>Pan card<br>
                        <input name="idproof" value="6" type="radio" class="minimal" <?php if($regData['idproof']=='6'){echo "checked='checked'";}?>>Passport<br>		
                       <input type="hidden" name="idproof_hidd" id="idproof_hidd" value="<?php echo $regData['idproof']; ?>" /> 
                    </div>
                </div>
                
                 
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">ID No.*</label>
                	<div class="col-sm-5">
                       <input type="text" class="form-control pull-right" id="idNo"  name="idNo" placeholder="ID No."  maxlength="25" data-parsley-maxlength="25" value="<?php echo $regData['idNo'];?>" >
                       <input type="hidden" name="idNo_hidd" id="idNo_hidd" value="<?php echo $regData['idNo']; ?>" /> 
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-9 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
                	<div class="col-sm-2">
                       <input value="Y" name="optnletter" id="optnletter1" checked="" type="radio"  <?php if($regData['optnletter']=='Y'){echo "checked='checked'";}?>>Yes
						<input value="N" name="optnletter" id="optnletter2" type="radio"  <?php if($regData['optnletter']=='N'){echo "checked='checked'";}?>>No
                       <input type="hidden" name="optnletter_hidd" id="optnletter_hidd" value="<?php echo $regData['optnletter']; ?>" /> 
                    </div>
                </div>
                 
                <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit" onclick="return checkEdit_admin();">
                     <!--<a href="<?php echo base_url();?>admin/Report" class="btn btn-default pull-right">Back</a>-->
                     <a href="javascript:history.back(-1);" class="btn btn-default pull-right">Back</a>
                    </div>
              </div>
                
            </div>
             
         </div>
        </div>
      </div>
     
      
      
    </section>
    </form>
  </div>
<!--<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">-->

<script>var site_url="<?php echo base_url();?>";</script> 
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url();?>js/validation.js"></script>
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script type="text/javascript">
  <!--var flag=$('#usersAddForm').parsley('validate');-->

</script>
<script>
$(document).ready(function() 
{
	//$('#usersAddForm').parsley('validate');
	$('#dob1').change();
	$('#doj1').change();
	
	var edu = '<?php echo $regData['qualification']; ?>';
	var qualification = '<?php echo $regData['specify_qualification']; ?>';
	if(edu == 'U')
	{
		$('#eduqual1').val(qualification);
		$('#eduqual1_hidd').val(qualification);
		/*$('#eduqual1').attr('required','required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');*/
	}
	else if(edu == 'G')
	{
		$('#eduqual2').val(qualification);
		$('#eduqual2_hidd').val(qualification);
		/*$('#eduqual1').removeAttr('required');
		$('#eduqual2').attr('required','required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');*/
	}
	else if(edu == 'P')
	{
		$('#eduqual3').val(qualification);
		$('#eduqual3_hidd').val(qualification);
		/*$('#eduqual1').removeAttr('required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').attr('required','required');
		$('#eduqual').removeAttr('required');*/
	}
		
	
	changedu(edu);

	 $(function() {
		$("#dob1").dateDropdowns({
			submitFieldName: 'dob1',
			minAge: 0,
			maxAge:59
		});
		// Set all hidden fields to type text for the demo
		//$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
	});
	
	$(function() {
		$("#doj1").dateDropdowns({
			submitFieldName: 'doj1',
			minAge: 0,
			maxAge:59
		});
	});
});
	
function checkEdit_admin()
{
	
	//if($('#sel_namesub').val() == $('#sel_namesub_hidd').val())
	var flag = true;
	var gender = $('input[name=gender]:checked').val();
	var optedu = $('input[name=optedu]:checked').val();
	var idproof = $('input[name=idproof]:checked').val();
	var optnletter = $('input[name=optnletter]:checked').val();
	
	var edu = '<?php echo $regData['qualification']; ?>';
	var qualification = '<?php echo $regData['specify_qualification']; ?>';
	qual_query = '';
	if(edu == 'U')
	{
		if($('#eduqual1').val() == $('#eduqual1_hidd').val())
			var qual_query = true;
		else
			var qual_query =  false;
	}
	else if(edu == 'G')
	{
		if($('#eduqual2').val() == $('#eduqual2_hidd').val())
			var qual_query = true;
		else
			var qual_query =  false;
	}
	else if(edu == 'P')
	{
		if($('#eduqual3').val() == $('#eduqual3_hidd').val())
			var qual_query = true;
		else
			var qual_query =  false;
	}
	else
	{
		var qual_query = true;
	}
	
	/*
	if($('#sel_namesub').val() == $('#sel_namesub_hidd').val() && $('#firstname').val() == $('#firstname_hidd').val() && $('#middlename').val() == $('#middlename_hidd').val() && $('#lastname').val() == $('#lastname_hidd').val() && $('#nameoncard').val() == $('#nameoncard_hidd').val() && $('#addressline1').val() == $('#addressline1_hidd').val() && $('#addressline2').val() == $('#addressline2_hidd').val() && $('#addressline3').val() == $('#addressline3_hidd').val() && $('#addressline4').val() == $('#addressline4_hidd').val() && $('#district').val() == $('#district_hidd').val() && $('#city').val() == $('#city_hidd').val()  && $('#state').val() == $('#state_hidd').val() && $('#dob1').val() == $('#datepicker_hidd').val()  && $('#pincode').val() == $('#pincode_hidd').val()  && gender == $('#gender_hidd').val()  && optedu == $('#optedu_hidd').val() && $('#institutionworking').val() == $('#institutionworking_hidd').val() && 
$('#email').val() == $('#email_hidd').val() && $('#stdcode').val() == $('#stdcode_hidd').val() && $('#phone').val() == $('#phone_hidd').val() && $('#office').val() == $('#office_hidd').val() && $('#mobile').val() == $('#mobile_hidd').val() && idproof == $('#idproof_hidd').val() && $('#idNo').val() == $('#idNo_hidd').val() && $('#optnletter').val() == $('#optnletter_hidd').val() && $('#designation').val().trim() == $('#designation_hidd').val().trim() && $('#doj1').val() == $('#doj_hidd').val() && qual_query) 
	*/
	
	if($('#sel_namesub').val() == $('#sel_namesub_hidd').val() && $('#firstname').val() == $('#firstname_hidd').val() && $('#middlename').val() == $('#middlename_hidd').val() && $('#lastname').val() == $('#lastname_hidd').val() && $('#nameoncard').val() == $('#nameoncard_hidd').val() && $('#addressline1').val() == $('#addressline1_hidd').val() && $('#addressline2').val() == $('#addressline2_hidd').val() && $('#addressline3').val() == $('#addressline3_hidd').val() && $('#addressline4').val() == $('#addressline4_hidd').val() && $('#district').val() == $('#district_hidd').val() && $('#city').val() == $('#city_hidd').val()  && $('#state').val() == $('#state_hidd').val() && $('#dob1').val() == $('#datepicker_hidd').val()  && $('#pincode').val() == $('#pincode_hidd').val()  && gender == $('#gender_hidd').val()  && optedu == $('#optedu_hidd').val() && $('#institutionworking').val() == $('#institutionworking_hidd').val() && 
$('#email').val() == $('#email_hidd').val() && $('#stdcode').val() == $('#stdcode_hidd').val() && $('#phone').val() == $('#phone_hidd').val() && $('#office').val() == $('#office_hidd').val() && $('#mobile').val() == $('#mobile_hidd').val() && idproof == $('#idproof_hidd').val() && $('#idNo').val() == $('#idNo_hidd').val() && $('#designation').val().trim() == $('#designation_hidd').val().trim() && $('#doj1').val() == $('#doj_hidd').val() &&  optnletter == $('#optnletter_hidd').val().trim() && qual_query) 
	{
		alert("Please Change atleast One Value");
		return false;
	}
	else
	{
		var flag=$('#usersAddForm').parsley().validate();
		var dob = $('#dob1').val();
		var doj = $('#doj1').val();
		var dob_err_msg = $("#dob_error").html();
		var doj_err_msg = $("#doj_error").html();
		
		if(dob=='')
		{
			$("#dob_error").html('Please select Date Of Birth');
			$(".day").focus();
			flag = false;	
		}
		else if(doj=='')
		{
			$("#doj_error").html('Please select Date Of Joining');
			$(".day").focus();
			flag = false;
		}
		if(dob_err_msg!='' || doj_err_msg!='')
			flag = false;
			
		//alert(flag);return false;
		if(flag){
			return true;	//$('#usersAddForm').submit();
		}
		else{
			return false;
		}
	}	
}
	
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
		/*$('#eduqual1').attr('required','required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');*/
		
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
		/*$('#eduqual1').removeAttr('required');
		$('#eduqual2').attr('required','required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');*/
			
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
		/*$('#eduqual1').removeAttr('required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').attr('required','required');
		$('#eduqual').removeAttr('required');*/
			
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
	else
	{
		document.getElementById('UG').style.display = "none";
		document.getElementById('GR').style.display = "none";
		document.getElementById('PG').style.display = "none";
		document.getElementById('edu').style.display = "block";
		
		$('#eduqual1').removeAttr('required');
		$('#eduqual2').removeAttr('required');
		$('#eduqual3').removeAttr('required');
		$('#eduqual').removeAttr('required');
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

 //$("#datepicker,#doj").keypress(function(event) {event.preventDefault();});
});
</script>
<style>
.labelleft {
	text-align:left !important;
	padding-left:0 !important;
	padding-right:0 !important;
	font-weight:normal;
}
.w50 {
	width:50% !important;
}
</style>
<?php $this->load->view('admin/includes/footer');?>