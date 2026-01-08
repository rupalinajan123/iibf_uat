<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
    <section class="content-header">
    	<h1></h1>
    </section>
    <section class="content">
	    <div class="row">
    	    <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
					
                        <h3 class="box-title">NEFT / RTGS Information</h3>
                    </div>
					
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
					 <a href="<?php echo base_url() ?>bulk/BulkApply/exam_applicantlst/" class="btn btn-warning" style="float: right;">Back</a>
                       <div class="col-md-12">
                        <?php
if ($this->session->flashdata('error') != '') {?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('error');?> </div>
        <?php } if ($this->session->flashdata('success') != '') {?>
        <div class="alert alert-success alert-dismissible" id="success_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('success');?> </div>
        <?php } if (validation_errors() != '') { ?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo validation_errors(); ?> </div>
        <?php } if ($var_errors != '') { ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $var_errors; ?> </div>
        <?php } ?>
                            
                            <form action="<?php echo base_url() ?>bulk/Bulk_exam_payment/make_neft" method="post" class="form-horizontal" name="neft_pay_form" id="neft_pay_form">
                                <div class="form-group">
                                    <?php 
                                    $instdata = $this->session->userdata('dra_institute');
                                    $inst_code = $instdata['institute_code']; ?>
                                    <label for="institute_name" class="col-sm-4 control-label"><strong style="color:#000">Institute Name&nbsp; :</strong></label>
                                    <div class="col-sm-8">
                                        <?php echo $_SESSION['institute_name'];?>
                                        <input type="hidden" class="form-control" id="institute_code" name="institute_code" value="<?php echo $_SESSION['institute_id'];?>" >
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label"><strong style="color:#000">To Payee(IIBF)&nbsp;:</strong></label><br>
                                    <div class="col-sm-8">
                                       <strong style="color:#000">Beneficiary Name : </strong> Indian Institute of Banking & Finance
                                        <br>
                                      <strong style="color:#000">  Bank : </strong> State bank of India
                                        <br>
                                       <strong style="color:#000"> Branch : </strong> Kurla (west)
                                        <br>
                                       <strong style="color:#000"> Code :</strong> 1886
                                        <br>
                                        <strong style="color:#000">IFSC :</strong> SBIN0001886
                                       <br>
                                       <strong style="color:#000">Type of Account : </strong> Current Account
                                        <br>
                                       <strong style="color:#000">Current Account No. : </strong> 31252654536
                                        <br>
                                       <strong style="color:#000"> MICR No : </strong> 400002091
                                        <br>
                                       <strong style="color:#000"> SWIFT CODE :</strong> SBININBB357
                                        <br>
                                     </div>
                                </div>
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label"> <strong style="color:#000"> Amount&nbsp;:</strong></label>
                                    <div class="col-sm-8">
                                        <?php 
										echo $total_fee;?>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label"> <strong style="color:#000"> NEFT / RTGS (UTR) Number &nbsp;</strong><span style="color:#F00">*</span>&nbsp;:</label>
                                    <div class="col-sm-8">
                                    <?php 
										echo 'TEMP-UTR-IIBF';?><span style="color:#F00"> &nbsp; ( Default  NEFT / RTGS (UTR) Number )</span>
                                       <!-- <input type="text" name="utr_no" id="utr_no" maxlength="20" data-parsley-type="alphanum"   value="TEMP-UTR-IIBF" required readonly="readonly"/><span style="color:#F00"> &nbsp; ( Default  NEFT / RTGS (UTR) Number )</span>-->
                                    </div>
                                </div>
                                <div class="form-group">
                                
            
                           
                                    <label for="institute_name" class="col-sm-4 control-label"> <strong style="color:#000">Payment Date &nbsp;</strong><span style="color:#F00">*</span>&nbsp;:</label>
                                    <div class="col-sm-8">
                                   
                                        <input type="text" name="payment_date" id="to_date" value="<?php echo date('Y-m-d'); ?>" required />
                                    </div>
                                </div>
                                <!--<div class="form-group">
                                    <label for="utr_slip" class="col-sm-4 control-label">Upload payment (UTR) slip (50 kb min & 100 kb max) *</label>
                                    <div class="col-sm-8">
                                        <input  type="file" class="form-control" name="utr_slip" id="utr_slip" required autocomplete="off">
                                        <input type="hidden" id="hiddenutrslip" name="hiddenutrslip">
                                        <div id="error_utrslip"></div>
                                        <div id="error_utrslip_size"></div>
                                        <span class="utrslip_text" style="display:none;"></span>
                                        <span class="error"><?php //echo form_error('utr_slip');?></span>
                                    </div>
                                </div>-->
                                
                                
                               
                                <input type="hidden" name="processPayment" value="processPayment" />
                                <input type="hidden" name="regNosToPay" value="<?php //echo $regNosToPay; ?>" />
                                <input type="hidden" name="tot_fee" value="<?php echo $total_fee; ?>" />
                                <input type='hidden' name='exam_code' id='exam_code' value="<?php // if(isset($exam_code) ){echo $exam_code;}else{echo '';}?>" /> <!-- passing telecall const to page to identify dra or tele cands payment -->
                                <input type='hidden' name='exam_period' id='exam_period' value="<?php echo $exam_period;?>" />
                                
                                <input type='hidden' name='final_subtotal_after_tds' id='final_subtotal_after_tds'  value="<?php echo $subtotal_after_tds;?>"  />
                              <input type='hidden' name='tds_amt' id='tds_amt' value="<?php echo $tds_amt;?>"  />
                               <input type='hidden' name='gst_rate_amt' id='gst_rate_amt'  value="<?php echo $gst_rate_amt;?>"  />
                                <input type='hidden' name='tax_type' id='tax_type'  value="<?php echo $tax_type;?>"  />
                                 <input type='hidden' name='base_amt_tot' id='base_amt_tot'  value="<?php echo $base_fee_amt;?>"  />
                                  <input type='hidden' name='base_amt_after_dsct' id='base_amt_after_dsct'  value="<?php echo $base_amt_after_dsct;?>"  />
                                <div class="col-sm-4 col-xs-offset-3">
                                	<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Generate Proforma Invoice">                                
                                </div>
                            </form>
                       </div><!--(Max 30 Characters) -->
                    </div>
                </div> <!-- Basic Details box closed-->
        	</div>
    	</div>
	</section>
</div>
<script src="<?php echo base_url()?>js/validation_dra.js"></script>
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">

<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">

<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>

<script>
$(document).ready(function() 
{
	$('#from_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#to_date').datepicker('setStartDate', new Date($(this).val()));
		$('#neft_pay_form').parsley('validate');
	}); 
	
	$('#to_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}).on('changeDate', function(){
		$('#from_date').datepicker('setEndDate', new Date($(this).val()));
	});
});
</script>
<!--//back button disable -->
<script>
history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
</script>
