<?php $this->load->view('creditnote/admin/includes/header');?>
<?php $this->load->view('creditnote/admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    

  <h1 class="register">Refund Request Application</h1>
  <br />
</section>
<div>
  <!-- Start Get Details -->
  <?php
if (!empty($row)) {
    if (isset($row['msg']) && $row['msg'] != '') {
        //echo '<div class="alert alert-danger alert-dismissible">' . $row['msg'] . '</div>';
    }
}

?>
  <div id="capacitymsg" style="display:none">
    <div class="alert alert-danger"> <?php echo 'Transaction Number is not vaild, Please enter valid transaction Number.'; ?> </div>
  </div>
</div>
<section class="">
  <div class="row">
    <div class="col-md-12" style="">
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
?>" <?php if ($transaction_no!='') { echo "readonly='readonly'";} elseif (set_value('transaction_no')) { echo "readonly='readonly'"; }  ?> style="border-color:#000;" title="Transaction No.">
          </div>
          <div class="col-sm-3" style="padding-bottom: 10px">
            <?php 
			  	if ($transaction_no!='' || set_value('transaction_no')) { 
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
<form class="form-horizontal" name="refundrequestForm" id="refundrequestForm"  method="post" enctype="multipart/form-data" >
  <!--<section class="content">-->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-body">
          <div class="alert alert-danger alert-dismissible"  style="display:none"> </div>
          <input type="hidden" name="pay_type" id="pay_type" value="<?php if ($pay_type!='') {echo $pay_type;}?>"/>
          <input type="hidden" name="transaction_no" id="transaction_no" value="<?php if ($transaction_no!='') {echo $transaction_no;}?>"/>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Module Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" id="module_name" name="module_name" value="<?php if ($module_name!='') { echo $module_name;}?>" readonly = "readonly" placeholder="Module Name">
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Title&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="req_title" name="req_title" placeholder="Title" value="<?php echo set_value('req_title') ?>"   required />
          <span class="error" id="err_title"></span></div>
		  

      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Description&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
        <div class="col-sm-5">
          <textarea  class="txtara form-control" name="req_desc" 
                                        id="req_desc" 
                                        value="" data-parsley-maxlength="300" maxlength="300"  required ></textarea>
          <span class="error" id="err_desc"></span> </div>
		  (Max 300 Characters)
      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Member Number&nbsp;:</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="req_member_no" name="req_member_no" placeholder="Member Number" value="" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
    data-parsley-type="number" />
          <span class="error"></span> </div>
      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Exceptional Case &nbsp;:</label>
        <div class="col-sm-5">
        <input type="checkbox" class="minimal" id="req_exceptional_case"  name="req_exceptional_case" value="YES"  onchange="checkAll()" />
		<input type="hidden" class="minimal" id="req_exceptional_case"  name="req_exceptional_case" value="NO"  onchange="checkAll()" />
        </div>
      </div>
      <div class="form-group" id= "req_reason" style="display: none">
        <label for="roleid" class="col-sm-4 control-label">Exceptional Case Reason&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
		<div class="col-sm-5">
        		<textarea  class="txtara form-control" name="req_reason1" 
                                        id="req_reason1" 
                                        value="" data-parsley-maxlength="300" maxlength="300"  ></textarea>
		  
      <span class="error" id="err_reason"></span> </div>
	  (Max 300 Characters)
	  </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Refund Request Image uploading&nbsp;:</label>
		
		<div class="row" >
          <div class="col-sm-5">
            <input  type="file" name="scannedphoto1" id="scannedphoto1" parsley-filemaxsize="Upload|1.5" />
            <div id="error_photo1"></div>
            <br>
            <div id="error_photo_size"></div>
            <span class="photo_text" style="display:none;"></span> <span class="error">
            <?php //echo form_error('scannedphoto');?>
            </span> </div>
        </div>
		
      </div>
      <!--<div class="form-group">
        <label for="roleid" class="col-sm-4 control-label"></label>
        
      </div>-->
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label"></label>
        <div class="row">
          <div class="col-sm-5">
            <input  type="file" name="scannedphoto2" id="scannedphoto2">
            <div id="error_photo2"></div>
            <br>
            <div id="error_photo_size"></div>
            <span class="photo_text" style="display:none;"></span> <span class="error">
            <?php //echo form_error('scannedphoto');?>
            </span> </div>
        </div>
      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label"></label>
        <div class="row">
          <div class="col-sm-5">
            <input  type="file" name="scannedphoto3" id="scannedphoto3">
            <div id="error_photo3"></div>
            <br>
            <div id="error_photo_size"></div>
            <span class="photo_text" style="display:none;"></span> <span class="error">
            <?php //echo form_error('scannedphoto');?>
            </span> </div>
        </div>
      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label"></label>
        <div class="row">
          <div class="col-sm-5">
            <input  type="file" name="scannedphoto4" id="scannedphoto4">
            <div id="error_photo4"></div>
            <br>
            <div id="error_photo_size"></div>
            <span class="photo_text" style="display:none;"></span> <span class="error">
            <?php //echo form_error('scannedphoto');?>
            </span> </div>
        </div>
        <!--<img id="image_upload_scanphoto_preview" height="100" width="100"/>-->
      </div>
      <div class="box-footer">
        <div class="col-sm-6 col-sm-offset-3">
          <input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Submit" onclick="javascript:return refundCheckForm();">
          <a href="<?php echo base_url();?>creditnote/refundrequest/refundRequest" class="btn btn-default" >Reset</a> </div>
      </div>
    </div>
  </div>
  </div>

</form>
  </section>
</div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>js/validation_refund_request.js?<?php echo time();?>"></script>
<style>
.active_batch{
color:#00a65a;	
font-weight:600;
}

.deactive_batch{
color:#930;	
font-weight:600;
}
.input_search_data{
 width:100%;	
}
tfoot {
    display: table-header-group;
}
.vbtn{
padding: 3px 4px;
font-weight: 600;
}
</style>
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
            $('#req_reason').show();
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
                    $('#err_desc').text('Text will not allow 300 characters.');
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
                    $('#err_reason').text('Text will not allow 300 characters.');
                return false;
            }
        }
    });
	
	/*$('INPUT[type="file"]').change(function () {
    var ext = this.value.match(/\.(.+)$/)[1];
    switch (ext) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            $('#scannedphoto1').attr('disabled', false);
			//$('#scannedphoto2').attr('disabled', false);
            break;
        default:
            $('#error_photo1').text('This is not an allowed file type.');
			this.value = '';
			//$('#error_photo2').text('This is not an allowed file type.');
			//this.value = '';
    }
  });
 	*/

});
history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
window.addEventListener('popstate', function(event) {
    window.location.assign(site_url+"creditnote/refundrequest/refundRequest/");
});


</script>

