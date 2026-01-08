
<!--<div class="wrapper">-->
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
			<?php if($this->session->flashdata('error')!=''){?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php } if($this->session->flashdata('success')!=''){ ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
            <?php } ?> 
          <div class="box box-info box-solid disabled">
            <div class="box-header with-border">
              <h3 class="box-title">Current Active Exams</h3>
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
				 {
				
					 
					 ?> 
                 
               
                  	    <div class="form-group">
                    	   <img src="<?php echo base_url()?>assets/images/bullet2.gif"> <a href="<?php echo base_url();?>bulk/BulkApply/examdetails/?excode2=<?php echo base64_encode($examrow['exam_code']);?>&Extype=<?php echo base64_encode($examrow['exam_type']);?> &Period=<?php echo base64_encode($examrow['exam_period']);?>"> <?php echo $examrow['description'];?>   <?php
                           if (! in_array($examrow['exam_code'], $elearning_course_code)) {
                             echo "- ( ".$examrow['exam_period']."/".date("jS F  Y",strtotime($examrow['exam_date'])).")";    
                            } 
                          
                          ?> </a>
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
