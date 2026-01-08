<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>    
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); 
    $bcbf_agency_code = $batch_data[0]['agency_code'];
    ?>
		
		<div id="wrapper">
      <?php $this->load->view('iibfbcbf/admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $mode; ?> Candidate </h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/training_batches'); ?>">Training Batches</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/batch_candidates/index/'.url_encode($batch_data[0]['batch_id'])); ?>">Candidate List (<?php echo $batch_data[0]['batch_code']; ?>)</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $mode; ?> Candidate</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
          <div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title">
									<div class="ibox-tools">
										<a href="<?php echo site_url('iibfbcbf/admin/batch_candidates/index/'.url_encode($batch_data[0]['batch_id'])); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <form method="post" action="<?php echo site_url('iibfbcbf/admin/batch_candidates/add_candidates/'.url_encode($batch_data[0]['batch_id']).'/'.$enc_candidate_id); ?>" id="add_candidate_form" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
                    <input type="hidden" name="form_action" id="form_action" value="">
                    
                    <input type="hidden" name="enc_batch_id" id="enc_batch_id" value="<?php echo $enc_batch_id; ?>">
                    
                    <h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Basic Details</h4>
                    
                    <?php 
                      if($bcbf_agency_code == '1019')
                      {
                        $salutation_master_arr = array('Mrs.', 'Ms.');
                      }else{
                        $salutation_master_arr = array('Mr.', 'Mrs.', 'Ms.');
                      } 
                       
                      $qualification_arr = array('1'=>'Under Graduate', '2'=>'Graduate', '3'=>'Post Graduate');
                      $id_proof_type_arr = array('1'=>'Aadhar Card', '2'=>'Driving Licence', '3'=>'Employee ID', '4'=>'Pan Card', '5'=>'Passport');
                      $qualification_certificate_type_arr = array('1'=>'10th Pass', '2'=>'12th Pass', '3'=>'Graduation', '4'=>'Post Graduation'); ?>
                      
                      <div class="row">
                        <?php if($mode == 'Update') { ?><?php /* Show Training ID only for edit mode */ ?>
                          <div class="col-xl-12 col-lg-12"><?php /* Training ID */ ?>
                            <div class="form-group">
                              <label class="form_label">Training ID <sup class="text-danger">*</sup></label>
                              <input type="text" value="<?php echo $form_data[0]['training_id']; ?>" class="form-control custom_input" readonly />                              
                            </div>					
                          </div>
                        <?php } ?>
                        
                        <div class="col-xl-3 col-lg-3"><?php /* Candidate Name (Salutation) */ ?>
                          <div class="form-group">
                            <?php 
                              if($mode == "Add") { $chk_salutation = set_value('salutation'); } else { $chk_salutation = $form_data[0]['salutation']; } 
                            ?>
                            <label for="salutation" class="form_label">Candidate Name (Salutation) <sup class="text-danger">*</sup></label>
                            <select name="salutation" id="salutation" class="form-control basic_form" required onchange="show_hide_gender(); validate_input('gender_male'); validate_input('gender_female');">
                              <?php if(count($salutation_master_arr) > 0)
                                { ?>
                                <option value="">Select Salutation *</option>
                                <?php foreach($salutation_master_arr as $sal_val)
                                  { ?>
                                  <option value="<?php echo $sal_val; ?>" <?php if($chk_salutation == $sal_val) { echo 'selected'; } ?>><?php echo $sal_val; ?></option>
                                  <?php }
                                } ?>
                            </select>
                            <?php if(form_error('salutation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('salutation'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-3 col-lg-3"><?php /* First Name */ ?>
                          <div class="form-group">
                            <label for="first_name" class="form_label">First Name <sup class="text-danger">*</sup></label>
                            <input type="text" name="first_name" id="first_name" value="<?php if($mode == "Add") { echo set_value('first_name'); } else { echo $form_data[0]['first_name']; } ?>" placeholder="First Name *" class="form-control custom_input allow_only_alphabets_and_space basic_form" maxlength="20" required/>
                            <note class="form_note" id="first_name_err">Note: Please enter only 20 characters</note>
                            
                            <?php if(form_error('first_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('first_name'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-3 col-lg-3"><?php /* Middle Name */ ?>
                          <div class="form-group">
                            <label for="middle_name" class="form_label">Middle Name <sup class="text-danger"></sup></label>
                            <input type="text" name="middle_name" id="middle_name" value="<?php if($mode == "Add") { echo set_value('middle_name'); } else { echo $form_data[0]['middle_name']; } ?>" placeholder="Middle Name" class="form-control custom_input allow_only_alphabets_and_space basic_form" maxlength="20"/>
                            <note class="form_note" id="middle_name_err">Note: Please enter only 20 characters</note>
                            
                            <?php if(form_error('middle_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('middle_name'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-3 col-lg-3"><?php /* Last Name */ ?>
                          <div class="form-group">
                            <label for="last_name" class="form_label">Last Name <sup class="text-danger"></sup></label>
                            <input type="text" name="last_name" id="last_name" value="<?php if($mode == "Add") { echo set_value('last_name'); } else { echo $form_data[0]['last_name']; } ?>" placeholder="Last Name" class="form-control custom_input allow_only_alphabets_and_space basic_form" maxlength="20"/>
                            <note class="form_note" id="last_name_err">Note: Please enter only 20 characters</note>
                            
                            <?php if(form_error('last_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('last_name'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* Date of Birth */ ?>
                          <div class="form-group">
                            <label for="dob" class="form_label">Date of Birth <sup class="text-danger">*</sup></label>
                            <input type="text" name="dob" id="dob" value="<?php if($mode == "Add") { echo set_value('dob'); } else { if($form_data[0]['dob'] != '0000-00-00') { echo $form_data[0]['dob']; } } ?>" placeholder="Date of Birth" class="form-control custom_input basic_form" onchange="validate_input('dob');" onclick="validate_input('dob');" required readonly/>
                            
                            <?php /* <note class="form_note" id="dob_err">Note: Please Select date of birth between <?php echo $dob_start_date; ?> to <?php echo $dob_end_date; ?> date.</note> */ ?>
                            <note class="form_note" id="dob_err">Note: Please Select date of birth before <?php echo date('Y-m-d', strtotime("+1days",strtotime($dob_end_date))); ?> date.</note>
                            
                            <?php if(form_error('dob')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('dob'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* Gender */ ?>
                          <div class="form-group">
                            <label for="gender" class="form_label">Gender <sup class="text-danger">*</sup></label>
                            <div id="gender_err">  
                              <?php  
                              if($bcbf_agency_code != '1019'){ ?>                            
                              <label class="css_checkbox_radio radio_only"> Male
                                <input type="radio" value="1" name="gender" id="gender_male" required <?php if($mode == "Add") { if(set_value('gender') == '1') { echo "checked"; } } else { if($form_data[0]['gender'] == '1') { echo "checked"; } } ?> class=" basic_form">
                                <span class="radiobtn"></span>
                              </label>
                            <?php } ?>
                              <label class="css_checkbox_radio radio_only"> Female
                                <input type="radio" value="2" name="gender" id="gender_female" required <?php if($mode == "Add") { if(set_value('gender') == '2') { echo "checked"; } } else { if($form_data[0]['gender'] == '2') { echo "checked"; } } ?> class=" basic_form">
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            <?php if(form_error('gender')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('gender'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* Mobile Number */ ?>
                          <div class="form-group">
                            <label for="mobile_no" class="form_label">Mobile Number <sup class="text-danger">*</sup></label>
                            <input type="text" name="mobile_no" id="mobile_no" value="<?php if($mode == "Add") { echo set_value('mobile_no'); } else { echo $form_data[0]['mobile_no']; } ?>" placeholder="Mobile Number *" class="form-control custom_input allow_only_numbers basic_form" required maxlength="10" minlength="10" />
                            
                            <?php if(form_error('mobile_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile_no'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* Alternate Mobile Number */ ?>
                          <div class="form-group">
                            <label for="alt_mobile_no" class="form_label">Alternate Mobile Number <sup class="text-danger"></sup></label>
                            <input type="text" name="alt_mobile_no" id="alt_mobile_no" value="<?php if($mode == "Add") { echo set_value('alt_mobile_no'); } else { echo $form_data[0]['alt_mobile_no']; } ?>" placeholder="Alternate Mobile Number" class="form-control custom_input allow_only_numbers basic_form" maxlength="10" minlength="10" />
                            
                            <?php if(form_error('alt_mobile_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('alt_mobile_no'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* Email id */ ?>
                          <div class="form-group">
                            <label for="email_id" class="form_label">Email id <sup class="text-danger">*</sup></label>
                            <input type="text" name="email_id" id="email_id" value="<?php if($mode == "Add") { echo set_value('email_id'); } else { echo $form_data[0]['email_id']; } ?>" placeholder="Email id *" class="form-control custom_input basic_form" required maxlength="80" />
                            <note class="form_note" id="email_id_err">Note: Please enter only 80 characters</note>
                            
                            <?php if(form_error('email_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('email_id'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* Alternate Email id */ ?>
                          <div class="form-group">
                            <label for="alt_email_id" class="form_label">Alternate Email id <sup class="text-danger"></sup></label>
                            <input type="text" name="alt_email_id" id="alt_email_id" value="<?php if($mode == "Add") { echo set_value('alt_email_id'); } else { echo $form_data[0]['alt_email_id']; } ?>" placeholder="Alternate Email id" class="form-control custom_input basic_form" maxlength="80" />
                            <note class="form_note" id="alt_email_id_err">Note: Please enter only 80 characters</note>
                            
                            <?php if(form_error('alt_email_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('alt_email_id'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* Qualification */ ?>
                          <div class="form-group">
                            <label for="qualification" class="form_label">Qualification <sup class="text-danger">*</sup></label>
                            <div id="qualification_err">   
                              <?php foreach($qualification_arr as $key => $val) { ?>                          
                                <label class="css_checkbox_radio radio_only"> <?php echo $val; ?>
                                  <input type="radio" value="<?php echo $key; ?>" name="qualification" id="qualification_<?php echo $key; ?>" required <?php if($mode == "Add") { if(set_value('qualification') == $key) { echo "checked"; } } else { if($form_data[0]['qualification'] == $key) { echo "checked"; } } ?> onchange="show_hide_qualification_certificate_type(); validate_input('qualification_<?php echo $key; ?>');">
                                  <span class="radiobtn"></span>
                                </label>
                              <?php } ?>
                            </div>
                            <?php if(form_error('qualification')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('qualification'); ?></label> <?php } ?>
                          </div>					
                        </div>
                      </div>
                      
                    <?php if($mode == 'Add') { ?><?php /* Submit Basic Details button visible only in Add mode */ ?>
                        <div class="hr-line-dashed"></div>   
                        <div class="row">                   
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer1">
                            <input type="submit" class="btn btn-primary" id="submitFirst" name="submitFirst" value="Submit I" onclick="validate_basic_details()">                          
                          </div>                      
                        </div>
                      <?php } ?>
                      
                      <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Other Details</h4>
                      <div class="row">
                        <div class="col-xl-12 col-lg-12"><?php /* Address Line-1 */ ?>
                          <div class="form-group">
                            <label for="address1" class="form_label">Address Line-1 <sup class="text-danger">*</sup></label>
                            <input type="text" name="address1" id="address1" placeholder="Address Line-1 *" class="form-control custom_input ignore_required" maxlength="75" required value="<?php if($mode == "Add") { echo set_value('address1'); } else { echo $form_data[0]['address1']; } ?>" />
                            
                            <note class="form_note" id="address1_err">Note: Please enter only 75 characters</note>
                            
                            <?php if(form_error('address1')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address1'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-12 col-lg-12"><?php /* Address Line-2 */ ?>
                          <div class="form-group">
                            <label for="address2" class="form_label">Address Line-2 <sup class="text-danger"></sup></label>
                            <input type="text" name="address2" id="address2" placeholder="Address Line-2" class="form-control custom_input" maxlength="75" value="<?php if($mode == "Add") { echo set_value('address2'); } else { echo $form_data[0]['address2']; } ?>" />
                            
                            <note class="form_note" id="address2_err">Note: Please enter only 75 characters</note>
                            
                            <?php if(form_error('address2')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address2'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-12 col-lg-12"><?php /* Address Line-3 */ ?>
                          <div class="form-group">
                            <label for="address3" class="form_label">Address Line-3 <sup class="text-danger"></sup></label>
                            <input type="text" name="address3" id="address3" placeholder="Address Line-3" class="form-control custom_input" maxlength="75" value="<?php if($mode == "Add") { echo set_value('address3'); } else { echo $form_data[0]['address3']; } ?>" />
                            
                            <note class="form_note" id="address3_err">Note: Please enter only 75 characters</note>
                            
                            <?php if(form_error('address3')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address3'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                    <div class="col-xl-12 col-lg-12"><?php /* Address Line-4 */ ?>
                          <div class="form-group">
                            <label for="address4" class="form_label">Address Line-4 <sup class="text-danger"></sup></label>
                            <input type="text" name="address4" id="address4" placeholder="Address Line-4" class="form-control custom_input" maxlength="75" value="<?php if($mode == "Add") { echo set_value('address4'); } else { echo $form_data[0]['address4']; } ?>" />
                            
                            <note class="form_note" id="address4_err">Note: Please enter only 75 characters</note>
                            
                            <?php if(form_error('address4')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('address4'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <?php if($mode == "Add") { $chk_state = set_value('state'); } else { $chk_state = $form_data[0]['state']; } ?>
                        <div class="col-xl-6 col-lg-6"><?php /* Select State */ ?>
                          <div class="form-group">
                            <label for="state" class="form_label">Select State <sup class="text-danger">*</sup></label>
                            <select name="state" id="state" class="form-control chosen-select ignore_required" required onchange="get_city_ajax(this.value); validate_input('state'); ">
                              <?php if(count($state_master_data) > 0)
                                { ?>
                                <option value="">Select State *</option>
                                <?php foreach($state_master_data as $state_res)
                                  { ?>
                                  <option value="<?php echo $state_res['state_code']; ?>" <?php if($chk_state == $state_res['state_code']) { echo 'selected'; } ?>><?php echo $state_res['state_name']; ?></option>
                                  <?php }
                                }
                                else 
                                { ?>
                                <option value="">No State Available</option>
                              <?php } ?>
                            </select>
                            
                            <span id="state_err"></span>
                            <?php if(form_error('state')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('state'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* Select City */ ?>
                          <div class="form-group">
                            <label for="city" class="form_label">City <sup class="text-danger">*</sup></label>
                            <div id="city_outer">
                              <select class="form-control chosen-select ignore_required" name="city" id="city" required onchange="validate_input('city'); ">
                                <?php $selected_state_val = '';
                                  if($mode == "Add")
                                  {
                                    if(set_value('state') != "") { $selected_state_val = set_value('state'); }
                                  }
                                  else { $selected_state_val = $form_data[0]['state']; }
                                  
                                  if($selected_state_val != "")
                                  {
                                    $city_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_state_val, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
                                    
                                    if(count($city_data) > 0)
                                    { ?>
                                    <option value="">Select City</option>
                                    <?php foreach($city_data as $city)
                                      { ?>
                                      <option value="<?php echo $city['id']; ?>" <?php if($mode == "Add") { if(set_value('city') == $city['id']) { echo "selected"; } } else { if($form_data[0]['city'] == $city['id']) { echo "selected"; } } ?>><?php echo $city['city_name']; ?></option>
                                      <?php }
                                    }
                                    else
                                    { ?>
                                    <option value="">No City Available</option>
                                    <?php }
                                  }
                                  else 
                                  {
                                    echo '<option value="">Select City</option>';
                                  } ?>
                              </select>
                            </div>
                            
                            <span id="city_err"></span>
                            
                            <?php if(form_error('city')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('city'); ?></label> <?php } ?>                            
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* District */ ?>
                          <div class="form-group">
                            <label for="district" class="form_label">District <sup class="text-danger">*</sup></label>
                            <input type="text" name="district" id="district" value="<?php if($mode == "Add") { echo set_value('district'); } else { echo $form_data[0]['district']; } ?>" placeholder="District *" class="form-control custom_input allow_only_alphabets_and_numbers_and_space ignore_required" maxlength="30" required/>
                            <note class="form_note" id="district_err">Note: Please enter only 30 characters</note>
                            
                            <?php if(form_error('district')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('district'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* Pincode */ ?>
                          <div class="form-group">
                            <label for="pincode" class="form_label">Pincode <sup class="text-danger">*</sup></label>
                            <input type="text" name="pincode" id="pincode" value="<?php if($mode == "Add") { echo set_value('pincode'); } else { echo $form_data[0]['pincode']; } ?>" placeholder="Pincode *" class="form-control custom_input allow_only_numbers ignore_required" required maxlength="6" minlength="6" />
                            
                            <?php if(form_error('pincode')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pincode'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                      <!-- <div class="col-xl-12 col-lg-12"> --><?php /* Affiliated with the Bank as a BC */ ?>

                        <div class="col-xl-6 col-lg-6">
                          <div class="form-group">
                          <label for="associated_with_any_bank" class="form_label">Affiliated with the Bank as a BC <sup class="text-danger">*</sup></label>
                          <div id="associated_with_any_bank_err">                              
                            <label class="css_checkbox_radio radio_only"> Yes
                              <input type="radio" value="1" name="associated_with_any_bank" id="associated_with_any_bank_yes" required <?php if($mode == "Add") { if(set_value('associated_with_any_bank') == '1' || set_value('associated_with_any_bank') == '') { echo "checked"; } } else { if($form_data[0]['associated_with_any_bank'] == '1') { echo "checked"; } } ?> class=" basic_form ignore_required" onchange="fun_show_hide_bank_associated(); validate_input('bank_associated');">
                              <span class="radiobtn"></span>
                            </label>
                            <label class="css_checkbox_radio radio_only"> No
                              <input type="radio" value="2" name="associated_with_any_bank" id="associated_with_any_bank_no" required <?php if($mode == "Add") { if(set_value('associated_with_any_bank') == '2') { echo "checked"; } } else { if($form_data[0]['associated_with_any_bank'] == '2') { echo "checked"; } } ?> class=" basic_form ignore_required" onchange="fun_show_hide_bank_associated(); validate_input('bank_associated');">
                              <span class="radiobtn"></span>
                            </label>
                          </div>
                          <?php if(form_error('associated_with_any_bank')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('associated_with_any_bank'); ?></label> <?php } ?>
                          </div>	
                        </div>  

                        <div class="col-xl-6 col-lg-6"><?php /* Bank Employee Id */  // Added By ANIL S on 24 July 2025 ?>
                          <div class="form-group">
                            <label for="bank_emp_id" class="form_label">Bank Employee Id <sup class="text-danger"></sup></label>
                            <input type="text" name="bank_emp_id" id="bank_emp_id" value="<?php if($mode == "Add") { echo set_value('bank_emp_id'); } else { echo $form_data[0]['bank_emp_id']; } ?>" placeholder="Bank Employee Id" class="form-control custom_input allow_only_alphabets_and_numbers_and_space" maxlength="20" />
                            
                            <?php if(form_error('bank_emp_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('bank_emp_id'); ?></label> <?php } ?>
                          </div>          
                        </div> 

                      <!-- </div> -->
											
                      <?php $required_bank_associated_flg = 'required';
                      if($mode == "Add") 
                      { 
                        if(set_value('associated_with_any_bank') == '2') { $required_bank_associated_flg = ""; } 
                        $chk_bank_associated = set_value('bank_associated');
                      } 
                      else 
                      { 
                        if($form_data[0]['associated_with_any_bank'] == '2') { $required_bank_associated_flg = ""; } 
                        $chk_bank_associated = $form_data[0]['bank_associated'];
                      } ?>
                      
                      <div class="col-xl-6 col-lg-6 bank_associated_outer"><?php /* Bank associated with */ ?>
												<div class="form-group">
													<label for="bank_associated" class="form_label">Bank associated with <sup class="text-danger show_hide_star"><?php if($required_bank_associated_flg != "") { echo '*'; } ?></sup></label>
													
                          <select name="bank_associated" id="bank_associated" class="form-control chosen-select" onchange="show_hide_other_section(); validate_input('bank_associated'); " <?php echo $required_bank_associated_flg; ?>>
														<?php if(count($bank_associated_master_data) > 0) { ?>
															<option value="">Select Bank associated with</option>
															<?php foreach($bank_associated_master_data as $res) { ?>
																<option value="<?php echo $res['bank_code']; ?>" <?php if($chk_bank_associated == $res['bank_code']) { echo 'selected'; } ?>><?php echo $res['bank_name']; ?></option>
                              <?php }
                            }
                            else 
                            { ?>
															<option value="">No Bank Available</option>
														<?php } ?>
													</select>
                          <note class="form_note" id="bank_associated_err"></note>
                            
                            <?php if(form_error('bank_associated')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('bank_associated'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                      <?php $bank_associated_other_cls = 'hide';
                      $bank_associated_required_flg = '';
                      if($mode == "Add") 
                      { 
                        if(set_value('bank_associated') == 'Other') 
                        { 
                          $bank_associated_other_cls = ""; 
                          $bank_associated_required_flg = 'required';
                        }                        
                      } 
                      else 
                      { 
                        if($form_data[0]['bank_associated'] == 'Other') 
                        { 
                          $bank_associated_other_cls = ""; 
                          $bank_associated_required_flg = 'required';
                        }
                      } ?>

                      <div class="col-xl-6 col-lg-6 bank_associated_other_outer_cls <?php echo $bank_associated_other_cls; ?>"><?php /* Other Bank associated with */ ?>
												<div class="form-group">
													<label for="bank_associated_other" class="form_label">Other Bank associated with <sup class="text-danger">*</sup></label>
													<input type="text" name="bank_associated_other" id="bank_associated_other" value="<?php if($mode == "Add") { echo set_value('bank_associated_other'); } else { echo $form_data[0]['bank_associated_other']; } ?>" placeholder="Other Bank associated with *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" <?php echo $bank_associated_required_flg; ?> />
													<note class="form_note" id="bank_associated_other_err">Note: Please enter only 90 characters</note>
													
													<?php if(form_error('bank_associated_other')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('bank_associated_other'); ?></label> <?php } ?>
												</div>					
											</div> 

                      </div>

                      <div class="row">
                         <div class="col-xl-6 col-lg-6"><?php /* Are you associated with a Corporate BC? */ ?>
                            <div class="form-group">
                            <label for="are_you_corporate_bc" class="form_label">Are you associated with a Corporate BC? <sup class="text-danger">*</sup></label>
                            <div id="are_you_corporate_bc_err">                              
                              <label class="css_checkbox_radio radio_only"> Yes
                                <input type="radio" value="Yes" onclick="check_are_you_corporate_bc(this.value);" name="are_you_corporate_bc" id="are_you_corporate_bc_yes" required <?php if($mode == "Add") { if(set_value('are_you_corporate_bc') == 'Yes') { echo "checked"; } } else { if($form_data[0]['are_you_corporate_bc'] == 'Yes') { echo "checked"; } } ?> class=" basic_form ignore_required" onchange="validate_input('corporate_bc_option');">
                                <span class="radiobtn"></span>
                              </label>
                              <label class="css_checkbox_radio radio_only"> No
                                <input type="radio" value="No" onclick="check_are_you_corporate_bc(this.value);" name="are_you_corporate_bc" id="are_you_corporate_bc_no" required <?php if($mode == "Add") { if(set_value('are_you_corporate_bc') == 'No') { echo "checked"; } } else { if($form_data[0]['are_you_corporate_bc'] == 'No') { echo "checked"; } } ?> class=" basic_form ignore_required">
                                <span class="radiobtn"></span>
                              </label>
                            </div>
                            <?php if(form_error('are_you_corporate_bc')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('are_you_corporate_bc'); ?></label> <?php } ?>
                          </div>          
                        </div>

                        <?php
                        $display_corporate_bc_option_div = 'display:none;';
                        if($mode == 'Update' && $form_data[0]['are_you_corporate_bc'] == 'Yes'){
                          $display_corporate_bc_option_div = 'display:block;';
                        }
                        $display_corporate_bc_associated_div = 'display:none;';
                        if($mode == 'Update' && $form_data[0]['corporate_bc_option'] == 'Other'){
                          $display_corporate_bc_associated_div = 'display:block;';
                        }
                        ?>

                        <div id="corporate_bc_option_div" style="<?php echo $display_corporate_bc_option_div; ?>" class="col-xl-6 col-lg-6"><?php /* Select Corporate BC */ ?>
                            <div class="form-group">
                            <label for="corporate_bc_option" class="form_label">Select Corporate BC <sup class="text-danger">*</sup></label>
                            <div id="corporate_bc_option_err">                              
                              <label class="css_checkbox_radio radio_only"> CSC
                                <input type="radio" value="CSC" onclick="check_corporate_bc_option(this.value);" name="corporate_bc_option" id="corporate_bc_option_CSC" <?php if($mode == "Add") { if(set_value('corporate_bc_option') == 'CSC' || set_value('corporate_bc_option') == '') { echo "checked"; } } else { if($form_data[0]['corporate_bc_option'] == 'CSC') { echo "checked"; } } ?> class=" basic_form">
                                
                                <span class="radiobtn"></span>
                              </label>
                              <label class="css_checkbox_radio radio_only"> Other
                                <input type="radio" value="Other" onclick="check_corporate_bc_option(this.value);" name="corporate_bc_option" id="corporate_bc_option_Other" <?php if($mode == "Add") { if(set_value('corporate_bc_option') == 'Other') { echo "checked"; } } else { if($form_data[0]['corporate_bc_option'] == 'Other') { echo "checked"; } } ?> class=" basic_form" onchange="validate_input('corporate_bc_associated');">
                                <span class="radiobtn"></span>
                              </label>
                            </div>

                            <note class="form_note" id="corporate_bc_validation_message_div" style="display:none;">Note: You are not eligible to appear at CSC center Exam.</note>

                            <?php if(form_error('corporate_bc_option')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('corporate_bc_option'); ?></label> <?php } ?>
                          </div>          
                        </div>

                        <div id="corporate_bc_associated_div" style="<?php echo $display_corporate_bc_associated_div; ?>" class="col-xl-6 col-lg-6"><?php /* Corporate BC associated with */ ?>
                          <div class="form-group">
                            <label for="corporate_bc_associated" class="form_label">Corporate BC associated with <sup class="text-danger"></sup></label>
                            <input type="text" name="corporate_bc_associated" id="corporate_bc_associated" value="<?php if($mode == "Add") { echo set_value('corporate_bc_associated'); } else { echo $form_data[0]['corporate_bc_associated']; } ?>" placeholder="Corporate BC associated with" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90"/>
                            <note class="form_note" id="corporate_bc_associated_err">Note: Please enter only 90 characters</note>
                            
                            <?php if(form_error('corporate_bc_associated')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('corporate_bc_associated'); ?></label> <?php } ?>
                          </div>          
                        </div>

                        <!-- <div id="corporate_bc_validation_message_div" style="display:none;" class="col-xl-12 col-lg-12"><?php /* Validation Message after selecting Corporate BC as CSC */ ?>
                          <div class="form-group">
                            <label for="corporate_bc_associated" class="form_label"><sup class="text-danger"></sup></label>
                            <span style="color:#f00">You are not eligible to appear at CSC center Exam. Kindly register through the IIBF website. (<a target="_blank" href="<?php echo base_url('nonreg/examlist/?Extype=Mg==&Mtype=Tk0=');?>">Click Here</a>)</span>      
                          </div>          
                        </div> -->

                      </div>
                      
                      <h4 class="custom_form_title" style="margin: 20px -20px 15px -20px !important;">Upload Documents</h4>
                      <div class="row">
                        <div class="col-xl-12 col-lg-12"><?php /* ID Proof Type */ ?>
                          <div class="form-group">
                            <label for="id_proof_type" class="form_label">ID Proof Type <sup class="text-danger">*</sup></label>
                            <div id="id_proof_type_err">   
                              <?php foreach($id_proof_type_arr as $id_proof_type_key => $id_proof_type_val) { ?>                          
                                <label class="css_checkbox_radio radio_only"> <?php echo $id_proof_type_val; ?>
                                  <input type="radio" value="<?php echo $id_proof_type_key; ?>" name="id_proof_type" id="id_proof_type<?php echo $id_proof_type_key; ?>" class="ignore_required" required <?php if($mode == "Add") { if(set_value('id_proof_type') == $id_proof_type_key) { echo "checked"; } } else { if($form_data[0]['id_proof_type'] == $id_proof_type_key) { echo "checked"; } } ?> onchange="id_proof_number_validation(); validate_input('aadhar_no');">
                                  <span class="radiobtn"></span>
                                </label>
                              <?php } ?>
                            </div>
                            <?php if(form_error('id_proof_type')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('id_proof_type'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php /* Id Proof Number */ ?>
                          <div class="form-group">
                            <label for="id_proof_number" class="form_label">Id Proof Number <sup class="text-danger">*</sup></label>
                            <input type="text" name="id_proof_number" id="id_proof_number" value="<?php if($mode == "Add") { echo set_value('id_proof_number'); } else { echo $form_data[0]['id_proof_number']; } ?>" placeholder="Id Proof Number *" class="form-control custom_input" onchange="validate_input('aadhar_no');" onkeyup="validate_input('aadhar_no');" />
                            
                            <note class="form_note" id="id_proof_number_err"></note>
                            
                            <?php if(form_error('id_proof_number')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('id_proof_number'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php // Upload Proof of Identity ?>
                          <div class="form-group">
                            <div class="img_preview_input_outer pull-left">
                              <label for="id_proof_file" class="form_label">Upload Proof of Identity <sup class="text-danger">*</sup></label>
                              <?php /* <input type="file" name="id_proof_file" id="id_proof_file" class="form-control" accept=".png,.jpeg,.jpg" data-accept=".jpg,.jpeg,.pdf" onchange="show_preview(event, 'id_proof_file_preview'); validate_input('id_proof_file');" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['id_proof_file'] == "")) { echo 'required'; } ?> />
                              <note class="form_note" id="id_proof_file_err">Note: Please upload only .jpg, .jpeg, or .png files between 75KB and 100KB in size.</note> */ ?>
                              
                            <input type="file" name="id_proof_file" id="id_proof_file" class="form-control hide_input_file_cropper" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['id_proof_file'] == "")) { echo 'required'; } ?> />
                            
                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('id_proof_file', 'iibfbcbf_batch_candidates', 'Edit Proof of Identity')">Upload Proof of Identity</button>
                            </div>
                            <note class="form_note" id="id_proof_file_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                            <input type="hidden" name="id_proof_file_cropper" id="id_proof_file_cropper" value="<?php echo set_value('id_proof_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                              
                              <?php if(form_error('id_proof_file')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('id_proof_file'); ?></label> <?php } ?>
                              <?php if($id_proof_file_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $id_proof_file_error; ?></label> <?php } ?>
                            </div>

                            <div id="id_proof_file_preview" class="upload_img_preview pull-right">
                              <?php 
                              $preview_candidate_id = $preview_first_name = $preview_training_id = '';
                              if($mode == 'Add') 
                              {
																$preview_first_name = set_value('first_name');
                              }
                              else if($mode == 'Update') 
                                { 
                                  $preview_candidate_id = $form_data[0]['candidate_id']; 
                                  $preview_first_name = $form_data[0]['first_name']; 
                                  $preview_training_id = $form_data[0]['training_id']; 
                                }
                                
                              $preview_id_proof_file = '';			
                              if($mode == 'Add' && set_value('id_proof_file_cropper') != "") 
                              {
                                $preview_id_proof_file = set_value('id_proof_file_cropper');
                              }
                              else if($mode == 'Update' && $form_data[0]['id_proof_file'] != "") 
                              { 
                                $preview_id_proof_file = $form_data[0]['id_proof_file'];
																$preview_id_proof_file = base_url($id_proof_file_path.'/'.$preview_id_proof_file);
                              }
                                
                              if($preview_id_proof_file != "")
                              { ?>
                              <a href="<?php echo $preview_id_proof_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Proof of Identity - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                <img src="<?php echo $preview_id_proof_file."?".time(); ?>">
                              </a>
                              
                              <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="id_proof_file" data-db_tbl_name="iibfbcbf_batch_candidates" data-title="Edit Proof of Identity" title="Edit Proof of Identity" alt="Edit Proof of Identity"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                              <?php }
                              else
                              {
                                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                              } ?>
                            </div><div class="clearfix"></div>
                          </div>
                        </div>

                        <input type="hidden" id="data_lightbox_hidden" value="candidate_images">
                        <input type="hidden" id="data_lightbox_title_hidden" value="<?php echo $preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                        
                        <div class="col-xl-6 col-lg-6"><?php // Qualification Certificate Type  ?>
                          <div class="form-group">
                            <label for="qualification_certificate_type" class="form_label">Qualification Certificate Type <sup class="text-danger">*</sup></label>
                            <div id="qualification_certificate_type_err">   
                              <?php foreach($qualification_certificate_type_arr as $key => $val) { ?>                          
                                <label class="css_checkbox_radio radio_only"> <?php echo $val; ?>
                                  <input type="radio" value="<?php echo $key; ?>" name="qualification_certificate_type" id="qualification_certificate_type<?php echo $key; ?>" required class="ignore_required chk_validation_qualification_certificate_type" <?php if($mode == "Add") { if(set_value('qualification_certificate_type') == $key) { echo "checked"; } } else { if($form_data[0]['qualification_certificate_type'] == $key) { echo "checked"; } } ?>>
                                  <span class="radiobtn"></span>
                                </label>
                              <?php } ?>
                            </div>
                            <?php if(form_error('qualification_certificate_type')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('qualification_certificate_type'); ?></label> <?php } ?>
                          </div>					
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php // Upload Qualification Certificate ?>
                          <div class="form-group">
                            <div class="img_preview_input_outer pull-left">
                              <label for="qualification_certificate_file" class="form_label">Upload Qualification Certificate <sup class="text-danger">*</sup></label>
                              <?php /* <input type="file" name="qualification_certificate_file" id="qualification_certificate_file" class="form-control" accept=".png,.jpeg,.jpg" data-accept=".jpg,.jpeg,.pdf" onchange="show_preview(event, 'qualification_certificate_file_preview'); validate_input('qualification_certificate_file');" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['qualification_certificate_file'] == "")) { echo 'required'; } ?> />
                              <note class="form_note" id="qualification_certificate_file_err">Note: Please upload only .jpg, .jpeg, or .png files between 75KB and 100KB in size.</note> */ ?>
                              
                            <input type="file" name="qualification_certificate_file" id="qualification_certificate_file" class="form-control hide_input_file_cropper" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['qualification_certificate_file'] == "")) { echo 'required'; } ?> />

                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('qualification_certificate_file', 'iibfbcbf_batch_candidates', 'Edit Qualification Certificate')">Upload Qualification Certificate</button>
                            </div>
                            <note class="form_note" id="qualification_certificate_file_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                            <input type="hidden" name="qualification_certificate_file_cropper" id="qualification_certificate_file_cropper" value="<?php echo set_value('qualification_certificate_file_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                              
                              <?php if(form_error('qualification_certificate_file')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('qualification_certificate_file'); ?></label> <?php } ?>
                              <?php if($qualification_certificate_file_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $qualification_certificate_file_error; ?></label> <?php } ?>
                            </div>
                            
                            <div id="qualification_certificate_file_preview" class="upload_img_preview pull-right">
                              <?php 
                              $preview_qualification_certificate_file = '';
                              if($mode == 'Add' && set_value('qualification_certificate_file_cropper') != "") 
                              {
                                $preview_qualification_certificate_file = set_value('qualification_certificate_file_cropper');
                              }
                              else if($mode == 'Update' && $form_data[0]['qualification_certificate_file'] != "") 
                              { 
                                $preview_qualification_certificate_file = $form_data[0]['qualification_certificate_file'];
                                $preview_qualification_certificate_file = base_url($qualification_certificate_file_path.'/'.$preview_qualification_certificate_file);
                              }
                                
                              if($preview_qualification_certificate_file != "")
                              { ?>
                                <a href="<?php echo $preview_qualification_certificate_file."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Qualification Certificate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                  <img src="<?php echo $preview_qualification_certificate_file."?".time(); ?>">
                                </a>

                                <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="qualification_certificate_file" data-db_tbl_name="iibfbcbf_batch_candidates" data-title="Edit Qualification Certificate" title="Edit Qualification Certificate" alt="Edit Qualification Certificate"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                              <?php }
                              else
                              {
                                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                              } ?>
                            </div><div class="clearfix"></div>
                          </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php // Upload Passport-size Photo ?>
                          <div class="form-group">
                            <div class="img_preview_input_outer pull-left">
                              <label for="candidate_photo" class="form_label">Upload Passport-size Photo <sup class="text-danger">*</sup></label>
                              <?php /* <input type="file" name="candidate_photo" id="candidate_photo" class="form-control" accept=".png,.jpeg,.jpg" data-accept=".jpg,.jpeg,.pdf" onchange="show_preview(event, 'candidate_photo_preview'); validate_input('candidate_photo');" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['candidate_photo'] == "")) { echo 'required'; } ?> />
                              
                              <note class="form_note" id="candidate_photo_err">Note: Please upload only .jpg, .jpeg, or .png files between 14KB and 20KB in size.</note> */ ?>
                              
                            <input type="file" name="candidate_photo" id="candidate_photo" class="form-control hide_input_file_cropper" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['candidate_photo'] == "")) { echo 'required'; } ?> />

                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('candidate_photo', 'iibfbcbf_batch_candidates', 'Edit Photo')">Upload Photo</button>
                            </div>
                            <note class="form_note" id="candidate_photo_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                            <input type="hidden" name="candidate_photo_cropper" id="candidate_photo_cropper" value="<?php echo set_value('candidate_photo_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                              
                              <?php if(form_error('candidate_photo')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_photo'); ?></label> <?php } ?>
                              <?php if($candidate_photo_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $candidate_photo_error; ?></label> <?php } ?>
                            </div>
                            
                            <div id="candidate_photo_preview" class="upload_img_preview pull-right">
                              <?php 
                                $preview_candidate_photo = '';
                              if($mode == 'Add' && set_value('candidate_photo_cropper') != "") 
                              {
                                $preview_candidate_photo = set_value('candidate_photo_cropper');
                              }
                              else if($mode == 'Update' && $form_data[0]['candidate_photo'] != "") 
                              { 
                                $preview_candidate_photo = $form_data[0]['candidate_photo'];
                                $preview_candidate_photo = base_url($candidate_photo_path.'/'.$preview_candidate_photo);
                              }
                                
                              if($preview_candidate_photo != "")
                              { ?>
                                <a href="<?php echo $preview_candidate_photo."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Passport-size Photo of the Candidate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                  <img src="<?php echo $preview_candidate_photo."?".time(); ?>">
                                </a>

                                <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="candidate_photo" data-db_tbl_name="iibfbcbf_batch_candidates" data-title="Edit Photo" title="Edit Photo" alt="Edit Photo"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                              <?php }
                              else
                              {
                                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                              } ?>
                            </div><div class="clearfix"></div>
                          </div>
                        </div>
                        
                        <div class="col-xl-6 col-lg-6"><?php // Upload Signature of the Candidate ?>
                          <div class="form-group">
                            <div class="img_preview_input_outer pull-left">
                              <label for="candidate_sign" class="form_label">Upload Signature of the Candidate <sup class="text-danger">*</sup></label>
                              <?php /* <input type="file" name="candidate_sign" id="candidate_sign" class="form-control ignore_required" accept=".png,.jpeg,.jpg" data-accept=".jpg,.jpeg,.pdf" onchange="show_preview(event, 'candidate_sign_preview'); validate_input('candidate_sign');" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['candidate_sign'] == "")) { echo 'required'; } ?> />
                              
                              <note class="form_note" id="candidate_sign_err">Note: Please upload only .jpg, .jpeg, or .png files between 14KB and 20KB in size.</note>  */ ?>
                              
                            <input type="file" name="candidate_sign" id="candidate_sign" class="form-control hide_input_file_cropper" <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['candidate_sign'] == "")) { echo 'required'; } ?> />

                            <div class="image-input image-input-outline image-input-circle image-input-empty">
                              <div class="profile-progress"></div>                              
                              <button type="button" class="btn btn-sm btn-primary w-100 mb-1" onclick="open_img_upload_modal('candidate_sign', 'iibfbcbf_batch_candidates', 'Edit Signature')">Upload Signature</button>
                            </div>
                            <note class="form_note" id="candidate_sign_err">Note: Please select only .jpg, .jpeg, .png file upto 20MB.</note>

                            <input type="hidden" name="candidate_sign_cropper" id="candidate_sign_cropper" value="<?php echo set_value('candidate_sign_cropper'); ?>" /><?php /* FOR CROPPED IMAGE */ ?>
                              
                              <?php if(form_error('candidate_sign')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_sign'); ?></label> <?php } ?>
                              <?php if($candidate_sign_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $candidate_sign_error; ?></label> <?php } ?>
                            </div>
                            
                            <div id="candidate_sign_preview" class="upload_img_preview pull-right">
                              <?php 
                                $preview_candidate_sign = '';
                              if($mode == 'Add' && set_value('candidate_sign_cropper') != "") 
                              {
                              $preview_candidate_sign = set_value('candidate_sign_cropper'); 
                              }
                              else if($mode == 'Update' && $form_data[0]['candidate_sign'] != "") 
                              { 
                                $preview_candidate_sign = $form_data[0]['candidate_sign'];
                                $preview_candidate_sign = base_url($candidate_sign_path.'/'.$preview_candidate_sign);
                              }
                                
                              if($preview_candidate_sign != "")
                              { ?>
                                <a href="<?php echo $preview_candidate_sign."?".time(); ?>" class="example-image-link" data-lightbox="candidate_images" data-title="<?php echo 'Signature of the Candidate - '.$preview_first_name; echo $preview_training_id != ""?" (".$preview_training_id.")":""; ?>">
                                  <img src="<?php echo $preview_candidate_sign."?".time(); ?>">
                                </a>

                                <button type="button" class="edit_existing_image btn btn-primary" data-current_image_id="candidate_sign" data-db_tbl_name="iibfbcbf_batch_candidates" data-title="Edit Signature" title="Edit Signature" alt="Edit Signature"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                              <?php }
                              else
                              {
                                echo '<i class="fa fa-picture-o" aria-hidden="true"></i>';
                              } ?>
                            </div><div class="clearfix"></div>
                          </div>
                        </div>
                        
                        <div class="col-xl-12 col-lg-12"><?php /* Aadhar Number */ ?>
                          <div class="form-group">
                            <label for="aadhar_no" class="form_label">Aadhar Number <sup class="text-danger"></sup></label>
                            <input type="text" name="aadhar_no" id="aadhar_no" value="<?php if($mode == "Add") { echo set_value('aadhar_no'); } else { echo $form_data[0]['aadhar_no']; } ?>" placeholder="Aadhar Number" class="form-control custom_input allow_only_numbers" maxlength="12" minlength="12" />
                            
                            <note class="form_note" id="aadhar_no_err">Note: Please enter aadhar number like: 666635870783</note>
                            
                            <?php if(form_error('aadhar_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('aadhar_no'); ?></label> <?php } ?>
                          </div>					
                        </div>
                      </div>
                      
                      <div class="hr-line-dashed"></div>										
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer2">
                          <input type="submit" class="btn btn-primary" id="submitAll" name="submitAll" value="<?php if($mode == 'Add') { echo "Submit II"; } else { echo "Update Candidate"; } ?>" onclick="validate_all_details(2)">  
                          
                          <input type="submit" class="btn btn-warning" id="PreviewForm" name="PreviewForm" value="Preview" onclick="validate_all_details(3)">  
                          
                          <a class="btn btn-danger" href="<?php echo site_url('iibfbcbf/admin/batch_candidates/index/'.url_encode($batch_data[0]['batch_id'])); ?>">Back</a>
                        </div>
                      </div>
                  </form>
                </div>
              </div>
              
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
        <?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>		
      </div>
    </div>
    
    <?php /****** START : Modal for display the form preview data  ******/ ?>
    <div class="modal inmodal fade" id="show_preview_modal" tabindex="-1" role="dialog"  aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Candidate Detsils Preview</h4>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-bordered custom_inner_tbl" style="width: 100%;background: #fff;margin: 0;">
                <tbody>
                  <tr><td class="text-center heading_row" colspan="2" ><b>Basic Details</b></td></tr>
                  <?php if($mode == 'Update') { ?>
                    <tr>
                      <td class="wrap" colspan="2"><b>Training ID</b> : <?php echo $form_data[0]['training_id']; ?></td>
                    </tr>
                  <?php } ?>
                  
                  <tr>
                    <td class="wrap">
                      <b>Candidate Full Name</b> : 
                      <span id="preview_salutation"></span>
                      <span id="preview_first_name"></span>
                      <span id="preview_middle_name"></span>
                      <span id="preview_last_name"></span>
                    </td>
                    <td class="wrap"><b>Date of Birth</b> : <span id="preview_dob"></span></td>
                  </tr>
                  
                  <tr>
                    <td class="wrap"><b>Gender</b> : <span id="preview_gender"></span></td>
                    <td class="wrap"><b>Mobile Number</b> : <span id="preview_mobile_no"></span></td>
                  </tr>
                  
                  <tr>
                    <td class="wrap"><b>Alternate Mobile Number</b> : <span id="preview_alt_mobile_no"></span></td>
                    <td class="wrap"><b>Email id</b> : <span id="preview_email_id"></span></td>
                  </tr>
                  
                  <tr>
                    <td class="wrap"><b>Alternate Email id</b> : <span id="preview_alt_email_id"></span></td>
                    <td class="wrap"><b>Qualification</b> : <span id="preview_qualification"></span></td>
                  </tr>
                  <tr><td class="empty_row" colspan="2"></td></tr>
                  
                  <tr><td class="text-center heading_row" colspan="2"><b>Other Details</b></td></tr>
                  <tr>
                    <td class="wrap"><b>Address Line-1</b> : <span id="preview_address1"></span></td>
                    <td class="wrap"><b>Address Line-2</b> : <span id="preview_address2"></span></td>
                  </tr>
                  <tr>
                    <td class="wrap"><b>Address Line-3</b> : <span id="preview_address3"></span></td>
                    <td class="wrap"><b>Address Line-4</b> : <span id="preview_address4"></span></td>
                  </tr>
                  <tr>
                    <td class="wrap"><b>State</b> : <span id="preview_state"></span></td>
                    <td class="wrap"><b>City</b> : <span id="preview_city"></span></td>
                  </tr>
                  <tr>
                    <td class="wrap"><b>District</b> : <span id="preview_district"></span></td>
                    <td class="wrap"><b>Pincode</b> : <span id="preview_pincode"></span></td>
                  </tr>
                  <tr>
                    <td class="wrap" colspan="2"><b>Bank Employee Id</b> : <span id="preview_bank_emp_id"></span></td>
                    <!-- <td class="wrap">&nbsp;</td> -->
                  </tr>
                  <tr>
										<td class="wrap"><b>Affiliated with the Bank as a BC</b> : <span id="preview_associated_with_any_bank"></span></td>
                    <td class="wrap"><b>Bank associated with</b> : <span id="preview_bank_associated"></span></td>
                  </tr>

									<tr>
                    <td class="wrap"><b>Are you associated with a Corporate BC?</b> : <span id="preview_are_you_corporate_bc"></span></td>
                    <td id="td_prev_are_you_corporate_bc_no" style="display:none;" class="wrap"></td>
                    <td id="td_prev_corporate_bc_option" class="wrap"><b>Select Corporate BC</b> : <span id="preview_corporate_bc_option"></span></td> 
                  </tr>

                  <tr id="tr_prev_corporate_bc_associated">
                    <td class="wrap"><b>Corporate BC associated with</b> : <span id="preview_corporate_bc_associated"></span></td>
                    <td class="wrap"></td>
                  </tr>

                  <tr><td class="empty_row" colspan="2"></td></tr>
                  
                  <tr><td class="text-center heading_row" colspan="2"><b>Document Details</b></td></tr>
                  <tr>
                    <td class="wrap"><b>ID Proof Type</b> : <span id="preview_id_proof_type"></span></td>
                    <td class="wrap"><b>Id Proof Number</b> : <span id="preview_id_proof_number"></span></td>
                  </tr>
                  <tr>
                    <td class="wrap"><b>Qualification Certificate Type</b> : <span id="preview_qualification_certificate_type"></span></td>
                    <td class="wrap"><b>Aadhar Number</b> : <span id="preview_aadhar_no"></span></td>
                  </tr>
                  
                  <tr>
                    <td class="wrap"><b>Proof of Identity</b> : <span id="preview_id_proof_file" class="upload_img_preview" style="display:block;"></span></td>
                    <td class="wrap"><b>Qualification Certificate</b> : <span id="preview_qualification_certificate_file" class="upload_img_preview" style="display:block;"></span></td>
                  </tr>
                  
                  <tr>
                    <td class="wrap"><b>Passport-size Photo of the Candidate</b> : <span id="preview_candidate_photo" class="upload_img_preview" style="display:block;"></span></td>
                    <td class="wrap"><b>Signature of the Candidate</b> : <span id="preview_candidate_sign" class="upload_img_preview" style="display:block;"></span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="modal-footer" id="submit_btn_outer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div><?php /****** END : Modal for display the form preview data  ******/ ?>
    
    <?php $this->load->view('iibfbcbf/inc_footer'); ?>		
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
  	<?php $this->load->view('iibfbcbf/common/inc_cropper_script', array('page_name'=>'bcbf_admin_add_candidate')); ?>
    
    <?php  if($mode == 'Update') {
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_candidate_id, 'module_slug'=>'candidate_action', 'log_title'=>'Candidate Log'));
    } ?>    
    
    <script type="text/javascript">
      /********** START : Function to display the form data into preview modal ***********/
      function show_preview_modal()
      {
        <?php if($mode == 'Update') { ?>
          $("#preview_salutation").html($("#salutation").val());
          $("#preview_salutation").html($("#salutation").val());
        <?php } ?>
        
        $("#preview_salutation").html($("#salutation").val());
        $("#preview_first_name").html($("#first_name").val());
        $("#preview_middle_name").html($("#middle_name").val());
        $("#preview_last_name").html($("#last_name").val());
        $("#preview_dob").html($("#dob").val());
        
        let preview_gender = 'Male'; if($("input[name=gender]:checked").val() == '2') { preview_gender = 'Female'; }
        $("#preview_gender").html(preview_gender);
        
        $("#preview_mobile_no").html($("#mobile_no").val());
        $("#preview_alt_mobile_no").html($("#alt_mobile_no").val());
        $("#preview_email_id").html($("#email_id").val());
        $("#preview_alt_email_id").html($("#alt_email_id").val());
        
        //'1'=>'Under Graduate', '2'=>'Graduate', '3'=>'Post Graduate'
        let preview_qualification = 'Under Graduate'; 
        if($("input[name=qualification]:checked").val() == '2') { preview_qualification = 'Graduate'; }
        else if($("input[name=qualification]:checked").val() == '3') { preview_qualification = 'Post Graduate'; }
        $("#preview_qualification").html(preview_qualification);
        
        $("#preview_address1").html($("#address1").val());
        $("#preview_address2").html($("#address2").val());
        $("#preview_address3").html($("#address3").val());
        $("#preview_address4").html($("#address4").val());
        $("#preview_state").html($("#state option:selected").text());
        $("#preview_city").html($("#city option:selected").text());
        $("#preview_district").html($("#district").val());
        $("#preview_pincode").html($("#pincode").val());
        $("#preview_bank_emp_id").html($("#bank_emp_id").val());

        let preview_associated_with_any_bank = 'Yes'; if($("input[name=associated_with_any_bank]:checked").val() == '2') { preview_associated_with_any_bank = 'No'; }
				$("#preview_associated_with_any_bank").html(preview_associated_with_any_bank);

        $("#preview_bank_associated").html($("#bank_associated option:selected").text());
        if($('#bank_associated').val() == 'Other') 
        { 
          $("#preview_bank_associated").html($("#bank_associated").val()+" - "+$("#bank_associated_other").val()); 
        }

        $("#preview_are_you_corporate_bc").html($("input[name='are_you_corporate_bc']:checked").val());
        $("#preview_corporate_bc_option").html($("input[name='corporate_bc_option']:checked").val()); 
        $("#preview_corporate_bc_associated").html($("#corporate_bc_associated").val());

        if($("input[name='are_you_corporate_bc']:checked").val() == 'No') {
          $("#td_prev_corporate_bc_option").hide();
          $("#td_prev_are_you_corporate_bc_no").show();
          $("#tr_prev_corporate_bc_associated").hide();
        }else{
          $("#td_prev_corporate_bc_option").show(); 
          $("#td_prev_are_you_corporate_bc_no").hide();
          if($("input[name='corporate_bc_option']:checked").val() == 'CSC') {
            $("#tr_prev_corporate_bc_associated").hide();
          }else{
            $("#tr_prev_corporate_bc_associated").show();
          }
        }

        
        
        
        //'1'=>'Aadhar Card', '2'=>'Driving Licence', '3'=>'Employee ID', '4'=>'Pan Card', '5'=>'Passport'
        let preview_id_proof_type = 'Aadhar Card'; 
        if($("input[name=id_proof_type]:checked").val() == '2') { preview_id_proof_type = 'Driving Licence'; }
        else if($("input[name=id_proof_type]:checked").val() == '3') { preview_id_proof_type = 'Employee ID'; }
        else if($("input[name=id_proof_type]:checked").val() == '4') { preview_id_proof_type = 'Pan Card'; }
        else if($("input[name=id_proof_type]:checked").val() == '5') { preview_id_proof_type = 'Passport'; }
        $("#preview_id_proof_type").html(preview_id_proof_type);
        
        $("#preview_id_proof_number").html($("#id_proof_number").val());      
        
        //'1'=>'10th Pass', '2'=>'12th Pass', '3'=>'Graduation', '4'=>'Post Graduation'
        let preview_qualification_certificate_type = ''; 
        if($("input[name=qualification_certificate_type]:checked").val() == '1') { preview_qualification_certificate_type = '10th Pass'; }
        else if($("input[name=qualification_certificate_type]:checked").val() == '2') { preview_qualification_certificate_type = '12th Pass'; }
        else if($("input[name=qualification_certificate_type]:checked").val() == '3') { preview_qualification_certificate_type = 'Graduation'; }
        else if($("input[name=qualification_certificate_type]:checked").val() == '4') { preview_qualification_certificate_type = 'Post Graduation'; }
        $("#preview_qualification_certificate_type").html(preview_qualification_certificate_type);
        
        $("#preview_aadhar_no").html($("#aadhar_no").val());
        
        $("#preview_id_proof_file").html($("#id_proof_file_preview").html());
        $("#preview_qualification_certificate_file").html($("#qualification_certificate_file_preview").html());
        $("#preview_candidate_photo").html($("#candidate_photo_preview").html());
        $("#preview_candidate_sign").html($("#candidate_sign_preview").html());
        
        $("#page_loader").hide();
        $("#show_preview_modal").modal({backdrop: 'static', keyboard: false}, 'show');
      }/********** END : Function to display the form data into preview modal ***********/
      
      var dob = $('#dob').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", clearBtn: true,  endDate:"<?php echo $dob_end_date; ?>" });
      
      /********** START : Function to get the city dropdown values as per state selection ***********/
      function get_city_ajax(state_id)
      {
        $("#page_loader").show();
        parameters="state_id="+state_id;
        
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/get_city_ajax'); ?>",
          data: parameters,
          cache: false,
          dataType: 'JSON',
          async:false,
          success:function(data)
          {
            if(data.flag == "success")
            {
              $("#city_outer").html(data.response);
              $("#page_loader").hide();
            }
            else
            {
              alert("Error occurred. Please try again.")
              $('#page_loader').hide();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            console.log('AJAX request failed: ' + errorThrown);
            alert("Error occurred. Please try again.")
            $('#page_loader').hide();
          }
        });
      }/********** END : Function to get the city dropdown values as per state selection ***********/
      
      /********** START : On Qualification selection, enable/disable the Qualification Certificate Type radio buttons ***********/
      function show_hide_qualification_certificate_type(chk_val)
      {
        var selectedqualification = $('input[name="qualification"]:checked').val();        
        
        if(selectedqualification == '1')//'Under Graduate'
        {
          $("#qualification_certificate_type1").prop( "checked", false );
          $("#qualification_certificate_type2").prop( "checked", false );
          $("#qualification_certificate_type3").prop( "checked", false );
          $("#qualification_certificate_type4").prop( "checked", false );
          
          $("#qualification_certificate_type1").prop( "disabled", false );
          $("#qualification_certificate_type2").prop( "disabled", false );
          $("#qualification_certificate_type3").prop( "disabled", true );
          $("#qualification_certificate_type4").prop( "disabled", true );
          
          $("#qualification_certificate_type1").parent('label').removeClass('disabled');
          $("#qualification_certificate_type2").parent('label').removeClass('disabled');
          $("#qualification_certificate_type3").parent('label').addClass('disabled'); 
          $("#qualification_certificate_type4").parent('label').addClass('disabled');
          
          //$("#qualification_certificate_type1").click();
          <?php if($mode == 'Add') 
            { 
              if(set_value('qualification_certificate_type') == "1")
              { ?>
              $("#qualification_certificate_type1").click();
              <?php }
              else if(set_value('qualification_certificate_type') == "2")
              {?>
              $("#qualification_certificate_type2").click();
              <?php }
            }
            else if($mode == 'Update') 
            {
              if($form_data[0]['qualification_certificate_type'] == "1")
              { ?>
              $("#qualification_certificate_type1").click();
              <?php }
              else if($form_data[0]['qualification_certificate_type'] == "2")
              {?>
              $("#qualification_certificate_type2").click();
              <?php }
            } ?>
        }
        else if(selectedqualification == '2')//'Graduate'
        {
          $("#qualification_certificate_type1").prop( "checked", false );
          $("#qualification_certificate_type2").prop( "checked", false );
          $("#qualification_certificate_type3").prop( "checked", true );
          $("#qualification_certificate_type4").prop( "checked", false );
          
          $("#qualification_certificate_type1").prop( "disabled", true );
          $("#qualification_certificate_type2").prop( "disabled", true );
          $("#qualification_certificate_type3").prop( "disabled", false );
          $("#qualification_certificate_type4").prop( "disabled", true );
          
          $("#qualification_certificate_type1").parent('label').addClass('disabled');
          $("#qualification_certificate_type2").parent('label').addClass('disabled');
          $("#qualification_certificate_type3").parent('label').removeClass('disabled'); 
          $("#qualification_certificate_type4").parent('label').addClass('disabled'); 
          
          $("#qualification_certificate_type3").click();
        }
        else if(selectedqualification == '3')//'Post Graduate'
        {
          $("#qualification_certificate_type1").prop( "checked", false );
          $("#qualification_certificate_type2").prop( "checked", false );
          $("#qualification_certificate_type3").prop( "checked", false );
          $("#qualification_certificate_type4").prop( "checked", true );
          
          $("#qualification_certificate_type1").prop( "disabled", true );
          $("#qualification_certificate_type2").prop( "disabled", true );
          $("#qualification_certificate_type3").prop( "disabled", true );
          $("#qualification_certificate_type4").prop( "disabled", false );
          
          $("#qualification_certificate_type1").parent('label').addClass('disabled');
          $("#qualification_certificate_type2").parent('label').addClass('disabled');
          $("#qualification_certificate_type3").parent('label').addClass('disabled'); 
          $("#qualification_certificate_type4").parent('label').removeClass('disabled'); 
          
          $("#qualification_certificate_type4").click();
        }
      }/********** END : On Qualification selection, enable/disable the Qualification Certificate Type radio buttons ***********/
      show_hide_qualification_certificate_type();
      
      /********** START : On Salutation selection, enable/disable the Gender radio buttons ***********/
      function show_hide_gender()
      {
        var selectedGender = $('#salutation').val();              
        
        if(selectedGender == 'Mr.')//'Mr.'
        {
          $("#gender_male").prop( "checked", true );
          $("#gender_female").prop( "checked", false );
          
          $("#gender_male").prop( "disabled", false );
          $("#gender_female").prop( "disabled", true );
          
          $("#gender_male").parent('label').removeClass('disabled');
          $("#gender_female").parent('label').addClass('disabled');
        }
        else if(selectedGender == 'Mrs.' || selectedGender == 'Ms.')//'Mrs, Miss'
        {
          $("#gender_male").prop( "checked", false );
          $("#gender_female").prop( "checked", true );
          
          $("#gender_male").prop( "disabled", true );
          $("#gender_female").prop( "disabled", false );
          
          $("#gender_male").parent('label').addClass('disabled');
          $("#gender_female").parent('label').removeClass('disabled');           
        }
      }/********** END : On Salutation selection, enable/disable the Gender radio buttons ***********/
      show_hide_gender();
      
      /********** START : Function for applying validation to 'Id Proof Number' input field  ***********/
      function id_proof_number_validation(is_validate='')
      {
        var selectedValue = $('input[name="id_proof_type"]:checked').val();
        
        if(typeof selectedValue != "undefined") 
        {
          //1=>Aadhar Card, 2=>Driving Licence, 3=>Employee's Card, 4=>Pan Card, 5=>Passport
          let msg = '';
          $('#id_proof_number').removeAttr('maxlength');
          $("#id_proof_number").removeClass('allow_only_numbers');
          $("#id_proof_number").removeClass('allow_only_alphabets_and_numbers');
          
          if(selectedValue == '1') //Aadhar Card
          {
            msg = 'Note: Please enter aadhar number like: 666635870783';
            
            $("#id_proof_number").prop('maxlength','12');
            $("#id_proof_number").addClass('allow_only_numbers');
            $('.allow_only_numbers').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_numbers'); });
          }
          else if(selectedValue == '2') //Driving Licence
          {
            msg = 'Note: Please enter driving license number like: MH2730123476102';
            
            $("#id_proof_number").prop('maxlength','15');
            $("#id_proof_number").addClass('allow_only_alphabets_and_numbers');
            $('.allow_only_alphabets_and_numbers').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_numbers'); });
          }
          else if(selectedValue == '3') //Employee's Id
          {
            msg = 'Note: Please enter maximum 10 character in employee number';
            
            $("#id_proof_number").prop('maxlength','10');
            $("#id_proof_number").addClass('allow_only_alphabets_and_numbers');
            $('.allow_only_alphabets_and_numbers').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_numbers'); });
          }
          else if(selectedValue == '4') //Pan Card
          {
            msg = 'Note: Please enter PAN number like: ABCTY1234D';
            
            $("#id_proof_number").prop('maxlength','10');
            $("#id_proof_number").addClass('allow_only_alphabets_and_numbers');
            $('.allow_only_alphabets_and_numbers').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_numbers'); });
          }
          else if(selectedValue == '5') //Passport
          {
            msg = 'Note: Please enter passport number like: J8369845';
            
            $("#id_proof_number").prop('maxlength','8');
            $("#id_proof_number").addClass('allow_only_alphabets_and_numbers');
            $('.allow_only_alphabets_and_numbers').on('keydown', function (e) { restrict_input(e,'allow_only_alphabets_and_numbers'); });
          }
          
          if(msg != "") 
          { 
            $("#id_proof_number_err").html(msg);  
            if(is_validate == '') { $("#id_proof_number").val(""); }
            $("#id_proof_number-error").html("");
          }
          
          if(is_validate == '')
          {
            if($.trim($("#id_proof_number").val()) != "") { validate_input('id_proof_number'); }
          }
        }        
      }/********** END : Function for applying validation to 'Id Proof Number' input field  ***********/
      id_proof_number_validation('0');      
      
      //START : JQUERY VALIDATION SCRIPT 
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
      {
        $("#add_candidate_form").submit(function() 
        {
          if($("#address1").valid() == false) { }
          else if($("#state").valid() == false) { $('#state').trigger('chosen:activate'); }
          else if($("#city").valid() == false) { $('#city').trigger('chosen:activate'); }
					else if($("#bank_associated").valid() == false) { $('#bank_associated').trigger('chosen:activate'); }
        });
        
        //START : VALIDATE Id Proof Number
        $.validator.addMethod("validate_id_proof_number", function(value, element)
        {
          //1=>Aadhar Card, 2=>Driving Licence, 3=>Employee's Id, 4=>Pan Card, 5=>Passport
          var selectedIdProofType = $('input[name="id_proof_type"]:checked').val();
          
          if($.trim(value).length == 0) 
          { 
            let err_msg1 = 'Please enter the id proof number';
            
            if(typeof selectedIdProofType != "undefined")
            {
              if(selectedIdProofType == '1') { err_msg1 = 'Please enter the aadhar card number'; }
              else if(selectedIdProofType == '2') { err_msg1 = 'Please enter the driving licence number'; }
              else if(selectedIdProofType == '3') { err_msg1 = 'Please enter the employee id number'; }
              else if(selectedIdProofType == '4') { err_msg1 = 'Please enter the pan card number'; }
              else if(selectedIdProofType == '5') { err_msg1 = 'Please enter the passport number'; }
            }
            
            $.validator.messages.validate_id_proof_number = err_msg1;
            return false; 
          }
          else
          {
            if(typeof selectedIdProofType != "undefined")
            {
              if(selectedIdProofType == '1')//Aadhar Card
              {
                var regex = /([0-9]){12}$/;
                if (regex.test(value)) { return true; } 
                else 
                { 
                  $.validator.messages.validate_id_proof_number = "Please enter valid aadhar card number";
                  return false; 
                }
              } 
              else if(selectedIdProofType == '2') //Driving Licence
              {
                var regex = /([A-Z]){2}([0-9]){13}$/;
                if (regex.test(value)) { return true; } 
                else 
                { 
                  $.validator.messages.validate_id_proof_number = "Please enter valid driving licence number";
                  return false; 
                }
              }
              else if(selectedIdProofType == '3') //Employee's Id
              {
                return true;
              }
              else if(selectedIdProofType == '4') //Pan Card
              { 
                var regex = /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
                if (regex.test(value)) { return true; } 
                else 
                { 
                  $.validator.messages.validate_id_proof_number = "Please enter valid pan card number";
                  return false; 
                }
              }
              else if(selectedIdProofType == '5') //Passport
              {
                var regex = /([A-Z]){1}([0-9]){7}$/;
                if (regex.test(value)) { return true; } 
                else 
                { 
                  $.validator.messages.validate_id_proof_number = "Please enter valid passport number";
                  return false; 
                }
              }
              else
              {
                return true;
              }
            }
            else
            {
              return true;
            }
          }
        });//END : VALIDATE Id Proof Number
        
        //START : VALIDATE validate_qualification_certificate_type
        $.validator.addMethod("validate_qualification_certificate_type", function(value, element)
        {
          var selectedQualification = $('input[name="qualification"]:checked').val();
          
          if($.trim(value).length == 0) { return true; }
          {
            if(typeof selectedQualification != "undefined")
            {
              let current_qualification_certificate_type = $.trim(value);
              if(selectedQualification == '1')//Under Graduate
              {
                if(current_qualification_certificate_type == 1 || current_qualification_certificate_type == 2) { return true; }
                else 
                { 
                  $.validator.messages.validate_qualification_certificate_type = "Invalid qualification certificate type selected";
                  return false; 
                }
              } 
              else if(selectedQualification == '2') //Graduate
              {
                if(current_qualification_certificate_type == 3) { return true; }
                else 
                { 
                  $.validator.messages.validate_qualification_certificate_type = "Invalid qualification certificate type selected";
                  return false; 
                }
              }
              else if(selectedQualification == '3') //post Graduate
              {
                if(current_qualification_certificate_type == 4) { return true; }
                else 
                { 
                  $.validator.messages.validate_qualification_certificate_type = "Invalid qualification certificate type selected";
                  return false; 
                }
              }              
              else
              {
                return true;
              }
            }
            else
            {
              return true;
            }
          }
        });//END : VALIDATE validate_qualification_certificate_type
        
        //START : VALIDATE GENDER
        $.validator.addMethod("validate_gender", function(value, element)
        {
          var selectedSalutation = $('#salutation').val();
          
          if($.trim(value).length == 0) { return true; }
          {
            if(typeof selectedSalutation != "undefined")
            {
              let current_gender = $.trim(value);
              if(selectedSalutation == 'Mr.')//Mr.
              {
                if(current_gender == 1) { return true; }
                else 
                { 
                  $.validator.messages.validate_gender = "Invalid gender selected";
                  return false; 
                }
              } 
              else if(selectedSalutation == 'Mrs.' || selectedSalutation == 'Ms.') //Mrs. or Ms.
              {
                if(current_gender == 2) { return true; }
                else 
                { 
                  $.validator.messages.validate_gender = "Invalid gender selected";
                  return false; 
                }
              }              
              else
              {
                return true;
              }
            }
            else
            {
              return true;
            }
          }
        });//END : VALIDATE GENDER
        
        $.validator.addMethod("validate_dob", function(value, element)
        {
          if($.trim(value).length == 0) { return true; }
          else
          {
            var current_val = $.trim(value);
            //var chk_dob_start_date = "<?php /* echo $dob_start_date; */ ?>";
            var chk_dob_end_date = "<?php echo $dob_end_date; ?>";
            
            //if(current_val >= chk_dob_start_date && current_val <= chk_dob_end_date)
            if(current_val <= chk_dob_end_date)
            { 
              return true;              
            }
            else 
            { 
              //$.validator.messages.validate_dob = "Select date of birth between <?php /* echo $dob_start_date; */ ?> to <?php /* echo $dob_end_date; */ ?> date";
              $.validator.messages.validate_dob = "Select the date of birth before <?php echo date('Y-m-d', strtotime("+1days",strtotime($dob_end_date))); ?> date";
              return false;
            }
          }
        }); 
        
        //START : VALIDATION TO CHECK THE TOTAL CANDIDATES CAN NOT BE MORE THAN DEFINE QUALIFICATION CANDIDATE COUNT
        //WHILE CREATING BATCH, IF CENTER SELECTED 5 CANDIDATES AS GRADUATE, THEN THEY CAN NOT ADD MORE THAN 5 GRADUATE CANDIDATES AS GRADUATE QUALIFICTION CANDIDATES  
        $.validator.addMethod("validate_qualification_candidates", function(value, element)
        {
          if($.trim(value).length == 0) { return true; }
          else
          {
            var isSuccess = false;
            var parameter = { "qualification":$.trim(value), "enc_batch_id":"<?php echo $enc_batch_id; ?>", "enc_candidate_id":"<?php echo $enc_candidate_id; ?>" }
            $.ajax(
            {
              type: "POST",
              url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/validation_check_qualification_candidates/0/1'); ?>",
              data: parameter,
              async: false,
              cache : false,
              dataType: 'JSON',
              success: function(data)
              {
                if($.trim(data.flag) == 'success')
                {
                  isSuccess = true;
                }
                
                $.validator.messages.validate_qualification_candidates = data.response;
              }
            });
            
            return isSuccess;
          }
        });//END : VALIDATION TO CHECK THE TOTAL CANDIDATES CAN NOT BE MORE THAN DEFINE QUALIFICATION CANDIDATE COUNT
        
        //START : VALIDATION TO CHECK IF SELECTED 'ID Proof Type' IS 'AADHAR CARD' THEN 'AADHAR NUMBER' MUST BE SAME AS 'ID PROOF NUMBER'
        $.validator.addMethod("CheckAadharNumberWithIdProof", function(value, element)
        {
          if ($('input[name="id_proof_type"]:checked').val() === "1") 
          {
            var id_proof_number_val = $.trim($('input[name="id_proof_number"]').val());
            var aadhar_no_val = $.trim($('input[name="aadhar_no"]').val());
            
            if(id_proof_number_val != "" && aadhar_no_val != "")
            {
              return $('input[name="id_proof_number"]').val() === $('input[name="aadhar_no"]').val();
            }
            else
            {
              return true;
            }
          }
          return true;
        }, "Aadhar Number value must be same as Id Proof Number.");
        //END : VALIDATION TO CHECK IF SELECTED 'ID Proof Type' IS 'AADHAR CARD' THEN 'AADHAR NUMBER' MUST BE SAME AS 'ID PROOF NUMBER'
        
        var form = $("#add_candidate_form").validate( 
        { 
          onkeyup: function(element) { $(element).valid(); },          
          rules:
          {
            salutation:{ required: true }, 
            first_name:{ required: true, allow_only_alphabets_and_space:true,maxlength:20 },
            middle_name:{ allow_only_alphabets_and_space:true,maxlength:20 },
            last_name:{ allow_only_alphabets_and_space:true,maxlength:20 },
            dob:{ required: true, dateFormat:'Y-m-d', validate_dob:true },
            gender:{ required: true, validate_gender: true },
            mobile_no:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10, remote: { url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/validation_check_mobile_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; }, "enc_batch_id":"<?php echo $enc_batch_id; ?>" } } },            
            alt_mobile_no:{ allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10 },
            email_id:{ required: true, maxlength:80, valid_email:true, remote: { url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/validation_check_email_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; }, "enc_batch_id":"<?php echo $enc_batch_id; ?>" } } },
            alt_email_id:{ maxlength:80, valid_email:true },
            qualification:{ required: true, validate_qualification_candidates:true },
            address1:{ required: true, maxlength:75 },
            address2:{ maxlength:75 },
            address3:{ maxlength:75 },
            address4:{ maxlength:75 },
            state:{ required: true },  
            city:{ required: true }, 
            district:{ required: true, allow_only_alphabets_and_numbers_and_space:true, maxlength:30 }, 
            pincode:{ required: true, allow_only_numbers:true, minlength:6, maxlength: 6, remote: { url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/validation_check_valid_pincode/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#state").val(); } } } },  //check validation for pincode as per selected state
            bank_emp_id:{ allow_only_alphabets_and_numbers_and_space:true, maxlength:20 }, 
            associated_with_any_bank:{ required: true }, 
						bank_associated: { }, 

						bank_associated_other:
            { 
              required: function() 
              { 
                if($('#bank_associated').val() == 'Other') { return true; } 
                else { return false; }
              }, 
              allow_only_alphabets_and_space:true, maxlength:90 
            }, 

            are_you_corporate_bc:{ required: true },
            corporate_bc_option:
            { 
              required: function() 
              { 
                if($("input[name='are_you_corporate_bc']:checked").val() == 'Yes') { return true; } 
                else { return false; }
              }
            },
            corporate_bc_associated:
            { 
              required: function() 
              { 
                if($("input[name='corporate_bc_option']:checked").val() == 'Other') { return true; } 
                else { return false; }
              },
              allow_only_alphabets_and_space:true, maxlength:90
            },
            /*corporate_bc_associated:{ allow_only_alphabets_and_space:true, maxlength:90 },*/

            id_proof_type:{ required: true },  
            id_proof_number:{ validate_id_proof_number: true, remote: { url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/validation_check_id_proof_number_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; }, "enc_batch_id":"<?php echo $enc_batch_id; ?>" } } },
            id_proof_file:{ <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['id_proof_file'] == "")) { ?>required: true,<?php } ?> check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'75000', filesize_max:'100000' }, //use size in bytes //filesize_max: 1MB : 1000000 
            qualification_certificate_type:{ required:true, validate_qualification_certificate_type: true },  
            qualification_certificate_file:{ <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['qualification_certificate_file'] == "")) { ?>required: true,<?php } ?> check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'75000', filesize_max:'100000' }, //use size in bytes //filesize_max: 1MB : 1000000 
            candidate_photo:{ <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['candidate_photo'] == "")) { ?>required: true,<?php } ?> check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'14000', filesize_max:'20000' }, //use size in bytes //filesize_max: 1MB : 1000000 
            candidate_sign:{ <?php if($mode == 'Add' || ($mode == 'Update' && $form_data[0]['candidate_sign'] == "")) { ?>required: true,<?php } ?> check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_min:'14000', filesize_max:'20000' }, //use size in bytes //filesize_max: 1MB : 1000000 
            aadhar_no:{ allow_only_numbers:true, maxlength:12, minlength:12, CheckAadharNumberWithIdProof: true, remote: { url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/validation_check_aadhar_no_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; }, "enc_batch_id":"<?php echo $enc_batch_id; ?>" } } },
          },
          messages:
          {
            salutation: { required: "Please select the candidate name (salutation)" },
            first_name: { required: "Please enter the first name" },
            middle_name: { },
            last_name: { },
            dob: { required: "Please select the date of birth", dateFormat:"Please enter the date of birth like yyyy-mm-dd" },
            gender: { required: "Please select the gender" },
            mobile_no: { required: "Please enter the mobile number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number", remote: "The mobile number is already exist" },
            alt_mobile_no: { minlength: "Please enter 10 numbers in alternate mobile number", maxlength: "Please enter 10 numbers in alternate mobile number" },
            email_id: { required: "Please enter the email id", valid_email: "Please enter the valid email id", remote: "The email id is already exist"  },
            alt_email_id: { valid_email: "Please enter the valid alternate email id" },
            qualification: { required: "Please select the qualification" },
            address1: { required: "Please enter the address line-1" },
            address2: { },
            address3: { },
            address4: { },
            state: { required: "Please select the state" },
            city: { required: "Please select the city" },
            district: { required: "Please enter the district" },
            pincode: { required: "Please enter the pincode", minlength: "Please enter 6 numbers in pincode", maxlength: "Please enter 6 numbers in pincode", remote: "Please enter valid pincode as per selected city" },
            bank_emp_id: { },
						associated_with_any_bank: { required: "Please select the option" },
						bank_associated: { required: "Please select the bank associated with" },
						bank_associated_other: { required: "Please select the Other bank associated with" },

            are_you_corporate_bc: { required: "Please select the option associated with a Corporate BC?" },
            corporate_bc_option: { required: "Please select the option Corporate BC" },
            corporate_bc_associated: { required: "Please enter the Corporate BC associated with" },
            /*corporate_bc_associated: { },*/
            id_proof_type: { required: "Please select the id proof type" },
            id_proof_number: { remote : "The id proof number is already exist" },
            id_proof_file: { required: "Please upload the proof of identity", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 100KB" },
            qualification_certificate_type: { required: "Please select the qualification certificate type" },
            qualification_certificate_file: { required: "Please upload the qualification certificate", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 75KB", filesize_max:"Please upload file less than 100KB" },
            candidate_photo: { required: "Please upload the passport-size photo of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
            candidate_sign: { required: "Please upload the signature of the candidate", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_min:"Please upload file greater than 14KB", filesize_max:"Please upload file less than 20KB" },
            aadhar_no: { minlength: "Please enter 12 numbers in aadhar number", maxlength: "Please enter 12 numbers in aadhar number", remote: "The aadhar number is already exist" },
          }, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "first_name") { error.insertAfter("#first_name_err"); }
            else if (element.attr("name") == "middle_name") { error.insertAfter("#middle_name_err"); }
            else if (element.attr("name") == "last_name") { error.insertAfter("#last_name_err"); }
            else if (element.attr("name") == "dob") { error.insertAfter("#dob_err"); }
            else if (element.attr("name") == "gender") { error.insertAfter("#gender_err"); }
            else if (element.attr("name") == "email_id") { error.insertAfter("#email_id_err"); }
            else if (element.attr("name") == "alt_email_id") { error.insertAfter("#alt_email_id_err"); }
            else if (element.attr("name") == "qualification") { error.insertAfter("#qualification_err"); }
            else if (element.attr("name") == "address1") { error.insertAfter("#address1_err"); }
            else if (element.attr("name") == "address2") { error.insertAfter("#address2_err"); }
            else if (element.attr("name") == "address3") { error.insertAfter("#address3_err"); }
            else if (element.attr("name") == "address4") { error.insertAfter("#address4_err"); }
            else if (element.attr("name") == "state") { error.insertAfter("#state_err"); }
            else if (element.attr("name") == "city") { error.insertAfter("#city_err"); }
            else if (element.attr("name") == "district") { error.insertAfter("#district_err"); }
						else if (element.attr("name") == "associated_with_any_bank") { error.insertAfter("#associated_with_any_bank_err"); }
            else if (element.attr("name") == "bank_associated") { error.insertAfter("#bank_associated_err"); }
						else if (element.attr("name") == "bank_associated_other") { error.insertAfter("#bank_associated_other_err"); }

            else if (element.attr("name") == "are_you_corporate_bc") { error.insertAfter("#are_you_corporate_bc_err"); }
            else if (element.attr("name") == "corporate_bc_option") { error.insertAfter("#corporate_bc_option_err"); }
            else if (element.attr("name") == "corporate_bc_associated") { error.insertAfter("#corporate_bc_associated_err"); }
            else if (element.attr("name") == "id_proof_type") { error.insertAfter("#id_proof_type_err"); }
            else if (element.attr("name") == "id_proof_number") { error.insertAfter("#id_proof_number_err"); }
            else if (element.attr("name") == "id_proof_file") { error.insertAfter("#id_proof_file_err"); }
            else if (element.attr("name") == "qualification_certificate_type") { error.insertAfter("#qualification_certificate_type_err"); }
            else if (element.attr("name") == "qualification_certificate_file") { error.insertAfter("#qualification_certificate_file_err"); }
            else if (element.attr("name") == "candidate_photo") { error.insertAfter("#candidate_photo_err"); }
            else if (element.attr("name") == "candidate_sign") { error.insertAfter("#candidate_sign_err"); }
            else if (element.attr("name") == "aadhar_no") { error.insertAfter("#aadhar_no_err"); }
            else { error.insertAfter(element); }
          },          
          submitHandler: function(form) 
          {
            let form_action = $("#form_action").val();
            if(form_action == 3)
            {
              show_preview_modal();
            }
            else
            {
              $("#page_loader").hide();
              swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
              { 
                $("#page_loader").show();
                
                if(form_action == '1')
                {
                  $("#submit_btn_outer1").html('<input type="button" class="btn btn-primary" id="submitFirst" name="submitFirst" value="Submit I " style="cursor:wait">');
                }
                else if(form_action == '2')
                {
                  $("#submit_btn_outer2").html('<input type="button" class="btn btn-primary" id="submitAll" name="submitAll" value="<?php if($mode == 'Add') { echo "Submit II"; } else { echo "Update Candidate"; } ?>" style="cursor:wait"> <a class="btn btn-danger" href="<?php echo site_url('iibfbcbf/admin/batch_candidates/index/'.url_encode($batch_data[0]['batch_id'])); ?>">Back</a>');
                }
                form.submit();
              });   
            }
          }
        });
      });
      //END : JQUERY VALIDATION SCRIPT
      
      <?php if($mode == 'Add')  { ?>
        function validate_basic_details()
        {
          //$("#add_candidate_form").validate();zzz
          $("#page_loader").show();
          $("#form_action").val(1);
          $("#training_id_regnumber_err").html("");
          $(".ignore_required").prop('required',false);
          $("#id_proof_file").prop('required',false);
          $("#qualification_certificate_file").prop('required',false);
          $("#candidate_photo").prop('required',false);
          $("#candidate_sign").prop('required',false);
          
          $("#pincode").val("");
          $("#aadhar_no").val("");
          
          // Remove validation rules for fields other than field1
          $("#address1").rules("remove");
          $("#address2").rules("remove");
          $("#address3").rules("remove");
          $("#address4").rules("remove");
          $("#state").rules("remove");
          $("#city").rules("remove");
          $("#district").rules("remove");
          $("#pincode").rules("remove");
          $("#bank_emp_id").rules("remove");
					
          $("#associated_with_any_bank_yes").rules("remove");
          $("#associated_with_any_bank_no").rules("remove");
          $("#bank_associated").rules("remove"); $("#bank_associated").prop('required',false);
          $("#bank_associated_other").rules("remove"); $("#bank_associated_other").prop('required',false);
          $("#corporate_bc_associated").rules("remove");
          <?php foreach($id_proof_type_arr as $key => $val) { ?> $("#id_proof_type<?php echo $key; ?>").rules("remove"); <?php } ?>        
          $("#id_proof_number").rules("remove");
          $("#id_proof_file").rules("remove"); 
          <?php foreach($qualification_arr as $key => $val) { ?>$("#qualification_certificate_type<?php echo $key; ?>").rules("remove");<?php } ?>
          $("#qualification_certificate_file").rules("remove");
          $("#candidate_photo").rules("remove");
          $("#candidate_sign").rules("remove");      
          $("#aadhar_no").rules("remove");      
          
          if($("#add_candidate_form").valid() == false)// Validate the form
          {
            $("#page_loader").hide();
          }
        }
      <?php } ?>
      
      function validate_all_details(form_action)
      {
        $("#page_loader").show();
        $("#training_id_regnumber_err").html("");
        $("#form_action").val(form_action);
        $(".ignore_required").prop('required',true);

        //console.log("2 "+$('input[name="associated_with_any_bank"]:checked').val());
        if($('input[name="associated_with_any_bank"]:checked').val() == '1') { $("#bank_associated").prop('required',true); }
        else { $("#bank_associated").prop('required',false); }
        
        <?php /* if($mode == 'Add') { ?> if($("#id_proof_file_cropper").val() == "") { $("#id_proof_file").prop('required',true); }<?php }
        else if($mode == 'Update' && $form_data[0]['id_proof_file'] != "") { ?>$("#id_proof_file").prop('required',false);<?php } ?>
        
        <?php if($mode == 'Add') { ?> if($("#qualification_certificate_file_cropper").val() == "") { $("#qualification_certificate_file").prop('required',true); }<?php }
        else if($mode == 'Update' && $form_data[0]['qualification_certificate_file'] != "") { ?>$("#qualification_certificate_file").prop('required',false);<?php } ?>
        
        <?php if($mode == 'Add') { ?> if($("#candidate_photo_cropper").val() == "") { $("#candidate_photo").prop('required',true); }
        <?php }
        else if($mode == 'Update' && $form_data[0]['candidate_photo'] != "") { ?> $("#candidate_photo").prop('required',false); <?php } ?>
        
        <?php if($mode == 'Add') { ?> if($("#candidate_sign_cropper").val() == "") { $("#candidate_sign").prop('required',true); }<?php }
        else if($mode == 'Update' && $form_data[0]['candidate_sign'] != "") { ?>$("#candidate_sign").prop('required',false);<?php } */ ?>      
        
        $("#address1").rules("add", { required: true, maxlength:75  });
        $("#address2").rules("add", { maxlength:75 });
        $("#address3").rules("add", { maxlength:75 });
        $("#address4").rules("add", { maxlength:75 });
        $("#state").rules("add", { required: true });
        $("#city").rules("add", { required: true });
        $("#district").rules("add", { required: true, allow_only_alphabets_and_numbers_and_space:true, maxlength:30 });
        $("#pincode").rules("add", { required: true, allow_only_numbers:true, minlength:6, maxlength: 6, remote: { url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/validation_check_valid_pincode/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#state").val(); } } } });
        
        $("#bank_emp_id").rules("add", { maxlength:20 });
        $("#associated_with_any_bank_yes").rules("add", { required: true });
        $("#associated_with_any_bank_no").rules("add", { required: true });        
        
        if($('input[name="associated_with_any_bank"]:checked').val() == '1') //YES
        {
          //console.log("3 "+$('input[name="associated_with_any_bank"]:checked').val())
          $("#bank_associated").rules("add", { required: true });
        }
        
        $("#bank_associated_other").rules("add", 
        { 
          required: function() 
          { 
            if($('#bank_associated').val() == 'Other') { return true; } 
            else { return false; }
          }, 
          allow_only_alphabets_and_space:true, maxlength:90 
        }),        
        $("#corporate_bc_associated").rules("add", { allow_only_alphabets_and_space:true, maxlength:90 });
        <?php foreach($id_proof_type_arr as $key => $val) { ?> $("#id_proof_type<?php echo $key; ?>").rules("add", { required: true }); <?php } ?>        
        $("#id_proof_number").rules("add", { validate_id_proof_number: true, remote: { url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/validation_check_id_proof_number_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; }, "enc_batch_id":"<?php echo $enc_batch_id; ?>" } } });
        
        var id_proof_file_required_flag = true;      
        <?php if($mode == 'Add') { ?> if($("#id_proof_file_cropper").val() != "") { id_proof_file_required_flag = false; } <?php }
        else if($mode == 'Update') 
        { ?> 
          var form_id_proof_file = '<?php echo $form_data[0]['id_proof_file'] ?>';
          if($("#id_proof_file_cropper").val() != "" || form_id_proof_file != "") { id_proof_file_required_flag = false; }
        <?php } ?> 
				
        $("#id_proof_file").rules("add", 
        { 
					required: id_proof_file_required_flag,
          check_valid_file:true, 
          valid_file_format:'.jpg,.jpeg,.png', 
          filesize_min:'75000',
          filesize_max:'100000' 
        });
        <?php foreach($qualification_arr as $key => $val) { ?>$("#qualification_certificate_type<?php echo $key; ?>").rules("add", { required:true, validate_qualification_certificate_type: true });<?php } ?>
        
        var qualification_certificate_file_required_flag = true;
        <?php if($mode == 'Add') { ?> if($("#qualification_certificate_file_cropper").val() != "") { qualification_certificate_file_required_flag = false; } <?php }
        else if($mode == 'Update') 
        { ?> 
          var form_qualification_certificate_file = '<?php echo $form_data[0]['qualification_certificate_file'] ?>';
          if($("#qualification_certificate_file_cropper").val() != "" || form_qualification_certificate_file != "") { qualification_certificate_file_required_flag = false; }
        <?php } ?> 


        $("#qualification_certificate_file").rules("add", 
        { 
					required: qualification_certificate_file_required_flag,
          check_valid_file:true, 
          valid_file_format:'.jpg,.jpeg,.png', 
          filesize_min:'75000',
          filesize_max:'100000' 
        });
        
        var candidate_photo_required_flag = true;
        <?php if($mode == 'Add') { ?> if($("#candidate_photo_cropper").val() != "") { candidate_photo_required_flag = false; } <?php }
        else if($mode == 'Update') 
        { ?> 
          var form_candidate_photo = '<?php echo $form_data[0]['candidate_photo'] ?>';
          if($("#candidate_photo_cropper").val() != "" || form_candidate_photo != "") { candidate_photo_required_flag = false; }
        <?php } ?>

        $("#candidate_photo").rules("add", 
        { 
					required: candidate_photo_required_flag,
          check_valid_file:true, 
          valid_file_format:'.jpg,.jpeg,.png', 
          filesize_min:'14000',
          filesize_max:'20000' 
        });
        
        var candidate_sign_required_flag = true;
        <?php if($mode == 'Add') { ?> if($("#candidate_sign_cropper").val() != "") { candidate_sign_required_flag = false; } <?php }
        else if($mode == 'Update') 
        { ?> 
          var form_candidate_sign = '<?php echo $form_data[0]['candidate_sign'] ?>';
          if($("#candidate_sign_cropper").val() != "" || form_candidate_sign != "") { candidate_sign_required_flag = false; }
        <?php } ?>

        $("#candidate_sign").rules("add", 
        { 
					required: candidate_sign_required_flag,
          check_valid_file:true, 
          valid_file_format:'.jpg,.jpeg,.png',
          filesize_min:'14000', 
          filesize_max:'20000' 
        });
        
        $("#aadhar_no").rules("add", { allow_only_numbers:true, maxlength:12, minlength:12, CheckAadharNumberWithIdProof: true, remote: { url: "<?php echo site_url('iibfbcbf/admin/batch_candidates/validation_check_aadhar_no_exist/0/1'); ?>", type: "post", data: { "enc_candidate_id": function() { return "<?php echo $enc_candidate_id; ?>"; }, "enc_batch_id":"<?php echo $enc_batch_id; ?>" } } });
        
        /* if($("#add_candidate_form").valid() == false)// Validate the form
        {
          $("#page_loader").hide();
        } */
        $("#page_loader").hide();
      }
    	
    	/********** START : On 'Affiliated with the Bank as a BC' selection, required the field 'Bank associated with' ***********/
			function fun_show_hide_bank_associated(flag)
			{
				var selected_associated_with_any_bank = $('input[name="associated_with_any_bank"]:checked').val();   
        //console.log("5 "+$('input[name="associated_with_any_bank"]:checked').val());
        if(selected_associated_with_any_bank == '1')//'Yes'
				{
					$(".show_hide_star").html('*');          
          $("#bank_associated").prop('required',true);
          
          if(flag != 1) 
          { 
            $("#bank_associated").rules("add", { required: true }); 
            $("#bank_associated").valid();
          }
				}
				else//'No'
				{
          $(".show_hide_star").html('');          
          $("#bank_associated").prop('required',false);
          
          if(flag != 1) 
          { 
            $("#bank_associated").rules("remove", "required"); 
            $("#bank_associated").valid();
          }
				}
			}/********** END : On 'Affiliated with the Bank as a BC' selection, required the field 'Bank associated with' ***********/
			fun_show_hide_bank_associated(1);

      /********** START : On 'Bank associated with' selection, show/hide the field 'Other Bank associated with' ***********/
      function show_hide_other_section(flag)
      {
        var selected_bank_associated = $('#bank_associated').val(); 
        
        if(selected_bank_associated == 'Other')
        {
          $(".bank_associated_other_outer_cls").removeClass('hide');
          $("#bank_associated_other").prop('required',true);

          if(flag != 1) 
          { 
            $("#bank_associated_other").rules("add", { required: true }); 
            $("#bank_associated_other").valid();
          }
        }
        else
        {
          $(".bank_associated_other_outer_cls").addClass('hide');
          $("#bank_associated_other").prop('required',false);
          
          if(flag != 1) 
          { 
            $("#bank_associated_other").rules("remove", "required"); 
            $("#bank_associated_other").valid();
          }
        }
      }/********** END : On 'Bank associated with' selection, show/hide the field 'Other Bank associated with' ***********/
      show_hide_other_section(1);


      /*Start: Corporate BC Changes*/
      function check_are_you_corporate_bc(val) {
         if(val == 'Yes'){
          $("#corporate_bc_option_div").show();
          
          $("input[name='corporate_bc_option']").prop('required', true);

         }else{
          $("#corporate_bc_option_div").hide();
          $("#corporate_bc_associated_div").hide();
          $("input[name='corporate_bc_option']").prop('checked', false);
          <?php if($mode == 'Add') { ?>
          $("#corporate_bc_associated").val('');
          <?php } ?>
          $("#corporate_bc_validation_message_div").hide();
            
          $("input[name='corporate_bc_option']").prop('required', false); 
         } 
      }

      function check_corporate_bc_option(val) {
         if(val == 'CSC'){
          $("#corporate_bc_option_div").show();
          $("#corporate_bc_associated_div").hide();
          <?php if($mode == 'Add') { ?>
          $("#corporate_bc_associated").val('');
          <?php } ?>
          $("#corporate_bc_validation_message_div").show();
          $("input[name='corporate_bc_associated']").prop('required', false);
         }else if(val == 'Other'){
          $("#corporate_bc_associated_div").show();
          $("#corporate_bc_validation_message_div").hide();
          $("input[name='corporate_bc_associated']").prop('required', true);
         }else{
          $("#corporate_bc_associated_div").hide();
          $("#corporate_bc_validation_message_div").hide();
          $("input[name='corporate_bc_associated']").prop('required', false);
         }
      } 
      //$("input[name='are_you_corporate_bc']").prop('checked', false);
      /*End: Corporate BC Changes*/

    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>