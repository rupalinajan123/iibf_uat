
<!--<div class="wrapper">-->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Mou Exam List </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Mou Exam List</a></li>
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
              <?php //print_r($exam_list);?>
               
               <?php if(count($exam_list) > 0)
			   {
				 foreach($exam_list as $examrow)
				 {
				        $showlink = 1;

                $admin_only = $this->master_model->getRecords('bulk_admin_only', array('exam_code' => $examrow['exam_code'],'exam_period' => $examrow['exam_period']));
                // echo $this->db->last_query();die;
                // print_r($admin_only);
                //Condition to showlink if login is of admin for given exam codes by pooja mane (2024-07-22)
                if($admin_only && $this->session->userdata('is_admin') == 'no'){
                  
                  $showlink = 0;
                }

					       
					       ?> 
                  	    <div class="form-group">
                          <?php
                          $today = date("Y-m-d");
                          if ($showlink == 1) {
                              $from_date = $examrow['exam_from_date']; // Assuming you have these dates in your $examrow array
                              $to_date = $examrow['exam_to_date'];
                              if ($today >= $from_date && $today <= $to_date) {
                                  echo '<img src="' . base_url() . 'assets/images/bullet2.gif"> <a href="' . base_url() . 'bulk/BulkApply/examdetails/?excode2=' . base64_encode($examrow['exam_code']) . '&Extype=' . base64_encode($examrow['exam_type']) . '&Period=' . base64_encode($examrow['exam_period']) . '"> ' . $examrow['description'];
                                  if (is_array($elearning_course_code) && !in_array($examrow['exam_code'], $elearning_course_code)) {
                                      echo ' - (' . $examrow['exam_period'] . '/' . date("jS F Y", strtotime($examrow['exam_date'])) . ')';
                                  }
                                  echo '</a>';
                              } else {
                                  echo '<img src="' . base_url() . 'assets/images/bullet2.gif"> ' . $examrow['description'];
                                  if (is_array($elearning_course_code) && !in_array($examrow['exam_code'], $elearning_course_code)) {
                                      echo ' - (' . $examrow['exam_period'] . '/' . date("jS F Y", strtotime($examrow['exam_date'])) . ')'.' <span style="color: red; font-weight: bold;">(Exam Registration not started)</span>';;
                                  }
                              }
                          }
                          ?>
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
