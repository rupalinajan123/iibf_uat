<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> Inspector Master </h1>
  <?php echo $breadcrumb;?> </section>
<form class="form-horizontal" name="usersAddForm" id="usersAddForm" action="<?php echo base_url();?>iibfdra/admin/InspectorMaster/add" method="post">
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <!-- Horizontal Form -->
        <div class="box box-info">
          <div class="box-header with-border">
            <?php $id = $this->uri->segment(4); 
              if($id=='edit'){?>
            <h3 class="box-title">Update Inspectors Details</h3>
            <?php } else{?>
            <h3 class="box-title">Add Inspectors Details</h3>
            <?php }?>
            <div class="pull-right"> <a href="<?php echo base_url();?>iibfdra/admin/InspectorMaster" class="btn btn-warning">Back</a> </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <div class="box-body">
            <?php //echo validation_errors(); ?>
            <?php if($this->session->flashdata('error')!=''){?>
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <?php echo $this->session->flashdata('error'); ?> </div>
            <?php } if($this->session->flashdata('success')!=''){ ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <?php echo $this->session->flashdata('success'); ?> </div>
            <?php } ?>
            <div class="form-group">
              <input type="hidden" class="form-control" id="id" name="id"  value="<?php echo set_value('id');?>" >
              <label for="roleid"  class="col-sm-2 control-label">Inspector Name <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <?php 
                      $last = $this->uri->total_segments();
                      $id = $this->uri->segment($last); ?>
                <input type="text" class="form-control" id="inspector_name" name="inspector_name" placeholder="Inspector Name" required value="<?php echo $inspectorRes['inspector_name'];?>" maxlength="40" data-parsley-pattern="/^[a-zA-Z ]+$/">
                <span class="error"><?php echo form_error('inspector_name');?></span> </div>
              <label for="roleid"  class="col-sm-2 control-label">Mobile Number <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="inspector_mobile" name="inspector_mobile" placeholder="Mobile Number" required value="<?php echo $inspectorRes['inspector_mobile'];?>" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" data-parsley-maxlength="10" maxlength="10"  data-parsley-minlength="10">
                <span class="error"><?php echo form_error('inspector_mobile');?></span> </div>
            </div>
            <!-- /form-group -->
            <div class="form-group">
              <label for="roleid"  class="col-sm-2 control-label"> Email Id <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="inspector_email" name="inspector_email" placeholder="Email Id" required value="<?php echo $inspectorRes['inspector_email'];?>" data-parsley-type="email" data-parsley-maxlength="80"  >
                <span class="error"><?php //echo form_error('inspector_email'); ?></span>
                <div id="email_error" style="color:#FF0000;">Email Id Already Exist</div>
              </div>
              <label for="roleid"  class="col-sm-2 control-label">Inspector Designation <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="inspector_name" maxlength="40" name="inspector_designation" placeholder="Inspector Designation" required value="<?php echo $inspectorRes['inspector_designation'];?>" data-parsley-pattern="/^[a-zA-Z ]+$/">
                <span class="error"><?php echo form_error('inspector_designation');?></span> </div>
            </div>
            <!-- /form-group -->
          </div>
          <!-- /box info -->
          <!-- Multi select of location name from center_id -->
          <!--  <div class="form-group" >
                   <label for="roleid"  class="col-sm-2 control-label">Assign Training Center <span style="color:#F00">*</span></label> 
                      <div class="col-sm-3">
                        <?php $last   = $this->
          uri->total_segments();
          $inspector_id   = $this->uri->segment($last);?>
          <?php if(is_numeric($id))
                        { ?>
          <select class="form-control"  name="inspector_center[]" id="inspector_center" multiple="" required>
            <option value = "-"> <?php echo"-select-"?></option>
            <?php 
              				$centerid = $this->master_model->getRecords("agency_inspector_center",array('inspector_id'=>$inspector_id),'center_id');
             				  $centerarr = array();
              				foreach($centerid as $centerid)
              				{
               				 		$centerarr[] = $centerid['center_id'];
             				  }
                            if($inspector_center) 
                            { 
                              foreach($inspector_center as $res) 
                              { ?>
            <option value="<?php echo $res['center_id']; ?>" <?php if(in_array($res['center_id'],$centerarr) ){?> selected="selected" <?php }?> > <?php echo $res['location_name']; ?> </option>
            <?php }  }   ?>
          </select>
          <?php }
                        else
                        { ?>
          <select class="form-control" name="inspector_center[]" id="inspector_center" multiple="" required>
            <option value=""> <?php echo"-select-"?></option>
            <?php  
                            if($inspector_center) 
                            { 
                              foreach($inspector_center as $res) 
                              { ?>
            <option value="<?php echo $res['center_id']?>" > <?php echo $res['location_name']; ?> </option>
            <?php } 
                            } ?>
          </select>
          <?php } ?>
        </div>
        <span class="error"><?php echo form_error('inspector_center');?></span> </div>
      -->
      <!-- /Multi select of location name from center_id -->
      <div class="box-header with-border">
        <h3 class="box-title">Location Assignment</h3>
      </div>
      <div class="box-body">
        <div class="col-md-12">
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
            <div class="col-sm-3">
              <select class="form-control" id="state" name="state" required >
                <option value="">Select</option>
                <?php 
                            if(count($states) > 0){
                            	foreach($states as $row){  ?>
                <option value="<?php echo $row['state_code'];?>" 
                         	<?php if(isset($inspectorState[0]['state']) && $row['state_code'] == $inspectorState[0]['state'])
                         	{ ?> selected="selected" <?php } ?>><?php echo $row['state_name'];?></option>
                <?php } } ?>
              </select>
              <input hidden="statepincode" id="statepincode" value="">
            </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">Name Of Location(City) <span style="color:#F00">*</span></label>
            <div class="col-sm-3">
              <select class="form-control" id="city" name="city" required >
                <option value="">Select</option>
                <?php if(count($cities) > 0){
                                foreach($cities as $row1){  ?>
                <option value="<?php echo $row1['id']; ?>" <?php if(isset($inspectorState[0]['city'])){
	                         if($row1['id'] == $inspectorState[0]['city']){ ?> selected="selected"
	                         <?php }} ?>> <?php echo $row1['city_name'];?></option>
                <?php } } ?>
              </select>
            </div>
          </div>
          <!--/ col-md-6 -->
        </div>
        <!--.box-body-->
      </div>
      <!-- /box body -->
      <div class="box-footer">
        <div class="col-sm-3 col-xs-offset-5">
          <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(is_numeric($id)){ echo 'Update';}else{ echo 'Add';} ?>">
          <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset" onclick="resetForm();">Reset</button>
        </div>
      </div>
      <!-- box footer -->
    </div>
    </div>
    </div>
  </section>
</form>
</div>
<script src="<?php echo base_url();?>js/jquery.validate.min.js"></script> 
<script src="<?php //echo base_url()?>assets/js/parsley.min.js"></script>
<script src="<?php //echo base_url();?>js/jquery.validate.min.js"></script> 

<script>

$(document).ready(function(){
	try{
		jQuery.validator.addMethod("lettersonly", function(value, element) {
		  return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Letters only please"); 
		
		jQuery.validator.addMethod("dateonly", function(value, element) {
		  return this.optional(element) || /(?:0[1-9]|[12][0-9]|3[01])-(?:0[1-9]|1[0-2])-(?:19|20\d{2})/i.test(value);
		}, "Date(dd-mm-yyyy) format only please"); 

		jQuery.validator.addMethod("chkextension", function(value, element) {
		  var ext = $('#img').val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
				return false;
			}else{
				return true;	
			}
		}, "<br><br>Invalid image"); 
		
		jQuery.validator.addMethod("fileonly", function(value, element) {
		  var ext = $('#upload_file').val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['docx','jpg','png','pdf']) == -1) {
				return false;
			}else{
				return true;	
			}
		}, "Invalid File"); 
		
		var inspector_email = document.getElementById('inspector_email').value;
		
		var validator = $("#usersAddForm").validate({
			errorElement: 'div',
			rules: {
			
				inspector_name:{required: true},
				inspector_designation:{required: true},
					
			},

			messages: {
				
				
				

			},
			errorPlacement: function(error, element) {
				error.appendTo( element.parent() );
			},
			submitHandler: function(form) { 
				form.submit();
			}
		});
	}catch(err){
		console.log(err.message);
	}
	
	
	
})

</script> 



<?php $this->load->view('iibfdra/admin/includes/footer');?>
