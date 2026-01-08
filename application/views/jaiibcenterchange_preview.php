<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> </h1>
    <!--<ol class="breadcrumb">
        <li><a href="<?php //echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="javascript:void(0);"><?php //echo ucwords($this->router->fetch_class());?></a></li>
        <li class="active">Manage Users</li>
      </ol>--> 
  </section>
  <?php 
		$function = "add_record";	
    ?>
  <form class="form-horizontal" name="member_exam_comApplication" id="member_exam_comApplication"  method="post"  enctype="multipart/form-data" action="<?php echo base_url();?>Applyjaiib_centerchange_special/<?php echo $function;?>/">
    <input type="hidden" name="regid" id="regid" value="<?php echo $this->session->userdata('regid');?>">
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
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Membership No</label>
                <div class="col-sm-1"> <?php echo $user_info[0]['regnumber'];?> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">First Name </label>
                <div class="col-sm-3"> <?php echo $user_info[0]['firstname'];?> <span class="error">
                  <?php //echo form_error('firstname');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Middle Name</label>
                <div class="col-sm-5">
                  <?php if($user_info[0]['middlename']!=''){echo $user_info[0]['middlename'];}else{echo '-';}?>
                  <span class="error">
                  <?php //echo form_error('middlename');?>
                  </span> </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-5">
                  <?php if($user_info[0]['lastname']!=''){echo $user_info[0]['lastname'];}else{echo '-';}?>
                  <span class="error">
                  <?php //echo form_error('lastname');?>
                  </span> </div>
                <!--(Max 30 Characters) --> 
              </div>
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
                <div class="col-sm-5 "> <?php echo $examinfo['0']['description'];?>
                  <div id="error_dob"></div>
                </div>
              </div>
              <?php 
				if(base64_decode($this->session->userdata['examinfo']['excd'])!=101)
				{?>
              <div class="form-group grayDiv">
                <label for="roleid" class="col-sm-3 control-label"><strong class="black_clr">Subject(s)</strong></label>
                <div class="col-sm-4 text-center"> Venue </div>
                <div class="col-sm-2 text-center"> Date </div>
                <div class="col-sm-2 text-center"> Time
                  </select>
                </div>
              </div>
              <?php //echo '<pre>'; print_r($compulsory_subjects); echo '</pre>';
				 	if(count($compulsory_subjects) > 0)
					{
					   $i=1;
					   foreach($compulsory_subjects as $k=>$v)
					   {
						  $venue_add_finalstring='';
						 $get_venue_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$v['venue_code'],'exam_date'=>$v['date'],'session_time'=>$v['session_time'],'center_code'=>$this->session->userdata['examinfo']['txtCenterCode']));
						//echo $this->db->last_query();
						$venue_add=$get_venue_details[0]['venue_name'].'*'.$get_venue_details[0]['venue_addr1'].'*'.$get_venue_details[0]['venue_addr2'].'*'.$get_venue_details[0]['venue_addr3'].'*'.$get_venue_details[0]['venue_addr4'].'*'.$get_venue_details[0]['venue_addr5'].'*'.$get_venue_details[0]['venue_pincode'];
						$venue_add_finalstring= preg_replace('#[\*]+#', ',', $venue_add);
						 
						   ?>
              <div class="form-group borderDiv">
                <label for="roleid" class="col-sm-3 control-label"><strong class="black_clr"><?php echo $v['subject_name']?></strong></label>
                <div class="col-sm-4 text-center"> <?php echo $venue_add_finalstring;?> </div>
                <div class="col-sm-2 text-center"> <?php echo date('d-M-Y',strtotime($v['date']));?> </div>
                <div class="col-sm-2 text-center"> <?php echo $v['session_time'];?>
                  </select>
                </div>
              </div>
              <?php 
			 	}
					}?>
              <?php 
				}?>
              <div class="form-group">
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
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Centre Code *</label>
                <div class="col-sm-2"> <?php echo  $this->session->userdata['examinfo']['txtCenterCode'];?> </div> 
              </div>
              <div class="box-footer">
                <div class="col-sm-4 col-xs-offset-3">
                  <?php 
				   if($function=='saveexam')
				   {?>
                  <input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Save">
                  <?php }
				 	else if($function=='add_record')
				   {
					   		if($this->config->item('exam_apply_gateway')=='sbi')
							{?>
                  <input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Submit">
                  <?php 
							}
							else
							{?>
                  <input type="submit" class="btn btn-info" name="btnPreview" id="btnPreview" value="Submit" onclick="javascript:return validate();">
                  <?php 
							}?>
                  <?php  }?>
                  <a href="javascript:window.history.go(-1);" class="btn btn-info" id="preview">Back</a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </form>
</div>
<!-- Data Tables --> 
