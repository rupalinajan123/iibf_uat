<?php $this->load->view('iibfdra/admin/includes/header');?>
<?php $this->load->view('iibfdra/admin/includes/sidebar');?>
<!-- Content Wrapper. Contains page content -->
<style type="text/css">
  .note-error {
    color: rgb(185, 74, 72);
    font-size: small;
  }
  .box-header {padding: 2px 10px 3px 10px;}
  .chnage_psw_box span.fa.field-icon {position: absolute;right: 1.5em;top: 0.5em;}
</style>
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
            <?php } 

              if (count($error_msg) > 0) {
                foreach ($error_msg as $key => $value) { ?>
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $value; ?>
                  </div>

              <?php }
              } ?>

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
						
						<!--########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################-->
						<div class="form-group">								
							<label for="" class="col-sm-2 control-label"> Type <span class="mandatory-field">*</span></label>
							<div class="col-sm-3">
								<label class="radio-inline" style="padding-top: 0;margin-top: -8px; margin-right:20px;">
									<input type="radio" name="batch_online_offline_flag" id="batch_online_offline_flag0" <?php if($inspectorRes['batch_online_offline_flag'] == 0) { echo "checked"; } ?> value="0" required onchange="batch_online_location_show(this.value)"> Offline
								</label>
								<label class="radio-inline" style="padding-top: 0;margin-top: -8px;"> 
									<input type="radio" name="batch_online_offline_flag" id="batch_online_offline_flag1" <?php if($inspectorRes['batch_online_offline_flag'] == 1) { echo "checked"; } ?> value="1" required onchange="batch_online_location_show(this.value)"> Online
								</label>
								<span class="error"></span>
							</div>
						</div>
						<!--########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################-->
							
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
			<div id="batch_online_location_outer" style="display:none;">
      <div class="box-header with-border">
        <h3 class="box-title">Location Assignment</h3>
      </div>
      <div class="box-body">
        <div class="col-md-12">
          <div class="form-group">
            <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
            <div class="col-sm-3">
              <?php 
                //echo '<pre>';print_r($inspectorStates);
                //echo '#####'. $inspectorStates[0]['state'];
                //echo '###'. Sizeof($inspectorStates);
              $inspector_id = base64_decode($inspector_id);  
              $this->db->distinct('state');
              $this->db->where('inspector_id',$inspector_id);
              $inspectorStates = $this->Master_model->getRecords('agency_inspector_center','','state');

              $inspectorStates_arr = array();
              foreach($inspectorStates as $inspectorStates)
               {
                          $inspectorStates_arr[] = $inspectorStates['state'];
               }
              ?>
              <select class="form-control" id="state" name="state[]"  multiple="">
                <option value="" >Select</option>
                <?php 
                     if(count($states) > 0){
                      $i= 0;
                     foreach($states as $row){ ?>
                      <option value="<?php echo $row['state_code'];?>" 
                        
                        <?php if(in_array($row['state_code'],$inspectorStates_arr) ){?> selected="selected" <?php }?>

                      >
                        <?php echo $row['state_name'];?>
                       </option>
                <?php $i++; } } ?>
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
    		    if(is_numeric($inspector_id)){?>

              <input type="hidden" id="inspector_id_prv" value="<?=$inspector_id?>">
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
    							
    						//print_r($cities);
    							
                if($centerarr) 
                { 
                  foreach($cities as $res) 
                  {
                    foreach($res as $res) {?>
                      <option value="<?php echo $res['id']; ?>" <?php if(in_array($res['id'],$centerarr) ){?> selected="selected" <?php }?> > <?php echo $res['city_name']; ?> </option>
                    <?php } 
                  } 
                }   ?>
              </select>
              <?php }
              else { ?>
                <select class="form-control" id="city" name="city[]" multiple=""  required>                    
                <!--  <select class="form-control" name="inspector_center[]" id="inspector_center" multiple="" >-->
                <option value=""> <?php echo"-select-"?></option>
                <?php if($cities) { 
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
			</div>

			<div class="box box-info">
        <div class="box-header with-border">
          <!-- tools box -->
          <div class="pull-right">
            <button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="collapse" data-target="#collapseExample">
            <i class="fa fa-plus"></i></button>
          </div>
          <!-- /. tools -->
          <h3 class="box-title">Change Password</h3>
        </div>

        <div class="box-body">
          <div class="col-md-12 collapse" id="collapseExample">

            <div class="form-group chnage_psw_box">
              <label for="roleid"  class="col-sm-2 control-label">New Password <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <input type="password" class="form-control form-control-lg border-left-0" id="new_password" name="new_password" value="" placeholder="New Password" >
                <span toggle="#new_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>         
              </div>
              <label for="roleid"  class="col-sm-2 control-label">Confirm Password <span style="color:#F00">*</span></label>
              <div class="col-sm-3">
                <input type="password" class="form-control form-control-lg border-left-0" id="confirm_password" name="confirm_password" value="" placeholder="Confirm Password">
                <span toggle="#confirm_password" class="fa fa-fw fa-eye field-icon toggle-password1"></span>         
                <i class="note-error" id="confirm_password_error"></i>
              </div>
            </div>
           
          </div>
        </div>
      </div>

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
	<!--########## START : CODE ADDED BY SAGAR ON 18-08-2020 ###################-->
	function batch_online_location_show(flag)
	{
		if(flag == 0) 
		{ 
			$("#batch_online_location_outer").css("display","block"); 
		}
		else 
		{ 
			$("#batch_online_location_outer").css("display","none"); 
		}			
	}
	
	$(document).ready(function()
	{
    var selected_flag_val = $("input[type=radio][name='batch_online_offline_flag']:checked").val();
		if(typeof  selected_flag_val === 'undefined') { var flag = 0; } else { var flag = selected_flag_val; }
		batch_online_location_show(flag);

    /*$('#new_password').on('blur',function(){
      var new_password = $('#new_password');
      var confirm_password = $('#confirm_password');

      if(new_password != confirm_password){
        $('#confirm_password_error').text('New Password and Confirm Password are not same.');
        return false;
      }
      else{
        return true;
      }
    });*/

    

	});
	<!--########## END : CODE ADDED BY SAGAR ON 18-08-2020 ###################-->
	
	
var site_path = '<?php echo site_url();?>';
/*Email and Mobile Number Duplication*/
$(document).ready(function(){

  $(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

  $(".toggle-password1").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

  jQuery.validator.addMethod("check_password", function(confirm_password, element){
    var new_password = $('#new_password').val();
    //var confirm_password = $('#confirm_password');
    //console.log(new_password+'--'+confirm_password);
    if(new_password != confirm_password){
      //$('#confirm_password_error').text('New Password and Confirm Password are not same.');
      return false;
    }
    else{
      return true;
    }
  }, "New Password and Confirm Password are not same."); 

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
        confirm_password: { check_password: true  }
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
    var inspector_id_prv=$('#inspector_id_prv').val();
//alert(inspector_id_prv);
if(state_code){
  $.ajax({
    type:'POST',
    url: site_path+'iibfdra/admin/InspectorMaster/getCity',
    data:'state_code='+state_code+'&inspector_id_prv='+inspector_id_prv,
    success:function(html){
      //alert(html);
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
