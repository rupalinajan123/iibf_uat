<?php $this->load->view('bulk/includes/header');?>
<?php $this->load->view('bulk/includes/sidebar');?>
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Exam List </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Exam List</a></li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
    <!-- Info boxes -->
      <div class="row mar30">
        <div class="col-md-12">
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Welcome</h3>
              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i> </button>
              </div>
              <!-- /.box-tools --> 
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
              
               <?php if(count($exam_list) > 0)
			   {
				 foreach($exam_list as $examrow)
				 {?> 
                  	   <div class="form-group">
                    	   <img src="<?php echo base_url()?>assets/images/bullet2.gif"> <a href="<?php echo base_url();?>bulk/BulkApply/examdetails/?excode2=<?php echo base64_encode($examrow['exam_code']);?>&Extype=<?php echo base64_encode($examrow['exam_type']);?>"> <?php echo $examrow['description'];?></a>
           				 </div>
             	 <?php }
			 }?>   
              
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>

      </div>
      
         
    </section>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper -->

<?php $this->load->view('bulk/includes/footer');?>