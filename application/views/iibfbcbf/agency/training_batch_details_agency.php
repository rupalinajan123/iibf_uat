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
						<h2>Training Batch Details</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/training_batches_agency'); ?>">Training Batches</a></li>
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
										<a href="<?php echo site_url('iibfbcbf/agency/training_batches_agency'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
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

                        <tr style="display:nonex;">
                          <td <?php if($form_data[0]['inspection_report_by_admin'] == "") { echo 'colspan="2"'; } ?>><b style="vertical-align:top">Assigned Inspector</b> : <?php if($form_data[0]['inspector_name']!= "") { echo $form_data[0]['inspector_name']; } else { echo "-"; } ?></td>
                          <?php if($form_data[0]['inspection_report_by_admin'] != "")
                          { ?>
                            <td>
                              <b style="vertical-align:top">Inspection Report By Admin</b> : 
                              <a href="<?php echo site_url('iibfbcbf/download_file_common/index/'.url_encode($form_data[0]['batch_id']).'/inspection_report_by_admin'); ?>" class="example-image-link btn btn-success">Download Inspection Report</a>
                            </td>
                          <?php } ?>
                        </tr>
                        <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency')
                        {
                          //0=>In Review, 1=>Final Review, 2=>Batch Error, 3=>Go Ahead, 4=>Hold, 5=>Rejected, 6=>Re-Submitted, 7=>Cancelled

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
                                    <input type="hidden" id="agency_batch_status" name="agency_batch_status">
                                    <input type="hidden" id="agency_batch_status_new" name="agency_batch_status_new">
                                    <input type="hidden" id="form_action" name="form_action" value="batch_status_action">
                                    <textarea name="batch_status_reason" id="batch_status_reason" class="form-control custom_input" maxlength="500"><?php echo set_value('batch_status_reason'); ?></textarea>
                                    <note class="form_note" id="batch_status_reason_err">Note: Please enter only 500 characters</note>
                                    
                                    <div id="submit_btn_training_batch_status">
                                      <input class="btn btn-primary mt-2" name="batch_status_action" type="submit" value="Submit">
                                    </div>
                                  </form> 
                              </td>
                            </tr>
                          <?php }
                        } ?>
                        
                        <tr>
                          <td><b style="vertical-align:top">Batch Communication</b></td>
                          <td>
                            <form method="post" action="<?php echo site_url('iibfbcbf/agency/training_batches_agency/training_batch_details_agency/' . $enc_batch_id); ?>" id="batch_communication_form" enctype="multipart/form-data" autocomplete="off">
                              <input type="hidden" id="form_action" name="form_action" value="batch_communication_action">
                              <textarea name="batch_communication" id="batch_communication" placeholder="Batch Communication" class="form-control custom_input" maxlength="500"><?php echo set_value('batch_communication'); ?></textarea>
                              <note class="form_note" id="batch_communication_err">Note: Please enter only 500 characters</note>

                              <div id="submit_btn_outer">
                                <button class="btn btn-primary mt-2" type="submit">Submit Batch Communication</button>
                              </div>
                            </form>
                          </td>
                        </tr>
                        <!-- Start: Adden Extend Date for Add/Update candidates functionality to Agency Login by Anil S on 12 March 2025 as per client requirement -->
                        <?php 
                        if(in_array($form_data[0]['agency_code'], array(1001,1002,1003,1010,1019))){
                        if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency' && date('Y-m-d') <= $form_data[0]['batch_end_date'] && $form_data[0]['batch_status'] == '3') { ?>
                          <tr>
                            <td><b style="vertical-align:top">Extend Date for Add/Update candidates</b></td>
                            <td>
                              <form method="post" action="<?php echo site_url('iibfbcbf/agency/training_batches_agency/training_batch_details_agency/'.$enc_batch_id); ?>" id="extend_date_form" enctype="multipart/form-data" autocomplete="off">
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
                        <?php } 
                      }
                        ?>
                        <!-- End: Adden Extend Date for Add/Update candidates functionality to Agency Login by Anil S on 12 March 2025 as per client requirement -->

                      </tbody>
                    </table>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="text-center" id="submit_btn_outer">
                      <a href="<?php echo site_url('iibfbcbf/agency/training_batches_agency'); ?>" class="btn btn-danger">Back</a>	
                    </div>
                  </div>                  
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
		
    <?php  
      $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_batch_id, 'module_slug'=>'batch_action', 'log_title'=>'Training Batch Log'))
    ?>
    
    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>	
    <script type="text/javascript">
      <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency')
      { ?>
        function iibf_batch_action_fun(batch_status,new_status='')
        {
          $("#batch_status_reason-error").html('');
          var agency_batch_status = $("#agency_batch_status").val();
          if(batch_status == '3')
          {
            $("#batch_action_title").html("Describe Approval Reason here");
            $("#batch_status_reason").prop("placeholder","Describe Approval Reason here");
            if($('#batch_action_form').is(':visible') && agency_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
            $("#agency_batch_status").val(batch_status);
          }
          else if(batch_status == '2')
          {
            $("#batch_action_title").html("Describe Error here");
            $("#batch_status_reason").prop("placeholder","Describe Error here");
            if($('#batch_action_form').is(':visible') && agency_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
            $("#agency_batch_status").val(batch_status);
          }
          else if(batch_status == '5')
          {
            $("#batch_action_title").html("Describe rejection reason here");
            $("#batch_status_reason").prop("placeholder","Describe rejection reason here");
            if($('#batch_action_form').is(':visible') && agency_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
            $("#agency_batch_status").val(batch_status);
          }
          else if(batch_status == '4')
          {
            $("#batch_action_title").html("Describe Hold batch reason here");
            $("#batch_status_reason").prop("placeholder","Describe Hold batch reason here");
            if($('#batch_action_form').is(':visible') && agency_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
            $("#agency_batch_status").val(batch_status);
          }
          else if(batch_status == '7')
          {
            $("#batch_action_title").html("Describe cancel batch reason here");
            $("#batch_status_reason").prop("placeholder","Describe cancel batch reason here");
            if($('#batch_action_form').is(':visible') && agency_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
            $("#agency_batch_status").val(batch_status);
          }
          else if(batch_status == '0' && new_status == 'UnHold')
          {
            $("#batch_action_title").html("Describe UnHold batch reason here");
            $("#batch_status_reason").prop("placeholder","Describe UnHold batch reason here");
            if($('#batch_action_form').is(':visible') && agency_batch_status == batch_status){ $("#batch_action_form").toggle("slow"); }else{ $("#batch_action_form").show("slow"); }
            $("#agency_batch_status").val('3');
            $("#agency_batch_status_new").val('UnHold');
          }
        }
      <?php } ?>
      
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
              $("#submit_btn_outer").html('<button class="btn btn-primary mt-2" type="button" style="cursor:wait">Submit Batch Communication <i class="fa fa-spinner" aria-hidden="true"></i></button>');
              form.submit();
            });            
          }
        });

        <?php if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency')
        { ?>
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
              batch_status_reason: 
              {
                required: function(element) 
                {
                  var err = "This field is required"; // default message
                  var agency_batch_status_var = $("#agency_batch_status").val();
                  var agency_batch_status_new_var = $("#agency_batch_status_new").val();
                  if (agency_batch_status_var == '3' && agency_batch_status_new_var == '') 
                  {
                    err = "Describe Approval Reason here";
                  }
                  else if (agency_batch_status_var == '2') 
                  {
                    err = "Describe Error here";
                  }
                  else if (agency_batch_status_var == '5') 
                  {
                    err = "Describe rejection reason here";
                  }
                  else if (agency_batch_status_var == '4') 
                  {
                    err = "Describe Hold batch reason here";
                  }
                  else if (agency_batch_status_var == '7') 
                  {
                    err = "Describe cancel batch reason here";
                  }
                  else if (agency_batch_status_new_var == 'UnHold') 
                  {
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
              var agency_batch_status_var = $("#agency_batch_status").val();
              var agency_batch_status_new_var = $("#agency_batch_status_new").val();
              var confirm_text = 'Please confirm to submit the form';
              if (agency_batch_status_var == '3' && agency_batch_status_new_var == '') 
              {
                confirm_text = "Please confirm to submit the batch Go Ahead";
              }
              else if (agency_batch_status_var == '2') 
              {
                confirm_text = "Please confirm to submit the batch Error";
              }
              else if (agency_batch_status_var == '5') 
              {
                confirm_text = "Please confirm to submit the batch Reject";
              }
              else if (agency_batch_status_var == '4') 
              {
                confirm_text = "Please confirm to submit the batch Hold";
              }
              else if (agency_batch_status_var == '7') 
              {
                confirm_text = "Please confirm to submit the batch Cancel";
              }
              else if (agency_batch_status_new_var == 'UnHold') 
              {
                confirm_text = "Please confirm to submit the batch UnHold";
              }

              swal({ title: "Please confirm", text: confirm_text, type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
              { 
                $("#page_loader").show();
                $("#submit_btn_training_batch_status").html('<input class="btn btn-primary mt-2" name="batch_status_action" type="submit" value="Submit"><i class="fa fa-spinner" aria-hidden="true"></i>'); 
                form.submit();
              });            
            }
          }); // END BATCH ACTION
        <?php } ?>

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

          $('#batch_extend_date').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", clearBtn: true, startDate:"<?php echo $form_data[0]['batch_start_date']; ?>" , endDate:"<?php echo $form_data[0]['batch_end_date']; ?>" });
          
      });
      //END : JQUERY VALIDATION SCRIPT 

      function fun_clear_extended_date()
      {
        swal({ title: "Please confirm", text: "Please confirm to clear the extended date", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
        { 
          $("#page_loader").show();
          window.location.href = "<?php echo site_url('iibfbcbf/agency/training_batches_agency/clear_extended_date/'.$enc_batch_id); ?>";
        });  
      }
    </script>
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>