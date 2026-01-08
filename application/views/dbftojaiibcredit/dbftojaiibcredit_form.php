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

.content-wrapper {

	border-bottom: 1px solid #1287c0;

	border-left: 1px solid #1287c0;

	border-right: 1px solid #1287c0;

	width: 70%;

	margin:0 auto 10px !important;

	padding:0 10px;

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

.content-header {

	padding:0;

	margin-bottom:10px;

}

.nobg {

	background: rgba(0, 0, 0, 0) none repeat scroll 0 0 !important;

	border: medium none !important;

}

.email {

	line-height:18px !important;

}

.box-body {

	padding: 0;

}

.example {

	text-align:left !important;

}

.example select {

	padding:5px 10px !important;

	border:1px solid #888 !important;

	border-radius:0 !important;

}
.main-header {

	width: 70% !important;
}
</style>

<?php 

header('Cache-Control: must-revalidate');

header('Cache-Control: post-check=0, pre-check=0', FALSE);

?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper"> 

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1 class="register"> DB&F To JAIIB Credit Transfer </h1>


  </section>

  
  <form class="form-horizontal" name="dbftojaiibcreditform" id="dbftojaiibcreditform"  method="post"  enctype="multipart/form-data">

    <input  type="hidden" class="exam_form_field" name="regnumber" id="regnumber" value="<?php echo $regnumber;?>">
<?php
/*
$server_ip = $_SERVER['SERVER_ADDR'];
echo "<br>SERVER_ADDR IP Address: $server_ip";

$app_server = explode('.',gethostname());
if(isset($app_server[0])){ echo "<br>".$app_server[0];}
echo "<br><br>";*/
?>

    <section class="content">

      <div class="row">

        <div class="col-md-12"> 

          <span style="color:#F00">Enter your details carefully, correction will not be possible later.</span> 

          <!-- Horizontal Form -->

          <div class="box-header with-border">

          <h3 class="box-title">Basic Details:</h3>

          </div>
          <div class="box box-info">

         
            <!-- form start -->

            

            <?php //echo validation_errors(); ?>

            <?php if($this->session->flashdata('error')!=''){?>

            <div class="alert alert-danger alert-dismissible" id="error_id">

              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

              <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 

              <?php echo $this->session->flashdata('error'); ?> </div>

            <?php } if($this->session->flashdata('success')!=''){ ?>

            <div class="alert alert-success alert-dismissible" id="success_id">

              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

              <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>--> 

              <?php echo $this->session->flashdata('success'); ?> </div>

            <?php } 

			 if(validation_errors()!=''){?>

            <div class="alert alert-danger alert-dismissible" id="error_id">

              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

              <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>--> 

              <?php echo validation_errors(); ?> </div>

            <?php } 

			 ?>

            <div class="box-body">
               <div class="row">
                <b  class="col-sm-6 " style="text-align: center;padding: 1%;">Ordinary Membership Details</b>
                <b  class="col-sm-6 " style="text-align: center;padding: 1%;">DB&F Membership Details</b>
              </div>
              <div class="row">
                 
                <div class="col-md-6">
                 
                  
                <div class="form-group" style="text-align: center;">

                      <?php 
                            if(is_file(get_img_name($aCandidate['regnumber'],'p')))
                            { ?>
                            <img src="<?php echo base_url();?><?php echo get_img_name($aCandidate['regnumber'],'p');?><?php echo '?'.time(); ?>" height="100" width="100" >
                            
                            <?php }
                            ?>

                  </div>
                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label"> Membership No. <span style="color:#f00">*</span></label>

                  

                    <div class="col-sm-9">
                    
                      <input type="text" readonly class="form-control exam_form_field" id="regnumber" name="regnumber" placeholder="Ordinary Membership Number" required value="<?php echo ('' !== set_value('regnumber'))?  set_value('regnumber') : $aCandidate['regnumber']; ?>" >

                      <span class="error regnumber_error">


                      </span> </div>

                  </div>

                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label">Full Name</label>

                    <div class="col-sm-9">

                      <input readonly required type="text" value="<?php echo $aCandidate['firstname'].' '.$aCandidate['lastname']; ?>" class="form-control exam_form_field display_name"   id="display_name" name="display_name" placeholder="Full Name"  >

                      <span class="error">

                      <?php //echo form_error('display_name');?>

                      </span> </div> </div>

                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label">Date Of Birth</label>

                    <div class="col-sm-9">

                      <input type="text" required readonly value="<?php echo $aCandidate['dateofbirth']; ?>" class="form-control exam_form_field" id="dateofbirth" name="dateofbirth" placeholder="Date Of Birth"  >

                      <span class="error">

                      <?php //echo form_error('lastname');?>

                      </span> 
                    </div>
                  </div>
                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label">Mobile Number</label>

                    <div class="col-sm-9">

                      <input type="text" required readonly value="<?php echo $aCandidate['mobile']; ?>" class="form-control exam_form_field mobile" id="mobile" name="mobile" placeholder="Mobile"  >

                      <span class="error">

                      <?php //echo form_error('lastname');?>

                      </span> 
                    </div>
                  </div>
                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label">Email Id</label>

                    <div class="col-sm-9">

                      <input type="text" required readonly value="<?php echo $aCandidate['email']; ?>" class="form-control exam_form_field" id="email" name="email" placeholder="Email"  >

                      <span class="error">

                      <?php //echo form_error('lastname');?>

                      </span> 
                    </div>
                  </div>

                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label">	Date of Election </label>

                    <div class="col-sm-9">

                      <input type="text" required readonly value="<?php echo date('Y-m-d',strtotime($aCandidate['createdon'])); ?>" class="form-control exam_form_field" id="createdon" name="createdon" placeholder="Date of Election"  >

                      <span class="error">

                      <?php //echo form_error('lastname');?>

                      </span> 
                    </div>
                  </div>


                </div>


                <div class="col-md-6">
                  <div class="form-group" style="text-align: center;">

                      <?php 
                            if(is_file(get_img_name($dbCandidate['regnumber'],'p')))
                            { ?>
                            <img src="<?php echo base_url();?><?php echo get_img_name($dbCandidate['regnumber'],'p');?><?php echo '?'.time(); ?>" height="100" width="100" >
                            
                            <?php }
                            ?>

                  </div>
                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label"> Membership No. <span style="color:#f00">*</span></label>

                  

                    <div class="col-sm-9">
                    
                      <input type="text" class="form-control exam_form_field" id="dbf_regnumber" name="dbf_regnumber" placeholder="DBF Membership Number" readonly value="<?php echo $dbCandidate['regnumber']; ?>" >

                      <span class="error dbf_regnumber_error">


                      </span> </div>

                  </div>

                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label">Full Name</label>

                    <div class="col-sm-9">

                      <input readonly required value="<?php echo $dbCandidate['firstname'].' '.$dbCandidate['lastname']; ?>" type="text" class="form-control exam_form_field display_name"   id="display_name" name="display_name" placeholder="Full Name"  >

                      <span class="error">

                      <?php //echo form_error('display_name');?>

                      </span> </div> </div>

                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label">Date Of Birth</label>

                    <div class="col-sm-9">

                      <input type="text" required readonly value="<?php echo $dbCandidate['dateofbirth']; ?>" class="form-control exam_form_field" id="dateofbirth" name="dateofbirth" placeholder="Date Of Birth"  >

                      <span class="error">

                      <?php //echo form_error('lastname');?>

                      </span> 
                    </div>
                  </div>
                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label">Mobile Number</label>

                    <div class="col-sm-9">

                      <input type="text" required readonly value="<?php echo $dbCandidate['mobile']; ?>" class="form-control exam_form_field mobile" id="mobile" name="mobile" placeholder="Mobile"  >

                      <span class="error">

                      <?php //echo form_error('lastname');?>

                      </span> 
                    </div>
                  </div>
                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label">Email Id</label>

                    <div class="col-sm-9">

                      <input type="text" required readonly  value="<?php echo $dbCandidate['email']; ?>" class="form-control exam_form_field" id="email" name="email" placeholder="Email"  >

                      <span class="error">

                      <?php //echo form_error('lastname');?>

                      </span> 
                    </div>
                  </div>

                  <div class="form-group">

                    <label for="roleid" class="col-sm-3 control-label">	Date of Registration </label>

                    <div class="col-sm-9">

                      <input type="text" required readonly value="<?php echo date('Y-m-d',strtotime($dbCandidate['createdon'])); ?>" class="form-control exam_form_field" id="dbf_createdon" name="createdon" placeholder="Date of Registration"  >

                      <span class="error">

                      <?php //echo form_error('lastname');?>

                      </span> 
                    </div>
                  </div>

                </div>
              </div>
            </div>

          </div>


          <div class="box box-info">

            <div class="box-header with-border">

              <h3 class="box-title">Subject Details:</h3>

            </div>

            

            <div class="box-body">

              <div class="form-group">

                <table class="table">
                  <tr>
                  <th>Sr.No</th><th>Subjects</th><th>Status</th>
                            </tr>
                  <?php
                  $i=1;
                  foreach($subject_details  as $subject) {
                    $status = 'Fail';
                    
                    
                    ?>
                    <tr>
                      <td><?php echo $i++; ?></td>
                      <td><?php echo $subject['subject_description'] ?></td>
                      <td>
                        
                      <?php
                      if(!in_array($subject['subject_code'],$not_eligible_subject_details_Arr)){
                          $status = 'Eligible For Credit';
                          echo'<input type="hidden" name="credit_subjects[]" value="'.$subject['subject_code'].'">';
                      }
                    ?><?php echo $status ?></td>
                    </tr>
                    <?php 
                  }
                  ?>
                </table>

               <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Balance Attempt Left</label>

                  <div class="col-sm-5">

                    <input readonly required type="text" value="<?php echo $balance_attempt_left ;?>" autocomplete="off" id="balance_attempt_left" name="balance_attempt_left"  class="form-control pull-right exam_form_field balance_attempt_left" >
                    
                  </div>

              </div>
              </div>
              </div>

              <div class="box box-info">

              <div class="box-header with-border">

              <h3 class="box-title">Bank appointment/ Joining Details:</h3>

              </div>
              <div class="form-group">
                <label for="roleid" class="col-sm-3 control-label">Upload Bank appointment / Joining Letter  <span style="color:#f00">**</span></label>
                <div class="col-sm-5">
                  <input  type="file" class="" name="offer_letter" id="offer_letter" required onchange="validateOfferLetterFile(event, 'error_photo_size', 'image_upload_offer_letter_preview', '5000kb')">
                  <input type="hidden" id="hiddenoffer_letter" name="hiddenoffer_letter" value="<?php if(isset($this->session->userdata['enduserinfo']['offer_letter'])) echo $this->session->userdata['enduserinfo']['offer_letter']; ?>">
                  <span class="note">Please Upload only .pdf  Files upto 5MB</span></br>
                  <div id="error_photo" class="error"></div>
                  <br>
                  <div id="error_photo_size" class="error"></div>
                  <span class="photo_text" style="display:none;"></span> <span class="error">
                 
                  </span> </div>
                  <?php  $defaultimg = "/assets/images/default1.png"; ?>
                   
                  
              </div>
              <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Date of Bank Appointment letter</label>

                  <div class="col-sm-5">

                    <input required type="text" autocomplete="off" id="bank_letter_date" name="bank_letter_date"  class="form-control pull-right exam_form_field bank_letter_date" >
                    
                  </div>

              </div>

                <div class="form-group">

                  <label for="roleid" class="col-sm-3 control-label">Date of Bank Joining</label>

                  <div class="col-sm-5">

                    <input required type="text"  autocomplete="off" id="bank_joining_date" name="bank_joining_date"  class="form-control pull-right exam_form_field bank_joining_date" >
                    <span class="error_bank_joining_date" style="color:red;"></span>
                  </div>

                </div>
            </div>

          </div>


          
          <div class="box box-info">

            <div class="box-header with-border">

              <h3 class="box-title">Fee Details:</h3>

            </div>

            

            <div class="box-body">

              <div class="form-group">

                <label for="roleid" class="col-sm-3 control-label">Fee Amount</label>

                <div class="col-sm-5 "> 
                    Rs. <?php 
                    if($aCandidate['state']=='MAH')
                    echo $fee['cs_tot']; 
                    else echo $fee['igst_tot']; ?>

                 </div>

              </div>



            </div>

          </div>


          <div class="box box-info">

            <div class="box-header with-border header_blue">

              <h3 class="box-title">Declaration:</h3>

            </div>

            <div class="box-body">

              <div class="form-group">

                <label for="roleid" class="col-sm-2 control-label"> </label>

                <div class="col-sm-12">

                  <ol>

                    <li>I hereby declare that all the information given in the application is true, complete and correct. I understand that in the event of any information being found false or incorrect subsequent to approval of the credit transfer request, the application is liable to be cancelled/terminated. </li>

            
                    
                  </ol>

                </div>

              </div>

            </div>

          </div>

          <div class="box box-info">

            <div class="box-header with-border">

              <h3 class="box-title">

                <input name="declaration1" value="1" type="checkbox" required="required" 

			  <?php if(set_value('declaration1'))

              {

                  echo set_radio('declaration1', '1');

                 }?>>

                &nbsp; I Accept</h3>

            </div>

            

            <div class="box-footer">

              <div class="col-sm-7 col-sm-offset-1"> <button type="submit" name="btnSubmit" class="btn btn-info" id="preview">Preview and Proceed for Payment</button> 

           
                <button type="reset" class="btn btn-default pull-right"  name="btnReset" id="btnReset">Reset</button>

              </div>

            </div>

          </div>

        </div>

      </div>


    </section>

  </form>

</div>

<link href="<?php echo base_url();?>assets/admin/dist/css/styles.css" rel="stylesheet">

<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script> 

<script src="<?php echo base_url();?>js/cscvalidation.js"></script> 

<script type="text/javascript">

  <!--var flag=$('#usersAddForm').parsley('validate');-->


$('#bank_joining_date').change(function(e) {
     
      $('.bank_joining_date').text('');
      //alert('Button clicked!');
      let dbf_createdon = new Date($('#dbf_createdon').val());
      let bank_joining_date = new Date($('#bank_joining_date').val());
      if (dbf_createdon > bank_joining_date) {
        $('.error_bank_joining_date').text('Since your date of joining the Bank is before DB&F Registration Date, you are not eligible for credit transfer. You may kindly apply for the upcoming JAIIB examinations only.');
        //$('#bank_joining_date').val('');
        return false;
      } 
      
    });
</script> 

<script>
$('.bank_joining_date').datepicker({
    format: 'dd-mm-yyyy',
    endDate:  new Date(),      
    
});
$('.bank_letter_date').datepicker({
    format: 'dd-mm-yyyy',
    endDate:  new Date(),      
    
});
function validateOfferLetterFile(event, error_id, show_img_id, size, img_width, img_height)
  {
    var srcid = event.srcElement.id;    
    if( document.getElementById(srcid).files.length != 0 )
    {
      var file = document.getElementById(srcid).files[0];

      if(file.size == 0)
      {
        $('#'+error_id).text('Please select valid file');
        $('#'+document.getElementById(srcid).id).val('')
        $('#'+show_img_id).attr('src', "/assets/images/default1.png");
      }  
      else
      {
        var file_size = document.getElementById(srcid).files[0].size/1024;
        var mimeType=document.getElementById(srcid).files[0].type;

        var allowedFiles = [".pdf"];
        if($('#'+document.getElementById(srcid).id+'_allowedFilesTypes').text() != "")
        {
          var allowedFiles = $('#'+document.getElementById(srcid).id+'_allowedFilesTypes').text().split(",");
        }
        var regex = new RegExp("([a-zA-Z0-9\s_\\((\d+)\)\.\-:])+(" + allowedFiles.join('|') + ")$");

        var reader = new FileReader();

        var check_size = '';
      
        if(size.indexOf('kb') !== -1){
          var check_size = size.split('k');
        }
        if(size.indexOf('mb') !== -1){
          var check_size = size.split('m');
        }

        reader.onload = function(e) {
          var img = new Image();      
          img.src = e.target.result;

          if (reader.result == 'data:') {
            $('#'+error_id).text('This file is corrupted');
            //$('.btn_submit').attr('disabled',true);
            //$('#'+show_img_id).removeAttr('src');
            $('#'+document.getElementById(srcid).id).val('')
            $('#'+show_img_id).attr('src', "/assets/images/default1.png");
          } 
          else {
            //$('#'+error_id).text('This file can be uploaded');
            if (!regex.test(file.name.toLowerCase())) {
              $('#'+error_id).text("Please upload " + allowedFiles.join(', ') + " only.");
              //$('.btn_submit').attr('disabled',true);
              //$('#'+show_img_id).removeAttr('src');
              $('#'+document.getElementById(srcid).id).val('')
              $('#'+show_img_id).attr('src', "/assets/images/default1.png");
            }
            else{
              if(file_size > check_size[0]) 
              {
                //console.log('if');
                $('#'+error_id).text("Please upload file less than "+size);
                //$('.btn_submit').attr('disabled',true);
                //$('#'+show_img_id).removeAttr('src');
                $('#'+document.getElementById(srcid).id).val('')
                $('#'+show_img_id).attr('src', "/assets/images/default1.png");
              } 
              else if(file_size < 8) //IF FILE SIZE IS LESS THAN 8KB
              {
                $('#'+error_id).text("Please upload file having size more than 8KB");
                $('#'+document.getElementById(srcid).id).val('')
                $('#'+show_img_id).attr('src', "/assets/images/default1.png");
              }
              else{
                img.onload = function () {
                  var width = this.width;
                  var height = this.height;

                  //console.log(width+'----'+height);
                  
                  if(width > img_width && height > img_height){
                    $('#'+error_id).text(' Uploaded File dimensions are '+width+'*'+height+' pixel. Please Upload file dimensions between '+img_width+'*'+img_height+' pixel');
                    //$('.btn_submit').attr('disabled',true);
                    //$('#'+show_img_id).removeAttr('src');
                    $('#'+document.getElementById(srcid).id).val('')
                    $('#'+show_img_id).attr('src', "/assets/images/default1.png");
                  }
                  else{
                    //console.log('else');
                    $('#'+error_id).text("");
                    $('.btn_submit').attr('disabled',false);
                    $('#'+show_img_id).attr('src', '');
                    $('#'+show_img_id).removeAttr('src');                  
                    $('#'+show_img_id).attr('src', reader.result);

                    var img = new Image();
                    img.src = reader.result;

                    //$('.'+show_img_id+'_zoom').zoom();
                  }
                }
              
              }
            }
          } 
        }
      
        reader.readAsDataURL(event.target.files[0]);
      }
    }
    else
    {
      $('#'+error_id).text('Please select file');
      //$('.btn_submit').attr('disabled',true);
      //$('#'+show_img_id).removeAttr('src');
      $('#'+document.getElementById(srcid).id).val('')
      $('#'+show_img_id).attr('src', "/assets/images/default1.png");
    }
  }

	$(document).ready(function() 

	{

	

		$(document).keydown(function(event) {

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

    });

	

	});




</script> 

<script>

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

	

    $('#new_captcha').click(function(event){

        event.preventDefault();

    $.ajax({

 		type: 'POST',

 		url: site_url+'Register/generatecaptchaajax/',

 		success: function(res)

 		{	

 			if(res!='')

 			{$('#captcha_img').html(res);

 			}

 		}

    });

});
	

});

</script> 

