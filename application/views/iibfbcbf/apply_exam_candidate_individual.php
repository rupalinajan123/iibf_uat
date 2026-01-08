<!DOCTYPE html>
<html>
  <head>
    <?php $this->load->view('iibfbcbf/inc_header'); ?>
    <style type="text/css">
      .blink-highlight {
        background-color: #ffb300;
        animation: blink 1s step-start 0s infinite;
      }
      @keyframes blink {
          50% {
            background-color: transparent;
          }
        }
    </style>
  </head>
  
  <body class="gray-bg">
    <?php $this->load->view('iibfbcbf/common/inc_loader'); ?>
    <div class="d-flex logo" style="z-index: 1;"><img src="<?php echo base_url('assets/iibfbcbf/images/iibf_logo.png'); ?>" class="img-fluid" title="INDIAN INSTITUTE OF BANKING & FINANCE" alt="INDIAN INSTITUTE OF BANKING & FINANCE">   <h3 class="mb-0">INDIAN INSTITUTE OF BANKING & FINANCE - BCBF Apply Exam</h3></div>
    <div class="bcbf_wrap"> 
        <div class="container">        
         
          
          <div class="admin_login_form animated fadeInDown" style="max-width:none; margin-top:110px;">
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

                  <?php if(count($subject_master) > 0) 
                    { ?>
                    <tr>
                      <td><strong><span style="font-weight: bold;background-color: #ffb300;padding: 5px;" class="blink-highlight">Examination Date</span></strong></td>
                      <td><span style="font-weight: bold;background-color: #ffb300;padding: 5px 14px 6px 9px;"><?php echo date("d-M-Y", strtotime($subject_master[0]['exam_date'])); ?></span></td>
                    </tr>
                  <?php } ?>

                  <tr>
                    <td><strong>Exam medium</strong><span class="mandatory-field">*</span></td>
                    <td>
                      <select required class="form-control" name="exam_medium" id="exam_medium">
                        <option value="">Select Exam medium</option>
                        <?php if(count($medium_master) > 0)
                          {
                            foreach($medium_master as $mediums)
                            { ?>
                            <option value="<?php echo $mediums['medium_code'];?>" <?php if(isset($applied_exam_data[0]['exam_medium']) && $mediums['medium_code'] == $applied_exam_data[0]['exam_medium']) { echo  'selected="selected"'; } ?>><?php echo $mediums['medium_description'];?>
                            </option>
                            <?php } 
                          } ?>
                      </select>
                      <?php if(form_error('exam_medium')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_medium'); ?></label> <?php } ?>
                    </td>
                  </tr>
                  
                  <tr>
                    <td><strong>Exam Centre Name</strong><span class="mandatory-field">*</span></td>
                    <td>
                      <select required class="form-control" name="exam_centre" id="exam_centre">
                        <option value="">Select Exam Centre</option>
                        <?php if(count($centre_master) > 0)
                          {      
                            foreach($centre_master as $centres)
                            { ?>   
                            <option value="<?php echo $centres['centre_code'];?>" <?php if(isset($applied_exam_data[0]['exam_centre_code']) && $centres['centre_code'] == $applied_exam_data[0]['exam_centre_code']){ echo 'selected="selected"'; } ?>><?php echo $centres['centre_name'];?>
                            </option>
                            <?php } 
                          } ?>
                      </select>
                      <?php if(form_error('exam_centre')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('exam_centre'); ?></label> <?php } ?>
                    </td>
                  </tr>
                                    
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
                <a href="<?php echo site_url('iibfbcbf/apply_exam_individual'); ?>" class="btn btn-danger">Back</a>
              </div>
            </form>
          
          </div>
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
          },
          messages:
          {
            exam_centre: { required: "Please select the exam centre" },
            exam_medium: { required: "Please select the exam medium" },
          },         
          submitHandler: function(form) 
          {
            let exam_fee = "<?php echo $exam_fees; ?>";
            if(parseFloat(exam_fee) > 0)
            {
              swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
              { 
                $("#page_loader").show();
                $("#submit_btn_outer").html('<button type="button" style="cursor:wait" class="btn btn-info btn_submit">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button>');
                
                form.submit();
              }); 
            }
            else
            {
              sweet_alert_error("You can not make payment for zero(0) fee");
            }
          }
        });
      });
      //END : JQUERY VALIDATION SCRIPT
    </script>
    
    <?php $this->load->view('iibfbcbf/common/inc_bottom_script'); ?>
  </body>
</html>