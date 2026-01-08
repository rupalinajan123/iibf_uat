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
					<div class="col-lg-10">
						<h2>Training Batch Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/training_batches'); ?>">Training Batch</a></li>
							<li class="breadcrumb-item active"> <strong>Training Batch Details</strong></li>
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
										<a href="<?php echo site_url('iibfbcbf/admin/training_batches'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <div class="table-responsive">
										<table class="table table-bordered custom_inner_tbl" style="width:100%">
											<tbody>
                        <?php 
                          $sub_data['batch_data'] = $form_data;
                          $sub_data['training_schedule_file_path'] = $training_schedule_file_path;
                          $this->load->view('iibfbcbf/common/inc_training_batch_details_common',$sub_data);
                        ?>

                        <tr>
                          <?php if(date('Y-m-d') <= $form_data[0]['batch_end_date'] && in_array($form_data[0]['batch_status'], array(3,4))) { ?>
                            <td><b style="vertical-align:top">Assign Inspector</b></td>
                            <td>
                              <form method="post" id="assign_inspector_form" enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" id="form_action" name="form_action" value="assign_inspector_action">
                                <input type="hidden" id="previous_inspector_id" name="previous_inspector_id" value="<?php echo $form_data[0]['inspector_id']; ?>">
                                <select class="form-control" name="inspector_id" id="inspector_id" >
                                  <option value="">Select Inspector</option>
                                  <?php if(count($inspector_data) > 0)
                                  {
                                    foreach($inspector_data as $res)
                                    { ?>
                                      <option value="<?php echo $res['inspector_id']; ?>" <?php if($form_data[0]['inspector_id'] != "" && $form_data[0]['inspector_id'] == $res['inspector_id']) { echo 'selected'; } else if(set_value('inspector_id') == $res['inspector_id']) { echo 'selected'; } ?>><?php echo $res['inspector_name']; ?></option>            
                                    <?php }
                                  } ?>  
                                </select>
                                <?php if(form_error('inspector_id')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('inspector_id'); ?></label> <?php } ?>
                                <div id="submit_btn_assign_inspector_outer">
                                  <input class="btn btn-primary mt-2" type="submit" value="Assign Inspector">
                                </div>
                              </form>
                            </td>
                          <?php } 
                          else
                          { ?>
                            <td colspan="2"><b style="vertical-align:top">Assigned Inspector</b> : <?php if($form_data[0]['inspector_name'] != "") { echo $form_data[0]['inspector_name']; }
                              else { echo '-'; } ?></td>
                          <?php } ?>
                        </tr>
                                                
                        <?php 
                        $inspection_report_mode = "Add";
                        $download_inspection_report_btn = '';
                        if($form_data[0]['inspection_report_by_admin'] != "")
                        { 
                          $download_inspection_report_btn = '<a href="'.site_url('iibfbcbf/download_file_common/index/'.$enc_batch_id.'/inspection_report_by_admin').'" class="example-image-link btn btn-success mt-2">Download Inspection Report Uploaded by Admin</a>';
                        }

                        if(in_array($form_data[0]['batch_status'], array(3,7)) && $form_data[0]['inspector_id'] > 0) //if batch status is approved or cancelled and inspector id not blank
                        { ?>
                          <tr>
                            <td><b style="vertical-align:top">Inspection Report</b></td>
                            <td>
                              <form method="post" id="inspection_report_form" enctype="multipart/form-data" autocomplete="off">
                                <?php if($form_data[0]['inspection_report_by_admin'] != "") { $inspection_report_mode = "Update"; } ?>
                                <input type="hidden" id="form_action" name="form_action" value="inspection_report_action">
                                <input type="hidden" id="inspection_report_mode" name="inspection_report_mode" value="<?php echo $inspection_report_mode; ?>">
                                
                                <input type="file" name="inspection_report_by_admin" id="inspection_report_by_admin" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg" data-accept=".pdf,.doc,.docx,.jpg,.png,.jpeg" onchange="validate_input('inspection_report_by_admin');" required />
                            
                                <note class="form_note" id="inspection_report_by_admin_err">Note: Please Upload only .pdf, .doc, .docx, .jpg, .png, .jpeg Files with size upto 5 MB</note>
                                
                                <?php if(form_error('inspection_report_by_admin')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('inspection_report_by_admin'); ?></label> <?php } ?>
                                <?php if($inspection_report_by_admin_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $inspection_report_by_admin_error; ?></label> <?php } ?>
                                  
                                <div>
                                  <input class="btn btn-primary mt-2" type="submit" value="<?php echo $inspection_report_mode; ?> Inspector Report">
                                  <?php echo $download_inspection_report_btn; ?>
                                </div>
                              </form>
                            </td>
                          </tr>
                        <?php }
                        else if($form_data[0]['inspection_report_by_admin'] != "")
                        { ?>
                          <tr>
                            <td><b style="vertical-align:top">Inspection Report By Admin</b></td>
                            <td><?php echo $download_inspection_report_btn; ?></td>
                          </tr>
                        <?php } ?>

                        <?php 
                        /*0=>In Review, 1=>Final Review, 2=>Batch Error, 3=>Go Ahead, 4=>Hold, 5=>Rejected, 6=>Re-Submitted, 7=>Cancelled*/

                        if($form_data[0]['batch_end_date'] >= date('Y-m-d') && ($form_data[0]['batch_status'] == '1' || $form_data[0]['batch_status'] == '2' || $form_data[0]['batch_status'] == '3' || $form_data[0]['batch_status'] == '4' || $form_data[0]['batch_status'] == '6'))
                        { ?>
                          <tr>
                            <td><b style="vertical-align:top">Action</b></td>
                            <td>
                              <?php
                              if($form_data[0]['batch_status'] == '1' || $form_data[0]['batch_status'] == '2' || $form_data[0]['batch_status'] == '6'){ 
                              ?>
                              <a href="javascript:void(0);" onclick="iibf_batch_action_fun('3');" class="btn btn-success mt-0">Go Ahead</a>
                              <a href="javascript:void(0);" onclick="iibf_batch_action_fun('2');" class="btn btn-danger batch_error mt-0">Batch Error</a>
                              <a href="javascript:void(0);" onclick="iibf_batch_action_fun('5');" class="btn btn-danger batch_reject mt-0">Reject</a>
                            <?php }else if($form_data[0]['batch_status'] == '3' || $form_data[0]['batch_status'] == '4'){
                              if($form_data[0]['batch_status'] == '3'){ 
                              ?>
                              <a href="javascript:void(0);" onclick="iibf_batch_action_fun('4');" class="btn btn-primary mt-0">Hold Batch</a>
                              <?php
                              }else if($form_data[0]['batch_status'] == '4'){ ?>
                              <a href="javascript:void(0);" onclick="iibf_batch_action_fun('0','UnHold');" class="btn btn-primary mt-0">UnHold Batch</a>  
                              <?php } ?>
                              <a href="javascript:void(0);" onclick="iibf_batch_action_fun('7');" class="btn btn-danger mt-0">Cancel Batch</a>
                            <?php } ?>
                            </td>
                          </tr>
                          
                          <tr id="batch_action_form" style="display: none;">
                            <td><b id="batch_action_title" style="vertical-align:top"></b></td>
                            <td>
                                <form method="post" id="batch_status_reason_form" enctype="multipart/form-data" autocomplete="off">
                                  <input type="hidden" id="admin_batch_status" name="admin_batch_status">
                                  <input type="hidden" id="admin_batch_status_new" name="admin_batch_status_new">
                                  <input type="hidden" id="form_action" name="form_action" value="batch_status_action">
                                  <textarea name="batch_status_reason" id="batch_status_reason" class="form-control custom_input" maxlength="500"><?php echo set_value('batch_status_reason'); ?></textarea>
                                  <note class="form_note" id="batch_status_reason_err">Note: Please enter only 500 characters</note>
                                  
                                  <div id="submit_btn_training_batch_status">
                                    <input class="btn btn-primary mt-2" name="batch_status_action" type="submit" value="Submit">
                                  </div>
                                </form> 
                            </td>
                          </tr>
                        <?php } ?>
                        
                        <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'admin') { ?>
                          <tr>
                            <td><b style="vertical-align:top">Batch Communication</b></td>
                            <td>
                              <form method="post" action="<?php echo site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id); ?>" id="batch_communication_form" enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" id="form_action" name="form_action" value="batch_communication_action">
                                <textarea name="batch_communication" id="batch_communication" placeholder="Batch Communication" class="form-control custom_input" maxlength="500"><?php echo set_value('batch_communication'); ?></textarea>
                                <note class="form_note" id="batch_communication_err">Note: Please enter only 500 characters</note>
                                
                                <div id="submit_btn_outer">
                                  <input class="btn btn-primary mt-2" name="batch_communication_action" id="batch_communication_action" type="submit" value="Submit Batch Communication">
                                </div>
                              </form>
                            </td>
                          </tr>
                        <?php } ?>
                        
                        <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'admin' && date('Y-m-d') <= $form_data[0]['batch_end_date'] && $form_data[0]['batch_status'] == '3') { ?>
                          <tr>
                            <td><b style="vertical-align:top">Extend Date for Add/Update candidates</b></td>
                            <td>
                              <form method="post" action="<?php echo site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id); ?>" id="extend_date_form" enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" id="form_action" name="form_action" value="extend_date_action">
                               
                                <div id="batch_extend_type_err">
                                  <label class="css_checkbox_radio radio_only"> Add/Update Candidate
                                    <input type="radio" value="1" name="batch_extend_type" id="batch_extend_type" required <?php if($form_data[0]['batch_extend_type'] != '2') { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>&nbsp;&nbsp; 
                                  <label class="css_checkbox_radio radio_only"> Only Update Candidate
                                    <input type="radio" value="2" name="batch_extend_type" id="batch_extend_type" required <?php if($form_data[0]['batch_extend_type'] == '2') { echo "checked"; } ?>>
                                    <span class="radiobtn"></span>
                                  </label>
                                  <?php if(form_error('batch_extend_type')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_extend_type'); ?></label> <?php } ?>
                                </div>

                                <div class="mt-3"></div>
                                <input type="text" name="batch_extend_date" id="batch_extend_date" placeholder="Extend Date for Add/Update candidates *" class="form-control custom_input" readonly value="<?php echo $form_data[0]['batch_extend_date']; ?>" onchange="validate_input('batch_extend_date');">
                                <note class="form_note" id="batch_extend_date_err">Note: Please select the date between <?php echo $form_data[0]['batch_start_date']; ?> and <?php echo $form_data[0]['batch_end_date']; ?></note>
                                <?php if(form_error('batch_extend_date')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('batch_extend_date'); ?></label> <?php } ?>
                                
                                <div id="submit_btn_outer">
                                  <button class="btn btn-primary mt-2" type="submit">Update Extend Date</button>
                                  <?php if($form_data[0]['batch_extend_date'] != "") { ?>
                                    <a href="javascript:void(0)" class="btn btn-danger mt-2" onclick="fun_clear_extended_date()">Clear Extended Date</a>
                                  <?php } ?>
                                </div>
                              </form>
                            </td>
                          </tr>
                        <?php }
                        else if($form_data[0]['batch_extend_date'] != "")
                        { ?>
                          <tr>
                            <td><b style="vertical-align:top">Extend Date for Add/Update candidates</b></td>
                            <td>
                              <?php 
                                echo $form_data[0]['batch_extend_date']; 
                                if($form_data[0]['batch_extend_type'] == '1') { echo " (Add/Update Candidate)"; }
                                else if($form_data[0]['batch_extend_type'] == '2') { echo " (Only Update Candidate)"; }
                              ?>
                            </td>
                          </tr>
                        <?php } ?>
                      
                      </tbody>
                    </table>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="text-center" id="submit_btn_outer">
                      <a href="<?php echo site_url('iibfbcbf/admin/training_batches'); ?>" class="btn btn-danger">Back</a>	
                    </div>
                  </div>                  
                </div>
              </div>

              <div id="common_log_outer"></div>              
              
            </div>					
          </div>
        </div>
				<?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>		
      </div>
    </div>
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>	
		
    <?php  
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_batch_id, 'module_slug'=>'batch_action', 'log_title'=>'Training Batch Log'));
    ?>

    <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'admin') { ?>
      <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>	
      <script type="text/javascript">
        function validate_input(input_id) { $("#"+input_id).valid(); }
        $(document ).ready( function() 
        {
          $("#batch_communication_form").validate( 
          {
            onkeyup: function(element) { $(element).valid(); },          
            rules:
            {
              batch_communication:{ required:true, maxlength:500 }          
            },
            messages:
            {
              batch_communication: { required: "Please enter the Batch Communication"},
            }, 
            errorPlacement: function(error, element) // For replace error 
            {
              if (element.attr("name") == "batch_communication") { error.insertAfter("#batch_communication_err"); }   
              else { error.insertAfter(element); }
            },          
            submitHandler: function(form) 
            {
              swal({ title: "Please confirm", text: "Please confirm to submit the batch communication", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
              { 
                $("#page_loader").show();
                //$("#submit_btn_outer").html('<input class="btn btn-primary mt-2" name="batch_communication_action" id="batch_communication_action" type="submit" value="Submit Batch Communication"><i class="fa fa-spinner" aria-hidden="true"></i>');
                form.submit();
              });            
            }
          });          

          // START BATCH ACTION
          $("#batch_status_reason_form").validate( 
          {
            onkeyup: function(element) { $(element).valid(); },          
            rules:
            {
              batch_status_reason:{ required:true, maxlength:500 }          
            },
            messages:
            {
              //batch_status_reason: { required: "Please enter the Batch"},
              batch_status_reason: {
                  required: function(element) {
                      var err = "This field is required"; // default message
                      var admin_batch_status_var = $("#admin_batch_status").val();
                      var admin_batch_status_new_var = $("#admin_batch_status_new").val();
                      if (admin_batch_status_var == '3' && admin_batch_status_new_var == '') {
                          err = "Describe Approval Reason here";
                      }else if (admin_batch_status_var == '2') {
                          err = "Describe Error here";
                      }else if (admin_batch_status_var == '5') {
                          err = "Describe rejection reason here";
                      }else if (admin_batch_status_var == '4') {
                          err = "Describe Hold batch reason here";
                      }else if (admin_batch_status_var == '7') {
                          err = "Describe cancel batch reason here";
                      }else if (admin_batch_status_new_var == 'UnHold') {
                          err = "Describe UnHold batch reason here";
                      } 

                      return err;  // <- display the custom message
                  }
              }
            }, 
            errorPlacement: function(error, element) // For replace error 
            {
              if (element.attr("name") == "batch_status_reason") { error.insertAfter("#batch_status_reason_err"); }   
              else { error.insertAfter(element); }
            },          
            submitHandler: function(form) 
            {
              var admin_batch_status_var = $("#admin_batch_status").val();
              var admin_batch_status_new_var = $("#admin_batch_status_new").val();
              var confirm_text = 'Please confirm to submit the form';
              if (admin_batch_status_var == '3' && admin_batch_status_new_var == '') {
                  confirm_text = "Please confirm to the batch Go Ahead";
              }else if (admin_batch_status_var == '2') {
                  confirm_text = "Please confirm to the batch Error";
              }else if (admin_batch_status_var == '5') {
                  confirm_text = "Please confirm to the batch Reject";
              }else if (admin_batch_status_var == '4') {
                  confirm_text = "Please confirm to the batch Hold";
              }else if (admin_batch_status_var == '7') {
                  confirm_text = "Please confirm to the batch Cancel";
              }else if (admin_batch_status_new_var == 'UnHold') {
                  confirm_text = "Please confirm to the batch UnHold";
              }

              swal({ title: "Please confirm", text: confirm_text, type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
              { 
                $("#page_loader").show();
                $("#submit_btn_training_batch_status").html('<input class="btn btn-primary mt-2" name="batch_status_action" type="submit" value="Submit"><i class="fa fa-spinner" aria-hidden="true"></i>'); 
                form.submit();
              });            
            }
          }); // END BATCH ACTION


          $("#assign_inspector_form").validate( 
          {
            onkeyup: function(element) { $(element).valid(); },          
            rules:
            {
              inspector_id:{ required:true, /* notEqual: "previous_inspector_id" */ }          
            },
            messages:
            {
              inspector_id: { required: "Please select the inspector to assign for batch", /* notEqual : "The selected inspector is already assigned to this batch. Please select different inspector" */ },
            }, 
            errorPlacement: function(error, element) // For replace error 
            {
              if (element.attr("name") == "inspector_id") { error.insertAfter("#inspector_id"); }   
              else { error.insertAfter(element); }
            },          
            submitHandler: function(form) 
            {
              var previous_inspector_id = $("#previous_inspector_id").val();
              var current_inspector_id = $("#inspector_id").val();

              if(previous_inspector_id == current_inspector_id)
              {
                sweet_alert_error("The selected inspector is already assigned to this batch. Please select different inspector");
              }
              else
              {
                swal({ title: "Please confirm", text: "Please confirm to assign the batch inspector", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: false }, 
                function (selectedOption) 
                {
                  if (selectedOption===true)
                  {
                    swal.close();
                    $("#page_loader").show();
                    $("#submit_btn_assign_inspector_outer").html('<input class="btn btn-primary mt-2" name="assign_inspector_action" id="assign_inspector_action" type="button" value="Assign Inspector">');
                    form.submit();
                  }
                  else 
                  {
                    var previous_inspector_id = $("#previous_inspector_id").val();
                    if(previous_inspector_id == '0') { previous_inspector_id = ''; }
                    $("#inspector_id").val(previous_inspector_id);
                  }
                });            
              }
            }
          });

          $("#inspection_report_form").validate( 
          {
            onkeyup: function(element) { $(element).valid(); },          
            rules:
            {
              inspection_report_by_admin:{ required: true, check_valid_file:true, valid_file_format:'.pdf,.doc,.docx,.jpg,.png,.jpeg', filesize_max:'5000000' }, //use size in bytes //filesize_max: 1MB : 1000000           
            },
            messages:
            {
              inspection_report_by_admin: { required: "Please select the inspection report", valid_file_format:"Please upload only .pdf, .doc, .docx, .jpg, .png, .jpeg files", filesize_max:"Please upload file less than 5MB" },
            }, 
            errorPlacement: function(error, element) // For replace error 
            {
              if (element.attr("name") == "inspection_report_by_admin") { error.insertAfter("#inspection_report_by_admin_err"); }   
              else { error.insertAfter(element); }
            },          
            submitHandler: function(form) 
            {
              swal({ title: "Please confirm", text: "Please confirm to <?php echo $inspection_report_mode; ?> inspection report to the batch", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
              { 
                $("#page_loader").show();                
                form.submit();
              });            
            }
          });

          $.validator.addMethod("check_batch_extend_date", function(value, element)
          {
            if($.trim(value).length == 0) { return true; }
            else
            {
              var current_val = $.trim(value);
              
              var extend_date = current_val;
              var batch_chk_date_start = "<?php echo $form_data[0]['batch_start_date']; ?>";
              var batch_chk_date_end = "<?php echo $form_data[0]['batch_end_date']; ?>";
              
              if(extend_date < batch_chk_date_start || extend_date > batch_chk_date_end)
              {
                $.validator.messages.check_batch_extend_date = "Please Select the Date between "+batch_chk_date_start+" and "+batch_chk_date_end;
                return false;
              }
              else { return true; }
            }
          });

          $("#extend_date_form").validate( 
          {
            onkeyup: function(element) { $(element).valid(); },          
            rules:
            {
              batch_extend_type:{ required:true },
              batch_extend_date:{ required:true, check_batch_extend_date:true },
            },
            messages:
            {
              batch_extend_type: { required: "Please select the type"},
              batch_extend_date: { required: "Please select the date"},
            }, 
            errorPlacement: function(error, element) // For replace error 
            {
              if (element.attr("name") == "batch_extend_type") { error.insertAfter("#batch_extend_type_err"); }   
              else if (element.attr("name") == "batch_extend_date") { error.insertAfter("#batch_extend_date_err"); }   
              else { error.insertAfter(element); }
            },          
            submitHandler: function(form) 
            {
              swal({ title: "Please confirm", text: "Please confirm to extend the candidate Add/Update date", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
              { 
                $("#page_loader").show();
                form.submit();
              });            
            }
          });
        });
        //END : JQUERY VALIDATION SCRIPT 

        function fun_clear_extended_date()
        {
          swal({ title: "Please confirm", text: "Please confirm to clear the extended date", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
          { 
            $("#page_loader").show();
            window.location.href = "<?php echo site_url('iibfbcbf/admin/training_batches/clear_extended_date/'.$enc_batch_id); ?>";
          });  
        }
      </script>
    <?php } ?>

    <script>
      function iibf_batch_action_fun(batch_status,new_status=''){
        $("#batch_status_reason-error").html('');
        var admin_batch_status = $("#admin_batch_status").val();
        if(batch_status == '3'){
          $("#batch_action_title").html("Describe Approval Reason here");
          $("#batch_status_reason").prop("placeholder","Describe Approval Reason here");
          if($('#batch_action_form').is(':visible') && admin_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
          $("#admin_batch_status").val(batch_status);
        }else if(batch_status == '2'){
          $("#batch_action_title").html("Describe Error here");
          $("#batch_status_reason").prop("placeholder","Describe Error here");
          if($('#batch_action_form').is(':visible') && admin_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
          $("#admin_batch_status").val(batch_status);
        }else if(batch_status == '5'){
          $("#batch_action_title").html("Describe rejection reason here");
          $("#batch_status_reason").prop("placeholder","Describe rejection reason here");
          if($('#batch_action_form').is(':visible') && admin_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
          $("#admin_batch_status").val(batch_status);
        }else if(batch_status == '4'){
          $("#batch_action_title").html("Describe Hold batch reason here");
          $("#batch_status_reason").prop("placeholder","Describe Hold batch reason here");
          if($('#batch_action_form').is(':visible') && admin_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
          $("#admin_batch_status").val(batch_status);
        }else if(batch_status == '7'){
          $("#batch_action_title").html("Describe cancel batch reason here");
          $("#batch_status_reason").prop("placeholder","Describe cancel batch reason here");
          if($('#batch_action_form').is(':visible') && admin_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
          $("#admin_batch_status").val(batch_status);
        }else if(batch_status == '0' && new_status == 'UnHold'){
          $("#batch_action_title").html("Describe UnHold batch reason here");
          $("#batch_status_reason").prop("placeholder","Describe UnHold batch reason here");
          if($('#batch_action_form').is(':visible') && admin_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
          $("#admin_batch_status").val('3');
          $("#admin_batch_status_new").val('UnHold');
        }
      }

      $(document).ready(function(){ 
        /*$(".batch_error").click(function(){
          $("#batch_action_title").html("Describe Error here");
          $("#batch_status_reason").prop("placeholder","Describe Error here");
          if($('#batch_action_form').is(':visible')){
            $(".batch_action_form").toggle("slow");
          }else{
            $("#batch_action_form").show("slow");
          }
        });
        $(".batch_reject").click(function(){
          $("#batch_action_title").html("Describe rejection reason here");
          $("#batch_status_reason").prop("placeholder","Describe rejection reason here");
          if($('#batch_action_form').is(':visible')){
            $(".batch_action_form").toggle("slow");
          }else{
            $("#batch_action_form").show("slow");
          }
        });*/
      });

      $('#batch_extend_date').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", clearBtn: true, startDate:"<?php echo $form_data[0]['batch_start_date']; ?>" , endDate:"<?php echo $form_data[0]['batch_end_date']; ?>" });      
    </script>

    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>