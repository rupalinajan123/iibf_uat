<?php
$disaply_class = '';
if ($this->session->userdata['is_elearning_course']=='y') {
   $disaply_class='hidden';
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
      </h1>
     
   </section>
   <?php 
      
      $function = "Msuccess";
       ?>

      <section class="content">
         <div class="row">
            <div class="col-md-12">
                  <form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>bulk/BulkApply/<?php echo $function;?>/">
      <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('mregid_applyexam');?>"> 
      <input type="hidden" name="processPayment" id="processPayment" value="1"> 
               <!-- Horizontal Form -->
               <div class="box box-info">
                  <div class="box-header with-border">
                     <h3 class="box-title">Basic Details</h3>
                  </div>
                  <!-- /.box-header -->
                  <!-- form start -->
                  <div class="box-body">
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                        <div class="col-sm-1">
                           <?php echo $user_info[0]['regnumber'];?>
                        </div>
                     </div>
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
                        </div>
                        <!--(Max 30 Characters) -->
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Phone : STD Code </label>
                        <div class="col-sm-2">
                           <?php if($user_info[0]['stdcode']){echo $user_info[0]['stdcode'];}else{echo '-';}?>
                           <?php if($user_info[0]['office_phone']){echo $user_info[0]['office_phone'];}else{echo '-';}?>
                           <span class="error"><?php //echo form_error('stdcode');?></span>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Mobile *</label>
                        <div class="col-sm-5">
                           <?php echo $user_info[0]['mobile'];?>
                           <span class="error"><?php //echo form_error('mobile');?></span>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Email *</label>
                        <div class="col-sm-5">
                           <?php echo $user_info[0]['email'];?>
                           <!--(For correction/updation of your Email id and Mobile no., use your Edit Profile available under Member Login.)-->
                           <span class="error"><?php //echo form_error('email');?></span>
                        </div>
                     </div>
                     <?php if($this->session->userdata['examinfo']['bank_emp_id'] != '') { ?> <?php } ?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Bank Employee Id <span style="color:#F00">*</span></label>
                        <div class="col-sm-5">
                           <?php echo $this->session->userdata['examinfo']['bank_emp_id'];?>
                        </div>
                     </div>
                     <?php if(count($bulk_payment_scale_master) > 0) { ?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Pay Scale <span style="color:#F00">*</span> </label>
                        <div class="col-sm-2">
                           <?php if(count($bulk_payment_scale_master) > 0){
                              foreach($bulk_payment_scale_master as $row1){   ?>
                           <?php if($this->session->userdata['examinfo']['bank_scale']==$row1['id']){echo  $row1['pay_scale'];}?>
                           <?php } } ?>
                        </div>
                     </div>
                     <?php } ?>
                     <?php if(count($bulk_zone_master) > 0) { ?>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Bank Zone <span style="color:#F00">*</span></label>
                        <div class="col-sm-2">
                           <?php if(count($bulk_zone_master) > 0){
                              foreach($bulk_zone_master as $row1){  ?>
                           <?php if($this->session->userdata['examinfo']['bank_zone']==$row1['zone_id']){echo  $row1['zone_code'];}?>
                           <?php } } ?>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
               </div>
               <!-- Basic Details box closed-->
               <div class="box box-info">
                  <div class="box-header with-border">
                     <h3 class="box-title">Exam Details:</h3>
                  </div>
                  <div class="box-body">
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Exam Name</label>
                        <div class="col-sm-5 ">
                           <?php //echo $this->session->userdata['examinfo']['exname'];?>
                           <?php echo str_replace("\\'","",html_entity_decode($this->session->userdata['examinfo']['exname']));?>
                           <?php if(!empty($this->session->userdata['examinfo']['selected_elect_subname']))
                              {
                              echo $this->session->userdata['examinfo']['selected_elect_subname'];
                              }
                              ?>
                           <div id="error_dob"></div>
                        </div>
                     </div>
                     <?php 
                        if($this->session->userdata('examcode')!=101 
                      && $this->session->userdata('is_elearning_course')=='n'                           
                        && $this->session->userdata('examcode')!=1010
                        && $this->session->userdata('examcode')!=10100
                        && $this->session->userdata('examcode')!=101000
                        && $this->session->userdata('examcode')!=1010000
                        && $this->session->userdata('examcode')!=10100000 ) /* && $this->session->userdata('examcode')!=996 */
                        {?>
                     <div class="form-group grayDiv">
                        <label for="roleid" class="col-sm-3 control-label"><strong class="black_clr">Subject(s)</strong></label>
                        <div class="col-sm-4 text-center">
                           Venue
                        </div>
                        <div class="col-sm-2 text-center">
                           Date
                        </div>
                        <div class="col-sm-2 text-center">
                           Time
                           </select>
                        </div>
                     </div>
                     <?php 
                        if(count($compulsory_subjects) > 0)
                        {
                         $i=1;
                         foreach($compulsory_subjects as $k=>$v)
                         {
                         $venue_add_finalstring='';
                        $get_venue_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue'],'exam_date'=>$v['date'],'session_time'=>$v['session_time']));
                        
                        //echo $this->db->last_query();
                        //echo $get_venue_details[0]['venue_name'];
                        $venue_add=$get_venue_details[0]['venue_name'].'*'.$get_venue_details[0]['venue_addr1'].'*'.$get_venue_details[0]['venue_addr2'].'*'.$get_venue_details[0]['venue_addr3'].'*'.$get_venue_details[0]['venue_addr4'].'*'.$get_venue_details[0]['venue_addr5'].'*'.$get_venue_details[0]['venue_pincode'];
                        $venue_add_finalstring= preg_replace('#[\*]+#', ',', $venue_add);
                        
                          ?>
                     <div class="form-group borderDiv">
                        <label for="roleid" class="col-sm-3 control-label"><strong class="black_clr"><?php echo $v['subject_name']?></strong></label>
                        <div class="col-sm-4 text-center">
                           <?php echo $venue_add_finalstring;?>
                        </div>
                        <div class="col-sm-2 text-center">
                           <?php echo date('d-M-Y',strtotime($v['date']));?>
                        </div>
                        <div class="col-sm-2 text-center">
                           <?php echo $v['session_time'];?>
                           </select>
                        </div>
                     </div>
                     <?php 
                        }
                        }?>
                     <?php 
                        }?>  
                     <?php 
                        if($this->session->userdata['examinfo']['elearning_flag'] == 'Y'){
                          $elearning_flag = 'Yes';
                        }else{
                          $elearning_flag = 'No';
                        }
                        ?>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Elearning Flag</label>
                        <div class="col-sm-5 ">
                           <?php echo $elearning_flag;?>
                           <div id="error_dob"></div>
                        </div>
                     </div>
                     <div class="form-group">
                        <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>
                        <div class="col-sm-5 ">
                           <?php echo $this->session->userdata['examinfo']['fee'];?>
                           <div id="error_dob"></div>
                        </div>
                     </div>
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Exam Period</label>
                        <div class="col-sm-5 ">
                           <?php 
                              //$month = date('Y')."-".substr($misc['0']['exam_month'],4)."-".date('d');
                                            $month = date('Y')."-".substr($misc['0']['exam_month'],4);
                                            echo date('F',strtotime($month))."-".substr($misc['0']['exam_month'],0,-2);
                                     ?>
                           <?php //echo $this->db->userdata['enduserinfo']['eprid'];?>
                           <div id="error_dob"></div>
                        </div>
                     </div>
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
                     <div class="form-group <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Centre Name *</label>
                        <div class="col-sm-2">
                           <?php 
                              if(isset($center[0]['center_name']))
                              {
                                    echo $center[0]['center_name'];
                              }
                              ?>
                        </div>
                     </div>
                     <div class="form-group  <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Centre Code *</label>
                        <div class="col-sm-2">
                           <?php echo  $this->session->userdata['examinfo']['txtCenterCode'];?>
                        </div>
                     </div>
                     <div class="form-group  <?php echo $disaply_class; ?>">
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
                     <div class="form-group  <?php echo $disaply_class; ?>">
                        <label for="roleid" class="col-sm-3 control-label">Scribe required</label>
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
                     <div class="form-group">
                        <div class="col-sm-12">

                           <label for="roleid" class="col-sm-0 control-label"></label>

                           <?php
                              $exam_code = $this->session->userdata['examinfo']['excd'];
                              if ($exam_code != '1006' && $exam_code != '1007')
                              {
                                 echo 'It is mandatory for a candidate to opt the examination centre being his/her place of work. If there is no centre at his/her place of work, shall have to opt the centre nearest to his/her place of work. Result of candidate violating this rule or giving wrong information is liable for cancellation'; 
                              } ?>

                              <br>

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
                           <input type="submit" class="btn btn-info submitBtn" name="btnPreview" id="btnPreview" value="Save">
                           <?php }
                              else if($function=='Msuccess')
                               {
                                      if($this->config->item('exam_apply_gateway')=='sbi')
                                {?>
                           <input type="submit" class="btn btn-info submitBtn" name="btnPreview" id="btnPreview" value="Submit"> 
                           <?php 
                              }
                              else
                              {?>
                           <input type="submit" class="btn btn-info submitBtn" name="btnPreview" id="btnPreview" value="Submit" onclick="javascript:return validate();"> 
                           <?php  
                              }
                              }?>
                           <!-- <a href="javascript:window.history.go(-1);" class="btn btn-info" id="preview">Back</a> -->
                           <!--<a href="<?php echo base_url();?>Home/examdetails/?excode2=<?php echo $this->session->userdata('examcode');?>" class="btn btn-info" id="preview">Back</a>-->

                           <button type="button" onclick="submit_back_form()" class="btn btn-info" name="getdata">Back</button>
                        </div>
                     </div>
                  </div>
               </div>
                </form>

                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>/bulk/BulkApply/add_member/" autocomplete="off" id="preview_back_form">
            
                           <input type="hidden" class="form-control" name="regnumber"  value="<?php echo $user_info[0]['regnumber'];?>">
                        <input type="hidden" name="getdata">
                    
                  </form>


            </div>
         </div>
      </section>
  
</div>
<!-- Data Tables -->
<script type="text/javascript">
   $(document).ready(function() 
   {
     $(".submitBtn").click(function () {
     $('#member_exam_comApplication').submit();
          $(".submitBtn").attr("disabled", true);
         // $('#member_exam_comApplication').submit();
        });
   });

   function submit_back_form()
   {
      $("#preview_back_form").submit();
   }
   
</script>