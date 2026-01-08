<!DOCTYPE html>
<html>

<head>
  <?php $this->load->view('institute_subscription/inc_header'); ?>
  <style>
    .table tr td { border:1px solid #ccc !important; }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    <?php $this->load->view('institute_subscription/inc_navbar'); ?>

    <div class="loading" style="display:none;"><img src="<?php echo base_url(); ?>assets/images/loading.gif"></div>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>Welcome Institute <span style="font-size: 20px; text-transform: capitalize; ">(<?php echo strtolower($institute_name); ?>)</span></h1>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-info">
              <div class="box-header with-border">
                <div style="float:right;">
                </div>
                <h3 class="box-title"></h3>
              </div>
              <div class="box-body">

                <?php
                if ($this->session->flashdata('error') != '')
                { ?>
                  <div class="alert alert-danger alert-dismissible" id="error_id">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('error'); ?>
                  </div>
                <?php }

                if ($this->session->flashdata('success') != '')
                { ?>
                  <div class="alert alert-success alert-dismissible" id="success_id">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('success'); ?>
                  </div>
                <?php }  ?>

                <form class="form-horizontal" name="institute_subscription" id="institute_subscription" method="post" enctype="multipart/form-data" action="<?php echo site_url('institute_subscription/dashboard'); ?>">
                  <input type="hidden" name="institute_subscription_form" id="institute_subscription_form" value="1">
                  <input type="hidden" name="subscription_base_amount" id="subscription_base_amount" value="<?php echo $subscription_base_amount; ?>">
                  <input type="hidden" name="subscription_gst_amount" id="subscription_gst_amount" value="<?php echo $subscription_gst_amount; ?>">
                  <input type="hidden" name="final_paid_amount" id="final_paid_amount" value="">
                  <div class="table-responsive">
                    <table class="table table-bordered" style="max-width:800px; margin:20px auto;">
                      <tbody>
                        <tr>
                          <td style="width:200px;"><strong>Institute No </strong></td>
                          <td><?php echo $institute_no; ?></td>
                        </tr>
                        <tr>
                          <td><strong>Invoice No </strong></td>
                          <td><?php echo $invoice_no; ?></td>
                        </tr>
                        <tr>
                          <td><strong>Institute Name </strong></td>
                          <td><?php echo $institute_name; ?></td>
                        </tr>
                        
                        <?php
                        $show_payment_btn = 0;
                        if ($subscription_base_amount > 0)
                        {
                          //echo '<pre>'; print_r($last_payment_data); echo '</pre>';
                          $current_date = date('Y-m-d');
                          $start_date = $end_date = '';
                          if (count($last_payment_data) > 0)
                          {
                            $explode_year = explode("-", $last_payment_data[0]['subscription_year']);
                            $start_date = $explode_year[0] . '-04-01';
                            $end_date = $explode_year[1] . '-03-31';
                          }
                          
                          if (count($last_payment_data) == 0)
                          {
                            $show_payment_btn = 1;
                          }
                          else
                          {
                            if ($current_date >= $start_date && $current_date <= $end_date)
                            {
                            }
                            else
                            {
                              $show_payment_btn = 1;
                            }
                          }                          
                        } 
                        
                        if ($show_payment_btn == '1')
                        {  ?>
                          <tr>
                            <td><strong>Subscription Year </strong></td>
                            <td><?php echo $subscription_year; ?></td>
                          </tr>
                          <tr>
                            <td><strong>Subscription Base Amount </strong></td>
                            <td><?php echo 'Rs. '.$subscription_base_amount.' /-'; ?></td>
                          </tr>
                          <tr>
                            <td><strong>Subscription GST Amount </strong></td>
                            <td><?php echo 'Rs. '.$subscription_gst_amount.' /-'; ?></td>
                          </tr>
                          
                          <tr>
                            <td><strong>IT TDS, if any <span class="text-danger">*</span></strong></td>
                            <td>
                              <div id="is_it_tds_applicable_err">
                                <label class="" style="margin-right:20px; cursor:pointer;">
                                  <input type="radio" value="yes" name="is_it_tds_applicable" id="is_it_tds_applicable_yes" onchange="show_hide_tds_amt();" required style="margin: 0 2px 0 0;top: 1px; cursor:pointer;" <?php if(set_value('is_it_tds_applicable') == 'yes') { echo "checked"; } ?>>
                                  Yes
                                </label>
                                <label class="" style="cursor:pointer;">
                                  <input type="radio" value="no" name="is_it_tds_applicable" id="is_it_tds_applicable_no" onchange="show_hide_tds_amt();" required style="margin: 0 2px 0 0;top: 1px; cursor:pointer;" <?php if(set_value('is_it_tds_applicable') == 'no') { echo "checked"; } ?>>
                                  No
                                </label>
                              </div> 
                              <?php if (form_error('is_it_tds_applicable') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('is_it_tds_applicable'); ?></label> <?php } ?>
                            </td>
                          </tr>

                          <tr class="it_tds_applicable_yes" style="display:none;">
                            <td><strong>IT TDS Percentage <span class="text-danger">*</span></strong></td>
                            <td>
                              <select name="it_tds_percentage_rate" id="it_tds_percentage_rate" class="form-control" onchange="show_hide_tds_amt()" required style="max-width:200px;">
                                <option value="">Select IT TDS Percentage</option>
                                <option value="2">2 %</option>
                                <option value="10">10 %</option>
                              </select>                              
                              <?php if (form_error('it_tds_percentage_rate') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('it_tds_percentage_rate'); ?></label> <?php } ?>
                            </td>
                          </tr>

                          <tr class="it_tds_applicable_yes" style="display:none;">
                            <td><strong>IT TDS Amount <span class="text-danger">*</span></strong></td>
                            <td>
                              <input type="text" class="form-control" value="0" name="it_tds_percentage_amount" id="it_tds_percentage_amount" readonly style="max-width:200px;">
                              <small class="text-primary">Note : IT TDS amount is calculated based on the subscription's base amount.</small>
                            </td>
                          </tr>
                          
                          <tr>
                            <td><strong>GST TDS, if any <span class="text-danger">*</span></strong></td>
                            <td>
                              <div id="is_gst_tds_applicable_err">
                                <label class="" style="margin-right:20px; cursor:pointer;">
                                  <input type="radio" value="yes" name="is_gst_tds_applicable" id="is_gst_tds_applicable_yes" onchange="show_hide_tds_amt();" required style="margin: 0 2px 0 0;top: 1px; cursor:pointer;" <?php if(set_value('is_gst_tds_applicable') == 'yes') { echo "checked"; } ?>>
                                  Yes
                                </label>
                                <label class="" style="cursor:pointer;">
                                  <input type="radio" value="no" name="is_gst_tds_applicable" id="is_gst_tds_applicable_no" onchange="show_hide_tds_amt();" required style="margin: 0 2px 0 0;top: 1px; cursor:pointer;" <?php if(set_value('is_gst_tds_applicable') == 'no') { echo "checked"; } ?>>
                                  No
                                </label>
                              </div> 
                              <?php if (form_error('is_gst_tds_applicable') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('is_gst_tds_applicable'); ?></label> <?php } ?>
                            </td>
                          </tr>

                          <tr class="gst_tds_applicable_yes" style="display:none;">
                            <td><strong>GST TDS Percentage <span class="text-danger">*</span></strong></td>
                            <td>
                              <select name="gst_tds_percentage_rate" id="gst_tds_percentage_rate" class="form-control" onchange="show_hide_tds_amt()" required style="max-width:200px;">
                                <option value="">Select GST TDS Percentage</option>
                                <option value="2">2 %</option>
                              </select>                              
                              <?php if (form_error('gst_tds_percentage_rate') != "") { ?> <div class="clearfix"></div><label class="error"><?php echo form_error('gst_tds_percentage_rate'); ?></label> <?php } ?>
                            </td>
                          </tr>

                          <tr class="gst_tds_applicable_yes" style="display:none;">
                            <td><strong>GST TDS Amount <span class="text-danger">*</span></strong></td>
                            <td>
                              <input type="text" class="form-control" value="0" name="gst_tds_percentage_amount" id="gst_tds_percentage_amount" readonly style="max-width:200px;">
                              <small class="text-primary">Note : GST TDS amount is calculated based on the subscription's base amount.</small>
                            </td>
                          </tr>
                          
                          <tr class="amount_to_be_paid_outer" style="display:none;">
                            <td><strong>Final amount to be paid </strong></td>
                            <td><span id="final_amount_to_be_paid_outer"><?php echo $amount; ?></span></td>
                          </tr>

                          <tr>
                            <td><strong>Invoice </strong></td>
                            <td><a href="<?php echo site_url('institute_subscription/invoice'); ?>" class="btn btn-success btn-flat btn-sm" target="_blank">View</a></td>
                          </tr>
                          <tr>
                            <td colspan="2" class="text-center">
                              <button type="submit" class="btn btn-info btn-flat" name="institute_subscription">Pay Now</button>
                            </td>
                          </tr>
                        <?php }
                        else if ($show_payment_btn == '0' && count($last_payment_data) > 0)
                        {  ?>
                          <tr>
                            <td><strong>Subscription Year </strong></td>
                            <td><?php echo $last_payment_data[0]['subscription_year']; ?></td>
                          </tr>

                          <tr>
                            <td><strong>Subscription Base Amount </strong></td>
                            <td><?php echo "Rs. ".$last_payment_data[0]['subscription_base_amount'].' /-'; ?></td>
                          </tr>

                          <tr>
                            <td><strong>Subscription GST Amount </strong></td>
                            <td><?php echo "Rs. ".$last_payment_data[0]['subscription_gst_amount'].' /-'; ?></td>
                          </tr>

                          <tr>
                            <td><strong>IT TDS, if any </strong></td>
                            <td><?php echo strtoupper($last_payment_data[0]['is_it_tds_applicable']); ?></td>
                          </tr>

                          <?php if($last_payment_data[0]['is_it_tds_applicable'] == 'yes')
                          { ?>
                            <tr>
                              <td><strong>IT TDS Percentage </strong></td>
                              <td><?php echo $last_payment_data[0]['it_tds_percentage_rate'].' %'; ?></td>
                            </tr>

                            <tr>
                              <td><strong>IT TDS Amount </strong></td>
                              <td><?php echo "Rs. ".$last_payment_data[0]['it_tds_percentage_amount'].' /-'; ?></td>
                            </tr>
                          <?php } ?>

                          <tr>
                            <td><strong>GST TDS, if any </strong></td>
                            <td><?php echo strtoupper($last_payment_data[0]['is_gst_tds_applicable']); ?></td>
                          </tr>
                          
                          <?php if($last_payment_data[0]['is_gst_tds_applicable'] == 'yes')
                          { ?>
                            <tr>
                              <td><strong>GST TDS Percentage </strong></td>
                              <td><?php echo $last_payment_data[0]['gst_tds_percentage_rate'].' %'; ?></td>
                            </tr>

                            <tr>
                              <td><strong>GST TDS Amount </strong></td>
                              <td><?php echo "Rs. ".$last_payment_data[0]['gst_tds_percentage_amount'].' /-'; ?></td>
                            </tr>
                            <?php } ?>

                          <tr>
                            <td><strong>Final paid amount </strong></td>
                            <td><?php echo "Rs. ".$last_payment_data[0]['amount'].' /-'; ?></td>
                          </tr>

                          <tr>
                            <td><strong>Invoice </strong></td>
                            <td><a href="<?php echo site_url('institute_subscription/invoice/' . base64_encode($last_payment_data[0]['receipt_no'])); ?>" class="btn btn-success btn-flat btn-sm" target="_blank">View</a></td>
                          </tr>
                          <tr>
                            <td><strong>Payment Date </strong></td>
                            <td><?php echo $last_payment_data[0]['date']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>Payment Gateway </strong></td>
                            <td><?php echo $last_payment_data[0]['gateway']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>Transaction Number </strong></td>
                            <td><?php echo $last_payment_data[0]['transaction_no']; ?></td>
                          </tr>
                          <tr>
                            <td><strong>Receipt </strong></td>
                            <td><a href="<?php echo site_url('institute_subscription/receipt/' . base64_encode($last_payment_data[0]['receipt_no'])); ?>" class="btn btn-success btn-flat btn-sm" target="_blank">View</a></td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <?php $this->load->view('institute_subscription/inc_footer_text'); ?>
  </div>
  <?php $this->load->view('institute_subscription/inc_footer'); ?>

  <script src="<?php echo base_url() ?>js/jquery.validate.min.js"></script>
  <?php $this->load->view('apply_elearning/common_validation_all'); ?>

  <script type="text/javascript">
    show_hide_tds_amt();
    function show_hide_tds_amt()
    {      
      var subscription_base_amount = "<?php echo $subscription_base_amount; ?>";
      var subscription_gst_amount = "<?php echo $subscription_gst_amount; ?>";
      
      var final_paid_amount = parseFloat(subscription_base_amount) + parseFloat(subscription_gst_amount);
      var display_amount_msg = 'Subscription Base Amount + Subscription GST Amount';
      
      //START : IT TDS PERCENTAGE
      var is_it_tds_applicable = $('input[name="is_it_tds_applicable"]:checked').val();
      if(is_it_tds_applicable == 'yes')
      {
        $(".it_tds_applicable_yes").show();
        var it_tds_percentage_rate = $("#it_tds_percentage_rate").val();        
        var it_tds_percentage_amount = 0;
        if ($.isNumeric(it_tds_percentage_rate)) 
        { 
          it_tds_percentage_amount = parseFloat(subscription_base_amount) * (parseFloat(it_tds_percentage_rate)/100);
          final_paid_amount = parseFloat(final_paid_amount) - parseFloat(it_tds_percentage_amount); 
          display_amount_msg += ' - IT TDS Amount';
        }

        $("#it_tds_percentage_amount").val(it_tds_percentage_amount);
      }
      else if(is_it_tds_applicable == 'no')
      {
        $(".it_tds_applicable_yes").hide();
        $("#it_tds_percentage_rate").val("");        
        $("#it_tds_percentage_amount").val("0");        
      }//END : IT TDS PERCENTAGE

      //START : GST TDS PERCENTAGE
      var is_gst_tds_applicable = $('input[name="is_gst_tds_applicable"]:checked').val();
      if(is_gst_tds_applicable == 'yes')
      {
        $(".gst_tds_applicable_yes").show();
        var gst_tds_percentage_rate = $("#gst_tds_percentage_rate").val();        
        var gst_tds_percentage_amount = 0;
        if ($.isNumeric(gst_tds_percentage_rate)) 
        { 
          gst_tds_percentage_amount = parseFloat(subscription_base_amount) * (parseFloat(gst_tds_percentage_rate)/100);
          final_paid_amount = parseFloat(final_paid_amount) - parseFloat(gst_tds_percentage_amount); 
          display_amount_msg += ' - GST TDS Amount';
        }

        $("#gst_tds_percentage_amount").val(gst_tds_percentage_amount);
      }
      else if(is_gst_tds_applicable == 'no')
      {
        $(".gst_tds_applicable_yes").hide();
        $("#gst_tds_percentage_rate").val("");        
        $("#gst_tds_percentage_amount").val("0");        
      }//END : GST TDS PERCENTAGE

      $(".amount_to_be_paid_outer").show();
      $("#final_amount_to_be_paid_outer").html('Rs. '+final_paid_amount+' /- ('+display_amount_msg+')');
      $("#final_paid_amount").val(final_paid_amount);
    }
    
    $(document).ready(function()
    {
      /* $.validator.addMethod('chkGreaterThan', function (value, el, param) { return parseFloat(value) > parseFloat(param); });
      $.validator.addMethod('chkLessThan', function (value, el, param) { return parseFloat(value) < parseFloat(param); }); */

      $("#institute_subscription").validate(
      {
        onkeyup: function(element) { $(element).valid(); },  
        rules: 
        {
          is_it_tds_applicable: { required: true },
          is_gst_tds_applicable: { required: true },
          it_tds_percentage_rate: { required: true },
          gst_tds_percentage_rate: { required: true },
          /* tds_amount: { required: true, number:true, chkGreaterThan:0, chkLessThan:"<?php echo $amount; ?>" }, */
        },
        messages: 
        {
          is_it_tds_applicable:{ required:"Please select the IT TDS option", },
          is_gst_tds_applicable:{ required:"Please select the GST TDS option", },
          it_tds_percentage_rate:{ required:"Please select the TDS Percentage" },
          gst_tds_percentage_rate:{ required:"Please select the TDS Percentage" },
          /* tds_amount:{ required:"Please enter the TDS amount", chkGreaterThan:"Please enter the TDS amount greater than 0", chkLessThan:"Please enter the TDS amount less than <?php echo $amount; ?>" }, */
        },
        errorPlacement: function(error, element) // For replace error 
        {
          if (element.attr("name") == "is_it_tds_applicable") { error.insertAfter("#is_it_tds_applicable_err"); }
          else if (element.attr("name") == "is_gst_tds_applicable") { error.insertAfter("#is_gst_tds_applicable_err"); }
          else { error.insertAfter(element); }
        },          
        submitHandler: function(form) 
        {          
          var confirmed = confirm("Are you sure you want to proceed with the payment?");
          if (confirmed) 
          {
            form.submit();
          }
        }
      });
    });
  </script>
</body>

</html>