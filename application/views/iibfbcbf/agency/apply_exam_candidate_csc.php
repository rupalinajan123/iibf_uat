<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('iibfbcbf/inc_header'); ?> 
    <style>.css_checkbox_radio { margin:0; }</style>   
  </head>
	
	<body class="fixed-sidebar">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
      <?php $this->load->view('iibfbcbf/agency/inc_sidebar_agency'); ?>
			<div id="page-wrapper" class="gray-bg">				
        <?php $this->load->view('iibfbcbf/agency/inc_topbar_agency'); ?>
				
        <div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-12">
						<h2><?php echo display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']); /* helpers/iibfbcbf/iibf_bcbf_helper.php */ ?> </h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/agency/dashboard_agency'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']); /* helpers/iibfbcbf/iibf_bcbf_helper.php */ ?> </strong></li>
						</ol>
					</div>
				</div>
        
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
                <div class="ibox-content">
                  <form class="form-horizontal" autocomplete="off" name="apply_exam_form" id="apply_exam_form"  method="post"  enctype="multipart/form-data">
                    <h3 class="text-center mb-4"><b><?php echo 'Apply For '.display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']); /* helpers/iibfbcbf/iibf_bcbf_helper.php */ ?></b></h3>
                    <div class="table-responsive">
                      <table class="table table-bordered custom_inner_tbl" style="width:100%">
                        <tbody>
                          <?php 
                            $sub_data['candidate_data'] = $candidate_data;
                            $sub_data['id_proof_file_path'] = $id_proof_file_path;
                            $sub_data['qualification_certificate_file_path'] = $qualification_certificate_file_path;
                            $sub_data['candidate_photo_path'] = $candidate_photo_path;
                            $sub_data['candidate_sign_path'] = $candidate_sign_path;
                            $this->load->view('iibfbcbf/common/inc_candidate_details_common', $sub_data); 
                          ?>
                          
                        <td class="empty_row" colspan="2"></td></tr>
                        <tr><td class="text-center heading_row" colspan="2" ><b>Exam Details</b></td></tr>
                        <tr>
                          <td><strong>Examination Name</strong></td>
                          <td><?php echo display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']); /* helpers/iibfbcbf/iibf_bcbf_helper.php */ ?></td>
                        </tr>
                                                  
                        <tr>
                          <td><strong>Examination Fees</strong></td>
                          <td>
                            <i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format_upto2($exam_fees); ?><?php //iibfbcbf/iibf_bcbf_helper.php ?>
                            <input type="hidden" name="form_total_fees" id="form_total_fees" value="<?php echo number_format_upto2($exam_fees); //iibfbcbf/iibf_bcbf_helper.php ?>">
                          </td> 
                        </tr>

                        <tr>
                          <td><strong>Exam medium</strong><span class="mandatory-field">*</span></td>
                          <td>
                            <select required class="form-control" name="exam_medium" id="exam_medium">
                              <option value="">Select Exam medium</option>
                              <?php 
                              $chk_medium_code = '';
                              if(isset($applied_exam_data[0]['exam_medium'])) { $chk_medium_code = $applied_exam_data[0]['exam_medium']; }
                              else if(set_value('exam_medium') != '') { $chk_medium_code = set_value('exam_medium'); }
                              
                              if(count($medium_master) > 0)
                              {
                                foreach($medium_master as $mediums)
                                { ?>
                                <option value="<?php echo $mediums['medium_code'];?>" <?php if($chk_medium_code == $mediums['medium_code']) { echo  'selected="selected"'; } ?>><?php echo $mediums['medium_description'];?></option>
                                <?php } 
                              } ?>
                            </select>
                            <?php if(form_error('exam_medium')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_medium'); ?></label> <?php } ?>
                          </td>
                        </tr>
                          
                        <tr>
                          <td><strong>Exam Centre Name</strong><span class="mandatory-field">*</span></td>
                          <td>
                            <select required class="form-control" name="exam_centre" id="exam_centre" <?php if(in_array($exam_code,$csc_venue_master_eligible_exam_codes_arr)) { ?> onchange="get_csc_venue_details(this.value)" <?php } ?>>
                              <option value="">Select Exam Centre</option>
                              <?php 
                              $chk_centre_code = '';
                              if(isset($applied_exam_data[0]['exam_centre_code'])) { $chk_centre_code = $applied_exam_data[0]['exam_centre_code']; }
                              else if(set_value('exam_centre') != '') { $chk_centre_code = set_value('exam_centre'); }
                              
                              if(count($centre_master) > 0)
                              {      
                                foreach($centre_master as $centres)
                                { ?>   
                                <option value="<?php echo $centres['centre_code'];?>" <?php if($chk_centre_code == $centres['centre_code']){ echo 'selected="selected"'; } ?>><?php echo $centres['centre_name'];?></option>
                                <?php } 
                              } ?>
                            </select>
                            <?php if(form_error('exam_centre')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_centre'); ?></label> <?php } ?>
                          </td>
                        </tr>

                        <?php if(in_array($exam_code,$csc_venue_master_eligible_exam_codes_arr)) 
                        { ?>
                          <tr>
                            <td><strong>Venue Name</strong><span class="mandatory-field">*</span></td>
                            <td>
                              <div id="venue_outer">
                                <select required class="form-control" name="venue_name" id="venue_name" onchange="get_venue_date_details(this.value)">
                                  <option value="">Select Venue Name</option>
                                </select>
                              </div>
                              <?php if(form_error('venue_name')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('venue_name'); ?></label> <?php } ?>
                            </td>
                          </tr>
                            
                          <tr>
                            <td><strong>Exam Date</strong><span class="mandatory-field">*</span></td>
                            <td>
                              <div id="exam_date_outer">
                                <select required class="form-control" name="exam_date" id="exam_date" onchange="get_csc_venue_time_details(this.value)">
                                  <option value="">Select Exam Date</option>
                                </select>
                              </div>
                              <?php if(form_error('exam_date')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_date'); ?></label> <?php } ?>
                            </td>
                          </tr>

                          <tr>
                            <td><strong>Exam Time</strong><span class="mandatory-field">*</span></td>
                            <td>
                              <div id="exam_time_outer">
                                <select required class="form-control" name="exam_time" id="exam_time" onchange="get_csc_capacity_details(this.value)">
                                  <option value="">Select Exam Time</option>                                      
                                </select>
                              </div>
                              <?php if(form_error('exam_time')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_time'); ?></label> <?php } ?>
                            </td>
                          </tr>

                          <tr>
                            <td><strong>Seat(s) Available</strong><span class="mandatory-field">*</span></td>
                            <td>
                              <div id="seat_available_outer">-</div>
                              <input type="hidden" name="seat_available" id="seat_available" value="">
                              <?php if(form_error('seat_available')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('seat_available'); ?></label> <?php } ?>
                            </td>
                          </tr>

                          <tr>
                            <td><strong>Centre Code</strong><span class="mandatory-field">*</span></td>
                            <td>
                              <div id="centre_code_outer"></div>                                  
                            </td>
                          </tr>
                        <?php } ?>
                        
                        <tr>
                          <td></td>
                          <td>
                            <div id="submit_btn_outer">
                              <button type="submit" class="btn btn-info btn_submit">Pay Now</button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    
                    <div class="hr-line-dashed"></div>										
                    <div class="text-center">                      
                      <a href="<?php echo site_url('iibfbcbf/agency/apply_exam_csc_agency/index/'.$enc_exam_code); ?>" class="btn btn-danger">Back</a>
                    </div>
                  </form>
								</div>                
              </div>
						</div>
					</div>
				</div>
				<?php $this->load->view('iibfbcbf/agency/inc_footerbar_agency'); ?>			
			</div>
		</div>
		
		<?php $this->load->view('iibfbcbf/inc_footer'); ?>

    <?php if ($error) { ?><script>sweet_alert_error("<?php echo $error; ?>"); </script><?php } ?> 

    <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>		
  
    <script type="text/javascript">
      $.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" })// For chosen validation
      
      //START : JQUERY VALIDATION SCRIPT 
      function validate_input(input_id) { $("#"+input_id).valid(); }
      $(document ).ready( function() 
      {
        $("#apply_exam_form").validate( 
        {
          onkeyup: function(element) { $(element).valid(); },          
          rules:
          {
            exam_centre:{ required: true },             
            exam_medium:{ required: true },    
            venue_name:{ required: true }, 
            exam_date:{ required: true }, 
            exam_time:{ required: true },         
          },
          messages:
          {
            exam_centre: { required: "Please select the exam centre" },
            exam_medium: { required: "Please select the exam medium" },
            venue_name:{ required: "Please select the venue" },
            exam_date:{ required: "Please select the exam date" },
            exam_time:{ required: "Please select the exam time" },
          },         
          submitHandler: function(form) 
          {
            let exam_fee = "<?php echo $exam_fees; ?>";
            let seat_available = $.trim($("#seat_available").val());
            
            if(parseFloat(exam_fee) > 0)
            {
              if(parseInt(seat_available) > 0)
              {
                swal({ title: "Please confirm", text: "Please confirm, do you want to proceed with the payment", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
                { 
                  $("#page_loader").show();
                  $("#submit_btn_outer").html('<button type="button" style="cursor:wait" class="btn btn-info btn_submit">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button>');
                  
                  form.submit();
                }); 
              }
              else
              {
                sweet_alert_error("The capacity is full");
              }  
            }
            else
            {
              sweet_alert_error("You can not make payment for zero(0) fee");
            }
          }
        });
      });
      //END : JQUERY VALIDATION SCRIPT

      <?php if(in_array($exam_code,$csc_venue_master_eligible_exam_codes_arr)) 
      { ?> 
        var selected_exam_centre = $.trim($("#exam_centre").val());
        if(selected_exam_centre != '') { get_csc_venue_details(selected_exam_centre); }
        
        function get_csc_venue_details(centre_code)
        {
          <?php 
            $selected_venue_name = '';
            if(isset($applied_exam_data[0]['exam_venue_code'])) { $selected_venue_name = $applied_exam_data[0]['exam_venue_code']; }
            else if(set_value('venue_name') != '') { $selected_venue_name = set_value('venue_name'); } 
          ?>

          $("#page_loader").show();
          
          parameters= { "centre_code":centre_code, "exam_code":"<?php echo $exam_code; ?>", "exam_period":"<?php echo $exam_period; ?>", "selected_venue_name":"<?php echo $selected_venue_name; ?>" };

          $.ajax(
          {
            type: "POST",
            url: "<?php echo site_url('iibfbcbf/agency/apply_exam_csc_agency/get_csc_venue_details_ajax'); ?>",
            data: parameters,
            cache: false,
            dataType: 'JSON',
            success:function(data)
            {
              if(data.flag == "success")
              {
                $("#venue_outer").html(data.response);

                var selected_venue_code = $.trim($("#venue_name").val());
                if(selected_venue_code != '') { get_csc_venue_date_details(selected_venue_code); }

                $("#centre_code_outer").html(centre_code);

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
              sweet_alert_error("Error occurred. Please try again.")
              $('#page_loader').hide();
            }
          });
        
          get_csc_venue_date_details();
          get_csc_venue_time_details();
          get_csc_capacity_details();
        }
        
        function get_csc_venue_date_details(venue_code)
        {
          <?php 
            $selected_venue_date = '';
            if(isset($applied_exam_data[0]['exam_date'])) { $selected_venue_date = $applied_exam_data[0]['exam_date']; }
            else if(set_value('exam_date') != '') { $selected_venue_date = set_value('exam_date'); } 
          ?>
          
          $("#page_loader").show();
          parameters= { "exam_code":"<?php echo $exam_code; ?>", "venue_code":venue_code,  "selected_venue_date":"<?php echo $selected_venue_date; ?>" };

          $.ajax(
          {
            type: "POST",
            url: "<?php echo site_url('iibfbcbf/agency/apply_exam_csc_agency/get_csc_venue_date_details_ajax'); ?>",
            data: parameters,
            cache: false,
            dataType: 'JSON',
            success:function(data)
            {
              if(data.flag == "success")
              {
                $("#exam_date_outer").html(data.response);

                var selected_exam_date = $.trim($("#exam_date").val());
                if(selected_exam_date != '') { get_csc_venue_time_details(selected_exam_date); }

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
              sweet_alert_error("Error occurred. Please try again.")
              $('#page_loader').hide();
            }
          });

          get_csc_venue_time_details();
          get_csc_capacity_details();
        }
        
        function get_csc_venue_time_details(exam_date)
        {
          <?php 
            $selected_venue_time = '';
            if(isset($applied_exam_data[0]['exam_time'])) { $selected_venue_time = $applied_exam_data[0]['exam_time']; }
            else if(set_value('exam_time') != '') { $selected_venue_time = set_value('exam_time'); } 
          ?>

          $("#page_loader").show();
          parameters= { "exam_code":"<?php echo $exam_code; ?>", "exam_date":exam_date, "selected_venue_time":"<?php echo trim($selected_venue_time); ?>" };

          $.ajax(
          {
            type: "POST",
            url: "<?php echo site_url('iibfbcbf/agency/apply_exam_csc_agency/get_csc_venue_time_details_ajax'); ?>",
            data: parameters,
            cache: false,
            dataType: 'JSON',
            success:function(data)
            {
              if(data.flag == "success")
              {
                $("#exam_time_outer").html(data.response);

                var selected_exam_time = $.trim($("#exam_time").val());
                if(selected_exam_time != '') { get_csc_capacity_details(selected_exam_time); }

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
              sweet_alert_error("Error occurred. Please try again.")
              $('#page_loader').hide();
            }
          });

          get_csc_capacity_details();          
        }

        function get_csc_capacity_details(exam_time)
        {
          <?php 
            $chk_member_exam_id = '';
            if(count($applied_exam_data) > 0) { $chk_member_exam_id = $applied_exam_data[0]['member_exam_id']; }  
          ?>
          $("#page_loader").show();
          parameters= { "centre_code":$.trim($("#exam_centre").val()), "venue_code":$.trim($("#venue_name").val()), "exam_date":$.trim($("#exam_date").val()), "exam_time":exam_time, "exam_code":"<?php echo $exam_code; ?>", "exam_period":"<?php echo $exam_period; ?>", 'chk_member_exam_id':"<?php echo $chk_member_exam_id; ?>" };

          $.ajax(
          {
            type: "POST",
            url: "<?php echo site_url('iibfbcbf/agency/apply_exam_csc_agency/get_csc_capacity_details_ajax'); ?>",
            data: parameters,
            cache: false,
            dataType: 'JSON',
            success:function(data)
            {
              if(data.flag == "success")
              {
                $("#seat_available_outer").html(data.response);
                $("#seat_available").val(data.response);
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
              sweet_alert_error("Error occurred. Please try again.")
              $('#page_loader').hide();
            }
          });
        }
      <?php } ?>
    </script>
    
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>	
	</body>
</html>