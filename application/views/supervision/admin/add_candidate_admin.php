<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('supervision/inc_header'); ?>    
  </head>
  
	<body class="fixed-sidebar">
    <?php $this->load->view('supervision/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('supervision/admin/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('supervision/admin/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $mode; ?> Observer </h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/admin/dashboard_admin'); ?>">Dashboard</a></li>
              
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/admin/candidate'); ?>">Observer Master</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $mode; ?> Observer</strong></li>
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
										<a href="<?php echo site_url('supervision/admin/candidate'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <form method="post" action="<?php echo site_url('supervision/admin/candidate/add_candidate/'.$enc_id); ?>" id="add_form" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
                    
                    <h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Basic Details</h4>
                    
                    <div class="row">                      
                      <?php if($mode == 'Update') { ?>
                        <div class="col-xl-12 col-lg-12"><?php /* Agency Code */ ?>
                          <div class="form-group">
                            <label class="form_label">Observer Code <sup class="text-danger">*</sup></label>
                            <input type="text" value="<?php echo $form_data[0]['candidate_code']; ?>" class="form-control custom_input" readonly />                              
                          </div>					
                        </div>
                      <?php } ?>
                      
                      <div class="col-xl-6 col-lg-6"><?php /*  Name */ ?>
                        <div class="form-group">
                          <label for="candidate_name" class="form_label"> Name <sup class="text-danger">*</sup></label>
                          <input type="text" name="candidate_name" id="candidate_name" value="<?php if($mode == "Add") { echo set_value('candidate_name'); } else { echo $form_data[0]['candidate_name']; } ?>" placeholder=" Name *" class="form-control custom_input allow_only_alphabets_and_space" maxlength="90" required/>
                          <note class="form_note" id="candidate_name_err">Note: Please enter only 90 characters</note>
                          
                          <?php if(form_error('candidate_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('candidate_name'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      
                      <div class="col-xl-6 col-lg-6"><?php /* Email */ ?>
                        <div class="form-group">
                          <label for="email" class="form_label">Email <sup class="text-danger">*</sup></label>
                          <input type="email" name="email"  id="email" value="<?php if($mode == "Add") { echo set_value('email'); } else { echo $form_data[0]['email']; } ?>" placeholder="Email *" class="form-control custom_input" maxlength="4" readonly onchange="validate_input('email')"/>
                          
                          <?php if(form_error('email')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('email'); ?></label> <?php } ?>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* Contact  Mobile Number */ ?>
                        <div class="form-group">
                          <label for="mobile" class="form_label">Mobile Number <sup class="text-danger">*</sup></label>
                          <input type="text" name="mobile"  id="mobile" value="<?php if($mode == "Add") { echo set_value('mobile'); } else { echo $form_data[0]['mobile']; } ?>" placeholder="Contact Person Mobile Number *" class="form-control custom_input allow_only_numbers" required maxlength="10" minlength="10" />
                          
                          <?php if(form_error('mobile')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('mobile'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    
                      <div class="col-xl-6 col-lg-6"><?php /* bank */ ?>
                        <div class="form-group">
                          <label for="bank" class="form_label">Bank <sup class="text-danger">*</sup></label>
                          <input type="text" name="bank" required id="bank" placeholder="Bank" class="form-control custom_input" maxlength="75" value="<?php if($mode == "Add") { echo set_value('bank'); } else { echo $form_data[0]['bank']; } ?>" />
                          
                          <note class="form_note" id="bank_err">Note: Please enter only 75 characters</note>
                          
                          <?php if(form_error('bank')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('bank'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    
                      <div class="col-xl-6 col-lg-6"><?php /* branch */ ?>
                        <div class="form-group">
                          <label for="branch" class="form_label">Branch <sup class="text-danger">*</sup></label>
                          <input type="text" name="branch" required id="branch" placeholder="Branch" class="form-control custom_input" maxlength="75" value="<?php if($mode == "Add") { echo set_value('branch'); } else { echo $form_data[0]['branch']; } ?>" />
                          
                          <note class="form_note" id="branch_err">Note: Please enter only 75 characters</note>
                          
                          <?php if(form_error('branch')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('branch'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    
                      <div class="col-xl-6 col-lg-6"><?php /* designation */ ?>
                        <div class="form-group">
                          <label for="designation" class="form_label">Designation<sup class="text-danger">*</sup></label>
                          <input type="text" name="designation" required id="designation" placeholder="Designation" class="form-control custom_input" maxlength="75" value="<?php if($mode == "Add") { echo set_value('designation'); } else { echo $form_data[0]['designation']; } ?>" />
                          
                          <note class="form_note" id="designation_err">Note: Please enter only 75 characters</note>
                          
                          <?php if(form_error('designation')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('designation'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    
                      

                      <?php if($mode == "Add") { $chk_center = set_value('center'); } else { $chk_center = $form_data[0]['center_code']; } ?>
                      <div class="col-xl-6 col-lg-6"><?php /* Select center */ ?>
                        <div class="form-group">
                          <label for="center" class="form_label">Select Center  <sup class="text-danger">*</sup></label>
                          <select name="center" id="center" class="form-control chosen-select select_center" required onchange="get_city_ajax(this.value); validate_input('center'); ">
                            <?php if(count($center_master_data) > 0)
                              { ?>
                              <option value="">Select Center *</option>
                              <?php foreach($center_master_data as $center_res)
                                { ?>
                                <option pdc_zone_name="<?php echo $center_res['pdc_zone_name']; ?>" pdc_zone="<?php echo $center_res['pdc_zone']; ?>" value="<?php echo $center_res['center_code']; ?>" <?php if($chk_center == $center_res['center_code']) { echo 'selected'; } ?>><?php echo $center_res['center_name']; ?></option>
                                <?php }
                              }
                              else 
                              { ?>
                              <option value="">No List Available</option>
                            <?php } ?>
                          </select>
                          
                          <span id="center_err"></span>
                          <?php if(form_error('center')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('center'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    
                      <?php if($mode == "Add") { $chk_pdc_zone = set_value('pdc_zone'); } else { $chk_pdc_zone = $form_data[0]['pdc_zone']; } ?>
                      <div class="col-xl-6 col-lg-6"><?php /* Select pdc */ ?>
                        <div class="form-group">
                          <label for="pdc_zone" class="form_label">Select PDC  <sup class="text-danger">*</sup></label>
                          <div class="pdc_zone_outer">
                          <select name="pdc_zone" id="pdc_zone" class="form-control chosen-select" required onchange="get_city_ajax(this.value); validate_input('pdc_zone'); ">
                          <option value="">No List Available</option>
                          </select>
                              </div>
                          
                          <span id="pdc_zone_err"></span>
                          <?php if(form_error('pdc_zone')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('pdc_zone'); ?></label> <?php } ?>
                        </div>					
                      </div>
                      <div class="col-xl-6 col-lg-6"><?php /* Contact Person Mobile Number */ ?>
                      <div class="form-group">
                        <label for="bank_id_card" class="form_label">Bank ID Card <sup class="text-danger">*</sup></label>
                        <i>Uploaded File: <a target="_blank" href="<?php echo base_url(); ?>/uploads/supervision/<?php echo $form_data[0]['bank_id_card'] ?>">View file</a></i>
                        <input type="file" name="bank_id_card" id="bank_id_card" value="<?php echo set_value('bank_id_card'); ?>" placeholder="Bank ID Card *" class="form-control  " onchange="validateFile(event, 'error_bank_id_card_size', 'image_upload_bank_id_card_preview', '300kb')" required maxlength="255" minlength="5" />
                        
                        <?php if(form_error('bank_id_card')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('bank_id_card'); ?></label> <?php } ?>
                      </div>					
                    </div>
                    </div>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">
                      <?php if($this->session->userdata('SUPERVISION_ADMIN_TYPE')!=1) { ?>				
												<button class="btn btn-primary" type="submit">Submit</button>
                        <?php } ?>
												<a class="btn btn-danger" href="<?php echo site_url('supervision/admin/candidate'); ?>">Back</a>	
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            
              <div id="common_log_outer"></div>              
            </div>
          </div>					
        </div>
      </div>
      <?php $this->load->view('supervision/admin/inc_footerbar_admin'); ?>		
    </div>
  </div>
  
  <?php $this->load->view('supervision/inc_footer'); ?>		
  <?php $this->load->view('supervision/common/inc_common_validation_all'); ?>
  <?php $this->load->view('supervision/common/inc_common_show_hide_password'); ?>
  
  <?php  if($mode == 'Update') {
    $this->load->view('supervision/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_id, 'module_slug'=>'candidate_action,candidate_password_action', 'log_title'=>'Agency Log'));
  } ?>    
  
  <script type="text/javascript">
    pdc_zone_func();
    $('select.select_center').change(function() {
      pdc_zone_func();
    });
    function pdc_zone_func() {
      var pdc_zone = $('select.select_center option:selected').attr('pdc_zone');
      var pdc_zone_name = $('select.select_center option:selected').attr('pdc_zone_name');
      $('.pdc_zone_outer').html('<select name="pdc_zone" id="pdc_zone" class="form-control chosen-select select_pdc_zone" readonly  ><option selected value="'+pdc_zone+'">'+pdc_zone_name+'</option></select>');
      
      $('.select_pdc_zone option[value="'+pdc_zone+'"]').prop('selected', true);

    }

    //var email = $('#email').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy", viewMode: "years", minViewMode: "years", clearBtn: true, endDate:"<?php echo date("Y"); ?>" });
    
        
    
    //START : JQUERY VALIDATION SCRIPT 
    function validate_input(input_id) { $("#"+input_id).valid(); }
    $(document ).ready( function() 
    {
      var form = $("#add_form").validate( 
      {
        onkeyup: function(element) { $(element).valid(); },          
        rules:
        {
          candidate_name:{ required: true, allow_only_alphabets_and_space:true, maxlength:90 },
          bank:{ maxlength:75 },
          branch:{ maxlength:75 },         
          pdc_zone:{ required: true },  
          center:{ required: true },           
          designation:{ required: true, allow_only_alphabets_and_space:true, maxlength:90 },
          mobile:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10, remote: { url: "<?php echo site_url('supervision/admin/candidate/validation_check_mobile_exist/0/1'); ?>", type: "post", data: { "enc_id": function() { return "<?php echo $enc_id; ?>"; } } } },            
          email:{ required: true, maxlength:80, valid_email:true, remote: { url: "<?php echo site_url('supervision/admin/candidate/validation_check_email_exist/0/1'); ?>", type: "post", data: { "enc_id": function() { return "<?php echo $enc_id; ?>"; } } } },
          
          <?php if($mode == 'Add') { ?>
            candidate_password: { required: true, minlength: 8, maxlength:20, pwcheck: true },
            confirm_password: { required: true, equalTo: "#candidate_password" },
          <?php }
           ?>
        },
        messages:
        {
          candidate_name: { required: "Please enter the candidate name" },
          
          bank: { },
          branch: { },          
          pdc_zone: { required: "Please select the pdc_zone" },
          center: { required: "Please select the city" },
          designation: { required: "Please enter the designation" },
          mobile: { required: "Please enter the mobile number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number", remote: "The mobile number is already exist" },
          email: { required: "Please enter the email id", valid_email: "Please enter the valid email id", remote: "The email id is already exist"  },
          
        }, 
        errorPlacement: function(error, element) // For replace error 
        {
          if (element.attr("name") == "candidate_name") { error.insertAfter("#candidate_name_err"); }
          else if (element.attr("name") == "bank") { error.insertAfter("#bank_err"); }
          else if (element.attr("name") == "branch") { error.insertAfter("#branch_err"); }
          else if (element.attr("name") == "designation") { error.insertAfter("#designation_err"); }
          else if (element.attr("name") == "pdc_zone") { error.insertAfter("#pdc_zone_err"); }
          else if (element.attr("name") == "center") { error.insertAfter("#center_err"); }
          else if (element.attr("name") == "designation") { error.insertAfter("#designation_err"); }
          else if (element.attr("name") == "email") { error.insertAfter("#email_err"); }
          else if (element.attr("name") == "is_active") { error.insertAfter("#is_active_err"); }
          else { error.insertAfter(element); }
        },          
        submitHandler: function(form) 
        {          
          $("#page_loader").hide();
          swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
          { 
            $("#page_loader").show();            
            $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait">Submit</button> <a class="btn btn-danger" href="<?php echo site_url('supervision/admin/candidate'); ?>">Back</a>');
           
            form.submit();
          }); 
        }
      });
    });
    //END : JQUERY VALIDATION SCRIPT
  </script>
  <?php $this->load->view('supervision/common/inc_bottom_script'); ?>
</body>
</html>