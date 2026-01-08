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
      <?php $this->load->view('supervision/candidate/inc_sidebar_candidate'); ?>		
			<div id="page-wrapper" class="gray-bg">				
      <?php $this->load->view('supervision/candidate/inc_topbar_candidate'); ?>
				
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2><?php echo $mode; ?> Honorarium form</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/candidate/dashboard_candidate'); ?>">Dashboard</a></li>
              <li class="breadcrumb-item">Masters</li>
							<li class="breadcrumb-item"><a href="<?php echo site_url('supervision/candidate/claims'); ?>">Honorarium form</a></li>
							<li class="breadcrumb-item active"> <strong><?php echo $mode; ?> Honorarium form </strong></li>
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
										<a href="<?php echo site_url('supervision/candidate/dashboard_candidate/claims'); ?>" class="btn btn-danger custom_right_add_new_btn">Back</a>
                  </div>
                </div>
								<div class="ibox-content">
                  <form method="post" action="<?php echo site_url('supervision/candidate/dashboard_candidate/save_claim_form/'.$enc_form_id.'/'.$enc_claim_id); ?>" id="add_form" enctype="multipart/form-data" autocomplete="off">
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
                          <input type="text" name="beneficiary_name"  id="beneficiary_name" value="<?php if($mode == "Add") { echo set_value('beneficiary_name'); } else { echo $claim_data[0]['beneficiary_name']; } ?>" placeholder="Beneficiary Name" class="form-control custom_input beneficiary_name allow_only_alphabets_and_space"  maxlength="254"  onchange="validate_input('beneficiary_name')"/>
                          <note class="form_note" id="beneficiary_name_err"></note>

                          <div class="clearfix"></div><label class="error"><?php echo form_error('beneficiary_name'); ?></label>
                        </div>					
                      </div>

                    
                      <div class="col-xl-6 col-lg-6"><?php /* Acc_no*/ ?>
                        <div class="form-group">
                          <label for="account_no" class="form_label">Account Number <sup class="text-danger"></sup></label>
                          <input type="text" name="account_no"  id="account_no" value="<?php if($mode == "Add") { echo set_value('account_no'); } else { echo $claim_data[0]['account_no']; } ?>" placeholder="Account Number" class="form-control custom_input account_no allow_only_numbers"  maxlength="25"  onchange="validate_input('account_no')"/>
                          <note class="form_note" id="account_no_err"></note>

                          <div class="clearfix"></div><label class="error"><?php echo form_error('account_no'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* bank_branch_name*/ ?>
                        <div class="form-group">
                          <label for="bank_branch_name" class="form_label">Bank & Branch Name <sup class="text-danger"></sup></label>
                          <input type="text" name="bank_branch_name"  id="bank_branch_name" value="<?php if($mode == "Add") { echo set_value('bank_branch_name'); } else { echo $claim_data[0]['bank_branch_name']; } ?>" placeholder="Bank & Branch Name" class="form-control custom_input bank_branch_name"  maxlength="254"  onchange="validate_input('bank_branch_name')"/>
                          <note class="form_note" id="bank_branch_name_err"></note>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('bank_branch_name'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* ifsc_code*/ ?>
                        <div class="form-group">
                          <label for="ifsc_code" class="form_label">IFSC Code <sup class="text-danger"></sup></label>
                          <input type="text" name="ifsc_code"  id="ifsc_code" value="<?php if($mode == "Add") { echo set_value('ifsc_code'); } else { echo $claim_data[0]['ifsc_code']; } ?>" placeholder="IFSC Code" class="form-control custom_input ifsc_code"  maxlength="20"  onchange="validate_input('ifsc_code')"/>
                          <note class="form_note" id="ifsc_code_err"></note>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('ifsc_code'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* email*/ ?>
                        <div class="form-group">
                          <label for="email" class="form_label">Email <sup class="text-danger"></sup></label>
                          <input type="text" name="email"  id="email" value="<?php if($mode == "Add") { echo set_value('email'); } else { echo $claim_data[0]['email']; } ?>" placeholder="Email" class="form-control custom_input email"  maxlength="160"  onchange="validate_input('email')"/>
                          <note class="form_note" id="email_err"></note>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('email'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* mobile*/ ?>
                        <div class="form-group">
                          <label for="mobile" class="form_label">Mobile <sup class="text-danger"></sup></label>
                          <input type="text" name="mobile"  id="mobile" value="<?php if($mode == "Add") { echo set_value('mobile'); } else { echo $claim_data[0]['mobile']; } ?>" placeholder="Mobile" class="form-control custom_input mobile allow_only_numbers"  maxlength="15"  onchange="validate_input('mobile')"/>
                          <note class="form_note" id="mobile_err"></note>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('mobile'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* pan_card*/ ?>
                        <div class="form-group">
                          <label for="pan_card" class="form_label">Pan Card Number<sup class="text-danger"></sup></label>
                          <input type="text" name="pan_card"  id="pan_card" value="<?php if($mode == "Add") { echo set_value('pan_card'); } else { echo $claim_data[0]['pan_card']; } ?>" placeholder="Pan Card Number" class="form-control custom_input pan_card "  maxlength="25"  onchange="validate_input('pan_card')"/>
                          <note class="form_note" id="pan_card_err"></note>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('pan_card'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* pan_card_doc*/ ?>
                        <div class="form-group">
                          <label for="pan_card_doc" class="form_label">Pan Card Upload<sup class="text-danger"></sup></label>
                          <?php if($mode == "Update" && $claim_data[0]['pan_card_doc']!='') { ?>
                          <i>Uploaded File: <a target="_blank" href="<?php echo base_url(); ?>/uploads/supervision/<?php echo $claim_data[0]['pan_card_doc'] ?>">View file</a></i><?php } ?>
                          <input <?php if($mode == "Add") echo'required'; ?>  type="file" name="pan_card_doc"  id="pan_card_doc" value="<?php if($mode == "Add") { echo set_value('pan_card_doc'); } else { echo $claim_data[0]['pan_card_doc']; } ?>" placeholder="Pan Card" class="form-control custom_input pan_card_doc "  maxlength="50"  onchange="validateFile(event, 'error_pan_card_doc_size', '', '300kb')"/>
                          <note class="form_note" id="pan_card_doc_err"></note>
                          <i>Upload in JPEG/PNG/PDF  format with 300kb Size</i>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('pan_card_doc'); ?></label>
                        </div>					
                      </div>

                      <div class="col-xl-6 col-lg-6"><?php /* canceled_cheque*/ ?>
                        <div class="form-group">
                          <label for="canceled_cheque" class="form_label">Cancelled cheque Upload<sup class="text-danger"></sup></label>
                          <?php if($mode == "Update" && $claim_data[0]['canceled_cheque']!='') { ?>
                          <i>Uploaded File: <a target="_blank" href="<?php echo base_url(); ?>/uploads/supervision/<?php echo $claim_data[0]['canceled_cheque'] ?>">View file</a></i><?php } ?>
                          <input <?php if($mode == "Add") echo'required'; ?>  type="file" name="canceled_cheque"  id="canceled_cheque" value="<?php if($mode == "Add") { echo set_value('canceled_cheque'); } else { echo $claim_data[0]['canceled_cheque']; } ?>" placeholder="Cancelled cheque" class="form-control custom_input canceled_cheque "  maxlength="50"  onchange="validateFile(event, 'error_canceled_cheque_size', '', '300kb')"/>
                          <note class="form_note" id="canceled_cheque_err"></note>
                          <div class="clearfix"></div>
                          <i>Upload in JPEG/PNG/PDF format with 300kb Size</i><label class="error"><?php echo form_error('canceled_cheque'); ?></label>
                        </div>					
                      </div>


                     <i style="display:none;" class="col-xl-12 col-lg-12">Download  file & enter below data manually</i>

                     <div style="display:none;" class="col-xl-4 col-lg-4"><?php /* place*/ ?>
                        <div class="form-group">
                          <label for="place" class="form_label">Place <sup class="text-danger"></sup></label>
                          <input type="text" name="place"  id="place" value="" placeholder="Place" class="form-control custom_input place "  maxlength="25" />
                          <div class="clearfix"></div><label class="error"><?php echo form_error('place'); ?></label>
                        </div>					
                      </div>
                      <div style="display:none;" class="col-xl-4 col-lg-4"><?php /* date*/ ?>
                        <div class="form-group">
                          <label for="date" class="form_label">Date <sup class="text-danger"></sup></label>
                          <input type="text" name="date"  id="date" value="" placeholder="date" class="form-control custom_input date "  maxlength="25" />
                          <div class="clearfix"></div><label class="error"><?php echo form_error('date'); ?></label>
                        </div>					
                      </div>
                      <div style="display:none;" class="col-xl-4 col-lg-4"><?php /* sign*/ ?>
                        <div class="form-group">
                          <label for="sign" class="form_label">Sign <sup class="text-danger"></sup></label>
                          <input type="text" name="sign"  id="sign" value="" placeholder="Sign" class="form-control custom_input sign "  maxlength="25" />
                          <div class="clearfix"></div><label class="error"><?php echo form_error('sign'); ?></label>
                        </div>					
                      </div>

                    
                    </div>
                    <?php if($mode == "Update" && $claim_data[0]['downloaded_file']!='') { ?>
                      <div class="hr-line-dashed"> </div>			
                      <div class="col-xl-6 col-lg-6"><?php /* pan_card_doc*/ ?>
                        <div class="form-group">
                          <label for="uploaded_file" class="form_label">Upload Signed Document<sup class="text-danger"></sup></label>
                          <?php if($mode == "Update" && $claim_data[0]['uploaded_file']!='') { ?>
                          <i>Uploaded PDF: <a target="_blank" href="<?php echo base_url(); ?>/uploads/supervision/<?php echo $claim_data[0]['uploaded_file'] ?>">View file</a></i><?php } ?>
                          <input <?php if($mode == "Add") echo'required'; ?>  type="file" name="uploaded_file"  id="uploaded_file" value="<?php if($mode == "Add") { echo set_value('uploaded_file'); } else { echo $claim_data[0]['uploaded_file']; } ?>" placeholder="Pan Card" class="form-control custom_input uploaded_file "  maxlength="50"  onchange="validateFile(event, 'error_uploaded_file_size', '', '300kb')"/>
                          <i>Upload in PDF format with 300kb Size</i>
                          <div class="clearfix"></div><label class="error"><?php echo form_error('uploaded_file'); ?></label>
                        </div>					
                      </div>

                      <?php if($mode == "Update" && $claim_data[0]['reject_reason']!='') { ?>
                        <div class="col-xl-6 col-lg-6"><?php /* pan_card_doc*/ ?>
                        <div class="form-group">
                          <label for="uploaded_file" class="form_label">Rejection Reason<sup class="text-danger"></sup></label>
                          
                          <div class="clearfix"></div><label class="error"><?php echo $claim_data[0]['reject_reason']; ?></label>
                        </div>					
                      </div>
                      <?php } ?>
                      <?php } ?>
                    <div class="hr-line-dashed"></div>									
										<div class="row">
											<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="submit_btn_outer">
												<button class="btn btn-primary" type="submit">Submit</button>
												<a class="btn btn-danger" href="<?php echo site_url('supervision/candidate/dashboard_candidate/claims'); ?>">Back</a>	
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
    var filled_date = $('#filled_date').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy", viewMode: "years", minViewMode: "years", clearBtn: true, endDate:"<?php echo date("Y"); ?>" });

    function validateFile(event, error_id, show_img_id, size, img_width, img_height) {
        var srcid = event.srcElement.id;
        if (document.getElementById(srcid).files.length != 0) {
          var file = document.getElementById(srcid).files[0];

          if (file.size == 0) {
            $('#' + error_id).text('Please select valid file');
            $('#' + document.getElementById(srcid).id).val('')
            $('#' + show_img_id).attr('src', "/assets/images/default1.png");
          }
          else {
            var file_size = document.getElementById(srcid).files[0].size / 1024;
            var mimeType = document.getElementById(srcid).files[0].type;

            var allowedFiles = [".jpg", ".jpeg",".pdf", ".jpeg",".png"];
            if ($('#' + document.getElementById(srcid).id + '_allowedFilesTypes').text() != "") {
              var allowedFiles = $('#' + document.getElementById(srcid).id + '_allowedFilesTypes').text().split(",");
            }
            var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");

            var reader = new FileReader();

            var check_size = '';

            if (size.indexOf('kb') !== -1) {
              var check_size = size.split('k');
            }
            if (size.indexOf('mb') !== -1) {
              var check_size = size.split('m');
            }

            reader.onload = function (e) {
              var img = new Image();
              img.src = e.target.result;

              if (reader.result == 'data:') {
                $('#' + error_id).text('This file is corrupted');
                $('#' + document.getElementById(srcid).id).val('')
                $('#' + show_img_id).attr('src', "/assets/images/default1.png");
              }
              else {
                
                if (!regex.test(file.name.toLowerCase())) {
                  $('#' + error_id).text("Please upload " + allowedFiles.join(', ') + " only.");
                  $('#' + document.getElementById(srcid).id).val('')
                  $('#' + show_img_id).attr('src', "/assets/images/default1.png");
                }
                else {
                  if (file_size > check_size[0]) {
                    //console.log('if');
                    $('#' + error_id).text("Please upload file less than " + size);
                  
                    $('#' + document.getElementById(srcid).id).val('')
                    $('#' + show_img_id).attr('src', "/assets/images/default1.png");
                  }
                  else if (file_size < 8) //IF FILE SIZE IS LESS THAN 8KB
                  {
                    $('#' + error_id).text("Please upload file having size more than 8KB");
                    $('#' + document.getElementById(srcid).id).val('')
                    $('#' + show_img_id).attr('src', "/assets/images/default1.png");
                  }
                  else {
                    img.onload = function () {
                      var width = this.width;
                      var height = this.height;


                      if (width > img_width && height > img_height) {
                        $('#' + error_id).text(' Uploaded File dimensions are ' + width + '*' + height + ' pixel. Please Upload file dimensions between ' + img_width + '*' + img_height + ' pixel');
                     
                        $('#' + document.getElementById(srcid).id).val('')
                        $('#' + show_img_id).attr('src', "/assets/images/default1.png");
                      }
                      else {
                        //console.log('else');
                        $('#' + error_id).text("");
                        $('.btn_submit').attr('disabled', false);
                        $('#' + show_img_id).attr('src', '');
                        $('#' + show_img_id).removeAttr('src');
                        $('#' + show_img_id).attr('src', reader.result);

                        var img = new Image();
                        img.src = reader.result;
                      }
                    }

                  }
                }
              }
            }

            reader.readAsDataURL(event.target.files[0]);
          }
        }
        else {
          $('#' + error_id).text('Please select file');         
          $('#' + document.getElementById(srcid).id).val('')
          $('#' + show_img_id).attr('src', "/assets/images/default1.png");
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
          beneficiary_name:{ required: true, allow_only_alphabets_and_space:true, maxlength:254 },
          account_no:{ required: true, maxlength:25 , allow_only_numbers:true},
          bank_branch_name:{  required: true,maxlength:254 },         
          ifsc_code:{ required: true,maxlength:20  },  
          pan_card:{ required: true ,maxlength:20 },   
          mobile:{ required: true, allow_only_numbers:true, first_zero_not_allowed:true, maxlength:10, minlength:10},            
          email:{ required: true, maxlength:80, valid_email:true},
          
         
        },
        messages:
        {
          beneficiary_name:{required:'Please enter beneficiary name',allow_only_alphabets_and_space:'Only alphabets are allowed',maxlength:'Please enter 255 characters'},
          bank_branch_name:{required:'Please enter bank/branch name',maxlength:'Please enter 255 characters'},
          ifsc_code:{required:'Please enter IFSC Code',maxlength:'Please enter 20 characters'},
          pan_card:{required:'Please enter Pan card Number',maxlength:'Please enter 20 characters'},
          pan_card_doc:{required:'Please upload Pan card Document in JPEG format'},
          canceled_cheque:{required:'Please upload Cancelled cheque in PDF format'},
          account_no:{required:'Please enter account number',allow_only_numbers:'Allowed only numbers'},
          mobile: { required: "Please enter the mobile number", minlength: "Please enter 10 numbers in mobile number", maxlength: "Please enter 10 numbers in mobile number", first_zero_not_allowed: "The mobile number is not valid",allow_only_numbers: "The mobile number is not valid" },
          email: { required: "Please enter the email id", valid_email: "Please enter the valid email id" },
        }, 
        errorPlacement: function(error, element) // For replace error 
        {
          if (element.attr("name") == "beneficiary_name") { error.insertAfter("#beneficiary_name_err"); }
          if (element.attr("name") == "bank_branch_name") { error.insertAfter("#bank_branch_name_err"); }
          if (element.attr("name") == "ifsc_code") { error.insertAfter("#ifsc_code_err"); }
          if (element.attr("name") == "pan_card") { error.insertAfter("#pan_card_err"); }
          if (element.attr("name") == "pan_card_doc") { error.insertAfter("#pan_card_doc_err"); }
          if (element.attr("name") == "account_no") { error.insertAfter("#account_no_err"); }
          if (element.attr("name") == "mobile") { error.insertAfter("#mobile_err"); }
          if (element.attr("name") == "email") { error.insertAfter("#email_err"); }
          if (element.attr("name") == "canceled_cheque") { error.insertAfter("#canceled_cheque_err"); }
        },          
        submitHandler: function(form) 
        {          
          $("#page_loader").hide();
          swal({ title: "Please confirm", text: "Please confirm to submit the details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
          { 
            $("#page_loader").show();            
            $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait">Submit</button> <a class="btn btn-danger" href="<?php echo site_url('supervision/candidate/dashboard_candidate/'); ?>">Back</a>');
           
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