<?php $this->load->view('creditnote/admin/includes/header');?>
<?php $this->load->view('creditnote/admin/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Refund Request Application
      </h1>
     
    </section>
  
    <!-- Main content -->
   <section class="content">
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
      

 
  
 
      <div class="box box-info">
        <div class="box-body">
		<span id="dispaly_error" ></span>
		<form class="form-horizontal" method="post" name="refundrequestForm" id="refundrequestForm" enctype="multipart/form-data" >
  <!--<section class="content">-->
          <div class="alert alert-danger alert-dismissible"  style="display:none"> </div>
          <div class="form-group">
		  <label for="roleid" class="col-sm-4 control-label">Transaction No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
        <div class="col-sm-5">
          <input type="text" name="transaction_no" class="form-control" id="transaction_no" value="<?php echo $page_info[0]['transaction_no']; ?>" readonly = "readonly"/>
          </div>
        </div>
        <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Title&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="req_title" name="req_title" placeholder="Title" value="<?php echo $page_info[0]['req_title']; ?>" data-parsley-maxlength="100" maxlength="100"   required />
          <span class="error" id="err_title"></span></div>
      

      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Description&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
        <div class="col-sm-5">
          <textarea  class="txtara form-control" name="req_desc" 
                                        id="req_desc" 
                                        value="" data-parsley-maxlength="300" maxlength="300"  required ><?php echo $page_info[0]['req_desc']; ?></textarea>
          <span class="error" id="err_desc"></span> </div>
      (Max 300 Characters)
      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Member Number&nbsp;:</label>
        <div class="col-sm-5">
          <input type="text" class="form-control" id="req_member_no" name="req_member_no" placeholder="Member Number" value="<?php echo $page_info[0]['req_member_no']; ?>" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
   data-parsley-type="alphanum" data-parsley-maxlength="11" maxlength="11"/>
          <span class="error"></span> </div>
      </div>
      <div class="form-group">
        <label for="roleid" class="col-sm-4 control-label">Exceptional Case &nbsp;:</label>
        <div class="col-sm-5">
        <input type="checkbox" class="minimal" id="req_exceptional_case"  name="req_exceptional_case" value="YES" <?php if($page_info[0]['req_exceptional_case'] == "YES"){ ?> checked="checked" <?php }?> />
   
        </div>
      </div>
	  
      <div class="form-group" id="textbox" <?php if($page_info[0]['req_exceptional_case'] == "YES"){ ?> <?php }else{ ?> style="display:none;"  <?php } ?> >
        <label for="roleid" class="col-sm-4 control-label">Exceptional Case Reason&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
    <div class="col-sm-5">
            <textarea  class="txtara form-control" name="req_reason" 
                                        id="req_reason" 
                                        data-parsley-maxlength="300" maxlength="300"><?php echo $page_info[0]['req_reason'];?></textarea>
      
      <span class="error" id="err_reason"></span> </div>
    (Max 300 Characters)
    </div>

      <input type="hidden" class="form-control" id="req_status" name="req_status" value="<?php echo $page_info[0]['req_status']; ?>" />
      <input type="hidden" name="checker_id" value="<?php echo $page_info[0]['checker_id']; ?>">
      <input type="hidden" name="maker_id" value="<?php echo $page_info[0]['req_maker_id']; ?>">
      <input type="hidden" name="req_id" value="<?php echo $page_info[0]['req_id']; ?>">
      <!--<div class="form-group">
        <label for="roleid" class="col-sm-4 control-label"></label>
        
      </div>-->
      
      <div class="box-footer">
        <div class="col-sm-6 col-sm-offset-3">
          <input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Submit" onclick="javascript:return refundCheckForm();">
		 
      </div>
    </div>
  
  

        	
</form> 

           
            
        </div>
        <!-- /.col -->
    
	</div>
    

  </div>
  </div>
  </div>
</section>  
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>

<script src="<?php echo base_url();?>js/validation_refund_request.js?<?php echo time();?>"></script>
<script>
$(document).ready(function() {
    /*$("#transaction_no").focus();
	var flag = $("#flag").val();
	if(flag == 1){
		$("#transaction_no").val('');
		$("#transaction_no").prop("readonly", false);
		$("#modify").hide();
		$("#btnGet").show();
	}
	*/
	 $("#req_exceptional_case").click(function(){
			console.log('sdf');
		 if ($(this).is(":checked")) {//alert('**');
           // $('#req_reason').show();
			$('#textbox').show();
			$("#req_reason").prop("required", true); 
        }
		else
		{ 
			$('#textbox').hide();
			$("#req_reason").prop("required", false); 
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
    $('#req_reason').keypress(function(event){ //alert("hello");
        var Length = $("#req_reason").val().length;
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
history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
window.addEventListener('popstate', function(event) {
    window.location.assign(site_url+"creditnote/refundrequest/refundRequest/");
});


</script>
<script>
$(document).ready(function() {
    window.ParsleyValidator
        .addValidator('fileextension', function (value, requirement) {
        		var tagslistarr = requirement.split(',');
            var fileExtension = value.split('.').pop();
						var arr=[];
						$.each(tagslistarr,function(i,val){
   						 arr.push(val);
						});
            if(jQuery.inArray(fileExtension, arr)!='-1') {
              console.log("is in array");
              return true;
            } else {
              console.log("is NOT in array");
              return false;
            }
        }, 32)
        .addMessage('en', 'fileextension', 'The extension doesn\'t match the required');

    $("#image_name1").parsley();
    
    $("#image_name1").on('submit', function(e) {
        var f = $(this);
        f.parsley().validate();
        
         if (f.parsley().isValid()) {
			//$('#dispaly_error').html('');
            //alert('The form is valid');
        } else {
			$('#dispaly_error').html('There are validation errors');
            //alert('There are validation errors');
			 e.preventDefault();
        }
    });
});
</script>
 <script>
$(document).ready(function() {
    window.ParsleyValidator
        .addValidator('fileextension', function (value, requirement) {
        		var tagslistarr = requirement.split(',');
            var fileExtension = value.split('.').pop();
						var arr=[];
						$.each(tagslistarr,function(i,val){
   						 arr.push(val);
						});
            if(jQuery.inArray(fileExtension, arr)!='-1') {
              console.log("is in array");
              return true;
            } else {
              console.log("is NOT in array");
              return false;
            }
        }, 32)
        .addMessage('en', 'fileextension', 'The extension doesn\'t match the required');

    $("#image_name2").parsley();
    
    $("#image_name2").on('submit', function(e) {
        var f = $(this);
        f.parsley().validate();
        
         if (f.parsley().isValid()) {
			//$('#dispaly_error').html('');
            //alert('The form is valid');
        } else {
			$('#dispaly_error').html('There are validation errors');
            //alert('There are validation errors');
			 e.preventDefault();
        }
    });
});
</script>
 <script>
$(document).ready(function() {
    window.ParsleyValidator
        .addValidator('fileextension', function (value, requirement) {
        		var tagslistarr = requirement.split(',');
            var fileExtension = value.split('.').pop();
						var arr=[];
						$.each(tagslistarr,function(i,val){
   						 arr.push(val);
						});
            if(jQuery.inArray(fileExtension, arr)!='-1') {
              console.log("is in array");
              return true;
            } else {
              console.log("is NOT in array");
              return false;
            }
        }, 32)
        .addMessage('en', 'fileextension', 'The extension doesn\'t match the required');

    $("#image_name3").parsley();
    
    $("#image_name3").on('submit', function(e) {
        var f = $(this);
        f.parsley().validate();
        
        if (f.parsley().isValid()) {
			//$('#dispaly_error').html('');
            //alert('The form is valid');
        } else {
			$('#dispaly_error').html('There are validation errors');
            //alert('There are validation errors');
			 e.preventDefault();
        }
    });
});
</script>
 <script>
$(document).ready(function() {
    window.ParsleyValidator
        .addValidator('fileextension', function (value, requirement) {
        		var tagslistarr = requirement.split(',');
            var fileExtension = value.split('.').pop();
						var arr=[];
						$.each(tagslistarr,function(i,val){
   						 arr.push(val);
						});
            if(jQuery.inArray(fileExtension, arr)!='-1') {
              console.log("is in array");
              return true;
            } else {
              console.log("is NOT in array");
              return false;
            }
        }, 32)
        .addMessage('en', 'fileextension', 'The extension doesn\'t match the required');

    $("#image_name4").parsley();
    
    $("#image_name4").on('submit', function(e) {
        var f = $(this);
        f.parsley().validate();
        
       if (f.parsley().isValid()) {
			//$('#dispaly_error').html('');
            //alert('The form is valid');
        } else {
			$('#dispaly_error').html('There are validation errors');
            //alert('There are validation errors');
			 e.preventDefault();
        }
    });
});
</script>
 
<?php $this->load->view('creditnote/admin/includes/footer');?>