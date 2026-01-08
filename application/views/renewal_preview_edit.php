<style>
.modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 920px;
	min-width: 300px;
}
#confirm .modal-dialog {
	position: relative;
	display: table;
	overflow-y: auto;
	overflow-x: auto;
	width: 420px;
	min-width: 400px;
}
.skin-blue .main-header .navbar {
	background-color: #fff;
}
body.layout-top-nav .main-header h1 {
	color: #0699dd;
	margin-bottom: 0;
	margin-top: 30px;
}
.container {
	position: relative;
}
.box-header.with-border {
	background-color: #7fd1ea;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
	margin-bottom: 10px;
}
.header_blue {
	background-color: #2ea0e2 !important;
	color: #fff !important;
	margin-bottom: 0 !important;
}
.box {
	border: none;
	box-shadow: none;
	border-radius: 0;
	margin-bottom: 0;
}
.nobg {
	background: none !important;
	border: none !important;
}
.box-title-hd {
	color: #3c8dbc;
	font-size: 16px;
	margin: 0;
}
.blue_bg {
	background-color: #e7f3ff;
}
.m_t_15 {
	margin-top: 15px;
}
.main-footer {
	padding-left: 160px;
	padding-right: 160px;
}
.content-header > h1 {
	font-size: 22px;
	font-weight: 600;
}
h4 {
	margin-top: 5px;
	margin-bottom: 10px !important;
	font-size: 14px;
	line-height: 18px;
	padding: 0 5px;
	font-weight: 600;
	text-align: justify;
}
.form-horizontal .control-label {
	padding-top: 4px;
}
.pad_top_2 {
	padding-top: 2px !important;
}
.pad_top_0 {
	padding-top: 0px !important;
}
div.form-group:nth-child(odd) {
	background-color: #dcf1fc;
	padding: 5px 0;
}
#confirmBox {
	display: none;
	background-color: #eee;
	border-radius: 5px;
	border: 1px solid #aaa;
	position: fixed;
	width: 300px;
	left: 50%;
	margin-left: -150px;
	padding: 6px 8px 8px;
	box-sizing: border-box;
	text-align: center;
	z-index: 1;
	box-shadow: 0 1px 3px #000;
}
#confirmBox .button {
	background-color: #ccc;
	display: inline-block;
	border-radius: 3px;
	border: 1px solid #aaa;
	padding: 2px;
	text-align: center;
	width: 80px;
	cursor: pointer;
}
#confirmBox .button:hover {
	background-color: #ddd;
}
#confirmBox .message {
	text-align: left;
	margin-bottom: 8px;
}
.form-group {
	margin-bottom: 10px;
}
.form-horizontal .form-group {
	margin-left: 0;
	margin-right: 0;
}
.form-control {
	border-color: #888;
}
.form-horizontal .control-label {
	font-weight: normal;
}
a.forget {
	color: #9d0000;
}
a.forget:hover {
	color: #9d0000;
	text-decoration: underline;
}
ol li {
	line-height: 18px;
}
.example {
	text-align: left !important;
	padding: 0 10px;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<div class="container">
  <div class="content-wrapper">
    <section class="content-header box-header with-border" style="height: 50px; background-color: #1287C0; ">
      <h1 class="register">Renewal Of Ordinary Membership</h1>
      <br />
    </section>
    <section class="content-header box-header with-border" style="background-color: #7FD1EA;">
      <h1> Please go through the given detail, correction may be made if necessary. <a  href="javascript:window.history.go(-1);">Modify</a></h1>
    </section>
    <section class="">
      <div class="row">
        <div class="col-md-12">
          <div class="">
            
            <label for="roleid" class="col-sm-4 control-label" style="text-align: right;">Membership No.&nbsp;<span style="color:#F00">*</span></label>
            <div class="col-sm-4" style="width: 25%;"> <?php echo $this->session->userdata['enduserinfo']['regnumber'];?> </div>
          </div>
          </form>
        </div>
      </div>
    </section>
    <form class="form-horizontal" name="usersAddForm" id="usersAddForm"  method="post"  enctype="multipart/form-data" action="<?php echo base_url()?>renewal_edit/addmember/">
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Basic Details</h3>
                <div style="float:right;"> </div>
              </div>
              <div class="box-body">
                <?php //echo validation_errors(); ?>
                <?php if($this->session->flashdata('error')!=''){?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('error'); ?> </div>
                <?php } if($this->session->flashdata('success')!=''){ ?>
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo $this->session->flashdata('success'); ?> </div>
                <?php } 
			 if(validation_errors()!=''){?>
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <?php echo validation_errors(); ?> </div>
                <?php } 
			 ?>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">First Name&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-1"><?php echo $this->session->userdata['enduserinfo']['sel_namesub'];?></div>
                  <div class="col-sm-0"> <?php if(isset($selectedRecord['firstname'])){ echo $selectedRecord['firstname']; } ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Middle Name</label>
                  <div class="col-sm-5"> <?php if(isset($selectedRecord['middlename'])){ echo $selectedRecord['middlename']; } ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Last Name</label>
                  <div class="col-sm-5"><?php if(isset($selectedRecord['lastname'])){ echo $selectedRecord['lastname']; } ?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name as to appear on Card&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['nameoncard'];?> </div>
                </div>
              </div>
            </div>
            <!-- Basic Details box closed-->
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Contact Details</h3>
              </div>
              <div>&nbsp;</div>
              <div class="box-body">
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line1&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline1'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line2</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline2'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line3</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline3'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line4</label>
                  <div class="col-sm-5"><?php echo $this->session->userdata['enduserinfo']['addressline4'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">District&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['district']?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">City&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['city']?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">State&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-2">
                    <?php 
				if(count($states) > 0){
				  foreach($states as $row1){
				   if($this->session->userdata['enduserinfo']['state']==$row1['state_code']){
					   echo  $row1['state_name'];
					   }
					} 
                 } 
				 ?>
                  </div>
                  <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-2"> <?php echo $this->session->userdata['enduserinfo']['pincode'];?> </div>
                </div>
                <div class="box box-info">
                  <div class="box-header with-border">
                    <h3 class="box-title">Permanent Address Details</h3>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Address line1&nbsp;<span style="color:#F00">*</span></label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['addressline1_pr'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Address line2</label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['addressline2_pr'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Address line3</label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['addressline3_pr'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Address line4</label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['addressline4_pr'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">District&nbsp;<span style="color:#F00">*</span></label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['district_pr']?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">City&nbsp;<span style="color:#F00">*</span></label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['city_pr']?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">State&nbsp;<span style="color:#F00">*</span></label>
                    <div class="col-sm-2">
                      <?php
                    if(count($states) > 0){
                        foreach($states as $row1){ 	 
                            if($this->session->userdata['enduserinfo']['state_pr']== $row1['state_code']){
                                echo  $row1['state_name'];
                            } 
                        } 
                    } 
                    ?>
                    </div>
                    <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode&nbsp;<span style="color:#F00">*</span></label>
                    <div class="col-sm-2"> <?php echo $this->session->userdata['enduserinfo']['pincode_pr'];?> </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Date of Birth&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-2"> <?php echo $this->session->userdata['enduserinfo']['dob'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Gender&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-2">
                    <?php if($this->session->userdata['enduserinfo']['gender']=='female'){echo 'Female';}?>
                    <?php if($this->session->userdata['enduserinfo']['gender']=='male'){echo  ' Male';}?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Qualification&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-4">
                    <?php if($this->session->userdata['enduserinfo']['optedu']=='U'){echo  'Under Graduate';}?>
                    <?php if($this->session->userdata['enduserinfo']['optedu']=='G'){echo  'Graduate';}?>
                    <?php if($this->session->userdata['enduserinfo']['optedu']=='P'){echo  'Post Graduate';}?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Please specify&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5">
                    <?php 
					if($this->session->userdata['enduserinfo']['optedu']=='U' && $this->session->userdata['enduserinfo']['eduqual1'])
					{
						 if(count($undergraduate))
						 {
                            foreach($undergraduate as $row1)
							{ 	
                        		if($this->session->userdata['enduserinfo']['eduqual1']==$row1['qid']){echo  $row1['name'];}
                       		  }
                       	   } 
					}?>
                  </div>
                  <div class="col-sm-5" id="GR">
                    <?php 
					if($this->session->userdata['enduserinfo']['optedu']=='G' && $this->session->userdata['enduserinfo']['eduqual2'])
					{
						 if(count($graduate))
						 {
                            foreach($graduate as $row2)
							{ 	
                        		if($this->session->userdata['enduserinfo']['eduqual2']==$row2['qid']){echo  $row2['name'];}
                       		  }
                       	   } 
					}?>
                  </div>
                  <div class="col-sm-5" id="PG">
                    <?php 
					if($this->session->userdata['enduserinfo']['optedu']=='P' && $this->session->userdata['enduserinfo']['eduqual3'])
					{
						 if(count($postgraduate))
						 {
                            foreach($postgraduate as $row3)
							{ 	
                        		if($this->session->userdata['enduserinfo']['eduqual3']==$row3['qid']){echo  $row3['name'];}
                       		  }
                       	   } 
					}?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Bank/Institution working&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"   id="edu">
                    <?php if(count($institution_master))
			  {
				  foreach($institution_master as $institution_row)
					{ 	
                  		if($this->session->userdata['enduserinfo']['institution']==$institution_row['institude_id']){echo  $institution_row['name'];}
                      }
			} ?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Branch/Office&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['office'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Designation&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"  style="display:block" id="edu">
                    <?php if(count($designation))
			  {
              	 foreach($designation as $designation_row)
				 {
                	if($this->session->userdata['enduserinfo']['designation']==$designation_row['dcode']){echo  $designation_row['dname'];}
                    } 
              } ?>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Bank Employee Id&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['bank_emp_id'];?> </div>
                </div>
                
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Date of joining Bank/Institution &nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-4"> <?php echo $this->session->userdata['enduserinfo']['doj']?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['email']?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Phone </label>
                  <div class="col-sm-3"> STD Code : <?php echo $this->session->userdata['enduserinfo']['stdcode'];?> </div>
                  <div class="col-sm-3"> Phone No : <?php echo $this->session->userdata['enduserinfo']['phone'];?> </div>
                </div>
                
                  <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile&nbsp;<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['mobile']?> </div>
                </div>
                
                <?php 
				$star='';
                if($this->session->userdata['enduserinfo']['state']!='ASS' && $this->session->userdata['enduserinfo']['state']!='JAM' && $this->session->userdata['enduserinfo']['state']!='MEG')
				{
						$star='*';
					}
				?>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Aadhar Card Number<!--<span style="color:#F00">&nbsp;<?php //echo $star;?></span>--></label>
                <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['aadhar_card'];?> </div>
              </div>
              </div>
              
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedphoto'];?>" height="100" width="100" ></label>
                <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['scannedsignaturephoto'];?>" height="100" width="100"></label>
                <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['idproofphoto'];?>"  height="100" width="100"></label>
                <label for="roleid" class="col-sm-3 control-label"><img src="<?php echo $this->session->userdata['enduserinfo']['declarationform'];?>"  height="100" width="100"></label>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Uploaded Photo</label>
                <label for="roleid" class="col-sm-3 control-label">uploaded Signature</label>
                <label for="roleid" class="col-sm-3 control-label">Uploaded ID Proof</label>
                <label for="roleid" class="col-sm-3 control-label">Uploaded Declaration</label>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Select Id Proof&nbsp;<span style="color:#F00">*</span></label>
                <div class="col-sm-8">
                  <?php if(count($idtype_master) > 0)
					{
						foreach($idtype_master as $idrow)
						{?>
                  <?php if($this->session->userdata['enduserinfo']['idproof']==$idrow['id']){echo  $idrow['name'];}?>
                  <?php 
				 	  }
				   }?>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-10 control-label">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy</label>
                <div class="col-sm-2">
                  <?php if($this->session->userdata['enduserinfo']['optnletter']=='Y'){echo  'Yes';}?>
                  <?php if($this->session->userdata['enduserinfo']['optnletter']=='N'){echo  'No';}?>
                  <span class="error">
                  <?php //echo form_error('optnletter');?>
                  </span> </div>
              </div>
            </div>
            <div class="box-footer">
              <div class="col-sm-12" align="center">
                <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Proceed for Payment">
              </div>
            </div>
          </div>
        </div>
      </section>
    </form>
  </div>
</div>
<script>
  $(document).ready(function(){
	 function createCookie(name, value, days) {
      var expires;

      if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
          expires = "; expires=" + date.toGMTString();
      } else {
          expires = "";
      }
      document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
   }
    createCookie('member_register_form','1','1');
	});
  </script>