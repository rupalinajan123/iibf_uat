<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Example</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1></h1>
    </section>
    <section class="content">
        <div class="row" style="margin-bottom: 10%;">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Online Payment Information</h3>
                    </div>
                    <!-- /.box-header -->

                    <?php
                    $totalAmount = base64_decode($tot_fee); // Total amount including GST
                    $gstRate = 18; // GST rate in percentage

                    // Calculate the base amount
                    $baseAmount = $totalAmount / (1 + ($gstRate / 100));

                    $baseAmount = number_format((float)$baseAmount, 2, '.', '')
                    ?>

                    <!-- form start -->
                    <div class="box-body">
                        <div class="col-md-12">
                            <?php if ($this->session->flashdata('error') != '') { ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php }
                            if ($this->session->flashdata('success') != '') { ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php } ?>
                            <form action="<?php echo base_url() ?>bulk/BulkTransaction_test/make_payment" method="post" class="form-horizontal" name="neft_pay_form" id="neft_pay_form" autocomplete="off">
                                <div class="form-group">
                                    <?php
                                    // print_r($gstin_no);die;
                                    $instdata = $this->session->userdata('institute_name');
                                    $inst_code = $this->session->userdata('institute_id'); ?>
                                    <label for="institute_name" class="col-sm-4 control-label">Institute Name</label>
                                    <div class="col-sm-8">
                                        <?php echo $instdata; ?>
                                        <input type="hidden" class="form-control" id="institute_code" name="institute_code" value="<?php echo $inst_code; ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">Exam Code & Exam Period</label>
                                    <div class="col-sm-8">
                                        <?php echo base64_decode($exam_code) . ' & ' . base64_decode($exam_period); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">Base Amount</label>
                                    <div class="col-sm-8">
                                        <?php echo 'Rs. ' . number_format((float)$baseAmount, 2, '.', ''); ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="utr_slip" class="col-sm-4 control-label"> Select GST No to be displayed on Invoice *</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="centerid" name="center_id" required>
                                            <option value="">Select</option>
                                            <option value="-">NA</option>

                                            <?php
                                            if (!empty($gstin_no)) {
                                                if($gstin_no  == "" || $gstin_no == NULL)
                                                {
                                                ?>
                                                <option value="-" disabled="disabled">Institute GSTIN-(No GSTIN Number)</option>
                                                <?php }else{ ?>
                                                <option value="Institute">Institute GSTIN - <?php echo $gstin_no; ?></option>
                                                <?php } ?>
                                                
                                            <?php
                                                /*foreach($inst_registration_info as $values)
                                                { 
                                                 if($values['gstin_no'] == "" || $values['gstin_no']== NULL)
                                                    {
                                                    ?>
                                                    <option value="-" disabled="disabled">Institute GSTIN-(No GSTIN Number)</option>
                                                   <?php }else{ ?>
                                                    <option value="Institute">Institute GSTIN - <?php echo $gstin_no; ?></option>
                                                <?php }
                                                }*/
                                            } ?>


                                            <?php $validity_to = $validity_from = '';
                                            if (!empty($agency_center)) 
                                            {
                                                /*
                                                foreach ($agency_center as $val) {

                                                    if ($val['city_name'] != "" || $val['city_name'] != NULL) {
                                                        $val['location_name'] = $val['city_name'];
                                                    } else {
                                                        $val['location_name'] = $val['location_name'];
                                                    }

                                                    if ($val['renew_pay_status'] != "") 
                                                    { //not empty
                                                        if (($val['renew_type'] == 'free' && $val['center_type'] == 'R') || ($val['renew_pay_status'] == 1 && $val['center_type'] == 'R')) 
                                                        { ?>

                                                            <!-- <option  <?php //echo (set_value('center_id')==$val['center_id'])?" selected=' selected'":""
                                                                ?>   value="<?php //echo $val['center_id']; 
                                                                  ?>"><?php //echo $val['location_name']; 
                                                                ?><?php //echo set_value('location_name'); 
                                                                ?> </option> -->
                                                                <?php
                                                                //////////////////////////


                                                            if ($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status'] == 1) {
                                                                $_SESSION['validity_to'] = $val['center_validity_to']; ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"> <?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( The accreditation period is not defined for this centre, please contact admin. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 0) {


                                                            ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' (The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d') && $val['gstin_no'] == '' || $val['gstin_no'] == NULL) { ?>

                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'] . '(No GSTIN Number)'; ?></option>

                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d')) {
                                                                $validity_to = $val['center_validity_to'];
                                                                $validity_from = (date('Y-m-d', strtotime("+6 day", strtotime($val['center_validity_from']))));

                                                            ?>

                                                                <option <?php echo (set_value('center_id') == $val['gstin_no'] . '_' . $val['center_id']) ? " selected=' selected'" : "" ?> value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>"><?php echo $val['location_name']; ?> - <?php echo $val['gstin_no']; ?> </option>

                                                            <?php } elseif ($val['center_validity_to'] < date('Y-m-d')  && $val['pay_status'] == 1) { ?>

                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'R') { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'IR') { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( IN Review Process. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'AR') { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Approve by Recommender. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 2) { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Approve by Approver. )'; ?></option>
                                                            <?php } elseif ($val['center_validity_from'] > date('Y-m-d')) { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Your Accreditation period is not started. )'; ?></option>
                                                            <?php }

                                                            /////////////////////////////////////////////////////////////////

                                                        } elseif (($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'R' && $val['renew_type'] != 'free') 
                                                        { ?>
                                                            <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"> <?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Your renewal process payment is pending. )'; ?><?php echo set_value('location_name'); ?></option>
                                                            <?php } elseif (($val['renew_pay_status'] == 0 || $val['renew_pay_status'] == 2) && $val['center_type'] == 'T') { ?>
                                                            <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"> <?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Your renewal process payment is pending. )'; ?></option>
                                                            <?php } elseif ($val['renew_pay_status'] == 1 && $val['center_type'] == 'T') {

                                                            if ($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status'] == 1) {
                                                                $_SESSION['validity_to'] = $val['center_validity_to']; ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"> <?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( The accreditation period is not defined for this centre, please contact admin. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 0) {


                                                            ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' (The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d') && $val['gstin_no'] == '' || $val['gstin_no'] == NULL) { ?>

                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . '(No GSTIN Number)'; ?></option>

                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d')) {
                                                                $validity_to = $val['center_validity_to'];
                                                                $validity_from = (date('Y-m-d', strtotime("+6 day", strtotime($val['center_validity_from']))));

                                                            ?>

                                                                <option <?php echo (set_value('center_id') == $val['gstin_no'] . '_' . $val['center_id']) ? " selected=' selected'" : "" ?> value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>"><?php echo $val['location_name']; ?> - <?php echo $val['gstin_no']; ?></option>

                                                            <?php } elseif ($val['center_validity_to'] < date('Y-m-d')  && $val['pay_status'] == 1) { ?>

                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'R') { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'IR') { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( IN Review Process. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'AR') { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Approve by Recommender. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 2) { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Approve by Approver. )'; ?></option>
                                                            <?php } elseif ($val['center_validity_from'] > date('Y-m-d')) { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Your Accreditation period is not started. )'; ?></option>
                                                            <?php }

                                                            ?>

                                                            <?php }
                                                       } //not empty renew payemnt status
                                                        else 
                                                        { //empty renew payemnt status



                                                            if ($val['center_validity_from'] == '' && $val['center_validity_to'] == '' && $val['pay_status'] == 1) {
                                                                $_SESSION['validity_to'] = $val['center_validity_to']; ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"> <?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . '( The accreditation period is not defined for this centre, please contact admin. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 0) {


                                                            ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . '(The selected centre is approved by the admin, kindly make the payment for this centre in order to add the batch. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d') && $val['gstin_no'] == '' || $val['gstin_no'] == NULL) { ?>

                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'] . '(No GSTIN Number)'; ?></option>

                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 1 && $val['center_validity_to'] > date('Y-m-d') && $val['center_validity_from'] <= date('Y-m-d')) {
                                                                $validity_to = $val['center_validity_to'];
                                                                $validity_from = (date('Y-m-d', strtotime("+6 day", strtotime($val['center_validity_from']))));

                                                            ?>

                                                                <option <?php echo (set_value('center_id') == $val['gstin_no'] . '_' . $val['center_id']) ? " selected=' selected'" : "" ?> value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>"><?php echo $val['location_name']; ?> - <?php echo $val['gstin_no']; ?></option>

                                                            <?php } elseif ($val['center_validity_to'] < date('Y-m-d')  && $val['pay_status'] == 1) { ?>

                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id']; ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( The accreditation period for the selected centre is expired, please contact admin. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'R') { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( The selected centre is rejected by the admin, please check the centre status in centre list or else contact admin. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'IR') { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( IN Review Process. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'AR') { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Approve by Recommender. )'; ?></option>
                                                            <?php } elseif ($val['center_status'] == 'A' && $val['pay_status'] == 2) { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Approve by Approver. )'; ?></option>
                                                            <?php } elseif ($val['center_validity_from'] > date('Y-m-d')) { ?>
                                                                <option value="<?php echo $val['gstin_no'] . '_' . $val['center_id'] ?>" disabled="disabled"><?php echo $val['location_name'] . ' - ' . $val['gstin_no'] . ' ( Your Accreditation period is not started. )'; ?></option>
                                                            <?php }

                                                            ?>
                                                            <?php
                                                        }
                                                    }
                                                */
                                            }
                                            ?>

                                        </select>

                                    </div>
                                </div>


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
                                        <?php echo 'Rs. ' . number_format((float)base64_decode($tot_fee), 2, '.', ''); ?>
                                    </div>
                                </div>

                                <input type="hidden" name="pay_id" id="pay_id" value="<?php echo $payTransId; ?>" />
                                <input type="hidden" name="tds_amount" id="tds_amount" value="" />
                                <input type="hidden" name="gst_amount" id="gst_amount" value="" />
                                <input type="hidden" name="final_amount" id="final_amount" value="<?php echo $tot_fee; ?>" />
                                <input type="hidden" name="base_amount" id="base_amount" value="<?php echo $baseAmount; ?>" />
                                <input type="hidden" name="processPayment" value="processPayment" />
                                <input type="hidden" name="regNosToPay" value="<?php echo $regNosToPay; ?>" />
                                <input type="hidden" name="tot_fee" value="<?php echo $tot_fee; ?>" />
                                <input type="hidden" name="base_fee" value="<?php echo base64_encode($baseAmount); ?>" />
                                <input type='hidden' name='exam_code' id='exam_code' value="<?php echo $exam_code; ?>" /> <!-- passing telecall const to page to identify dra or tele cands payment -->
                                <input type='hidden' name='exam_period' id='exam_period' value="<?php echo $exam_period; ?>" />
                                <div class="col-sm-4 col-xs-offset-3">
                                    <!--   <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit" onclick="this.disabled=true; this.value='Please Wait...';">  -->
                                    <!-- <input type="button" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit"> -->
                                    <input type="submit" class="btn btn-info" name="btnSubmit1" id="btnSubmit1" value="Proceed To Pay">
                                    <?php
                                    if (!empty($active_exams)) {

                                    ?>
                                        <input type="hidden" id='exam_from_date' name="exam_from_date" value="<?php echo $active_exams[0]['exam_from_date']; ?>" />
                                    <?php }
                                    ?>
                                </div>
                            </form>
                        </div><!--(Max 30 Characters) -->
                    </div>
                </div> <!-- Basic Details box closed-->
            </div>
        </div>
    </section>
</div>
<script src="<?php echo base_url() ?>js/validation_dra.js"></script>
<script>
    var baseAmount = '<?php echo $baseAmount; ?>';
    var totalAmount = '<?php echo $totalAmount; ?>';
    var gstRate = 18;

    baseAmount = parseFloat(baseAmount);
    totalAmount = parseFloat(totalAmount).toFixed(2);

    var gstAmount = (baseAmount * gstRate) / 100;
    $('#gst_amount').val(btoa(gstAmount.toFixed(2)));

    $(document).ready(function() {
        // Event listener for TDS radio buttons
        $('input[name="TDS"]').on('change', function() {
            if ($(this).val() === "Yes") {
                $('.tds-section').show(); // Show the .tds-section field
                $('#tds_type').attr('required', true);
                $('#tds_type').val('');
            } else {
                $('.tds-section').hide(); // Hide the .tds-section field
                $('#tds_type').attr('required', false);

                $('#final_amount').val(btoa(totalAmount)); // Reset final amount
                $('.final-amount').text('Rs. ' + totalAmount);
                $('#tds_amount').val('0');
                $('.tds-amount').text('Rs. ' + '0');
            }
        });

        // Calculate TDS and update the amounts
        $('#tds_type').on('change', function() {
            var tdsRate = parseFloat($(this).val()); // Get the selected TDS percentage

            if (!isNaN(tdsRate)) {
                var tdsAmount = (baseAmount * tdsRate) / 100; // Calculate TDS amount  
                // var finalAmount = baseAmount - tdsAmount; // Calculate final amount after TDS deduction

                var finalAmount = baseAmount + gstAmount - tdsAmount; // Calculate final amount after TDS deduction
                finalAmount = finalAmount.toFixed(2);

                tdsAmount = tdsAmount.toFixed(2);

                $('.tds-amount').text('Rs. ' + tdsAmount);
                $('#tds_amount').val(btoa(tdsAmount)); // Update TDS amount 
                $('#final_amount').val(btoa(finalAmount)); // Update final amount
                $('.final-amount').text('Rs. ' + finalAmount);

            } else {
                $('#tds_amount').val('0'); // Reset TDS amount if no valid TDS rate
                $('.tds-amount').text('Rs. ' + '0');
                $('#final_amount').val(btoa(totalAmount)); // Reset final amount
                $('.final-amount').text('Rs. ' + totalAmount);
            }
        });

        $('#btnSubmit').click(function() {
            if ($('#centerid').val() != "") {

            } else {
                alert("Please enter data for mandatory fields. ");
            }
        });

        var s_date = $('#exam_from_date').val();
        // alert(s_date);
        $('#payment_date').datepicker({
            format: 'yyyy-mm-dd',
            startDate: new Date(s_date),
            endDate: '+0d',
            autoclose: true
        });
        //$('#payment_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}); 
        $('#neft_pay_form').parsley('validate');
    });
</script>