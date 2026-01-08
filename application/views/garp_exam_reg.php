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
  <section class="content-header box-header with-border" style="height: 45px; background-color: #1287C0; ">
    <h1 class="register">Apply for GARP-FRR Exam</h1>
    <br />
  </section>
  <div> 
    <!-- Start Get Details -->
    <?php
if (!empty($row)) {
    if (isset($row['msg']) && $row['msg'] != '') {
        echo '<div class="alert alert-danger alert-dismissible">' . $row['msg'] . '</div>';
    }
}

?>
<div id="capacitymsg" style="display:none">
  <div class="alert alert-danger" style="font-size:150%"> <?php echo 'Seats are Full, apply for subsequent training.'; ?> </div>
</div>
  </div>
  <section class="">
    <div class="row">
      <div class="col-md-12" style="">
        <?php if ($this->session->flashdata('flsh_msg') != '') {?>
        <div class="alert alert-danger"> <?php echo $this->session->flashdata('flsh_msg'); ?> </div>
        <?php }?>
        <?php
if ($this->session->flashdata('error') != '') {?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('error');?> </div>
        <?php } if ($this->session->flashdata('success') != '') {?>
        <div class="alert alert-success alert-dismissible" id="success_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $this->session->flashdata('success');?> </div>
        <?php } if (validation_errors() != '') { ?>
        <div class="alert alert-danger alert-dismissible" id="error_id">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo validation_errors(); ?> </div>
        <?php } if ($var_errors != '') { ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php echo $var_errors; ?> </div>
        <?php } ?>
        <form name="getDetailsForm" autocomplete="off" id="getDetailsForm" method="post" action="<?php echo base_url(); ?>Garp_exam">
          <br />
          <?php
      if(validation_errors() != '')
      { 
      ?>
          <input type="hidden" autocomplete="false" id="flag" value="1" />
          <?php 
      }
      ?>
          <div class="">
            <div for="roleid" class="col-sm-5 control-label" style="text-align: right; width:35%;">Membership No.&nbsp;<span style="color:#F00">*</span>&nbsp;:</div>
           
           
            <div class="col-sm-4" style="width: 32%;text-align: left;">
              <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Membership No." required value="<?php if (isset($row['regnumber'])) { echo $row['regnumber'];} else { echo set_value('regnumber'); }
?>" <?php if (isset($row['regnumber'])) { echo "readonly='readonly'";} elseif (set_value('regnumber')) { echo "readonly='readonly'"; } ?> style="border-color:#000;" title="Membership No.">
            </div>
            <div class="col-sm-3" style="padding-bottom: 10px">
              <?php 
          if (isset($row['regnumber']) || set_value('regnumber')) {
        ?>
              <a href="<?php echo base_url();?>garp_exam" class="btn btn-info" id="modify" style="height: 32px; width: 150px">Modify</a>
              <input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGet" value="Get Details" style="height: 32px; width: 150px; font-size:15px; display:none;">
              <?php
        } 
        else
        {
        ?>
              <input type="submit" class="btn btn-info" name="btnGetDetails" id="btnGetDetails" value="Get Details" style="height: 32px; width: 150px; font-size:15px;">
              <?php 
         } 
          ?>
            </div>
              
					 <!-- Added by chaitali on 2021-10-15 -->
					  <?php if(empty($row['regnumber'])){?>
					 <div class="form-group m_t_15">
						<label for="roleid" class="col-sm-3 control-label">Security Code<span style="color:#F00">*</span></label>
							<div class="col-sm-2">
							  <input type="text" name="code" id="code"  class="form-control" required>
								 <span class="error" id="captchaid" style="color:#B94A48;"></span>
								 
							</div>
							 <div class="col-sm-3">
								 <div id="captcha_img"><?php echo @$image;?></div>
								 <span class="error"><?php //echo form_error('code');?></span>
							</div>
							<div class="col-sm-3">
								  <a href="javascript:void(0);" id="reload_captcha" class="forget">Change Image</a>
								 <span class="error"><?php //echo form_error('code');?></span>
							</div>
							  
					</div> 
			<?php  }?>
            <div>
              <div class="col-sm-12" align="center"> <span style="color:#F00; font-size:14px;">Please insert your 'Membership No.' and click on 'Get Details' button. All below details will get filled automatically.</span> </div>
            </div>
          </div>
          
        </form>
      </div>
    </div>
  </section>
  <br />
  <!-- Close Get Details-->
  
  <form class="form-horizontal" data-parsley-validate="parsley" name="blendedAddForm" id="blendedAddForm" autocomplete="off"  method="post"  enctype="multipart/form-data">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            
              
              <input type="hidden" autocomplete="false" class="form-control" id="regnumberHidden" name="regnumberHidden" value="<?php if (isset($row['regnumber'])) { echo $row['regnumber'];} else { echo set_value('regnumber'); }?>">
              <input type="hidden" autocomplete="false" class="form-control" id="exam_code" name="exam_code" value="1018">
              <input type="hidden" autocomplete="false" class="form-control" id="exam_period" name="exam_period" value="997">
            <input type="hidden" autocomplete="false" class="form-control" id="exam_name" name="exam_name" value="<?php if (isset($row['qualification'])) { echo $row['qualification'];} else { echo set_value('qualification'); }?>">
            <input type="hidden" autocomplete="false" required="required" name="optedu" id="optedu" value="<?php
            if (isset($row['qualification'])) {echo $row['qualification']; }?>" />
              <input type="hidden" autocomplete="false" class="form-control" id="registrationtype" name="registrationtype" value="<?php if (isset($row['registrationtype'])) { echo $row['registrationtype'];}?>">
              
              
              <img alt="Loding..." title="Loding..." name="Loding..." src="<?php echo base_url();?>assets/images/ajax-loader.gif" id="TrainingTypeLoading" style="display:none;"/> 
            
            <div class="form-group" style="display:none;" id="showFees">
              <label for="roleid" class="col-sm-4 control-label">Fee Amount&nbsp;:</label>
              <div class="col-sm-6"> <strong>
                <div id="fees"></div>
                </strong> </div>
            </div>
            
           
            
            
            <div class="form-group" style="display:none;" id="vmsgDiv">
              <div class="col-sm-12" style="text-align:center; color:#F00; font-size:14px;">
                <div id="vmsg"></div>
              </div>
            </div>
          </div>
          
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
            <div class="box-body">
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
              <input type="hidden" autocomplete="false" name="regnumber" id="regnumber" value="<?php if (isset($row['regnumber'])) {echo $row['regnumber'];}?>"/>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">First Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                <div class="col-sm-2">
                  <input type="text" class="form-control" id="sel_namesub" name="sel_namesub" value="<?php if (isset($row['namesub'])) { echo $row['namesub'];}?>"readonly="readonly" placeholder="Prefix">
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" required value="<?php if (isset($row['firstname'])) {echo $row['firstname'];
}?>" readonly="readonly">
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Middle Name&nbsp;:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="middlename" id="middlename" value="<?php if (isset($row['middlename'])){echo $row['middlename'];}?>" readonly="readonly"  placeholder="Middle Name"/>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Last Name&nbsp;:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="lastname" id="lastname" value="<?php if (isset($row['lastname'])) {echo $row['lastname'];}?>" placeholder="Last Name" readonly="readonly"/>
                </div>
              </div>
            </div>

            
            <!-- Basic Details box closed-->
            <div class="box box-info">
              <div class="box-header with-border">
                <h3 class="box-title">Contact Details</h3>
              </div>
              <div class="box-body">

                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Date of Birth&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                  <div class="col-sm-3">
                    <?php
          if (isset($row['dateofbirth'])) {
            $originalDate = $row['dateofbirth'];
            $newDate      = date("d/m/Y", strtotime($originalDate));
          }
          ?>
                    <input type="text" class="form-control" id="dob1" name="dob1" placeholder="Date of Birth"  value="<?php
          if (isset($row['dateofbirth'])) {echo $newDate;} ?>"  required readonly="readonly">
                  </div>
                  <div class="col-sm-3">(DD/MM/YYYY)</div>

                  <span class="error"></span> </div>


                <!-- <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Designation&nbsp;<?php if (isset($row['registrationtype']) && $row['registrationtype'] != 'NM') { ?><span style="color:#F00">*</span><?php } ?>&nbsp;:</label>
                  <div class="col-sm-5">
                    <select id="designation" name="designation" class="form-control" <?php if (isset($row['registrationtype']) && $row['registrationtype'] != 'NM') {?> required <?php }?> disabled="disabled">
                      <option value="">- Select Designation -</option>
                      <?php
            if (count($designation) > 0) {
              foreach ($designation as $designation_row) {
            ?>
                      <option value="<?php echo $designation_row['dcode'];
            ?>" <?php if (isset($row['designation']) && $designation_row['dcode'] == $row['designation']) {?>selected="selected"<?php }?>> <?php echo $designation_row['dname'];?> </option>
                      <?php
              }
            }
            ?>
                    </select>
                    <input type="hidden" class="form-control" id="designation" name="designation" required value="<?php
if (isset($row['designation'])) {echo $row['designation'];}?>">
                    <span class="error"></span> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Bank/Institution working&nbsp;<?php if (isset($row['registrationtype']) && $row['registrationtype'] != 'NM') {?><span style="color:#F00">*</span><?php } ?>&nbsp;:</label>
                  <div class="col-sm-5"  style="display:block" >
                    <select id="institutionworking" name="institutionworking" class="form-control" 
                    
                    <?php if (isset($row['registrationtype']) && $row['registrationtype'] != 'NM') {?> required <?php }?>
                    
                     readonly="readonly" disabled="disabled">
                      <option value="">- Select Bank/Institution -</option>
                      <?php if (count($institution_master) > 0) {
                            foreach ($institution_master as $institution_row) {
                        ?>
                      <option value="<?php echo $institution_row['institude_id'];
                        ?>" <?php if (isset($row['associatedinstitute']) && $institution_row['institude_id'] == $row['associatedinstitute']) { ?>selected="selected"<?php }
                        ?>> <?php echo $institution_row['name']; ?> </option>
                      <?php
              }
            }
            ?>
                    </select>
                    <input type="hidden" class="form-control" id="institutionworking" name="institutionworking" required value="<?php if (isset($row['associatedinstitute'])) { echo $row['associatedinstitute'];}?>">
                    <span class="error"></span> </div>
                </div>
                
                 --><?php /*?><div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">GSTIN No.&nbsp;<!--<span style="color:#F00">*</span>&nbsp;-->:</label>
                  <div class="col-sm-5">
                  <!-- data-parsley-minlength="15" data-parsley-maxlength="15" data-parsley-trigger-after-failure="focusout"-->
                     <input type="text" class="form-control" id="gstin_no" name="gstin_no" placeholder="GSTIN No." value=""  <?php if (!isset($row['regnumber'])) { echo "disabled='disabled'";}  ?> data-parsley-minlength="15" data-parsley-maxlength="15" data-parsley-trigger-after-failure="focusout">
                    
                    <span class="error"></span> </div>
                    <div class="col-sm-12" align="center"> <span style="font-size:14px;"><strong>(Note&nbsp;:&nbsp;In case you are claiming reimbursement from your employer/bank.)</strong></span> </div>
                    
                </div><?php */?>
                
                
                
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Email&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                  <div class="col-sm-5">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email"  data-parsley-type="email" value="<?php if (isset($row['email'])) { echo $row['email'];
} ?>"  data-parsley-maxlength="45" required   data-parsley-trigger-after-failure="focusout" readonly="readonly">
                    <span class="error"> </span> </div>
                </div>
                <div class="form-group">
                  <label for="roleid" class="col-sm-4 control-label">Mobile&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                  <div class="col-sm-5">
                    <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Mobile" data-parsley-type="number"  data-parsley-minlength="10" data-parsley-maxlength="10"  value="<?php if (isset($row['mobile'])) {
    echo $row['mobile'];} ?>"  required  data-parsley-trigger-after-failure="focusout" readonly="readonly">
                    <span class="error"></span> </div>
                </div>
                <div class="form-group">
                
							<?php 
              $link='';
              if (isset($row['registrationtype'])) {
                if($row['registrationtype']=='O')
                  $link=base_url();
                  else
                $link= base_url().'nonmem';
              }
              
                
                    
                    ?>
						
						<div class="col-sm-12" style="text-align:center; color:#F00; font-size:14px;font-weight:700">
            <p style="color:#F00">“The above email and mobile number will be used by GARP for communicating the exam formalities, kindly ensure the details are latest, if not then click on the <a href="<?php echo $link ;?>" ><span style="color:#0000FF;">'Edit Profile'</span></a> button to change the details in your profile.”</p>
            </div>
                
                
                
              </div>
            </div>
          </div>
          <div class="box box-info">
            <div class="box-header with-border"> <h3 class="box-title" style='color:black'>
              <input name="declaration1" value="1" type="checkbox" required="required" 
			  <?php if(set_value('declaration1'))
			  {
				  echo set_radio('declaration1', '1');
				 }?>>&nbsp; I here by give my consent to GARP for sharing the FRR result with IIBF.
              </h3></div>
              <?php if($row['regnumber']){ ?>
            <div class="form-group m_t_15">
              <label for="roleid" class="col-sm-3 control-label">Security Code&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
              <div class="col-sm-2">
                <!-- <input type="text" name="code" id="code" required class="form-control" <?php if (!isset($row['regnumber'])) { echo "readonly='readonly'";}  ?>> -->
                <input type="text" name="code" id="code" required class="form-control" >
                <span class="error" id="captchaid" style="color:#B94A48;"></span> </div>
              <div class="col-sm-3">
                <div id="captcha_img"> <?php echo $image; ?></div>
                <span class="error"> </span> </div>
              <div class="col-sm-2"> <a href="javascript:void(0);" id="reload_captcha" class="forget" >Change Image</a> <span class="error"></span> </div>
            </div>
            <?php } ?>
            <div class="box-footer">
          
              <div class="col-sm-6 col-sm-offset-3">
              
                <input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Preview and Proceed for Payment" onclick="javascript:return blendedcheckform();" >  
                <a href="<?php echo base_url();?>Garp_exam" class="btn btn-default" >Reset</a> </div>
              
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="modal fade" id="confirm"  role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header"> 
            <!-- <button type="button" class="close"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
            <h4 class="modal-title" style="color:#F00"><strong>VERY IMPORTANT</strong></h4>
          </div>
          <div class="modal-body">
            <p style="color:#F00">Mandatory fields of form are blank. Kindly go to Edit Profile and update your profile in order to submit the form.</p>
          </div>
          <div class="modal-footer">
            <input type="button" name="Close" data-dismiss="modal" class="btn btn-primary" id="Close" value="Close">
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-dialog -->
    
  </form>
</div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/admin/plugins/datatables/jquery.dataTables.js"></script> 
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 
<script src="<?php echo base_url();?>js/validation_blended.js?<?php echo time();?>"></script> 
<script>
$(document).ready(function() {
    $("#regnumber").focus();
  var flag = $("#flag").val();
  if(flag == 1){
    $("#regnumber").val('');
    $("#regnumber").prop("readonly", false);
    $("#modify").hide();
    $("#btnGet").show();
  }
});

history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
window.addEventListener('popstate', function(event) {
    window.location.assign(site_url+"Garp_exam/");
});

$('#Close').click(function(event){
  event.preventDefault();
$("#residential_phone").css("position", "relative");
$("#phone").css("position", "relative");
});

$('#reload_captcha').click(function(event){
  event.preventDefault();
  $.ajax({
    type: 'POST',
    url: site_url+'blended/generatecaptchaajax/',
    success: function(res)
    { 
      if(res!='')
      {$('#captcha_img').html(res);
      }
    }
  });
});
$('#reload_captcha1').click(function(event){
  event.preventDefault();
  $.ajax({
    type: 'POST',
    url: site_url+'Garp_exam/generatecaptchaajax/',
    success: function(res)
    { 
      if(res!='')
      {$('#captcha_img1').html(res);
      }
    }
  });
});

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

$(function(){
  function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) === ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
  }

  if(readCookie('member_register_form'))
  {
    $('#error_id').html(''); 
    $('#error_id').removeClass("alert alert-danger alert-dismissible");
    createCookie('member_register_form', "", -1); 
  }
  

   /*$(document).keydown(function(event) {
        if (event.ctrlKey==true && (event.which == '67' || event.which == '86')) {
            if(event.which == '67')
      {
        alert('Key combination CTRL + C has been disabled.');
      }
      if(event.which == '86')
      {
        alert('Key combination CTRL + V has been disabled.');
      }
      event.preventDefault();
         }
    });*/
  
  $("body").on("contextmenu",function(e){
        return false;
    });
    $(this).scrollTop(0);

});

</script> 