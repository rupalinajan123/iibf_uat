<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('vendors/inc_header'); ?>
</head>

<body class="hold-transition skin-blue layout-top-nav">
  <div class="wrapper">
    <?php $this->load->view('vendors/inc_navbar'); ?>

    <div class="container">
      <section class="content">
        <section class="content-header">
          <h1 class="register">Vendor Registration Form</h1><br />
          <div style="display:none"><?php echo get_ip_address(); ?></div>
        </section>

        <div class="col-md-12">
          <div class="row">
            <?php if ($this->session->flashdata('error') != '') { ?>
              <div class="alert alert-danger alert-dismissible" id="error_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('error'); ?>
              </div>
            <?php }

            if ($this->session->flashdata('success') != '') { ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?>
              </div>
            <?php } ?>

            <div class="box box-info">
              <form class="form-vertical" name="usersAddForm" id="usersAddForm" method="post" action="<?php echo site_url('vendors/registration/index/' . $member_type); ?>" enctype="multipart/form-data" autocomplete="off">

                <div class="col-md-12"> 
                  <div class="form-group col-md-12">
                    <note>Note:Please fill Form in BLOCK Letters</note>
                    <label for="full_name" class="control-label">Vendor/ Trade Full Name<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" maxlength="200" class="form-control custom_input_formfields" id="full_name" name="full_name" placeholder="Vendor/ Trade Full Name" required value="<?php echo set_value('full_name'); ?>">
                    <?php if (form_error('full_name') != "") { ?><label class="error"><?php echo form_error('full_name'); ?></label> <?php } ?>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="address" class="control-label">Address<span style="color:#F00">*</span></label>
                    <textarea style="resize: none;" maxlength="500" class="form-control custom_input_formfields" id="address" name="address" placeholder="Address" required><?php echo set_value('address'); ?></textarea>
                    <?php if (form_error('address') != "") { ?><label class="error"><?php echo form_error('address'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="state" class="control-label">State<span style="color:#F00">*</span></label>
                    <select class="form-control form2_element custom_input_formfields" id="ccstate" name="state" required onchange="get_city_ajax()">
                      <option value="">Select</option>
                      <?php if (count($states) > 0) {
                        foreach ($states as $row1) {  ?>
                          <option value="<?php echo $row1['state_code']; ?>" <?php echo  set_select('state', $row1['state_code']); ?>><?php echo $row1['state_name']; ?></option>
                      <?php }
                      } ?>
                    </select>
                    <input hidden="statepincode" id="statepincode" value="<?php echo set_value('statepincode'); ?>">

                    <!-- <input type="text" class="form-control" id="state" name="state" placeholder="State" required value="<?php echo set_value('state'); ?>" >
											<?php //if(form_error('state')!=""){ 
                      ?><label class="error"><?php echo form_error('state'); ?></label> <?php //} 
                                                                                        ?> -->
                  </div>

                  <div class="form-group col-md-4">
                    <label for="city" class="control-label">City<span style="color:#F00">*</span></label>
                    <select class="form-control city form2_element custom_input_formfields" id="city" name="city" required>
                      <option value="">Select City</option>
                    </select>

                    <?php if (form_error('city') != "") { ?><label class="error"><?php echo form_error('city'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="pin_code" class="control-label">Pin code<span style="color:#F00">*</span></label>
                    <input type="text" class="form-control custom_input_formfields" id="pin_code" name="pin_code" placeholder="Pin code" required onkeypress="return(isNumber(event));" maxlength="6" autocomplete="off" value="<?php echo set_value('pin_code'); ?>">
                    <?php if (form_error('pin_code') != "") { ?><label class="error"><?php echo form_error('pin_code'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="type_of_person" class="control-label">Type of Person<span style="color:#F00">*</span></label>
                    <div class="checkbox type_of_person_checkbox" id="type_of_person_err">
                      <ul>
                        <li><label><input type="radio" class="person_type custom_input_formfields" value="Individual" name="type_of_person"> Individual</label></li>
                        <li><label><input type="radio" class="person_type custom_input_formfields" value="HUF" name="type_of_person"> HUF</label></li>
                        <li><label><input type="radio" class="person_type custom_input_formfields" value="Company" name="type_of_person"> Company</label></li>
                        <li><label><input type="radio" class="person_type custom_input_formfields" value="Firm" name="type_of_person"> Firm</label></li>
                        <li><label><input type="radio" class="person_type custom_input_formfields" value="AOP/BOI" name="type_of_person"> AOP/BOI</label></li>
                        <li><label><input type="radio" class="person_type custom_input_formfields" value="Local Authority" name="type_of_person"> Local Authority</label></li>
                        <li><label><input type="radio" class="person_type custom_input_formfields" value="Every Artificial Judicial Person" name="type_of_person"> Every Artificial Judicial Person</label></li>
                      </ul>
                    </div>
                    <input style="display: none;" type="text" maxlength="200" class="form-control custom_input_formfields col-md-4" id="company_cin" name="company_cin" placeholder="Enter CIN*">
                    <?php if (form_error('type_of_person') != "") { ?><label class="error"><?php echo form_error('type_of_person'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="contact_person_name" class="control-label">Contact Person Name<span style="color:#F00"></span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));"  class="form-control custom_input_formfields" id="contact_person_name" name="contact_person_name" maxlength="150" placeholder="Contact Person Name" value="<?php echo set_value('contact_person_name'); ?>">
                    <?php if (form_error('contact_person_name') != "") { ?><label class="error"><?php echo form_error('contact_person_name'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="designation" class="control-label">Designation<span style="color:#F00"></span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" class="form-control custom_input_formfields" id="designation" name="designation" maxlength="150" placeholder="Designation" value="<?php echo set_value('designation'); ?>">
                    <?php if (form_error('designation') != "") { ?><label class="error"><?php echo form_error('designation'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="email_id" class="control-label">Email ID<span style="color:#F00">*</span></label>
                    <input type="text" class="form-control custom_input_formfields" id="email_id" name="email_id" placeholder="Email ID" maxlength="150" value="<?php echo set_value('email_id'); ?>">
                    <?php if (form_error('email_id') != "") { ?><label class="error"><?php echo form_error('email_id'); ?></label> <?php } ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="mobile_no" class="control-label">Mobile No.<span style="color:#F00">*</span></label>
                    <input type="text" class="form-control custom_input_formfields" onkeypress="return isNumber(event)" required maxlength="10" id="mobile_no" name="mobile_no" placeholder="Mobile No." value="<?php echo set_value('mobile_no'); ?>">
                    <?php if (form_error('mobile_no') != "") { ?><label class="error"><?php echo form_error('mobile_no'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="website" class="control-label">Website<span style="color:#F00"></span></label>
                    <input type="text" class="form-control" id="website" name="website" placeholder="Website" value="<?php echo set_value('website'); ?>">
                    <?php if (form_error('website') != "") { ?><label class="error"><?php echo form_error('website'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="telephone_no" class="control-label">Telephone No. (If any)<span style="color:#F00"></span></label>
                    <input type="text" onkeypress="return isNumber(event)" maxlength="20" class="form-control custom_input_formfields" id="telephone_no" name="telephone_no" placeholder="Telephone No. (If any)" value="<?php echo set_value('telephone_no'); ?>">
                    <?php if (form_error('telephone_no') != "") { ?><label class="error"><?php echo form_error('telephone_no'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="nature_of_goods_services" class="control-label">Nature of Goods/ Services provided to IIBF<span style="color:#F00"></span></label>
                    <input type="text" class="form-control custom_input_formfields" id="nature_of_goods_services" maxlength="200" name="nature_of_goods_services" placeholder="Nature of Goods/ Services provided to IIBF" value="<?php echo set_value('nature_of_goods_services'); ?>">
                    <?php if (form_error('nature_of_goods_services') != "") { ?><label class="error"><?php echo form_error('nature_of_goods_services'); ?></label> <?php } ?>
                  </div>
                </div>
                <div class="col-md-12">
                  <h1 class="heading_class">Taxation Related Information</h1>
                  <div class="form-group col-md-6">
                    <label for="pan_no" class="control-label">Pan No.<span style="color:#F00">*</span></label>
                    <input type="text" class="form-control custom_input_formfields" maxlength="10" required id="pan_no" name="pan_no" placeholder="Pan No." value="<?php echo set_value('pan_no'); ?>">
                    <note>Note: Please Enter PAN no like: ABCTY1234D</note>

                    <?php if (form_error('pan_no') != "") { ?><label class="error"><?php echo form_error('pan_no'); ?></label> <?php } ?>
                    <label class="error" id="pan_no_error"></label>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="upload_pan_no" class="control-label">Upload Pan No.<span style="color:#F00">*</span></label>
                    <input type="file" class="form-control custom_input_formfields" id="upload_pan_no" name="upload_pan_no" placeholder="Upload Pan No." value="<?php echo set_value('upload_pan_no'); ?>" onchange="validate_file('upload_pan_no')" accept=".png,.jpeg,.jpg, .pdf">
                    <note>Note: Please upload only pdf, jpg, jpeg, png extension files with size 8kb to 200kb </note>
                    <?php if (form_error('upload_pan_no') != "") { ?><label class="error"><?php echo form_error('upload_pan_no'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="gst" class="control-label">GST No: <span style="color:#F00">*</span></label>
                    <div class="checkbox yes_no_checkbox" id="chk_gst_err">
                      <ul>
                        <li><label><input type="radio" required class="gst_doc" value="yes" name="chk_gst"> Yes</label></li>
                        <li><label><input type="radio" required value="no" class="gst_doc" name="chk_gst"> No</label></li>
                      </ul> 
                    </div>
                  </div>

                  <div style="display: none;" id="chk_gst_div">
                    <div class="form-group col-md-6">
                      <label for="gst_no" class="control-label">GST No.<span style="color:#F00">*</span></label>
                      <input type="text" maxlength="15" class="form-control custom_input_formfields" id="gst_no" name="gst_no" placeholder="GST No." value="<?php echo set_value('gst_no'); ?>">
                      <?php if (form_error('gst_no') != "") { ?><label class="error"><?php echo form_error('gst_no'); ?></label> <?php } ?>
                      <note>Note: Please Enter GST No like: 04ABCTY1234D8Z5</note>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="upload_gst_no" class="control-label">Upload GST Registration.<span style="color:#F00">*</span></label>
                      <input type="file" class="form-control custom_input_formfields" id="upload_gst_no" name="upload_gst_no" placeholder="Upload GST No." value="<?php echo set_value('upload_gst_no'); ?>" onchange="validate_file('upload_gst_no')" accept=".png,.jpeg,.jpg, .pdf">
                      <note>Note: Please upload only pdf, jpg, jpeg, png extension files with size 8kb to 200kb </note>
                      <?php if (form_error('upload_gst_no') != "") { ?><label class="error"><?php echo form_error('upload_gst_no'); ?></label> <?php } ?>
                    </div>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="chk_msmed" class="control-label">MSMED Registration No: <span style="color:#F00">*</span></label>
                    <div class="checkbox yes_no_checkbox" id="chk_msmed_err">
                      <ul>
                        <li><label><input type="radio" required class="msmed_doc" value="yes" name="chk_msmed"> Yes</label></li>
                        <li><label><input type="radio" value="no" required class="msmed_doc" name="chk_msmed"> No</label></li>
                      </ul>
                    </div>
                  </div>

                  <div style="display: none;" id="chk_msmed_div">
                    <div class="form-group col-md-6">
                      <label for="msmed_reg_no" class="control-label">MSMED Registration No.<span style="color:#F00">*</span></label>
                      <input type="text" class="form-control custom_input_formfields" id="msmed_reg_no" name="msmed_reg_no" placeholder="MSMED Registration No." value="<?php echo set_value('msmed_reg_no'); ?>">
                      <?php if (form_error('msmed_reg_no') != "") { ?><label class="error"><?php echo form_error('msmed_reg_no'); ?></label> <?php } ?>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="upload_msmed_reg_no" class="control-label">Upload MSMED Registration Certificate<span style="color:#F00">*</span></label>
                      <input type="file" class="form-control custom_input_formfields" id="upload_msmed_reg_no" name="upload_msmed_reg_no" placeholder="Upload MSMED Registration No." value="<?php echo set_value('upload_msmed_reg_no'); ?>" onchange="validate_file('upload_msmed_reg_no')" accept=".png,.jpeg,.jpg, .pdf">
                      <note>Note: Please upload only pdf, jpg, jpeg, png extension files with size 8kb to 200kb </note>
                      <?php if (form_error('upload_msmed_reg_no') != "") { ?><label class="error"><?php echo form_error('upload_msmed_reg_no'); ?></label> <?php } ?>
                    </div>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="chk_epfo" class="control-label">EPFO Registration No: <span style="color:#F00">*</span></label>
                    <div class="checkbox yes_no_checkbox" id="chk_epfo_err">
                      <ul>
                        <li><label><input type="radio" required class="epfo_doc" value="yes" name="chk_epfo"> Yes</label></li>
                        <li><label><input type="radio" value="no" required class="epfo_doc" name="chk_epfo"> No</label></li>
                      </ul>
                    </div>
                  </div>
                  <div style="display: none;" id="chk_epfo_div">
                    <div class="form-group col-md-6">
                      <label for="epfo_reg_no" class="control-label">EPFO Registration No.<span style="color:#F00">*</span></label>
                      <input type="text" class="form-control custom_input_formfields" id="epfo_reg_no" name="epfo_reg_no" placeholder="EPFO Registration No." value="<?php echo set_value('epfo_reg_no'); ?>">
                      <?php if (form_error('epfo_reg_no') != "") { ?><label class="error"><?php echo form_error('epfo_reg_no'); ?></label> <?php } ?>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <h1 class="heading_class">Vendors Bank Account Details</h1>
                  <div class="form-group col-md-12">
                    <label for="vendor_name_in_bank" class="control-label">Vendor Name as per Bank<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));"  maxlength="150" class="form-control custom_input_formfields" required id="vendor_name_in_bank" name="vendor_name_in_bank" placeholder="Vendor Name as per Bank" value="<?php echo set_value('vendor_name_in_bank'); ?>">
                    <?php if (form_error('vendor_name_in_bank') != "") { ?><label class="error"><?php echo form_error('vendor_name_in_bank'); ?></label> <?php } ?>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="bank_name" class="control-label">Name of the Bank<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" maxlength="150" class="form-control custom_input_formfields" required id="bank_name" name="bank_name" placeholder="Name of the Bank" value="<?php echo set_value('bank_name'); ?>">
                    <?php if (form_error('bank_name') != "") { ?><label class="error"><?php echo form_error('bank_name'); ?></label> <?php } ?>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="bank_branch_address" maxlength="200" class="control-label">Branch Address<span style="color:#F00">*</span></label>
                    <input type="text" class="form-control custom_input_formfields" required id="bank_branch_address" name="bank_branch_address" placeholder="Branch Address" value="<?php echo set_value('bank_branch_address'); ?>">
                    <?php if (form_error('bank_branch_address') != "") { ?><label class="error"><?php echo form_error('bank_branch_address'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="account_type" class="control-label">Type of Account<span style="color:#F00">*</span></label>
                    <div class="checkbox yes_no_checkbox" id="account_type_err">
                      <ul>
                        <li><label><input type="radio" name="account_type" value="SAVING" class="custom_input_formfields"> Saving</label></li>
                        <li><label><input type="radio" name="account_type" value="CURRENT" class="custom_input_formfields"> Current</label></li>
                      </ul>
                    </div>
                  </div>

                  <div class="form-group col-md-12">
                    <label for="bank_account_no" class="control-label">Bank Account No.<span style="color:#F00">*</span></label>
                    <input type="text" onkeypress="return isNumber(event)" maxlength="50" class="form-control custom_input_formfields" required id="bank_account_no" name="bank_account_no" placeholder="Bank Account No." value="<?php echo set_value('bank_account_no'); ?>">
                    <?php if (form_error('bank_account_no') != "") { ?><label class="error"><?php echo form_error('bank_account_no'); ?></label> <?php } ?>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="ifsc_code" class="control-label">IFSC Code<span style="color:#F00">*</span></label>
                    <input type="text" maxlength="11" class="form-control custom_input_formfields" required id="ifsc_code" name="ifsc_code" placeholder="IFSC Code" value="<?php echo set_value('ifsc_code'); ?>">
                    <?php if (form_error('ifsc_code') != "") { ?><label class="error"><?php echo form_error('ifsc_code'); ?></label> <?php } ?>
                    <note>Note: Please Enter IFSC Code like: SBIN0125159</note>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="micr_code" class="control-label">MICR Code of Branch<span style="color:#F00"></span></label>
                    <input type="text" onkeypress="return isNumber(event)" maxlength="9" class="form-control custom_input_formfields" id="micr_code" name="micr_code" placeholder="MICR Code of Branch" value="<?php echo set_value('micr_code'); ?>">
                    <?php if (form_error('micr_code') != "") { ?><label class="error"><?php echo form_error('micr_code'); ?></label> <?php } ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="upload_canceled_cheque" class="control-label">Upload Canceled Cheque<span style="color:#F00">*</span></label>
                    <input type="file" class="form-control custom_input_formfields" id="upload_canceled_cheque" name="upload_canceled_cheque" placeholder="Upload GST No." value="<?php echo set_value('upload_canceled_cheque'); ?>" onchange="validate_file('upload_canceled_cheque')" accept=".png,.jpeg,.jpg, .pdf">
                    <note>Note: Please upload only pdf, jpg, jpeg, png extension files with size 8kb to 200kb </note>
                    <?php if (form_error('upload_canceled_cheque') != "") { ?><label class="error"><?php echo form_error('upload_canceled_cheque'); ?></label> <?php } ?>
                  </div>

                  <div class="form-group col-md-12">
                    <div class="checkbox" id="declaration_err">
                      <label class="form-group col-md-12"><input type="checkbox" required name="declaration" id="declaration" class="custom_input_formfields"> I / We here by declare that the particulars given above are true, correct and complete. I / We undertake to keep IIBF informed of any changes in any of the above mentioned details.</label>
                    </div>
                  </div>

                  <div class="form-group col-md-6">
                    <label for="authorized_person_name" class="control-label">Name of Authorized Person<span style="color:#F00"></span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" class="form-control custom_input_formfields" id="authorized_person_name" maxlength="150" name="authorized_person_name" placeholder="Name of Authorized Person" value="<?php echo set_value('authorized_person_name'); ?>">
                    <?php if (form_error('authorized_person_name') != "") { ?><label class="error"><?php echo form_error('authorized_person_name'); ?></label> <?php } ?>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="authorized_person_designation" class="control-label">Designation<span style="color:#F00"></span></label>
                    <input type="text" onkeypress="return(alphanumeric_custom(event));" maxlength="150" class="form-control custom_input_formfields" id="authorized_person_designation" name="authorized_person_designation" placeholder="Designation" value="<?php echo set_value('authorized_person_designation'); ?>">
                    <?php if (form_error('authorized_person_designation') != "") { ?><label class="error"><?php echo form_error('authorized_person_designation'); ?></label> <?php } ?>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 text-center">
                    <input type="submit" class="btn btn-info" name="btn_Submit" id="btn_Submit" value="Submit">&nbsp;&nbsp;
                    <input type="button" class="btn btn-info" value="Cancel" onclick="window.location='<?php echo base_url('vendors/registration'); ?>'">
                  </div>
                </div>
              </form>
            </div>
          </div>
          <?php $this->load->view('vendors/inc_footerbar'); ?>
        </div>
      </section>
    </div>
  </div>

  <?php $this->load->view('vendors/inc_footer'); ?>

  <?php $this->load->view('vendors/common_validation_all'); ?>
  <script type="text/javascript">
    function validate_file(input_id) {
      $("#" + input_id).valid();
    }

    $(document).ready(function() {
      $('.person_type').click(function() {
        var type_of_person = $(this).val();
        if (type_of_person == "Company") {
          $("#company_cin").val('');
          $("#company_cin").show();
        } else {
          $("#company_cin").hide();
          $("#company_cin-error").hide(); 
        }
      });

      $('.gst_doc').click(function() {
        var chk_gst = $(this).val();
        if (chk_gst == "yes") {
          $("#chk_gst_div").show();
        } else {
          $("#chk_gst_div").hide();
        }
      });

      $('.msmed_doc').click(function() {
        var msmed_doc = $(this).val();
        if (msmed_doc == "yes") {
          $("#chk_msmed_div").show();
        } else {
          $("#chk_msmed_div").hide();
        }
      });

      $('.epfo_doc').click(function() {
        var epfo_doc = $(this).val();
        if (epfo_doc == "yes") {
          $("#chk_epfo_div").show();
        } else {
          $("#chk_epfo_div").hide();
        }
      });

      $.validator.addMethod("custom_check_pin_code_ajax", function(value, element) {
        if ($.trim(value).length == 0) {
          return true;
        } else {
          var is_Success = false;
          var datastring = 'statecode=' + $('#ccstate').val() + '&pincode=' + value;
          $.ajax({
            url: '<?php echo site_url("vendors/registration/checkpin"); ?>',
            data: datastring,
            type: 'POST',
            async: false,
            success: function(data) {
              //alert(data);
              if (data == 'true') {
                is_Success = true;
              } else {
                is_Success = false;
              }

              $.validator.messages.custom_check_pin_code_ajax = data;

            }
          });
          return is_Success;
        }
      }, '');

      $("#usersAddForm").validate({
        /*onkeyup: false,
        onclick: false,
        onblur: false,
        onfocusout: false,*/
        onkeyup: function(element) {
          $(element).valid();
          // As sparky mentions, this could cause infinite loops, should be this:
          // this.element(element);
        },
        rules: {
          full_name: {
            required: true,
            valid_vendor_name: true,
          },
          address: {
            required: true
          },
          state: {
            required: true
          },
          city: {
            required: true
          },
          pin_code: {
            required: true,
            digits: true,
            minlength: 6,
            maxlength: 6,
            custom_check_pin_code_ajax: true
          },
          type_of_person: {
            required: true
          },
          company_cin: {
            required: function(element) {
              return $("input:radio[name='type_of_person']:checked").val() == 'Company';
            }
          },
          contact_person_name: {
            valid_vendor_name: true,
          },
          designation: {
            valid_vendor_name: true,
          },
          email_id: {
            required: true,
            email: true,
            valid_email_address: true
          },
          mobile_no: {
            required: true,
            digits: true,
            minlength: 10,
            maxlength: 10
          },
          website: {
            valid_url: true,
          },
          telephone_no: {
            digits: true,
          },
          pan_no: {
            required: true,
            valid_pan_no: true,
            /*custom_check_pan_no_ajax: true, */ remote: {
              url: "<?php echo site_url('vendors/registration/validation_pan_no_exist/0/1'); ?>",
              type: "post"
            }
          },

          //upload_pan_no: { required: true, file_extension: "pdf|jpg|jpeg|png" },
          upload_pan_no: {
            required: true,
            check_valid_file: true,
            valid_file_format: ".jpg,.jpeg,.png,.pdf",
            filesize_min: 8000,
            filesize_max: 200000
          },

          chk_gst: {
            required: true
          },
          gst_no: {
            required: function(element) {
              return $("input:radio[name='chk_gst']:checked").val() == 'yes';
            },
            valid_gst_no: function(element) {
              return $("input:radio[name='chk_gst']:checked").val() == 'yes';
            } 
          },
          upload_gst_no: {
            required: function(element) {
              return $("input:radio[name='chk_gst']:checked").val() == 'yes';
            },
            check_valid_file: true,
            valid_file_format: ".jpg,.jpeg,.png,.pdf",
            filesize_min: 8000,
            filesize_max: 200000
          },

          chk_msmed: {
            required: true
          },
          msmed_reg_no: {
            required: function(element) {
              return $("input:radio[name='chk_msmed']:checked").val() == 'yes';
            },
            valid_any_reg_no: function(element) {
              return $("input:radio[name='chk_msmed']:checked").val() == 'yes';
            } 
          },
          upload_msmed_reg_no: {
            required: function(element) {
              return $("input:radio[name='chk_msmed']:checked").val() == 'yes';
            },
            check_valid_file: true,
            valid_file_format: ".jpg,.jpeg,.png,.pdf",
            filesize_min: 8000,
            filesize_max: 200000
          },

          chk_epfo: {
            required: true
          },
          epfo_reg_no: {
            required: function(element) {
              return $("input:radio[name='chk_epfo']:checked").val() == 'yes';
            },
            valid_any_reg_no: function(element) {
              return $("input:radio[name='chk_epfo']:checked").val() == 'yes';
            }
          },

          vendor_name_in_bank: {
            required: true,
            valid_vendor_name: true,
          },
          bank_name: {
            required: true,
            valid_vendor_name: true,
          },
          bank_branch_address: {
            required: true
          },
          account_type: {
            required: true
          },
          bank_account_no: {
            required: true,
            digits: true,
          },
          micr_code: { 
            minlength: 9,
            maxlength: 9, 
            digits: true,
          },
          ifsc_code: {
            required: true,
            valid_ifsc_code: true
          },

          upload_canceled_cheque: {
            required: true,
            check_valid_file: true,
            valid_file_format: ".jpg,.jpeg,.png,.pdf",
            filesize_min: 8000,
            filesize_max: 200000
          },
          declaration: {
            required: true
          },
          authorized_person_name: {
            valid_vendor_name: true,
          },
          authorized_person_designation: {
            valid_vendor_name: true,
          },
        },
        messages: {
          full_name: {
            required: "Please enter Vendor/ Trade Full Name",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          address: {
            required: "Please enter Address"
          },
          state: {
            required: "Please select state"
          },
          city: {
            required: "Please select city"
          },
          pin_code: {
            required: "Please enter Pin code",
            digits: "Please enter only numbers",
            minlength: "Please enter 6 digits pin code",
            maxlength: "Please enter 6 digits pin code",
            custom_check_pin_code_ajax: "Please enter valid Pin code"
          },
          type_of_person: {
            required: "Please select Type of Person"
          },
          company_cin: {
            required: "Please enter company CIN"
          },
          contact_person_name: {
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          designation: {
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          email_id: {
            required: "Please enter Email ID",
            email: "Please enter valid Email ID",
            valid_email_address: "Please enter valid Email ID"
          },
          mobile_no: {
            required: "Please enter Mobile No",
            digits: "Please enter only numbers",
            minlength: "Please enter 10 digits Mobile No",
            maxlength: "Please enter 10 digits Mobile No"
          },
          website: {
            valid_url: "Please enter valid url",
          },
          telephone_no: {
            digits: "Please enter only numbers",
          },
          pan_no: {
            required: "Please enter Pan No",
            valid_pan_no: "Please enter valid Pan No",
            remote: "Pan No already exist"
          },
          upload_pan_no: {
            required: "Please upload Pan No.",
            check_valid_file: "Please upload valid file",
            valid_file_format: "Please upload only .jpg,.jpeg,.png,.pdf files",
            filesize_min: "Please upload minimum 8kb file",
            filesize_max: "Please upload maximum 200kb file"
          },

          chk_gst: {
            required: "Please select GST No"
          },
          gst_no: {
            required: "Please enter GST No",
            valid_gst_no: "Please enter valid GST No",
          },
          upload_gst_no: {
            required: "Please upload GST Registration",
            check_valid_file: "Please upload valid file",
            valid_file_format: "Please upload only .jpg,.jpeg,.png,.pdf files",
            filesize_min: "Please upload minimum 8kb file",
            filesize_max: "Please upload maximum 200kb file"
          },

          chk_msmed: {
            required: "Please select MSMED Registration No"
          },
          msmed_reg_no: {
            required: "Please enter MSMED Registration No",
            valid_any_reg_no: "Only Allow Alphabets, Numbers, Dot(.),underscore ( _ ) and hyphen(-)"
          },
          upload_msmed_reg_no: {
            required: "Please upload MSMED Registration Certificate",
            check_valid_file: "Please upload valid file",
            valid_file_format: "Please upload only .jpg,.jpeg,.png,.pdf files",
            filesize_min: "Please upload minimum 8kb file",
            filesize_max: "Please upload maximum 200kb file"
          },

          chk_epfo: {
            required: "Please select EPFO Registration No"
          },
          epfo_reg_no: {
            required: "Please enter EPFO Registration No",
            valid_any_reg_no: "Only Allow Alphabets, Numbers, Dot(.),underscore ( _ ) and hyphen(-)"
          },

          vendor_name_in_bank: {
            required: "Please enter Vendor Name as per Bank",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          bank_name: {
            required: "Please enter Name of the Bank",
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          bank_branch_address: {
            required: "Please enter Branch Address"
          },
          account_type: {
            required: "Please select Type of Account"
          },
          bank_account_no: {
            required: "Please enter Bank Account No",
            digits: "Please enter only numbers",
          },
          micr_code: { 
            minlength: "Please enter 9 digits MICR Code",
            maxlength: "Please enter 9 digits MICR Code",
            digits: "Please enter only numbers",
          },
          ifsc_code: {
            required: "Please enter IFSC Code",
            valid_ifsc_code: "Please enter valid IFSC Code",
          },

          upload_canceled_cheque: {
            required: "Please Upload Canceled Cheque",
            check_valid_file: "Please upload valid file",
            valid_file_format: "Please upload only .jpg,.jpeg,.png,.pdf files",
            filesize_min: "Please upload minimum 8kb file",
            filesize_max: "Please upload maximum 200kb file"
          },
          declaration: {
            required: "Please accept the declaration"
          },
          authorized_person_name: {
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
          authorized_person_designation: {
            valid_vendor_name: "Only Allow Alphabets, Numbers, Dot(.) and forward slash(/)",
          },
        },
        errorPlacement: function(error, element) {
          if (element.attr("name") == "type_of_person") {
            error.insertAfter("#type_of_person_err");
          } else if (element.attr("name") == "chk_gst") {
            error.insertAfter("#chk_gst_err");
          } else if (element.attr("name") == "chk_msmed") {
            error.insertAfter("#chk_msmed_err");
          } else if (element.attr("name") == "chk_epfo") {
            error.insertAfter("#chk_epfo_err");
          } else if (element.attr("name") == "account_type") {
            error.insertAfter("#account_type_err");
          } else if (element.attr("name") == "declaration") {
            error.insertAfter("#declaration_err");
          } else {
            error.insertAfter(element);
          }
        }

      });
    });


    function get_city_ajax() {
      var state_code = $("#ccstate").val();
      if (state_code) {
        $.ajax({
          type: 'POST',
          url: '<?php echo site_url("vendors/registration/getCity"); ?>',
          data: 'state_code=' + state_code,
          success: function(html) {
            //alert(html);
            $('#city').show();
            $('#city').html(html);
          }
        });
      } else {
        $('#city').html('<option value="">Select State First</option>');
      }
    }

    function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
      }
      return true;
    }

    function onlyAlphabets(key) {
      var keycode = (key.which) ? key.which : key.keyCode;
      //alert(keycode);
      if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 32) {
        return true;
      } else {
        return false;
      }
    }

    function alphanumeric(key) {
      var keycode = (key.which) ? key.which : key.keyCode;

      if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 8 || keycode == 32 || (keycode >= 48 && keycode <= 57)) {
        return true;
      } else {
        return false;
      }
    } 

    function alphanumeric_custom(key) {
      var keycode = (key.which) ? key.which : key.keyCode;

        try {  
            if ((keycode > 64 && keycode < 91) || (keycode > 96 && keycode < 123) || keycode == 8 || keycode == 32 || keycode == 46 || (keycode >= 48 && keycode <= 57) || (keycode > 46 && keycode < 58))
                return true; 
            else
                return false;
        }
        catch (err) {
            //alert(err.Description);
          return false;
        }
    }

    $(document).ready(function() {
      $('.loading').delay(0).fadeOut('slow');
    });
  </script>
</body>

</html>