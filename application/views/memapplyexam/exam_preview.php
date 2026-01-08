  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      </h1>
      <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>-->
    </section>
    <?php 
	 	/*if($this->session->userdata['examinfo']['fee']=='')
		{$function = "saveexam";	}
        else
        {	$function = "Msuccess";	}*/
		$function = "Msuccess";
    ?>
	<form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Applyexam/<?php echo $function;?>/">
   <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('mregid_applyexam');?>"> 
    <input type="hidden" name="processPayment" id="processPayment" value="1"> 
     
   
    <section class="content">
      <div class="row">
       
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
             <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                	<div class="col-sm-1">
                	 <?php echo $user_info[0]['regnumber'];?>
                    </div>
                </div>-->
                <?php if(in_array($this->session->userdata('examcode'),$this->config->item('skippedAdmitCardForExams'))) {

                    ?>
                          <div class="form-group col-md-2">&nbsp;</div>
                          <div class="form-group col-md-2">
                           
                              <div class="col-sm-2">
                                <label for="roleid" class="col-sm-3 control-label">
                                  <?php 
                                      if(is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'p')))
                                        { ?>
                                            <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('mregnumber_applyexam'),'p');?><?php echo '?'.time(); ?>" height="100" width="100" >
                                            <label class="photolabel">Photo</label>
                                      <?php 
                                        }
                                      else
                                      { 
                                        ?>
                                          <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                                        <?php   
                                      } ?>
                                </label>
                                <span class="error"><?php //echo form_error('gender');?></span>
                              </div>
                              
                          </div>
                          
                        <div class="form-group col-md-2">
                         
                            <div class="col-sm-2">
                              <label for="roleid" class="col-sm-3 control-label">
                                  <?php 
                                      if(is_file(get_img_name($this->session->userdata('mregnumber_applyexam'),'s')))
                                    {?>
                                                <img src="<?php echo base_url();?><?php echo get_img_name($this->session->userdata('mregnumber_applyexam'),'s');?><?php echo '?'.time(); ?>" height="100" width="100">
                                                <label class="photolabel">Signature</label>
                                              <?php 
                                    }
                                    else
                                    {?>
                                              <img src="<?php echo base_url();?>assets/images/default1.png<?php echo '?'.time(); ?>" height="100" width="100" >
                                            <?php 
                                    }?>   
                                </label>
                          
                                <span class="error"><?php //echo form_error('gender');?></span>
                              </div>
                          </div>
                          
                          <?php } ?>
                <div class="col-md-12">&nbsp;</div>
               <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name </label>
                     <div class="col-sm-3">
					<?php echo $user_info[0]['firstname'];?>
                         <span class="error"><?php //echo form_error('firstname');?></span>
                    </div>
                    
                </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                	<div class="col-sm-5">
                    <?php if($user_info[0]['middlename']!=''){echo $user_info[0]['middlename'];}else{echo '-';}?>
                      <span class="error"><?php //echo form_error('middlename');?></span>
                    </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                	<div class="col-sm-5">
                    <?php if($user_info[0]['lastname']!=''){echo $user_info[0]['lastname'];}else{echo '-';}?>
                      <span class="error"><?php //echo form_error('lastname');?></span>
                    </div><!--(Max 30 Characters) -->
                </div>
                
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Phone : STD Code </label>
                	<div class="col-sm-2">
                     <?php if($user_info[0]['stdcode']){echo $user_info[0]['stdcode'];}else{echo '-';}?>
                     <?php if($user_info[0]['office_phone']){echo $user_info[0]['office_phone'];}else{echo '-';}?>
                      <span class="error"><?php //echo form_error('stdcode');?></span>
                    </div>
                    
                </div>-->
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Mobile *</label>
                	<div class="col-sm-5">
                      <?php echo $this->session->userdata['examinfo']['mobile'];?>
                      <span class="error"><?php //echo form_error('mobile');?></span>
                    </div>
                </div>-->
                
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Email *</label>
                	<div class="col-sm-5">
                    <?php echo $this->session->userdata['examinfo']['email'];?>
                      <span class="error"><?php //echo form_error('email');?></span>
                    </div>
                </div>-->
                </div>
                
               </div> <!-- Basic Details box closed-->
                 <div class="box box-info">
                 <div class="box-header with-border">
              <h3 class="box-title">Exam Details:</h3>
            </div>
            
         

            <div class="box-body">
                <?php if( $this->session->userdata('examcode') == 1009 ) { ?>
                    <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Institute Name</label>
                        <div class="col-sm-5 ">
                         <?php //echo $this->session->userdata['examinfo']['exname'];?>
                         
                            <?php echo $this->session->userdata['examinfo']['selinstitutionname'];?>
                            
                         <div id="error_dob"></div>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                	<div class="col-sm-5 ">
                     <?php //echo $this->session->userdata['examinfo']['exname'];?>
                     
                     <?php echo str_replace("\\'","",html_entity_decode($this->session->userdata['examinfo']['exname']));?>
                        
                         <?php echo $this->session->userdata['examinfo']['selected_elect_subname'];?>
                     <div id="error_dob"></div>
                    </div>
                </div>
                
                  <?php 
                  $skipped_admitcard_examcodes =$this->config->item('skippedAdmitCardForExams'); //priyanka d >> 16-jan-25 to skip admitcard
				if(base64_decode($this->session->userdata['examinfo']['excd'])!=101 && base64_decode($this->session->userdata['examinfo']['excd'])!=1046 && base64_decode($this->session->userdata['examinfo']['excd'])!=1047)
				{?>
                    <div class="form-group grayDiv">
                          	  <label for="roleid" class="col-sm-3 control-label"><strong class="black_clr">Subject(s)</strong></label>
                              <?php
                               if(!in_array(base64_decode($this->session->userdata['examinfo']['excd']),$skipped_admitcard_examcodes))  { ?>
                                <div class="col-sm-4 text-center">
                                
                                Venue
                                
                                </div>
                                <?php } ?>
                                <div class="col-sm-2 text-center">
                                Date
                                </div>
                                <?php
                               if(!in_array(base64_decode($this->session->userdata['examinfo']['excd']),$skipped_admitcard_examcodes))  { ?>
                                <div class="col-sm-2 text-center">
                                
                                  Time
                                
                                </div>
                                <?php } ?>
                               </div>
                               
              	   <?php 
				   if(count($compulsory_subjects) > 0)
				   {
					   $i=1;
					   foreach($compulsory_subjects as $k=>$v)
					   {
						  $venue_add_finalstring='';
						 $get_venue_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['selCenterName']));
						
						//echo $this->db->last_query();
						//echo $get_venue_details[0]['venue_name'];
						$venue_add=$get_venue_details[0]['venue_name'].'*'.$get_venue_details[0]['venue_addr1'].'*'.$get_venue_details[0]['venue_addr2'].'*'.$get_venue_details[0]['venue_addr3'].'*'.$get_venue_details[0]['venue_addr4'].'*'.$get_venue_details[0]['venue_addr5'].'*'.$get_venue_details[0]['venue_pincode'];
						$venue_add_finalstring= preg_replace('#[\*]+#', ',', $venue_add);
						 
						   ?>
                            <div class="form-group borderDiv">
                          	  <label for="roleid" class="col-sm-3 control-label"><strong class="black_clr"><?php echo $v['subject_name']?></strong></label>
                              <?php
                               if(!in_array(base64_decode($this->session->userdata['examinfo']['excd']),$skipped_admitcard_examcodes))  { ?>
                                <div class="col-sm-4 text-center">
                                
                                <?php echo $venue_add_finalstring;?>
                                
                                </div>
                                <?php } ?>
                                <div class="col-sm-2 text-center">
                                <?php echo date('d-M-Y',strtotime($v['date']));?>
                             
                                </div>
                                
                                <?php
                               if(!in_array(base64_decode($this->session->userdata['examinfo']['excd']),$skipped_admitcard_examcodes))  { ?>
                                <div class="col-sm-2 text-center">
                                
                                 <?php echo $v['session_time'];?>
                                 
                              
                               
                                </div>
                                <?php } ?>
                               </div>
                               
                               
                <?php 
			 	    }
            ?>
            <div class="col-md-12">&nbsp;</div>
            <?php 
					}?>
              <?php 
				}?>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Apply for elearning application?</label>
                	<div class="col-sm-5 ">
                     <?php 
					 
					 	if($this->session->userdata['examinfo']['elearning_flag'] == 'Y'){
							echo 'Yes';
						}else{
							echo 'No';	
						}
					 ?>
                     <div id="error_dob"></div>
                    </div>
                </div>
                
                
                <?php
					
                	if(isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && $this->session->userdata['examinfo']['el_subject'] != 'N'){
						foreach($compulsory_subjects as $k=>$v){
							if (array_key_exists($k,$this->session->userdata['examinfo']['el_subject'])){
				?>
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"><?php echo $v['subject_name'];?></label>
                	<div class="col-sm-5 ">
                     <?php echo 'Yes';?>
                     <div id="error_dob"></div>
                    </div>
                </div>
                <?php } } } ?>
                       
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                	<div class="col-sm-5 ">
                     <?php echo $this->session->userdata['examinfo']['fee'];?>
                     <div id="error_dob"></div>
                    </div>
                </div>
                
                <?php if( $this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047 || $this->session->userdata('examcode') == 991 || $this->session->userdata('examcode') == 997 ) { ?>
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Name of Bank where working as BC</label>
                    <div class="col-sm-5 ">
                     <?php 
                     $get_bank_inst_details=$this->master_model->getRecords('bcbf_old_exam_institute_master',array('institute_id'=>$this->session->userdata['examinfo']['name_of_bank_bc']));
                     echo $get_bank_inst_details[0]['institute_name'];?>
                     <div id="error_dob"></div>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Date of commencement of operations/joining as BC</label>
                    <div class="col-sm-5 ">
                     <?php echo ($this->session->userdata['examinfo']['date_of_commenc_bc'] != "" ? date("d-m-Y",strtotime($this->session->userdata['examinfo']['date_of_commenc_bc'])) : '');?>
                     <div id="error_dob"></div>
                    </div>
                </div>  
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Bank BC ID No</label>
                    <div class="col-sm-5 ">
                     <?php echo $this->session->userdata['examinfo']['ippb_emp_id'];?>
                     <div id="error_dob"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Bank BC ID Card</label>
                    <?php //print_r($this->session->userdata['examinfo']); ?>
                    <div class="col-sm-5 ">
                        <label for="roleid" class="col-sm-2 control-label"><img src="<?php echo $this->session->userdata['examinfo']['bank_bc_id_card_file_path'];?>"  height="100" width="100"></label>
                        <div id="error_dob"></div>
                    </div>
                </div>
                <?php } ?>
                
                
                 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                	<div class="col-sm-5 ">
                        <?php 
						//$month = date('Y')."-".substr($misc['0']['exam_month'],4)."-".date('d');
                    $month = date('Y')."-".substr($misc['0']['exam_month'],4);
                    echo date('F',strtotime($month))."-".substr($misc['0']['exam_month'],0,-2);
					//echo 'Dec 2020';
             ?>
                        <?php //echo $this->db->userdata['enduserinfo']['eprid'];?>
                 	  <div id="error_dob"></div>
                    </div>
                </div>
                
                <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">GSTIN No.</label>
                	<div class="col-sm-2">
                  
                  <?php echo $this->session->userdata['examinfo']['gstin_no'];?>
                    </div>
                </div>-->
                
               <?php 
			   /*$elective_exam_code= $this->config->item('elective_exam_code');
			   if($this->session->userdata['examinfo']['elected_exam_mode']=='E' && base64_decode($this->session->userdata['examinfo']['excd'])!=21 && !in_array($this->session->userdata('examcode'),$elective_exam_code))
			  {?>
          	      <!--<div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Elective Subject Name</label>
                	<div class="col-sm-5 ">
                         <?php //echo $this->session->userdata['examinfo']['selected_elect_subname'];?>
                  	   <div id="error_dob"></div>
                    </div>
                </div>-->
   				<?php 
			  }*/?>
			     <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Medium *</label>
                	<div class="col-sm-2">
                  <?php 
				  if(count($medium) > 0)
                    {
                        foreach($medium as $mrow)
                        {
                            if($this->session->userdata['examinfo']['medium']==$mrow['medium_code'])
                            {
                                echo $mrow['medium_description'];
                            }	
                         }
                    }?>
                  
                    </div>
                </div>
   
  				 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Centre Name *</label>
                	<div class="col-sm-2" style="color:red;font-size:18px; ">
                   <?php 
					if(isset($center[0]['center_name']))
					{
								echo $center[0]['center_name'];
					}
					?>
                    </div>
                   </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Centre Code *</label>
                	<div class="col-sm-2">
                    <?php echo  $this->session->userdata['examinfo']['txtCenterCode'];?>
                    </div>
                  </div>
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Exam Mode *</label>
                	<div class="col-sm-2">
                    <?php 
					if($this->session->userdata['examinfo']['optmode']=='ON')
					{
						echo 'Online';
					}
					else if($this->session->userdata['examinfo']['optmode']=='OF')
					{
						echo 'Offline';
					}?>
                      <span class="error"><?php //echo form_error('gender');?></span>
                    </div>
        	       </div>
                 
               
                
                 
              <?php if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
			  {?>
             	 <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Place of Work</label>
                	<div class="col-sm-5 ">
                         <?php echo $this->session->userdata['examinfo']['placeofwork'];?>
                     <div id="error_dob"></div>
                  </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">State (Place of Work)</label>
                	<div class="col-sm-5 ">
                     <?php 
				  if(count($states) > 0)
                    {
                        foreach($states as $srow)
                        {
                            if($this->session->userdata['examinfo']['state_place_of_work']==$srow['state_code'])
                            {
                                echo $srow['state_name'];
                            }	
                         }
                    }?>
                </div>
                </div>
                
                
                <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Pin Code (Place of Work)</label>
                	<div class="col-sm-5 ">
                         <?php echo $this->session->userdata['examinfo']['pincode_place_of_work'];?>
                     <div id="error_dob"></div>
                    </div>
                </div>
              <?php 
			  }?>  
			
                <?php /*?> <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Are you a person with benchmark disability of 40% or above (PwBD)</label>
                        <div class="col-sm-3">
                         <?php 
						 if($this->session->userdata['examinfo']['scribe_flag_d']=='Y')
						 {
							echo 'Yes';
						}
						else
						{
							echo 'No';
						}?>
                        </div>
                    </div>
					<?php if($this->session->userdata['examinfo']['disability_value']!='')
					{?>
					<div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Type of Disability </label>
					
				
                        <div class="col-sm-6">
                         <?php 
						 if(!empty($disability_value))
						 {
						  foreach($disability_value as $value)
						  {
						  if($this->session->userdata['examinfo']['disability_value']==$value['code'])
						  {
						     echo $value['disability'];
						  }
						 }
						 }else{
						 
						 echo '';
						 }
					
						?>
                        </div>
                    </div>
					<?php }
						if($this->session->userdata['examinfo']['Sub_menue_disability']!='')
					{?>
					<div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Sub Type of Disability</label>
					
					
                        <div class="col-sm-6">
                         <?php 
						 if(!empty($scribe_sub_disability))
						 {
						  foreach($scribe_sub_disability as $value)
						  {
						  if($this->session->userdata['examinfo']['Sub_menue_disability']==$value['sub_code'])
						  {
						     echo $value['sub_disability'];
						  }
						 }
						 }else{
						 
						 echo '';
						 }
					
						?>
                        </div>
                    </div>
					<?php }?><?php */?>
			
			
			<!-- Benchmark Disability Code Start -->
             <div class="form-group">
			  <label for="roleid" class="col-sm-3 control-label">Person with Benchmark Disability</label>
			  <div class="col-sm-5">
			   <?php if($benchmark_disability_info[0]['benchmark_disability']=='Y'){
			  	echo "Yes";
				  } else{
					echo "No";
				  } ?>
			  </div>
			</div>
			<?php 
			  if($benchmark_disability_info[0]['benchmark_disability']=='Y')
			  {
			  ?>
              <div id="benchmark_disability_div">
				<div class="form-group">
				  <label for="roleid" class="col-sm-3 control-label">Visually impaired</label>
				  <div class="col-sm-5">
				  <?php if($benchmark_disability_info[0]['visually_impaired']=='Y'){
			  		echo "Yes";
				  } else{
					echo "No";
				  } ?>
				  </div>
				</div>
				 <?php 
				  if($benchmark_disability_info[0]['visually_impaired']=='Y')
				  {
				  ?>
				  <div class="form-group"  id="vis_imp_cert_div">
				  <label for="roleid" class="col-sm-3 control-label">Attach scan copy of PWD certificate</label>
				  <div class="col-sm-5">
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/v_'.$this->session->userdata('mregnumber_applyexam').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
				  </div>
					</div>
				  <?php
				  }
				  ?>
				<div class="form-group">
				  <label for="roleid" class="col-sm-3 control-label">Orthopedically handicapped</label>
				  <div class="col-sm-5">
				  <?php if($benchmark_disability_info[0]['orthopedically_handicapped']=='Y'){
			  		echo "Yes";
				  } else{
					echo "No";
				  } ?>
					</div>
				</div>
				  <?php 
				  if($benchmark_disability_info[0]['orthopedically_handicapped']=='Y')
				  {
				  ?>
				  <div class="form-group" id="orth_han_cert_div">
				  <label for="roleid" class="col-sm-3 control-label">Attach scan copy of PWD certificate</label>
				  <div class="col-sm-5">
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/o_'.$this->session->userdata('mregnumber_applyexam').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
				  </div>
				</div>
				  <?php
				  }
				  ?>
				<div class="form-group">
				  <label for="roleid" class="col-sm-3 control-label">Cerebral palsy</label>
				  <div class="col-sm-5">
				  <?php if($benchmark_disability_info[0]['cerebral_palsy']=='Y'){
			  		echo "Yes";
				  } else{
					echo "No";
				  } ?>
					</div>
				</div>
				  <?php 
				  if($benchmark_disability_info[0]['cerebral_palsy']=='Y')
				  {
				  ?>
				  <div class="form-group" id="cer_palsy_cert_div">
				  <label for="roleid" class="col-sm-3 control-label">Attach scan copy of PWD certificate</label>
				  <div class="col-sm-5">
				  <img src="<?php echo base_url();?><?php echo '/uploads/disability/c_'.$this->session->userdata('mregnumber_applyexam').'.jpg' ?><?php echo '?'.time(); ?>" height="100" width="100" >
				  </div>
				</div>
				  <?php
				  }
				  ?>
			</div>
			<?php 
			}
			?>
			<!-- Benchmark Disability Code Close -->	
			
             		 <div class="form-group">
                    <label for="roleid" class="col-sm-3 control-label">Do you intend to use the services of a scribe ?</label>
                        <div class="col-sm-3">
                         <?php 
						 if($this->session->userdata['examinfo']['scribe_flag']=='Y')
						 {
							echo 'Yes';
						}
						else
						{
							echo 'No';
						}?>
                        </div>
                    </div>  
        		<div class="form-group">
              <div class="col-sm-12">
                <label for="roleid" class="col-sm-0 control-label"></label>

                <?php 
              $exam_code_chk = $this->session->userdata('examcode');
              $exam_arr = array(1002,1003,1004,1005,1009,1013,1014,1006,1007,1008,1011,1012,2027,1019,1020,1058); // 1002,1003,1004,1005,1009,1013,1014
              if(!in_array($exam_code_chk, $exam_arr)){ 
              ?>  
                It is mandatory for a candidate to opt the examination centre being his/her place of work. If there is no centre at his/her place of work, shall have to opt the centre nearest to his/her place of work. Result of candidate violating this rule or giving wrong information is liable for cancellation<br>
            <?php } ?>

<!--
B) Since the Institute will not be sending the Admit Letter(hard copy) through post, Correct E-mail address is mandatory for receipt of Admit Letter/Hall Ticket through e-mail.-->
                      <span class="error"><?php //echo form_error('gender');?></span>
                </div>
                </div>
               
             <div class="box-footer">
                  <div class="col-sm-4 col-xs-offset-3">
                <!--     <a href="javascript:void(0);" class="btn btn-info" id="payonline" onclick="javascript:return validate();">Pay Online</a>-->
                   <?php if($function=='saveexam')
				   {?>
						  <input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Save">
				<?php }
				 	else if($function=='Msuccess')
				   {
					   			if($this->config->item('exam_apply_gateway')=='sbi')
							{?>
									<input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Pay Online"> 
							<?php 
							}
							else
							{?>
						<input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Pay Online" onclick="javascript:return validate();"> 
							<?php  
							}
					}?>
                   <a href="javascript:window.history.go(-1);" class="btn btn-info" id="preview">Back</a>
                     <!--<a href="<?php echo base_url();?>Home/examdetails/?excode2=<?php echo base64_encode($this->session->userdata('examcode'));?>" class="btn btn-info" id="preview">Back</a>-->
                    </div>
              </div>
             </div>
     </div>
  </div>
     
      
      </div>
    </section>
 
  
     </form>
     </div>
<!-- Data Tables -->




 

