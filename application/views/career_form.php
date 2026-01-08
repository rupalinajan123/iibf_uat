<!DOCTYPE html>
<html lang="en"><head>
<?php $this->load->view('google_analytics_script_common'); ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Current Opening">
<meta name="author" content="IFCI">
<title>Current Opening</title>
<?php include 'header-script.php';?>
<link href="<?php echo base_url()?>assets/css/styles.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/css/ladda.min.css" rel="stylesheet">
<link href="<?php echo  base_url()?>assets/css/css_header.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-select.css">
<script src="<?php echo base_url()?>assets/js/jquery.js"></script>
<style>
.ui-datepicker-year, .ui-datepicker-month { color:#000; }
.mar5{ margin-top:5px;}
.eligible{ color:#b94a48; font-size: 14px;}
.drop_custom
{
	float:left;
	width:100%;
	min-height:30px;
	border:1px solid #ccc;
	padding:5px;
	margin-bottom:10px;
}
.success{color:#3C0; font-size:16px;}
.error{color:#F00; font-size:14px;}
.input-group.browsefile.resume {
    width: 365px;
    float: left;
}
.check.fa.fa-check-circle {
    float: right;
    font-size: 20px;
    line-height: 1.5;
    margin: 0px 20px;
    color: #13bda0;
}
.min50{ min-height:50px}

select.titleSelect {
-moz-appearance: none;
-webkit-appearance:none;
}
select.titleSelect::-ms-expand {
display: none !important;
background:#000;
}
</style>
</head>
<body>
<!-- Start of Header Area -->
<?php include 'header.php';?>
<!-- End of Header Area --> 

<!-- Start of Form Wizard -->
<div class="container atm_m40-top atm_m40-bottom">

	 <?php /*if ($this->session->flashdata('error_message') != "") { 
	 	echo '<p class="error">'.$this->session->flashdata('error_message').'</p>'; 
	  }
	   if ($this->session->flashdata('success_message') != "") { 
	    echo '<p class="success">'.$this->session->flashdata('success_message').'</p>'; 
		
		echo '<p class="error">'.validation_errors().'</p>';}
	?>*/
	
	
   if(validation_errors()){ ?>
   <div class="error">
	<?php 	echo validation_errors(); ?>
    </div>
    <?php 
	} 
	
	if($this->session->flashdata('error') != ""){ ?>
       <div class="error">
        <?php 	echo $this->session->flashdata('error'); ?>
       </div>
    <?php 
	} 
	
	if($signatureErr != ""){ ?>
       <div class="error">
        <?php 	echo $signatureErr; ?>
       </div>
    <?php 
	} 
	
	if ($this->session->flashdata('success_message') != "") { 
	    echo '<p class="success">'.$this->session->flashdata('success_message').'</p>'; 
	 }
	?>
                               
                             
    
  <div class="col-lg-12 well well-sm table-responsive">
    <form class="form-horizontal" method="post"  name="applicationForm" id="applicationForm" enctype="multipart/form-data">
      <fieldset>
      <!--<h2>Application No : <?php echo $applicationNo; ?></h2>-->
        <legend class="text-center header change">Current Opening </legend>
        <!--<div class="col-md-9  col-md-offset-2">
                <div class="form-group">
                  <div class="col-md-3">
                    <a href="#" class="btn btn-primary">Instructions</a>
                  </div>
                  <div class="col-md-3">
                   <a href="#" class="btn btn-primary">Advertisement</a>
                  </div>
                    <div class="col-md-3">
                   <a href="#" class="btn btn-primary">Make Payment</a>
                  </div>
                </div>
              </div>-->
        <?php //if($this->uri->segment(1)!=''){?>
        <div class="col-md-12">
          <div class="form-group"> <span class="col-md-2">Post Applied <span class="error">*</span></span>
            <div class="col-md-5">
              
              <select name="apply_for1" id="apply_for" class="drop_custom titleSelect" data-live-search="true"  title="post applied for" required disabled ><!-- onChange="qualification_custom(this.value)" -->
                <option value="">Select</option>
                <option value="General Manager ( Credit )"<?php if($this->uri->segment(1)=='a001'){?>selected="selected"<?php }?>>General Manager ( Credit )</option>
                <option value="General Manager ( NPA Resolution and Recovery )" <?php if($this->uri->segment(1)=='a002'){?>selected="selected"<?php }?>>General Manager ( NPA Resolution and Recovery )</option>
                <option value="Deputy General Manager ( Credit )" <?php if($this->uri->segment(1)=='a003'){?>selected="selected"<?php }?>>Deputy General Manager  ( Credit )</option>
                <option value="Deputy General Manager  ( Internal and Credit Audit )" <?php if($this->uri->segment(1)=='a004'){?>selected="selected"<?php }?>>Deputy General Manager  ( Internal and Credit Audit )</option>
                <option value="Asst. General Manager ( Technical )" <?php if($this->uri->segment(1)=='a005'){?>selected="selected"<?php }?>>Asst. General Manager ( Technical )</option>
                <option value="Manager  ( Finance )" <?php if($this->uri->segment(1)=='a006'){?>selected="selected"<?php }?>>Manager  ( Finance )</option>
                <option value="Manager ( Legal )" <?php if($this->uri->segment(1)=='a007'){?>selected="selected"<?php }?>>Manager ( Legal )</option>
                <option value="Asst. Manager  ( Finance )" <?php if($this->uri->segment(1)=='a008'){?>selected="selected"<?php }?>>Asst. Manager  ( Finance )</option>
                <option value="Asst. Manager ( IT )" <?php if($this->uri->segment(1)=='a009'){?>selected="selected"<?php }?>>Asst. Manager ( IT )</option>
              </select>
              <?php 
			  if($this->uri->segment(1)=='a001')
			  {
				  $apply_for_val='General Manager ( Credit )';
			   }
			    if($this->uri->segment(1)=='a002')
			  {
				  $apply_for_val='General Manager ( NPA Resolution and Recovery )';
			   } if($this->uri->segment(1)=='a003')
			  {
				  $apply_for_val='Deputy General Manager ( Credit )';
			   }
			    if($this->uri->segment(1)=='a004')
			  {
				  $apply_for_val='Deputy General Manager  ( Internal and Credit Audit )';
			   }
			    if($this->uri->segment(1)=='a005')
			  {
				  $apply_for_val='Asst. General Manager ( Technical )';
			   }
			    if($this->uri->segment(1)=='a006')
			  {
				  $apply_for_val='Manager  ( Finance )';
			   }
			    if($this->uri->segment(1)=='a007')
			  {
				  $apply_for_val='Manager ( Legal )';
			   }
			    if($this->uri->segment(1)=='a008')
			  {
				  $apply_for_val='Asst. Manager  ( Finance )';
			   }
			    if($this->uri->segment(1)=='a009')
			  {
				  $apply_for_val='Asst. Manager ( IT )';
			   }
			   
			   //echo $this->session->userdata('uniqueString');
			
			// echo getcwd();
			
			
			   ?>
            <input type="hidden" value="<?php echo $apply_for_val?>" name="apply_for" id="">
            
             <input type="hidden" value="<?php echo $uniqueString; ?>" name="uniqueString" id="uniqueString">
            </div>
            
            
            
            <!--<span class="col-md-4">Is SC / ST / PWD ?  :
            <input type="radio" name="IsCategory" value="Yes" >Yes
            <input type="radio" name="IsCategory" value="No">No
            </span>
            
            
            <span class="col-md-4 fees" style="display:none;">Fees paid
            <input type="radio" name="feespaid" value="yes" required>Yes
            <input type="radio" name="feespaid" value="no" checked>No
            </span>-->
             
           </div>
        </div>
        
        <?php //} ?>
        <!--============================================ Step - 1 ============================================-->
        <div class="row" id="formwrapper" ><!--style="display:none"-->
          <div class="">
            <div class="col-md-12">
              <h2 class="inner">A. Personal Details <span style="font-size:12px;">All fields marked with <span class="error">*</span> are mandatory.</span></h2>
              <p></p>
			  <div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">1. Category <span class="error">*</span></span>
                  <div class="col-md-9 ">
                  <div class="col-md-3">
                  <p>
                    <!--<label >
                      <input type="radio" class="category" name="category" value="General" required onclick="checkAge()">
                      UR </label>
                      <label >
                      <input type="radio" class="category" name="category" value="SC" onclick="checkAge()">
                      SC </label>
                      <label >
                      <input type="radio" class="category" name="category" value="ST" onclick="checkAge()">
                      ST </label>
                      <label >
                      <input type="radio" class="category" name="category" value="OBC" onclick="checkAge()">
                      OBC </label>-->
                      
                      
                      <input type="radio" class="category" name="category" value="General" required onclick="checkAge()" <?php echo  set_radio('category', 'General'); ?>>
                      UR 
                      <input type="radio" class="category" name="category" value="SC" onclick="checkAge()" <?php echo  set_radio('category', 'SC'); ?>>
                      SC 
                      <input type="radio" class="category" name="category" value="ST" onclick="checkAge()" <?php echo  set_radio('category', 'ST'); ?>>
                      ST 
                      <input type="radio" class="category" name="category" value="OBC" onclick="checkAge()" <?php echo  set_radio('category', 'OBC'); ?>>
                      OBC 
                      
                      </p>
                  </div>
                </div>
              </div>
              
              
              <!--<div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">Fees Paid ? <span class="error">*</span></span>
                  <div class="col-md-9 ">
                    
                    <div class="form-group">
                      <div class=""> <span class="col-md-3 text-left">
                        <label class="">
                          <input type="checkbox" name="feesPaid" id="feesPaid" value="1" onclick="">
                        </label>
                        </span> </div>
                    </div>
              
                  </div>
                </div>
              </div>-->
              
              <div class="row">
			  <div id="appForm" style="display:none;">
              <div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">2. Name of Applicant <span class="error">*</span></span>
                  <div class="col-md-3">
                    <select name="title" id="title" class="drop_custom" data-live-search="true" title="title" required onChange="checkTitle();">
                      <option value="">Select</option>
                      <option value="Mr" <?php echo  set_select('title', 'Mr'); ?>>Mr.</option>
                      <option value="Mrs" <?php echo  set_select('title', 'Mrs'); ?>>Mrs.</option>
                      <option value="Ms" <?php echo  set_select('title', 'Ms'); ?>>Ms.</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-9 col-md-offset-3">
                <div class="form-group">
                  <div class="col-md-4 ">
                    <input name="first_name" id="first_name" type="text" placeholder="Enter First Name" class="form-control" required data-parsley-type="character" value="<?php echo  set_value('first_name'); ?>">
                  </div>
                  <div class="col-md-4 ">
                    <input  name="middle_name"  id="middle_name" type="text" placeholder="Enter Middle Name" class="form-control" data-parsley-type="character"  value="<?php echo  set_value('middle_name'); ?>">
                  </div>
                  <div class="col-md-4 ">
                    <input  name="last_name" id="last_name" type="text" placeholder="Enter Last Name" class="form-control"  data-parsley-type="character"  value="<?php echo  set_value('last_name'); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">3. Former/Maiden Name<span style="display:none" id="maiden_name_err" class="error">*</span></span>
                  <div class="col-md-3">
                    <input name="m_first_name" id="m_first_name" type="text" placeholder="Enter First Name" class="form-control" data-parsley-type="character"  value="<?php echo  set_value('m_first_name'); ?>">
                  </div>
                  <div class="col-md-3">
                    <input name="m_middle_name" id="m_middle_name" type="text" placeholder="Enter Middle Name" class="form-control"  data-parsley-type="character"  value="<?php echo  set_value('m_middle_name'); ?>">
                  </div>
                  <div class="col-md-3">
                    <input name="m_last_name" id="m_last_name" type="text" placeholder="Enter Last Name" class="form-control"  data-parsley-type="character"  value="<?php echo  set_value('m_last_name'); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">4. Father's/Husband's Name <span class="error">*</span></span>
                  <div class="col-md-6 ">
                    <p>
                      <input type="checkbox" class="fatherHusbandCheck" id="fatherCheck" name="father_husband" value="Father" data-parsley-group="father_husband" <?php echo  set_checkbox('father_husband', 'Father'); ?> required>
                      Father
                      <input type="checkbox" class="fatherHusbandCheck" id="husbandCheck" name="father_husband" value="Husband" data-parsley-group="father_husband"   <?php echo  set_checkbox('father_husband', 'Husband'); ?>>
                      Husband 
                      </p>
                   </div>
                  
                  
                </div>
              </div>
              
               <div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">&nbsp;</span>
                  <div class="col-md-3">
                        <input name="father_husband_name1" id="father_husband_name1" type="text" placeholder="Enter First Name" class="form-control" required data-parsley-type="character"  value="<?php echo  set_value('father_husband_name1'); ?>">
                  </div>
                  <div class="col-md-3">
                    <input name="father_husband_name2" id="father_husband_name2" type="text" placeholder="Enter Middle Name" class="form-control"  data-parsley-type="character"  value="<?php echo  set_value('father_husband_name2'); ?>">
                  </div>
                  <div class="col-md-3">
                    <input name="father_husband_name3" id="father_husband_name3" type="text" placeholder="Enter Last Name" class="form-control"  data-parsley-type="character"  value="<?php echo  set_value('father_husband_name3'); ?>">
                  </div>
                </div>
              </div>
              
              
             <!-- <div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">&nbsp;</span>
                  <div class="col-md-6 ">
                    <div class="col-md-6">
                    <p>
                      <input type="radio" class="genderradio" name="gender" value="Female" data-parsley-group="gender" <?php //echo  set_radio('gender', 'Female'); ?>>
                      Female
                      <input type="radio" class="genderradio" name="gender" value="Male" data-parsley-group="gender" required  <?php //echo  set_radio('gender', 'Male'); ?>>
                      Male 
                      </p>
                      </div>
                  </div>
                </div>
              </div>-->
              
              
              <div class="col-md-6">
                <div class="form-group"> <span class="col-md-6">5. Date of Birth <span class="error">*</span></span>
                  <div class="col-md-6 ">
                    <input name="dob" type="text" id="dob" placeholder="" class="form-control" required onchange="return getAge();" readonly="readonly" value="<?php echo  set_value('dob'); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group"> <span class="col-md-3">6. Age: (as on 31/03/2016) <span class="error">*</span></span>
                  <div class="col-md-9 ">
                    <input name="age" type="text" id="age" readonly placeholder="" class="form-control" required  onFocus="return checkAge();" value="<?php echo  set_value('age'); ?>" >
                    <span class="eligible" id="ageEligible" ></span>
                    
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group"> <span class="col-md-6">7. Gender <span class="error">*</span></span>
                  <div class="col-md-6 ">
                    <div class="col-md-12">
                    <p>
                      <input type="radio" class="genderradio" name="gender" value="Female" data-parsley-group="gender" <?php echo  set_radio('gender', 'Female'); ?>>
                      Female
                      <input type="radio" class="genderradio" name="gender" value="Male" data-parsley-group="gender" required  <?php echo  set_radio('gender', 'Male'); ?>>
                      Male 
                      </p>
                      </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group"> <span class="col-md-3">8. Nationality <span class="error">*</span></span>
                  <div class="col-md-9">
                    <select name="nationality" id="nationality" class="drop_custom" title="Select Nationality" required onchange="return checkNationality(this.value);">
                      <option value="Indian" <?php echo  set_select('nationality', 'Indian'); ?>>Indian</option>
                      <option value="NRI" <?php echo  set_select('nationality', 'NRI'); ?>>NRI</option>
                      <option value="Others" <?php echo  set_select('nationality', 'Others'); ?>>Others</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">9. Marital Status <span class="error">*</span></span>
                  <div class="col-md-6 ">
                    <div class="col-md-12">
                    <p>
                      <input type="radio" class="marrital_status" name="marrital_status" value="Single" required <?php echo  set_radio('marrital_status', 'Single'); ?>>
                      Single
                      <input type="radio" class="marrital_status" name="marrital_status" value="Married" <?php echo  set_radio('marrital_status', 'Married'); ?>>
                      
                      Married </p></div>
                  </div>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group"> <span class="col-md-6">10. Mobile No. <span class="error">*</span></span>
                  <div class="col-md-6 ">
                    <input name="mobile" id="mobile" type="text" placeholder="Enter Mobile Number" class="form-control input-selector" required data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" value="<?php echo set_value('mobile'); ?>" onInput="checkLength('mobile')" >
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group"> <span class="col-md-3">11. Email ID <span class="error">*</span></span>
                  <div class="col-md-9">
                    <input name="email" id="email" type="text" placeholder="Enter Email Address" class="form-control" required data-parsley-type="email" value="<?php echo set_value('email'); ?>" data-parsley-trigger-after-failure="focusout">
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">12. Alternate Email ID</span>
                  <div class="col-md-3 ">
                    <input name="alternate_email" id="alternate_email" type="text" placeholder="Enter Alternate Email Address" class="form-control" data-parsley-type="email" value="<?php echo set_value('alternate_email'); ?>" data-parsley-trigger-after-failure="focusout">
                  </div>
                </div>
              </div>
            
            <!-- Applicant's Name --> 
            <!--<h2 class="inner">More Details</h2>-->
            
            <div class="col-md-12">
              <div class="form-group"> <span class="col-md-8">13. Whether belonging to Minority Community? <span class="error">*</span></span>
                <div class="col-md-4 ">
                  <div class="col-md-12">
                  <p>
                    <input type="radio" class="minority_community" name="minority_community" value="Yes" required <?php echo  set_radio('minority_community', 'Yes'); ?>>
                    Yes
                    <input type="radio" class="minority_community" name="minority_community" value="No" <?php echo  set_radio('minority_community', 'No'); ?>>
                    No 
                    </p></div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group"> <span class="col-md-8">14. Are you a Domicile of J & K during 1.1.1980 to 31.12.1989? <span class="error">*</span></span>
                <div class="col-md-4 ">
                  <div class="col-md-12">
                  <p>
                    <input type="radio" class="domicile_j" name="domicile_jk" value="Yes" required onclick="checkAge()" <?php echo  set_radio('domicile_jk', 'Yes'); ?>>
                    Yes
                    <input type="radio" class="domicile_j" name="domicile_jk" value="No" onclick="checkAge()" <?php echo  set_radio('domicile_jk', 'No'); ?>>
                    No
                    </p> </div>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group"> <span class="col-md-8">15. Whether Ex-Serviceman? <span class="error">*</span></span>
                <div class="col-md-4 ">
                  <div class="col-md-12">
                  <p>
                    <input type="radio" class="ex_serviceman" name="ex_serviceman" value="Yes" required <?php echo  set_radio('ex_serviceman', 'Yes'); ?>>
                    Yes
                    <input type="radio" name="ex_serviceman" class="ex_serviceman" value="No" <?php echo  set_radio('ex_serviceman', 'No'); ?>>
                    No</p></div>
                </div>
              </div>
            </div>
            
            <div class="col-md-12">
              <div class="form-group"> <span class="col-md-8">16. Whether Person with Disabilities (PWD)? <span class="error">*</span></span>
                <div class="col-md-4 ">
                  <div class="col-md-12">
                  <p>
                    <input type="radio" class="pwd1" name="pwd1" id="pwd3" value="Yes" required <?php echo  set_radio('pwd1', 'Yes'); ?>>
                    Yes
                    <input type="radio" name="pwd1"  class="pwd1" id="pwd4" value="No" <?php echo  set_radio('pwd1', 'No'); ?>>
                    No 
                    </p></div>
                    
                    <input type="hidden" name="pwdText"  class="" id="pwdText" value="">
                </div>
              </div>
            </div>
            
            <div class="col-md-12">
              <div class="form-group"> <span class="col-md-8">17. Whether IFCI employee? <span class="error">*</span></span>
                <div class="col-md-4 ">
                  <div class="col-md-12">
                  <p>
                    <input type="radio" class="ifci_employee" name="ifci_employee" value="Yes" required <?php echo  set_radio('ifci_employee', 'Yes'); ?>>
                    Yes
                    <input type="radio" class="ifci_employee" name="ifci_employee" value="No" <?php echo  set_radio('ifci_employee', 'No'); ?>>
                    No 
                    </p></div>
                </div>
              </div>
            </div>
            
            <div class="col-md-12">
              <div class="form-group"> <span class="col-md-8">18. Whether employed in Central or State Govt./Semi/-Gov. autonomous bodies /CPSEs/PSUs/PSEs? <span class="error">*</span></span>
                <div class="col-md-4 ">
                  <div class="col-md-12">
                  <p>
                    <input type="radio" class="government_employee" name="government_employee" value="Yes" required <?php echo  set_radio('government_employee', 'Yes'); ?>>
                    Yes
                    <input type="radio" class="government_employee" name="government_employee" value="No" <?php echo  set_radio('government_employee', 'No'); ?>>
                    No 
                    </p></div>
                </div>
              </div>
            </div>
            
            <!--<div class="col-md-12" style="display:none;" id="nocObtained">
              <div class="form-group"> <span class="col-md-8">18. b) Whether obtained the requisite NOC from the current employer as  mentioned in General condition S. No. 3 (a)  of the detailed advertisement? <span class="error">*</span></span>
                <div class="col-md-4 ">
                  <div class="col-md-12">
                  <p>
                    <input type="radio" class="noc" name="noc" value="Yes" <?php echo  set_radio('noc', 'Yes'); ?> id="noc1">
                    Yes
                    <input type="radio" class="noc" name="noc" value="No" <?php echo  set_radio('noc', 'No'); ?> id="noc2">
                    No 
                    </p></div>
                </div>
              </div>
            </div>-->
             
			<div class="">
            <h2 class="inner">B. Contact Details</h2>
            <div class="row"> 
              
              <!--<div class="col-md-12">
                      <div class="form-group">
                        <span class="col-md-3">Address</span>
                        <div class="col-md-9">
                          <input name="nameadd" id="autocomplete" onFocus="geolocate()" type="text" placeholder="Enter Area , City to get Auto Suggestions" class="form-control">
                        </div>
                      </div>
                    </div>-->
              
             
              <div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">19. Correspondence Address <span class="error">*</span></span>
                  <div class="col-md-9 ">
                    <textarea class="form-control" rows="3" id="current_address" name="current_address" required  data-parsley-pattern="/^[a-zA-Z0-9 .,()\/_& @#_'-/\n]+$/"><?php echo set_value('current_address'); ?></textarea><!--/^[a-zA-Z][a-zA-Z0-9.,\-&' ]+$/    /^[a-zA-Z][a-zA-Z0-9.,()\/_& @#_'-/\n]+$/-->
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group"> <span class="col-md-6">20. City <span class="error">*</span></span>
                  <div class="col-md-6 ">
                    <input name="c_city" id="c_city" type="text" placeholder="Current City" class="form-control" required data-parsley-type="character" value="<?php echo set_value('c_city'); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group"> <span class="col-md-3">21. State <span class="error">*</span></span>
                  <div class="col-md-9 min50">
                    <select id="c_state" name="c_state" class="drop_custom" required title="Select State">
                     	<option value="">Select State</option>
                        <option value="Andaman and Nicobar Islands" <?php echo set_select('c_state', 'Andaman and Nicobar Islands'); ?>>Andaman and Nicobar Islands</option>
                        <option value="Andhra Pradesh" <?php echo set_select('c_state', 'Andhra Pradesh'); ?>>Andhra Pradesh</option>
                        <option value="Arunachal Pradesh" <?php echo set_select('c_state', 'Arunachal Pradesh'); ?>>Arunachal Pradesh</option>
                        <option value="Assam" <?php echo set_select('c_state', 'Assam'); ?>>Assam</option>
                        <option value="Bihar" <?php echo set_select('c_state', 'Bihar'); ?>>Bihar</option>
                        <option value="Chandigarh" <?php echo set_select('c_state', 'Chandigarh'); ?>>Chandigarh</option>
                        <option value="Chhattisgarh" <?php echo set_select('c_state', 'Chhattisgarh'); ?>>Chhattisgarh</option>
                        <option value="Dadra and Nagar Haveli" <?php echo set_select('c_state', 'Dadra and Nagar Haveli'); ?>>Dadra and Nagar Haveli</option>
                        <option value="Daman and Diu" <?php echo set_select('c_state', 'Daman and Diu'); ?>>Daman and Diu</option>
                        <option value="Delhi" <?php echo set_select('c_state', 'Delhi'); ?>>Delhi</option>
                        <option value="Goa" <?php echo set_select('c_state', 'Goa'); ?>>Goa</option>
                        <option value="Gujarat" <?php echo set_select('c_state', 'Gujarat'); ?>>Gujarat</option>
                        <option value="Haryana" <?php echo set_select('c_state', 'Haryana'); ?>>Haryana</option>
                        <option value="Himachal Pradesh" <?php echo set_select('c_state', 'Himachal Pradesh'); ?>>Himachal Pradesh</option>
                        <option value="Jammu and Kashmir" <?php echo set_select('c_state', 'Jammu and Kashmir'); ?>>Jammu and Kashmir</option>
                        <option value="Jharkhand" <?php echo set_select('c_state', 'Jharkhand'); ?>>Jharkhand</option>
                        <option value="Karnataka" <?php echo set_select('c_state', 'Karnataka'); ?>>Karnataka</option>
                        <option value="Kerala" <?php echo set_select('c_state', 'Kerala'); ?>>Kerala</option>
                        <option value="Lakshadweep" <?php echo set_select('c_state', 'Lakshadweep'); ?>>Lakshadweep</option>
                        <option value="Madhya Pradesh" <?php echo set_select('c_state', 'Madhya Pradesh'); ?>>Madhya Pradesh</option>
                        <option value="Maharashtra" <?php echo set_select('c_state', 'Maharashtra'); ?>>Maharashtra</option>
                        <option value="Manipur" <?php echo set_select('c_state', 'Manipur'); ?>>Manipur</option>
                        <option value="Meghalaya" <?php echo set_select('c_state', 'Meghalaya'); ?>>Meghalaya</option>
                        <option value="Mizoram" <?php echo set_select('c_state', 'Mizoram'); ?>>Mizoram</option>
                        <option value="Nagaland" <?php echo set_select('c_state', 'Nagaland'); ?>>Nagaland</option>
                        <option value="Odisha" <?php echo set_select('c_state', 'Odisha'); ?>>Odisha</option>
                        <option value="Pondicherry" <?php echo set_select('c_state', 'Pondicherry'); ?>>Pondicherry</option>
                        <option value="Punjab" <?php echo set_select('c_state', 'Punjab'); ?>>Punjab</option>
                        <option value="Rajasthan" <?php echo set_select('c_state', 'Rajasthan'); ?>>Rajasthan</option>
                        <option value="Sikkim" <?php echo set_select('c_state', 'Sikkim'); ?>>Sikkim</option>
                        <option value="Tamil Nadu" <?php echo set_select('c_state', 'Tamil Nadu'); ?>>Tamil Nadu</option>
                        <option value="Telangana" <?php echo set_select('c_state', 'Telangana'); ?>>Telangana</option>
                        <option value="Tripura" <?php echo set_select('c_state', 'Tripura'); ?>>Tripura</option>
                        <option value="Uttar Pradesh" <?php echo set_select('c_state', 'Uttar Pradesh'); ?>>Uttar Pradesh</option>
                        <option value="Uttarakhand" <?php echo set_select('c_state', 'Uttarakhand'); ?>>Uttarakhand</option>
                        <option value="West Bengal" <?php echo set_select('c_state', 'West Bengal'); ?>>West Bengal</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group"> <span class="col-md-6">22. Pin <span class="error">*</span></span>
                  <div class="col-md-6 ">
                    <input  name="c_pin" id="c_pin" type="text" placeholder="Enter Postal Code" class="form-control input-selector" required data-parsley-type="number"data-parsley-maxlength="7" value="<?php echo set_value('c_pin'); ?>" data-parsley-trigger-after-failure="focusout">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group min50"> <span class="col-md-3">23. Phone <span class="error"></span></span>
                  <div class="col-md-3 ">
                  <input name="c_phone_std" id="c_phone_std" type="text" placeholder="STD Code" class="form-control input-selector"  data-parsley-type="number"data-parsley-maxlength="5" value="<?php echo set_value('c_phone_std'); ?>" data-parsley-trigger-after-failure="focusout">
                  </div>
                   <div class="col-md-6 ">
                  	 <input name="c_phone" id="c_phone" type="text" placeholder="Enter Number" class="form-control input-selector"  data-parsley-type="number"data-parsley-maxlength="10" value="<?php echo set_value('c_phone'); ?>" onInput="checkLength('c_phone')">
                  </div>
                </div>
              </div>
			   <div class="col-md-12">
                <div class="form-group min50"> <span class="col-md-3">24. Mobile <span class="error">*</span></span>
                  <div class="col-md-3 ">
                    <input name="c_mobile" id="c_mobile" type="text" placeholder="Enter Number" class="form-control input-selector" required  data-parsley-type="number"data-parsley-maxlength="10" data-parsley-minlength="10" value="<?php echo set_value('c_mobile'); ?>" onInput="checkLength('c_mobile')">
                  </div>
                </div>
              </div>
              
              <div class="col-md-12">
                <div class="form-group"> <span class="col-md-3">25. Permanent Address <span class="error">*</span></span>
                  <div class="col-md-9">
                    <div class="form-group">
                      <div class=""> <span class="col-md-3 text-left">Same as above
                        <label class="">
                          <input type="checkbox" name="addresshCheck" id="addresshCheck" value="1" onclick="" <?php echo set_checkbox('addresshCheck', '1'); ?> >
                        </label>
                        </span> </div>
                    </div>
                  </div>
                  <div class="col-md-9 col-md-offset-3">
                    <textarea class="form-control permanent_address_patch p_add" rows="3" id="permanent_address" name="permanent_address" required data-parsley-pattern="/^[a-zA-Z0-9 .,()\/_& @#_'-/\n]+$/"><?php echo set_value('permanent_address'); ?></textarea>
                  </div>
                </div>
              </div>
              
              <div id="permanantAddr">
                  <div class="col-md-6">
                    <div class="form-group"> <span class="col-md-6">26. City <span class="error">*</span></span>
                      <div class="col-md-6 ">
                        <input name="p_city" id="p_city" type="text" placeholder="Enter City" class="form-control p_add" required data-parsley-type="character" value="<?php echo set_value('p_city'); ?>">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group"> <span class="col-md-3">27. State <span class="error">*</span></span>
                      <div class="col-md-9 min50">
                        <select id="p_state" name="p_state" class="drop_custom p_add" title="Select State" required>
                         	<option value="">Select State</option>
<option value="Andaman and Nicobar Islands" <?php echo set_select('p_state', 'Andaman and Nicobar Islands'); ?>>Andaman and Nicobar Islands</option>
                            <option value="Andhra Pradesh" <?php echo set_select('p_state', 'Andhra Pradesh'); ?>>Andhra Pradesh</option>
                            <option value="Arunachal Pradesh" <?php echo set_select('p_state', 'Arunachal Pradesh'); ?>>Arunachal Pradesh</option>
                            <option value="Assam" <?php echo set_select('p_state', 'Assam'); ?>>Assam</option>
                            <option value="Bihar" <?php echo set_select('p_state', 'Bihar'); ?>>Bihar</option>
                            <option value="Chandigarh" <?php echo set_select('p_state', 'Chandigarh'); ?>>Chandigarh</option>
                            <option value="Chhattisgarh" <?php echo set_select('p_state', 'Chhattisgarh'); ?>>Chhattisgarh</option>
                            <option value="Dadra and Nagar Haveli" <?php echo set_select('p_state', 'Dadra and Nagar Haveli'); ?>>Dadra and Nagar Haveli</option>
                            <option value="Daman and Diu" <?php echo set_select('p_state', 'Daman and Diu'); ?>>Daman and Diu</option>
                            <option value="Delhi" <?php echo set_select('p_state', 'Delhi'); ?>>Delhi</option>
                            <option value="Goa" <?php echo set_select('p_state', 'Goa'); ?>>Goa</option>
                            <option value="Gujarat" <?php echo set_select('p_state', 'Gujarat'); ?>>Gujarat</option>
                            <option value="Haryana" <?php echo set_select('p_state', 'Haryana'); ?>>Haryana</option>
                            <option value="Himachal Pradesh" <?php echo set_select('p_state', 'Himachal Pradesh'); ?>>Himachal Pradesh</option>
                            <option value="Jammu and Kashmir" <?php echo set_select('p_state', 'Jammu and Kashmir'); ?>>Jammu and Kashmir</option>
                            <option value="Jharkhand" <?php echo set_select('p_state', 'Jharkhand'); ?>>Jharkhand</option>
                            <option value="Karnataka" <?php echo set_select('p_state', 'Karnataka'); ?>>Karnataka</option>
                            <option value="Kerala" <?php echo set_select('p_state', 'Kerala'); ?>>Kerala</option>
                            <option value="Lakshadweep" <?php echo set_select('p_state', 'Lakshadweep'); ?>>Lakshadweep</option>
                            <option value="Madhya Pradesh" <?php echo set_select('p_state', 'Madhya Pradesh'); ?>>Madhya Pradesh</option>
                            <option value="Maharashtra" <?php echo set_select('p_state', 'Maharashtra'); ?>>Maharashtra</option>
                            <option value="Manipur" <?php echo set_select('p_state', 'Manipur'); ?>>Manipur</option>
                            <option value="Meghalaya" <?php echo set_select('p_state', 'Meghalaya'); ?>>Meghalaya</option>
                            <option value="Mizoram" <?php echo set_select('p_state', 'Mizoram'); ?>>Mizoram</option>
                            <option value="Nagaland" <?php echo set_select('p_state', 'Nagaland'); ?>>Nagaland</option>
                            <option value="Odisha" <?php echo set_select('p_state', 'Odisha'); ?>>Odisha</option>
                            <option value="Pondicherry" <?php echo set_select('p_state', 'Pondicherry'); ?>>Pondicherry</option>
                            <option value="Punjab" <?php echo set_select('p_state', 'Punjab'); ?>>Punjab</option>
                            <option value="Rajasthan" <?php echo set_select('p_state', 'Rajasthan'); ?>>Rajasthan</option>
                            <option value="Sikkim" <?php echo set_select('p_state', 'Sikkim'); ?>>Sikkim</option>
                            <option value="Tamil Nadu" <?php echo set_select('p_state', 'Tamil Nadu'); ?>>Tamil Nadu</option>
                            <option value="Telangana" <?php echo set_select('p_state', 'Telangana'); ?>>Telangana</option>
                            <option value="Tripura" <?php echo set_select('p_state', 'Tripura'); ?>>Tripura</option>
                            <option value="Uttar Pradesh" <?php echo set_select('p_state', 'Uttar Pradesh'); ?>>Uttar Pradesh</option>
                            <option value="Uttarakhand" <?php echo set_select('p_state', 'Uttarakhand'); ?>>Uttarakhand</option>
                            <option value="West Bengal" <?php echo set_select('p_state', 'West Bengal'); ?>>West Bengal</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group"> <span class="col-md-6">28. Pin <span class="error">*</span></span>
                      <div class="col-md-6 ">
                        <input id="p_pin" name="p_pin" type="text" placeholder="Enter Postal Code" class="form-control input-selector p_add" required data-parsley-type="number"data-parsley-maxlength="7" value="<?php echo set_value('p_pin'); ?>" data-parsley-trigger-after-failure="focusout">
                      </div>
                    </div>
                  </div>
				  <div class="col-md-6">
                <div class="form-group min50"> <span class="col-md-3">29. Phone <span class="error"></span></span>
                  <div class="col-md-3 ">
                  <input name="p_phone_std" id="p_phone_std" type="text" placeholder="STD Code" class="form-control input-selector p_add"  data-parsley-type="number"data-parsley-maxlength="5" value="<?php echo set_value('p_phone_std'); ?>" data-parsley-trigger-after-failure="focusout">
                  </div>
                  <div class="col-md-6 ">
                  	<input name="p_phone" id="p_phone" type="text" placeholder="Enter Number" class="form-control input-selector p_add"  data-parsley-type="number"data-parsley-maxlength="10" value="<?php echo set_value('p_phone'); ?>" onInput="checkLength('p_phone')">
                  </div>
                </div>
              </div>
				  <div class="col-md-12">
                <div class="form-group min50"> <span class="col-md-3">30. Mobile <span class="error">*</span></span>
                  <div class="col-md-3 ">
                    <input name="p_mobile" id="p_mobile" type="text" placeholder="Enter Mobile Number" class="form-control input-selector p_add" required  data-parsley-type="number"data-parsley-maxlength="10" data-parsley-minlength="10" value="<?php echo set_value('p_mobile'); ?>" onInput="checkLength('p_mobile')">
                  </div>
                </div>
              </div>
                 </div>
            </div>
            <div class="clearfix"></div>
            <h2 class="inner">C. Educational Details</h2>
            <div class="table-responsive">
              <table class="table" id="educationTable">
                <thead>
                  <tr>
                    <th style="vertical-align:top;">S.No.</th>
                    <th style="vertical-align:top;"> Qualification (Academic, Professional and Technical) <span class="error">*</span> </th>
                    <th style="vertical-align:top;"> College /School/ Institute Name and Address <span class="error">*</span> </th>
                    <th style="vertical-align:top;"> Affiliated to University / Board <span class="error">*</span> </th>
                    <th style="vertical-align:top;"> Year of Passing<span class="error">*</span> </th>
                    <th style="vertical-align:top;"> Percentage <br>(eg 56.87)<span class="error">*</span> </th>
                    <th style="vertical-align:top;"> Division<span class="error">*</span> </th>
                    <th style="vertical-align:top;"> Program<span class="error">*</span> </th>
                  </tr>
                </thead>
                <tbody id="education">
                  <tr>
                    <td>1</td>
                    <td>10th
                      <input name="qualification[]" id="qualification1" type="hidden" placeholder="" class="form-control" value="10th"></td>
                    <td><input name="college_institute[]" id="college_institute1" type="text" placeholder="" class="form-control"  required  data-parsley-type="characterSpecial"><!-- /^(?:[0-9]|[a-z]+[0-9])[a-z0-9]*$/i--></td>
                    <td><input name="university[]" id="university1" type="text" placeholder="" class="form-control"  required data-parsley-type="characterSpecial"></td>
                    <td><select name="passing_year[]" id="passing_year1"  placeholder="" class="form-control" required>
                        <option value="">  Select</option>
                        <?php 
								   		$from = date('Y',strtotime('-40 years'));
										$to = date('Y',strtotime('1 years'));
										for($i=$from; $i<$to;$i++)
										{ ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?> </option>
                        <?php } ?>
                      </select></td>
                    <td><input name="percentage[]" id="percentage1"   placeholder="" class="form-control " type="text" required  data-parsley-range="[33, 100]" onkeypress="return check_digit(event,this,2,2);"></td><!-- data-parsley-pattern="^[0-9]*\.[0-9]{2}$" -->
                    <td><select name="division[]" id="division1"  placeholder="" class="form-control" required>
                            <option value="">  Select</option>
                            <option value="I">I </option>
                            <option value="II">II </option>
                            <option value="III">III</option>
                      </select></td>
                    <td><select name="program[]" id="program1" type="hidden" placeholder="" class="form-control" required>
                            <option value="">  Select</option>
                            <option value="Regular">Regular </option>
                            <option value="Part Time">Part Time </option>
                            <option value="Full Time">Full Time </option>
                             <option value="Correspondence">Correspondence </option>
                      </select></td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>12th
                      <input name="qualification[]" id="qualification2" type="hidden" placeholder="" class="form-control" value="12th"></td>
                   	<td><input name="college_institute[]" id="college_institute2" type="text" placeholder="" class="form-control"  required data-parsley-type="characterSpecial"></td>
                    <td><input name="university[]" id="university2" type="text" placeholder="" class="form-control"  required data-parsley-type="characterSpecial"></td>
                     <td><select name="passing_year[]" id="passing_year2"  placeholder="" class="form-control"  required >
                        <option value="">  Select</option>
                        <?php 
								   		$from = date('Y',strtotime('-40 years'));
										$to = date('Y',strtotime('1 years'));
										for($i=$from; $i<$to;$i++)
										{ ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?> </option>
                        <?php } ?>
                      </select></td>
                    <td><input name="percentage[]" id="percentage2"  placeholder="" class="form-control"  type="text" required  data-parsley-range="[33, 100]" onkeypress="return check_digit(event,this,2,2);"></td>
                    <td><select name="division[]" id="division2"  placeholder="" class="form-control" required>
                            <option value="">  Select</option>
                            <option value="I">I </option>
                            <option value="II">II </option>
                            <option value="III">III</option>
                      </select></td>
                    <td><select name="program[]" id="program2" type="" placeholder="" class="form-control"  required>
                            <option value="">  Select</option>
                            <option value="Regular">Regular </option>
                            <option value="Part Time">Part Time </option>
                            <option value="Full Time">Full Time </option>
                             <option value="Correspondence">Correspondence </option>
                      </select></td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>
                    	<select name="qualification[]"  type="" placeholder="" class="form-control"  required id="qualification3" onchange="showQualificationText1(this.value);" >
                            <option value="">Graduation</option>
                            <option value="B Sc">B.Sc.</option>
                            <option value="B A">B.A.</option>
                            <option value="B Com">B.Com.</option>
                            <option value="B Sc(Hons)">B.Sc.(Hons.)</option>
                            <option value="B A (Hons)">B.A (Hons.)</option>
                            <option value="B Com (Hons)">B.Com. (Hons.)</option>
                            <option value="B E">B.E.</option>
                            <option value="B Tech">B.Tech</option>
                            <option value="B E (CS)">B.E (CS)</option>
                            <option value="B Tech (CS)">B.Tech (CS)</option>
                            <option value="LLB">LLB</option>
                            <option value="B.E (IT)">B.E (IT)</option>
                            <option value="B.Tech (IT)">B.Tech (IT)</option>
                            <option value="BBA">BBA</option>
                            <option value="OthersB">Others</option>
                      </select>
                      <input name="qualification1" id="qualificationOther3" type="text" placeholder="" class="form-control mar5" style="display:none;" data-parsley-type="characterSpecial">
                    </td>
                   	<td><input name="college_institute[]" id="college_institute3" type="text" placeholder="" class="form-control"  required data-parsley-type="characterSpecial"></td>
                    <td><input name="university[]" id="university3" type="text" placeholder="" class="form-control"  required data-parsley-type="characterSpecial"></td>
                    <td><select name="passing_year[]" id="passing_year3" placeholder="" class="form-control"  required>
                        <option value="">  Select</option>
                        <?php 
								   		$from = date('Y',strtotime('-40 years'));
										$to = date('Y',strtotime('1 years'));
										for($i=$from; $i<$to;$i++)
										{ ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?> </option>
                        <?php } ?>
                      </select></td>
                    <td><input name="percentage[]" id="percentage3" placeholder="" class="form-control"  type="text" required data-parsley-range="[33, 100]" onkeypress="return check_digit(event,this,2,2);"></td>
                    <td><select name="division[]" id="division3"  placeholder="" class="form-control"  required >
                            <option value="">  Select</option>
                            <option value="I">I </option>
                            <option value="II">II </option>
                            <option value="III">III</option>
                      </select></td>
                    <td><select name="program[]" type="" id="program3" placeholder="" class="form-control" required>
                            <option value="">  Select</option>
                            <option value="Regular">Regular </option>
                            <option value="Part Time">Part Time </option>
                            <option value="Full Time">Full Time </option>
                             <option value="Correspondence">Correspondence </option>
                      </select></td>
                  </tr>
                  <tr>
                    <td>4</td>
                    <td>
                    	<select name="qualification[]" type="hidden" placeholder="" class="form-control" id="qualification4"   onchange="showQualificationText2(this.value);"  >
                            <option value="">Post Graduation</option>
                            <option value="MBA (Finance)">MBA (Finance)</option>
                            <option value="MBA (Others)">MBA (Others)</option>
                            <option value="LLM">LLM</option>
                            <option value="Chartered Accountant">Chartered Accountant</option>
                            <option value="ICWA">ICWA</option>
                            <option value="M Tech">M.Tech</option>
                            <option value="MA">MA</option>
                            <option value="M Com">M.Com.</option>
                            <option value="MSc">MSc</option>  
                            <option value="MCA">MCA</option>
                            <option value="OthersM">Others</option>
                      </select>
                      <input name="qualification2" id="qualificationOther4" type="text" placeholder="" class="form-control mar5" style="display:none;" data-parsley-type="characterSpecial">
                    </td>
                    <td><input name="college_institute[]" id="college_institute4" type="text" placeholder="" class="form-control"  data-parsley-type="characterSpecial"></td>
                    <td><input name="university[]" id="university4" type="text" placeholder="" class="form-control"   data-parsley-type="characterSpecial"></td>
                    <td><select name="passing_year[]"  id="passing_year4" placeholder="" class="form-control"   >
                        <option value="">Select</option>
                        <?php 
								   		$from = date('Y',strtotime('-40 years'));
										$to = date('Y',strtotime('1 years'));
										for($i=$from; $i<$to;$i++)
										{ ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?> </option>
                        <?php } ?>
                      </select></td>
                    <td><input name="percentage[]" id="percentage4" placeholder="" class="form-control"  type="text"  data-parsley-range="[33, 100]" onkeypress="return check_digit(event,this,2,2);"></td>
                    <td><select name="division[]" id="division4" placeholder="" class="form-control" >
                            <option value="">Select</option>
                            <option value="I">I </option>
                            <option value="II">II </option>
                        <option value="III">III</option>
                      </select></td>
                    <td><select name="program[]" id="program4" type="" placeholder="" class="form-control" >
                            <option value="">Select</option>
                            <option value="Regular">Regular </option>
                            <option value="Part Time">Part Time </option>
                            <option value="Full Time">Full Time </option>
                             <option value="Correspondence">Correspondence </option>
                      </select></td>
                  </tr>
                  
                  <tr id="dynamicInput" class="tr_5">
                   	<td id="tr5" class="" colspan="8">
                    	<span id="sr5" class="showTd" style="display:none;">5</span>
                    	<img src="<?php echo base_url();?>assets/images/add.png" class="add" onClick="addEducation('dynamicInput');" id="addRow">
                   	</td>
                     <td class="showTd" style="display:none;">
                     	<div style="width:100%;">
                        <img src="<?php echo base_url();?>assets/images/delete.png" class="delete" onclick="removeEducation(5)" id="deleteRow" style="display:none;display:inline-block">
                     	<input name="qualification[]" id="qualification5" type="text" placeholder="" class="crosstxt mar5" data-parsley-type="characterSpecial">
                    	</div>
                    </td>
                    <td class="showTd" style="display:none;"><input name="college_institute[]" id="college_institute5" type="text" placeholder="" class="form-control" value="" data-parsley-type="characterSpecial"></td>
                
                    <td class="showTd" style="display:none;"><input name="university[]"  id="university5" type="text" placeholder="" class="form-control" value="" data-parsley-type="characterSpecial"></td>
                   
                    <td class="showTd" style="display:none;"><select name="passing_year[]" id="passing_year5" placeholder="" class="form-control">
                        <option value="">Select</option>
                        <?php 
								   		$from = date('Y',strtotime('-40 years'));
										$to = date('Y',strtotime('1 years'));
										for($i=$from; $i<$to;$i++)
										{ ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?> </option>
                        <?php } ?>
                      </select></td>
                    <td class="showTd"style="display:none;" ><input name="percentage[]" id="percentage5" placeholder="" class="form-control" value="" type="text" data-parsley-range="[33, 100]" onkeypress="return check_digit(event,this,2,2);"></td>
                    <td class="showTd" style="display:none;"><select name="division[]" id="division5"  placeholder="" class="form-control">
                        <option value="">Select</option> 
                        <option value="I">I </option>
                        <option value="II">II </option>
                        <option value="III">III</option>
                      </select></td>
                    <td class="showTd" style="display:none;"><select name="program[]" id="program5" type="" placeholder="" class="form-control">
                        <option value="">Select</option>
                        <option value="Regular">Regular </option>
                        <option value="Part Time">Part Time </option>
                        <option value="Full Time">Full Time </option>
                        <option value="Correspondence">Correspondence </option>
                      </select></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <h2 class="inner">D. Employment Details (If Any)</h2>
            <div class="table-responsive">
              <table class="table" id="employeeTable">
                <thead>
                  <tr>
                    <th style="vertical-align:top;">Starting from most recent</th>
                    <th style="vertical-align:top;">Name of the Organisation </th>
					<th style="vertical-align:top;">Address of the Organisation   </th>
                    <th style="vertical-align:top;">Type of employer</th>
                    <th style="vertical-align:top;">Post held</th>
                    <th style="vertical-align:top;">Department</th>
                    <th style="vertical-align:top;">Period From</th>
                    <th style="vertical-align:top;">Period To</th>
                    <th style="vertical-align:top;">No. of years of experience</th>
                    <th style="vertical-align:top;">Nature of duties/ responsibilities</th>
                  </tr>
                </thead>
                <tbody id="employer">
                  <tr>
                    <td>Current Employer</td>
                    <td><input name="organisation[]" id="organisation1"  type="text" placeholder="" class="form-control" data-parsley-type="characterSpecial"></td>
					<td><input class="form-control"  id="organisation_address1" type="text" name="organisation_address[]" data-parsley-type="characterSpecial"></td>
                    <td>
                        <select name="employer_type[]" id="employer_type1" type="" placeholder="" class="form-control" >
                            <option value="">  Select</option>
                            <option value="PSU">PSU </option>
                            <option value="Private Sector">Private Sector </option>
                            <option value="Govt. Sector">Govt. Sector</option>
                            <option value="Semi Government">Semi Government</option>
                            <option value="Govt. Autonomous Bodies">Govt. Autonomous Bodies</option>
                            <option value="CPSE's">CPSE's</option>
                            <option value="Others">Others</option>
                      </select>
                    </td>
                  	<td><input name="designation[]" id="designation1"  type="text" placeholder="" class="form-control"  data-parsley-type="characterSpecial"></td>
                    <td><input name="department[]"  id="department1" type="text" placeholder="" class="form-control"  data-parsley-type="characterSpecial"></td>
                    <td><input name="periodFrom[]" id="periodFrom1"  type="text" placeholder="" class="form-control commonDatepickerFrom" value="" onchange="CalcExperience(this.value,1)" readonly="readonly"></td>
                    <td><input name="periodTo[]" id="periodTo1"  type="text" placeholder="" class="form-control commonDatepickerTo"  value="" onchange="CalcExperience(this.value,1)" readonly="readonly"></td>
                    <td><input name="experience[]" id="experience1" value="0.0"  type="text" placeholder="" class="form-control" readonly onFocus="checkExperience()"></td>
                    <td><textarea class="form-control" id="responsibility1" name="responsibility[]" data-parsley-pattern="/^[a-zA-Z][a-zA-Z0-9.,()\/_& '-/\n]+$/"></textarea></td>
                  </tr>
                  <tr>
                    <td>Previous Employer- I</td>
                    <td><input name="organisation[]" id="organisation2"  type="text" placeholder="" class="form-control"   data-parsley-type="characterSpecial"></td>
                    <td><input class="form-control"  id="organisation_address2" type="text" name="organisation_address[]" data-parsley-type="characterSpecial"></td>
					<td>
                        <select name="employer_type[]" id="employer_type2" type="" placeholder="" class="form-control" >
                            <option value="">  Select</option>
                            <option value="PSU">PSU </option>
                            <option value="Private Sector">Private Sector </option>
                            <option value="Govt. Sector">Govt. Sector</option>
                            <option value="Semi Government">Semi Government</option>
                            <option value="Govt. Autonomous Bodies">Govt. Autonomous Bodies</option>
                            <option value="CPSE's">CPSE's</option>
                            <option value="Others">Others</option>
                      	</select>
                    </td>
                    <td><input name="designation[]" id="designation2" type="text" placeholder="" class="form-control"  data-parsley-type="characterSpecial"></td>
                    <td><input name="department[]" id="department2"  type="text" placeholder="" class="form-control"  data-parsley-type="characterSpecial"></td>
                   <td><input name="periodFrom[]"  id="periodFrom2"  type="text" placeholder=""  class="form-control commonDatepickerFrom" value=""  onchange="CalcExperience(this.value,2)" readonly="readonly"></td>
                    <td><input name="periodTo[]"  id="periodTo2"  type="text" placeholder=""  class="form-control commonDatepickerTo" value="" onchange="CalcExperience(this.value,2)" readonly="readonly" ></td>
                    <td><input name="experience[]" id="experience2" value="0.0" readonly type="text"   placeholder="" class="form-control"  onFocus="checkExperience()"></td>
                    <td><textarea class="form-control" id="responsibility2"  data-parsley-pattern="/^[a-zA-Z][a-zA-Z0-9.,()\/_& '-/\n]+$/" name="responsibility[]"></textarea></td>
                  </tr>
                  <tr>
                    <td>Previous Employer- II</td>
                    <td><input name="organisation[]" id="organisation3"  type="text" placeholder="" class="form-control"  data-parsley-type="characterSpecial"></td>
                   <td><input class="form-control"  id="organisation_address3" type="text" name="organisation_address[]" data-parsley-type="characterSpecial"></td>
				   <td>
                        <select name="employer_type[]" id="employer_type3" type="" placeholder="" class="form-control">
                            <option value="">  Select</option>
                            <option value="PSU">PSU </option>
                            <option value="Private Sector">Private Sector </option>
                            <option value="Govt. Sector">Govt. Sector</option>
                            <option value="Semi Government">Semi Government</option>
                            <option value="Govt. Autonomous Bodies">Govt. Autonomous Bodies</option>
                            <option value="CPSE's">CPSE's</option>
                            <option value="Others">Others</option>
                      </select>
                    </td>
                    <td><input name="designation[]"id="designation3"  type="text" placeholder="" class="form-control"  data-parsley-type="characterSpecial"></td>
                    <td><input name="department[]" id="department" type="text" placeholder="" class="form-control"  data-parsley-type="characterSpecial"></td>
                   <td><input name="periodFrom[]" id="periodFrom3"  type="text" placeholder="" class="form-control commonDatepickerFrom" value=""  onchange="CalcExperience(this.value,3)" readonly="readonly"></td>
                    <td><input name="periodTo[]" id="periodTo3"  type="text" placeholder="" class="form-control commonDatepickerTo" value="" onchange="CalcExperience(this.value,3)" readonly="readonly"></td>
                    <td><input name="experience[]" id="experience3"  value="0.0" type="text" placeholder="" readonly class="form-control" onFocus="checkExperience()"></td>
                    <td><textarea class="form-control" id="responsibility3" name="responsibility[]" data-parsley-pattern="/^[a-zA-Z][a-zA-Z0-9.,()\/_& '-/\n]+$/"></textarea>
                    </td>
                  </tr>
                  <tr id="dynamicEmployer">
                    <td colspan="10" id="emp4">
                    
                    	<img src="<?php echo base_url();?>assets/images/add.png" class="add" onClick="addEmployer('dynamicEmployer');"> 
                    	</td>
                    <td class="showTd1" style="display:none;">
                    	<img src="<?php echo base_url();?>assets/images/delete.png" class="delete" onclick="removeEmployee(4)" id="deleteRow1" style="display:none;display:inline-block">
                    	<input name="organisation[]" id="organisation4" type="text" data-parsley-type="characterSpecial" placeholder="" class="crosstxt">
                    </td>
					<td class="showTd1" style="display:none;"><input class="form-control"  id="organisation_address4" type="text" name="organisation_address[]" data-parsley-type="characterSpecial"></td>
                   	<td class="showTd1" style="display:none;">
                        <select name="employer_type[]" id="employer_type4" type="" placeholder="" class="form-control">
                            <option value="">  Select</option>
                            <option value="PSU">PSU </option>
                            <option value="Private Sector">Private Sector </option>
                            <option value="Govt. Sector">Govt. Sector</option>
                            <option value="Semi Government">Semi Government</option>
                            <option value="Govt. Autonomous Bodies">Govt. Autonomous Bodies</option>
                            <option value="CPSE's">CPSE's</option>
                            <option value="Others">Others</option>
                      </select> 
                    <td class="showTd1" style="display:none;"><input name="designation[]" id="designation4" type="text" placeholder="" class="form-control" data-parsley-type="characterSpecial"></td>
                    <td class="showTd1" style="display:none;"><input name="department[]" id="department4" type="text" placeholder="" class="form-control" data-parsley-type="characterSpecial"></td>
                   	<td class="showTd1" style="display:none;"><input name="periodFrom[]" id="periodFrom4"  type="text" placeholder="" class="form-control commonDatepickerFrom" value=""  onchange="CalcExperience(this.value,4)" readonly="readonly"></td>
                    <td class="showTd1" style="display:none;"><input name="periodTo[]" id="periodTo4"  type="text" placeholder="" class="form-control commonDatepickerTo" value="" readonly onchange="CalcExperience(this.value,4)" ></td>
                    <td class="showTd1" style="display:none;"><input name="experience[]" id="experience4" readonly value="0.0"  type="text" placeholder="" class="form-control" onFocus="checkExperience()" ></td>
                    <td class="showTd1" style="display:none;"><textarea class="form-control" name="responsibility[]" id="responsibility4" data-parsley-pattern="/^[a-zA-Z][a-zA-Z0-9.,()\/_& '-/\n]+$/"></textarea></td>
                  </tr>
                </tbody>
              </table>
              <input type="hidden" name="expEligibility" id="expEligibility" value="">
              <span id="expEligible" class="eligible"></span>
            </div>
            <div id="paymentDetails" >
            <h2 class="inner"><span id="">E. </span>Cost of Application Details:</h2>
            <div class="col-md-12">
              <div class="form-group"> <span class="col-md-3">SBCollect Reference Number <span class="error">*</span></span>
                <div class="col-md-4 ">
                  <input name="reference_no" id="reference_no" type="text" placeholder="Enter SBCollect Reference Number" class="form-control" required  onblur="checkReferenceNo();" data-parsley-pattern="/^(?:[0-9]|[a-z]+[0-9])[a-z0-9]*$/i" value="<?php echo set_value('reference_no'); ?>"><!--data-parsley-pattern="^[a-zA-Z0-9]{4,10}$  -->
                  <span id="reference_no_err" class="eligible"></span>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group"> <span class="col-md-3">Date of Payment <span class="error">*</span></span>
                <div class="col-md-4">
                  <input name="payment_date"  id="payment_date" type="text" class="form-control"  readonly style="z-index:5;" required value="<?php echo set_value('payment_date'); ?>">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group"> <span class="col-md-3">Upload payment receipt (Upload pdf or jpg file only, sized less than 100 kb) <span class="error">*</span></span>
                <div class="col-md-9">
                  <div class="input-group browsefile"  style="z-index:1">
              <input type="text" class=" form-control" readonly required id="payment_text">
                <div id="error_payment_receipt"></div>
                 <br>
                 <div id="error_payment_receipt_size"></div>
              <span class="input-group-btn"> <span class="btn btn-primary btn-file"  style="z-index:1"> Browse&hellip;
              <input type="file" name="payment_receipt" id="payment_receipt" style="z-index:1">
              </span> </span> 
             	<span class="tick payment_text" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span> 
             </div>
                </div>
              </div>
            </div>
             </div>
             
            <h2 class="inner"><span id="f_other1"> F. Other Details</span><span id="f_other" style="display:none">E. Other Details</span></h2>
            
              <div class="form-group"> <span class="col-md-8">31. Are you currently under service agreement/bond with your existing employer? <span class="error">*</span></span>
                <div class="col-md-4 ">
                  <div class="col-md-6">
                  <p>
                    <input type="radio" class="service_agreement" name="service_agreement" value="Yes" required <?php echo set_radio('service_agreement', 'Yes'); ?>>Yes 
                  	<input type="radio" class="service_agreement" name="service_agreement" value="No" <?php echo set_radio('service_agreement', 'No'); ?>>No
                  
                  </p></div>
                </div>
              </div>
            
            
              <div class="form-group"> <span class="col-md-8">32. Have you ever been charged or convicted for any criminal offense in India or abroad? <span class="error">*</span> </span>
                <div class="col-md-4 ">
                  <div class="col-md-6">
                  <p>
                    <input type="radio" class="criminal_offence" name="criminal_offence" value="Yes" required <?php echo set_radio('criminal_offence', 'Yes'); ?>>Yes
                  	<input type="radio" class="criminal_offence" name="criminal_offence" value="No" <?php echo set_radio('criminal_offence', 'No'); ?>>No
                   </p></div>
                </div>
              </div>
            
            
              <div class="form-group"> <span class="col-md-8"> 33. Are you related to anyone who works for the company?<span class="error">*</span> </span>
                <div class="col-md-4 ">
                  <div class="col-md-6">
                    <p><input type="radio" class="relative_company" name="relative_company" value="Yes" required <?php echo set_radio('relative_company', 'Yes'); ?>> Yes 
                  	<input type="radio" class="relative_company" name="relative_company" value="No" <?php echo set_radio('relative_company', 'No'); ?>>No
                  </p></div>
                  <div class="col-md-10" >
                    <input type="text" class="relative_company_name form-control" name="relative_company_name" id="relative_company_name" placeholder="Mention name and designation" value="<?php echo set_value('relative_company_name'); ?>" style="display:none;" data-parsley-type="characterSpecial"> 
               		</div>
                </div>
              </div>
            
           
              <div class="form-group"> <span class="col-md-3">34. Academics Achievements (in less than 1000 characters) :</span>
                <div class="col-md-9">
                  <textarea class="form-control academic_achievement" rows="3" name="academic_achievement" id="academic_achievement" data-parsley-pattern="/^[a-zA-Z][a-zA-Z0-9.,()\/_& '-/\n]+$/" data-parsley-maxlength="1000"><?php echo set_value('academic_achievement'); ?></textarea><!-- data-parsley-maxlength="1000" -->
                </div>
              </div>
            
            
              <div class="form-group"> <span class="col-md-3">35. Professional Achievements (in less than 1000 characters):</span>
                <div class="col-md-9">
                  <textarea class="form-control professional_achievement" rows="3" name="professional_achievement" id="professional_achievement"  data-parsley-pattern="/^[a-zA-Z][a-zA-Z0-9.,()\/_& '-/\n]+$/" data-parsley-maxlength="1000"><?php echo set_value('professional_achievement'); ?></textarea>
                </div>
              </div>
           
            
              <div class="form-group"> <span class="col-md-3">36. Hobbies if any (in less than 1000 characters):</span>
                <div class="col-md-9">
                  <textarea class="form-control" rows="3" name="hobby" id="hobby" data-parsley-pattern="/^[a-zA-Z][a-zA-Z0-9.,()\/_& '-/\n]+$/" data-parsley-maxlength="1000" ><?php echo set_value('hobby'); ?></textarea>
                </div>
              </div>
            
            
              <div class="form-group"> <span class="col-md-3">37. Details of outstanding loans with the present employer (in less than 1000 characters):</span>
                <div class="col-md-9 ">
                  <textarea class="form-control" rows="3" name="loan_detail" id="loan_detail" data-parsley-pattern="/^[a-zA-Z][a-zA-Z0-9.,()\/_& '-/\n]+$/" data-parsley-maxlength="1000"><?php echo set_value('loan_detail'); ?></textarea>
                </div>
              </div>
            
            
              <div class="form-group"> <span class="col-md-3"> 38. Joining time required: <span class="error">*</span></span>
                <div class="col-md-6 ">
                  	<!--<input name="joining_time" id="joining_time" type="text" placeholder="Enter time Required To Join" class="form-control">-->
                    <div class="form-group">
                        <span class="col-md-3"> Months:
                        <select name="joining_time_month" id="joining_time1" placeholder="" class="form-control" required>
                            <option value="">  Select</option>
                            <?php
                                for($i=0; $i<=12;$i++)
                                { ?>
                                    <option value="<?php echo $i;?>" <?php echo set_select('joining_time_month', $i); ?>><?php echo $i;?> </option>
                            <?php } ?>
                          </select>
                          </span>
                          
                          <span class="col-md-3">Days : 
                          <select name="joining_time_day" id="joining_time2" placeholder="" class="form-control" required>
                            <option value="">  Select</option>
                            <?php
                                for($i=0; $i<=31;$i++)
                                { ?>
                                    <option value="<?php echo $i;?>" <?php echo set_select('joining_time_day', $i); ?>><?php echo $i;?> </option>
                            <?php } ?>
                          </select>
                          </span>
                      </div>
                   </div>
              </div>
           
            <div style="clear:both;"></div>
            <h2 class="inner"><span id="g_document1">G. List of documents to be uploaded:</span><span id="g_document" style="display:none">F. List of documents to be uploaded:</span></h2>
            <div class="row">
            <div class="fileUploads" >
                <span class="col-md-6"><span class="filenumber">39</span>.  Upload Scanned Photograph (Upload jpeg or jpg file only, sized less than 20 kb) <span class="error">*</span></span>
                  <div class="col-lg-6 input-group browsefile">
                    <input type="text" id="photo_text" class=" form-control" readonly onChange="showFileSize();" required>
                     <div id="error_photo"></div>
                     <br>
                     <div id="error_photo_size"></div>
                    <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;
                    <input type="file" name="photograph" id="photograph" >
                    </span> </span>
                    <span class="tick photo_text" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
                </div>
            </div>
            <div class="fileUploads" >
                <span class="col-md-6"><span class="filenumber">40</span>. Upload scanned Signature (Upload jpeg or jpg file only, sized less than 20 kb)<span class="error">*</span></span>
                  <div class="col-lg-6 input-group browsefile">
                    <input type="text" id="signature_text" class=" form-control" readonly required>
                    <input type="hidden" name="uploadedSignature" id="uploadedSignature" value="">
                     <div id="error_signature"></div>
                     <br>
                     <div id="error_signature_size"></div>
                    <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;
                    <input type="file" name="signature" id="signature" >
                    </span> </span>
                    <span class="tick signature_text" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
                </div>
            </div>
            
            <div class="fileUploads" >
                <span class="col-md-6"><span class="filenumber">41</span>. Upload Scanned Copy for the proof of date of birth<br>(Upload jpg, jpeg or pdf file only, sized less than 100 kb)<span class="error">*</span>:</span>
                  <div class="col-lg-6 input-group browsefile">
                    <input type="text" class=" form-control" id="dob_proof_text" readonly required>
                      <div id="error_dob"></div>
                     <br>
                     <div id="error_dob_size"></div>
                    <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;
                    <input type="file" name="dob_proof" id="dob_proof">
                    </span> </span>
                    <span class="tick dob_proof_text" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
                </div>
            </div>
            <br>
            <div class="fileUploads" >
            <span class="col-md-6"><span class="filenumber">42</span>. Upload Scanned Copy of qualifying educational certificates<br>(Upload pdf file only, sized less than 500 kb): <span class="error">*</span></span>
              <div class="col-lg-6 input-group browsefile">
                <input type="text" class=" form-control" id="edu_certificate_text" readonly required>
                 <div id="error_educat"></div>
                 <br>
                 <div id="error_educat_size"></div>
                <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;
                <input type="file" name="edu_certificate" id="edu_certificate">
                </span> </span>
                <span class="tick edu_certificate_text" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
            </div>
            </div>
            <br>
            <div class="fileUploads" >
                <span class="col-md-6"><span class="filenumber">43</span>. Upload Scanned Copy of present employment certificate<br> (Upload pdf file only, sized less than 100 kb): <span class="error" id="empl_exp_red" style="display:none">*</span> <span id="emploementReq" class="error"></span></span>
                  <div class="col-lg-6 input-group browsefile">
                    <input type="text" class=" form-control" readonly id="emp_certificate1">
                     <div id="error_empcert"></div>
                     <br>
                      <div id="error_empcert_size"></div>
                    <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;
                    <input type="file" name="emp_certificate" id="emp_certificate">
                    </span> </span>
                    <span class="tick emp_certificate1" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
                </div>
                <br>
             </div>
            
            <div id="casteCertificate" class="fileUploads" >
            <span class="col-md-6"><span class="filenumber">44</span>. Upload Scanned Copy of caste certificate<br> (Upload pdf file only, sized less than 100 kb): <span id="casteReq" class="error"></span></span>
              <div class="col-lg-6 input-group browsefile">
                <input type="text" class=" form-control" readonly id="caste_certificate1" >
                 <div id="error_cast"></div>
                 <br>
                 <div id="error_cast_size"></div>
                <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;
                <input type="file" name="caste_certificate" id="caste_certificate" >
                </span> </span> 
                	<span class="tick caste_certificate1" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
                </div>
                <br>
            </div>
           
            <div id="domicileCertificate" class="fileUploads" style="display:none;"> 
            <span class="col-md-6"><span class="filenumber">45</span>. Upload scanned copy of proof confirming domicile of J & K during 1/1/1980 to 31/12/1989 (Upload pdf file only, sized less than 100 kb): <span id="cdomicileReq" class="error" >*</span></span>
              <div class="col-lg-6 input-group browsefile">
                <input type="text" class=" form-control" readonly id="domicile_certificate1" >
                 <div id="error_domicile"></div>
                 <br>
                 <div id="error_domicile_size"></div>
                <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;
                <input type="file" name="domicile_certificate" id="domicile_certificate" >
                </span> </span>
              	<span class="tick domicile_certificate1" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
               </div>
               <br>
            </div>
            
            
            <div id="servicemanCertificate" class="fileUploads" style="display:none;"> 
            <span class="col-md-6"><span class="filenumber">46</span>. Upload scanned copy of proof confirming status of Ex-serviceman (Upload pdf file only, sized less than 100 kb): <span id="servicemanReq" class="error" >*</span></span>
              <div class="col-lg-6 input-group browsefile">
                <input type="text" class=" form-control" readonly id="servicemanCertificate1" >
                 <div id="error_serviceman"></div>
                 <br>
                 <div id="error_serviceman_size"></div>
                <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;
                <input type="file" name="serviceman_certificate" id="serviceman_certificate" >
                </span> </span>
                	<span class="tick servicemanCertificate1" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
                 </div>
                
                <br>
            </div>
            
            <div class="disabilityCertificate fileUploads"  style="display:none;">
             <span class="col-md-6"><span class="filenumber">47</span>. Upload Scanned Copy of disability certificate(Upload pdf file only, sized less than 100 kb):<span class="error">*</span></span>
              <div class="col-lg-6 input-group browsefile">
                <input type="text" class=" form-control" readonly id="pwd_certificate1">
                  <div id="error_pwd_certificate"></div>
                <br>
                 <div id="error_pwd_certificate_size"></div>
                <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;

                <input type="file" name="pwd_certificate" id="pwd_certificate" >
                </span> </span>
              	<span class="tick pwd_certificate1" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
               </div>
            </div>
            
             <div class="nocCertificate fileUploads"  style="display:none;">
             <span class="col-md-6"><span class="filenumber">48</span>. Upload scanned copy of the No Objection Certification(NOC) (Upload pdf file only, sized less then 100kb):<span class="error">*</span></span>
              <div class="col-lg-6 input-group browsefile">
                <input type="text" class=" form-control" readonly id="noc_certificate1">
                  <div id="error_noc_certificate"></div>
                <br>
                 <div id="error_noc_certificate_size"></div>
                <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;

                <input type="file" name="noc_certificate" id="noc_certificate" >
                </span> </span>
              	<span class="tick noc_certificate1" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
               </div>
            </div>
            
            <div class="fileUploads" >
                <span class="col-md-6"><span class="filenumber">49</span>. Resume (Upload pdf,doc or docx file only, sized less than 100 kb): <span class="error">*</span></span>
                  <div class="col-lg-6 input-group browsefile ">
                 
                    <input type="text" class=" form-control"  required id="resumetext" name="resumetext" value="" readonly>
                    <div id="error_resume"></div>
                    <br>
                     <div id="error_resume_size"></div>
                    <span class="input-group-btn"> <span class="btn btn-primary btn-file"> Browse&hellip;
                    <input type="file" name="resume" id="resume">
                  
                    
                    </span> </span>
                    <span class="tick resumetext" style="display:none;"><i class="fa fa-check-square" aria-hidden="true"></i></span>
                   
                    <!--<i class="check fa fa-check-circle" aria-hidden="true"></i>-->
                </div>
                <br>
            </div>
            
            <div class="col-md-12 col-xs-12">
              <div class="form-group">
                <label class="">
                  <input type="checkbox" id="termCondition" name="termCondition" value="1" required <?php echo set_checkbox('termCondition', '1'); ?>>
                  <span style="text-align:justify;">&nbsp;I hereby declare that I have read all the terms and conditions mentioned in the Advertisement No.: IFCI/2016/01 and I fulfill the same. All the statements made in the application are true, complete and correct to the best of my knowledge and belief. I also declare that I have submitted one application only for the above post. I am duly aware that in the event if any particulars or information furnished by me is found to be false/incorrect/incomplete or if found indulging in some unlawful act at any time, my candidature for the post is liable to be summarily rejected/cancelled and in the event of any statement/ information submitted found false/incorrect even after my appointment, my services are liable to be terminated.</span> </label>
                 <!-- <a href="" id="testResume">view File</a>-->
              </div>
            </div>
            <!-- I Agree -->
            </div>
            <div class="row">
              <div class="form-group">
                <div class="col-md-12 text-center atm_m10-top">
                  <!--<button type="submit" class="btn btn3 nextBtn" name="submitcareerform" id="submitBtn" onclick="">Submit</button>-->
                  <button type="submit" class="btn btn3 nextBtn" name="submitcareerform" id="submitBtn" onclick="return confirm('Data once submitted cannot be modified later. Kindly verify the details filled in the application form before pressing submit button.');" onSubmit="document.getElementById('submitBtn').disabled=true;">Submit</button>
                  <button type="button" class="btn btn-info" id="previewform" data-toggle="modal" data-target="#myModalpreview">Preview</button>
                  <a class="btn btn-primary" href="<?php echo current_url(); ?>">Reset</a>
                </div>
              </div>
            </div>
            
            </div>
            
            
          </div>
        </div>
        </div>
        <!-- Modal -->
<div id="myModalpreview" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Preview</h4>
      </div>
      <div class="modal-body">
       <table width="800" cellspacing="0" cellpadding="0" align="center" border="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:28px; padding:10px 15px;">
      <tbody>
        <tr>
          <td valign="top" style="line-height:28px; font-size:16px;">
          <p><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJUAAABlCAYAAABEMKFWAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2ZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo3MUM4OUUzREE5NTBFNTExQTlCOUNBRUY0MDhEQUE3NiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpDRUQyM0EzODZFNjcxMUU1QkI0MkZCNDc2QUE5MUZDNiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpDRUQyM0EzNzZFNjcxMUU1QkI0MkZCNDc2QUE5MUZDNiIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M2IChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkY3MzBFM0M5Nzk1MUU1MTFCRkQ4OEFFOTY0MzVBODdEIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjcxQzg5RTNEQTk1MEU1MTFBOUI5Q0FFRjQwOERBQTc2Ii8+IDwvcmRmOkRlc2NyaXB0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" style=" margin-left:225px;"></p>
          <center><strong>Application for the post of : <span id="positionApply"></span><br /></strong></center> 
          <br>
          <p id="advance_application" style="display:none">Advance Application</p>
          </td>
        <td align="right" style="text-align:center;" width="20%" valign="middle">
          <p style="width:130px; height:130px; border:1px solid #ccc;"><img src="" id="p_photograph" style="display:none;"></p>
          <p style="width:130px; height:60px; border:1px solid #ccc; margin:5px 0;"><img src="" id="p_signature" style="display:none;"></p>
          </td>
        </tr>
      </tbody>
    </table><table width="800" cellspacing="0" cellpadding="0" align="center" border="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:28px; padding:10px 15px;">
        <tbody>
          <tr>
            <td colspan="4">
              <h3 style="font-size:18px; border-bottom:1px solid #ccc; padding-bottom:8px; color:#437abd; margin:5px 0;">
              &raquo; Personal Details</h3>
            </td>
          </tr>
          <tr>
            <td width="4%" align="left"><strong>1.&nbsp;</strong></td>
            <td width="46%"><b>Name of Applicant</b></td>
            <td width="2%" align="center">:</td>
            <td><b><span id="p_name_applicant"></span></b></td>
          </tr>
          <tr>
            <td align="left"><strong>2.&nbsp;</strong></td>
            <td><b>Former / Maiden Name</b></td>
            <td width="2%" align="center">:</td>
            <td><span id="p_maiden_name"></span></td>
          </tr>
          <tr>
            <td align="left"><strong>3.&nbsp;</strong></td>
            <td><b><span id="p_father_husband"></span></b></td>
            <td width="2%" align="center">:</td>
            <td><span id="p_fathers_name"></span></td>
          </tr>
          <tr>
            <td align="left"><strong>4.&nbsp;</strong></td>
            <td><b>Date of Birth</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_dob">30-12-1993</td>
          </tr>
          <tr>
            <td align="left"><strong>5.&nbsp;</strong></td>
            <td><b>Age as on 30/03/2016</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_age"></td>
          </tr>
          <tr>
            <td align="left"><strong>6.&nbsp;</strong></td>
            <td><b>Gender</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_gender"></td>
          </tr>
          <tr>
            <td align="left"><strong>7.&nbsp;</strong></td>
            <td><b>Nationality</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_nationality"></td>
          </tr>
          <tr>
            <td align="left"><strong>8.&nbsp;</strong></td>
            <td><b>Marital Status</b></td>
            <td width="2%" align="center">:</td>
            <td colspan="2" id="p_m_status"></td>
          </tr>
          <tr>
            <td align="left"><strong>9.&nbsp;</strong></td>
            <td><b>Category</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_caregory"></td>
          </tr>
          <tr>
            <td align="left"><strong>10.&nbsp;</strong></td>
            <td><b>Mobile No.</b></td>
            <td width="2%" align="center">:</td>
            <td colspan="2" id="p_mobile_number"></td>
          </tr>
          <tr>
            <td align="left"><strong>11.&nbsp;</strong></td>
            <td><b>Email ID</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_email"></td>
          </tr>
          <tr>
            <td align="left"><strong>12.&nbsp;</strong></td>
            <td><b>Alternate Email ID</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_email_alt"></td>
          </tr>
          <tr>
            <td align="left"><strong>13.&nbsp;</strong></td>
            <td><b>Whether belonging to Minority Community</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_minority"></td>
          </tr>
          <tr>
            <td align="left"><strong>14.&nbsp;</strong></td>
            <td><b>Are you a Domicile of J&K during 1.1.1980 to 31.12.1989</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_domicile"></td>
          </tr>
          <tr>
            <td align="left"><strong>15.&nbsp;</strong></td>
            <td><b>Whether Ex-Serviceman</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_exservice"></td>
          </tr>
          <tr>
            <td align="left"><strong>16.&nbsp;</strong></td>
            <td><b>Whether Person with Disabilities (PWD)?</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_pwd"></td>
          </tr>
          <tr>
            <td align="left"><strong>17.&nbsp;</strong></td>
            <td><b>Whether IFCI employee</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_ifciemp"></td>
          </tr>
          <tr>
            <td align="left"><strong>18.&nbsp;</strong></td>
            <td><b> Whether employed in Central or State Govt./Semi-Gov. autonomous bodies /CPSEs/PSUs/PSEs</b></td>
            <td width="2%" align="center">:</td>
            <td id="p_govnemp"></td>
          </tr>
          <!--<tr>
            <td align="left"><strong>&nbsp;</strong></td>
            <td><b>b) Whether obtained the requisite  NOC from the current employer as  mentioned in General condition    
 S. No. 3 (a)  of the detailed advertisement </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_noc"></td>
          </tr>-->
          
          </tbody>
          </table><table width="800" cellspacing="0" cellpadding="0" align="center" border="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:28px; padding:10px 15px;">
        <tbody>
          <tr>
            <td colspan="4">
              <h3 style="font-size:18px; border-bottom:1px solid #ccc; padding-bottom:8px; color:#437abd; margin:5px 0;">
              &raquo; Contact Details</h3>
            </td>
          </tr>
          <tr>
            <td align="left"><strong><u>A.&nbsp;</u></strong></td>
            <td colspan="3"><b><u>Correspondence Address</u></b></td>
          </tr>
          <tr>
            <td align="left"><strong>1.&nbsp;</strong></td>
            <td><b>Address </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_c_address"></td>
          </tr>
          <tr>
            <td align="left"><strong>2.&nbsp;</strong></td>
            <td><b>City </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_c_city"></td>
          </tr>
          
           <tr>
            <td align="left"><strong>3.&nbsp;</strong></td>
            <td><b>State </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_c_state"></td>
          </tr>
          <tr>
            <td align="left"><strong>4.&nbsp;</strong></td>
            <td><b>Pin </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_c_pin"></td>
          </tr>
          <tr>
            <td align="left"><strong>5.&nbsp;</strong></td>
            <td><b>Phone No. </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_c_phone"></td>
          </tr>
          <tr>
            <td align="left"><strong>6.&nbsp;</strong></td>
            <td><b>Mobile No. </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_c_mobile"></td>
          </tr>
          <tr>
            <td align="left"><strong><u>B.&nbsp;</u></strong></td>
            <td colspan="3"><b><u id="">Permanent Address</u></b></td>
          </tr>
          <tr>
            <td align="left"><strong>1.&nbsp;</strong></td>
            <td><b>Address </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_p_address"></td>
          </tr>
          <tr>
            <td align="left"><strong>2.&nbsp;</strong></td>
            <td><b>City </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_p_city"></td>
          </tr>
           <tr>
            <td align="left"><strong>3.&nbsp;</strong></td>
            <td><b>State </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_p_state"></td>
          </tr>
          <tr>
            <td align="left"><strong>4.&nbsp;</strong></td>
            <td><b>Pin </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_p_pin"></td>
          </tr>
          <tr>
            <td align="left"><strong>5.&nbsp;</strong></td>
            <td><b>Phone No. </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_p_phone"></td>
          </tr>
          <tr>
            <td align="left"><strong>6.&nbsp;</strong></td>
            <td><b>Mobile No. </b></td>
            <td width="2%" align="center">:</td>
            <td id="p_p_mobile"></td>
          </tr>
          </tbody>
          </table><table  width="800" cellspacing="0" cellpadding="0" align="center" border="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:28px; padding:10px 15px;">
        <tbody>
          <tr>
            <td colspan="4">
              <h3 style="font-size:18px; padding-bottom:8px; color:#437abd; margin:5px 0;">
              &raquo; Educational Details</h3>
            </td>
          </tr>
		   </tbody>
          </table><table id="educationalTablemodal" width="800" cellspacing="0" cellpadding="0" align="center" border="1" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:28px; padding:10px 15px;">
            
          </table><table width="800" cellspacing="0" cellpadding="0" align="center" border="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:28px; padding:10px 15px;">
        <tbody>
          <tr>
            <td colspan="4">
              <h3 style="font-size:18px; padding-bottom:8px; color:#437abd; margin:5px 0;">
              &raquo; Employment Details</h3>
            </td>
          </tr>
		   </tbody>
          </table><div style="overflow-x:auto; width:92%; margin-left:36px;"><table id="employeeTablemodal"  width="800" cellspacing="0" cellpadding="0" align="center" border="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:28px; padding:10px 15px;">
            
          </table></div><table id="application_fee_table" width="800" cellspacing="0" cellpadding="0" align="center" border="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:28px; padding:10px 15px;">
        <tbody>
          <tr>
            <td colspan="4">
              <h3 style="font-size:18px; border-bottom:1px solid #ccc; padding-bottom:8px; color:#437abd; margin:5px 0;">
              &raquo; Cost of Application Details</h3>
            </td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>1.&nbsp;</strong></td>
            <td><b>SBCollect Reference Number</b></td>
            <td width="2%" align="center">:</td>
            <td id="ref_number_bank"></td>
          </tr>
          <tr>
            <td align="left"><strong>2.&nbsp;</strong></td>
            <td><b>Date of Payment</b></td>
            <td width="2%" align="center">:</td>
            <td id="date_of_payment"></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>3.&nbsp;</strong></td>
            <td valign="top"><b>Payment Receipt</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" ><a href="javascript:void();" id="p_payment_receipt" target="_blank">View File</a></td>
          </tr>
          </tbody>
          </table><table width="800" cellspacing="0" cellpadding="0" align="center" border="0" bgcolor="#fff" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:28px; padding:10px 15px;">
        <tbody>
          <tr>
            <td colspan="4">
              <h3 style="font-size:18px; border-bottom:1px solid #ccc; padding-bottom:8px; color:#437abd; margin:5px 0;">
              &raquo; Other Details</h3>
            </td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>1.&nbsp;</strong></td>
            <td valign="top"><b>Are you currently under service agreement/bond with your existing employer?</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" id="p_ser_agr"></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>2.&nbsp;</strong></td>
            <td valign="top"><b>Have you ever been charged or convicted for any criminal offence in India or abroad?</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" id="p_criminal"></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>3.&nbsp;</strong></td>
            <td valign="top"><b>Are you related to anyone who works for the company?</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" id="p_realated"></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>4.&nbsp;</strong></td>
            <td valign="top"><b>Academics Achievements</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" id="p_achieve"></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>5.&nbsp;</strong></td>
            <td valign="top"><b>Professional Achievements</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" id="p_pro_ach"></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>6.&nbsp;</strong></td>
            <td valign="top"><b>Hobbies</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" id="p_hobby"></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>7.&nbsp;</strong></td>
            <td valign="top"><b>Details of outstanding loans with the present employer</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" id="p_p_emp"></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>8.&nbsp;</strong></td>
            <td valign="top"><b>Joining time required</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" id="p_joining_time"></td>
          </tr>
          
          <!--<tr>
            <td align="left" valign="top"><strong>8.&nbsp;</strong></td>
            <td valign="top"><b>PDF</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" ><a href="" id="p_user_pdf" target="_blank">Your PDF</a></td>
          </tr>-->
          
          <tr>
            <td colspan="4">
              <h3 style="font-size:18px; border-bottom:1px solid #ccc; padding-bottom:8px; color:#437abd; margin:5px 0;">
              &raquo; Other Details</h3>
            </td>
          </tr>
          <tr id="">
            <td align="left" valign="top"><strong>1.&nbsp;</strong></td>
            <td valign="top"><b>Scanned Copy for the proof of date of birth</b></td>
            <td width="2%" align="center" valign="top">:</td>
            
            <td valign="top" ><a href="javascript:void();" id="p_dob_proof" target="_blank">View File</a></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>2.&nbsp;</strong></td>
            <td valign="top"><b>Scanned Copy of qualifying educational certificates</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" ><a href="javascript:void();" id="p_edu_certificate" target="_blank">View File</a></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>3.&nbsp;</strong></td>
            <td valign="top"><b>Scanned Copy of present employment certificate</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" ><a href="javascript:void();" id="p_emp_certificate" target="_blank">View File</a></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>4.&nbsp;</strong></td> 
            <td valign="top"><b>Scanned Copy of caste certificate</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" ><a href="javascript:void();" id="p_caste_certificate" target="_blank">View File</a></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>5.&nbsp;</strong></td>
            <td valign="top"><b>Resume</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" ><a href="javascript:void();" id="p_resume" target="_blank" >View File</a></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>6.&nbsp;</strong></td>
            <td valign="top"><b>Scanned Copy of disability certificate</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" >
            
            <a href="javascript:void();" id="p_pwd_certificate" target="_blank">View File</a></td>
          </tr>
          <tr>
            <td align="left" valign="top"><strong>7.&nbsp;</strong></td>
            <td valign="top"><b>Scanned copy of  confirming domicile of J & K during 1/1/1980 to 31/12/1989</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" >
            
            <a href="javascript:void();" id="p_domicile_certificate" target="_blank">View File</a></td>
          </tr>
          
          <tr>
            <td align="left" valign="top"><strong>8.&nbsp;</strong></td>
            <td valign="top"><b>Scanned copy of proof confirming status of Ex-serviceman</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" >
            
            <a href="javascript:void();" id="p_serviceman_certificate" target="_blank">View File</a></td>
          </tr>
          <!--<tr>
            <td align="left" valign="top"><strong>9.&nbsp;</strong></td>
            <td valign="top"><b>Scanned Copy of No Objection Certification(NOC)</b></td>
            <td width="2%" align="center" valign="top">:</td>
            <td valign="top" >
            
            <a href="javascript:void();" id="p_noc_certificate" target="_blank">View File</a></td>
          </tr>-->
          <tr>
            <td colspan="4">
            <p style="text-align:justify; line-height:18px;"> I hereby declare that I have read all the terms and conditions mentioned in the Advertisement No.: IFCI/2016/01 and I fulfill the same. All the statements made in the application are true, complete and correct to the best of my knowledge and belief. I also declare that I have submitted one application only for the above post. I am duly aware that in the event if any particulars or information furnished by me is found to be false/incorrect/incomplete or if found indulging in some unlawful act at any time, my candidature for the post is liable to be summarily rejected/cancelled and in the event of any statement/ information submitted found false/incorrect even after my appointment, my services are liable to be terminated.</p>
            </td>
          </tr>
          
          
        </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Go Back</button>
      </div>
    </div>
</div>
  </div>
  
  <!---------------    PWD & Payment Popup -------------------------------------->
<div id="myModalPwd" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
    
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <!--<a href="<?php echo current_url();?>"><button type="button" class="close" data-dismiss="">&times;</button></a>-->
            <button type="button" class="close" data-dismiss="" onClick="window.location.href='<?php echo current_url();?>'">&times;</button>
           <!-- 
           	data-dismiss="modal"
           	<h4 class="modal-title">Whether Person with Disabilities (PWD)?</h4>
           -->
        </div>
        <div class="modal-body">
        <div class="col-md-12">
            <div class="form-group"> <span class="col-md-9"> Whether Person with Disabilities (PWD)? <span class="error">*</span></span>
                <div class="col-md-3 ">
                  <label>
                        <input type="radio" class="pwd" name="pwd" id="pwdPop1"  value="Yes" required> Yes 
                   </label>
                       <label>
                        <input type="radio" name="pwd"  class="pwd" id="pwdPop2"  value="No">  No 
                 </label>
                </div>
            </div>
         </div>
         
         <div class="col-md-12" id="feesPaid_div" style="display:none">
            <div class="form-group"> <span class="col-md-9"> Please click the check box if payment already made and if not, then click Make Payment  <span class="error">*</span></span>
                <div class="col-md-3 ">
                  <label class="">
                          <input type="checkbox" name="feesPaid"  id="feesPaid" value="1" onclick="" >
                  </label>
                </div>
            </div>
            
         </div>
         
             <br><br><br><br>
        </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-primary" data-dismiss="modal">Go Back</button>-->
        <div class="col-md-12">&nbsp;
			<a href="https://www.onlinesbi.com/prelogin/icollecthome.htm?corpID=643220" target="_blank" class="btn btn-primary" id="paymentBtn" style="display:none;">Make Payment</a>
            
            
       	</div>
      </div>
    </div>
    </div>
</div>
  
  <!---------------  PWD & Payment Popup End -------------------------------------->
  
</div>
</div>
        <!--============================================ Step - 1 ============================================-->
      	</div>
      </fieldset>
    </form>
  </div>
  <!-- /well-sm--> 
</div>


<!-- /container--> 
<!-- End of Form Wizard --> 
<!-- Start of Footer --> 

<?php include 'validation.php';?>
<?php include 'footer.php';?>
<script>
if($("#reference_no").val()=='')
{
	$("#application_fee_table").hide();
}

if($('#apply_for').val()=="General Manager ( Credit )" || $('#apply_for').val()=="General Manager ( NPA Resolution and Recovery )" || $('#apply_for').val()=="Deputy General Manager ( Credit )" || $('#apply_for').val()=="Deputy General Manager  ( Internal and Credit Audit )" || $('#apply_for').val()=="Asst. General Manager ( Technical )" || $('#apply_for').val()=="Manager  ( Finance )" || $('#apply_for').val()=="Manager ( Legal )")
{
	$("#emp_certificate1").prop('required',true);
	$("#empl_exp_red").show();
}
if($('#apply_for').val()=='Asst. Manager  ( Finance )' || $('#apply_for').val()=='Asst. Manager ( IT )' )
{
	$("#emp_certificate1").prop('required',false);
	$("#empl_exp_red").hide();
}

$('input[type=radio][name=category]').change(function() {
if (this.value == 'SC'|| this.value == 'ST') 
{
	$("#reference_no").prop('required',false);
	$("#payment_date").prop('required',false);
	$("#payment_text").prop('required',false);
	$(".pwd").prop('required',false);
	$("#feesPaid").prop('required',false);
	$("#application_fee_table").hide();
	
}
if (this.value == 'General'|| this.value == 'OBC') 
{
	$("#reference_no").prop('required',true);
	$("#payment_date").prop('required',true);

	$("#payment_text").prop('required',true);
	$("#application_fee_table").show();
	
}
});

$('input[type=radio][name=pwd]').change(function() {
if (this.value == 'Yes') 
{
	$("#reference_no").prop('required',false);
	$("#payment_date").prop('required',false);
	$("#payment_text").prop('required',false);
	$("#application_fee_table").hide();
	$('#feesPaid_div').hide();
}
else
{
	$("#reference_no").prop('required',true);
	$("#payment_date").prop('required',true);
	$("#payment_text").prop('required',true);
	
}
if (this.value == 'No') 
{
	$("#application_fee_table").show();
	$('#feesPaid_div').show();
}
});
</script>
<!-- End of Footer --> 
<!-- Start of Footer-script -->
<?php include 'footer-script.php';?>
<script>  

$(function() {
	

    $( "#periodFrom1" ).datepicker({
	  dateFormat: 'dd/mm/yy',
      changeMonth: true,
	  maxDate: 0,
	  changeYear:true,
	  defaultDate: "-1y",
	  yearRange: '-40:-0',
      onClose: function( selectedDate ) {
        $( "#periodTo1" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $(  "#periodTo1" ).datepicker({
      changeMonth: true,
	  dateFormat: 'dd/mm/yy',
	  maxDate: 0,
	  changeYear:true,
	  defaultDate: "-1y",
	  yearRange: '-40:-0',
      onClose: function( selectedDate ) {
        $( "#periodFrom1"  ).datepicker( "option", "maxDate", selectedDate );
      }
    });	
	
	
    $( "#periodFrom2" ).datepicker({
	  dateFormat: 'dd/mm/yy',
      changeMonth: true,
	  maxDate: 0,
	  changeYear:true,
	  defaultDate: "-1y",
	  yearRange: '-40:-0',
      onClose: function( selectedDate ) {
        $( "#periodTo2" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $(  "#periodTo2" ).datepicker({
      changeMonth: true,
	  dateFormat: 'dd/mm/yy',
	  maxDate: 0,
	  changeYear:true,
	  defaultDate: "-1y",
	  yearRange: '-40:-0',
      onClose: function( selectedDate ) {
        $( "#periodFrom2"  ).datepicker( "option", "maxDate", selectedDate );
      }
    });	
	
	
	

    $( "#periodFrom4" ).datepicker({
	  dateFormat: 'dd/mm/yy',
      changeMonth: true,
	  maxDate: 0,
	  changeYear:true,
	  defaultDate: "-1y",
	  yearRange: '-40:-0',
      onClose: function( selectedDate ) {
        $( "#periodTo4" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $(  "#periodTo4" ).datepicker({
      changeMonth: true,
	  dateFormat: 'dd/mm/yy',
	  maxDate: 0,
	  changeYear:true,
	  defaultDate: "-1y",
	  yearRange: '-40:-0',
      onClose: function( selectedDate ) {
        $( "#periodFrom4"  ).datepicker( "option", "maxDate", selectedDate );
      }
    });	
	
	
  $( "#periodFrom3" ).datepicker({
	  dateFormat: 'dd/mm/yy',

      changeMonth: true,
	  maxDate: 0,
	  changeYear:true,
	  defaultDate: "-1y",
	  yearRange: '-40:-0',
      onClose: function( selectedDate ) {
        $( "#periodTo3" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $(  "#periodTo3" ).datepicker({
      changeMonth: true,
	  dateFormat: 'dd/mm/yy',
	  maxDate: 0,
	  changeYear:true,
	  defaultDate: "-1y",
	  yearRange: '-40:-0',
      onClose: function( selectedDate ) {
        $( "#periodFrom3"  ).datepicker( "option", "maxDate", selectedDate );
      }
    });	
		
  });
  

    
  
  </script>

<script src="<?php echo base_url()?>assets/js/ladda.min.js"></script>
<script src="<?php echo base_url()?>assets/js/spin.min.js"></script> 
<script src="<?php echo base_url()?>assets/js/myjs.js"></script> 
<script src="<?php echo base_url()?>assets/js/bootstrap-select.js"></script> 

<!-- End of Footer-script -->
</body>
</html>