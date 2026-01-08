<?php $this->load->view('bulk/admin/includes/header');?>
<?php $this->load->view('bulk/admin/includes/sidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Institution Master
      </h1>
     <?php echo $breadcrumb; ?>
    </section>
	<form class="form-horizontal" name="addForm" id="addForm" action="" method="post" data-parsley-validate="parsley"> 
    <!-- Main content -->
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            <?php //echo validation_errors(); ?>
              <?php if($this->session->flashdata('error')!=''){?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                <?php echo $this->session->flashdata('success'); ?>
                </div>
             <?php } ?> 
                
                   <div class="form-group">
                	<label for="institute_code" class="col-sm-3 control-label">Institute Code <span style="color:#F00">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="institute_code" name="institute_code" placeholder="Institute Code" required value="<?php echo $institutionRes['institute_code'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-type="number" data-parsley-checkcode_inst data-parsley-trigger-after-failure="focusout"> 
                             <span class="error"><?php //echo form_error('institute_code');?></span>
                        </div>
                    </div>
					<div class="form-group">
                    <label for="institute_name" class="col-sm-3 control-label">Institute Name <span style="color:#F00">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="institute_name" name="institute_name" required placeholder="Institute Name" value="<?php echo $institutionRes['institute_name'];?>"  onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('institute_name');?></span>
                        </div>
                    </div>
                
                <!--<div class="form-group">
                	<label for="category_code" class="col-sm-2 control-label">Category Code *</label>
                         <div class="col-sm-3">
                            <input type="text" class="form-control" id="category_code" name="category_code" placeholder="Category Code" required value="<?php echo $institutionRes['category_code'];?>"  onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/">
                             <span class="error"><?php echo form_error('category_code');?></span>
                        </div>
                </div>-->
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Addressline1<span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="address1" name="address1" placeholder="Address line1" required value="<?php echo set_value('main_address1');?>"  data-parsley-maxlength="75" maxlength="75" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/"  >
                    </div> 
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Addressline2</label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="address2" name="address2" placeholder="Address line2"  value="<?php echo set_value('main_address2');?>"  data-parsley-maxlength="75" maxlength="75" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" >
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div> 
                </div> 
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Addressline3</label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="address3" name="address3" placeholder="Address line3"  value="<?php echo set_value('main_address3');?>"  data-parsley-maxlength="75" maxlength="75" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" >
                      <span class="error"><?php //echo form_error('addressline3');?></span>
                    </div> 
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Addressline4</label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="address4" name="address4" placeholder="Address line4"  value="<?php echo set_value('main_address4');?>" data-parsley-maxlength="75" maxlength="75" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/" >
                      <span class="error"><?php //echo form_error('addressline4');?></span>
                    </div>
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">District<span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                        <input type="text" class="form-control" id="address5" name="address5" placeholder="District" required value="<?php echo set_value('address5');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" >
                      <span class="error"><?php //echo form_error('district');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">City <span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
                        <input type="text" class="form-control" id="address6" name="address6" placeholder="Enter City" required value="<?php echo set_value('address6');?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" maxlength="30" >
                      <span class="error"><?php //echo form_error('city');?></span>
                    </div>
                </div>
				
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State <span style="color:#F00">*</span></label>
                	<div class="col-sm-3">
                   <select class="form-control" id="ste_code" name="ste_code" required >
                    <option value="">Select</option>
                    <?php if(count($states) > 0){
                                foreach($states as $row1){ 	?>
                    <option value="<?php echo $row1['state_code'];?>" <?php echo  set_select('ste_code', $row1['state_code']); ?>><?php echo $row1['state_name'];?></option>
                    <?php } } ?>
                  </select>
                    
                    <input hidden="statepincode" id="statepincode" value="">
      
                    </div> 
                     <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                   
                     <div class="col-sm-3">
                        <input type="text" class="form-control" id="pin_code" name="pin_code" placeholder="Pincode/Zipcode" required value="<?php echo set_value('pin_code');?>"  data-parsley-maxlength="6" maxlength="6" size="6" data-parsley-checkpin_inst_addr data-parsley-type="number" data-parsley-trigger-after-failure="focusout" > (Max 6 digits)
                         <span class="error"><?php //echo form_error('pincode');?></span>
                    </div>
                </div>
				
				<div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">phone</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone no"  value="<?php echo set_value('phone');?>" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger-after-failure="focusout" >
                    <span class="error">
                    <?php //data-parsley-checkinst_phone  //echo form_error('nameOfBank');?>
                    </span> 
					</div>
                </div>
				
				<div class="form-group">
                  <label for="roleid" class="col-sm-3 control-label">Mobile no</label>
                    <div class="col-sm-6">
                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile no"  value="<?php echo set_value('mobile');?>" data-parsley-type="number" data-parsley-minlength="10" data-parsley-maxlength="10" data-parsley-trigger-after-failure="focusout" >
                    <span class="error">
                    <?php //data-parsley-checkinst_mobile_no //data-parsley-pattern="[0-9 _,]*" //data-parsley-type="number"//echo form_error('nameOfBank');?>
                    </span> 
					</div>
					<!--(Comma separated values)-->
                </div>
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email id<span style="color:#F00">*</span></label>
                	<div class="col-sm-6">
					   <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email id"  data-parsley-type="email" value="<?php echo set_value('email');?>"  data-parsley-maxlength="80" required  data-parsley-trigger-after-failure="focusout" >
                      <span class="error"><?php //data-parsley-checkinst_head_email  //echo form_error('email');?></span>
                    </div>
                </div>
               
			   
			   <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Zone code</label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="zone_code" name="zone_code" placeholder="Enter Zone code"  value="<?php echo set_value('main_address2');?>"  data-parsley-maxlength="10" maxlength="10" data-parsley-pattern="/^[a-zA-Z/ ]+$/" >
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div> 
                </div> 
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Contact Person Name</label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="coord_name" name="coord_name" placeholder="Enter Contact Person Name"  value="<?php echo set_value('coord_name');?>"  data-parsley-maxlength="80" maxlength="80" data-parsley-pattern="/^[a-zA-Z/ ]+$/" >
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div> 
                </div>
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Contact Person Designation</label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter Contact Person Designation"  value="<?php echo set_value('designation');?>"  data-parsley-maxlength="80" maxlength="80" data-parsley-pattern="/^[a-zA-Z/ ]+$/" >
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div> 
                </div> 
				
				<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">GSTIN No</label>
                	<div class="col-sm-6">
                       <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="Enter GSTN No"  value="<?php echo set_value('gstin_no');?>"  data-parsley-maxlength="80" maxlength="80" data-parsley-pattern="/^[a-zA-Z-0-9/ ]+$/">
                      <span class="error"><?php //echo form_error('addressline2');?></span>
                    </div> 
                </div> 
				
            </div>
             
             <div class="box-footer">
                <div class="col-sm-2 col-xs-offset-5">
                  <?php $last = $this->uri->total_segments();
						$id = $this->uri->segment($last);
				?>
		
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(is_numeric($id)){ echo 'Update';}else{ echo 'Add';} ?>">
                    <a href="<?php echo base_url();?>bulk/admin/InstitutionMaster" class="btn btn-default pull-right">Back</a>
                 </div>
              </div>
           </div>
        </div>
      </div>
    </section>
    </form>
  </div>
  
<script src="<?php echo base_url()?>assets/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/datepicker/datepicker3.css">

<script src="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/admin/plugins/timepicker/bootstrap-timepicker.min.css">
<script src="<?php echo base_url()?>assets/js/parsley.min.js"></script>
<!--<script src="<?php echo base_url()?>js/validation.js"></script>-->
<script src="<?php echo base_url();?>js/validation_dra_register.js?<?php echo time(); ?>"></script> 
<?php //$this->load->view('bulk/admin/includes/footer');?>