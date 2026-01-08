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
	background-color:#fff;
}
body.layout-top-nav .main-header h1 {
	color:#0699dd;
	margin-bottom:0;
	margin-top:30px;
}
.container {
	position:relative;
}
.box-header.with-border {
	background-color:#7fd1ea;
	border-top-left-radius:0;
	border-top-right-radius:0;
	margin-bottom:10px;
}
.header_blue {
	background-color:#2ea0e2 !important;
	color:#fff !important;
	margin-bottom:0 !important;
}
.box {
	border:none;
	box-shadow:none;
	border-radius:0;
	margin-bottom:0;
}
.nobg {
	background:none !important;
	border:none !important;
}
.box-title-hd {
	color:#3c8dbc;
	font-size:16px;
	margin:0;
}
.blue_bg {
	background-color:#e7f3ff;
}
.m_t_15 {
	margin-top:15px;
}
.main-footer {
	padding-left:160px;
	padding-right:160px;
}
.content-header > h1 {
	font-size:22px;
	font-weight:600;
}
h4 {
	margin-top:5px;
	margin-bottom:10px !important;
	font-size:14px;
	line-height:18px;
	padding:0 5px;
	font-weight:600;
	text-align:justify;
}
.form-horizontal .control-label {
	padding-top:4px;
}
.pad_top_2 {
	padding-top:2px !important;
}
.pad_top_0 {
	padding-top:0px !important;
}
 div.form-group:nth-child(odd) {
 background-color:#dcf1fc;
 padding:5px 0;
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
	z-index:1;
	box-shadow:0 1px 3px #000;
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
	margin-bottom:10px;
}
.form-horizontal .form-group {
	margin-left:0;
	margin-right:0;
}
.form-control {
	border-color:#888;
}
.form-horizontal .control-label {
	font-weight:normal;
}
a.forget {
	color:#9d0000;
}
a.forget:hover {
	color:#9d0000;
	text-decoration:underline;
}
ol li {
	line-height:18px;
}
.example {
	text-align:left !important;
	padding:0 10px;
}
</style>
<?php 
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>
<!-- Content Wrapper. Contains page content -->
<div class="container">

  <!-- Content Header (Page header) -->
   <section class="content-header box-header with-border" style="height: 48px; background-color: #1287C0; ">
      <h1 class="register"> Please go through the given detail, correction may be made if necessary. <a  href="javascript:window.history.go(-1);" style="color:#0FF; font-size:25px"" >Modify</a> </h1>
    </section>

   
    <br>
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
            <h3 class="box-title">Basic Details</h3>
            <div style="float:right;"> 
              <!--   <a  href="javascript:window.history.go(-1);">Back</a>--> 
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.box-header --> 
    <!-- form start -->
    <div class="box-body">
      <?php //echo validation_errors(); ?>
      <?php if($this->session->flashdata('error')!=''){?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
        <?php echo $this->session->flashdata('error'); ?> </div>
      <?php } if($this->session->flashdata('success')!=''){ ?>
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 
        <?php echo $this->session->flashdata('success'); ?> </div>
      <?php } 
			 if(validation_errors()!=''){?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 
        <?php echo validation_errors(); ?> </div>
      <?php } 
			 ?>
      <form class="form-horizontal" name="bankquestForm" id="bankquestForm"  method="post"   action="<?php echo base_url()?>contactClasses/addrecord">
  
          <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Membership No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-5"> <?php echo 	$_SESSION['mem_no']?> 
              <!--<input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo $this->session->userdata['enduserinfo']['email']?>"  data-parsley-maxlength="30" required>--> 
              <!--(Enter valid and correct email ID to receive communication)--> 
              <span class="error">
              <?php //echo form_error('email');?>
              </span> </div>
          </div>
          
          <?php 
		//get center name
			$member_info= $this->master_model->getRecords('member_registration', array('regnumber' =>$this->session->userdata['mem_no']));	
	
		  ?>
                   <div class="form-group">
          <label for="roleid" class="col-sm-3 control-label"> First Name &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
          <div class="col-sm-1"> <?php 
		  if(isset($member_info[0]['namesub']))
		  {
		  echo $member_info[0]['namesub'];
		  }?> 
            <!--<select name="sel_namesub" id="sel_namesub" class="form-control">
                    <option value="Mr." <?php if($this->session->userdata['enduserinfo']['sel_namesub']=='Mr.'){echo  'selected="selected"';}?>>Mr.</option>
                    <option value="Mrs." <?php if($this->session->userdata['enduserinfo']['sel_namesub']=='Mrs.'){echo  'selected="selected"';}?>>Mrs.</option>
                    <option value="Ms." <?php if($this->session->userdata['enduserinfo']['sel_namesub']=='Ms.'){echo  'selected="selected"';}?>>Ms.</option>
                    <option value="Dr." <?php if($this->session->userdata['enduserinfo']['sel_namesub']=='Dr.'){echo  'selected="selected"';}?>>Dr.</option>
                    <option value="Prof." <?php if($this->session->userdata['enduserinfo']['sel_namesub']=='Prof.'){echo  'selected="selected"';}?>>Prof.</option>
                    </select>--> 
          </div>
        <?php 
		  if(isset($member_info[0]['firstname']))
		  {
		  echo $member_info[0]['firstname'];
		  }
		  else
		  {
			  echo ' ';
		  }?> 
    <!--(Max 30 Characters) -->
 
      </div>
      
      <div class="form-group">
          <label for="roleid" class="col-sm-3 control-label"> Middle Name&nbsp;:</label>
          <div class="col-sm-1"> <?php   if(isset($member_info[0]['middlename']))
		  {
		  echo $member_info[0]['middlename'];
		  }
		  else
		  {
			  echo ' ';
		  }?> 
    </div>
      </div>
            <div class="form-group">
          <label for="roleid" class="col-sm-3 control-label"> Last Name&nbsp;:</label>
          <div class="col-sm-1"> <?php if(isset($member_info[0]['lastname']))
		  {
		  echo $member_info[0]['lastname'];
		  }
		  else
		  {
			  echo ' ';
		  }?> 
    </div>
      </div>
    
      
        <div class="box-body">
        
          <div class="form-group">
          
            <label for="roleid" class="col-sm-3 control-label">Address line1&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-5"> <?php  if(isset($member_info[0]['address1']))
		  {
		  echo $member_info[0]['address1'];
		  }?> 
              <!--<input type="text" class="form-control" id="addressline1" name="addressline1" placeholder="Address line1" required value="<?php echo $this->session->userdata['enduserinfo']['addressline1']?>"  data-parsley-maxlength="30" >--> 
              <span class="error">
              <?php //echo form_error('addressline1');?>
              </span> </div>
            <!-- (Max 30 Characters) --> 
            
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Address line2 &nbsp;:</label>
            <div class="col-sm-5"> <?php if(isset($member_info[0]['address2']))
		  {
		  echo $member_info[0]['address2'];
		  }?> 
              <!--<input type="text" class="form-control" id="addressline2" name="addressline2" placeholder="Address line2"  value="<?php echo $this->session->userdata['enduserinfo']['addressline2']?>"  data-parsley-maxlength="30" >--> 
              <span class="error">
              <?php //echo form_error('addressline2');?>
              </span> </div>
            <!-- (Max 30 Characters) --> 
            
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Address line3&nbsp;:</label>
            <div class="col-sm-5"> <?php if(isset($member_info[0]['address3']))
		  {
		  echo $member_info[0]['address3'];
		  }?> 
              <!--<input type="text" class="form-control" id="addressline3" name="addressline3" placeholder="Address line3"  value="<?php echo $this->session->userdata['enduserinfo']['addressline3']?>"  data-parsley-maxlength="30" >--> 
              <span class="error">
              <?php //echo form_error('addressline3');?>
              </span> </div>
            <!--(Max 30 Characters) --> 
            
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Address line4&nbsp;:</label>
            <div class="col-sm-5"> <?php if(isset($member_info[0]['address4']))
		  {
		  echo $member_info[0]['address4'];
		  }?> 
              <!--<input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo $this->session->userdata['enduserinfo']['addressline4']?>" data-parsley-maxlength="30" >--> 
              <span class="error">
              <?php //echo form_error('addressline4');?>
              </span> </div>
            <!-- (Max 30 Characters) --> 
            
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">District&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-5"> <?php if(isset($member_info[0]['district']))
		  {
		  echo $member_info[0]['district'];
		  }?> 
              <!--<input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $this->session->userdata['enduserinfo']['district']?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >--> 
              <span class="error">
              <?php //echo form_error('city');?>
              </span> </div>
            <!-- (Max 30 Characters) --> 
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">City&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-5"> <?php if(isset($member_info[0]['city']))
		  {
		  echo $member_info[0]['city'];
		  }?> 
              <!--<input type="text" class="form-control" id="city" name="city" placeholder="City" required value="<?php echo $this->session->userdata['enduserinfo']['city']?>" data-parsley-pattern="/^[a-zA-Z][a-zA-Z ]+$/" data-parsley-maxlength="30" >--> 
              <span class="error">
              <?php //echo form_error('city');?>
              </span> </div>
            <!-- (Max 30 Characters) --> 
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">State&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-2">
              <?php
			  if(isset($member_info[0]['state']))
		  {
		 $State=$this->master_model->getRecords('state_master',array('state_code'=>$member_info[0]['state']),'state_name');
							echo $State[0]['state_name'];
		  }
			  
			?>
              <!--<input type="text" class="form-control" id="addressline4" name="addressline4" placeholder="Address line4"  value="<?php echo $this->session->userdata['enduserinfo']['state']?>" data-parsley-maxlength="30" >-->
              </select>
            </div>
            <!--(Max 6 digits) -->
            <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-2"> <?php   if(isset($member_info[0]['pincode']))
		  {
			echo  $member_info[0]['pincode'];
			
		  }?> 
              <!--<input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode/Zipcode" required value="<?php echo $this->session->userdata['enduserinfo']['pincode']?>"  data-parsley-maxlength="6" maxlength="6" size="6">--> 
              <span class="error">
              <?php //echo form_error('pincode');?>
              </span> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Email &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-5"> <?php if(isset($member_info[0]['email']))
		  {
		  echo $member_info[0]['email'];
		  } ?> 
              <!--<input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php echo $this->session->userdata['enduserinfo']['email']?>"  data-parsley-maxlength="30" required>--> 
              <!--(Enter valid and correct email ID to receive communication)--> 
              <span class="error">
              <?php //echo form_error('email');?>
              </span> </div>
          </div>
          <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Mobile Number &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            <div class="col-sm-5"> <?php if(isset($member_info[0]['mobile']))
		  {
		  echo $member_info[0]['mobile'];
		  }?> 
              <!--<input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="12"  value="<?php echo $this->session->userdata['enduserinfo']['mobile'];?>"  required>--> 
              <span class="error">
              <?php //echo form_error('mobile');?>
              </span> </div>
          </div>
          
          <div class="form-group">
          <label for="roleid" class="col-sm-3 control-label">Course&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
             
          <div class="col-sm-5"> 
                <?php if(isset($this->session->userdata['enduserinfo']['cource_code']))
				  {
					  		$cource=array(); 
							$this->db->where('isactive','1');
					     	$this->db->where('course_code', $this->session->userdata['enduserinfo']['cource_code']);
							$cource=$this->master_model->getRecords('contact_classes_cource_master');
					  
						
					    echo $cource[0]['course_name'];
				  }?>
           
                <span class="error" id="tiitle_error">
                <?php //echo form_error('firstname');?>
                </span> </div>
            </div>
            
            
            
            <div class="form-group">
 <label for="roleid" class="col-sm-3 control-label">Center&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            
             <div class="col-sm-5"> 
                
                  <?php if(isset($this->session->userdata['enduserinfo']['center_code']))
				  {
						$this->db->where('center_code', $this->session->userdata['enduserinfo']['center_code']);
						$center=$this->master_model->getRecords('contact_classes_center_master');
					    echo $center[0]['center_name'];
				  }?>
          
                <span class="error" id="tiitle_error">
                <?php //echo form_error('firstname');?>
                </span> </div>
            </div>
            
             <div class="form-group">
             <label for="roleid" class="col-sm-3 control-label">Venue &nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
              
              <div class="col-sm-9">
             
                  <?php if(isset($this->session->userdata['enduserinfo']['center_code']) )
				   {
						//venue
						
						$query = $this->db->query("SELECT DISTINCT(venue_name) FROM `contact_classes_venue_master` WHERE `center_code` = ".$this->session->userdata['enduserinfo']['center_code']."");
						$reg_count = $query->result_array();
					
					    echo $reg_count[0]['venue_name'];
				  }?>
            
                <span class="error" id="tiitle_error">
                <?php //echo form_error('firstname');?>
                </span> </div>
            </div>
            
                <div class="form-group">
            <label for="roleid" class="col-sm-3 control-label">Subjects&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
            
              <div class="col-sm-4">
            
          <?php if(isset($this->session->userdata['enduserinfo']['subjects']) )
				   {
						$array = implode("','", $this->session->userdata['enduserinfo']['subjects']);
					
						$sub=array();
						$count=1;
						$i=0;
						$query = $this->db->query("SELECT DISTINCT(sub_name) FROM `contact_classes_subject_master` WHERE `sub_code` IN('".$array."')");
						$reg_count = $query->result_array();
					  if(!empty($reg_count))
						{ $i=1;
							foreach($reg_count as $reg)
              {  
                if(isset($reg['sub_name']))
                { 
                  echo ' '.$i.'. ';
                  echo $reg['sub_name'];
                  echo '<br>';
                  $i++;
                }
                
              }
						}
				  }?>
         
                <span class="error" id="tiitle_error">
                <?php //echo form_error('firstname');?>
                </span> </div>
            </div>
          
        </div>
        <div class="box-footer">
          <div class="col-sm-4 col-xs-offset-3">
          <center>  <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Proceed for Payment"> </center> 
          </div>
        </div>
      </form>
    </div>
  </section>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<script src="<?php echo base_url();?>js/Fin_Quest.js?<?php echo time(); ?>"></script> 
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