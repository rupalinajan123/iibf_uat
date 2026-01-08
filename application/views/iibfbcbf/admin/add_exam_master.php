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
						<h2><?php echo $mode; ?> Exam </h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/dashboard_admin'); ?>">Dashboard</a></li>
							<li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_master_admin'); ?>">Exam Master</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $mode; ?> Exam </strong></li>
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
										<a href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_master_admin'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <form method="post" action="<?php echo site_url('iibfbcbf/admin/masters_admin/add_exam_master/'.$enc_exam_id); ?>" id="add_form" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
                    
                    <div class="row">                      
                      <div class="col-xl-12 col-lg-12"><?php /* Exam Code & Type */ ?>
                        <div class="form-group">
                          <label class="form_label">Exam Code - Type <sup class="text-danger">*</sup></label>
                          <input type="text" value="<?php echo $form_data[0]['exam_code'].' - '.$form_data[0]['DispExamType']; ?>" placeholder="Exam Code - Type *" class="form-control custom_input" readonly disabled/>                          
                        </div>					
                      </div>
                    </div>

                    <div class="row">                      
                      <div class="col-xl-12 col-lg-12"><?php /* Exam Name */ ?>
                        <div class="form-group">
                          <label for="description" class="form_label">Exam Name <sup class="text-danger">*</sup></label>
                          <input type="text" name="description" id="description" value="<?php if($mode == "Add") { echo set_value('description'); } else { echo $form_data[0]['description']; } ?>" placeholder="Exam Name *" class="form-control custom_input validCustomInput" maxlength="90" required/>
                          <note class="form_note" id="description_err">Note: Please enter only 90 characters</note>
                          
                          <?php if(form_error('description')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('description'); ?></label> <?php } ?>
                        </div>					
                      </div>
                    </div>
                    
                    <div class="hr-line-dashed"></div>										
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">
												<button class="btn btn-primary" type="submit">Submit</button>
												<a class="btn btn-danger" href="<?php echo site_url('iibfbcbf/admin/masters_admin/exam_master_admin'); ?>">Back</a>	
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
      <?php $this->load->view('iibfbcbf/admin/inc_footerbar_admin'); ?>		
    </div>
  </div>
  
  <?php $this->load->view('iibfbcbf/inc_footer'); ?>		
  <?php $this->load->view('iibfbcbf/common/inc_common_validation_all'); ?>
  
  <?php  if($mode == 'Update') {
    $this->load->view('iibfbcbf/common/get_logs_common_ajax_call', array('enc_pk_id'=>$enc_exam_id, 'module_slug'=>'exam_master_action,exam_master_action', 'log_title'=>'Exam Master Log'));
  } ?>    
  
  <script type="text/javascript">
    //START : JQUERY VALIDATION SCRIPT 
    function validate_input(input_id) { $("#"+input_id).valid(); }
    $(document ).ready( function() 
    {
      var form = $("#add_form").validate( 
      {
        onkeyup: function(element) { $(element).valid(); },          
        rules:
        {
          description:{ required: true, validCustomInput:true, maxlength:90 },          
        },
        messages:
        {
          description: { required: "Please enter the exam name" },          
        }, 
        errorPlacement: function(error, element) // For replace error 
        {
          if (element.attr("name") == "description") { error.insertAfter("#description_err"); }
          else { error.insertAfter(element); }
        },          
        submitHandler: function(form) 
        {          
          $("#page_loader").hide();
          swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
          { 
            $("#page_loader").show();            
            $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait">Submit</button> <a class="btn btn-danger" href="<?php echo site_url('iibfbcbf/admin/agency'); ?>">Back</a>');
           
            form.submit();
          }); 
        }
      });
    });
    //END : JQUERY VALIDATION SCRIPT
  </script>
  <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
</body>
</html>