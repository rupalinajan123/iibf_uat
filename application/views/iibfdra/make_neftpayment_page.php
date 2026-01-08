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
                       <div class="col-md-12">
                             <?php if($this->session->flashdata('error')!=''){?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                              <?php } if($this->session->flashdata('success')!=''){ ?>
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                   <?php echo $this->session->flashdata('success'); ?>
                              </div>
                             <?php } ?> 
                            <form action="<?php echo base_url() ?>iibfdra/DraExam/make_neft" method="post" class="form-horizontal" name="neft_pay_form" id="neft_pay_form">
                                <div class="form-group">
                                    <?php 
                                    $instdata = $this->session->userdata('dra_institute');
                                    $inst_code = $instdata['institute_code']; ?>
                                    <label for="institute_name" class="col-sm-4 control-label">Institute Name</label>
                                    <div class="col-sm-8">
                                        <?php echo $instdata['institute_name'];?>
                                        <input type="hidden" class="form-control" id="institute_code" name="institute_code" value="<?php echo $inst_code;?>" >
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">To Payee(IIBF)</label>
                                    <div class="col-sm-8">
                                        Beneficiary Name: Indian Institute of Banking & Finance
                                        <br>
                                        Bank: State bank of India
                                        <br>
                                        Branch: Kurla (west)
                                        <br>
                                        Code: 1886
                                        <br>
                                        IFSC: SBIN0001886
                                        <br>
                                        Current Acct No: 10783154783
                                        <br>
                                        MICR No: 400002091
                                        <br>
                                        SWIFT CODE: SBININBB357
                                        <br>
                                     </div>
                                </div>
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">Amount</label>
                                    <div class="col-sm-8">
                                        <?php echo base64_decode($tot_fee);?>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">NEFT / RTGS (UTR) Number *</label>
                                    <div class="col-sm-8"> 
                                        <input type="text" name="utr_no" id="utr_no" maxlength="25" data-parsley-type="alphanum" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">Payment Date *</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="payment_date" id="payment_date" data-date-minDate="<?php echo date('m/d/Y'); ?>" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="utr_slip" class="col-sm-4 control-label">Upload payment (UTR) slip (50 kb min & 100 kb max) *</label>
                                    <div class="col-sm-8">
                                        <input  type="file" class="form-control" name="utr_slip" id="utr_slip" required autocomplete="off">
                                        <input type="hidden" id="hiddenutrslip" name="hiddenutrslip">
                                        <div id="error_utrslip"></div>
                                        <div id="error_utrslip_size"></div>
                                        <span class="utrslip_text" style="display:none;"></span>
                                        <span class="error"><?php //echo form_error('utr_slip');?></span>
                                    </div>
                                </div>
                                
                               <div class="form-group">
                                    <label for="institute_name" class="col-sm-4 control-label">Exam Code & Exam Period</label>
                                    <div class="col-sm-8">
                                        <?php echo base64_decode($exam_code).' & '.base64_decode($exam_period);?>
                                    </div>
                                </div>
                                <input type="hidden" name="processPayment" value="processPayment" />
                                <input type="hidden" name="regNosToPay" value="<?php echo $regNosToPay; ?>" />
                                <input type="hidden" name="tot_fee" value="<?php echo $tot_fee; ?>" />
                                <input type='hidden' name='exam_code' id='exam_code' value="<?php echo $exam_code;?>" /> <!-- passing telecall const to page to identify dra or tele cands payment -->
                                <input type='hidden' name='exam_period' id='exam_period' value="<?php echo $exam_period;?>" />
                                <div class="col-sm-4 col-xs-offset-3">
                                	<input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Submit">                                
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
<script>
	$( document ).ready(function() {
		$('#payment_date').datepicker({format: 'yyyy-mm-dd',endDate: '+0d',autoclose: true}); 
		$('#neft_pay_form').parsley('validate');
	});
  /*history.pushState(null, null, document.title);
  window.addEventListener('popstate', function () {
      history.pushState(null, null, document.title);
  });*/
</script>