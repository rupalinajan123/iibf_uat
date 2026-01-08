<link href="<?php echo base_url();?>assets/css/wizard.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/js/wizard.js"></script>
<link href="<?php echo base_url();?>assets/css/center_add.css" rel="stylesheet">
<style>
.form-group ul li.parsley-required {
  color: #F00 !important;
  float: left;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<form class="form-horizontal demo-form" name="frmDrA" id="frmDrA" method="post" action="<?php echo base_url();?>DraRegister/make_payment" enctype="multipart/form-data" data-parsley-validate="parsley">
  <div class="container">
    <section class="content-header">
      <h1 class="register"> Application for DRA Agency </h1>
      <br/>
    </section>
    <div class="stepwizard">
      <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-default btn-circle" disabled="disabled" id="step_1"><i class="fa fa-university" aria-hidden="true"></i></a>
          <p class="mb-0">01</p>
          <span class="step_ttl">agency basic details</span> </div>
        <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-default btn-circle" disabled="disabled" id="step_2"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
          <p class="mb-0">02</p>
          <span class="step_ttl">Accreditation Details (Centre Details) </span> </div>
        <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-default btn-circle" disabled="disabled" id="step_3"><i class="fa fa-search" aria-hidden="true"></i></a>
          <p class="mb-0">03</p>
          <span class="step_ttl">review details</span> </div>
        <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-primary btn-circle" id="step_4"><i class="fa fa-money" aria-hidden="true"></i></a>
          <p class="mb-0">04</p>
          <span class="step_ttl">Payment details</span> </div>

      </div>
    </div>
    <section class="content step_form">
      <div class="row">
        <div class="col-sm-12"> 
          <!-- Basic Details box Start-->
          <div class="box box-info">
            <div class="box-body">

              <?php 
                $totalAmount = base64_decode($tot_fee); // Total amount including GST
                $gstRate     = 18; // GST rate in percentage

                // Calculate the base amount
                $baseAmount = $totalAmount / (1 + ($gstRate / 100));

                $baseAmount = number_format((float)$baseAmount,2,'.','')
              ?>   

              <div class="form-group">
                <label for="institute_name" class="col-sm-4 control-label">Base Amount</label>
                <div class="col-md-8">
                  <?php echo 'Rs. '. $baseAmount;?>
                </div>
              </div>

              <input type="hidden" name="tds_amount" id="tds_amount" value="" />
              <input type="hidden" name="gst_amount" id="gst_amount" value="" />
              <input type="hidden" name="final_amount" id="final_amount" value="<?php echo $tot_fee; ?>" />
              <input type="hidden" name="base_fee" value="<?php echo base64_encode($baseAmount); ?>" />
              
              <div class="form-group">
                    <label for="institute_name" class="col-sm-4 control-label">IT TDS, if any *</label>
                    <div class="row">
                        <div class="col-sm-1">
                            <input class="tds-btn" type="radio" name="TDS" value="Yes" required>Yes
                        </div>
                        <div class="col-sm-1">
                            <input class="tds-btn" type="radio" name="TDS" value="No" checked required>No
                        </div>
                    </div>
                </div>

                <div class="form-group tds-section" style="display:none;">
                    <label for="institute_name" class="col-sm-4 control-label">IT TDS Percentage *</label>
                    <div class="row">
                        <div class="col-sm-4">
                            <select class="form-control" name="tds_type" id="tds_type">
                                <option value="">Select IT TDS Percentage</option>
                                <option value="10">10%</option>
                                <option value="2">2%</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group tds-section" style="display:none;">
                    <label for="institute_name" class="col-sm-4 control-label">IT TDS Amount </label>
                    <div class="col-sm-8 tds-amount">
                       Rs. 0.00
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="institute_name" class="col-sm-4 control-label">GST TDS, if any</label>
                    <div class="row">
                        <div class="col-sm-1">
                            <input type="radio" name="GST" value="Yes" disabled>Yes
                        </div>
                        <div class="col-sm-1">
                            <input type="radio" name="GST" value="No" checked disabled>No
                        </div>
                    </div>
                </div> 

                <div class="form-group">
                    <label for="institute_name" class="col-sm-4 control-label">Final amount to be paid</label>
                    <div class="col-sm-8 final-amount">
                        <?php echo 'Rs. '. number_format((float)base64_decode($tot_fee), 2, '.','');?>
                    </div>
                </div>
                <div class="form-group">
                  <label for="institute_name" class="col-sm-4 control-label"></label>
                <input type="submit" class="btn btn-info" name="btnSubmit1" id="btnSubmit1" value="Proceed To Pay">
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</form>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 

<script type="text/javascript">
  var baseAmount  = '<?php echo $baseAmount; ?>';
    var totalAmount = '<?php echo $totalAmount; ?>';
    var gstRate     = 18;

    baseAmount  = parseFloat(baseAmount);
    totalAmount = parseFloat(totalAmount).toFixed(2);

    var gstAmount   = (baseAmount * gstRate) / 100;
    $('#gst_amount').val(btoa(gstAmount.toFixed(2)));

  $( document ).ready(function() 
  {
    // Event listener for TDS radio buttons
    $('input[name="TDS"]').on('change', function () {
        if ($(this).val() === "Yes") {
            $('.tds-section').show(); // Show the .tds-section field
            $('#tds_type').attr('required',true);
            $('#tds_type').val('');
        } else {
            $('.tds-section').hide(); // Hide the .tds-section field
            $('#tds_type').attr('required',false); 

            $('#final_amount').val(btoa(totalAmount)); // Reset final amount
            $('.final-amount').text('Rs. '+totalAmount); 
            $('#tds_amount').val('0');
            $('.tds-amount').text('Rs. '+'0');
        }
    });

    // Calculate TDS and update the amounts
    $('#tds_type').on('change', function () {
        var tdsRate = parseFloat($(this).val()); // Get the selected TDS percentage
        
        if (!isNaN(tdsRate)) {
            var tdsAmount = (baseAmount * tdsRate) / 100; // Calculate TDS amount  
            // var finalAmount = baseAmount - tdsAmount; // Calculate final amount after TDS deduction

            var finalAmount = baseAmount + gstAmount - tdsAmount; // Calculate final amount after TDS deduction
                finalAmount = finalAmount.toFixed(2);

                tdsAmount   = tdsAmount.toFixed(2);

            $('.tds-amount').text('Rs. '+tdsAmount);               
            $('#tds_amount').val(btoa(tdsAmount)); // Update TDS amount 
            $('#final_amount').val(btoa(finalAmount)); // Update final amount
            $('.final-amount').text('Rs. '+finalAmount);

        } else {
            $('#tds_amount').val('0'); // Reset TDS amount if no valid TDS rate
            $('.tds-amount').text('Rs. '+'0'); 
            $('#final_amount').val(btoa(totalAmount)); // Reset final amount
            $('.final-amount').text('Rs. '+totalAmount);    
        }
    });
  });
</script>
