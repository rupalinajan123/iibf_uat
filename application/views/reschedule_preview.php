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

<div class="container">
  <section class="content-header box-header with-border" style="background-color: #1287C0; padding:16px 10px;">
    <h1 class="register" style="padding:0;">Request registration form.<span style='display: block;font-size: 16px;line-height: 18px; margin: 6px 0 0 0; '>Candidates affected with COVID-19 during the exam time and could not take the exam(JAIIB/DB&F/SOB/CAIIB/CAIIB Electives examinations)</span></h1>    
	</section>
  <br />
  <!-- Close Get Details-->
  
  <form class="form-horizontal" name="RescheduleForm" id="RescheduleForm"  method="post"  enctype="multipart/form-data" action="add_record">
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Basic Details</h3>
            </div>
            <div class="box-body">
            
            <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Candidate Name:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="candidate_name" name="candidate_name" value="<?php echo $this->session->userdata['reschedule_examinfo']['candidate_name'] ?>" readonly="readonly">
                  
                </div>
              </div>
            
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Exam Name:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="exam_name" name="exam_name"  value="<?php echo $this->session->userdata['reschedule_examinfo']['exam_name'] ?>" readonly="readonly">
                </div>
              </div>
              
              
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Subject Name:</label>
                <?php 
					$i=1;
					foreach($this->session->userdata['reschedule_examinfo']['el_subject'] as $key=>$val){
						$this->db->where('subject_code',$key);
						$sql = $this->master_model->getRecords('subject_master','','subject_description,exam_date');
				?>
                
                <div class="col-sm-8" style="float:inline-end;">
                  <?php echo $i.". ". $sql[0]['subject_description'] ."( ".$sql[0]['exam_date']." )";?>
                </div>
                
                <?php $i++; }?>
              </div>
              
             
             <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Center Name:</label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="center_name" name="center_name"  value="<?php echo $this->session->userdata['reschedule_examinfo']['center_name']?>" readonly="readonly">
                </div>
              </div>
              
              
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Contact Number:<span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <input type="tel" class="form-control" id="contact_no" name="contact_no" value="<?php echo $this->session->userdata['reschedule_examinfo']['contact_no']?>" >
                  
                </div>
              </div>
              
              
              <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Email ID:<span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="candidate_email" name="candidate_email"  value="<?php echo $this->session->userdata['reschedule_examinfo']['candidate_email']?>" >
                </div>
              </div>
              
              
             <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">PwD Disability:<span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <input type="text" class="form-control" id="candidate_email" name="candidate_email"  value="<?php echo $this->session->userdata['reschedule_examinfo']['disability']?>" >
                </div>
              </div>
                
                
               <div class="form-group">
                <label for="roleid" class="col-sm-4 control-label">Covid Certificate:<span style="color:#F00">*</span></label>
                <div class="col-sm-5">
                  <a href="<?php echo base_url()?>/uploads/reschudule/<?php echo $this->session->userdata['reschedule_examinfo']['covid_certificate']?>" target="_blank" style="width: 60px;height: 70px;	display: inline-block;border: 1px solid #ccc;text-align: center;vertical-align: middle;line-height: 65px;font-size: 20px;font-weight: 500;background: #f8f8f8;">PDF</a>
                </div>
              </div>
            </div>
            <!-- Basic Details box closed-->
          </div>
          <div class="box box-info">
            <div class="box-header with-border"> &nbsp; </div>
            <div class="box-footer">
              <div class="col-sm-6 col-sm-offset-4">
                <input type="submit" name="btnSubmit" class="btn btn-info" id="btnSubmit" value="Submit">
            </div>
          </div>
          </div>
        </div>
      </div>
    </section>
    
    <div class="modal fade" id="confirm"  role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
            <p style="color:#F00"> <strong>VERY IMPORTANT</strong><br>
              I confirm that all the detail entered are correct as per my knowledge.</p>
          </div>
          <div class="modal-footer"> 
            <!--  <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="preview();">Confirm</button>-->
            <input type="submit" name="btnSubmit" class="btn btn-primary" id="btnSubmit" value="Confirm" >
          </div>
        </div>
        <!-- /.modal-content --> 
      </div>
      <!-- /.modal-dialog --> 
    </div>
    
    <!-- /.modal-dialog -->
    
  </form>
  
 
</div>
<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">
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
  
$('input[type=file]').change(function () {
		
	var files = !!this.files ? this.files : [];
	//if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
	if (/^image/.test( files[0].type)){ // only image file
		var reader = new FileReader(); // instance of the FileReader
		reader.readAsDataURL(files[0]); // read the local file
		reader.onloadend = function(){ // set image data as background of div
			$('#hiddenphoto').val(this.result);
			$("#image_upload_scanphoto_preview").attr('src',$("#hiddenphoto").val());
		}
	}
	
});
  
});

function checkform(){
	
	
	$('#error_id').html(''); 
	$('#success_id').html(''); 
	$('#error_id').removeClass("alert alert-danger alert-dismissible");
	$('#success_id').removeClass("alert alert-danger alert-dismissible");
	$('#tiitle_error').html(''); 
	$('#captchaid').html('');
	
	var el_subject_cnt =  $('.show_el_subject :input[type="checkbox"]:checked').length;
	
	if(el_subject_cnt == 0){
		alert("Please select atleast one subject");
		return false;
	}
	
	var code=$('#code').val();
	var form_flag=$('#RescheduleForm').parsley().validate();
	
	
	if(code != '' && form_flag){
		$.ajax({
			url: site_url+'Covid/ajax_check_captcha',
			type: 'post',
			data:'code='+code+'&random='+Math.random(),
			success: function(result) {
				if(result=='true'){
					$('#confirm').modal('show');
				}else{
					$('#captchaid').html('Enter valid captcha code.');
				}
			}
		});
	}
	
	
	
}

history.pushState(null, null, '<?php echo $_SERVER["REQUEST_URI"]; ?>');
window.addEventListener('popstate', function(event) {
    window.location.assign(site_url+"Covid/");
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
    url: site_url+'Covid/generatecaptchaajax/',
    success: function(res)
    { 
      if(res!='')
      {$('#captcha_img').html(res);
      }
    }
  });
});


$(function(){
  $("body").on("contextmenu",function(e){
        return false;
    });
    $(this).scrollTop(0);

});

</script> 