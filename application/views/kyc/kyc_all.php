<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if(isset($page_title)) { echo $page_title; } else { echo 'IIBF'; } ?></title>
    <?php $this->load->view('kyc/inc_header'); ?>    
  </head>
	<body class="fixed-sidebar">
  <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
		
		<div id="wrapper">
    <?php $this->load->view('kyc/inc_sidebar_admin'); ?>		
			<div id="page-wrapper" class="gray-bg">				
      <?php $this->load->view('kyc/inc_topbar_admin'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $disp_title; ?></h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item active"> <strong><?php echo $disp_title; ?></strong></li>
						</ol>
					</div>
					<div class="col-lg-2"> </div>
				</div>
				
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox">
								<div class="ibox-content">
                  <form method="post" action="<?php echo site_url('kyc/kyc_all/index/'.$module_name); ?>" id="search_form" class="search_form_common_all" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="form_type" id="form_type" value="search_form">
                    
                    <div class="form-group text-left" style="min-width:250px;">
                      <select class="form-control search_opt" name="s_membership_type" id="s_membership_type" required onchange="fun_get_member_type_ajax(this.value)">
                        <?php if(count($form_membership_type_arr) > 0)
                        { ?>
                          <option value="">Select Membership Type</option> 
                          <?php foreach($form_membership_type_arr as $form_membership_type_key=>$form_membership_type_res)
                          { ?>
                            <option value="<?php echo $form_membership_type_key; ?>"><?php echo $form_membership_type_res; ?></option> 
                          <?php } 
                        }
                        else
                        { ?>
                          <option value="">Membership Type Not Available</option> 
                        <?php } ?>                    
                      </select>
                    </div>

                    <span id="member_type_outer">
                      <div class="form-group text-left" style="min-width:250px;">
                        <select class="form-control search_opt" name="s_member_type" id="s_member_type" required>
                          <option value="">Select Member Type</option>
                        </select>
                      </div>
                    </span>
                    
                    <span id="exam_code_outer"></span>

                    <?php 
                    
                    if(count($form_exam_codes_arr) > 0)
                    { ?>
                      <div class="form-group text-left">
                        <select class="form-control search_opt" name="s_exam_code" id="s_exam_code" required>
                          <option value="">Select Exam Code</option> 
                          <?php foreach($form_exam_codes_arr as $exam_codes_res)
                          { ?>
                            <option value="<?php echo $exam_codes_res; ?>" <?php if($exam_code == $exam_codes_res) { echo 'selected'; } ?>><?php echo $exam_codes_res; ?></option> 
                          <?php } ?>                    
                        </select>
                      </div>
                    <?php } ?>

                    <div class="form-group" style="width:auto;">
                      <button type="submit" class="btn btn-primary" id="start_kyc_button">Start KYC</button>
                    </div>
                  </form>
								</div>               
							</div>
						</div>
					</div>
				</div>				
				
				<?php $this->load->view('kyc/inc_footerbar_admin'); ?>	
			</div>
		</div>
		<?php $this->load->view('kyc/inc_footer'); ?>
		<?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>  
    
    <script type="text/javascript">
      function fun_get_member_type_ajax(selected_membership_type)
      {
        $("#page_loader").show();
        parameters= { 'module_name':'<?php echo $module_name; ?>', 'selected_membership_type':selected_membership_type }
        
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('kyc/kyc_all/get_member_type_ajax'); ?>",
          data: parameters,
          cache: false,
          dataType: 'JSON',
          success:function(data)
          {
            if(data.flag == "success")
            {
              $("#start_kyc_button").prop('disabled',false);
              $("#start_kyc_button").removeClass('disabled');
              $("#member_type_outer").html(data.response);
              $("#exam_code_outer").html('');
              $("#page_loader").hide();
            }
            else
            {
              $("#start_kyc_button").prop('disabled',true);
              $("#start_kyc_button").addClass('disabled');
              sweet_alert_error("Error occurred. Please try again.")
              $('#page_loader').hide();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            $("#start_kyc_button").prop('disabled',true);
            $("#start_kyc_button").addClass('disabled');
            console.log('AJAX request failed: ' + errorThrown);
            sweet_alert_error("Error occurred. Please try again.")
            $('#page_loader').hide();
          }
        });
      }

      function fun_get_exam_code_ajax(selected_member_type)
      {
        $("#page_loader").show();
        parameters= { 'module_name':'<?php echo $module_name; ?>', 'selected_member_type':selected_member_type, 'selected_membership_type':$.trim($("#s_membership_type").val()) }
        
        $.ajax(
        {
          type: "POST",
          url: "<?php echo site_url('kyc/kyc_all/get_exam_code_ajax'); ?>",
          data: parameters,
          cache: false,
          dataType: 'JSON',
          success:function(data)
          {
            if(data.flag == "success")
            {
              $("#start_kyc_button").prop('disabled',false);
              $("#start_kyc_button").removeClass('disabled');
              $("#exam_code_outer").html(data.response);
              $("#page_loader").hide();
            }
            else
            {
              $("#start_kyc_button").prop('disabled',true);
              $("#start_kyc_button").addClass('disabled');
              sweet_alert_error("Error occurred. Please try again.")
              $('#page_loader').hide();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) 
          {
            $("#start_kyc_button").prop('disabled',true);
            $("#start_kyc_button").addClass('disabled');
            console.log('AJAX request failed: ' + errorThrown);
            sweet_alert_error("Error occurred. Please try again.")
            $('#page_loader').hide();
          }
        });
      }

      function validate_input(input_id) { $("#"+input_id).valid(); }
			$(document ).ready( function() 
			{
        $("#search_form").validate( 
				{
          onkeyup: function(element) { $(element).valid(); },
					rules:
					{
            s_membership_type: { required: true },
            s_member_type: { required: true },
            s_exam_code: { required: function() { return $('#s_membership_type').val() === 'NM'; } }
					},
					messages:
					{
						s_membership_type: { required: "Please select the Membership Type" },
						s_member_type: { required: "Please select the Member Type" },
						s_exam_code: { required: "Please select the Exam Code" },	
					},
          errorPlacement: function(error, element) // For replace error 
          {
            if (element.attr("name") == "new_pass_kyc") { error.insertAfter("#new_pass_kyc_err"); }
            else { error.insertAfter(element); }
          },
					submitHandler: function(form) 
					{
            var s_membership_type_val = $("#s_membership_type").find('option:selected').val();
            var s_membership_type_text = $("#s_membership_type").find('option:selected').text();
            var s_member_type = $("#s_member_type").find('option:selected').text();
            var s_exam_code = $("#s_exam_code").val();

            var swal_msg = "Please confirm to start the KYC for "+s_membership_type_text+" ("+s_member_type+")";            
            if(s_membership_type_val === 'NM')
            {
              var swal_msg = "Please confirm to start the KYC for "+s_membership_type_text+" ("+s_member_type+" - "+s_exam_code+")";
            }

            swal({ title: "Please confirm", text: swal_msg, type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Confirm", closeOnConfirm: true }, function () 
            { 
              $("#page_loader").show();              
              form.submit();
            });
					}
				});

        $('#s_membership_type').change(function() 
        {
          if ($(this).val() === 'NM') 
          {
            $('#s_exam_code').rules('add', { required: true });
          } 
          else { $('#s_exam_code').rules('remove', 'required'); }
        });
      });
		</script>	
		<?php $this->load->view('kyc/common/inc_bottom_script'); ?>
	</body>
</html>