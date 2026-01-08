<style>
.modal-dialog{
    position: relative;
    display: table; 
    overflow-y: auto;    
    overflow-x: auto;
    width: 920px;
    min-width: 300px;   
}

#confirm .modal-dialog{
    position: relative;
    display: table; 
    overflow-y: auto;    
    overflow-x: auto;
  	width: 420px;
    min-width: 400px;   
}
.skin-blue .main-header .navbar {
	background-color:#fff;
}
body.layout-top-nav .main-header h1 {
	color:#0699dd;
	margin-bottom:0;
	margin-top:30px;
}
.container {
	position:relative;
}
.box-header.with-border {
	background-color:#7fd1ea;
	border-top-left-radius:0;
	border-top-right-radius:0;
	margin-bottom:10px;
}
.header_blue {
	background-color:#2ea0e2 !important;
	color:#fff !important;
	margin-bottom:0 !important;
}
.box {
	border:none;
	box-shadow:none;
	border-radius:0;
	margin-bottom:0;
}
.nobg {
	background:none !important;
	border:none !important;
}
.box-title-hd {
	color:#3c8dbc;
	font-size:16px;
	margin:0;
}
.blue_bg {
	background-color:#e7f3ff;
}
.m_t_15 {
	margin-top:15px;
}
.main-footer {
	padding-left:160px;
	padding-right:160px;
}
.content-header > h1 {
	font-size:22px;
	font-weight:600;
}
h4 {
	margin-top:5px;
	margin-bottom:10px !important;
	font-size:14px;
	line-height:18px;
	padding:0 5px;
	font-weight:600;
	text-align:justify;
}
.form-horizontal .control-label {
	padding-top:4px;
}
.pad_top_2 {
	padding-top:2px !important;
}
.pad_top_0 {
	padding-top:0px !important;
}

div.form-group:nth-child(odd) {
	background-color:#dcf1fc;
	padding:5px 0;
}

#confirmBox
{
    display: none;
    background-color: #eee;
    border-radius: 5px;
    border: 1px solid #aaa;
    position: fixed;
    width: 300px;
    left: 50%;
    margin-left: -150px;
    padding: 6px 8px 8px;
    box-sizing: border-box;
    text-align: center;
	z-index:1;
	box-shadow:0 1px 3px #000;
}
#confirmBox .button {
    background-color: #ccc;
    display: inline-block;
    border-radius: 3px;
    border: 1px solid #aaa;
    padding: 2px;
    text-align: center;
    width: 80px;
    cursor: pointer;
}
#confirmBox .button:hover
{
    background-color: #ddd;
}
#confirmBox .message
{
    text-align: left;
    margin-bottom: 8px;
}
.form-group {
	margin-bottom:10px;
}
.form-horizontal .form-group {
	margin-left:0;
	margin-right:0;

}
.form-control {
	border-color:#888;
}
.form-horizontal .control-label {
	font-weight:normal;
}
a.forget  {color:#9d0000;}
a.forget:hover {color:#9d0000; text-decoration:underline;}
ol li {
	line-height:18px;
}
.example {
	text-align:left !important;
	padding:0 10px;
}
</style>
	<div class="container">
		<section class="content-header">
			<h1 class="register">Candidate Details</h1><br/>
		</section>
		<span class="error">
			<?php echo validation_errors(); ?>
		</span>
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<form class="form-horizontal" method="post" action="<?php echo base_url(); ?>XLRI/insert_XLRI_data">
					<!-- Payment Details -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Payment Details</h3>
						</div>
						
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-12 control-label" style="text-align:left; border-bottom:solid 2px #fff;">Payment Option <span style="color:#F00">*</span></label>
								<div class="col-sm-12">
									<?php if($this->session->userdata['insertdata']['payment']=='first'){ ?>
									<input type="radio" id="payment" name="payment" value="first" checked >First Installment : Rs.<?php echo $this->config->item('XLRI_first_cs_total'); ?>/-
									<table border="2" style="width:100%">
									<!--<tr><th>Rs.118000/- in one lump sum</th></tr>-->
									<tr>
									<td>course fee</td>
									<td><?php echo $this->config->item('XLRI_first_course_fee'); ?>/-</td>
									<td>GST - <?php echo $this->config->item('XLRI_first_GST_amt'); ?>/-</td>
									
									<td><?php echo $this->config->item('XLRI_first_course_fee')  + $this->config->item('XLRI_first_GST_amt'); ?>/-</td>
									</tr>
									<?php if($this->config->item('XLRI_first_travel_fee') > 0) { ?>
									<tr>
									<td>Travel related expenses</td>
									<td><?php echo $this->config->item('XLRI_first_travel_fee'); ?>/-</td>
									<td>-</td>
									<td><?php echo $this->config->item('XLRI_first_travel_fee'); ?>/-</td>
									</tr>
									<?php } ?>
									<tr>
									<td>Total</td>
									<td><?php echo $this->config->item('XLRI_first_course_fee') + $this->config->item('XLRI_first_travel_fee'); ?>/-</td>
									<td>GST - <?php echo $this->config->item('XLRI_first_GST_amt'); ?>/-</td>
									<td><?php echo $this->config->item('XLRI_first_cs_total'); ?>/-</td>
									</tr>
									</table>
									<?php }elseif(strtolower($this->session->userdata['insertdata']['payment'])=='full'){ ?>
									<input type="radio" id="payment" name="payment" value="full" checked >Full Payment : Rs.<?php echo $this->config->item('XLRI_full_cs_total'); ?>/-
									<table border="2" style="width:100%">
									<!--<tr><th>Rs.118000/- in one lump sum</th></tr>--->
									<tr>
									<td>course fee</td>
									<td><?php echo $this->config->item('XLRI_full_course_fee'); ?>/-</td>
									<td>GST - <?php echo $this->config->item('XLRI_full_GST_amt'); ?>/-</td>
									
									<td><?php echo $this->config->item('XLRI_full_course_fee')  + $this->config->item('XLRI_full_GST_amt'); ?>/-</td>
									</tr>
									<?php if($this->config->item('XLRI_full_travel_fee') > 0) { ?>
									<tr>
									<td>Travel related expenses</td>
									<td><?php echo $this->config->item('XLRI_full_travel_fee'); ?>/-</td>
									<td>-</td>
									<td><?php echo $this->config->item('XLRI_full_travel_fee'); ?>/-</td>
									</tr>
									<?php } ?>
									<tr>
									<td>Total</td>
									<td><?php echo $this->config->item('XLRI_full_course_fee') + $this->config->item('XLRI_full_travel_fee'); ?>/-</td>
									<td>GST - <?php echo $this->config->item('XLRI_full_GST_amt'); ?>/-</td>
									<td><?php echo $this->config->item('XLRI_full_cs_total'); ?>/-</td>
									</tr>
									</table>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<!-- Payment Details box close -->
					
					<div class="box box-info">
						<div class="form-group">								
				          <label class="col-sm-3 control-label">&nbsp;</label>		
							<!--<input type="hidden" name="form_type" value="pay_form" />-->
							<input type="submit" class="btn btn-info" name="pay_form" value="Proceed for Payment" /> 
							<!--	<input type="hidden" name="form_type" value="p_invoice" />--> 
						</div>
					</div>
					</form>
					<!--<div class="box box-info">
						<div class="form-group">								
				          <label class="col-sm-3 control-label">&nbsp;</label>
							<!--<input type="hidden" name="form_type" value="pay_form" />--
							<input type="submit" class="btn btn-info" id="p_invoice" name="p_invoice" value="Download Proforma Invoice"/>	
							<!--	<input type="hidden" name="form_type" value="p_invoice" />--
						</div>
					</div>-->
					
				</div>
			</div>
		
		</section>
	</div>

<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<script src="<?php echo base_url();?>js/amp_validation.js"></script>
<script>
$(document).ready(function() 
{
 $('#p_invoice').click(function(event){
// $("#confirm").modal('show');
 $('#confirm').modal({backdrop: 'static'});
  /*      event.preventDefault();
    $.ajax({
 		type: 'POST',
 		url: site_url+'Amp/insert_proforma/',
 		success: function(res)
 		{	
		//alert(res);
 			if(res!='')
 			{
				//$('#temp_mem_no').html(res);
				$("#confirm").modal('show');
				//$('#p_invoice').attr('disabled', true);
 			}
 		}
    });*/
	});
	/*$(".show-modal").click(function(){

        $("#myModal").modal({

            backdrop: 'static',

            keyboard: false

        });

    });*/
});
</script>
