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
      <?php $this->load->view('iibfbcbf/admin/inc_sidebar_admin'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2><?php echo $mode; ?> Inspector</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/inspector_master'); ?>">Inspector Master</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $mode; ?> Inspector</strong></li>
						</ol>
					</div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-title">
									<div class="ibox-tools">
										<a href="<?php echo site_url('iibfbcbf/admin/inspector_master'); ?>" class="btn btn-primary custom_right_add_new_btn">Back</a>                 
									</div>
								</div>

								<div class="ibox-content">
                  <form method="post" action="<?php echo site_url('iibfbcbf/admin/inspector_master/add_inspector/'.$enc_inspector_id); ?>" id="add_inspector_form" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
										
                    <?php /* echo validation_errors(); */ ?>

										<div class="row">
                      <div class="col-xl-12 col-lg-12"><?php /* Inspector Name */ ?>
												<div class="form-group">
													<label for="inspector_name" class="form_label">Inspector Name <sup class="text-danger">*</sup></label>
													<input type="text" name="inspector_name" id="inspector_name" value="<?php if($mode == "Add") { echo set_value('inspector_name'); } else { echo $form_data[0]['inspector_name']; } ?>" placeholder="Inspector Name *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                          <note class="form_note" id="inspector_name_err">Note: Please enter only 90 characters</note>

													<?php if(form_error('inspector_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('inspector_name'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Mobile Number */ ?>
                        <div class="form-group">
                          <label for="inspector_mobile" class="form_label">Mobile Number <sup class="text-danger">*</sup></label>
                          <input type="text" name="inspector_mobile" id="inspector_mobile" value="<?php if($mode == "Add") { echo set_value('inspector_mobile'); } else { echo $form_data[0]['inspector_mobile']; } ?>" placeholder="Mobile Number *" class="form-control custom_input allow_only_numbers basic_form" required maxlength="10" minlength="10" />
                          
                          <?php if(form_error('inspector_mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('inspector_mobile'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Email id */ ?>
                        <div class="form-group">
                          <label for="inspector_email" class="form_label">Email id <sup class="text-danger">*</sup></label>
                          <input type="text" name="inspector_email" id="inspector_email" value="<?php if($mode == "Add") { echo set_value('inspector_email'); } else { echo $form_data[0]['inspector_email']; } ?>" placeholder="Email id *" class="form-control custom_input basic_form" required maxlength="80" />
                          <note class="form_note" id="inspector_email_err">Note: Please enter only 80 characters</note>
                          
                          <?php if(form_error('inspector_email')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('inspector_email'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Inspector Designation */ ?>
												<div class="form-group">
													<label for="inspector_designation" class="form_label">Inspector Designation <sup class="text-danger">*</sup></label>
													<input type="text" name="inspector_designation" id="inspector_designation" value="<?php if($mode == "Add") { echo set_value('inspector_designation'); } else { echo $form_data[0]['inspector_designation']; } ?>" placeholder="Inspector Designation *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                          <note class="form_note" id="inspector_designation_err">Note: Please enter only 90 characters</note>

													<?php if(form_error('inspector_designation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('inspector_designation'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Inspector Username */ ?>
												<div class="form-group">
													<label for="inspector_username" class="form_label">Inspector Username <sup class="text-danger">*</sup></label>
													<input type="text" name="inspector_username" id="inspector_username" value="<?php if($mode == "Add") { echo set_value('inspector_username'); } else { echo $form_data[0]['inspector_username']; } ?>" placeholder="Inspector Username *" class="form-control custom_input" minlength="3" maxlength="30" required/>
                          <note class="form_note" id="inspector_username_err">Note: Please enter minimum 3 character and maximum 30 characters</note>

													<?php if(form_error('inspector_username')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('inspector_username'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <?php if($mode == 'Add') { ?>
                        <div class="col-xl-6 col-lg-6"><?php /* Inspector Password */ ?>
                          <div class="form-group login_password_common">
                            <label for="inspector_password" class="form_label">Inspector Password <sup class="text-danger">*</sup></label>
                            <input type="password" class="form-control custom_input" name="inspector_password" id="inspector_password" value="<?php echo set_value('inspector_password'); ?>" placeholder="Inspector Password *" required minlength="8" maxlength="20">
                            <?php if(form_error('inspector_password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('inspector_password'); ?></label> <?php } ?>
                            
                            <span class="show-password" data-id="inspector_password" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                            <span class="hide-password" data-id="inspector_password" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                          </div>
											  </div>

                        <div class="col-xl-6 col-lg-6"><?php /* Confirm Password */ ?>
                          <div class="form-group login_password_common">
                            <label for="confirm_password" class="form_label">Confirm Password <sup class="text-danger">*</sup></label>
                            <input type="password" class="form-control custom_input" name="confirm_password" id="confirm_password" value="<?php echo set_value('confirm_password'); ?>" placeholder="Confirm Password *" required minlength="8" maxlength="20">
                            <?php if(form_error('confirm_password')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('confirm_password'); ?></label> <?php } ?>
                            
                            <span class="show-password" data-id="confirm_password" style="top:28px;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                            <span class="hide-password" data-id="confirm_password" style="display:none;top:28px;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
                          </div>
                        </div>
                      <?php } ?>

                      <div class="col-xl-6 col-lg-6"><?php /* Type */ ?>
                        <div class="form-group">
                          <label for="batch_online_offline_flag" class="text_bold">Type <sup class="text-danger">*</sup></label>
                          <div id="batch_online_offline_flag_err">
                            <label class="css_checkbox_radio radio_only"> Offline
                              <input type="radio" value="1" name="batch_online_offline_flag" required <?php if($mode == "Add") { if(set_value('batch_online_offline_flag') == "" || set_value('batch_online_offline_flag') == '1') { echo "checked"; } } else { if($form_data[0]['batch_online_offline_flag']=='1') { echo "checked"; } } ?> onchange="hide_state_city_outer()">
                              <span class="radiobtn"></span>
                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                            <label class="css_checkbox_radio radio_only"> Online
                              <input type="radio" value="2" name="batch_online_offline_flag" required <?php if($mode == "Add") { if(set_value('batch_online_offline_flag') == '2') { echo "checked"; } } else { if($form_data[0]['batch_online_offline_flag']=='2') { echo "checked"; } } ?> onchange="hide_state_city_outer()">
                              <span class="radiobtn"></span>
                            </label>
                          </div>
                          <div class="clearfix"></div><label class="error" id="batch_online_offline_flag_error"><?php if(form_error('batch_online_offline_flag')!=""){ echo form_error('batch_online_offline_flag'); } ?></label>
                        </div>					
                      </div>

                      <?php $chk_state = array(); if($mode == "Add") { if(set_value('state') != "") { $chk_state = set_value('state'); } } else { $chk_state = explode(",",$form_data[0]['state_codes']); } ?>
                      <div class="col-xl-12 col-lg-12 hide_state_city_outer"><?php /* Select State */ ?>
                        <div class="form-group">
                          <label for="state" class="form_label">Select State <sup class="text-danger">*</sup></label>
                          <select name="state[]" id="state" class="form-control chosen-select" required onchange="get_city_ajax(); validate_input('state'); " multiple data-placeholder="<?php if(count($state_master_data) > 0)
                              { echo "Select State *"; } else { echo "No State Available"; } ?>">
                            <?php if(count($state_master_data) > 0)
                            { 
                              foreach($state_master_data as $state_res)
                              { ?>
                                <option value="<?php echo $state_res['state_code']; ?>" <?php if(in_array($state_res['state_code'],$chk_state)){ echo 'selected'; } ?>><?php echo $state_res['state_name']; ?></option>
                              <?php }
                            } ?>
                          </select>
                          
                          <span id="state_err"></span>
                          <?php if(form_error('state[]')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('state[]'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      <?php 
                      $selected_state_val = '';
                      $city_data = array();
                      if($mode == "Add")
                      {
                        if(set_value('state') != "") { $selected_state_val = set_value('state'); }
                      }
                      else { $selected_state_val = explode(",",$form_data[0]['state_codes']); }
                      
                      if($selected_state_val != "")
                      {
                        $this->db->where_in('state_code', $selected_state_val);
                        $city_data = $this->master_model->getRecords('city_master', array('city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));                        
                      }

                      $chk_city = array();
                      if($mode == "Add") { if(set_value('city') !="") { $chk_city = set_value('city'); } } else { $chk_city = $form_city_id_arr; } ?>

                      <div class="col-xl-12 col-lg-12 hide_state_city_outer"><?php /* Select City */ ?>
                        <div class="form-group">
                          <label for="city" class="form_label">City <sup class="text-danger">*</sup></label>
                          <div id="city_outer">
                            <select class="form-control chosen-select" name="city[]" id="city" required onchange="validate_input('city'); " multiple data-placeholder="<?php if(count($city_data) > 0)
                              { echo "Select City *"; } else { echo "No City Available"; } ?>">
                              <?php                                   
                              if(count($city_data) > 0)
                              {
                                foreach($city_data as $city)
                                { ?>
                                <option value="<?php echo $city['id']; ?>" <?php if(in_array($city['id'],$chk_city)) { echo "selected"; } ?>><?php echo $city['city_name']; ?></option>
                                <?php }
                              } ?>
                            </select>
                          </div>
                          
                          <span id="city_err"></span>
                          
                          <?php if(form_error('city[]')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('city[]'); ?></label> <?php } ?>                            
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Status */ ?>
                        <div class="form-group">
                          <label for="status" class="text_bold">Status <sup class="text-danger">*</sup></label>
                          <div id="status_err">
                            <label class="css_checkbox_radio radio_only"> Active
                              <input type="radio" value="1" name="status" required <?php if($mode == "Add") { if(set_value('status') == "" || set_value('status') == '1') { echo "checked"; } } else { if($form_data[0]['is_active']=='1') { echo "checked"; } } ?>>
                              <span class="radiobtn"></span>
                            </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            
                            <label class="css_checkbox_radio radio_only"> Inactive
                              <input type="radio" value="0" name="status" required <?php if($mode == "Add") { if(set_value('status') == '0') { echo "checked"; } } else { if($form_data[0]['is_active']=='0') { echo "checked"; } } ?>>
                              <span class="radiobtn"></span>
                            </label>
                          </div>
                          <div class="clearfix"></div><label class="error" id="status_error"><?php if(form_error('status')!=""){ echo form_error('status'); } ?></label>
                        </div>					
                      </div>
                    </div>

                    <div class="hr-line-dashed"></div>										
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">
												<button class="btn btn-primary" type="submit">Submit</button>
												<a class="btn btn-danger" href="<?php echo site_url('iibfbcbf/admin/inspector_master'); ?>">Back</a>	
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
    
    <?php if($mode == 'Update') { 
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_inspector_id, 'module_slug'=>'inspector_action', 'log_title'=>'Inspector Log'));
    } ?>

    <script type="text/javascript">
      function get_city_ajax()
      {
        $("#page_loader").show();
        parameters="state_id="+$("#state").chosen().val()+"&selected_cities="+$("#city").chosen().val();
        
        $.ajax({
          type: "POST",
          url: "<?php echo site_url('iibfbcbf/admin/inspector_master/get_city_ajax'); ?>",
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

      function hide_state_city_outer()
      {
        var selectedType = $("input[name='batch_online_offline_flag']:checked").val();
        if(selectedType == '1')//offline
        {
          $(".hide_state_city_outer").show();
        }
        else
        {
          $("#state").val('').trigger('chosen:updated');
          get_city_ajax();
          $(".hide_state_city_outer").hide();
        }
      }
      hide_state_city_outer();

      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
			{
        $("#add_inspector_form").validate( 
				{
          //onfocusout: true,
          onkeyup: function(element) 
          {
            $(element).valid();
          },          
          rules:
					{
            inspector_name:{ required: true, allow_only_alphabets_and_space:true, maxlength:90 }, 
            inspector_mobile:{ required: true, first_zero_not_allowed:true, allow_only_numbers:true, maxlength:10, minlength:10, remote: { url: "<?php echo site_url('iibfbcbf/admin/inspector_master/validation_check_mobile_exist/0/1'); ?>", type: "post", data: { "enc_inspector_id": function() { return "<?php echo $enc_inspector_id; ?>"; } } } },
            inspector_email:{ required: true, maxlength:80, valid_email:true, remote: { url: "<?php echo site_url('iibfbcbf/admin/inspector_master/validation_check_email_exist/0/1'); ?>", type: "post", data: { "enc_inspector_id": function() { return "<?php echo $enc_inspector_id; ?>"; } } } },
            inspector_designation:{ required: true, allow_only_alphabets_and_space:true, maxlength:90 }, 
            inspector_username:{ required: true, minlength:3, maxlength:30, ValidUsername:true, remote: { url: "<?php echo site_url('iibfbcbf/admin/inspector_master/validation_check_username_exist/0/1'); ?>", type: "post", data: { "enc_inspector_id": function() { return "<?php echo $enc_inspector_id; ?>"; } } } },
            <?php if($mode == 'Add') { ?>
              inspector_password: { required: true, minlength: 8, maxlength:20, pwcheck: true },
              confirm_password: { required: true, equalTo: "#inspector_password" },
            <?php } ?>
            batch_online_offline_flag:{ required: true }, 
            'state[]':{ required: function(element){ return $("input[name='batch_online_offline_flag']:checked").val()=="1"; } }, 
            'city[]':{ required: function(element){ return $("input[name='batch_online_offline_flag']:checked").val()=="1"; } }, 
            status:{ required: true },
					},
					messages:
					{
            inspector_name: { required: "Please enter the inspector name" },
            inspector_mobile: { required: "Please enter the mobile number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number", remote: "The mobile number is already exist" },
            inspector_email: { required: "Please enter the email id", valid_email: "Please enter the valid email id", remote: "The email id is already exist"  },
            inspector_designation: { required: "Please enter the inspector designation" },
            inspector_username: { required: "Please enter the inspector username", ValidUsername:"Please enter only letters, numbers or _@#$&*!.?", remote: "The username is already exist" },
            inspector_password: { required: "Please enter the inspector password", minlength: "Please enter minimum 8 character", pwcheck:"Password must contain at least one upper-case character, one lower-case character, one digit and one special character" },					
						confirm_password: { required: "Please enter the Confirm Password", equalTo:"Please enter confirm password same as inspector password" },
						batch_online_offline_flag: { required: "Please select the type" },
						'state[]': { required: "Please select the state" },
						'city[]': { required: "Please select the city" },
						status: { required: "Please select the status" },
					}, 
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "inspector_name") { error.insertAfter("#inspector_name_err"); }
            else if (element.attr("name") == "inspector_email") { error.insertAfter("#inspector_email_err"); }
            else if (element.attr("name") == "inspector_designation") { error.insertAfter("#inspector_designation_err"); }
            else if (element.attr("name") == "inspector_username") { error.insertAfter("#inspector_username_err"); }
            else if (element.attr("name") == "batch_online_offline_flag") { error.insertAfter("#batch_online_offline_flag_err"); }
            else if (element.attr("name") == "state[]") { error.insertAfter("#state_err"); }
            else if (element.attr("name") == "city[]") { error.insertAfter("#city_err"); }
            else if (element.attr("name") == "status") { error.insertAfter("#status_err"); }
            else { error.insertAfter(element); }
          },          
					submitHandler: function(form) 
					{
            swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();
              $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait" value="submit">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button> <a class="btn btn-danger" href="<?php echo site_url("iibfbcbf/admin/inspector_master"); ?>">Back</a>');
              form.submit();
            });            
					}
				});

        $(".show-password, .hide-password").on('click', function() 
        {
          var passwordId = $(this).data("id");
          if ($(this).hasClass('show-password')) 
          {
            $("#" + passwordId).attr("type", "text");
            $(this).parent().find(".show-password").hide();
            $(this).parent().find(".hide-password").show();
          }
          else 
          {
            $("#" + passwordId).attr("type", "password");
            $(this).parent().find(".hide-password").hide();
            $(this).parent().find(".show-password").show();
          }
        });
			});
		</script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>