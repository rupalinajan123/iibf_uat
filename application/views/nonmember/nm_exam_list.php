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

              <?php if($this->session->flashdata('error_nm_without_pass')!=''){ ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                    <?php echo $this->session->flashdata('error_nm_without_pass'); ?>
                </div>
              <?php } ?>

               <?php if(count($exam_list) > 0)
			   {
				 foreach($exam_list as $examrow)
				 {

            /*$get_ip_address = '';
            $set_client_ip_address = array('182.73.101.70','115.124.115.75');
            $exam_codes_chk = array(1002,1003,1004,1005,1009,1013,1014,101,1046,1047);
            $get_ip_address = get_ip_address();
            if(in_array($examrow['exam_code'],$exam_codes_chk) && in_array($get_ip_address,$set_client_ip_address) )*/
            {                  
              $exam_name_append = '';
              if($examrow['exam_code'] == "101" || $examrow['exam_code'] == "1046" || $examrow['exam_code'] == "1047"){
                $exam_name_append = '<span style="font-weight: bold;">(for BC agents onboarded with Banks before 1st April 2024)</span>';
              }
					?>
						<div class="form-group">
            	   <img src="<?php echo base_url()?>assets/images/bullet2.gif"> <a href="<?php echo base_url();?>NonMember/examdetails/?excode2=<?php echo base64_encode($examrow['exam_code']);?>&Extype=<?php echo base64_encode($examrow['exam_type']);?>"> <?php echo $examrow['description']." ".$exam_name_append." - ".$examrow['exam_code'];?></a>
   				 </div>
					<?php 
            }
          }
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