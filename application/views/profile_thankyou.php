  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
     INDIAN INSTITUTE OF BANKING & FINANCE
        
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>

    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
            (An ISO 21001:2018 Certified )
            <div style="float:right;">
            <a href="<?php echo base_url();?>home/profile/">Back</a>
            </div>
            </div>
           
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
               <div class="form-group">
             
                	<div class="col-sm-9">
                   Your application saved successfully.<br><br><strong>Your Membership No is</strong> <?php echo $application_number;?> <strong>and Your password is </strong><?php echo $password?><br><br>Please note down your Membership No and Password for further reference.<br> <br>You may print or save membership registration page for further reference.<br><br>Please ensure proper Page Setup before printing.<br><br>Click on Continue to print registration page.<br><br>You can save system generated application form as PDF for future reference<br>
									
                        <div style="text-align:left">
                        <a href="<?php echo base_url()?>home/downloadeditprofile/">Save as pdf</a> &nbsp; &nbsp;
                        <a href="javascript:void(0);" id="print_id" onClick="edit_profile_preview();">Continue</a>
                        </div>
                    </div>
                    
                     
                    
                </div>
           
                </div>
                
               </div> <!-- Basic Details box closed-->
                 
                
             
             
        </div>
      </div>
     
      
      
    </section>
 
  </div>
  
<!-- Div to print  -->
<!--<div class="content-wrapper" id="print_div" style="display:none">
    <section class="content-header">
      <h1>
     INDIAN INSTITUTE OF BANKING & FINANCE
        
      </h1>
    </section>
	<form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
            (An ISO 21001:2018 Certified )
            </div>
            <div class="box-body">
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"></label>
                	<div class="col-sm-2">
                    <div style="text-align:justify;width:600px;">Your application saved successfully.<br><br><strong>Your Membership No is</strong> <?php echo $application_number;?> <strong>and Your password is </strong><?php echo $password?><br><br>Please note down your Membership No and Password for further reference.<br> <br>You may print or save membership registration page for further reference.<br><br>Please ensure proper Page Setup before printing.<br><br>Click on Continue to print registration page.<br><br>You can save system generated application form as PDF for future refence</div><br>
                    </div>
                </div>
                </div>
               </div>
        </div>
      </div>
    </section>
    </form>
  </div>-->

<!--<script>
function printDiv(divName) {
     var printContents = document.getElementById('print_div').innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>-->  
<!--<script>
   history.pushState(null, null, document.title);
   window.addEventListener('popstate', function () {
       history.pushState(null, null, document.title);
   });
 </script>-->