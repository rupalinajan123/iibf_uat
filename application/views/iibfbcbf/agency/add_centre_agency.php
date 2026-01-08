<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>    
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('iibfbcbf/agency/inc_sidebar_agency'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/agency/inc_topbar_agency'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $mode; ?> Centre</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/centre_master_agency'); ?>">Centre Master</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $mode; ?> Centre</strong></li>
            </ol>
          </div>
					<div class="col-lg-2"> 
            <div class="back-btn">
              <a href="<?php echo site_url('iibfbcbf/agency/centre_master_agency'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
            </div>
          </div>
        </div>			
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-title white-bg">
									<div class="px-3 frm-head">
                    <h4 class="mb-0">Add Centre Form</h4>
                  </div>
                </div>
								<div class="ibox-content">
                  <form method="post" action="<?php echo site_url('iibfbcbf/agency/centre_master_agency/add_centre_agency/'.$enc_centre_id); ?>" id="add_centre_form" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">

                    <div class="row">
                      <div class="<?php if($mode == 'Add') { echo 'col-xl-12 col-lg-12'; } else { echo 'col-xl-6 col-lg-6'; } ?>"><?php /* Centre Name */ ?>
                        <div class="form-group">
                          <label for="centre_name" class="form_label">Centre Name <sup class="text-danger">*</sup></label>
                          <input type="text" name="centre_name" id="centre_name" value="<?php if($mode == "Add") { echo set_value('centre_name'); } else { echo $form_data[0]['centre_name']; } ?>" placeholder="Centre Name *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                          <note class="form_note" id="centre_name_err">Note: Please enter only 90 characters</note>

                          <?php if(form_error('centre_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_name'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <?php if($mode == 'Update') { ?>
                        <div class="col-xl-6 col-lg-6"><?php /* Centre ID */  ?>
                          <div class="form-group">
                            <label class="form_label">Centre ID <sup class="text-danger">*</sup></label>
                            <input type="text" value="<?php echo $form_data[0]['centre_username']; ?>" placeholder="Centre ID *" class="form-control custom_input" readonly />
                          </div>					
                        </div>
                      <?php }  ?>

                      <div class="col-xl-12 col-lg-12"><?php /* Address line1 */ ?>
												<div class="form-group">
													<label for="centre_address1" class="form_label">Address Line-1 <sup class="text-danger">*</sup></label>
													<input type="text" name="centre_address1" id="centre_address1" value="<?php if($mode == "Add") { echo set_value('centre_address1'); } else { echo $form_data[0]['centre_address1']; } ?>" placeholder="Address Line-1 *" class="form-control custom_input" maxlength="75" required/>
                          <note class="form_note" id="centre_address1_err">Note: Please enter only 75 characters</note>

													<?php if(form_error('centre_address1')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_address1'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-12 col-lg-12"><?php /* Address line2 */ ?>
												<div class="form-group">
													<label for="centre_address2" class="form_label">Address Line-2 <sup class="text-danger"></sup></label>
													<input type="text" name="centre_address2" id="centre_address2" value="<?php if($mode == "Add") { echo set_value('centre_address2'); } else { echo $form_data[0]['centre_address2']; } ?>" placeholder="Address Line-2" class="form-control custom_input" maxlength="75" />
                          <note class="form_note" id="centre_address2_err">Note: Please enter only 75 characters</note>

													<?php if(form_error('centre_address2')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_address2'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-12 col-lg-12"><?php /* Address line3 */ ?>
												<div class="form-group">
													<label for="centre_address3" class="form_label">Address Line-3 <sup class="text-danger"></sup></label>
													<input type="text" name="centre_address3" id="centre_address3" value="<?php if($mode == "Add") { echo set_value('centre_address3'); } else { echo $form_data[0]['centre_address3']; } ?>" placeholder="Address Line-3" class="form-control custom_input" maxlength="75" />
                          <note class="form_note" id="centre_address3_err">Note: Please enter only 75 characters</note>

													<?php if(form_error('centre_address3')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_address3'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-12 col-lg-12"><?php /* Address line4 */ ?>
												<div class="form-group">
													<label for="centre_address4" class="form_label">Address Line-4 <sup class="text-danger"></sup></label>
													<input type="text" name="centre_address4" id="centre_address4" value="<?php if($mode == "Add") { echo set_value('centre_address4'); } else { echo $form_data[0]['centre_address4']; } ?>" placeholder="Address Line-4" class="form-control custom_input" maxlength="75" />
                          <note class="form_note" id="centre_address4_err">Note: Please enter only 75 characters</note>

													<?php if(form_error('centre_address4')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_address4'); ?></label> <?php } ?>
                        </div>					
                      </div>
										
                      <?php if($mode == "Add") { $chk_state = set_value('centre_state'); } else { $chk_state = $form_data[0]['centre_state']; } ?>
                      <div class="col-xl-6 col-lg-6"><?php /* Select State */ ?>
												<div class="form-group">
                          <label for="centre_state" class="form_label">Select State <sup class="text-danger">*</sup></label>
													<select name="centre_state" id="centre_state" class="form-control chosen-select" required onchange="get_city_ajax(this.value); validate_input('centre_state'); check_validity_gst_no();">
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

                          <span id="centre_state_err"></span>
													<?php if(form_error('centre_state')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_state'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Select City */ ?>
                        <div class="form-group">
                            <label for="centre_city" class="form_label">City <sup class="text-danger">*</sup></label>
                            <div id="city_outer">
                              <select class="form-control chosen-select" name="centre_city" id="centre_city" required onchange="validate_input('centre_city'); ">
                                <?php $selected_state_val = '';
                                if($mode == "Add")
                                {
                                  if(set_value('centre_state') != "") { $selected_state_val = set_value('centre_state'); }
                                }
                                else { $selected_state_val = $form_data[0]['centre_state']; }
                                
                                if($selected_state_val != "")
                                {
                                  $city_data = $this->master_model->getRecords('city_master', array('state_code' => $selected_state_val, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
                                  
                                  if(count($city_data) > 0)
                                  { ?>
                                    <option value="">Select City</option>
                                    <?php foreach($city_data as $city)
                                    { ?>
                                      <option value="<?php echo $city['id']; ?>" <?php if($mode == "Add") { if(set_value('centre_city') == $city['id']) { echo "selected"; } } else { if($form_data[0]['centre_city'] == $city['id']) { echo "selected"; } } ?>><?php echo $city['city_name']; ?></option>
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

                            <span id="centre_city_err"></span>

                            <?php if(form_error('centre_city')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_city'); ?></label> <?php } ?>                            
                          </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* District */ ?>
												<div class="form-group">
													<label for="centre_district" class="form_label">District <sup class="text-danger">*</sup></label>
													<input type="text" name="centre_district" id="centre_district" value="<?php if($mode == "Add") { echo set_value('centre_district'); } else { echo $form_data[0]['centre_district']; } ?>" placeholder="District *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="30" required/>
                          <note class="form_note" id="centre_district_err">Note: Please enter only 30 characters</note>

													<?php if(form_error('centre_district')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_district'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Pincode */ ?>
												<div class="form-group">
													<label for="centre_pincode" class="form_label">Pincode <sup class="text-danger">*</sup></label>
													<input type="text" name="centre_pincode" id="centre_pincode" value="<?php if($mode == "Add") { echo set_value('centre_pincode'); } else { echo $form_data[0]['centre_pincode']; } ?>" placeholder="Pincode *" class="form-control custom_input allow_only_numbers" required maxlength="6" minlength="6" />

                          <?php if(form_error('centre_pincode')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_pincode'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Centre Contact Number */ ?>
												<div class="form-group">
													<label for="centre_mobile" class="form_label">Centre Contact Number <sup class="text-danger">*</sup></label>
													<input type="text" name="centre_mobile" id="centre_mobile" value="<?php if($mode == "Add") { echo set_value('centre_mobile'); } else { echo $form_data[0]['centre_mobile']; } ?>" placeholder="Centre Contact Number *" class="form-control custom_input allow_only_numbers" required maxlength="10" minlength="10" />

                          <?php if(form_error('centre_mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_mobile'); ?></label> <?php } ?>
                        </div>					
                      </div>                      

                      <div class="col-xl-6 col-lg-6"><?php /* Name of contact Person */ ?>
												<div class="form-group">
													<label for="centre_contact_person_name" class="form_label">Name of contact Person <sup class="text-danger">*</sup></label>
													<input type="text" name="centre_contact_person_name" id="centre_contact_person_name" value="<?php if($mode == "Add") { echo set_value('centre_contact_person_name'); } else { echo $form_data[0]['centre_contact_person_name']; } ?>" placeholder="Name of contact Person *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                          <note class="form_note" id="centre_contact_person_name_err">Note: Please enter only 90 characters</note>

													<?php if(form_error('centre_contact_person_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_contact_person_name'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Contact Person Mobile Number */ ?>
												<div class="form-group">
													<label for="centre_contact_person_mobile" class="form_label">Contact Person Mobile Number <sup class="text-danger">*</sup></label>
													<input type="text" name="centre_contact_person_mobile" id="centre_contact_person_mobile" value="<?php if($mode == "Add") { echo set_value('centre_contact_person_mobile'); } else { echo $form_data[0]['centre_contact_person_mobile']; } ?>" placeholder="Contact Person Mobile Number *" class="form-control custom_input allow_only_numbers" required maxlength="10" minlength="10" />

                          <?php if(form_error('centre_contact_person_mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_contact_person_mobile'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Contact Person Email id */ ?>
												<div class="form-group">
													<label for="centre_contact_person_email" class="form_label">Contact Person Email id <sup class="text-danger">*</sup></label>
													<input type="text" name="centre_contact_person_email" id="centre_contact_person_email" value="<?php if($mode == "Add") { echo set_value('centre_contact_person_email'); } else { echo $form_data[0]['centre_contact_person_email']; } ?>" placeholder="Contact Person Email id *" class="form-control custom_input" required maxlength="80" />
                          <note class="form_note" id="centre_contact_person_email_err">Note: Please enter only 80 characters</note>

                          <?php if(form_error('centre_contact_person_email')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_contact_person_email'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      <?php /*      
                      <div class="col-xl-6 col-lg-6"><?php // Centre Type ?>
                        <div class="form-group">
                          <label for="centre_type" class="form_label">Centre Type <sup class="text-danger">*</sup></label>
                          <div id="centre_type_err">                              
                            <label class="css_checkbox_radio radio_only"> Regular
                              <input type="radio" value="1" name="centre_type" required <?php if($mode == "Add") { if(set_value('centre_type') == "" || set_value('centre_type') == '1') { echo "checked"; } } else { if($form_data[0]['centre_type'] == '1') { echo "checked"; } } ?>>
                              <span class="radiobtn"></span>
                            </label>
                            <label class="css_checkbox_radio radio_only"> Temporary
                              <input type="radio" value="2" name="centre_type" required <?php if($mode == "Add") { if(set_value('centre_type') == '2') { echo "checked"; } } else { if($form_data[0]['centre_type'] == '2') { echo "checked"; } } ?>>
                              <span class="radiobtn"></span>
                            </label> 
                          </div>
                          <?php if(form_error('centre_type')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_type'); ?></label> <?php } ?>
                        </div>					
                      </div>*/ ?>

                      <div class="col-xl-12 col-lg-12"><?php /* Centre GST No */ ?>
												<div class="form-group">
													<label for="gst_no" class="form_label">Centre GST No <sup class="text-danger">*</sup></label>
													<input type="text" name="gst_no" id="gst_no" value="<?php if($mode == "Add") { echo set_value('gst_no'); } else { echo $form_data[0]['gst_no']; } ?>" placeholder="Centre GST No *" class="form-control custom_input allow_only_alphabets_and_numbers" required minlength="15" maxlength="15" />
                          
                          <note class="form_note" id="gst_no_err">Note: Please enter GST no like 29ABCDE1234F1ZW</note>

                          <?php if(form_error('gst_no')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('gst_no'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      <?php if($mode == 'Add') { ?>
                        <div class="col-xl-6 col-lg-6"><?php /* Centre Password */ ?>
                          <div class="form-group login_password_common">
                            <label for="centre_password" class="form_label">Centre Password <sup class="text-danger">*</sup></label>
                            <input type="password" name="centre_password" id="centre_password" value="<?php if($mode == "Add") { echo set_value('centre_password'); } else { echo $this->Iibf_bcbf_model->password_decryption($form_data[0]['centre_password']); } ?>" placeholder="Centre Password *" class="form-control custom_input" required minlength="8" maxlength="20" />

                            <span class="show-password" onclick="show_hide_password(this,'show', 'centre_password')" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                            <span class="hide-password" onclick="show_hide_password(this,'hide', 'centre_password')" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>

                            <note class="form_note" id="centre_password_err">Note: Please enter minimum 8 and maximum 20 characters</note>

                            <?php if(form_error('centre_password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_password'); ?></label> <?php } ?>
                          </div>					
                        </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Confirm Password */ ?>
                          <div class="form-group login_password_common">
                            <label for="confirm_password" class="form_label">Confirm Password <sup class="text-danger">*</sup></label>
                            <input type="password" class="form-control custom_input" name="confirm_password" id="confirm_password" value="<?php echo set_value('confirm_password'); ?>" placeholder="Confirm Password *" required minlength="8" maxlength="20">
                            <?php if(form_error('confirm_password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('confirm_password'); ?></label> <?php } ?>
                            
                            <span class="show-password" onclick="show_hide_password(this,'show', 'confirm_password')" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                            <span class="hide-password" onclick="show_hide_password(this,'hide', 'confirm_password')" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                          </div>
                        </div>
                      <?php } ?>

                      <div class="col-xl-12 col-lg-12"><?php /* Address On Invoice */ ?>
                        <div class="form-group">
                          <label for="invoice_address" class="form_label">Address On Invoice <sup class="text-danger">*</sup></label>
                          <div id="invoice_address_err">  
                            <?php if($mode == 'Add') 
                            { ?>                            
                              <label class="css_checkbox_radio radio_only"> Option to display <b>'Institute Address and GST No.'</b> on the invoice
                                <input type="radio" value="1" name="invoice_address" required <?php if($mode == "Add") { if(set_value('invoice_address') == "" || set_value('invoice_address') == '1') { echo "checked"; } } else { if($form_data[0]['invoice_address'] == '1') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                              <label class="css_checkbox_radio radio_only"> Option to display <b>'Centre Address and GST No.'</b> on the invoice
                                <input type="radio" value="2" name="invoice_address" required <?php if($mode == "Add") { if(set_value('invoice_address') == '2') { echo "checked"; } } else { if($form_data[0]['invoice_address'] == '2') { echo "checked"; } } ?>>
                                <span class="radiobtn"></span>
                              </label>
                            <?php }
                            else 
                            {  
                              if($form_data[0]['invoice_address'] == '1') { echo "<label>Institute Address and GST No.</label>"; }
                              else if($form_data[0]['invoice_address'] == '2') { echo "<label>Centre Address and GST No.</label>"; } ?>
                              <input type="hidden" name="invoice_address" value="<?php echo $form_data[0]['invoice_address']; ?>">
                            <?php } ?>
                          </div>
                          <?php if(form_error('invoice_address')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('invoice_address'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-12 col-lg-12"><?php /* Centre Remarks */ ?>
												<div class="form-group">
													<label for="centre_remarks" class="form_label">Centre Remarks <sup class="text-danger"></sup></label>
													<textarea name="centre_remarks" id="centre_remarks" placeholder="Centre Remarks" rows="3" class="form-control custom_input" maxlength="300"><?php if($mode == "Add") { echo set_value('centre_remarks'); } else { echo $form_data[0]['centre_remarks']; } ?></textarea>

                          <note class="form_note" id="centre_remarks_err">Note: Please enter only 300 characters</note>

													<?php if(form_error('centre_remarks')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('centre_remarks'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <?php if($mode == 'Update') { ?>
                        <div class="col-xl-6 col-lg-6"><?php /* Status */ ?>
                          <div class="form-group">
                            <label class="form_label">Status <sup class="text-danger">*</sup></label>
                            <div><span class="disp_status_details badge <?php echo show_faculty_status($form_data[0]['status']); ?>" style="min-width:90px;"><?php echo $form_data[0]['DispStatus']; ?></span></div>
                          </div>					
                        </div>
                      <?php } ?>
                      
                      <div class="col-xl-12 col-lg-12"><?php /* Declaration */ ?>
												<div class="form-group">
                          <label class="css_checkbox_radio" id="declaration_err"> Kindly check the above details and tick (<i class="fa fa-check" aria-hidden="true"></i>) the check-box for confirmation.
                            <input type="checkbox" id="declaration" name="declaration" value="1" required>
                            <span class="checkmark" style="top:4px;"></span>
                          </label>

													<?php if(form_error('declaration')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('declaration'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    </div>                
                    
										<div class="hr-line-dashed"></div>										
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">
												<button class="btn btn-submit mr-3" type="submit">Submit</button>
												<a class="btn btn-submit" href="<?php echo site_url('iibfbcbf/agency/centre_master_agency'); ?>">Back</a>	
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              
              <div id="common_log_outer"></div>
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>		
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>		
		<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
		<?php $this->load->view('iibfbcbf/common/inc_common_show_hide_password'); ?>

    <?php if($mode == 'Update') {
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_centre_id, 'module_slug'=>'centre_action,centre_password_action', 'log_title'=>'Centre Log'));
    } ?>
    
    <script type="text/javascript">
      function get_city_ajax(state_id)
			{
				$("#page_loader").show();
				parameters="state_id="+state_id;
				
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('iibfbcbf/agency/centre_master_agency/get_city_ajax'); ?>",
					data: parameters,
					cache: false,
          dataType: 'JSON',
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
			}

      function check_validity_gst_no()
      {
        var gst_no = $("#gst_no").val();
        if(gst_no != "")
        {
          validate_input('gst_no');
        }
      }
      
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
			{
        $("#add_centre_form").submit(function() 
        {
          if($("#centre_state").valid() == false) { $('#centre_state').trigger('chosen:activate'); }
          else if($("#centre_city").valid() == false) { $('#centre_city').trigger('chosen:activate'); }
        });

        $.validator.addMethod("validation_check_pan_exist", function(value, element)
        {
          if($.trim(value).length == 0) { return true; }
          else
          {
            var isSuccess = false;
            var pan_no = $.trim(value);
            $.ajax(
            {
              type: "POST",
              url: "<?php echo site_url('iibfbcbf/agency/centre_master_agency/validation_check_pan_exist/'.$enc_centre_id.'/1'); ?>",
              data: "pan_no="+pan_no,
              async: false,
              cache : false,
              dataType: 'JSON',
              success: function(data)
              {
                if($.trim(data.flag) == 'success')
                {
                  isSuccess = true;
                }
                
                $.validator.messages.validation_check_pan_exist = data.response;
              }
            });
            
            return isSuccess;
          }
        });

				$("#add_centre_form").validate( 
				{
          onkeyup: function(element) { $(element).valid(); },          
          rules:
					{
            centre_name:{ required: true, allow_only_alphabets_and_space:true, maxlength:90, remote: { url: "<?php echo site_url('iibfbcbf/agency/centre_master_agency/validation_check_centre_name_exist/0/1'); ?>", type: "post", data: { "enc_centre_id": function() { return "<?php echo $enc_centre_id; ?>"; }, "centre_city": function() { return $("#centre_city").val(); } } } }, //check validation for duplicate Centre Name for same centre
            centre_address1:{ required: true, validAddress:false, maxlength:75 },  
            centre_address2:{ validAddress:false, maxlength:75 },  
            centre_address3:{ validAddress:false, maxlength:75 },  
            centre_address4:{ validAddress:false, maxlength:75 },  
            centre_state:{ required: true },  
            centre_city:{ required: true },
            centre_district:{ required: true, allow_only_alphabets_and_space:true, maxlength:30 },
            centre_pincode:{ required: true, allow_only_numbers:true, minlength:6, maxlength: 6, remote: { url: "<?php echo site_url('iibfbcbf/agency/centre_master_agency/validation_check_valid_pincode/0/1'); ?>", type: "post", data: { "selected_state_code": function() { return $("#centre_state").val(); } } } },  //check validation for pincode as per selected state
            centre_mobile:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10, remote: { url: "<?php echo site_url('iibfbcbf/agency/centre_master_agency/validation_check_mobile_exist/0/1'); ?>", type: "post", data: { "enc_centre_id": function() { return "<?php echo $enc_centre_id; ?>"; } } } },
            centre_contact_person_name:{ required: true, allow_only_alphabets_and_space:true, maxlength:90 },
            centre_contact_person_mobile:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10 },
            centre_contact_person_email:{ required: true, maxlength:80, valid_email:true },
            /* centre_type:{ required: true }, */
            gst_no:{ required: true, minlength:15, maxlength:15, allow_only_alphabets_and_numbers:true, valid_gst_no:true, remote: { url: "<?php echo site_url('iibfbcbf/agency/centre_master_agency/validation_check_gst_no_exist/0/1'); ?>", type: "post", data: { "enc_centre_id": function() { return "<?php echo $enc_centre_id; ?>"; }, "centre_state": function() { return $("#centre_state").val(); } } } },            
            <?php if($mode == 'Add') { ?>
              centre_password:{ required: true, minlength:8, maxlength:20, pwcheck:true },
              confirm_password: { required: true, equalTo: "#centre_password" },
            <?php } ?>
            invoice_address:{ required: true },            
            centre_remarks:{ maxlength:300 },            
            declaration:{ required:true },            
					},
					messages:
					{
            centre_name: { required: "Please enter the Centre Name", remote: "The centre with same Centre Name is already exist in your agency" },
            centre_address1: { required: "Please enter the address line-1" },
            centre_address2: { },
            centre_address3: { },
            centre_address4: { },
            centre_state: { required: "Please select the state" },
            centre_city: { required: "Please select the city" },
            centre_district: { required: "Please enter the district" },
            centre_pincode: { required: "Please enter the pincode", minlength: "Please enter 6 numbers in pincode", maxlength: "Please enter 6 numbers in pincode", remote: "Please enter valid pincode as per selected city" },
            centre_mobile: { required: "Please enter the centre contact number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number", remote: "The mobile number is already exist" },
            centre_contact_person_name: { required: "Please enter the name of contact person" },
            centre_contact_person_mobile: { required: "Please enter the contact person mobile number", minlength: "Please enter 10 numbers in contact person mobile number", maxlength: "Please enter 10 numbers in contact person mobile number" },
            centre_contact_person_email: { required: "Please enter the contact person email id", valid_email: "Please enter the valid email id"  },
            /* centre_type: { required: "Please select the centre type" }, */
            gst_no: { required: "Please enter the GST no.", minlength: "Please enter 15 character in GST no.", maxlength: "Please enter 15 character in GST no.", valid_gst_no : "Please enter the valid GST no. like 29ABCDE1234F1ZW", remote: "The gst number is already exist" },            
            centre_password: { required: "Please enter the centre password", minlength: "Please enter miniumum 8 character in centre password", maxlength: "Please enter maximun 20 character in centre password", pwcheck:"Centre password must contains one uppercase letter, one lowercase letter, one number and one special character" },
            confirm_password: { required: "Please enter the confirm password", equalTo:"Please enter confirm password same as password" },
            invoice_address: { required: "Please select the address on invoice" },
            centre_remarks: { },
            declaration: { required:"Please confirm the details" },
					}, 
          errorPlacement: function(error, element) // For replace error 
          {            
            if (element.attr("name") == "centre_name") { error.insertAfter("#centre_name_err"); }
            else if (element.attr("name") == "centre_address1") { error.insertAfter("#centre_address1_err"); }
            else if (element.attr("name") == "centre_address2") { error.insertAfter("#centre_address2_err"); }
            else if (element.attr("name") == "centre_address3") { error.insertAfter("#centre_address3_err"); }
            else if (element.attr("name") == "centre_address4") { error.insertAfter("#centre_address4_err"); }
            else if (element.attr("name") == "centre_state") { error.insertAfter("#centre_state_err"); }
            else if (element.attr("name") == "centre_city") { error.insertAfter("#centre_city_err"); }
            else if (element.attr("name") == "centre_district") { error.insertAfter("#centre_district_err"); }
            else if (element.attr("name") == "centre_contact_person_name") { error.insertAfter("#centre_contact_person_name_err"); }
            else if (element.attr("name") == "centre_contact_person_email") { error.insertAfter("#centre_contact_person_email_err"); }
            /* else if (element.attr("name") == "centre_type") { error.insertAfter("#centre_type_err"); }            */ 
            else if (element.attr("name") == "gst_no") { error.insertAfter("#gst_no_err"); }          
            else if (element.attr("name") == "centre_password") { error.insertAfter("#centre_password_err"); }            
            else if (element.attr("name") == "invoice_address") { error.insertAfter("#invoice_address_err"); }            
            else if (element.attr("name") == "centre_remarks") { error.insertAfter("#centre_remarks_err"); }            
            else if (element.attr("name") == "declaration") { error.insertAfter("#declaration_err"); }            
            else { error.insertAfter(element); }
          },          
					submitHandler: function(form) 
					{
            swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();
              $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait" value="submit">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button> <a class="btn btn-danger" href="<?php echo site_url("iibfbcbf/agency/centre_master_agency"); ?>">Back</a>');
              form.submit();
            });            
					}
				});
			});
		</script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>