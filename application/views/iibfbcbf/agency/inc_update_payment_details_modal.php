<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="modal-title">Update Payment Details</h4>
  <?php  ?>
</div>
<form action="<?php echo site_url('iibfbcbf/agency/transaction_details_agency'); ?>" method="post" class="form-horizontal" name="update_payment_form" id="update_payment_form" autocomplete="off" enctype="multipart/form-data">
  <input type="hidden" name="enc_payment_id" id="enc_payment_id" value="<?php echo url_encode($payment_data[0]['id']); ?>">
  <div class="modal-body">
    <div class="modal_form_outer">
      <div class="form-group row">
        <label class="col-lg-4 text-right"><b>Agency Name :</b></label>
        <div class="col-lg-8"><div class="modal_form_info_text"><?php echo $agency_centre_data[0]['agency_name']." (".$agency_centre_data[0]['agency_code'].")"; ?></div></div>
      </div>
      <div class="form-group row">
      <label class="col-lg-4 text-right"><b>Centre Name :</b></label>
        <div class="col-lg-8"><div class="modal_form_info_text"><?php echo $agency_centre_data[0]['centre_name']." (".$agency_centre_data[0]['state_name'].", ".$agency_centre_data[0]['city_name'].")"; ?></div></div>
      </div>
      <div class="form-group row">
      <label class="col-lg-4 text-right"><b>To Payee (IIBF) :</b></label>
        <div class="col-lg-8">
          <div class="modal_form_info_text">Beneficiary Name: Indian Institute of Banking & Finance<br>Bank: State bank of India<br>Branch: Kurla (west)<br>Code: 1886<br>IFSC: SBIN0001886<br>Current Acct No: 32344902738<br>MICR No: 400002091<br>SWIFT CODE: SBININBB357</div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-lg-4 text-right"><b>Amount :</b></label>
        <div class="col-lg-8"><div class="modal_form_info_text"><?php echo number_format_upto2($payment_data[0]['amount']); ?></div></div>
      </div>
      <div class="form-group row">
        <label class="col-lg-4 text-right"><b>Exam Name & Exam Period :</b></label>
        <div class="col-lg-8"><div class="modal_form_info_text"><?php echo display_exam_name($active_exam_data[0]['description'], $active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_type']).' - '.$active_exam_data[0]['exam_period']; /* helpers/iibfbcbf/iibf_bcbf_helper.php */ ?></div></div>
      </div>

      <div class="form-group row">
        <label class="col-lg-4 text-right"><b>NEFT / RTGS (UTR) Number <sup class="text-danger">*</sup> :</b></label>
        <div class="col-lg-8">
          <input type="text" name="utr_no" id="utr_no" value="<?php echo $form_utr_no; ?>" placeholder="NEFT / RTGS (UTR) Number *" class="form-control custom_input allow_only_alphabets_and_numbers" maxlength="30" required/>
          
          <?php if($form_utr_no_error!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo $form_utr_no_error; ?></label> <?php } ?>
        </div>
      </div>
      
      <div class="form-group row">
        <label class="col-lg-4 text-right"><b>Payment Date <sup class="text-danger">*</sup> :</b></label>
        <div class="col-lg-8">
          <input type="text" name="payment_date" id="payment_date" value="<?php echo $form_payment_date; ?>" placeholder="Payment Date *" class="form-control custom_input" onchange="validate_input('payment_date');" required readonly/>
          
          <?php if($form_payment_date_error!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo $form_payment_date_error; ?></label> <?php } ?>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-lg-4 text-right"><b>Upload payment (UTR) slip <sup class="text-danger">*</sup> :</b></label>
        <div class="col-lg-8">
          <div class="img_preview_input_outer pull-left">
            <input type="file" name="utr_slip" id="utr_slip" class="form-control" accept=".png,.jpeg,.jpg" data-accept=".jpg,.jpeg,.pdf" onchange="show_preview(event, 'utr_slip_preview'); validate_input('utr_slip');" required />

            <note class="form_note" id="utr_slip_err">Note: Please Upload only .jpg, .jpeg, .png Files upto 100KB</note>

            <?php if($form_utr_slip_error!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo $form_utr_slip_error; ?></label> <?php } ?>
            <?php if($utr_slip_error != ""){ ?> <div class="clearfix"></div><label class="error"><?php echo $utr_slip_error; ?></label> <?php } ?>
          </div>

          <div id="utr_slip_preview" class="upload_img_preview pull-right">
            <i class="fa fa-picture-o" aria-hidden="true"></i>
          </div>
        </div>
      </div>

      <div class="form-group row">
      <label class="col-lg-4 text-right"><b>GST No to be displayed on Invoice <sup class="text-danger">*</sup> :</b></label>
        <div class="col-lg-8">
          <?php /* <select name="gst_centre_id" id="gst_centre_id" class="form-control" required>
            <option value="">Select GST No *</option>
            <option value="0" <?php if($form_gst_centre_id == '0') { echo 'selected'; } ?>>NA</option>
            <option value="<?php echo $agency_centre_data[0]['centre_id']; ?>" <?php if($form_gst_centre_id == $agency_centre_data[0]['centre_id']) { echo 'selected'; } ?>><?php echo $agency_centre_data[0]['gst_no']; ?></option>
          </select>  
          <?php if($form_gst_centre_id_error!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo $form_gst_centre_id_error; ?></label> <?php }  */?>

          <strong><?php if($agency_centre_data[0]['invoice_address'] == '1') { echo $agency_centre_data[0]['AgencyGST']; }
          else if($agency_centre_data[0]['invoice_address'] == '2') { echo $agency_centre_data[0]['CentreGST']; } ?></strong>
        </div>
      </div>

    </div>
  </div>

  <div class="modal-footer" id="submit_btn_outer">
    <button class="btn btn-primary" type="submit" value="submit">Submit</button>
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
  </div>
</form>

<script language="javascript">
  var payment_date = $('#payment_date').datepicker({ keyboardNavigation: true, forceParse: true, autoclose: true, format: "yyyy-mm-dd", clearBtn: true, startDate:"<?php echo date("Y-m-d", strtotime($active_exam_data[0]['exam_from_date'])); ?>", endDate:"<?php echo date("Y-m-d"); ?>" });

  function validate_input(input_id) { $("#"+input_id).valid(); }
  $(document ).ready( function() 
  {
    $("#update_payment_form").validate( 
    {
      //onfocusout: true,
      onkeyup: function(element) { $(element).valid(); },          
      rules:
      {
        utr_no:{ required: true, allow_only_alphabets_and_numbers:true, maxlength:30, remote: { url: "<?php echo site_url('iibfbcbf/agency/transaction_details_agency/validation_check_utr_exist/0/1'); ?>", type: "post" } },
        payment_date:{ required: true, dateFormat:'Y-m-d' },
        utr_slip:{ required: true, check_valid_file:true, valid_file_format:'.jpg,.jpeg,.png', filesize_max:'100000' }, //use size in bytes //filesize_max: 1MB : 1000000  
        /* gst_centre_id:{ required: true }, */
      },
      messages:
      {
        utr_no: { required: "Please enter the NEFT / RTGS (UTR) Number", remote:"This UTR No is already present. Please enter unique utr no." },
        payment_date: { required: "Please select the Payment Date", dateFormat:"Please select the payment date like yyyy-mm-dd" },
        utr_slip: { required: "Please select the payment (UTR) slip", valid_file_format:"Please upload only .jpg, .jpeg, .png files", filesize_max:"Please upload file less than 100KB" },
        /* gst_centre_id: { required: "Please select the GST No to be displayed on Invoice" }, */
      }, 
      errorPlacement: function(error, element) // For replace error 
      {
        if (element.attr("name") == "utr_slip") { error.insertAfter("#utr_slip_err"); }      
        else { error.insertAfter(element); }
      },          
      submitHandler: function(form) 
      {
        swal({ title: "Please confirm", text: "Please confirm to submit the payment details", type: "warning", showCancelButton: true, confirmButtonColor: "#DD6B55", confirmButtonText: "Yes", closeOnConfirm: true }, function () 
        { 
          $("#page_loader").show();
          $("#submit_btn_outer").html('<button class="btn btn-primary" type="button" style="cursor:wait" value="submit">Submit <i class="fa fa-spinner" aria-hidden="true"></i></button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>');
          form.submit();
        });            
      }
    });
  });
</script>