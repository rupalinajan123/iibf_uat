<style>
  .modal-dialog { position: relative; display: table; overflow-y: auto; overflow-x: auto; width: 920px; min-width: 300px; }
  #confirm .modal-dialog { position: relative; display: table; overflow-y: auto; overflow-x: auto; width: 420px; min-width: 400px; }
  .skin-blue .main-header .navbar { background-color: #fff; }
  body.layout-top-nav .main-header h1 { color: #0699dd; margin-bottom: 0; margin-top: 30px; }
  .container { position: relative; }
  .box-header.with-border { background-color: #7fd1ea; border-top-left-radius: 0; border-top-right-radius: 0; margin-bottom: 10px; }
  .header_blue { background-color: #2ea0e2 !important; color: #fff !important; margin-bottom: 0 !important; }
  .box { border: none; box-shadow: none; border-radius: 0; margin-bottom: 0; }
  .nobg { background: none !important; border: none !important; }
  .box-title-hd { color: #3c8dbc; font-size: 16px; margin: 0; }
  .blue_bg { background-color: #e7f3ff; } 
  .m_t_15 { margin-top: 15px; }
  .main-footer { padding-left: 0px; padding-right: 0px; margin:0 auto !important; width: 95%;}
  .content-header > h1 { font-size: 22px; font-weight: 600; }
  h4 { margin-top: 5px; margin-bottom: 10px !important; line-height: 18px; padding: 0 5px; font-weight: 600; text-align: justify; }
  /* .form-horizontal .control-label { padding-top: 400px; } */
  .pad_top_2 { padding-top: 2px !important; }
  .pad_top_0 { padding-top: 0px !important; }
  /* div.form-group:nth-child(odd) { background-color: #dcf1fc; padding: 5px 0; } */
  #confirmBox { display: none; background-color: #eee; border-radius: 5px; border: 1px solid #aaa; position: fixed; width: 300px; left: 50%; margin-left: -150px; padding: 6px 8px 8px; box-sizing: border-box; text-align: center; z-index: 1; box-shadow: 0 1px 3px #000; }
  #confirmBox .button { background-color: #ccc; display: inline-block; border-radius: 3px; border: 1px solid #aaa; padding: 2px; text-align: center; width: 80px; cursor: pointer; }
  #confirmBox .button:hover { background-color: #ddd; }
  #confirmBox .message { text-align: left; margin-bottom: 8px; }
  .form-group { margin-bottom: 10px; }
  .form-horizontal .form-group { margin-left: 0; margin-right: 0; }
  .form-control { border-color: #888; }
  /* .form-horizontal .control-label { font-weight: normal; } */
  a.forget { color: #9d0000; }
  a.forget:hover { color: #9d0000; text-decoration: underline; }
  ol li { line-height: 18px; }
  .example { text-align: left !important; padding: 0 10px; }
  
  
  .main-header { max-height: none; width: 100%; max-width: 900px; }
  .container { width: 100%; max-width: 900px; }
  .error, .error > p { color: #F00; margin: 0; font-weight: 500; line-height: 15px; display: block; text-align: left;	font-size: 13px; }
  
  ul.member_img_outer { list-style:none; margin:0; padding:0; text-align:center; }
  ul.member_img_outer li { display:inline-block; margin:10px; }
  ul.member_img_outer li a { display: table-cell; width: 180px; height: 130px; overflow: hidden; border: 4px solid #f6f6f6; padding: 2px; background: #FBFBFB; vertical-align: middle; }
  ul.member_img_outer li a.missing_img_outer, ul.member_img_outer li a.missing_img_outer:hover { color: #F00;font-size: 20px;background: #fff; }
  ul.member_img_outer li a img { max-width:100%; max-height:100%; opacity:0.8; transition: all .3s ease-in-out; }
  ul.member_img_outer li a:hover img { opacity:1; }
  ul.member_img_outer li p { text-align: center; font-weight: 600; margin: 4px 0 5px 0; }
  a.download_img_btn { margin: 0 auto 15px;display: block;max-width: 160px; }
  
  html, body { height: 100%; }
  #top_header { margin-bottom: -51px; min-height: 100%; padding-bottom:55px; }
  .main-footer { position:relative; }
	
	.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td 
	{ border: 1px solid #9b9b8c !important; }
	.bg_header > th { background:#dddddd; }
	
	.custom_form_filter { background: #f1f1f1;text-align: center;padding: 20px 20px 10px 20px;margin: 0px 0 20px 0;	}
	.custom_form_filter  .form-group {  }
  .form-label { text-align: left; display: block; margin: 0; }
	.custom_form_filter > .form-group > .btn { padding: 4px 15px; border-radius: 0; }
	.main-header { position: unset; }
	
  
  @media only screen and (max-width:991px) { #getDetailsForm > .form-group > .col-sm-6 { margin-bottom:15px; } }
  @media only screen and (max-width:768px) { #getDetailsForm > .form-group > .col-xs-12 { padding:0; } }
</style>

<?php
  header('Cache-Control: must-revalidate');
  header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

<div class="container">
  <section class="content-header box-header with-border" style="height: 45px; background-color: #1287C0; ">
    <h1 class="register">CSC Refund</h1><br />
	</section>
  
  <section>
    <div class="row">
      <div class="col-md-12">
        <?php
          if($this->session->flashdata('success') != '') 
          { ?>
          <div class="alert alert-success alert-dismissible" id="success_id">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('success');?> 
					</div>
					<?php }
					
          if($this->session->flashdata('error') != '') 
          { ?>
          <div class="alert alert-danger alert-dismissible" id="error_id">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $this->session->flashdata('error');?> 
					</div>
				<?php } ?>
        
        <form class="form-horizontal custom_form_filter" name="getDetailsForm" id="getDetailsForm" method="post" action="<?php echo site_url('admin/csc_refund'); ?>" autocomplete="off">
          <div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="form-group">
								<label class="form-label">CSC Transaction Number *</label>
							<input type="text" class="form-control" id="csc_txn" name="csc_txn" value="<?php if(set_value('csc_txn')) { echo set_value('csc_txn'); } else { echo $csc_txn; } ?>" placeholder="CSC Transaction Number *" required></span>
							<?php if(form_error('csc_txn')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('csc_txn'); ?></label> <?php } ?>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">          
						<div class="form-group">
							<label class="form-label">Merchant Transaction Number *</label>
						<input type="text" class="form-control" id="merchant_txn" name="merchant_txn" value="<?php if(set_value('merchant_txn')) { echo set_value('merchant_txn'); } else { echo $merchant_txn; } ?>" placeholder="Merchant Transaction Number *" required></span>
						<?php if(form_error('merchant_txn')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('merchant_txn'); ?></label> <?php } ?>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<div class="form-group">
						<label class="form-label">Transaction Date</label>
					<input type="text" class="form-control" id="txn_date" name="txn_date" value="<?php if(set_value('txn_date')) { echo set_value('txn_date'); } else { echo $txn_date; } ?>" placeholder="Transaction Date"></span>
					<?php if(form_error('txn_date')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('txn_date'); ?></label> <?php } ?>
				</div>
			</div>
			
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="form-group">
					<label class="form-label">Refund Deduction *</label> <!-- value="<?php if(set_value('refund_deduction')) { echo set_value('refund_deduction'); } else { echo $refund_deduction; } ?>" -->
				<input type="text" class="form-control" id="refund_deduction" name="refund_deduction" value="944.00"  placeholder="Refund Deduction *" required></span>
				<?php if(form_error('refund_deduction')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('refund_deduction'); ?></label> <?php } ?>
			</div>
		</div>
		
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="form-group"> <!-- value="<?php if(set_value('refund_reason')) { echo set_value('refund_reason'); } else { echo $refund_reason; } ?>" -->
				<label class="form-label">Refund Reason *</label>
			<input type="text" class="form-control" id="refund_reason" name="refund_reason" value="Mail CSC Transactions for which amount is received in bank but invoice not generated"  placeholder="Refund Reason *" required></span>
			<?php if(form_error('refund_reason')!=""){ ?> <div class="clearfix"></div><label class="error"><?php echo form_error('refund_reason'); ?></label> <?php } ?>
		</div>
	</div>
</div>

<div class="form-group" style="margin:10px 0;">
	<button type="submit" class="btn btn-primary" style="">Submit</button>
</div>					
</form>        
</div>
</div>
</section>
</div>
<div id="footer_script_outer"></div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">
<style>.datepicker table tbody tr td.disabled, .datepicker table tbody tr td.disabled:hover { background: rgba(0, 0, 0, 0.04) !important; cursor: not-allowed !important; border: 1px solid #fff; color: #ccc !important; }</style>

<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('#txn_date').datepicker(
		{ 
			/* todayBtn: "linked", */ 
			keyboardNavigation: true, 
			forceParse: true, 
			/* calendarWeeks: true, */ 
			autoclose: true, 
			format: "yyyy-mm-dd", 
			/* todayHighlight:true, 
			startDate:"", */
			clearBtn: true,
      endDate:"<?php echo date('Y-m-d') ?>" 				
		});
	});
</script>		