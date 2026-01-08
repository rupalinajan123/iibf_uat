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
    <h1 class="register">Centre change request update JAIIB/DB&F/SOB/CAIIB/CAIIB Elective Exam - Aug/Sep-2021</h1>
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
        <?php if ($this->session->flashdata('flsh_msg_success') != '') {?>
        <div class="alert alert-success"> <?php echo $this->session->flashdata('flsh_msg_success'); ?> </div>
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
        <form name="getDetailsForm" autocomplete="off" id="getDetailsForm" method="post" action="<?php echo base_url(); ?>centerchnage">
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
              <input type="text" class="form-control" id="regnumber" name="regnumber" placeholder="Membership No." required value="<?php if (isset($row['mem_mem_no'])) { echo $row['mem_mem_no'];} else { echo set_value('regnumber'); }
?>" <?php if (isset($row['mem_mem_no'])) { echo "readonly='readonly'";} elseif (set_value('regnumber')) { echo "readonly='readonly'"; } ?> style="border-color:#000;" title="Membership No.">
            </div>
            <div class="col-sm-3" style="padding-bottom: 10px">
              <?php 
          if (isset($row['mem_mem_no']) || set_value('regnumber')) {
        ?>
              <a href="<?php echo base_url();?>centerchnage" class="btn btn-info" id="modify" style="height: 32px; width: 150px">Modify</a>
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
            
              
              <input type="hidden" autocomplete="false" class="form-control" id="regnumberHidden" name="regnumberHidden" value="<?php if (isset($row['mem_mem_no'])) { echo $row['mem_mem_no'];} else { echo set_value('regnumber'); }?>">
              <input type="hidden" autocomplete="false" class="form-control" id="exam_code" name="exam_code" value="<?php if (isset($row['exm_cd'])) { echo $row['exm_cd'];} else { echo set_value('exam_cod'); }?>">
              <input type="hidden" autocomplete="false" class="form-control" id="exam_period" name="exam_period" value="<?php if (isset($row['exm_prd'])) { echo $row['exm_prd'];} else { echo set_value('exam_period'); }?>">
            <!--<input type="hidden" autocomplete="false" class="form-control" id="exam_name" name="exam_name" value="<?php if (isset($row['qualification'])) { echo $row['qualification'];} else { echo set_value('qualification'); }?>">-->
              
            <!--  <input type="hidden" autocomplete="false" class="form-control" id="registrationtype" name="registrationtype" value="<?php if (isset($row['registrationtype'])) { echo $row['registrationtype'];}?>">-->
              
              
              <img alt="Loding..." title="Loding..." name="Loding..." src="<?php echo base_url();?>assets/images/ajax-loader.gif" id="TrainingTypeLoading" style="display:none;"/> 
            
           
            
           
            
            
            <div class="form-group" style="display:none;" id="vmsgDiv">
              <div class="col-sm-12" style="text-align:center; color:#F00; font-size:14px;">
                <div id="vmsg"></div>
              </div>
            </div>
          </div>
          
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Details</h3>
            </div>
            <div class="box-body">
              <div class="alert alert-danger alert-dismissible" id="reg_form_validation_ajax_resp" style="display:none"> <span>display ajax response errors here</span> </div>
              <input type="hidden" autocomplete="false" name="regnumber" id="regnumber" value="<?php if (isset($row['mem_mem_no'])) {echo $row['mem_mem_no'];}?>"/>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Exam Name&nbsp;<span style="color:#F00">*</span>&nbsp;:</label>
                
                <div class="col-sm-6">
                  <input type="text" class="form-control" id="exam_name" name="exam_name" placeholder="Exam Name" required value="<?php if (isset($row['description'])) {echo $row['description'];
}?>" readonly="readonly">
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Old Centre Name&nbsp;:</label>
                <div class="col-sm-6">
                    <input type="hidden" autocomplete="false" name="old_center_code" id="old_center_code" value="<?php if (isset($row['center_code'])) {echo $row['center_code'];}?>"/>
                  <input type="text" class="form-control" name="old_center_name" id="new_center_name" value="<?php if (isset($row['center_name'])){echo $row['center_name'];}?>" readonly="readonly"  placeholder="Centre Name"/><span style="color:#F00">(Centre selected during registration Mar/Apr 2021)</span>
                </div>
              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">New Centre Name&nbsp;<span style="color:#F00">*</span>:</label>
                <div class="col-sm-6">
                     <select class="form-control" id="new_center_code" name="new_center_code" required >
                      <option value="">- Select centre -</option>
                      <?php
            if (count($center) > 0) {
              foreach ($center as $row1) {
            ?>
                      <option value="<?php echo $row1['center_code']; ?>" <?php  if (isset($row['new_center_code']) && $row1['center_code'] == $row['new_center_code']) {?>selected="selected"<?php } ?>><?php echo $row1['center_name']; ?> </option>
                      <?php
              }
            }
            ?>
                    </select></div>
              </div>
            </div>
            
            <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Transfer Order Upload&nbsp;<span style="color:#F00">*</span>:</label>
                <div class="col-sm-6">
                    <input type="file" name="userfile" required/>
                    </div>
              </div>
            

            <!-- Basic Details box closed-->
           
          </div>
          <div class="box box-info">
            <div class="box-header with-border"> <h3 class="box-title" style='color:black'>I confirm that I have been transferred to another city due to work requirement and have uploaded the transferred order.
<br>I am also aware that the change of centre request will be considered on case-to-case basis subject to availability of seats and is available on first-come-first-serve basis.
              <br>
              <input name="declaration1" value="1" type="checkbox" required="required" 
			  <?php if(set_value('declaration1'))
			  {
				  echo set_radio('declaration1', '1');
				 }?>>&nbsp; I Agree<br></h3></div>
            <div class="form-group m_t_15">
              Note:
The Change of request If accepted, a revised Admit letter will be sent to the registered email id as well as will be available under the candidate’s login profile one week before the exam date.
              
            </div>
            <div class="box-footer">
          
              <div class="col-sm-6 col-sm-offset-4">
              
                <input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Submit" >  
                <a href="<?php echo base_url();?>centerchnage" class="btn btn-default" >Reset</a> </div>
              
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
    window.location.assign(site_url+"centerchnage/");
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
    url: site_url+'centerchnage/generatecaptchaajax/',
    success: function(res)
    { 
      if(res!='')
      {$('#captcha_img').html(res);
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