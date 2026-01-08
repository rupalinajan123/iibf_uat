<?php $this->load->view('admin/ippb_dashboard/includes/header');?>
<?php $this->load->view('admin/ippb_dashboard/includes/sidebar');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        IPPB Employee/Agent Add 
      </h1>
     <?php echo $breadcrumb; ?>
    </section>
  <form class="form-horizontal" name="usersAddForm" id="usersAddForm" action="" method="post">
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

             <?php if(isset( $mem_info['regnumber'])){?>
                <div class="form-group">
                  <label for="firstname" class="col-sm-2 control-label">Registered Member Number</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control " id="regnumber" name="regnumber" placeholder="Member Number" required value="<?php echo $mem_info['regnumber'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/" disabled>
                             <span class="error"><?php echo form_error('regnumber');?></span>
                        </div>
                </div>
                <?php } ?>
               
                <div class="form-group">
                  <label for="firstname" class="col-sm-2 control-label">First name *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control " id="firstname" name="firstname" placeholder="First name" required value="<?php echo $mem_info['firstname'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/"  <?php if(isset( $mem_info['regnumber'])){ echo "disabled"; }?>>
                             <span class="error"><?php echo form_error('firstname');?></span>
                        </div>
                    
                    <label for="middlename" class="col-sm-2 control-label">Middle Name</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="middlename" name="middlename"  placeholder="Middle Name" value="<?php echo $mem_info['middlename'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/"  <?php if(isset( $mem_info['regnumber'])){ echo "disabled"; }?>>
                             <span class="error"><?php echo form_error('middlename');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                  <label for="lastname" class="col-sm-2 control-label">Last name *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last name" value="<?php echo $mem_info['lastname'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/"  <?php if(isset( $mem_info['regnumber'])){ echo "disabled"; }?>>
                             <span class="error"><?php echo form_error('lastname');?></span>
                        </div>
                    
                    <label for="email" class="col-sm-2 control-label">Email Id *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="email" name="email" required placeholder="Email Id" value="<?php echo $mem_info['email'];?>"  data-parsley-emailcheckippb data-parsley-pattern="/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/"  <?php if(isset( $mem_info['regnumber'])){ echo "disabled"; }?>>
                             <span class="error"><?php echo form_error('email');?></span>
                        </div>
                </div>
                
                <div class="form-group">
                    <label for="mobile" class="col-sm-2 control-label">Mobile Number *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-selector" id="mobile" name="mobile" placeholder="Mobile Number" required value="<?php echo $mem_info['mobile']; ?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/"  <?php if(isset( $mem_info['regnumber'])){ echo "disabled"; }?> data-parsley-minlength="10" data-parsley-maxlength="10">
                             <span class="error"><?php echo form_error('mobile');?></span>
                        </div>
                

                    <label for="emp_id" class="col-sm-2 control-label">Employee Id *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="emp_id" name="emp_id" placeholder="Employee Id"  value="<?php echo $mem_info['emp_id'];?>" <?php if(isset( $mem_info['emp_id'])){?>disabled <?php }?> data-parsley-pattern="/^[a-zA-Z0-9 ]+$/"  <?php if(isset( $mem_info['emp_id'])){ echo "disabled"; }?>>
                             <span class="error"><?php echo form_error('emp_id');?></span>
                        </div>                    
                </div>
                <!-- POOJA MANE : 13/7/2022 -->
                <!-- <div class="form-group">
                    <label for="branch" class="col-sm-2 control-label">Branch *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="branch" name="branch" placeholder="Branch" required value="<?php echo $mem_info['branch'];?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/"  <?php if(isset( $mem_info['regnumber'])){ echo "disabled"; }?>>
                             <span class="error"><?php echo form_error('branch');?></span>
                        </div>

                   <label for="circle" class="col-sm-2 control-label">Circle  *</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control " id="circle" name="circle" placeholder="Circle" required value="<?php echo $mem_info['circle']; ?>" onkeypress="return (alphanumber(event) && alphanumberctrl(event));" data-parsley-pattern="/^[a-zA-Z0-9 ]+$/"  <?php if(isset( $mem_info['regnumber'])){ echo "disabled"; }?>>
                             <span class="error"><?php echo form_error('circle');?></span>
                        </div>
                </div> -->
                <!-- POOJA MANE : 13/7/2022 -->
             </div>
             
              <div class="box-footer">
                  <div class="col-sm-2 col-xs-offset-5">
                    <?php $last = $this->uri->total_segments();
                        $id = $this->uri->segment($last);
                    ?>
      
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="<?php if(isset($regid)){ echo 'Update';}else{ echo 'Add';} ?>" <?php if(isset( $mem_info['regnumber'])){ echo "disabled"; }?>>
                    <a href="<?php echo base_url();?>admin/ippb/IppbDashboard/" class="btn btn-default pull-right">Back</a>
                     
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
<script src="<?php echo base_url()?>js/validation.js?<?php echo time(); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() 
  {
     $('#usersAddForm').parsley('validate');  
     
     $('#exam_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
     });
     
     $(".timepicker").timepicker({
      showInputs: false
    });
   });  
    // check email duplication for member user
    //var checkval=0;
    // window.Parsley.addValidator('emailcheckippb', function (value, requirement) {
    //  var response = false;
    //  var filter = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
    //  var datastring='email='+value;
    
    //  if(filter.test(value))
    //  {
    //    $.ajax({
    //       url:site_url+'admin/ippb/IppbDashboard/emailduplication/',
    //       data: datastring,
    //       type:'POST',
    //       dataType:'json',
    //       async: false,
    //       success: function(data) {
    //       if(data.ans=="exists")
    //       {
    //         checkval=1;
    //         alert(data.output);
    //         response = false;
    //       }
    //       else
    //       {
    //         checkval=0;
    //         response = true;
    //       }
    //       }
    //     });
    //    return response;
    //  }
    // }, 32)
    
    // .addMessage('en', 'emailcheckippb', 'The email already exists.');

    
  

  /*emplyee exist*/
  $(document ).ready( function() 
      { 
        $("#usersAddForm").validate(  
        {
          rules:
          {
            emp_id: 
            { 
              required : true 
              /*remote: { 
                url: "<?php echo site_url('/admin/ippb/IppbDashboard/check_empid_exist_ajax') ?>", 
                type: "post", 
                async:false,
                data: { emp_id: $("#emp_id").val() }
              } */      
            },  
          },
          messages:
          {
            emp_id: { required : "Please enter Employee Id"/*, remote : "Employee Id is already exist"*/ }
          },
          submitHandler: function(form) 
          {
             form.submit();
          },
          
        });
      });

  $('.input-selector').on('keypress', function(e){
    return e.metaKey || // cmd/ctrl
    e.which <= 0 || // arrow keys
    e.which == 8 || // delete key
    /[0-9]/.test(String.fromCharCode(e.which)); // numbers
  });
  
</script>


</script>
 
<?php $this->load->view('admin/ippb_dashboard/includes/footer');?>