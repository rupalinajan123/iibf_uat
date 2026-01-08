  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Apply for Exams
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
              <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
               <?php if(count($exam_list) > 0)
			   {
				 foreach($exam_list as $examrow)
				 {
					## Condition added on 12 May to hide exam links from non eligible members
					 /*if($examrow["exam_code"] != 21 && $examrow["exam_code"] != 42 && $examrow["exam_code"] != 992 && $examrow["exam_code"] != 60 && $examrow["exam_code"] != 63 && $examrow["exam_code"] != 65 && $examrow["exam_code"] != 68 && $examrow["exam_code"] != 69 && $examrow["exam_code"] != 70 && $examrow["exam_code"] != 71) { */
					 
					 ## Above commented by pratibha on 9 Nov 2021 to show JAIIB links
					 /*if($examrow["exam_code"] != 60 && $examrow["exam_code"] != 63 && $examrow["exam_code"] != 65 && $examrow["exam_code"] != 68 && $examrow["exam_code"] != 69 && $examrow["exam_code"] != 70 && $examrow["exam_code"] != 71)*/
					if($examrow["exam_code"] != 75){
					 ?> 
                  	   <div class="form-group">
                    	   <img src="<?php echo base_url()?>assets/images/bullet2.gif"> <a href="<?php echo base_url();?>Home/examdetails/?excode2=<?php echo base64_encode($examrow['exam_code']);?>&Extype=<?php echo base64_encode($examrow['exam_type']);?>"> <?php echo $examrow['description'];?></a>
           				 </div>
             	 <?php } }
			 }
			 else
			{
			  echo '<h4 style="color:#F00">No Records</h4>';
			}
			 ?>      
                </div>
                
               </div> <!-- Basic Details box closed-->
                 
     </div>
  </div>
     
      
      
    </section>

  </div>
  
<!-- Data Tables -->