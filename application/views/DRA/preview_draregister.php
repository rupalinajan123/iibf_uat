<link href="<?php echo base_url();?>assets/css/wizard.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/js/wizard.js"></script>
<link href="<?php echo base_url();?>assets/css/center_add.css" rel="stylesheet">

<!-- Content Wrapper. Contains page content -->
<div class="container">
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    
    <section class="content-header">
      <h1 class="register"> Application for DRA Accreditation </h1>
      <br/>
      <div class="stepwizard">
        <div class="stepwizard-row setup-panel">
          <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-default btn-circle" id="step_1"><i class="fa fa-university" aria-hidden="true"></i></a>
            <p class="mb-0">01</p>
            <span class="step_ttl">agency basic details</span> </div>
          <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-default btn-circle" disabled="disabled" id="step_2"><i class="fa fa-map-marker" aria-hidden="true"></i></a>
            <p class="mb-0">02</p>
            <span class="step_ttl">Accreditation Details (Centre Details) </span> </div>
          <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-primary btn-circle" disabled="disabled" id="step_3"><i class="fa fa-search" aria-hidden="true"></i></a>
            <p class="mb-0">03</p>
            <span class="step_ttl">review details</span> </div>
          <div class="stepwizard-step"> <a href="javascript:void(0)" type="button" class="btn btn-default btn-circle" id="step_4"><i class="fa fa-money" aria-hidden="true"></i></a>
          <p class="mb-0">04</p>
          <span class="step_ttl">Payment details</span> </div>  

        </div>
      </div>
      <h1> Please go through the given detail, correction may be made if necessary. <a  href="javascript:window.history.go(-1);">Modify</a> </h1>
      <br>
    </section>
    <form class="form-horizontal" name="drafrmpreview" id="drafrmpreview"  method="post"  enctype="multipart/form-data" 
    action="<?php echo base_url()?>DraRegister/register">
      <section class="content">
        <div class="row">
        <div class="col-md-12"> 
          
          <!-- Horizontal Form -->
          <div class="box box-info"> 
            
            <!-- form start -->
            <div class="box-body">
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
              <div class="form-group">
              <div class="box-header with-border">
                  <h3 class="box-title" style="color:#333"> <b>Agency Basic Details</b></h3>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Agency Type<span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['agency_type']; ?> </span> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name Of Agency<span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['inst_name']; ?> </span> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Year of establishment <span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['estb_year']; ?> </div>
                </div>
                <!--<div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">
                  <h4 class="title">Agency Address</h4>
                  </label>
                </div>-->
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label"> Address line1<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['userinfo']['main_address1'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line2</label>
                  <div class="col-sm-5"> <?php if( $this->session->userdata['userinfo']['main_address2']== ""){ echo "-";}else{?><?php echo $this->session->userdata['userinfo']['main_address2'];?> <?php }?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line3</label>
                  <div class="col-sm-5"><?php if($this->session->userdata['userinfo']['main_address3']==""){ echo "-";} else{?> <?php echo $this->session->userdata['userinfo']['main_address3'];?><?php }?> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line4</label>
                  <div class="col-sm-5"><?php if($this->session->userdata['userinfo']['main_address4']==""){echo "-";}else{ ?> <?php echo $this->session->userdata['userinfo']['main_address4'];?> <?php }?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">District<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['userinfo']['main_district'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
                  <div class="col-sm-2">
                    <?php
                    if(count($states) > 0){
                        foreach($states as $row1){   
                            if($this->session->userdata['userinfo']['main_state']== $row1['state_code']){
                                echo  $row1['state_name'];
                            } 
                        } 
                    } 
                    ?>
                  </div>
                  
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">City<span style="color:#F00">*</span></label>
                  <div class="col-sm-3"> <?php 
                  if(count($cities) > 0){
                        foreach($cities as $row){   
                            if($this->session->userdata['userinfo']['main_city']== $row['id']){
                                echo  $row['city_name'];
                            } 
                        } 
                    }  ?> 
                  </div>
                  <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                  <div class="col-sm-2"> <?php echo $this->session->userdata['userinfo']['main_pincode'];?> </div>
                </div>

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Telephone No of the agency  <span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['inst_stdcode']; ?> -  <?php echo $this->session->userdata['userinfo']['inst_phone']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Fax no of the agency </label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['inst_fax_no']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Website address </label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['inst_website']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name of director/head of the agency <span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['inst_head_name']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile no of head of the agency<span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['inst_head_contact_no']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email id of the head of the agency<span style="color:#F00">*</span> </label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['inst_head_email']; ?> </div>
                </div>


                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name of Alternate Contact Person of the agency <span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['inst_altr_person_name']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile No. of the Alternate Contact Person of the agency.<span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['inst_alter_contact_no']; ?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email ID of the Alternate Contact Person of the agency.<span style="color:#F00">*</span> </label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['inst_altr_email']; ?> </div>
                </div>


                <div class="box-header with-border">
                  <h3 class="box-title" style="color:#333"><b>Accreditation Details (Centre Details)</b></h3>
                </div>
                
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">State <span style="color:#F00">*</span></label>
                  <div class="col-sm-2">
                    <?php
                    if(count($states) > 0){
                        foreach($states as $row1){ 	 
                            if($this->session->userdata['userinfo']['state']== $row1['state_code']){
                                echo  $row1['state_name'];
                            } 
                        } 
                    } 
                    ?>
                  </div>
                  <label for="roleid" class="col-sm-2 control-label">Pincode/Zipcode <span style="color:#F00">*</span></label>
                  <div class="col-sm-2"> <?php echo $this->session->userdata['userinfo']['pincode'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Name of Location(City)<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php 
                  if(count($cities) > 0){
                        foreach($cities as $row){   
                            if($this->session->userdata['userinfo']['city']== $row['id']){
                                echo  $row['city_name'];
                            } 
                        } 
                    }  ?>  </div>
                </div>
                
                <div class="form-group" style="display:none;">
                  <label for="roleid" class="col-sm-4 control-label">Name of Location(City)<span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['location_name']; ?> </div>
                </div>
                <!--<div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">
                  <h4 class="title">Address of Center</h4>
                  </label>
                </div>-->
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line1 <span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['userinfo']['addressline1'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line2</label>
                  <div class="col-sm-5"> <?php if($this->session->userdata['userinfo']['addressline2']==""){ echo"-";}else{ ?><?php echo $this->session->userdata['userinfo']['addressline2'];?><?php }?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line3</label>
                  <div class="col-sm-5"><?php if($this->session->userdata['userinfo']['addressline3']==""){ echo"-";} else{ ?> <?php echo $this->session->userdata['userinfo']['addressline3'];?> <?php }?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Address line4</label>
                  <div class="col-sm-5"><?php if($this->session->userdata['userinfo']['addressline4']==""){ echo"-";} else{ echo $this->session->userdata['userinfo']['addressline4'];  }?></div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">District<span style="color:#F00">*</span></label>
                  <div class="col-sm-5"> <?php echo $this->session->userdata['userinfo']['district'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Office Number<span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['stdcode'];?> - <?php echo $this->session->userdata['userinfo']['office_no'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Contact Person Name<span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['contact_person_name'];?> </span> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile Number<span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['contact_person_mobile'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email id<span style="color:#F00">*</span></label>
                  <div class="col-sm-6"> <?php echo $this->session->userdata['userinfo']['email_id'];?> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Center Type <span style="color:#F00">*</span></label>
                  <div class="col-sm-3">
                    <?php if($this->session->userdata['userinfo']['center_type']=='R'){echo 'Regular';}?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Due diligence </label>
                  <div class="col-sm-3">
                    <?php if($this->session->userdata['userinfo']['due_diligence']=='Yes'){echo 'Yes';}?>
                    <?php if($this->session->userdata['userinfo']['due_diligence']=='No'){echo 'No';}?>
                    <?php if($this->session->userdata['userinfo']['due_diligence']==''){echo '-';}?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">GSTIN No</label>
                  <div class="col-sm-6"> <?php if($this->session->userdata['userinfo']['gstin_no']==""){ echo"-";}else{echo $this->session->userdata['userinfo']['gstin_no']; }?> </div>
                </div>
                
                 <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Remarks</label>
                  <div class="col-sm-6"> <?php if($this->session->userdata['userinfo']['remarks']==""){ echo"-";}else{echo $this->session->userdata['userinfo']['remarks']; }?></div>
                </div>
                
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Select to display <span style="color:#090;">'Agency'</span> address on the invoice <span style="color:#F00">*</span></label>
                  <div class="col-sm-3">
                    <?php 
						if($this->session->userdata['userinfo']['invoice_flag'] == 'AS'){
							echo "Yes";
						}else{
							echo "No";
						}
					?>
                  </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Select to display <span style="color:#090;">'Accreditation Centre'</span> address on the invoice <span style="color:#F00">*</span></label>
                  <div class="col-sm-3">
                    <?php 
						if($this->session->userdata['userinfo']['invoice_flag'] == 'CS'){
							echo "Yes";
						}else{
							echo "No";
						}
					?>
                  </div>
                </div>
              </div>
              <div class="box-footer">
                <div class="col-sm-12 text-center">
                  <input type="submit" class="btn btn-info" name="btnSubmit" id="btnSubmit" value="Go to Payment Details">
                </div>
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