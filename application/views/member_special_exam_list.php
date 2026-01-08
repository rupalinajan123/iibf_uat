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

      <?php
      if($this->session->flashdata('error_invalide_exam_selection')){
        ?>
        <br>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error_invalide_exam_selection'); ?></div>
        <?php
      }
      ?>

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
               <?php 
       $associatedinstitute = isset($associatedinstitute) ? $associatedinstitute : '';  
       if(count($exam_list) > 0)
			 {
				 foreach($exam_list as $examrow)
         {
          $hide_exam_flag = 1;

          $exam_name_append = '';
          if($examrow['exam_code'] == "101" || $examrow['exam_code'] == "1046" || $examrow['exam_code'] == "1047"){
            $exam_name_append = '<span style="font-weight: bold;">(for BC agents onboarded with Banks before 1st April 2024)</span>';
          }

          ## Condition added on 12 May to hide exam links from non eligible members
           /*if($examrow["exam_code"] != 21 && $examrow["exam_code"] != 42 && $examrow["exam_code"] != 992 && $examrow["exam_code"] != 60 && $examrow["exam_code"] != 63 && $examrow["exam_code"] != 65 && $examrow["exam_code"] != 68 && $examrow["exam_code"] != 69 && $examrow["exam_code"] != 70 && $examrow["exam_code"] != 71) { */
           
           ## Above commented by pratibha on 9 Nov 2021 to show JAIIB links
           /*if($examrow["exam_code"] != 60 && $examrow["exam_code"] != 63 && $examrow["exam_code"] != 65 && $examrow["exam_code"] != 68 && $examrow["exam_code"] != 69 && $examrow["exam_code"] != 70 && $examrow["exam_code"] != 71)*/
//echo $_SERVER['SERVER_ADDR'];
           if($examrow["exam_code"] != 75){
            
            $valid_ExamCode_InstituteCode_arr = $this->config->item('VALID_EXAMCODE_INSTITUTECODE_ARR');
            $valid_ExamCode_InstituteCode_KARUR_BANK_arr = $this->config->item('VALID_EXAMCODE_INSTITUTECODE_ARR_KARUR_BANK');
            

            if(count($valid_ExamCode_InstituteCode_arr) > 0){
              foreach($valid_ExamCode_InstituteCode_arr as $res_code){
                $inst_code_arr = $res_code["inst_code_arr"];
                $exam_code_arr = $res_code["exam_code_arr"];
                
                if(in_array($examrow['exam_code'],$exam_code_arr)){
                  
                  if(!in_array($associatedinstitute,$inst_code_arr)){
                    $hide_exam_flag = 0;
                  }
                } 

              }
            }

            if(count($valid_ExamCode_InstituteCode_KARUR_BANK_arr) > 0){
              foreach($valid_ExamCode_InstituteCode_KARUR_BANK_arr as $res_code){
                $inst_code_arr = $res_code["inst_code_arr"];
                $exam_code_arr = $res_code["exam_code_arr"];
                
                if(in_array($examrow['exam_code'],$exam_code_arr)){
                  
                  if(!in_array($associatedinstitute,$inst_code_arr)){
                    $hide_exam_flag = 0;
                  }
                } 

              }
            }

           if($hide_exam_flag == 1){ 
            //182.73.101.70
                $get_ip_address = '';
                $set_client_ip_address = array('182.73.101.70','115.124.115.75','106.216.246.171');
                $exam_codes_chk = array(1002,1003,1004,1005,1009,1013,1014,1006,1007,1008,1011,1012,1017,1019,1020,2027,1058);
                $get_ip_address = get_ip_address();
                
                //if(in_array($examrow['exam_code'],$exam_codes_chk) && in_array($get_ip_address,$set_client_ip_address) )
                if( $examrow['exam_code'] == 157 )
                {
                  ?> 
                  <div class="form-group">
                         <img src="<?php echo base_url()?>assets/images/bullet2.gif"> <a title="<?php echo $get_ip_address; ?>" href="<?php echo base_url();?>Home/examdetails/?excode2=<?php echo base64_encode($examrow['exam_code']);?>&Extype=<?php echo base64_encode($examrow['exam_type']);?>"> <?php echo $examrow['description']." ".$exam_name_append;?></a>
                  </div>
                  <?php
                }
                //else{
                  ?>
                  <!-- <div class="form-group">
                         <img src="<?php echo base_url()?>assets/images/bullet2.gif"> <a title="<?php echo $get_ip_address; ?>" href="<?php echo base_url();?>Home/examdetails/?excode2=<?php echo base64_encode($examrow['exam_code']);?>&Extype=<?php echo base64_encode($examrow['exam_type']);?>"> <?php echo $examrow['description']." ".$exam_name_append;?></a>
                  </div> --> 
                <?php //} ?>
                  
                  <?php 
                } 
                
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