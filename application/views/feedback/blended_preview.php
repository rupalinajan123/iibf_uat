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
.content-header>h1 {
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
<!-- Content Wrapper. Contains page content -->
<div class="container">
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    
    <section class="content-header box-header with-border" style="background-color: #1287C0">
      <h1 class="register"> Blended Course Training Application </h1>
    </section>
    <section class="content-header box-header with-border" style="height: 60px">
      <h3 style="color:#FFF; font-size:20px; font-weight:bold; text-align:center;">Please go through the given detail, correction may be made if necessary <a href=<?php if(!empty($row['registrationtype'])){if($row['registrationtype']=='NM'){echo base_url('nonmem');}else{echo base_url();}}else{echo base_url();} ?> target="_blank"><span style="color:#F00">&nbsp;Edit Profile</span></a></h3>
    </section>
    <section class="">
      <div class="row">
        <div class="col-md-12">
          <div class="">
            <div for="roleid" class="col-sm-4 control-label" style="text-align: right;">Membership No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</div>
            <div class="col-sm-4" style="width: 25%;"> <?php echo $this->session->userdata['enduserinfo']['regnumber'];?> </div>
          </div>
        </div>
      </div>
    </section>
    <br />
    <form class="form-horizontal" name="blendedForm" id="blendedForm" method="post" enctype="multipart/form-data" action="<?php echo base_url()?>Blended/addmember/">
      <div class="content-wrapper">
        <section class="content">
          <div class="row">
            <div class="col-md-12"> 
              <!-- Basic Details box Start-->
              
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Course Details</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Course&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['program_name'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Training Type&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5">
                      <?php
					$training_type =  $this->session->userdata['enduserinfo']['training_type'];
					if($training_type == "PC"){ echo $training_type = 'Physical Classroom' ; }else{ echo $training_type = 'Virtual Classes';}
					?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Training Date&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-6"> <strong>
                      <?php 
					$dateArr = explode("~",$this->session->userdata['enduserinfo']['training_date']);
					$start_date   = date("d-M-Y", strtotime($dateArr[0]));
					$end_date     = date("d-M-Y", strtotime($dateArr[1]));
					echo $start_date.' To '.$end_date;
					
					?>
                      </strong> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Fee Amount&nbsp;:</label>
                    <div class="col-sm-5"> <strong><?php echo $this->session->userdata['enduserinfo']['fees']; ?> </strong> </div>
                  </div>
                  <?php
                  if($training_type == "Physical Classroom")
				  { 
                  ?>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Center&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['center_name'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Venue&nbsp;:</label>
                    <div class="col-sm-6"> <strong><?php echo $this->session->userdata['enduserinfo']['venue_name'];?></strong> <br />
                      <!-- <strong>Start Date &nbsp; : &nbsp;</strong> <?php //echo $this->session->userdata['enduserinfo']['start_date']?>&nbsp;&nbsp;|&nbsp;&nbsp;<strong>End Date &nbsp; : &nbsp;</strong> <?php //echo $this->session->userdata['enduserinfo']['end_date']?>--> 
                    </div>
                  </div>
                  <?php
				  }
				  else
				  {
					  ?>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Center&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-6"> Mumbai </div>
                  </div>
                  <?php
					  
				  }
				  ?>
                </div>
              </div>
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Basic Details</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">First Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['namesub'];?>&nbsp; <?php echo $row['firstname'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Middle Name&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['middlename'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Last Name&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['lastname'];?> </div>
                  </div>
                </div>
              </div>
              <!-- Basic Details box closed--> 
              
              <!-- Contact Details box Start-->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Contact Details</h3>
                </div>
                <div class="box-body">
                <?php
				
				$address1 = $address2 = $address3 = $address4 = '';
				if (isset($row['address1'])){ $address1 = $row['address1']; }
				if (isset($row['address2'])){ $address2 = $row['address2']; }
				if (isset($row['address3'])){ $address3 = $row['address3']; }
				if (isset($row['address4'])){ $address4 = $row['address4']; }
				
				$address1 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address1);
				$address2 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address2);
				$address3 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address3);
				$address4 = preg_replace('/[^A-Za-z0-9\. -]/', '', $address4);
				
				?>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Address line1&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $address1;?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Address line2&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $address2;?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Address line3&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $address3;?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Address line4&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $address4;?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">District&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['district'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">City&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['city'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">State&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-3">
                      <?php if(count($states) > 0){
							 foreach($states as $row1){ 	
								if($row['state']==$row1['state_code']){ 
									echo $row1['state_name'];
								} 
							  } 
							} ?>
                    </div>
                    <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-3"> <?php echo $row['pincode'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Designation&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5" style="display:block" id="edu">
                      <?php if(count($designation)){
							 foreach($designation as $designation_row){
								if($row['designation']==$designation_row['dcode']){
									echo  $designation_row['dname'];}
								} 
						  } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Bank/Institution working&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5" id="edu">
                      <?php if(count($institution_master)){
							  foreach($institution_master as $institution_row){ 	
								if($row['associatedinstitute']==$institution_row['institude_id']){
									echo  $institution_row['name'];}
								  }
						} ?>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">GSTIN No.&nbsp;<!--<span style="color:#F00">*</span>&nbsp;-->:</label>
                    <div class="col-sm-3 example"> <?php echo $this->session->userdata['enduserinfo']['gstin_no'];?></div>
                  </div>
                  
                  <div class="form-group">
                    <?php
					if (isset($row['dateofbirth'])) {
						$originalDate = $row['dateofbirth'];
						$newDate      = date("d/m/Y", strtotime($originalDate));
					}
					?>
                    <label for="roleid" class="col-sm-4 control-label">Date of Birth&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-3 example"> <?php echo $newDate;?>&nbsp;(DD/MM/YYYY)</div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Email&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['email']?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Mobile&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $row['mobile']?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Qualification&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-4">
                      <?php if($row['qualification']=='U'){echo  'Under Graduate';}?>
                      <?php if($row['qualification']=='G'){echo  'Graduate';}?>
                      <?php if($row['qualification']=='P'){echo  'Post Graduate';}?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Please specify&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5">
                      <?php 
					if($row['qualification']=='U' && $row['specify_qualification']){
						 if(count($undergraduate)) {
                            foreach($undergraduate as $row1){ 	
                        		if($row['specify_qualification']==$row1['qid']){echo  $row1['name'];}
                       		  }
                       	   } 
					}?>
                    </div>
                    <div class="col-sm-5" id="GR">
                      <?php 
					if($row['qualification']=='G' && $row['specify_qualification']){
						 if(count($graduate)) {
                            foreach($graduate as $row2){ 	
                        		if($row['specify_qualification']==$row2['qid']){echo  $row2['name'];}
                       		  }
                       	   } 
					}?>
                    </div>
                    <div class="col-sm-5" id="PG">
                      <?php 
					if($row['qualification']=='P' && $row['specify_qualification']){
						 if(count($postgraduate)){
                            foreach($postgraduate as $row3){ 	
                        		if($row['specify_qualification']==$row3['qid']){echo  $row3['name'];}
                       		  }
                       	   } 
					}?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Residential Phone No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-3"> STD Code &nbsp;:&nbsp; <?php echo $row['stdcode'];?> </div>
                    <div class="col-sm-3"> Phone No &nbsp;:&nbsp; <?php echo $row['office_phone'];?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Office Phone No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-3"> STD Code &nbsp;:&nbsp;<?php echo $this->session->userdata['enduserinfo']['stdcode'];?> </div>
                    <div class="col-sm-3"> Phone No &nbsp;:&nbsp; <?php echo $this->session->userdata['enduserinfo']['phone'];?> </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Blood Group&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['blood_group']?> </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Name Of Contact Person(in emergency)&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['emergency_name']?> </div>
                  </div>
                  <div class="form-group">
                    <label for="roleid" class="col-sm-4 control-label">Contact Person Mobile No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                    <div class="col-sm-5"> <?php echo $this->session->userdata['enduserinfo']['emergency_contact_no']?> </div>
                  </div>
                  
                </div>
              </div>
              <!-- Invoice Address Details box Closed-->
              
              <div class="box box-info">
                <div class="box-header with-border"></div>
                <div class="box-footer">
                  <div class="col-sm-5 col-xs-offset-3" style="text-align: center">
                    <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Proceed for Payment">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </form>
  </div>
</div>
<script>

history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
window.addEventListener('popstate', function(event) {
    window.location.assign(site_url+"blended/");
});


    $(document).ready(function() {
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
        createCookie('member_register_form', '1', '1');



    });
</script>