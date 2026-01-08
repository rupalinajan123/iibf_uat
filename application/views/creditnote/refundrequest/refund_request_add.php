<?php $this->load->view('creditnote/admin/includes/header');?>
<?php $this->load->view('creditnote/admin/includes/sidebar');?>
<style type="text/css">
  .read_only{
border: none;
  }
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Refund Request Application
      </h1>
     
    </section>
	<div  class="col-md-12"> 
	<br/>
    <!-- Start Get Details -->
    <?php
if (!empty($row)) {
    if (isset($row['msg']) && $row['msg'] != '') {
        echo '<div class="alert alert-danger alert-dismissible">' . $row['msg'] . '</div>';
    }
}

?>
<div id="capacitymsg" style="display:none">
  <div class="alert alert-danger"> <?php echo 'Transaction Number is required.'; ?> </div>
</div>
  </div>
  <div class="col-xs-12">
    <br />    
    <?php /*?><?php 
   if($this->session->flashdata('success')!=''){ ?>
              <div class="alert alert-success alert-dismissible" id="success_id">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $this->session->flashdata('success'); ?> </div>
     <?php }?>    
    </div><?php */?>
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">           
            <div class="box-body">
   
<section class="">
  <div class="row">
    <div class="col-xs-12" style="">
      <?php if ($this->session->flashdata('flsh_msg') != '') {?>
      <div class="alert alert-danger"> <?php echo $this->session->flashdata('flsh_msg'); ?> </div>
      <?php }?>
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
      <?php } ?>
      <form name="getDetailsForm" id="getDetailsForm" method="post" action="<?php echo base_url(); ?>creditnote/refundrequest/refundRequest">
        <br />
        <?php
      if(validation_errors() != '')
      { 
      ?>
        <input type="hidden" id="flag" value="1" />
        <?php 
      }
      ?>
        <div class="">
          <div for="roleid" class="col-sm-5 control-label" style="text-align: right; width:35%;">Transaction No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</div>
          <div class="col-sm-4" style="width: 32%;text-align: left;">
            <input type="text" class="form-control" id="transaction_no" name="transaction_no" placeholder="Transaction No."  value="<?php if (isset($transaction_no) && $transaction_no!='') { echo $transaction_no;} else { echo set_value('transaction_no'); } 
?>" <?php if ($transaction_no!='') { echo "readonly='readonly'";} elseif (set_value('transaction_no')) { echo "readonly='readonly'"; }  ?> style="border-color:#000;" title="Transaction No." >
          </div>
          <div class="col-sm-3" style="padding-bottom: 10px">
            <?php 
          if ($transaction_no!='') { 
        ?>
            <a href="<?php echo base_url();?>creditnote/refundrequest/refundRequest" class="btn btn-info" id="modify" style="height: 32px; width: 150px">Get Details</a>
            <input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGet" value="Get Details" style="height: 32px; width: 150px; font-size:15px; display:none;">
            <?php
        } 
        else
        {
        ?>
            <input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGetDetails" value="Get Details" style="height: 32px; width: 150px; font-size:15px;">
            <?php 
         } 
          ?>
          </div>
          <div>
            <div class="col-sm-12" align="center"> <span style="color:#F00; font-size:14px;">Please insert 'Transaction No.' and click on 'Get Details' button.</span> </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  
</section>
<br />

<!-- Close Get Details-->

<!--<form class="form-horizontal" name="refundrequestForm" id="refundrequestForm"  method="post" enctype="multipart/form-data" onsubmit="return validate_submit()" >-->
<span id="dispaly_error" ></span>
<form class="form-horizontal" name="refundrequestForm" id="refundrequestForm"  method="post" enctype="multipart/form-data">
  <!--<section class="content">-->
  <div class="row">
    <div class="col-xs-12">
    
        <div class="box-body">
          <div class="alert alert-danger alert-dismissible"  style="display:none"> </div>
          <input type="hidden" name="pay_type" id="pay_type" value="<?php if ($pay_type!='') {echo $pay_type;}?>"/>
          <input type="hidden" name="transaction_no" id="transaction_no" value="<?php if ($transaction_no!='') {echo $transaction_no;}?>" />
          <div class="form-group">
            <label for="roleid" class="col-xs-4 control-label">Module Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="module_name" name="module_name" value="<?php if ($module_name!='') { echo $module_name;}?>" readonly = "readonly" placeholder="Module Name">
            </div>
          </div>

          <div class="form-group">
            <label for="roleid" class="col-xs-4 control-label">Exam Period&nbsp;:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control read_only" id="exam_period"  value="<?php if ($exam_period!='') { echo $exam_period;}else { echo '-';}?>" placeholder="-">
            </div>
          </div>

          <div class="form-group">
            <label for="roleid" class="col-xs-4 control-label">Exam Code&nbsp;:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control read_only" id="exam_code" value="<?php if ($exam_code!='') { echo $exam_code;}else { echo '-';}?>" placeholder="-">
            </div>
          </div>

          <div class="form-group">
            <label for="roleid" class="col-xs-4 control-label">Invoice No.&nbsp;:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control read_only" id="invoice_no"  value="<?php if ($invoice_no!='') { echo $invoice_no;}?>" placeholder="-">
            </div>
          </div>

          <div class="form-group">
            <label for="roleid" class="col-xs-4 control-label">Date Of Invoice&nbsp;:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control read_only" id="date_of_invoice" value="<?php if ($date_of_invoice!='') { echo $date_of_invoice;}?>" placeholder="-">
            </div>
          </div>

          <div class="form-group">
            <label for="roleid" class="col-xs-4 control-label">Amount&nbsp;:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control read_only" id="amount"  value="<?php if ($amount!='') { echo $amount;}?>" placeholder="-">
            </div>
          </div>
       
      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Title&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="req_title" name="req_title" placeholder="Title" value="<?php echo set_value('title') ?>"  data-parsley-maxlength="100" maxlength="100" required />
          <span class="error" id="err_title"></span></div>
      

      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Description&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
        <div class="col-sm-5">
          <textarea  class="txtara form-control" name="req_desc" 
                                        id="req_desc" 
                                        value="" data-parsley-maxlength="300" maxlength="300"  required ><?php echo set_value('description') ?></textarea>
          <span class="error" id="err_desc"></span> </div>
      (Max 300 Characters)
      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Member Number&nbsp;:</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="req_member_no" name="req_member_no" placeholder="Member Number" value="<?php  echo $member_regnumber;?>" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
   data-parsley-type="alphanum" data-parsley-maxlength="11" maxlength="11"/>
          <span class="error" id="err_member_no" ></span> </div>
      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Exceptional Case &nbsp;:</label>
        <div class="col-sm-5">
        <input type="checkbox" class="minimal" id="req_exceptional_case"  name="req_exceptional_case" value="YES"/>
    <!--<input type="hidden" class="minimal" id="req_exceptional_case"  name="req_exceptional_case" value="NO" />-->
        </div>
      </div>
      <div class="form-group" id= "req_reason" style="display: none">
        <label for="roleid" class="col-sm-4 control-label">Exceptional Case Reason&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
    <div class="col-sm-5">
     <textarea class="txtara form-control" name="req_reason1"  id="req_reason1" value="" data-parsley-maxlength="300" maxlength="300" ><?php echo set_value('reason') ?></textarea>
      
      <span class="error" id="err_reason"></span> </div>
    (Max 300 Characters)
    </div>
     
      <!--<div class="form-group">
        <label for="roleid" class="col-sm-4 control-label"></label>
        
      </div>-->
     
      
      
        <!--<img id="image_upload_scanphoto_preview" height="100" width="100"/>-->
      
      <div class="box-footer">
        <div class="col-sm-6 col-sm-offset-3">
          <input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Submit" onclick="javascript:return refundCheckForm();">
          <a href="<?php echo base_url();?>creditnote/refundrequest/refundRequest" class="btn btn-default" >Reset</a> </div>
      </div>
    </div>
  </div>
</form>
        
           
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
		</div>
    </section>
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php echo base_url();?>js/validation_refund_request.js?<?php echo time();?>"></script>
<script>
$(document).ready(function() {
    $("#transaction_no").focus();
	var flag = $("#flag").val();
	if(flag == 1){
		$("#transaction_no").val('');
		$("#transaction_no").prop("readonly", false);
		$("#modify").hide();
		$("#btnGet").show();
	}
	$("#req_exceptional_case").click(function(){
		 if ($(this).is(":checked")) {
           // $('#req_reason').show();
			$('#req_reason').show();
			$("#req_reason1").prop("required", true); 
        }
		else
		{ 
			$('#req_reason').hide();
		}
		
	});
	
	 
	var maxLen = 300;
    $('#req_desc').keypress(function(event){ //alert("hello");
        var Length = $("#req_desc").val().length;
        var AmountLeft = maxLen - Length;
        $('#txt-length-left').html(AmountLeft);
        if(Length >= maxLen){
            if (event.which != 8) {
                    $('#err_desc').text('Text not allow more than 300 characters.');
                return false;
            }
        }
    });
	
	var maxLen = 300;
    $('#req_reason1').keypress(function(event){ //alert("hello");
        var Length = $("#req_reason1").val().length;
        var AmountLeft = maxLen - Length; 
        $('#txt-length-left').html(AmountLeft);
        if(Length >= maxLen){
            if (event.which != 8) {
                    $('#err_reason').text('Text not allow more than 300 characters.');
                return false;
            }
        }
    });
	
	

});


</script>

<?php $this->load->view('creditnote/admin/includes/footer');?>