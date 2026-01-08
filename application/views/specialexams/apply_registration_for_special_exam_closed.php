  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Access Denied 
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
    
    <?php 
		if($this->session->userdata('memtype')=='NM')
		{	$class = "NonMember";	}
        else
        {	$class = "Home";	}
    ?>
    
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
            <div style="float:right;">

            </div>
              <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
               <div style="color:#F00">
			   <?php 
			   	echo $check_eligibility;
			   ?>  
               </div>     
              </div>
               </div> <!-- Basic Details box closed-->
                 
     </div>
  </div>
     
      
      
    </section>

  </div>
  
<!-- Data Tables -->