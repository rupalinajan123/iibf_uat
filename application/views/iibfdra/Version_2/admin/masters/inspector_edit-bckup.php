<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> Inspector Master </h1>
  <?php echo $breadcrumb;?> </section>
<form class="form-horizontal" name="userseditForm" id="userseditForm" action="<?php echo base_url();?>iibfdra/admin/InspectorMaster/edit/<?php echo $this->uri->segment(5); ?>" method="post">
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <!-- Horizontal Form -->
        <div class="box box-info">
          <div class="box-header with-border">
            <?php $inspector_id = base64_decode($this->uri->segment(5)); ?>
            <h3 class="box-title">Update Inspectors Details</h3>
  
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
              <label for="roleid"  class="col-sm-2 control-label">Inspector Name <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="inspector_name" name="inspector_name" value="<?php echo $inspectorRes['inspector_name'];?>" placeholder="Inspector Name" >
              </div>
              <label for="roleid"  class="col-sm-2 control-label">Mobile Number <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="inspector_mobile" name="inspector_mobile" value="<?php echo $inspectorRes['inspector_mobile'];?>" placeholder="Mobile Number" maxlength="10" minlength="10">
              </div>
            </div>
            <!-- /form-group -->
            <div class="form-group">
              <label for="roleid"  class="col-sm-2 control-label"> Email Id <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <input type="email" class="form-control" id="inspector_email" name="inspector_email" value="<?php echo $inspectorRes['inspector_email'];?>" placeholder="Email Id" >
              </div>
              <label for="roleid"  class="col-sm-2 control-label">Inspector Designation <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="inspector_name" maxlength="40" name="inspector_designation" value="<?php echo $inspectorRes['inspector_designation'];?>" placeholder="Inspector Designation" >
              </div>
            </div>
            <!-- /form-group -->
          </div>
          <!-- /box info -->
          <!-- Multi select of location name from center_id -->
          <!--  <div class="form-group" >
                   <label for="roleid"  class="col-sm-2 control-label">Assign Training Center <span style="color:#F00">*</span></label> 
                      <div class="col-sm-3">
                        <?php 
		$last   = $this-> uri->total_segments();
          $inspector_id   = $this->uri->segment($last);?>
          <?php if(is_numeric($id))
                        { ?>
          <select class="form-control"  name="inspector_center[]" id="inspector_center" multiple="" >
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
          <select class="form-control" name="inspector_center[]" id="inspector_center" multiple="" >
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
              <select class="form-control" id="state" name="state"  >
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
           <?php /*?> <div class="col-sm-3">
              <select class="form-control" id="city" name="city[]" multiple=""  >
                <option value="">Select</option>
                <?php if(count($cities) > 0){
                                foreach($cities as $row1){  ?>
                <option value="<?php echo $row1['id']; ?>" <?php if(isset($inspectorState[0]['city'])){
	                         if($row1['id'] == $inspectorState[0]['city']){ ?> selected="selected"
	                         <?php }} ?>> <?php echo $row1['city_name'];?></option>
                <?php } } ?>
              </select>
            </div><?php */?>
            
            
             <div class="col-sm-3">
         <?php 
			$last = $this-> uri->total_segments();
          	$inspector_id   = $this->uri->segment($last);?>
           
          <?php
		  // echo 'inspector_id'.$inspector_id;
		  $inspector_id = base64_decode($inspector_id);  
		   if(is_numeric($inspector_id)){
			
			    ?>
          <select class="form-control" id="city" name="city[]" multiple=""  style="height: 260px;"  >              
         <!-- <select class="form-control"  name="inspector_center[]" id="inspector_center" multiple="" >-->
          <!--  <option value = "-"> -select-</option>-->
            <?php 
              				$centerid = $this->master_model->getRecords("agency_inspector_center",array('inspector_id'=>$inspector_id),'city');
             				
							$centerarr = array();
              				foreach($centerid as $citys)
              				{
               				 		$centerarr[] = $citys['city'];
             				}
							
							print_r($centerarr);
							
                            if($centerarr) 
                            { 
                              foreach($cities as $res) 
                              { ?>
            <option value="<?php echo $res['id']; ?>" <?php if(in_array($res['id'],$centerarr) ){?> selected="selected" <?php }?> > <?php echo $res['city_name']; ?> </option>
            <?php }  }   ?>
          </select>
          <?php }
                        else
                        { ?>
           <select class="form-control" id="city" name="city[]" multiple=""  required>                    
        <!--  <select class="form-control" name="inspector_center[]" id="inspector_center" multiple="" >-->
            <option value=""> <?php echo"-select-"?></option>
            <?php  
                            if($cities) 
                            { 
                              foreach($cities as $res) 
                              { ?>
            <option value="<?php echo $res['id']?>" > <?php echo $res['city_name']; ?> </option>
            <?php } 
                            } ?>
          </select>
          <?php } ?>
        </div>
            
            
          </div>
          <!--/ col-md-6 -->
        </div>
        <!--.box-body-->
      </div>
      <!-- /box body -->
      <div class="box-footer">
        <div class="col-sm-3 col-xs-offset-5">
          <input type="hidden" class="form-control" id="inspector_id" name="inspector_id"  value="<?php echo base64_decode($this->uri->segment($last))?>">
          <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Update">
          <!-- <button type="reset" class="btn btn-default"  name="btnReset" id="btnReset" onclick="resetForm();">Reset</button> -->
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
<script>
var site_path = '<?php echo site_url();?>';
/*Email and Mobile Number Duplication*/
$(document).ready(function(){
	try{
		
		var inspector_email = $('#inspector_email').val();
		var inspector_mobile = $('#inspector_mobile').val();
    var inspector_id = $('#inspector_id').val();
		var validator = $("#userseditForm").validate({
			errorElement: 'div',
			rules: {
				inspector_email:{required: true,remote:site_path+'iibfdra/admin/InspectorMaster/emailduplication_edit?inspector_email='+inspector_email+'&inspector_id='+inspector_id},
				inspector_mobile:{number: true,required: true,remote:site_path+'iibfdra/admin/InspectorMaster/mobileduplication_edit?inspector_mobile='+inspector_mobile+'&inspector_id='+inspector_id},
				inspector_name:{required: true},
				inspector_designation:{required: true},
				'city[]':{required: true},
				state:{required: true},
				},
			messages: {
				inspector_email:{remote: "Email ID already exist."},	
				inspector_mobile:{remote: "Mobile Number already exist."},	
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
});

/* Get City From State */
$('#state').on('change',function(){
var state_code = $(this).val();
    var email=$('#inspector_email').val();
if(state_code){
  $.ajax({
    type:'POST',
    url: site_path+'iibfdra/admin/InspectorMaster/getCity',
    data:'state_code='+state_code,
    success:function(html){
      $('#city').show();
      $('#city').html(html);
    }
  });
  }else{
    $('#city').html('<option value="">Select State First</option>');
  }
});

/*Inspector No. validation allow numbers only.*/
$('#inspector_mobile').keypress(function (event) {
    var keycode = event.which;
    if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
        event.preventDefault();
    }
});

/*Inspector Name Allow letters and whitespaces only.*/
$(document).ready(function(){
    $("#inspector_name").keypress(function(event){
        var inputValue = event.which;
        // allow letters and whitespaces only.
        if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)) { 
            event.preventDefault(); 
        }
    });
});

/*Inspector designation Allow chatercters,whitespaces and dots only.*/
$('#inspector_designation').keyup(function()
{
 var yourInput = $(this).val();
  re = /[`~!@#$%^&*()_|+\-=?;:'",<>\{\}\[\]\\\^\d+$/]/gi;
  var isSplChar = re.test(yourInput);
  if(isSplChar)
  {
    var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",<>\{\}\[\]\\\^\d+$/]/gi, '');
    $(this).val(no_spl_char);
  }
});


</script>
<?php $this->load->view('iibfdra/admin/includes/footer');?>
