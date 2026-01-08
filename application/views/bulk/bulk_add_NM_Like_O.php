<?php
$disaply_class = '';
$jK_exam_code = array(1005,1009);//added to disable e-learning for J & K (Pooja mane 12-2-24)
?>
<!-- custom style for datepicker dropdowns -->
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
   .example {
   width: 33%;
   min-width: 370px;
   /* padding: 15px;*/
   display: inline-block;
   box-sizing: border-box;
   /*text-align: center;*/
   }
   .example select {
   padding: 10px;
   background: #ffffff;
   border: 1px solid #CCCCCC;
   border-radius: 3px;
   margin: 0 3px;
   }
   .example select.invalid {
   color: #E9403C;
   }
   .mandatory-field,
   .required-spn {
   color: #F00;
   }
   .box-title-hd {
   color: #3c8dbc;
   font-size: 16px;
   margin: 0;
   }
</style>
<!-- Content Wrapper. Contains page content -->
<?php
$disaply_class = '';
if ($_SESSION['is_elearning_course']=='y') {
   $disaply_class='hidden';
}
?>
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1 class="register"> Examination Application(Registration) for Non-Member<br/>
      </h1>
      <span style="color:#F00"></span>

   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-info">
               <!-- form start -->
               <?php //echo validation_errors(); ?>
               <?php if($this->session->flashdata('error')!=''){?>
               <div class="alert alert-danger alert-dismissible" id="error_id">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                  <?php echo $this->session->flashdata('error'); ?>
               </div>
               <?php } if($this->session->flashdata('success')!=''){ ?>
               <div class="alert alert-success alert-dismissible" id="success_id">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                  <?php echo $this->session->flashdata('success'); ?>
               </div>
               <?php } 
                  if(validation_errors()!=''){?>
               <div class="alert alert-danger alert-dismissible" id="error_id">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <!--<h4><i class="icon fa fa-ban"></i> Alert!</h4>-->
                  <?php echo validation_errors(); ?>
               </div>
               <?php } 
                  ?>
               <div class="box-header with-border">
                  <h3 class="box-title">Get Details</h3>
               </div>
               <div class="box-body">
                  <form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url();?>bulk/BulkApplyNM/add_member/" autocomplete="off">
                     <div class="form-group">
                        <label class="col-sm-3 control-label">Membership No :</label>
                        <div class="col-sm-5">
                           <input type="text" class="form-control" name="regnumber" placeholder="Registration no" value="" />
                        </div>
                        <button name="getdata">Get Details</button>
                     </div>
        
                  </form>
               </div>
            </div>
            <?php if(!empty($mem_info)) {
               //print_r($mem_info); ?>
            <?php } ?>
            <form class="form-horizontal" name="nonmemAddForm" id="nonmemAddForm" method="post" enctype="multipart/form-data" action="<?php echo base_url();?>bulk/BulkApplyNM/comApplication_reg/" autocomplete="off">
            </form>
         </div>
      </div>
   </section>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <center>
               <strong>
                  <h4 class="modal-title" id="myModalLabel" style="color:#F00"> Important Notice</h4>
               </strong>
            </center>
         </div>
         <div class="modal-body">
           
            Dear Candidate,<br><br>
            <p>
               You have opted for the services of a scribe for the above mentioned examination under <strong>Remote Proctored mode</strong>.<br><br>
               For the purpose of approving the scribe and to give you extra time as per rules, you are requested to email Admit letter, Details of the scribe, Declaration and Relevant Doctor's Certificates to <strong>anil@iibf.org.in / sajan@iibf.org.in</strong> at least one week before the exam date<br><br>
               Your application for scribe will be scrutinized and an email will be sent 1-2 days before the exam date, mentioning the status of acceptance of scribe.<br><br>
               You will be required to produce the print out of permission granted, required documents along with the Admit Letter to the test conducting authority (procter).<br><br>
            </p>
            <p style="color:#F00">Click Here - <a href="http://www.iibf.org.in/documents/Scribe_Guideliness_R-150219.pdf" target="_blank">GENERAL GUIDELINES/RULES FOR USING SCRIBE BY VISUALLY IMPAIRED & ORTHOPEADICALLY CHALLENGED CANDIDATES</a><br>
            </p>
            Regards,<br>
            IIBF Team.<br>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<script src="<?php echo base_url();?>assets/admin/dist/js/jquery.date-dropdowns.js"></script>
<!--<script src="<?php //echo base_url();?>js/validation.js"></script>-->
<script type="text/javascript">
   <!--var flag=$('#usersAddForm').parsley('validate');-->
   
   
   
</script>
<script>
   $(document).ready(function(){
   
   
   
    $('#scribe_flag').on('change', function(e){
   
   
   
      if(e.target.checked){
   
   
   
        $('#myModal').modal();
   
   
   
      }
   
   
   
   });
   
   
   
   });
   
   
   
</script>
<script>
   $(document).ready(function(){
   
   
   
   $("#elearning_flag_Y").click(function(){
   
   
   
        var cCode =  document.getElementById('txtCenterCode').value;
   
   
   
        var examType = document.getElementById('extype').value;
   
   
   
        var examCode = document.getElementById('examcode').value;
   
   
   
        var temp = document.getElementById("selCenterName").selectedIndex;
   
   
   
        var selected_month = document.getElementById("selCenterName").options[temp].className;
   
   
   
        var eprid = document.getElementById('eprid').value;
   
   
   
        var excd = document.getElementById('excd').value;
   
   
   
        var grp_code = document.getElementById('grp_code').value;
   
   
   
        var extype= document.getElementById('extype').value;
   
   
   
        var mtype= document.getElementById('mtype').value;
   
   
   
        var discount_flag = document.getElementById('discount_flag').value;
   
   
   
        var free_paid_flag = document.getElementById('free_paid_flag').value;
   
   
   
        if(document.getElementById('elearning_flag_Y').checked){
   
   
   
            var Eval = document.getElementById('elearning_flag_Y').value;
   
   
   
        }
   
        if(document.getElementById('elearning_flag_N').checked){
   
   
   
            var Eval = document.getElementById('elearning_flag_N').value;
   
   
   
        }
   
        if(cCode != ''){
   
   
   
            var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval+'&discount_flag='+discount_flag+'&free_paid_flag='+free_paid_flag;
   
   
   
                $.ajax({
   
   
   
                        url:site_url+'Bulk_fee/getFee/',
   
   
   
                        data: datastring,
   
   
   
                        type:'POST',
   
   
   
                        async: false,
   
   
   
                        success: function(data) {
   
   
   
                         if(data)
   
   
   
                        {
   
   
   
                            document.getElementById('fee').value = data ;
   
   
   
                            document.getElementById('html_fee_id').innerHTML =data;
   
   
   
                            //response = true;
   
   
   
                        }
   
   
   
                    }
   
   
   
                });
   
   
   
        }
   
   
   
    });
   
   $("#elearning_flag_N").click(function(){
   
   
   
        var cCode =  document.getElementById('txtCenterCode').value;
   
   
   
        var examType = document.getElementById('extype').value;
   
   
   
        var examCode = document.getElementById('examcode').value;
   
   
   
        var temp = document.getElementById("selCenterName").selectedIndex;
   
   
   
        var selected_month = document.getElementById("selCenterName").options[temp].className;
   
   
   
        var eprid = document.getElementById('eprid').value;
   
   
   
        var excd = document.getElementById('excd').value;
   
   
   
        var grp_code = document.getElementById('grp_code').value;
   
   
   
        var extype= document.getElementById('extype').value;
   
   
   
        var mtype= document.getElementById('mtype').value;
   
   
   
        var discount_flag = document.getElementById('discount_flag').value;
   
   
   
        var free_paid_flag = document.getElementById('free_paid_flag').value;
   
   
   
        if(document.getElementById('elearning_flag_Y').checked){
   
   
   
            var Eval = document.getElementById('elearning_flag_Y').value;
   
   
   
        }
   
        if(document.getElementById('elearning_flag_N').checked){
   
   
   
            var Eval = document.getElementById('elearning_flag_N').value;
   
   
   
        }
   
        if(cCode != ''){
   
   
   
            var datastring='centerCode='+cCode+'&eprid='+eprid+'&excd='+excd+'&grp_code='+grp_code+'&mtype='+mtype+'&elearning_flag='+Eval+'&discount_flag='+discount_flag+'&free_paid_flag='+free_paid_flag;
   
   
   
                $.ajax({
   
   
   
                        url:site_url+'Bulk_fee/getFee/',
   
   
   
                        data: datastring,
   
   
   
                        type:'POST',
   
   
   
                        async: false,
   
   
   
                        success: function(data) {
   
   
   
                         if(data)
   
   
   
                        {
   
   
   
                            document.getElementById('fee').value = data ;
   
   
   
                            document.getElementById('html_fee_id').innerHTML =data;
   
   
   
                            //response = true;
   
   
   
                        }
   
   
   
                    }
   
   
   
                });
   
   
   
        }
   
   
   
    }); 
   
   
   
   })
   
   
   
</script>
<script>
   $(document).ready(function() {
   
   
   
       var cCode = $('#selCenterName').val();
   
   
   
       if (cCode != '') {
   
   
   
           document.getElementById('txtCenterCode').value = cCode;
   
   
   
           var examType = document.getElementById('extype').value;
   
   
   
           var examCode = document.getElementById('examcode').value;
   
   
   
           var temp = document.getElementById("selCenterName").selectedIndex;
   
   
   
           selected_month = document.getElementById("selCenterName").options[temp].className;
   
   
   
           if (selected_month == 'ON') {
   
   
   
               if (document.getElementById("optmode1")) {
   
   
   
                   document.getElementById("optmode1").style.display = "block";
   
   
   
                   document.getElementById('optmode').value = 'ON';
   
   
   
               }
   
   
   
   
   
   
   
               if (document.getElementById("optmode2")) {
   
   
   
                   document.getElementById("optmode2").style.display = "none";
   
   
   
               }
   
   
   
   
   
   
   
           } else if (selected_month == 'OF') {
   
   
   
               if (document.getElementById("optmode2")) {
   
   
   
                   document.getElementById("optmode2").style.display = "block";
   
   
   
                   document.getElementById('optmode').value = 'OF';
   
   
   
               }
   
   
   
               if (document.getElementById("optmode1")) {
   
   
   
                   document.getElementById("optmode1").style.display = "none";
   
   
   
               }
   
   
   
           } else {
   
   
   
               if (document.getElementById("optmode1")) {
   
   
   
                   document.getElementById("optmode1").style.display = "none";
   
   
   
               }
   
   
   
               if (document.getElementById("optmode2")) {
   
   
   
                   document.getElementById("optmode2").style.display = "none";
   
   
   
               }
   
   
   
           }
   
   
   
   
   
   
   
       }
   
   
   
       //var dtable = $('.dataTables-example').DataTable();
   
   
   
   
   
   
   
       //$(".DTTT_button_print")).hide();
   
   
   
       /*$('#datepicker,#doj').datepicker({
   
   
   
        autoclose: true
   
   
   
       });*/
   
   
   
   
   
   
   
       $(function() {
   
   
           var examCode_dob = document.getElementById('examcode').value;
           var VarMaxAge = 59;
           if (examCode_dob==996 || examCode_dob==994) {
               VarMaxAge = 69;
           }
   
           $("#dob1").dateDropdowns({
   
   
   
               submitFieldName: 'dob1',
   
   
   
               minAge: 0,
   
   
   
               maxAge: VarMaxAge
   
   
   
           });
   
   
   
           // Set all hidden fields to type text for the demo
   
   
   
           //$('input[type="hidden"]').attr('type', 'text').attr('readonly', 'readonly');
   
   
   
       });
   
   
   
   
   
   
   
       $("#dob1").change(function() {
   
   
   
           var sel_dob = $("#dob1").val();
   
   
   
           if (sel_dob != '') {
   
   
   
               var dob_arr = sel_dob.split('-');
   
   
   
               if (dob_arr.length == 3) {
   
   
   
                   chkage(dob_arr[2], dob_arr[1], dob_arr[0]);
   
   
   
               } else {
   
   
   
                   alert('Select valid date');
   
   
   
               }
   
   
   
           }
   
   
   
       });
   
   
   
   
   
   
   
       $("body").on("contextmenu", function(e) {
   
   
   
           return false;
   
   
   
       });
   
   
   
   
   
   
   
       $('#male').prop("checked", true);
   
   
   
   
   
   
   
       /*$('#eduqual1').show();
   
   
   
       $('#UG').show();
   
   
   
       $('#eduqual').hide();
   
   
   
       $('#edu').hide();*/
   
   
   
   
   
   
   
       var selEducation = $("#education_type").val();
   
   
   
       if (selEducation != '') {
   
   
   
           changedu(selEducation);
   
   
   
       }
   
   });
   
   
   
   
   
   
   
   function editUser(id, roleid, Name, Username, Email) {
   
   
   
       $('#id').val(id);
   
   
   
       $('#roleid').val(roleid);
   
   
   
       $('#name').val(Name);
   
   
   
       $('#username').val(Username);
   
   
   
       $('#emailid').val(Email);
   
   
   
       $('#btnSubmit').val('Update');
   
   
   
       $('#roleid').focus();
   
   
   
       $('#password').removeAttr('required');
   
   
   
       $('#confirmPassword').removeAttr('required');
   
   
   
   
   
   
   
   }
   
   
   
   
   
   
   
   function changedu(dval) {
   
   
   
   
   
   
   
       $("#education_type").val(dval);
   
   
   
       var UGid = document.getElementById('UG');
   
   
   
       var GRid = document.getElementById('GR');
   
   
   
       var PGid = document.getElementById('PG');
   
   
   
       var EDUid = document.getElementById('edu');
   
   
   
   
   
   
   
       if (dval == 'U') {
   
   
   
           $('#eduqual1').attr('required', 'required');
   
   
   
           $('#eduqual2').removeAttr('required');
   
   
   
           $('#eduqual3').removeAttr('required');
   
   
   
           $('#eduqual').removeAttr('required');
   
   
   
   
   
   
   
           if (UGid != null) {
   
   
   
               //   alert('UG');
   
   
   
               document.getElementById('UG').style.display = "block";
   
   
   
           }
   
   
   
           if (GRid != null) {
   
   
   
               document.getElementById('GR').style.display = "none";
   
   
   
           }
   
   
   
           if (PGid != null) {
   
   
   
               document.getElementById('PG').style.display = "none";
   
   
   
           }
   
   
   
           if (EDUid != null) {
   
   
   
               document.getElementById('edu').style.display = "none";
   
   
   
           }
   
   
   
       } else if (dval == 'G') {
   
   
   
           $('#eduqual1').removeAttr('required');;
   
   
   
           $('#eduqual2').attr('required', 'required');
   
   
   
           $('#eduqual3').removeAttr('required');
   
   
   
           $('#eduqual').removeAttr('required');
   
   
   
   
   
   
   
           if (UGid != null) {
   
   
   
               document.getElementById('UG').style.display = "none";
   
   
   
           }
   
   
   
           if (GRid != null) {
   
   
   
               document.getElementById('GR').style.display = "block";
   
   
   
           }
   
   
   
           if (PGid != null) {
   
   
   
               document.getElementById('PG').style.display = "none";
   
   
   
           }
   
   
   
           if (EDUid != null) {
   
   
   
               document.getElementById('edu').style.display = "none";
   
   
   
           }
   
   
   
   
   
   
   
       } else if (dval == 'P') {
   
   
   
           $('#eduqual1').removeAttr('required');;
   
   
   
           $('#eduqual2').removeAttr('required');
   
   
   
           $('#eduqual3').attr('required', 'required');
   
   
   
           $('#eduqual').removeAttr('required');
   
   
   
   
   
   
   
           if (UGid != null) {
   
   
   
               document.getElementById('UG').style.display = "none";
   
   
   
           }
   
   
   
           if (GRid != null) {
   
   
   
               document.getElementById('GR').style.display = "none";
   
   
   
           }
   
   
   
           if (PGid != null) {
   
   
   
               document.getElementById('PG').style.display = "block";
   
   
   
           }
   
   
   
           if (EDUid != null) {
   
   
   
               document.getElementById('edu').style.display = "none";
   
   
   
           }
   
   
   
       }
   
   
   
   }
   
   
   
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
   
   
   
   
   
   
   
   $(function() {
   
   
   
   
   
   
   
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
   
   
   
   
   
   
   
       if (readCookie('member_register_form')) {
   
   
   
           $('#error_id').html('');
   
   
   
           $('#error_id').removeClass("alert alert-danger alert-dismissible");
   
   
   
           createCookie('member_register_form', "", -1);
   
   
   
       }
   
   
   
   
   
   
   
       $('#new_captcha').click(function(event) {
   
   
   
           event.preventDefault();
   
   
   
           $.ajax({
   
   
   
               type: 'POST',
   
   
   
               url: site_url + 'bulk/BulkApplyNM/generatecaptchaajax/',
   
   
   
               success: function(res) {
   
   
   
                   if (res != '') {
   
   
   
                       $('#captcha_img').html(res);
   
   
   
                   }
   
   
   
               }
   
   
   
           });
   
   
   
       });
   
   
   
       //$("#datepicker,#doj").keypress(function(event) {event.preventDefault();});
   
   
   
   
   
   
   
       if ($('#hiddenphoto').val() != '') {
   
   
   
           $('#image_upload_scanphoto_preview').attr('src', $('#hiddenphoto').val());
   
   
   
       }
   
   
   
       if ($('#hiddenscansignature').val() != '') {
   
   
   
           $('#image_upload_sign_preview').attr('src', $('#hiddenscansignature').val());
   
   
   
       }
   
   
   
       if ($('#hiddenidproofphoto').val() != '') {
   
   
   
           $('#image_upload_idproof_preview').attr('src', $('#hiddenidproofphoto').val());
   
   
   
       }
   
   
   
   
   
   
   var selCenterName = $("#selCenterName").val(); 
   if(selCenterName != "")
   {
      valCentre(selCenterName);
   }

   });
   
   

</script>