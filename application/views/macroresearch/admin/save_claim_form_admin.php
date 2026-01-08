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
						<h2><?php echo $mode; ?> Honorarium Form </h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/admin/dashboard_admin'); ?>">Dashboard</a></li>
              
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/admin/candidate'); ?>">Candidate Master</a></li>
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
										<a href="<?php echo site_url('supervision/admin/candidate'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                <form method="post" action="<?php echo site_url('supervision/admin/candidate//save_claim_form/'.$enc_form_id.'/'.$enc_claim_id); ?>" id="add_form" enctype="multipart/form-data" autocomplete="off">
										<input type="hidden" name="mode" id="mode" value="<?php echo $mode; ?>">
                    <input type="hidden" name="form_id" id="form_id" value="<?php echo $form_id; ?>">
                    <input type="hidden" name="claim_id" id="claim_id" value="<?php echo $claim_id; ?>">
                    
                    <h4 class="custom_form_title" style="margin: -15px -20px 15px -20px !important;">Honorarium Form</h4>
                    
                    <div class="row">                      
                      <div class="col-xl-4 col-lg-4"><?php /* exam Name */ ?>
                        <div class="form-group">
                          <label for="exam" class="form_label"> Exam <sup class="text-danger">*</sup></label>
                          <input readonly type="text" name="" id="exam" value="<?php echo $form_data[0]['exam_name'];?>" placeholder=" Name *" class="form-control  " maxlength="90" />
                          
                        </div>					
                      </div>
                      
                      <div class="col-xl-4 col-lg-4"><?php /* venue */ ?>
                        <div class="form-group">
                          <label for="Venue" class="form_label">Venue <sup class="text-danger">*</sup></label>
                          <input readonly type="text" name="" id="Venue" value="<?php echo $form_data[0]['venue_name'] ?>" placeholder="Venue *" class="form-control " readonly/>
                          
                   
                        </div>					
                      </div>

                      <div class="col-xl-4 col-lg-4"><?php /* venueadd */ ?>
                        <div class="form-group">
                          <label for="venueadd" class="form_label">Venue Address <sup class="text-danger">*</sup></label>
                          <input readonly type="text" name="" id="venueadd" value="<?php echo $form_data[0]['venueadd1'].' '.$form_data[0]['venueadd2'].' '.$form_data[0]['venueadd3'].' '.$form_data[0]['venueadd4'].' '.$form_data[0]['venueadd5'].' '.$form_data[0]['venpin']; ?>" placeholder="Venue Address *" class="form-control  "   />
                          
                        </div>					
                      </div>

                      <div class="table-responsive" style="margin-top: 25px;margin-bottom: 25px;">
                        <table class="table table-bordered table-hover dataTables-example" style="width:100%">
                          <thead>
                            <tr> 
                              
                              <th class="text-center nowrap"><strong>Exam Date</strong></th> 
                              <th class="text-center nowrap"><strong>Name / Designation of the official visited</strong></th> 
                              <?php if(isset($sessions)) { 
                                foreach($sessions as $key=>$session ){
                                  if($key==0) $session_text = '1st';
                                  if($key==1) $session_text = '2nd';
                                  if($key==2) $session_text = '3rd';
                                ?>
                              <th class="text-center nowrap"> <strong><?php echo $session_text ?> session</strong></th> 
                              <?php } } ?>
                              
                              <th class="text-center nowrap"><strong>Total Amount</strong></th> 
                            </tr>
                          </thead>
                          
                          <tbody>
                                  <tr>
                                    <td class="text-center nowrap"><?php echo $form_data[0]['exam_date']; ?></td>
                                    <td class="text-center nowrap"><?php echo $candidate_data[0]['candidate_name']; ?></td>
                                    <?php if(isset($session_wise_amount)) { 
                                      foreach($session_wise_amount as $key=>$session ){ ?>
                                      <td class="text-center nowrap">Rs. <?php echo $session; ?></td>
                                      <?php } } ?>
                                      <td class="text-center nowrap">Rs. <?php echo $form_data[0]['total_amount']; ?></td>
                                </tr>

                          </tbody>
                        </table>
                      </div>	
                      
                    
                      <div class="col-xl-6 col-lg-6"><?php /* name*/ ?>
                        <div class="form-group">
                          <label for="beneficiary_name" class="form_label">Beneficiary Name <sup class="text-danger"></sup></label>
                          <input readonly type="text" name="beneficiary_name"  id="beneficiary_name" value="<?php if($mode == "Add") { echo set_value('beneficiary_name'); } else { echo $claim_data[0]['beneficiary_name']; } ?>" placeholder="Beneficiary Name" class="form-control custom_input beneficiary_name allow_only_alphabets_and_space"  maxlength="254"  onchange="validate_input('beneficiary_name')"/>
                          <div class="clearfix"></div>
                          <label class="error"><?php echo form_error('beneficiary_name'); ?></label>
                        </div>					
                      </div>

                    
                      <div class="col-xl-6 col-lg-6"><?php /* Acc_no*/ ?>
                        <div class="form-group">
                          <label for="account_no" class="form_label">Account Number <sup class="text-danger"></sup></label>
                          <input readonly type="text" name="account_no"  id="account_no" value="<?php if($mode == "Add") { echo set_value('account_no'); } else { echo $claim_data[0]['account_no']; } ?>" placeholder="Account Number" class="form-control custom_input account_no allow_only_numbers"  maxlength="25"  onchange="validate_input('account_no')"/>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('account_no'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* bank_branch_name*/ ?>
                        <div class="form-group">
                          <label for="bank_branch_name" class="form_label">Bank & Branch Name <sup class="text-danger"></sup></label>
                          <input readonly type="text" name="bank_branch_name"  id="bank_branch_name" value="<?php if($mode == "Add") { echo set_value('bank_branch_name'); } else { echo $claim_data[0]['bank_branch_name']; } ?>" placeholder="Bank & Branch Name" class="form-control custom_input bank_branch_name"  maxlength="254"  onchange="validate_input('bank_branch_name')"/>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('bank_branch_name'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* ifsc_code*/ ?>
                        <div class="form-group">
                          <label for="ifsc_code" class="form_label">IFSC Code <sup class="text-danger"></sup></label>
                          <input readonly type="text" name="ifsc_code"  id="ifsc_code" value="<?php if($mode == "Add") { echo set_value('ifsc_code'); } else { echo $claim_data[0]['ifsc_code']; } ?>" placeholder="IFSC Code" class="form-control custom_input ifsc_code"  maxlength="20"  onchange="validate_input('ifsc_code')"/>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('ifsc_code'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* email*/ ?>
                        <div class="form-group">
                          <label for="email" class="form_label">Email <sup class="text-danger"></sup></label>
                          <input readonly type="text" name="email"  id="email" value="<?php if($mode == "Add") { echo set_value('email'); } else { echo $claim_data[0]['email']; } ?>" placeholder="Email" class="form-control custom_input email"  maxlength="160"  onchange="validate_input('email')"/>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('email'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* mobile*/ ?>
                        <div class="form-group">
                          <label for="mobile" class="form_label">Mobile <sup class="text-danger"></sup></label>
                          <input readonly type="text" name="mobile"  id="mobile" value="<?php if($mode == "Add") { echo set_value('mobile'); } else { echo $claim_data[0]['mobile']; } ?>" placeholder="Mobile" class="form-control custom_input mobile allow_only_numbers"  maxlength="15"  onchange="validate_input('mobile')"/>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('mobile'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* pan_card*/ ?>
                        <div class="form-group">
                          <label for="pan_card" class="form_label">Pan Card Number<sup class="text-danger"></sup></label>
                          <input readonly type="text" name="pan_card"  id="pan_card" value="<?php if($mode == "Add") { echo set_value('pan_card'); } else { echo $claim_data[0]['pan_card']; } ?>" placeholder="Pan Card Number" class="form-control custom_input pan_card "  maxlength="25"  onchange="validate_input('pan_card')"/>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('pan_card'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* pan_card_doc*/ ?>
                        <div class="form-group">
                          <label style="margin-right: 35px;" for="pan_card_doc" class="form_label">Pan Card Upload<sup class="text-danger"></sup>
                          </label>
                          <?php if($mode == "Update" && $claim_data[0]['pan_card_doc']!='') { ?>
                          <i>Uploaded File: <a target="_blank" href="<?php echo base_url(); ?>/uploads/supervision/<?php echo $claim_data[0]['pan_card_doc'] ?>">View file</a></i><?php } ?>

                          <br>
                          <label style="margin-top: 10px;margin-right: 35px;" for="pan_card_doc" class="form_label">Cancelled cheque <sup class="text-danger"></sup></label>
                          <?php if($mode == "Update" && $claim_data[0]['canceled_cheque']!='') { ?>
                          <i>Uploaded File: <a target="_blank" href="<?php echo base_url(); ?>/uploads/supervision/<?php echo $claim_data[0]['canceled_cheque'] ?>">View file</a></i><?php } ?>
                          
                          <br><label style="margin-top: 10px;margin-right: 35px;" for="uploaded_file" class="form_label">Signed Document<sup class="text-danger"></sup></label>
                          <?php if($mode == "Update" && $claim_data[0]['uploaded_file']!='') { ?>
                          <i style="margin-top: 10px;">Uploaded File: <a  target="_blank" href="<?php echo base_url(); ?>/uploads/supervision/<?php echo $claim_data[0]['uploaded_file'] ?>">View file</a></i><?php } else echo'<i>Not Uploaded</i>'; ?>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('pan_card_doc'); ?></label>
                        </div>					
                      </div>

                      

                      <div class="col-xl-6 col-lg-6"><?php /* pan_card*/ ?>
                        <div class="form-group">
                          <label for="is_paid" class="form_label">Payment Status <sup class="text-danger"></sup></label>
                          <select class="form-control search_opt is_paid" required name="is_paid" id="is_paid" >
                            <option value="">Select Payment Status</option>
                            <option <?php if( $claim_data[0]['is_paid']==1) echo'selected'; ?>  value="1">Processed</option>                       
                            <option <?php if( $claim_data[0]['is_paid']==3) echo'selected'; ?> value="3">Rejected</option>           
                          </select>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('pan_card'); ?></label>
                        </div>					
                      </div>
                     
                      <div class="col-xl-6 col-lg-6 reject_reason_div" ><?php /* reject_reason*/ ?>
                        <div class="form-group">
                          <label for="reject_reason" class="form_label">Reason of Rejection<sup class="text-danger"></sup></label>
                          <input  type="text" name="reject_reason"  id="reject_reason" value="<?php if($mode == "Add") { echo set_value('reject_reason'); } else { echo $claim_data[0]['reject_reason']; } ?>" placeholder="Reason of Rejection" class="form-control custom_input reject_reason "  maxlength="500"  onchange="validate_input('reject_reason')"/>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('reject_reason'); ?></label>
                        </div>					
                      </div>                    
                    </div>             							
									
                   
                    <div class="hr-line-dashed"></div>										
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">
                      <?php if($this->session->userdata('SUPERVISION_ADMIN_TYPE')!=1) { ?>			
												<button class="btn btn-primary" type="submit">Submit</button>
                        <?php } ?>
												<a class="btn btn-danger" href="<?php echo site_url('supervision/admin/candidate/claims'); ?>">Back</a>	
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
    
  
  <script type="text/javascript">
    var email = $('#email').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy", viewMode: "years", minViewMode: "years", clearBtn: true, endDate:"<?php echo date("Y"); ?>" });
    
    $('select.is_paid').change(function() {
      reject_reason_func();
    });
    reject_reason_func();
    function reject_reason_func() {
        if ($('select.is_paid').val() == '3') {
            $('.reject_reason_div').show();
            $('.reject_reason').attr('required','required');
        }
        else {
          $('.reject_reason_div').hide();
            $('.reject_reason').removeAttr('required').removeClass('error').val('');
        }
    }
    
    //START : JQUERY VALIDATION SCRIPT 
    function validate_input(input_id) { $("#"+input_id).valid(); }
    $(document ).ready( function() 
    {
      var form = $("#add_form").validate( 
      {
        onkeyup: function(element) { $(element).valid(); },          
        rules:
        {
         
          is_paid:{ required: true }
        },
        messages:
        {
          
          is_paid: { required: "Please select payment status" },
         
          
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